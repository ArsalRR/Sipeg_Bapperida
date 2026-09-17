@extends('layouts.admin')

@section('title', 'Data Keluarga')

@section('content')
<div x-data="keluargaCrud()">
    <div class="max-w-7xl mx-auto space-y-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Data Keluarga</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola data anggota keluarga pegawai (Suami/Istri/Anak/Orang Tua)</p>
            </div>
            <button @click="openCreateModal()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-sm transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Tambah Anggota Keluarga
            </button>
        </div>

        @if(in_array(auth()->user()->role, ['admin', 'superadmin']))
        <div class="bg-white dark:bg-[#111111] rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
            <div class="p-4 border-b border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row justify-between gap-4">
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Tampilkan</span>
                    <select x-model="perPage" class="border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white px-3 py-1.5 focus:ring-2 focus:ring-blue-600 outline-none">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                    </select>
                    <span class="text-sm text-gray-500 dark:text-gray-400">pegawai</span>
                </div>

                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" x-model="search" placeholder="Cari NIP atau Nama Pegawai..." class="w-full sm:w-64 pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                </div>
            </div>
            <!-- Desktop View: Table -->
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-[#1a1a1a] border-b border-gray-100 dark:border-gray-800">
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400">NIP</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400">Nama Pegawai</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400">Jabatan</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400 text-center">Jumlah Anggota Keluarga</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        <template x-for="p in paginatedPegawai" :key="p.id">
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-[#18181b] transition-colors">
                                <td class="px-6 py-4 text-sm font-medium text-slate-900 dark:text-white" x-text="p.nip"></td>
                                <td class="px-6 py-4 text-sm font-semibold text-slate-900 dark:text-white" x-text="p.nama"></td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400" x-text="p.jabatan ? p.jabatan.nama_jabatan : '-'"></td>
                                <td class="px-6 py-4 text-sm text-center">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400" x-text="(p.keluargas ? p.keluargas.length : 0) + ' Orang'"></span>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <button @click="openDetailModal(p)" class="px-3 py-1.5 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 hover:bg-blue-100 rounded-lg font-medium text-sm transition-colors inline-flex items-center gap-1.5 whitespace-nowrap">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        Lihat Detail Keluarga
                                    </button>
                                </td>
                            </tr>
                        </template>
                        <template x-if="paginatedPegawai.length === 0">
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">Pegawai tidak ditemukan.</td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Mobile View: Accordion List -->
            <div class="block sm:hidden divide-y divide-gray-100 dark:divide-gray-800">
                <template x-for="p in paginatedPegawai" :key="p.id">
                    <div class="p-4 space-y-3 bg-white dark:bg-[#111111]">
                        <div class="flex justify-between items-start cursor-pointer select-none" @click="toggleExpand(p.id)">
                            <div class="space-y-1 pr-2">
                                <h4 class="font-bold text-slate-900 dark:text-white text-base" x-text="p.nama"></h4>
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="text-xs text-gray-500 dark:text-gray-400" x-text="'NIP: ' + p.nip"></span>
                                    <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400" x-text="(p.keluargas ? p.keluargas.length : 0) + ' Anggota'"></span>
                                </div>
                            </div>
                            <button type="button" class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <svg class="w-5 h-5 transition-transform duration-200" :class="expandedId === p.id ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Accordion Detail -->
                        <div x-show="expandedId === p.id" x-collapse class="pt-3 border-t border-gray-100 dark:border-gray-800 space-y-3">
                            <div class="grid grid-cols-1 gap-2 text-xs">
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">Jabatan:</span>
                                    <span class="font-medium text-slate-900 dark:text-white ml-1" x-text="p.jabatan ? p.jabatan.nama_jabatan : '-'"></span>
                                </div>
                            </div>

                            <div class="pt-2 flex items-center justify-end">
                                <button @click="openDetailModal(p)" class="w-full px-3 py-2 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 hover:bg-blue-100 rounded-lg font-medium text-xs transition-colors flex items-center justify-center gap-1.5 whitespace-nowrap">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    Lihat Detail Keluarga
                                </button>
                            </div>
                        </div>
                    </div>
                </template>

                <template x-if="paginatedPegawai.length === 0">
                    <div class="p-6 text-center text-gray-500 dark:text-gray-400 text-sm">
                        Pegawai tidak ditemukan.
                    </div>
                </template>
            </div>
            <div class="p-4 border-t border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row justify-between items-center gap-4">
                <span class="text-sm text-gray-500 dark:text-gray-400 text-center sm:text-left">
                    Menampilkan <span x-text="filteredPegawai.length > 0 ? ((currentPage - 1) * perPage) + 1 : 0"></span>
                    sampai <span x-text="Math.min(currentPage * perPage, filteredPegawai.length)"></span>
                    dari <span x-text="filteredPegawai.length"></span> pegawai
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

        @else

        <div class="bg-white dark:bg-[#111111] rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
            <!-- Desktop View: Table -->
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-[#1a1a1a] border-b border-gray-100 dark:border-gray-800">
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400">Nama Lengkap</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400">Hubungan</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400">TTL</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400">Tgl Perkawinan</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400">Pekerjaan</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400">Tunjangan</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($keluargas as $k)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-[#18181b] transition-colors">
                                <td class="px-6 py-4 text-sm font-semibold text-slate-900 dark:text-white">{{ $k->nama }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $k->hubungan }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $k->tempat_lahir ?? '-' }}{{ $k->tanggal_lahir ? ', ' . $k->tanggal_lahir->translatedFormat('d F Y') : '' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ in_array($k->hubungan, ['Suami','Istri']) && $k->tanggal_perkawinan ? $k->tanggal_perkawinan->translatedFormat('d F Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $k->pekerjaan ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $k->tunjangan === 'Dapat' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400' }}">
                                        {{ $k->tunjangan }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <div class="relative group">
                                            <button @click="openEditModal({{ json_encode($k) }})" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-blue-600 hover:text-blue-800 hover:bg-blue-50 dark:text-blue-400 dark:hover:text-blue-300 dark:hover:bg-blue-900/30 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </button>
                                            <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1.5 px-2 py-0.5 text-[10px] font-medium bg-gray-800 text-white rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">Edit</span>
                                        </div>
                                        <div class="relative group">
                                            <button @click="confirmDelete('{{ url('/keluarga') }}/{{ $k->id }}')" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-red-500 hover:text-red-700 hover:bg-red-50 dark:text-red-400 dark:hover:text-red-300 dark:hover:bg-red-900/30 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                            <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1.5 px-2 py-0.5 text-[10px] font-medium bg-gray-800 text-white rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">Hapus</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">Belum ada data keluarga.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile View: Accordion List -->
            <div class="block sm:hidden divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($keluargas as $k)
                    <div class="p-4 space-y-3 bg-white dark:bg-[#111111]">
                        <div class="flex justify-between items-start cursor-pointer select-none" @click="toggleExpand({{ $k->id }})">
                            <div class="space-y-1">
                                <h4 class="font-bold text-slate-900 dark:text-white text-base">{{ $k->nama }}</h4>
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                                        {{ $k->hubungan }}
                                    </span>
                                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $k->tunjangan === 'Dapat' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400' }}">
                                        Tunjangan: {{ $k->tunjangan }}
                                    </span>
                                </div>
                            </div>
                            <button type="button" class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <svg class="w-5 h-5 transition-transform duration-200" :class="expandedId === {{ $k->id }} ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Accordion Detail -->
                        <div x-show="expandedId === {{ $k->id }}" x-collapse class="pt-3 border-t border-gray-100 dark:border-gray-800 space-y-3">
                            <div class="grid grid-cols-1 gap-2 text-xs">
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">TTL:</span>
                                    <span class="font-medium text-slate-900 dark:text-white ml-1">
                                        {{ $k->tempat_lahir ?? '-' }}{{ $k->tanggal_lahir ? ', ' . $k->tanggal_lahir->translatedFormat('d F Y') : '' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">Tgl Perkawinan:</span>
                                    <span class="font-medium text-slate-900 dark:text-white ml-1">
                                        {{ in_array($k->hubungan, ['Suami','Istri']) && $k->tanggal_perkawinan ? $k->tanggal_perkawinan->translatedFormat('d F Y') : '-' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">Pekerjaan:</span>
                                    <span class="font-medium text-slate-900 dark:text-white ml-1">{{ $k->pekerjaan ?? '-' }}</span>
                                </div>
                            </div>

                            <div class="pt-2 flex items-center justify-end gap-2">
                                <button @click="openEditModal({{ json_encode($k) }})" class="px-3 py-1.5 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 hover:bg-blue-100 rounded-lg text-xs font-semibold transition-colors flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Edit
                                </button>
                                <button @click="confirmDelete('{{ url('/keluarga') }}/{{ $k->id }}')" class="px-3 py-1.5 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-100 rounded-lg text-xs font-semibold transition-colors flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-500 dark:text-gray-400 text-sm">
                        Belum ada data keluarga.
                    </div>
                @endforelse
            </div>
        </div>
        @endif
    </div>
    <template x-teleport="body">
    <div x-show="detailModalOpen"
         class="flex items-center justify-center p-3 sm:p-6"
         x-cloak
         style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 99999;"
         @keydown.escape.window="detailModalOpen = false">

        <div x-show="detailModalOpen" x-transition.opacity @click="detailModalOpen = false" class="absolute inset-0 bg-slate-900/60 dark:bg-black/80 backdrop-blur-sm"></div>

        <div x-show="detailModalOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="relative w-full max-w-4xl flex flex-col bg-white dark:bg-[#111111] shadow-2xl rounded-2xl border border-gray-100 dark:border-gray-800 transition-all transform z-10 overflow-hidden">
            <div class="px-4 sm:px-6 py-4 border-b border-gray-100 dark:border-gray-800 shrink-0 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex justify-between items-start gap-2">
                    <div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">Detail Anggota Keluarga</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 break-words" x-text="selectedPegawai ? 'Pegawai: ' + selectedPegawai.nama + ' (NIP: ' + selectedPegawai.nip + ')' : ''"></p>
                    </div>
                    <button @click="detailModalOpen = false" class="sm:hidden text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg p-1 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="flex items-center gap-2">
                    <a :href="'{{ url('/cetak-kp4') }}?pegawai_id=' + selectedPegawai.id" target="_blank" class="px-3 py-1.5 bg-gray-700 hover:bg-gray-800 text-white rounded-lg text-xs font-semibold transition-colors inline-flex items-center gap-1.5 whitespace-nowrap">
                        🖨️ Cetak KP4
                    </a>
                    <button @click="openCreateModalForPegawai(selectedPegawai.id)" type="button" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold transition-colors inline-flex items-center gap-1.5 whitespace-nowrap">
                        + Tambah Keluarga
                    </button>
                    <button @click="detailModalOpen = false" class="hidden sm:block text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
            <div class="p-4 sm:p-6 overflow-y-auto max-h-[calc(100vh-14rem)] space-y-4">
                <!-- Desktop View: Table -->
                <div class="hidden sm:block overflow-x-auto border border-gray-100 dark:border-gray-800 rounded-xl">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-[#1a1a1a] border-b border-gray-100 dark:border-gray-800">
                                <th class="px-4 py-3 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Nama Lengkap</th>
                                <th class="px-4 py-3 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Hubungan</th>
                                <th class="px-4 py-3 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">TTL</th>
                                <th class="px-4 py-3 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Tgl Perkawinan</th>
                                <th class="px-4 py-3 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Pekerjaan</th>
                                <th class="px-4 py-3 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Tunjangan</th>
                                <th class="px-4 py-3 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            <template x-if="selectedPegawai && selectedPegawai.keluargas && selectedPegawai.keluargas.length > 0">
                                <template x-for="k in selectedPegawai.keluargas" :key="k.id">
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-[#18181b] transition-colors">
                                        <td class="px-4 py-3 text-sm font-semibold text-slate-900 dark:text-white" x-text="k.nama"></td>
                                        <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400" x-text="k.hubungan"></td>
                                        <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                            <span x-text="(k.tempat_lahir || '-') + (k.tanggal_lahir ? ', ' + formatDate(k.tanggal_lahir) : '')"></span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                            <span x-text="['Suami','Istri'].includes(k.hubungan) ? (k.tanggal_perkawinan ? formatDate(k.tanggal_perkawinan) : '-') : '-'"></span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400" x-text="k.pekerjaan || '-'"></td>
                                        <td class="px-4 py-3 text-sm">
                                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold"
                                                :class="k.tunjangan === 'Dapat' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400'"
                                                x-text="k.tunjangan">
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <div class="flex items-center justify-end gap-1">
                                                <div class="relative group">
                                                    <button @click="openEditModal(k)" class="inline-flex items-center justify-center w-7 h-7 rounded-lg text-blue-600 hover:text-blue-800 hover:bg-blue-50 dark:text-blue-400 dark:hover:text-blue-300 dark:hover:bg-blue-900/30 transition-colors">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                    </button>
                                                    <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 text-[10px] font-medium bg-gray-800 text-white rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">Edit</span>
                                                </div>
                                                <div class="relative group">
                                                    <button @click="confirmDelete('{{ url('/keluarga') }}/' + k.id)" class="inline-flex items-center justify-center w-7 h-7 rounded-lg text-red-500 hover:text-red-700 hover:bg-red-50 dark:text-red-400 dark:hover:text-red-300 dark:hover:bg-red-900/30 transition-colors">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                    <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 text-[10px] font-medium bg-gray-800 text-white rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">Hapus</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </template>
                            <template x-if="!selectedPegawai || !selectedPegawai.keluargas || selectedPegawai.keluargas.length === 0">
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">Pegawai ini belum memiliki data anggota keluarga.</td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- Mobile View: Card List -->
                <div class="block sm:hidden border border-gray-100 dark:border-gray-800 rounded-xl overflow-hidden divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-[#111111]">
                    <template x-if="selectedPegawai && selectedPegawai.keluargas && selectedPegawai.keluargas.length > 0">
                        <template x-for="k in selectedPegawai.keluargas" :key="k.id">
                            <div class="p-3.5 space-y-2">
                                <div class="flex justify-between items-start gap-2">
                                    <div>
                                        <h4 class="font-bold text-slate-900 dark:text-white text-sm" x-text="k.nama"></h4>
                                        <div class="flex flex-wrap items-center gap-1.5 mt-1">
                                            <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300" x-text="k.hubungan"></span>
                                            <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold"
                                                :class="k.tunjangan === 'Dapat' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400'"
                                                x-text="'Tunjangan: ' + k.tunjangan">
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1 shrink-0">
                                        <button @click="openEditModal(k)" class="p-1.5 text-blue-600 hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-900/30 rounded-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        <button @click="confirmDelete('{{ url('/keluarga') }}/' + k.id)" class="p-1.5 text-red-500 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/30 rounded-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 gap-1 text-xs text-gray-500 dark:text-gray-400 pt-2 border-t border-gray-100 dark:border-gray-800">
                                    <div>TTL: <span class="font-medium text-slate-800 dark:text-gray-200" x-text="(k.tempat_lahir || '-') + (k.tanggal_lahir ? ', ' + formatDate(k.tanggal_lahir) : '')"></span></div>
                                    <div x-show="['Suami','Istri'].includes(k.hubungan)">Tgl Perkawinan: <span class="font-medium text-slate-800 dark:text-gray-200" x-text="k.tanggal_perkawinan ? formatDate(k.tanggal_perkawinan) : '-'"></span></div>
                                    <div>Pekerjaan: <span class="font-medium text-slate-800 dark:text-gray-200" x-text="k.pekerjaan || '-'"></span></div>
                                </div>
                            </div>
                        </template>
                    </template>
                    <template x-if="!selectedPegawai || !selectedPegawai.keluargas || selectedPegawai.keluargas.length === 0">
                        <div class="p-6 text-center text-xs text-gray-500 dark:text-gray-400">
                            Pegawai ini belum memiliki data anggota keluarga.
                        </div>
                    </template>
                </div>
            </div>

            <div class="flex justify-end px-6 py-4 border-t border-gray-100 dark:border-gray-800 shrink-0">
                <button type="button" @click="detailModalOpen = false" class="px-5 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-[#111111] dark:text-gray-300 dark:border-gray-700 transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>
    </template>
    <template x-teleport="body">
    <div x-show="modalOpen"
         class="flex items-center justify-center p-3 sm:p-6"
         x-cloak
         style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 999999;"
         @keydown.escape.window="modalOpen = false">
        <div x-show="modalOpen" x-transition.opacity @click="modalOpen = false" class="absolute inset-0 bg-slate-900/60 dark:bg-black/80 backdrop-blur-sm"></div>
        <div x-show="modalOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="relative w-full max-w-xl flex flex-col bg-white dark:bg-[#111111] shadow-2xl rounded-2xl border border-gray-100 dark:border-gray-800 transition-all transform z-10 overflow-hidden">
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100 dark:border-gray-800">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white" x-text="isEdit ? 'Edit Data Keluarga' : 'Tambah Data Keluarga'"></h3>
                <button @click="modalOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form :action="formAction" method="POST" id="keluarga-form" class="p-6 space-y-4 max-h-[calc(100vh-12rem)] overflow-y-auto">
                @csrf
                <template x-if="isEdit">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                @if(in_array(auth()->user()->role, ['admin', 'superadmin']))
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Pilih Pegawai <span class="text-red-500">*</span></label>
                    <select name="pegawai_id" x-model="form.pegawai_id" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                        <option value="">-- Pilih Pegawai --</option>
                        @foreach($pegawais as $p)
                            <option value="{{ $p->id }}">{{ $p->nama }} (NIP: {{ $p->nip }})</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" x-model="form.nama" required placeholder="Masukkan nama lengkap" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Hubungan Keluarga <span class="text-red-500">*</span></label>
                    <select name="hubungan" x-model="form.hubungan" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                        <option value="">-- Pilih Hubungan --</option>
                        <option value="Suami">Suami</option>
                        <option value="Istri">Istri</option>
                        <option value="Anak">Anak</option>
                        <option value="Ayah">Ayah</option>
                        <option value="Ibu">Ibu</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Pekerjaan <span class="text-red-500">*</span></label>
                    <select name="pekerjaan" x-model="form.pekerjaan" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                        <option value="">-- Pilih Pekerjaan --</option>
                        <option value="ASN">ASN</option>
                        <option value="Swasta">Swasta</option>
                        <option value="BUMN">BUMN</option>
                        <option value="BUMD">BUMD</option>
                        <option value="IRT">IRT</option>
                        <option value="Tidak Bekerja">Tidak Bekerja</option>
                        <option value="Pelajar / Mahasiswa">Pelajar / Mahasiswa</option>
                        <option value="Ayah">Ayah</option>
                        <option value="Ibu">Ibu</option>
                        <option value="Pensiunan">Pensiunan</option>
                        <option value="Wiraswasta">Wiraswasta</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tempat Lahir <span class="text-red-500">*</span></label>
                        <input type="text" name="tempat_lahir" x-model="form.tempat_lahir" required placeholder="Kota/Kabupaten" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tanggal Lahir <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_lahir" x-model="form.tanggal_lahir" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                    </div>
                </div>
                <div x-show="['Suami', 'Istri'].includes(form.hubungan)" x-transition x-cloak>
                    <label class="block text-sm font-medium text-blue-600 dark:text-blue-400 mb-1.5">Tanggal Perkawinan <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_perkawinan" x-model="form.tanggal_perkawinan" :required="['Suami', 'Istri'].includes(form.hubungan)" class="w-full px-4 py-2 border-2 border-blue-100 dark:border-blue-900/30 rounded-lg bg-blue-50/30 dark:bg-blue-900/10 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                    <p class="text-xs text-gray-500 mt-1">Khusus untuk hubungan Suami atau Istri.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Status Tunjangan <span class="text-red-500">*</span></label>
                    <select name="tunjangan" x-model="form.tunjangan" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                        <option value="Dapat">Dapat</option>
                        <option value="Tidak Dapat">Tidak Dapat</option>
                    </select>
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                    <button type="button" @click="modalOpen = false" class="px-5 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-[#111111] dark:text-gray-300 dark:border-gray-700 dark:hover:bg-slate-800 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-md shadow-blue-500/20 transition-colors">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
    </template>
    <form id="delete-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('keluargaCrud', () => ({
        allPegawai: @json($pegawais),
        search: '',
        perPage: 10,
        currentPage: 1,
        expandedId: null,

        toggleExpand(id) {
            this.expandedId = this.expandedId === id ? null : id;
        },

        detailModalOpen: false,
        selectedPegawai: null,

        modalOpen: false,
        isEdit: false,
        formAction: '',
        form: {
            pegawai_id: '',
            nama: '',
            hubungan: '',
            pekerjaan: '',
            tempat_lahir: '',
            tanggal_lahir: '',
            tanggal_perkawinan: '',
            tunjangan: 'Dapat'
        },

        init() {
            this.$watch('search', () => { this.currentPage = 1; });
            this.$watch('perPage', () => { this.currentPage = 1; });
        },

        formatDate(dateStr) {
            if (!dateStr) return '-';
            const cleanDate = dateStr.split('T')[0];
            const parts = cleanDate.split('-');
            if (parts.length === 3) {
                const year = parts[0];
                const monthIdx = parseInt(parts[1], 10) - 1;
                const day = parseInt(parts[2], 10);
                const bulanIndo = [
                    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                ];
                if (monthIdx >= 0 && monthIdx < 12) {
                    return `${day} ${bulanIndo[monthIdx]} ${year}`;
                }
            }
            return dateStr;
        },

        get filteredPegawai() {
            let result = this.allPegawai;
            if (this.search) {
                const q = this.search.toLowerCase();
                result = result.filter(p =>
                    (p.nama && p.nama.toLowerCase().includes(q)) ||
                    (p.nip && p.nip.toLowerCase().includes(q))
                );
            }
            return result;
        },

        get paginatedPegawai() {
            const start = (this.currentPage - 1) * this.perPage;
            return this.filteredPegawai.slice(start, start + parseInt(this.perPage));
        },

        get totalPages() {
            return Math.ceil(this.filteredPegawai.length / this.perPage);
        },

        openDetailModal(p) {
            this.selectedPegawai = p;
            this.detailModalOpen = true;
        },

        openCreateModalForPegawai(pegawaiId) {
            this.isEdit = false;
            this.formAction = '{{ route("keluarga.store") }}';
            this.form = {
                pegawai_id: pegawaiId,
                nama: '',
                hubungan: '',
                pekerjaan: '',
                tempat_lahir: '',
                tanggal_lahir: '',
                tanggal_perkawinan: '',
                tunjangan: 'Dapat'
            };
            this.modalOpen = true;
        },

        openCreateModal() {
            this.isEdit = false;
            this.formAction = '{{ route("keluarga.store") }}';
            this.form = {
                pegawai_id: '',
                nama: '',
                hubungan: '',
                pekerjaan: '',
                tempat_lahir: '',
                tanggal_lahir: '',
                tanggal_perkawinan: '',
                tunjangan: 'Dapat'
            };
            this.modalOpen = true;
        },

        openEditModal(k) {
            this.isEdit = true;
            this.formAction = `/keluarga/${k.id}`;
            this.form = {
                pegawai_id: k.pegawai_id,
                nama: k.nama,
                hubungan: k.hubungan,
                pekerjaan: k.pekerjaan || '',
                tempat_lahir: k.tempat_lahir || '',
                tanggal_lahir: k.tanggal_lahir ? k.tanggal_lahir.split('T')[0] : '',
                tanggal_perkawinan: k.tanggal_perkawinan ? k.tanggal_perkawinan.split('T')[0] : '',
                tunjangan: k.tunjangan
            };
            this.modalOpen = true;
        },

        confirmDelete(url) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data keluarga akan dihapus!",
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
            });
        }
    }));
});
</script>
@endsection
