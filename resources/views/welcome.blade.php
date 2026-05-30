<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistem Monitoring Tahfidz - Ponpes Al Mujahidin</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}">


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&family=Amiri:ital,wght@0,400;0,700;1,400;1,700&display=swap"
        rel="stylesheet">

    <!-- Theme Toggle Initialization -->
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }

        .arabic {
            font-family: 'Amiri', serif;
        }

        /* Simple Professional Background with Dynamic Glow */
        .simple-glow-bg {
            background-color: #ffffff;
            background-image: radial-gradient(circle at 70% 30%, rgba(16, 185, 129, 0.08) 0%, transparent 50%);
            position: relative;
        }

        .dark .simple-glow-bg {
            background-color: #030712;
            /* Deeper background */
            background-image: radial-gradient(circle at 70% 30%, rgba(16, 185, 129, 0.1) 0%, transparent 60%);
        }

        /* Ambient Orbs */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(120px);
            z-index: 0;
            pointer-events: none;
        }

        .orb-green {
            background: rgba(16, 185, 129, 0.1);
            width: 600px;
            height: 600px;
            top: -100px;
            right: -100px;
        }

        .dark .orb-green {
            background: rgba(5, 150, 105, 0.12);
        }

        /* Professional Card Shadow */
        .pro-card {
            background: white;
            border: 1px solid #f1f5f9;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.05);
        }

        .dark .pro-card {
            background: #0f172a;
            /* Slightly lighter than bg for contrast */
            border: 1px solid rgba(255, 255, 255, 0.03);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        /* Hide scrollbars but allow scrolling */
        ::-webkit-scrollbar {
            width: 0px;
            background: transparent;
        }

        /* Custom reveal animations */
        @keyframes subtle-bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-5px);
            }
        }

        .hover-bounce:hover {
            animation: subtle-bounce 2s ease-in-out infinite;
        }
    </style>
</head>

