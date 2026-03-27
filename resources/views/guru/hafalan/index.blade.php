<x-tahfidz-layout>
    <x-slot name="header">
        Riwayat Hafalan & Absensi
    </x-slot>
    <x-slot name="subtitle">
        Daftar perkembangan hafalan dan kehadiran santri.
    </x-slot>

    <div x-data="{ search: '' }">
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="relative w-full md:w-96">
            <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </span>
            <input type="text" x-model="search" class="block w-full pl-12 pr-4 py-3 border border-gray-100 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm dark:text-white transition-all shadow-sm" placeholder="Cari santri...">
        </div>
        <a href="{{ route('guru.hafalan.create') }}" class="w-full md:w-auto px-6 py-3 bg-emerald-700 text-white rounded-2xl text-sm font-bold hover:bg-emerald-800 transition-all shadow-lg shadow-emerald-200 dark:shadow-none flex items-center justify-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Input Hafalan Baru
        </a>
    </div>

    @if(session('success'))
    <div class="p-4 mb-6 text-sm text-emerald-800 dark:text-emerald-400 rounded-2xl bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-100 dark:border-emerald-800/50 font-bold flex items-center gap-3 animate-fade-in" role="alert">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-3xl shadow-sm overflow-hidden transition-all">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-500 dark:text-gray-400 uppercase bg-gray-50 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th class="px-8 py-5">Tanggal</th>
                        <th class="px-8 py-5">Santri</th>
                        <th class="px-8 py-5">Juz</th>
                        <th class="px-8 py-5">Status / Hafalan</th>
                        <th class="px-8 py-5">Catatan</th>
                        <th class="px-8 py-5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($hafalan as $item)
                    <tr x-show="!search || '{{ strtolower($item->student->name) }}'.includes(search.toLowerCase()) || '{{ strtolower($item->surah) }}'.includes(search.toLowerCase())"
                        class="hover:bg-gray-50 dark:hover:bg-gray-900/40 transition-colors">
                        <td class="px-8 py-5 text-gray-400 whitespace-nowrap">{{ $item->created_at->format('d M Y') }}</td>
                        <td class="px-8 py-5">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-gray-900 dark:text-white">{{ $item->student->name }}</span>
                                <a href="{{ route('guru.hafalan.export', $item->student) }}" class="ml-2 p-1.5 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all" title="Download Raport PDF">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </a>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            @if($item->juz)
                                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400 rounded-lg font-bold">
                                    Juz {{ $item->juz }}
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-8 py-5">
                            @if($item->is_present)
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase mb-1">Hadir & Setoran</span>
                                    <div class="font-bold text-gray-900 dark:text-white">{{ $item->surah }}</div>
                                    <div class="text-xs text-gray-400">{{ $item->ayat }} • {{ $item->status }}</div>
                                </div>
                            @else
                                <span class="px-3 py-1 text-xs font-bold bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-400 rounded-lg uppercase tracking-wider">
                                    Tidak Hadir
                                </span>
                            @endif
                        </td>
                        <td class="px-8 py-5 text-gray-600 dark:text-gray-300 italic">
                            <div>"{{ $item->notes ?? '-' }}"</div>
                            @if($item->parent_comment)
                                <div class="mt-2 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800/50 rounded-xl not-italic">
                                    <div class="flex items-center gap-2 mb-1">
                                        <svg class="w-3 h-3 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z"></path></svg>
                                        <span class="text-[10px] font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider">Pesan Orang Tua</span>
                                    </div>
                                    <p class="text-xs text-blue-800 dark:text-blue-300 font-medium">{{ $item->parent_comment }}</p>
                                </div>
                            @endif
                        </td>
                        <td class="px-8 py-5 text-center text-xs">
                            <div class="flex justify-center items-center space-x-3">
                                <a href="{{ route('guru.hafalan.edit', $item) }}" class="p-2.5 text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 rounded-xl transition-all" title="Ubah">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('guru.hafalan.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-xl transition-all" title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-16 h-16 text-gray-200 dark:text-gray-700 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                <p class="text-gray-400 dark:text-gray-500 font-medium text-lg">Belum ada input hafalan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    </div>
</x-tahfidz-layout>
