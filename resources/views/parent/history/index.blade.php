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
                            class="block w-full px-4 py-3 border border-gray-100 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm dark:text-white transition-all shadow-sm font-bold">
                        @foreach($students as $s)
                            <option value="{{ $s->id }}" {{ $student && $student->id == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        @else
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white uppercase tracking-tight">Riwayat: {{ $student->name }}</h2>
                <p class="text-xs text-gray-400 font-medium italic mt-1 font-bold">Menampilkan seluruh catatan setoran hafalan.</p>
            </div>
        @endif

        @if($student)
            <div class="flex flex-wrap gap-3 w-full lg:w-auto">
                <a href="{{ route('parent.history.export', $student) }}" 
                   class="flex-1 lg:flex-none px-6 py-3 bg-emerald-700 text-white rounded-2xl font-bold hover:bg-emerald-800 transition-all shadow-lg shadow-emerald-200 dark:shadow-none flex items-center justify-center gap-2 text-sm uppercase tracking-wider">
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
                <form action="{{ route('parent.history.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4 items-end">
                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                    <div class="flex-1 w-full">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1.5 ml-1 tracking-widest">Cari Hafalan</label>
                        <div class="relative">
                            <input type="text" name="search" id="parentSearch" value="{{ request('search') }}" 
                                   placeholder="Surah, Juz, atau Catatan..." 
                                   class="w-full bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-2xl text-sm focus:ring-emerald-500 focus:border-emerald-500 shadow-sm pl-11 font-medium">
                            <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>
                    <div class="w-full lg:w-40">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1.5 ml-1 tracking-widest">Status</label>
                        <select name="status" onchange="this.form.submit()" 
                                class="w-full bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-2xl text-sm focus:ring-emerald-500 focus:border-emerald-500 shadow-sm px-4 cursor-pointer font-bold">
                            <option value="">Semua Status</option>
                            <option value="Lancar" {{ request('status') == 'Lancar' ? 'selected' : '' }}>Lancar</option>
                            <option value="Perlu Perbaikan" {{ request('status') == 'Perlu Perbaikan' ? 'selected' : '' }}>Perlu Perbaikan</option>
                        </select>
                    </div>
                    <div class="w-full lg:w-40">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1.5 ml-1 tracking-widest">Kehadiran</label>
                        <select name="presence" onchange="this.form.submit()" 
                                class="w-full bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-2xl text-sm focus:ring-emerald-500 focus:border-emerald-500 shadow-sm px-4 cursor-pointer font-bold">
                            <option value="">Semua</option>
                            <option value="hadir" {{ request('presence') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                            <option value="absen" {{ request('presence') == 'absen' ? 'selected' : '' }}>Izin/Absen</option>
                        </select>
                    </div>
                    <div class="w-full lg:w-auto flex gap-2">
                        <button type="submit" class="flex-1 lg:flex-none px-6 py-2.5 bg-gray-900 dark:bg-gray-700 text-white rounded-2xl font-bold hover:bg-black transition-all shadow-sm">
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
                        <tr class="bg-gray-50/50 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700">
                            <th class="px-6 py-5 text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest leading-none whitespace-nowrap">Tanggal & Guru</th>
                            <th class="px-6 py-5 text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest leading-none">Hafalan</th>
                            <th class="px-6 py-5 text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest leading-none">Status</th>
                            <th class="px-6 py-5 text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest leading-none">Kehadiran</th>
                            <th class="px-6 py-5 text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest leading-none">Catatan Guru</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($hafalan as $m)
                            <tr class="hover:bg-gray-50/30 dark:hover:bg-gray-900/20 transition-all group">
                                <td class="px-6 py-5">
                                    <div class="text-sm font-black text-gray-900 dark:text-white leading-none mb-1">{{ $m->tanggal ? \Carbon\Carbon::parse($m->tanggal)->format('d F Y') : $m->created_at->format('d F Y') }}</div>
                                    <div class="text-[10px] text-gray-400 font-bold uppercase italic tracking-tighter">Oleh: {{ $m->guru->name ?? 'Sistem' }}</div>
                                </td>
                                <td class="px-6 py-5">
                                    @if($m->is_present)
                                        <div class="text-sm font-black text-emerald-700 dark:text-emerald-400 uppercase tracking-tighter leading-none mb-1">Juz {{ $m->juz }}: {{ $m->surah }}</div>
                                        <div class="text-[10px] text-gray-500 font-bold italic">Ayat: {{ $m->ayat }}</div>
                                    @else
                                        <span class="text-red-400 font-black text-[10px] italic uppercase">Tidak Ada Setoran</span>
                                    @endif
                                </td>
                                <td class="px-6 py-5">
                                    @if($m->is_present)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-tight {{ $m->status === 'Lancar' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-orange-50 text-orange-700 border border-orange-100' }}">
                                            {{ $m->status }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-5">
                                    @if($m->is_present)
                                        <div class="flex items-center gap-1.5 text-emerald-600">
                                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                                            <span class="text-[10px] font-black uppercase">Hadir</span>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-1.5 text-red-400 opacity-60">
                                            <div class="w-1.5 h-1.5 rounded-full bg-red-400"></div>
                                            <span class="text-[10px] font-black uppercase tracking-tighter">Absen/Izin</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-5">
                                    @if($m->notes)
                                        <div class="p-3 bg-gray-50/50 dark:bg-gray-900/40 rounded-xl border border-gray-100 dark:border-gray-800 group-hover:bg-white dark:group-hover:bg-gray-900 transition-colors max-w-xs">
                                            <p class="text-[11px] text-gray-600 dark:text-gray-400 leading-relaxed italic">"{{ $m->notes }}"</p>
                                        </div>
                                    @else
                                        <span class="text-[10px] text-gray-300 italic">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-16 text-center text-gray-400 italic font-medium">Belum ada catatan riwayat setoran hafalan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 bg-gray-50/30 dark:bg-gray-900/10 border-t border-gray-100 dark:border-gray-700">
                {{ $hafalan->appends(request()->input())->links('vendor.pagination.custom') }}
            </div>
        </div>
    @else
        <div class="p-20 bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 text-center shadow-lg">
            <div class="w-20 h-20 bg-emerald-50 dark:bg-emerald-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase">Pilih Data Ananda</h3>
            <p class="text-sm text-gray-500 mt-2 font-medium">Silakan pilih salah satu ananda Anda pada pilihan di atas <br> untuk melihat detail riwayat hafalan selengkapnya.</p>
        </div>
    @endif
</x-tahfidz-layout>
