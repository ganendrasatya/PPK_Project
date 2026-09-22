<x-admin-layout>
    <x-slot name="header">
        <p class="text-xs font-semibold tracking-wide text-emerald-700 uppercase">Manajemen Pengguna</p>
        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 mt-1">Tambah Akun Pengguna</h1>
        <p class="text-slate-500 mt-1">Buat akun pengguna langsung tanpa melalui form registrasi mandiri.</p>
    </x-slot>

    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-900/5 p-6 max-w-lg">
        <form method="POST" action="{{ route('admin.users.store-pengguna') }}">
            @csrf
            @include('admin.users._account-form')

            <div class="mt-6 flex items-center gap-3">
                <button type="submit" class="px-5 py-2.5 bg-emerald-800 rounded-xl font-semibold text-sm text-white shadow-sm hover:bg-emerald-900 transition">
                    {{ __('Buat Akun Pengguna') }}
                </button>
                <a href="{{ route('admin.users.index') }}" class="text-sm text-slate-500 hover:text-slate-800">Batal</a>
            </div>
        </form>
    </div>
</x-admin-layout>
