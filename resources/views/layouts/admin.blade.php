<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') - SIMPEG ASN</title>
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

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-[#f8fafc] dark:bg-[#0a0a0a] text-slate-800 dark:text-slate-200 font-sans antialiased flex h-screen overflow-hidden transition-colors duration-300 isolate">
    <aside class="w-64 bg-white dark:bg-[#111111] border-r border-gray-200 dark:border-gray-800 flex flex-col transition-all duration-300 shrink-0 absolute z-30 h-full -translate-x-full md:relative md:translate-x-0" id="sidebar">
        <div class="h-20 flex items-center justify-between px-6 shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-lg shadow-sm shrink-0">
                    S
                </div>
                <span class="font-bold text-2xl tracking-tight text-slate-900 dark:text-white">SIMPEG</span>
            </div>

            <button class="md:hidden text-gray-400 hover:text-gray-600 dark:hover:text-white transition-colors p-2 shrink-0" onclick="toggleSidebar()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-lg {{ request()->routeIs('dashboard') ? 'text-blue-600 bg-blue-50 dark:bg-blue-600/10' : 'text-gray-500 dark:text-gray-400 hover:text-slate-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800/50' }} transition-colors">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard
            </a>
            @if(in_array(auth()->user()->role, ['admin', 'superadmin']))
            <a href="{{ route('admin.pegawais.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.pegawais.*') ? 'text-blue-600 bg-blue-50 dark:bg-blue-600/10' : 'text-gray-500 dark:text-gray-400 hover:text-slate-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800/50' }} transition-colors">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Data Pegawai
            </a>
            <a href="{{ route('admin.jabatans.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.jabatans.index') ? 'text-blue-600 bg-blue-50 dark:bg-blue-600/10' : 'text-gray-500 dark:text-gray-400 hover:text-slate-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800/50' }} transition-colors">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                Data Jabatan
            </a>
            <a href="{{ route('admin.jabatans.peta') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.jabatans.peta') ? 'text-blue-600 bg-blue-50 dark:bg-blue-600/10' : 'text-gray-500 dark:text-gray-400 hover:text-slate-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800/50' }} transition-colors">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                Peta Jabatan
            </a>
            @endif

            <a href="{{ route('keluarga.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('keluarga.*') ? 'text-blue-600 bg-blue-50 dark:bg-blue-600/10' : 'text-gray-500 dark:text-gray-400 hover:text-slate-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800/50' }} transition-colors">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Data Keluarga
            </a>
            <a href="{{ route('dokumen.kp4') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('dokumen.kp4') ? 'text-blue-600 bg-blue-50 dark:bg-blue-600/10' : 'text-gray-500 dark:text-gray-400 hover:text-slate-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800/50' }} transition-colors">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Form KP4
            </a>

            <a href="{{ route('dokumen.file.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('dokumen.file.*') ? 'text-blue-600 bg-blue-50 dark:bg-blue-600/10' : 'text-gray-500 dark:text-gray-400 hover:text-slate-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800/50' }} transition-colors">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 002 2z"></path></svg>
                Menu File
            </a>
            <a href="{{ route('profile.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('profile.*') ? 'text-blue-600 bg-blue-50 dark:bg-blue-600/10' : 'text-gray-500 dark:text-gray-400 hover:text-slate-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800/50' }} transition-colors">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Edit Profile
            </a>

            @if(auth()->user()->role === 'superadmin')
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.users.*') ? 'text-blue-600 bg-blue-50 dark:bg-blue-600/10' : 'text-gray-500 dark:text-gray-400 hover:text-slate-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800/50' }} transition-colors">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Data User
            </a>
            @endif
        </nav>
    </aside>
    <div id="sidebar-overlay" class="fixed inset-0 bg-slate-900/50 dark:bg-black/60 z-20 hidden md:hidden transition-opacity opacity-0 backdrop-blur-sm" onclick="toggleSidebar()"></div>
    <div class="flex-1 flex flex-col h-screen overflow-hidden relative">
        <header class="h-20 flex items-center justify-between px-6 lg:px-10 shrink-0 z-10 bg-[#f8fafc]/90 dark:bg-[#0a0a0a]/90 backdrop-blur-md border-b border-gray-200 dark:border-gray-800 transition-colors duration-300">
            <div class="flex items-center gap-4 min-w-0">
                <button class="shrink-0 text-gray-500 dark:text-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg p-2 transition-colors hover:bg-gray-100 dark:hover:bg-gray-800/50" onclick="toggleSidebar()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <div class="hidden sm:flex flex-col min-w-0">
                    <span class="text-xs text-gray-400 dark:text-gray-500 font-semibold tracking-wider uppercase truncate">SIMPEG ASN</span>
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white truncate">@yield('title', 'Dashboard')</h2>
                </div>
            </div>

            <div class="flex items-center gap-3 sm:gap-6 shrink-0">
                <div class="bg-white dark:bg-[#111111] p-1 rounded-full border border-gray-200 dark:border-gray-800 shadow-sm flex items-center justify-center h-10 w-10 shrink-0">
                    <x-theme-toggle />
                </div>
                <button class="relative shrink-0 p-2 text-gray-400 hover:text-slate-900 dark:hover:text-white transition-colors bg-white dark:bg-[#111111] border border-gray-200 dark:border-gray-800 rounded-full h-10 w-10 flex items-center justify-center shadow-sm">
                    <span class="absolute top-0 right-0 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white dark:border-[#111111]"></span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                </button>
                <div class="flex items-center gap-3 pl-2 sm:pl-4 border-l border-gray-200 dark:border-gray-800 shrink-0">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-slate-900 dark:text-white leading-tight">
                            {{ auth()->user()->pegawai?->nama_lengkap ?? auth()->user()->username }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 capitalize">{{ auth()->user()->role ?? 'Admin' }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-slate-900 dark:bg-gray-800 flex items-center justify-center text-white font-bold shadow-sm border border-gray-200 dark:border-gray-700 shrink-0">
                        {{ substr(auth()->user()->pegawai?->nama ?? auth()->user()->username, 0, 1) }}
                    </div>

                    <form method="POST" action="{{ route('logout') }}" class="ml-1 shrink-0">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-red-500 dark:hover:text-red-400 transition-colors p-2 rounded-full hover:bg-red-50 dark:hover:bg-red-900/20" title="Logout">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        </header>
        <main class="flex-1 overflow-y-auto p-6 lg:p-10 transition-colors duration-300 relative z-0">
            @yield('content')
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            if (window.innerWidth < 768) {
                // Mobile behavior: toggle translation
                sidebar.classList.toggle('-translate-x-full');

                // Handle overlay opacity and display
                if (sidebar.classList.contains('-translate-x-full')) {
                    // Hiding sidebar
                    overlay.classList.remove('opacity-100');
                    overlay.classList.add('opacity-0');
                    setTimeout(() => {
                        overlay.classList.add('hidden');
                    }, 300); // match transition duration
                } else {
                    // Showing sidebar
                    overlay.classList.remove('hidden');
                    // Small delay to allow display to apply before opacity transition
                    setTimeout(() => {
                        overlay.classList.remove('opacity-0');
                        overlay.classList.add('opacity-100');
                    }, 10);
                }
            } else {
                // Desktop behavior: toggle negative margin
                sidebar.classList.toggle('md:-ml-64');
            }
        }

        // Global SweetAlert2 logic
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session('error') }}',
                    timer: 3000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            @endif

            @if($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan Input',
                    text: 'Mohon periksa kembali form Anda.',
                    timer: 3000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            @endif
        });
    </script>
    @stack('modals')
</body>
</html>