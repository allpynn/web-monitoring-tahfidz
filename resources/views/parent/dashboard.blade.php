<x-tahfidz-layout>
    <x-slot name="header">
        Dashboard Orang Tua
    </x-slot>
    <x-slot name="subtitle">
        Pantau perkembangan hafalan buah hati Anda.
    </x-slot>

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

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <x-tahfidz.card title="Total Setoran" :value="$totalMemorizations . ' Kali'" icon='<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9 4.804A7.937 7.937 0 0112 4c1.232 0 2.403.28 3.448.784a1 1 0 01.552.894V17a1 1 0 01-1.342.948 6.037 6.037 0 00-4.32-.2L10 18v-3a1 1 0 011-1h1a1 1 0 100-2h-1V9a1 1 0 10-2 0v5H8a1 1 0 100 2h1a1 1 0 011 1v3l-.336-.112a6.037 6.037 0 00-4.32.2A1 1 0 013 17V5.698a1 1 0 01.552-.894A7.937 7.937 0 017 4c1.232 0 2.403.28 3.448.784z"></path></svg>' />
            <x-tahfidz.card title="Status Terakhir" :value="$latest && $latest->is_present ? $latest->status : ($latest ? 'Tidak Hadir' : '-')" icon='<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>' />
            <x-tahfidz.card title="Kehadiran" :value="$student->memorizations->count() > 0 ? round(($student->memorizations->where('is_present', true)->count() / $student->memorizations->count()) * 100) . '%' : '0%'" icon='<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path></svg>' />
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <x-tahfidz.card title="Pencapaian Juz">
                <div class="space-y-6">
                    @php
                        // Dummy logic for progress bars based on real counts
                        $progress = min(100, $totalMemorizations * 5);
                    @endphp
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-bold text-gray-700 dark:text-gray-300">Target Hafalan Saat Ini</span>
                            <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400">{{ $progress }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-3">
                            <div class="bg-emerald-500 h-3 rounded-full shadow-lg shadow-emerald-200 dark:shadow-none transition-all duration-1000" style="width: {{ $progress }}%"></div>
                        </div>
                    </div>
                </div>
            </x-tahfidz.card>

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
</x-tahfidz-layout>
