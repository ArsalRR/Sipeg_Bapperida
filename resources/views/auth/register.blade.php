<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Akun - SIMPEG ASN</title>
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
                <h1 class="text-6xl font-extrabold tracking-tight mb-6">Bergabung Bersama Kami! 🚀</h1>
                <p class="text-lg text-blue-100/90 leading-relaxed font-light">
                    Daftarkan akun Anda untuk mulai mengelola data kepegawaian secara mandiri dan efisien.
                </p>
            </div>

            <div class="z-10">
                <p class="text-sm text-blue-200/60">&copy; 2026 SIMPEG ASN. Hak Cipta Dilindungi.</p>
            </div>
        </div>
        <div class="w-full lg:w-1/2 bg-white dark:bg-slate-950 relative flex flex-col transition-colors duration-300">
            
            <div class="absolute top-6 right-6 z-10">
                <x-theme-toggle />
            </div>

            <div class="lg:hidden absolute top-6 left-6 flex items-center gap-2">
                <div class="w-8 h-8 bg-blue-700 dark:bg-blue-600 rounded-md flex items-center justify-center text-white font-bold text-lg shadow-sm">
                    A
                </div>
                <span class="font-bold text-xl tracking-tight text-slate-900 dark:text-white">SIMPEG</span>
            </div>

            <div class="flex flex-col justify-center items-center min-h-screen px-8 sm:px-12 py-20">
                <div class="w-full max-w-md">
                    <h2 class="text-3xl font-bold text-slate-900 dark:text-white mb-2">Pendaftaran Akun Baru</h2>
                    <p class="text-gray-500 dark:text-gray-400 mb-8 text-sm">Lengkapi data di bawah ini untuk mendaftar.</p>

                    <form method="POST" action="{{ route('register') }}" class="w-full mt-8 space-y-5">
                        @csrf
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700 dark:text-gray-300 mb-2">Nama Lengkap</label>
                            <input id="name" name="name" type="text" required class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-colors shadow-sm" value="{{ old('name') }}">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="username" class="block text-sm font-medium text-slate-700 dark:text-gray-300 mb-2">Username</label>
                            <input id="username" name="username" type="text" required class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-colors shadow-sm" value="{{ old('username') }}">
                            @error('username')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700 dark:text-gray-300 mb-2">Email</label>
                            <input id="email" name="email" type="email" required class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-colors shadow-sm" value="{{ old('email') }}">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-medium text-slate-700 dark:text-gray-300 mb-2">Kata Sandi</label>
                            <input id="password" name="password" type="password" required class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-colors shadow-sm">
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-gray-300 mb-2">Konfirmasi Kata Sandi</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" required class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-colors shadow-sm">
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full flex justify-center py-3 px-4 rounded-md shadow-sm text-sm font-semibold text-white bg-slate-900 dark:bg-blue-600 hover:bg-slate-800 dark:hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-900 dark:focus:ring-blue-600 transition-all transform hover:-translate-y-0.5">
                                Daftar Sekarang
                            </button>

                            <p class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
                                Sudah memiliki akun? 
                                <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:text-blue-500 dark:text-blue-400">Masuk di sini</a>
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

</body>
</html>
