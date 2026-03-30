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
                        Export PDF
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

                    <div class="lg:col-span-1">
                        <x-tahfidz.card title="Statistik Visual">
                            <!-- Placeholder Chart using inline styles for mini visual -->
                            <div class="h-48 flex items-end justify-between gap-1 px-2 pt-4">
                                @foreach($student->trend_data ?? [] as $data)
                                    <div class="flex-1 flex flex-col items-center gap-2 group">
                                        <div class="w-full bg-emerald-100 dark:bg-emerald-900/30 rounded-t-lg group-hover:bg-emerald-500 transition-all relative" style="height: {{ ($data['value'] / 7) * 100 }}%">
                                            <span class="absolute -top-6 left-1/2 -translate-x-1/2 text-[10px] font-bold opacity-0 group-hover:opacity-100 transition-opacity">{{ $data['value'] }}</span>
                                        </div>
                                        <span class="text-[8px] text-gray-400 font-bold truncate w-full text-center">{{ str_replace('Mgu ', '', $data['label']) }}</span>
                                    </div>
                                @endforeach
                            </div>
                            <p class="text-[10px] text-center text-gray-400 mt-4 font-bold uppercase tracking-widest">Aktivitas 8 Minggu Terakhir</p>
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
</x-tahfidz-layout>
