@php($inputClass = 'block mt-1 w-full rounded-lg border-slate-300 shadow-sm py-2.5 focus:border-emerald-500 focus:ring-emerald-500 transition')

<div>
    <x-input-label for="name" value="Nama" />
    <input id="name" name="name" type="text" class="{{ $inputClass }}" value="{{ old('name') }}" required autofocus>
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="email" value="Email" />
    <input id="email" name="email" type="email" class="{{ $inputClass }}" value="{{ old('email') }}" required>
    <x-input-error :messages="$errors->get('email')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="password" value="Password" />
    <input id="password" name="password" type="password" class="{{ $inputClass }}" required>
    <x-input-error :messages="$errors->get('password')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="password_confirmation" value="Konfirmasi Password" />
    <input id="password_confirmation" name="password_confirmation" type="password" class="{{ $inputClass }}" required>
</div>
