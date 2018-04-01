<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
	protected $fillable = ['user_role_id', 'permission_group_id', 'name', 'description', 'type', 'is_blocked'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public static $updatable = [
        'user_role_id' => 1, 'permission_group_id' => '', 'name' => '', 'description' => '', 'type' => '', 'is_blocked' => 0
    ];

	//To get the info of the user role associated by a permission
    public function role()
    {
        return $this->belongsTo('App\UserRole', 'user_role_id');
    }

    //To get the info of the permission gropu associated by a permission
    public function group()
    {
        return $this->belongsTo('App\PermissionGroup', 'permission_group_id');
    }
}
