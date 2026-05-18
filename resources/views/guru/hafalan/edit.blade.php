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

        <form id="hafalan-form" action="{{ route('guru.hafalan.update', $hafalan) }}" method="POST" class="space-y-6">
            @csrf
            @method('PATCH')
            
            <x-tahfidz.card title="Informasi Santri">
                <div class="space-y-4">
                    <div class="w-full">
                        <label for="tanggal" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Tanggal Setor</label>
                        <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', \Carbon\Carbon::parse($hafalan->tanggal ?? $hafalan->created_at)->format('Y-m-d')) }}" required class="block w-full px-4 py-3 border border-gray-100 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm dark:text-white transition-all shadow-sm">
                        @error('tanggal') <p class="mt-1 text-xs text-red-600 font-bold italic">{{ $message }}</p> @enderror
                    </div>
                    <div class="w-full">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nama Santri</label>
                        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-bold text-gray-900 dark:text-white">
                            {{ $hafalan->student->name }} ({{ $hafalan->student->nis }})
                        </div>
                        <input type="hidden" name="student_id" id="student_id" value="{{ $hafalan->student_id }}">
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
                            <x-tahfidz.form-input type="number" name="juz" id="juz" label="Juz" placeholder="Contoh: 30" :value="old('juz', $hafalan->juz)" min="1" max="30" />
                            <div class="w-full">
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nama Surah</label>
                                <div class="relative" id="surah-search-wrapper">
                                    <input type="text" id="surah-search-input" autocomplete="off"
                                        placeholder="Ketik nama surah..."
                                        class="block w-full px-4 py-3 border border-gray-100 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm dark:text-white transition-all shadow-sm">
                                    <input type="hidden" name="surah" id="surah" value="{{ old('surah', $hafalan->surah) }}">
                                    <ul id="surah-dropdown" class="hidden absolute z-20 w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-2xl shadow-xl mt-1 max-h-52 overflow-y-auto">
                                    </ul>
                                </div>
                                @error('surah') <p class="mt-1 text-xs text-red-600 font-bold italic">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        @php
                            $parts = explode('-', $hafalan->ayat);
                            $dari = (int) trim($parts[0]);
                            $sampai = isset($parts[1]) ? (int) trim($parts[1]) : $dari;
                        @endphp
                        <div class="grid grid-cols-2 gap-6">
                            <x-tahfidz.form-input type="number" name="ayat_dari" label="Dari Ayat" placeholder="1" :value="old('ayat_dari', $dari)" min="1" required />
                            <x-tahfidz.form-input type="number" name="ayat_sampai" label="Sampai Ayat" placeholder="7" :value="old('ayat_sampai', $sampai)" min="1" required />
                        </div>
                        
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
        const surahsData = @json($surahsList->values());

        // ── Generic Searchable Dropdown Builder ───────────────────────
        function buildSearchable(inputId, dropdownId, hiddenId, items, displayFn, valueFn, onSelect) {
            const input = document.getElementById(inputId);
            const dropdown = document.getElementById(dropdownId);
            const hidden = document.getElementById(hiddenId);

            // Restore initial label
            if (hidden.value) {
                const found = items.find(i => String(valueFn(i)) === String(hidden.value));
                if (found) input.value = displayFn(found);
            }

            input.addEventListener('input', () => {
                const q = input.value.toLowerCase();
                const filtered = q.length === 0 ? items : items.filter(i => displayFn(i).toLowerCase().includes(q));
                renderDropdown(filtered);
            });

            input.addEventListener('focus', () => {
                const q = input.value.toLowerCase();
                const filtered = q.length === 0 ? items : items.filter(i => displayFn(i).toLowerCase().includes(q));
                renderDropdown(filtered);
            });

            document.addEventListener('click', (e) => {
                if (!input.closest('[id$="-search-wrapper"]').contains(e.target)) {
                    dropdown.classList.add('hidden');
                }
            });

            function renderDropdown(filtered) {
                dropdown.innerHTML = '';
                if (!filtered.length) {
                    dropdown.innerHTML = '<li class="px-4 py-3 text-sm text-gray-400 italic">Tidak ditemukan.</li>';
                } else {
                    filtered.slice(0, 50).forEach(item => {
                        const li = document.createElement('li');
                        li.textContent = displayFn(item);
                        li.className = 'px-4 py-3 text-sm text-gray-700 dark:text-gray-200 cursor-pointer hover:bg-emerald-50 dark:hover:bg-emerald-900/30 first:rounded-t-2xl last:rounded-b-2xl transition-colors';
                        li.addEventListener('mousedown', (e) => {
                            e.preventDefault();
                            input.value = displayFn(item);
                            hidden.value = valueFn(item);
                            dropdown.classList.add('hidden');
                            if (onSelect) onSelect(item);
                        });
                        dropdown.appendChild(li);
                    });
                }
                dropdown.classList.remove('hidden');
            }
        }

        // -- Init Surah Search --
        buildSearchable(
            'surah-search-input', 'surah-dropdown', 'surah',
            surahsData,
            s => `${s.nomor}. ${s.nama_latin}`,
            s => s.nama_latin,
            (surah) => {
                updateSurahContext(surah.nama_latin);
            }
        );

        // -- Form Submission Guard --
        document.getElementById('hafalan-form').addEventListener('submit', function(e) {
            const errorMsg = document.getElementById('ayat-error');
            const isPresent = document.querySelector('input[name="is_present"]:checked').value === '1';
            
            if (isPresent && errorMsg) {
                e.preventDefault();
                alert('Silakan perbaiki kesalahan input ayat sebelum menyimpan.');
                return false;
            }

            const surah = document.getElementById('surah').value;
            if (isPresent && !surah) {
                e.preventDefault();
                alert('Silakan pilih nama surah terlebih dahulu.');
                return false;
            }
        });

        const inputDari = document.getElementsByName('ayat_dari')[0];
        const inputSampai = document.getElementsByName('ayat_sampai')[0];
        const juzInput = document.getElementById('juz');

        function updateSurahContext(surahName = null) {
            const val = surahName || document.getElementById('surah').value;
            if (!val) return;

            const surah = surahsData.find(s => s.nama_latin === val);
            if (surah) {
                juzInput.value = surah.juz_awal;
                [inputDari, inputSampai].forEach(el => {
                    el.max = surah.jumlah_ayat;
                    el.dataset.maxAyat = surah.jumlah_ayat;
                });

                const existingHint = document.getElementById('ayat-hint');
                if (existingHint) existingHint.remove();
                
                const hint = document.createElement('p');
                hint.id = 'ayat-hint';
                hint.className = 'mt-1 text-[10px] font-bold text-emerald-600 uppercase tracking-tight';
                hint.textContent = `Surah ${surah.nama_latin} memiliki ${surah.jumlah_ayat} ayat.`;
                inputSampai.parentElement.parentElement.parentElement.appendChild(hint);
                
                // Re-validate current verses with new max
                validateAyat();
            }
        }

        function validateAyat() {
            const max = parseInt(inputDari.dataset.maxAyat);
            if (!max) return;

            const dari = parseInt(inputDari.value);
            const sampai = parseInt(inputSampai.value);
            let hasError = false;
            let msg = '';

            if (dari > max || sampai > max) {
                hasError = true;
                msg = `⚠️ Maksimal surah ini adalah ${max} ayat.`;
            } else if (sampai < dari) {
                hasError = true;
                msg = `⚠️ 'Sampai Ayat' tidak boleh lebih kecil dari 'Dari Ayat'.`;
            }

            const errorId = 'ayat-error';
            let errorMsg = document.getElementById(errorId);

            if (hasError) {
                [inputDari, inputSampai].forEach(el => el.classList.add('border-red-500', 'ring-red-500'));
                if (!errorMsg) {
                    errorMsg = document.createElement('p');
                    errorMsg.id = errorId;
                    errorMsg.className = 'mt-1 text-[11px] font-bold text-red-600 italic';
                    inputSampai.parentElement.parentElement.parentElement.appendChild(errorMsg);
                }
                errorMsg.textContent = msg;
            } else {
                [inputDari, inputSampai].forEach(el => el.classList.remove('border-red-500', 'ring-red-500'));
                if (errorMsg) errorMsg.remove();
            }
        }

        inputDari.addEventListener('input', validateAyat);
        inputSampai.addEventListener('input', validateAyat);

        // Initial trigger to load max ayat based on existing surah
        if (document.getElementById('surah').value) updateSurahContext();

        function toggleInputs(isPresent) {
            const inputs = document.getElementById('hafalan-inputs');
            inputs.classList.toggle('hidden', !isPresent);
        }
    </script>
    @endpush
</x-tahfidz-layout>
