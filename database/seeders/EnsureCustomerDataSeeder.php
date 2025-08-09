<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\User;

class EnsureCustomerDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada customer dengan ID 1, 2, 3 untuk testing
        $customers = [
            [
                'id' => 1,
                'name' => 'Ahmad Wijaya',
                'email' => 'ahmad.wijaya@example.com',
                'phone' => '081234567891',
                'address' => 'Jl. Merdeka No. 1, Jakarta Pusat',
                'id_number' => '3171234567890001',
                'id_type' => 'ktp',
                'date_of_birth' => '1985-05-15',
                'place_of_birth' => 'Jakarta',
                'gender' => 'male',
                'occupation' => 'Wiraswasta',
                'monthly_income' => 5000000,
                'status' => 'active'
            ],
            [
                'id' => 2,
                'name' => 'Siti Nurhaliza',
                'email' => 'siti.nurhaliza@example.com',
                'phone' => '081234567892',
                'address' => 'Jl. Sudirman No. 2, Jakarta Selatan',
                'id_number' => '3171234567890002',
                'id_type' => 'ktp',
                'date_of_birth' => '1990-08-20',
                'place_of_birth' => 'Bandung',
                'gender' => 'female',
                'occupation' => 'Karyawan Swasta',
                'monthly_income' => 4000000,
                'status' => 'active'
            ],
            [
                'id' => 3,
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@example.com',
                'phone' => '081234567893',
                'address' => 'Jl. Thamrin No. 3, Jakarta Pusat',
                'id_number' => '3171234567890003',
                'id_type' => 'ktp',
                'date_of_birth' => '1988-12-10',
                'place_of_birth' => 'Surabaya',
                'gender' => 'male',
                'occupation' => 'Pegawai Negeri',
                'monthly_income' => 6000000,
                'status' => 'active'
            ]
        ];

        foreach ($customers as $customerData) {
            $customer = Customer::find($customerData['id']);
            
            if (!$customer) {
                // Cek apakah ada user dengan role nasabah yang bisa dijadikan customer
                $user = User::where('role', 'nasabah')->first();
                
                if ($user && $customerData['id'] <= 3) {
                    // Gunakan data dari user jika ada
                    $customerData['name'] = $user->name;
                    $customerData['email'] = $user->email;
                    $customerData['phone'] = $user->phone ?? $customerData['phone'];
                    $customerData['address'] = $user->address ?? $customerData['address'];
                    $customerData['id_number'] = $user->identity_number ?? $customerData['id_number'];
                }
                
                Customer::create($customerData);
                $this->command->info("Customer created: {$customerData['name']} (ID: {$customerData['id']})");
            } else {
                $this->command->info("Customer already exists: {$customer->name} (ID: {$customer->id})");
            }
        }
        
        // Buat beberapa customer tambahan untuk testing
        if (Customer::count() < 10) {
            $additionalCustomers = [
                [
                    'name' => 'Dewi Sartika',
                    'email' => 'dewi.sartika@example.com',
                    'phone' => '081234567894',
                    'address' => 'Jl. Gatot Subroto No. 4, Jakarta Selatan',
                    'id_number' => '3171234567890004',
                    'id_type' => 'ktp',
                    'date_of_birth' => '1992-03-25',
                    'place_of_birth' => 'Yogyakarta',
                    'gender' => 'female',
                    'occupation' => 'Guru',
                    'monthly_income' => 3500000,
                    'status' => 'active'
                ],
                [
                    'name' => 'Rudi Hermawan',
                    'email' => 'rudi.hermawan@example.com',
                    'phone' => '081234567895',
                    'address' => 'Jl. Kuningan No. 5, Jakarta Selatan',
                    'id_number' => '3171234567890005',
                    'id_type' => 'ktp',
                    'date_of_birth' => '1987-07-18',
                    'place_of_birth' => 'Medan',
                    'gender' => 'male',
                    'occupation' => 'Dokter',
                    'monthly_income' => 8000000,
                    'status' => 'active'
                ]
            ];
            
            foreach ($additionalCustomers as $customerData) {
                if (!Customer::where('email', $customerData['email'])->exists()) {
                    Customer::create($customerData);
                    $this->command->info("Additional customer created: {$customerData['name']}");
                }
            }
        }
    }
}