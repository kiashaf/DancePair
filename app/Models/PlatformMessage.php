<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlatformMessage extends Model
{
    /*
    |--------------------------------------------------------------------------
    | FILLABLE
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'title_en',
        'title_fr',

        'message_en',
        'message_fr',

        'importance',

        'audience_type',

        'starts_at',
        'ends_at',

        'is_active',

        'created_by',
    ];


    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        'starts_at' =>
            'datetime',

        'ends_at' =>
            'datetime',

        'is_active' =>
            'boolean',
    ];


    /*
    |--------------------------------------------------------------------------
    | CREATOR
    |--------------------------------------------------------------------------
    */

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | RECIPIENTS
    |--------------------------------------------------------------------------
    */

    public function recipients(): HasMany
    {
        return $this->hasMany(
            PlatformMessageRecipient::class,
            'platform_message_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ACTIVE MESSAGES
    |--------------------------------------------------------------------------
    */

    public function scopeCurrentlyActive(
        Builder $query
    ): Builder {

        return $query

            ->where(
                'is_active',
                true
            )

            ->where(
                function ($query) {

                    $query
                        ->whereNull(
                            'starts_at'
                        )

                        ->orWhere(
                            'starts_at',
                            '<=',
                            now()
                        );
                }
            )

            ->where(
                function ($query) {

                    $query
                        ->whereNull(
                            'ends_at'
                        )

                        ->orWhere(
                            'ends_at',
                            '>=',
                            now()
                        );
                }
            );
    }


    /*
    |--------------------------------------------------------------------------
    | EN / FR TITLE
    |--------------------------------------------------------------------------
    */

    public function titleForLocale(
        ?string $locale = null
    ): string {

        $locale =
            $locale
            ??
            app()->getLocale();


        if ($locale === 'fr') {

            return
                $this->title_fr
                ?:
                $this->title_en;
        }


        return
            $this->title_en
            ?:
            $this->title_fr;
    }


    /*
    |--------------------------------------------------------------------------
    | EN / FR MESSAGE
    |--------------------------------------------------------------------------
    */

    public function messageForLocale(
        ?string $locale = null
    ): string {

        $locale =
            $locale
            ??
            app()->getLocale();


        if ($locale === 'fr') {

            return
                $this->message_fr
                ?:
                $this->message_en;
        }


        return
            $this->message_en
            ?:
            $this->message_fr;
    }


    /*
    |--------------------------------------------------------------------------
    | IMPORTANCE HELPERS
    |--------------------------------------------------------------------------
    */

    public function isNormal(): bool
    {
        return
            $this->importance
            ===
            'normal';
    }


    public function isImportant(): bool
    {
        return
            $this->importance
            ===
            'important';
    }


    public function isCritical(): bool
    {
        return
            $this->importance
            ===
            'critical';
    }
}