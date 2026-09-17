<x-catalog-layout>
    <!-- A. Hero Section -->
    <section class="bg-gradient-to-br from-teal-800 to-teal-600 text-white rounded-b-[2rem] md:rounded-b-[3xl] overflow-hidden py-16 px-8 relative">
        <div class="max-w-6xl mx-auto relative z-10">
            <span class="inline-block bg-teal-900/50 text-teal-100 rounded-full px-4 py-1.5 text-sm font-medium mb-6">
                🏢 Sistem Peminjaman Fasilitas Kampus Terpadu
            </span>
            <h1 class="text-4xl md:text-5xl font-bold max-w-2xl leading-tight">
                Reservasi fasilitas kampus dalam hitungan menit.
            </h1>
            <p class="max-w-2xl text-teal-100 mt-4 text-lg">
                Jelajahi ketersediaan lapangan, ruang kelas, dan laboratorium modern. Pesan slot 30 menit dengan deteksi bentrok otomatis — tanpa antrean fisik dan birokrasi manual.
            </p>
            <div class="flex flex-wrap gap-3 mt-6">
                <span class="bg-teal-700/50 rounded-full px-4 py-1.5 text-sm font-medium">📋 {{ $facilities->total() }}+ Fasilitas Terdaftar</span>
                <span class="bg-teal-700/50 rounded-full px-4 py-1.5 text-sm font-medium">⏱ Slot 30 Menit (07.00–18.00 WIB)</span>
                <span class="bg-teal-700/50 rounded-full px-4 py-1.5 text-sm font-medium">✅ Persetujuan Petugas Cepat</span>
            </div>
            <div class="flex flex-wrap gap-3 mt-8">
                <a href="#katalog" class="bg-white text-teal-700 font-semibold rounded-lg px-6 py-3 hover:bg-gray-100 transition-colors">
                    Cek Ketersediaan Langsung
                </a>
                <a href="#alur" class="border border-white/50 text-white rounded-lg px-6 py-3 hover:bg-white/10 transition-colors">
                    📋 Panduan Peminjaman
                </a>
            </div>
        </div>
        <!-- Decorative Background Pattern (Optional) -->
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <svg class="absolute right-0 top-0 h-full w-1/2" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none">
                <polygon points="50,0 100,0 100,100 0,100"/>
            </svg>
        </div>
    </section>

    <!-- B. Search & Filter Bar -->
    <section class="max-w-6xl mx-auto px-4 relative z-20 -mt-8">
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <form action="{{ route('catalog.index') }}" method="GET" id="filter-form">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Search -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari fasilitas, ruang, gedung, atau lokasi..." class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500 text-sm">
                    </div>
                    <!-- Date -->
                    <div>
                        <input type="date" name="date" value="{{ $date }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500 text-sm" onchange="document.getElementById('filter-form').submit()">
                    </div>
                    <!-- Session (Decorative) -->
                    <div>
                        <select disabled class="w-full px-3 py-2 border border-gray-200 bg-gray-50 text-gray-500 rounded-lg text-sm cursor-not-allowed">
                            <option>Semua Sesi Jam Operasional</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex flex-col md:flex-row md:items-center justify-between mt-4 gap-4">
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('catalog.index', ['search' => $search, 'date' => $date]) }}" 
                           class="{{ empty($selectedType) ? 'bg-teal-700 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} rounded-full px-4 py-1.5 text-sm font-medium transition-colors">
                            Semua
                        </a>
                        @foreach($types as $type)
                            <a href="{{ route('catalog.index', ['type' => $type, 'search' => $search, 'date' => $date]) }}" 
                               class="{{ $selectedType === $type ? 'bg-teal-700 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} rounded-full px-4 py-1.5 text-sm font-medium transition-colors">
                                {{ $type }}
                            </a>
                        @endforeach
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-500 shrink-0">
                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                        Sistem sinkronisasi slot real-time aktif
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- C. Facility Grid -->
    <section id="katalog" class="max-w-6xl mx-auto px-4 py-12">
        <div class="flex flex-col md:flex-row justify-between items-end mb-6 border-b border-gray-200 pb-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Katalog Fasilitas Siap Pakai</h2>
                <p class="text-gray-500 mt-1">Pilih fasilitas dan atur durasi pemakaian Anda secara mandiri.</p>
            </div>
            <div class="text-sm text-gray-500 mt-4 md:mt-0 font-medium">
                Menampilkan {{ $facilities->total() }} fasilitas
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($facilities as $facility)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow group flex flex-col h-full">
                    <div class="relative h-48 bg-gray-200 overflow-hidden">
                        @if($facility->image_path)
                            <img src="{{ Storage::url($facility->image_path) }}" alt="{{ $facility->nama_fasilitas }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                        @endif

                        <!-- Status Badge -->
                        <div class="absolute top-3 right-3">
                            @if($facility->status === 'aktif')
                                <span class="bg-emerald-500 text-white text-xs font-medium px-2.5 py-1 rounded-full shadow-sm">● Aktif</span>
                            @elseif($facility->status === 'dalam_perbaikan')
                                <span class="bg-amber-500 text-white text-xs font-medium px-2.5 py-1 rounded-full shadow-sm">● Dalam Perbaikan</span>
                            @else
                                <span class="bg-red-500 text-white text-xs font-medium px-2.5 py-1 rounded-full shadow-sm">● Nonaktif</span>
                            @endif
                        </div>

                        <!-- Location Overlay -->
                        <div class="absolute bottom-3 left-3 bg-black/60 backdrop-blur-sm text-white text-xs px-2.5 py-1 rounded-md flex items-center gap-1">
                            📍 {{ $facility->lokasi }}
                        </div>
                    </div>

                    <div class="p-5 flex flex-col flex-grow">
                        <h3 class="font-semibold text-lg text-gray-900 group-hover:text-teal-700 transition-colors">{{ $facility->nama_fasilitas }}</h3>
                        <p class="text-sm text-gray-500 mt-1 line-clamp-2 flex-grow">{{ $facility->deskripsi }}</p>
                        
                        <div class="flex flex-wrap gap-x-4 gap-y-2 mt-4 text-sm text-gray-600 font-medium">
                            <span class="flex items-center gap-1">👥 Kapasitas {{ $facility->kapasitas }} orang</span>
                            <span class="flex items-center gap-1">⏱ Min. 1 jam / Sesi</span>
                        </div>

                        @if($facility->status === 'dalam_perbaikan')
                            <div class="mt-3 bg-amber-50 text-amber-700 text-xs px-3 py-2 rounded-lg border border-amber-100 flex items-start gap-2">
                                ⚠️ Fasilitas sedang dalam pemeliharaan dan tidak dapat dipesan.
                            </div>
                        @endif

                        <hr class="my-4 border-gray-100">

                        <div class="flex items-center justify-between mt-auto">
                            <div>
                                <div class="text-xs text-gray-400 mb-0.5">Status Hari Ini</div>
                                @php
                                    // Mock available count or pass from controller
                                    $availableCount = $facility->available_slots_count ?? 0;
                                @endphp
                                @if($availableCount > 0)
                                    <span class="inline-block bg-emerald-100 text-emerald-700 text-xs font-medium px-2 py-0.5 rounded-full">{{ $availableCount }} Slot Kosong</span>
                                @else
                                    <span class="inline-block bg-red-100 text-red-700 text-xs font-medium px-2 py-0.5 rounded-full">Penuh</span>
                                @endif
                            </div>
                            
                            @if($facility->status === 'aktif')
                                <a href="{{ route('catalog.show', $facility) }}" class="text-teal-600 font-medium text-sm hover:text-teal-800 transition-colors flex items-center gap-1">
                                    Lihat Jadwal <span aria-hidden="true">&rarr;</span>
                                </a>
                            @else
                                <span class="text-gray-400 text-sm font-medium cursor-not-allowed">Tidak Tersedia</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center bg-white rounded-xl border border-dashed border-gray-300">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada fasilitas ditemukan</h3>
                    <p class="mt-1 text-sm text-gray-500">Coba sesuaikan filter atau kata kunci pencarian Anda.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $facilities->appends(request()->query())->links() }}
        </div>
    </section>

    <!-- D. Alur Peminjaman Section -->
    <section id="alur" class="bg-white py-16 mt-8 border-t border-gray-100">
        <div class="max-w-6xl mx-auto px-4">
            <div class="text-center md:text-left">
                <span class="text-teal-600 uppercase text-sm font-semibold tracking-wider">LANGKAH MUDAH</span>
                <h2 class="text-2xl font-bold mt-2 text-gray-900">Alur Peminjaman Fasilitas</h2>
                <p class="text-gray-500 mt-1">Proses transparan dan terverifikasi untuk seluruh civitas akademika.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-10">
                <!-- Step 1 -->
                <div class="bg-white rounded-xl border border-gray-200 p-6 relative hover:shadow-md transition-shadow">
                    <div class="w-10 h-10 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center font-bold text-lg mb-4">1</div>
                    <h3 class="font-semibold text-gray-900 text-lg">Pilih Fasilitas</h3>
                    <p class="text-sm text-gray-500 mt-2 leading-relaxed">Cari ruangan atau lapangan sesuai jumlah audiens dan peralatan penunjang.</p>
                </div>
                <!-- Step 2 -->
                <div class="bg-white rounded-xl border border-gray-200 p-6 relative hover:shadow-md transition-shadow">
                    <div class="w-10 h-10 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center font-bold text-lg mb-4">2</div>
                    <h3 class="font-semibold text-gray-900 text-lg">Tentukan Slot</h3>
                    <p class="text-sm text-gray-500 mt-2 leading-relaxed">Pilih waktu kelipatan 30 menit. Sistem memvalidasi bebas tabrakan secara real-time.</p>
                </div>
                <!-- Step 3 -->
                <div class="bg-white rounded-xl border border-gray-200 p-6 relative hover:shadow-md transition-shadow">
                    <div class="w-10 h-10 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center font-bold text-lg mb-4">3</div>
                    <h3 class="font-semibold text-gray-900 text-lg">Review Petugas</h3>
                    <p class="text-sm text-gray-500 mt-2 leading-relaxed">Petugas kampus memeriksa kelayakan berkas & agenda maksimal 2 jam kerja.</p>
                </div>
                <!-- Step 4 -->
                <div class="bg-white rounded-xl border border-gray-200 p-6 relative hover:shadow-md transition-shadow">
                    <div class="w-10 h-10 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center font-bold text-lg mb-4">4</div>
                    <h3 class="font-semibold text-gray-900 text-lg">Surat Disetujui</h3>
                    <p class="text-sm text-gray-500 mt-2 leading-relaxed">Surat persetujuan peminjaman diterbitkan dan reservasi dikonfirmasi.</p>
                </div>
            </div>
        </div>
    </section>
</x-catalog-layout>
