<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - PPK 2026 Facilities</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800 antialiased font-sans">

    <!-- Header / Navbar -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="text-2xl font-black text-indigo-600">PPK</span>
                <span class="text-sm font-semibold tracking-wider text-gray-500 uppercase">2026 Facilities</span>
            </div>

            <nav class="flex items-center gap-4">
                <span class="text-sm text-gray-600">Halo, {{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition">
                        Logout
                    </button>
                </form>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="bg-indigo-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight mb-4">Layanan Pemesanan Fasilitas & Pelaporan Kampus</h1>
            <p class="text-lg text-indigo-200 max-w-2xl mx-auto mb-8">Ajukan pinjaman ruangan, laboratorium, atau laporkan kerusakan sarana prasarana secara praktis dan transparan.</p>
            <div class="flex justify-center gap-4">
                <a href="#katalog" class="px-6 py-3 bg-white text-indigo-900 font-semibold rounded-lg shadow hover:bg-gray-100 transition">Cari Fasilitas</a>
                <a href="#lapor" class="px-6 py-3 bg-indigo-700 text-white font-semibold rounded-lg hover:bg-indigo-600 transition">Laporkan Insiden</a>
            </div>
        </div>
    </section>

    <!-- Content / Katalog Fasilitas Mockup -->
    <main id="katalog" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Daftar Fasilitas Populer</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Card 1 -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                <div class="h-40 bg-gray-200 flex items-center justify-center text-gray-400">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <div class="p-5">
                    <span class="text-xs font-semibold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2 py-1 rounded">Auditorium</span>
                    <h3 class="text-lg font-bold text-gray-900 mt-2">Aula Utama Gedung A</h3>
                    <p class="text-sm text-gray-500 mt-1">Kapasitas: 300 Orang • AC, Sound System, Proyektor</p>
                    <div class="mt-4 flex items-center justify-between">
                        <span class="text-xs text-green-600 font-medium bg-green-50 px-2 py-1 rounded-full">Tersedia</span>
                        <a href="#" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800">Pinjam &rarr;</a>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                <div class="h-40 bg-gray-200 flex items-center justify-center text-gray-400">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <div class="p-5">
                    <span class="text-xs font-semibold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2 py-1 rounded">Laboratorium</span>
                    <h3 class="text-lg font-bold text-gray-900 mt-2">Lab Komputer 3</h3>
                    <p class="text-sm text-gray-500 mt-1">Kapasitas: 40 Unit PC • High-Spec, LAN, AC</p>
                    <div class="mt-4 flex items-center justify-between">
                        <span class="text-xs text-green-600 font-medium bg-green-50 px-2 py-1 rounded-full">Tersedia</span>
                        <a href="#" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800">Pinjam &rarr;</a>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                <div class="h-40 bg-gray-200 flex items-center justify-center text-gray-400">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
                </div>
                <div class="p-5">
                    <span class="text-xs font-semibold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2 py-1 rounded">Lapangan</span>
                    <h3 class="text-lg font-bold text-gray-900 mt-2">Lapangan Olahraga Indoor</h3>
                    <p class="text-sm text-gray-500 mt-1">Fasilitas: Basket, Futsal, Badminton</p>
                    <div class="mt-4 flex items-center justify-between">
                        <span class="text-xs text-yellow-600 font-medium bg-yellow-50 px-2 py-1 rounded-full">Maintenance</span>
                        <span class="text-sm font-medium text-gray-400">Tidak Tersedia</span>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-6 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center text-sm text-gray-500">
            &copy; 2026 Tim Proyek PPK. All rights reserved.
        </div>
    </footer>

</body>
</html>