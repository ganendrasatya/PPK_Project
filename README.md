# 🏢 Sistem Reservasi Fasilitas Kampus & Pelaporan Kerusakan

Aplikasi berbasis web untuk digitalisasi pengelolaan, peminjaman fasilitas sarana-prasarana kampus, dan pelaporan kerusakan fasilitas secara terpadu. Sistem ini dirancang untuk menggantikan birokrasi manual menjadi proses yang transparan, efisien, dan bebas konflik jadwal (*zero-collision booking*).

---

## 🌟 Fitur Utama

Sistem ini memiliki **3 tingkatan hak akses peran (Role-Based Access Control)** yang disesuaikan dengan kebutuhan civitas akademika:

### 1. 🎓 Portal Mahasiswa & Sivitas (Pengguna)
* **Katalog Fasilitas Interaktif:** Pencarian sarana kampus (ruang kelas pintar, laboratorium, aula serbaguna, sarana olahraga) dilengkapi filter kategori, kapasitas, dan jam operasional.
* **Pemesanan Slot Waktu Real-Time:** Penjadwalan kelipatan 30 menit (07.00 – 18.00 WIB) dengan validasi otomatis agar tidak terjadi bentrok jadwal dengan peminjam lain.
* **Unggah Berkas Persyaratan:** Form reservasi terintegrasi untuk pengunggahan *Proposal Kegiatan* dan *Surat Permohonan Fakultas* (format PDF).
* **Riwayat & Pantauan Pengajuan:** Dasbor mandiri untuk melacak status verifikasi (Menunggu, Disetujui, Ditolak, Dibatalkan) dan opsi pembatalan pengajuan.
* **Pelaporan Kerusakan Fasilitas:** Formulir pelaporan sarana rusak dengan fitur *Searchable Combobox* (pencarian fasilitas instan), unggah foto bukti kerusakan, serta riwayat tindak lanjut petugas.

### 2. 🛡️ Portal Petugas Sarana & Prasarana
* **Dashboard Verifikasi:** Antrean tinjauan reservasi yang masuk secara terstruktur.
* **Pemeriksaan Dokumen:** Pratinjau langsung berkas proposal kegiatan dan surat izin mahasiswa.
* **Persetujuan & Penolakan:** Keputusan verifikasi dilengkapi pengisian catatan resmi atau alasan penolakan.
* **Kelola Laporan Kerusakan:** Pemantauan sarana rusak, peninjauan foto bukti kerusakan, serta pembaruan progres perbaikan (*Baru*, *Diproses*, *Selesai*, *Ditolak*).

### 3. ⚙️ Panel Administrator
* **Master Data Fasilitas (CRUD):** Tambah, ubah, dan kelola data sarana kampus termasuk kuota kapasitas, foto sarana, dan jam operasional.
* **Otomatisasi Pembatalan Fasilitas Perbaikan:** Saat status fasilitas diubah menjadi *Dalam Perbaikan* atau *Nonaktif*, jadwal reservasi mendatang yang terdampak otomatis dibatalkan oleh sistem dengan pemberitahuan alasan yang terstandarisasi.
* **Manajemen Pengguna:** Verifikasi aktivasi akun pengguna baru dan pendaftaran akun petugas.
* **Rekapitulasi & Ekspor Laporan:** Laporan komprehensif data reservasi dan kerusakan sarana dengan fitur ekspor ke format CSV, Excel, dan PDF.

---

## 🛠️ Tech Stack & Arsitektur

* **Backend:** [Laravel 11](https://laravel.com/) (PHP 8.2+)
* **Database ORM:** Eloquent ORM dengan relasi model terstruktur
* **Frontend:** Blade Templating, [Tailwind CSS v3](https://tailwindcss.com/), [Alpine.js](https://alpinejs.dev/)
* **Asset Bundler:** [Vite](https://vitejs.dev/)
* **Automated Testing:** [Pest PHP](https://pestphp.com/) & PHPUnit (**86 test cases, 272 assertions**)
* **File Storage:** Laravel Filesystem Storage (Proposal PDF & Foto Kerusakan)

---

## 🚀 Panduan Instalasi & Menjalankan Proyek

Pastikan perangkat Anda telah terpasang:
* **PHP >= 8.2** (dengan ekstensi `pdo`, `mbstring`, `openssl`, `fileinfo`)
* **Composer**
* **Node.js & NPM**
* **MySQL** atau **SQLite**

### Langkah-langkah Setup:

1. **Clone repository ini:**
   ```bash
   git clone https://github.com/ganendrasatya/PPK_Project.git
   cd PPK_Project
   ```

2. **Pasang dependensi PHP:**
   ```bash
   composer install
   ```

3. **Pasang dependensi JavaScript & kompilasi aset:**
   ```bash
   npm install
   npm run build
   ```

4. **Konfigurasi Environment:**
   Salin file konfigurasi `.env.example` ke `.env`:
   ```bash
   cp .env.example .env
   ```
   *Sesuaikan pengaturan koneksi basis data (`DB_CONNECTION`, `DB_DATABASE`, dll.) pada file `.env`.*

5. **Generate Application Key:**
   ```bash
   php artisan key:generate
   ```

6. **Jalankan Migrasi & Data Seeder:**
   ```bash
   php artisan migrate --seed
   ```

7. **Hubungkan Symlink Penyimpanan Publik:**
   ```bash
   php artisan storage:link
   ```

8. **Jalankan Server Lokal:**
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses melalui browser di: `http://127.0.0.1:8000`

---

## 🔑 Akun Demo Pengujian

Data seeder menyediakan akun uji coba untuk setiap tingkatan hak akses:

| Peran (*Role*) | Email | Password | Wewenang Utama |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin@ppk.test` | `password` | Mengelola data fasilitas, manajemen akun, rekap data & ekspor |
| **Petugas** | `petugas@ppk.test` | `password` | Meninjau proposal reservasi, memproses laporan kerusakan |
| **Pengguna** | `test@example.com` | `password` | Booking slot fasilitas, unggah proposal, buat laporan kerusakan |

---

## 🧪 Pengujian Otomatis (*Automated Tests*)

Proyek ini dilengkapi rangkaian pengujian otomatis end-to-end yang menguji otorisasi peran, validasi jadwal, pencegahan tabrakan reservasi, dan integritas berkas upload:

```bash
php artisan test
```

Hasil pengujian:
```text
Tests:    86 passed (272 assertions)
Status:   100% Green
```

---

## 📄 Lisensi

Proyek ini dikembangkan di bawah lisensi terbuka [MIT License](LICENSE).
