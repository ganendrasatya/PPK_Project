<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Verifikasi Registrasi Mandiri') }}</h2>
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
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Email</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Terdaftar</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($users as $user)
                    <tr>
                        <td class="px-4 py-3 text-gray-900">{{ $user->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $user->email }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $user->created_at->format('d M Y H:i') }}</td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <form method="POST" action="{{ route('admin.users.approve', $user) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-green-700 hover:text-green-900 font-medium">Verifikasi</button>
                            </form>
                            <form method="POST" action="{{ route('admin.users.reject', $user) }}" class="inline" onsubmit="return confirm('Tolak akun ini?');">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Tolak</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-gray-500">Tidak ada registrasi yang menunggu verifikasi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</x-admin-layout>
