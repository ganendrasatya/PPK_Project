@php($facility = $facility ?? null)

<div>
    <x-input-label for="nama_fasilitas" value="Nama Fasilitas" />
    <x-text-input id="nama_fasilitas" name="nama_fasilitas" type="text" class="block mt-1 w-full"
        value="{{ old('nama_fasilitas', $facility?->nama_fasilitas) }}" required autofocus />
    <x-input-error :messages="$errors->get('nama_fasilitas')" class="mt-2" />
</div>

<div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <x-input-label for="tipe" value="Tipe" />
        <x-text-input id="tipe" name="tipe" type="text" class="block mt-1 w-full"
            value="{{ old('tipe', $facility?->tipe) }}" required placeholder="Ruang kelas, aula, lab, dsb." />
        <x-input-error :messages="$errors->get('tipe')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="lokasi" value="Lokasi" />
        <x-text-input id="lokasi" name="lokasi" type="text" class="block mt-1 w-full"
            value="{{ old('lokasi', $facility?->lokasi) }}" required />
        <x-input-error :messages="$errors->get('lokasi')" class="mt-2" />
    </div>
</div>

<div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <x-input-label for="kapasitas" value="Kapasitas" />
        <x-text-input id="kapasitas" name="kapasitas" type="number" min="1" class="block mt-1 w-full"
            value="{{ old('kapasitas', $facility?->kapasitas) }}" required />
        <x-input-error :messages="$errors->get('kapasitas')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="status" value="Status" />
        <select id="status" name="status" required class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm py-2.5">
            @foreach (['aktif' => 'Aktif', 'nonaktif' => 'Nonaktif', 'dalam_perbaikan' => 'Dalam Perbaikan'] as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $facility?->status ?? 'aktif') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('status')" class="mt-2" />
    </div>
</div>

<div class="mt-4">
    <x-input-label for="deskripsi" value="Deskripsi (opsional)" />
    <textarea id="deskripsi" name="deskripsi" rows="3"
        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm">{{ old('deskripsi', $facility?->deskripsi) }}</textarea>
    <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
</div>
