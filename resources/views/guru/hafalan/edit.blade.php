<x-tahfidz-layout>
    <x-slot name="header">
        Edit Catatan Hafalan
    </x-slot>
    <x-slot name="subtitle">
        Update data hafalan untuk {{ $hafalan->student->name }}.
    </x-slot>

    <div class="max-w-2xl">
        <div class="mb-6">
            <a href="{{ route('guru.hafalan.index') }}" class="text-sm font-bold text-emerald-700 dark:text-emerald-500 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Riwayat
            </a>
        </div>

        <form action="{{ route('guru.hafalan.update', $hafalan) }}" method="POST" class="space-y-6">
            @csrf
            @method('PATCH')
            
            <x-tahfidz.card title="Informasi Santri">
                <div class="space-y-4">
                    <div class="w-full">
                        <label for="student_id" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Pilih Santri</label>
                        <select name="student_id" id="student_id" class="block w-full px-4 py-3 border border-gray-100 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm dark:text-white transition-all shadow-sm" required>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ old('student_id', $hafalan->student_id) == $student->id ? 'selected' : '' }}>{{ $student->name }} ({{ $student->nis }})</option>
                            @endforeach
                        </select>
                        @error('student_id') <p class="mt-1 text-xs text-red-600 font-bold italic">{{ $message }}</p> @enderror
                    </div>

                    <div class="w-full">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Status Kehadiran</label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio" name="is_present" value="1" class="w-5 h-5 text-emerald-600 focus:ring-emerald-500" {{ old('is_present', $hafalan->is_present) == '1' ? 'checked' : '' }} onchange="toggleInputs(true)">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-emerald-600 transition-colors">Hadir</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio" name="is_present" value="0" class="w-5 h-5 text-red-600 focus:ring-red-500" {{ old('is_present', $hafalan->is_present) == '0' ? 'checked' : '' }} onchange="toggleInputs(false)">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-red-600 transition-colors">Absen / Tidak Setor</span>
                            </label>
                        </div>
                    </div>
                </div>
            </x-tahfidz.card>

            <div id="hafalan-inputs" class="{{ old('is_present', $hafalan->is_present) == '0' ? 'hidden' : '' }}">
                <x-tahfidz.card title="Detail Hafalan">
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-6">
                            <x-tahfidz.form-input type="number" name="juz" label="Juz" placeholder="Contoh: 30" :value="old('juz', $hafalan->juz)" min="1" max="30" />
                            <x-tahfidz.form-input name="surah" label="Nama Surah" placeholder="Contoh: Al-Baqarah" :value="old('surah', $hafalan->surah)" />
                        </div>
                        <x-tahfidz.form-input name="ayat" label="Ayat" placeholder="Contoh: 1-10" :value="old('ayat', $hafalan->ayat)" />
                        
                        <div class="w-full">
                            <label for="status" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Evaluasi Status</label>
                            <select name="status" id="status" class="block w-full px-4 py-3 border border-gray-100 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm dark:text-white transition-all shadow-sm">
                                <option value="Lancar" {{ old('status', $hafalan->status) == 'Lancar' ? 'selected' : '' }}>Lancar ✨</option>
                                <option value="Perlu Perbaikan" {{ old('status', $hafalan->status) == 'Perlu Perbaikan' ? 'selected' : '' }}>Perlu Perbaikan ⚠️</option>
                            </select>
                        </div>
                    </div>
                </x-tahfidz.card>
            </div>

            <x-tahfidz.card title="Catatan Guru">
                <textarea name="notes" rows="3" class="block w-full px-4 py-3 border border-gray-100 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm dark:text-white transition-all shadow-sm placeholder-gray-400" placeholder="Berikan catatan atau motivasi untuk santri...">{{ old('notes', $hafalan->notes) }}</textarea>
                @error('notes') <p class="mt-1 text-xs text-red-600 font-bold italic">{{ $message }}</p> @enderror
            </x-tahfidz.card>

            <div class="flex justify-end">
                <button type="submit" class="px-8 py-4 bg-emerald-700 text-white rounded-2xl font-bold text-lg hover:bg-emerald-800 shadow-xl shadow-emerald-200 dark:shadow-none transition-all">
                    Update Catatan
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        function toggleInputs(isPresent) {
            const inputs = document.getElementById('hafalan-inputs');
            if (isPresent) {
                inputs.classList.remove('hidden');
            } else {
                inputs.classList.add('hidden');
            }
        }
    </script>
    @endpush
</x-tahfidz-layout>
