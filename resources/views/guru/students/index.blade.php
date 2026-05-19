<x-tahfidz-layout>
    <x-slot name="header">
        Daftar Santri
    </x-slot>
    <x-slot name="subtitle">
        Kelola data santri bimbingan Anda.
    </x-slot>

    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Semua Santri</h2>
        <div class="flex flex-wrap items-center gap-3">
            <form action="{{ route('guru.students.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari santri..."
                        class="pl-10 pr-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm focus:ring-emerald-500 focus:border-emerald-500 w-64 shadow-sm transition-all"
                        oninput="filterTable(this.value)">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>

                <select name="gender" onchange="this.form.submit()"
                    class="py-2 pl-3 pr-8 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm focus:ring-emerald-500 focus:border-emerald-500 cursor-pointer shadow-sm">
                    <option value="">Semua Jenkel</option>
                    <option value="Laki-laki" {{ request('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="Perempuan" {{ request('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                </select>

                <select name="sort" onchange="this.form.submit()"
                    class="py-2 pl-3 pr-8 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm focus:ring-emerald-500 focus:border-emerald-500 cursor-pointer shadow-sm">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="abjad" {{ request('sort') == 'abjad' ? 'selected' : '' }}>Abjad (A-Z)</option>
                    <option value="nis" {{ request('sort') == 'nis' ? 'selected' : '' }}>NISN</option>
                </select>
            </form>

            <a href="{{ route('guru.students.create') }}"
                class="px-5 py-2.5 bg-emerald-700 text-white rounded-2xl font-bold hover:bg-emerald-800 transition-all shadow-lg shadow-emerald-200 dark:shadow-none flex items-center gap-2 text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Santri
            </a>
        </div>
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
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700">
                        <th class="px-6 py-4 text-xs font-black text-gray-500 uppercase tracking-wider">Info Santri</th>
                        <th class="px-6 py-4 text-xs font-black text-gray-500 uppercase tracking-wider">Target</th>
                        <th class="px-6 py-4 text-xs font-black text-gray-500 uppercase tracking-wider text-emerald-600">Terverifikasi</th>
                        <th class="px-6 py-4 text-xs font-black text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody id="studentTableBody" class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($students as $student)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900/30 transition-colors group">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center text-emerald-700 dark:text-emerald-400 font-bold text-lg shadow-sm">
                                        {{ substr($student->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $student->name }}</div>
                                        <div class="text-[11px] text-gray-500 font-medium">NIS: {{ $student->nis }} • {{ $student->gender }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex flex-wrap gap-1.5 max-w-[200px]">
                                    @php $completedJuz = $student->completed_juz; @endphp
                                    @forelse($student->targets as $t)
                                        @php $isAchieved = in_array($t->target_juz, $completedJuz); @endphp
                                        <span class="px-2 py-1 rounded-lg text-[10px] font-black {{ $isAchieved ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : 'bg-blue-50 text-blue-600 border border-blue-100' }}">
                                            JUZ {{ $t->target_juz }}
                                        </span>
                                    @empty
                                        <span class="text-xs text-gray-400 italic">Belum ada target</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex flex-wrap gap-1">
                                    @forelse($student->completed_juz as $cj)
                                        <span class="px-2 py-0.5 bg-emerald-600 text-white text-[10px] font-bold rounded shadow-sm">Juz {{ $cj }}</span>
                                    @empty
                                        <span class="text-[10px] text-gray-400 italic">Berproses...</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('guru.students.show', $student) }}" class="p-2 text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 rounded-xl transition-all" title="Detail">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('guru.students.edit', $student) }}" class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-xl transition-all" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-500 italic">Tidak ada data santri ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($students->hasPages())
            <div class="px-6 py-4 bg-gray-50/50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700">
                {{ $students->links() }}
            </div>
        @endif
    @push('scripts')
        <script>
            function filterTable(val) {
                const query = val.toLowerCase();
                const rows = document.querySelectorAll('#studentTableBody tr');
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(query) ? '' : 'none';
                });
            }
        </script>
    @endpush
</x-tahfidz-layout>