<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'booking_id',
        'student_id',
        'teacher_id',

        'promotion_id',
        'promotion_code',

        'amount',
        'original_amount',
        'discount_percent',
        'discount_amount',

        'platform_fee',
        'commission_percentage',
        'teacher_amount',

        'currency',
        'status',
        'payment_provider',
        'transaction_id',
        'paid_at',
        'refunded_at',
        'cancellation_policy_accepted_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'original_amount' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'discount_amount' => 'decimal:2',

        'platform_fee' => 'decimal:2',
        'commission_percentage' => 'decimal:2',
        'teacher_amount' => 'decimal:2',

        'paid_at' => 'datetime',
        'refunded_at' => 'datetime',
        'cancellation_policy_accepted_at' => 'datetime',
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


    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }
}