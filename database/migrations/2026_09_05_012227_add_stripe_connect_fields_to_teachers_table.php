<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {

            $table->string('stripe_account_id')
                ->nullable()
                ->unique();

            $table->boolean('stripe_onboarding_complete')
                ->default(false);

            $table->boolean('stripe_payouts_enabled')
                ->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {

            $table->dropUnique([
                'stripe_account_id'
            ]);

            $table->dropColumn([
                'stripe_account_id',
                'stripe_onboarding_complete',
                'stripe_payouts_enabled',
            ]);
        });
    }
};