<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('page_views', function (Blueprint $table) {

            $table->string('country_code', 2)
                ->nullable();

            $table->string('country_name', 100)
                ->nullable();

            $table->string('region_name', 120)
                ->nullable();

            $table->string('city', 120)
                ->nullable();
        });
    }


    public function down(): void
    {
        Schema::table('page_views', function (Blueprint $table) {

            $table->dropColumn([
                'country_code',
                'country_name',
                'region_name',
                'city',
            ]);
        });
    }
};