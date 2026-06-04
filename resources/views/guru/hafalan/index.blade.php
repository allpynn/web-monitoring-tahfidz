<x-tahfidz-layout>
    <x-slot name="header">
        Riwayat Hafalan
    </x-slot>
    <x-slot name="subtitle">
        Pantau riwayat setoran santri bimbingan Anda.
    </x-slot>

    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Daftar Riwayat</h2>
        <a href="{{ route('guru.hafalan.create') }}"
            class="px-5 py-2.5 bg-emerald-700 text-white rounded-2xl font-bold hover:bg-emerald-800 transition-all shadow-lg flex items-center gap-2 text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Riwayat
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 rounded-2xl border border-emerald-100 dark:border-emerald-800 font-bold flex items-center gap-3">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-3xl overflow-hidden shadow-sm mb-8">
        <!-- ADVANCED FILTER BAR -->
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50/30 dark:bg-gray-900/20">
            <form action="{{ route('guru.hafalan.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4 items-end">
                <div class="flex-1 w-full">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 ml-1">Cari Nama / NIS</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Masukkan kata kunci..."
                               class="w-full bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-2xl text-sm focus:ring-emerald-500 focus:border-emerald-500 shadow-sm pl-11 font-medium"
                               oninput="debounceSubmit(this.form)">
                        <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>
                <div class="w-full lg:w-48">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 ml-1">Filter Tanggal</label>
                    <input type="date" name="date" value="{{ request('date') }}" onchange="updateTable(this.form)"
                           class="w-full bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-2xl text-sm focus:ring-emerald-500 focus:border-emerald-500 shadow-sm">
                </div>
                <div class="w-full lg:w-40">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 ml-1">Status</label>
                    <select name="status" onchange="updateTable(this.form)"
                            class="w-full bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-2xl text-sm focus:ring-emerald-500 focus:border-emerald-500 shadow-sm cursor-pointer font-bold">
                        <option value="">Semua Status</option>
                        <option value="Lancar" {{ request('status') == 'Lancar' ? 'selected' : '' }}>Lancar</option>
                        <option value="Perlu Perbaikan" {{ request('status') == 'Perlu Perbaikan' ? 'selected' : '' }}>Perlu Perbaikan</option>
                    </select>
                </div>
                <div class="w-full lg:w-40">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 ml-1">Kehadiran</label>
                    <select name="presence" onchange="updateTable(this.form)"
                            class="w-full bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-2xl text-sm focus:ring-emerald-500 focus:border-emerald-500 shadow-sm cursor-pointer font-bold">
                        <option value="">Semua Kehadiran</option>
                        <option value="hadir" {{ request('presence') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="absen" {{ request('presence') == 'absen' ? 'selected' : '' }}>Tidak Hadir</option>
                    </select>
                </div>
            </form>
        </div>

        <div id="table-container">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700">
                            <th class="px-6 py-4 text-xs font-black text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-4 text-xs font-black text-gray-500 uppercase tracking-wider">Santri</th>
                            <th class="px-6 py-4 text-xs font-black text-gray-500 uppercase tracking-wider">Materi Hafalan</th>
                            <th class="px-6 py-4 text-xs font-black text-gray-500 uppercase tracking-wider text-center">Status</th>
                            <th class="px-6 py-4 text-xs font-black text-gray-500 uppercase tracking-wider text-center">Kehadiran</th>
                            <th class="px-6 py-4 text-xs font-black text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-sm">
                        @forelse($hafalan as $item)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900/30 transition-colors">
                                <td class="px-6 py-4 text-gray-500">{{ $item->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900 dark:text-white">{{ $item->student->name }}</div>
                                    <div class="text-[11px] text-gray-400">NIS: {{ $item->student->nis }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($item->is_present)
                                        <span class="font-bold text-emerald-600">Juz {{ $item->juz }}</span> •
                                        <span class="text-gray-600 dark:text-gray-300">{{ $item->surah }} ({{ $item->ayat }})</span>
                                    @else
                                        <span class="text-gray-400 italic">Tidak ada setoran</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($item->is_present)
                                        <span class="px-2 py-1 text-[10px] font-black uppercase rounded-lg border {{ $item->status === 'Lancar' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-orange-50 text-orange-600 border-orange-100' }}">
                                            {{ $item->status }}
                                        </span>
                                    @else
                                        <span class="text-gray-300">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($item->is_present)
                                        <span class="px-2 py-1 bg-emerald-600 text-white text-[10px] font-black rounded-lg uppercase shadow-sm">Hadir</span>
                                    @else
                                        <span class="px-2 py-1 bg-gray-100 text-gray-400 text-[10px] font-bold rounded-lg uppercase border border-gray-200">Absen</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        @can('update', $item)
                                            <a href="{{ route('guru.hafalan.edit', $item) }}" class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-xl transition-all">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </a>
                                        @endcan

                                        @can('delete', $item)
                                            <form action="{{ route('guru.hafalan.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus riwayat ini?')" class="inline">
                                                @csrf @method('DELETE')
                                                <button class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-xl transition-all">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-500 italic">Riwayat tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 bg-gray-50/50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700">
                {{ $hafalan->appends(request()->input())->links('vendor.pagination.custom') }}
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

            const hafalanFilterForm = document.querySelector('form[action="{{ route('guru.hafalan.index') }}"]');
            if (hafalanFilterForm) {
                hafalanFilterForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    updateTable(e.target);
                });
            }
        </script>
    @endpush
</x-tahfidz-layout>
