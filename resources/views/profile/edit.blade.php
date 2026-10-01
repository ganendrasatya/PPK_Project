<x-catalog-layout>
    <div class="max-w-4xl mx-auto px-4 pt-8">
        <span class="text-teal-600 uppercase text-xs font-semibold tracking-wider">PENGATURAN AKUN</span>
        <h1 class="text-2xl sm:text-3xl font-bold mt-1 text-gray-900">Profil Pengguna</h1>
        <p class="text-gray-500 mt-1">Perbarui informasi profil akun, email, dan kata sandi Anda.</p>
    </div>

    <div class="max-w-4xl mx-auto px-4 py-8 space-y-6">
        <div class="p-6 sm:p-8 bg-white rounded-xl border border-gray-200 shadow-sm">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="p-6 sm:p-8 bg-white rounded-xl border border-gray-200 shadow-sm">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="p-6 sm:p-8 bg-white rounded-xl border border-gray-200 shadow-sm">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-catalog-layout>
