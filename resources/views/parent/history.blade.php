<x-tahfidz-layout>
    <x-slot name="header">
        Riwayat Hafalan
    </x-slot>
    <x-slot name="subtitle">
        Catatan lengkap perkembangan hafalan santri.
    </x-slot>

    @if(session('success'))
    <div class="p-4 mb-6 text-sm text-emerald-800 dark:text-emerald-400 rounded-2xl bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-100 dark:border-emerald-800/50 font-bold flex items-center gap-3" role="alert">
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
                        @if(Auth::user()->role === 'guru' || Auth::user()->role === 'admin')
                        <th class="px-8 py-5">Santri</th>
                        @endif
                        <th class="px-8 py-5">Surah / Ayat</th>
                        <th class="px-8 py-5">Status</th>
                        <th class="px-8 py-5">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($history as $record)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/40 transition-colors">
                        <td class="px-8 py-5 text-gray-400 whitespace-nowrap">{{ $record->created_at->format('d M Y, H:i') }}</td>
                        @if(Auth::user()->role === 'guru' || Auth::user()->role === 'admin')
                        <td class="px-8 py-5 font-bold text-gray-900 dark:text-white">{{ $record->student->name }}</td>
                        @endif
                        <td class="px-8 py-5">
                            <div class="font-bold text-gray-900 dark:text-white">{{ $record->surah }}</div>
                            <div class="text-xs text-gray-400">{{ $record->ayat }}</div>
                        </td>
                        <td class="px-8 py-5">
                            <span class="px-3 py-1 text-xs font-bold rounded-lg uppercase tracking-wider {{ $record->status === 'Lancar' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400' : 'bg-orange-100 text-orange-700 dark:bg-orange-900/40 dark:text-orange-400' }}">
                                {{ $record->status }}
                            </span>
                        </td>
                        <td class="px-8 py-5 text-gray-600 dark:text-gray-300 italic">"{{ $record->notes ?? '-' }}"</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ (Auth::user()->role === 'guru' || Auth::user()->role === 'admin') ? 5 : 4 }}" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-16 h-16 text-gray-200 dark:text-gray-700 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                <p class="text-gray-400 dark:text-gray-500 font-medium text-lg">Belum ada riwayat hafalan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-tahfidz-layout>
