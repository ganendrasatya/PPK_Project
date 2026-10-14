# 📋 Dokumentasi Pembaruan Sistem (Changelog & Architecture Guide)

Dokumen ini mencatat seluruh pembaruan fitur, perbaikan bug, keputusan arsitektur, dan panduan teknis pada sistem **Reservasi Fasilitas Kampus & Pelaporan Kerusakan**. Dokumen ini dirancang agar **anggota tim pengembang** maupun **AI Coding Agent** (Cursor, Claude, Copilot, Antigravity, dll.) dapat langsung memahami status terkini proyek tanpa kebingungan.

---

## 📌 Ringkasan Status Proyek Saat Ini
* **Framework:** Laravel 11 (PHP 8.2+)
* **Frontend:** Blade Templating, Alpine.js, Tailwind CSS v3, Vite
* **Testing Suite:** Pest PHP / PHPUnit (**86 tests passing, 272 assertions**)
* **Branch Utama:** `feature/user-portal`
* **Default Locale:** `id` (Bahasa Indonesia)

---

## 🔐 1. Segmentasi Peran & Hak Akses (Role-Based Segmentation)

Sistem memiliki 3 tingkatan peran (*role*) yang tersegmentasi secara ketat di tingkat rute, controller, form request, dan tampilan antarmuka:

| Peran | Tanggung Jawab Utama | Akses Halaman | Batasan Akses |
| :--- | :--- | :--- | :--- |
| **`pengguna`** *(Mahasiswa & Sivitas)* | Menjelajahi katalog, memesan slot waktu fasilitas (kelipatan 30 menit), melihat riwayat reservasi mandiri, membatalkan reservasi pending, dan melaporkan sarana rusak. | • `/` (Katalog)<br>• `/facilities/{id}` (Detail Fasilitas)<br>• `/reservations` (Reservasi Saya)<br>• `/reports` (Laporan Kerusakan) | Dilarang mengakses rute berawalan `/admin/*`. |
| **`petugas`** *(Staff Fasilitas)* | Memverifikasi dan memproses persetujuan reservasi, memeriksa berkas proposal (PDF), menindaklanjuti laporan kerusakan, dan mengubah status operasional fasilitas. | • `/` (Katalog)<br>• `/admin` (Dashboard Petugas)<br>• `/reports` (Antrean Kelola Laporan) | Tidak melakukan peminjaman mandiri melalui antarmuka mahasiswa. |
| **`admin`** *(Administrator Utama)* | Mengelola data master fasilitas (CRUD), menyetujui pendaftaran akun pengguna/petugas, melihat rekapitulasi, dan ekspor data CSV. | • `/admin/*` (Seluruh Panel Admin)<br>• `/` (Katalog Publik) | Jika Admin membuka `/reservations` atau `/reports`, sistem **otomatis mengalihkan (*redirect*) ke `/admin`** dengan notifikasi info. |

### Aturan Otorisasi Kode (Wajib Dipertahankan):
1. **Form Request Authorization (`app/Http/Requests`):**
   * [`StoreReservationRequest.php`](app/Http/Requests/StoreReservationRequest.php) dan [`StoreDamageReportRequest.php`](app/Http/Requests/StoreDamageReportRequest.php) menerapkan:
     ```php
     public function authorize(): bool
     {
         return $this->user()?->isPengguna() ?? false;
     }
     ```
     Jika Admin atau Petugas mencoba mengirim request POST, request akan ditolak langsung dengan status **HTTP 403 Forbidden** sebelum validasi dijalankan.
2. **Controller Redirection:**
   * Pada `DamageReportController@index` dan `ReservationController@index`, akun dengan role `admin` otomatis dialihkan ke `admin.dashboard`.
3. **Navigasi Dinamis (`resources/views/layouts/catalog.blade.php`):**
   * Menu navbar desktop & mobile hanya menampilkan item yang relevan dengan peran yang sedang login (`isPengguna`, `isPetugas`, `isAdmin`).
