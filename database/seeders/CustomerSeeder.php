<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Create sample customers
        for ($i = 1; $i <= 20; $i++) {
            Customer::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'address' => $faker->address,
                'id_number' => $faker->unique()->numerify('################'), // 16 digit KTP
                'id_type' => $faker->randomElement(['ktp', 'sim', 'passport']),
                'date_of_birth' => $faker->dateTimeBetween('-60 years', '-17 years'),
                'place_of_birth' => $faker->city,
                'gender' => $faker->randomElement(['male', 'female']),
                'occupation' => $faker->jobTitle,
                'monthly_income' => $faker->numberBetween(2000000, 15000000),
                'emergency_contact_name' => $faker->name,
                'emergency_contact_phone' => $faker->phoneNumber,
                'notes' => $faker->optional(0.3)->sentence,
                'status' => $faker->randomElement(['active', 'active', 'active', 'inactive']), // 75% active
            ]);
        }
    }
}