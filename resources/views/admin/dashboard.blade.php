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
                <canvas id="weeklyChart" data-labels='@json($weeklyLabels)' data-values='@json($weeklyData)'></canvas>
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
                        labels: JSON.parse(ctx.dataset.labels),
                        datasets: [{
                            label: 'Setoran Hafalan',
                            data: JSON.parse(ctx.dataset.values),
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
        
        <x-tahfidz.card title="Distribusi Kinerja Guru">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50/50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-800">
                        <tr>
                            <th class="py-2 px-3">Nama Ustadz</th>
                            <th class="py-2 px-3 text-center">Jml Santri</th>
                            <th class="py-2 px-3 text-center">Setoran Bulan Ini</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($teacher_performance as $guru)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition-colors">
                                <td class="py-3 px-3">
                                    <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $guru->name }}</p>
                                </td>
                                <td class="py-3 px-3 text-center text-sm font-medium text-emerald-600 dark:text-emerald-400">
                                    {{ $guru->students_count }} 
                                </td>
                                <td class="py-3 px-3 text-center text-sm font-bold text-gray-700 dark:text-gray-300">
                                    {{ $guru->total_memorizations }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-6 text-center text-gray-400 italic">Belum ada data guru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-tahfidz.card>
    </div>
</x-tahfidz-layout>
