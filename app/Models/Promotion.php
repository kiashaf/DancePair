<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Promotion extends Model
{
    protected $fillable = [
        'teacher_id',
        'title',
        'type',
        'package_size',
        'discount_percent',
        'free_sessions_count',
        'starts_at',
        'ends_at',
        'is_active',
        'code',
        'usage_limit',
        'used_count',
    ];

    protected $casts = [
        'package_size' => 'integer',
        'discount_percent' => 'decimal:2',
        'free_sessions_count' => 'integer',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
        
    }


    public function redemptions()
    {
        return $this->hasMany(PromotionRedemption::class);
    }

    public function studentPackages()
{
    return $this->hasMany(StudentPackage::class);
}

}