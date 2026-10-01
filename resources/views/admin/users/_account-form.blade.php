@php($inputClass = 'block mt-1 w-full rounded-lg border-gray-300 shadow-sm py-2.5 text-sm focus:border-teal-500 focus:ring-teal-500 transition')

<div>
    <x-input-label for="name" value="Nama Lengkap" />
    <input id="name" name="name" type="text" class="{{ $inputClass }}" value="{{ old('name') }}" required autofocus placeholder="Nama pengguna">
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="email" value="Alamat Email" />
    <input id="email" name="email" type="email" class="{{ $inputClass }}" value="{{ old('email') }}" required placeholder="email@kampus.ac.id">
    <x-input-error :messages="$errors->get('email')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="password" value="Kata Sandi" />
    <input id="password" name="password" type="password" class="{{ $inputClass }}" required placeholder="Minimal 8 karakter">
    <x-input-error :messages="$errors->get('password')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="password_confirmation" value="Konfirmasi Kata Sandi" />
    <input id="password_confirmation" name="password_confirmation" type="password" class="{{ $inputClass }}" required placeholder="Ulangi kata sandi">
</div>
