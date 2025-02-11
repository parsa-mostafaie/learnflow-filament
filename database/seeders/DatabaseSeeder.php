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
            'name' => 'Parsa',
            'email' => 'pmostafaie1390@gmail.com',
            'password' => bcrypt("pmostafaie1390@gmail.com"),
            'role' => 2
        ]);

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt("admin@example.com"),
            'role' => 1
        ]);

        User::factory()->create([
            'name' => 'Ali',
            'email' => 'ali@gmail.com',
            'password' => bcrypt("ali@gmail.com"),
            'role' => 0
        ]);
    }
}
