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
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $student->parent->name ?? '-' }}</p>
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

                <div class="mt-8">
                    <a href="{{ route('guru.students.export', $student) }}" class="w-full py-3 bg-emerald-700 text-white rounded-2xl font-bold flex items-center justify-center gap-2 hover:bg-emerald-800 transition-all shadow-lg shadow-emerald-200 dark:shadow-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Download Raport (PDF)
                    </a>
                </div>
            </x-tahfidz.card>
        </div>

        <!-- Main Content: History -->
        <div class="lg:col-span-2 space-y-6">
            <x-tahfidz.card title="Riwayat Hafalan">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <th class="py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Tanggal</th>
                                <th class="py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Hafalan</th>
                                <th class="py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase text-center">Status</th>
                                <th class="py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($memorizations as $m)
                                <tr>
                                    <td class="py-4 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $m->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="py-4">
                                        @if($m->is_present)
                                            <div class="text-sm font-bold text-gray-900 dark:text-white">Juz {{ $m->juz }}: {{ $m->surah }}</div>
                                            <div class="text-xs text-gray-500">Ayat {{ $m->ayat }}</div>
                                        @else
                                            <span class="text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded font-bold">Absen</span>
                                        @endif
                                    </td>
                                    <td class="py-4 text-center">
                                        @if($m->is_present)
                                            <span class="text-xs font-bold {{ $m->status === 'Lancar' ? 'text-emerald-600' : 'text-orange-600' }}">
                                                {{ $m->status }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="py-4">
                                        <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-2" title="{{ $m->notes }}">
                                            {{ $m->notes ?: '-' }}
                                        </p>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-10 text-center text-gray-500 italic">Belum ada riwayat setoran.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-tahfidz.card>
        </div>
    </div>
</x-tahfidz-layout>
