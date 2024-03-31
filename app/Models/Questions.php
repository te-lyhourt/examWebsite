<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Questions extends Model
{
    public function project()
    {
        return $this->belongsTo(Projects::class);
    }

    public function answers()
    {
        return $this->hasMany(Answers::class);
    }
    protected $fillable = [
        'description',
        'type',
        'projects_id',
        'fileUpload',
        'options',
    ];
}
