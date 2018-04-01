<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermissionGroup extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public static $updatable = [
        'name' => '', 'description' => ''
    ];
}
