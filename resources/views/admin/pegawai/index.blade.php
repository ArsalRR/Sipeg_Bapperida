@extends('layouts.admin')

@section('title', 'Manajemen Pegawai')

@section('content')
<div x-data="pegawaiCrud()">
    <div class="max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Daftar Pegawai</h2>
        <div class="flex flex-wrap items-center gap-2">
            <button @click="exportExcel()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg shadow-sm transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export Excel
            </button>
            <button @click="printPdf()" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-sm transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak PDF
            </button>
            <button @click="openCreateModal()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-sm transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Tambah Pegawai
            </button>
        </div>
    </div>

    <div class="bg-white dark:bg-[#111111] rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">


        <div class="p-4 border-b border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-500 dark:text-gray-400">Tampilkan</span>
                <select x-model="perPage" class="border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white px-3 py-1.5 focus:ring-2 focus:ring-blue-600 outline-none">
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

                {{-- Column Toggle Dropdown --}}
                <div class="relative" @click.outside="showColumnDropdown = false">
                    <button @click="showColumnDropdown = !showColumnDropdown" class="px-3.5 py-2 bg-white dark:bg-slate-900 border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-slate-800 text-slate-700 dark:text-gray-300 font-medium rounded-lg text-sm transition-colors flex items-center gap-2 shadow-sm">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span>Kolom</span>
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <div x-show="showColumnDropdown" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-56 bg-white dark:bg-[#18181b] border border-gray-200 dark:border-gray-700 rounded-xl shadow-xl z-50 p-3 space-y-2">
                        <div class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider px-1 pb-1 border-b border-gray-100 dark:border-gray-800">Tampilkan Kolom</div>
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
        <div class="overflow-x-auto" id="printable-area">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-[#1a1a1a] border-b border-gray-100 dark:border-gray-800">
                        <th x-show="columns.nama.visible" @click="sortBy('nama')" class="cursor-pointer px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-blue-600 select-none">
                            <div class="flex items-center gap-1">Nama <span x-html="sortIcon('nama')"></span></div>
                        </th>
                        <th x-show="columns.nip.visible" @click="sortBy('nip')" class="cursor-pointer px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-blue-600 select-none">
                            <div class="flex items-center gap-1">NIP <span x-html="sortIcon('nip')"></span></div>
                        </th>
                        <th x-show="columns.jabatan.visible" @click="sortBy('jabatan_nama')" class="cursor-pointer px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-blue-600 select-none">
                            <div class="flex items-center gap-1">Jabatan <span x-html="sortIcon('jabatan_nama')"></span></div>
                        </th>
                        <th x-show="columns.status.visible" @click="sortBy('status_kepegawaian')" class="cursor-pointer px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-blue-600 select-none">
                            <div class="flex items-center gap-1">Status <span x-html="sortIcon('status_kepegawaian')"></span></div>
                        </th>
                        <th x-show="columns.golongan.visible" @click="sortBy('golongan')" class="cursor-pointer px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-blue-600 select-none">
                            <div class="flex items-center gap-1">Golongan <span x-html="sortIcon('golongan')"></span></div>
                        </th>
                        <th x-show="columns.ttl.visible" class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400">
                            TTL
                        </th>
                        <th x-show="columns.jk.visible" class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400">
                            L/P
                        </th>
                        <th x-show="columns.agama.visible" class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400">
                            Agama
                        </th>
                        <th x-show="columns.nik.visible" class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400">
                            NIK
                        </th>
                        <th x-show="columns.pernikahan.visible" class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400">
                            Status Nikah
                        </th>
                        <th x-show="columns.alamat.visible" class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400">
                            Alamat
                        </th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400 text-right print:hidden">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    <template x-for="p in paginatedPegawai" :key="p.id">
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-[#18181b] transition-colors">
                            {{-- Kolom Nama: foto kecil + nama (+ NIK jika kolom NIK disembunyikan) --}}
                            <td x-show="columns.nama.visible" class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    {{-- Avatar / Foto --}}
                                    <div class="shrink-0 w-9 h-9 rounded-full overflow-hidden border border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-slate-800 flex items-center justify-center">
                                        <img x-show="p.foto" :src="p.foto ? '/storage/' + p.foto : ''" class="w-full h-full object-cover" :alt="p.nama">
                                        <svg x-show="!p.foto" class="w-5 h-5 text-gray-400 dark:text-gray-600" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                                        </svg>
                                    </div>
                                    {{-- Nama & NIK --}}
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-slate-900 dark:text-white leading-tight"
                                            x-text="(p.gelar_depan ? p.gelar_depan + ' ' : '') + p.nama + (p.gelar_belakang ? ', ' + p.gelar_belakang : '')"></p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500 leading-tight mt-0.5" x-text="p.nik"></p>
                                    </div>
                                </div>
                            </td>
                            {{-- Kolom NIP --}}
                            <td x-show="columns.nip.visible" class="px-6 py-3 text-sm font-mono text-gray-600 dark:text-gray-400 whitespace-nowrap" x-text="p.nip"></td>
                            <td x-show="columns.jabatan.visible" class="px-6 py-3 text-sm text-gray-500 dark:text-gray-400" x-text="p.jabatan ? p.jabatan.nama_jabatan : '-'"></td>
                            <td x-show="columns.status.visible" class="px-6 py-3 text-sm">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold"
                                    :class="{
                                        'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400': p.status_kepegawaian === 'PNS',
                                        'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400': p.status_kepegawaian === 'PPPK',
                                        'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400': p.status_kepegawaian === 'CPNS',
                                        'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400': p.status_kepegawaian === 'PPPK Paruh Waktu',
                                        'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400': p.status_kepegawaian === 'Non ASN'
                                    }" x-text="p.status_kepegawaian">
                                </span>
                            </td>
                            <td x-show="columns.golongan.visible" class="px-6 py-3 text-sm text-slate-700 dark:text-gray-300 whitespace-nowrap"
                                x-text="p.golongan ? getGolonganLabel(p.golongan) + ' (' + getGolonganDisplay(p.golongan) + ')' : '-'">
                            </td>
                            <td x-show="columns.ttl.visible" class="px-6 py-3 text-sm text-gray-600 dark:text-gray-400 whitespace-nowrap"
                                x-text="(p.tempat_lahir || '-') + ', ' + (p.tanggal_lahir ? new Date(p.tanggal_lahir).toLocaleDateString('id-ID', {day:'numeric',month:'short',year:'numeric'}) : '-')">
                            </td>
                            <td x-show="columns.jk.visible" class="px-6 py-3 text-sm text-gray-600 dark:text-gray-400 whitespace-nowrap" x-text="p.jenis_kelamin || '-'"></td>
                            <td x-show="columns.agama.visible" class="px-6 py-3 text-sm text-gray-600 dark:text-gray-400 whitespace-nowrap" x-text="p.agama || '-'"></td>
                            <td x-show="columns.nik.visible" class="px-6 py-3 text-sm font-mono text-gray-600 dark:text-gray-400 whitespace-nowrap" x-text="p.nik || '-'"></td>
                            <td x-show="columns.pernikahan.visible" class="px-6 py-3 text-sm text-gray-600 dark:text-gray-400 whitespace-nowrap" x-text="p.status_pernikahan || '-'"></td>
                            <td x-show="columns.alamat.visible" class="px-6 py-3 text-sm text-gray-600 dark:text-gray-400 min-w-[200px]" x-text="p.alamat || '-'"></td>
                            <td class="px-6 py-4 text-right print:hidden">
                                <div class="flex items-center justify-end gap-1">
                                    <div class="relative group">
                                        <button @click="openEditModal(p)" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-blue-600 hover:text-blue-800 hover:bg-blue-50 dark:text-blue-400 dark:hover:text-blue-300 dark:hover:bg-blue-900/30 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1.5 px-2 py-0.5 text-[10px] font-medium bg-gray-800 text-white rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">Edit</span>
                                    </div>
                                    <div class="relative group">
                                        <button @click="confirmDelete('{{ url('/admin/pegawais') }}/' + p.id)" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-red-500 hover:text-red-700 hover:bg-red-50 dark:text-red-400 dark:hover:text-red-300 dark:hover:bg-red-900/30 transition-colors">
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


        <div class="p-4 border-t border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row justify-between items-center gap-4">
            <span class="text-sm text-gray-500 dark:text-gray-400">
                Menampilkan <span x-text="filteredPegawai.length > 0 ? ((currentPage - 1) * perPage) + 1 : 0"></span>
                sampai <span x-text="Math.min(currentPage * perPage, filteredPegawai.length)"></span>
                dari <span x-text="filteredPegawai.length"></span> baris
            </span>

            <div class="flex items-center gap-1">
                <button @click="if(currentPage > 1) currentPage--" :disabled="currentPage === 1" class="px-3 py-1.5 border border-gray-300 dark:border-gray-700 rounded-lg text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    Sebelumnya
                </button>
                <template x-for="page in totalPages" :key="page">
                    <button @click="currentPage = page"
                        :class="currentPage === page ? 'bg-blue-600 text-white border-blue-600' : 'border-gray-300 dark:border-gray-700 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800'"
                        class="px-3 py-1.5 border rounded-lg text-sm transition-colors" x-text="page">
                    </button>
                </template>
                <button @click="if(currentPage < totalPages) currentPage++" :disabled="currentPage === totalPages || totalPages === 0" class="px-3 py-1.5 border border-gray-300 dark:border-gray-700 rounded-lg text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    Selanjutnya
                </button>
            </div>
        </div>
    </div>

    </div>
    <template x-teleport="body">
    <div x-show="modalOpen"
         class="flex items-center justify-center p-6 sm:p-10"
         x-cloak
         style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 99999;"
         @keydown.escape.window="modalOpen = false">

        <div x-show="modalOpen"
             x-transition.opacity
             @click="modalOpen = false"
             class="absolute inset-0 bg-slate-900/60 dark:bg-black/80 backdrop-blur-sm"></div>

        <div x-show="modalOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="relative w-full max-w-4xl max-h-[calc(100vh-3rem)] sm:max-h-[calc(100vh-5rem)] flex flex-col bg-white dark:bg-[#111111] shadow-2xl rounded-2xl border border-gray-100 dark:border-gray-800 transition-all transform z-10 overflow-hidden">


            <div class="flex justify-between items-center gap-4 px-6 py-3.5 border-b border-gray-100 dark:border-gray-800 shrink-0">
                <div class="min-w-0 flex items-baseline gap-2">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white truncate shrink-0" x-text="isEdit ? 'Detail & Edit Data Pegawai' : 'Tambah Pegawai Baru'"></h3>
                    <span class="text-xs text-gray-400 dark:text-gray-500 truncate" x-show="isEdit" x-text="'· NIP ' + form.nip"></span>
                </div>
                <button @click="modalOpen = false" type="button" class="shrink-0 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg p-1.5 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="px-6 pt-3 shrink-0" x-show="isEdit" x-cloak>
                <div class="inline-flex bg-gray-100 dark:bg-slate-900 rounded-lg p-1 gap-1">
                    <button @click="activeTab = 'data'" type="button"
                        :class="activeTab === 'data' ? 'bg-white dark:bg-slate-700 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'"
                        class="px-4 py-1.5 rounded-md font-medium text-sm transition-all">
                        Data Utama
                    </button>
                    <button @click="activeTab = 'history'" type="button"
                        :class="activeTab === 'history' ? 'bg-white dark:bg-slate-700 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'"
                        class="px-4 py-1.5 rounded-md font-medium text-sm transition-all inline-flex items-center gap-1.5">
                        Riwayat Perubahan
                        <span x-show="histories.length" x-text="histories.length" class="text-[10px] leading-none bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 rounded-full px-1.5 py-1"></span>
                    </button>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto px-6 pb-6" :class="isEdit ? 'pt-4' : 'pt-5'">

                <div x-show="activeTab === 'data'">
                    <form :action="formAction" method="POST" enctype="multipart/form-data" id="pegawai-form" @submit="submitting = true">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="flex items-center gap-4 pb-5 mb-5 border-b border-gray-100 dark:border-gray-800">
                        <div class="relative shrink-0 w-20 h-20">
                            <div class="w-20 h-20 rounded-full border-2 border-dashed border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-slate-900 flex items-center justify-center overflow-hidden">
                                <template x-if="photoPreview">
                                    <img :src="photoPreview" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!photoPreview">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </template>
                            </div>
                            <input type="file" name="foto" @change="handlePhotoChange" class="absolute inset-0 w-20 h-20 opacity-0 cursor-pointer rounded-full" accept="image/*">
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-slate-900 dark:text-white">Foto Profil</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Klik lingkaran untuk ganti foto.</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">Format JPEG/PNG, maks 2MB.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                        <div class="space-y-4">
                            <h4 class="flex items-center gap-2 font-semibold text-blue-600 dark:text-blue-400 text-sm mb-1">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                Identitas Utama
                            </h4>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">NIP (18 Digit) <span class="text-red-500">*</span></label>
                                    <input type="text" name="nip" x-model="form.nip" required maxlength="18" pattern="\d{18}" title="Harus 18 digit angka" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">NIK (16 Digit) <span class="text-red-500">*</span></label>
                                    <input type="text" name="nik" x-model="form.nik" required maxlength="16" pattern="\d{16}" title="Harus 16 digit angka" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Nama Lengkap (Sesuai KTP) <span class="text-red-500">*</span></label>
                                <input type="text" name="nama" x-model="form.nama" required autofocus class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Gelar Depan</label>
                                    <input type="text" name="gelar_depan" x-model="form.gelar_depan" placeholder="Dr." class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Gelar Belakang</label>
                                    <input type="text" name="gelar_belakang" x-model="form.gelar_belakang" placeholder="S.Kom" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tempat Lahir <span class="text-red-500">*</span></label>
                                    <input type="text" name="tempat_lahir" x-model="form.tempat_lahir" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tanggal Lahir <span class="text-red-500">*</span></label>
                                    <input type="date" name="tanggal_lahir" x-model="form.tanggal_lahir" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Jenis Kelamin <span class="text-red-500">*</span></label>
                                    <select name="jenis_kelamin" x-model="form.jenis_kelamin" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Agama <span class="text-red-500">*</span></label>
                                    <input type="text" name="agama" x-model="form.agama" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                                </div>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <h4 class="flex items-center gap-2 font-semibold text-blue-600 dark:text-blue-400 text-sm mb-1">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2M5 21h2m10 0h-6"></path></svg>
                                Detail Kepegawaian
                            </h4>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Status Kepegawaian <span class="text-red-500">*</span></label>
                                <select name="status_kepegawaian" x-model="form.status_kepegawaian" required @change="form.golongan = ''" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                                    <option value="PNS">PNS</option>
                                    <option value="PPPK">PPPK</option>
                                    <option value="CPNS">CPNS</option>
                                    <option value="PPPK Paruh Waktu">PPPK Paruh Waktu</option>
                                    <option value="Non ASN">Non ASN</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                    Golongan
                                    <span x-show="form.status_kepegawaian === 'Non ASN'" class="text-gray-400 font-normal">(tidak berlaku)</span>
                                </label>
                                <select x-show="['PNS','CPNS'].includes(form.status_kepegawaian)" :disabled="!['PNS','CPNS'].includes(form.status_kepegawaian)" name="golongan" x-model="form.golongan" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                                    <option value="">-- Pilih Golongan --</option>
                                    <option value="I/a">I/a</option><option value="I/b">I/b</option><option value="I/c">I/c</option><option value="I/d">I/d</option>
                                    <option value="II/a">II/a</option><option value="II/b">II/b</option><option value="II/c">II/c</option><option value="II/d">II/d</option>
                                    <option value="III/a">III/a</option><option value="III/b">III/b</option><option value="III/c">III/c</option><option value="III/d">III/d</option>
                                    <option value="IV/a">IV/a</option><option value="IV/b">IV/b</option><option value="IV/c">IV/c</option><option value="IV/d">IV/d</option><option value="IV/e">IV/e</option>
                                </select>
                                <select x-show="['PPPK','PPPK Paruh Waktu'].includes(form.status_kepegawaian)" :disabled="!['PPPK','PPPK Paruh Waktu'].includes(form.status_kepegawaian)" name="golongan" x-model="form.golongan" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                                    <option value="">-- Pilih Golongan --</option>
                                    <template x-for="g in ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII','XIII','XIV','XV','XVI','XVII']" :key="g">
                                        <option :value="g" x-text="g"></option>
                                    </template>
                                </select>
                                <input x-show="form.status_kepegawaian === 'Non ASN'" type="text" disabled value="-" class="w-full px-4 py-2 border border-gray-200 dark:border-gray-800 rounded-lg bg-gray-100 dark:bg-slate-800 text-gray-400">
                            </div>

                            <div x-data="{ jabatanOpen: false }" class="relative">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Jabatan <span class="text-red-500">*</span></label>
                                <input type="hidden" name="jabatan_id" x-model="form.jabatan_id" required>
                                <button type="button" @click="jabatanOpen = !jabatanOpen" @click.outside="jabatanOpen = false"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none text-left flex items-center justify-between">
                                    <span x-text="form.jabatan_id ? getJabatanNama(form.jabatan_id) : '-- Pilih Jabatan --'" :class="!form.jabatan_id ? 'text-gray-400' : ''"></span>
                                    <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                                <div x-show="jabatanOpen" x-transition x-cloak
                                    class="absolute z-50 mt-1 w-full max-h-60 overflow-y-auto bg-white dark:bg-[#1a1a1a] border border-gray-200 dark:border-gray-700 rounded-lg shadow-xl">
                                    <template x-for="jab in allJabatans" :key="jab.id">
                                        <button type="button"
                                            @click="
                                                if (jab.jumlah !== null && jab.pegawais_count >= jab.jumlah && form.jabatan_id != jab.id) {
                                                    Swal.fire({
                                                        icon: 'warning',
                                                        title: 'Jabatan Penuh!',
                                                        text: 'Jabatan ' + jab.nama_jabatan + ' sudah terisi penuh (' + jab.pegawais_count + '/' + jab.jumlah + ')',
                                                        confirmButtonColor: '#3085d6'
                                                    });
                                                } else {
                                                    form.jabatan_id = jab.id;
                                                    jabatanOpen = false;
                                                }
                                            "
                                            :class="jab.jumlah !== null && jab.pegawais_count >= jab.jumlah && form.jabatan_id != jab.id
                                                ? 'text-gray-400 dark:text-gray-600 cursor-not-allowed bg-gray-50 dark:bg-[#111]'
                                                : (form.jabatan_id == jab.id ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400' : 'text-slate-900 dark:text-white hover:bg-gray-100 dark:hover:bg-[#222]')"
                                            class="w-full px-4 py-2.5 text-left text-sm flex items-center justify-between border-b border-gray-100 dark:border-gray-800 last:border-0 transition-colors">
                                            <span>
                                                <span x-text="jab.nama_jabatan"></span>
                                                <span class="text-xs ml-1 opacity-60" x-text="'(' + jab.jenis_jabatan + ')'"></span>
                                            </span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Status Pernikahan <span class="text-red-500">*</span></label>
                                <select name="status_pernikahan" x-model="form.status_pernikahan" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                                    <option value="Lajang">Lajang</option>
                                    <option value="Menikah">Menikah</option>
                                    <option value="Cerai Hidup">Cerai Hidup</option>
                                    <option value="Cerai Mati">Cerai Mati</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Akun Terhubung</label>
                                <select name="user_id" x-model="form.user_id" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                                    <option value="">-- Tidak Ada Akun --</option>
                                    <template x-if="isEdit && currentAccount">
                                        <option :value="currentAccount.id" x-text="currentAccount.username + ' (Saat Ini)'" selected></option>
                                    </template>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->username }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Alamat Lengkap <span class="text-red-500">*</span></label>
                            <textarea name="alamat" x-model="form.alamat" required rows="3" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none resize-none"></textarea>
                        </div>

                        <div class="md:col-span-2 pt-2 border-t border-gray-100 dark:border-gray-800">
                            <div class="bg-blue-50/50 dark:bg-blue-900/10 p-4 rounded-xl border border-blue-100 dark:border-blue-900/30 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                                <div>
                                    <label class="block text-sm font-bold text-blue-600 dark:text-blue-400">Tanggal Berlaku Perubahan <span class="text-red-500">*</span></label>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Kapan data status/jabatan/golongan di atas mulai berlaku (untuk pencatatan riwayat).</p>
                                </div>
                                <div class="w-full sm:w-56 shrink-0">
                                    <input type="date" name="tanggal_berlaku" x-model="form.tanggal_berlaku" required class="w-full px-4 py-2 border-2 border-blue-500 rounded-lg bg-white dark:bg-slate-900 text-slate-900 dark:text-white font-medium focus:ring-2 focus:ring-blue-600 outline-none shadow-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>

                <div x-show="activeTab === 'history'" x-cloak>
                    <div class="overflow-x-auto rounded-xl border border-gray-100 dark:border-gray-800">
                        <table class="w-full text-left border-collapse" style="min-width: 1100px;">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-[#1a1a1a] border-b border-gray-100 dark:border-gray-800">
                                    <th class="px-3 py-3 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase whitespace-nowrap">Tgl Berlaku</th>
                                    <th class="px-3 py-3 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase whitespace-nowrap">NIP</th>
                                    <th class="px-3 py-3 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase whitespace-nowrap">NIK</th>
                                    <th class="px-3 py-3 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase whitespace-nowrap">Nama Lengkap</th>
                                    <th class="px-3 py-3 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase whitespace-nowrap">Tempat, Tgl Lahir</th>
                                    <th class="px-3 py-3 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase whitespace-nowrap">Kelamin</th>
                                    <th class="px-3 py-3 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase whitespace-nowrap">Agama</th>
                                    <th class="px-3 py-3 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase whitespace-nowrap">Jabatan</th>
                                    <th class="px-3 py-3 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase whitespace-nowrap">Gol</th>
                                    <th class="px-3 py-3 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase whitespace-nowrap">Status</th>
                                    <th class="px-3 py-3 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase whitespace-nowrap">Pernikahan</th>
                                    <th class="px-3 py-3 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase whitespace-nowrap">Alamat</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                <template x-for="h in histories" :key="h.id">
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-[#18181b] transition-colors">
                                        <td class="px-3 py-3 text-xs text-slate-900 dark:text-white whitespace-nowrap"
                                            x-text="new Date(h.tanggal_berlaku).toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year: 'numeric'})"></td>
                                        <td class="px-3 py-3 text-xs font-mono text-gray-600 dark:text-gray-400 whitespace-nowrap" x-text="h.nip || '-'"></td>
                                        <td class="px-3 py-3 text-xs font-mono text-gray-600 dark:text-gray-400 whitespace-nowrap" x-text="h.nik || '-'"></td>
                                        <td class="px-3 py-3 text-xs text-gray-600 dark:text-gray-400 whitespace-nowrap"
                                            x-text="(h.gelar_depan ? h.gelar_depan + ' ' : '') + (h.nama || '-') + (h.gelar_belakang ? ', ' + h.gelar_belakang : '')"></td>
                                        <td class="px-3 py-3 text-xs text-gray-600 dark:text-gray-400 whitespace-nowrap"
                                            x-text="(h.tempat_lahir || '-') + ', ' + (h.tanggal_lahir ? new Date(h.tanggal_lahir).toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year: 'numeric'}) : '-')"></td>
                                        <td class="px-3 py-3 text-xs text-gray-600 dark:text-gray-400 whitespace-nowrap" x-text="h.jenis_kelamin || '-'"></td>
                                        <td class="px-3 py-3 text-xs text-gray-600 dark:text-gray-400 whitespace-nowrap" x-text="h.agama || '-'"></td>
                                        <td class="px-3 py-3 text-xs text-gray-600 dark:text-gray-400"
                                            x-text="h.jabatan ? h.jabatan.nama_jabatan + ' (' + h.jabatan.jenis_jabatan + ')' : '-'"></td>
                                        <td class="px-3 py-3 text-xs text-gray-600 dark:text-gray-400 whitespace-nowrap" x-text="h.golongan || '-'"></td>
                                        <td class="px-3 py-3 text-xs text-gray-600 dark:text-gray-400 whitespace-nowrap" x-text="h.status_kepegawaian || '-'"></td>
                                        <td class="px-3 py-3 text-xs text-gray-600 dark:text-gray-400 whitespace-nowrap" x-text="h.status_pernikahan || '-'"></td>
                                        <td class="px-3 py-3 text-xs text-gray-600 dark:text-gray-400" style="min-width:160px;" x-text="h.alamat || '-'"></td>
                                    </tr>
                                </template>
                                <template x-if="histories.length === 0">
                                    <tr>
                                        <td colspan="12" class="px-4 py-10 text-center text-sm text-gray-500 dark:text-gray-400 italic">Belum ada riwayat perubahan data.</td>
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
            <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-100 dark:border-gray-800 shrink-0" x-show="activeTab === 'data'">
                <button type="button" @click="modalOpen = false" class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-[#111111] dark:text-gray-300 dark:border-gray-700 dark:hover:bg-slate-800 transition-colors">
                    Batal
                </button>
                <button type="submit" form="pegawai-form" :disabled="submitting" class="px-6 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-md shadow-blue-500/20 disabled:opacity-60 disabled:cursor-not-allowed inline-flex items-center gap-2 transition-colors">
                    <svg x-show="submitting" class="animate-spin w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    <span x-text="submitting ? 'Menyimpan...' : 'Simpan Data'"></span>
                </button>
            </div>
        </div>
    </div>
    </template>
    <form id="delete-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
</div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('pegawaiCrud', () => ({
        allPegawai: @json($pegawais),
        allJabatans: @json($jabatans),
        search: '',
        perPage: 5,
        currentPage: 1,
        sortCol: 'nama',
        sortAsc: true,
        showColumnDropdown: false,

        columns: {
            nama: { label: 'Nama', visible: true },
            nip: { label: 'NIP', visible: true },
            jabatan: { label: 'Jabatan', visible: true },
            status: { label: 'Status Kepegawaian', visible: true },
            golongan: { label: 'Golongan', visible: true },
            ttl: { label: 'Tempat, Tgl Lahir', visible: false },
            jk: { label: 'Jenis Kelamin', visible: false },
            agama: { label: 'Agama', visible: false },
            nik: { label: 'NIK', visible: false },
            pernikahan: { label: 'Status Nikah', visible: false },
            alamat: { label: 'Alamat', visible: false },
        },

        get visibleColumnCount() {
            return Object.values(this.columns).filter(c => c.visible).length;
        },

        formatGelar(depan, belakang) {
            depan = (depan || '').trim();
            belakang = (belakang || '').trim();
            let parts = [];
            if (depan) parts.push(depan);
            if (belakang) parts.push(belakang);
            return parts.join(', ') || '-';
        },

        getJabatanNama(id) {
            if (!id) return '-';
            const j = this.allJabatans.find(item => item.id == id);
            return j ? j.nama_jabatan : '-';
        },

        modalOpen: false,
        isEdit: false,
        activeTab: 'data',
        submitting: false,
        formAction: '',
        currentAccount: null,
        photoPreview: null,
        histories: [],
        form: {
            nama: '',
            gelar_depan: '',
            gelar_belakang: '',
            nip: '',
            nik: '',
            alamat: '',
            tempat_lahir: '',
            tanggal_lahir: '',
            jenis_kelamin: 'Laki-laki',
            agama: '',
            status_kepegawaian: 'PNS',
            jabatan_id: '',
            golongan: '',
            status_pernikahan: 'Lajang',
            tanggal_berlaku: '',
            user_id: ''
        },

        init() {
            this.$watch('search', () => { this.currentPage = 1; });
            this.$watch('perPage', () => { this.currentPage = 1; });

            // Load saved column preferences
            const savedCols = localStorage.getItem('sipeg_pegawai_columns');
            if (savedCols) {
                try {
                    const parsed = JSON.parse(savedCols);
                    Object.keys(parsed).forEach(k => {
                        if (this.columns[k]) {
                            this.columns[k].visible = parsed[k];
                        }
                    });
                } catch (e) {}
            }

            // Save column preferences on change
            this.$watch('columns', (val) => {
                const stateToSave = {};
                Object.keys(val).forEach(k => stateToSave[k] = val[k].visible);
                localStorage.setItem('sipeg_pegawai_columns', JSON.stringify(stateToSave));
            }, { deep: true });
        },

        handlePhotoChange(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.photoPreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },

        get filteredPegawai() {
            let result = this.allPegawai;

            if (this.search) {
                const q = this.search.toLowerCase();
                result = result.filter(p =>
                    (p.nama && p.nama.toLowerCase().includes(q)) ||
                    (p.nip && p.nip.toLowerCase().includes(q)) ||
                    (p.nik && p.nik.toLowerCase().includes(q)) ||
                    (p.jabatan && p.jabatan.nama_jabatan && p.jabatan.nama_jabatan.toLowerCase().includes(q))
                );
            }

            result = result.sort((a, b) => {
                let valA = a[this.sortCol] || '';
                let valB = b[this.sortCol] || '';

                if (this.sortCol === 'jabatan_nama') {
                    valA = a.jabatan ? a.jabatan.nama_jabatan : '';
                    valB = b.jabatan ? b.jabatan.nama_jabatan : '';
                }

                if (typeof valA === 'string') valA = valA.toLowerCase();
                if (typeof valB === 'string') valB = valB.toLowerCase();

                if (valA < valB) return this.sortAsc ? -1 : 1;
                if (valA > valB) return this.sortAsc ? 1 : -1;
                return 0;
            });

            return result;
        },

        get paginatedPegawai() {
            const start = (this.currentPage - 1) * this.perPage;
            return this.filteredPegawai.slice(start, start + parseInt(this.perPage));
        },

        get totalPages() {
            return Math.ceil(this.filteredPegawai.length / this.perPage);
        },

        sortBy(col) {
            if (this.sortCol === col) {
                this.sortAsc = !this.sortAsc;
            } else {
                this.sortCol = col;
                this.sortAsc = true;
            }
        },

        // Mapping golongan ke nama pangkat ASN Indonesia
        golonganMap: {
            // PNS/CPNS
            'I/a': 'Juru Muda', 'I/b': 'Juru Muda Tingkat I', 'I/c': 'Juru', 'I/d': 'Juru Tingkat I',
            'II/a': 'Pengatur Muda', 'II/b': 'Pengatur Muda Tingkat I', 'II/c': 'Pengatur', 'II/d': 'Pengatur Tingkat I',
            'III/a': 'Penata Muda', 'III/b': 'Penata Muda Tingkat I', 'III/c': 'Penata', 'III/d': 'Penata Tingkat I',
            'IV/a': 'Pembina', 'IV/b': 'Pembina Tingkat I', 'IV/c': 'Pembina Utama Muda',
            'IV/d': 'Pembina Utama Madya', 'IV/e': 'Pembina Utama',
        },

        getGolonganLabel(gol) {
            if (!gol) return '-';
            // PNS/CPNS: format I/a, II/b, dst
            if (this.golonganMap[gol]) return this.golonganMap[gol];
            // PPPK: angka romawi saja → label "Golongan X"
            return 'Golongan ' + gol;
        },

        getGolonganDisplay(gol) {
            if (!gol) return '-';
            // Ubah I/a → I A, IV/a → IV A
            return gol.replace('/', ' ').toUpperCase();
        },


        sortIcon(col) {
            if (this.sortCol !== col) return '<svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path></svg>';
            if (this.sortAsc) return '<svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>';
            return '<svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>';
        },

        exportExcel() {
            const self = this;
            let rows = '';
            
            this.allPegawai.forEach(function(p, idx) {
                let jab = p.jabatan ? p.jabatan.nama_jabatan : '-';
                let gol = p.golongan || '-';
                let pangkat = gol ? self.getGolonganLabel(gol) : '-';
                let namaLengkap = (p.gelar_depan ? p.gelar_depan + ' ' : '') + p.nama + (p.gelar_belakang ? ', ' + p.gelar_belakang : '');
                let tglLahir = p.tanggal_lahir ? p.tanggal_lahir.split('T')[0] : '-';

                rows += `<tr>
                    <td style="border: 1px solid #000000; text-align: center; vertical-align: middle;">${idx + 1}</td>
                    <td style="border: 1px solid #000000; mso-number-format:'\\@'; vertical-align: middle;">${p.nip || '-'}</td>
                    <td style="border: 1px solid #000000; vertical-align: middle;">${namaLengkap}</td>
                    <td style="border: 1px solid #000000; mso-number-format:'\\@'; vertical-align: middle;">${p.nik || '-'}</td>
                    <td style="border: 1px solid #000000; vertical-align: middle;">${p.tempat_lahir || '-'}</td>
                    <td style="border: 1px solid #000000; text-align: center; vertical-align: middle;">${tglLahir}</td>
                    <td style="border: 1px solid #000000; text-align: center; vertical-align: middle;">${p.jenis_kelamin || '-'}</td>
                    <td style="border: 1px solid #000000; text-align: center; vertical-align: middle;">${p.agama || '-'}</td>
                    <td style="border: 1px solid #000000; text-align: center; vertical-align: middle;">${p.status_kepegawaian || '-'}</td>
                    <td style="border: 1px solid #000000; vertical-align: middle;">${jab}</td>
                    <td style="border: 1px solid #000000; text-align: center; vertical-align: middle;">${gol}</td>
                    <td style="border: 1px solid #000000; vertical-align: middle;">${pangkat}</td>
                    <td style="border: 1px solid #000000; text-align: center; vertical-align: middle;">${p.status_pernikahan || '-'}</td>
                    <td style="border: 1px solid #000000; vertical-align: middle;">${p.alamat || '-'}</td>
                </tr>`;
            });

            const excelTemplate = `
                <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                    <!--[if gte mso 9]>
                    <` + `xml>
                        <` + `x:ExcelWorkbook>
                            <` + `x:ExcelWorksheets>
                                <` + `x:ExcelWorksheet>
                                    <` + `x:Name>Data Pegawai</` + `x:Name>
                                    <` + `x:WorksheetOptions>
                                        <` + `x:DisplayGridlines/>
                                    </` + `x:WorksheetOptions>
                                </` + `x:ExcelWorksheet>
                            </` + `x:ExcelWorksheets>
                        </` + `x:ExcelWorkbook>
                    </` + `xml>
                    <![endif]-->
                </head>
                <body>
                    <h2 style="font-family: Arial, sans-serif; color: #000000;">Data Pegawai SIMPEG BAPPERIDA</h2>
                    <table border="1" style="border-collapse: collapse; font-family: Arial, sans-serif; font-size: 11px;">
                        <thead>
                            <tr style="background-color: #000000; color: #ffffff;">
                                <th style="border: 1px solid #000000; padding: 8px; text-align: center; background-color: #000000; color: #ffffff;">No</th>
                                <th style="border: 1px solid #000000; padding: 8px; text-align: center; background-color: #000000; color: #ffffff;">NIP</th>
                                <th style="border: 1px solid #000000; padding: 8px; text-align: center; background-color: #000000; color: #ffffff;">Nama Lengkap</th>
                                <th style="border: 1px solid #000000; padding: 8px; text-align: center; background-color: #000000; color: #ffffff;">NIK</th>
                                <th style="border: 1px solid #000000; padding: 8px; text-align: center; background-color: #000000; color: #ffffff;">Tempat Lahir</th>
                                <th style="border: 1px solid #000000; padding: 8px; text-align: center; background-color: #000000; color: #ffffff;">Tanggal Lahir</th>
                                <th style="border: 1px solid #000000; padding: 8px; text-align: center; background-color: #000000; color: #ffffff;">L/P</th>
                                <th style="border: 1px solid #000000; padding: 8px; text-align: center; background-color: #000000; color: #ffffff;">Agama</th>
                                <th style="border: 1px solid #000000; padding: 8px; text-align: center; background-color: #000000; color: #ffffff;">Status Kepegawaian</th>
                                <th style="border: 1px solid #000000; padding: 8px; text-align: center; background-color: #000000; color: #ffffff;">Jabatan</th>
                                <th style="border: 1px solid #000000; padding: 8px; text-align: center; background-color: #000000; color: #ffffff;">Golongan</th>
                                <th style="border: 1px solid #000000; padding: 8px; text-align: center; background-color: #000000; color: #ffffff;">Pangkat</th>
                                <th style="border: 1px solid #000000; padding: 8px; text-align: center; background-color: #000000; color: #ffffff;">Status Nikah</th>
                                <th style="border: 1px solid #000000; padding: 8px; text-align: center; background-color: #000000; color: #ffffff;">Alamat</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${rows}
                        </tbody>
                    </table>
                </body>
                </html>`;

            const blob = new Blob([excelTemplate], { type: 'application/vnd.ms-excel;charset=utf-8' });
            const link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = `Data_Pegawai_SIMPEG_${new Date().toISOString().split('T')[0]}.xls`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        },

        printPdf() {
            // Build tabel dari SEMUA data pegawai (bukan dari DOM yang terpaginasi)
            const self = this;
            let rows = '';
            this.allPegawai.forEach(function(p, idx) {
                let namaLengkap = (p.gelar_depan ? p.gelar_depan + ' ' : '') + p.nama + (p.gelar_belakang ? ', ' + p.gelar_belakang : '');
                let jab = p.jabatan ? p.jabatan.nama_jabatan : '-';
                let gol = p.golongan || '-';
                let pangkat = p.golongan ? self.getGolonganLabel(p.golongan) : '-';
                let golDisplay = p.golongan ? (pangkat + ' / ' + self.getGolonganDisplay(p.golongan)) : '-';
                rows += `<tr style="background:${idx % 2 === 0 ? '#fff' : '#f9fafb'}">
                    <td style="border:1px solid #e5e7eb;padding:6px 8px;font-size:10px">${idx + 1}</td>
                    <td style="border:1px solid #e5e7eb;padding:6px 8px;font-size:10px;font-family:monospace">${p.nip}</td>
                    <td style="border:1px solid #e5e7eb;padding:6px 8px;font-size:10px">${namaLengkap}</td>
                    <td style="border:1px solid #e5e7eb;padding:6px 8px;font-size:10px">${jab}</td>
                    <td style="border:1px solid #e5e7eb;padding:6px 8px;font-size:10px">${p.status_kepegawaian}</td>
                    <td style="border:1px solid #e5e7eb;padding:6px 8px;font-size:10px">${golDisplay}</td>
                </tr>`;
            });

            const printHtml = `
                <html><head>
                <title>Laporan Data Pegawai SIMPEG</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    h2 { font-size: 16px; font-weight: bold; margin-bottom: 4px; }
                    p.sub { font-size: 11px; color: #666; margin-bottom: 16px; }
                    table { width: 100%; border-collapse: collapse; }
                    thead tr { background: #1e40af; color: #fff; }
                    th { border: 1px solid #e5e7eb; padding: 7px 8px; font-size: 10px; text-align: left; }
                    @media print { @page { size: landscape; margin: 12mm; } }
                </style>
                </head><body>
                <h2>Laporan Data Pegawai SIMPEG</h2>
                <p class="sub">Dicetak pada: ${new Date().toLocaleDateString('id-ID', {day:'numeric',month:'long',year:'numeric'})} | Total: ${self.allPegawai.length} pegawai</p>
                <table>
                    <thead><tr>
                        <th>No</th><th>NIP</th><th>Nama Lengkap</th><th>Jabatan</th><th>Status</th><th>Golongan / Pangkat</th>
                    </tr></thead>
                    <tbody>${rows}</tbody>
                </table>
                </body></html>`;

            const printWindow = window.open('', '_blank', 'width=1000,height=700');
            printWindow.document.write(printHtml);
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
            setTimeout(() => printWindow.close(), 1000);
        },

        openCreateModal() {
            this.isEdit = false;
            this.activeTab = 'data';
            this.submitting = false;
            this.formAction = '{{ route("admin.pegawais.store") }}';
            this.form = {
                nama: '', gelar_depan: '', gelar_belakang: '', nip: '', nik: '', alamat: '',
                tempat_lahir: '', tanggal_lahir: '', jenis_kelamin: 'Laki-laki', agama: '',
                status_kepegawaian: 'PNS', jabatan_id: '', golongan: '', status_pernikahan: 'Lajang',
                tanggal_berlaku: new Date().toISOString().split('T')[0], user_id: ''
            };
            this.currentAccount = null;
            this.photoPreview = null;
            this.histories = [];
            this.modalOpen = true;
        },
        openEditModal(p) {
            this.isEdit = true;
            this.activeTab = 'data';
            this.submitting = false;
            this.formAction = `/admin/pegawais/${p.id}`;
            this.form = {
                nama: p.nama, gelar_depan: p.gelar_depan || '', gelar_belakang: p.gelar_belakang || '',
                nip: p.nip, nik: p.nik, alamat: p.alamat, tempat_lahir: p.tempat_lahir,
                tanggal_lahir: p.tanggal_lahir ? p.tanggal_lahir.split('T')[0] : '',
                jenis_kelamin: p.jenis_kelamin, agama: p.agama, status_kepegawaian: p.status_kepegawaian,
                jabatan_id: p.jabatan_id, golongan: p.golongan || '', status_pernikahan: p.status_pernikahan,
                tanggal_berlaku: p.tanggal_berlaku ? p.tanggal_berlaku.split('T')[0] : new Date().toISOString().split('T')[0],
                user_id: p.user_id || ''
            };
            this.currentAccount = p.user;
            this.photoPreview = p.foto ? '/storage/' + p.foto : null;
            this.histories = p.histories || [];
            this.modalOpen = true;
        },
        confirmDelete(url) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data pegawai akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = document.getElementById('delete-form');
                    form.action = url;
                    form.submit();
                }
            })
        }
    }))
})
</script>
@endsection