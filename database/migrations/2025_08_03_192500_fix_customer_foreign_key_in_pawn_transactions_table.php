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
            // Drop the existing foreign key constraint
            $table->dropForeign(['customer_id']);
            
            // Add new foreign key constraint pointing to customers table
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pawn_transactions', function (Blueprint $table) {
            // Drop the current foreign key constraint
            $table->dropForeign(['customer_id']);
            
            // Restore the original foreign key constraint pointing to users table
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};