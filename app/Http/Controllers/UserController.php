<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Validator;
use Auth;

class UserController extends Controller
{
    /**
     * Instantiate a new new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->user = new User();

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('user_role_id', '>', Auth::user()->id)->get();
        return response()->json([
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_role_id' => 'required',
            'fname' => 'required',
            'lname' => 'required',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'sometimes|required|min:5|max:255',
            'cpassword' => 'sometimes|required|min:5|max:255|same:password',
            'phone' => 'required|numeric',
            'class' => 'required|string|min:3',
            'is_blocked' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $input = array_intersect_key($request->input(), User::$updatable);
        if (isset($input['password'])) {
            $input['password'] = bcrypt($input['password']);
        }
        $user = User::create($input);
        
        return response()->json([
            'user' => $user
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json([
            'user' => User::findOrFail($id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = array_intersect_key($request->input(), User::$updatable);

        if (isset($input['password'])) {
            $input['password'] = bcrypt($input['password']);
        }
        
        $user = User::findOrFail($id);
        $user->update($input);
        
        return response()->json([
            'user' => $user
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ( $user->delete() ) {
            return response()->json([
                'status' => true
            ]);
        }

        return response()->json([
            'status' => false
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function students($id)
    {
        if(isset($id) and ($id != "" or $id != 0)) {
            return response()->json([
                'users' => User::where('class', $id)->where('user_role_id', 5)->get()
            ]);
        }
        return response()->json([
            'users' => []
        ]);
    }
}
