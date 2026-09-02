<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('provinces', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();

            $table->foreignId('country_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name', 100);
            $table->string('code', 10);

            $table->timestamps();

            $table->unique(['country_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('provinces');
    }
};