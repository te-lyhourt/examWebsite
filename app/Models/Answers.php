<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Answers extends Model
{
    public function question()
    {
        return $this->belongsTo(Questions::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    protected $fillable = [
        'questions_id',
        'user_id',
        'answer',
        'fileName',
        'filePath',
        'url',
        'repeatIndex',
    ];
}