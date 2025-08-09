<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Customer;
use Illuminate\Support\Facades\DB;

echo "=== MEMBUAT SAMPLE CUSTOMERS ===\n\n";

try {
    DB::transaction(function() {
        echo "1. Mengecek customers yang sudah ada...\n";
        $existingCount = Customer::count();
        echo "   Customers yang sudah ada: {$existingCount}\n\n";
        
        if ($existingCount >= 5) {
            echo "✅ Sudah ada cukup customers ({$existingCount}). Tidak perlu membuat yang baru.\n";
            return;
        }
        
        echo "2. Membuat sample customers...\n";
        
        $sampleCustomers = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@email.com',
                'phone' => '081234567890',
                'address' => 'Jl. Merdeka No. 123, Jakarta Pusat',
                'id_number' => '3171012345678901',
                'id_type' => 'ktp',
                'date_of_birth' => '1985-05-15',
                'place_of_birth' => 'Jakarta',
                'gender' => 'male',
                'occupation' => 'Karyawan Swasta',
                'monthly_income' => 5000000,
                'emergency_contact_name' => 'Siti Santoso',
                'emergency_contact_phone' => '081234567891',
                'status' => 'active',
                'notes' => 'Customer sample untuk testing'
            ],
            [
                'name' => 'Sari Dewi',
                'email' => 'sari.dewi@email.com',
                'phone' => '081234567892',
                'address' => 'Jl. Sudirman No. 456, Jakarta Selatan',
                'id_number' => '3171012345678902',
                'id_type' => 'ktp',
                'date_of_birth' => '1990-08-20',
                'place_of_birth' => 'Bandung',
                'gender' => 'female',
                'occupation' => 'Wiraswasta',
                'monthly_income' => 7500000,
                'emergency_contact_name' => 'Ahmad Dewi',
                'emergency_contact_phone' => '081234567893',
                'status' => 'active',
                'notes' => 'Customer sample untuk testing'
            ],
            [
                'name' => 'Agus Pratama',
                'email' => 'agus.pratama@email.com',
                'phone' => '081234567894',
                'address' => 'Jl. Gatot Subroto No. 789, Jakarta Barat',
                'id_number' => '3171012345678903',
                'id_type' => 'ktp',
                'date_of_birth' => '1988-12-10',
                'place_of_birth' => 'Surabaya',
                'gender' => 'male',
                'occupation' => 'PNS',
                'monthly_income' => 6000000,
                'emergency_contact_name' => 'Rina Pratama',
                'emergency_contact_phone' => '081234567895',
                'status' => 'active',
                'notes' => 'Customer sample untuk testing'
            ],
            [
                'name' => 'Maya Sari',
                'email' => 'maya.sari@email.com',
                'phone' => '081234567896',
                'address' => 'Jl. Thamrin No. 321, Jakarta Utara',
                'id_number' => '3171012345678904',
                'id_type' => 'ktp',
                'date_of_birth' => '1992-03-25',
                'place_of_birth' => 'Medan',
                'gender' => 'female',
                'occupation' => 'Guru',
                'monthly_income' => 4500000,
                'emergency_contact_name' => 'Dedi Sari',
                'emergency_contact_phone' => '081234567897',
                'status' => 'active',
                'notes' => 'Customer sample untuk testing'
            ],
            [
                'name' => 'Rudi Hermawan',
                'email' => 'rudi.hermawan@email.com',
                'phone' => '081234567898',
                'address' => 'Jl. Kuningan No. 654, Jakarta Timur',
                'id_number' => '3171012345678905',
                'id_type' => 'ktp',
                'date_of_birth' => '1987-11-30',
                'place_of_birth' => 'Yogyakarta',
                'gender' => 'male',
                'occupation' => 'Pengusaha',
                'monthly_income' => 10000000,
                'emergency_contact_name' => 'Lisa Hermawan',
                'emergency_contact_phone' => '081234567899',
                'status' => 'active',
                'notes' => 'Customer sample untuk testing'
            ]
        ];
        
        $createdCount = 0;
        
        foreach ($sampleCustomers as $customerData) {
            // Cek apakah customer dengan email atau phone sudah ada
            $existing = Customer::where('email', $customerData['email'])
                              ->orWhere('phone', $customerData['phone'])
                              ->orWhere('id_number', $customerData['id_number'])
                              ->first();
            
            if ($existing) {
                echo "   - Skip {$customerData['name']} (sudah ada)\n";
                continue;
            }
            
            $customer = Customer::create($customerData);
            echo "   - Dibuat: {$customer->name} (ID: {$customer->id})\n";
            $createdCount++;
        }
        
        echo "\n✅ Berhasil membuat {$createdCount} customers baru\n";
        
        $totalCustomers = Customer::count();
        echo "📊 Total customers sekarang: {$totalCustomers}\n";
        
        // Tampilkan daftar customers untuk referensi
        echo "\n=== DAFTAR CUSTOMERS AKTIF ===\n";
        $customers = Customer::where('status', 'active')->orderBy('id')->get();
        
        foreach ($customers as $customer) {
            echo "ID: {$customer->id} - {$customer->name} ({$customer->phone})\n";
        }
    });
    
    echo "\n✅ SELESAI!\n";
    echo "Sekarang Anda bisa mencoba membuat transaksi dengan customer yang tersedia.\n";
    
} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== COMPLETED ===\n";