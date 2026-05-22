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

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <x-tahfidz.card title="Pencapaian">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl text-emerald-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18 18.247 18.477 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            </div>
                            <div>
                                <p class="text-xl font-black text-gray-900 dark:text-white">Juz {{ $student->current_juz }}</p>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Hafalan Terakhir</p>
                            </div>
                        </div>
                    </x-tahfidz.card>

                    <x-tahfidz.card title="Progres Target">
                        @php $progress = $student->target_progress; @endphp
                        <div class="flex flex-col gap-2">
                            <div class="flex justify-between items-end">
                                <span class="text-2xl font-black text-gray-900 dark:text-white">{{ $progress }}%</span>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">{{ count($student->completed_juz) }}/{{ $student->target_juz }} Juz</span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2">
                                <div class="h-2 rounded-full transition-all duration-1000 {{ $progress == 100 ? 'bg-emerald-500' : 'bg-blue-500' }}" style="width: {{ $progress }}%"></div>
                            </div>
                        </div>
                    </x-tahfidz.card>

                    <x-tahfidz.card title="Ustadz Pendamping">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-gray-100 dark:bg-gray-800 rounded-xl text-gray-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ $student->guru->name ?? '-' }}</p>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider italic">Pembimbing</p>
                            </div>
                        </div>
                    </x-tahfidz.card>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">
                    <div class="lg:col-span-2">
                        <x-tahfidz.card title="Riwayat Terakhir" class="h-full flex flex-col">
                            <div class="overflow-x-auto flex-1">
                                <table class="w-full text-left">
                                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                        @forelse($student->memorizations->take(10) as $m)
                                            <tr>
                                                <td class="py-4 text-[10px] text-gray-500 font-black bg-gray-50/30 dark:bg-gray-900/10 px-3 rounded-l-xl uppercase">
                                                    {{ $m->tanggal ? \Carbon\Carbon::parse($m->tanggal)->format('d M') : $m->created_at->format('d M') }}
                                                </td>
                                                <td class="py-4 px-4 font-bold text-gray-900 dark:text-white text-sm">
                                                    @if($m->is_present)
                                                        <div class="flex flex-col lg:flex-row lg:items-center gap-1">
                                                            <span class="text-emerald-700 dark:text-emerald-400">Jz.{{ $m->juz }} {{ $m->surah }}</span>
                                                            <span class="text-[10px] text-gray-400 font-medium">({{ $m->ayat }})</span>
                                                        </div>
                                                    @else
                                                        <span class="text-red-500 uppercase text-[10px] font-black italic">Izin / Sakit</span>
                                                    @endif
                                                </td>
                                                <td class="py-4 px-4">
                                                    @if($m->is_present)
                                                        <span class="px-2 py-0.5 {{ $m->status === 'Lancar' ? 'bg-emerald-100 text-emerald-700' : 'bg-orange-100 text-orange-700' }} text-[9px] font-black rounded-lg uppercase tracking-tighter">
                                                            {{ $m->status }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="py-4 text-right pr-3 rounded-r-xl">
                                                    @if($m->is_present)
                                                        <span class="text-[10px] text-gray-400 italic">Disimak: {{ $m->guru->name ?? 'Guru' }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="py-12 text-center text-gray-400 italic">Belum ada riwayat hafalan.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </x-tahfidz.card>
                    </div>

                    <div class="lg:col-span-1 flex flex-col gap-6">
                        @if($student->latest_notes)
                            <div class="p-4 bg-emerald-50 dark:bg-emerald-900/30 border-l-4 border-emerald-500 rounded-r-xl shadow-sm">
                                <h4 class="text-[10px] font-black text-emerald-800 dark:text-emerald-400 mb-1 flex items-center gap-2 uppercase tracking-widest">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                                    Feedback Guru
                                </h4>
                                <p class="text-sm text-emerald-700 dark:text-emerald-300 italic font-medium">"{{ $student->latest_notes }}"</p>
                            </div>
                        @endif

                        <x-tahfidz.card title="Kualitas Hafalan">
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

                        <x-tahfidz.card class="flex flex-col">
                            <x-slot name="title_slot">
                                <h5 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ruang Komunikasi</h5>
                                @if(($student->messages ?? collect())->count() > 0)
                                    <form action="{{ route('parent.messages.clear', $student) }}" method="POST" onsubmit="return confirm('Bersihkan seluruh riwayat chat? Tindakan ini tidak bisa dibatalkan.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="flex items-center gap-1.5 px-3 py-1.5 bg-red-50 hover:bg-red-500 text-red-400 hover:text-white border border-red-100 hover:border-red-500 rounded-xl transition-all duration-200 shadow-sm text-[10px] font-black uppercase tracking-tight">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            Bersihkan
                                        </button>
                                    </form>
                                @endif
                            </x-slot>
                            
                            <div class="h-52 overflow-y-auto mb-4 border border-gray-100 dark:border-gray-700 rounded-2xl p-4 bg-gray-50/50 dark:bg-gray-900/50 space-y-4 custom-scrollbar mt-2">
                                @forelse($student->messages ?? [] as $msg)
                                    <div class="flex {{ $msg->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }} items-start animate-fade-in">
                                        @if($msg->sender_id !== auth()->id())
                                            {{-- Guru's Message --}}
                                            <div class="flex flex-col items-start max-w-[85%] group">
                                                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white rounded-2xl rounded-tl-none px-4 py-3 text-sm shadow-sm relative leading-relaxed">
                                                    {{ $msg->message }}
                                                </div>
                                                <span class="text-[10px] text-gray-400 mt-1.5 ml-1 font-bold">{{ $msg->created_at->format('H:i') }}</span>
                                            </div>
                                        @else
                                            {{-- Parent's Message --}}
                                            <div class="flex flex-col items-end max-w-[85%] group">
                                                <div class="bg-emerald-600 text-white rounded-2xl rounded-tr-none px-4 py-3 text-sm shadow-lg shadow-emerald-500/20 font-medium leading-relaxed">
                                                    {{ $msg->message }}
                                                </div>
                                                <span class="text-[10px] text-gray-400 mt-1.5 mr-1 font-bold">{{ $msg->created_at->format('H:i') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <div class="h-full flex flex-col items-center justify-center py-20 opacity-40">
                                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                        </div>
                                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Belum ada percakapan</p>
                                    </div>
                                @endforelse
                            </div>
                            
                            <form action="{{ route('parent.messages.send', $student) }}" method="POST" class="flex gap-3 bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700 p-3 rounded-2xl shadow-inner-sm">
                                @csrf
                                <input type="text" name="message" required placeholder="Tulis pesan ke Ustadz..." class="flex-1 bg-transparent border-none focus:ring-0 text-sm dark:text-white placeholder:text-gray-300 font-medium">
                                <button type="submit" class="w-12 h-12 flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl transition-all shadow-lg shadow-emerald-500/20 active:scale-90 flex-shrink-0">
                                    <svg class="w-6 h-6 rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                </button>
                            </form>
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
