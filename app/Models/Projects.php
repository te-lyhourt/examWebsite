<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Projects extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    //decide what can be fill when create user
    protected $fillable = [
        'name',
        'created_by',
    ];

}
