<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('platform_messages', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | CONTENT - EN / FR
            |--------------------------------------------------------------------------
            */

            $table->string('title_en');
            $table->string('title_fr');

            $table->text('message_en');
            $table->text('message_fr');


            /*
            |--------------------------------------------------------------------------
            | IMPORTANCE
            |--------------------------------------------------------------------------
            |
            | normal
            | important
            | critical
            |
            */

            $table
                ->string('importance', 20)
                ->default('normal');


            /*
            |--------------------------------------------------------------------------
            | AUDIENCE
            |--------------------------------------------------------------------------
            |
            | all_users
            | all_students
            | all_teachers
            | selected_users
            | single_user
            |
            */

            $table
                ->string('audience_type', 30)
                ->default('selected_users');


            /*
            |--------------------------------------------------------------------------
            | ACTIVE PERIOD
            |--------------------------------------------------------------------------
            */

            $table
                ->timestamp('starts_at')
                ->nullable();

            $table
                ->timestamp('ends_at')
                ->nullable();

            $table
                ->boolean('is_active')
                ->default(true);


            /*
            |--------------------------------------------------------------------------
            | CREATED BY ADMIN
            |--------------------------------------------------------------------------
            */

            $table
                ->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | TIMESTAMPS
            |--------------------------------------------------------------------------
            */

            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | INDEXES
            |--------------------------------------------------------------------------
            */

            $table->index('importance');
            $table->index('audience_type');
            $table->index('is_active');
            $table->index('starts_at');
            $table->index('ends_at');
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('platform_messages');
    }
};