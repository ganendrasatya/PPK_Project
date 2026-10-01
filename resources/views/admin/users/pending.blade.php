<x-admin-layout>
    <x-slot name="header">
        <p class="text-xs font-semibold tracking-wider text-teal-600 uppercase">Manajemen Pengguna</p>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">Verifikasi Registrasi Mandiri</h1>
        <p class="text-gray-500 mt-1">Tinjau dan setujui akun mahasiswa / sivitas akademika yang mendaftar mandiri sebelum dapat mengakses portal.</p>
    </x-slot>

    @if (session('status'))
        <div class="mb-4 rounded-xl bg-emerald-50 px-4 py-3 text-sm text-emerald-700 border border-emerald-200">
            {{ session('status') }}
        </div>
    @endif

    <div class="space-y-3">
        @forelse ($users as $user)
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex flex-wrap items-center justify-between gap-4 hover:shadow-md transition">
                <div class="flex items-center gap-3 min-w-0">
                    <span class="flex items-center justify-center w-11 h-11 rounded-full bg-amber-50 text-amber-700 font-bold shrink-0 text-sm border border-amber-200">
                        {{ Str::of($user->name)->substr(0, 2)->upper() }}
                    </span>
                    <div class="min-w-0">
                        <p class="font-bold text-gray-900 truncate">{{ $user->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">Mendaftar pada {{ $user->created_at->format('d M Y, H:i') }} WIB</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <form method="POST" action="{{ route('admin.users.approve', $user) }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-teal-600 rounded-lg text-xs font-semibold text-white hover:bg-teal-700 shadow-sm transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                            Verifikasi Akun
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.users.reject', $user) }}" onsubmit="return confirm('Tolak pendaftaran akun ini?');">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-rose-200 rounded-lg text-xs font-semibold text-rose-600 hover:bg-rose-50 transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            Tolak
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl border border-gray-200 p-10 text-center text-gray-500">
                Tidak ada registrasi akun yang sedang menunggu verifikasi saat ini.
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $users->links() }}
    </div>
</x-admin-layout>
