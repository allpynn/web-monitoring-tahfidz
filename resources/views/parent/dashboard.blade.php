<x-tahfidz-layout>
    <x-slot name="header">
        Monitoring Hafalan Ananda
    </x-slot>
    <x-slot name="subtitle">
        Progres hafalan santri di bawah wali santri {{ auth()->user()->name }}.
    </x-slot>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 rounded-2xl border border-emerald-100 dark:border-emerald-800 font-bold">
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-10">
        @forelse($students as $student)
            <div>
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center font-extrabold text-xl shadow-lg shadow-emerald-500/20">
                            {{ substr($student->name, 0, 1) }}
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $student->name }}</h2>
                            <p class="text-sm text-gray-500">Dibimbing oleh: <span class="font-bold text-emerald-600">{{ $student->guru->name ?? 'Ustadz Belum Ditentukan' }}</span></p>
                        </div>
                    </div>
                    <a href="{{ route('parent.history.export', $student) }}" class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-xl text-sm font-bold hover:bg-gray-50 flex items-center gap-2 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Rekap Setoran
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <x-tahfidz.card title="Pencapaian" :value="'Juz ' . $student->current_juz" />
                    <x-tahfidz.card title="Progres Target" :value="$student->target_progress . '%'" />
                    <x-tahfidz.card title="Prediksi Selesai" :value="$student->prediction ?? 'Dalam Evaluasi'" />
                    <x-tahfidz.card title="Ustadz Pendamping" :value="$student->guru->name ?? '-'" />
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2">
                        <x-tahfidz.card title="Riwayat Terakhir">
                            <div class="overflow-x-auto">
                                <table class="w-full text-left">
                                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                        @forelse($student->memorizations->take(5) as $m)
                                            <tr>
                                                <td class="py-4 text-xs text-gray-500 font-bold bg-gray-50/30 dark:bg-gray-900/10 px-3 rounded-l-xl">
                                                    {{ $m->created_at->format('d M') }}
                                                </td>
                                                <td class="py-4 px-4 font-bold text-gray-900 dark:text-white text-sm">
                                                    @if($m->is_present)
                                                        Juz {{ $m->juz }}: {{ $m->surah }} ({{ $m->ayat }})
                                                    @else
                                                        <span class="text-red-500 uppercase">Izin / Sakit</span>
                                                    @endif
                                                </td>
                                                <td class="py-4 px-4">
                                                    @if($m->is_present)
                                                        <span class="px-2 py-0.5 {{ $m->status === 'Lancar' ? 'bg-emerald-100 text-emerald-700' : 'bg-orange-100 text-orange-700' }} text-[10px] font-bold rounded uppercase">
                                                            {{ $m->status }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="py-4 text-right pr-3 rounded-r-xl">
                                                    @if($m->parent_comment)
                                                        <span class="text-blue-500 text-[10px] font-bold uppercase">Feedback Terkirim</span>
                                                    @else
                                                        <!-- Modal Trigger or simple form -->
                                                        <form action="{{ route('parent.hafalan.comment', $m) }}" method="POST" class="inline-flex items-center gap-2">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="text" name="parent_comment" placeholder="Beri feedback..." class="text-[10px] px-2 py-1 rounded border-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-emerald-500">
                                                            <button type="submit" class="p-1 bg-emerald-600 text-white rounded hover:bg-emerald-700"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="py-6 text-center text-gray-400 italic">Belum ada riwayat hafalan.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </x-tahfidz.card>
                    </div>

                    <div class="lg:col-span-1 space-y-6">
                        @if($student->latest_notes)
                            <div class="p-4 bg-emerald-50 dark:bg-emerald-900/30 border-l-4 border-emerald-500 rounded-r-xl shadow-sm">
                                <h4 class="text-xs font-bold text-emerald-800 dark:text-emerald-400 mb-1 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                                    Pesan Ustadz
                                </h4>
                                <p class="text-sm text-emerald-700 dark:text-emerald-300 italic">"{{ $student->latest_notes }}"</p>
                            </div>
                        @endif

                        <x-tahfidz.card title="Kualitas Hafalan (30 Hari)">
                            <div class="relative w-full aspect-square max-h-48 mx-auto flex items-center justify-center">
                                @php
                                    $totalQ = $student->quality_chart_data['lancar'] + $student->quality_chart_data['perbaikan'];
                                    $lancarPct = $totalQ > 0 ? round(($student->quality_chart_data['lancar'] / $totalQ) * 100) : 0;
                                @endphp
                                @if($totalQ > 0)
                                    <canvas id="qualityChart{{ $student->id }}" class="quality-chart" data-lancar="{{ $student->quality_chart_data['lancar'] }}" data-perbaikan="{{ $student->quality_chart_data['perbaikan'] }}"></canvas>
                                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                                        <span class="text-2xl font-black text-gray-900 dark:text-white">{{ $lancarPct }}%</span>
                                        <span class="text-[10px] text-gray-500 font-bold uppercase">Lancar</span>
                                    </div>
                                @else
                                    <p class="text-xs text-gray-400 italic">Belum ada data bulan ini.</p>
                                @endif
                            </div>
                            <div class="flex justify-center gap-4 mt-4">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                                    <span class="text-[10px] text-gray-600 dark:text-gray-400 font-bold">Lancar ({{ $student->quality_chart_data['lancar'] }})</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                                    <span class="text-[10px] text-gray-600 dark:text-gray-400 font-bold">Perbaikan ({{ $student->quality_chart_data['perbaikan'] }})</span>
                                </div>
                            </div>
                        </x-tahfidz.card>
                    </div>
                </div>
            </div>
            <div class="border-t-2 border-dashed border-gray-100 dark:border-gray-800"></div>
        @empty
            <div class="p-12 text-center bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-800">
                <p class="text-gray-500">Anda tidak terhubung dengan data santri manapun. Silakan hubungi Admin.</p>
            </div>
        @endforelse
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.quality-chart').forEach(canvas => {
                const ctx = canvas.getContext('2d');
                const lancar = parseInt(canvas.dataset.lancar);
                const perbaikan = parseInt(canvas.dataset.perbaikan);

                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Lancar', 'Perlu Perbaikan'],
                        datasets: [{
                            data: [lancar, perbaikan],
                            backgroundColor: ['#10b981', '#fbbf24'],
                            borderWidth: 0,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '75%',
                        plugins: {
                            legend: { display: false },
                            tooltip: { enabled: true }
                        }
                    }
                });
            });
        });
    </script>
    @endpush
</x-tahfidz-layout>
