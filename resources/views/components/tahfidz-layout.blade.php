<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="user-id" content="{{ auth()->id() }}">

        <title>{{ config('app.name', 'Sistem Monitoring Tahfidz') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}">

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
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }
            .dark {
                color-scheme: dark;
            }
        </style>
    </head>
    <body class="bg-gray-50 dark:bg-gray-900 antialiased">
        <x-tahfidz.navbar />
        
        @php
            $role = Auth::user()->role === 'orang_tua' ? 'parent' : Auth::user()->role;
        @endphp
        
        <x-tahfidz.sidebar :role="$role" />

        <div id="sidebar-backdrop" class="fixed inset-0 bg-gray-900/50 z-30 hidden sm:hidden backdrop-blur-sm transition-opacity"></div>

        <div class="p-4 sm:ml-64 pt-20">
            <div id="main-content-container" class="p-4 rounded-lg dark:border-gray-700">
                <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white truncate">{{ $header ?? '' }}</h1>
                        <p class="text-gray-600 dark:text-gray-400 truncate">{{ $subtitle ?? '' }}</p>
                    </div>
                    @if(isset($header_actions))
                        <div class="flex-shrink-0 self-center md:self-end">
                            {{ $header_actions }}
                        </div>
                    @endif
                </div>

                {{ $slot }}
            </div>
        </div>
        <script>
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            const toggleButtons = document.querySelectorAll('[data-drawer-toggle="sidebar"]');

            function toggleSidebar() {
                sidebar.classList.toggle('-translate-x-full');
                backdrop.classList.toggle('hidden');
            }

            toggleButtons.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    toggleSidebar();
                });
            });

            backdrop.addEventListener('click', () => {
                if (!sidebar.classList.contains('-translate-x-full')) {
                    toggleSidebar();
                }
            });

            var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
            var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                themeToggleLightIcon.classList.remove('hidden');
            } else {
                themeToggleDarkIcon.classList.remove('hidden');
            }

            var themeToggleBtn = document.getElementById('theme-toggle');

            themeToggleBtn.addEventListener('click', function() {
                themeToggleDarkIcon.classList.toggle('hidden');
                themeToggleLightIcon.classList.toggle('hidden');

                if (localStorage.getItem('color-theme')) {
                    if (localStorage.getItem('color-theme') === 'light') {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('color-theme', 'dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('color-theme', 'light');
                    }

                } else {
                    if (document.documentElement.classList.contains('dark')) {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('color-theme', 'light');
                    } else {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('color-theme', 'dark');
                    }
                }
            });
        </script>
        @stack('scripts')
    </body>
</html>
