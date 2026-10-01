<x-admin-layout>
    <x-slot name="header">
        <p class="text-xs font-semibold tracking-wider text-teal-600 uppercase">Data Master</p>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">Edit Fasilitas</h1>
        <p class="text-gray-500 mt-1">Perbarui detail fasilitas "{{ $facility->nama_fasilitas }}".</p>
    </x-slot>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 max-w-2xl">
        <form method="POST" action="{{ route('admin.facilities.update', $facility) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.facilities._form')

            <div class="mt-6 flex items-center gap-3">
                <button type="submit" class="px-5 py-2.5 bg-teal-600 rounded-lg font-semibold text-sm text-white shadow-sm hover:bg-teal-700 transition">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.facilities.index') }}" class="px-4 py-2.5 bg-white border border-gray-300 rounded-lg font-semibold text-sm text-gray-700 hover:bg-gray-50 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-admin-layout>
