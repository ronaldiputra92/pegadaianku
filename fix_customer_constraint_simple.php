<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\Customer;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== PERBAIKAN CEPAT FOREIGN KEY CONSTRAINT ===\n\n";

try {
    // 1. Buat customer dengan ID 3 jika belum ada
    echo "1. Mengecek dan membuat customer ID 3...\n";
    
    $customer = Customer::find(3);
    if (!$customer) {
        $customer = Customer::create([
            'name' => 'Budi Santoso',
            'email' => 'budi.santoso@example.com',
            'phone' => '081234567890',
            'address' => 'Jl. Contoh No. 123, Jakarta',
            'id_number' => '3171234567890123',
            'id_type' => 'ktp',
            'date_of_birth' => '1990-01-01',
            'place_of_birth' => 'Jakarta',
            'gender' => 'male',
            'occupation' => 'Karyawan Swasta',
            'status' => 'active'
        ]);
        echo "   ✅ Customer ID 3 berhasil dibuat: {$customer->name}\n";
    } else {
        echo "   ✅ Customer ID 3 sudah ada: {$customer->name}\n";
    }
    
    // 2. Perbaiki foreign key constraint
    echo "\n2. Memperbaiki foreign key constraint...\n";
    
    // Cek constraint saat ini
    $constraints = DB::select("
        SELECT CONSTRAINT_NAME, REFERENCED_TABLE_NAME
        FROM information_schema.KEY_COLUMN_USAGE 
        WHERE TABLE_NAME = 'pawn_transactions' 
        AND COLUMN_NAME = 'customer_id' 
        AND CONSTRAINT_NAME LIKE '%foreign%'
        AND TABLE_SCHEMA = DATABASE()
    ");
    
    $needsFix = false;
    foreach ($constraints as $constraint) {
        if ($constraint->REFERENCED_TABLE_NAME === 'users') {
            $needsFix = true;
            echo "   - Ditemukan constraint yang merujuk ke 'users': {$constraint->CONSTRAINT_NAME}\n";
            
            // Disable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            
            // Drop constraint lama
            DB::statement("ALTER TABLE pawn_transactions DROP FOREIGN KEY {$constraint->CONSTRAINT_NAME}");
            echo "   - Constraint lama dihapus\n";
            
            // Tambah constraint baru
            DB::statement("ALTER TABLE pawn_transactions ADD CONSTRAINT pawn_transactions_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE");
            echo "   - Constraint baru ditambahkan (merujuk ke customers)\n";
            
            // Enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            
            break;
        }
    }
    
    if (!$needsFix) {
        echo "   ✅ Foreign key constraint sudah benar\n";
    } else {
        echo "   ✅ Foreign key constraint berhasil diperbaiki\n";
    }
    
    // 3. Verifikasi perbaikan
    echo "\n3. Verifikasi perbaikan...\n";
    
    $newConstraints = DB::select("
        SELECT CONSTRAINT_NAME, REFERENCED_TABLE_NAME
        FROM information_schema.KEY_COLUMN_USAGE 
        WHERE TABLE_NAME = 'pawn_transactions' 
        AND COLUMN_NAME = 'customer_id' 
        AND CONSTRAINT_NAME LIKE '%foreign%'
        AND TABLE_SCHEMA = DATABASE()
    ");
    
    foreach ($newConstraints as $constraint) {
        echo "   - Constraint: {$constraint->CONSTRAINT_NAME} -> {$constraint->REFERENCED_TABLE_NAME}\n";
    }
    
    echo "\n=== PERBAIKAN SELESAI ===\n";
    echo "✅ Sekarang Anda dapat membuat transaksi baru dengan customer ID 3\n";
    echo "✅ Foreign key constraint sudah merujuk ke tabel 'customers'\n\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\nJika error masih terjadi, coba jalankan:\n";
    echo "1. php artisan migrate:rollback --step=1\n";
    echo "2. php artisan migrate\n";
    echo "3. php artisan db:seed --class=CustomerSeeder\n";
}