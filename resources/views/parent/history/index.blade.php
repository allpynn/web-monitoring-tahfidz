<x-tahfidz-layout>
    <x-slot name="header">
        Rekap Setoran Ananda
    </x-slot>
    <x-slot name="subtitle">
        Pantau seluruh riwayat hafalan dan perkembangan ananda secara detail.
    </x-slot>

    <!-- HEADER FILTER & INFO -->
    <div class="mb-8 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
        @if($students->count() > 1)
            <div class="w-full lg:w-72">
                <form action="{{ route('parent.history.index') }}" method="GET" id="studentFilter">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Pilih Ananda</label>
                    <select name="student_id" onchange="this.form.submit()" 
                            class="block w-full px-4 py-3 border border-gray-100 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm dark:text-white transition-all shadow-sm">
                        @foreach($students as $s)
                            <option value="{{ $s->id }}" {{ $student && $student->id == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        @else
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white uppercase tracking-tight">Riwayat: {{ $student->name }}</h2>
                <p class="text-xs text-gray-400 font-medium italic mt-1">Menampilkan seluruh catatan setoran hafalan.</p>
            </div>
        @endif

        @if($student)
            <div class="flex flex-wrap gap-3 w-full lg:w-auto">
                <a href="{{ route('parent.history.export', $student) }}" 
                   class="flex-1 lg:flex-none px-6 py-3 bg-emerald-700 text-white rounded-2xl font-bold hover:bg-emerald-800 transition-all shadow-lg shadow-emerald-200 dark:shadow-none flex items-center justify-center gap-2 text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Download PDF
                </a>
            </div>
        @endif
    </div>

    @if($student)
        <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-3xl overflow-hidden shadow-sm">
            <!-- ADVANCED SEARCH & FILTER BAR -->
            <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50/30 dark:bg-gray-900/10">
                <form action="{{ route('parent.history.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                    <div class="flex-1 w-full">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1.5 ml-1">Cari Hafalan (Surah/Juz/Ayat)</label>
                        <div class="relative">
                            <input type="text" name="search" id="parentSearch" value="{{ request('search') }}" 
                                   placeholder="Contoh: Al-Baqarah, Juz 30..." 
                                   class="w-full bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-2xl text-sm focus:ring-emerald-500 focus:border-emerald-500 shadow-sm pl-11"
                                   oninput="filterInstantHistory(this.value)">
                            <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>
                    <div class="w-full md:w-36">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1.5 ml-1">Status</label>
                        <select name="status" onchange="this.form.submit()" 
                                class="w-full bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-2xl text-sm focus:ring-emerald-500 focus:border-emerald-500 shadow-sm px-4 cursor-pointer font-bold">
                            <option value="">Semua</option>
                            <option value="Lancar" {{ request('status') == 'Lancar' ? 'selected' : '' }}>Lancar</option>
                            <option value="Perlu Perbaikan" {{ request('status') == 'Perlu Perbaikan' ? 'selected' : '' }}>Perlu Perbaikan</option>
                        </select>
                    </div>
                    <div class="w-full md:w-36">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1.5 ml-1">Hadir</label>
                        <select name="presence" onchange="this.form.submit()" 
                                class="w-full bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-2xl text-sm focus:ring-emerald-500 focus:border-emerald-500 shadow-sm px-4 cursor-pointer font-bold">
                            <option value="">Semua</option>
                            <option value="hadir" {{ request('presence') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                            <option value="absen" {{ request('presence') == 'absen' ? 'selected' : '' }}>Absen</option>
                        </select>
                    </div>
                    <div class="w-full md:w-44">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1.5 ml-1">Tanggal</label>
                        <input type="date" name="date" value="{{ request('date') }}" onchange="this.form.submit()"
                               class="w-full bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-2xl text-sm focus:ring-emerald-500 focus:border-emerald-500 shadow-sm px-4">
                    </div>
                    <div class="flex gap-2 w-full md:w-auto">
                        <button type="submit" class="flex-1 md:flex-none px-6 py-2.5 bg-gray-900 dark:bg-gray-700 text-white rounded-2xl font-bold hover:bg-black transition-all shadow-sm">
                            Filter
                        </button>
                        @if(request()->anyFilled(['search', 'date', 'status', 'presence']))
                            <a href="{{ route('parent.history.index', ['student_id' => $student->id]) }}" 
                               class="px-4 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-500 rounded-2xl flex items-center justify-center hover:bg-gray-200 transition-all font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- TABLE -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700 text-[10px] font-black text-gray-500 uppercase tracking-widest leading-none">
                            <th class="px-6 py-5">Tanggal</th>
                            <th class="px-6 py-5">Setoran Hafalan</th>
                            <th class="px-6 py-5">Kualitas Bacaan</th>
                            <th class="px-6 py-5 text-center">Hadir</th>
                            <th class="px-6 py-5">Catatan & Feedback</th>
                        </tr>
                    </thead>
                    <tbody id="historyTableBody" class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($hafalan as $m)
                            <tr class="hover:bg-gray-50/30 dark:hover:bg-gray-900/20 transition-all group">
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $m->tanggal ? \Carbon\Carbon::parse($m->tanggal)->format('d M Y') : $m->created_at->format('d M Y') }}</div>
                                    <div class="text-[10px] text-gray-400 font-medium">Oleh: {{ $m->guru->name ?? 'Sistem' }}</div>
                                </td>
                                <td class="px-6 py-5">
                                    @if($m->is_present)
                                        <div class="text-sm font-black text-emerald-700 dark:text-emerald-400 uppercase tracking-tight">Juz {{ $m->juz }}: {{ $m->surah }}</div>
                                        <div class="text-xs text-gray-500 font-medium">Ayat: <span class="text-gray-900 dark:text-white">{{ $m->ayat }}</span></div>
                                    @else
                                        <div class="flex items-center gap-2 text-red-400 italic text-sm font-medium">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Tidak Ada Setoran
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-5">
                                    @if($m->is_present)
                                        <div class="flex flex-col gap-1">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wide {{ $m->status === 'Lancar' ? 'bg-emerald-100 text-emerald-700' : 'bg-orange-100 text-orange-700' }}">
                                                {{ $m->status }}
                                            </span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-5 text-center">
                                    @if($m->is_present)
                                        <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 mx-auto shadow-sm shadow-emerald-200"></div>
                                    @else
                                        <div class="w-2.5 h-2.5 rounded-full bg-red-400 mx-auto opacity-50"></div>
                                    @endif
                                </td>
                                <td class="px-6 py-5 max-w-sm">
                                    <div class="space-y-3">
                                        @if($m->notes)
                                            <div class="p-3 bg-gray-50 dark:bg-gray-900/40 rounded-2xl border border-gray-100 dark:border-gray-800 relative group-hover:bg-white dark:group-hover:bg-gray-900 transition-colors">
                                                <p class="text-[11px] text-gray-600 dark:text-gray-400 leading-relaxed italic">"{{ $m->notes }}"</p>
                                                <div class="absolute -left-1 top-4 w-0.5 h-4 bg-emerald-500 rounded-full"></div>
                                            </div>
                                        @endif
                                        @if($m->parent_comment)
                                            <div class="p-3 bg-blue-50/50 dark:bg-blue-900/10 rounded-2xl border border-blue-100/50 dark:border-blue-800/20 relative">
                                                <p class="text-[10px] font-black text-blue-700 dark:text-blue-400 uppercase tracking-tighter mb-0.5">Balasan Anda:</p>
                                                <p class="text-[11px] text-blue-600/80 dark:text-blue-400/80 leading-relaxed italic">"{{ $m->parent_comment }}"</p>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-16 text-center text-gray-400 italic">Belum ada catatan riwayat setoran.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($hafalan->hasPages())
                <div class="px-6 py-5 bg-gray-50/30 dark:bg-gray-900/20 border-t border-gray-100 dark:border-gray-700">
                    {{ $hafalan->links() }}
                </div>
            @endif
        </div>
    @else
        <div class="p-20 bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 text-center shadow-sm">
            <div class="w-20 h-20 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Pilih Data Ananda</h3>
            <p class="text-sm text-gray-500 mt-1">Silakan pilih ananda Anda untuk melihat riwayat lengkap setoran hafalan.</p>
        </div>
    @endif

    @push('scripts')
        <script>
            function filterInstantHistory(val) {
                const query = val.toLowerCase();
                const rows = document.querySelectorAll('#historyTableBody tr');
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(query) ? '' : 'none';
                });
            }
        </script>
    @endpush
</x-tahfidz-layout>
