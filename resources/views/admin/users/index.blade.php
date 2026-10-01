@php
    $roleMeta = [
        'admin'    => ['badge' => 'bg-indigo-100 text-indigo-700', 'label' => 'Admin'],
        'petugas'  => ['badge' => 'bg-sky-100 text-sky-700',     'label' => 'Petugas'],
        'pengguna' => ['badge' => 'bg-gray-100 text-gray-600',   'label' => 'Pengguna'],
    ];
    $statusMeta = [
        'verified' => ['badge' => 'bg-emerald-100 text-emerald-700', 'label' => 'Terverifikasi'],
        'pending'  => ['badge' => 'bg-amber-100 text-amber-700',    'label' => 'Menunggu'],
        'rejected' => ['badge' => 'bg-rose-100 text-rose-700',      'label' => 'Ditolak'],
    ];
@endphp

<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <p class="text-xs font-semibold tracking-wider text-teal-600 uppercase">Manajemen Pengguna</p>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">Kelola Akun</h1>
                <p class="text-gray-500 mt-1">Daftar seluruh akun admin, petugas, dan sivitas akademika dalam sistem.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.users.pending') }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-amber-50 border border-amber-200 rounded-lg font-semibold text-xs text-amber-700 shadow-sm hover:bg-amber-100 transition">
                    @php $pendingCount = \App\Models\User::where('status','pending')->count() @endphp
                    Verifikasi Pending
                    @if($pendingCount > 0)
                        <span class="bg-amber-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full leading-none">{{ $pendingCount }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.users.create-petugas') }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-teal-600 rounded-lg font-semibold text-xs text-white shadow-sm hover:bg-teal-700 transition">
                    + Akun Petugas
                </a>
                <a href="{{ route('admin.users.create-pengguna') }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 shadow-sm hover:bg-gray-50 transition">
                    + Akun Pengguna
                </a>
            </div>
        </div>
    </x-slot>

    @if (session('status'))
        <div class="mb-4 rounded-xl bg-emerald-50 px-4 py-3 text-sm text-emerald-700 border border-emerald-200">
            {{ session('status') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 rounded-xl bg-rose-50 px-4 py-3 text-sm text-rose-700 border border-rose-200">
            {{ session('error') }}
        </div>
    @endif

    {{-- Filter & Search --}}
    <form method="GET" class="mb-6 flex flex-wrap gap-3 items-end bg-white rounded-xl border border-gray-200 shadow-sm p-4">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-semibold text-gray-600 mb-1">Cari nama / email</label>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Nama atau email..."
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Role</label>
            <x-select-dropdown name="role" :options="['admin' => 'Admin', 'petugas' => 'Petugas', 'pengguna' => 'Pengguna']" :selected="request('role')" />
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Status</label>
            <x-select-dropdown name="status" :options="['pending' => 'Pending', 'verified' => 'Verified', 'rejected' => 'Rejected']" :selected="request('status')" />
        </div>
        <button type="submit" class="px-5 py-2.5 bg-teal-600 rounded-lg text-sm font-semibold text-white hover:bg-teal-700 shadow-sm transition">
            Filter
        </button>
        @if(request()->hasAny(['search','role','status']))
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2.5 bg-white border border-gray-300 rounded-lg text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">
                Reset
            </a>
        @endif
    </form>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm divide-y divide-gray-100 overflow-hidden">
        @forelse ($users as $user)
            <div class="flex flex-wrap items-center justify-between gap-4 px-5 py-4 hover:bg-gray-50/50 transition">
                <div class="flex items-center gap-3 min-w-0">
                    <span class="flex items-center justify-center w-10 h-10 rounded-full bg-teal-50 text-teal-800 font-bold shrink-0 text-sm">
                        {{ Str::of($user->name)->substr(0, 2)->upper() }}
                    </span>
                    <div class="min-w-0">
                        <p class="font-bold text-gray-900 truncate">{{ $user->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">Bergabung {{ $user->created_at->format('d M Y') }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-2 shrink-0 flex-wrap">
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $roleMeta[$user->role]['badge'] ?? 'bg-gray-100 text-gray-600' }}">
                        {{ $roleMeta[$user->role]['label'] ?? ucfirst($user->role) }}
                    </span>
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusMeta[$user->status]['badge'] ?? 'bg-gray-100 text-gray-600' }}">
                        {{ $statusMeta[$user->status]['label'] ?? ucfirst($user->status) }}
                    </span>

                    {{-- Approve / Reject for pending --}}
                    @if($user->status === 'pending')
                        <form method="POST" action="{{ route('admin.users.approve', $user) }}">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-teal-600 rounded-lg text-xs font-semibold text-white hover:bg-teal-700 shadow-sm transition">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                Verifikasi
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.users.reject', $user) }}">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-white border border-rose-200 rounded-lg text-xs font-semibold text-rose-600 hover:bg-rose-50 transition">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                Tolak
                            </button>
                        </form>
                    @elseif($user->status === 'rejected' && !$user->isAdmin())
                        <form method="POST" action="{{ route('admin.users.approve', $user) }}">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-white border border-gray-300 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">
                                Aktifkan Kembali
                            </button>
                        </form>
                    @endif

                    {{-- Delete --}}
                    @if(!$user->isAdmin() && !$user->is(auth()->user()))
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                            onsubmit="return confirm('Hapus akun {{ addslashes($user->name) }}? Tindakan ini tidak dapat dibatalkan.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-white border border-rose-200 rounded-lg text-xs font-semibold text-rose-600 hover:bg-rose-50 transition">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                Hapus
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="px-5 py-10 text-center text-gray-500">Belum ada data akun yang sesuai filter.</div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $users->appends(request()->query())->links() }}
    </div>
</x-admin-layout>
