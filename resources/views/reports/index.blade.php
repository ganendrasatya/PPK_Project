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
                <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-1">
                    <h2 class="font-bold text-xl text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9.303 3.376c.866 1.5-.217 3.374-1.948 3.374H4.645c-1.73 0-2.813-1.874-1.948-3.374L10.652 4.5c.866-1.5 3.032-1.5 3.898 0l7.753 11.25zM12 15.75h.007v.008H12v-.008z" /></svg>
                        Form Pelaporan Baru
                    </h2>
                    <span class="bg-teal-50 text-teal-700 text-xs font-semibold px-2.5 py-1 rounded-full whitespace-nowrap">SLA Respon &lt; 2 Jam</span>
                </div>
                <p class="text-sm text-gray-500 mb-5">Isi detail gangguan fasilitas di bawah ini. Laporan Anda akan langsung diverifikasi dan diteruskan ke tim teknisi sarpras yang relevan.</p>

                <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data"
                      x-data="{
                          category: @js(old('category', '')),
                          urgency: @js(old('urgency', 'sedang')),
                          description: @js(old('description', '')),
                      }">
                    @csrf

                    <div class="mb-4">
                        <label class="block font-semibold text-sm text-gray-700 mb-1">Fasilitas Kampus <span class="text-red-500">*</span></label>
                        <select name="facility_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2.5 mt-1 text-sm shadow-sm focus:ring-teal-500 focus:border-teal-500">
                            <option value="">Pilih fasilitas yang bermasalah...</option>
                            @foreach($facilities as $f)
                                <option value="{{ $f->id }}" {{ old('facility_id') == $f->id ? 'selected' : '' }}>{{ $f->nama_fasilitas }}</option>
                            @endforeach
                        </select>
                        @error('facility_id') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold text-sm text-gray-700 mb-2">Kategori Sarana / Prasarana <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-2 gap-2.5">
                            @php
                                $categories = [
                                    'Kelistrikan & Lampu' => 'M13 10V3L4 14h7v7l9-11h-7z',
                                    'AC & Ventilasi' => 'M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z',
                                    'IT, PC & Kabel LAN' => 'M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25',
                                    'Mebel & Fisik Pintu' => 'M4.5 21V4.5A1.5 1.5 0 016 3h9a1.5 1.5 0 011.5 1.5V21M4.5 21h13.5M4.5 21H3m15 0h1.5m-4.5-9h.008v.008H15V12z',
                                ];
                            @endphp
                            @foreach ($categories as $label => $iconPath)
                                <label class="relative flex items-center gap-2 border rounded-xl px-3 py-2.5 cursor-pointer transition-colors text-sm font-medium"
                                       :class="category === @js($label) ? 'border-teal-500 bg-teal-50 text-teal-800' : 'border-gray-200 text-gray-600 hover:bg-gray-50'">
                                    <input type="radio" name="category" value="{{ $label }}" x-model="category" class="sr-only" required>
                                    <span class="flex items-center justify-center w-4 h-4 rounded-full border shrink-0"
                                          :class="category === @js($label) ? 'border-teal-600' : 'border-gray-300'">
                                        <span class="w-2 h-2 rounded-full bg-teal-600" x-show="category === @js($label)"></span>
                                    </span>
                                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPath }}" /></svg>
                                    <span>{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('category') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold text-sm text-gray-700 mb-1">Judul Kendala <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" required placeholder="Contoh: Lampu sorot sisi barat lapangan mati total" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 mt-1 text-sm shadow-sm focus:ring-teal-500 focus:border-teal-500">
                        @error('title') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold text-sm text-gray-700 mb-2">Tingkat Urgensi</label>
                        <div class="grid grid-cols-3 gap-2">
                            @php
                                $urgencies = [
                                    'rendah' => ['Rendah', 'Tidak darurat', 'border-slate-400 bg-slate-100 text-slate-800'],
                                    'sedang' => ['Sedang', 'Gangguan rutin', 'border-amber-400 bg-amber-100 text-amber-800'],
                                    'mendesak' => ['Mendesak', 'Bahaya/Macet', 'border-red-400 bg-red-100 text-red-800'],
                                ];
                            @endphp
                            @foreach ($urgencies as $value => [$label, $sublabel, $activeClass])
                                <label class="relative flex flex-col items-center text-center border rounded-xl px-2 py-2.5 cursor-pointer transition-colors"
                                       :class="urgency === @js($value) ? @js($activeClass) : 'border-gray-200 text-gray-500 hover:bg-gray-50'">
                                    <input type="radio" name="urgency" value="{{ $value }}" x-model="urgency" class="sr-only" required>
                                    <span class="text-sm font-semibold">{{ $label }}</span>
                                    <span class="text-[11px] mt-0.5 opacity-80">{{ $sublabel }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('urgency') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-5">
                        <div class="flex items-center justify-between mb-1">
                            <label class="block font-semibold text-sm text-gray-700">Deskripsi & Posisi Kerusakan <span class="text-red-500">*</span></label>
                            <span class="text-xs text-gray-400" x-text="description.length + ' / 500'"></span>
                        </div>
                        <textarea name="description" rows="4" required maxlength="500" x-model="description"
                                  placeholder="Jelaskan titik spesifik masalah, kronologi, atau indikasi lain untuk mempercepat investigasi teknisi..."
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm shadow-sm focus:ring-teal-500 focus:border-teal-500">{{ old('description') }}</textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-5" x-data="{ fileName: '', filePreview: null, dragging: false }">
                        <label class="block font-semibold text-sm text-gray-700 mb-1">Foto Bukti Kerusakan (Opsional)</label>

                        <div class="mt-1 flex justify-center px-6 pt-6 pb-6 border-2 border-dashed rounded-xl relative transition-colors"
                             :class="dragging || filePreview ? 'border-teal-500 bg-teal-50' : 'border-gray-300 bg-gray-50/50 hover:bg-gray-50'"
                             @dragover.prevent="dragging = true" @dragleave.prevent="dragging = false"
                             @drop.prevent="
                                dragging = false;
                                const file = $event.dataTransfer.files[0];
                                if (file) {
                                    document.getElementById('photo-upload').files = $event.dataTransfer.files;
                                    fileName = file.name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => filePreview = e.target.result;
                                    reader.readAsDataURL(file);
                                }
                             ">

                            <template x-if="!filePreview">
                                <div class="space-y-2 text-center">
                                    <span class="mx-auto flex items-center justify-center w-10 h-10 rounded-full bg-white shadow-sm ring-1 ring-gray-200">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.174C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" /><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" /></svg>
                                    </span>
                                    <div class="text-sm text-gray-700">
                                        <span class="font-medium">Tarik &amp; Lepaskan foto atau</span>
                                        <label for="photo-upload" class="cursor-pointer font-semibold text-teal-600 hover:text-teal-700">Pilih File</label>
                                    </div>
                                    <input id="photo-upload" name="photo" type="file" accept="image/jpeg,image/png,image/webp" class="sr-only"
                                           @change="
                                                const file = $event.target.files[0];
                                                if(file) {
                                                    fileName = file.name;
                                                    const reader = new FileReader();
                                                    reader.onload = (e) => filePreview = e.target.result;
                                                    reader.readAsDataURL(file);
                                                }
                                           ">
                                    <p class="text-xs text-gray-400">Format JPG, PNG atau WebP (Maks. 5MB)</p>
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

                    <button type="submit" class="w-full bg-teal-800 text-white rounded-xl py-3 font-semibold hover:bg-teal-900 transition-colors shadow-sm flex justify-center items-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" /></svg>
                        Kirim Laporan Kerusakan
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

                            @if ($report->status === 'ditolak' && $report->resolution_note)
                                <p class="text-sm text-red-700 mt-2 bg-red-50 p-2.5 rounded-lg border border-red-100"><span class="font-semibold">Alasan ditolak:</span> {{ $report->resolution_note }}</p>
                            @endif

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
                                    <button type="button" x-data="" @click="$dispatch('open-modal', 'reject-report-{{ $mReport->id }}')"
                                        class="text-red-500 hover:text-red-700 text-xs font-semibold px-3 py-1.5 transition-colors">
                                        Tolak
                                    </button>
                                @endif
                            </div>
                        </div>

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
