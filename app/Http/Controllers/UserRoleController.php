<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserRole;
use Validator;
use Auth;

class UserRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userRoles = UserRole::where('id', '>', Auth::user()->user_role_id)->get();
        return response()->json([
            'userRoles' => $userRoles
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
        $input = array_intersect_key($request->input(), UserRole::$updatable);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3',
            'display_name' => 'required|string|min:3',
            'description' => 'required|string|min:3',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $userRole = UserRole::create($input);
        
        return response()->json([
            'userRole' => $userRole
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
            'userRole' => UserRole::findOrFail($id)
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
        $input = array_intersect_key($request->input(), UserRole::$updatable);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3',
            'display_name' => 'required|string|min:3',
            'description' => 'required|string|min:3',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }
        
        $userRole = UserRole::findOrFail($id);
        $userRole->update($input);
        
        return response()->json([
            'userRole' => $userRole
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
        $userRole = UserRole::findOrFail($id);

        if ( $userRole->delete() ) {
            return response()->json([
                'status' => true
            ]);
        }

        return response()->json([
            'status' => false
        ]);
    }
}
