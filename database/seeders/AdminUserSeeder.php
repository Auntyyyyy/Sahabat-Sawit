<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@sahabatsawit.com'], // dipakai sebagai kunci pengecekan
            [
                'name' => 'Admin Utama',
                'password' => Hash::make('SSRS2026'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'personalia@sahabatsawit.com'],
            [
                'name' => 'Sintia Talesta Pakpahan, S.P',
                'password' => Hash::make('Personalia2026'),
                'role' => 'personalia',
            ]
        );
    }
}