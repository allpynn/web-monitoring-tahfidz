<x-tahfidz-layout>
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endpush
    <x-slot name="header">
        Dashboard Orang Tua
    </x-slot>
    <x-slot name="subtitle">
        Pantau perkembangan hafalan buah hati Anda.
    </x-slot>

    @if(session('success'))
    <div class="mb-6 p-4 text-sm text-emerald-800 dark:text-emerald-400 rounded-2xl bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-100 dark:border-emerald-800/50 font-bold flex items-center gap-3 animate-fade-in" role="alert">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
        {{ session('success') }}
    </div>
    @endif

    @forelse($students as $student)
    <div class="mb-12">
        <div class="flex items-center mb-6">
            <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-2xl flex items-center justify-center mr-4 text-emerald-700 dark:text-emerald-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $student->name }}</h3>
                <p class="text-sm text-gray-500">NIS: {{ $student->nis }}</p>
            </div>
        </div>

        @php
            $latest = $student->memorizations->first();
            $totalMemorizations = $student->memorizations->where('is_present', true)->count();
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <x-tahfidz.card title="Total Setoran" :value="$totalMemorizations . ' Kali'" icon='<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9 4.804A7.937 7.937 0 0112 4c1.232 0 2.403.28 3.448.784a1 1 0 01.552.894V17a1 1 0 01-1.342.948 6.037 6.037 0 00-4.32-.2L10 18v-3a1 1 0 011-1h1a1 1 0 100-2h-1V9a1 1 0 10-2 0v5H8a1 1 0 100 2h1a1 1 0 011 1v3l-.336-.112a6.037 6.037 0 00-4.32.2A1 1 0 013 17V5.698a1 1 0 01.552-.894A7.937 7.937 0 017 4c1.232 0 2.403.28 3.448.784z"></path></svg>' />
            <x-tahfidz.card title="Pencapaian" :value="$student->current_juz . ' / ' . $student->target_juz . ' Juz'" icon='<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>' />
            <x-tahfidz.card title="Kehadiran" :value="$student->memorizations->count() > 0 ? round(($student->memorizations->where('is_present', true)->count() / $student->memorizations->count()) * 100) . '%' : '0%'" icon='<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path></svg>' />
            <x-tahfidz.card title="Prediksi Khatam" :value="$student->prediction" icon='<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>' />
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <x-tahfidz.card title="Progres Visual">
                <div class="space-y-6">
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-bold text-gray-700 dark:text-gray-300">Menuju Target {{ $student->target_juz }} Juz</span>
                            <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400">{{ $student->target_progress }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-4">
                            <div class="bg-gradient-to-r from-emerald-600 to-emerald-400 h-4 rounded-full shadow-lg shadow-emerald-200 dark:shadow-none transition-all duration-1000" style="width: {{ $student->target_progress }}%"></div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100 dark:border-gray-700/50">
                        <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-4">Grafik Tren (8 Minggu)</h4>
                        <div class="h-48 w-full">
                            <canvas id="trendChart_{{ $student->id }}"></canvas>
                        </div>
                    </div>
                </div>
            </x-tahfidz.card>

            <x-tahfidz.card title="Heatmap Kehadiran (90 Hari)">
                <div class="space-y-4">
                    <div class="flex flex-wrap gap-1" id="heatmap_{{ $student->id }}">
                        @foreach($student->attendance_heatmap as $date => $status)
                            <div 
                                class="w-3 h-3 rounded-sm transition-all hover:scale-150 cursor-help
                                {{ $status === 'present' ? 'bg-emerald-500 shadow-sm shadow-emerald-200' : ($status === 'absent' ? 'bg-red-400' : 'bg-gray-100 dark:bg-gray-700') }}"
                                title="{{ \Carbon\Carbon::parse($date)->format('d M Y') }}: {{ $status === 'present' ? 'Hadir' : ($status === 'absent' ? 'Alpa/Izin' : 'Tidak ada jadwal') }}"
                            ></div>
                        @endforeach
                    </div>
                    <div class="flex items-center gap-4 text-[10px] font-bold text-gray-400 uppercase pt-2">
                        <div class="flex items-center gap-1"><div class="w-2 h-2 bg-emerald-500 rounded-sm"></div> Hadir</div>
                        <div class="flex items-center gap-1"><div class="w-2 h-2 bg-red-400 rounded-sm"></div> Alpa/Izin</div>
                        <div class="flex items-center gap-1"><div class="w-2 h-2 bg-gray-200 dark:bg-gray-700 rounded-sm"></div> Tidak Ada Data</div>
                    </div>
                </div>
            </x-tahfidz.card>
        </div>

        <div class="grid grid-cols-1 gap-6 mb-8">
            <x-tahfidz.card title="Laporan Terbaru">
                @if($latest)
                    <div class="p-6 {{ $latest->is_present ? 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-100 dark:border-emerald-800/50' : 'bg-red-50 dark:bg-red-900/20 border-red-100 dark:border-red-800/50' }} rounded-3xl border">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                @if($latest->is_present)
                                    <h4 class="text-lg font-bold text-gray-900 dark:text-white">Surah {{ $latest->surah }}</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Ayat {{ $latest->ayat }}</p>
                                @else
                                    <h4 class="text-lg font-bold text-red-700 dark:text-red-400">Tidak Hadir</h4>
                                    <p class="text-sm text-gray-500">Izin / Alpa</p>
                                @endif
                            </div>
                            @if($latest->is_present)
                                <span class="px-3 py-1 bg-emerald-600 text-white text-xs font-bold rounded-lg uppercase tracking-wider">{{ $latest->status }}</span>
                            @else
                                <span class="px-3 py-1 bg-red-600 text-white text-xs font-bold rounded-lg uppercase tracking-wider">Absen</span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-300 italic">"{{ $latest->notes ?? 'Tidak ada catatan.' }}"</p>
                        
                        <div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-700/50">
                            <form action="{{ route('parent.hafalan.comment', $latest) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <label for="parent_comment_{{ $latest->id }}" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">Tanggapan Orang Tua</label>
                                <textarea 
                                    name="parent_comment" 
                                    id="parent_comment_{{ $latest->id }}" 
                                    rows="2" 
                                    class="block w-full px-4 py-3 border border-gray-100 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm dark:text-white transition-all shadow-sm mb-3"
                                    placeholder="Tulis tanggapan atau pesan untuk ustadz/ustadzah..."
                                >{{ $latest->parent_comment }}</textarea>
                                <div class="flex justify-end">
                                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-xs font-bold hover:bg-emerald-700 transition-all shadow-md">
                                        Kirim Feedback
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700/50 flex justify-between items-center">
                            <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Input oleh: {{ $latest->guru->name }}</span>
                            <span class="text-xs text-gray-400">{{ $latest->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                @else
                    <div class="p-12 text-center text-gray-400 italic">Belum ada laporan setoran.</div>
                @endif
            </x-tahfidz.card>
        </div>
    </div>
    @if(!$loop->last) <hr class="border-gray-100 dark:border-gray-700 my-12"> @endif
    @empty
        <div class="p-20 text-center bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm">
            <p class="text-gray-500 dark:text-gray-400 text-lg">Data santri belum dikaitkan dengan akun Anda.</p>
            <p class="text-sm text-gray-400 mt-2">Silakan hubungi Admin untuk menautkan data anak Anda.</p>
        </div>
    @endforelse

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @foreach($students as $student)
            const ctx_{{ $student->id }} = document.getElementById('trendChart_{{ $student->id }}').getContext('2d');
            new Chart(ctx_{{ $student->id }}, {
                type: 'line',
                data: {
                    labels: {!! json_encode(collect($student->trend_data)->pluck('label')) !!},
                    datasets: [{
                        label: 'Setoran Lancar',
                        data: {!! json_encode(collect($student->trend_data)->pluck('value')) !!},
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#10b981'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, color: '#9ca3af' },
                            grid: { color: 'rgba(156, 163, 175, 0.1)' }
                        },
                        x: {
                            ticks: { color: '#9ca3af', font: { size: 10 } },
                            grid: { display: false }
                        }
                    }
                }
            });
            @endforeach
        });
    </script>
    @endpush
</x-tahfidz-layout>
