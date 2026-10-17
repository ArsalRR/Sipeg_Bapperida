            {{-- TOOLBAR: per halaman, cari, kolom --}}
            <div class="p-4 border-b border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row justify-between gap-4">
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Tampilkan</span>
                    <select x-model.number="perPage" class="border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white px-3 py-1.5 focus:ring-2 focus:ring-blue-600 outline-none">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="100">100</option>
                    </select>
                    <span class="text-sm text-gray-500 dark:text-gray-400">baris</span>
                </div>

                <div class="flex items-center gap-3">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" x-model="search" placeholder="Cari NIP, NIK, atau Nama..." class="w-full sm:w-64 pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                    </div>

                    {{-- Pilih kolom --}}
                    <div class="relative" @click.outside="showColumnDropdown = false">
                        <button @click="showColumnDropdown = !showColumnDropdown" type="button" class="px-3.5 py-2 bg-white dark:bg-slate-900 border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-slate-800 text-slate-700 dark:text-gray-300 font-medium rounded-lg text-sm transition-colors flex items-center gap-2 shadow-sm">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span>Kolom</span>
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        <div x-show="showColumnDropdown" x-transition x-cloak class="absolute right-0 mt-2 w-56 bg-white dark:bg-[#18181b] border border-gray-200 dark:border-gray-700 rounded-xl shadow-xl z-50 p-3 space-y-2">
                            <div class="text-xs font-semibold text-gray-400 dark:text-gray-500 px-1 pb-1 border-b border-gray-100 dark:border-gray-800">Tampilkan kolom</div>
                            <template x-for="(col, key) in columns" :key="key">
                                <label class="flex items-center gap-2.5 px-1 py-1 hover:bg-gray-50 dark:hover:bg-slate-800/60 rounded-md cursor-pointer text-sm text-slate-700 dark:text-gray-300 select-none">
                                    <input type="checkbox" x-model="columns[key].visible" class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-700 bg-white dark:bg-slate-900">
                                    <span x-text="col.label"></span>
                                </label>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
