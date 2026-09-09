<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Informasi Kepegawaian ASN</title>
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
<body class="bg-gray-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 font-sans antialiased selection:bg-corporate-blue selection:text-white flex flex-col min-h-screen transition-colors duration-300">
    

    <nav class="w-full bg-white dark:bg-slate-900 shadow-sm border-b border-gray-200 dark:border-slate-800 sticky top-0 z-50 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-corporate-blue rounded-lg flex items-center justify-center text-white font-bold text-xl shadow-sm">
                        A
                    </div>
                    <span class="font-bold text-xl tracking-tight text-corporate-navy dark:text-white transition-colors duration-300">SIMPEG</span>
                </div>
                <div class="flex space-x-4 sm:space-x-6 items-center">
                    <x-theme-toggle />
                    <a href="/" class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-corporate-blue dark:hover:text-white transition-colors">Home</a>
                    <a href="/login" class="text-sm font-medium px-5 py-2 bg-corporate-blue text-white rounded-md hover:bg-corporate-blue-hover transition-colors shadow-sm ring-1 ring-inset ring-transparent hover:ring-blue-300">
                        Login SSO
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-1 flex flex-col justify-center transition-colors duration-300 bg-gradient-to-br from-slate-100 to-white dark:from-corporate-navy dark:to-slate-900">
        <div class="relative isolate px-6 lg:px-8 overflow-hidden min-h-[calc(100vh-4rem)] flex items-center">
            <div class="absolute inset-x-0 top-0 -z-10 transform-gpu overflow-hidden blur-3xl" aria-hidden="true">
                <div class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-corporate-blue to-[#3b82f6] opacity-20 dark:opacity-30 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]"></div>
            </div>
            
            <div class="mx-auto max-w-3xl py-16 text-center">
                <div class="mb-8 flex justify-center">
                    <span class="relative rounded-full px-3 py-1 text-sm leading-6 text-slate-600 dark:text-gray-300 ring-1 ring-slate-900/10 dark:ring-white/10 hover:ring-slate-900/20 dark:hover:ring-white/20 transition-all">
                        Versi Enterprise 1.0 <a href="#" class="font-semibold text-corporate-blue dark:text-white"><span class="absolute inset-0" aria-hidden="true"></span>Lebih Lanjut <span aria-hidden="true">&rarr;</span></a>
                    </span>
                </div>
                <h1 class="text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-6xl drop-shadow-sm transition-colors duration-300">
                    Sistem Informasi Kepegawaian ASN
                </h1>
                <p class="mt-6 text-lg leading-8 text-slate-600 dark:text-gray-300 transition-colors duration-300">
                    Platform terpusat dan modular untuk mengelola data aparatur negara. Dibangun dengan fokus pada keamanan tingkat tinggi, integrasi REST API, dan pengalaman pengguna yang modern.
                </p>
                <div class="mt-10 flex items-center justify-center gap-x-6">
                    <a href="/login" class="rounded-lg bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-md hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-all transform hover:-translate-y-0.5">
                        Akses Portal Kepegawaian
                    </a>
                    <a href="#dokumentasi" class="text-sm font-semibold leading-6 text-slate-700 dark:text-white hover:text-corporate-blue dark:hover:text-gray-300 transition-colors">
                        Dokumentasi API <span aria-hidden="true">→</span>
                    </a>
                </div>
            </div>
        </div>
    </main>

</body>
</html>
