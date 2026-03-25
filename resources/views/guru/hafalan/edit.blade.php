<x-tahfidz-layout>
    <x-slot name="header">
        Edit Data Hafalan
    </x-slot>
    <x-slot name="subtitle">
        Perbarui catatan perkembangan atau status kehadiran santri.
    </x-slot>

    <div class="max-w-2xl mx-auto" x-data="{ isPresent: {{ $hafalan->is_present ? 'true' : 'false' }} }">
        <div class="p-8 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-3xl shadow-sm relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-emerald-50 dark:bg-emerald-900/10 rounded-full blur-3xl"></div>
            
            <form action="{{ route('guru.hafalan.update', $hafalan) }}" method="POST" class="relative z-10 space-y-6">
                @csrf
                @method('PUT')
                
                <div>
                    <label for="student_id" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Pilih Santri</label>
                    <select name="student_id" id="student_id" class="block w-full px-4 py-3 border border-gray-100 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm dark:text-white transition-all shadow-sm">
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ $hafalan->student_id == $student->id ? 'selected' : '' }}>{{ $student->name }} (NIS: {{ $student->nis }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Status Kehadiran / Absensi</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="relative flex items-center p-4 border border-gray-100 dark:border-gray-700 rounded-2xl cursor-pointer hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-all group" :class="isPresent ? 'bg-emerald-50/50 dark:bg-emerald-900/10 border-emerald-200' : ''">
                            <input type="radio" name="is_present" value="1" x-model="isPresent" x-on:change="isPresent = true" class="w-4 h-4 text-emerald-600 bg-gray-100 border-gray-300 focus:ring-emerald-500" {{ $hafalan->is_present ? 'checked' : '' }}>
                            <span class="ml-3 text-sm font-bold text-gray-700 dark:text-gray-300 group-hover:text-emerald-700">HADIR</span>
                        </label>
                        <label class="relative flex items-center p-4 border border-gray-100 dark:border-gray-700 rounded-2xl cursor-pointer hover:bg-red-50 dark:hover:bg-red-900/20 transition-all group" :class="!isPresent ? 'bg-red-50/50 dark:bg-red-900/10 border-red-200' : ''">
                            <input type="radio" name="is_present" value="0" x-model="isPresent" x-on:change="isPresent = false" class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 focus:ring-red-500" {{ !$hafalan->is_present ? 'checked' : '' }}>
                            <span class="ml-3 text-sm font-bold text-gray-700 dark:text-gray-300 group-hover:text-red-700">TIDAK HADIR</span>
                        </label>
                    </div>
                </div>

                <div x-show="isPresent" x-transition class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-tahfidz.form-input label="Nama Surah" name="surah" type="text" placeholder="Contoh: An-Naba" :value="$hafalan->surah" />
                        <x-tahfidz.form-input label="Ayat" name="ayat" type="text" placeholder="Contoh: 1 - 10" :value="$hafalan->ayat" />
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Status Hafalan</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative flex items-center p-4 border border-gray-100 dark:border-gray-700 rounded-2xl cursor-pointer hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-all group">
                                <input type="radio" name="status" value="Lancar" class="w-4 h-4 text-emerald-600 bg-gray-100 border-gray-300 focus:ring-emerald-500" {{ $hafalan->status === 'Lancar' ? 'checked' : '' }}>
                                <span class="ml-3 text-sm font-bold text-gray-700 dark:text-gray-300 group-hover:text-emerald-700">LANCAR</span>
                            </label>
                            <label class="relative flex items-center p-4 border border-gray-100 dark:border-gray-700 rounded-2xl cursor-pointer hover:bg-orange-50 dark:hover:bg-orange-900/20 transition-all group">
                                <input type="radio" name="status" value="Perlu Perbaikan" class="w-4 h-4 text-orange-600 bg-gray-100 border-gray-300 focus:ring-orange-500" {{ $hafalan->status === 'Perlu Perbaikan' ? 'checked' : '' }}>
                                <span class="ml-3 text-sm font-bold text-gray-700 dark:text-gray-300 group-hover:text-orange-700 whitespace-nowrap">PERLU PERBAIKAN</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="notes" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Catatan (Pilihan)</label>
                    <textarea name="notes" id="notes" rows="3" class="block w-full px-4 py-3 border border-gray-100 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm dark:text-white transition-all shadow-sm" placeholder="Tambahkan catatan khusus...">{{ $hafalan->notes }}</textarea>
                </div>

                <div class="flex items-center justify-end pt-4 space-x-3">
                    <a href="{{ route('guru.hafalan.index') }}" class="px-6 py-3 text-sm font-bold text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">Batal</a>
                    <button type="submit" class="px-8 py-3 bg-emerald-700 text-white rounded-2xl text-sm font-bold hover:bg-emerald-800 transition-all shadow-lg shadow-emerald-200 dark:shadow-none">
                        Perbarui Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-tahfidz-layout>
