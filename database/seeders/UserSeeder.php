<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(state: [
            'name' => 'پارسا',
            'email' => 'pmostafaie1390@gmail.com',
            'password' => bcrypt("pmostafaie1390@gmail.com"),
        ])
            ->role('developer')
            ->create();

        User::factory([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt("admin@example.com"),
        ])
            ->role('admin')
            ->create();

        User::factory([
            'name' => 'Manager',
            'email' => 'manager@example.com',
            'password' => bcrypt("manager@example.com"),
        ])
            ->role('manager')
            ->create();

        User::factory([
            'name' => 'Instructor',
            'email' => 'instructor@example.com',
            'password' => bcrypt("instructor@example.com"),
        ])
            ->role('instructor')
            ->create();

        User::factory([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => bcrypt("user@example.com"),
        ])->create();
    }
}
