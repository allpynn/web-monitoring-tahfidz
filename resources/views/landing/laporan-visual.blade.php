@extends('layouts.landing')

@section('title', 'Laporan Visual - Sistem Monitoring Tahfidz')

@section('content')
<section class="pt-32 pb-20 lg:pt-48 lg:pb-32 px-4 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-blue-50 via-white to-emerald-50 dark:from-gray-900 dark:via-gray-900 dark:to-blue-950 -z-10"></div>
    
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col lg:flex-row items-center gap-16">
            <div class="lg:w-1/2">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-emerald-700 dark:text-emerald-400 font-bold mb-6 hover:gap-3 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke Beranda
                </a>
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white mb-6 leading-tight">
                    Analitik Progres <br> <span class="text-blue-600 dark:text-blue-400">Transparan & Bermakna</span>
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-400 mb-8 leading-relaxed">
                    Kami menyajikan data bukan hanya sekadar angka, tapi informasi yang bermakna. Orang tua dapat melihat grafik pertumbuhan hafalan anak mereka, tren mingguan, hingga mengunduh laporan resmi dalam format PDF.
                </p>
                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-700 dark:text-blue-400 shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-white">Chart Interaktif</h3>
                            <p class="text-gray-600 dark:text-gray-400">Visualisasi data menggunakan Chart.js yang memudahkan pemahaman perkembangan hafalan.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-700 dark:text-blue-400 shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-white">Export PDF 1-Klik</h3>
                            <p class="text-gray-600 dark:text-gray-400">Unduh laporan bulanan atau semesteran dengan format profesional yang siap cetak.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lg:w-1/2">
                <div class="relative">
                    <div class="absolute -inset-4 bg-blue-500/20 rounded-[3rem] blur-2xl"></div>
                    <div class="relative bg-white dark:bg-gray-800 p-2 rounded-[3rem] shadow-2xl border border-gray-100 dark:border-gray-700">
                        <div class="bg-gray-50 dark:bg-gray-900 rounded-[2.5rem] p-8 aspect-video flex items-center justify-center overflow-hidden">
                            <!-- Chart Mockup -->
                            <div class="w-full h-full flex items-end justify-between gap-2 p-4">
                                <div class="w-8 bg-blue-500 rounded-t-lg" style="height: 40%"></div>
                                <div class="w-8 bg-blue-500 rounded-t-lg" style="height: 60%"></div>
                                <div class="w-8 bg-blue-600 rounded-t-lg" style="height: 85%"></div>
                                <div class="w-8 bg-blue-400 rounded-t-lg" style="height: 50%"></div>
                                <div class="w-8 bg-blue-700 rounded-t-lg" style="height: 95%"></div>
                                <div class="w-8 bg-blue-500 rounded-t-lg" style="height: 70%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
