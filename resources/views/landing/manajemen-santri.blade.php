@extends('layouts.landing')

@section('title', 'Manajemen Santri - Sistem Monitoring Tahfidz')

@section('content')
<section class="pt-32 pb-20 lg:pt-48 lg:pb-32 px-4 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-emerald-50 via-white to-teal-50 dark:from-gray-900 dark:via-gray-900 dark:to-emerald-950 -z-10"></div>
    
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col lg:flex-row items-center gap-16">
            <div class="lg:w-1/2">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-emerald-700 dark:text-emerald-400 font-bold mb-6 hover:gap-3 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke Beranda
                </a>
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white mb-6 leading-tight">
                    Manajemen Santri <br> <span class="text-emerald-700 dark:text-emerald-400">Terpusat & Efisien</span>
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-400 mb-8 leading-relaxed">
                    Kelola ribuan data santri tanpa ribet. Dashboard admin kami dirancang untuk memberikan kendali penuh bagi pengelola pondok pesantren dalam mengatur data santri, guru, hingga wali murid dalam satu layar.
                </p>
                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-700 dark:text-emerald-400 shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-white">Multi-Role Support</h3>
                            <p class="text-gray-600 dark:text-gray-400">Pisahkan akses antara Admin Utama, Guru Tahfidz, dan Wali Santri dengan sistem keamanan tingkat tinggi.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-700 dark:text-emerald-400 shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-white">Database Lengkap</h3>
                            <p class="text-gray-600 dark:text-gray-400">Simpan informasi NIS, Target Juz, Riwayat Kesehatan, hingga data kontak darurat dengan rapi.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lg:w-1/2">
                <div class="relative">
                    <div class="absolute -inset-4 bg-emerald-500/20 rounded-[3rem] blur-2xl"></div>
                    <div class="relative bg-white dark:bg-gray-800 p-2 rounded-[3rem] shadow-2xl border border-gray-100 dark:border-gray-700">
                        <div class="bg-gray-50 dark:bg-gray-900 rounded-[2.5rem] p-8 aspect-video flex items-center justify-center overflow-hidden">
                            <!-- Visual representation -->
                            <div class="w-full space-y-4">
                                <div class="h-8 w-1/3 bg-emerald-100 dark:bg-emerald-900/50 rounded-lg animate-pulse"></div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="h-24 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700"></div>
                                    <div class="h-24 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700"></div>
                                </div>
                                <div class="h-48 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 space-y-3">
                                    <div class="h-6 w-full bg-gray-100 dark:bg-gray-700 rounded"></div>
                                    <div class="h-6 w-full bg-gray-100 dark:bg-gray-700 rounded"></div>
                                    <div class="h-6 w-3/4 bg-gray-100 dark:bg-gray-700 rounded"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
