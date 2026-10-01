@php($facility = $facility ?? null)
@php($inputClass = 'block mt-1 w-full rounded-lg border-gray-300 shadow-sm py-2.5 text-sm focus:border-teal-500 focus:ring-teal-500 transition')

<div>
    <x-input-label for="nama_fasilitas" value="Nama Fasilitas" />
    <input id="nama_fasilitas" name="nama_fasilitas" type="text" class="{{ $inputClass }}"
        value="{{ old('nama_fasilitas', $facility?->nama_fasilitas) }}" required autofocus placeholder="Contoh: Ruang Seminar A101">
    <x-input-error :messages="$errors->get('nama_fasilitas')" class="mt-2" />
</div>

<div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <x-input-label for="tipe" value="Tipe Fasilitas" />
        <input id="tipe" name="tipe" type="text" class="{{ $inputClass }}"
            value="{{ old('tipe', $facility?->tipe) }}" required placeholder="Ruang Kelas, Aula, Laboratorium, Lapangan, dsb.">
        <x-input-error :messages="$errors->get('tipe')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="lokasi" value="Lokasi / Gedung" />
        <input id="lokasi" name="lokasi" type="text" class="{{ $inputClass }}"
            value="{{ old('lokasi', $facility?->lokasi) }}" required placeholder="Contoh: Gedung B Lantai 2">
        <x-input-error :messages="$errors->get('lokasi')" class="mt-2" />
    </div>
</div>

<div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <x-input-label for="kapasitas" value="Kapasitas (Orang)" />
        <input id="kapasitas" name="kapasitas" type="number" min="1" class="{{ $inputClass }}"
            value="{{ old('kapasitas', $facility?->kapasitas) }}" required placeholder="Contoh: 50">
        <x-input-error :messages="$errors->get('kapasitas')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="status" value="Status Operasional" />
        <div class="mt-1">
            <x-select-dropdown name="status" :nullable="false"
                :options="['aktif' => 'Aktif', 'nonaktif' => 'Nonaktif', 'dalam_perbaikan' => 'Dalam Perbaikan']"
                :selected="old('status', $facility?->status ?? 'aktif')" />
        </div>
        <x-input-error :messages="$errors->get('status')" class="mt-2" />
    </div>
</div>

<div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <x-input-label for="jam_buka" value="Jam Buka Operasional" />
        <input id="jam_buka" name="jam_buka" type="time" class="{{ $inputClass }}"
            value="{{ old('jam_buka', $facility?->jam_buka ? \Carbon\Carbon::parse($facility->jam_buka)->format('H:i') : '07:00') }}" required>
        <x-input-error :messages="$errors->get('jam_buka')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="jam_tutup" value="Jam Tutup Operasional" />
        <input id="jam_tutup" name="jam_tutup" type="time" class="{{ $inputClass }}"
            value="{{ old('jam_tutup', $facility?->jam_tutup ? \Carbon\Carbon::parse($facility->jam_tutup)->format('H:i') : '18:00') }}" required>
        <x-input-error :messages="$errors->get('jam_tutup')" class="mt-2" />
    </div>
</div>

<div class="mt-4">
    <x-input-label for="image" value="Foto Fasilitas (opsional)" />
    @if($facility?->image_path)
        <div class="mt-2 mb-3 flex items-center gap-3">
            <img src="{{ Storage::url($facility->image_path) }}" alt="{{ $facility->nama_fasilitas }}" class="w-20 h-16 object-cover rounded-lg border border-gray-200">
            <span class="text-xs text-gray-500">Foto saat ini terpasang. Unggah file baru di bawah ini untuk menggantinya.</span>
        </div>
    @endif
    <input id="image" name="image" type="file" accept="image/*"
        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 border border-gray-300 rounded-lg p-1.5 focus:border-teal-500 focus:ring-teal-500 transition">
    <x-input-error :messages="$errors->get('image')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="deskripsi" value="Deskripsi (opsional)" />
    <textarea id="deskripsi" name="deskripsi" rows="3" class="{{ $inputClass }}"
        placeholder="Informasi sarana prasarana, fasilitas penunjang, proyektor, AC, sound system, dsb.">{{ old('deskripsi', $facility?->deskripsi) }}</textarea>
    <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
</div>
