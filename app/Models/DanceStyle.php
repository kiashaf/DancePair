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
        'pending',
        'submitted_by_teacher_id',
    ];

    public function teachers()
    {
        return $this->belongsToMany(
            Teacher::class,
            'dance_style_teacher'
        )
        ->withPivot('hourly_rate')
        ->withTimestamps();
    }

    public function submittedByTeacher()
    {
        return $this->belongsTo(
            Teacher::class,
            'submitted_by_teacher_id'
        );
    }
}