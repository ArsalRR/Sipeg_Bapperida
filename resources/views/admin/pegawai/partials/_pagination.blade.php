            {{-- PAGINASI --}}
            <div class="p-4 border-t border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row justify-between items-center gap-4">
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    Menampilkan <span x-text="filteredPegawai.length > 0 ? ((safePage - 1) * perPageNumber) + 1 : 0"></span>
                    sampai <span x-text="Math.min(safePage * perPageNumber, filteredPegawai.length)"></span>
                    dari <span x-text="filteredPegawai.length"></span> baris
                </span>

                <div class="flex items-center gap-1 flex-wrap justify-center">
                    <button @click="if (safePage > 1) currentPage = safePage - 1" type="button" :disabled="safePage <= 1" class="px-3 py-1.5 border border-gray-300 dark:border-gray-700 rounded-lg text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        Sebelumnya
                    </button>
                    <template x-for="(page, idx) in visiblePages" :key="idx">
                        <button @click="if (page !== '…') currentPage = page" type="button" :disabled="page === '…'"
                            :class="safePage === page ? 'bg-blue-600 text-white border-blue-600' : 'border-gray-300 dark:border-gray-700 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800'"
                            class="px-3 py-1.5 border rounded-lg text-sm transition-colors" x-text="page">
                        </button>
                    </template>
                    <button @click="if (safePage < totalPages) currentPage = safePage + 1" type="button" :disabled="safePage >= totalPages" class="px-3 py-1.5 border border-gray-300 dark:border-gray-700 rounded-lg text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        Selanjutnya
                    </button>
                </div>
            </div>
