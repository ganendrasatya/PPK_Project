<x-admin-layout>
    <x-slot name="header">
        <p class="text-xs font-semibold tracking-wider text-teal-600 uppercase flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            Panel Administrator
        </p>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">Dashboard Admin</h1>
        <p class="text-gray-500 mt-1">Ringkasan operasional reservasi, laporan kerusakan, dan fasilitas kampus.</p>
    </x-slot>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm flex items-start justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Fasilitas</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ $totalFacilities }}</p>
                <p class="text-xs text-gray-400 mt-1 font-medium">Seluruh fasilitas terdaftar</p>
            </div>
            <span class="flex items-center justify-center w-11 h-11 rounded-xl bg-teal-50 text-teal-600 shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6" /></svg>
            </span>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm flex items-start justify-between">
            <div>
                <p class="text-xs font-semibold text-amber-600 uppercase tracking-wide">Akun Menunggu Verifikasi</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ $pendingUsers }}</p>
                @if ($pendingUsers > 0)
                    <a href="{{ route('admin.users.pending') }}" class="text-xs font-semibold text-amber-700 hover:text-amber-900 mt-1 inline-block">Proses sekarang &rarr;</a>
                @else
                    <p class="text-xs text-gray-400 mt-1 font-medium">Tidak ada antrean</p>
                @endif
            </div>
            <span class="flex items-center justify-center w-11 h-11 rounded-xl bg-amber-50 text-amber-600 shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </span>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm flex items-start justify-between">
            <div>
                <p class="text-xs font-semibold text-indigo-600 uppercase tracking-wide">Reservasi Pending</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ $pendingReservations }}</p>
                <p class="text-xs text-gray-400 mt-1 font-medium">Menunggu diproses petugas</p>
            </div>
            <span class="flex items-center justify-center w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
            </span>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm flex items-start justify-between">
            <div>
                <p class="text-xs font-semibold text-rose-500 uppercase tracking-wide">Laporan Terbuka</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ $openReports }}</p>
                <p class="text-xs text-gray-400 mt-1 font-medium">Status baru / diproses</p>
            </div>
            <span class="flex items-center justify-center w-11 h-11 rounded-xl bg-rose-50 text-rose-600 shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9.303 3.376c.866 1.5-.217 3.374-1.948 3.374H4.645c-1.73 0-2.813-1.874-1.948-3.374L10.652 4.5c.866-1.5 3.032-1.5 3.898 0l7.753 11.25zM12 15.75h.007v.008H12v-.008z" /></svg>
            </span>
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('admin.facilities.create') }}" class="bg-teal-600 text-white rounded-xl p-5 hover:bg-teal-700 transition shadow-sm group">
            <p class="font-bold flex items-center justify-between">
                <span>+ Tambah Fasilitas Baru</span>
                <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
            </p>
            <p class="text-sm text-teal-100 mt-1">Daftarkan ruangan, laboratorium, atau lapangan baru.</p>
        </a>
        <a href="{{ route('admin.users.pending') }}" class="bg-white rounded-xl p-5 border border-gray-200 hover:border-teal-400 hover:shadow-sm transition group">
            <p class="font-bold text-gray-900 flex items-center justify-between">
                <span>Verifikasi Akun Pengguna</span>
                <span class="text-gray-400 group-hover:text-teal-600 group-hover:translate-x-1 transition">&rarr;</span>
            </p>
            <p class="text-sm text-gray-500 mt-1">Tinjau registrasi mandiri pengguna baru sebelum login.</p>
        </a>
        <a href="{{ route('admin.recap.index') }}" class="bg-white rounded-xl p-5 border border-gray-200 hover:border-teal-400 hover:shadow-sm transition group">
            <p class="font-bold text-gray-900 flex items-center justify-between">
                <span>Rekap &amp; Ekspor Data</span>
                <span class="text-gray-400 group-hover:text-teal-600 group-hover:translate-x-1 transition">&rarr;</span>
            </p>
            <p class="text-sm text-gray-500 mt-1">Lihat okupansi fasilitas &amp; unduh CSV, Excel, atau PDF.</p>
        </a>
    </div>
</x-admin-layout>
