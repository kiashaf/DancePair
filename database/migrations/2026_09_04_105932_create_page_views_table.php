<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_views', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | LOGGED-IN USER
            |--------------------------------------------------------------------------
            |
            | null = guest visitor
            |
            */

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | PAGE
            |--------------------------------------------------------------------------
            */

            $table->string('path', 255);

            $table->string('route_name', 150)
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | VISITOR
            |--------------------------------------------------------------------------
            |
            | We will NOT store the raw IP address.
            | Middleware will generate an anonymous SHA-256 visitor hash.
            |
            */

            $table->char('visitor_hash', 64);


            /*
            |--------------------------------------------------------------------------
            | VISIT TIME
            |--------------------------------------------------------------------------
            */

            $table->timestamp('visited_at');


            /*
            |--------------------------------------------------------------------------
            | INDEXES
            |--------------------------------------------------------------------------
            */

            $table->index('visited_at');

            $table->index('path');

            $table->index('route_name');

            $table->index('visitor_hash');

            $table->index([
                'visitor_hash',
                'visited_at',
            ]);
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('page_views');
    }
};