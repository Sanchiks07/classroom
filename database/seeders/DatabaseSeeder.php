<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'username' => 'SanijaAdmin',
            'email' => 'sanija.admin@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::factory()->create([
            'username' => 'SanijaTeacher',
            'email' => 'sanija.teacher@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'teacher',
        ]);

        User::factory()->create([
            'username' => 'SanijaStudent',
            'email' => 'sanija.student@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'student',
        ]);

        User::factory()->create([
            'username' => 'LeonsAdmin',
            'email' => 'leons.admin@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::factory()->create([
            'username' => 'LeonsTeacher',
            'email' => 'leons.teacher@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'teacher',
        ]);

        User::factory()->create([
            'username' => 'LeonsStudent',
            'email' => 'leons.student@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'student',
        ]);
    }
}
