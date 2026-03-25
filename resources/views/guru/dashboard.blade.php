<x-tahfidz-layout>
    <x-slot name="header">
        Dashboard Guru
    </x-slot>
    <x-slot name="subtitle">
        Selamat bertugas, Ustadz/Ustadzah. Mari pantau perkembangan santri.
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <x-tahfidz.card title="Hafalan Diinput" :value="$stats['total_hafalan']" icon='<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM6 8a2 2 0 11-4 0 2 2 0 014 0zM11 18a4.99 4.99 0 01-9 0H11zM19 18a4.99 4.99 0 01-9 0h9z"></path></svg>' />
        <x-tahfidz.card title="Total Absensi" :value="$stats['total_absensi']" icon='<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path></svg>' />
        <x-tahfidz.card title="Input Hari Ini" :value="$stats['today_entries']" icon='<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>' />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <x-tahfidz.card title="Aksi Cepat">
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('guru.hafalan.create') }}" class="p-6 bg-emerald-50 dark:bg-emerald-900/20 border-2 border-dashed border-emerald-200 dark:border-emerald-800 rounded-3xl flex flex-col items-center justify-center text-center group hover:bg-emerald-100 dark:hover:bg-emerald-900/40 transition-all">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-700 text-white flex items-center justify-center mb-4 shadow-lg shadow-emerald-200 dark:shadow-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <span class="text-sm font-bold text-emerald-800 dark:text-emerald-400">Input Hafalan</span>
                </a>
                <a href="{{ route('guru.hafalan.index') }}" class="p-6 bg-blue-50 dark:bg-blue-900/20 border-2 border-dashed border-blue-200 dark:border-blue-800 rounded-3xl flex flex-col items-center justify-center text-center group hover:bg-blue-100 dark:hover:bg-blue-900/40 transition-all">
                    <div class="w-12 h-12 rounded-2xl bg-blue-700 text-white flex items-center justify-center mb-4 shadow-lg shadow-blue-200 dark:shadow-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                    <span class="text-sm font-bold text-blue-800 dark:text-blue-400">Riwayat Input</span>
                </a>
            </div>
        </x-tahfidz.card>

        <x-tahfidz.card title="Jadwal Pengujian">
            <div class="space-y-4">
                <div class="flex items-center p-4 bg-gray-50 dark:bg-gray-900/40 rounded-2xl">
                    <div class="w-10 h-10 rounded-xl bg-gray-200 dark:bg-gray-700 flex items-center justify-center mr-4 text-gray-500 font-bold text-xs uppercase text-center leading-tight">
                        SEN<br>08
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-900 dark:text-white">Ujian Harian Santri Kelas A</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">08:00 - 10:00 • Masjid Jami</p>
                    </div>
                </div>
            </div>
        </x-tahfidz.card>
    </div>
</x-tahfidz-layout>
