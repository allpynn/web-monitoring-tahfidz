<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Tahfidz Mujahidin') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <script>
            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark')
            }
        </script>
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            .dark { color-scheme: dark; }
        </style>
    </head>
    <body class="antialiased selection:bg-emerald-500 selection:text-white">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
            <div class="mb-8">
                <a href="/" class="flex items-center space-x-2">
                    <span class="text-3xl font-black text-emerald-700 dark:text-emerald-500 tracking-tighter">Tahfidz<span class="text-gray-900 dark:text-white">AlMujahidin</span></span>
                </a>
            </div>

            <div class="w-full sm:max-w-md px-1 py-1 overflow-hidden">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
