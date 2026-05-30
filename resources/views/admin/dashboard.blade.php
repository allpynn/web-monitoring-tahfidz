<x-tahfidz-layout>
    <x-slot name="header">
        Dashboard Admin
    </x-slot>
    <x-slot name="subtitle">
        Selamat datang di pusat kendali Sistem Monitoring Tahfidz.
    </x-slot>

    <div class="mb-4 flex justify-between items-center">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white">Ringkasan Sistem</h2>
        <div class="flex gap-2">

            <button onclick="document.getElementById('importModal').classList.remove('hidden')" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                Import Data Massal
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 rounded-2xl border border-emerald-100 dark:border-emerald-800 font-bold flex items-center gap-3 animate-fadeIn">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('import_warning'))
        <div class="mb-6 p-5 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-2xl animate-fadeIn">
            <div class="flex items-start gap-4">
                <div class="p-2 bg-yellow-100 dark:bg-yellow-900/40 rounded-xl text-yellow-700 dark:text-yellow-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-bold text-yellow-800 dark:text-yellow-300">Import Berhasil Sebagian</h3>
                    <p class="text-xs text-yellow-700 dark:text-yellow-400 mt-1">
                        Berhasil menambahkan <span class="font-black underline">{{ session('import_warning')['success'] }}</span> data {{ session('import_warning')['tipe'] }}, namun ada <span class="font-black text-red-600 dark:text-red-400">{{ count(session('import_warning')['errors']) }}</span> baris yang dilewati karena sudah ada atau bermasalah:
                    </p>
                    <div class="mt-3 bg-white/50 dark:bg-black/20 rounded-xl p-3 border border-yellow-200/50 dark:border-yellow-800/50">
                        <ul class="text-[10px] space-y-1.5 text-yellow-800 dark:text-yellow-400 max-h-40 overflow-y-auto custom-scrollbar">
                            @foreach(session('import_warning')['errors'] as $error)
                                <li class="flex gap-2">
                                    <span class="font-bold shrink-0 text-yellow-600">•</span>
                                    <span>{{ $error }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-2xl border border-red-100 dark:border-red-800 font-bold flex items-center gap-3 animate-fadeIn">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <x-tahfidz.card title="Total Orang Tua" value="{{ $parentCount ?? 0 }}" icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path fill-rule="evenodd" d="M8.25 6.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM15.75 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM2.25 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM6.31 15.117A6.745 6.745 0 0 1 12 12a6.745 6.745 0 0 1 6.709 7.498.75.75 0 0 1-.372.568A12.696 12.696 0 0 1 12 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 0 1-.372-.568 6.787 6.787 0 0 1 1.019-4.38Z" clip-rule="evenodd" /><path d="M5.082 14.254a8.287 8.287 0 0 0-1.308 5.135 9.687 9.687 0 0 1-1.764-.44l-.115-.04a.563.563 0 0 1-.373-.487l-.01-.121a3.75 3.75 0 0 1 3.57-4.047ZM20.226 19.389a8.287 8.287 0 0 0-1.308-5.135 3.75 3.75 0 0 1 3.57 4.047l-.01.121a.563.563 0 0 1-.373.486l-.115.04c-.567.2-1.156.349-1.764.441Z" /></svg>' />
        <x-tahfidz.card title="Total Guru/Pengajar" value="{{ $guruCount ?? 0 }}" icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path fill-rule="evenodd" d="M8.25 6.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM15.75 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM2.25 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM6.31 15.117A6.745 6.745 0 0 1 12 12a6.745 6.745 0 0 1 6.709 7.498.75.75 0 0 1-.372.568A12.696 12.696 0 0 1 12 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 0 1-.372-.568 6.787 6.787 0 0 1 1.019-4.38Z" clip-rule="evenodd" /><path d="M5.082 14.254a8.287 8.287 0 0 0-1.308 5.135 9.687 9.687 0 0 1-1.764-.44l-.115-.04a.563.563 0 0 1-.373-.487l-.01-.121a3.75 3.75 0 0 1 3.57-4.047ZM20.226 19.389a8.287 8.287 0 0 0-1.308-5.135 3.75 3.75 0 0 1 3.57 4.047l-.01.121a.563.563 0 0 1-.373.486l-.115.04c-.567.2-1.156.349-1.764.441Z" /></svg>' />
        <x-tahfidz.card title="Total Santri" value="{{ $studentCount ?? 0 }}" icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path fill-rule="evenodd" d="M8.25 6.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM15.75 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM2.25 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM6.31 15.117A6.745 6.745 0 0 1 12 12a6.745 6.745 0 0 1 6.709 7.498.75.75 0 0 1-.372.568A12.696 12.696 0 0 1 12 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 0 1-.372-.568 6.787 6.787 0 0 1 1.019-4.38Z" clip-rule="evenodd" /><path d="M5.082 14.254a8.287 8.287 0 0 0-1.308 5.135 9.687 9.687 0 0 1-1.764-.44l-.115-.04a.563.563 0 0 1-.373-.487l-.01-.121a3.75 3.75 0 0 1 3.57-4.047ZM20.226 19.389a8.287 8.287 0 0 0-1.308-5.135 3.75 3.75 0 0 1 3.57 4.047l-.01.121a.563.563 0 0 1-.373.486l-.115.04c-.567.2-1.156.349-1.764.441Z" /></svg>' />
    </div>

    <!-- Import Modal -->
    <div id="importModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center backdrop-blur-sm transition-opacity">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-md mx-4 overflow-hidden transform transition-all">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Import Data Massal</h3>
                <button onclick="document.getElementById('importModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <form action="{{ route('admin.import') ?? '#' }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Upload File (.csv)</label>
                    <input type="file" name="file" accept=".csv" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 dark:file:bg-emerald-900/30 dark:file:text-emerald-400">
                    
                    @error('file')
                        <p class="mt-2 text-sm text-red-600 font-bold bg-red-50 p-2 rounded-lg">{{ $message }} (Ingat, simpan file Excel Anda sebagai CSV)</p>
                    @enderror

                    @if(session('error'))
                        <p class="mt-2 text-sm text-red-600 font-bold bg-red-50 p-2 rounded-lg">{{ session('error') }}</p>
                    @endif

                    <div class="mt-3 p-3 bg-blue-50 dark:bg-blue-900/30 rounded-lg space-y-2">
                        <p class="text-xs text-blue-700 dark:text-blue-300 font-bold">Instruksi Import Cerdas (Guru/Santri):</p>
                        <p class="text-[11px] text-blue-600 dark:text-blue-400">Sistem akan membedakan data secara otomatis berdasarkan nama kolom Anda di Excel (Baris ke-1).</p>
                        <div class="space-y-3 mt-2">
                            <div class="p-2 bg-white/50 dark:bg-black/20 rounded border border-blue-100 dark:border-blue-800">
                                <p class="text-xs text-blue-800 dark:text-blue-300 font-bold mb-1">▶ Untuk Import Guru:</p>
                                <p class="text-[11px] text-blue-700 dark:text-blue-400">Urutan: <strong>Nama | NIP | No telp | Email | Jenis Kelamin</strong></p>
                            </div>
                            <div class="p-2 bg-white/50 dark:bg-black/20 rounded border border-blue-100 dark:border-blue-800">
                                <p class="text-xs text-blue-800 dark:text-blue-300 font-bold mb-1">▶ Untuk Import Santri:</p>
                                <p class="text-[11px] text-blue-700 dark:text-blue-400">Urutan: <strong>Nama Santri | NISN | JenKel Santri | Nama Ortu | Email Ortu | No HP Ortu | JenKel Ortu</strong></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-bold transition-colors">Mulai Import Data</button>
                </div>
            </form>
        </div>
    </div>
</x-tahfidz-layout>
