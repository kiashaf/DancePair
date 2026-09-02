<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'platform_message_recipients',
            function (Blueprint $table) {

                $table->id();


                /*
                |--------------------------------------------------------------------------
                | MESSAGE
                |--------------------------------------------------------------------------
                */

                $table
                    ->foreignId('platform_message_id')
                    ->constrained('platform_messages')
                    ->cascadeOnDelete();


                /*
                |--------------------------------------------------------------------------
                | RECIPIENT USER
                |--------------------------------------------------------------------------
                */

                $table
                    ->foreignId('user_id')
                    ->constrained('users')
                    ->cascadeOnDelete();


                /*
                |--------------------------------------------------------------------------
                | MESSAGE STATE
                |--------------------------------------------------------------------------
                |
                | seen_at:
                | Popup برای User نمایش داده شده
                |
                | read_at:
                | User پیام را خوانده / باز کرده
                |
                | dismissed_at:
                | User پنجره پیام را بسته
                |
                */

                $table
                    ->timestamp('seen_at')
                    ->nullable();

                $table
                    ->timestamp('read_at')
                    ->nullable();

                $table
                    ->timestamp('dismissed_at')
                    ->nullable();


                /*
                |--------------------------------------------------------------------------
                | DISPLAY TRACKING
                |--------------------------------------------------------------------------
                */

                $table
                    ->timestamp('last_shown_at')
                    ->nullable();

                $table
                    ->unsignedInteger('show_count')
                    ->default(0);


                /*
                |--------------------------------------------------------------------------
                | TIMESTAMPS
                |--------------------------------------------------------------------------
                */

                $table->timestamps();


                /*
                |--------------------------------------------------------------------------
                | PREVENT DUPLICATE RECIPIENT
                |--------------------------------------------------------------------------
                */

                $table->unique([
                    'platform_message_id',
                    'user_id',
                ]);


                /*
                |--------------------------------------------------------------------------
                | INDEXES
                |--------------------------------------------------------------------------
                */

                $table->index([
                    'user_id',
                    'read_at',
                ]);

                $table->index([
                    'user_id',
                    'seen_at',
                ]);
            }
        );
    }


    public function down(): void
    {
        Schema::dropIfExists(
            'platform_message_recipients'
        );
    }
};