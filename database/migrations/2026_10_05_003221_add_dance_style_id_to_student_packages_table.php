<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_packages', function (Blueprint $table) {
            $table->foreignId('dance_style_id')
                ->nullable()
                ->after('promotion_id')
                ->constrained('dance_styles')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('student_packages', function (Blueprint $table) {
            $table->dropForeign(['dance_style_id']);
            $table->dropColumn('dance_style_id');
        });
    }
};