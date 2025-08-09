<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\PawnTransaction;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "=== MIGRASI USERS KE CUSTOMERS (SOLUSI AMAN) ===\n\n";

try {
    DB::transaction(function() {
        echo "1. Mencari users dengan role 'nasabah'...\n";
        $nasabahUsers = User::where('role', 'nasabah')->get();
        echo "   Ditemukan: {$nasabahUsers->count()} users\n\n";
        
        if ($nasabahUsers->count() == 0) {
            echo "   Tidak ada users dengan role 'nasabah' ditemukan.\n";
            echo "   Akan membuat customer dummy untuk transaksi yang bermasalah...\n\n";
            
            // Buat customer dummy untuk transaksi yang bermasalah
            $invalidTransactions = PawnTransaction::whereNotIn('customer_id', function($query) {
                $query->select('id')->from('customers');
            })->get();
            
            echo "2. Membuat customer dummy untuk {$invalidTransactions->count()} transaksi...\n";
            
            $createdCustomers = [];
            
            foreach ($invalidTransactions as $transaction) {
                if (!isset($createdCustomers[$transaction->customer_id])) {
                    $customer = Customer::create([
                        'name' => 'Customer ID ' . $transaction->customer_id . ' (Migrasi)',
                        'email' => 'customer' . $transaction->customer_id . '@migrated.local',
                        'phone' => '000000000000',
                        'address' => 'Alamat belum diisi - Data migrasi',
                        'id_number' => 'MIG' . str_pad($transaction->customer_id, 13, '0', STR_PAD_LEFT),
                        'id_type' => 'ktp',
                        'gender' => 'male',
                        'status' => 'active',
                        'notes' => 'Customer dibuat otomatis saat migrasi data dari user ID ' . $transaction->customer_id,
                    ]);
                    
                    $createdCustomers[$transaction->customer_id] = $customer->id;
                    echo "   - Dibuat customer baru: ID {$customer->id} untuk transaksi dengan customer_id {$transaction->customer_id}\n";
                }
                
                // Update customer_id di transaksi
                $transaction->update([
                    'customer_id' => $createdCustomers[$transaction->customer_id]
                ]);
                
                echo "   - Updated transaksi {$transaction->transaction_code}: customer_id {$transaction->customer_id} -> {$createdCustomers[$transaction->customer_id]}\n";
            }
            
        } else {
            // Migrasi users ke customers
            echo "2. Migrasi users ke customers...\n";
            $userToCustomerMap = [];
            
            foreach ($nasabahUsers as $user) {
                // Cek apakah customer dengan email ini sudah ada
                $existingCustomer = Customer::where('email', $user->email)->first();
                
                if ($existingCustomer) {
                    $userToCustomerMap[$user->id] = $existingCustomer->id;
                    echo "   - User {$user->name} sudah ada sebagai customer ID {$existingCustomer->id}\n";
                } else {
                    $customer = Customer::create([
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone ?? '000000000000',
                        'address' => 'Alamat belum diisi - Data migrasi',
                        'id_number' => 'USR' . str_pad($user->id, 13, '0', STR_PAD_LEFT),
                        'id_type' => 'ktp',
                        'gender' => 'male', // Default, bisa diupdate nanti
                        'status' => 'active',
                        'notes' => 'Customer dibuat otomatis saat migrasi dari user ID ' . $user->id,
                        'created_at' => $user->created_at,
                        'updated_at' => $user->updated_at,
                    ]);
                    
                    $userToCustomerMap[$user->id] = $customer->id;
                    echo "   - Migrasi user {$user->name} -> customer ID {$customer->id}\n";
                }
            }
            
            echo "\n3. Update transaksi yang bermasalah...\n";
            $invalidTransactions = PawnTransaction::whereNotIn('customer_id', function($query) {
                $query->select('id')->from('customers');
            })->get();
            
            $updatedCount = 0;
            $notFoundCount = 0;
            
            foreach ($invalidTransactions as $transaction) {
                if (isset($userToCustomerMap[$transaction->customer_id])) {
                    $oldCustomerId = $transaction->customer_id;
                    $newCustomerId = $userToCustomerMap[$transaction->customer_id];
                    
                    $transaction->update([
                        'customer_id' => $newCustomerId
                    ]);
                    
                    echo "   - Updated transaksi {$transaction->transaction_code}: customer_id {$oldCustomerId} -> {$newCustomerId}\n";
                    $updatedCount++;
                } else {
                    echo "   - TIDAK DITEMUKAN mapping untuk customer_id {$transaction->customer_id} di transaksi {$transaction->transaction_code}\n";
                    $notFoundCount++;
                }
            }
            
            echo "\n   Summary: {$updatedCount} transaksi berhasil diupdate, {$notFoundCount} tidak ditemukan mapping\n";
            
            // Untuk transaksi yang tidak ditemukan mapping, buat customer dummy
            if ($notFoundCount > 0) {
                echo "\n4. Membuat customer dummy untuk transaksi yang tidak ditemukan mapping...\n";
                
                $remainingInvalidTransactions = PawnTransaction::whereNotIn('customer_id', function($query) {
                    $query->select('id')->from('customers');
                })->get();
                
                $createdCustomers = [];
                
                foreach ($remainingInvalidTransactions as $transaction) {
                    if (!isset($createdCustomers[$transaction->customer_id])) {
                        $customer = Customer::create([
                            'name' => 'Customer ID ' . $transaction->customer_id . ' (Tidak ditemukan)',
                            'email' => 'notfound' . $transaction->customer_id . '@migrated.local',
                            'phone' => '000000000000',
                            'address' => 'Alamat belum diisi - Data tidak ditemukan',
                            'id_number' => 'NF' . str_pad($transaction->customer_id, 14, '0', STR_PAD_LEFT),
                            'id_type' => 'ktp',
                            'gender' => 'male',
                            'status' => 'active',
                            'notes' => 'Customer dibuat otomatis - user asli dengan ID ' . $transaction->customer_id . ' tidak ditemukan',
                        ]);
                        
                        $createdCustomers[$transaction->customer_id] = $customer->id;
                        echo "   - Dibuat customer dummy: ID {$customer->id} untuk customer_id {$transaction->customer_id}\n";
                    }
                    
                    $transaction->update([
                        'customer_id' => $createdCustomers[$transaction->customer_id]
                    ]);
                    
                    echo "   - Updated transaksi {$transaction->transaction_code}: customer_id {$transaction->customer_id} -> {$createdCustomers[$transaction->customer_id]}\n";
                }
            }
        }
        
        echo "\n=== VERIFIKASI HASIL ===\n";
        
        // Verifikasi tidak ada lagi transaksi dengan customer_id invalid
        $remainingInvalid = PawnTransaction::whereNotIn('customer_id', function($query) {
            $query->select('id')->from('customers');
        })->count();
        
        if ($remainingInvalid == 0) {
            echo "✅ BERHASIL: Semua transaksi sekarang memiliki customer_id yang valid!\n";
        } else {
            echo "❌ MASIH ADA MASALAH: {$remainingInvalid} transaksi masih memiliki customer_id tidak valid\n";
        }
        
        $totalCustomers = Customer::count();
        $totalTransactions = PawnTransaction::count();
        
        echo "📊 STATISTIK AKHIR:\n";
        echo "   - Total customers: {$totalCustomers}\n";
        echo "   - Total transactions: {$totalTransactions}\n";
        echo "   - Invalid transactions: {$remainingInvalid}\n";
    });
    
    echo "\n✅ MIGRASI SELESAI!\n";
    echo "Sekarang Anda bisa mencoba membuat transaksi baru.\n";
    
} catch (Exception $e) {
    echo "\n❌ ERROR TERJADI: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
    echo "\nTransaksi database di-rollback. Tidak ada perubahan yang disimpan.\n";
}

echo "\n=== SELESAI ===\n";