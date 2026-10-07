<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('promotions', function (Blueprint $table) {
            $table->string('code')
                ->nullable()
                ->unique()
                ->after('type');

            $table->unsignedInteger('usage_limit')
                ->nullable()
                ->after('discount_percent');

            $table->unsignedInteger('used_count')
                ->default(0)
                ->after('usage_limit');
        });

        DB::statement("
            ALTER TABLE promotions
            MODIFY COLUMN type ENUM(
                'free_session',
                'package_discount',
                'discount_code'
            ) NOT NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE promotions
            MODIFY COLUMN type ENUM(
                'free_session',
                'package_discount'
            ) NOT NULL
        ");

        Schema::table('promotions', function (Blueprint $table) {
            $table->dropUnique(['code']);

            $table->dropColumn([
                'code',
                'usage_limit',
                'used_count',
            ]);
        });
    }
};