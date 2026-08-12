<x-tahfidz-layout>
    <x-slot name="header">
        Tambah Santri Baru
    </x-slot>
    <x-slot name="subtitle">
        Daftarkan santri baru ke dalam sistem monitoring.
    </x-slot>

    <div class="max-w-3xl">
        <div class="mb-6">
            <a href="{{ route('admin.students.index') }}" class="text-sm font-bold text-emerald-700 dark:text-emerald-500 flex items-center gap-2 hover:gap-3 transition-all">
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

        <form action="{{ route('admin.students.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <x-tahfidz.card title="Data Identitas">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-tahfidz.form-input name="name" label="Nama Lengkap" placeholder="Masukkan nama lengkap santri" required />
                    <x-tahfidz.form-input type="number" name="nis" label="NISN (10 Angka)" placeholder="Contoh: 0041234567" required />
                </div>
                <div class="mt-4">
                    <label for="gender" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Jenis Kelamin</label>
                    <select id="gender" name="gender" required
                        class="block w-full px-4 py-3 border border-gray-100 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm dark:text-white transition-all shadow-sm">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Laki-laki" {{ old('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('gender')
                        <p class="mt-1 text-xs text-red-600 font-bold italic">{{ $message }}</p>
                    @enderror
                </div>
            </x-tahfidz.card>

            <x-tahfidz.card title="Data Orang Tua / Wali">

                {{-- === BAGIAN 1: PILIH ORANG TUA YANG SUDAH ADA === --}}
                <div class="mb-6">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-6 h-6 rounded-full bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center text-emerald-700 dark:text-emerald-400 text-xs font-black">1</div>
                        <p class="text-sm font-bold text-gray-700 dark:text-gray-300">Pilih Orang Tua yang Sudah Terdaftar</p>
                    </div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mb-3">Ketik nama atau nomor HP untuk mencari data orang tua yang sudah ada di sistem.</p>

                    <div class="relative mb-2">
                        <input type="text" id="parentSearch" autocomplete="off"
                            placeholder="🔍 Cari nama atau nomor HP orang tua..."
                            class="block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm dark:text-white transition-all shadow-sm placeholder-gray-400"
                            oninput="filterParents(this.value)">
                    </div>

                    <div id="parent-search-results" class="space-y-2 max-h-56 overflow-y-auto pr-1">
                        @foreach($parents as $p)
                        <label id="parent-item-{{ $p->id }}"
                            class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 cursor-pointer hover:bg-emerald-50 dark:hover:bg-emerald-900/20 hover:border-emerald-200 dark:hover:border-emerald-700 transition-all">
                            <input type="checkbox" name="existing_parent_ids[]" value="{{ $p->id }}"
                                class="w-4 h-4 accent-emerald-600 rounded"
                                {{ in_array($p->id, old('existing_parent_ids', [])) ? 'checked' : '' }}>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $p->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $p->phone ?? '-' }} &bull; {{ $p->email }}</div>
                            </div>
                            @if($p->gender === 'Laki-laki')
                                <span class="px-2 py-0.5 text-xs font-bold rounded bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400">L</span>
                            @elseif($p->gender === 'Perempuan')
                                <span class="px-2 py-0.5 text-xs font-bold rounded bg-pink-100 text-pink-700 dark:bg-pink-900/40 dark:text-pink-400">P</span>
                            @endif
                        </label>
                        @endforeach
                        @if($parents->isEmpty())
                        <p class="text-xs text-gray-400 italic px-2">Belum ada data orang tua di sistem.</p>
                        @endif
                    </div>
                </div>

                <div class="relative my-5">
                    <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-dashed border-gray-200 dark:border-gray-700"></div></div>
                    <div class="relative flex justify-center"><span class="bg-white dark:bg-gray-800 px-3 text-xs font-bold text-gray-400 uppercase tracking-widest">atau</span></div>
                </div>

                {{-- === BAGIAN 2: TAMBAH ORANG TUA BARU === --}}
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-6 h-6 rounded-full bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center text-blue-700 dark:text-blue-400 text-xs font-black">2</div>
                        <p class="text-sm font-bold text-gray-700 dark:text-gray-300">Tambah Orang Tua Baru</p>
                    </div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mb-4">Isi form di bawah jika orang tua belum terdaftar. Sistem akan otomatis membuat akun baru.</p>

                    <div id="parent-container" class="space-y-4">
                        <div class="parent-entry p-4 bg-gray-50 dark:bg-gray-900/40 rounded-2xl border border-gray-100 dark:border-gray-700 space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-black text-blue-600 dark:text-blue-400 uppercase tracking-wider">Orang Tua Baru #1</span>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nama Lengkap</label>
                                    <input type="text" name="parent_names[]" value="{{ old('parent_names.0') }}" placeholder="Masukkan nama lengkap" class="block w-full px-4 py-3 border border-gray-100 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm dark:text-white transition-all shadow-sm placeholder-gray-400">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Jenis Kelamin</label>
                                    <select name="parent_genders[]" class="block w-full px-4 py-3 border border-gray-100 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm dark:text-white transition-all shadow-sm">
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki" {{ old('parent_genders.0') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ old('parent_genders.0') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Email</label>
                                    <input type="email" name="parent_emails[]" value="{{ old('parent_emails.0') }}" placeholder="orangtua@example.com" class="block w-full px-4 py-3 border border-gray-100 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm dark:text-white transition-all shadow-sm placeholder-gray-400">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nomor Handphone</label>
                                    <input type="tel" inputmode="numeric" name="parent_phones[]" value="{{ old('parent_phones.0') }}" placeholder="Contoh: 08123456789" class="block w-full px-4 py-3 border border-gray-100 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm dark:text-white transition-all shadow-sm placeholder-gray-400">
                                    @error('parent_phones.0') <p class="mt-1 text-xs text-red-600 font-bold italic">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="add-parent-btn" class="mt-4 w-full py-3 border-2 border-dashed border-blue-300 dark:border-blue-700 text-blue-600 dark:text-blue-400 rounded-2xl text-sm font-bold hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Orang Tua Baru Lainnya
                    </button>
                </div>

            </x-tahfidz.card>


            <x-tahfidz.card title="Guru Pendamping">
                <div class="mb-3">
                    <p class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Pilih Guru Pendamping <span class="text-red-500">*</span></p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mb-3">Cari nama guru yang akan membimbing santri ini.</p>
                    
                    <div class="relative mb-3">
                        <input type="text" id="guruSearch" autocomplete="off"
                            placeholder="🔍 Cari nama guru..."
                            class="block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm dark:text-white transition-all shadow-sm placeholder-gray-400"
                            oninput="filterGurus(this.value)">
                    </div>

                    <div id="guru-search-results" class="space-y-2 max-h-48 overflow-y-auto pr-1">
                        @foreach($gurus as $guru)
                        <label class="guru-item flex items-center gap-3 p-3 rounded-xl border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 cursor-pointer hover:bg-emerald-50 dark:hover:bg-emerald-900/20 hover:border-emerald-200 dark:hover:border-emerald-700 transition-all">
                            <input type="radio" name="guru_id" value="{{ $guru->id }}" required
                                class="w-4 h-4 accent-emerald-600"
                                {{ old('guru_id') == $guru->id ? 'checked' : '' }}>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $guru->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 truncate">NIP: {{ $guru->nip ?? '-' }}</div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @error('guru_id') <p class="mt-2 text-xs text-red-600 font-bold italic">{{ $message }}</p> @enderror
                </div>
            </x-tahfidz.card>

            <x-tahfidz.card title="Target Hafalan">
                <div id="target-container" class="space-y-4">
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
                </div>

                <button type="button" id="add-target-btn" class="mt-4 w-full py-3 border-2 border-dashed border-emerald-300 dark:border-emerald-700 text-emerald-600 dark:text-emerald-400 rounded-2xl text-sm font-bold hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Target Hafalan Baru
                </button>
            </x-tahfidz.card>

            <div class="flex justify-center pt-8">
                <button type="submit" class="group px-12 py-4 bg-[#066447] text-white rounded-full font-bold text-xl hover:bg-[#044d36] shadow-2xl shadow-emerald-200/50 dark:shadow-none transition-all flex items-center gap-4 hover:scale-[1.02] active:scale-[0.98]">
                    <svg class="w-7 h-7 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Data Santri
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        let parentCount = 1;

        // ===== Tambah form orang tua baru =====
        document.getElementById('add-parent-btn').addEventListener('click', function () {
            parentCount++;
            const container = document.getElementById('parent-container');
            const entry = document.createElement('div');
            entry.className = 'parent-entry p-4 bg-gray-50 dark:bg-gray-900/40 rounded-2xl border border-gray-100 dark:border-gray-700 space-y-4 animate-fadeIn';
            entry.innerHTML = `
                <div class="flex items-center justify-between">
                    <span class="text-xs font-black text-blue-600 dark:text-blue-400 uppercase tracking-wider">Orang Tua Baru #${parentCount}</span>
                    <button type="button" onclick="this.closest('.parent-entry').remove(); renumberParents();"
                        class="p-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-colors" title="Hapus">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nama Lengkap</label>
                        <input type="text" name="parent_names[]" placeholder="Masukkan nama lengkap"
                            class="block w-full px-4 py-3 border border-gray-100 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm dark:text-white transition-all shadow-sm placeholder-gray-400">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Jenis Kelamin</label>
                        <select name="parent_genders[]"
                            class="block w-full px-4 py-3 border border-gray-100 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm dark:text-white transition-all shadow-sm">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Email</label>
                        <input type="email" name="parent_emails[]" placeholder="orangtua@example.com"
                            class="block w-full px-4 py-3 border border-gray-100 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm dark:text-white transition-all shadow-sm placeholder-gray-400">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nomor Handphone</label>
                        <input type="text" name="parent_phones[]" placeholder="Contoh: 08123456789"
                            class="block w-full px-4 py-3 border border-gray-100 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm dark:text-white transition-all shadow-sm placeholder-gray-400">
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
                if (label) label.textContent = `Orang Tua Baru #${index + 1}`;
            });
        }

        // ===== Filter / Search orang tua yang sudah ada =====
        function filterParents(query) {
            query = query.toLowerCase().trim();
            const items = document.querySelectorAll('#parent-search-results label');
            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = (!query || text.includes(query)) ? '' : 'none';
            });
        }
        // ===== Filter Guru =====
        function filterGurus(query) {
            query = query.toLowerCase().trim();
            const items = document.querySelectorAll('#guru-search-results .guru-item');
            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = (!query || text.includes(query)) ? '' : 'none';
            });
        }
        // ===== Tambah Target Hafalan =====
        document.getElementById('add-target-btn').addEventListener('click', function() {
            const container = document.getElementById('target-container');
            const row = document.createElement('div');
            row.className = 'target-row p-4 bg-gray-50 dark:bg-gray-900/40 rounded-2xl border border-gray-100 dark:border-gray-700 space-y-4 animate-fadeIn';
            row.innerHTML = `
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 font-mono tracking-wider italic uppercase">Target Baru</span>
                    <button type="button" onclick="this.closest('.target-row').remove();"
                        class="p-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-colors" title="Hapus">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </div>
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
            `;
            container.appendChild(row);
        });
    </script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn { animation: fadeIn 0.3s ease-out; }
        #parent-search-results::-webkit-scrollbar, #guru-search-results::-webkit-scrollbar { width: 4px; }
        #parent-search-results::-webkit-scrollbar-track, #guru-search-results::-webkit-scrollbar-track { background: transparent; }
        #parent-search-results::-webkit-scrollbar-thumb, #guru-search-results::-webkit-scrollbar-thumb { background: #d1fae5; border-radius: 4px; }
    </style>
    @endpush
</x-tahfidz-layout>
