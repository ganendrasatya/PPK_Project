@php
    $statusMeta = [
        'aktif' => ['label' => 'Aktif', 'badge' => 'bg-emerald-100 text-emerald-700'],
        'nonaktif' => ['label' => 'Nonaktif', 'badge' => 'bg-gray-100 text-gray-600'],
        'dalam_perbaikan' => ['label' => 'Dalam Perbaikan', 'badge' => 'bg-amber-100 text-amber-700'],
    ];
@endphp

<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <p class="text-xs font-semibold tracking-wider text-teal-600 uppercase">Data Master</p>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">Kelola Fasilitas</h1>
                <p class="text-gray-500 mt-1">Tambah, ubah, atau atur ketersediaan operasional fasilitas kampus.</p>
            </div>
            <a href="{{ route('admin.facilities.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-teal-600 rounded-lg font-semibold text-sm text-white shadow-sm hover:bg-teal-700 transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Tambah Fasilitas
            </a>
        </div>
    </x-slot>

    @if (session('status'))
        <div class="mb-4 rounded-xl bg-emerald-50 px-4 py-3 text-sm text-emerald-700 border border-emerald-200">
            {{ session('status') }}
        </div>
    @endif

    <!-- Filter Tabs -->
    <div class="flex flex-wrap items-center gap-2 mb-6">
        @foreach (['' => ['Semua', $statusCounts['semua']], 'aktif' => ['Aktif', $statusCounts['aktif']], 'nonaktif' => ['Nonaktif', $statusCounts['nonaktif']], 'dalam_perbaikan' => ['Dalam Perbaikan', $statusCounts['dalam_perbaikan']]] as $value => [$label, $count])
            <a href="{{ route('admin.facilities.index', $value ? ['status' => $value] : []) }}"
               class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-semibold transition-colors {{ request('status', '') === $value ? 'bg-teal-700 text-white shadow-sm' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
                {{ $label }}
                <span class="text-xs {{ request('status', '') === $value ? 'text-teal-200' : 'text-gray-400' }}">{{ $count }}</span>
            </a>
        @endforeach
    </div>

    <!-- Facility Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($facilities as $facility)
            @php($meta = $statusMeta[$facility->status] ?? ['label' => ucfirst($facility->status), 'badge' => 'bg-gray-100 text-gray-600'])
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden flex flex-col hover:shadow-md transition">
                <!-- Facility Image / Thumbnail -->
                <div class="relative h-44 bg-gray-100 overflow-hidden">
                    @if ($facility->image_path)
                        <img src="{{ Storage::url($facility->image_path) }}" alt="{{ $facility->nama_fasilitas }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-teal-50 to-gray-100 text-teal-600">
                            <svg class="w-12 h-12 text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6" />
                            </svg>
                            <span class="text-xs text-teal-700 font-medium mt-1">Belum ada foto</span>
                        </div>
                    @endif
                    <!-- Status Badge Overlay -->
                    <div class="absolute top-3 right-3">
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold shadow-sm {{ $meta['badge'] }}">
                            ● {{ $meta['label'] }}
                        </span>
                    </div>
                    <!-- Type Badge Overlay -->
                    <div class="absolute bottom-3 left-3">
                        <span class="px-2 py-0.5 rounded-md text-xs font-semibold bg-black/60 text-white backdrop-blur-sm">
                            {{ $facility->tipe }}
                        </span>
                    </div>
                </div>

                <div class="p-5 flex-1 flex flex-col justify-between">
                    <div>
                        <h3 class="font-bold text-lg text-gray-900 leading-snug">{{ $facility->nama_fasilitas }}</h3>

                        <div class="mt-3 space-y-1.5 text-xs text-gray-500">
                            <p class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-teal-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                                <span>{{ $facility->lokasi }}</span>
                            </p>
                            <p class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-teal-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                                <span>Kapasitas {{ $facility->kapasitas }} orang</span>
                            </p>
                            @if ($facility->jam_buka && $facility->jam_tutup)
                                <p class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-teal-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <span>Operasional: {{ \Carbon\Carbon::parse($facility->jam_buka)->format('H:i') }} &ndash; {{ \Carbon\Carbon::parse($facility->jam_tutup)->format('H:i') }} WIB</span>
                                </p>
                            @endif
                        </div>

                        @if ($facility->deskripsi)
                            <p class="mt-3 text-xs text-gray-500 line-clamp-2 leading-relaxed">{{ $facility->deskripsi }}</p>
                        @endif
                    </div>

                    <!-- Bottom Status Switch & Actions -->
                    <div class="mt-5 pt-4 border-t border-gray-100 flex items-center justify-between gap-2">
                        <form method="POST" action="{{ route('petugas.facilities.update-status', $facility) }}" class="w-36">
                            @csrf
                            @method('PATCH')
                            <x-select-dropdown name="status" :nullable="false" :autosubmit="true"
                                :options="['aktif' => 'Aktif', 'nonaktif' => 'Nonaktif', 'dalam_perbaikan' => 'Dalam Perbaikan']"
                                :selected="$facility->status" />
                        </form>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.facilities.edit', $facility) }}"
                               class="inline-flex items-center px-3 py-1.5 bg-teal-50 border border-teal-200 rounded-lg text-xs font-semibold text-teal-700 hover:bg-teal-100 transition">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.facilities.destroy', $facility) }}" onsubmit="return confirm('Hapus fasilitas ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center px-3 py-1.5 bg-white border border-rose-200 rounded-lg text-xs font-semibold text-rose-600 hover:bg-rose-50 transition">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-xl border border-gray-200 p-10 text-center text-gray-500">
                Belum ada data fasilitas untuk filter ini.
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $facilities->links() }}
    </div>
</x-admin-layout>
