<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <p class="text-xs font-semibold tracking-wider text-teal-600 uppercase">Analitik &amp; Laporan</p>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">Rekap Okupansi &amp; Laporan Kerusakan</h1>
                <p class="text-gray-500 mt-1">Ringkasan pemakaian dan frekuensi kerusakan seluruh fasilitas kampus.</p>
            </div>

            <!-- Export Dropdown -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" @keydown.escape="open = false"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-teal-600 rounded-lg font-semibold text-sm text-white shadow-sm hover:bg-teal-700 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                    <span>Ekspor Rekap</span>
                    <svg class="w-3.5 h-3.5 ml-0.5 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                </button>

                <div x-show="open" x-transition @click.outside="open = false"
                    class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 py-1.5 z-50 divide-y divide-gray-50"
                    style="display:none;">
                    <a href="{{ route('admin.recap.export') }}"
                        class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-gray-700 hover:bg-gray-50 hover:text-teal-700 transition">
                        <svg class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" /></svg>
                        Format CSV (.csv)
                    </a>
                    <a href="{{ route('admin.recap.export-excel') }}"
                        class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-50 transition">
                        <svg class="w-4 h-4 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h1.5C5.496 19.5 6 18.996 6 18.375m-3.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125h-1.5m1.5-1.125c0 .621-.504 1.125-1.125 1.125M18 18.375v1.5c0 .621-.504 1.125-1.125 1.125h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5m4.125 0c0 .621-.504 1.125-1.125 1.125h-1.5c-.621 0-1.125-.504-1.125-1.125M15.75 9.75H3.375" /></svg>
                        Format Excel (.xlsx)
                    </a>
                    <a href="{{ route('admin.recap.export-pdf') }}"
                        class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-rose-700 hover:bg-rose-50 transition">
                        <svg class="w-4 h-4 text-rose-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                        Format PDF (.pdf)
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-100 text-sm">
            <thead class="bg-gray-50 text-gray-600 text-xs font-semibold uppercase tracking-wider">
                <tr>
                    <th class="px-5 py-3.5 text-left">Nama Fasilitas</th>
                    <th class="px-5 py-3.5 text-left">Tipe</th>
                    <th class="px-5 py-3.5 text-left">Lokasi</th>
                    <th class="px-5 py-3.5 text-left">Status</th>
                    <th class="px-5 py-3.5 text-right">Reservasi</th>
                    <th class="px-5 py-3.5 text-right">Laporan Kerusakan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($facilities as $facility)
                    <tr class="hover:bg-gray-50/60 transition">
                        <td class="px-5 py-3.5 font-bold text-gray-900">{{ $facility->nama_fasilitas }}</td>
                        <td class="px-5 py-3.5 text-gray-600 text-xs font-medium">{{ $facility->tipe }}</td>
                        <td class="px-5 py-3.5 text-gray-500 text-xs">{{ $facility->lokasi }}</td>
                        <td class="px-5 py-3.5">
                            @if($facility->status === 'aktif')
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">Aktif</span>
                            @elseif($facility->status === 'dalam_perbaikan')
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">Dalam Perbaikan</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <span class="inline-flex items-center justify-center min-w-7 px-2.5 py-0.5 rounded-full text-xs font-bold bg-teal-100 text-teal-800">{{ $facility->reservations_count }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <span class="inline-flex items-center justify-center min-w-7 px-2.5 py-0.5 rounded-full text-xs font-bold {{ $facility->reports_count > 0 ? 'bg-rose-100 text-rose-700' : 'bg-gray-100 text-gray-500' }}">{{ $facility->reports_count }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center text-gray-500">Belum ada data fasilitas tercatat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>
