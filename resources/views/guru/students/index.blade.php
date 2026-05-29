<x-tahfidz-layout>
    <x-slot name="header">
        Daftar Santri
    </x-slot>
    <x-slot name="subtitle">
        Kelola data santri bimbingan Anda.
    </x-slot>

    <div class="mb-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white uppercase tracking-tight">Semua Santri</h2>
        <a href="{{ route('guru.students.create') }}"
            class="px-5 py-2.5 bg-emerald-700 text-white rounded-2xl font-bold hover:bg-emerald-800 transition-all shadow-lg shadow-emerald-200 dark:shadow-none flex items-center gap-2 text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Santri
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

    <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-3xl overflow-hidden shadow-sm">
        <!-- ADVANCED FILTER BAR -->
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50/30 dark:bg-gray-900/10">
            <form action="{{ route('guru.students.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4">
                <div class="flex-1">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 ml-1 tracking-widest">Cari Santri</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Nama atau NISN..." 
                               class="w-full bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-2xl text-sm focus:ring-emerald-500 focus:border-emerald-500 shadow-sm pl-11 font-medium"
                               oninput="debounceSubmit(this.form)">
                        <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>

                <div class="w-full lg:w-48">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 ml-1 tracking-widest">Jenis Kelamin</label>
                    <select name="gender" onchange="updateTable(this.form)" 
                            class="w-full bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-2xl text-sm focus:ring-emerald-500 focus:border-emerald-500 shadow-sm cursor-pointer font-bold">
                        <option value="">Semua Jenkel</option>
                        <option value="Laki-laki" {{ request('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ request('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div class="w-full lg:w-48">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 ml-1 tracking-widest">Urutkan</label>
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
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700">
                            <th class="px-6 py-5 text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest leading-none">Info Santri</th>
                            <th class="px-6 py-5 text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest leading-none">Target</th>
                            <th class="px-6 py-5 text-[10px] font-black text-emerald-600 uppercase tracking-widest leading-none">Terverifikasi</th>
                            <th class="px-6 py-5 text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest leading-none text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($students as $student)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900/30 transition-colors group">
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 flex items-center justify-center font-bold text-lg shadow-sm">
                                            {{ substr($student->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-900 dark:text-white leading-none mb-1">{{ $student->name }}</div>
                                            <div class="text-[10px] text-gray-500 font-bold italic tracking-tighter">NIS: {{ $student->nis }} • {{ $student->gender }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-wrap gap-1.5 max-w-[200px]">
                                        @php $completedJuz = $student->completed_juz; @endphp
                                        @forelse($student->targets as $t)
                                            @php $isAchieved = in_array($t->target_juz, $completedJuz); @endphp
                                            <span class="px-2 py-1 rounded-lg text-[10px] font-black uppercase tracking-tighter {{ $isAchieved ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-blue-50 text-blue-600 border border-blue-100' }}">
                                                JUZ {{ $t->target_juz }}
                                            </span>
                                        @empty
                                            <span class="text-[10px] text-gray-400 italic">Belum ada target</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse($student->completed_juz as $cj)
                                            <span class="px-2 py-0.5 bg-emerald-600 text-white text-[9px] font-black rounded uppercase">Juz {{ $cj }}</span>
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
                                        <a href="{{ route('guru.students.edit', $student) }}" class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-xl transition-all" title="Edit Profile">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center text-gray-400 italic font-medium">Tidak ada data santri bimbingan ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 bg-gray-50/50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700">
                {{ $students->appends(request()->input())->links('vendor.pagination.custom') }}
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

                // Update URL browser tanpa reload
                window.history.pushState({}, '', url);

                try {
                    const response = await fetch(url, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
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

            // Cegah submit form biasa agar tidak reload
            const filterForm = document.querySelector('form[action="{{ route('guru.students.index') }}"]');
            if (filterForm) {
                filterForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    updateTable(e.target);
                });
            }
        </script>
    @endpush
</x-tahfidz-layout>