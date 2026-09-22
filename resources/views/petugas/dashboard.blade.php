<x-catalog-layout>
    <div class="max-w-6xl mx-auto px-4 pt-8">
        <span class="text-teal-600 uppercase text-sm font-semibold tracking-wider">PORTAL PETUGAS</span>
        <h1 class="text-3xl font-bold mt-1 text-gray-900">Dashboard Petugas</h1>
        <p class="text-gray-500 mt-1">Tinjau, setujui, atau tolak pengajuan reservasi fasilitas kampus.</p>
    </div>

    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="text-yellow-600 text-sm font-medium uppercase tracking-wide">Menunggu Persetujuan</div>
                <div class="text-4xl font-bold mt-2 text-gray-900">{{ $counts['pending'] }}</div>
                <div class="text-xs text-gray-400 mt-2 font-medium">Reservasi belum diproses</div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="text-green-600 text-sm font-medium uppercase tracking-wide">Disetujui</div>
                <div class="text-4xl font-bold mt-2 text-gray-900">{{ $counts['approved'] }}</div>
                <div class="text-xs text-gray-400 mt-2 font-medium">Total reservasi terjadwal</div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="text-red-500 text-sm font-medium uppercase tracking-wide">Laporan Kerusakan Terbuka</div>
                <div class="text-4xl font-bold mt-2 text-gray-900">{{ $openReports }}</div>
                <div class="text-xs text-gray-400 mt-2 font-medium">
                    <a href="{{ route('reports.index') }}" class="text-teal-600 hover:text-teal-800 font-semibold">Kelola laporan &rarr;</a>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="text-teal-600 text-sm font-medium uppercase tracking-wide">Fasilitas Aktif</div>
                <div class="text-4xl font-bold mt-2 text-gray-900">{{ $activeFacilities }}</div>
                <div class="text-xs text-gray-400 mt-2 font-medium">Siap direservasi pengguna</div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="flex flex-wrap gap-2 mt-8">
            @php
                $tabs = [
                    'pending' => 'Menunggu',
                    'approved' => 'Disetujui',
                    'rejected' => 'Ditolak',
                    'cancelled' => 'Dibatalkan',
                    'semua' => 'Semua',
                ];
            @endphp
            @foreach ($tabs as $tabKey => $tabLabel)
                <a href="{{ route('petugas.dashboard', ['tab' => $tabKey]) }}"
                   class="px-4 py-1.5 rounded-full text-xs font-semibold transition-colors {{ $tab === $tabKey ? 'bg-teal-700 text-white shadow-sm' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                    {{ $tabLabel }} <span class="ml-1 opacity-80">{{ $counts[$tabKey] ?? 0 }}</span>
                </a>
            @endforeach
        </div>

        <!-- Reservation Queue -->
        <div class="mt-6 space-y-4">
            @forelse ($reservations as $reservation)
                <div class="bg-white rounded-xl border border-gray-200 p-5 flex flex-col lg:flex-row lg:items-center justify-between gap-4 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-2 mb-1.5">
                            <span class="bg-gray-100 text-gray-700 text-xs font-semibold px-2 py-0.5 rounded border border-gray-200">
                                #RSV-{{ str_pad($reservation->id, 5, '0', STR_PAD_LEFT) }}
                            </span>

                            @if ($reservation->status === 'pending')
                                <span class="bg-yellow-100 text-yellow-800 border border-yellow-200 text-xs font-semibold px-2.5 py-0.5 rounded-full flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span> Menunggu Persetujuan
                                </span>
                            @elseif ($reservation->status === 'approved')
                                <span class="bg-green-100 text-green-800 border border-green-200 text-xs font-semibold px-2.5 py-0.5 rounded-full flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Disetujui
                                </span>
                            @elseif ($reservation->status === 'rejected')
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
                        <p class="text-sm text-teal-700 font-medium">{{ $reservation->user->name ?? 'Pengguna' }} &middot; {{ $reservation->user->email ?? '' }}</p>

                        <div class="flex flex-wrap gap-4 text-sm text-gray-600 mt-2 font-medium">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                {{ $reservation->start_time->format('d M Y') }}
                            </span>
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $reservation->start_time->format('H:i') }} – {{ $reservation->end_time->format('H:i') }} WIB
                            </span>
                        </div>

                        <p class="text-sm text-gray-500 mt-2 bg-gray-50 p-2 rounded border border-gray-100">
                            <span class="font-medium text-gray-700">Keperluan:</span> {{ $reservation->purpose }}
                        </p>

                        @if ($reservation->status === 'rejected' && $reservation->cancel_reason)
                            <p class="text-xs text-red-700 mt-2 bg-red-50 border border-red-100 p-2 rounded">
                                <span class="font-semibold">Alasan penolakan:</span> {{ $reservation->cancel_reason }}
                            </p>
                        @endif
                    </div>

                    <div class="flex flex-col items-stretch lg:items-end gap-2 shrink-0 self-end lg:self-center">
                        <button type="button" x-data="" @click="$dispatch('open-modal', 'reservation-detail-{{ $reservation->id }}')"
                            class="inline-flex items-center justify-center gap-1.5 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            Detail
                        </button>

                        @if ($reservation->status === 'pending')
                            <div class="flex items-center gap-2">
                                <form method="POST" action="{{ route('reservations.approve', $reservation) }}">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1.5 bg-teal-700 hover:bg-teal-800 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-sm transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                        Setujui
                                    </button>
                                </form>
                                <button type="button" x-data="" @click="$dispatch('open-modal', 'reject-reservation-{{ $reservation->id }}')"
                                    class="inline-flex items-center gap-1.5 bg-white border border-red-200 text-red-600 hover:bg-red-50 text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                    Tolak
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Detail Modal -->
                <x-modal name="reservation-detail-{{ $reservation->id }}" max-width="lg">
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div>
                                <span class="bg-gray-100 text-gray-700 text-xs font-semibold px-2 py-0.5 rounded border border-gray-200">
                                    #RSV-{{ str_pad($reservation->id, 5, '0', STR_PAD_LEFT) }}
                                </span>
                                <h3 class="font-bold text-gray-900 text-lg mt-2">{{ $reservation->facility->nama_fasilitas ?? 'Fasilitas Tidak Diketahui' }}</h3>
                            </div>
                            <button type="button" @click="show = false" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>

                        <dl class="mt-4 grid grid-cols-2 gap-x-4 gap-y-3 text-sm">
                            <div>
                                <dt class="text-gray-400 text-xs uppercase font-semibold">Pemohon</dt>
                                <dd class="text-gray-800 font-medium mt-0.5">{{ $reservation->user->name ?? 'Pengguna' }}</dd>
                                <dd class="text-gray-500 text-xs">{{ $reservation->user->email ?? '' }}</dd>
                            </div>
                            <div>
                                <dt class="text-gray-400 text-xs uppercase font-semibold">Lokasi Fasilitas</dt>
                                <dd class="text-gray-800 font-medium mt-0.5">{{ $reservation->facility->lokasi ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-gray-400 text-xs uppercase font-semibold">Tanggal</dt>
                                <dd class="text-gray-800 font-medium mt-0.5">{{ $reservation->start_time->format('d M Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-gray-400 text-xs uppercase font-semibold">Waktu</dt>
                                <dd class="text-gray-800 font-medium mt-0.5">{{ $reservation->start_time->format('H:i') }} – {{ $reservation->end_time->format('H:i') }} WIB</dd>
                            </div>
                            <div class="col-span-2">
                                <dt class="text-gray-400 text-xs uppercase font-semibold">Keperluan</dt>
                                <dd class="text-gray-700 mt-0.5 bg-gray-50 p-2.5 rounded border border-gray-100">{{ $reservation->purpose }}</dd>
                            </div>
                        </dl>

                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <p class="text-gray-400 text-xs uppercase font-semibold mb-2">Berkas Terlampir</p>
                            <div class="space-y-2">
                                @if ($reservation->proposal_kegiatan_path)
                                    <a href="{{ Storage::url($reservation->proposal_kegiatan_path) }}" target="_blank"
                                       class="flex items-center gap-2.5 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 transition-colors">
                                        <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6 15h1.5m-9-1.5h6" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-6-6H6a2.25 2.25 0 00-2.25 2.25v15A2.25 2.25 0 006 21h12a2.25 2.25 0 002.25-2.25V8.25z" /></svg>
                                        <span class="flex-1 truncate">Proposal Kegiatan</span>
                                        <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" /></svg>
                                    </a>
                                @endif
                                @if ($reservation->proposal_permohonan_path)
                                    <a href="{{ Storage::url($reservation->proposal_permohonan_path) }}" target="_blank"
                                       class="flex items-center gap-2.5 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 transition-colors">
                                        <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6 15h1.5m-9-1.5h6" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-6-6H6a2.25 2.25 0 00-2.25 2.25v15A2.25 2.25 0 006 21h12a2.25 2.25 0 002.25-2.25V8.25z" /></svg>
                                        <span class="flex-1 truncate">Surat Permohonan Peminjaman</span>
                                        <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" /></svg>
                                    </a>
                                @endif
                                @if (! $reservation->proposal_kegiatan_path && ! $reservation->proposal_permohonan_path)
                                    <p class="text-sm text-gray-400 italic">Tidak ada berkas yang dilampirkan.</p>
                                @endif
                            </div>
                        </div>

                        @if ($reservation->status === 'rejected' && $reservation->cancel_reason)
                            <p class="text-xs text-red-700 mt-4 bg-red-50 border border-red-100 p-2.5 rounded">
                                <span class="font-semibold">Alasan penolakan:</span> {{ $reservation->cancel_reason }}
                            </p>
                        @endif

                        @if ($reservation->status === 'pending')
                            <div class="mt-6 flex items-center justify-end gap-2 pt-4 border-t border-gray-100">
                                <button type="button" x-data=""
                                    @click="$dispatch('close-modal', 'reservation-detail-{{ $reservation->id }}'); $dispatch('open-modal', 'reject-reservation-{{ $reservation->id }}')"
                                    class="bg-white border border-red-200 text-red-600 hover:bg-red-50 text-sm font-semibold px-4 py-2 rounded-lg transition-colors">Tolak</button>
                                <form method="POST" action="{{ route('reservations.approve', $reservation) }}">
                                    @csrf
                                    <button type="submit" class="bg-teal-700 hover:bg-teal-800 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-sm transition-colors">Setujui</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </x-modal>

                <!-- Reject Reason Modal -->
                <x-modal name="reject-reservation-{{ $reservation->id }}" max-width="md">
                    <form method="POST" action="{{ route('reservations.reject', $reservation) }}" class="p-6">
                        @csrf
                        <h3 class="font-bold text-gray-900 text-lg">Tolak Reservasi</h3>
                        <p class="text-sm text-gray-500 mt-1">
                            Reservasi <span class="font-semibold text-gray-700">#RSV-{{ str_pad($reservation->id, 5, '0', STR_PAD_LEFT) }}</span>
                            untuk <span class="font-semibold text-gray-700">{{ $reservation->facility->nama_fasilitas ?? 'fasilitas ini' }}</span>
                            akan ditolak. Berikan alasan agar pemohon mengetahui tindak lanjutnya.
                        </p>

                        <div class="mt-4">
                            <label class="block font-semibold text-sm text-gray-700 mb-1">Alasan Penolakan <span class="text-red-500">*</span></label>
                            <textarea name="reason" rows="3" required maxlength="255"
                                placeholder="Contoh: Jadwal bentrok dengan kegiatan lain, dokumen tidak lengkap, dsb."
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm shadow-sm focus:ring-red-500 focus:border-red-500"></textarea>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-2">
                            <button type="button" @click="show = false" class="text-sm font-semibold text-gray-600 hover:text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
                                Batal
                            </button>
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-sm transition-colors">
                                Tolak Reservasi
                            </button>
                        </div>
                    </form>
                </x-modal>
            @empty
                <div class="text-center py-16 bg-white rounded-xl border border-dashed border-gray-300">
                    <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h3 class="text-lg font-medium text-gray-900">Tidak ada reservasi pada status ini</h3>
                    <p class="mt-1 text-sm text-gray-500">Antrean akan muncul di sini ketika ada pengajuan baru.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-6 pb-6">
            {{ $reservations->links() }}
        </div>
    </div>
</x-catalog-layout>
