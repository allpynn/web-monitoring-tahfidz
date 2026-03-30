<x-guest-layout>
    <div class="bg-white dark:bg-gray-800 p-10 rounded-[2.5rem] shadow-2xl shadow-emerald-100 dark:shadow-none border border-gray-100 dark:border-gray-700 relative overflow-hidden">
        <!-- Background Accents -->
        <div class="absolute -right-16 -top-16 w-48 h-48 bg-emerald-50 dark:bg-emerald-900/20 rounded-full blur-3xl"></div>
        <div class="absolute -left-16 -bottom-16 w-48 h-48 bg-blue-50 dark:bg-blue-900/20 rounded-full blur-3xl"></div>
        
        <div class="relative z-10">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">
                    Halaman Masuk
                </h2>
                <p class="mt-3 text-sm text-gray-500 dark:text-gray-400 font-medium leading-relaxed">
                    Silakan masuk untuk mengelola dan memantau <br class="hidden sm:block"> perkembangan hafalan santri.
                </p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-6" :status="session('status')" />

            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label for="email" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 ml-1">Email / WhatsApp</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-emerald-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"></path></svg>
                        </span>
                        <input id="email" name="email" type="text" autocomplete="username" required value="{{ old('email') }}"
                            class="block w-full pl-12 pr-4 py-4 bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700 dark:text-white rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all placeholder-gray-400 sm:text-sm"
                            placeholder="nama@email.com atau 0812xxxx">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <div class="flex justify-between items-center mb-2 ml-1">
                        <label for="password" class="block text-sm font-bold text-gray-700 dark:text-gray-300">Kata Sandi</label>
                    </div>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-emerald-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </span>
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                            class="block w-full pl-12 pr-4 py-4 bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700 dark:text-white rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all placeholder-gray-400 sm:text-sm"
                            placeholder="••••••••">
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="flex items-center">
                    <input id="remember_me" name="remember" type="checkbox"
                        class="h-5 w-5 text-emerald-600 focus:ring-emerald-500/40 border-gray-200 dark:border-gray-700 dark:bg-gray-900 rounded-lg transition-all">
                    <label for="remember_me" class="ml-3 block text-sm font-medium text-gray-600 dark:text-gray-400">
                        Ingat sesi saya
                    </label>
                </div>

                <div>
                    <button type="submit"
                        class="w-full py-4 px-6 bg-emerald-700 hover:bg-emerald-800 text-white rounded-2xl font-black text-lg shadow-xl shadow-emerald-200 dark:shadow-none hover:-translate-y-0.5 active:scale-95 transition-all duration-200">
                        Masuk Sekarang
                    </button>
                </div>
            </form>
            
            <div class="mt-8 pt-8 border-t border-gray-50 dark:border-gray-700/50 text-center">
                <p class="text-xs text-gray-400 dark:text-gray-500">
                    Akses terbatas untuk Pengajar & Orang Tua Ponpes Al Mujahidin.
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
