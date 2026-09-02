<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommissionHistory extends Model
{
    protected $fillable = [
        'old_percentage',
        'new_percentage',
        'changed_by',
    ];

    protected $casts = [
        'old_percentage' => 'decimal:2',
        'new_percentage' => 'decimal:2',
    ];


    /*
    |--------------------------------------------------------------------------
    | ADMIN / USER WHO CHANGED THE COMMISSION
    |--------------------------------------------------------------------------
    */

    public function changedBy()
    {
        return $this->belongsTo(
            User::class,
            'changed_by'
        );
    }
}