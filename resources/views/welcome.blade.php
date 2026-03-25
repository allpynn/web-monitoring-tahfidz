<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Sistem Monitoring Tahfidz - Ponpes Al Mujahidin Balikpapan</title>
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
                    <div class="flex items-center">
                        <span class="text-2xl font-black text-emerald-700 dark:text-emerald-500 tracking-tighter">Tahfidz<span class="text-gray-900 dark:text-white">AlMujahidin</span></span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button id="theme-toggle" type="button" class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5 transition-all">
                            <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                            <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464l-.707-.707a1 1 0 00-1.414 1.414l.707.707a1 1 0 001.414-1.414zm2.12 10.607a1 1 0 010-1.414l.706-.707a1 1 0 111.414 1.414l-.707.707a1 1 0 01-1.414 0z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                        </button>
                        <div class="hidden md:flex items-center space-x-8">
                            <a href="#fitur" class="text-gray-600 dark:text-gray-400 hover:text-emerald-700 dark:hover:text-emerald-500 font-medium whitespace-nowrap transition-colors">Fitur</a>
                            <a href="#tentang" class="text-gray-600 dark:text-gray-400 hover:text-emerald-700 dark:hover:text-emerald-500 font-medium whitespace-nowrap transition-colors">Tentang</a>
                            @auth
                                <a href="{{ url('/dashboard') }}" class="px-5 py-2 bg-emerald-700 text-white rounded-full font-semibold hover:bg-emerald-800 transition-all shadow-lg shadow-emerald-200 dark:shadow-none">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-gray-600 dark:text-gray-400 hover:text-emerald-700 dark:hover:text-emerald-500 font-medium whitespace-nowrap transition-colors">Masuk</a>
                                <a href="{{ route('register') }}" class="px-5 py-2 bg-emerald-700 text-white rounded-full font-semibold hover:bg-emerald-800 transition-all shadow-lg shadow-emerald-200 dark:shadow-none">Daftar</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="pt-32 pb-20 lg:pt-48 lg:pb-32 px-4 relative overflow-hidden">
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-emerald-50 dark:bg-emerald-900/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 bg-blue-50 dark:bg-blue-900/20 rounded-full blur-3xl"></div>
            
            <div class="max-w-7xl mx-auto text-center relative z-10">
                <span class="inline-block px-4 py-1.5 mb-6 text-sm font-semibold tracking-wide text-emerald-700 dark:text-emerald-400 uppercase bg-emerald-50 dark:bg-emerald-900/30 rounded-full">Sistem Monitoring Terpadu</span>
                <h1 class="text-4xl md:text-6xl font-extrabold text-gray-900 dark:text-white mb-6 leading-tight">
                    Monitor Hafalan <span class="text-emerald-700 dark:text-emerald-500">Al-Qur'an</span> <br class="hidden md:block"> Ponpes Al Mujahidin
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-400 mb-10 max-w-2xl mx-auto">
                    Platform digital untuk Guru dan Orang Tua dalam memantau perkembangan tahfidz santri secara real-time, akurat, dan transparan.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('login') }}" class="px-8 py-4 bg-emerald-700 text-white rounded-xl font-bold text-lg hover:bg-emerald-800 shadow-lg shadow-emerald-200 dark:shadow-none transition-all">Mulai Monitoring Sekarang</a>
                    <a href="#fitur" class="px-8 py-4 bg-white dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-700 rounded-xl font-bold text-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">Lihat Fitur</a>
                </div>
            </div>
        </section>

        <!-- Stats Section (unchanged start) -->
        <section class="py-20 bg-emerald-900 text-white overflow-hidden">
            <div class="max-w-7xl mx-auto px-4">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                    <div>
                        <div class="text-4xl font-extrabold mb-2">1,500+</div>
                        <div class="text-emerald-300">Santri Aktif</div>
                    </div>
                    <div>
                        <div class="text-4xl font-extrabold mb-2">50+</div>
                        <div class="text-emerald-300">Lembaga</div>
                    </div>
                    <div>
                        <div class="text-4xl font-extrabold mb-2">250k+</div>
                        <div class="text-emerald-300">Setoran Hafalan</div>
                    </div>
                    <div>
                        <div class="text-4xl font-extrabold mb-2">100+</div>
                        <div class="text-emerald-300">Ustadz/Ustadzah</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Tentang Kami Section -->
        <section id="tentang" class="py-24 px-4 relative overflow-hidden bg-white dark:bg-gray-900 transition-colors">
            <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-center gap-16">
                <div class="lg:w-1/2 relative">
                    <div class="absolute -left-10 -top-10 w-64 h-64 bg-emerald-100 dark:bg-emerald-900/20 rounded-full blur-3xl"></div>
                    <div class="relative rounded-[3rem] overflow-hidden shadow-2xl border-8 border-gray-50 dark:border-gray-800">
                        <img src="https://images.unsplash.com/photo-1585036156171-3839efc229b7?q=80&w=1000&auto=format&fit=crop" alt="Pondok Pesantren" class="w-full h-[500px] object-cover hover:scale-105 transition-transform duration-700">
                    </div>
                </div>
                <div class="lg:w-1/2">
                    <span class="text-emerald-700 dark:text-emerald-400 font-bold tracking-widest uppercase text-sm mb-4 block">Mengenal Kami</span>
                    <h2 class="text-4xl font-black text-gray-900 dark:text-white mb-6 leading-tight">Membangun Generasi <br> Qur'ani yang Mandiri</h2>
                    <p class="text-lg text-gray-600 dark:text-gray-400 mb-6 leading-relaxed">
                        **Pondok Pesantren Al Mujahidin Balikpapan** berkomitmen melahirkan penghafal Al-Qur'an yang tidak hanya unggul dalam hafalan, tetapi juga memiliki karakter islami yang kuat.
                    </p>
                    <p class="text-gray-600 dark:text-gray-400 mb-8 leading-relaxed">
                        Sistem Monitoring Tahfidz ini hadir sebagai jembatan komunikasi antara Asatidz dan Wali Santri untuk bersama-sama mengawal perjalanan suci setiap santri dalam menjaga kalam Ilahi.
                    </p>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-700 dark:text-emerald-400">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                            </div>
                            <span class="font-bold text-gray-900 dark:text-white">Kurikulum Terpadu</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-700 dark:text-emerald-400">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                            </div>
                            <span class="font-bold text-gray-900 dark:text-white">Monitoring Akurat</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features -->
        <section id="fitur" class="py-24 px-4 bg-gray-50 dark:bg-gray-900/50 transition-colors">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">Fitur Utama Platform Kami</h2>
                    <p class="text-gray-600 dark:text-gray-400 max-w-xl mx-auto">Dirancang untuk memudahkan kolaborasi antara pengajar dan wali santri.</p>
                </div>
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="p-8 bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-xl transition-all group">
                        <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-emerald-700 transition-colors">
                            <svg class="w-6 h-6 text-emerald-700 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">Manajemen Santri</h3>
                        <p class="text-gray-600 dark:text-gray-400">Kelola data santri, guru, dan wali santri dalam satu dashboard admin yang intuitif.</p>
                    </div>
                    <div class="p-8 bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-xl transition-all group">
                        <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-emerald-700 transition-colors">
                            <svg class="w-6 h-6 text-emerald-700 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">Input Real-time</h3>
                        <p class="text-gray-600 dark:text-gray-400">Guru dapat menginput progres hafalan (Surah, Ayat, Status) langsung setelah setoran.</p>
                    </div>
                    <div class="p-8 bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-xl transition-all group">
                        <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-emerald-700 transition-colors">
                            <svg class="w-6 h-6 text-emerald-700 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">Laporan Visual</h3>
                </div>
            </div>
        </section>

        <!-- Kontak Section -->
        <section id="kontak" class="py-24 px-4 bg-white dark:bg-gray-900 transition-colors">
            <div class="max-w-7xl mx-auto">
                <div class="bg-emerald-900 rounded-[3rem] p-12 lg:p-20 relative overflow-hidden shadow-2xl">
                    <div class="absolute right-0 top-0 w-64 h-64 bg-emerald-800 rounded-full blur-3xl -mr-32 -mt-32"></div>
                    <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-12">
                        <div class="lg:w-1/2">
                            <h2 class="text-3xl md:text-4xl font-black text-white mb-6">Hubungi Kami</h2>
                            <p class="text-emerald-100 text-lg mb-8">
                                Ada pertanyaan mengenai program Tahfidz atau penggunaan sistem monitoring? Silakan hubungi sekretariat kami.
                            </p>
                            <div class="space-y-4">
                                <div class="flex items-start space-x-4">
                                    <div class="w-10 h-10 bg-emerald-800 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-white">Alamat</p>
                                        <p class="text-emerald-200">Jl. Soekarno Hatta KM. 5, Balikpapan, Kalimantan Timur</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="lg:w-auto">
                            <a href="https://wa.me/628123456789" class="inline-flex items-center px-8 py-4 bg-white text-emerald-900 rounded-2xl font-black text-lg hover:bg-emerald-50 transition-all shadow-xl">
                                <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.414 0 .015 5.398.01 12.038c0 2.123.554 4.197 1.609 6.04L0 24l6.13-.1.1.002.003-.003c.1-.1.1-.1.1-.1.1.1.1.1.1.1-.002.003-.003.003-.003.003l-.001.001A11.75 11.75 0 0012.05 24c6.638 0 12.037-5.398 12.042-12.038a11.82 11.82 0 00-3.417-8.413z"></path></svg>
                                Hubungi via WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="py-12 border-t border-gray-100 dark:border-gray-800 transition-colors">
            <div class="max-w-7xl mx-auto px-4 text-center">
                <p class="text-gray-500 dark:text-gray-400">&copy; 2024 Ponpes Al Mujahidin Balikpapan. Built with ❤️ for the Ummah.</p>
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
    </body>
</html>
