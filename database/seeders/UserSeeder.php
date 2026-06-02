<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Juan Dela Cruz',
            'email'    => 'admin@movielist.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);
    }
}