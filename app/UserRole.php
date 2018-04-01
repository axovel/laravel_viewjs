<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
	protected $fillable = ['name', 'display_name', 'description', 'menu'];

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    public static $updatable = ['name' => '', 'display_name' => '', 'description' => '', 'menu' => ''];
	
    //To get the list of the user associated by a user role
    public function user()
    {
    	return $this->hasMany('App\User');
    }

    //To get the list of the permission associated by a user role
    public function permission()
    {
        return $this->hasMany('App\Permission');
    }
}
