<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'nama' => 'Admin Sarpras',
            'password' => Hash::make('sarpras123'),
            'role' => 'sarpras'
        ]);

        User::create([
            'nama' => 'dani',
            'password' => Hash::make('12345'),
            'role' => 'guru'
        ]);
    }
}