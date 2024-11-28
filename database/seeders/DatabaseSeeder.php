<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('malaiktha1290'),
            'email_verified_at' => now()
        ]);
        User::create([
            'name' => 'Basic User',
            'email' => 'sumnsth@gmail.com',
            'password' => bcrypt('malaiktha1290'),
            'email_verified_at' => now()
        ]);
    }
}
