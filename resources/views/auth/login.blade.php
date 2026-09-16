<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="text-xl font-semibold text-gray-900">{{ __('Selamat datang kembali') }}</h1>
        <p class="mt-1 text-sm text-gray-500">{{ __('Masuk untuk melanjutkan ke akun Anda') }}</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <div class="relative mt-1">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0-.828.672-1.5 1.5-1.5h16.5c.828 0 1.5.672 1.5 1.5v10.5a1.5 1.5 0 01-1.5 1.5H3.75a1.5 1.5 0 01-1.5-1.5V6.75z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M22.5 7.5l-9.75 6.75a1.5 1.5 0 01-1.5 0L1.5 7.5" />
                    </svg>
                </span>
                <x-text-input id="email" class="block w-full pl-10" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@email.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />

            <div class="relative mt-1">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 9h10.5a1.5 1.5 0 001.5-1.5v-6a1.5 1.5 0 00-1.5-1.5H6.75a1.5 1.5 0 00-1.5 1.5v6a1.5 1.5 0 001.5 1.5z" />
                    </svg>
                </span>
                <x-text-input id="password" class="block w-full pl-10"
                                type="password"
                                name="password"
                                required autocomplete="current-password" placeholder="••••••••" />
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-indigo-600 hover:text-indigo-800 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>

        <x-primary-button class="w-full justify-center py-2.5">
            {{ __('Log in') }}
        </x-primary-button>

        @if (Route::has('register'))
            <p class="text-center text-sm text-gray-500">
                {{ __("Don't have an account?") }}
                <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-800">{{ __('Register') }}</a>
            </p>
        @endif
    </form>
</x-guest-layout>
