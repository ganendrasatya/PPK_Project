@php
    $statusMeta = [
        'aktif' => ['label' => 'Aktif', 'badge' => 'bg-emerald-100 text-emerald-700', 'icon' => 'text-emerald-600 bg-emerald-50'],
        'nonaktif' => ['label' => 'Nonaktif', 'badge' => 'bg-slate-200 text-slate-600', 'icon' => 'text-slate-500 bg-slate-100'],
        'dalam_perbaikan' => ['label' => 'Dalam Perbaikan', 'badge' => 'bg-amber-100 text-amber-700', 'icon' => 'text-amber-600 bg-amber-50'],
    ];
@endphp

<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <p class="text-xs font-semibold tracking-wide text-emerald-700 uppercase">Data Master</p>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 mt-1">Kelola Fasilitas</h1>
                <p class="text-slate-500 mt-1">Tambah, ubah, atau nonaktifkan fasilitas kampus yang bisa direservasi.</p>
            </div>
            <a href="{{ route('admin.facilities.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-emerald-800 rounded-xl font-semibold text-sm text-white shadow-sm hover:bg-emerald-900 transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Tambah Fasilitas
            </a>
        </div>
    </x-slot>

    @if (session('status'))
        <div class="mb-4 rounded-xl bg-emerald-50 px-4 py-3 text-sm text-emerald-700 ring-1 ring-emerald-200">
            {{ session('status') }}
        </div>
    @endif

    <div class="flex flex-wrap items-center gap-2 mb-6">
        @foreach (['' => ['Semua', $statusCounts['semua']], 'aktif' => ['Aktif', $statusCounts['aktif']], 'nonaktif' => ['Nonaktif', $statusCounts['nonaktif']], 'dalam_perbaikan' => ['Dalam Perbaikan', $statusCounts['dalam_perbaikan']]] as $value => [$label, $count])
            <a href="{{ route('admin.facilities.index', $value ? ['status' => $value] : []) }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-sm font-medium transition {{ request('status', '') === $value ? 'bg-emerald-800 text-white' : 'bg-white text-slate-600 ring-1 ring-slate-200 hover:bg-slate-50' }}">
                {{ $label }}
                <span class="text-xs {{ request('status', '') === $value ? 'text-emerald-200' : 'text-slate-400' }}">{{ $count }}</span>
            </a>
        @endforeach
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse ($facilities as $facility)
            @php($meta = $statusMeta[$facility->status])
            <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-900/5 p-5 flex flex-col">
                <div class="flex items-start justify-between gap-3">
                    <span class="flex items-center justify-center w-11 h-11 rounded-xl shrink-0 {{ $meta['icon'] }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6" /></svg>
                    </span>
                    <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $meta['badge'] }}">{{ $meta['label'] }}</span>
                </div>

                <h3 class="mt-3 font-semibold text-slate-900">{{ $facility->nama_fasilitas }}</h3>
                <p class="text-xs text-emerald-700 font-medium uppercase tracking-wide mt-0.5">{{ $facility->tipe }}</p>

                <div class="mt-3 space-y-1.5 text-sm text-slate-500">
                    <p class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                        {{ $facility->lokasi }}
                    </p>
                    <p class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                        Kapasitas {{ $facility->kapasitas }} orang
                    </p>
                </div>

                @if ($facility->deskripsi)
                    <p class="mt-3 text-sm text-slate-500 line-clamp-2">{{ $facility->deskripsi }}</p>
                @endif

                <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between gap-2">
                    <form method="POST" action="{{ route('admin.facilities.update-status', $facility) }}" class="w-36">
                        @csrf
                        @method('PATCH')
                        <x-select-dropdown name="status" :nullable="false" :autosubmit="true"
                            :options="['aktif' => 'Aktif', 'nonaktif' => 'Nonaktif', 'dalam_perbaikan' => 'Dalam Perbaikan']"
                            :selected="$facility->status" />
                    </form>

                    <div class="flex items-center gap-3 text-sm">
                        <a href="{{ route('admin.facilities.edit', $facility) }}" class="font-medium text-emerald-700 hover:text-emerald-900">Edit</a>
                        <form method="POST" action="{{ route('admin.facilities.destroy', $facility) }}" onsubmit="return confirm('Hapus fasilitas ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="font-medium text-rose-600 hover:text-rose-800">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl ring-1 ring-slate-900/5 p-10 text-center text-slate-500">
                Belum ada data fasilitas untuk filter ini.
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $facilities->links() }}
    </div>
</x-admin-layout>
