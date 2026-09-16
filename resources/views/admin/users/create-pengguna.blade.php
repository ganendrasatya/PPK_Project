<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Tambah Akun Pengguna') }}</h2>
    </x-slot>

    <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-900/5 p-6 max-w-lg">
        <form method="POST" action="{{ route('admin.users.store-pengguna') }}">
            @csrf
            @include('admin.users._account-form')

            <div class="mt-6 flex items-center gap-3">
                <x-primary-button>{{ __('Buat Akun Pengguna') }}</x-primary-button>
                <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Batal</a>
            </div>
        </form>
    </div>
</x-admin-layout>
