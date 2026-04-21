<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Konfirmasi Sandi - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50 dark:bg-gray-950 flex items-center justify-center min-h-screen p-6">
    <div class="w-full max-w-md bg-white dark:bg-gray-900 p-8 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-800">
        <div class="mb-6 text-sm text-gray-600 dark:text-gray-400 font-medium">
            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
        </div>

        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
            @csrf

            <!-- Password -->
            <div class="space-y-2">
                <label for="password" class="block text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest ml-1">{{ __('Password') }}</label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="block w-full px-5 py-4 bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-700 dark:text-white rounded-2xl focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 transition-all duration-200 sm:text-sm font-semibold">
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="px-8 py-3 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl font-bold transition-all shadow-lg shadow-emerald-200 dark:shadow-none">
                    {{ __('Confirm') }}
                </button>
            </div>
        </form>
    </div>
</body>
</html>
