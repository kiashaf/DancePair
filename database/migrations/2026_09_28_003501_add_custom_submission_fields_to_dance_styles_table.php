<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dance_styles', function (Blueprint $table) {

            $table->boolean('pending')
                ->default(false)
                ->after('active');

            $table->foreignId('submitted_by_teacher_id')
                ->nullable()
                ->after('pending')
                ->constrained('teachers')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('dance_styles', function (Blueprint $table) {

            $table->dropForeign([
                'submitted_by_teacher_id'
            ]);

            $table->dropColumn([
                'pending',
                'submitted_by_teacher_id',
            ]);
        });
    }
};