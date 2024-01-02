<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Menambahkan data ke table di database tanpa melalui input form (biasanya untuk data default / bawaan)
        // "fillable" => "isilainnya"
        User::create([
            'name' => 'Administrator1',
            'email' => 'apotek_admin1@gmail.com',
            // hash : enkripsi agar password tersimpan berisi teks acak agar tidak bisa diprediksi / dibaca orang lain
            // Selain hash ada juga (bcrypt)
            'password' => Hash::make('adminapotek'),
            'role' => 'admin',
        ]);
        User::create([
            'name' => 'Hasan',
            'email' => 'hasan@gmail.com',
            // hash : enkripsi agar password tersimpan berisi teks acak agar tidak bisa diprediksi / dibaca orang lain
            // Selain hash ada juga (bcrypt)
            'password' => Hash::make('ubi'),
            'role' => 'cashier',
        ]);
    }
}
