<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Rekap Okupansi & Laporan Kerusakan') }}</h2>
            <a href="{{ route('admin.recap.export') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 rounded-lg font-semibold text-sm text-white shadow-sm hover:bg-indigo-500">
                {{ __('Export CSV') }}
            </a>
        </div>
    </x-slot>

    <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-900/5 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Nama Fasilitas</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Tipe</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Lokasi</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Status</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-500">Jumlah Reservasi</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-500">Jumlah Laporan Kerusakan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($facilities as $facility)
                    <tr>
                        <td class="px-4 py-3 text-gray-900">{{ $facility->nama_fasilitas }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $facility->tipe }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $facility->lokasi }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ ucfirst(str_replace('_', ' ', $facility->status)) }}</td>
                        <td class="px-4 py-3 text-right text-gray-900">{{ $facility->reservations_count }}</td>
                        <td class="px-4 py-3 text-right text-gray-900">{{ $facility->reports_count }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">Belum ada data fasilitas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>
