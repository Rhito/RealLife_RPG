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
        // Seed items first (no dependencies)
        $this->call(ItemSeeder::class);
        
        // Seed test users with known credentials for manual testing
        $this->call(UserSeeder::class);
        
        // Optionally create some random users for testing with larger datasets
        // User::factory(10)->create();
    }
}
