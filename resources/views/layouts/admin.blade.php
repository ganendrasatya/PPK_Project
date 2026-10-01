<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ReservasiFasilitas') }} - Admin</title>
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50 min-h-screen flex flex-col">
    <!-- Navbar Admin -->
    <nav class="bg-white sticky top-0 shadow-sm z-50 border-b border-gray-100" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Brand Logo -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 group">
                        <div class="bg-teal-50 p-1.5 rounded-lg group-hover:bg-teal-100 transition-colors">
                            <svg class="w-7 h-7 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <span class="font-extrabold text-xl text-teal-800 tracking-tight">Reservasi<span class="text-teal-600">Fasilitas</span></span>
                    </a>
                    <span class="hidden sm:inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-teal-100 text-teal-800 uppercase tracking-wider">
                        Admin
                    </span>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex md:items-center md:space-x-1">
                    @php
                        $adminLinks = [
                            'admin.dashboard' => ['Dashboard', route('admin.dashboard')],
                            'admin.facilities.*' => ['Fasilitas', route('admin.facilities.index')],
                            'admin.users.index' => ['Kelola Akun', route('admin.users.index')],
                            'admin.users.pending' => ['Verifikasi', route('admin.users.pending')],
                            'admin.recap.*' => ['Rekap & Export', route('admin.recap.index')],
                        ];
                    @endphp

                    @foreach ($adminLinks as $pattern => [$label, $url])
                        <a href="{{ $url }}"
                           class="rounded-full px-4 py-2 text-sm font-semibold transition-all {{ request()->routeIs($pattern) ? 'bg-teal-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-teal-700' }}">
                            {{ $label }}
                        </a>
                    @endforeach

                    <!-- Link to Public Catalog -->
                    <a href="{{ route('catalog.index') }}"
                       class="rounded-full px-4 py-2 text-sm font-semibold text-teal-700 hover:bg-teal-50 transition-all flex items-center gap-1.5 ml-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                        </svg>
                        Portal Publik
                    </a>
                </div>

                <!-- User Profile & Logout -->
                <div class="hidden md:flex md:items-center">
                    <div class="flex items-center gap-3 border-l border-gray-200 pl-5 ml-2">
                        <div class="w-9 h-9 rounded-full bg-teal-600 text-white flex items-center justify-center text-sm font-bold shadow-sm border-2 border-white ring-2 ring-teal-50">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <div class="flex flex-col">
                            <a href="{{ route('profile.edit') }}" class="text-sm font-bold text-gray-800 leading-none hover:text-teal-700 transition-colors">{{ auth()->user()->name }}</a>
                            <span class="text-xs text-teal-600 font-semibold mt-1">Administrator</span>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="ml-4">
                            @csrf
                            <button type="submit" class="text-sm text-red-500 hover:text-red-700 font-bold bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition-colors">
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Hamburger Mobile -->
                <div class="-mr-2 flex items-center md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="inline-flex items-center justify-center p-2 rounded-lg text-gray-500 hover:text-teal-600 hover:bg-teal-50 focus:outline-none transition-colors">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': mobileMenuOpen, 'inline-flex': !mobileMenuOpen }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': !mobileMenuOpen, 'inline-flex': mobileMenuOpen }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-transition class="md:hidden border-t border-gray-100 bg-white" style="display: none;">
            <div class="pt-2 pb-3 space-y-1 px-4">
                @foreach ($adminLinks as $pattern => [$label, $url])
                    <a href="{{ $url }}" class="block px-3 py-2 rounded-lg text-base font-semibold {{ request()->routeIs($pattern) ? 'bg-teal-50 text-teal-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        {{ $label }}
                    </a>
                @endforeach
                <a href="{{ route('catalog.index') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-teal-700 hover:bg-teal-50">
                    Portal Publik &rarr;
                </a>
            </div>
            <div class="pt-4 pb-3 border-t border-gray-100 px-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-teal-600 text-white flex items-center justify-center text-sm font-bold shadow-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div>
                        <div class="font-bold text-gray-800">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-teal-600 font-semibold">Administrator</div>
                    </div>
                </div>
                <div class="mt-3">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left font-bold text-red-500 bg-red-50 hover:bg-red-100 px-3 py-2 rounded-lg text-sm transition-colors">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="flex-1">
        @isset($header)
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
                {{ $header }}
            </div>
        @endisset

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            {{ $slot }}
        </main>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-gray-500">
            <p>&copy; {{ date('Y') }} ReservasiFasilitas Kampus &amp; Perkantoran. Seluruh hak cipta dilindungi.</p>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Jam Operasional: 07.00 &ndash; 18.00 WIB</span>
            </div>
        </div>
    </footer>
</body>
</html>
