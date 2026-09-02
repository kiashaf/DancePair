<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {

            $table->boolean('offers_online')
                ->default(false)
                ->after('bio');

            $table->boolean('offers_face_to_face')
                ->default(false)
                ->after('offers_online');

            $table->boolean('offers_public_place')
                ->default(false)
                ->after('offers_face_to_face');


            /*
            |--------------------------------------------------------------------------
            | SERVICE LOCATION
            |--------------------------------------------------------------------------
            */

            $table->string('service_address')
                ->nullable()
                ->after('offers_public_place');

            $table->string('service_city')
                ->nullable()
                ->after('service_address');

            $table->string('service_province')
                ->nullable()
                ->after('service_city');

            $table->string('service_postal_code')
                ->nullable()
                ->after('service_province');

            $table->string('service_country')
                ->nullable()
                ->default('Canada')
                ->after('service_postal_code');


            /*
            |--------------------------------------------------------------------------
            | COORDINATES
            |--------------------------------------------------------------------------
            */

            $table->decimal(
                'service_latitude',
                10,
                7
            )
                ->nullable()
                ->after('service_country');

            $table->decimal(
                'service_longitude',
                10,
                7
            )
                ->nullable()
                ->after('service_latitude');


            /*
            |--------------------------------------------------------------------------
            | SERVICE RADIUS
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('service_radius_km')
                ->nullable()
                ->after('service_longitude');
        });
    }


    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {

            $table->dropColumn([
                'offers_online',
                'offers_face_to_face',
                'offers_public_place',

                'service_address',
                'service_city',
                'service_province',
                'service_postal_code',
                'service_country',

                'service_latitude',
                'service_longitude',

                'service_radius_km',
            ]);
        });
    }
};