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
        Schema::create('pawn_extensions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('pawn_transactions')->onDelete('cascade');
            $table->foreignId('officer_id')->constrained('users')->onDelete('cascade');
            $table->string('extension_code')->unique();
            $table->date('original_due_date');
            $table->date('new_due_date');
            $table->integer('extension_months');
            $table->decimal('interest_amount', 15, 2)->default(0);
            $table->decimal('penalty_amount', 15, 2)->default(0);
            $table->decimal('admin_fee', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->string('receipt_number')->nullable();
            $table->boolean('receipt_printed')->default(false);
            $table->timestamp('receipt_printed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pawn_extensions');
    }
};