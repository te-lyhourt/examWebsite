<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Projects extends Model
{
    public function groups()
    {
        return $this->belongsToMany(Groups::class)->withTimestamps()->as('group_project')->withPivot('added_by');
    }
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
