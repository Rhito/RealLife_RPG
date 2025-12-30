<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Test User 1 - Verified, with some progress
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password', // Will be hashed automatically
            'email_verified_at' => now(),
            'coins' => 1000,
            'exp' => 1500,
            'level' => 3,
            'hp' => 80,
            'max_hp' => 100,
            'current_streak' => 5,
            'last_streak_date' => now(),
        ]);

        // Test User 2 - New user, just registered
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'email_verified_at' => now(),
            'coins' => 500,
            'exp' => 500,
            'level' => 1,
            'hp' => 50,
            'max_hp' => 100,
            'current_streak' => 0,
        ]);

        // Test User 3 - High level user
        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => 'password',
            'email_verified_at' => now(),
            'coins' => 5000,
            'exp' => 10000,
            'level' => 10,
            'hp' => 100,
            'max_hp' => 150,
            'current_streak' => 30,
            'last_streak_date' => now(),
        ]);

        // Test User 4 - Unverified email (for testing verification flow)
        User::create([
            'name' => 'Unverified User',
            'email' => 'unverified@example.com',
            'password' => 'password',
            'email_verified_at' => null,
            'coins' => 500,
            'exp' => 500,
            'level' => 1,
            'hp' => 50,
            'max_hp' => 100,
        ]);

        // Test User 5 - Low HP user (for testing HP mechanics)
        User::create([
            'name' => 'Low HP User',
            'email' => 'lowhp@example.com',
            'password' => 'password',
            'email_verified_at' => now(),
            'coins' => 100,
            'exp' => 800,
            'level' => 2,
            'hp' => 10,
            'max_hp' => 100,
            'current_streak' => 3,
            'last_streak_date' => now()->subDay(),
        ]);

        echo "\n✅ Created 5 test users:\n";
        echo "   1. test@example.com (password: password) - Verified, Level 3\n";
        echo "   2. john@example.com (password: password) - Verified, Level 1\n";
        echo "   3. jane@example.com (password: password) - Verified, Level 10 (High level)\n";
        echo "   4. unverified@example.com (password: password) - Unverified email\n";
        echo "   5. lowhp@example.com (password: password) - Verified, Low HP\n\n";
    }
}
