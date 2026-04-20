<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Sistem Monitoring Tahfidz')</title>
        <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}">
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
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            .dark { color-scheme: dark; }
        </style>
    </head>
    <body class="antialiased bg-white dark:bg-gray-900 transition-colors duration-300">
        <!-- Navbar -->
        <nav class="fixed w-full z-50 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-b border-gray-100 dark:border-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <a href="/" class="flex items-center gap-3 group">
                        <x-application-logo class="h-10 w-auto" />
                        <span class="text-2xl font-black text-emerald-700 dark:text-emerald-500 tracking-tighter group-hover:text-emerald-600 transition-colors">Tahfidz<span class="text-gray-900 dark:text-white truncate">AlMujahidin</span></span>
                    </a>
                        <!-- Theme Toggle & Mobile Menu -->
                        <div class="flex items-center space-x-2">
                            <button id="theme-toggle" type="button" class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5 transition-all">
                                <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                                <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464l-.707-.707a1 1 0 00-1.414 1.414l.707.707a1 1 0 001.414-1.414zm2.12 10.607a1 1 0 010-1.414l.706-.707a1 1 0 111.414 1.414l-.707.707a1 1 0 01-1.414 0z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                            </button>
                            <button id="mobile-menu-button" type="button" class="md:hidden text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5 transition-all">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                            </button>
                        </div>

                        <div class="hidden md:flex items-center space-x-8">
                            <a href="{{ url('/') }}#fitur" class="text-gray-600 dark:text-gray-400 hover:text-emerald-700 dark:hover:text-emerald-500 font-medium whitespace-nowrap transition-colors">Fitur</a>
                            <a href="{{ url('/') }}#tentang" class="text-gray-600 dark:text-gray-400 hover:text-emerald-700 dark:hover:text-emerald-500 font-medium whitespace-nowrap transition-colors">Tentang</a>
                            @auth
                                <a href="{{ url('/dashboard') }}" class="px-5 py-2 bg-emerald-700 text-white rounded-full font-semibold hover:bg-emerald-800 transition-all shadow-lg shadow-emerald-200 dark:shadow-none">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="px-5 py-2 bg-emerald-700 text-white rounded-full font-semibold hover:bg-emerald-800 transition-all shadow-lg shadow-emerald-200 dark:shadow-none">Masuk</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Mobile Menu Drawer -->
            <div id="mobile-menu" class="hidden md:hidden bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800 animate-fade-in-down">
                <div class="px-4 pt-2 pb-6 space-y-2">
                    <a href="{{ url('/') }}#fitur" class="block px-4 py-3 text-base font-bold text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-xl transition-all">Fitur</a>
                    <a href="{{ url('/') }}#tentang" class="block px-4 py-3 text-base font-bold text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-xl transition-all">Tentang</a>
                    <hr class="border-gray-100 dark:border-gray-800 my-2">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="block px-4 py-4 bg-emerald-700 text-white text-center rounded-2xl font-black shadow-lg shadow-emerald-200 dark:shadow-none">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="block px-4 py-4 bg-emerald-700 text-white text-center rounded-2xl font-black shadow-lg shadow-emerald-200 dark:shadow-none">Masuk / Login</a>
                    @endauth
                </div>
            </div>
        </nav>

        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-gray-50 dark:bg-gray-900 pt-16 pb-8 border-t border-gray-100 dark:border-gray-800 transition-colors">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
                    <!-- Brand Section -->
                    <div class="space-y-6">
                        <a href="/" class="flex items-center gap-3 group">
                            <x-application-logo class="h-10 w-auto" />
                            <span class="text-2xl font-black text-emerald-700 dark:text-emerald-500 tracking-tighter group-hover:text-emerald-600 transition-colors">Tahfidz<span class="text-gray-900 dark:text-white">AlMujahidin</span></span>
                        </a>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                            Mencetak generasi penghafal Al-Qur'an yang beradab, cerdas, dan mandiri melalui pendekatan teknologi yang transparan.
                        </p>
                        <div class="flex space-x-4">
                            <a href="https://www.instagram.com/mujahidinbpn" target="_blank" rel="noopener" title="Instagram" class="w-10 h-10 rounded-full bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-700 dark:text-emerald-400 hover:bg-emerald-700 hover:text-white transition-all shadow-sm">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 1.366.062 2.633.332 3.608 1.308.975.975 1.245 2.242 1.308 3.608.058 1.266.07 1.646.07 4.85s-.012 3.584-.07 4.85c-.063 1.366-.33 2.633-1.308 3.608-.977.975-2.242 1.245-3.608 1.308-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07c-1.366-.063-2.633-.33-3.608-1.308-.975-.975-1.245-2.242-1.308-3.608-.058-1.266-.07-1.646-.07-4.85s.012-3.584.07-4.85c.062-1.366.332-2.633 1.308-3.608.975-.975 2.242-1.245 3.608-1.308 1.266-.058 1.646-.07 4.85-.07zm0-2.163c-3.259 0-3.667.014-4.947.072-1.277.057-2.15.26-2.914.557-.79.306-1.459.715-2.126 1.383-.668.667-1.077 1.336-1.383 2.126-.297.764-.5 1.637-.557 2.914-.058 1.28-.072 1.688-.072 4.947s.014 3.667.072 4.947c.057 1.277.26 2.15.557 2.914.306.79.715 1.459 1.383 2.126.667.668 1.336 1.077 2.126 1.383.764.297 1.637.5 2.914.557 1.28.058 1.688.072 4.947.072s3.667-.014 4.947-.072c1.277-.057 2.15-.26 2.914-.557.79-.306 1.459-.715 2.126-1.383.668-.667 1.077-1.336 1.383-2.126.297-.764.5-1.637.557-2.914.058-1.28.072-1.688.072-4.947s-.014-3.667-.072-4.947c-.057-1.277-.26-2.150-.557-2.914-.306-.79-.715-1.459-1.383-2.126-.667-.668-1.336-1.077-2.126-1.383-.764-.297-1.637-.5-2.914-.557-1.28-.058-1.688-.072-4.947-.072z"/><path d="M12 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            </a>
                            <a href="https://wa.me/6282252768268" target="_blank" rel="noopener" title="WhatsApp" class="w-10 h-10 rounded-full bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-700 dark:text-emerald-400 hover:bg-emerald-700 hover:text-white transition-all shadow-sm">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            </a>
                            <a href="https://www.youtube.com/@ponpesal-mujahidinbalikpap5653" target="_blank" rel="noopener" title="YouTube" class="w-10 h-10 rounded-full bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-700 dark:text-emerald-400 hover:bg-emerald-700 hover:text-white transition-all shadow-sm">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Navigation Links -->
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider mb-6">Navigasi</h3>
                        <ul class="space-y-4">
                            <li><a href="{{ url('/') }}" class="text-gray-600 dark:text-gray-400 hover:text-emerald-700 dark:hover:text-emerald-500 transition-colors">Beranda</a></li>
                            <li><a href="{{ url('/') }}#fitur" class="text-gray-600 dark:text-gray-400 hover:text-emerald-700 dark:hover:text-emerald-500 transition-colors">Fitur Utama</a></li>
                            <li><a href="{{ url('/') }}#tentang" class="text-gray-600 dark:text-gray-400 hover:text-emerald-700 dark:hover:text-emerald-500 transition-colors">Tentang Kami</a></li>
                            <li><a href="{{ route('login') }}" class="text-gray-600 dark:text-gray-400 hover:text-emerald-700 dark:hover:text-emerald-500 transition-colors">Login</a></li>
                        </ul>
                    </div>

                    <!-- Quick Contact -->
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider mb-6">Hubungi Kami</h3>
                        <ul class="space-y-4">
                            <li class="flex items-start space-x-3 text-gray-600 dark:text-gray-400">
                                <svg class="w-6 h-6 text-emerald-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <span>Jl. Soekarno-Hatta KM. 10, Kel. Karang Joang, Kec. Balikpapan Utara, Kota Balikpapan, Kalimantan Timur 76127</span>
                            </li>
                            <li class="flex items-center space-x-3 text-gray-600 dark:text-gray-400">
                                <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                <a href="https://wa.me/6282252768268" target="_blank" class="hover:text-emerald-700 transition-colors">082252768268</a>
                            </li>
                            <li class="flex items-center space-x-3 text-gray-600 dark:text-gray-400">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                <a href="mailto:admin@mujahidinbalikpapan.id" class="hover:text-emerald-700 transition-colors">admin@mujahidinbalikpapan.id</a>
                            </li>
                        </ul>
                    </div>

                    <!-- Map/Location -->
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider mb-6">Lokasi</h3>
                        <div class="h-48 bg-gray-200 dark:bg-gray-800 rounded-2xl relative overflow-hidden group shadow-inner">
                           <iframe 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3988.95812320928!2d116.88733710000001!3d-1.1897833!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2df148d3b354278d%3A0xeb206b75591d78bb!2sPondok%20Pesantren%20Al-Mujahidin%20Balikpapan!5e0!3m2!1sid!2sid!4v1774892565390!5m2!1sid!2sid" 
                                class="absolute inset-0 w-full h-full border-0 grayscale hover:grayscale-0 transition-all duration-500" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade">
                           </iframe>
                        </div>
                    </div>
                </div>

                <div class="pt-8 border-t border-gray-100 dark:border-gray-800 flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-gray-500 dark:text-gray-400 text-sm">&copy; {{ date('Y') }} Ponpes Al Mujahidin Balikpapan. Built with ❤️ for the Ummah.</p>
                    <div class="flex space-x-6">
                        <a href="#" class="text-gray-400 hover:text-emerald-700 text-sm transition-colors">Privacy Policy</a>
                        <a href="#" class="text-gray-400 hover:text-emerald-700 text-sm transition-colors">Terms of Service</a>
                    </div>
                </div>
            </div>
        </footer>
        <script>
            var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
            var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                themeToggleLightIcon.classList.remove('hidden');
            } else {
                themeToggleDarkIcon.classList.remove('hidden');
            }

            var themeToggleBtn = document.getElementById('theme-toggle');

            if (themeToggleBtn) {
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
            }

            // Mobile menu toggle
            var mobileMenuButton = document.getElementById('mobile-menu-button');
            var mobileMenu = document.getElementById('mobile-menu');

            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });

                // Close mobile menu when clicking a link
                mobileMenu.querySelectorAll('a').forEach(function(link) {
                    link.addEventListener('click', function() {
                        mobileMenu.classList.add('hidden');
                    });
                });
            }
        </script>
        @stack('scripts')
    </body>
</html>
