<x-tahfidz-layout>
    <x-slot name="header">
        Tambah Orang Tua
    </x-slot>
    <x-slot name="subtitle">
        Buat akun baru untuk wali murid / orang tua santri.
    </x-slot>

    <div class="max-w-2xl">
        <div class="mb-6">
            <a href="{{ route('admin.parents.index') }}" class="text-sm font-bold text-emerald-700 dark:text-emerald-500 flex items-center gap-2 hover:gap-3 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Daftar Orang Tua
            </a>
        </div>

        <form action="{{ route('admin.parents.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <x-tahfidz.card title="Informasi Dasar">
                <div class="space-y-4">
                    <x-tahfidz.form-input name="name" label="Nama Lengkap" placeholder="Masukkan nama lengkap orang tua" required />
                    <x-tahfidz.form-input type="email" name="email" label="Alamat Email" placeholder="email@example.com" required />
                    <x-tahfidz.form-input name="phone" label="Nomor Telepon / WhatsApp" placeholder="Contoh: 08123456789" required />
                </div>
            </x-tahfidz.card>

            <x-tahfidz.card title="Keamanan">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-tahfidz.form-input type="password" name="password" label="Kata Sandi" placeholder="••••••••" required />
                    <x-tahfidz.form-input type="password" name="password_confirmation" label="Konfirmasi Kata Sandi" placeholder="••••••••" required />
                </div>
            </x-tahfidz.card>

            <div class="flex justify-end pt-4">
                <button type="submit" class="px-8 py-4 bg-emerald-700 text-white rounded-2xl font-bold text-lg hover:bg-emerald-800 shadow-xl shadow-emerald-200 dark:shadow-none transition-all flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    Buat Akun Orang Tua
                </button>
            </div>
        </form>
    </div>
</x-tahfidz-layout>
