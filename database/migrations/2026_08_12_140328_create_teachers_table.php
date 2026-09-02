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
       Schema::create('teachers', function (Blueprint $table) {
        $table->engine = 'InnoDB';
    $table->id();

    $table->foreignId('user_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->text('bio')->nullable();

    $table->unsignedInteger('experience_years')->default(0);

    $table->decimal('hourly_rate', 8, 2)->nullable();

    $table->string('currency', 3)->default('CAD');

    $table->string('city')->nullable();

    $table->string('province')->nullable();

    $table->string('country')->default('Canada');

    $table->string('profile_photo')->nullable();

    $table->string('cover_photo')->nullable();

    $table->string('intro_video')->nullable();

    $table->boolean('verified')->default(false);

    $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
