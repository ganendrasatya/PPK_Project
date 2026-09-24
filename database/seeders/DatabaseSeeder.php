<?php

namespace Database\Seeders;

use App\Models\Facility;
use App\Models\Reservation;
use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        User::create([
            'name' => 'Admin PPK',
            'email' => 'admin@ppk.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'verified',
        ]);

        // Create Petugas
        User::create([
            'name' => 'Petugas Fasilitas',
            'email' => 'petugas@ppk.test',
            'password' => Hash::make('password'),
            'role' => 'petugas',
            'status' => 'verified',
        ]);

        // Create verified user
        $testUser = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'role' => 'pengguna',
            'status' => 'verified',
        ]);

        // Create pending user
        User::create([
            'name' => 'Calon Pengguna',
            'email' => 'pending@ppk.test',
            'password' => Hash::make('password'),
            'role' => 'pengguna',
            'status' => 'pending',
        ]);

        // Create Facilities
        $fac1 = Facility::create([
            'nama_fasilitas' => 'Ruang Kelas A101',
            'tipe' => 'Ruang Kelas',
            'lokasi' => 'Gedung A Lantai 1',
            'kapasitas' => 40,
            'deskripsi' => 'Ruang kelas standar dengan AC dan proyektor.',
            'status' => 'aktif',
            'jam_buka' => '07:00',
            'jam_tutup' => '18:00',
        ]);

        Facility::create([
            'nama_fasilitas' => 'Aula Serbaguna',
            'tipe' => 'Aula',
            'lokasi' => 'Gedung Utama',
            'kapasitas' => 300,
            'deskripsi' => 'Aula besar untuk acara kampus.',
            'status' => 'aktif',
            'jam_buka' => '07:00',
            'jam_tutup' => '18:00',
        ]);

        Facility::create([
            'nama_fasilitas' => 'Laboratorium Komputer 1',
            'tipe' => 'Laboratorium',
            'lokasi' => 'Gedung B Lantai 2',
            'kapasitas' => 30,
            'deskripsi' => 'Lab komputer dengan 30 unit PC.',
            'status' => 'aktif',
            'jam_buka' => '07:00',
            'jam_tutup' => '18:00',
        ]);

        Facility::create([
            'nama_fasilitas' => 'Lapangan Basket',
            'tipe' => 'Olahraga',
            'lokasi' => 'Area Olahraga',
            'kapasitas' => 20,
            'deskripsi' => 'Lapangan basket outdoor standar.',
            'status' => 'aktif',
            'jam_buka' => '07:00',
            'jam_tutup' => '18:00',
        ]);

        Facility::create([
            'nama_fasilitas' => 'GOR Basket',
            'tipe' => 'Olahraga',
            'lokasi' => 'Pusat Olahraga Kampus',
            'kapasitas' => 1000,
            'deskripsi' => 'GOR Basket indoor dengan tribun penonton.',
            'status' => 'aktif',
            'jam_buka' => '07:00',
            'jam_tutup' => '18:00',
        ]);

        Facility::create([
            'nama_fasilitas' => 'Stadion UNDIP',
            'tipe' => 'Olahraga',
            'lokasi' => 'Kompleks Olahraga',
            'kapasitas' => 5000,
            'deskripsi' => 'Stadion sepak bola dengan lintasan atletik.',
            'status' => 'aktif',
            'jam_buka' => '07:00',
            'jam_tutup' => '18:00',
        ]);

        Facility::create([
            'nama_fasilitas' => 'Ruang Kelas Smart A101',
            'tipe' => 'Ruang Kelas',
            'lokasi' => 'Gedung A Lantai 1',
            'kapasitas' => 40,
            'deskripsi' => 'Ruang kelas pintar dengan smart board.',
            'status' => 'aktif',
            'jam_buka' => '07:00',
            'jam_tutup' => '18:00',
        ]);

        $labKom = Facility::create([
            'nama_fasilitas' => 'Lab Komputer Acintya Prasada',
            'tipe' => 'Laboratorium',
            'lokasi' => 'Gedung Acintya Prasada',
            'kapasitas' => 50,
            'deskripsi' => 'Laboratorium komputer canggih.',
            'status' => 'dalam_perbaikan',
            'jam_buka' => '07:00',
            'jam_tutup' => '18:00',
        ]);

        Facility::create([
            'nama_fasilitas' => 'Acintya Prasada Lt. 6',
            'tipe' => 'Auditorium',
            'lokasi' => 'Gedung Acintya Prasada',
            'kapasitas' => 200,
            'deskripsi' => 'Auditorium modern untuk seminar dan acara besar.',
            'status' => 'aktif',
            'jam_buka' => '07:00',
            'jam_tutup' => '18:00',
        ]);

        // Sample Reservation
        Reservation::create([
            'user_id' => $testUser->id,
            'facility_id' => $fac1->id,
            'purpose' => 'Rapat BEM',
            'start_time' => Carbon::tomorrow()->setTime(9, 0, 0),
            'end_time' => Carbon::tomorrow()->setTime(11, 0, 0),
            'status' => 'approved',
        ]);

        Reservation::create([
            'user_id' => $testUser->id,
            'facility_id' => $fac1->id,
            'purpose' => 'Seminar Himpunan',
            'start_time' => Carbon::now()->addDays(2)->setTime(13, 0, 0),
            'end_time' => Carbon::now()->addDays(2)->setTime(15, 0, 0),
            'status' => 'pending',
        ]);

        Report::create([
            'user_id' => $testUser->id,
            'facility_id' => $labKom->id,
            'title' => 'AC Rusak',
            'category' => 'AC & Ventilasi',
            'urgency' => 'sedang',
            'description' => 'AC di pojok ruangan tidak dingin sama sekali.',
            'status' => 'baru',
        ]);
 
    }
}
