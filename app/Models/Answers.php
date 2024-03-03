<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Answer extends Model
{
    public function user() : HasOne{
        return $this->hasOne(User::class);
    }
    public function task() : HasOne{
        return $this->hasOne(Task::class);
    }
}