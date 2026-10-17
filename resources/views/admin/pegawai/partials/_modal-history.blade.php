                    {{-- TAB RIWAYAT --}}
                    <div x-show="activeTab === 'history'" x-cloak>
                        <div class="overflow-x-auto rounded-xl border border-gray-100 dark:border-gray-800">
                            <table class="w-full text-left border-collapse" style="min-width: 1100px;">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-[#1a1a1a] border-b border-gray-100 dark:border-gray-800">
                                        <th class="px-3 py-3 text-xs font-semibold text-gray-600 dark:text-gray-400 whitespace-nowrap">Tgl berlaku</th>
                                        <th class="px-3 py-3 text-xs font-semibold text-gray-600 dark:text-gray-400 whitespace-nowrap">NIP</th>
                                        <th class="px-3 py-3 text-xs font-semibold text-gray-600 dark:text-gray-400 whitespace-nowrap">NIK</th>
                                        <th class="px-3 py-3 text-xs font-semibold text-gray-600 dark:text-gray-400 whitespace-nowrap">Nama lengkap</th>
                                        <th class="px-3 py-3 text-xs font-semibold text-gray-600 dark:text-gray-400 whitespace-nowrap">Tempat, tgl lahir</th>
                                        <th class="px-3 py-3 text-xs font-semibold text-gray-600 dark:text-gray-400 whitespace-nowrap">Kelamin</th>
                                        <th class="px-3 py-3 text-xs font-semibold text-gray-600 dark:text-gray-400 whitespace-nowrap">Agama</th>
                                        <th class="px-3 py-3 text-xs font-semibold text-gray-600 dark:text-gray-400 whitespace-nowrap">Bidang / unit</th>
                                        <th class="px-3 py-3 text-xs font-semibold text-gray-600 dark:text-gray-400 whitespace-nowrap">Jabatan</th>
                                        <th class="px-3 py-3 text-xs font-semibold text-gray-600 dark:text-gray-400 whitespace-nowrap">Gol</th>
                                        <th class="px-3 py-3 text-xs font-semibold text-gray-600 dark:text-gray-400 whitespace-nowrap">Status</th>
                                        <th class="px-3 py-3 text-xs font-semibold text-gray-600 dark:text-gray-400 whitespace-nowrap">Keaktifan</th>
                                        <th class="px-3 py-3 text-xs font-semibold text-gray-600 dark:text-gray-400 whitespace-nowrap">Pernikahan</th>
                                        <th class="px-3 py-3 text-xs font-semibold text-gray-600 dark:text-gray-400 whitespace-nowrap">Alamat</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                    <template x-for="h in histories" :key="h.id">
                                        <tr class="hover:bg-gray-50/50 dark:hover:bg-[#18181b] transition-colors">
                                            <td class="px-3 py-3 text-xs text-slate-900 dark:text-white whitespace-nowrap" x-text="fmtTgl(h.tanggal_berlaku)"></td>
                                            <td class="px-3 py-3 text-xs font-mono text-gray-600 dark:text-gray-400 whitespace-nowrap" x-text="h.nip || '-'"></td>
                                            <td class="px-3 py-3 text-xs font-mono text-gray-600 dark:text-gray-400 whitespace-nowrap" x-text="h.nik || '-'"></td>
                                            <td class="px-3 py-3 text-xs text-gray-600 dark:text-gray-400 whitespace-nowrap" x-text="namaLengkap(h)"></td>
                                            <td class="px-3 py-3 text-xs text-gray-600 dark:text-gray-400 whitespace-nowrap" x-text="ttlText(h)"></td>
                                            <td class="px-3 py-3 text-xs text-gray-600 dark:text-gray-400 whitespace-nowrap" x-text="h.jenis_kelamin || '-'"></td>
                                            <td class="px-3 py-3 text-xs text-gray-600 dark:text-gray-400 whitespace-nowrap" x-text="h.agama || '-'"></td>
                                            <td class="px-3 py-3 text-xs text-gray-600 dark:text-gray-400"
                                                x-text="h.bidang ? h.bidang.nama_bidang + (h.bidang.singkatan ? ' (' + h.bidang.singkatan + ')' : '') : '-'"></td>
                                            <td class="px-3 py-3 text-xs text-gray-600 dark:text-gray-400"
                                                x-text="h.jabatan ? h.jabatan.nama_jabatan + ' (' + h.jabatan.jenis_jabatan + ')' : '-'"></td>
                                            <td class="px-3 py-3 text-xs text-gray-600 dark:text-gray-400 whitespace-nowrap" x-text="h.golongan || '-'"></td>
                                            <td class="px-3 py-3 text-xs text-gray-600 dark:text-gray-400 whitespace-nowrap" x-text="h.status_kepegawaian || '-'"></td>
                                            <td class="px-3 py-3 text-xs text-gray-600 dark:text-gray-400 whitespace-nowrap" x-text="h.status_kerja || 'Aktif'"></td>
                                            <td class="px-3 py-3 text-xs text-gray-600 dark:text-gray-400 whitespace-nowrap" x-text="h.status_pernikahan || '-'"></td>
                                            <td class="px-3 py-3 text-xs text-gray-600 dark:text-gray-400" style="min-width:160px;" x-text="h.alamat || '-'"></td>
                                        </tr>
                                    </template>
                                    <template x-if="histories.length === 0">
                                        <tr>
                                            <td colspan="14" class="px-4 py-10 text-center text-sm text-gray-500 dark:text-gray-400">Belum ada riwayat perubahan data.</td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-5 p-4 bg-blue-50 dark:bg-blue-900/10 rounded-xl border border-blue-100 dark:border-blue-900/20">
                            <div class="flex gap-3">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="text-xs text-blue-700 dark:text-blue-400 leading-relaxed">
                                    Riwayat ini otomatis mencatat kondisi data sebelum setiap perubahan dilakukan.
                                    Data yang muncul saat pencetakan dokumen akan disesuaikan dengan riwayat yang berlaku pada tanggal dokumen tersebut.
                                </p>
                            </div>
                        </div>
                    </div>
