<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ReservasiFasilitas') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50 min-h-screen flex flex-col">
    <!-- Navbar -->
    <nav class="bg-white sticky top-0 shadow-sm z-50 border-b border-gray-100" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('catalog.index') }}" class="flex items-center gap-2 group">
                        <!-- Shield Icon -->
                        <div class="bg-teal-50 p-1.5 rounded-lg group-hover:bg-teal-100 transition-colors">
                            <svg class="w-7 h-7 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <span class="font-extrabold text-xl text-teal-800 tracking-tight">Reservasi<span class="text-teal-600">Fasilitas</span></span>
                    </a>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex md:items-center md:space-x-2">
                    <a href="{{ route('catalog.index') }}" class="{{ request()->routeIs('catalog.*') ? 'bg-teal-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-teal-700' }} rounded-full px-4 py-2 text-sm font-semibold transition-all">Katalog Fasilitas</a>
                    @auth
                        <a href="{{ route('reservations.index') }}" class="{{ request()->routeIs('reservations.*') ? 'bg-teal-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-teal-700' }} rounded-full px-4 py-2 text-sm font-semibold transition-all">Reservasi Saya</a>
                        
                        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'petugas')
                            <a href="{{ route('admin.dashboard') ?? '#' }}" class="{{ request()->routeIs('admin.*') ? 'bg-teal-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-teal-700' }} rounded-full px-4 py-2 text-sm font-semibold transition-all">Dashboard Petugas</a>
                        @endif
                        
                        <a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.*') ? 'bg-teal-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-teal-700' }} rounded-full px-4 py-2 text-sm font-semibold transition-all">Laporan & Kerusakan</a>
                    @endauth
                </div>

                <div class="hidden md:flex md:items-center">
                    @auth
                        <div class="flex items-center gap-3 border-l border-gray-200 pl-5 ml-2">
                            <div class="w-9 h-9 rounded-full bg-teal-600 text-white flex items-center justify-center text-sm font-bold shadow-sm border-2 border-white ring-2 ring-teal-50">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-gray-800 leading-none">{{ auth()->user()->name }}</span>
                                <span class="text-xs text-teal-600 font-medium mt-1">{{ auth()->user()->role ?? 'Sivitas Akademika' }}</span>
                            </div>
                            <form method="POST" action="{{ route('logout') }}" class="ml-4">
                                @csrf
                                <button type="submit" class="text-sm text-red-500 hover:text-red-700 font-bold bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition-colors">Keluar</button>
                            </form>
                        </div>
                    @else
                        <div class="flex items-center gap-3 border-l border-gray-200 pl-5 ml-2">
                            <a href="{{ route('login') }}" class="text-gray-600 font-semibold hover:text-teal-700 transition-colors">Masuk</a>
                            <a href="{{ route('register') ?? '#' }}" class="bg-teal-600 text-white font-semibold px-4 py-2 rounded-lg hover:bg-teal-700 transition-colors shadow-sm">Daftar</a>
                        </div>
                    @endauth
                </div>

                <!-- Hamburger -->
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
                <a href="{{ route('catalog.index') }}" class="block px-3 py-2 rounded-lg text-base font-semibold {{ request()->routeIs('catalog.*') ? 'bg-teal-50 text-teal-700' : 'text-gray-700 hover:bg-gray-50' }}">Katalog Fasilitas</a>
                @auth
                    <a href="{{ route('reservations.index') }}" class="block px-3 py-2 rounded-lg text-base font-semibold {{ request()->routeIs('reservations.*') ? 'bg-teal-50 text-teal-700' : 'text-gray-700 hover:bg-gray-50' }}">Reservasi Saya</a>
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'petugas')
                        <a href="{{ route('admin.dashboard') ?? '#' }}" class="block px-3 py-2 rounded-lg text-base font-semibold {{ request()->routeIs('admin.*') ? 'bg-teal-50 text-teal-700' : 'text-gray-700 hover:bg-gray-50' }}">Dashboard Petugas</a>
                    @endif
                    <a href="{{ route('reports.index') }}" class="block px-3 py-2 rounded-lg text-base font-semibold {{ request()->routeIs('reports.*') ? 'bg-teal-50 text-teal-700' : 'text-gray-700 hover:bg-gray-50' }}">Laporan & Kerusakan</a>
                @endauth
            </div>
            @auth
                <div class="pt-4 pb-4 border-t border-gray-100 bg-gray-50">
                    <div class="px-5 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-teal-600 text-white flex items-center justify-center font-bold shadow-sm">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <div>
                            <div class="text-base font-bold text-gray-800">{{ auth()->user()->name }}</div>
                            <div class="text-sm font-medium text-teal-600">{{ auth()->user()->role ?? 'Sivitas Akademika' }}</div>
                        </div>
                    </div>
                    <div class="mt-4 px-4">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-center px-4 py-2.5 text-base font-bold text-red-600 bg-red-100 hover:bg-red-200 rounded-lg transition-colors">Keluar dari Sistem</button>
                        </form>
                    </div>
                </div>
            @else
                <div class="p-4 border-t border-gray-100 bg-gray-50 flex gap-3">
                    <a href="{{ route('login') }}" class="flex-1 text-center bg-white border border-gray-300 text-gray-700 px-4 py-2.5 rounded-lg font-bold">Masuk</a>
                    <a href="{{ route('register') ?? '#' }}" class="flex-1 text-center bg-teal-600 text-white px-4 py-2.5 rounded-lg font-bold">Daftar</a>
                </div>
            @endauth
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow flex flex-col relative z-0">
        <!-- Flash Messages -->
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition.duration.500ms class="bg-emerald-50 border-b border-emerald-200">
                <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-2 text-emerald-800 text-sm font-semibold">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ session('success') }}
                    </div>
                    <button @click="show = false" class="text-emerald-600 hover:text-emerald-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-transition.duration.500ms class="bg-red-50 border-b border-red-200">
                <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-2 text-red-800 text-sm font-semibold">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ session('error') }}
                    </div>
                    <button @click="show = false" class="text-red-600 hover:text-red-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
        @endif
        
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-8 mt-auto z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-2 opacity-80">
                <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                <span class="text-sm text-gray-600 font-semibold">
                    © {{ date('Y') }} ReservasiFasilitas Kampus & Perkantoran. Seluruh hak cipta dilindungi.
                </span>
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-700 font-semibold bg-gray-50 px-4 py-2 rounded-full border border-gray-100">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse shadow-sm shadow-emerald-200"></span>
                Jam Operasional: 07.00 – 18.00 WIB
            </div>
        </div>
    </footer>
</body>
</html>
