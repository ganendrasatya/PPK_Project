<x-catalog-layout>
    <div class="max-w-6xl mx-auto px-4 pt-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <span class="text-teal-600 uppercase text-sm font-semibold tracking-wider">PORTAL MAHASISWA & SIVITAS</span>
                <h1 class="text-3xl font-bold mt-1 text-gray-900">Reservasi Saya</h1>
                <p class="text-gray-500 mt-1">Riwayat, pantauan verifikasi, dan status pengajuan peminjaman fasilitas Anda.</p>
            </div>
            <div>
                <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-2 bg-teal-600 text-white px-5 py-2.5 rounded-lg font-medium hover:bg-teal-700 transition-colors shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Buat Reservasi Baru
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8">
            <!-- Total -->
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="text-orange-500 text-sm font-medium uppercase tracking-wide">Total Reservasi</div>
                <div class="text-4xl font-bold mt-2 text-gray-900">{{ $stats->total ?? 0 }}</div>
                <div class="text-xs text-gray-400 mt-2 font-medium">Seluruh riwayat pengajuan peminjaman</div>
            </div>
            <!-- Active -->
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="text-green-500 text-sm font-medium uppercase tracking-wide">Reservasi Aktif & Terjadwal</div>
                <div class="text-4xl font-bold mt-2 text-gray-900">{{ $stats->active ?? 0 }}</div>
                <div class="text-xs text-gray-400 mt-2 font-medium">Kegiatan mendatang &middot; <span class="text-gray-600">{{ $stats->pending ?? 0 }} menunggu verifikasi</span></div>
            </div>
            <!-- Approval Rate -->
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm flex items-center justify-between">
                <div>
                    <div class="text-purple-500 text-sm font-medium uppercase tracking-wide">Tingkat Persetujuan</div>
                    <div class="text-xs text-gray-400 mt-8 font-medium">Tingkat lolos verifikasi petugas</div>
                </div>
                <div class="relative w-16 h-16 mt-2 shrink-0">
                    <svg class="w-16 h-16 -rotate-90 transform" viewBox="0 0 36 36">
                        <!-- Background Circle -->
                        <path class="text-gray-100" stroke="currentColor" stroke-width="3.5" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                        <!-- Foreground Circle -->
                        @php $rate = isset($stats->approval_rate) && $stats->approval_rate > 0 ? $stats->approval_rate : 0; @endphp
                        @if($rate > 0)
                            <path class="text-teal-600" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" fill="none" stroke-dasharray="{{ $rate }}, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                        @endif
                    </svg>
                    <span class="absolute inset-0 flex items-center justify-center text-sm font-bold text-gray-700">
                        {{ $rate > 0 ? round($rate) . '%' : '—' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Filter & Search -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mt-10">
            <div class="flex flex-wrap gap-2">
                @php
                    $tabs = [
                        ['val' => null, 'label' => 'Semua'],
                        ['val' => 'pending', 'label' => 'Menunggu'],
                        ['val' => 'approved', 'label' => 'Disetujui'],
                        ['val' => 'rejected', 'label' => 'Ditolak'],
                        ['val' => 'cancelled', 'label' => 'Dibatalkan'],
                    ];
                @endphp
                @foreach($tabs as $tab)
                    @php $isActive = $status === $tab['val']; @endphp
                    <a href="{{ route('reservations.index', array_filter(['status' => $tab['val'], 'search' => $search])) }}" 
                       class="{{ $isActive ? 'bg-gray-800 text-white shadow-sm' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }} rounded-full px-4 py-1.5 text-sm font-medium transition-colors">
                        {{ $tab['label'] }}
                    </a>
                @endforeach
            </div>
            
            <form method="GET" action="{{ route('reservations.index') }}" class="w-full lg:w-1/3">
                @if($status)
                    <input type="hidden" name="status" value="{{ $status }}">
                @endif
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
                    </div>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari fasilitas, ID reservasi..." class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500 text-sm shadow-sm">
                </div>
            </form>
        </div>

        <!-- Reservation List -->
        <div class="mt-6 space-y-4">
            @forelse($reservations as $reservation)
                <div class="bg-white rounded-xl border border-gray-200 p-4 flex flex-col md:flex-row gap-5 shadow-sm hover:shadow-md transition-shadow">
                    <!-- Image -->
                    <div class="shrink-0">
                        @if($reservation->facility && $reservation->facility->image_path)
                            <img src="{{ Storage::url($reservation->facility->image_path) }}" class="w-full md:w-32 h-32 md:h-24 rounded-lg object-cover border border-gray-100">
                        @else
                            <div class="w-full md:w-32 h-32 md:h-24 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 border border-gray-200">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                        @endif
                    </div>

                    <!-- Details -->
                    <div class="flex-1 flex flex-col justify-center">
                        <div class="flex flex-wrap items-center gap-2 mb-1.5">
                            <span class="bg-gray-100 text-gray-700 text-xs font-semibold px-2 py-0.5 rounded border border-gray-200">
                                #RSV-{{ str_pad($reservation->id, 5, '0', STR_PAD_LEFT) }}
                            </span>
                            
                            @if($reservation->status === 'pending')
                                <span class="bg-yellow-100 text-yellow-800 border border-yellow-200 text-xs font-semibold px-2.5 py-0.5 rounded-full flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span> Menunggu Persetujuan
                                </span>
                            @elseif($reservation->status === 'approved')
                                <span class="bg-green-100 text-green-800 border border-green-200 text-xs font-semibold px-2.5 py-0.5 rounded-full flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Disetujui
                                </span>
                            @elseif($reservation->status === 'rejected')
                                <span class="bg-red-100 text-red-800 border border-red-200 text-xs font-semibold px-2.5 py-0.5 rounded-full flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Ditolak
                                </span>
                            @else
                                <span class="bg-gray-100 text-gray-600 border border-gray-200 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                    Dibatalkan
                                </span>
                            @endif
                        </div>
                        
                        <h3 class="font-bold text-gray-900 text-lg">{{ $reservation->facility->nama_fasilitas ?? 'Fasilitas Tidak Diketahui' }}</h3>
                        
                        <div class="flex flex-wrap gap-4 text-sm text-gray-600 mt-2 font-medium">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                {{ \Carbon\Carbon::parse($reservation->date ?? $reservation->start_time)->format('Y-m-d') }}
                            </span>
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }} WIB
                            </span>
                        </div>
                        
                        <p class="text-sm text-gray-500 mt-2 bg-gray-50 p-2 rounded border border-gray-100"><span class="font-medium text-gray-700">Keperluan:</span> {{ $reservation->purpose }}</p>
                    </div>

                    <!-- Action -->
                    <div class="md:self-end mt-2 md:mt-0 pt-3 md:pt-0 border-t md:border-0 border-gray-100">
                        @if($reservation->status === 'pending')
                            <form method="POST" action="{{ route('reservations.cancel', $reservation) }}" class="inline-block">
                                @csrf
                                <button type="submit" onclick="return confirm('Yakin ingin membatalkan pengajuan ini?')" class="text-gray-500 hover:text-red-600 text-sm font-medium transition-colors flex items-center gap-1.5 px-3 py-1.5 rounded-lg hover:bg-red-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    Batalkan Pengajuan
                                </button>
                            </form>
                        @endif
                        @if($reservation->status === 'approved')
                             <a href="#" class="text-teal-600 hover:text-teal-800 text-sm font-medium transition-colors flex items-center gap-1.5 px-3 py-1.5 rounded-lg hover:bg-teal-50">
                                Unduh Bukti Persetujuan
                             </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-16 bg-white rounded-xl border border-dashed border-gray-300">
                    <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <h3 class="text-lg font-medium text-gray-900">Belum ada reservasi</h3>
                    <p class="mt-1 text-sm text-gray-500 mb-4">Mulai dengan memilih fasilitas dari katalog.</p>
                    <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-2 bg-teal-600 text-white px-5 py-2 rounded-lg font-medium hover:bg-teal-700 transition-colors">
                        Lihat Katalog Fasilitas
                    </a>
                </div>
            @endforelse
        </div>

        <div class="mt-6 pb-6">
            {{ $reservations->appends(request()->query())->links() }}
        </div>

        <!-- Info Banner -->
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-5 mb-10 flex gap-4 shadow-sm items-start">
            <div class="shrink-0 text-amber-500 bg-amber-100 p-2 rounded-full">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <div>
                <h4 class="font-semibold text-amber-900">Butuh persetujuan darurat atau izin dispensasi kegiatan?</h4>
                <p class="text-sm text-amber-800 mt-1 leading-relaxed">Ajukan melalui form reservasi seperti biasa, lalu hubungi petugas kampus untuk mempercepat verifikasi. Pantau statusnya langsung dari halaman ini.</p>
            </div>
        </div>
    </div>
</x-catalog-layout>
