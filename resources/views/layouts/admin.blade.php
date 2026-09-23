<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Admin</title>
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-50">
        <div class="min-h-screen">
            <!-- Top Bar -->
            <header class="bg-white border-b border-slate-200 sticky top-0 z-40">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-8 min-w-0">
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 shrink-0">
                            <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-800 text-white">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6" />
                                </svg>
                            </span>
                            <span class="font-bold text-slate-800 hidden sm:inline">PPK Admin</span>
                        </a>

                        <nav class="hidden md:flex items-center gap-1 overflow-x-auto">
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
                                   class="px-3.5 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition {{ request()->routeIs($pattern) ? 'bg-emerald-800 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                    {{ $label }}
                                </a>
                            @endforeach
                        </nav>
                    </div>

                    <div class="flex items-center gap-3 shrink-0">
                        <a href="{{ route('dashboard') }}" class="hidden sm:inline text-sm text-slate-500 hover:text-slate-800">Homepage</a>

                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="flex items-center gap-2 pl-1 pr-2 py-1 rounded-full hover:bg-slate-100 transition">
                                    <span class="flex items-center justify-center w-8 h-8 rounded-full bg-emerald-100 text-emerald-800 font-semibold text-sm">
                                        {{ Str::of(Auth::user()->name)->substr(0, 1)->upper() }}
                                    </span>
                                    <span class="hidden sm:block text-left">
                                        <span class="block text-sm font-medium text-slate-800 leading-tight">{{ Auth::user()->name }}</span>
                                        <span class="block text-xs text-emerald-700 leading-tight">Admin</span>
                                    </span>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>

                <!-- Mobile nav -->
                <nav class="md:hidden flex items-center gap-1 px-4 pb-3 overflow-x-auto">
                    @foreach ($adminLinks as $pattern => [$label, $url])
                        <a href="{{ $url }}"
                           class="px-3 py-1.5 rounded-lg text-xs font-medium whitespace-nowrap transition {{ request()->routeIs($pattern) ? 'bg-emerald-800 text-white' : 'text-slate-600 bg-slate-100' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </nav>
            </header>

            @isset($header)
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
                    {{ $header }}
                </div>
            @endisset

            <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
