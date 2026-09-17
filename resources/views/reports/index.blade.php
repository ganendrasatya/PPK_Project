<x-catalog-layout>
    <div class="max-w-6xl mx-auto px-4 pt-8">
        <div>
            <span class="text-teal-600 uppercase text-sm font-semibold tracking-wider">PORTAL MAHASISWA & SIVITAS</span>
            <h1 class="text-3xl font-bold mt-1 text-gray-900">Laporan & Kerusakan</h1>
            <p class="text-gray-500 mt-1">Laporkan kerusakan fasilitas dan pantau status penanganannya.</p>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 py-8 flex flex-col lg:flex-row gap-8">
        <!-- Left: Form -->
        <div class="w-full lg:w-5/12">
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm sticky top-24">
                <h2 class="font-bold text-xl text-gray-900 border-b border-gray-100 pb-3 mb-5">Form Pelaporan Kerusakan</h2>
                
                <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block font-semibold text-sm text-gray-700 mb-1">Fasilitas <span class="text-red-500">*</span></label>
                        <select name="facility_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2.5 mt-1 text-sm shadow-sm focus:ring-teal-500 focus:border-teal-500">
                            <option value="">Pilih fasilitas yang rusak...</option>
                            @foreach($facilities as $f)
                                <option value="{{ $f->id }}" {{ old('facility_id') == $f->id ? 'selected' : '' }}>{{ $f->nama_fasilitas }}</option>
                            @endforeach
                        </select>
                        @error('facility_id') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold text-sm text-gray-700 mb-1">Kategori Laporan <span class="text-red-500">*</span></label>
                        <select name="category" required class="w-full border border-gray-300 rounded-lg px-3 py-2.5 mt-1 text-sm shadow-sm focus:ring-teal-500 focus:border-teal-500">
                            <option value="Kerusakan Ringan" {{ old('category') == 'Kerusakan Ringan' ? 'selected' : '' }}>Kerusakan Ringan (mis: lampu mati, kursi goyang)</option>
                            <option value="Kerusakan Sedang" {{ old('category') == 'Kerusakan Sedang' ? 'selected' : '' }}>Kerusakan Sedang (mis: AC bocor, pintu macet)</option>
                            <option value="Kerusakan Berat" {{ old('category') == 'Kerusakan Berat' ? 'selected' : '' }}>Kerusakan Berat (mis: atap bocor parah, korsleting)</option>
                        </select>
                        @error('category') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold text-sm text-gray-700 mb-1">Judul Laporan <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" required placeholder="Contoh: AC bocor menetes ke meja" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 mt-1 text-sm shadow-sm focus:ring-teal-500 focus:border-teal-500">
                        @error('title') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold text-sm text-gray-700 mb-1">Deskripsi Kerusakan <span class="text-red-500">*</span></label>
                        <textarea name="description" rows="4" required placeholder="Jelaskan kerusakan secara rinci, lokasi spesifik, dsb..." class="w-full border border-gray-300 rounded-lg px-3 py-2.5 mt-1 text-sm shadow-sm focus:ring-teal-500 focus:border-teal-500">{{ old('description') }}</textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-5" x-data="{ fileName: '', filePreview: null }">
                        <label class="block font-semibold text-sm text-gray-700 mb-1">Foto (opsional)</label>
                        
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl relative hover:bg-gray-50 transition-colors"
                             :class="{ 'border-teal-500 bg-teal-50' : filePreview }">
                            
                            <template x-if="!filePreview">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600 justify-center">
                                        <label for="photo-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-teal-600 hover:text-teal-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-teal-500 px-1">
                                            <span>Unggah foto kerusakan</span>
                                            <input id="photo-upload" name="photo" type="file" accept="image/*" class="sr-only"
                                                   @change="
                                                        const file = $event.target.files[0];
                                                        if(file) {
                                                            fileName = file.name;
                                                            const reader = new FileReader();
                                                            reader.onload = (e) => filePreview = e.target.result;
                                                            reader.readAsDataURL(file);
                                                        }
                                                   ">
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF up to 5MB</p>
                                </div>
                            </template>
                            
                            <template x-if="filePreview">
                                <div class="w-full text-center">
                                    <img :src="filePreview" class="h-32 mx-auto object-cover rounded-lg shadow-sm mb-2">
                                    <div class="text-xs font-medium text-gray-600 truncate px-4" x-text="fileName"></div>
                                    <button type="button" @click="filePreview = null; fileName = ''; document.getElementById('photo-upload').value = ''" class="mt-2 text-xs font-semibold text-red-600 hover:text-red-800">
                                        Hapus Foto
                                    </button>
                                </div>
                            </template>
                        </div>
                        @error('photo') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="w-full bg-teal-600 text-white rounded-xl py-3 font-semibold mt-6 hover:bg-teal-700 transition-colors shadow-sm flex justify-center items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        Kirim Laporan
                    </button>
                </form>
            </div>
        </div>

        <!-- Right: Laporan Saya -->
        <div class="w-full lg:w-7/12">
            <h2 class="font-bold text-xl text-gray-900 mb-5 flex items-center justify-between">
                Laporan Saya
                <span class="bg-gray-100 text-gray-600 text-sm py-1 px-3 rounded-full font-medium">{{ $reports->total() }} total</span>
            </h2>
            
            <div class="space-y-4">
                @forelse($reports as $report)
                    <div class="bg-white rounded-xl border border-gray-200 p-5 flex flex-col sm:flex-row gap-5 shadow-sm hover:shadow-md transition-shadow">
                        <!-- Photo -->
                        <div class="shrink-0">
                            @if($report->photo_path)
                                <img src="{{ Storage::url($report->photo_path) }}" class="w-full sm:w-20 h-32 sm:h-20 rounded-xl object-cover border border-gray-100">
                            @else
                                <div class="w-full sm:w-20 h-32 sm:h-20 bg-gray-50 rounded-xl flex items-center justify-center border border-gray-200">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Content -->
                        <div class="flex-1 flex flex-col justify-center">
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <span class="bg-orange-100 text-orange-800 border border-orange-200 text-[11px] uppercase font-bold px-2 py-0.5 rounded shadow-sm">
                                    {{ $report->category }}
                                </span>
                                
                                @if($report->status === 'baru' || $report->status === 'pending')
                                    <span class="bg-yellow-100 text-yellow-800 border border-yellow-200 text-xs font-semibold px-2.5 py-0.5 rounded-full flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span> Menunggu Validasi
                                    </span>
                                @elseif($report->status === 'diproses')
                                    <span class="bg-blue-100 text-blue-800 border border-blue-200 text-xs font-semibold px-2.5 py-0.5 rounded-full flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span> Sedang Diproses
                                    </span>
                                @elseif($report->status === 'selesai')
                                    <span class="bg-green-100 text-green-800 border border-green-200 text-xs font-semibold px-2.5 py-0.5 rounded-full flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Selesai
                                    </span>
                                @elseif($report->status === 'ditolak')
                                    <span class="bg-red-100 text-red-800 border border-red-200 text-xs font-semibold px-2.5 py-0.5 rounded-full flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Ditolak
                                    </span>
                                @endif
                            </div>
                            
                            <h3 class="font-bold text-gray-900 text-lg leading-tight">{{ $report->title }}</h3>
                            <p class="text-sm text-teal-700 font-medium mt-1">{{ $report->facility->nama_fasilitas ?? 'Fasilitas Terhapus' }}</p>
                            
                            <p class="text-sm text-gray-600 mt-2 bg-gray-50 p-2.5 rounded-lg border border-gray-100">{{ $report->description }}</p>
                            
                            <div class="flex items-center gap-2 mt-3 text-xs text-gray-400 font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Dilaporkan pada {{ $report->created_at->format('d M Y, H:i') }} WIB
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-16 bg-white rounded-xl border border-dashed border-gray-300">
                        <div class="bg-gray-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">Belum ada laporan</h3>
                        <p class="mt-1 text-sm text-gray-500 max-w-sm mx-auto">Anda belum pernah melaporkan kerusakan fasilitas. Gunakan formulir di samping jika menemukan kendala.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-6 pb-6">
                {{ $reports->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

    <!-- Bottom: Kelola Laporan Kerusakan (Khusus Petugas & Admin) -->
    @if(auth()->user()->isAdmin() || auth()->user()->isPetugas())
        <div class="max-w-6xl mx-auto px-4 pb-12">
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-100 pb-4">
                    <div>
                        <h2 class="font-bold text-xl text-gray-900">Kelola Laporan Kerusakan</h2>
                        <p class="text-sm text-gray-500 mt-0.5">Verifikasi dan tindak lanjut laporan dari seluruh pengguna.</p>
                    </div>
                    <div>
                        <a href="{{ route('reports.index', ['manage_status' => $manageStatus]) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 px-3 py-2 rounded-lg transition-colors">
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
                        <a href="{{ route('reports.index', ['manage_status' => $tabKey]) }}"
                           class="px-4 py-1.5 rounded-full text-xs font-semibold transition-colors {{ $manageStatus === $tabKey ? 'bg-teal-700 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            {{ $tabLabel }} <span class="ml-1 opacity-80">{{ $manageCounts[$tabKey] ?? 0 }}</span>
                        </a>
                    @endforeach
                </div>

                <!-- Reports List -->
                <div class="mt-6 space-y-4">
                    @forelse($manageReports as $mReport)
                        <div class="bg-gray-50/50 rounded-xl border border-gray-200 p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-white rounded-xl border border-gray-200 flex items-center justify-center shrink-0">
                                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
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
                                    <form action="{{ route('reports.update-status', $mReport) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="selesai">
                                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-4 py-1.5 rounded-lg shadow-sm transition-colors">
                                            Selesai
                                        </button>
                                    </form>
                                @endif
                                @if($mReport->status !== 'ditolak')
                                    <form action="{{ route('reports.update-status', $mReport) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="ditolak">
                                        <button type="submit" onclick="return confirm('Tolak laporan ini?')" class="text-red-500 hover:text-red-700 text-xs font-semibold px-3 py-1.5 transition-colors">
                                            Tolak
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-sm text-gray-500">
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
