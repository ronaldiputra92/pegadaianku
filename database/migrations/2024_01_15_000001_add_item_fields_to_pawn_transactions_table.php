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
            // Item condition and photos
            $table->string('item_condition')->nullable()->after('item_weight'); // Baik, Rusak, dll
            $table->json('item_photos')->nullable()->after('item_condition'); // Array foto barang
            
            // Item appraisal details
            $table->decimal('market_value', 15, 2)->nullable()->after('estimated_value'); // Nilai pasar
            $table->decimal('appraisal_value', 15, 2)->nullable()->after('market_value'); // Nilai taksir petugas
            $table->text('appraisal_notes')->nullable()->after('appraisal_value'); // Catatan penilaian
            $table->timestamp('appraised_at')->nullable()->after('appraisal_notes'); // Waktu penilaian
            $table->unsignedBigInteger('appraiser_id')->nullable()->after('appraised_at'); // ID penilai
            
            // Loan calculation details
            $table->decimal('loan_to_value_ratio', 5, 2)->default(80.00)->after('interest_rate'); // LTV ratio (%)
            $table->decimal('admin_fee', 15, 2)->default(0)->after('loan_to_value_ratio'); // Biaya admin
            $table->decimal('insurance_fee', 15, 2)->default(0)->after('admin_fee'); // Biaya asuransi
            
            // Digital signature
            $table->text('customer_signature')->nullable()->after('notes'); // Base64 tanda tangan customer
            $table->text('officer_signature')->nullable()->after('customer_signature'); // Base64 tanda tangan petugas
            $table->timestamp('signed_at')->nullable()->after('officer_signature'); // Waktu tanda tangan
            
            // Receipt printing
            $table->boolean('receipt_printed')->default(false)->after('signed_at'); // Status cetak bukti
            $table->timestamp('receipt_printed_at')->nullable()->after('receipt_printed'); // Waktu cetak
            $table->string('receipt_number')->nullable()->after('receipt_printed_at'); // Nomor bukti
            
            // Add foreign key for appraiser
            $table->foreign('appraiser_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pawn_transactions', function (Blueprint $table) {
            $table->dropForeign(['appraiser_id']);
            $table->dropColumn([
                'item_condition',
                'item_photos',
                'market_value',
                'appraisal_value',
                'appraisal_notes',
                'appraised_at',
                'appraiser_id',
                'loan_to_value_ratio',
                'admin_fee',
                'insurance_fee',
                'customer_signature',
                'officer_signature',
                'signed_at',
                'receipt_printed',
                'receipt_printed_at',
                'receipt_number'
            ]);
        });
    }
};