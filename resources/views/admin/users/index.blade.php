@php
    $roleMeta = [
        'admin' => 'bg-indigo-100 text-indigo-700',
        'petugas' => 'bg-sky-100 text-sky-700',
        'pengguna' => 'bg-slate-200 text-slate-600',
    ];
    $statusMeta = [
        'verified' => 'bg-emerald-100 text-emerald-700',
        'pending' => 'bg-amber-100 text-amber-700',
        'rejected' => 'bg-rose-100 text-rose-700',
    ];
@endphp

<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <p class="text-xs font-semibold tracking-wide text-emerald-700 uppercase">Manajemen Pengguna</p>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 mt-1">Kelola Akun</h1>
                <p class="text-slate-500 mt-1">Daftar seluruh akun admin, petugas, dan pengguna dalam sistem.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.users.create-petugas') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-emerald-800 rounded-xl font-semibold text-sm text-white shadow-sm hover:bg-emerald-900 transition">
                    + Akun Petugas
                </a>
                <a href="{{ route('admin.users.create-pengguna') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-white ring-1 ring-slate-200 rounded-xl font-semibold text-sm text-slate-700 shadow-sm hover:bg-slate-50 transition">
                    + Akun Pengguna
                </a>
            </div>
        </div>
    </x-slot>

    @if (session('status'))
        <div class="mb-4 rounded-xl bg-emerald-50 px-4 py-3 text-sm text-emerald-700 ring-1 ring-emerald-200">
            {{ session('status') }}
        </div>
    @endif

    <form method="GET" class="mb-6 flex flex-wrap gap-3 items-end bg-white rounded-2xl ring-1 ring-slate-900/5 p-4">
        <div>
            <label class="block text-xs font-medium text-slate-500 mb-1">Role</label>
            <select name="role" class="rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                <option value="">Semua</option>
                @foreach (['admin', 'petugas', 'pengguna'] as $role)
                    <option value="{{ $role }}" @selected(request('role') === $role)>{{ ucfirst($role) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-slate-500 mb-1">Status</label>
            <select name="status" class="rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                <option value="">Semua</option>
                @foreach (['pending', 'verified', 'rejected'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-slate-800 rounded-lg text-sm font-medium text-white hover:bg-slate-700 transition">Filter</button>
    </form>

    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-900/5 divide-y divide-slate-100 overflow-hidden">
        @forelse ($users as $user)
            <div class="flex items-center justify-between gap-4 px-5 py-4">
                <div class="flex items-center gap-3 min-w-0">
                    <span class="flex items-center justify-center w-10 h-10 rounded-full bg-emerald-50 text-emerald-800 font-semibold shrink-0">
                        {{ Str::of($user->name)->substr(0, 1)->upper() }}
                    </span>
                    <div class="min-w-0">
                        <p class="font-medium text-slate-900 truncate">{{ $user->name }}</p>
                        <p class="text-sm text-slate-500 truncate">{{ $user->email }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $roleMeta[$user->role] }}">{{ ucfirst($user->role) }}</span>
                    <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $statusMeta[$user->status] }}">{{ ucfirst($user->status) }}</span>
                </div>
            </div>
        @empty
            <div class="px-5 py-10 text-center text-slate-500">Belum ada data akun.</div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $users->links() }}
    </div>
</x-admin-layout>
