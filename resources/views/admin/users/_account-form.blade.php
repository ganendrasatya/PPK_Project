<div>
    <x-input-label for="name" value="Nama" />
    <x-text-input id="name" name="name" type="text" class="block mt-1 w-full" value="{{ old('name') }}" required autofocus />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="email" value="Email" />
    <x-text-input id="email" name="email" type="email" class="block mt-1 w-full" value="{{ old('email') }}" required />
    <x-input-error :messages="$errors->get('email')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="password" value="Password" />
    <x-text-input id="password" name="password" type="password" class="block mt-1 w-full" required />
    <x-input-error :messages="$errors->get('password')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="password_confirmation" value="Konfirmasi Password" />
    <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="block mt-1 w-full" required />
</div>
