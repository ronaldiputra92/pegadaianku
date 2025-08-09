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
        Schema::table('payments', function (Blueprint $table) {
            // Payment method details
            $table->string('payment_method')->default('cash')->after('payment_type'); // cash, transfer, debit, credit
            $table->string('bank_name')->nullable()->after('payment_method'); // For transfer payments
            $table->string('reference_number')->nullable()->after('bank_name'); // Transfer reference
            
            // Receipt details
            $table->boolean('receipt_printed')->default(false)->after('notes');
            $table->timestamp('receipt_printed_at')->nullable()->after('receipt_printed');
            $table->string('receipt_number')->nullable()->after('receipt_printed_at');
            
            // Additional tracking
            $table->decimal('remaining_balance', 15, 2)->default(0)->after('principal_amount'); // Balance after payment
            $table->boolean('is_final_payment')->default(false)->after('remaining_balance'); // Mark if this completes the loan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method',
                'bank_name',
                'reference_number',
                'receipt_printed',
                'receipt_printed_at',
                'receipt_number',
                'remaining_balance',
                'is_final_payment'
            ]);
        });
    }
};