<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="relative min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 overflow-hidden bg-gradient-to-br from-indigo-50 via-white to-slate-100">
            <div class="pointer-events-none absolute -top-24 -left-24 w-72 h-72 bg-indigo-200/50 rounded-full blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-24 -right-24 w-72 h-72 bg-slate-300/40 rounded-full blur-3xl"></div>

            <div class="relative z-10 flex flex-col items-center">
                <a href="/" class="flex items-center justify-center w-16 h-16 rounded-2xl bg-white shadow-md ring-1 ring-gray-900/5">
                    <x-application-logo class="w-9 h-9 fill-current text-indigo-600" />
                </a>
                <span class="mt-3 text-sm font-semibold tracking-wide text-gray-500 uppercase">{{ config('app.name', 'Laravel') }}</span>
            </div>

            <div class="relative z-10 w-full sm:max-w-md mt-6 px-8 py-8 bg-white/90 backdrop-blur shadow-xl ring-1 ring-gray-900/5 overflow-hidden sm:rounded-2xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
