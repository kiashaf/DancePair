<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {

            $table->boolean('teaches_in_person')
                ->default(false);

            $table->boolean('teaches_public_place')
                ->default(false);

            $table->boolean('teaches_online')
                ->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {

            $table->dropColumn([
                'teaches_in_person',
                'teaches_public_place',
                'teaches_online',
            ]);
        });
    }
};