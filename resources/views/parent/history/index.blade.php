<x-tahfidz-layout>
    <x-slot name="header">
        Riwayat Hafalan
    </x-slot>
    <x-slot name="subtitle">
        Seluruh catatan setoran hafalan {{ $student->name ?? 'Anak' }} dari waktu ke waktu.
    </x-slot>

    @if(!$student)
        <div class="p-8 text-center bg-white rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-gray-400">Data santri tidak ditemukan.</p>
        </div>
    @else
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Surah</th>
                            <th class="px-6 py-4">Ayat</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Guru Penguji</th>
                            <th class="px-6 py-4">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($hafalan as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-gray-400 font-medium">{{ $item->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $item->surah }}</div>
                            </td>
                            <td class="px-6 py-4 font-mono text-xs">{{ $item->ayat }}</td>
                            <td class="px-6 py-4">
                                @if($item->status === 'Lancar')
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">LANCAR</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">PERLU PERBAIKAN</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $item->guru->name }}</td>
                            <td class="px-6 py-4 italic text-xs text-gray-600">{{ $item->notes ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">Belum ada riwayat hafalan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</x-tahfidz-layout>
