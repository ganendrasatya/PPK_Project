<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Admin</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row gap-6">
                <aside class="w-full md:w-56 shrink-0">
                    <nav class="bg-white rounded-lg shadow-sm ring-1 ring-gray-900/5 p-2 space-y-1">
                        @php
                            $adminLinks = [
                                'admin.dashboard' => ['Dashboard', route('admin.dashboard')],
                                'admin.facilities.*' => ['Fasilitas', route('admin.facilities.index')],
                                'admin.users.index' => ['Kelola Akun', route('admin.users.index')],
                                'admin.users.pending' => ['Verifikasi Registrasi', route('admin.users.pending')],
                                'admin.recap.*' => ['Rekap & Export', route('admin.recap.index')],
                            ];
                        @endphp

                        @foreach ($adminLinks as $pattern => [$label, $url])
                            <a href="{{ $url }}"
                               class="block px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs($pattern) ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </nav>
                </aside>

                <main class="flex-1 min-w-0">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
