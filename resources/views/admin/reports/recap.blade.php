<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <p class="text-xs font-semibold tracking-wide text-emerald-700 uppercase">Analitik</p>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 mt-1">Rekap Okupansi & Laporan Kerusakan</h1>
                <p class="text-slate-500 mt-1">Ringkasan pemakaian dan frekuensi kerusakan per fasilitas.</p>
            </div>
            <a href="{{ route('admin.recap.export') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-emerald-800 rounded-xl font-semibold text-sm text-white shadow-sm hover:bg-emerald-900 transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                Export CSV
            </a>
        </div>
    </x-slot>

    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-900/5 overflow-hidden">
        <table class="min-w-full divide-y divide-slate-100 text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-5 py-3 text-left font-medium text-slate-500">Nama Fasilitas</th>
                    <th class="px-5 py-3 text-left font-medium text-slate-500">Tipe</th>
                    <th class="px-5 py-3 text-left font-medium text-slate-500">Lokasi</th>
                    <th class="px-5 py-3 text-left font-medium text-slate-500">Status</th>
                    <th class="px-5 py-3 text-right font-medium text-slate-500">Reservasi</th>
                    <th class="px-5 py-3 text-right font-medium text-slate-500">Laporan Kerusakan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($facilities as $facility)
                    <tr class="hover:bg-slate-50/60">
                        <td class="px-5 py-3 font-medium text-slate-900">{{ $facility->nama_fasilitas }}</td>
                        <td class="px-5 py-3 text-slate-600">{{ $facility->tipe }}</td>
                        <td class="px-5 py-3 text-slate-600">{{ $facility->lokasi }}</td>
                        <td class="px-5 py-3 text-slate-600">{{ ucfirst(str_replace('_', ' ', $facility->status)) }}</td>
                        <td class="px-5 py-3 text-right">
                            <span class="inline-flex items-center justify-center min-w-7 px-2 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700">{{ $facility->reservations_count }}</span>
                        </td>
                        <td class="px-5 py-3 text-right">
                            <span class="inline-flex items-center justify-center min-w-7 px-2 py-0.5 rounded-full text-xs font-semibold {{ $facility->reports_count > 0 ? 'bg-rose-100 text-rose-700' : 'bg-slate-100 text-slate-500' }}">{{ $facility->reports_count }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-slate-500">Belum ada data fasilitas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>
