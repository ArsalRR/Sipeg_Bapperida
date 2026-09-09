<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - SIMPEG ASN</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased selection:bg-blue-600 selection:text-white transition-colors duration-300">

    <div class="min-h-screen w-full flex">
        <div class="hidden lg:flex w-1/2 bg-gradient-to-br from-blue-700 to-slate-900 flex-col justify-between p-12 text-white relative overflow-hidden">
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-white opacity-5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-full h-1/2 bg-gradient-to-t from-slate-900/50 to-transparent"></div>

            <div class="z-10 flex items-center gap-2">
                <div class="w-10 h-10 bg-white/20 rounded-md flex items-center justify-center text-white font-bold text-xl backdrop-blur-sm shadow-sm ring-1 ring-white/30">
                    A
                </div>
                <span class="font-bold text-2xl tracking-tight">SIMPEG</span>
            </div>

            <div class="z-10 max-w-lg mt-auto mb-32">
                <h1 class="text-6xl font-extrabold tracking-tight mb-6">Halo ASN! 👋</h1>
                <p class="text-lg text-blue-100/90 leading-relaxed font-light">
                    Sistem Informasi Kepegawaian terpadu. Kelola data aparatur negara dengan lebih cepat, aman, dan efisien.
                </p>
            </div>

            <div class="z-10">
                <p class="text-sm text-blue-200/60">&copy; 2026 SIMPEG ASN. Hak Cipta Dilindungi.</p>
            </div>
        </div>
        <div class="w-full lg:w-1/2 bg-white dark:bg-slate-950 relative flex flex-col transition-colors duration-300">
            <div class="absolute top-6 right-6 z-10 flex items-center gap-3">
                <button 
                    type="button" 
                    onclick="openSsoModal()"
                    class="p-2 rounded-lg text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                    title="Informasi SSO"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </button>
                <x-theme-toggle />
            </div>

            <div class="lg:hidden absolute top-6 left-6 flex items-center gap-2">
                <div class="w-8 h-8 bg-blue-700 dark:bg-blue-600 rounded-md flex items-center justify-center text-white font-bold text-lg shadow-sm">
                    A
                </div>
                <span class="font-bold text-xl tracking-tight text-slate-900 dark:text-white">SIMPEG</span>
            </div>

            <div class="flex flex-col justify-center items-center h-full px-8 sm:px-12">
                <div class="w-full max-w-sm">
                    <h2 class="text-3xl font-bold text-slate-900 dark:text-white mb-2">Selamat Datang Kembali!</h2>
                    <p class="text-gray-500 dark:text-gray-400 mb-8 text-sm">Silakan masuk menggunakan kredensial Anda.</p>

                    <form method="POST" action="{{ route('login') }}" class="w-full max-w-sm mt-8">
                        @csrf
                        <div class="mb-6 relative">
                            <label for="login" class="block text-sm font-medium text-slate-700 dark:text-gray-300 mb-2">Email atau Username</label>
                            <input id="login" name="login" type="text" autocomplete="username" required class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-colors shadow-sm" value="{{ old('login') }}">
                            @error('login')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-6 relative">
                            <label for="password" class="block text-sm font-medium text-slate-700 dark:text-gray-300 mb-2">Kata Sandi</label>
                            <input id="password" name="password" type="password" autocomplete="current-password" required class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-colors shadow-sm">
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-8 relative">
                            <label for="captcha" class="block text-sm font-medium text-slate-700 dark:text-gray-300 mb-2">Keamanan: {{ $captchaQuestion }}</label>
                            <input id="captcha" name="captcha" type="number" required class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-colors shadow-sm">
                            @error('captcha')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="pt-2">
                            <button type="submit" class="w-full flex justify-center py-3 px-4 rounded-md shadow-sm text-sm font-semibold text-white bg-slate-900 dark:bg-blue-600 hover:bg-slate-800 dark:hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-900 dark:focus:ring-blue-600 transition-all transform hover:-translate-y-0.5">
                                Masuk
                            </button>
                            
                            <button type="button" onclick="openSsoModal()" class="mt-4 w-full flex justify-center items-center gap-2 py-3 px-4 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-transparent border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200 dark:focus:ring-gray-700 transition-colors">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Login dengan SSO BKN
                            </button>

                            <p class="mt-8 text-center text-sm text-gray-600 dark:text-gray-400">
                                Belum memiliki akun? 
                                <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:text-blue-500 dark:text-blue-400">Daftar Akun</a>
                            </p>
                        </div>
                    </form>
                    
                    <div class="mt-12 text-center lg:hidden">
                        <p class="text-xs text-gray-500 dark:text-gray-500">&copy; 2026 SIMPEG ASN. Hak Cipta Dilindungi.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="ssoModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 sm:p-6">
        <div class="relative w-full max-w-lg bg-white dark:bg-[#111111] rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-800 overflow-hidden">
            <div class="px-6 pt-8 pb-4">
                <div class="flex items-start justify-between">
                    <div class="space-y-2">
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white">
                            Informasi SSO BKN
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Akun Anda telah berhasil dibuat dan dapat digunakan di beberapa situs berikut
                        </p>
                    </div>
                    <button
                        type="button"
                        onclick="closeSsoModal()"
                        aria-label="Tutup"
                        class="flex-shrink-0 ml-4 p-2 rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            @php
                $ssoSites = config('sso_sites', []);
            @endphp

            @if (count($ssoSites) > 0)
                <div class="px-6 pb-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="h-px flex-1 bg-gray-200 dark:bg-gray-700"></div>
                        <span class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            Situs Terhubung
                        </span>
                        <div class="h-px flex-1 bg-gray-200 dark:bg-gray-700"></div>
                    </div>

                    <div class="space-y-2.5">
                        @foreach ($ssoSites as $site)
                            <a
                                href="{{ $site['url'] }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="group flex items-center justify-between p-4 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-blue-400 dark:hover:border-blue-700 bg-white dark:bg-slate-900 hover:bg-blue-50/30 dark:hover:bg-blue-900/10 transition-all duration-200"
                            >
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center border border-blue-100 dark:border-blue-800">
                                        <span class="text-sm font-bold text-blue-600 dark:text-blue-400">
                                            {{ strtoupper(substr($site['nama'], 0, 2)) }}
                                        </span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-slate-800 dark:text-slate-200 truncate">
                                            {{ $site['nama'] }}
                                        </p>
                                        @if (!empty($site['deskripsi']))
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-1">
                                                {{ $site['deskripsi'] }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex-shrink-0 ml-4 flex items-center gap-1.5">
                                    <span class="text-sm font-medium text-blue-600 dark:text-blue-400 group-hover:translate-x-0.5 transition-transform">
                                        Buka
                                    </span>
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="px-6 pb-6">
                    <div class="text-center py-8 bg-gray-50 dark:bg-slate-900/50 rounded-xl border border-dashed border-gray-200 dark:border-gray-700">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Tidak ada situs terhubung saat ini
                        </p>
                    </div>
                </div>
            @endif
            <div class="px-6 py-4 bg-gray-50/50 dark:bg-slate-900/30 border-t border-gray-100 dark:border-gray-800">
                <button
                    type="button"
                    onclick="closeSsoModal()"
                    class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-colors focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 dark:focus:ring-offset-slate-900"
                >
                    Mengerti, Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        function openSsoModal() {
            const modal = document.getElementById('ssoModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeSsoModal() {
            const modal = document.getElementById('ssoModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }
        document.getElementById('ssoModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeSsoModal();
            }
        });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('ssoModal');
                if (!modal.classList.contains('hidden')) {
                    closeSsoModal();
                }
            }
        });
    </script>
@include('partials.sso-connected-modal')
</body>
</html>