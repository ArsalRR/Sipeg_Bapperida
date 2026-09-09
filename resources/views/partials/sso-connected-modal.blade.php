@php
    $ssoSites = config('sso_sites', []);
@endphp

@if (session('success'))
    <div
        id="ssoConnectedModal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 sm:p-6"
    >
        <div
            id="ssoConnectedModalBox"
            class="relative w-full max-w-lg bg-white dark:bg-[#111111] rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-800 overflow-hidden"
        >
            <div class="px-6 pt-8 pb-4">
                <div class="flex items-start justify-between">
                    <div class="space-y-2">
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white">
                            Pendaftaran Berhasil
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Akun Anda telah berhasil dibuat dan dapat digunakan di beberapa situs berikut
                        </p>
                    </div>
                    <button
                        type="button"
                        onclick="document.getElementById('ssoConnectedModal').remove()"
                        aria-label="Tutup"
                        class="flex-shrink-0 ml-4 p-2 rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
           <div class="px-6 pb-5">
    <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 border-2 border-emerald-300 dark:border-emerald-600 rounded-xl">
        <div class="flex gap-3">
            <div class="flex-shrink-0 mt-0.5">
                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-emerald-800 dark:text-emerald-200">
                    {{ session('success') }}
                </p>
            </div>
        </div>
    </div>
</div>

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
                    onclick="document.getElementById('ssoConnectedModal').remove()"
                    class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-colors focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 dark:focus:ring-offset-slate-900"
                >
                    Mengerti, Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        (function () {
            var overlay = document.getElementById('ssoConnectedModal');
            var box = document.getElementById('ssoConnectedModalBox');
            if (!overlay || !box) return;

            overlay.addEventListener('click', function (e) {
                if (e.target === overlay) {
                    overlay.remove();
                }
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && document.getElementById('ssoConnectedModal')) {
                    document.getElementById('ssoConnectedModal').remove();
                }
            });
        })();
    </script>
@endif