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
        Schema::create('pawn_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code')->unique();
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('officer_id')->constrained('users')->onDelete('cascade');
            $table->string('item_name');
            $table->text('item_description')->nullable();
            $table->string('item_category');
            $table->decimal('item_weight', 8, 2)->nullable();
            $table->decimal('estimated_value', 15, 2);
            $table->decimal('loan_amount', 15, 2);
            $table->decimal('interest_rate', 5, 2)->default(1.25); // 1.25% per bulan
            $table->integer('loan_period_months')->default(4);
            $table->date('start_date');
            $table->date('due_date');
            $table->enum('status', ['active', 'extended', 'paid', 'overdue', 'auction'])->default('active');
            $table->decimal('total_interest', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pawn_transactions');
    }
};