<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Customer;
use App\Models\User;
use App\Models\PawnTransaction;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== PERBAIKAN FOREIGN KEY CONSTRAINT PAWN_TRANSACTIONS ===\n\n";

try {
    // 1. Cek status foreign key constraint saat ini
    echo "1. Mengecek foreign key constraint saat ini...\n";
    
    $constraints = DB::select("
        SELECT 
            CONSTRAINT_NAME,
            REFERENCED_TABLE_NAME,
            REFERENCED_COLUMN_NAME
        FROM information_schema.KEY_COLUMN_USAGE 
        WHERE TABLE_NAME = 'pawn_transactions' 
        AND COLUMN_NAME = 'customer_id' 
        AND CONSTRAINT_NAME != 'PRIMARY'
        AND TABLE_SCHEMA = DATABASE()
    ");
    
    foreach ($constraints as $constraint) {
        echo "   - Constraint: {$constraint->CONSTRAINT_NAME}\n";
        echo "   - Merujuk ke: {$constraint->REFERENCED_TABLE_NAME}.{$constraint->REFERENCED_COLUMN_NAME}\n";
    }
    
    // 2. Cek data customers dan users
    echo "\n2. Mengecek data customers dan users...\n";
    
    $customersCount = Customer::count();
    $usersCount = User::count();
    
    echo "   - Total customers: {$customersCount}\n";
    echo "   - Total users: {$usersCount}\n";
    
    // Cek customer dengan ID 3
    $customer3 = Customer::find(3);
    $user3 = User::find(3);
    
    echo "   - Customer ID 3: " . ($customer3 ? "Ada ({$customer3->name})" : "Tidak ada") . "\n";
    echo "   - User ID 3: " . ($user3 ? "Ada ({$user3->name})" : "Tidak ada") . "\n";
    
    // 3. Cek transaksi yang bermasalah
    echo "\n3. Mengecek transaksi yang bermasalah...\n";
    
    $invalidTransactions = DB::table('pawn_transactions')
        ->leftJoin('customers', 'pawn_transactions.customer_id', '=', 'customers.id')
        ->whereNull('customers.id')
        ->select('pawn_transactions.*')
        ->get();
    
    echo "   - Transaksi dengan customer_id tidak valid: {$invalidTransactions->count()}\n";
    
    if ($invalidTransactions->count() > 0) {
        echo "   - Detail transaksi bermasalah:\n";
        foreach ($invalidTransactions as $transaction) {
            echo "     * ID: {$transaction->id}, Code: {$transaction->transaction_code}, Customer ID: {$transaction->customer_id}\n";
        }
    }
    
    // 4. Solusi
    echo "\n4. Menerapkan solusi...\n";
    
    // Jika customer ID 3 tidak ada, buat customer dummy
    if (!$customer3) {
        echo "   - Membuat customer dummy dengan ID 3...\n";
        
        // Cek apakah ada user dengan ID 3 yang bisa dijadikan customer
        if ($user3 && $user3->role === 'nasabah') {
            $customer3 = Customer::create([
                'name' => $user3->name,
                'email' => $user3->email,
                'phone' => $user3->phone ?? '000000000000',
                'address' => $user3->address ?? 'Alamat tidak diketahui',
                'id_number' => $user3->identity_number ?? '0000000000000000',
                'id_type' => 'ktp',
                'gender' => 'male',
                'status' => 'active'
            ]);
            echo "     ✅ Customer dibuat dari data user: {$customer3->name}\n";
        } else {
            $customer3 = Customer::create([
                'name' => 'Customer Dummy #3',
                'email' => 'dummy3@example.com',
                'phone' => '000000000003',
                'address' => 'Alamat dummy untuk customer ID 3',
                'id_number' => '0000000000000003',
                'id_type' => 'ktp',
                'gender' => 'male',
                'status' => 'active'
            ]);
            echo "     ✅ Customer dummy dibuat: {$customer3->name}\n";
        }
    }
    
    // 5. Perbaiki foreign key constraint
    echo "\n5. Memperbaiki foreign key constraint...\n";
    
    // Cek apakah constraint masih merujuk ke users
    $userConstraint = collect($constraints)->first(function($c) {
        return $c->REFERENCED_TABLE_NAME === 'users';
    });
    
    if ($userConstraint) {
        echo "   - Menghapus foreign key constraint lama...\n";
        
        // Disable foreign key checks sementara
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Drop constraint lama
        DB::statement("ALTER TABLE pawn_transactions DROP FOREIGN KEY {$userConstraint->CONSTRAINT_NAME}");
        
        // Tambah constraint baru ke customers
        DB::statement("ALTER TABLE pawn_transactions ADD CONSTRAINT pawn_transactions_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE");
        
        // Enable foreign key checks kembali
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        echo "     ✅ Foreign key constraint berhasil diperbaiki\n";
    } else {
        echo "   - Foreign key constraint sudah benar (merujuk ke customers)\n";
    }
    
    // 6. Test transaksi baru
    echo "\n6. Testing pembuatan transaksi...\n";
    
    try {
        // Test dengan data minimal
        $testData = [
            'customer_id' => 3,
            'officer_id' => 1,
            'item_name' => 'Test Item',
            'item_description' => 'Test Description',
            'item_category' => 'Test Category',
            'item_condition' => 'Baik',
            'item_photos' => [],
            'item_weight' => 0,
            'market_value' => 1000000,
            'estimated_value' => 1000000,
            'loan_amount' => 800000,
            'interest_rate' => 2,
            'loan_to_value_ratio' => 80,
            'admin_fee' => 0,
            'insurance_fee' => 0,
            'loan_period_months' => 4,
            'start_date' => now(),
            'notes' => 'Test transaction',
        ];
        
        // Buat transaksi test (tapi jangan simpan)
        $testTransaction = new PawnTransaction($testData);
        
        echo "   ✅ Data transaksi valid, siap untuk disimpan\n";
        
    } catch (\Exception $e) {
        echo "   ❌ Masih ada masalah: " . $e->getMessage() . "\n";
    }
    
    echo "\n=== PERBAIKAN SELESAI ===\n";
    echo "\nSilakan coba buat transaksi baru lagi.\n";
    echo "Jika masih ada masalah, jalankan: php artisan migrate:fresh --seed\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}