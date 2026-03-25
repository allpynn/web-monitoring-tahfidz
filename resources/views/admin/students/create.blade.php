<x-tahfidz-layout>
    <x-slot name="header">
        Tambah Santri
    </x-slot>
    <x-slot name="subtitle">
        Masukkan data santri baru dan hubungkan dengan akun orang tua.
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="p-8 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-3xl shadow-sm relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-emerald-50 dark:bg-emerald-900/10 rounded-full blur-3xl"></div>
            
            <form action="{{ route('admin.students.store') }}" method="POST" class="relative z-10 space-y-6">
                @csrf
                <x-tahfidz.form-input label="Nama Lengkap Santri" name="name" type="text" placeholder="Masukkan nama santri" required autofocus />
                <x-tahfidz.form-input label="NIS (Nomor Induk Santri)" name="nis" type="text" placeholder="Contoh: 2024001" required />
                
                <div>
                    <label for="parent_id" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Orang Tua / Wali</label>
                    <select name="parent_id" id="parent_id" class="block w-full px-4 py-3 border border-gray-100 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm dark:text-white transition-all shadow-sm">
                        <option value="">Pilih Orang Tua</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->name }} ({{ $parent->phone }})</option>
                        @endforeach
                    </select>
                    @error('parent_id')
                        <p class="mt-1 text-xs text-red-600 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end pt-4 space-x-3">
                    <a href="{{ route('admin.students.index') }}" class="px-6 py-3 text-sm font-bold text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">Batal</a>
                    <button type="submit" class="px-8 py-3 bg-emerald-700 text-white rounded-2xl text-sm font-bold hover:bg-emerald-800 transition-all shadow-lg shadow-emerald-200 dark:shadow-none">
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-tahfidz-layout>
