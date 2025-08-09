<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\PawnTransaction;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "=== MEMPERBAIKI MASALAH FOREIGN KEY CONSTRAINT ===\n\n";

try {
    // 1. Cek status database saat ini
    echo "1. Mengecek status database...\n";
    
    $customersCount = Customer::count();
    $usersCount = User::count();
    $transactionsCount = PawnTransaction::count();
    
    echo "   - Total customers: {$customersCount}\n";
    echo "   - Total users: {$usersCount}\n";
    echo "   - Total transactions: {$transactionsCount}\n\n";
    
    // 2. Cek transaksi dengan customer_id yang tidak valid
    echo "2. Mengecek transaksi dengan customer_id tidak valid...\n";
    $invalidTransactions = PawnTransaction::whereNotIn('customer_id', function($query) {
        $query->select('id')->from('customers');
    })->get();
    
    echo "   Ditemukan {$invalidTransactions->count()} transaksi dengan customer_id tidak valid\n";
    
    if ($invalidTransactions->count() > 0) {
        echo "   Detail transaksi bermasalah:\n";
        foreach ($invalidTransactions as $transaction) {
            echo "   - ID: {$transaction->id}, Code: {$transaction->transaction_code}, Customer ID: {$transaction->customer_id}\n";
        }
        echo "\n";
    }
    
    // 3. Cek apakah ada users dengan role 'nasabah'
    echo "3. Mengecek users dengan role 'nasabah'...\n";
    $nasabahUsers = User::where('role', 'nasabah')->get();
    echo "   Ditemukan {$nasabahUsers->count()} users dengan role 'nasabah'\n\n";
    
    // 4. Cek foreign key constraint saat ini
    echo "4. Mengecek foreign key constraint...\n";
    $foreignKeys = DB::select("
        SELECT 
            CONSTRAINT_NAME,
            TABLE_NAME,
            COLUMN_NAME,
            REFERENCED_TABLE_NAME,
            REFERENCED_COLUMN_NAME
        FROM information_schema.KEY_COLUMN_USAGE 
        WHERE TABLE_SCHEMA = DATABASE() 
        AND TABLE_NAME = 'pawn_transactions' 
        AND COLUMN_NAME = 'customer_id'
        AND REFERENCED_TABLE_NAME IS NOT NULL
    ");
    
    if (!empty($foreignKeys)) {
        foreach ($foreignKeys as $fk) {
            echo "   - Constraint: {$fk->CONSTRAINT_NAME}\n";
            echo "   - References: {$fk->REFERENCED_TABLE_NAME}.{$fk->REFERENCED_COLUMN_NAME}\n";
        }
    } else {
        echo "   - Tidak ada foreign key constraint ditemukan\n";
    }
    echo "\n";
    
    // 5. Berikan solusi berdasarkan kondisi
    echo "=== SOLUSI YANG TERSEDIA ===\n\n";
    
    if ($invalidTransactions->count() > 0) {
        echo "MASALAH DITEMUKAN: Ada transaksi dengan customer_id yang tidak valid\n\n";
        
        echo "SOLUSI 1: Migrasi data dari users ke customers (RECOMMENDED)\n";
        echo "- Akan memindahkan data users dengan role 'nasabah' ke tabel customers\n";
        echo "- Akan memperbarui customer_id di transaksi yang bermasalah\n";
        echo "- Aman dan mempertahankan data yang ada\n\n";
        
        echo "SOLUSI 2: Buat customer dummy untuk transaksi yang bermasalah\n";
        echo "- Akan membuat customer placeholder untuk transaksi yang tidak memiliki customer valid\n";
        echo "- Lebih cepat tetapi data customer akan kosong\n\n";
        
        echo "SOLUSI 3: Hapus transaksi yang bermasalah (TIDAK RECOMMENDED)\n";
        echo "- Akan menghapus transaksi yang tidak memiliki customer valid\n";
        echo "- Berisiko kehilangan data\n\n";
        
        // Tampilkan kode untuk solusi 1
        if ($nasabahUsers->count() > 0) {
            echo "=== KODE UNTUK SOLUSI 1 (MIGRASI DATA) ===\n";
            echo "Jalankan kode berikut untuk migrasi data:\n\n";
            echo "DB::transaction(function() {\n";
            echo "    // Migrasi users ke customers\n";
            echo "    \$nasabahUsers = User::where('role', 'nasabah')->get();\n";
            echo "    \$userToCustomerMap = [];\n";
            echo "    \n";
            echo "    foreach (\$nasabahUsers as \$user) {\n";
            echo "        \$customer = Customer::create([\n";
            echo "            'name' => \$user->name,\n";
            echo "            'email' => \$user->email,\n";
            echo "            'phone' => \$user->phone ?? '000000000000',\n";
            echo "            'address' => 'Alamat belum diisi',\n";
            echo "            'id_number' => 'ID' . str_pad(\$user->id, 10, '0', STR_PAD_LEFT),\n";
            echo "            'id_type' => 'ktp',\n";
            echo "            'gender' => 'male',\n";
            echo "            'status' => 'active',\n";
            echo "            'created_at' => \$user->created_at,\n";
            echo "            'updated_at' => \$user->updated_at,\n";
            echo "        ]);\n";
            echo "        \n";
            echo "        \$userToCustomerMap[\$user->id] = \$customer->id;\n";
            echo "    }\n";
            echo "    \n";
            echo "    // Update transaksi yang bermasalah\n";
            echo "    \$invalidTransactions = PawnTransaction::whereNotIn('customer_id', function(\$query) {\n";
            echo "        \$query->select('id')->from('customers');\n";
            echo "    })->get();\n";
            echo "    \n";
            echo "    foreach (\$invalidTransactions as \$transaction) {\n";
            echo "        if (isset(\$userToCustomerMap[\$transaction->customer_id])) {\n";
            echo "            \$transaction->update([\n";
            echo "                'customer_id' => \$userToCustomerMap[\$transaction->customer_id]\n";
            echo "            ]);\n";
            echo "        }\n";
            echo "    }\n";
            echo "});\n\n";
        }
        
        // Tampilkan kode untuk solusi 2
        echo "=== KODE UNTUK SOLUSI 2 (CUSTOMER DUMMY) ===\n";
        echo "Jalankan kode berikut untuk membuat customer dummy:\n\n";
        echo "DB::transaction(function() {\n";
        echo "    \$invalidTransactions = PawnTransaction::whereNotIn('customer_id', function(\$query) {\n";
        echo "        \$query->select('id')->from('customers');\n";
        echo "    })->get();\n";
        echo "    \n";
        echo "    \$createdCustomers = [];\n";
        echo "    \n";
        echo "    foreach (\$invalidTransactions as \$transaction) {\n";
        echo "        if (!isset(\$createdCustomers[\$transaction->customer_id])) {\n";
        echo "            \$customer = Customer::create([\n";
        echo "                'name' => 'Customer ID ' . \$transaction->customer_id,\n";
        echo "                'email' => 'customer' . \$transaction->customer_id . '@dummy.com',\n";
        echo "                'phone' => '000000000000',\n";
        echo "                'address' => 'Alamat belum diisi',\n";
        echo "                'id_number' => 'DUMMY' . str_pad(\$transaction->customer_id, 10, '0', STR_PAD_LEFT),\n";
        echo "                'id_type' => 'ktp',\n";
        echo "                'gender' => 'male',\n";
        echo "                'status' => 'active',\n";
        echo "            ]);\n";
        echo "            \n";
        echo "            \$createdCustomers[\$transaction->customer_id] = \$customer->id;\n";
        echo "        }\n";
        echo "        \n";
        echo "        \$transaction->update([\n";
        echo "            'customer_id' => \$createdCustomers[\$transaction->customer_id]\n";
        echo "        ]);\n";
        echo "    }\n";
        echo "});\n\n";
        
    } else {
        echo "✅ TIDAK ADA MASALAH DITEMUKAN\n";
        echo "Semua transaksi memiliki customer_id yang valid.\n";
        echo "Masalah mungkin terjadi saat runtime atau ada data yang baru saja dihapus.\n\n";
        
        echo "SARAN:\n";
        echo "1. Pastikan ada data customer di database sebelum membuat transaksi\n";
        echo "2. Jalankan seeder untuk membuat data customer: php artisan db:seed --class=CustomerSeeder\n";
        echo "3. Periksa apakah migration sudah dijalankan dengan benar\n\n";
    }
    
    // 6. Cek apakah perlu menjalankan migration
    echo "=== CEK MIGRATION STATUS ===\n";
    echo "Pastikan migration berikut sudah dijalankan:\n";
    echo "- 2024_01_15_000000_create_customers_table.php\n";
    echo "- 2025_08_03_192500_fix_customer_foreign_key_in_pawn_transactions_table.php\n\n";
    echo "Jalankan: php artisan migrate:status untuk mengecek status migration\n";
    echo "Jalankan: php artisan migrate untuk menjalankan migration yang belum dijalankan\n\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "=== SELESAI ===\n";
echo "Silakan pilih solusi yang sesuai dan jalankan kode yang diperlukan.\n";
echo "PENTING: Backup database Anda sebelum menjalankan solusi apapun!\n";