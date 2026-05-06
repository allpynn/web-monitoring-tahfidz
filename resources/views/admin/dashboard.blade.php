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
            <a href="{{ route('admin.export.credentials') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Backup Database
            </a>
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

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-2xl border border-red-100 dark:border-red-800 font-bold flex items-center gap-3 animate-fadeIn">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
            {{ session('error') }}
        </div>
    @endif

    @if(session('import_warning'))
        <div class="mb-6 p-4 bg-orange-50 dark:bg-orange-900/30 border border-orange-200 dark:border-orange-800 rounded-2xl flex items-start gap-3 animate-fadeIn">
            <svg class="w-6 h-6 text-orange-600 dark:text-orange-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <div>
                <h3 class="text-sm font-bold text-orange-800 dark:text-orange-300">
                    Import Selesai dengan Catatan: {{ session('import_warning')['success'] }} data santri baru berhasil ditambahkan.
                </h3>
                <p class="text-xs text-orange-700 dark:text-orange-400 font-semibold mt-1">Ditemukan {{ count(session('import_warning')['errors']) }} baris yang dilewati karena format tidak sesuai atau data duplikat/sudah ada:</p>
                <ul class="mt-2 list-disc list-inside text-xs text-orange-700 dark:text-orange-400 space-y-1 bg-white/50 dark:bg-black/20 p-3 rounded-lg max-h-48 overflow-y-auto">
                    @foreach(session('import_warning')['errors'] as $errorMsg)
                        <li>{{ $errorMsg }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <x-tahfidz.card title="Total Orang Tua" value="{{ $parentCount ?? 0 }}" icon='<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9 4.804A7.937 7.937 0 0112 4c1.232 0 2.403.28 3.448.784a1 1 0 01.552.894V17a1 1 0 01-1.342.948 6.037 6.037 0 00-4.32-.2L10 18v-3a1 1 0 011-1h1a1 1 0 100-2h-1V9a1 1 0 10-2 0v5H8a1 1 0 100 2h1a1 1 0 011 1v3l-.336-.112a6.037 6.037 0 00-4.32.2A1 1 0 013 17V5.698a1 1 0 01.552-.894A7.937 7.937 0 017 4c1.232 0 2.403.28 3.448.784z"></path></svg>' />
        <x-tahfidz.card title="Total Guru/Pengajar" value="{{ $guruCount ?? 0 }}" icon='<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path></svg>' />
        <x-tahfidz.card title="Total Santri" value="{{ $studentCount ?? 0 }}" icon='<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM6 8a2 2 0 11-4 0 2 2 0 014 0zM11 18a4.99 4.99 0 01-9 0H11zM19 18a4.99 4.99 0 01-9 0h9z"></path></svg>' />
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
                        <p class="text-xs text-blue-700 dark:text-blue-300 font-bold">Instruksi Import:</p>
                        <ol class="list-decimal pl-4 text-xs text-blue-700 dark:text-blue-300 space-y-1 font-medium">
                            <li>Buat data di Excel dengan urutan kolom: <br><strong>Nama Santri | NIS | Nama Orang Tua | Email Orang Tua</strong></li>
                            <li>Gunakan <strong>Save As</strong> atau <strong>Export</strong> di Excel, pilih format <strong>CSV (Comma delimited) (*.csv)</strong></li>
                            <li>Upload file CSV tersebut ke sini.</li>
                        </ol>
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
