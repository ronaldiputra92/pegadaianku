<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pawn_transactions', function (Blueprint $table) {
            $table->decimal('penalty_amount', 15, 2)->default(0)->after('total_amount');
            $table->integer('penalty_days')->default(0)->after('penalty_amount');
            $table->decimal('appraised_value', 15, 2)->default(0)->after('estimated_value');
            $table->decimal('remaining_balance', 15, 2)->default(0)->after('total_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pawn_transactions', function (Blueprint $table) {
            $table->dropColumn(['penalty_amount', 'penalty_days', 'appraised_value', 'remaining_balance']);
        });
    }
};