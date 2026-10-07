<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_packages', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('teacher_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('promotion_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | PACKAGE SNAPSHOT
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('package_size');

            $table->unsignedInteger('sessions_used')
                ->default(0);

            $table->unsignedInteger('sessions_remaining');

            $table->decimal('session_price', 10, 2);

            $table->decimal('original_amount', 10, 2);

            $table->decimal('discount_percent', 5, 2)
                ->nullable();

            $table->decimal('discount_amount', 10, 2)
                ->default(0);

            $table->decimal('final_amount', 10, 2);

            $table->string('currency', 3)
                ->default('CAD');


            /*
            |--------------------------------------------------------------------------
            | PAYMENT
            |--------------------------------------------------------------------------
            */

            $table->string('payment_provider')
                ->nullable();

            $table->string('transaction_id')
                ->nullable()
                ->unique();

            $table->string('stripe_checkout_session_id')
                ->nullable()
                ->unique();


            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [
                'pending',
                'active',
                'completed',
                'cancelled',
                'refunded',
            ])->default('pending');

            $table->timestamp('purchased_at')
                ->nullable();

            $table->timestamp('expires_at')
                ->nullable();

            $table->timestamps();

            $table->index([
                'student_id',
                'teacher_id',
                'status',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_packages');
    }
};