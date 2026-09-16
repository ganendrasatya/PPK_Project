<?php

namespace Database\Seeders;

use App\Models\Facility;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's application.
     */
    public function run(): void
    {
        User::factory()->admin()->create([
            'name' => 'Admin PPK',
            'email' => 'admin@ppk.test',
        ]);

        User::factory()->petugas()->create([
            'name' => 'Petugas Fasilitas',
            'email' => 'petugas@ppk.test',
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        User::factory()->pending()->create([
            'name' => 'Calon Pengguna',
            'email' => 'pending@ppk.test',
        ]);

        Facility::factory()->createMany([
            ['nama_fasilitas' => 'Ruang Kelas A101', 'tipe' => 'Ruang Kelas', 'lokasi' => 'Gedung A Lantai 1', 'kapasitas' => 40, 'deskripsi' => 'Ruang kelas standar dengan proyektor.'],
            ['nama_fasilitas' => 'Aula Serbaguna', 'tipe' => 'Aula', 'lokasi' => 'Gedung Utama', 'kapasitas' => 300, 'deskripsi' => 'Aula untuk acara besar.'],
            ['nama_fasilitas' => 'Laboratorium Komputer 1', 'tipe' => 'Laboratorium', 'lokasi' => 'Gedung B Lantai 2', 'kapasitas' => 30, 'deskripsi' => 'Lab komputer untuk praktikum.'],
            ['nama_fasilitas' => 'Lapangan Basket', 'tipe' => 'Lapangan', 'lokasi' => 'Area Olahraga', 'kapasitas' => 20, 'deskripsi' => 'Lapangan basket outdoor.'],
        ]);
    }
}
