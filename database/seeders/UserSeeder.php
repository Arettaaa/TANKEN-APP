<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Akun Super Admin (Owner)
        \App\Models\User::create([
            'name' => 'Owner',
            'email' => 'explore.tanken@gmail.com',
            'password' => bcrypt('tankensukses'),
            'role' => 'super_admin',
        ]);

        // Akun Admin Gudang
        \App\Models\User::create([
            'name' => 'Staff Gudang',
            'email' => 'gudang@gmail.com',
            'password' => bcrypt('gudang123'),
            'role' => 'admin_gudang',
        ]);
    }
}
