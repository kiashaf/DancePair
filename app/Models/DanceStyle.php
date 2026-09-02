<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DanceStyle extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'active',
    ];

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class);
    }
}