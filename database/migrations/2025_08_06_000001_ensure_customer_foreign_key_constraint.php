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
        // Cek apakah foreign key constraint masih merujuk ke users
        $constraints = DB::select("
            SELECT CONSTRAINT_NAME, REFERENCED_TABLE_NAME
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_NAME = 'pawn_transactions' 
            AND COLUMN_NAME = 'customer_id' 
            AND CONSTRAINT_NAME LIKE '%foreign%'
            AND TABLE_SCHEMA = DATABASE()
        ");
        
        foreach ($constraints as $constraint) {
            if ($constraint->REFERENCED_TABLE_NAME === 'users') {
                // Disable foreign key checks
                DB::statement('SET FOREIGN_KEY_CHECKS=0');
                
                // Drop constraint lama
                DB::statement("ALTER TABLE pawn_transactions DROP FOREIGN KEY {$constraint->CONSTRAINT_NAME}");
                
                // Tambah constraint baru ke customers
                DB::statement("ALTER TABLE pawn_transactions ADD CONSTRAINT pawn_transactions_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE");
                
                // Enable foreign key checks
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
                
                break;
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pawn_transactions', function (Blueprint $table) {
            // Drop current constraint
            $table->dropForeign(['customer_id']);
            
            // Restore original constraint to users table
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};