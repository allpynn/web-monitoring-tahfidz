<x-tahfidz-layout>
    <x-slot name="header">
        Dashboard Admin
    </x-slot>
    <x-slot name="subtitle">
        Selamat datang di pusat kendali Sistem Monitoring Tahfidz.
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-tahfidz.card title="Guru Aktif" value="{{ $guruCount }}" icon='<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path></svg>' />
        <x-tahfidz.card title="Total Santri" value="{{ $studentCount }}" icon='<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM6 8a2 2 0 11-4 0 2 2 0 014 0zM11 18a4.99 4.99 0 01-9 0H11zM19 18a4.99 4.99 0 01-9 0h9z"></path></svg>' />
        <x-tahfidz.card title="Setoran Hafalan" value="{{ $hafalanCount }}" icon='<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9 4.804A7.937 7.937 0 0112 4c1.232 0 2.403.28 3.448.784a1 1 0 01.552.894V17a1 1 0 01-1.342.948 6.037 6.037 0 00-4.32-.2L10 18v-3a1 1 0 011-1h1a1 1 0 100-2h-1V9a1 1 0 10-2 0v5H8a1 1 0 100 2h1a1 1 0 011 1v3l-.336-.112a6.037 6.037 0 00-4.32.2A1 1 0 013 17V5.698a1 1 0 01.552-.894A7.937 7.937 0 017 4c1.232 0 2.403.28 3.448.784z"></path></svg>' />
        <x-tahfidz.card title="Persentase Lancar" value="{{ $lancarPercent }}%" icon='<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>' />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <x-tahfidz.card title="Statistik Mingguan">
            <div class="relative h-64">
                <canvas id="weeklyChart"></canvas>
            </div>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-3 text-center">Jumlah setoran hafalan 7 hari terakhir</p>
        </x-tahfidz.card>

        @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script>
            (function() {
                const isDark = document.documentElement.classList.contains('dark');
                const gridColor = isDark ? 'rgba(255,255,255,0.07)' : 'rgba(0,0,0,0.06)';
                const textColor = isDark ? '#9ca3af' : '#6b7280';

                const ctx = document.getElementById('weeklyChart');
                if (!ctx) return;

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: @json($weeklyLabels),
                        datasets: [{
                            label: 'Setoran Hafalan',
                            data: @json($weeklyData),
                            backgroundColor: 'rgba(5, 150, 105, 0.75)',
                            borderColor: 'rgba(5, 150, 105, 1)',
                            borderWidth: 2,
                            borderRadius: 8,
                            borderSkipped: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: ctx => ` ${ctx.parsed.y} setoran`
                                }
                            }
                        },
                        scales: {
                            x: {
                                ticks: { color: textColor, font: { size: 11 } },
                                grid: { display: false },
                                border: { display: false }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    color: textColor,
                                    precision: 0,
                                    stepSize: 1
                                },
                                grid: { color: gridColor },
                                border: { display: false }
                            }
                        }
                    }
                });
            })();
        </script>
        @endpush
        
        <x-tahfidz.card title="Aktivitas Terkini">
            <ul class="space-y-4">
                <li class="flex items-center p-3 bg-gray-50 dark:bg-gray-900/40 rounded-2xl border border-transparent hover:border-emerald-100 dark:hover:border-emerald-900/50 transition-all">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center mr-4 text-emerald-600 dark:text-emerald-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-900 dark:text-white leading-tight">Ustadz Ahmad menginput hafalan Fulan</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Baru saja • An-Naba: 1-10</p>
                    </div>
                </li>
                <li class="flex items-center p-3 bg-gray-50 dark:bg-gray-900/40 rounded-2xl border border-transparent hover:border-emerald-100 dark:hover:border-emerald-900/50 transition-all">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center mr-4 text-blue-600 dark:text-blue-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-900 dark:text-white leading-tight">Santri baru "Aisyah" terdaftar</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">1 jam yang lalu • Kelas Tahfidz A</p>
                    </div>
                </li>
            </ul>
        </x-tahfidz.card>
    </div>
</x-tahfidz-layout>
