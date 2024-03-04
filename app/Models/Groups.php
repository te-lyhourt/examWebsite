<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Groups extends Model
{
    public function users()
    {
        return $this->belongsToMany(User::class);
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