<body
    class="antialiased simple-glow-bg transition-colors duration-500 selection:bg-emerald-100 selection:text-emerald-900 min-h-screen overflow-x-hidden">

    <div class="orb orb-green"></div>

    <!-- Background Centered Logo with Green Light -->
    <div class="fixed inset-0 flex items-center justify-center pointer-events-none z-0 overflow-hidden">
        <div class="relative w-full max-w-2xl px-4 scale-75 lg:scale-100">
            <!-- Green Light Aura -->
            <div
                class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[400px] lg:w-[600px] h-[400px] lg:h-[600px] bg-emerald-600/25 dark:bg-emerald-500/15 rounded-full blur-[100px] lg:blur-[140px] pointer-events-none">
            </div>
            <!-- Translucent Large Logo -->
            <x-application-logo class="w-full h-auto opacity-[0.08] dark:opacity-[0.04] relative z-10" />
        </div>
    </div>

    <!-- ── Theme Toggle ────────────────────────────────────────── -->
    <div class="fixed top-4 lg:top-8 right-4 lg:right-8 z-50">
        <button id="theme-toggle" type="button"
            class="p-3 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border border-slate-200 dark:border-slate-800 rounded-2xl shadow-lg hover:border-emerald-500 transition-all duration-300 group">
            <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5 text-slate-600 group-hover:text-emerald-600"
                fill="currentColor" viewBox="0 0 20 20">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
            </svg>
            <svg id="theme-toggle-light-icon" class="hidden w-5 h-5 text-emerald-500 group-hover:text-emerald-400"
                fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464l-.707-.707a1 1 0 00-1.414 1.414l.707.707a1 1 0 001.414-1.414zm2.12 10.607a1 1 0 010-1.414l.706-.707a1 1 0 111.414 1.414l-.707.707a1 1 0 01-1.414 0z"
                    fill-rule="evenodd" clip-rule="evenodd"></path>
            </svg>
        </button>
    </div>

    <!-- ── Content Wrapper ─────────────────────────────────────── -->
    <main class="min-h-screen w-full flex flex-col lg:flex-row relative z-10">

        <!-- SIDE 1: BRANDING -->
        <div
            class="w-full lg:w-[50%] flex flex-col justify-center items-center lg:items-end p-10 lg:p-24 lg:pr-12 text-center lg:text-right">
            <div class="max-w-xl w-full">
                <div class="space-y-4 lg:space-y-2 mb-10 lg:mb-12 animate-in slide-in-from-left duration-700">
                    <span
                        class="block text-[11px] lg:text-[13px] font-black tracking-[0.4em] uppercase text-emerald-600 dark:text-emerald-500 opacity-80">Sistem
                        Monitoring Terpadu</span>
                    <h1
                        class="text-5xl lg:text-7xl font-black text-slate-900 dark:text-white leading-[1.05] tracking-tight">
                        Tahfidz <br> <span
                            class="text-emerald-600 dark:text-emerald-500 underline decoration-4 lg:decoration-8 underline-offset-8">Al-Mujahidin</span>
                    </h1>
                </div>

                <div class="space-y-8 lg:space-y-8 animate-in slide-in-from-left duration-1000 delay-150">
                    <p
                        class="arabic text-5xl lg:text-6xl text-slate-800 dark:text-emerald-400 font-bold leading-relaxed">
                        خَيْرُكُمْ مَنْ تَعَلَّمَ الْقُرْآنَ وَعَلَّمَهُ
                    </p>
                    <p
                        class="text-lg lg:text-xl text-slate-500 dark:text-slate-400 font-medium italic max-w-sm lg:ml-auto">
                        "Sebaik-baik kalian adalah orang yang belajar Al-Qur'an dan mengajarkannya."
                        <span
                            class="block mt-3 text-xs lg:text-sm font-bold text-slate-400 not-italic uppercase tracking-widest">—
                            HR. Bukhari</span>
                    </p>
                </div>

                <!-- Feature Pills -->
                <div
                    class="mt-12 lg:mt-16 flex flex-wrap justify-center lg:justify-end gap-3 lg:gap-4 animate-in fade-in duration-1000 delay-300">
                    <div
                        class="px-6 py-3 bg-slate-100/80 dark:bg-slate-800/80 backdrop-blur-sm text-slate-600 dark:text-slate-400 rounded-2xl font-bold text-xs lg:text-sm border border-slate-200/50 dark:border-slate-700/50">
                        Premium Monitoring</div>
                    <div
                        class="px-6 py-3 bg-emerald-50/80 dark:bg-emerald-900/30 backdrop-blur-sm text-emerald-700 dark:text-emerald-400 rounded-2xl font-bold text-xs lg:text-sm border border-emerald-100/50 dark:border-emerald-800/50">
                        Secure Access</div>
                </div>
            </div>
        </div>

        <!-- SIDE 2: LOGIN (Flexible Panel) -->
        <div class="w-full lg:w-[50%] flex items-center justify-center lg:justify-start">

            <div class="w-full h-full min-h-[600px] lg:min-h-screen">
                <!-- Professional Login Card (Panel Style) -->
                <div
                    class="pro-card h-full w-full p-10 lg:p-24 flex flex-col justify-center rounded-t-[3rem] lg:rounded-t-0 lg:rounded-l-[4rem] animate-in slide-in-from-right duration-700">
                    <div class="max-w-md w-full mx-auto lg:mx-0">
                        <div class="mb-12">
                            <h2 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight mb-4">Selamat
                                Datang</h2>
                            <p class="text-slate-500 dark:text-slate-400 text-base font-medium leading-relaxed">
                                Silakan masuk menggunakan email atau username dan kata sandi Anda untuk memantau
                                perkembangan hafalan santri secara langsung.
                            </p>
                        </div>

                        <!-- Session Status -->
                        <x-auth-session-status class="mb-8" :status="session('status')" />

                        <form method="POST" action="{{ route('login') }}" class="space-y-6">
                            @csrf

                            <!-- Identifier -->
                            <div class="space-y-2">
                                <label for="email"
                                    class="block text-xs lg:text-sm font-bold text-slate-500 dark:text-slate-400 ml-1 tracking-wide">Email
                                    / Nomor HP</label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-emerald-500 transition-colors">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                                        </svg>
                                    </div>
                                    <input id="email" type="text" name="email"
                                        value="{{ old('email', Cookie::get('remember_email')) }}" required autofocus
                                        placeholder="Masukkan Email atau Nomor HP"
                                        class="block w-full pl-14 pr-6 py-5 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 dark:text-white rounded-3xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all duration-300 font-medium text-base placeholder:text-slate-300 dark:placeholder:text-slate-600">
                                </div>
                                <x-input-error :messages="$errors->get('email')" class="mt-1 ml-1" />
                            </div>

                            <!-- Password -->
                            <div class="space-y-2">
                                <label for="password"
                                    class="block text-xs lg:text-sm font-bold text-slate-500 dark:text-slate-400 ml-1 tracking-wide">Kata
                                    Sandi</label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-emerald-500 transition-colors">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                    <input id="password" type="password" name="password" required
                                        autocomplete="current-password" placeholder="••••••••"
                                        class="block w-full pl-14 pr-14 py-5 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 dark:text-white rounded-3xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all duration-300 font-medium text-base placeholder:text-slate-300 dark:placeholder:text-slate-600">

                                    <!-- Toggle Password Visibility -->
                                    <button type="button" id="toggle-password"
                                        class="absolute inset-y-0 right-0 pr-5 flex items-center text-slate-400 hover:text-emerald-600 transition-colors cursor-pointer focus:outline-none">
                                        <svg id="eye-icon" class="h-6 w-6" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg id="eye-off-icon" class="hidden h-6 w-6" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                        </svg>
                                    </button>
                                </div>
                                <x-input-error :messages="$errors->get('password')" class="mt-1 ml-1" />
                            </div>

                            <!-- Remember Me -->
                            <div class="flex items-center px-1">
                                <label for="remember_me" class="inline-flex items-center group cursor-pointer">
                                    <input id="remember_me" type="checkbox" name="remember" {{ Cookie::get('remember_email') ? 'checked' : '' }}
                                        class="rounded-lg border-slate-300 dark:border-slate-700 text-emerald-600 focus:ring-emerald-500/20 dark:bg-slate-900 h-5 w-5 cursor-pointer transition-all">
                                    <span
                                        class="ml-3 text-sm font-bold text-slate-500 dark:text-slate-400 group-hover:text-emerald-600 transition-colors">Ingat
                                        Saya</span>
                                </label>
                            </div>

                            <!-- Submit -->
                            <div class="pt-6">
                                <button type="submit"
                                    class="w-full py-5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-3xl font-black text-base lg:text-lg tracking-widest shadow-2xl shadow-emerald-700/20 dark:shadow-none hover:-translate-y-1 active:scale-[0.98] transition-all outline-none focus:ring-4 focus:ring-emerald-500/30">
                                    LOGIN
                                </button>
                            </div>
                        </form>

                        <div class="mt-16 text-center opacity-40">
                            <p
                                class="text-[10px] font-black text-slate-400 dark:text-slate-600 uppercase tracking-[0.5em]">
                                Education • Discipline • Integrity
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        const darkIcon = document.getElementById('theme-toggle-dark-icon');
        const lightIcon = document.getElementById('theme-toggle-light-icon');
        const themeBtn = document.getElementById('theme-toggle');

        function updateIcons() {
            if (document.documentElement.classList.contains('dark')) {
                lightIcon.classList.remove('hidden');
                darkIcon.classList.add('hidden');
            } else {
                darkIcon.classList.remove('hidden');
                lightIcon.classList.add('hidden');
            }
        }

        updateIcons();

        themeBtn.addEventListener('click', function () {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            }
            updateIcons();
        });

        // Toggle Password Visibility Logic
        const togglePassword = document.getElementById('toggle-password');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');
        const eyeOffIcon = document.getElementById('eye-off-icon');

        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Toggle Icons
            eyeIcon.classList.toggle('hidden');
            eyeOffIcon.classList.toggle('hidden');
        });

        // Reverb Real-time Listener
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof Echo !== 'undefined') {
                window.Echo.channel('hafalan-updates')
                    .listen('HafalanUpdated', (e) => {
                        console.log('Update Hafalan:', e.message);
                        // Implementasi notifikasi Toast bisa dilakukan di sini
                    });
            }
        });
    </script>
</body>

</html>