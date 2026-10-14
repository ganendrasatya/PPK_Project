<x-catalog-layout>
    <div class="max-w-6xl mx-auto px-4 pt-8">
        <div>
            @if(auth()->user()->isPetugas())
                <span class="text-teal-600 uppercase text-sm font-semibold tracking-wider">PORTAL PETUGAS</span>
                <h1 class="text-3xl font-bold mt-1 text-gray-900">Kelola Laporan Kerusakan</h1>
                <p class="text-gray-500 mt-1">Verifikasi kondisi fasilitas, periksa foto bukti kerusakan, dan tindak lanjuti laporan pengguna.</p>
            @else
                <span class="text-teal-600 uppercase text-sm font-semibold tracking-wider">PORTAL MAHASISWA & SIVITAS</span>
                <h1 class="text-3xl font-bold mt-1 text-gray-900">Laporan & Kerusakan</h1>
                <p class="text-gray-500 mt-1">Laporkan kerusakan fasilitas dan pantau status penanganannya.</p>
            @endif
        </div>
    </div>

    @if(!auth()->user()->isPetugas())
    <div class="max-w-6xl mx-auto px-4 py-8"
         x-data="{
             activeTab: @js($errors->any() || request('action') === 'new' || request('facility_id') || $reports->total() === 0 ? 'form' : 'riwayat')
         }">

        <!-- Tab Header Switcher -->
        <div class="flex items-center gap-2 border-b border-gray-200 pb-px mb-8">
            <button type="button" @click="activeTab = 'riwayat'"
                    class="pb-3 px-4 text-sm font-semibold border-b-2 transition-all flex items-center gap-2"
                    :class="activeTab === 'riwayat' ? 'border-teal-600 text-teal-700 font-bold' : 'border-transparent text-gray-500 hover:text-gray-800'">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                <span>Riwayat Laporan Saya</span>
                <span class="px-2 py-0.5 text-xs rounded-full font-bold"
                      :class="activeTab === 'riwayat' ? 'bg-teal-100 text-teal-800' : 'bg-gray-100 text-gray-600'">
                    {{ $reports->total() }}
                </span>
            </button>

            <button type="button" @click="activeTab = 'form'"
                    class="pb-3 px-4 text-sm font-semibold border-b-2 transition-all flex items-center gap-2"
                    :class="activeTab === 'form' ? 'border-teal-600 text-teal-700 font-bold' : 'border-transparent text-gray-500 hover:text-gray-800'">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                <span>Buat Laporan Baru</span>
                @if($errors->any())
                    <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse" title="Terdapat error validasi"></span>
                @endif
            </button>
        </div>

        <!-- TAB 1: RIWAYAT LAPORAN SAYA (FULL WIDTH CARDS) -->
        <div x-show="activeTab === 'riwayat'" class="space-y-6">
            <!-- Filter Pills -->
            <div class="flex flex-wrap items-center justify-between gap-3 bg-white p-3 rounded-xl border border-gray-200 shadow-sm">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-2">Filter:</span>
                    @php
                        $statusFilters = [
                            null => 'Semua',
                            'baru' => 'Menunggu Validasi',
                            'diproses' => 'Sedang Diproses',
                            'selesai' => 'Selesai',
                            'ditolak' => 'Ditolak',
                        ];
                    @endphp
                    @foreach($statusFilters as $val => $label)
                        <a href="{{ route('reports.index', array_filter(['status' => $val])) }}"
                           class="px-3 py-1 rounded-full text-xs font-semibold transition-colors {{ ($status === $val || (is_null($status) && is_null($val))) ? 'bg-teal-700 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
                <div class="text-xs text-gray-400 font-medium px-2">
                    Menampilkan {{ $reports->count() }} dari {{ $reports->total() }} laporan
                </div>
            </div>

            <!-- List of Reports (Full Width Cards) -->
            <div class="space-y-4">
                @forelse($reports as $report)
                    <div class="bg-white rounded-2xl border border-gray-200 p-5 sm:p-6 flex flex-col md:flex-row gap-5 shadow-sm hover:shadow-md transition-shadow">
                        <!-- Photo Thumbnail -->
                        <div class="shrink-0">
                            @if($report->photo_path)
                                <a href="{{ Storage::url($report->photo_path) }}" target="_blank" title="Klik untuk membuka foto ukuran penuh"
                                   class="group relative block w-full md:w-32 h-44 md:h-32 rounded-xl overflow-hidden border border-gray-200 hover:ring-2 hover:ring-teal-500 transition shadow-sm bg-gray-50">
                                    <img src="{{ Storage::url($report->photo_path) }}" alt="{{ $report->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-semibold">
                                        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Buka Foto
                                    </div>
                                </a>
                            @else
                                <div class="w-full md:w-32 h-32 bg-gray-50 rounded-xl flex flex-col items-center justify-center border border-gray-200 text-gray-400">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="text-[10px] text-gray-400 mt-1 font-medium">Tanpa Foto</span>
                                </div>
                            @endif
                        </div>

                        <!-- Content Details -->
                        <div class="flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="bg-teal-50 text-teal-800 border border-teal-200 text-xs uppercase font-bold px-2.5 py-0.5 rounded shadow-sm">
                                            {{ $report->category }}
                                        </span>
                                        <span class="bg-gray-100 text-gray-700 text-xs font-medium px-2 py-0.5 rounded">
                                            Urgensi: <strong class="capitalize">{{ $report->urgency ?? 'Sedang' }}</strong>
                                        </span>
                                    </div>

                                    @if($report->status === 'baru' || $report->status === 'pending')
                                        <span class="bg-yellow-100 text-yellow-800 border border-yellow-200 text-xs font-semibold px-3 py-1 rounded-full flex items-center gap-1.5 shadow-sm">
                                            <span class="w-2 h-2 rounded-full bg-yellow-500"></span> Menunggu Validasi
                                        </span>
                                    @elseif($report->status === 'diproses')
                                        <span class="bg-blue-100 text-blue-800 border border-blue-200 text-xs font-semibold px-3 py-1 rounded-full flex items-center gap-1.5 shadow-sm">
                                            <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span> Sedang Diproses
                                        </span>
                                    @elseif($report->status === 'selesai')
                                        <span class="bg-green-100 text-green-800 border border-green-200 text-xs font-semibold px-3 py-1 rounded-full flex items-center gap-1.5 shadow-sm">
                                            <span class="w-2 h-2 rounded-full bg-green-500"></span> Selesai
                                        </span>
                                    @elseif($report->status === 'ditolak')
                                        <span class="bg-red-100 text-red-800 border border-red-200 text-xs font-semibold px-3 py-1 rounded-full flex items-center gap-1.5 shadow-sm">
                                            <span class="w-2 h-2 rounded-full bg-red-500"></span> Ditolak
                                        </span>
                                    @endif
                                </div>

                                <h3 class="font-bold text-gray-900 text-xl leading-snug">{{ $report->title }}</h3>
                                <div class="text-sm text-teal-700 font-semibold mt-1 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $report->facility->nama_fasilitas ?? 'Fasilitas Terhapus' }}
                                </div>

                                <p class="text-sm text-gray-700 mt-3 bg-gray-50/80 p-3 rounded-xl border border-gray-100 leading-relaxed">{{ $report->description }}</p>

                                @if ($report->status === 'ditolak' && $report->resolution_note)
                                    <div class="text-sm text-red-800 mt-3 bg-red-50 p-3 rounded-xl border border-red-200 flex items-start gap-2">
                                        <svg class="w-5 h-5 text-red-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                        <div><strong class="font-semibold">Alasan Penolakan:</strong> {{ $report->resolution_note }}</div>
                                    </div>
                                @elseif ($report->status === 'selesai' && $report->resolution_note)
                                    <div class="text-sm text-emerald-900 mt-3 bg-emerald-50 p-3 rounded-xl border border-emerald-200 flex items-start gap-2">
                                        <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <div><strong class="font-semibold">Catatan Solusi Teknisi:</strong> {{ $report->resolution_note }}</div>
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center justify-between gap-4 mt-4 pt-3 border-t border-gray-100 text-xs text-gray-400 font-medium">
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Dilaporkan pada {{ $report->created_at->format('d M Y, H:i') }} WIB
                                </span>
                                @if($report->photo_path)
                                    <a href="{{ Storage::url($report->photo_path) }}" target="_blank" class="text-teal-600 hover:text-teal-800 font-semibold flex items-center gap-1">
                                        Lihat Foto Bukti &rarr;
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-16 bg-white rounded-2xl border border-dashed border-gray-300 shadow-sm">
                        <div class="bg-teal-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="h-8 w-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Belum Ada Laporan Kerusakan</h3>
                        <p class="mt-1 text-sm text-gray-500 max-w-sm mx-auto">Anda belum pernah melaporkan kerusakan fasilitas. Jika menemukan kendala fasilitas kampus, laporkan segera.</p>
                        <button type="button" @click="activeTab = 'form'"
                                class="mt-5 inline-flex items-center gap-2 bg-teal-700 text-white px-5 py-2.5 rounded-xl font-semibold hover:bg-teal-800 transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            Buat Laporan Sekarang
                        </button>
                    </div>
                @endforelse
            </div>

            <div class="mt-6 pb-6">
                {{ $reports->appends(request()->query())->links() }}
            </div>
        </div>

        <!-- TAB 2: FORM PELAPORAN BARU (CENTERED & SPACIOUS) -->
        <div x-show="activeTab === 'form'" style="display: none;" class="max-w-3xl mx-auto">
            <div class="bg-white rounded-2xl border border-gray-200 p-6 sm:p-8 shadow-sm">
                <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-2">
                    <div>
                        <h2 class="font-bold text-2xl text-gray-900 flex items-center gap-2">
                            <svg class="w-6 h-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9.303 3.376c.866 1.5-.217 3.374-1.948 3.374H4.645c-1.73 0-2.813-1.874-1.948-3.374L10.652 4.5c.866-1.5 3.032-1.5 3.898 0l7.753 11.25zM12 15.75h.007v.008H12v-.008z" /></svg>
                            Form Pelaporan Kerusakan
                        </h2>
                        <p class="text-sm text-gray-500 mt-1">Laporan Anda akan langsung diverifikasi petugas dan diteruskan ke teknisi sarpras terkait.</p>
                    </div>
                    <span class="bg-teal-50 text-teal-700 text-xs font-semibold px-3 py-1.5 rounded-full whitespace-nowrap hidden sm:inline-block">SLA Respon &lt; 2 Jam</span>
                </div>

                <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data"
                      class="mt-6 space-y-6"
                      x-data="{
                          category: @js(old('category', '')),
                          urgency: @js(old('urgency', 'sedang')),
                          description: @js(old('description', '')),
                      }">
                    @csrf

                    @php
                        $facilityData = $facilities->map(fn($f) => [
                            'id' => (string) $f->id,
                            'name' => $f->nama_fasilitas,
                            'location' => $f->lokasi,
                            'type' => $f->tipe,
                        ])->values();
                    @endphp

                    {{-- SEARCHABLE COMBOBOX DROPDOWN (100% VALID HTML, NO NESTED BUTTONS) --}}
                    <div class="relative" x-data="{
                        open: false,
                        search: '',
                        selectedId: @js((string) old('facility_id', request('facility_id', ''))),
                        facilities: @js($facilityData),
                        get selected() {
                            return this.facilities.find(f => String(f.id) === String(this.selectedId)) || null;
                        },
                        get filtered() {
                            if (!this.search.trim()) return this.facilities;
                            const q = this.search.toLowerCase().trim();
                            return this.facilities.filter(f =>
                                (f.name && f.name.toLowerCase().includes(q)) ||
                                (f.location && f.location.toLowerCase().includes(q)) ||
                                (f.type && f.type.toLowerCase().includes(q))
                            );
                        },
                        select(id) {
                            this.selectedId = String(id);
                            this.open = false;
                            this.search = '';
                        },
                        clear() {
                            this.selectedId = '';
                            this.search = '';
                        },
                        toggle() {
                            this.open = !this.open;
                            if (this.open) {
                                this.$nextTick(() => this.$refs.facilitySearchInput?.focus());
                            }
                        }
                    }" @click.outside="open = false" @keydown.escape="open = false">

                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block font-semibold text-sm text-gray-700">
                                Fasilitas Kampus <span class="text-red-500">*</span>
                            </label>
                            <span class="text-xs text-gray-400 font-medium" x-text="facilities.length + ' fasilitas aktif'"></span>
                        </div>

                        {{-- Hidden input untuk form submit --}}
                        <input type="hidden" name="facility_id" :value="selectedId">

                        {{-- Trigger Dropdown (Menggunakan DIV role='button' agar valid HTML, tidak ada button di dalam button) --}}
                        <div @click="toggle()"
                             role="button"
                             tabindex="0"
                             @keydown.enter.prevent="toggle()"
                             @keydown.space.prevent="toggle()"
                             class="w-full cursor-pointer text-left border rounded-xl px-3.5 py-3 text-sm shadow-sm transition-all flex items-center justify-between gap-2 bg-white select-none"
                             :class="{
                                 'border-teal-500 ring-2 ring-teal-500/20': open,
                                 'border-red-400 ring-1 ring-red-400': {{ $errors->has('facility_id') ? 'true' : 'false' }},
                                 'border-gray-300 hover:border-gray-400': !open && !{{ $errors->has('facility_id') ? 'true' : 'false' }}
                             }">
                            <div class="flex items-center gap-3 min-w-0 flex-1">
                                <span class="p-2 rounded-lg shrink-0 transition-colors" :class="selected ? 'bg-teal-100 text-teal-700' : 'bg-gray-100 text-gray-400'">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </span>
                                <template x-if="selected">
                                    <div class="min-w-0 flex-1">
                                        <div class="font-semibold text-gray-900 truncate text-sm" x-text="selected.name"></div>
                                        <div class="text-xs text-gray-500 truncate" x-text="(selected.location || '-') + ' • ' + (selected.type || '')"></div>
                                    </div>
                                </template>
                                <template x-if="!selected">
                                    <span class="text-gray-400 truncate">Cari dan pilih fasilitas yang bermasalah...</span>
                                </template>
                            </div>

                            <div class="flex items-center gap-2 shrink-0">
                                <button type="button" x-show="selected" @click.stop="clear()" title="Hapus pilihan fasilitas"
                                        class="p-1 text-gray-400 hover:text-red-500 rounded-lg hover:bg-gray-100 transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                                <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </div>
                        </div>

                        {{-- Dropdown Search Panel --}}
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-1"
                             class="absolute left-0 right-0 z-30 mt-1.5 bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden"
                             style="display: none;">

                            {{-- Search Input Bar --}}
                            <div class="p-3 border-b border-gray-100 bg-gray-50/80">
                                <div class="relative">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                        </svg>
                                    </div>
                                    <input type="text" x-model="search" x-ref="facilitySearchInput"
                                           placeholder="Ketik nama, gedung, atau jenis fasilitas..."
                                           @keydown.escape="open = false"
                                           @keydown.enter.prevent="if (filtered.length > 0) select(filtered[0].id)"
                                           class="w-full pl-9 pr-8 py-2.5 text-xs sm:text-sm border border-gray-300 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">
                                    <button type="button" x-show="search.length > 0" @click="search = ''; $refs.facilitySearchInput.focus()"
                                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            {{-- Daftar Fasilitas Terfilter --}}
                            <div class="max-h-64 overflow-y-auto p-2 space-y-1">
                                <template x-for="f in filtered" :key="f.id">
                                    <button type="button" @click="select(f.id)"
                                            class="w-full text-left px-3.5 py-2.5 rounded-xl transition-colors flex items-center justify-between gap-3 group"
                                            :class="String(selectedId) === String(f.id) ? 'bg-teal-50 text-teal-900 font-medium' : 'text-gray-700 hover:bg-gray-50'">
                                        <div class="min-w-0 flex-1">
                                            <div class="text-sm font-semibold truncate"
                                                 :class="String(selectedId) === String(f.id) ? 'text-teal-900' : 'text-gray-900 group-hover:text-teal-700'"
                                                 x-text="f.name"></div>
                                            <div class="text-xs text-gray-500 truncate flex items-center gap-1.5 mt-0.5">
                                                <span x-text="f.location || '-'"></span>
                                                <span>•</span>
                                                <span class="inline-block px-1.5 py-0.5 bg-gray-100 rounded text-[10px] text-gray-600 font-medium" x-text="f.type"></span>
                                            </div>
                                        </div>
                                        <div x-show="String(selectedId) === String(f.id)" class="text-teal-600 shrink-0">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                            </svg>
                                        </div>
                                    </button>
                                </template>

                                {{-- Pesan Kosong --}}
                                <div x-show="filtered.length === 0" class="py-8 px-4 text-center text-xs text-gray-500" style="display: none;">
                                    <svg class="mx-auto h-7 w-7 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                    </svg>
                                    <span>Tidak ada fasilitas yang cocok dengan "</span><span x-text="search" class="font-semibold text-gray-700"></span><span>"</span>
                                </div>
                            </div>
                        </div>

                        @error('facility_id') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                    </div>

                    {{-- KATEGORI SARANA / PRASARANA --}}
                    <div>
                        <label class="block font-semibold text-sm text-gray-700 mb-2">Kategori Sarana / Prasarana <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @php
                                $categories = [
                                    'Kelistrikan & Lampu' => 'M13 10V3L4 14h7v7l9-11h-7z',
                                    'AC & Ventilasi' => 'M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z',
                                    'IT, PC & Kabel LAN' => 'M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25',
                                    'Mebel & Fisik Pintu' => 'M4.5 21V4.5A1.5 1.5 0 016 3h9a1.5 1.5 0 011.5 1.5V21M4.5 21h13.5M4.5 21H3m15 0h1.5m-4.5-9h.008v.008H15V12z',
                                ];
                            @endphp
                            @foreach ($categories as $label => $iconPath)
                                <label class="relative flex items-center gap-3 border rounded-xl px-3.5 py-3 cursor-pointer transition-all text-sm font-medium"
                                       :class="category === @js($label) ? 'border-teal-500 bg-teal-50/80 text-teal-900 shadow-sm' : 'border-gray-200 text-gray-700 hover:bg-gray-50'">
                                    <input type="radio" name="category" value="{{ $label }}" x-model="category" class="sr-only" required>
                                    <span class="flex items-center justify-center w-4 h-4 rounded-full border shrink-0"
                                          :class="category === @js($label) ? 'border-teal-600' : 'border-gray-300'">
                                        <span class="w-2 h-2 rounded-full bg-teal-600" x-show="category === @js($label)"></span>
                                    </span>
                                    <svg class="w-4 h-4 shrink-0 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPath }}" /></svg>
                                    <span>{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('category') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                    </div>

                    {{-- JUDUL KENDALA --}}
                    <div>
                        <label class="block font-semibold text-sm text-gray-700 mb-1.5">Judul Kendala <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" required placeholder="Contoh: Lampu sorot sisi barat lapangan mati total"
                               class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 text-sm shadow-sm focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">
                        @error('title') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                    </div>

                    {{-- TINGKAT URGENSI --}}
                    <div>
                        <label class="block font-semibold text-sm text-gray-700 mb-2">Tingkat Urgensi <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-3 gap-3">
                            @php
                                $urgencies = [
                                    'rendah' => ['Rendah', 'Tidak darurat', 'border-slate-400 bg-slate-100 text-slate-800'],
                                    'sedang' => ['Sedang', 'Gangguan rutin', 'border-amber-400 bg-amber-100 text-amber-800'],
                                    'mendesak' => ['Mendesak', 'Bahaya/Macet', 'border-red-400 bg-red-100 text-red-800'],
                                ];
                            @endphp
                            @foreach ($urgencies as $value => [$label, $sublabel, $activeClass])
                                <label class="relative flex flex-col items-center text-center border rounded-xl px-2 py-2.5 cursor-pointer transition-all"
                                       :class="urgency === @js($value) ? @js($activeClass) : 'border-gray-200 text-gray-500 hover:bg-gray-50'">
                                    <input type="radio" name="urgency" value="{{ $value }}" x-model="urgency" class="sr-only" required>
                                    <span class="text-sm font-semibold">{{ $label }}</span>
                                    <span class="text-[11px] mt-0.5 opacity-80">{{ $sublabel }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('urgency') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                    </div>

                    {{-- DESKRIPSI KERUSAKAN --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block font-semibold text-sm text-gray-700">Deskripsi & Posisi Kerusakan <span class="text-red-500">*</span></label>
                            <span class="text-xs text-gray-400" x-text="description.length + ' / 500'"></span>
                        </div>
                        <textarea name="description" rows="4" required maxlength="500" x-model="description"
                                  placeholder="Jelaskan titik spesifik masalah, kronologi, atau indikasi lain untuk mempercepat investigasi teknisi..."
                                  class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 text-sm shadow-sm focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">{{ old('description') }}</textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                    </div>

                    {{-- FOTO BUKTI KERUSAKAN --}}
                    <div x-data="{
                            fileName: '',
                            filePreview: null,
                            dragging: false,
                            fileError: null,
                            pick(files) {
                                const file = files && files[0];
                                this.fileError = null;
                                if (!file) return;
                                const ext = file.name ? file.name.split('.').pop().toLowerCase() : '';
                                const validMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg', 'image/pjpeg', 'image/x-png'];
                                const validExts = ['jpg', 'jpeg', 'png', 'webp'];
                                if (!validMimes.includes(file.type) && !validExts.includes(ext)) {
                                    return this.reject('Format foto harus JPG, PNG, atau WebP.');
                                }
                                if (file.size > 5 * 1024 * 1024) {
                                    return this.reject('File hanya bisa max 5MB');
                                }
                                this.fileName = file.name;
                                const reader = new FileReader();
                                reader.onload = (e) => this.filePreview = e.target.result;
                                reader.readAsDataURL(file);
                            },
                            dropFiles(event) {
                                this.dragging = false;
                                const files = event.dataTransfer.files;
                                if (files && files.length > 0) {
                                    try {
                                        const dt = new DataTransfer();
                                        dt.items.add(files[0]);
                                        this.$refs.photo.files = dt.files;
                                    } catch(e) {}
                                    this.pick(files);
                                }
                            },
                            reject(message) {
                                this.fileError = message;
                                this.clear();
                            },
                            clear() {
                                this.filePreview = null;
                                this.fileName = '';
                                this.$refs.photo.value = '';
                            },
                         }">
                        <label class="block font-semibold text-sm text-gray-700 mb-1.5">Foto Bukti Kerusakan (Opsional)</label>

                        <input id="photo-upload" x-ref="photo" name="photo" type="file" accept="image/jpeg,image/png,image/webp,image/jpg,.jpg,.jpeg,.png,.webp" class="sr-only"
                               @change="pick($event.target.files)">

                        <div class="mt-1 flex justify-center px-6 pt-6 pb-6 border-2 border-dashed rounded-2xl relative transition-colors cursor-pointer"
                             :class="dragging || filePreview ? 'border-teal-500 bg-teal-50/50' : 'border-gray-300 bg-gray-50/50 hover:bg-gray-50'"
                             @dragover.prevent="dragging = true" @dragleave.prevent="dragging = false"
                             @drop.prevent="dropFiles($event)"
                             @click="if (!filePreview) $refs.photo.click()">

                            <div x-show="!filePreview" class="space-y-2 text-center">
                                    <span class="mx-auto flex items-center justify-center w-12 h-12 rounded-full bg-white shadow-sm ring-1 ring-gray-200">
                                        <svg class="w-6 h-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.174C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" /><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" /></svg>
                                    </span>
                                    <div class="text-sm text-gray-700">
                                        <span class="font-medium">Tarik &amp; Lepaskan foto atau</span>
                                        <span class="font-semibold text-teal-600 hover:text-teal-700">Pilih File</span>
                                    </div>
                                    <p class="text-xs text-gray-400">Format JPG, PNG atau WebP (Maks. 5MB)</p>
                            </div>

                            <div x-show="filePreview" class="w-full text-center" style="display: none;" @click.stop>
                                <img :src="filePreview" class="h-40 mx-auto object-cover rounded-xl shadow-sm mb-2">
                                <div class="text-xs font-medium text-gray-600 truncate px-4" x-text="fileName"></div>
                                <button type="button" @click.stop="clear()" class="mt-2 text-xs font-semibold text-red-600 hover:text-red-800">
                                    Hapus Foto
                                </button>
                            </div>
                        </div>
                        <p x-show="fileError" x-text="fileError" class="text-red-500 text-xs mt-1.5 font-semibold" style="display: none;"></p>
                        @error('photo') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                    </div>

                    {{-- SUBMIT BUTTONS --}}
                    <div class="flex flex-col sm:flex-row items-center gap-3 pt-2">
                        <button type="submit" class="w-full sm:flex-1 bg-teal-800 text-white rounded-xl py-3 font-semibold hover:bg-teal-900 transition-colors shadow-sm flex justify-center items-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" /></svg>
                            Kirim Laporan Kerusakan
                        </button>
                        <button type="button" @click="activeTab = 'riwayat'" class="w-full sm:w-auto px-6 py-3 border border-gray-300 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Bottom: Kelola Laporan Kerusakan (Khusus Petugas) -->
    @if(auth()->user()->isPetugas())
        <div id="kelola-laporan" class="max-w-6xl mx-auto px-4 pb-12 scroll-mt-24">
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm flex flex-col" style="min-height: 480px;">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-100 pb-4">
                    <div>
                        <h2 class="font-bold text-xl text-gray-900">Kelola Laporan Kerusakan</h2>
                        <p class="text-sm text-gray-500 mt-0.5">Verifikasi dan tindak lanjut laporan dari seluruh pengguna.</p>
                    </div>
                    <div>
                        <a href="{{ route('reports.index', ['manage_status' => $manageStatus]) }}#kelola-laporan" class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 px-3 py-2 rounded-lg transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            Muat ulang
                        </a>
                    </div>
                </div>

                <!-- Tabs -->
                <div class="flex flex-wrap gap-2 mt-5">
                    @php
                        $tabs = [
                            'menunggu' => 'Menunggu',
                            'diproses' => 'Diproses',
                            'selesai' => 'Selesai',
                            'ditolak' => 'Ditolak',
                            'semua' => 'Semua',
                        ];
                    @endphp
                    @foreach($tabs as $tabKey => $tabLabel)
                        <a href="{{ route('reports.index', ['manage_status' => $tabKey]) }}#kelola-laporan"
                           class="px-4 py-1.5 rounded-full text-xs font-semibold transition-colors {{ $manageStatus === $tabKey ? 'bg-teal-700 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            {{ $tabLabel }} <span class="ml-1 opacity-80">{{ $manageCounts[$tabKey] ?? 0 }}</span>
                        </a>
                    @endforeach
                </div>

                <!-- Reports List -->
                <div class="mt-6 space-y-4 flex-1 flex flex-col">
                    @forelse($manageReports as $mReport)
                        <div class="bg-gray-50/50 rounded-xl border border-gray-200 p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div class="flex items-start gap-4">
                                @if($mReport->photo_path)
                                    <div class="shrink-0">
                                        <a href="{{ Storage::url($mReport->photo_path) }}" target="_blank" title="Klik untuk membuka foto ukuran penuh"
                                           class="group relative block w-16 h-16 sm:w-20 sm:h-20 rounded-xl overflow-hidden border border-gray-200 shadow-sm hover:ring-2 hover:ring-teal-500 transition">
                                            <img src="{{ Storage::url($mReport->photo_path) }}" alt="{{ $mReport->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-[10px] font-bold">
                                                Buka
                                            </div>
                                        </a>
                                    </div>
                                @else
                                    <div class="w-16 h-16 sm:w-20 sm:h-20 bg-white rounded-xl border border-gray-200 flex flex-col items-center justify-center shrink-0 text-gray-400">
                                        <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span class="text-[9px] text-gray-400 mt-1 font-medium">Tanpa Foto</span>
                                    </div>
                                @endif
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="font-bold text-gray-900">{{ $mReport->title }}</span>
                                        @if($mReport->status === 'baru')
                                            <span class="bg-yellow-100 text-yellow-800 text-[11px] font-semibold px-2 py-0.5 rounded-full">Menunggu Persetujuan</span>
                                        @elseif($mReport->status === 'diproses')
                                            <span class="bg-blue-100 text-blue-800 text-[11px] font-semibold px-2 py-0.5 rounded-full">Sedang Diproses</span>
                                        @elseif($mReport->status === 'selesai')
                                            <span class="bg-green-100 text-green-800 text-[11px] font-semibold px-2 py-0.5 rounded-full">Selesai</span>
                                        @elseif($mReport->status === 'ditolak')
                                            <span class="bg-red-100 text-red-800 text-[11px] font-semibold px-2 py-0.5 rounded-full">Ditolak</span>
                                        @endif
                                        <span class="bg-blue-50 text-blue-700 text-[11px] font-medium px-2 py-0.5 rounded-full">{{ $mReport->category }}</span>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $mReport->facility->nama_fasilitas ?? 'Fasilitas' }} &middot; {{ $mReport->user->name ?? 'Pengguna' }} &middot; {{ $mReport->created_at->format('d M Y, H:i') }}
                                    </div>
                                    <p class="text-xs text-gray-600 mt-1">{{ $mReport->description }}</p>

                                    @if ($mReport->photo_path)
                                        <div class="mt-2">
                                            <a href="{{ Storage::url($mReport->photo_path) }}" target="_blank"
                                               class="inline-flex items-center gap-1.5 text-xs font-semibold text-teal-700 bg-teal-50 hover:bg-teal-100 border border-teal-200 px-2.5 py-1 rounded-lg transition-colors shadow-sm">
                                                <svg class="w-3.5 h-3.5 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                                Lihat Foto Bukti Kerusakan &rarr;
                                            </a>
                                        </div>
                                    @endif

                                    @if ($mReport->resolution_note)
                                        <p class="text-xs {{ $mReport->status === 'ditolak' ? 'text-red-700 bg-red-50 border-red-200' : 'text-emerald-800 bg-emerald-50 border-emerald-200' }} mt-1.5 p-2 rounded-lg border">
                                            <span class="font-semibold">{{ $mReport->status === 'ditolak' ? 'Alasan penolakan:' : 'Catatan resolusi:' }}</span> {{ $mReport->resolution_note }}
                                        </p>
                                    @endif

                                    @if($mReport->facility)
                                        <div class="mt-2.5 flex flex-wrap items-center gap-2 bg-white/90 border border-gray-200 rounded-lg p-2 text-xs">
                                            <span class="text-gray-500 font-medium">Status Fasilitas:</span>
                                            @if($mReport->facility->status === 'aktif')
                                                <span class="bg-emerald-100 text-emerald-800 font-semibold px-2 py-0.5 rounded-full flex items-center gap-1">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                                </span>
                                                <form method="POST" action="{{ route('petugas.facilities.update-status', $mReport->facility) }}" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="dalam_perbaikan">
                                                    <button type="submit" onclick="return confirm({{ Js::from('Tandai fasilitas ' . $mReport->facility->nama_fasilitas . ' sebagai Dalam Perbaikan? Pengguna tidak dapat mereservasi fasilitas ini sementara waktu, dan semua reservasi mendatang (menunggu/disetujui) akan dibatalkan otomatis.') }})"
                                                        class="bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-300 font-semibold px-2 py-0.5 rounded transition-colors flex items-center gap-1 text-[11px]">
                                                        <svg class="w-3 h-3 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.162 1.15-.1 1.667.18 1.107.597 1.517 1.968.92 3.076a2.25 2.25 0 01-1.01 1.01" /></svg>
                                                        Tandai Dalam Perbaikan
                                                    </button>
                                                </form>
                                            @elseif($mReport->facility->status === 'dalam_perbaikan')
                                                <span class="bg-amber-100 text-amber-800 font-semibold px-2 py-0.5 rounded-full flex items-center gap-1">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Dalam Perbaikan
                                                </span>
                                                <form method="POST" action="{{ route('petugas.facilities.update-status', $mReport->facility) }}" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="aktif">
                                                    <button type="submit" onclick="return confirm({{ Js::from('Kembalikan status fasilitas ' . $mReport->facility->nama_fasilitas . ' menjadi Aktif? Fasilitas akan siap direservasi kembali.') }})"
                                                        class="bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-300 font-semibold px-2 py-0.5 rounded transition-colors flex items-center gap-1 text-[11px]">
                                                        <svg class="w-3 h-3 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                                        Kembalikan ke Aktif
                                                    </button>
                                                </form>
                                            @else
                                                <span class="bg-gray-100 text-gray-700 font-semibold px-2 py-0.5 rounded-full text-[11px]">Nonaktif</span>
                                                <form method="POST" action="{{ route('petugas.facilities.update-status', $mReport->facility) }}" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="aktif">
                                                    <button type="submit" class="bg-gray-50 hover:bg-gray-100 text-gray-700 border border-gray-300 font-semibold px-2 py-0.5 rounded transition-colors text-[11px]">
                                                        Aktifkan
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-2 self-end sm:self-center">
                                @if($mReport->status !== 'diproses')
                                    <form action="{{ route('reports.update-status', $mReport) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="diproses">
                                        <button type="submit" class="bg-teal-700 hover:bg-teal-800 text-white text-xs font-semibold px-4 py-1.5 rounded-lg shadow-sm transition-colors">
                                            Proses
                                        </button>
                                    </form>
                                @endif
                                @if($mReport->status !== 'selesai')
                                    <button type="button" x-data="" @click="$dispatch('open-modal', 'resolve-report-{{ $mReport->id }}')"
                                        class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-4 py-1.5 rounded-lg shadow-sm transition-colors">
                                        Selesai
                                    </button>
                                @endif
                                @if($mReport->status !== 'ditolak')
                                    <button type="button" x-data="" @click="$dispatch('open-modal', 'reject-report-{{ $mReport->id }}')"
                                        class="text-red-500 hover:text-red-700 text-xs font-semibold px-3 py-1.5 transition-colors">
                                        Tolak
                                    </button>
                                @endif
                            </div>
                        </div>

                        <!-- Modal Selesaikan Laporan -->
                        <x-modal name="resolve-report-{{ $mReport->id }}" max-width="md">
                            <form method="POST" action="{{ route('reports.update-status', $mReport) }}" class="p-6">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="selesai">
                                <h3 class="font-bold text-gray-900 text-lg">Tutup & Selesaikan Laporan</h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    Laporan <span class="font-semibold text-gray-700">"{{ $mReport->title }}"</span> akan ditandai selesai.
                                    Tuliskan catatan resolusi atau tindakan perbaikan yang telah dilakukan.
                                </p>

                                <div class="mt-4">
                                    <label class="block font-semibold text-sm text-gray-700 mb-1">Catatan Resolusi / Tindakan Perbaikan <span class="text-emerald-600">*</span></label>
                                    <textarea name="resolution_note" rows="3" required maxlength="255"
                                        placeholder="Contoh: Perbaikan fasilitas telah selesai dikerjakan oleh teknisi kampus..."
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm shadow-sm focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                                </div>

                                @if($mReport->facility && $mReport->facility->status === 'dalam_perbaikan')
                                    <div class="mt-3 p-3 bg-amber-50 border border-amber-200 rounded-lg text-xs text-amber-900">
                                        <label class="flex items-center gap-2 cursor-pointer font-medium">
                                            <input type="checkbox" name="reactivate_facility" value="1" checked class="rounded border-amber-300 text-emerald-600 focus:ring-emerald-500">
                                            Kembalikan status fasilitas ({{ $mReport->facility->nama_fasilitas }}) menjadi 'Aktif'
                                        </label>
                                    </div>
                                @endif

                                <div class="mt-6 flex items-center justify-end gap-2">
                                    <button type="button" @click="show = false" class="text-sm font-semibold text-gray-600 hover:text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
                                        Batal
                                    </button>
                                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-sm transition-colors">
                                        Simpan & Tandai Selesai
                                    </button>
                                </div>
                            </form>
                        </x-modal>

                        <x-modal name="reject-report-{{ $mReport->id }}" max-width="md">
                            <form method="POST" action="{{ route('reports.update-status', $mReport) }}" class="p-6">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="ditolak">
                                <h3 class="font-bold text-gray-900 text-lg">Tolak Laporan</h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    Laporan <span class="font-semibold text-gray-700">"{{ $mReport->title }}"</span> akan ditolak.
                                    Berikan alasan agar pelapor mengetahui tindak lanjutnya.
                                </p>

                                <div class="mt-4">
                                    <label class="block font-semibold text-sm text-gray-700 mb-1">Alasan Penolakan <span class="text-red-500">*</span></label>
                                    <textarea name="resolution_note" rows="3" required maxlength="255"
                                        placeholder="Contoh: Bukan kerusakan fasilitas, sudah ditangani sebelumnya, dsb."
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm shadow-sm focus:ring-red-500 focus:border-red-500"></textarea>
                                </div>

                                <div class="mt-6 flex items-center justify-end gap-2">
                                    <button type="button" @click="show = false" class="text-sm font-semibold text-gray-600 hover:text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
                                        Batal
                                    </button>
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-sm transition-colors">
                                        Tolak Laporan
                                    </button>
                                </div>
                            </form>
                        </x-modal>
                    @empty
                        <div class="flex-1 flex flex-col items-center justify-center text-center text-sm text-gray-500 bg-gray-50/50 rounded-xl border border-dashed border-gray-200">
                            <svg class="w-8 h-8 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Tidak ada laporan dengan status ini.
                        </div>
                    @endforelse
                </div>
                @if($manageReports && $manageReports->hasPages())
                    <div class="mt-4">
                        {{ $manageReports->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    @endif
</x-catalog-layout>
