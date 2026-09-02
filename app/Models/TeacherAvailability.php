<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherAvailability extends Model
{
    protected $fillable = [
        'teacher_id',
        'dance_style_id',
        'available_date',
        'start_time',
        'end_time',
        'active',
    ];

    public function danceStyle()
    {
        return $this->belongsTo(DanceStyle::class);
    }
    
}