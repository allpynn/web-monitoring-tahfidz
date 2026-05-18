<x-tahfidz-layout>
    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-tight">Santri Bimbingan</h2>
            <p class="text-sm text-gray-500 font-medium">Kelola dan pantau perkembangan hafalan santri Anda.</p>
        </div>
        <a href="{{ route('guru.students.create') }}"
            class="px-6 py-3 bg-emerald-700 text-white rounded-2xl font-bold hover:bg-emerald-800 transition-all shadow-xl shadow-emerald-200 dark:shadow-none flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Santri Baru
        </a>
    </div>

    @if(session('success'))
        <x-tahfidz.card class="mb-6 !bg-emerald-50 !border-emerald-100 !py-3">
            <p class="text-emerald-700 font-bold flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd"></path>
                </svg>
                {{ session('success') }}
            </p>
        </x-tahfidz.card>
    @endif

    <x-tahfidz.card>
        <div class="mb-6 flex flex-col md:flex-row gap-4">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" id="studentSearch"
                    class="block w-full pl-11 pr-4 py-3 border border-gray-100 dark:border-gray-700 rounded-2xl bg-gray-50 dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm transition-all"
                    placeholder="Cari berdasarkan nama atau NIS...">
            </div>
        </div>

        <div class="overflow-x-auto rounded-2xl">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700">
                        <th class="px-6 py-4 text-xs font-black text-gray-500 uppercase tracking-wider">Info Santri</th>
                        <th class="px-6 py-4 text-xs font-black text-gray-500 uppercase tracking-wider">Target Hafalan
                        </th>
                        <th
                            class="px-6 py-4 text-xs font-black text-gray-500 uppercase tracking-wider text-emerald-600">
                            Verifikasi Selesai</th>
                        <th class="px-6 py-4 text-xs font-black text-gray-500 uppercase tracking-wider text-right">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700" id="studentTable">
                    @forelse($students as $student)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors group">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 flex items-center justify-center font-bold text-sm">
                                        {{ substr($student->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div
                                            class="font-bold text-gray-900 dark:text-white group-hover:text-emerald-700 transition-colors">
                                            {{ $student->name }}
                                        </div>
                                        <div class="text-xs text-gray-500 font-medium tracking-tight">NIS:
                                            {{ $student->nis }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex flex-wrap gap-1.5 max-w-[150px]">
                                    @php $completedJuz = $student->completed_juz; @endphp
                                    @forelse($student->targets as $t)
                                        @php $isAchieved = in_array($t->target_juz, $completedJuz); @endphp
                                        <div class="flex flex-col items-center">
                                            <span
                                                class="px-2 py-0.5 rounded-lg text-[10px] font-black {{ $isAchieved ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : 'bg-blue-50 text-blue-600 border border-blue-100' }}">
                                                JUZ {{ $t->target_juz }}
                                                @if($isAchieved)
                                                    <svg class="w-2.5 h-2.5 inline ml-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z">
                                                        </path>
                                                    </svg>
                                                @endif
                                            </span>
                                        </div>
                                    @empty
                                        <span class="text-gray-400 text-xs">-</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex flex-wrap gap-1">
                                    @forelse($student->completed_juz as $cj)
                                        <span
                                            class="px-2 py-1 bg-emerald-600 text-white text-[10px] font-bold rounded-md shadow-sm">
                                            {{ $cj }}
                                        </span>
                                    @empty
                                        <span class="text-[10px] text-gray-400 italic">Belum Ada...</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('guru.students.show', $student) }}"
                                        class="p-2 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 rounded-xl hover:bg-emerald-700 hover:text-white transition-all shadow-sm"
                                        title="Monitoring Lengkap">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                            </path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('guru.students.edit', $student) }}"
                                        class="p-2 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-xl hover:bg-blue-600 hover:text-white transition-all shadow-sm"
                                        title="Edit Profil">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <p class="text-gray-500 font-medium">Belum ada santri yang ditugaskan kepada Anda.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-tahfidz.card>

    @push('scripts')
        <script>
            document.getElementById('studentSearch').addEventListener('input', function (e) {
                const query = e.target.value.toLowerCase();
                const rows = document.getElementById('studentTable').getElementsByTagName('tr');

                for (let i = 0; i < rows.length; i++) {
                    const name = rows[i].getElementsByTagName('td')[0]?.innerText.toLowerCase() || '';
                    if (name.includes(query)) {
                        rows[i].style.display = '';
                    } else {
                        rows[i].style.display = 'none';
                    }
                }
            });
        </script>
    @endpush
</x-tahfidz-layout>