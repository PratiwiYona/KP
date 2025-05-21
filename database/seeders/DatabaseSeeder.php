<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Mobil; 
use App\Models\KondisiMobil; 
use App\Models\Keterangan; 
use App\Models\KeteranganMobil; 
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'username' => 'admin',
            'email'    => 'admin@gmail.com',
            'password' => Hash::make('password123'),
            'role'     => 'admin',
        ]);

        User::create([
            'username' => 'user',
            'email'    => 'user@gmail.com',
            'password' => Hash::make('password123'),
            'role'     => 'user',
        ]);

        Mobil::factory(100)->create();

        Keterangan::insert([
            ['keterangan' => 'Dicuci'],
            ['keterangan' => 'Dikeringkan'],
            ['keterangan' => 'Parkir'],
            ['keterangan' => 'Defect'],
            ['keterangan' => 'Maintenance'],
            ['keterangan' => 'Sudah Diperbaiki'],
        ]);

        KeteranganMobil::factory(20)->create();
        KondisiMobil::factory(20)->create();
    }
}
