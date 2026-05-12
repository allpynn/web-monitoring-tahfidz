<x-tahfidz-layout>
    <x-slot name="header">
        Tambah Guru Pengajar
    </x-slot>
    <x-slot name="subtitle">
        Tambahkan akun khusus baru untuk staf guru / pengajar Al-Quran.
    </x-slot>

    <div class="max-w-2xl">
        <div class="mb-6">
            <a href="{{ route('admin.guru.index') }}"
                class="text-sm font-bold text-emerald-700 dark:text-emerald-500 flex items-center gap-2 hover:gap-3 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar Guru
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
                    <h3 class="text-sm font-bold text-red-800 dark:text-red-300">Penyimpanan Gagal!</h3>
                    <ul class="mt-1 list-disc list-inside text-xs text-red-700 dark:text-red-400 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form action="{{ route('admin.guru.store') }}" method="POST" class="space-y-6">
            @csrf

            <x-tahfidz.card title="Informasi Dasar Guru">
                <div class="space-y-4">
                    <x-tahfidz.form-input name="name" label="Nama Lengkap"
                        placeholder="Masukkan nama lengkap ustadz/ustadzah" required />
                    <x-tahfidz.form-input name="nip" label="NIP" placeholder="Nomor Induk Pegawai" required />
                    <x-tahfidz.form-input type="email" name="email" label="Email" placeholder="guru@example.com"
                        required />
                    <x-tahfidz.form-input type="text" name="phone" label="Nomor Handphone"
                        placeholder="Contoh: 08123456789" required />
                </div>
            </x-tahfidz.card>

            <x-tahfidz.card title="Akses & Keamanan">
                <p class="text-xs text-gray-500 mb-4">Jika Anda membiarkan form sandi ini kosong, sistem secara otomatis
                    akan mengatur sandi mengunakan <span class="font-bold">Nomor Handphone</span></p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-tahfidz.form-input type="password" name="password" label="Kata Sandi (Opsional)"
                        placeholder="••••••••" />
                    <x-tahfidz.form-input type="password" name="password_confirmation" label="Konfirmasi Kata Sandi"
                        placeholder="••••••••" />
                </div>
            </x-tahfidz.card>

            <div class="flex justify-end pt-4">
                <button type="submit"
                    class="px-8 py-4 bg-emerald-700 text-white rounded-2xl font-bold text-lg hover:bg-emerald-800 shadow-xl shadow-emerald-200 dark:shadow-none transition-all flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                        </path>
                    </svg>
                    Simpan Akun Guru
                </button>
            </div>
        </form>
    </div>
</x-tahfidz-layout>