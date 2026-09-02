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
        Schema::create('dance_styles', function (Blueprint $table) {

            $table->engine = 'InnoDB';
        
            $table->id();
        
            $table->string('name');
        
            $table->string('slug')->unique();
        
            $table->text('description')->nullable();
        
            $table->string('image')->nullable();
        
            $table->boolean('active')->default(true);
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dance_styles');
    }
};
