<x-tahfidz-layout>
    <x-slot name="header">
        Tambah User Baru
    </x-slot>
    <x-slot name="subtitle">
        Daftarkan akun Guru atau Orang Tua ke dalam sistem.
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="p-8 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-3xl shadow-sm relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-emerald-50 dark:bg-emerald-900/10 rounded-full blur-3xl"></div>
            
            <form action="{{ route('admin.users.store') }}" method="POST" class="relative z-10 space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-tahfidz.form-input label="Nama Lengkap" name="name" type="text" placeholder="Masukkan nama" required autofocus />
                    <div>
                        <label for="role" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Role User</label>
                        <select name="role" id="role" class="block w-full px-4 py-3 border border-gray-100 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm dark:text-white transition-all shadow-sm">
                            <option value="guru">Guru</option>
                            <option value="orang_tua">Orang Tua</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-tahfidz.form-input label="Email" name="email" type="email" placeholder="email@tahfidz.com" required />
                    <x-tahfidz.form-input label="Nomor WhatsApp" name="phone" type="text" placeholder="0812xxxx" required />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-tahfidz.form-input label="Kata Sandi" name="password" type="password" placeholder="••••••••" required />
                    <x-tahfidz.form-input label="Konfirmasi Sandi" name="password_confirmation" type="password" placeholder="••••••••" required />
                </div>

                <div class="flex items-center justify-end pt-4 space-x-3">
                    <a href="{{ route('admin.users.index') }}" class="px-6 py-3 text-sm font-bold text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">Batal</a>
                    <button type="submit" class="px-8 py-3 bg-emerald-700 text-white rounded-2xl text-sm font-bold hover:bg-emerald-800 transition-all shadow-lg shadow-emerald-200 dark:shadow-none">
                        Simpan Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-tahfidz-layout>
