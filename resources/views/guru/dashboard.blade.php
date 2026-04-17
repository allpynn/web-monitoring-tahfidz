<x-tahfidz-layout>
    @php
    /**
     * @var array $stats
     * @var array $weeklyLabels
     * @var array $weeklyData
     * @var \Illuminate\Database\Eloquent\Collection|\App\Models\Memorization[] $recent_activities
     * @var \Illuminate\Database\Eloquent\Collection|\App\Models\Memorization[] $parent_feedbacks
     * @var \Illuminate\Support\Collection|\App\Models\Student[] $early_warnings
     * @var \Illuminate\Support\Collection|\App\Models\Student[] $top_targets
     */
    @endphp

    <x-slot name="header">
        Dashboard Guru
    </x-slot>
    <x-slot name="subtitle">
        Selamat datang kembali, Ustadz {{ auth()->user()->name }}.
    </x-slot>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
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
                <canvas id="weeklyChart" data-labels="{{ json_encode($weeklyLabels) }}" data-values="{{ json_encode($weeklyData) }}"></canvas>
            </div>
        </x-tahfidz.card>

        <div class="space-y-8">
            <x-tahfidz.card title="⚠️ Perhatian Khusus" class="border-amber-200 dark:border-amber-900/50">
                <div class="space-y-4">
                    @forelse($early_warnings as $warning)
                        <div class="flex justify-between items-center bg-amber-50 dark:bg-amber-900/20 p-3 rounded-xl border border-amber-100 dark:border-amber-800/50">
                            <div>
                                <p class="font-bold text-sm text-gray-900 dark:text-white">{{ $warning->name }}</p>
                                <p class="text-[11px] text-amber-700 dark:text-amber-500 font-medium">{{ $warning->warning_reason }}</p>
                            </div>
                            <span class="text-xs text-gray-500">{{ $warning->last_mem_date ? $warning->last_mem_date->diffForHumans() : '-' }}</span>
                        </div>
                    @empty
                        <p class="text-xs text-gray-500 italic text-center py-4">Semua santri dalam kondisi baik.</p>
                    @endforelse
                </div>
            </x-tahfidz.card>

            <x-tahfidz.card title="🏆 Top Pencapaian Target">
                <div class="space-y-3">
                    @forelse($top_targets as $top)
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xs">{{ $loop->iteration }}</div>
                            <div class="flex-1">
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-bold dark:text-white">{{ $top->name }}</span>
                                    <span class="text-xs text-emerald-600 font-bold">{{ $top->progress_percent }}%</span>
                                </div>
                                @php $progressWidth = ($top->progress_percent ?? 0) . '%'; @endphp
                                <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-1.5">
                                    <div class="bg-emerald-500 h-1.5 rounded-full" style="width: <?php echo $progressWidth; ?>;"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-gray-500 italic text-center py-2">Belum ada data target.</p>
                    @endforelse
                </div>
            </x-tahfidz.card>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <x-tahfidz.card title="Setoran Terakhir Hari Ini">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($recent_activities as $activity)
                            <tr>
                                <td class="py-3 px-2">
                                    <p class="text-sm font-bold dark:text-white">{{ $activity->student->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $activity->created_at->format('H:i') }} WIB</p>
                                </td>
                                <td class="py-3 px-2 text-sm dark:text-gray-300">
                                    @if($activity->is_present)
                                        Jz:{{ $activity->juz }} {{ $activity->surah }} ({{ $activity->ayat }})
                                    @else
                                        <span class="text-red-500 font-bold text-xs">ABSEN</span>
                                    @endif
                                </td>
                                <td class="py-3 px-2 text-right">
                                    @if($activity->is_present)
                                        <span class="px-2 py-1 {{ $activity->status === 'Lancar' ? 'bg-emerald-100 text-emerald-700' : 'bg-orange-100 text-orange-700' }} text-[10px] font-bold rounded uppercase">
                                            {{ $activity->status }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="py-6 text-center text-gray-400 text-sm">Belum ada setoran masuk hari ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-tahfidz.card>

        <x-tahfidz.card title="Pesan & Komentar Orang Tua">
            <div class="space-y-4">
                @forelse($parent_feedbacks as $feedback)
                    <div class="p-4 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl">
                        <div class="flex justify-between mb-2">
                            <span class="text-xs font-bold text-emerald-700 dark:text-emerald-500">{{ $feedback->student->name }}</span>
                            <span class="text-xs text-gray-400">{{ $feedback->updated_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm text-gray-700 dark:text-gray-300 italic">"{{ $feedback->parent_comment }}"</p>
                        <a href="{{ route('guru.hafalan.edit', $feedback) }}" class="inline-block mt-2 text-[10px] uppercase font-bold text-gray-500 hover:text-emerald-600">Balas / Lihat Detail &rarr;</a>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 italic text-center py-6">Tidak ada pesan baru dari orang tua.</p>
                @endforelse
            </div>
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
        const canvas = document.getElementById('weeklyChart');
        const ctx = canvas.getContext('2d');
        const labels = JSON.parse(canvas.getAttribute('data-labels'));
        const dataValues = JSON.parse(canvas.getAttribute('data-values'));

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Setoran',
                    data: dataValues,
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
