<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes; 

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_role_id', 'slug', 'fname', 'lname', 'email', 'phone', 'class', 'address', 'city', 'state', 'country', 'pin_code', 'password', 'image', 'is_blocked'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public static $updatable = [
        'user_role_id' => "", 'fname' => "", 'lname' => "", 'email' => "", 'password' => "", 'phone' => "", 'class' => "", 'address' => "", 'city' => "", 'state' => "", 'country' => "", 'pin_code' => "", 'password' => "", 'image' => "", 'is_blocked' => 0
    ];

    //To get the info of the user role associated by a user
    public function role()
    {
        return $this->belongsTo('App\UserRole', 'user_role_id');
    }
}
