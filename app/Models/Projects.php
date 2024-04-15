<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Projects extends Model
{
    public function groups()
    {
        //belongsToMany return group related to $this (Projects)
        //withTimestamps manage created by and updata by
        //as change pivot varaible name to group_project
        //withPivot get column added_by and added to group_project
        return $this->belongsToMany(Groups::class)->withTimestamps()->as('group_project')->withPivot('added_by');
    }
    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps()->as('group_user')->withPivot('added_by');
    }
    public function questions()
    {
        return $this->hasMany(Questions::class);
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    //decide what can be fill when create user
    protected $fillable = [
        'name',
        'description',
        'questNum',
        'repeatNum',
        'created_by',
        'admin',
        'url',
    ];

}
