<x-tahfidz-layout>
    <x-slot name="header">
        Tambah Santri Bimbingan
    </x-slot>
    <x-slot name="subtitle">
        Daftarkan santri baru yang berada di bawah bimbingan Anda. Akun orang tua akan otomatis dibuat.
    </x-slot>

    <div class="max-w-3xl">
        <div class="mb-6">
            <a href="{{ route('guru.students.index') }}"
                class="text-sm font-bold text-emerald-700 dark:text-emerald-500 flex items-center gap-2 hover:gap-3 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar Santri
            </a>
        </div>

        @if($errors->any())
            <div
                class="mb-6 p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-2xl flex items-start gap-3 animate-fadeIn">
                <svg class="w-5 h-5 text-red-600 dark:text-red-400 mt-0.5 shrink-0" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
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

            <x-tahfidz.card title="Data Identitas Santri">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-tahfidz.form-input name="name" label="Nama Lengkap" placeholder="Masukkan nama lengkap santri"
                        required />
                    <x-tahfidz.form-input type="number" name="nis" label="NISN (10 Angka)"
                        placeholder="Contoh: 0041234567" required />
                </div>
            </x-tahfidz.card>

            <x-tahfidz.card title="Data Orang Tua / Wali">
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">
                </p>

                <div id="parent-container" class="space-y-4">
                    <div
                        class="parent-entry p-4 bg-gray-50 dark:bg-gray-900/40 rounded-2xl border border-gray-100 dark:border-gray-700 space-y-4">
                        <div class="flex items-center justify-between mb-1">
                            <span
                                class="text-xs font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Orang
                                Tua #1</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nama
                                    Lengkap</label>
                                <input type="text" name="parent_names[]" value="{{ old('parent_names.0') }}" required
                                    placeholder="Masukkan nama orang tua"
                                    class="block w-full px-4 py-3 border border-gray-100 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm dark:text-white transition-all shadow-sm placeholder-gray-400">
                                @error('parent_names.0') <p class="mt-1 text-xs text-red-600 font-bold italic">
                                {{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nomor
                                    Telepon / HP</label>
                                <input type="text" name="parent_phones[]" value="{{ old('parent_phones.0') }}" required
                                    placeholder="Contoh: 08123456789"
                                    class="block w-full px-4 py-3 border border-gray-100 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm dark:text-white transition-all shadow-sm placeholder-gray-400">
                                @error('parent_phones.0') <p class="mt-1 text-xs text-red-600 font-bold italic">
                                {{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                @error('parent_names') <p class="mt-2 text-xs text-red-600 font-bold italic">{{ $message }}</p>
                @enderror

                <button type="button" id="add-parent-btn"
                    class="mt-4 w-full py-3 border-2 border-dashed border-emerald-300 dark:border-emerald-700 text-emerald-600 dark:text-emerald-400 rounded-2xl text-sm font-bold hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Orang Tua Lain
                </button>
            </x-tahfidz.card>

            <x-tahfidz.card title="Target Hafalan">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-tahfidz.form-input type="number" name="target_juz" label="Target Hafalan (Juz)"
                        placeholder="Contoh: 30" value="30" min="1" max="30" required />
                    <x-tahfidz.form-input type="date" name="target_date" label="Target Selesai (Opsional)" />
                </div>
            </x-tahfidz.card>

            <div class="flex justify-end pt-4">
                <button type="submit"
                    class="px-8 py-4 bg-emerald-700 text-white rounded-2xl font-bold text-lg hover:bg-emerald-800 shadow-xl shadow-emerald-200 dark:shadow-none transition-all flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Data Santri
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            let parentCount = 1;

            document.getElementById('add-parent-btn').addEventListener('click', function () {
                parentCount++;
                const container = document.getElementById('parent-container');

                const entry = document.createElement('div');
                entry.className = 'parent-entry p-4 bg-gray-50 dark:bg-gray-900/40 rounded-2xl border border-gray-100 dark:border-gray-700 space-y-4 animate-fadeIn';
                entry.innerHTML = `
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-xs font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Orang Tua #${parentCount}</span>
                        <button type="button" onclick="this.closest('.parent-entry').remove(); renumberParents();" class="p-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-colors" title="Hapus">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nama Lengkap</label>
                            <input type="text" name="parent_names[]" required placeholder="Masukkan nama orang tua" class="block w-full px-4 py-3 border border-gray-100 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm dark:text-white transition-all shadow-sm placeholder-gray-400">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nomor Telepon / HP</label>
                            <input type="text" name="parent_phones[]" required placeholder="Contoh: 08123456789" class="block w-full px-4 py-3 border border-gray-100 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm dark:text-white transition-all shadow-sm placeholder-gray-400">
                        </div>
                    </div>
                `;

                container.appendChild(entry);
            });

            function renumberParents() {
                const entries = document.querySelectorAll('.parent-entry');
                parentCount = entries.length;
                entries.forEach((entry, index) => {
                    const label = entry.querySelector('span');
                    if (label) label.textContent = `Orang Tua #${index + 1}`;
                });
            }
        </script>
        <style>
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(-8px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .animate-fadeIn {
                animation: fadeIn 0.3s ease-out;
            }
        </style>
    @endpush
</x-tahfidz-layout>