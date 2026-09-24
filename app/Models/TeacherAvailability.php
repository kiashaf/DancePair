<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherAvailability extends Model
{
    protected $fillable = [
        'teacher_id',
        'dance_style_id',
        'teaching_types',
        'available_date',
        'start_time',
        'end_time',
        'active',
    ];

    protected $casts = [
        'teaching_types' => 'array',
        'available_date' => 'date',
        'active' => 'boolean',
    ];

    public function danceStyle()
    {
        return $this->belongsTo(DanceStyle::class);
    }
}