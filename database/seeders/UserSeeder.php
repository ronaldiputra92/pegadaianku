<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@pegadaianku.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Jl. Admin No. 1, Jakarta',
            'identity_number' => '1234567890123456',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create Officer User
        User::create([
            'name' => 'Petugas Pegadaian',
            'email' => 'petugas@pegadaianku.com',
            'password' => Hash::make('password'),
            'role' => 'petugas',
            'phone' => '081234567891',
            'address' => 'Jl. Petugas No. 2, Jakarta',
            'identity_number' => '1234567890123457',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create Customer User
        User::create([
            'name' => 'Nasabah Contoh',
            'email' => 'nasabah@pegadaianku.com',
            'password' => Hash::make('password'),
            'role' => 'nasabah',
            'phone' => '081234567892',
            'address' => 'Jl. Nasabah No. 3, Jakarta',
            'identity_number' => '1234567890123458',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create additional customers
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => Hash::make('password'),
            'role' => 'nasabah',
            'phone' => '081234567893',
            'address' => 'Jl. Mawar No. 10, Bandung',
            'identity_number' => '1234567890123459',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@example.com',
            'password' => Hash::make('password'),
            'role' => 'nasabah',
            'phone' => '081234567894',
            'address' => 'Jl. Melati No. 15, Surabaya',
            'identity_number' => '1234567890123460',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
    }
}