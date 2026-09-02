<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\DanceStyle;
use App\Models\TeacherAvailability;

class Teacher extends Model
{
    protected $fillable = [
        'user_id',
        'bio',
        'experience_years',
        'hourly_rate',
        'currency',
        'city',
        'province',
        'country',
        'profile_photo',
        'cover_photo',
        'intro_video',
        
    ];

    /*
    |--------------------------------------------------------------------------
    | User
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Dance Styles
    |--------------------------------------------------------------------------
    | Each teacher can set a different hourly rate
    | for every dance style.
    */

    public function danceStyles()
    {
        return $this->belongsToMany(
            DanceStyle::class,
            'dance_style_teacher'
        )
        ->withPivot('hourly_rate')
        ->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | Availabilities
    |--------------------------------------------------------------------------
    */

    public function availabilities()
    {
        return $this->hasMany(TeacherAvailability::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class)
            ->where('reviewer_type', 'student');
    }

}