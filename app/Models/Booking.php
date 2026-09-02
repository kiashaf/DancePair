<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'student_id',
        'teacher_id',
        'dance_style_id',
        'lesson_date',
        'lesson_time',
        'duration',
        'price',
        'status',
        'paid',
    ];

    protected $casts = [
        'lesson_date' => 'date',
        'price' => 'decimal:2',
        'paid' => 'boolean',
    ];


    /*
    |--------------------------------------------------------------------------
    | STUDENT
    |--------------------------------------------------------------------------
    */

    public function student()
    {
        return $this->belongsTo(Student::class);
    }


    /*
    |--------------------------------------------------------------------------
    | TEACHER
    |--------------------------------------------------------------------------
    */

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }


    /*
    |--------------------------------------------------------------------------
    | DANCE STYLE
    |--------------------------------------------------------------------------
    */

    public function danceStyle()
    {
        return $this->belongsTo(DanceStyle::class);
    }


    /*
    |--------------------------------------------------------------------------
    | REVIEWS
    |--------------------------------------------------------------------------
    */

    public function review()
    {
        return $this->hasOne(Review::class);
    }


    public function reviews()
    {
        return $this->hasMany(Review::class);
    }


    public function studentReview()
    {
        return $this->hasOne(Review::class)
            ->where('reviewer_type', 'student');
    }


    public function teacherReview()
    {
        return $this->hasOne(Review::class)
            ->where('reviewer_type', 'teacher');
    }


    /*
    |--------------------------------------------------------------------------
    | PAYMENT
    |--------------------------------------------------------------------------
    */

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function messages()
{
    return $this->hasMany(BookingMessage::class)
        ->orderBy('created_at', 'asc');
}
}