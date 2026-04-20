@extends('layouts.landing')

@section('title', 'Sistem Monitoring Tahfidz - Ponpes Al Mujahidin')

@section('content')
<!-- Hero Section -->
<section class="pt-32 pb-20 lg:pt-48 lg:pb-32 px-4 relative overflow-hidden">
    <!-- Animated Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-emerald-50 via-white to-teal-50 dark:from-gray-900 dark:via-gray-900 dark:to-emerald-950 -z-10"></div>
    <div class="absolute top-1/4 right-1/4 w-[500px] h-[500px] bg-emerald-200/30 dark:bg-emerald-800/10 rounded-full blur-3xl animate-pulse -z-10"></div>
    <div class="absolute bottom-1/4 left-1/4 w-[400px] h-[400px] bg-teal-100/40 dark:bg-teal-900/10 rounded-full blur-3xl -z-10"></div>

    <div class="max-w-5xl mx-auto text-center relative z-10">
        <div class="flex justify-center mb-8">
            <x-application-logo class="h-28 w-auto drop-shadow-2xl hover:scale-110 transition-transform duration-700" />
        </div>
        <span class="inline-flex items-center gap-2 px-4 py-1.5 mb-6 text-sm font-semibold tracking-wide text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 rounded-full">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping inline-block"></span>
            Sistem Monitoring Terpadu
        </span>
        <h1 class="text-4xl md:text-6xl font-extrabold text-gray-900 dark:text-white mb-6 leading-tight">
            Monitor Hafalan <span class="text-emerald-700 dark:text-emerald-400">Al-Qur'an</span><br class="hidden md:block"> Ponpes Al Mujahidin
        </h1>
        <p class="text-lg text-gray-600 dark:text-gray-400 mb-10 max-w-2xl mx-auto leading-relaxed">
            Platform digital untuk Guru dan Orang Tua dalam memantau perkembangan tahfidz santri secara <strong>real-time</strong>, akurat, dan transparan.
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-emerald-700 text-white rounded-2xl font-bold text-lg hover:bg-emerald-800 shadow-lg shadow-emerald-200 dark:shadow-emerald-900/30 transition-all hover:-translate-y-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Mulai Monitoring
            </a>
            <a href="#fitur" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-700 rounded-2xl font-bold text-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-all hover:-translate-y-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                Lihat Fitur
            </a>
        </div>

        <!-- Trust Badges -->
        <div class="mt-14 flex flex-wrap justify-center gap-6 text-sm text-gray-500 dark:text-gray-400">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Real-time Monitoring
            </div>
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Laporan PDF Otomatis
            </div>
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Akses Multi-Peran
            </div>
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Notifikasi Orang Tua
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
                <img src="{{ asset('assets/img/banner1.jpg') }}" alt="Pondok Pesantren" class="w-full h-[500px] object-cover hover:scale-105 transition-transform duration-700">
                <!-- Overlay badge -->
                <div class="absolute bottom-6 left-6 right-6 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md rounded-2xl p-4 flex items-center gap-4 shadow-lg">
                    <div class="w-12 h-12 rounded-xl bg-emerald-700 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <div>
                        <p class="font-bold text-gray-900 dark:text-white text-sm">The School of Future Leaders</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Ponpes Al Mujahidin Balikpapan</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="lg:w-1/2">
            <span class="text-emerald-700 dark:text-emerald-400 font-bold tracking-widest uppercase text-sm mb-4 block">Mengenal Kami</span>
            <h2 class="text-4xl font-black text-gray-900 dark:text-white mb-6 leading-tight">Membangun Generasi <br> Qur'ani yang Mandiri</h2>
            <p class="text-lg text-gray-600 dark:text-gray-400 mb-4 leading-relaxed">
                <strong class="text-gray-900 dark:text-white">Pondok Pesantren Al Mujahidin Balikpapan</strong> berkomitmen melahirkan penghafal Al-Qur'an yang tidak hanya unggul dalam hafalan, tetapi juga memiliki karakter islami yang kuat.
            </p>
            <p class="text-gray-600 dark:text-gray-400 mb-8 leading-relaxed">
                Sistem Monitoring Tahfidz ini hadir sebagai jembatan komunikasi antara Asatidz dan Wali Santri untuk bersama-sama mengawal perjalanan suci setiap santri dalam menjaga kalam Ilahi.
            </p>
            <div class="grid grid-cols-2 gap-4">
                <div class="flex items-center space-x-3 p-4 bg-emerald-50 dark:bg-emerald-900/20 rounded-2xl">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-800/50 flex items-center justify-center text-emerald-700 dark:text-emerald-400 shrink-0">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                    </div>
                    <span class="font-semibold text-gray-900 dark:text-white text-sm">Kurikulum Terpadu</span>
                </div>
                <div class="flex items-center space-x-3 p-4 bg-emerald-50 dark:bg-emerald-900/20 rounded-2xl">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-800/50 flex items-center justify-center text-emerald-700 dark:text-emerald-400 shrink-0">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                    </div>
                    <span class="font-semibold text-gray-900 dark:text-white text-sm">Monitoring Akurat</span>
                </div>
                <div class="flex items-center space-x-3 p-4 bg-emerald-50 dark:bg-emerald-900/20 rounded-2xl">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-800/50 flex items-center justify-center text-emerald-700 dark:text-emerald-400 shrink-0">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                    </div>
                    <span class="font-semibold text-gray-900 dark:text-white text-sm">Pembinaan 24 Jam</span>
                </div>
                <div class="flex items-center space-x-3 p-4 bg-emerald-50 dark:bg-emerald-900/20 rounded-2xl">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-800/50 flex items-center justify-center text-emerald-700 dark:text-emerald-400 shrink-0">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                    </div>
                    <span class="font-semibold text-gray-900 dark:text-white text-sm">Laporan Transparan</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features -->
