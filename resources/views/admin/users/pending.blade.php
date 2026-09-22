<x-admin-layout>
    <x-slot name="header">
        <p class="text-xs font-semibold tracking-wide text-emerald-700 uppercase">Manajemen Pengguna</p>
        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 mt-1">Verifikasi Registrasi Mandiri</h1>
        <p class="text-slate-500 mt-1">Tinjau akun pengguna yang mendaftar sendiri sebelum bisa login.</p>
    </x-slot>

    @if (session('status'))
        <div class="mb-4 rounded-xl bg-emerald-50 px-4 py-3 text-sm text-emerald-700 ring-1 ring-emerald-200">
            {{ session('status') }}
        </div>
    @endif

    <div class="space-y-3">
        @forelse ($users as $user)
            <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-900/5 p-5 flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3 min-w-0">
                    <span class="flex items-center justify-center w-11 h-11 rounded-full bg-amber-50 text-amber-700 font-semibold shrink-0">
                        {{ Str::of($user->name)->substr(0, 1)->upper() }}
                    </span>
                    <div class="min-w-0">
                        <p class="font-semibold text-slate-900 truncate">{{ $user->name }}</p>
                        <p class="text-sm text-slate-500 truncate">{{ $user->email }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">Mendaftar {{ $user->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <form method="POST" action="{{ route('admin.users.approve', $user) }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-700 rounded-full text-sm font-semibold text-white hover:bg-emerald-800 transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                            Verifikasi
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.users.reject', $user) }}" onsubmit="return confirm('Tolak akun ini?');">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white ring-1 ring-rose-200 rounded-full text-sm font-semibold text-rose-600 hover:bg-rose-50 transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            Tolak
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl ring-1 ring-slate-900/5 p-10 text-center text-slate-500">
                Tidak ada registrasi yang menunggu verifikasi.
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $users->links() }}
    </div>
</x-admin-layout>
