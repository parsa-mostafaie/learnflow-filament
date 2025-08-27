<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class SeedUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:seed-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seeds Users';

    /**
     * Execute the console command.
     */
    public function handle()
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
