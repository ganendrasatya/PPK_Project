<x-admin-layout>
    <x-slot name="header">
        <p class="text-xs font-semibold tracking-wide text-emerald-700 uppercase flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            Panel Admin
        </p>
        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 mt-1">Dashboard</h1>
        <p class="text-slate-500 mt-1">Ringkasan operasional reservasi, laporan, dan fasilitas kampus.</p>
    </x-slot>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-900/5 p-5 flex items-start justify-between">
            <div>
                <p class="text-sm text-slate-500">Total Fasilitas</p>
                <p class="mt-1 text-3xl font-bold text-slate-900">{{ $totalFacilities }}</p>
                <p class="text-xs text-slate-400 mt-1">Seluruh fasilitas terdaftar</p>
            </div>
            <span class="flex items-center justify-center w-11 h-11 rounded-xl bg-sky-50 text-sky-600 shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6" /></svg>
            </span>
        </div>

        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-900/5 p-5 flex items-start justify-between">
            <div>
                <p class="text-sm text-slate-500">Akun Menunggu Verifikasi</p>
                <p class="mt-1 text-3xl font-bold text-slate-900">{{ $pendingUsers }}</p>
                @if ($pendingUsers > 0)
                    <a href="{{ route('admin.users.pending') }}" class="text-xs font-medium text-amber-700 hover:text-amber-900 mt-1 inline-block">Proses sekarang &rarr;</a>
                @else
                    <p class="text-xs text-slate-400 mt-1">Tidak ada antrean</p>
                @endif
            </div>
            <span class="flex items-center justify-center w-11 h-11 rounded-xl bg-amber-50 text-amber-600 shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </span>
        </div>

        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-900/5 p-5 flex items-start justify-between">
            <div>
                <p class="text-sm text-slate-500">Reservasi Pending</p>
                <p class="mt-1 text-3xl font-bold text-slate-900">{{ $pendingReservations }}</p>
                <p class="text-xs text-slate-400 mt-1">Menunggu diproses petugas</p>
            </div>
            <span class="flex items-center justify-center w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
            </span>
        </div>

        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-900/5 p-5 flex items-start justify-between">
            <div>
                <p class="text-sm text-slate-500">Laporan Kerusakan Terbuka</p>
                <p class="mt-1 text-3xl font-bold text-slate-900">{{ $openReports }}</p>
                <p class="text-xs text-slate-400 mt-1">Status baru / diproses</p>
            </div>
            <span class="flex items-center justify-center w-11 h-11 rounded-xl bg-rose-50 text-rose-600 shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9.303 3.376c.866 1.5-.217 3.374-1.948 3.374H4.645c-1.73 0-2.813-1.874-1.948-3.374L10.652 4.5c.866-1.5 3.032-1.5 3.898 0l7.753 11.25zM12 15.75h.007v.008H12v-.008z" /></svg>
            </span>
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('admin.facilities.create') }}" class="bg-emerald-800 text-white rounded-2xl p-5 hover:bg-emerald-900 transition shadow-sm">
            <p class="font-semibold">+ Tambah Fasilitas</p>
            <p class="text-sm text-emerald-100 mt-1">Daftarkan ruangan, lab, atau lapangan baru.</p>
        </a>
        <a href="{{ route('admin.users.pending') }}" class="bg-white rounded-2xl p-5 ring-1 ring-slate-900/5 hover:ring-emerald-300 transition shadow-sm">
            <p class="font-semibold text-slate-800">Verifikasi Akun</p>
            <p class="text-sm text-slate-500 mt-1">Tinjau registrasi mandiri pengguna baru.</p>
        </a>
        <a href="{{ route('admin.recap.index') }}" class="bg-white rounded-2xl p-5 ring-1 ring-slate-900/5 hover:ring-emerald-300 transition shadow-sm">
            <p class="font-semibold text-slate-800">Rekap & Export</p>
            <p class="text-sm text-slate-500 mt-1">Lihat okupansi fasilitas & unduh CSV.</p>
        </a>
    </div>
</x-admin-layout>
