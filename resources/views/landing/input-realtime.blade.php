@extends('layouts.landing')

@section('title', 'Input Real-time - Sistem Monitoring Tahfidz')

@section('content')
<section class="pt-32 pb-20 lg:pt-48 lg:pb-32 px-4 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-teal-50 via-white to-emerald-50 dark:from-gray-900 dark:via-gray-900 dark:to-teal-950 -z-10"></div>
    
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col lg:flex-row-reverse items-center gap-16">
            <div class="lg:w-1/2">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-emerald-700 dark:text-emerald-400 font-bold mb-6 hover:gap-3 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke Beranda
                </a>
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white mb-6 leading-tight">
                    Input Hafalan <br> <span class="text-teal-600 dark:text-teal-400">Instan & Akurat</span>
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-400 mb-8 leading-relaxed">
                    Ucapkan selamat tinggal pada pencatatan manual di kertas. Guru Tahfidz kini dapat mencatat setoran santri secara langsung melalui smartphone atau laptop segera setelah setoran selesai.
                </p>
                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-teal-100 dark:bg-teal-900/30 flex items-center justify-center text-teal-700 dark:text-teal-400 shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-white">Sinkronisasi Instan</h3>
                            <p class="text-gray-600 dark:text-gray-400">Data yang diinput otomatis terupdate di dashboard orang tua dalam hitungan detik.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-teal-100 dark:bg-teal-900/30 flex items-center justify-center text-teal-700 dark:text-teal-400 shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-white">Pilihan Status Cerdas</h3>
                            <p class="text-gray-600 dark:text-gray-400">Tentukan kualitas hafalan (Lancar, Perlu Perbaikan, dll) dengan satu klik.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lg:w-1/2">
                <div class="relative">
                    <div class="absolute -inset-4 bg-teal-500/20 rounded-[3rem] blur-2xl"></div>
                    <div class="relative bg-white dark:bg-gray-800 p-2 rounded-[3rem] shadow-2xl border border-gray-100 dark:border-gray-700">
                        <div class="bg-gray-50 dark:bg-gray-900 rounded-[2.5rem] p-8 aspect-video flex flex-col justify-center overflow-hidden">
                            <!-- Mobile Input Form Mockup -->
                            <div class="max-w-xs mx-auto bg-white dark:bg-gray-800 rounded-3xl shadow-lg p-6 space-y-4 border border-gray-100 dark:border-gray-700">
                                <div class="h-4 w-1/2 bg-gray-100 dark:bg-gray-700 rounded mb-4"></div>
                                <div class="space-y-2">
                                    <div class="h-10 w-full bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700"></div>
                                    <div class="h-10 w-full bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700"></div>
                                    <div class="h-10 w-full bg-emerald-600 rounded-xl shadow-lg"></div>
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
