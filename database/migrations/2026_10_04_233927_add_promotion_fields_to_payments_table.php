<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {

            $table->foreignId('promotion_id')
                ->nullable()
                ->after('booking_id')
                ->constrained()
                ->nullOnDelete();

            $table->decimal('original_amount', 10, 2)
                ->nullable()
                ->after('amount');

            $table->decimal('discount_percent', 5, 2)
                ->nullable()
                ->after('original_amount');

            $table->decimal('discount_amount', 10, 2)
                ->default(0)
                ->after('discount_percent');

            $table->string('promotion_code')
                ->nullable()
                ->after('discount_amount');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {

            $table->dropForeign([
                'promotion_id'
            ]);

            $table->dropColumn([
                'promotion_id',
                'original_amount',
                'discount_percent',
                'discount_amount',
                'promotion_code',
            ]);
        });
    }
};