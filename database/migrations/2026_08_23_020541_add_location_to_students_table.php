<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {

            if (!Schema::hasColumn('students', 'address')) {
                $table->string('address')
                    ->nullable();
            }

            if (!Schema::hasColumn('students', 'city')) {
                $table->string('city')
                    ->nullable();
            }

            if (!Schema::hasColumn('students', 'province')) {
                $table->string('province')
                    ->nullable();
            }

            if (!Schema::hasColumn('students', 'postal_code')) {
                $table->string('postal_code')
                    ->nullable();
            }

            if (!Schema::hasColumn('students', 'country')) {
                $table->string('country')
                    ->nullable()
                    ->default('Canada');
            }

            if (!Schema::hasColumn('students', 'latitude')) {
                $table->decimal(
                    'latitude',
                    10,
                    7
                )->nullable();
            }

            if (!Schema::hasColumn('students', 'longitude')) {
                $table->decimal(
                    'longitude',
                    10,
                    7
                )->nullable();
            }
        });
    }


    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {

            if (Schema::hasColumn('students', 'address')) {
                $table->dropColumn('address');
            }

            /*
            |--------------------------------------------------------------------------
            | IMPORTANT
            |--------------------------------------------------------------------------
            |
            | city / province / country ممکنه از قبل جزو جدول اصلی بوده باشند.
            | بنابراین در down آنها را حذف نمی‌کنیم.
            |
            */

            if (Schema::hasColumn('students', 'postal_code')) {
                $table->dropColumn('postal_code');
            }

            if (Schema::hasColumn('students', 'latitude')) {
                $table->dropColumn('latitude');
            }

            if (Schema::hasColumn('students', 'longitude')) {
                $table->dropColumn('longitude');
            }
        });
    }
};