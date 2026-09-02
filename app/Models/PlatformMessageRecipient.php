<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlatformMessageRecipient extends Model
{
    /*
    |--------------------------------------------------------------------------
    | FILLABLE
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'platform_message_id',

        'user_id',

        'seen_at',

        'read_at',

        'dismissed_at',

        'last_shown_at',

        'show_count',
    ];


    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        'seen_at' =>
            'datetime',

        'read_at' =>
            'datetime',

        'dismissed_at' =>
            'datetime',

        'last_shown_at' =>
            'datetime',

        'show_count' =>
            'integer',
    ];


    /*
    |--------------------------------------------------------------------------
    | MESSAGE
    |--------------------------------------------------------------------------
    */

    public function message(): BelongsTo
    {
        return $this->belongsTo(
            PlatformMessage::class,
            'platform_message_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | USER
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UNREAD
    |--------------------------------------------------------------------------
    */

    public function isUnread(): bool
    {
        return
            $this->read_at === null;
    }


    /*
    |--------------------------------------------------------------------------
    | SEEN
    |--------------------------------------------------------------------------
    */

    public function isSeen(): bool
    {
        return
            $this->seen_at !== null;
    }


    /*
    |--------------------------------------------------------------------------
    | DISMISSED
    |--------------------------------------------------------------------------
    */

    public function isDismissed(): bool
    {
        return
            $this->dismissed_at !== null;
    }
}