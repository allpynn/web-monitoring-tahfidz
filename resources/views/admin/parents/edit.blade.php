<x-tahfidz-layout>
    <x-slot name="header">
        Edit Akun Orang Tua
    </x-slot>
    <x-slot name="subtitle">
        Perbarui informasi akun: {{ $parent->name }}.
    </x-slot>

    <div class="max-w-2xl">
        <div class="mb-6">
            <a href="{{ route('admin.parents.index') }}" class="text-sm font-bold text-emerald-700 dark:text-emerald-500 flex items-center gap-2 hover:gap-3 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Daftar Orang Tua
            </a>
        </div>

        <form action="{{ route('admin.parents.update', $parent) }}" method="POST" class="space-y-6">
            @csrf
            @method('PATCH')
            
            <x-tahfidz.card title="Informasi Dasar">
                <div class="space-y-4">
                    <x-tahfidz.form-input name="name" label="Nama Lengkap" placeholder="Masukkan nama lengkap" :value="$parent->name" required />

                    <div class="w-full">
                        <label for="gender" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Jenis Kelamin</label>
                        <select id="gender" name="gender" required
                            class="block w-full px-4 py-3 border border-gray-100 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm dark:text-white transition-all shadow-sm">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki" {{ old('gender', $parent->gender) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('gender', $parent->gender) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('gender')
                            <p class="mt-1 text-xs text-red-600 font-bold italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <x-tahfidz.form-input type="email" name="email" label="Alamat Email" placeholder="email@example.com" :value="$parent->email" required />
                    <x-tahfidz.form-input name="phone" label="Nomor Telepon / WhatsApp" placeholder="Contoh: 08123456789" :value="$parent->phone" required />
                </div>
            </x-tahfidz.card>

            <x-tahfidz.card title="Keamanan (Biarkan kosong jika tidak ingin mengubah)">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-tahfidz.form-input type="password" name="password" label="Kata Sandi Baru" placeholder="••••••••" />
                    <x-tahfidz.form-input type="password" name="password_confirmation" label="Konfirmasi Kata Sandi Baru" placeholder="••••••••" />
                </div>
            </x-tahfidz.card>

            <div class="flex justify-center pt-8">
                <button type="submit" class="group px-12 py-4 bg-[#066447] text-white rounded-full font-bold text-xl hover:bg-[#044d36] shadow-2xl shadow-emerald-200/50 dark:shadow-none transition-all flex items-center gap-4 hover:scale-[1.02] active:scale-[0.98]">
                    <svg class="w-7 h-7 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-tahfidz-layout>
