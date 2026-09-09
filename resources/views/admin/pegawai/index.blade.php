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

            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" x-model="search" placeholder="Cari NIP atau Nama..." class="w-full sm:w-64 pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
            </div>
        </div>
        <div class="overflow-x-auto" id="printable-area">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-[#1a1a1a] border-b border-gray-100 dark:border-gray-800">
                        <th @click="sortBy('nip')" class="cursor-pointer px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-blue-600 select-none">
                            <div class="flex items-center gap-1">NIP <span x-html="sortIcon('nip')"></span></div>
                        </th>
                        <th @click="sortBy('nama')" class="cursor-pointer px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-blue-600 select-none">
                            <div class="flex items-center gap-1">Nama <span x-html="sortIcon('nama')"></span></div>
                        </th>
                        <th @click="sortBy('jabatan_nama')" class="cursor-pointer px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-blue-600 select-none">
                            <div class="flex items-center gap-1">Jabatan <span x-html="sortIcon('jabatan_nama')"></span></div>
                        </th>
                        <th @click="sortBy('status_kepegawaian')" class="cursor-pointer px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-blue-600 select-none">
                            <div class="flex items-center gap-1">Status <span x-html="sortIcon('status_kepegawaian')"></span></div>
                        </th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400 text-right print:hidden">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    <template x-for="p in paginatedPegawai" :key="p.id">
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-[#18181b] transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-slate-900 dark:text-white" x-text="p.nip"></td>
                            <td class="px-6 py-4 text-sm text-slate-900 dark:text-white">
                                <div class="flex flex-col">
                                    <span x-text="(p.gelar_depan ? p.gelar_depan + ' ' : '') + p.nama + (p.gelar_belakang ? ', ' + p.gelar_belakang : '')"></span>
                                    <span class="text-xs text-gray-500" x-text="'NIK: ' + p.nik"></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400" x-text="p.jabatan ? p.jabatan.nama_jabatan : '-'"></td>
                            <td class="px-6 py-4 text-sm">
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
                            <td class="px-6 py-4 text-right space-x-2 print:hidden">
                                <button @click="openEditModal(p)" class="text-blue-600 hover:text-blue-800 dark:text-blue-500 dark:hover:text-blue-400 p-1">
                                    Edit
                                </button>
                                <button @click="confirmDelete('{{ url('/admin/pegawais') }}/' + p.id)" class="text-red-600 hover:text-red-800 dark:text-red-500 dark:hover:text-red-400 p-1">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    </template>
                    <template x-if="paginatedPegawai.length === 0">
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">Tidak ada data yang ditemukan.</td>
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
                                <select x-show="['PNS','CPNS'].includes(form.status_kepegawaian)" name="golongan" x-model="form.golongan" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                                    <option value="">-- Pilih Golongan --</option>
                                    <option value="I/a">I/a</option><option value="I/b">I/b</option><option value="I/c">I/c</option><option value="I/d">I/d</option>
                                    <option value="II/a">II/a</option><option value="II/b">II/b</option><option value="II/c">II/c</option><option value="II/d">II/d</option>
                                    <option value="III/a">III/a</option><option value="III/b">III/b</option><option value="III/c">III/c</option><option value="III/d">III/d</option>
                                    <option value="IV/a">IV/a</option><option value="IV/b">IV/b</option><option value="IV/c">IV/c</option><option value="IV/d">IV/d</option><option value="IV/e">IV/e</option>
                                </select>
                                <select x-show="['PPPK','PPPK Paruh Waktu'].includes(form.status_kepegawaian)" name="golongan" x-model="form.golongan" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                                    <option value="">-- Pilih Golongan --</option>
                                    <template x-for="g in ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII','XIII','XIV','XV','XVI','XVII']" :key="g">
                                        <option :value="g" x-text="g"></option>
                                    </template>
                                </select>
                                <input x-show="form.status_kepegawaian === 'Non ASN'" type="text" disabled value="-" class="w-full px-4 py-2 border border-gray-200 dark:border-gray-800 rounded-lg bg-gray-100 dark:bg-slate-800 text-gray-400">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Jabatan <span class="text-red-500">*</span></label>
                                <select name="jabatan_id" x-model="form.jabatan_id" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                                    <option value="">-- Pilih Jabatan --</option>
                                    @foreach($jabatans as $jab)
                                        <option value="{{ $jab->id }}">{{ $jab->nama_jabatan }} ({{ $jab->jenis_jabatan }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
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
                                    <label class="block text-sm font-medium text-blue-600 dark:text-blue-400 mb-1.5">Tgl Berlaku <span class="text-red-500">*</span></label>
                                    <input type="date" name="tanggal_berlaku" x-model="form.tanggal_berlaku" required class="w-full px-4 py-2 border-2 border-blue-100 dark:border-blue-900/30 rounded-lg bg-blue-50/30 dark:bg-blue-900/10 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                                </div>
                            </div>
                            <p class="text-[11px] text-gray-500 -mt-2.5">Kapan status/jabatan di atas mulai berlaku (untuk riwayat).</p>

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
                    </div>
                    </form>
                </div>

                <div x-show="activeTab === 'history'" x-cloak>
                    <div class="overflow-x-auto rounded-xl border border-gray-100 dark:border-gray-800">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-[#1a1a1a] border-b border-gray-100 dark:border-gray-800">
                                    <th class="px-4 py-3 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Tgl Berlaku</th>
                                    <th class="px-4 py-3 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Jabatan</th>
                                    <th class="px-4 py-3 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Gol</th>
                                    <th class="px-4 py-3 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Status</th>
                                    <th class="px-4 py-3 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Gelar</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                <template x-for="h in histories" :key="h.id">
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-[#18181b] transition-colors">
                                        <td class="px-4 py-3 text-sm text-slate-900 dark:text-white" x-text="new Date(h.tanggal_berlaku).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'})"></td>
                                        <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400" x-text="h.jabatan ? h.jabatan.nama_jabatan : '-'"></td>
                                        <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400" x-text="h.golongan || '-'"></td>
                                        <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400" x-text="h.status_kepegawaian"></td>
                                        <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400" x-text="((h.gelar_depan ? h.gelar_depan + ' ' : '') + (h.gelar_belakang ? ', ' + h.gelar_belakang : '')) || '-'"></td>
                                    </tr>
                                </template>
                                <template x-if="histories.length === 0">
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400 italic">Belum ada riwayat perubahan data.</td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/10 rounded-xl border border-blue-100 dark:border-blue-900/20">
                        <div class="flex gap-3">
                            <svg class="w-5 h-5 text-blue-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="text-xs text-blue-700 dark:text-blue-400 leading-relaxed">
                                Riwayat ini otomatis mencatat kondisi data sebelum setiap perubahan dilakukan.
                                Data yang muncul saat pencetakan dokumen akan disesuaikan dengan riwayat yang berlaku pada tanggal dokumen tersebut.
                            </p>
                        </div>
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
        search: '',
        perPage: 5,
        currentPage: 1,
        sortCol: 'nama',
        sortAsc: true,

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

        sortIcon(col) {
            if (this.sortCol !== col) return '<svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path></svg>';
            if (this.sortAsc) return '<svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>';
            return '<svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>';
        },

        exportExcel() {
            let csvContent = "data:text/csv;charset=utf-8,";
            csvContent += "NIP,Nama,Gelar Depan,Gelar Belakang,NIK,Tempat Lahir,Tanggal Lahir,Jenis Kelamin,Agama,Status Kepegawaian,Jabatan,Golongan,Status Pernikahan\n";

            this.filteredPegawai.forEach(function(p) {
                let jab = p.jabatan ? p.jabatan.nama_jabatan : '-';
                let row = `"${p.nip}","${p.nama}","${p.gelar_depan || ''}","${p.gelar_belakang || ''}","${p.nik}","${p.tempat_lahir}","${p.tanggal_lahir}","${p.jenis_kelamin}","${p.agama}","${p.status_kepegawaian}","${jab}","${p.golongan || ''}","${p.status_pernikahan}"`;
                csvContent += row + "\n";
            });

            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "Data_Pegawai_SIMPEG.csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        },

        printPdf() {
            const printContent = document.getElementById('printable-area').innerHTML;

            const style = document.createElement('style');
            style.innerHTML = `
                @media print {
                    body { visibility: hidden; }
                    #print-section { visibility: visible; position: absolute; left: 0; top: 0; width: 100%; }
                    .print\\:hidden { display: none !important; }
                    table { width: 100%; border-collapse: collapse; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 10px; }
                }
            `;
            document.head.appendChild(style);

            const printSection = document.createElement('div');
            printSection.id = 'print-section';
            printSection.innerHTML = '<h2 style="font-size:20px; font-weight:bold; margin-bottom: 20px;">Laporan Data Pegawai SIMPEG</h2>' + printContent;
            document.body.appendChild(printSection);

            window.print();

            document.body.removeChild(printSection);
            document.head.removeChild(style);
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