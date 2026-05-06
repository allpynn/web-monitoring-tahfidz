<x-tahfidz-layout>
    <x-slot name="header">
        Tambah Santri Bimbingan
    </x-slot>
    <x-slot name="subtitle">
        Daftarkan santri baru yang berada di bawah bimbingan Anda.
    </x-slot>

    <div class="max-w-3xl">
        <div class="mb-6">
            <a href="{{ route('guru.students.index') }}" class="text-sm font-bold text-emerald-700 dark:text-emerald-500 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Daftar Santri
            </a>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-2xl flex items-start gap-3 animate-fadeIn">
                <svg class="w-5 h-5 text-red-600 dark:text-red-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <h3 class="text-sm font-bold text-red-800 dark:text-red-300">Gagal Menyimpan Data!</h3>
                    <ul class="mt-1 list-disc list-inside text-xs text-red-700 dark:text-red-400 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form action="{{ route('guru.students.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <x-tahfidz.card title="Data Identitas">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-tahfidz.form-input name="name" label="Nama Lengkap" placeholder="Masukkan nama lengkap santri" required />
                    <x-tahfidz.form-input type="number" name="nis" label="NISN (10 Angka)" placeholder="Contoh: 0041234567" required />
                </div>
            </x-tahfidz.card>

            <x-tahfidz.card title="Relasi Orang Tua">
                <div class="w-full">
                    <label for="parent_ids" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Orang Tua / Wali</label>
                    <select name="parent_ids[]" id="parent_ids" multiple required class="block w-full px-4 py-3 border border-gray-100 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm dark:text-white transition-all shadow-sm">
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}" {{ in_array($parent->id, old('parent_ids', [])) ? 'selected' : '' }}>{{ $parent->name }} ({{ $parent->email }})</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Tekan Ctrl/Cmd + Klik untuk memilih lebih dari satu.</p>
                    @error('parent_ids') <p class="mt-1 text-xs text-red-600 font-bold italic">{{ $message }}</p> @enderror
                </div>
            </x-tahfidz.card>

            <x-tahfidz.card title="Target Hafalan">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-tahfidz.form-input type="number" name="target_juz" label="Target Hafalan (Juz)" placeholder="Contoh: 30" value="30" min="1" max="30" required />
                    <x-tahfidz.form-input type="date" name="target_date" label="Target Selesai" />
                </div>
            </x-tahfidz.card>

            <div class="flex justify-end pt-4">
                <button type="submit" class="px-8 py-4 bg-emerald-700 text-white rounded-2xl font-bold text-lg hover:bg-emerald-800 shadow-xl shadow-emerald-200 dark:shadow-none transition-all flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Data Santri
                </button>
            </div>
        </form>
    </div>
</x-tahfidz-layout>
