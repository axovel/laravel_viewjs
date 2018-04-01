<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\PermissionGroup;

class PermissionGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissionGroups = PermissionGroup::all();
        return response()->json([
            'permissionGroups' => $permissionGroups
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
        $input = array_intersect_key($request->input(), PermissionGroup::$updatable);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3',
            'description' => 'required|string|min:3',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $permissionGroup = PermissionGroup::create($input);
        
        return response()->json([
            'permissionGroup' => $permissionGroup
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
            'permissionGroup' => PermissionGroup::findOrFail($id)
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
        $input = array_intersect_key($request->input(), PermissionGroup::$updatable);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3',
            'description' => 'required|string|min:3',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }
        
        if(!isset($input['user_role_id'])) {
            $input['user_role_id'] = 1;
        }
        $permissionGroup = PermissionGroup::findOrFail($id);
        $permissionGroup->update($input);
        
        return response()->json([
            'permissionGroup' => $permissionGroup
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
        $permissionGroup = PermissionGroup::findOrFail($id);

        if( $permissionGroup->delete() ) {
            return response()->json([
                'status' => true
            ]);
        }

        return response()->json([
            'status' => false
        ]);
    }
}
