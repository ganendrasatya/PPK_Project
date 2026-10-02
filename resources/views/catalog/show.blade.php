<x-catalog-layout>
    <div class="max-w-6xl mx-auto px-4 pt-6">
        <a href="{{ route('catalog.index') }}" class="text-gray-500 hover:text-teal-600 text-sm font-medium flex items-center gap-1 transition-colors">
            &larr; Kembali ke katalog
        </a>
    </div>

    <div class="max-w-6xl mx-auto px-4 py-6 flex flex-col lg:flex-row gap-8">
        <!-- Left Column: Facility Detail -->
        <div class="w-full lg:w-5/12">
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                @if($facility->image_path)
                    <img src="{{ Storage::url($facility->image_path) }}" alt="{{ $facility->nama_fasilitas }}" class="rounded-xl h-72 w-full object-cover shadow-sm">
                @else
                    <div class="rounded-xl h-72 w-full bg-gray-100 flex flex-col items-center justify-center text-gray-400">
                        <svg class="w-16 h-16 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        <span class="text-sm font-medium">Foto Tidak Tersedia</span>
                    </div>
                @endif

                <div class="flex gap-2 mt-5">
                    <span class="bg-teal-100 text-teal-700 text-xs font-semibold px-3 py-1 rounded-full">{{ $facility->tipe }}</span>
                    @if($facility->status === 'aktif')
                        <span class="bg-emerald-100 text-emerald-700 text-xs font-semibold px-3 py-1 rounded-full">Aktif</span>
                    @elseif($facility->status === 'dalam_perbaikan')
                        <span class="bg-amber-100 text-amber-700 text-xs font-semibold px-3 py-1 rounded-full">Dalam Perbaikan</span>
                    @else
                        <span class="bg-red-100 text-red-700 text-xs font-semibold px-3 py-1 rounded-full">Nonaktif</span>
                    @endif
                </div>

                <h1 class="text-2xl font-bold mt-3 text-gray-900">{{ $facility->nama_fasilitas }}</h1>
                
                <ul class="space-y-3 mt-4 text-gray-600 text-sm bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <li class="flex items-start gap-3">
                        <i class="ph ph-map-pin text-xl text-teal-600"></i> 
                        <span class="mt-0.5">{{ $facility->lokasi }}</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="ph ph-users text-xl text-teal-600"></i> 
                        <span class="mt-0.5">Kapasitas maksimal {{ $facility->kapasitas }} orang</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="ph ph-clock text-xl text-teal-600"></i> 
                        <span class="mt-0.5">Jam Operasional: {{ \Carbon\Carbon::parse($facility->jam_buka)->format('H:i') }} – {{ \Carbon\Carbon::parse($facility->jam_tutup)->format('H:i') }} WIB</span>
                    </li>
                </ul>

                <div class="mt-5 border-t border-gray-100 pt-5">
                    <h3 class="text-sm font-semibold text-gray-900 mb-2">Deskripsi Fasilitas</h3>
                    <p class="text-gray-600 text-sm leading-relaxed italic">{{ $facility->deskripsi }}</p>
                </div>
            </div>
        </div>

        <!-- Right Column: Slots & Form -->
        <div class="w-full lg:w-7/12" x-data="slotGrid()">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Ketersediaan Slot</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Pilih tanggal dan slot waktu operasional (07:00 – 18:00 WIB)</p>
                        <p class="text-xs text-teal-700 font-semibold mt-1 flex items-center gap-1">
                            <i class="ph ph-clock"></i> Sekarang: <span x-text="clockLabel"></span> WIB
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="text-xs text-gray-500 font-medium">Tanggal:</label>
                        <input type="date" x-model="selectedDate" @change="fetchSlots()" :min="todayDate" min="{{ now()->format('Y-m-d') }}" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-teal-500 focus:border-teal-500 shadow-sm font-medium text-gray-700">
                    </div>
                </div>

                <!-- Warning Notice for Past Date / Time -->
                <div x-show="pastDateWarning" x-transition.duration.300ms style="display: none;" class="mb-5 bg-amber-50 border border-amber-300 text-amber-900 px-4 py-3 rounded-xl flex items-start gap-3 text-sm shadow-sm">
                    <i class="ph ph-warning-circle text-amber-600 text-2xl shrink-0 mt-0.5"></i>
                    <div>
                        <p class="font-semibold text-amber-900">Peringatan: Tidak dapat memilih waktu di masa lampau!</p>
                        <p class="text-xs text-amber-800 mt-0.5">Sistem telah mengembalikan pilihan ke tanggal hari ini secara otomatis. Reservasi hanya dapat diajukan untuk hari ini atau hari mendatang.</p>
                    </div>
                </div>

                <!-- Toast Notice for Clicking Unavailable Slot -->
                <div x-show="unavailableNotice" x-transition.duration.200ms style="display: none;" class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-2.5 rounded-xl flex items-center gap-2 text-xs font-medium">
                    <i class="ph ph-prohibit text-red-500 text-base"></i>
                    <span x-text="unavailableNotice"></span>
                </div>

                <!-- Loader -->
                <div x-show="loading" class="py-12 flex justify-center items-center">
                    <svg class="animate-spin h-8 w-8 text-teal-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>

                <!-- Grid -->
                <div x-show="!loading" style="display: none;" x-transition>
                    <template x-if="slots.length === 0">
                        <div class="text-center py-8 text-gray-500 text-sm">
                            Tidak ada slot tersedia untuk tanggal ini.
                        </div>
                    </template>
                    
                    <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-3">
                        <template x-for="slot in slots" :key="slot.time">
                            <button
                                type="button"
                                @click="handleSlotClick(slot)"
                                :disabled="!slot.available"
                                :title="slot.state === 'booked' ? 'Sudah dipesan' : (slot.state === 'past' ? 'Waktu sudah lewat' : 'Tersedia')"
                                :class="{
                                    'border-emerald-400 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 cursor-pointer': slot.available && selectedSlot !== slot.time,
                                    'border-teal-600 bg-teal-600 text-white shadow-md': slot.available && selectedSlot === slot.time,
                                    'border-red-200 bg-red-50/70 text-red-400 cursor-not-allowed opacity-70': slot.state === 'booked',
                                    'border-gray-200 bg-gray-100 text-gray-400 cursor-not-allowed opacity-60 line-through': slot.state === 'past'
                                }"
                                class="border-2 rounded-xl px-2 py-2.5 text-center text-sm font-medium transition-all flex flex-col items-center leading-tight">
                                <span x-text="slot.time"></span>
                                <span x-show="slot.state === 'booked'" class="text-[10px] font-semibold uppercase tracking-wide no-underline">Dipesan</span>
                            </button>
                        </template>
                    </div>

                    <div class="flex flex-wrap gap-6 mt-6 pt-6 border-t border-gray-100 text-sm text-gray-600 font-medium justify-center">
                        <span class="flex items-center gap-2"><span class="w-4 h-4 border-2 border-emerald-400 bg-emerald-50 rounded"></span> Tersedia</span>
                        <span class="flex items-center gap-2"><span class="w-4 h-4 border-2 border-teal-600 bg-teal-600 rounded"></span> Dipilih</span>
                        <span class="flex items-center gap-2"><span class="w-4 h-4 border-2 border-red-200 bg-red-50 rounded"></span> Sudah Dipesan</span>
                        <span class="flex items-center gap-2"><span class="w-4 h-4 border-2 border-gray-200 bg-gray-100 rounded"></span> Sudah Lewat</span>
                    </div>

                    <button 
                        @click="openModal()" 
                        :disabled="!selectedSlot"
                        :class="selectedSlot ? 'bg-teal-600 hover:bg-teal-700 shadow-md' : 'bg-gray-300 cursor-not-allowed'"
                        class="mt-8 w-full text-white rounded-xl py-3.5 font-semibold text-center transition-colors">
                        Lanjutkan Reservasi
                    </button>
                </div>
            </div>

            <!-- Modal Form -->
            <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                    <!-- Overlay -->
                    <div x-show="showModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-gray-900/50 backdrop-blur-sm" @click="showModal = false"></div>

                    <!-- Card -->
                    <div x-show="showModal" 
                         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         class="relative inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                        
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="flex justify-between items-center mb-5 pb-4 border-b border-gray-100">
                                <h3 class="text-xl leading-6 font-bold text-gray-900">Formulir Reservasi</h3>
                                <button @click="showModal = false" class="text-gray-400 hover:text-gray-500 p-1 rounded-full hover:bg-gray-100 transition-colors">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>

                            <!-- Notif file terlalu besar / bukan PDF -->
                            <div x-show="fileNotice" x-transition.duration.200ms style="display: none;" class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-2.5 rounded-xl flex items-center gap-2 text-sm font-semibold">
                                <i class="ph ph-warning-circle text-red-500 text-lg"></i>
                                <span x-text="fileNotice"></span>
                            </div>

                            <form action="{{ route('reservations.store') }}" method="POST" enctype="multipart/form-data" id="reservation-form" @submit="validateFiles($event)">
                                @csrf
                                <input type="hidden" name="facility_id" value="{{ $facility->id }}">
                                
                                <div class="mb-4">
                                    <label class="block font-semibold text-sm text-gray-700 mb-1">Tanggal</label>
                                    <input type="date" name="date" x-model="selectedDate" readonly class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 text-gray-700 shadow-sm">
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block font-semibold text-sm text-gray-700 mb-1">Jam Mulai</label>
                                        <select name="start_time" x-model="formStartTime" @change="updateEndTime()" required class="w-full border border-gray-300 rounded-lg px-3 py-2 shadow-sm focus:ring-teal-500 focus:border-teal-500 text-sm">
                                            <option value="">Pilih Jam Mulai...</option>
                                            <template x-for="slot in availableStartSlots()" :key="'start-'+slot.time">
                                                <option :value="slot.time" x-text="slot.time + ' WIB'"></option>
                                            </template>
                                        </select>
                                        @error('start_time') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block font-semibold text-sm text-gray-700 mb-1">Jam Selesai</label>
                                        <select name="end_time" x-model="formEndTime" required class="w-full border border-gray-300 rounded-lg px-3 py-2 shadow-sm focus:ring-teal-500 focus:border-teal-500 text-sm">
                                            <option value="">Pilih Jam Selesai...</option>
                                            <template x-for="slot in availableEndSlots()" :key="'end-'+slot.end_time">
                                                <option :value="slot.end_time" x-text="slot.end_time + ' WIB'"></option>
                                            </template>
                                        </select>
                                        @error('end_time') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="block font-semibold text-sm text-gray-700 mb-1">Keperluan</label>
                                    <textarea name="purpose" rows="3" required class="w-full border border-gray-300 rounded-lg px-3 py-2 shadow-sm focus:ring-teal-500 focus:border-teal-500 text-sm" placeholder="Contoh: Latihan basket tim fakultas">{{ old('purpose') }}</textarea>
                                    @error('purpose') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="block font-semibold text-sm text-gray-700 mb-1">Proposal Kegiatan <span class="text-red-500">*</span></label>
                                    <input type="file" name="proposal_kegiatan" accept=".pdf" required @change="checkFile($event)"class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 shadow-sm">
                                    <p class="text-xs text-gray-400 mt-1.5 flex items-center gap-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Wajib format PDF, maksimal 5MB</p>
                                    <span x-show="fileErrors.proposal_kegiatan" x-text="fileErrors.proposal_kegiatan" class="text-red-500 text-xs mt-1 block" style="display: none;"></span>
                                    @error('proposal_kegiatan') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div class="mb-5">
                                    <label class="block font-semibold text-sm text-gray-700 mb-1">Permohonan Peminjaman <span class="text-red-500">*</span></label>
                                    <input type="file" name="proposal_permohonan" accept=".pdf" required @change="checkFile($event)"class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 shadow-sm">
                                    <p class="text-xs text-gray-400 mt-1.5 flex items-center gap-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Wajib format PDF, maksimal 5MB dari Fakultas/UKM</p>
                                    <span x-show="fileErrors.proposal_permohonan" x-text="fileErrors.proposal_permohonan" class="text-red-500 text-xs mt-1 block" style="display: none;"></span>
                                    @error('proposal_permohonan') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                                    <button type="button" @click="showModal = false" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium px-6 py-2.5 rounded-lg transition-colors">Batal</button>
                                    <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-medium px-6 py-2.5 rounded-lg shadow-sm transition-colors">Kirim Reservasi</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alpine Script -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('slotGrid', () => ({
                selectedDate: '{{ $date }}',
                todayDate: '{{ now()->format('Y-m-d') }}',
                slots: @json($slots ?? []),
                showModal: {{ $errors->any() ? 'true' : 'false' }},
                selectedSlot: null,
                formStartTime: '',
                formEndTime: '',
                loading: false,
                pastDateWarning: false,
                unavailableNotice: null,
                // Selisih jam browser terhadap jam server, agar jam yang dipakai selalu jam server (WIB)
                serverOffset: {{ now()->getTimestampMs() }} - Date.now(),
                clockLabel: '',

                init() {
                    if(this.slots.length === 0) {
                        this.fetchSlots();
                    }
                    // Pulihkan jam yang tadi dipilih hanya jika tanggalnya masih sama
                    // (tanggal lampau sudah dikembalikan ke hari ini oleh server)
                    const oldInput = @js(['date' => old('date'), 'start' => old('start_time'), 'end' => old('end_time')]);
                    if ({{ $errors->any() ? 'true' : 'false' }} && oldInput.start && oldInput.date === this.selectedDate) {
                        this.selectedSlot = oldInput.start;
                        this.formStartTime = oldInput.start;
                        this.formEndTime = oldInput.end || '';
                        this.dropUnavailableSelection();
                    }

                    this.tick();
                    setInterval(() => this.tick(), 1000);
                    // Muat ulang slot berkala supaya pesanan orang lain langsung terlihat
                    setInterval(() => { if (!this.showModal) this.fetchSlots(true); }, 30000);
                },

                jakartaNow() {
                    const parts = Object.fromEntries(
                        new Intl.DateTimeFormat('en-GB', {
                            timeZone: 'Asia/Jakarta', hourCycle: 'h23',
                            year: 'numeric', month: '2-digit', day: '2-digit',
                            hour: '2-digit', minute: '2-digit', second: '2-digit',
                        }).formatToParts(new Date(Date.now() + this.serverOffset)).map(p => [p.type, p.value])
                    );
                    return {
                        date: `${parts.year}-${parts.month}-${parts.day}`,
                        time: `${parts.hour}:${parts.minute}`,
                        seconds: parts.second,
                    };
                },

                tick() {
                    const now = this.jakartaNow();
                    this.clockLabel = `${now.time}:${now.seconds}`;

                    // Ganti hari: tanggal minimum ikut maju
                    if (now.date !== this.todayDate) {
                        this.todayDate = now.date;
                        if (this.selectedDate < this.todayDate) {
                            this.fetchSlots();
                            return;
                        }
                    }

                    // Tandai slot yang baru saja lewat tanpa menunggu refresh dari server
                    if (this.selectedDate !== now.date) return;
                    this.slots.forEach(slot => {
                        if (slot.state !== 'past' && slot.time <= now.time) {
                            slot.state = 'past';
                            slot.available = false;
                        }
                    });
                    this.dropUnavailableSelection();
                },

                dropUnavailableSelection() {
                    if (!this.selectedSlot) return;
                    const current = this.slots.find(s => s.time === this.selectedSlot);
                    if (current && current.available) return;

                    this.unavailableNotice = `Slot pukul ${this.selectedSlot} WIB sudah tidak tersedia, silakan pilih slot lain.`;
                    setTimeout(() => { this.unavailableNotice = null; }, 4000);
                    this.selectedSlot = null;
                    this.formStartTime = '';
                    this.formEndTime = '';
                },

                async fetchSlots(silent = false) {
                    if (this.selectedDate < this.todayDate) {
                        this.pastDateWarning = true;
                        this.selectedDate = this.todayDate;
                        setTimeout(() => { this.pastDateWarning = false; }, 5000);
                    } else if (!silent) {
                        this.pastDateWarning = false;
                    }

                    if (!silent) {
                        this.loading = true;
                        this.selectedSlot = null;
                    }
                    try {
                        const response = await fetch(`{{ url('/facilities/' . $facility->id . '/slots') }}?date=${this.selectedDate}`);
                        if(response.ok) {
                            this.slots = await response.json();
                            this.dropUnavailableSelection();
                        }
                    } catch(e) {
                        console.error("Gagal memuat slot jadwal.", e);
                    } finally {
                        this.loading = false;
                    }
                },

                handleSlotClick(slot) {
                    if (!slot.available) return;
                    this.selectSlot(slot.time);
                    this.unavailableNotice = null;
                },

                selectSlot(time) {
                    this.selectedSlot = time;
                    this.formStartTime = time;
                    const found = this.slots.find(s => s.time === time);
                    if (found) {
                        this.formEndTime = found.end_time;
                    }
                },

                availableStartSlots() {
                    return this.slots.filter(s => s.available);
                },

                // Jam selesai hanya boleh sampai sebelum slot berikutnya yang sudah dipesan
                availableEndSlots() {
                    const startIndex = this.slots.findIndex(s => s.time === this.formStartTime);
                    if (startIndex === -1) {
                        return [];
                    }
                    const result = [];
                    for (let i = startIndex; i < this.slots.length && this.slots[i].available; i++) {
                        result.push(this.slots[i]);
                    }
                    return result;
                },

                updateEndTime() {
                    if (this.formStartTime) {
                        const found = this.slots.find(s => s.time === this.formStartTime);
                        const validEnd = this.availableEndSlots().some(s => s.end_time === this.formEndTime);
                        if (found && !validEnd) {
                            this.formEndTime = found.end_time;
                        }
                    }
                },

                fileErrors: {},
                fileNotice: null,
                fileNoticeTimer: null,

                // Batas 5MB per dokumen, sama dengan validasi di server
                checkFile(event) {
                    const input = event.target;
                    const file = input.files[0];
                    let message = null;
                    if (file && file.type !== 'application/pdf' && !file.name.toLowerCase().endsWith('.pdf')) {
                        message = 'File harus berformat PDF.';
                    } else if (file && file.size > 5 * 1024 * 1024) {
                        message = 'File hanya bisa max 5MB';
                    }
                    this.fileErrors = { ...this.fileErrors, [input.name]: message };
                    if (message) {
                        this.fileNotice = message;
                        clearTimeout(this.fileNoticeTimer);
                        this.fileNoticeTimer = setTimeout(() => { this.fileNotice = null; }, 4000);
                    }
                    if (message) input.value = '';
                    return !message;
                },

                validateFiles(event) {
                    const inputs = event.target.querySelectorAll('input[type=file]');
                    const valid = [...inputs].map(input => this.checkFile({ target: input })).every(Boolean);
                    if (!valid) event.preventDefault();
                },

                openModal() {
                    if(!this.selectedSlot) return;
                    this.showModal = true;
                }
            }));
        });
    </script>
</x-catalog-layout>
