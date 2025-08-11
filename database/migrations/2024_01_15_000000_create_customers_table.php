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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('phone', 20);
            $table->text('address');
            $table->string('id_number', 50)->unique();
            $table->enum('id_type', ['ktp', 'sim', 'passport'])->default('ktp');
            $table->date('date_of_birth')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->enum('gender', ['male', 'female']);
            $table->string('occupation')->nullable();
            $table->decimal('monthly_income', 15, 2)->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone', 20)->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['active', 'inactive', 'blocked'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'created_at']);
            $table->index('phone');
            $table->index('id_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign keys dari tabel yang terkait dengan customers
        Schema::table('pawn_transactions', function (Blueprint $table) {
            // Hapus foreign key jika ada
            if (Schema::hasColumn('pawn_transactions', 'customer_id')) {
                $table->dropForeign(['customer_id']);
            }
        });

        Schema::table('customer_documents', function (Blueprint $table) {
            // Hapus foreign key jika ada
            if (Schema::hasColumn('customer_documents', 'customer_id')) {
                $table->dropForeign(['customer_id']);
            }
        });

        // Hapus tabel customers
        Schema::dropIfExists('customers');
    }
};
