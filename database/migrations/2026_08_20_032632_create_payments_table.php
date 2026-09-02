<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {

            $table->id();

            // Booking مربوط به این پرداخت
            $table->foreignId('booking_id')
                ->constrained()
                ->cascadeOnDelete();

            // Student who pays
            $table->foreignId('student_id')
                ->constrained()
                ->cascadeOnDelete();

            // Teacher who receives the lesson payment
            $table->foreignId('teacher_id')
                ->constrained()
                ->cascadeOnDelete();

            // Total amount paid by the student
            $table->decimal('amount', 10, 2);

            // DansePair commission
            $table->decimal('platform_fee', 10, 2)
                ->default(0);

            // Amount belonging to the teacher
            $table->decimal('teacher_amount', 10, 2)
                ->default(0);

            // Currency
            $table->string('currency', 3)
                ->default('CAD');

            // Payment status
            $table->string('status', 30)
                ->default('pending');

            // Payment provider
            // Example: stripe
            $table->string('payment_provider', 50)
                ->nullable();

            // External payment ID
            // Example: Stripe PaymentIntent ID
            $table->string('transaction_id')
                ->nullable()
                ->unique();

            // When payment was successfully completed
            $table->timestamp('paid_at')
                ->nullable();

            // When payment was refunded
            $table->timestamp('refunded_at')
                ->nullable();

            $table->timestamps();

            // One payment record per booking
            $table->unique('booking_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};