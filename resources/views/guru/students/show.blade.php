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

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 rounded-2xl border border-emerald-100 dark:border-emerald-800 font-bold flex items-center gap-3 animate-fadeIn">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            {{ session('success') }}
        </div>
    @endif

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
                    {{-- Overview 30 Juz (Grid 5x6 & Comprehensive Detail) --}}
                    @php
                        $completedList = $student->completed_juz ?? [];
                        $completedCount = count($completedList);
                        $inProgressCount = 0;
                        $notStartedCount = 0;
                        $juzStats = [];

                        for($j = 1; $j <= 30; $j++) {
                            $isCompleted = in_array($j, $completedList);
                            $prog = $isCompleted ? 100 : $student->getJuzProgress($j);
                            
                            if ($isCompleted) {
                                $statusText = "Juz $j: Selesai (Mumtaz - 100%)";
                            } elseif ($prog > 0) {
                                $inProgressCount++;
                                $statusText = "Juz $j: Dalam Progres ($prog%)";
                            } else {
                                $notStartedCount++;
                                $statusText = "Juz $j: Belum Dimulai";
                            }

                            $juzStats[$j] = [
                                'isCompleted' => $isCompleted,
                                'prog' => $prog,
                                'statusText' => $statusText,
                            ];
                        }

                        $overallPercent = round(($completedCount / 30) * 100);
                    @endphp

                    <div class="mb-6 p-4 rounded-2xl bg-gray-50/80 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-800 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-black text-gray-700 dark:text-gray-200 uppercase tracking-wider flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Monitoring 30 Juz
                            </span>
                            <span class="text-xs font-black text-emerald-600 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-900/40 px-2.5 py-1 rounded-full border border-emerald-200 dark:border-emerald-800">
                                {{ $completedCount }}/30 Juz ({{ $overallPercent }}%)
                            </span>
                        </div>

                        {{-- Total Progress Bar --}}
                        <div class="w-full bg-gray-200 dark:bg-gray-700/60 h-2 rounded-full overflow-hidden">
                            <div class="bg-emerald-500 h-2 rounded-full transition-all duration-700 shadow-sm" style="width: {{ $overallPercent }}%"></div>
                        </div>

                        {{-- 5x6 Grid Layout (5 Columns x 6 Rows = 30 Juz) --}}
                        <div class="grid grid-cols-5 gap-1.5 sm:gap-2" style="display: grid !important; grid-template-columns: repeat(5, minmax(0, 1fr)) !important; gap: 6px !important;">
                            @for($j = 1; $j <= 30; $j++)
                                @php
                                    $item = $juzStats[$j];
                                    $isCompleted = $item['isCompleted'];
                                    $prog = $item['prog'];
                                @endphp

                                <div title="{{ $item['statusText'] }}"
                                    style="min-height: 42px;"
                                    class="relative group rounded-xl flex flex-col items-center justify-center text-xs font-black transition-all duration-200 cursor-pointer border shadow-sm select-none p-1
                                        {{ $isCompleted 
                                            ? 'bg-emerald-500 text-white border-emerald-400/40 shadow-emerald-500/20 hover:scale-105 hover:bg-emerald-600' 
                                            : ($prog > 0 
                                                ? 'bg-amber-400 text-white border-amber-300/40 shadow-amber-400/20 hover:scale-105 hover:bg-amber-500' 
                                                : 'bg-white dark:bg-gray-800 text-gray-400 dark:text-gray-500 border-gray-200/80 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-600 dark:hover:text-gray-300') }}">
                                    
                                    <span>{{ $j }}</span>
                                    
                                    @if($isCompleted)
                                        <span class="text-[8px] leading-none font-bold opacity-95 flex items-center justify-center">
                                            ✓
                                        </span>
                                    @elseif($prog > 0)
                                        <span class="text-[7.5px] leading-none font-extrabold opacity-95">
                                            {{ $prog }}%
                                        </span>
                                    @endif
                                </div>
                            @endfor
                        </div>


                    </div>

                    <div class="mt-6">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Target Selesai</span>
                        <p class="text-sm font-bold mt-1 {{ $student->activeTarget() && $student->activeTarget()->target_date ? 'text-emerald-600' : 'text-gray-400 italic' }}">
                            {{ ($student->activeTarget() && $student->activeTarget()->target_date) ? \Carbon\Carbon::parse($student->activeTarget()->target_date)->translatedFormat('d M Y') : 'Belum ditentukan' }}
                        </p>
                    </div>
                </div>

                <div class="mt-8 pb-6 space-y-3">
                    <a href="{{ route('guru.hafalan.create', ['student_id' => $student->id]) }}" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-bold flex items-center justify-center gap-2 transition-all shadow-md shadow-emerald-200 dark:shadow-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Input Hafalan Santri Ini
                    </a>

                    <a href="{{ route('guru.students.export', $student) }}" class="w-full py-3 bg-emerald-700 text-white rounded-2xl font-bold flex items-center justify-center gap-2 hover:bg-emerald-800 transition-all shadow-lg shadow-emerald-200 dark:shadow-none">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                          <path stroke-linecap="round" stroke-linejoin="round" d="m9 13.5 3 3m0 0 3-3m-3 3v-6m1.06-4.19-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
                        </svg>
                        Download Rekap Hafalan
                    </a>

                    <button type="button" onclick="openTargetModal()" class="w-full py-3 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 rounded-2xl font-bold flex items-center justify-center gap-2 hover:bg-emerald-100 dark:hover:bg-emerald-900/50 transition-all shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                        </svg>
                        Edit Target Hafalan
                    </button>
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
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[9px] font-black bg-red-50 text-red-600 border border-red-100 uppercase tracking-tighter text-center">Absensi / Tanpa Setoran</span>
                                        @endif

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

    <!-- MODAL EDIT TARGET HAFALAN -->
    <div id="targetModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="relative w-full max-w-lg bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden animate-fadeIn">
            <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50">
                <div>
                    <h3 class="text-base font-extrabold text-gray-900 dark:text-white">Edit Target Hafalan</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Kelola target juz & tanggal untuk {{ $student->name }}</p>
                </div>
                <button type="button" onclick="closeTargetModal()" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form action="{{ route('guru.students.update-target', $student) }}" method="POST" class="p-6 space-y-5">
                @csrf
                @method('PATCH')

                <div id="modal-target-container" class="space-y-4 max-h-[60vh] overflow-y-auto pr-1">
                    @forelse($student->targets as $index => $target)
                        <div class="modal-target-row p-4 bg-gray-50 dark:bg-gray-900/40 rounded-2xl border border-gray-100 dark:border-gray-700 space-y-3 relative">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-widest">Target #{{ $index + 1 }}</span>
                                <button type="button" onclick="this.closest('.modal-target-row').remove(); renumberModalTargets();" class="p-1 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Hapus Target">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Target Juz (1-30)</label>
                                    <input type="number" name="target_juz[]" value="{{ $target->target_juz }}" min="1" max="30" placeholder="Juz (1-30)" required
                                        class="w-full px-3.5 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-sm font-bold text-gray-900 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Target Selesai</label>
                                    <input type="date" name="target_date[]" value="{{ $target->target_date }}"
                                        class="w-full px-3.5 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-sm font-medium text-gray-900 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="modal-target-row p-4 bg-gray-50 dark:bg-gray-900/40 rounded-2xl border border-gray-100 dark:border-gray-700 space-y-3 relative">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-widest">Target #1</span>
                                <button type="button" onclick="this.closest('.modal-target-row').remove(); renumberModalTargets();" class="p-1 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Hapus Target">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Target Juz (1-30)</label>
                                    <input type="number" name="target_juz[]" min="1" max="30" placeholder="Contoh: 30"
                                        class="w-full px-3.5 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-sm font-bold text-gray-900 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Target Selesai</label>
                                    <input type="date" name="target_date[]"
                                        class="w-full px-3.5 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-sm font-medium text-gray-900 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>

                <button type="button" onclick="addModalTargetRow()" class="w-full py-3 border-2 border-dashed border-emerald-300 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 font-bold rounded-2xl hover:bg-emerald-50 dark:hover:bg-emerald-900/20 text-xs flex items-center justify-center gap-2 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Target Juz Baru
                </button>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <button type="button" onclick="closeTargetModal()" class="px-5 py-2.5 text-xs font-bold text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl transition-all">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-emerald-700 text-white rounded-xl text-xs font-bold hover:bg-emerald-800 transition-all shadow-md shadow-emerald-200 dark:shadow-none">
                        Simpan Target
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function openTargetModal() {
            document.getElementById('targetModal').classList.remove('hidden');
        }

        function closeTargetModal() {
            document.getElementById('targetModal').classList.add('hidden');
        }

        function renumberModalTargets() {
            const rows = document.querySelectorAll('.modal-target-row');
            rows.forEach((row, index) => {
                const title = row.querySelector('span');
                if (title) title.textContent = `Target #${index + 1}`;
            });
        }

        function addModalTargetRow() {
            const container = document.getElementById('modal-target-container');
            const rowCount = document.querySelectorAll('.modal-target-row').length + 1;
            const div = document.createElement('div');
            div.className = 'modal-target-row p-4 bg-gray-50 dark:bg-gray-900/40 rounded-2xl border border-gray-100 dark:border-gray-700 space-y-3 relative animate-fadeIn';
            div.innerHTML = `
                <div class="flex items-center justify-between">
                    <span class="text-xs font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-widest">Target #${rowCount}</span>
                    <button type="button" onclick="this.closest('.modal-target-row').remove(); renumberModalTargets();" class="p-1 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Hapus Target">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Target Juz (1-30)</label>
                        <input type="number" name="target_juz[]" min="1" max="30" placeholder="Contoh: 30"
                            class="w-full px-3.5 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-sm font-bold text-gray-900 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Target Selesai</label>
                        <input type="date" name="target_date[]"
                            class="w-full px-3.5 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-sm font-medium text-gray-900 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                </div>
            `;
            container.appendChild(div);
        }

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

            const pageSize = 10;
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

            function getTotalPages() {
                return Math.ceil(filteredRows.length / pageSize) || 1;
            }

            function renderPagination() {
                const totalPages = getTotalPages();

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
                const totalPages = getTotalPages();
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
                const totalPages = getTotalPages();
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
</x-tahfidz-layout>