4. **Detail Fasilitas (`resources/views/catalog/show.blade.php`):**
   * Tombol booking slot `Buat Reservasi` hanya muncul untuk `pengguna`. Untuk Admin dan Petugas, ditampilkan kotak informasi peran yang mengarahkan mereka ke panel masing-masing.

---

## 🛠️ 2. Pembaruan Modul Pelaporan Kerusakan (`/reports`)

### A. Sistem Tab Navigasi Bebas Scroll
* **Masalah Sebelumnya:** Halaman sebelumnya menggunakan layout samping 2 kolom yang runtuh (*collapse*) menjadi 1 kolom vertikal panjang di layar laptop/tablet. Pengguna harus scroll panjang melewati formulir tinggi (>900px) untuk melihat riwayat laporannya di bawah.
* **Solusi Terpasang:**
  * Dibagi menjadi dua tab bersih: **`Riwayat Laporan Saya`** dan **`Buat Laporan Baru`**.
  * **Default State:** Pengguna yang sudah memiliki laporan langsung disambut oleh tab **Riwayat Laporan Saya** di posisi atas tanpa perlu scroll sama sekali.
  * Jika pengguna belum memiliki laporan (0 data) atau form mengalami kegagalan validasi, tab otomatis membuka **Buat Laporan Baru**.
  * Kartu riwayat tampil lebar penuh (*full-width*) dilengkapi filter status (*Semua, Menunggu Validasi, Sedang Diproses, Selesai, Ditolak*).
  * Tombol navigasi dibuat minimalis langsung pada tab bar tanpa redundansi tombol di sisi samping.

### B. Searchable Combobox Fasilitas (Pencarian Real-Time)
* **Masalah Sebelumnya:** Dropdown `<select>` standar sulit digunakan jika jumlah fasilitas kampus sangat banyak.
* **Solusi Terpasang:**
  * Dropdown digantikan dengan komponen **Searchable Combobox** interaktif menggunakan Alpine.js.
  * Pengguna bisa mencari secara instan berdasarkan nama fasilitas, lokasi gedung, atau jenis fasilitas.
  * **HTML Semantics Fix:** Elemen pembungkus menggunakan `<div role="button" tabindex="0">`, menghindari bug penutupan paksa browser akibat nesting `<button>` di dalam `<button>`.
  * Mendukung keyboard: Tombol `Enter` memilih opsi teratas tanpa memicu submit form sebelum waktunya (`@keydown.enter.prevent`), dan tombol `Esc` menutup dropdown.
  * Nilai pilihan dikirimkan via `<input type="hidden" name="facility_id" :value="selectedId">`, mempertahankan kompatibilitas validasi Laravel dan fungsi `old('facility_id')`.

### C. Akses & Tinjauan Foto Bukti Kerusakan
* **Masalah Sebelumnya:** Foto yang diunggah pengguna tidak muncul di halaman peninjauan petugas (hanya placeholder kunci inggris statis) dan validasi upload Alpine.js rentan menghapus file pada browser Windows.
* **Solusi Terpasang:**
  * Pengecekan file upload kini mendukung ekstensi (`.jpg`, `.jpeg`, `.png`, `.webp`) dan MIME type secara fleksibel hingga 5MB.
  * Pada dashboard petugas dan kartu riwayat pengguna, thumbnail foto kini terhubung langsung dengan tautan **"Buka Foto"** / **"Lihat Foto Bukti Kerusakan →"** (`target="_blank"`) melalui `Storage::url(...)`.

---

## 🏢 3. Pembaruan Modul Admin Fasilitas & Reservasi

1. **Perbaikan Edit Fasilitas:**
   * Method `edit(Facility $facility)` pada [`app/Http/Controllers/Admin/FacilityController.php`](app/Http/Controllers/Admin/FacilityController.php) telah dipulihkan untuk merender view `admin.facilities.edit`.
