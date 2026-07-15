<x-tahfidz-layout>
    <x-slot name="header">
        Manajemen Santri
    </x-slot>
    <x-slot name="subtitle">
        Kelola data santri, hubungkan dengan orang tua dan guru pendamping.
    </x-slot>

    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Daftar Santri</h2>
        <a href="{{ route('admin.students.create') }}"
            class="px-5 py-2.5 bg-emerald-700 text-white rounded-2xl font-bold hover:bg-emerald-800 transition-all shadow-lg flex items-center gap-2 text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Santri
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 rounded-2xl border border-emerald-100 dark:border-emerald-800 font-bold flex items-center gap-3">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-3xl overflow-hidden shadow-sm">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50/30 dark:bg-gray-900/20">
            <form action="{{ route('admin.students.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4 items-end">
                <div class="flex-1 w-full">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 ml-1">Cari Nama / NISN</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari santri..."
                            class="w-full bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-2xl text-sm focus:ring-emerald-500 focus:border-emerald-500 shadow-sm pl-11 font-medium"
                            oninput="debounceSubmit(this.form)">
                        <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                <div class="w-full lg:w-48">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 ml-1">Tahun Ajaran</label>
                    <select name="academic_year" onchange="updateTable(this.form)"
                        class="w-full bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-2xl text-sm focus:ring-emerald-500 focus:border-emerald-500 shadow-sm cursor-pointer font-bold">
                        <option value="all" {{ $academicYear === 'all' ? 'selected' : '' }}>Semua</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year }}" {{ $academicYear === $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full lg:w-48">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 ml-1">Jenis Kelamin</label>
                    <select name="gender" onchange="updateTable(this.form)"
                        class="w-full bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-2xl text-sm focus:ring-emerald-500 focus:border-emerald-500 shadow-sm cursor-pointer font-bold">
                        <option value="">Semua</option>
                        <option value="Laki-laki" {{ request('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ request('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div class="w-full lg:w-40">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 ml-1">Urutan</label>
                    <select name="sort" onchange="updateTable(this.form)"
                        class="w-full bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-2xl text-sm focus:ring-emerald-500 focus:border-emerald-500 shadow-sm cursor-pointer font-bold">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="abjad" {{ request('sort') == 'abjad' ? 'selected' : '' }}>Abjad (A-Z)</option>
                        <option value="nis" {{ request('sort') == 'nis' ? 'selected' : '' }}>NISN</option>
                    </select>
                </div>
            </form>
        </div>

        <div id="table-container">
            <div class="overflow-x-auto">
                <table id="studentTable" class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700">
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">JK</th>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Orang Tua</th>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Guru Pendamping</th>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Target(JUZ)</th>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-emerald-600">Terverifikasi Selesai</th>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($students as $student)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900/30 transition-colors group">
                                <td class="px-6 py-2.5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center text-emerald-700 dark:text-emerald-400 font-bold">
                                            {{ substr($student->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $student->name }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">NIS: {{ $student->nis }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-2.5">
                                    @if($student->gender === 'Laki-laki')
                                        <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold rounded bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400" title="Laki-laki">L</span>
                                    @elseif($student->gender === 'Perempuan')
                                        <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold rounded bg-pink-100 text-pink-700 dark:bg-pink-900/40 dark:text-pink-400" title="Perempuan">P</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-2.5">
                                    @if($student->parents->count())
                                        @foreach($student->parents as $p)
                                            <div class="text-sm text-gray-900 dark:text-white font-medium">{{ $p->name }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $p->phone ?? '' }}</div>
                                        @endforeach
                                    @else
                                        <span class="text-xs text-gray-400 italic">Belum diassign</span>
                                    @endif
                                </td>
                                <td class="px-6 py-2.5">
                                    <span class="px-3 py-1 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-xs font-bold rounded-full">
                                        {{ $student->guru->name ?? 'Belum Ditentukan' }}
                                    </span>
                                </td>
                                <td class="px-6 py-2.5">
                                    <div class="flex flex-wrap gap-1.5 max-w-[150px]">
                                        @php $completedJuz = $student->completed_juz; @endphp
                                        @forelse($student->targets as $t)
                                            @php $isAchieved = in_array($t->target_juz, $completedJuz); @endphp
                                            <div class="flex flex-col items-center">
                                                <span class="px-2 py-0.5 rounded-lg text-[10px] font-black {{ $isAchieved ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : 'bg-blue-50 text-blue-600 border border-blue-100' }}">
                                                    JUZ {{ $t->target_juz }}
                                                    @if($isAchieved)
                                                        <svg class="w-2.5 h-2.5 inline ml-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"></path></svg>
                                                    @endif
                                                </span>
                                                <span class="text-[9px] text-gray-400">{{ $t->target_date ? \Carbon\Carbon::parse($t->target_date)->format('d/m/y') : '' }}</span>
                                            </div>
                                        @empty
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-6 py-2.5">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse($student->completed_juz as $cj)
                                            <span class="px-2 py-1 bg-emerald-600 text-white text-[10px] font-bold rounded-md shadow-sm">{{ $cj }}</span>
                                        @empty
                                            <span class="text-[10px] text-gray-400 italic">Berproses...</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-6 py-2.5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.students.edit', $student) }}" class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-xl transition-all" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        <a href="{{ route('admin.students.export', $student) }}" class="p-2 text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 rounded-xl transition-all" title="Download Report">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m9 13.5 3 3m0 0 3-3m-3 3v-6m1.06-4.19-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.students.destroy', $student) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus santri ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-xl transition-all" title="Hapus">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-6 py-10 text-center text-gray-500 italic">Belum ada data santri.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
                {{ $students->links('vendor.pagination.custom') }}
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let debounceTimer;
            function debounceSubmit(form) {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    updateTable(form);
                }, 300);
            }

            async function updateTable(form) {
                const formData = new FormData(form);
                const params = new URLSearchParams(formData);
                const url = `${form.action}?${params.toString()}`;

                window.history.pushState({}, '', url);

                try {
                    const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    const html = await response.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    const newTable = doc.getElementById('table-container');
                    if (newTable) {
                        document.getElementById('table-container').innerHTML = newTable.innerHTML;
                    }
                } catch (error) {
                    console.error('Gagal mengambil data:', error);
                    form.submit();
                }
            }

            const adminFilterForm = document.querySelector('form[action="{{ route('admin.students.index') }}"]');
            if (adminFilterForm) {
                adminFilterForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    updateTable(e.target);
                });
            }
        </script>
    @endpush
</x-tahfidz-layout>