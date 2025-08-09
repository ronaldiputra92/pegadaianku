<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserAndCustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CustomerSeeder::class,
        ]);
    }
}