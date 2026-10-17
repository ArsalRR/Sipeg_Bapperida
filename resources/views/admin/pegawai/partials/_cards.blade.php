            {{-- LAYAR MOBILE: KARTU ACCORDION --}}
            <div class="block sm:hidden p-3 space-y-3">
                <template x-for="p in paginatedPegawai" :key="p.id">
                    <div class="bg-white dark:bg-[#18181b] border border-gray-200 dark:border-gray-800 rounded-2xl overflow-hidden shadow-sm">

                        <button @click="expandedId = (expandedId === p.id ? null : p.id)" type="button" class="w-full p-4 flex items-center justify-between text-left hover:bg-gray-50 dark:hover:bg-slate-800/40 transition-colors">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="shrink-0 w-11 h-11 rounded-full overflow-hidden border border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-slate-800 flex items-center justify-center">
                                    <img x-show="p.foto" :src="p.foto ? storageBase + p.foto : ''" class="w-full h-full object-cover" :alt="p.nama">
                                    <svg x-show="!p.foto" class="w-6 h-6 text-gray-400 dark:text-gray-600" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                                    </svg>
                                </div>

                                <div class="min-w-0 flex-1">
                                    <h3 x-show="columns.nama.visible" class="text-sm font-bold text-slate-900 dark:text-white truncate leading-tight" x-text="namaLengkap(p)"></h3>
                                    <p x-show="columns.nip.visible" class="text-xs font-mono text-gray-500 dark:text-gray-400 mt-0.5" x-text="'NIP. ' + (p.nip || '-')"></p>
                                    <div class="flex items-center gap-1.5 mt-1.5 flex-wrap">
                                        <span x-show="columns.status.visible" class="px-2 py-0.5 rounded-full text-[10px] font-semibold whitespace-nowrap" :class="statusClass(p.status_kepegawaian)" x-text="p.status_kepegawaian"></span>
                                        <span x-show="columns.status_kerja.visible" class="px-2 py-0.5 rounded-full text-[10px] font-semibold whitespace-nowrap" :class="kerjaClass(p.status_kerja)" x-text="p.status_kerja || 'Aktif'"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="ml-2 shrink-0 p-1 text-gray-400">
                                <svg class="w-5 h-5 transition-transform duration-200" :class="expandedId === p.id ? 'rotate-180 text-blue-600 dark:text-blue-400' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </button>

                        <div x-show="expandedId === p.id" x-collapse class="px-4 pb-4 pt-2 border-t border-gray-100 dark:border-gray-800/80 space-y-3">
                            <div class="grid grid-cols-2 gap-2 text-xs pt-1">
                                <div x-show="columns.jabatan.visible" class="col-span-2 bg-gray-50 dark:bg-[#111111] p-2.5 rounded-xl border border-gray-100 dark:border-gray-800">
                                    <span class="text-gray-400 dark:text-gray-500 block text-[10px] font-semibold">Jabatan</span>
                                    <span class="font-medium text-slate-800 dark:text-slate-200" x-text="p.jabatan ? p.jabatan.nama_jabatan : '-'"></span>
                                </div>
                                <div x-show="columns.bidang.visible" class="col-span-2 bg-gray-50 dark:bg-[#111111] p-2.5 rounded-xl border border-gray-100 dark:border-gray-800">
                                    <span class="text-gray-400 dark:text-gray-500 block text-[10px] font-semibold">Bidang / Unit</span>
                                    <span class="font-medium text-slate-800 dark:text-slate-200" x-text="p.bidang ? p.bidang.nama_bidang : '-'"></span>
                                </div>
                                <div x-show="columns.mkg.visible" class="bg-gray-50 dark:bg-[#111111] p-2.5 rounded-xl border border-gray-100 dark:border-gray-800">
                                    <span class="text-gray-400 dark:text-gray-500 block text-[10px] font-semibold">Masa Kerja Golongan</span>
                                    <span class="font-medium text-slate-800 dark:text-slate-200" x-text="getMkgDisplay(p)"></span>
                                </div>
                                <div x-show="columns.golongan.visible" class="bg-gray-50 dark:bg-[#111111] p-2.5 rounded-xl border border-gray-100 dark:border-gray-800">
                                    <span class="text-gray-400 dark:text-gray-500 block text-[10px] font-semibold">Golongan</span>
                                    <span class="font-medium text-slate-800 dark:text-slate-200" x-text="golonganText(p.golongan)"></span>
                                </div>
                                <div x-show="columns.nik.visible" class="bg-gray-50 dark:bg-[#111111] p-2.5 rounded-xl border border-gray-100 dark:border-gray-800">
                                    <span class="text-gray-400 dark:text-gray-500 block text-[10px] font-semibold">NIK</span>
                                    <span class="font-mono font-medium text-slate-800 dark:text-slate-200" x-text="p.nik || '-'"></span>
                                </div>
                                <div x-show="columns.ttl.visible" class="bg-gray-50 dark:bg-[#111111] p-2.5 rounded-xl border border-gray-100 dark:border-gray-800">
                                    <span class="text-gray-400 dark:text-gray-500 block text-[10px] font-semibold">Tempat, tgl lahir</span>
                                    <span class="font-medium text-slate-800 dark:text-slate-200" x-text="ttlText(p)"></span>
                                </div>
                                <div x-show="columns.jk.visible" class="bg-gray-50 dark:bg-[#111111] p-2.5 rounded-xl border border-gray-100 dark:border-gray-800">
                                    <span class="text-gray-400 dark:text-gray-500 block text-[10px] font-semibold">Jenis kelamin</span>
                                    <span class="font-medium text-slate-800 dark:text-slate-200" x-text="p.jenis_kelamin || '-'"></span>
                                </div>
                                <div x-show="columns.agama.visible" class="bg-gray-50 dark:bg-[#111111] p-2.5 rounded-xl border border-gray-100 dark:border-gray-800">
                                    <span class="text-gray-400 dark:text-gray-500 block text-[10px] font-semibold">Agama</span>
                                    <span class="font-medium text-slate-800 dark:text-slate-200" x-text="p.agama || '-'"></span>
                                </div>
                                <div x-show="columns.pernikahan.visible" class="bg-gray-50 dark:bg-[#111111] p-2.5 rounded-xl border border-gray-100 dark:border-gray-800">
                                    <span class="text-gray-400 dark:text-gray-500 block text-[10px] font-semibold">Status nikah</span>
                                    <span class="font-medium text-slate-800 dark:text-slate-200" x-text="p.status_pernikahan || '-'"></span>
                                </div>
                                <div x-show="columns.alamat.visible" class="col-span-2 bg-gray-50 dark:bg-[#111111] p-2.5 rounded-xl border border-gray-100 dark:border-gray-800">
                                    <span class="text-gray-400 dark:text-gray-500 block text-[10px] font-semibold">Alamat lengkap</span>
                                    <span class="font-medium text-slate-800 dark:text-slate-200" x-text="p.alamat || '-'"></span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 pt-2">
                                <button @click="openEditModal(p)" type="button" class="flex-1 py-2.5 px-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold flex items-center justify-center gap-1.5 transition-colors shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Edit data
                                </button>
                                <button @click="confirmDelete(p.id)" type="button" class="flex-1 py-2.5 px-3 bg-red-50 hover:bg-red-100 dark:bg-rose-900/20 dark:hover:bg-rose-900/30 text-red-600 dark:text-rose-400 border border-red-200 dark:border-rose-900/40 rounded-xl text-xs font-semibold flex items-center justify-center gap-1.5 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </template>

                <template x-if="paginatedPegawai.length === 0">
                    <div class="p-6 text-center text-gray-500 dark:text-gray-400 text-sm">Tidak ada data yang ditemukan.</div>
                </template>
            </div>
