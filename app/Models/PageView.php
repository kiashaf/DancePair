<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageView extends Model
{
    public $timestamps = false;


    protected $fillable = [

        'user_id',

        'path',

        'route_name',

        'visitor_hash',

        'country_code',

        'country_name',

        'region_name',

        'city',

        'visited_at',
    ];


    protected function casts(): array
    {
        return [

            'visited_at' => 'datetime',
        ];
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class
        );
    }
}