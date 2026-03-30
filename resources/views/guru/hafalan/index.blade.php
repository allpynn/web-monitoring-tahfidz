<x-tahfidz-layout>
    <x-slot name="header">
        Riwayat Setoran Hafalan
    </x-slot>
    <x-slot name="subtitle">
        Daftar semua input hafalan yang Anda lakukan.
    </x-slot>

    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Semua Rekaman</h2>
        <a href="{{ route('guru.hafalan.create') }}" class="px-5 py-2.5 bg-emerald-700 text-white rounded-2xl font-bold hover:bg-emerald-800 transition-all shadow-lg shadow-emerald-200 dark:shadow-none flex items-center gap-2 text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Baru
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 rounded-2xl border border-emerald-100 dark:border-emerald-800 font-bold flex items-center gap-3">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-3xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700">
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Santri</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kehadiran</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Hafalan</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($hafalan as $item)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900/30 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $item->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $item->student->name }}</div>
                                <div class="text-xs text-gray-500">NIS: {{ $item->student->nis }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($item->is_present)
                                    <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs font-bold rounded">Hadir</span>
                                @else
                                    <span class="px-2 py-0.5 bg-red-100 text-red-700 text-xs font-bold rounded">Absen</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($item->is_present)
                                    <div class="text-sm text-gray-900 dark:text-white font-medium">Juz {{ $item->juz }}: {{ $item->surah }}</div>
                                    <div class="text-xs text-gray-500">Ayat {{ $item->ayat }}</div>
                                @else
                                    <span class="text-gray-400 italic text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($item->is_present)
                                    <span class="px-3 py-1 {{ $item->status === 'Lancar' ? 'bg-emerald-100 text-emerald-700' : 'bg-orange-100 text-orange-700' }} text-xs font-bold rounded-full">
                                        {{ $item->status }}
                                    </span>
                                @else
                                    <span class="text-gray-400 italic text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('guru.hafalan.edit', $item) }}" class="text-blue-600 hover:underline font-bold">Edit</a>
                                    <form action="{{ route('guru.hafalan.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline font-bold">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400 italic">Belum ada rekaman hafalan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-tahfidz-layout>
