            {{-- TABEL DESKTOP --}}
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-[#1a1a1a] border-b border-gray-100 dark:border-gray-800">
                            <template x-for="(col, key) in columns" :key="key">
                                <th x-show="col.visible"
                                    @click="sortBy(col.sort)"
                                    :class="col.sort ? 'cursor-pointer hover:text-blue-600' : ''"
                                    class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400 select-none">
                                    <div class="flex items-center gap-1">
                                        <span x-text="col.short"></span>
                                        <span x-show="col.sort" x-html="sortIcon(col.sort)"></span>
                                    </div>
                                </th>
                            </template>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        <template x-for="p in paginatedPegawai" :key="p.id">
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-[#18181b] transition-colors">
                                <td x-show="columns.nama.visible" class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="shrink-0 w-9 h-9 rounded-full overflow-hidden border border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-slate-800 flex items-center justify-center">
                                            <img x-show="p.foto" :src="p.foto ? storageBase + p.foto : ''" class="w-full h-full object-cover" :alt="p.nama">
                                            <svg x-show="!p.foto" class="w-5 h-5 text-gray-400 dark:text-gray-600" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                                            </svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-slate-900 dark:text-white leading-tight" x-text="namaLengkap(p)"></p>
                                            <p x-show="!columns.nik.visible" class="text-xs text-gray-400 dark:text-gray-500 leading-tight mt-0.5" x-text="p.nik"></p>
                                        </div>
                                    </div>
                                </td>
                                <td x-show="columns.nip.visible" class="px-6 py-3 text-sm font-mono text-gray-600 dark:text-gray-400 whitespace-nowrap" x-text="p.nip"></td>
                                <td x-show="columns.mkg.visible" class="px-6 py-3 text-sm text-slate-700 dark:text-gray-300 whitespace-nowrap font-medium" x-text="getMkgDisplay(p)"></td>
                                <td x-show="columns.jabatan.visible" class="px-6 py-3 text-sm text-gray-500 dark:text-gray-400" x-text="p.jabatan ? p.jabatan.nama_jabatan : '-'"></td>
                                <td x-show="columns.bidang.visible" class="px-6 py-3 text-sm whitespace-nowrap">
                                    <template x-if="p.bidang">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-mono font-bold bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-200 dark:border-blue-800/60 uppercase">
                                            <span x-text="p.bidang.singkatan"></span>
                                        </span>
                                    </template>
                                    <template x-if="!p.bidang">
                                        <span class="text-gray-400 dark:text-gray-500 text-xs">-</span>
                                    </template>
                                </td>
                                <td x-show="columns.status.visible" class="px-6 py-3 text-sm">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold whitespace-nowrap" :class="statusClass(p.status_kepegawaian)" x-text="p.status_kepegawaian"></span>
                                </td>
                                <td x-show="columns.status_kerja.visible" class="px-6 py-3 text-sm">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold whitespace-nowrap" :class="kerjaClass(p.status_kerja)" x-text="p.status_kerja || 'Aktif'"></span>
                                </td>
                                <td x-show="columns.golongan.visible" class="px-6 py-3 text-sm text-slate-700 dark:text-gray-300 whitespace-nowrap" x-text="golonganText(p.golongan)"></td>
                                <td x-show="columns.ttl.visible" class="px-6 py-3 text-sm text-gray-600 dark:text-gray-400 whitespace-nowrap" x-text="ttlText(p)"></td>
                                <td x-show="columns.jk.visible" class="px-6 py-3 text-sm text-gray-600 dark:text-gray-400 whitespace-nowrap" x-text="p.jenis_kelamin || '-'"></td>
                                <td x-show="columns.agama.visible" class="px-6 py-3 text-sm text-gray-600 dark:text-gray-400 whitespace-nowrap" x-text="p.agama || '-'"></td>
                                <td x-show="columns.nik.visible" class="px-6 py-3 text-sm font-mono text-gray-600 dark:text-gray-400 whitespace-nowrap" x-text="p.nik || '-'"></td>
                                <td x-show="columns.pernikahan.visible" class="px-6 py-3 text-sm text-gray-600 dark:text-gray-400 whitespace-nowrap" x-text="p.status_pernikahan || '-'"></td>
                                <td x-show="columns.alamat.visible" class="px-6 py-3 text-sm text-gray-600 dark:text-gray-400 min-w-[200px]" x-text="p.alamat || '-'"></td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <div class="relative group">
                                            <button @click="openEditModal(p)" type="button" aria-label="Edit pegawai" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-blue-600 hover:text-blue-800 hover:bg-blue-50 dark:text-blue-400 dark:hover:text-blue-300 dark:hover:bg-blue-900/30 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </button>
                                            <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1.5 px-2 py-0.5 text-[10px] font-medium bg-gray-800 text-white rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">Edit</span>
                                        </div>
                                        <div class="relative group">
                                            <button @click="confirmDelete(p.id)" type="button" aria-label="Hapus pegawai" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-red-500 hover:text-red-700 hover:bg-red-50 dark:text-red-400 dark:hover:text-red-300 dark:hover:bg-red-900/30 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                            <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1.5 px-2 py-0.5 text-[10px] font-medium bg-gray-800 text-white rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">Hapus</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <template x-if="paginatedPegawai.length === 0">
                            <tr>
                                <td :colspan="visibleColumnCount + 1" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">Tidak ada data yang ditemukan.</td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
