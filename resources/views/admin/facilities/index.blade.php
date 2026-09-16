<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Kelola Fasilitas') }}</h2>
            <a href="{{ route('admin.facilities.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 rounded-lg font-semibold text-sm text-white shadow-sm hover:bg-indigo-500">
                {{ __('+ Tambah Fasilitas') }}
            </a>
        </div>
    </x-slot>

    @if (session('status'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700 ring-1 ring-green-200">
            {{ session('status') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-900/5 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Nama</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Tipe</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Lokasi</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Kapasitas</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Status</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($facilities as $facility)
                    <tr>
                        <td class="px-4 py-3 text-gray-900">{{ $facility->nama_fasilitas }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $facility->tipe }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $facility->lokasi }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $facility->kapasitas }}</td>
                        <td class="px-4 py-3">
                            <form method="POST" action="{{ route('admin.facilities.update-status', $facility) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()" class="text-xs rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="aktif" @selected($facility->status === 'aktif')>Aktif</option>
                                    <option value="nonaktif" @selected($facility->status === 'nonaktif')>Nonaktif</option>
                                    <option value="dalam_perbaikan" @selected($facility->status === 'dalam_perbaikan')>Dalam Perbaikan</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="{{ route('admin.facilities.edit', $facility) }}" class="text-indigo-600 hover:text-indigo-800">Edit</a>
                            <form method="POST" action="{{ route('admin.facilities.destroy', $facility) }}" class="inline" onsubmit="return confirm('Hapus fasilitas ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">Belum ada data fasilitas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $facilities->links() }}
    </div>
</x-admin-layout>
