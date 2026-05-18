<x-tahfidz-layout>
    @php
        /**
         * @var array $stats
         * @var array $weeklyLabels
         * @var array $weeklyData
         * @var \Illuminate\Database\Eloquent\Collection|\App\Models\RiwayatHafalan[] $recent_activities
         * @var \Illuminate\Database\Eloquent\Collection|\App\Models\RiwayatHafalan[] $parent_feedbacks
         * @var \Illuminate\Support\Collection|\App\Models\Student[] $early_warnings
         * @var \Illuminate\Support\Collection|\App\Models\Student[] $top_targets
         */
    @endphp

    <x-slot name="header">
        Dashboard Guru
    </x-slot>
    <x-slot name="subtitle">
        Selamat datang kembali, {{ auth()->user()->gender === 'Perempuan' ? 'Ustadzah' : 'Ustadz' }}
        {{ auth()->user()->name }}.
    </x-slot>

    <!-- AKSI CEPAT DIBAGIAN ATAS -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
        <a href="{{ route('guru.hafalan.create') }}"
            class="p-4 bg-emerald-600 dark:bg-emerald-700 text-white rounded-2xl flex items-center gap-4 hover:bg-emerald-700 transition shadow-lg">
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </div>
            <div>
                <span class="block font-bold text-lg">Input Hafalan</span>
                <span class="text-xs text-emerald-100">Catat setoran hafalan hari ini</span>
            </div>
        </a>

        <a href="{{ route('guru.students.index') }}"
            class="p-4 bg-blue-600 dark:bg-blue-700 text-white rounded-2xl flex items-center gap-4 hover:bg-blue-700 transition shadow-lg">
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
            </div>
            <div>
                <span class="block font-bold text-lg">Daftar Santri</span>
                <span class="text-xs text-blue-100">Kelola dan monitor bimbingan</span>
            </div>
        </a>
    </div>

    <!-- CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <x-tahfidz.card title="Total Setoran" :value="$stats['total_hafalan']"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5S19.832 5.477 21 6.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>' />
        <x-tahfidz.card title="Total Santri" :value="$stats['total_santri']"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>' />
        <x-tahfidz.card title="Setoran Hari Ini" :value="$stats['today_entries']"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 002-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>' />
    </div>

    <!-- FILTER & CHART -->
    <div class="mb-4 flex flex-wrap items-center justify-between gap-4">
        <h3 class="font-bold text-gray-800 dark:text-white">Statistik Hafalan</h3>
        <form action="{{ route('guru.dashboard') }}" method="GET"
            class="flex items-center gap-4 bg-white/80 dark:bg-gray-800/80 p-2 rounded-xl border border-gray-100 dark:border-gray-700">
            <select name="month"
                class="rounded-lg border-gray-200 dark:border-gray-700 bg-transparent text-xs font-bold"
                onchange="this.form.submit()">
                @foreach(range(1, 12) as $m)
                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create($year, $m, 1)->translatedFormat('F') }}
                    </option>
                @endforeach
            </select>
            <select name="year" class="rounded-lg border-gray-200 dark:border-gray-700 bg-transparent text-xs font-bold"
                onchange="this.form.submit()">
                @foreach(range(now()->year - 3, now()->year + 1) as $y)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <x-tahfidz.card title="Grafik Bulanan">
            <div class="relative h-48 w-full">
                <canvas id="weeklyChart" data-labels='@json($weeklyLabels)' data-values='@json($weeklyData)'>
                </canvas>
            </div>
        </x-tahfidz.card>

        <x-tahfidz.card title="⚠️ Perhatian Khusus (Alert)" class="border-red-200 dark:border-red-900">
            <div class="space-y-3">
                @forelse($early_warnings as $warning)
                    <div
                        class="flex justify-between items-center bg-red-50 dark:bg-red-900/20 p-3 rounded-lg border-l-4 border-red-500">
                        <div>
                            <p class="font-bold text-sm text-red-900 dark:text-red-200">{{ $warning->name }}</p>
                            <p class="text-[11px] text-red-600 font-bold uppercase">{{ $warning->warning_reason }}</p>
                        </div>
                        <span
                            class="text-xs text-red-400 font-bold">{{ $warning->last_mem_date ? $warning->last_mem_date->diffForHumans() : '-' }}</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 italic text-center py-4 bg-gray-50 dark:bg-gray-800 rounded-lg">Semua
                        santri dalam kondisi baik, rutin setor.</p>
                @endforelse
            </div>
        </x-tahfidz.card>
    </div>

    <!-- OTHERS -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <x-tahfidz.card title="🏆 Top Pencapaian Target">
            <div class="space-y-4 mt-4">
                @forelse($top_targets as $top)
                    <div>
                        <div class="text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">
                            {{ $top->name }}
                        </div>
                        <div class="space-y-3 pl-2 border-l-2 border-gray-100 dark:border-gray-700">
                            @foreach($top->targets as $t)
                                @php
                                    $p = $top->getJuzProgress($t->target_juz);
                                @endphp
                                <div>
                                    <div class="flex justify-between text-[10px] mb-1 font-semibold uppercase tracking-tight">
                                        <span>Juz {{ $t->target_juz }}</span>
                                        <span class="{{ $p == 100 ? 'text-emerald-500' : 'text-gray-500' }}">{{ $p }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-1.5">
                                        <div class="h-1.5 rounded-full transition-all duration-700
                                                {{ $p == 100 ? 'bg-emerald-500' : ($p >= 50 ? 'bg-blue-400' : 'bg-red-300') }}"
                                            style="width: {{ $p }}%">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-gray-500 italic">Belum ada data target.</p>
                @endforelse
            </div>
        </x-tahfidz.card>

        <x-tahfidz.card title="Data Setoran Terakhir">
            <table class="w-full text-left mt-2 text-sm">
                <tbody>
                    @forelse($recent_activities as $activity)
                        <tr class="border-b border-gray-50 dark:border-gray-800">
                            <td class="py-2"><span
                                    class="font-bold block w-24 truncate">{{ $activity->student->name }}</span><span
                                    class="text-[10px] text-gray-400">{{ \Carbon\Carbon::parse($activity->tanggal)->format('d/m/Y') }}</span>
                            </td>
                            <td class="py-2 text-gray-600 dark:text-gray-300">Jz.{{ $activity->juz }} {{ $activity->surah }}
                            </td>
                            <td class="py-2 text-right"><span
                                    class="text-[10px] uppercase font-bold text-white px-2 py-0.5 rounded {{ $activity->status === 'Lancar' ? 'bg-emerald-500' : 'bg-orange-500' }}">{{ $activity->status }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center italic text-gray-400 py-4">Belum ada data setoran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </x-tahfidz.card>
    </div>

    <!-- PESAN DARI ORANG TUA -->
    <div class="mt-8">
        <x-tahfidz.card title="📨 Pesan Dari Orang Tua">
            <div class="space-y-4 mt-3">
                @forelse($parent_messages as $msg)
                    <div class="p-4 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl">
                        <div class="flex justify-between mb-2">
                            <span
                                class="text-xs font-bold text-emerald-700 dark:text-emerald-500">{{ $msg->sender->name ?? 'Orang Tua' }}
                                — {{ $msg->student->name ?? '' }}</span>
                            <span class="text-xs text-gray-400">{{ $msg->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm text-gray-700 dark:text-gray-300 italic">"{{ $msg->message }}"</p>
                        <form action="{{ route('guru.messages.reply', $msg) }}" method="POST" class="mt-3 flex gap-2">
                            @csrf
                            <input type="text" name="message" required placeholder="Balas pesan..."
                                class="flex-1 rounded-lg border-gray-200 dark:border-gray-700 dark:bg-gray-900 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                            <button type="submit"
                                class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg transition-colors">Balas</button>
                        </form>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 italic text-center py-6">Tidak ada pesan baru dari orang tua.</p>
                @endforelse
            </div>
        </x-tahfidz.card>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const ctx = document.getElementById('weeklyChart');
                if (ctx) {
                    const labels = JSON.parse(ctx.dataset.labels);
                    const values = JSON.parse(ctx.dataset.values);
                    const isDark = document.documentElement.classList.contains('dark');
                    const gridColor = isDark ? 'rgba(255,255,255,0.07)' : 'rgba(0,0,0,0.07)';
                    const labelColor = isDark ? '#9ca3af' : '#6b7280';

                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Jumlah Setoran',
                                data: values,
                                backgroundColor: 'rgba(16, 185, 129, 0.8)',
                                hoverBackgroundColor: 'rgba(5, 150, 105, 1)',
                                borderRadius: 6,
                                borderSkipped: false,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: { callbacks: { label: (ctx) => ` ${ctx.parsed.y} setoran` } }
                            },
                            scales: {
                                x: {
                                    grid: { color: gridColor },
                                    ticks: { color: labelColor, font: { weight: 'bold' } }
                                },
                                y: {
                                    beginAtZero: true,
                                    ticks: { color: labelColor, precision: 0 },
                                    grid: { color: gridColor }
                                }
                            }
                        }
                    });
                }
            });
        </script>
    @endpush
</x-tahfidz-layout>