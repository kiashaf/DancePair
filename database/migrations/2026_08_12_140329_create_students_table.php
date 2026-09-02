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
        Schema::create('students', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
        
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();
        
            $table->date('birth_date')->nullable();
        
            $table->string('gender')->nullable();
        
            $table->string('city')->nullable();
        
            $table->string('province')->nullable();
        
            $table->string('country')->default('Canada');
        
            $table->text('bio')->nullable();
        
            $table->string('profile_photo')->nullable();
        
            $table->timestamps();
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
