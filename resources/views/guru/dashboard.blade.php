<x-tahfidz-layout>
    <x-slot name="header">
        Dashboard Guru
    </x-slot>
    <x-slot name="subtitle">
        Selamat datang kembali, Ustadz {{ auth()->user()->name }}.
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <x-tahfidz.card title="Total Setoran" :value="$stats['total_hafalan']" icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5S19.832 5.477 21 6.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>'>
            <p class="text-xs text-gray-500">Jumlah total ayat/surah yang disetor santri Anda.</p>
        </x-tahfidz.card>

        <x-tahfidz.card title="Total Presensi" :value="$stats['total_absensi']" icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 002-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>'>
            <p class="text-xs text-gray-500">Catatan kehadiran santri dalam halaqah.</p>
        </x-tahfidz.card>

        <x-tahfidz.card title="Setoran Hari Ini" :value="$stats['today_entries']" icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 002-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>'>
            <p class="text-xs text-gray-500">Data yang baru saja diinputkan hari ini.</p>
        </x-tahfidz.card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <x-tahfidz.card title="Aktivitas Halaqah Mingguan">
            <div class="h-64">
                <canvas id="weeklyChart"></canvas>
            </div>
        </x-tahfidz.card>

        <x-tahfidz.card title="Petunjuk Guru">
            <ul class="space-y-4">
                <li class="flex gap-4">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400 flex items-center justify-center flex-shrink-0 font-bold text-sm">1</div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Pilih menu <strong>Input Hafalan</strong> untuk mencatat setoran santri baru.</p>
                </li>
                <li class="flex gap-4">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400 flex items-center justify-center flex-shrink-0 font-bold text-sm">2</div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Pastikan status <strong>Lancar</strong> atau <strong>Perlu Perbaikan</strong> sesuai hasil evaluasi.</p>
                </li>
                <li class="flex gap-4">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400 flex items-center justify-center flex-shrink-0 font-bold text-sm">3</div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Anda dapat memonitor detail progres dan mencetak raport di menu <strong>Data Santri</strong>.</p>
                </li>
            </ul>
        </x-tahfidz.card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-1 gap-8">
        <x-tahfidz.card title="Aksi Cepat">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('guru.hafalan.create') }}" class="p-6 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800 rounded-3xl flex flex-row items-center gap-6 group hover:bg-emerald-600 transition-all">
                    <div class="w-14 h-14 bg-emerald-600 text-white rounded-2xl flex items-center justify-center group-hover:bg-white group-hover:text-emerald-600 transition-colors shadow-lg shadow-emerald-500/20">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <div class="text-left">
                        <span class="block font-bold text-lg text-gray-900 dark:text-white group-hover:text-white transition-colors">Input Hafalan</span>
                        <span class="text-xs text-gray-500 group-hover:text-emerald-50 dark:group-hover:text-emerald-200 transition-colors">Catat setoran hafalan hari ini</span>
                    </div>
                </a>

                <a href="{{ route('guru.students.index') }}" class="p-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-3xl flex flex-row items-center gap-6 group hover:bg-blue-600 transition-all">
                    <div class="w-14 h-14 bg-blue-600 text-white rounded-2xl flex items-center justify-center group-hover:bg-white group-hover:text-blue-600 transition-colors shadow-lg shadow-blue-500/20">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <div class="text-left">
                        <span class="block font-bold text-lg text-gray-900 dark:text-white group-hover:text-white transition-colors">Data Santri</span>
                        <span class="text-xs text-gray-500 group-hover:text-blue-50 dark:group-hover:text-blue-200 transition-colors">Kelola & monitor bimbingan</span>
                    </div>
                </a>
            </div>
        </x-tahfidz.card>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('weeklyChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($weeklyLabels) !!},
                datasets: [{
                    label: 'Jumlah Setoran',
                    data: {!! json_encode($weeklyData) !!},
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { display: false } },
                    x: { grid: { display: false } }
                }
            }
        });
    </script>
    @endpush
</x-tahfidz-layout>
</x-tahfidz-layout>