2. **Perbaikan Overflow Dropdown Status:**
   * Pada tabel daftar fasilitas di [`resources/views/admin/facilities/index.blade.php`](resources/views/admin/facilities/index.blade.php), ditambahkan penyesuaian CSS agar dropdown aksi status tidak terpotong oleh `overflow-hidden` pada elemen tabel.
3. **Pembatalan Otomatis Reservasi:**
   * Ketika status fasilitas diubah menjadi `dalam_perbaikan` atau `nonaktif`, seluruh reservasi mendatang yang berstatus `pending` atau `approved` dibatalkan secara otomatis dengan alasan terstandarisasi via method `Facility::cancelUpcomingReservations()`.

---

## 📁 4. Daftar File yang Diubah & Tanggung Jawabnya

| File | Perubahan Utama |
| :--- | :--- |
| `app/Http/Controllers/Admin/FacilityController.php` | Memulihkan method `edit()` untuk form update fasilitas. |
| `app/Http/Controllers/DamageReportController.php` | Pengalihan role admin ke dashboard, pengurutan fasilitas secara alfabetis (`orderBy('nama_fasilitas')`), proteksi otorisasi. |
| `app/Http/Controllers/ReservationController.php` | Pengalihan role admin ke dashboard, pengetatan akses reservasi khusus pengguna. |
| `app/Http/Requests/StoreDamageReportRequest.php` | Otorisasi method `authorize()` khusus `isPengguna()`. |
| `app/Http/Requests/StoreReservationRequest.php` | Otorisasi method `authorize()` khusus `isPengguna()`. |
| `resources/views/layouts/catalog.blade.php` | Segmentasi menu navbar desktop dan mobile berdasarkan peran pengguna. |
| `resources/views/catalog/show.blade.php` | Pembedaan tombol booking slot slot waktu vs kotak info peran untuk admin & petugas. |
| `resources/views/reports/index.blade.php` | Sistem tab navigasi riwayat vs form, searchable combobox fasilitas, validasi upload foto, dan penampil foto bukti. |
| `resources/views/admin/facilities/index.blade.php` | Perbaikan styling overflow pada dropdown status fasilitas di tabel admin. |
| `tests/Feature/UserPortalTest.php` | Penambahan pengujian segmentasi peran, proteksi submit, rendering pencarian fasilitas, dan upload foto. |
| `phpunit.xml` | Penegasan konfigurasi env `APP_LOCALE=id`. |

---

## 🤖 5. Panduan Khusus untuk Developer & AI Coding Agent

Jika Anda adalah developer baru atau AI Agent yang melanjutkan pekerjaan di repo ini, harap ikuti aturan main berikut:

1. **Prinsip Minimalisme (Ponytail / YAGNI):**
   * Jangan membuat abstraksi atau helper baru yang tidak diminta secara eksplisit.
   * Gunakan helper yang sudah ada di model `User` (`isAdmin()`, `isPetugas()`, `isPengguna()`) dan `Facility` (`getStatusLabelAttribute()`, `cancelUpcomingReservations()`).
2. **Jangan Rusak Spesifikasi HTML pada Alpine.js:**
   * Hindari menaruh tag `<button>` di dalam `<button>`. Gunakan `<div role="button" tabindex="0">` jika memerlukan tombol dropdown interaktif yang memiliki sub-tombol hapus.
3. **Penanganan Form Upload:**
   * Jangan membungkus `<input type="file">` di dalam `<template x-if="...">`. Tag input file harus selalu ada di DOM agar data file terkirim dalam request HTTP POST.
4. **Verifikasi Selalu dengan Test Suite:**
   * Sebelum melakukan commit atau menyelesaikan tugas, jalankan perintah:
     ```bash
     php artisan test
     ```
   * Pastikan seluruh **86 test** (270+ assertions) tetap berwarna hijau.
5. **Kompilasi Aset Frontend:**
   * Jika ada perubahan styling Blade/Tailwind yang memerlukan build Vite:
     ```bash
     npm run build
     ```

