<x-tahfidz-layout>
    <x-slot name="header">
        Edit Akun Pengguna
    </x-slot>
    <x-slot name="subtitle">
        Perbarui informasi akun: {{ $user->name }}.
    </x-slot>

    <div class="max-w-2xl">
        <div class="mb-6">
            <a href="{{ route('admin.users.index') }}" class="text-sm font-bold text-emerald-700 dark:text-emerald-500 flex items-center gap-2 hover:gap-3 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Daftar Akun
            </a>
        </div>

        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
            @csrf
            @method('PATCH')
            
            <x-tahfidz.card title="Informasi Dasar">
                <div class="space-y-4">
                    <x-tahfidz.form-input name="name" label="Nama Lengkap" placeholder="Masukkan nama lengkap" :value="$user->name" required />
                    <x-tahfidz.form-input type="email" name="email" label="Alamat Email" placeholder="email@example.com" :value="$user->email" required />
                    <x-tahfidz.form-input name="phone" label="Nomor Telepon / WhatsApp" placeholder="Contoh: 08123456789" :value="$user->phone" required />
                    
                    <div class="w-full">
                        <label for="role" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Peran (Role)</label>
                        <select name="role" id="role" class="block w-full px-4 py-3 border border-gray-100 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm dark:text-white transition-all shadow-sm">
                            <option value="guru" {{ old('role', $user->role) == 'guru' ? 'selected' : '' }}>Guru (Asatidz)</option>
                            <option value="orang_tua" {{ old('role', $user->role) == 'orang_tua' ? 'selected' : '' }}>Orang Tua / Wali</option>
                        </select>
                        @error('role') <p class="mt-1 text-xs text-red-600 font-bold italic">{{ $message }}</p> @enderror
                    </div>
                </div>
            </x-tahfidz.card>

            <x-tahfidz.card title="Keamanan (Biarkan kosong jika tidak ingin mengubah)">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-tahfidz.form-input type="password" name="password" label="Kata Sandi Baru" placeholder="••••••••" />
                    <x-tahfidz.form-input type="password" name="password_confirmation" label="Konfirmasi Kata Sandi Baru" placeholder="••••••••" />
                </div>
            </x-tahfidz.card>

            <div class="flex justify-end pt-4">
                <button type="submit" class="px-8 py-4 bg-emerald-700 text-white rounded-2xl font-bold text-lg hover:bg-emerald-800 shadow-xl shadow-emerald-200 dark:shadow-none transition-all flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-tahfidz-layout>
