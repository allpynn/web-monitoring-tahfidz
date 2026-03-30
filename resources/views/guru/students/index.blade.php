<x-tahfidz-layout>
    <x-slot name="header">
        Daftar Santri Saya
    </x-slot>
    <x-slot name="subtitle">
        Santri yang berada di bawah bimbingan Anda.
    </x-slot>

    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white uppercase tracking-tight">Santri Bimbingan</h2>
        <a href="{{ route('guru.students.create') }}" class="px-5 py-2.5 bg-emerald-700 text-white rounded-2xl font-bold hover:bg-emerald-800 transition-all shadow-lg shadow-emerald-200 dark:shadow-none flex items-center gap-2 text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Santri
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 rounded-2xl border border-emerald-100 dark:border-emerald-800 font-bold">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($students as $student)
            <x-tahfidz.card :title="$student->name">
                <div class="space-y-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 font-bold uppercase text-[10px]">NIS</span>
                        <span class="font-bold text-gray-900 dark:text-white">{{ $student->nis }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 font-bold uppercase text-[10px]">Progres Target</span>
                        <span class="font-bold text-emerald-600">{{ $student->target_progress }}%</span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-700 h-1.5 rounded-full overflow-hidden">
                        <div class="bg-emerald-500 h-full rounded-full" style="width: {{ $student->target_progress }}%"></div>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 font-bold uppercase text-[10px]">Hafalan Terakhir</span>
                        <span class="font-bold text-gray-900 dark:text-white">Juz {{ $student->current_juz }}</span>
                    </div>
                    
                    <div class="pt-4 flex items-center gap-2">
                        <a href="{{ route('guru.students.show', $student) }}" class="flex-1 text-center py-2 bg-emerald-600 text-white text-xs font-bold rounded-xl hover:bg-emerald-700 transition-colors">Detail</a>
                        <a href="{{ route('guru.students.edit', $student) }}" class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-xl transition-all" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </a>
                        <form action="{{ route('guru.students.destroy', $student) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus santri ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-xl transition-all" title="Hapus">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </x-tahfidz.card>
        @empty
            <div class="col-span-full p-12 bg-white dark:bg-gray-800 rounded-3xl border border-dashed border-gray-200 dark:border-gray-700 text-center">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <p class="text-gray-500 dark:text-gray-400">Belum ada santri yang ditugaskan kepada Anda.</p>
            </div>
        @endforelse
    </div>
</x-tahfidz-layout>
