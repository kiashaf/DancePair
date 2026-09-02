<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'birth_date',
        'gender',
        'city',
        'province',
        'country',
        'bio',
        'profile_photo',
        'experience_level',
        'has_dance_experience',
    ];


    protected $casts = [
        'birth_date' => 'date',
        'has_dance_experience' => 'boolean',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
    return $this->hasMany(Review::class)
        ->where('reviewer_type', 'teacher');
    }
}