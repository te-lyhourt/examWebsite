<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Symfony\Component\Console\Question\Question;

class User extends Authenticatable
{
    use HasApiTokens;


    //relationship user has many project,task and answer
    public function groups()
    {
        return $this->belongsToMany(Groups::class);
    }

    public function project() : HasMany{
        return $this->hasMany(Projects::class);
    }
    public function question() : HasMany{
        return $this->hasMany(Question::class);
    }
    public function answer() : HasMany{
        return $this->hasMany(Answer::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    //decide what can be fill when create user
    protected $fillable = [
        'email',
        'password',
        'role',
        'created_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed'
    ];
}
