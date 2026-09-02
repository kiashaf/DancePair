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
        Schema::table('teacher_availabilities', function (Blueprint $table) {
    
            if (!Schema::hasColumn('teacher_availabilities', 'dance_style_id')) {
                $table->foreignId('dance_style_id')
                    ->nullable()
                    ->after('teacher_id')
                    ->constrained('dance_styles')
                    ->nullOnDelete();
            }
    
            if (Schema::hasColumn('teacher_availabilities', 'day_of_week')) {
                $table->dropColumn('day_of_week');
            }
    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teacher_availabilities', function (Blueprint $table) {
    
            if (Schema::hasColumn('teacher_availabilities', 'dance_style_id')) {
                $table->dropForeign(['dance_style_id']);
                $table->dropColumn('dance_style_id');
            }
    
            if (!Schema::hasColumn('teacher_availabilities', 'day_of_week')) {
                $table->unsignedTinyInteger('day_of_week')->nullable();
            }
    
        });
    }
};
