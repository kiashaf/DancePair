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
        Schema::create('bookings', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
        
            // Student
            $table->foreignId('student_id')
                  ->constrained()
                  ->cascadeOnDelete();
        
            // Teacher
            $table->foreignId('teacher_id')
                  ->constrained()
                  ->cascadeOnDelete();
        
            // Dance Style
            $table->foreignId('dance_style_id')
                  ->constrained()
                  ->cascadeOnDelete();
        
            // Date & Time
            $table->date('lesson_date');
        
            $table->time('lesson_time');
        
            $table->integer('duration')->default(60);
        
            // Price
            $table->decimal('price',8,2);
        
            // Status
            $table->enum('status',[
                'pending',
                'confirmed',
                'completed',
                'cancelled'
            ])->default('pending');
        
            // Payment
            $table->boolean('paid')->default(false);
        
            $table->timestamps();
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
