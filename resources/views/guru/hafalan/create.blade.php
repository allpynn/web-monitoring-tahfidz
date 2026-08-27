<x-tahfidz-layout>
    <x-slot name="header">
        Input Hafalan Santri
    </x-slot>
    <x-slot name="subtitle">
        Catat kemajuan hafalan santri yang diampu tahun ajaran <span class="font-bold text-emerald-600">{{ $currentAcademicYear }}</span>.
    </x-slot>

    @php
        $studentsData = $students->map(function ($s) {
            $lastMem = $s->memorizations->first();
            $lastSetoran = null;
            if ($lastMem) {
                $range = trim($lastMem->ayat ?? '');
                $start = 0;
                $end = 0;
                if (str_contains($range, '-') || str_contains($range, '–')) {
                    $parts = preg_split('/[-–]/', $range);
                    $start = (int) ($parts[0] ?? 0);
                    $end = (int) ($parts[1] ?? 0);
                } else {
                    $start = (int) $range;
                    $end = (int) $range;
                }

                $lastSetoran = [
                    'id' => $lastMem->id,
                    'tanggal' => \Carbon\Carbon::parse($lastMem->tanggal)->translatedFormat('d M Y'),
                    'juz' => $lastMem->juz,
                    'surah' => $lastMem->surah,
                    'ayat' => $lastMem->ayat,
                    'start_ayat' => $start,
                    'end_ayat' => $end,
                    'status' => $lastMem->status,
                    'notes' => $lastMem->notes,
                ];
            }

            $surahHistory = [];
            foreach ($s->memorizations as $m) {
                if (!$m->surah) continue;
                $sKey = str_replace(["'", "-", " ", "‘", "’", "`", "´"], "", strtolower($m->surah));
                
                $range = trim($m->ayat ?? '');
                $start = 0;
                $end = 0;
                if (str_contains($range, '-') || str_contains($range, '–')) {
                    $parts = preg_split('/[-–]/', $range);
                    $start = (int) ($parts[0] ?? 0);
                    $end = (int) ($parts[1] ?? 0);
                } else {
                    $start = (int) $range;
                    $end = (int) $range;
                }

                if (!isset($surahHistory[$sKey])) {
                    $surahHistory[$sKey] = [
                        'surah' => $m->surah,
                        'max_ayat' => $end,
                        'latest_range' => $m->ayat,
                        'latest_date' => \Carbon\Carbon::parse($m->tanggal)->translatedFormat('d M Y'),
                        'latest_status' => $m->status,
                    ];
                } else {
                    if ($end > $surahHistory[$sKey]['max_ayat']) {
                        $surahHistory[$sKey]['max_ayat'] = $end;
                    }
                }
            }

            return [
                'id' => $s->id,
                'name' => $s->name,
                'nis' => $s->nis,
                'last_setoran' => $lastSetoran,
                'surah_history' => $surahHistory,
            ];
        })->values();
    @endphp

    <div class="max-w-2xl">
        <div class="mb-6">
            <a href="{{ route('guru.hafalan.index') }}"
                class="text-sm font-bold text-emerald-700 dark:text-emerald-500 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Riwayat
            </a>
        </div>

        <form id="hafalan-form" action="{{ route('guru.hafalan.store') }}" method="POST" class="space-y-6">
            @csrf

            <x-tahfidz.card title="Informasi Santri">
                <div class="space-y-4">
                    <div class="w-full">
                        <label for="tanggal"
                            class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Tanggal Setor</label>
                        <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}"
                            required
                            class="block w-full px-4 py-3 border border-gray-100 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm dark:text-white transition-all shadow-sm">
                        @error('tanggal') <p class="mt-1 text-xs text-red-600 font-bold italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="w-full">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Pilih
                            Santri</label>
                        <div class="relative" id="student-search-wrapper">
                            <input type="text" id="student-search-input" autocomplete="off"
                                placeholder="Ketik nama santri..." required
                                class="block w-full px-4 py-3 border border-gray-100 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm dark:text-white transition-all shadow-sm">
                            <input type="hidden" name="student_id" id="student_id" value="{{ old('student_id', $selectedStudentId ?? '') }}"
                                required>
                            <ul id="student-dropdown"
                                class="hidden absolute z-20 w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-2xl shadow-xl mt-1 max-h-52 overflow-y-auto">
                            </ul>
                        </div>
                        @error('student_id') <p class="mt-1 text-xs text-red-600 font-bold italic">{{ $message }}</p>
                        @enderror

                        <!-- CARD HINT SETORAN TERAKHIR SANTRI -->
                        <div id="last-setoran-box" class="hidden mt-4 p-4 rounded-2xl bg-emerald-50/70 dark:bg-emerald-950/40 border border-emerald-200/80 dark:border-emerald-800/80 space-y-3 transition-all">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-black text-emerald-800 dark:text-emerald-300 uppercase tracking-wider flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    Setoran Terakhir Santri
                                </span>
                                <span id="last-setoran-date" class="text-[11px] font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-900/60 px-2.5 py-0.5 rounded-full border border-emerald-200 dark:border-emerald-800"></span>
                            </div>

                            <div id="last-setoran-content" class="text-xs text-gray-700 dark:text-gray-200 space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <span class="font-black text-emerald-900 dark:text-emerald-200" id="last-setoran-surah-juz"></span>
                                        <span class="text-gray-400">•</span>
                                        <span class="font-bold text-gray-700 dark:text-gray-300" id="last-setoran-ayat"></span>
                                    </div>
                                    <span id="last-setoran-status" class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider border"></span>
                                </div>
                                <div id="last-setoran-notes" class="text-[11px] italic text-gray-500 dark:text-gray-400 bg-white/60 dark:bg-gray-800/60 p-2 rounded-xl border border-emerald-100 dark:border-emerald-900/50 hidden"></div>
                            </div>

                            <div id="last-setoran-hint-container" class="pt-3 border-t border-emerald-200/60 dark:border-emerald-800/60 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div class="text-xs font-semibold text-emerald-950 dark:text-emerald-100 flex items-start gap-2">
                                    <span class="text-base flex-shrink-0">💡</span>
                                    <span id="last-setoran-hint-text" class="leading-relaxed"></span>
                                </div>
                                <button type="button" id="btn-auto-fill-next" onclick="autoFillNextSetoran()"
                                    class="hidden px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white rounded-xl text-xs font-extrabold transition-all shadow-md shadow-emerald-200 dark:shadow-none flex items-center justify-center gap-1.5 flex-shrink-0 self-end sm:self-auto">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    Isi Otomatis Lanjutan
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="w-full">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Status
                            Kehadiran</label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio" name="is_present" value="1"
                                    class="w-5 h-5 text-emerald-600 focus:ring-emerald-500" {{ old('is_present', '1') == '1' ? 'checked' : '' }} onchange="toggleInputs(true)">
                                <span
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-emerald-600 transition-colors">Hadir</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio" name="is_present" value="0"
                                    class="w-5 h-5 text-red-600 focus:ring-red-500" {{ old('is_present') == '0' ? 'checked' : '' }} onchange="toggleInputs(false)">
                                <span
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-red-600 transition-colors">Absen
                                    / Tidak Setor</span>
                            </label>
                        </div>
                    </div>
                </div>
            </x-tahfidz.card>

            <div id="hafalan-inputs" class="{{ old('is_present', '1') == '0' ? 'hidden' : '' }}">
                <x-tahfidz.card title="Detail Hafalan">
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-6">
                            <x-tahfidz.form-input type="number" name="juz" id="juz" label="Juz"
                                placeholder="Otomatis terisi" :value="old('juz')" min="1" max="30" />

                            <div class="w-full">
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nama
                                    Surah</label>
                                <div class="relative" id="surah-search-wrapper">
                                    <input type="text" id="surah-search-input" autocomplete="off"
                                        placeholder="Ketik nama surah..."
                                        class="block w-full px-4 py-3 border border-gray-100 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm dark:text-white transition-all shadow-sm">
                                    <input type="hidden" name="surah" id="surah" value="{{ old('surah') }}">
                                    <ul id="surah-dropdown"
                                        class="hidden absolute z-20 w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-2xl shadow-xl mt-1 max-h-52 overflow-y-auto">
                                    </ul>
                                </div>
                                @error('surah') <p class="mt-1 text-xs text-red-600 font-bold italic">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-6">
                            <x-tahfidz.form-input type="number" name="ayat_dari" label="Dari Ayat" placeholder="1"
                                min="1" :value="old('ayat_dari')" />
                            <x-tahfidz.form-input type="number" name="ayat_sampai" label="Sampai Ayat" placeholder="7"
                                min="1" :value="old('ayat_sampai')" />
                        </div>
                        <div id="ayat-info-wrapper" class="mt-1 space-y-1.5">
                            <p id="ayat-hint" class="hidden text-xs font-bold text-emerald-600 dark:text-emerald-400">
                            </p>
                            <p id="ayat-gap-warning" class="hidden text-xs font-bold p-2.5 rounded-xl border transition-all">
                            </p>
                        </div>

                        <div class="w-full">
                            <label for="status"
                                class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Evaluasi
                                Status</label>
                            <select name="status" id="status"
                                class="block w-full px-4 py-3 border border-gray-100 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm dark:text-white transition-all shadow-sm">
                                <option value="Lancar" {{ old('status') == 'Lancar' ? 'selected' : '' }}>Lancar ✨</option>
                                <option value="Perlu Perbaikan" {{ old('status') == 'Perlu Perbaikan' ? 'selected' : '' }}>Perlu Perbaikan ⚠️</option>
                            </select>
                        </div>
                    </div>
                </x-tahfidz.card>
            </div>

            <x-tahfidz.card title="Catatan Guru">
                <textarea name="notes" rows="3"
                    class="block w-full px-4 py-3 border border-gray-100 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm dark:text-white transition-all shadow-sm placeholder-gray-400"
                    placeholder="Berikan catatan atau motivasi untuk santri...">{{ old('notes') }}</textarea>
                @error('notes') <p class="mt-1 text-xs text-red-600 font-bold italic">{{ $message }}</p> @enderror
            </x-tahfidz.card>

            <div class="flex justify-end">
                <button type="submit"
                    class="px-8 py-4 bg-emerald-700 text-white rounded-2xl font-bold text-lg hover:bg-emerald-800 shadow-xl shadow-emerald-200 dark:shadow-none transition-all">
                    Simpan Catatan
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            const surahsData = @json($surahsList->values());
            const studentsData = @json($studentsData);
            const initialSelectedId = @json($selectedStudentId ?? null);

            const inputDari = document.getElementsByName('ayat_dari')[0];
            const inputSampai = document.getElementsByName('ayat_sampai')[0];
            const juzInput = document.getElementById('juz');

            let currentRecommendation = null;
            let currentSelectedStudent = null;

            function normalizeSurahKey(str) {
                if (!str) return '';
                return str.toLowerCase().replace(/['"`´‘’\-\s]/g, '');
            }

            document.getElementById('hafalan-form').addEventListener('submit', function (e) {
                const isPresent = document.querySelector('input[name="is_present"]:checked').value === '1';
                const studentId = document.getElementById('student_id').value;

                if (!studentId) {
                    e.preventDefault();
                    alert('Silakan pilih nama santri terlebih dahulu.');
                    document.getElementById('student-search-input').focus();
                    return false;
                }

                const surah = document.getElementById('surah').value;
                if (isPresent && !surah) {
                    e.preventDefault();
                    alert('Silakan pilih nama surah terlebih dahulu.');
                    return false;
                }
            });

            function buildSearchable(inputId, dropdownId, hiddenId, items, displayFn, valueFn, onSelect) {
                const input = document.getElementById(inputId);
                const dropdown = document.getElementById(dropdownId);
                const hidden = document.getElementById(hiddenId);

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

            buildSearchable(
                'student-search-input', 'student-dropdown', 'student_id',
                studentsData,
                s => `${s.name} (${s.nis})`,
                s => s.id,
                (student) => {
                    onSelectStudent(student);
                }
            );

            buildSearchable(
                'surah-search-input', 'surah-dropdown', 'surah',
                surahsData,
                s => `${s.nomor}. ${s.nama_latin}`,
                s => s.nama_latin,
                (surah) => {
                    updateSurahContext(surah.nama_latin);
                    checkAyatGapWarning();
                }
            );

            function onSelectStudent(student) {
                currentSelectedStudent = student;
                renderLastSetoranBox(student);
                checkAyatGapWarning();
            }

            function renderLastSetoranBox(student, activeSurahName = null) {
                const box = document.getElementById('last-setoran-box');
                const dateEl = document.getElementById('last-setoran-date');
                const surahJuzEl = document.getElementById('last-setoran-surah-juz');
                const ayatEl = document.getElementById('last-setoran-ayat');
                const statusEl = document.getElementById('last-setoran-status');
                const notesEl = document.getElementById('last-setoran-notes');
                const hintTextEl = document.getElementById('last-setoran-hint-text');
                const autoFillBtn = document.getElementById('btn-auto-fill-next');

                if (!student) {
                    box.classList.add('hidden');
                    currentRecommendation = null;
                    return;
                }

                box.classList.remove('hidden');

                if (!student.last_setoran) {
                    dateEl.textContent = 'Belum ada setoran';
                    dateEl.className = 'text-[11px] font-bold text-gray-500 bg-gray-100 dark:bg-gray-800 px-2.5 py-0.5 rounded-full border border-gray-200 dark:border-gray-700';
                    surahJuzEl.textContent = 'Belum Ada Riwayat Hafalan';
                    ayatEl.textContent = '';
                    statusEl.classList.add('hidden');
                    notesEl.classList.add('hidden');
                    hintTextEl.innerHTML = 'Santri ini belum memiliki riwayat setoran hafalan. Silakan input setoran perdana.';
                    autoFillBtn.classList.add('hidden');
                    currentRecommendation = null;
                    return;
                }

                const ls = student.last_setoran;
                dateEl.textContent = ls.tanggal;
                dateEl.className = 'text-[11px] font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-900/60 px-2.5 py-0.5 rounded-full border border-emerald-200 dark:border-emerald-800';

                surahJuzEl.textContent = `Juz ${ls.juz} • Surah ${ls.surah}`;
                ayatEl.textContent = `(Ayat ${ls.ayat})`;

                statusEl.classList.remove('hidden');
                statusEl.textContent = ls.status;
                if (ls.status === 'Lancar') {
                    statusEl.className = 'px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-700';
                } else {
                    statusEl.className = 'px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300 border border-amber-300 dark:border-amber-700';
                }

                if (ls.notes) {
                    notesEl.textContent = `Catatan terakhir: "${ls.notes}"`;
                    notesEl.classList.remove('hidden');
                } else {
                    notesEl.classList.add('hidden');
                }

                // Kalkulasi rekomendasi berdasarkan Surah aktif yang dipilih (jika guru memilih surah tertentu) ATAU setoran paling akhir secara keseluruhan
                let targetSurahName = activeSurahName || ls.surah;
                let targetKey = normalizeSurahKey(targetSurahName);
                let surahHistoryItem = student.surah_history ? student.surah_history[targetKey] : null;
                let targetSurahObj = surahsData.find(s => normalizeSurahKey(s.nama_latin) === targetKey);

                if (activeSurahName && targetSurahObj) {
                    // Guru memilih surah spesifik (misal Al-Baqarah)
                    if (surahHistoryItem) {
                        const maxAyat = parseInt(surahHistoryItem.max_ayat);
                        const totalSurahAyat = parseInt(targetSurahObj.jumlah_ayat);

                        if (maxAyat < totalSurahAyat) {
                            const nextAyat = maxAyat + 1;
                            hintTextEl.innerHTML = `📌 <b>Surah ${targetSurahObj.nama_latin}</b>: Pernah disetorkan hingga <b>Ayat ${maxAyat}</b> (terakhir ${surahHistoryItem.latest_date}). Rekomendasi kelanjutan: <b>Ayat ${nextAyat}</b>.`;
                            currentRecommendation = {
                                surah: targetSurahObj,
                                juz: targetSurahObj.juz_awal,
                                dari_ayat: nextAyat
                            };
                            autoFillBtn.classList.remove('hidden');
                        } else {
                            hintTextEl.innerHTML = `📌 <b>Surah ${targetSurahObj.nama_latin}</b>: Telah selesai disetorkan seluruhnya (hingga Ayat ${totalSurahAyat}).`;
                            currentRecommendation = null;
                            autoFillBtn.classList.add('hidden');
                        }
                    } else {
                        // Belum pernah disetorkan
                        hintTextEl.innerHTML = `📌 <b>Surah ${targetSurahObj.nama_latin}</b>: Belum pernah disetorkan sebelumnya. Rekomendasi kelanjutan dimulai dari <b>Ayat 1</b>.`;
                        currentRecommendation = {
                            surah: targetSurahObj,
                            juz: targetSurahObj.juz_awal,
                            dari_ayat: 1
                        };
                        autoFillBtn.classList.remove('hidden');
                    }
                } else {
                    // Default rekomendasi dari setoran terakhir secara keseluruhan
                    const currentSurah = surahsData.find(s => normalizeSurahKey(s.nama_latin) === normalizeSurahKey(ls.surah));
                    let nextSurah = null;
                    let nextJuz = ls.juz;
                    let nextDariAyat = 1;

                    if (currentSurah) {
                        const totalAyat = parseInt(currentSurah.jumlah_ayat);
                        const endAyat = parseInt(ls.end_ayat);

                        if (endAyat < totalAyat) {
                            nextSurah = currentSurah;
                            nextJuz = currentSurah.juz_awal || ls.juz;
                            nextDariAyat = endAyat + 1;
                            hintTextEl.innerHTML = `Lanjutan setoran berikutnya: <b>Surah ${nextSurah.nama_latin} Ayat ${nextDariAyat}</b> (setoran terakhir: Ayat ${endAyat}).`;
                        } else {
                            const nextSurahObj = surahsData.find(s => parseInt(s.nomor) === parseInt(currentSurah.nomor) + 1);
                            if (nextSurahObj) {
                                nextSurah = nextSurahObj;
                                nextJuz = nextSurahObj.juz_awal || ls.juz;
                                nextDariAyat = 1;
                                hintTextEl.innerHTML = `Surah ${currentSurah.nama_latin} telah selesai (hingga Ayat ${totalAyat}). Lanjutan berikutnya: <b>Surah ${nextSurah.nama_latin} Ayat 1</b>.`;
                            } else {
                                hintTextEl.innerHTML = `Surah ${currentSurah.nama_latin} telah selesai.`;
                            }
                        }
                    } else {
                        hintTextEl.innerHTML = `Setoran terakhir: Surah ${ls.surah} Ayat ${ls.ayat}.`;
                    }

                    if (nextSurah) {
                        currentRecommendation = {
                            surah: nextSurah,
                            juz: nextJuz,
                            dari_ayat: nextDariAyat
                        };
                        autoFillBtn.classList.remove('hidden');
                    } else {
                        currentRecommendation = null;
                        autoFillBtn.classList.add('hidden');
                    }
                }
            }

            function autoFillNextSetoran() {
                if (!currentRecommendation) return;

                const surah = currentRecommendation.surah;
                document.getElementById('surah').value = surah.nama_latin;
                document.getElementById('surah-search-input').value = `${surah.nomor}. ${surah.nama_latin}`;
                juzInput.value = currentRecommendation.juz;

                inputDari.value = currentRecommendation.dari_ayat;
                inputSampai.value = '';

                updateSurahContext(surah.nama_latin);
                checkAyatGapWarning();

                inputSampai.focus();
            }

            function checkAyatGapWarning() {
                const warningEl = document.getElementById('ayat-gap-warning');
                if (!warningEl) return;

                if (!currentSelectedStudent) {
                    warningEl.classList.add('hidden');
                    return;
                }

                const currentSurahInput = document.getElementById('surah').value;
                const dariVal = parseInt(inputDari.value);

                if (!currentSurahInput || isNaN(dariVal) || dariVal <= 0) {
                    warningEl.classList.add('hidden');
                    return;
                }

                const targetKey = normalizeSurahKey(currentSurahInput);
                const surahHistoryItem = currentSelectedStudent.surah_history ? currentSelectedStudent.surah_history[targetKey] : null;

                if (surahHistoryItem) {
                    const lastEnd = parseInt(surahHistoryItem.max_ayat);
                    const expectedNext = lastEnd + 1;

                    if (dariVal === expectedNext) {
                        warningEl.className = 'text-xs font-bold p-2.5 rounded-xl border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 transition-all';
                        warningEl.innerHTML = `✅ <b>Sesuai:</b> Melanjutkan tepat dari Ayat ${dariVal} pada Surah ${surahHistoryItem.surah} (setoran sebelumnya hingga Ayat ${lastEnd}).`;
                        warningEl.classList.remove('hidden');
                    } else if (dariVal > expectedNext) {
                        const gap = dariVal - expectedNext;
                        const gapStr = gap === 1 ? `Ayat ${expectedNext}` : `Ayat ${expectedNext}–${dariVal - 1}`;
                        warningEl.className = 'text-xs font-bold p-2.5 rounded-xl border border-amber-300 dark:border-amber-700 bg-amber-50 dark:bg-amber-950/40 text-amber-800 dark:text-amber-200 transition-all';
                        warningEl.innerHTML = `⚠️ <b>Perhatian (Lompat Hafalan):</b> Pada Surah ${surahHistoryItem.surah}, setoran sebelumnya hingga Ayat ${lastEnd}. Menginput dari Ayat ${dariVal} melompati <b>${gap} ayat (${gapStr})</b>!`;
                        warningEl.classList.remove('hidden');
                    } else if (dariVal <= lastEnd) {
                        warningEl.className = 'text-xs font-bold p-2.5 rounded-xl border border-blue-200 dark:border-blue-800 bg-blue-50 dark:bg-blue-950/40 text-blue-800 dark:text-blue-200 transition-all';
                        warningEl.innerHTML = `ℹ️ <b>Catatan:</b> Ayat 1–${lastEnd} pada Surah ${surahHistoryItem.surah} sudah pernah disetorkan sebelumnya.`;
                        warningEl.classList.remove('hidden');
                    }
                } else {
                    // Setoran perdana untuk surah ini
                    if (dariVal === 1) {
                        warningEl.className = 'text-xs font-bold p-2.5 rounded-xl border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 transition-all';
                        warningEl.innerHTML = `✅ <b>Sesuai:</b> Memulai setoran perdana Surah ${currentSurahInput} dari Ayat 1.`;
                        warningEl.classList.remove('hidden');
                    } else if (dariVal > 1) {
                        const gap = dariVal - 1;
                        const gapStr = gap === 1 ? `Ayat 1` : `Ayat 1–${dariVal - 1}`;
                        warningEl.className = 'text-xs font-bold p-2.5 rounded-xl border border-amber-300 dark:border-amber-700 bg-amber-50 dark:bg-amber-950/40 text-amber-800 dark:text-amber-200 transition-all';
                        warningEl.innerHTML = `⚠️ <b>Perhatian (Lompat Hafalan):</b> Surah ${currentSurahInput} belum pernah disetorkan. Menginput dari Ayat ${dariVal} melompati <b>${gap} ayat (${gapStr})</b>!`;
                        warningEl.classList.remove('hidden');
                    }
                }
            }

            inputDari.addEventListener('input', checkAyatGapWarning);

            function updateSurahContext(surahName = null) {
                const val = surahName || document.getElementById('surah').value;
                const hintEl = document.getElementById('ayat-hint');
                if (!val) {
                    if (hintEl) hintEl.classList.add('hidden');
                    renderLastSetoranBox(currentSelectedStudent);
                    return;
                }

                const surah = surahsData.find(s => s.nama_latin.toLowerCase() === val.toLowerCase());
                if (surah) {
                    juzInput.value = surah.juz_awal;
                    [inputDari, inputSampai].forEach(el => {
                        el.max = surah.jumlah_ayat;
                        el.dataset.maxAyat = surah.jumlah_ayat;
                    });
                    inputSampai.placeholder = surah.jumlah_ayat;

                    if (hintEl) {
                        hintEl.textContent = `💡 Surah ${surah.nama_latin} memiliki ${surah.jumlah_ayat} ayat.`;
                        hintEl.classList.remove('hidden');
                    }
                }

                renderLastSetoranBox(currentSelectedStudent, val);
            }

            if (document.getElementById('surah').value) {
                updateSurahContext();
            }

            // Handle Pre-selection
            if (initialSelectedId) {
                const found = studentsData.find(s => String(s.id) === String(initialSelectedId));
                if (found) {
                    document.getElementById('student_id').value = found.id;
                    document.getElementById('student-search-input').value = `${found.name} (${found.nis})`;
                    onSelectStudent(found);
                }
            }

            function toggleInputs(isPresent) {
                const inputs = document.getElementById('hafalan-inputs');
                inputs.classList.toggle('hidden', !isPresent);
            }
        </script>
    @endpush
</x-tahfidz-layout>