<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->updateOrInsert(
            ['user_id' => 'ADMIN001'],
            [
                'name' => 'Admin & Operation',
                'email' => 'admin@bmms.local',
                'password' => Hash::make('ChangeMe123!'),
                'role' => 'Admin & Operation',
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }
}
