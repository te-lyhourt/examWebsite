<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;

class Groups extends Model
{
    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps()->as('group_user')->withPivot('added_by');
    }
    public function projects()
    {
        return $this->belongsToMany(Project::class);
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
