<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commission_histories', function (Blueprint $table) {

            $table->id();

            $table->decimal(
                'old_percentage',
                5,
                2
            )->nullable();

            $table->decimal(
                'new_percentage',
                5,
                2
            );

            $table->foreignId(
                'changed_by'
            )
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'commission_histories'
        );
    }
};