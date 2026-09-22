<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>ReservasiFasilitas</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://unpkg.com/@phosphor-icons/web"></script>
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-50">
        <div class="relative min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 overflow-hidden bg-gradient-to-br from-teal-50 via-white to-gray-100">
            <div class="pointer-events-none absolute -top-24 -left-24 w-96 h-96 bg-teal-200/40 rounded-full blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-24 -right-24 w-96 h-96 bg-teal-300/20 rounded-full blur-3xl"></div>

            <div class="relative z-10 flex flex-col items-center">
                <a href="/" class="flex items-center justify-center w-16 h-16 rounded-2xl bg-teal-600 shadow-md ring-1 ring-gray-900/5 transition-transform hover:scale-105">
                    <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </a>
                <span class="mt-4 text-xl font-extrabold tracking-tight text-teal-800">Reservasi<span class="text-teal-600">Fasilitas</span></span>
                <p class="text-sm text-gray-500 mt-1">Sistem Peminjaman Fasilitas Kampus Terpadu</p>
            </div>

            <div class="relative z-10 w-full sm:max-w-md mt-6 px-8 py-8 bg-white/95 backdrop-blur shadow-xl border border-gray-100 overflow-hidden sm:rounded-2xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
