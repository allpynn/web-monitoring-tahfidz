<x-tahfidz-layout>
    <x-slot name="header">
        Detail Santri
    </x-slot>
    <x-slot name="subtitle">
        Informasi lengkap dan riwayat hafalan {{ $student->name }}.
    </x-slot>

    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('guru.students.index', ['academic_year' => $academicYear]) }}" class="text-sm font-bold text-emerald-700 dark:text-emerald-500 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar Santri
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar Detail -->
        <div class="lg:col-span-1 space-y-6">
            <x-tahfidz.card title="Profil Santri">
                <div class="flex flex-col items-center text-center pb-6 border-b border-gray-100 dark:border-gray-700 mb-6">
                    <div class="w-24 h-24 rounded-3xl bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400 flex items-center justify-center text-4xl font-extrabold mb-4">
                        {{ substr($student->name, 0, 1) }}
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $student->name }}</h3>
                    <p class="text-sm text-gray-500">NIS: {{ $student->nis }}</p>
                </div>

                <div class="space-y-4">
                    <div>
                        <span class="text-xs font-bold text-gray-400 uppercase">Orang Tua</span>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $student->parents->pluck('name')->join(', ') ?: '-' }}</p>
                    </div>
                    {{-- Overview 30 Juz --}}
                    <div class="mb-5">
                        <span class="text-[9px] font-extrabold text-gray-400 uppercase tracking-widest mb-3 block">Monitoring 30 Juz</span>
                        <div class="flex flex-wrap gap-1.5">
                            @php
                                $completedList = $student->completed_juz;
                            @endphp
                            @for($j = 1; $j <= 30; $j++)
                                @php
                                    $isCompleted = in_array($j, $completedList);
                                    $prog = $isCompleted ? 100 : $student->getJuzProgress($j);
                                    
                                    $bgClass = $isCompleted 
                                        ? 'bg-emerald-500 text-white' 
                                        : ($prog > 0 ? 'bg-amber-400 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-400/60');
                                    
                                    $statusLabel = $isCompleted ? 'Mumtaz' : ($prog > 0 ? $prog . '%' : 'Belum');
                                @endphp
                                <div class="w-7 h-7 flex items-center justify-center rounded-lg text-[10px] font-black {{ $bgClass }} transition-all duration-200 cursor-default shadow-sm border border-transparent {{ $isCompleted ? 'shadow-emerald-100/50' : '' }}">
                                    {{ $j }}
                                </div>
                            @endfor
                        </div>

                        <div class="flex items-center justify-between mt-4 px-3 py-2 bg-gray-50 dark:bg-gray-900/40 rounded-xl border border-gray-100 dark:border-gray-800">
                            <span class="text-[9px] font-bold text-gray-500 uppercase tracking-tight">Capaian: <span class="text-emerald-600 font-extrabold">{{ count($completedList) }}/30 Juz</span></span>
                            <div class="flex gap-2.5">
                                <div class="flex items-center gap-1 text-[8px] font-bold text-gray-400 uppercase">
                                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div> Selesai
                                </div>
                                <div class="flex items-center gap-1 text-[8px] font-bold text-gray-400 uppercase">
                                    <div class="w-1.5 h-1.5 rounded-full bg-amber-400"></div> Progres
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Detail Progres Aktif --}}
                    @php
                        $currentJuz = $student->current_juz ?: 30;
                        $juzProgress = $student->getJuzProgress($currentJuz);
                    @endphp
                    <div class="p-3 bg-emerald-50/30 dark:bg-emerald-900/10 rounded-xl border border-emerald-100/50 dark:border-emerald-900/20">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-[9px] font-black text-emerald-700 dark:text-emerald-400 uppercase">Fokus: Juz {{ $currentJuz }}</span>
                            <span class="text-[9px] font-black {{ $juzProgress == 100 ? 'text-emerald-600' : 'text-amber-600' }}">
                                {{ $juzProgress == 100 ? 'Mumtaz' : $juzProgress . '%' }}
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                            <div class="h-1.5 rounded-full transition-all duration-1000 {{ $juzProgress == 100 ? 'bg-emerald-500' : 'bg-amber-400' }}" 
                                 style="width: {{ $juzProgress }}%"></div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Target Selesai</span>
                        <p class="text-sm font-bold mt-1 {{ $student->activeTarget() && $student->activeTarget()->target_date ? 'text-emerald-600' : 'text-gray-400 italic' }}">
                            {{ ($student->activeTarget() && $student->activeTarget()->target_date) ? \Carbon\Carbon::parse($student->activeTarget()->target_date)->translatedFormat('d M Y') : 'Belum ditentukan' }}
                        </p>
                    </div>
                </div>

                <div class="mt-8 space-y-3 pb-12">
                    <a href="{{ route('guru.students.export', $student) }}" class="w-full py-3 bg-emerald-700 text-white rounded-2xl font-bold flex items-center justify-center gap-2 hover:bg-emerald-800 transition-all shadow-lg shadow-emerald-200 dark:shadow-none">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                          <path stroke-linecap="round" stroke-linejoin="round" d="m9 13.5 3 3m0 0 3-3m-3 3v-6m1.06-4.19-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
                        </svg>
                        Download Raport (PDF)
                    </a>
                    {{-- Rekap Semester Dropdown --}}
                    <div class="relative w-full" x-data="{ open: false }">
                        <button @click="open = !open" type="button" class="w-full py-3 bg-amber-600 text-white rounded-2xl font-bold flex items-center justify-center gap-2 hover:bg-amber-700 transition-all shadow-lg shadow-amber-200 dark:shadow-none">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                              <path stroke-linecap="round" stroke-linejoin="round" d="m9 13.5 3 3m0 0 3-3m-3 3v-6m1.06-4.19-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
                            </svg>
                            Rekap Semester (PDF)
                            <svg class="ml-1 h-4 w-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="open" 
                             x-collapse
                             @click.away="open = false" 
                             class="mt-2 w-full rounded-2xl bg-gray-50/50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700/50 overflow-hidden" 
                             style="display: none;">
                            <div class="py-2 px-1">
                                <div class="px-3 py-2 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-50 dark:border-gray-700/50 mb-1 leading-none">Pilih Periode</div>
                                
                                @php
                                    $currentYearForDropdown = date('Y');
                                    $semesterAcademicYears = [
                                        ($currentYearForDropdown - 1) . '/' . $currentYearForDropdown,
                                        $currentYearForDropdown . '/' . ($currentYearForDropdown + 1),
                                    ];
                                @endphp
                                
                                @foreach($semesterAcademicYears as $ay)
                                    <div class="px-3 py-2 text-[9px] font-bold text-gray-500/60 dark:text-gray-400/60 uppercase tracking-tighter bg-gray-50 dark:bg-gray-900/40 mb-1 rounded-lg">TA {{ $ay }}</div>
                                    <a href="{{ route('guru.students.export_semester', [$student, 'semester' => 'ganjil', 'year' => $ay]) }}" class="group flex items-center px-4 py-2 text-sm font-bold text-gray-700 dark:text-gray-300 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 hover:text-emerald-700 dark:hover:text-emerald-400 rounded-xl transition-all mb-1">
                                        Semester Ganjil
                                    </a>
                                    <a href="{{ route('guru.students.export_semester', [$student, 'semester' => 'genap', 'year' => $ay]) }}" class="group flex items-center px-4 py-2 text-sm font-bold text-gray-700 dark:text-gray-300 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 hover:text-emerald-700 dark:hover:text-emerald-400 rounded-xl transition-all mb-2">
                                        Semester Genap
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </x-tahfidz.card>
        </div>

        <!-- Main Content: History -->
        <div class="lg:col-span-2 space-y-6">
            <x-tahfidz.card title="Rekaman Hafalan & Monitoring">
                <!-- Record Count -->
                <div class="mb-4">
                    <span class="text-xs text-gray-400">Total: <span class="font-bold text-emerald-600">{{ $memorizations->count() }}</span> rekaman hafalan</span>
                </div>
                <!-- Filters & Search -->
                <div class="mb-6 bg-gray-50 dark:bg-gray-900/40 p-5 rounded-3xl border border-gray-100 dark:border-gray-700 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="relative">
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Cari Catatan</label>
                            <input type="text" id="logSearch" class="block w-full px-4 py-2 text-sm border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 focus:ring-emerald-500 focus:border-emerald-500 transition-all font-medium" placeholder="Surah/catatan...">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Filter Status</label>
                            <select id="statusFilter" class="block w-full px-4 py-2 text-sm border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 focus:ring-emerald-500 focus:border-emerald-500 transition-all font-bold">
                                <option value="all">Semua Status</option>
                                <option value="Lancar">Lancar</option>
                                <option value="Perlu Perbaikan">Perlu Perbaikan</option>
                                <option value="Absen">Absen</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Filter Tanggal</label>
                            <input type="date" id="dateFilter" class="block w-full px-4 py-2 text-sm border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 focus:ring-emerald-500 focus:border-emerald-500 transition-all font-medium">
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-2xl bg-white dark:bg-transparent">
                    <table class="w-full min-w-[650px] text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                                <th class="px-4 py-3 text-[10px] font-black text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-4 py-3 text-[10px] font-black text-gray-500 uppercase tracking-wider">Materi Hafalan</th>
                                <th class="px-4 py-3 text-[10px] font-black text-gray-500 uppercase tracking-wider text-center">Status</th>
                                <th class="px-4 py-3 text-[10px] font-black text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700" id="logTableBody">
                            @forelse($memorizations as $m)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-all log-row" 
                                    data-status="{{ $m->is_present ? $m->status : 'Absen' }}" 
                                    data-date="{{ $m->created_at->format('Y-m-d') }}">
                                    <td class="px-4 py-2.5 text-xs font-bold text-gray-500">
                                        {{ $m->created_at->format('d/m/y') }}
                                    </td>
                                    <td class="px-4 py-2.5">
                                        @if($m->is_present)
                                            <div class="text-sm font-bold text-gray-800 dark:text-white leading-tight flex items-center gap-1.5">
                                                <span class="text-emerald-600 dark:text-emerald-400">Juz {{ $m->juz }}</span>
                                                <span class="text-gray-300 dark:text-gray-600">•</span>
                                                <span class="text-gray-600 dark:text-gray-300">{{ $m->surah }}</span>
                                                <span class="text-gray-400 text-[10px] font-medium">({{ $m->ayat }})</span>
                                            </div>
                                            
                                            @if($m->notes)
                                                <div class="mt-1.5 text-[10px] text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-800/50 p-2 rounded-xl border border-gray-100 dark:border-gray-700/50 font-medium italic">
                                                    "{{ $m->notes }}"
                                                </div>
                                            @endif
                                            @if($m->parent_comment)
                                                <div class="mt-1.5 text-[10px] text-blue-500 font-bold bg-blue-50 dark:bg-blue-900/20 p-2 rounded-xl border border-blue-100 dark:border-blue-800/50 line-clamp-1">
                                                    <span class="uppercase text-[8px] opacity-70">💬 Wali:</span> {{ $m->parent_comment }}
                                                </div>
                                            @endif
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[9px] font-black bg-red-50 text-red-600 border border-red-100 uppercase tracking-tighter text-center">Absensi / Tanpa Setoran</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2.5 text-center">
                                        @if($m->is_present)
                                            @php
                                                $statusClasses = [
                                                    'Lancar' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800',
                                                    'Perlu Perbaikan' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400 border-orange-200 dark:border-orange-800',
                                                    'Sedang' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 border-blue-200 dark:border-blue-800'
                                                ];
                                                $currentClass = $statusClasses[$m->status] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                                            @endphp
                                            <span class="inline-block px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tight border {{ $currentClass }}">
                                                {{ $m->status }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2.5 text-right">
                                        <div class="flex justify-end items-center gap-3">
                                            @can('update', $m)
                                                <a href="{{ route('guru.hafalan.edit', $m) }}" class="flex items-center justify-center w-9 h-9 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-xl hover:bg-blue-600 hover:text-white transition-all shadow-sm active:scale-90" title="Ubah">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </a>
                                            @endcan

                                            @can('delete', $m)
                                                <form action="{{ route('guru.hafalan.destroy', $m) }}" method="POST" class="inline" onsubmit="return confirm('Hapus rekaman ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="flex items-center justify-center w-9 h-9 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm active:scale-90" title="Hapus">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-14 text-center">
                                        <div class="flex flex-col items-center gap-2">
                                            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            <p class="text-gray-400 font-bold italic text-sm">Belum ada riwayat setoran hafalan santri ini.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            <tr id="emptyFilterRow" style="display: none;">
                                <td colspan="4" class="py-14 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <p class="text-gray-400 font-bold italic text-sm">Tidak ada riwayat setoran yang cocok dengan pencarian / filter Anda.</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Stats & Controls -->
                <div class="mt-4 px-4 py-3 bg-gray-50 dark:bg-gray-800/50 rounded-2xl flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest text-center sm:text-left">
                        Menampilkan <span id="visibleCount" class="text-emerald-600">0</span> dari <span class="text-emerald-600 font-black">{{ $memorizations->count() }}</span> data
                    </p>
                    
                    <div class="flex justify-center">
                        <nav id="paginationNav" class="inline-flex rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 overflow-hidden shadow-sm">
                            <button id="prevPageBtn" type="button" class="px-4 py-2 text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-white dark:disabled:hover:bg-gray-900 transition-colors flex items-center justify-center font-bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                            </button>
                            <div id="pageNumbersContainer" class="flex border-x border-gray-200 dark:border-gray-700">
                                <!-- Page numbers created dynamically -->
                            </div>
                            <button id="nextPageBtn" type="button" class="px-4 py-2 text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-white dark:disabled:hover:bg-gray-900 transition-colors flex items-center justify-center font-bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </button>
                        </nav>
                    </div>
                </div>
            </x-tahfidz.card>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('logSearch');
            const statusFilter = document.getElementById('statusFilter');
            const dateFilter = document.getElementById('dateFilter');
            const rows = document.querySelectorAll('.log-row');
            const visibleCountSpan = document.getElementById('visibleCount');
            const prevPageBtn = document.getElementById('prevPageBtn');
            const nextPageBtn = document.getElementById('nextPageBtn');
            const pageNumbersContainer = document.getElementById('pageNumbersContainer');
            const emptyFilterRow = document.getElementById('emptyFilterRow');

            const pageSize = 20;
            let currentPage = 1;
            let filteredRows = [];

            function filterLogs() {
                const query = searchInput.value.toLowerCase();
                const status = statusFilter.value;
                const date = dateFilter.value;

                filteredRows = [];

                rows.forEach(row => {
                    const rowText = row.innerText.toLowerCase();
                    const rowStatus = row.getAttribute('data-status');
                    const rowDate = row.getAttribute('data-date');

                    let matchesFilter = true;

                    if (query && !rowText.includes(query)) matchesFilter = false;
                    if (status !== 'all' && rowStatus !== status) matchesFilter = false;
                    if (date && rowDate !== date) matchesFilter = false;

                    if (matchesFilter) {
                        filteredRows.push(row);
                    } else {
                        row.style.display = 'none';
                    }
                });

                if (filteredRows.length === 0 && rows.length > 0) {
                    if (emptyFilterRow) emptyFilterRow.style.display = '';
                } else {
                    if (emptyFilterRow) emptyFilterRow.style.display = 'none';
                }

                currentPage = 1;
                renderPagination();
                showPage(currentPage);
            }

            function renderPagination() {
                const totalRows = filteredRows.length;
                const totalPages = Math.ceil(totalRows / pageSize) || 1;

                pageNumbersContainer.innerHTML = '';

                for (let i = 1; i <= totalPages; i++) {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = `px-5 py-2 font-bold text-sm transition-all flex items-center justify-center border-r last:border-r-0 border-gray-200 dark:border-gray-700 ${
                        i === currentPage 
                            ? 'bg-emerald-50/70 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400' 
                            : 'bg-white dark:bg-gray-900 text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-800'
                    }`;
                    btn.textContent = i;
                    btn.addEventListener('click', () => {
                        currentPage = i;
                        updateActivePageDecorations();
                        showPage(currentPage);
                    });
                    pageNumbersContainer.appendChild(btn);
                }

                prevPageBtn.disabled = currentPage === 1;
                nextPageBtn.disabled = currentPage === totalPages;
            }

            function updateActivePageDecorations() {
                const totalPages = Math.ceil(filteredRows.length / pageSize) || 1;
                prevPageBtn.disabled = currentPage === 1;
                nextPageBtn.disabled = currentPage === totalPages;

                const buttons = pageNumbersContainer.querySelectorAll('button');
                buttons.forEach((btn, idx) => {
                    const pageNum = idx + 1;
                    if (pageNum === currentPage) {
                        btn.className = 'px-5 py-2 font-bold text-sm bg-emerald-50/70 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 transition-all flex items-center justify-center border-r last:border-r-0 border-gray-200 dark:border-gray-700';
                    } else {
                        btn.className = 'px-5 py-2 font-bold text-sm bg-white dark:bg-gray-900 text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-800 transition-all flex items-center justify-center border-r last:border-r-0 border-gray-200 dark:border-gray-700';
                    }
                });
            }

            function showPage(page) {
                const startIdx = (page - 1) * pageSize;
                const endIdx = startIdx + pageSize;

                // Hide all rows first, then show only the ones on the current page
                rows.forEach(r => r.style.display = 'none');

                const pageRows = filteredRows.slice(startIdx, endIdx);
                pageRows.forEach(row => {
                    row.style.display = '';
                });

                visibleCountSpan.innerText = pageRows.length;
            }

            prevPageBtn.addEventListener('click', () => {
                if (currentPage > 1) {
                    currentPage--;
                    updateActivePageDecorations();
                    showPage(currentPage);
                }
            });

            nextPageBtn.addEventListener('click', () => {
                const totalPages = Math.ceil(filteredRows.length / pageSize) || 1;
                if (currentPage < totalPages) {
                    currentPage++;
                    updateActivePageDecorations();
                    showPage(currentPage);
                }
            });

            searchInput.addEventListener('input', filterLogs);
            statusFilter.addEventListener('change', filterLogs);
            dateFilter.addEventListener('change', filterLogs);

            // Initial Filter
            filterLogs();
        });
    </script>
    @endpush
    </div>
</x-tahfidz-layout>
