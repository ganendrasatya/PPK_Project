<x-admin-layout>
    <x-slot name="header">
        <p class="text-xs font-semibold tracking-wide text-teal-600 uppercase">Manajemen Pengguna</p>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">Tambah Akun {{ ucfirst($role) }}</h1>
        <p class="text-gray-500 mt-1">
            {{ $role === 'petugas' ? 'Petugas tidak melakukan registrasi mandiri — akun dibuat langsung oleh admin.' : 'Buat akun pengguna langsung tanpa melalui form registrasi mandiri.' }}
        </p>
    </x-slot>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 max-w-lg">
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            <input type="hidden" name="role" value="{{ $role }}">
            @include('admin.users._account-form')

            <div class="mt-6 flex items-center gap-3">
                <button type="submit" class="px-5 py-2.5 bg-teal-600 rounded-lg font-semibold text-sm text-white shadow-sm hover:bg-teal-700 transition">
                    {{ __('Buat Akun ') . ucfirst($role) }}
                </button>
                <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500 hover:text-gray-800">Batal</a>
            </div>
        </form>
    </div>
</x-admin-layout>
