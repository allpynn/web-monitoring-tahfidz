<x-tahfidz-layout>
    <x-slot name="header">
        Detail Santri
    </x-slot>
    <x-slot name="subtitle">
        Informasi lengkap dan riwayat hafalan {{ $student->name }}.
    </x-slot>

    <div class="mb-6">
        <a href="{{ route('guru.students.index') }}" class="text-sm font-bold text-emerald-700 dark:text-emerald-500 flex items-center gap-2">
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
                    <div>
                        <span class="text-xs font-bold text-gray-400 uppercase">Target Hafalan</span>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $student->target_juz }} Juz</p>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-gray-400 uppercase">Target Selesai</span>
                        <p class="text-sm font-medium text-gray-900 dark:text-white text-emerald-600 font-bold">
                            {{ $student->target_date ? \Carbon\Carbon::parse($student->target_date)->format('d M Y') : '-' }}
                        </p>
                    </div>
                </div>

                <div class="mt-8 space-y-3">
                    <a href="{{ route('guru.students.export', $student) }}" class="w-full py-3 bg-emerald-700 text-white rounded-2xl font-bold flex items-center justify-center gap-2 hover:bg-emerald-800 transition-all shadow-lg shadow-emerald-200 dark:shadow-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Download Raport (PDF)
                    </a>
                    <a href="{{ route('guru.students.export_semester', $student) }}" class="w-full py-3 bg-amber-600 text-white rounded-2xl font-bold flex items-center justify-center gap-2 hover:bg-amber-700 transition-all shadow-lg shadow-amber-200 dark:shadow-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Rekap Semester (PDF)
                    </a>
                </div>
            </x-tahfidz.card>
        </div>

        <!-- Main Content: History -->
        <div class="lg:col-span-2 space-y-6">
            <x-tahfidz.card title="Rekaman Hafalan & Monitoring">
                <!-- Filters & Search -->
                <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4 bg-gray-50 dark:bg-gray-900/40 p-4 rounded-2xl border border-gray-100 dark:border-gray-700">
                    <div class="relative">
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Cari Catatan</label>
                        <input type="text" id="logSearch" class="block w-full px-4 py-2 text-sm border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 focus:ring-emerald-500 focus:border-emerald-500 transition-all" placeholder="Ketik surah/catatan...">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Filter Status</label>
                        <select id="statusFilter" class="block w-full px-4 py-2 text-sm border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 focus:ring-emerald-500 focus:border-emerald-500 transition-all">
                            <option value="all">Semua Status</option>
                            <option value="Lancar">Lancar</option>
                            <option value="Perlu Perbaikan">Perlu Perbaikan</option>
                            <option value="Absen">Absen</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Filter Tanggal</label>
                        <input type="date" id="dateFilter" class="block w-full px-4 py-2 text-sm border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 focus:ring-emerald-500 focus:border-emerald-500 transition-all">
                    </div>
                </div>

                <div class="overflow-x-auto rounded-2xl bg-white dark:bg-transparent">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                                <th class="px-4 py-4 text-[10px] font-black text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-4 py-4 text-[10px] font-black text-gray-500 uppercase tracking-wider">Materi Hafalan</th>
                                <th class="px-4 py-4 text-[10px] font-black text-gray-500 uppercase tracking-wider text-center">Status</th>
                                <th class="px-4 py-4 text-[10px] font-black text-gray-500 uppercase tracking-wider text-right">Kelola</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700" id="logTableBody">
                            @forelse($memorizations as $m)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-all log-row" 
                                    data-status="{{ $m->is_present ? $m->status : 'Absen' }}" 
                                    data-date="{{ $m->created_at->format('Y-m-d') }}">
                                    <td class="px-4 py-4 text-sm font-medium text-gray-600 dark:text-gray-400">
                                        {{ $m->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-4">
                                        @if($m->is_present)
                                            <div class="text-sm font-bold text-gray-900 dark:text-white">Juz {{ $m->juz }}: {{ $m->surah }}</div>
                                            <div class="text-[10px] text-gray-500 font-medium">Ayat {{ $m->ayat }}</div>
                                            @if($m->notes)
                                                <div class="mt-1 text-[10px] text-gray-400 italic bg-gray-50 dark:bg-gray-800 p-1.5 rounded-lg border border-gray-100 dark:border-gray-700 line-clamp-1 log-notes" title="Catatan Guru: {{ $m->notes }}">{{ $m->notes }}</div>
                                            @endif
                                            @if($m->parent_comment)
                                                <div class="mt-1 text-[10px] text-blue-500 font-bold bg-blue-50 dark:bg-blue-900/20 p-1.5 rounded-lg border border-blue-100 dark:border-blue-800 line-clamp-1" title="Komentar Orang Tua: {{ $m->parent_comment }}">
                                                    <span class="uppercase text-[8px] opacity-70">💬 Wali:</span> {{ $m->parent_comment }}
                                                </div>
                                            @endif
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-700">Abstensi / Tanpa Setoran</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        @if($m->is_present)
                                            <span class="text-[10px] font-black uppercase {{ $m->status === 'Lancar' ? 'text-emerald-600' : 'text-orange-600' }}">
                                                {{ $m->status }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-right">
                                        <div class="flex justify-end gap-1.5 opacity-0 group-hover:opacity-100 focus-within:opacity-100 transition-opacity">
                                            <a href="{{ route('guru.hafalan.edit', $m) }}" class="p-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition-all shadow-sm" title="Ubah Data">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <form action="{{ route('guru.hafalan.destroy', $m) }}" method="POST" class="inline" onsubmit="return confirm('Hapus rekaman ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all shadow-sm" title="Hapus">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-12 text-center text-gray-500 font-medium italic">Belum ada riwayat setoran untuk santri ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
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

            function filterLogs() {
                const query = searchInput.value.toLowerCase();
                const status = statusFilter.value;
                const date = dateFilter.value;

                rows.forEach(row => {
                    const rowText = row.innerText.toLowerCase();
                    const rowStatus = row.getAttribute('data-status');
                    const rowDate = row.getAttribute('data-date');

                    let show = true;

                    if (query && !rowText.includes(query)) show = false;
                    if (status !== 'all' && rowStatus !== status) show = false;
                    if (date && rowDate !== date) show = false;

                    row.style.display = show ? '' : 'none';
                });
            }

            searchInput.addEventListener('input', filterLogs);
            statusFilter.addEventListener('change', filterLogs);
            dateFilter.addEventListener('change', filterLogs);
        });
    </script>
    @endpush
    </div>
</x-tahfidz-layout>
