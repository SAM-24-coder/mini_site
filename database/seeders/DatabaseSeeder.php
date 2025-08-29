<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'surname' => 'Doe',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'gender' => 'male',
            'date_of_birth' => '1990-01-01',
            'phone' => '0612345678',
            'status' => 'active',
            'registration_timestamp' => now(),
            'email_verified_at' => now()
        ]);
    }
}
