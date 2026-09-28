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
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="text-xs text-gray-500 font-medium">Tanggal:</label>
                        <input type="date" x-model="selectedDate" @change="fetchSlots()" min="{{ date('Y-m-d') }}" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-teal-500 focus:border-teal-500 shadow-sm font-medium text-gray-700">
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
                                :class="{
                                    'border-emerald-400 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 cursor-pointer': slot.available && selectedSlot !== slot.time,
                                    'border-teal-600 bg-teal-600 text-white shadow-md': slot.available && selectedSlot === slot.time,
                                    'border-red-200 bg-red-50/70 text-red-400 cursor-not-allowed opacity-60 line-through': !slot.available
                                }"
                                class="border-2 rounded-xl px-2 py-2.5 text-center text-sm font-medium transition-all"
                                x-text="slot.time">
                            </button>
                        </template>
                    </div>

                    <div class="flex flex-wrap gap-6 mt-6 pt-6 border-t border-gray-100 text-sm text-gray-600 font-medium justify-center">
                        <span class="flex items-center gap-2"><span class="w-4 h-4 border-2 border-emerald-400 bg-emerald-50 rounded"></span> Tersedia</span>
                        <span class="flex items-center gap-2"><span class="w-4 h-4 border-2 border-teal-600 bg-teal-600 rounded"></span> Dipilih</span>
                        <span class="flex items-center gap-2"><span class="w-4 h-4 border-2 border-red-200 bg-red-50 rounded line-through"></span> Penuh / Lewat</span>
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

                            <form action="{{ route('reservations.store') }}" method="POST" enctype="multipart/form-data" id="reservation-form">
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
                                    <input type="file" name="proposal_kegiatan" accept=".pdf" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 shadow-sm">
                                    <p class="text-xs text-gray-400 mt-1.5 flex items-center gap-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Wajib format PDF, maksimal 5MB</p>
                                    @error('proposal_kegiatan') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div class="mb-5">
                                    <label class="block font-semibold text-sm text-gray-700 mb-1">Permohonan Peminjaman <span class="text-red-500">*</span></label>
                                    <input type="file" name="proposal_permohonan" accept=".pdf" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 shadow-sm">
                                    <p class="text-xs text-gray-400 mt-1.5 flex items-center gap-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Wajib format PDF, maksimal 5MB dari Fakultas/UKM</p>
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
                todayDate: '{{ date('Y-m-d') }}',
                slots: @json($slots ?? []),
                showModal: {{ $errors->any() ? 'true' : 'false' }},
                selectedSlot: null,
                formStartTime: '{{ old('start_time') }}',
                formEndTime: '{{ old('end_time') }}',
                loading: false,
                pastDateWarning: false,
                unavailableNotice: null,

                init() {
                    if(this.slots.length === 0) {
                        this.fetchSlots();
                    }
                    if({{ $errors->any() ? 'true' : 'false' }} && '{{ old('start_time') }}') {
                        this.selectedSlot = '{{ old('start_time') }}';
                        this.formStartTime = '{{ old('start_time') }}';
                        this.formEndTime = '{{ old('end_time') }}';
                    }
                },

                async fetchSlots() {
                    if (this.selectedDate < this.todayDate) {
                        this.pastDateWarning = true;
                        this.selectedDate = this.todayDate;
                        setTimeout(() => { this.pastDateWarning = false; }, 5000);
                    } else {
                        this.pastDateWarning = false;
                    }

                    this.loading = true;
                    this.selectedSlot = null;
                    try {
                        const response = await fetch(`{{ url('/facilities/' . $facility->id . '/slots') }}?date=${this.selectedDate}`);
                        if(response.ok) {
                            this.slots = await response.json();
                        }
                    } catch(e) {
                        console.error("Gagal memuat slot jadwal.", e);
                    } finally {
                        this.loading = false;
                    }
                },

                handleSlotClick(slot) {
                    if(slot.available) {
                        this.selectSlot(slot.time);
                        this.unavailableNotice = null;
                    } else {
                        this.unavailableNotice = `Slot pukul ${slot.time} WIB tidak dapat dipilih karena waktu telah lewat atau sudah dipesan.`;
                        setTimeout(() => { this.unavailableNotice = null; }, 4000);
                    }
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

                availableEndSlots() {
                    if (!this.formStartTime) {
                        return this.slots;
                    }
                    return this.slots.filter(s => s.end_time > this.formStartTime);
                },

                updateEndTime() {
                    if (this.formStartTime) {
                        const found = this.slots.find(s => s.time === this.formStartTime);
                        if (found && (!this.formEndTime || this.formEndTime <= this.formStartTime)) {
                            this.formEndTime = found.end_time;
                        }
                    }
                },

                openModal() {
                    if(!this.selectedSlot) return;
                    this.showModal = true;
                }
            }));
        });
    </script>
</x-catalog-layout>
