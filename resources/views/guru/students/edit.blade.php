<x-tahfidz-layout>
    <x-slot name="header">
        Edit Data Santri
    </x-slot>
    <x-slot name="subtitle">
        Perbarui informasi bimbingan untuk <span class="text-emerald-700 dark:text-emerald-400 font-black">{{ $student->name }}</span>.
    </x-slot>

    <div class="max-w-4xl pb-20">
        <div class="mb-8 flex items-center justify-between">
            <a href="{{ route('guru.students.index') }}" class="group flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-emerald-600 transition-colors">
                <div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center group-hover:bg-emerald-100 dark:group-hover:bg-emerald-900/30 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </div>
                Kembali ke Daftar
            </a>
        </div>

        <form action="{{ route('guru.students.update', $student) }}" method="POST" class="space-y-8">
            @csrf
            @method('PATCH')
            
            <!-- SEKSI IDENTITAS -->
            <x-tahfidz.card title="Identitas Santri" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>'>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <x-tahfidz.form-input name="name" label="Nama Lengkap" placeholder="Nama lengkap santri" :value="old('name', $student->name)" required />
                    <x-tahfidz.form-input name="nis" label="NISN (10 Digit)" placeholder="Masukkan 10 digit angka" :value="old('nis', $student->nis)" maxlength="10" required />
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Jenis Kelamin</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative flex items-center justify-center p-3 border rounded-2xl cursor-pointer transition-all {{ old('gender', $student->gender) === 'Laki-laki' ? 'bg-emerald-50 border-emerald-500 text-emerald-700' : 'bg-white dark:bg-gray-800 border-gray-100 dark:border-gray-700 text-gray-600' }}">
                                <input type="radio" name="gender" value="Laki-laki" class="hidden" {{ old('gender', $student->gender) === 'Laki-laki' ? 'checked' : '' }}>
                                <span class="text-sm font-bold">Laki-laki</span>
                            </label>
                            <label class="relative flex items-center justify-center p-3 border rounded-2xl cursor-pointer transition-all {{ old('gender', $student->gender) === 'Perempuan' ? 'bg-emerald-50 border-emerald-500 text-emerald-700' : 'bg-white dark:bg-gray-800 border-gray-100 dark:border-gray-700 text-gray-600' }}">
                                <input type="radio" name="gender" value="Perempuan" class="hidden" {{ old('gender', $student->gender) === 'Perempuan' ? 'checked' : '' }}>
                                <span class="text-sm font-bold">Perempuan</span>
                            </label>
                        </div>
                    </div>
                </div>
            </x-tahfidz.card>

            <!-- SEKSI ORANG TUA (READ ONLY UNTUK GURU) -->
            <x-tahfidz.card title="Data Orang Tua / Wali" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>'>
                <div class="space-y-4">
                    <p class="text-xs text-gray-500 italic mb-4 bg-blue-50 dark:bg-blue-900/20 p-3 rounded-xl border border-blue-100 dark:border-blue-800 text-center">
                        🔒 Data orang tua hanya dapat diubah oleh Admin. Hubungi Admin jika ada kesalahan data.
                    </p>
                    @forelse($student->parents as $parent)
                        <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-900/40 rounded-2xl border border-gray-100 dark:border-gray-700">
                            <div class="w-12 h-12 rounded-xl bg-white dark:bg-gray-800 flex items-center justify-center text-emerald-600 font-bold shadow-sm">
                                {{ substr($parent->name, 0, 1) }}
                            </div>
                            <div class="flex-1">
                                <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $parent->name }}</div>
                                <div class="text-xs text-gray-500">{{ $parent->phone ?? 'Tidak ada nomor HP' }} • {{ $parent->email }}</div>
                            </div>
                            <div class="px-3 py-1 bg-gray-200 dark:bg-gray-700 rounded-lg text-[10px] font-black uppercase tracking-widest text-gray-600 dark:text-gray-400">
                                TERHUBUNG
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center border-2 border-dashed border-gray-100 dark:border-gray-800 rounded-3xl">
                            <p class="text-sm text-gray-400">Belum ada data orang tua yang terhubung.</p>
                        </div>
                    @endforelse
                </div>
            </x-tahfidz.card>

            <!-- SEKSI TARGET HAFALAN (DINAMIS SEPERTI ADMIN) -->
            <x-tahfidz.card title="Target Hafalan" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>'>
                <div id="target-container" class="space-y-4">
                    @forelse($student->targets as $index => $target)
                    <div class="target-row p-4 {{ in_array($target->target_juz, $student->completed_juz) ? 'bg-emerald-50/50 dark:bg-emerald-900/10' : 'bg-gray-50 dark:bg-gray-900/40' }} rounded-2xl border border-gray-100 dark:border-gray-700 space-y-4 relative">
                        <div class="flex items-center justify-between">
                            @php $isAchieved = in_array($target->target_juz, $student->completed_juz); @endphp
                            <span class="text-xs font-black {{ $isAchieved ? 'text-emerald-600' : 'text-blue-600' }} uppercase tracking-widest">
                                TARGET #{{ $index + 1 }} {{ $isAchieved ? '(TERVERIFIKASI TERCAPAI)' : '' }}
                            </span>
                            <button type="button" onclick="this.closest('.target-row').remove();"
                                class="p-1.5 text-red-500 hover:bg-red-50 rounded-xl transition-colors" title="Hapus">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Target Hafalan (Juz)</label>
                                <input type="number" name="target_juz[]" value="{{ $target->target_juz }}" min="1" max="30"
                                    class="block w-full px-4 py-3 border border-gray-100 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 text-sm dark:text-white transition-all shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Target Selesai</label>
                                <input type="date" name="target_date[]" value="{{ $target->target_date ? $target->target_date : '' }}"
                                    class="block w-full px-4 py-3 border border-gray-100 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 text-sm dark:text-white transition-all shadow-sm">
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="target-row p-4 bg-gray-50 dark:bg-gray-900/40 rounded-2xl border border-gray-100 dark:border-gray-700 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Target Hafalan (Juz)</label>
                                <input type="number" name="target_juz[]" placeholder="Contoh: 30" min="1" max="30"
                                    class="block w-full px-4 py-3 border border-gray-100 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 text-sm dark:text-white transition-all shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Target Selesai</label>
                                <input type="date" name="target_date[]"
                                    class="block w-full px-4 py-3 border border-gray-100 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 text-sm dark:text-white transition-all shadow-sm">
                            </div>
                        </div>
                    </div>
                    @endforelse
                </div>

                <button type="button" id="add-target" class="mt-6 w-full py-4 border-2 border-dashed border-emerald-200 dark:border-emerald-900/50 rounded-2xl text-emerald-600 dark:text-emerald-400 font-bold hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Target Hafalan Baru
                </button>
            </x-tahfidz.card>

            <div class="flex flex-col sm:flex-row gap-4 pt-6">
                <button type="submit" class="flex-1 px-8 py-4 bg-emerald-700 text-white rounded-2xl font-black text-lg hover:bg-emerald-800 shadow-xl shadow-emerald-200 dark:shadow-none transition-all flex items-center justify-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    SIMPAN PERUBAHAN DATA
                </button>
                <a href="{{ route('guru.students.index') }}" class="px-8 py-4 bg-white dark:bg-gray-800 text-gray-500 border border-gray-100 dark:border-gray-700 rounded-2xl font-bold text-center hover:bg-gray-50 transition-all">
                    Batal
                </a>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        // Logika Tab Jenis Kelamin
        document.querySelectorAll('input[name="gender"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('input[name="gender"]').forEach(r => {
                    r.parentElement.classList.remove('bg-emerald-50', 'border-emerald-500', 'text-emerald-700');
                    r.parentElement.classList.add('bg-white', 'dark:bg-gray-800', 'border-gray-100', 'dark:border-gray-700', 'text-gray-600');
                });
                if (this.checked) {
                    this.parentElement.classList.add('bg-emerald-50', 'border-emerald-500', 'text-emerald-700');
                    this.parentElement.classList.remove('bg-white', 'dark:bg-gray-800', 'border-gray-100', 'dark:border-gray-700', 'text-gray-600');
                }
            });
        });

        // Logika Tambah Target Dinamis
        document.getElementById('add-target').addEventListener('click', function() {
            const container = document.getElementById('target-container');
            const rowCount = container.querySelectorAll('.target-row').length;
            const row = document.createElement('div');
            row.className = 'target-row p-4 bg-gray-50 dark:bg-gray-900/40 rounded-2xl border border-gray-100 dark:border-gray-700 space-y-4 relative animate-in fade-in slide-in-from-top-4 duration-300';
            
            row.innerHTML = `
                <div class="flex items-center justify-between">
                    <span class="text-xs font-black text-blue-600 uppercase tracking-widest">TARGET #${rowCount + 1} (BARU)</span>
                    <button type="button" onclick="this.closest('.target-row').remove();" 
                        class="p-1.5 text-red-500 hover:bg-red-50 rounded-xl transition-colors" title="Hapus">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Target Hafalan (Juz)</label>
                        <input type="number" name="target_juz[]" placeholder="Contoh: 30" min="1" max="30" 
                            class="block w-full px-4 py-3 border border-gray-100 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 text-sm dark:text-white transition-all shadow-sm focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Target Selesai</label>
                        <input type="date" name="target_date[]" 
                            class="block w-full px-4 py-3 border border-gray-100 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 text-sm dark:text-white transition-all shadow-sm focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>
            `;
            container.appendChild(row);
        });
    </script>
    @endpush
</x-tahfidz-layout>
