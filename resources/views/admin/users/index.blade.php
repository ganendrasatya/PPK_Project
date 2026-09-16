<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Kelola Akun') }}</h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.users.create-petugas') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 rounded-lg font-semibold text-sm text-white shadow-sm hover:bg-indigo-500">
                    {{ __('+ Akun Petugas') }}
                </a>
                <a href="{{ route('admin.users.create-pengguna') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 rounded-lg font-semibold text-sm text-white shadow-sm hover:bg-gray-700">
                    {{ __('+ Akun Pengguna') }}
                </a>
            </div>
        </div>
    </x-slot>

    @if (session('status'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700 ring-1 ring-green-200">
            {{ session('status') }}
        </div>
    @endif

    <form method="GET" class="mb-4 flex flex-wrap gap-3 items-end">
        <div>
            <x-input-label for="role" value="Role" />
            <select id="role" name="role" class="mt-1 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                <option value="">Semua</option>
                @foreach (['admin', 'petugas', 'pengguna'] as $role)
                    <option value="{{ $role }}" @selected(request('role') === $role)>{{ ucfirst($role) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <x-input-label for="status" value="Status" />
            <select id="status" name="status" class="mt-1 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                <option value="">Semua</option>
                @foreach (['pending', 'verified', 'rejected'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>
        <x-secondary-button type="submit">Filter</x-secondary-button>
    </form>

    <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-900/5 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Nama</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Email</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Role</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($users as $user)
                    <tr>
                        <td class="px-4 py-3 text-gray-900">{{ $user->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $user->email }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ ucfirst($user->role) }}</td>
                        <td class="px-4 py-3">
                            <span @class([
                                'px-2 py-1 rounded-full text-xs font-medium',
                                'bg-green-100 text-green-700' => $user->status === 'verified',
                                'bg-yellow-100 text-yellow-700' => $user->status === 'pending',
                                'bg-red-100 text-red-700' => $user->status === 'rejected',
                            ])>{{ ucfirst($user->status) }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-gray-500">Belum ada data akun.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</x-admin-layout>
