<x-guest-layout>
    <div class="bg-white dark:bg-gray-800 p-10 rounded-[2.5rem] shadow-2xl shadow-emerald-100 dark:shadow-none border border-gray-100 dark:border-gray-700 relative overflow-hidden">
        <!-- Background Accents -->
        <div class="absolute -right-16 -top-16 w-48 h-48 bg-emerald-50 dark:bg-emerald-900/20 rounded-full blur-3xl"></div>
        <div class="absolute -left-16 -bottom-16 w-48 h-48 bg-blue-50 dark:bg-blue-900/20 rounded-full blur-3xl"></div>
        
        <div class="relative z-10">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">
                    Daftar Akun
                </h2>
                <p class="mt-3 text-sm text-gray-500 dark:text-gray-400 font-medium leading-relaxed">
                    Lengkapi data diri untuk mulai memantau <br class="hidden sm:block"> perkembangan hafalan santri secara digital.
                </p>
            </div>

            <form action="{{ route('register') }}" method="POST" class="space-y-5">
                @csrf
                
                <x-tahfidz.form-input label="Nama Lengkap" name="name" type="text" placeholder="Masukkan nama lengkap" required autofocus />
                <x-tahfidz.form-input label="Alamat Email" name="email" type="email" placeholder="nama@email.com" required />
                <x-tahfidz.form-input label="Nomor WhatsApp" name="phone" type="text" placeholder="0812xxxx" required />
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <x-tahfidz.form-input label="Kata Sandi" name="password" type="password" placeholder="••••••••" required />
                    <x-tahfidz.form-input label="Konfirmasi Sandi" name="password_confirmation" type="password" placeholder="••••••••" required />
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="w-full py-4 px-6 bg-emerald-700 hover:bg-emerald-800 text-white rounded-2xl font-black text-lg shadow-xl shadow-emerald-200 dark:shadow-none hover:-translate-y-0.5 active:scale-95 transition-all duration-200">
                        Daftar Sekarang
                    </button>
                </div>
            </form>
            
            <div class="mt-8 pt-8 border-t border-gray-50 dark:border-gray-700/50 text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Sudah punya akun? <a href="{{ route('login') }}" class="font-black text-emerald-700 dark:text-emerald-500 hover:underline">Masuk di sini</a>
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
