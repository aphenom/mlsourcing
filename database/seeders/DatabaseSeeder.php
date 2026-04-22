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
        $this->call(PaymentOptionsSeeder::class);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'demo@example.com',
            'address' => 'CI ABJ01',
            'user_type' => 'admin',
            'password' => 'Mozagames123@@',
        ]);
    }
}
