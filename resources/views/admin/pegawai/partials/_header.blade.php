        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Daftar Pegawai</h2>
            <div class="flex flex-wrap items-center gap-2">
                <div class="relative" @click.outside="exportOpen = false" @keydown.escape.window="exportOpen = false">
                    <button @click="exportOpen = !exportOpen" type="button"
                        class="px-4 py-2 bg-slate-700 hover:bg-slate-800 text-white font-semibold rounded-lg shadow-sm transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Export
                        <svg class="w-3.5 h-3.5 transition-transform" :class="exportOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <div x-show="exportOpen" x-transition x-cloak
                        class="absolute right-0 mt-2 w-44 bg-white dark:bg-[#18181b] border border-gray-200 dark:border-gray-700 rounded-xl shadow-xl z-50 p-1.5">
                        <button @click="exportExcel(); exportOpen = false" type="button"
                            class="w-full flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-slate-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors">
                            <span class="w-2 h-2 rounded-full bg-emerald-600"></span> Excel (.xls)
                        </button>
                        <button @click="exportPdf(); exportOpen = false" type="button"
                            class="w-full flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-slate-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors">
                            <span class="w-2 h-2 rounded-full bg-rose-600"></span> PDF (.pdf)
                        </button>
                    </div>
                </div>

                <button @click="openCreateModal()" type="button" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-sm transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Tambah Pegawai
                </button>
            </div>
        </div>
