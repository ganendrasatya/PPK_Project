<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Admin Dashboard') }}</h2>
    </x-slot>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-900/5 p-5">
            <p class="text-sm text-gray-500">Total Fasilitas</p>
            <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $totalFacilities }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-900/5 p-5">
            <p class="text-sm text-gray-500">Akun Menunggu Verifikasi</p>
            <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $pendingUsers }}</p>
            @if ($pendingUsers > 0)
                <a href="{{ route('admin.users.pending') }}" class="mt-2 inline-block text-sm text-indigo-600 hover:text-indigo-800">Proses sekarang &rarr;</a>
            @endif
        </div>
        <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-900/5 p-5">
            <p class="text-sm text-gray-500">Reservasi Pending</p>
            <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $pendingReservations }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-900/5 p-5">
            <p class="text-sm text-gray-500">Laporan Kerusakan Terbuka</p>
            <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $openReports }}</p>
        </div>
    </div>
</x-admin-layout>
