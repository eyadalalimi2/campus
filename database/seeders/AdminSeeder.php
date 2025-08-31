<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::firstOrCreate(
            ['email' => 'eyad@admin.com'],
            ['name' => 'Super Admin', 'password' => Hash::make('123456789')]
        );
    }
}
