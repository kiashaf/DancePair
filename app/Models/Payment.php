<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'booking_id',
        'student_id',
        'teacher_id',
        'amount',
        'platform_fee',
        'commission_percentage',
        'teacher_amount',
        'currency',
        'status',
        'payment_provider',
        'transaction_id',
        'paid_at',
        'refunded_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'commission_percentage' => 'decimal:2',
        'teacher_amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'refunded_at' => 'datetime',
    ];


    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }


    public function student()
    {
        return $this->belongsTo(Student::class);
    }


    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}