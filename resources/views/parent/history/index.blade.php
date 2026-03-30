<x-tahfidz-layout>
    <x-slot name="header">
        Riwayat Lengkap Hafalan
    </x-slot>
    <x-slot name="subtitle">
        Telusuri seluruh catatan setoran ananda dari awal hingga sekarang.
    </x-slot>

    @if($students->count() > 1)
        <div class="mb-8">
            <x-tahfidz.card title="Pilih Ananda">
                <form action="{{ route('parent.history.index') }}" method="GET" class="flex items-end gap-4">
                    <div class="flex-1">
                        <select name="student_id" class="block w-full px-4 py-3 border border-gray-100 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm dark:text-white transition-all shadow-sm">
                            @foreach($students as $s)
                                <option value="{{ $s->id }}" {{ $student && $student->id == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="px-6 py-3 bg-emerald-700 text-white rounded-2xl font-bold hover:bg-emerald-800 transition-all">Tampilkan</button>
                </form>
            </x-tahfidz.card>
        </div>
    @endif

    @if($student)
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white uppercase tracking-tight">Riwayat: {{ $student->name }}</h2>
            <a href="{{ route('parent.history.export', $student) }}" class="px-5 py-2.5 bg-emerald-700 text-white rounded-2xl font-bold hover:bg-emerald-800 transition-all shadow-lg shadow-emerald-200 dark:shadow-none flex items-center gap-2 text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export PDF
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-3xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Setoran</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-center">Kehadiran</th>
                            <th class="px-6 py-4">Catatan Guru & Feedback Anda</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($hafalan as $m)
                            <tr class="hover:bg-gray-50/30 dark:hover:bg-gray-900/20 transition-all">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $m->created_at->format('d M Y') }}</div>
                                    <div class="text-[10px] text-gray-500">{{ $m->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($m->is_present)
                                        <div class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-tight">Juz {{ $m->juz }}: {{ $m->surah }}</div>
                                        <div class="text-xs text-emerald-600 font-medium italic">Ayat {{ $m->ayat }}</div>
                                    @else
                                        <span class="text-gray-400 italic text-sm">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($m->is_present)
                                        <span class="px-3 py-1 {{ $m->status === 'Lancar' ? 'bg-emerald-100 text-emerald-700' : 'bg-orange-100 text-orange-700' }} text-[10px] font-bold rounded-full uppercase">
                                            {{ $m->status }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="w-2 h-2 rounded-full mx-auto {{ $m->is_present ? 'bg-emerald-500 animate-pulse' : 'bg-red-500' }}"></div>
                                </td>
                                <td class="px-6 py-4 max-w-xs">
                                    <div class="space-y-2">
                                        @if($m->notes)
                                            <div class="p-2 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg border-l-2 border-emerald-500 italic text-[10px] text-gray-600 dark:text-gray-400">
                                                "{{ $m->notes }}"
                                            </div>
                                        @endif
                                        @if($m->parent_comment)
                                            <div class="p-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg border-l-2 border-blue-500 italic text-[10px] text-gray-600 dark:text-gray-400">
                                                <strong>Feedback Anda:</strong> "{{ $m->parent_comment }}"
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-10 text-center text-gray-400 italic">Belum ada catatan riwayat.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($hafalan->hasPages())
                <div class="p-4 border-t border-gray-100 dark:border-gray-700">
                    {{ $hafalan->links() }}
                </div>
            @endif
        </div>
    @else
        <div class="p-12 bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 text-center">
            <p class="text-gray-500">Pilih santri untuk melihat riwayat.</p>
        </div>
    @endif
</x-tahfidz-layout>
