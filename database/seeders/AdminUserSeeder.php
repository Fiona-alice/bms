<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         User::create([
        'username' => 'Admin', 
        'name' => 'Administrator',
        'email' => 'admin@example.com',
        'role' => 'admin', 
        'password' => Hash::make('password123'),
    ]);
    }
}
