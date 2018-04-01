<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Permission;
use Validator;
use Auth;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = Permission::where('user_role_id', '>', Auth::user()->id)->get();
        return response()->json([
            'permissions' => $permissions
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
        $input = array_intersect_key($request->input(), Permission::$updatable);

        $validator = Validator::make($request->all(), [
            'user_role_id' => 'required',
            'permission_group_id' => 'required',
            'name' => 'required|string|min:3',
            'description' => 'required|string|min:3',
            'is_blocked' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $permission = Permission::create($input);
        
        return response()->json([
            'permission' => $permission
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
            'permission' => Permission::findOrFail($id)
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
        $input = array_intersect_key($request->input(), Permission::$updatable);

        $validator = Validator::make($request->all(), [
            'user_role_id' => 'required',
            'permission_group_id' => 'required',
            'name' => 'required|string|min:3',
            'description' => 'required|string|min:3',
            'is_blocked' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }
        
        $permission = Permission::findOrFail($id);
        $permission->update($input);
        
        return response()->json([
            'permission' => $permission
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
        $permission = Permission::findOrFail($id);

        if ( $permission->delete() ) {
            return response()->json([
                'status' => true
            ]);
        }

        return response()->json([
            'status' => false
        ]);
    }
}
