<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // $faker = Faker::create();

        // $total = 10000;       // Số lượng bạn muốn seed
        // $batchSize = 1000;    // Tùy vào máy, có thể nâng lên 5000

        // $hashedPassword = bcrypt('password'); // Hash 1 lần thay vì 10k lần

        // for ($i = 0; $i < $total / $batchSize; $i++) {
        //     $data = [];

        //     for ($j = 0; $j < $batchSize; $j++) {
        //         $index = $i * $batchSize + $j;

        //         $data[] = [
        //             'name' => $faker->name(),
        //             'email' => "admin{$index}@example.com", // đảm bảo duy nhất
        //             'password' => $hashedPassword,
        //             'role' => 'moderator',
        //             'remember_token' => Str::random(10),
        //             'not_allowed' => false,
        //             'created_at' => now(),
        //             'updated_at' => now(),
        //         ];
        //     }


        //     DB::table('admins')->insert($data);
        // }

        //Admin::factory(10000)->create();

        Admin::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'), // Hoặc 'password' => 'secret' nếu bạn dùng hashing cast
            'role' => 'super',
        ]);
    }
}
