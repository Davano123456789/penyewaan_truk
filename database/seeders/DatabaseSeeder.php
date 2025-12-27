<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Peran; // Pastikan model Peran ada
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat Data Peran (Admin, Client, Sopir)
        $peranAdmin = Peran::create(['nama' => 'admin']);
        $peranClient = Peran::create(['nama' => 'client']);
        $peranSopir = Peran::create(['nama' => 'sopir']);

        // 2. Buat Akun Admin Default
        User::create([
            'nama' => 'Admin Utama',
            'email' => 'admin@gmail.com', // Email untuk login
            'kata_sandi' => Hash::make('password'), // Password default
            'alamat' => 'Kantor Pusat',
            'telepon' => '08123456789',
            'umur' => 25,
            'peran_id' => $peranAdmin->id, // Hubungkan ke peran Admin
        ]);

        // 3. (Opsional) Buat Akun Sopir Dummy untuk tes
        User::create([
            'nama' => 'Sopir Budi',
            'email' => 'sopir@gmail.com',
            'kata_sandi' => Hash::make('password'),
            'alamat' => 'Garasi Surabaya',
            'telepon' => '08987654321',
            'umur' => 30,
            'peran_id' => $peranSopir->id,
        ]);
        
        // 4. (Opsional) Buat Parkir Dummy agar peta tidak error
        \App\Models\Parkir::create([
            'nama' => 'Garasi Pusat Surabaya',
            'alamat' => 'Jl. Ahmad Yani No. 123, Surabaya',
            'latitude' => -7.311394,
            'longitude' => 112.726212
        ]);
    }
}
