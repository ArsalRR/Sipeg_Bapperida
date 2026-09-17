@extends('layouts.admin')

@section('title', 'Manajemen Jabatan')

@section('content')
<div x-data="jabatanCrud()" class="max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Daftar Jabatan</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Kelola data jabatan, kelas jabatan, formasi kebutuhan, dan atasan hirarki</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.jabatans.peta') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg shadow-sm transition-colors flex items-center gap-2 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                Lihat Peta Jabatan
            </a>
            <button @click="openCreateModal()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-sm transition-colors flex items-center gap-2 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Tambah Jabatan
            </button>
        </div>
    </div>
    <div class="bg-white dark:bg-[#111111] rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">

        <div class="p-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center gap-4">
            <div class="relative w-full sm:w-64">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" x-model="search" placeholder="Cari nama jabatan..." class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none text-sm">
            </div>
        </div>

        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-[#1a1a1a] border-b border-gray-100 dark:border-gray-800">
                        <th class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400">Nama Jabatan</th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400">Unit / Bidang Kerja</th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400">Atasan Jabatan</th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400">Jenis</th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400 text-center">Kelas</th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400 text-center">Bezetting (B)</th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400 text-center">Kebutuhan (K)</th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400 text-center">Selisih (+/-)</th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    <template x-for="j in filteredJabatan" :key="j.id">
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-[#18181b] transition-colors">
                            <td class="px-6 py-4 text-sm font-semibold text-slate-900 dark:text-white" x-text="j.nama_jabatan"></td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                <span x-text="j.unit_kerja || '-'" class="px-2 py-0.5 rounded bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-medium text-xs"></span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                <span x-text="j.parent ? j.parent.nama_jabatan : '-'" class="italic"></span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400" x-text="j.jenis_jabatan"></td>
                            <td class="px-6 py-4 text-sm text-center font-bold text-blue-600 dark:text-blue-400" x-text="j.kelas_jabatan || '-'"></td>
                            <td class="px-6 py-4 text-sm text-center font-medium" x-text="j.bezetting"></td>
                            <td class="px-6 py-4 text-sm text-center font-medium" x-text="j.kebutuhan || j.jumlah || 0"></td>
                            <td class="px-6 py-4 text-sm text-center">
                                <span :class="{
                                    'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400': j.selisih === 0,
                                    'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400': j.selisih < 0,
                                    'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400': j.selisih > 0
                                }" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold" x-text="j.selisih > 0 ? '+' + j.selisih : j.selisih"></span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <div class="relative group">
                                        <button @click="openEditModal(j)" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-blue-600 hover:text-blue-800 hover:bg-blue-50 dark:text-blue-400 dark:hover:text-blue-300 dark:hover:bg-blue-900/30 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1.5 px-2 py-0.5 text-[10px] font-medium bg-gray-800 text-white rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">Edit</span>
                                    </div>
                                    <div class="relative group">
                                        <button @click="confirmDelete('{{ url('/admin/jabatans') }}/' + j.id)" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-red-500 hover:text-red-700 hover:bg-red-50 dark:text-red-400 dark:hover:text-red-300 dark:hover:bg-red-900/30 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                        <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1.5 px-2 py-0.5 text-[10px] font-medium bg-gray-800 text-white rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">Hapus</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        {{-- LAYAR MOBILE: CARD ACCORDION LIST UNTUK DAFTAR JABATAN --}}
        <div class="block sm:hidden p-3 space-y-3">
            <template x-for="j in filteredJabatan" :key="j.id">
                <div class="bg-white dark:bg-[#18181b] border border-gray-200 dark:border-gray-800 rounded-2xl overflow-hidden transition-all shadow-sm">
                    
                    {{-- Card Header Mobile --}}
                    <button @click="expandedId = (expandedId === j.id ? null : j.id)" type="button" class="w-full p-4 flex items-center justify-between text-left hover:bg-gray-50 dark:hover:bg-slate-800/40 transition-colors">
                        <div class="flex items-center gap-3 min-w-0">
                            {{-- Icon Jabatan --}}
                            <div class="shrink-0 w-11 h-11 rounded-full bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 font-bold flex items-center justify-center border border-blue-200 dark:border-blue-800/50">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>

                            {{-- Nama Jabatan & Jenis --}}
                            <div class="min-w-0 flex-1">
                                <h3 class="text-sm font-bold text-slate-900 dark:text-white truncate leading-tight" x-text="j.nama_jabatan"></h3>
                                <div class="flex items-center gap-1.5 mt-1.5 flex-wrap">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 whitespace-nowrap" x-text="j.jenis_jabatan"></span>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 whitespace-nowrap" x-text="'Kelas: ' + (j.kelas_jabatan || '-')"></span>
                                </div>
                            </div>
                        </div>

                        {{-- Chevron Icon --}}
                        <div class="ml-2 shrink-0 p-1 text-gray-400">
                            <svg class="w-5 h-5 transition-transform duration-200" :class="expandedId === j.id ? 'rotate-180 text-blue-600 dark:text-blue-400' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>

                    {{-- Card Expanded Detail Body --}}
                    <div x-show="expandedId === j.id" x-collapse class="px-4 pb-4 pt-2 border-t border-gray-100 dark:border-gray-800/80 space-y-3">
                        <div class="grid grid-cols-2 gap-2 text-xs pt-1">
                            <div class="col-span-2 bg-gray-50 dark:bg-[#111111] p-2.5 rounded-xl border border-gray-100 dark:border-gray-800">
                                <span class="text-gray-400 dark:text-gray-500 block text-[10px] uppercase font-semibold">Atasan Jabatan</span>
                                <span class="font-medium text-slate-800 dark:text-slate-200" x-text="j.parent ? j.parent.nama_jabatan : 'Atasan Tertinggi (Top Level)'"></span>
                            </div>
                            <div class="bg-gray-50 dark:bg-[#111111] p-2.5 rounded-xl border border-gray-100 dark:border-gray-800">
                                <span class="text-gray-400 dark:text-gray-500 block text-[10px] uppercase font-semibold">Bezetting (B)</span>
                                <span class="font-medium text-slate-800 dark:text-slate-200" x-text="j.bezetting + ' Pegawai'"></span>
                            </div>
                            <div class="bg-gray-50 dark:bg-[#111111] p-2.5 rounded-xl border border-gray-100 dark:border-gray-800">
                                <span class="text-gray-400 dark:text-gray-500 block text-[10px] uppercase font-semibold">Kebutuhan (K)</span>
                                <span class="font-medium text-slate-800 dark:text-slate-200" x-text="(j.kebutuhan || j.jumlah || 0) + ' Formasi'"></span>
                            </div>
                            <div class="col-span-2 bg-gray-50 dark:bg-[#111111] p-2.5 rounded-xl border border-gray-100 dark:border-gray-800">
                                <span class="text-gray-400 dark:text-gray-500 block text-[10px] uppercase font-semibold">Selisih (+/-)</span>
                                <span class="font-bold text-slate-800 dark:text-slate-200" x-text="j.selisih > 0 ? '+' + j.selisih + ' (Kelebihan)' : (j.selisih < 0 ? j.selisih + ' (Kekurangan)' : '0 (Pas)')"></span>
                            </div>
                        </div>

                        {{-- Tombol Aksi Mobile --}}
                        <div class="flex items-center gap-2 pt-2">
                            <button @click="openEditModal(j)" type="button" class="flex-1 py-2.5 px-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold flex items-center justify-center gap-1 transition-colors shadow-sm whitespace-nowrap">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                <span>Edit</span>
                            </button>
                            <button @click="confirmDelete('{{ url('/admin/jabatans') }}/' + j.id)" type="button" class="flex-1 py-2.5 px-2 bg-red-50 hover:bg-red-100 dark:bg-rose-900/20 dark:hover:bg-rose-900/30 text-red-600 dark:text-rose-400 border border-red-200 dark:border-rose-900/40 rounded-xl text-xs font-semibold flex items-center justify-center gap-1 transition-colors whitespace-nowrap">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                <span>Hapus</span>
                            </button>
                        </div>
                    </div>
                </div>
            </template>

            <template x-if="filteredJabatan.length === 0">
                <div class="p-6 text-center text-gray-500 dark:text-gray-400 text-sm">Tidak ada data yang ditemukan.</div>
            </template>
        </div>
    </div>

    {{-- Modal Tambah / Edit Jabatan --}}
    <div x-show="modalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="modalOpen" @click="modalOpen = false" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/50 dark:bg-black/80 backdrop-blur-sm" aria-hidden="true"></div>

            <div x-show="modalOpen" x-transition class="relative inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-[#111111] shadow-xl rounded-2xl border border-gray-100 dark:border-gray-800">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white" x-text="isEdit ? 'Edit Jabatan' : 'Tambah Jabatan'"></h3>
                    <button @click="modalOpen = false" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form :action="formAction" method="POST">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="space-y-4 max-h-[calc(100vh-14rem)] overflow-y-auto pr-1">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Jabatan <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_jabatan" x-model="form.nama_jabatan" required placeholder="Misal: Kepala Badan Perencanaan Pembangunan..." class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Atasan Jabatan (Hirarki Peta Jabatan)</label>
                            <select name="parent_id" x-model="form.parent_id" @change="onParentChange()" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none text-sm">
                                <option value="">-- Tanpa Atasan (Top Level / Kepala Badan) --</option>
                                <template x-for="j in availableParents" :key="j.id">
                                    <option :value="j.id" x-text="j.nama_jabatan + (j.kelas_jabatan ? ' (Kelas ' + j.kelas_jabatan + ')' : '')"></option>
                                </template>
                            </select>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Pilih jabatan atasan langsung tempat posisi ini bernaung.</p>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jenis Jabatan <span class="text-red-500">*</span></label>
                                <select name="jenis_jabatan" x-model="form.jenis_jabatan" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none text-sm">
                                    <option value="Struktural">Struktural</option>
                                    <option value="Fungsional">Fungsional</option>
                                    <option value="Pelaksana">Pelaksana</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kelas Jabatan</label>
                                <input type="number" name="kelas_jabatan" x-model="form.kelas_jabatan" min="1" max="15" placeholder="Misal: 14, 12, 11, 9" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none text-sm">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Unit / Bidang Kerja (Lokasi Peta)</label>
                            <select name="unit_kerja" x-model="form.unit_kerja" @change="onUnitChange()" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none text-sm">
                                <option value="">-- Bebas / Otomatis --</option>
                                @foreach($bidangs as $b)
                                    <option value="{{ $b->singkatan }}">{{ $b->nama_bidang }} ({{ $b->singkatan }})</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Pilih lokasi unit/bidang (singkatan) untuk penempatan presisi di Peta Jabatan.</p>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kebutuhan Formasi (K)</label>
                                <input type="number" name="kebutuhan" x-model="form.kebutuhan" min="0" placeholder="Kebutuhan ideal (K)" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none text-sm">
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" @click="modalOpen = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-[#111111] dark:text-gray-300 dark:border-gray-700 dark:hover:bg-slate-800 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-md shadow-blue-500/20 transition-colors">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <form id="delete-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('jabatanCrud', () => ({
        allJabatan: @json($jabatans),
        search: '',
        modalOpen: false,
        expandedId: null,
        isEdit: false,
        currentId: null,
        formAction: '',
        form: {
            nama_jabatan: '',
            parent_id: '',
            jenis_jabatan: 'Struktural',
            unit_kerja: '',
            kelas_jabatan: '',
            kebutuhan: '1',
            kategori_warna: ''
        },

        get filteredJabatan() {
            if (!this.search) return this.allJabatan;
            const q = this.search.toLowerCase();
            return this.allJabatan.filter(j => j.nama_jabatan.toLowerCase().includes(q));
        },

        get availableParents() {
            if (!this.isEdit) return this.allJabatan;
            return this.allJabatan.filter(j => j.id !== this.currentId);
        },

        openCreateModal() {
            this.isEdit = false;
            this.currentId = null;
            this.formAction = '{{ route("admin.jabatans.store") }}';
            this.form = {
                nama_jabatan: '',
                parent_id: '',
                jenis_jabatan: 'Struktural',
                unit_kerja: '',
                kelas_jabatan: '',
                kebutuhan: '1',
                kategori_warna: ''
            };
            this.modalOpen = true;
        },

        openEditModal(j) {
            this.isEdit = true;
            this.currentId = j.id;
            this.formAction = `/admin/jabatans/${j.id}`;
            this.form = {
                nama_jabatan: j.nama_jabatan,
                parent_id: j.parent_id || '',
                jenis_jabatan: j.jenis_jabatan,
                unit_kerja: j.unit_kerja || '',
                kelas_jabatan: j.kelas_jabatan || '',
                kebutuhan: j.kebutuhan || j.jumlah || '1',
                kategori_warna: j.kategori_warna || ''
            };
            this.modalOpen = true;
        },

        onUnitChange() {
            if (!this.form.unit_kerja) return;
            const u = this.form.unit_kerja;
            let match = null;
            if (u.includes('Subbag Umum')) {
                match = this.allJabatan.find(j => j.nama_jabatan.includes('Sub Bagian Umum'));
            } else if (u.includes('Subbag Perencanaan')) {
                match = this.allJabatan.find(j => j.nama_jabatan.includes('Sub Bagian Perencanaan'));
            } else if (u.includes('Pemerintahan')) {
                match = this.allJabatan.find(j => j.nama_jabatan.includes('Kepala Bidang Pemerintahan'));
            } else if (u.includes('Perekonomian')) {
                match = this.allJabatan.find(j => j.nama_jabatan.includes('Kepala Bidang Perekonomian'));
            } else if (u.includes('Perencanaan, Pengendalian')) {
                match = this.allJabatan.find(j => j.nama_jabatan.includes('Kepala Bidang Perencanaan'));
            } else if (u.includes('Riset')) {
                match = this.allJabatan.find(j => j.nama_jabatan.includes('Kepala Bidang Riset'));
            }
            if (match) {
                this.form.parent_id = match.id;
            }
        },

        onParentChange() {
            if (!this.form.parent_id) return;
            const p = this.allJabatan.find(j => j.id == this.form.parent_id);
            if (!p) return;
            const n = p.nama_jabatan;
            if (n.includes('Sub Bagian Umum')) {
                this.form.unit_kerja = 'Subbag Umum & Kepegawaian';
            } else if (n.includes('Sub Bagian Perencanaan')) {
                this.form.unit_kerja = 'Subbag Perencanaan Evaluasi & Keuangan';
            } else if (n.includes('Kepala Bidang Pemerintahan')) {
                this.form.unit_kerja = 'Bidang Pemerintahan & Pembangunan Manusia';
            } else if (n.includes('Kepala Bidang Perekonomian')) {
                this.form.unit_kerja = 'Bidang Perekonomian, SDA, Infrastruktur & Kewilayahan';
            } else if (n.includes('Kepala Bidang Perencanaan')) {
                this.form.unit_kerja = 'Bidang Perencanaan, Pengendalian & Evaluasi';
            } else if (n.includes('Kepala Bidang Riset')) {
                this.form.unit_kerja = 'Bidang Riset & Inovasi Daerah';
            } else if (p.unit_kerja) {
                this.form.unit_kerja = p.unit_kerja;
            }
        },

        confirmDelete(url) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data jabatan yang sedang digunakan pegawai tidak dapat dihapus!",
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