<section id="fitur" class="py-24 px-4 bg-gray-50 dark:bg-gray-950 transition-colors">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-1.5 mb-4 text-sm font-semibold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 rounded-full">Platform Kami</span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">Fitur Utama Platform Kami</h2>
            <p class="text-gray-600 dark:text-gray-400 max-w-xl mx-auto">Dirancang untuk memudahkan kolaborasi antara pengajar dan wali santri.</p>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            <!-- Card 1 -->
            <div class="p-8 bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="w-14 h-14 bg-emerald-100 dark:bg-emerald-900/30 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-emerald-700 transition-colors duration-300">
                    <svg class="w-7 h-7 text-emerald-700 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">Manajemen Santri</h3>
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed">Kelola data santri, guru, dan wali santri dalam satu dashboard admin yang intuitif dan lengkap.</p>
                <a href="{{ route('fitur.manajemen') }}" class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-700 flex items-center text-emerald-700 dark:text-emerald-400 font-semibold text-sm group-hover:gap-2 transition-all">
                    <span>Lihat detail</span>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
            <!-- Card 2 -->
            <div class="p-8 bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="w-14 h-14 bg-emerald-100 dark:bg-emerald-900/30 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-emerald-700 transition-colors duration-300">
                    <svg class="w-7 h-7 text-emerald-700 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
                <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">Input Real-time</h3>
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed">Guru dapat menginput progres hafalan (Surah, Ayat, Status) langsung setelah setoran selesai.</p>
                <a href="{{ route('fitur.realtime') }}" class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-700 flex items-center text-emerald-700 dark:text-emerald-400 font-semibold text-sm group-hover:gap-2 transition-all">
                    <span>Lihat detail</span>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
            <!-- Card 3 -->
            <div class="p-8 bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="w-14 h-14 bg-emerald-100 dark:bg-emerald-900/30 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-emerald-700 transition-colors duration-300">
                    <svg class="w-7 h-7 text-emerald-700 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                </div>
                <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">Laporan Visual</h3>
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed">Grafik perkembangan hafalan dan laporan PDF yang bisa diunduh kapan saja oleh wali santri.</p>
                <a href="{{ route('fitur.laporan') }}" class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-700 flex items-center text-emerald-700 dark:text-emerald-400 font-semibold text-sm group-hover:gap-2 transition-all">
                    <span>Lihat detail</span>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
