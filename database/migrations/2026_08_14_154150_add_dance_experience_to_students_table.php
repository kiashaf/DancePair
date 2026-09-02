<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {

            $table->string('experience_level')
                ->nullable()
                ->after('bio');

            $table->boolean('has_dance_experience')
                ->default(false)
                ->after('experience_level');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'experience_level',
                'has_dance_experience'
            ]);
        });
    }
};