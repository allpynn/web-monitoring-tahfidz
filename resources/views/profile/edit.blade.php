<x-tahfidz-layout>
    <x-slot name="header">
        Profil Saya
    </x-slot>
    <x-slot name="subtitle">
        Kelola informasi akun dan keamanan Anda.
    </x-slot>

    <div class="space-y-6">
        <div class="p-8 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-sm dark:shadow-none rounded-2xl">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="p-8 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-sm dark:shadow-none rounded-2xl">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

    </div>
</x-tahfidz-layout>
