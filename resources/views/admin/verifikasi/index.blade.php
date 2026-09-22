@extends('layouts.admin')

@section('title', 'Verifikasi Perubahan Data')

@section('content')
<div x-data="verifikasiCrud()" class="max-w-7xl mx-auto space-y-6 pb-20">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Verifikasi Perubahan Data Pegawai</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Kelola dan tinjau usulan perubahan data profil yang dikirimkan oleh pegawai.</p>
        </div>

        {{-- Status Filter Badges --}}
        <div class="grid grid-cols-2 sm:flex items-center gap-1.5 bg-white dark:bg-[#111111] p-1.5 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm w-full sm:w-auto">
            <a href="{{ route('admin.verifikasi.index', ['status' => 'pending']) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-bold transition-colors flex items-center justify-center gap-2 {{ $status === 'pending' ? 'bg-amber-500 text-white shadow-sm' : 'text-gray-500 hover:text-slate-900 dark:hover:text-white' }}">
                <span>Menunggu</span>
                @if($counts['pending'] > 0)
                    <span class="px-1.5 py-0.5 text-[10px] rounded-full {{ $status === 'pending' ? 'bg-white text-amber-600 font-extrabold' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-400 font-bold' }}">
                        {{ $counts['pending'] }}
                    </span>
                @endif
            </a>
            <a href="{{ route('admin.verifikasi.index', ['status' => 'disetujui']) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-bold transition-colors flex items-center justify-center gap-2 {{ $status === 'disetujui' ? 'bg-emerald-600 text-white shadow-sm' : 'text-gray-500 hover:text-slate-900 dark:hover:text-white' }}">
                <span>Disetujui</span>
                <span class="px-1.5 py-0.5 text-[10px] rounded-full {{ $status === 'disetujui' ? 'bg-white text-emerald-600 font-extrabold' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400 font-bold' }}">
                    {{ $counts['disetujui'] }}
                </span>
            </a>
            <a href="{{ route('admin.verifikasi.index', ['status' => 'ditolak']) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-bold transition-colors flex items-center justify-center gap-2 {{ $status === 'ditolak' ? 'bg-rose-600 text-white shadow-sm' : 'text-gray-500 hover:text-slate-900 dark:hover:text-white' }}">
                <span>Ditolak</span>
                <span class="px-1.5 py-0.5 text-[10px] rounded-full {{ $status === 'ditolak' ? 'bg-white text-rose-600 font-extrabold' : 'bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-400 font-bold' }}">
                    {{ $counts['ditolak'] }}
                </span>
            </a>
            <a href="{{ route('admin.verifikasi.index', ['status' => 'semua']) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-bold transition-colors flex items-center justify-center {{ $status === 'semua' ? 'bg-slate-700 text-white shadow-sm' : 'text-gray-500 hover:text-slate-900 dark:hover:text-white' }}">
                <span>Semua ({{ $counts['total'] }})</span>
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 dark:bg-emerald-900/20 border-l-4 border-emerald-500 p-4 rounded-xl shadow-sm flex items-center justify-between">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="text-sm font-semibold text-emerald-800 dark:text-emerald-300">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-rose-50 dark:bg-rose-900/20 border-l-4 border-rose-500 p-4 rounded-xl shadow-sm flex items-center justify-between">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-rose-600 dark:text-rose-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            <span class="text-sm font-semibold text-rose-800 dark:text-rose-300">{{ session('error') }}</span>
        </div>
    </div>
    @endif

    <div class="bg-white dark:bg-[#111111] rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-[#1a1a1a] border-b border-gray-100 dark:border-gray-800">
                        <th class="px-6 py-4 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase whitespace-nowrap">Tgl Pengajuan</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Pegawai</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">NIP</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Status Usulan</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase whitespace-nowrap">Tgl Berlaku Usulan</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-sm">
                    @forelse($pengajuans as $item)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-[#18181b] transition-colors">
                            <td class="px-6 py-4 text-xs font-mono text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                {{ $item->created_at->translatedFormat('d M Y, H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full overflow-hidden border border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-slate-800 shrink-0 flex items-center justify-center font-bold text-blue-600">
                                        @if($item->pegawai && $item->pegawai->foto)
                                            <img src="{{ asset('storage/' . $item->pegawai->foto) }}" class="w-full h-full object-cover">
                                        @else
                                            <span>{{ strtoupper(substr($item->pegawai->nama ?? 'P', 0, 1)) }}</span>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-900 dark:text-white leading-tight">
                                            {{ $item->pegawai ? $item->pegawai->nama_lengkap : ($item->user->username ?? 'User') }}
                                        </p>
                                        <p class="text-xs text-gray-400">{{ $item->user->email ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-mono text-xs text-gray-600 dark:text-gray-400 whitespace-nowrap">
                                {{ $item->pegawai->nip ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->status === 'pending')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 border border-amber-200 dark:border-amber-800/50 flex items-center gap-1.5 w-max">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-ping"></span>
                                        Menunggu Persetujuan
                                    </span>
                                @elseif($item->status === 'disetujui')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50 flex items-center gap-1.5 w-max">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Disetujui
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400 border border-rose-200 dark:border-rose-800/50 flex items-center gap-1.5 w-max">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        Ditolak
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs font-semibold text-slate-700 dark:text-gray-300 whitespace-nowrap">
                                {{ isset($item->data_baru['tanggal_berlaku']) ? \Carbon\Carbon::parse($item->data_baru['tanggal_berlaku'])->translatedFormat('d F Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <button type="button" @click="openDetailModalById({{ $item->id }})"
                                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-xs shadow-md shadow-blue-500/20 transition-all flex items-center gap-1.5 ml-auto">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    Tinjau & Komparasi
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                <div class="max-w-xs mx-auto text-center space-y-2">
                                    <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-slate-800 text-gray-400 flex items-center justify-center mx-auto">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <p class="font-bold text-slate-800 dark:text-slate-200 text-sm">Tidak ada pengajuan perubahan data</p>
                                    <p class="text-xs text-gray-400">Belum ada permohonan perubahan data pegawai pada kategori ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pengajuans->hasPages())
        <div class="p-4 border-t border-gray-100 dark:border-gray-800">
            {{ $pengajuans->links() }}
        </div>
        @endif
    </div>

    {{-- MODAL DETAIL KOMPARASI & PERSETUJUAN --}}
    <template x-teleport="body">
        <div x-show="modalDetailOpen"
             class="flex items-center justify-center p-4 sm:p-6"
             x-cloak
             style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 99999;"
             @keydown.escape.window="modalDetailOpen = false">

            <div x-show="modalDetailOpen"
                 x-transition.opacity
                 @click="modalDetailOpen = false"
                 class="absolute inset-0 bg-slate-900/60 dark:bg-black/80 backdrop-blur-sm"></div>

            <div x-show="modalDetailOpen"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="relative w-full max-w-4xl max-h-[calc(100vh-3rem)] sm:max-h-[calc(100vh-4rem)] flex flex-col bg-white dark:bg-[#111111] shadow-2xl rounded-2xl border border-gray-100 dark:border-gray-800 transition-all transform z-10 overflow-hidden">
                
                {{-- Modal Header --}}
                <div class="flex justify-between items-center gap-4 px-6 py-4 border-b border-gray-100 dark:border-gray-800 shrink-0">
                    <div class="min-w-0 flex items-center gap-3">
                        <div class="p-2.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 dark:text-white text-base">Verifikasi Usulan Perubahan Data</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400" x-text="selectedItem ? 'Pegawai: ' + (selectedItem.pegawai ? selectedItem.pegawai.nama_lengkap : '') : ''"></p>
                        </div>
                    </div>
                    <button type="button" @click="modalDetailOpen = false" class="shrink-0 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg p-1.5 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                {{-- Modal Content Scrollable --}}
                <div class="p-6 space-y-5 overflow-y-auto flex-1 custom-scrollbar">
                    {{-- Tanggal Berlaku Alert --}}
                    <div class="p-4 bg-blue-50/70 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800/60 rounded-xl flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-blue-600 text-white rounded-lg shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <span class="text-[10px] text-blue-600 dark:text-blue-400 font-extrabold uppercase tracking-wider block">Tanggal Berlaku Usulan:</span>
                                <span class="text-sm font-extrabold text-slate-900 dark:text-white" x-text="selectedItem && selectedItem.data_baru && selectedItem.data_baru.tanggal_berlaku ? new Date(selectedItem.data_baru.tanggal_berlaku).toLocaleDateString('id-ID', {day:'numeric',month:'long',year:'numeric'}) : '-'"></span>
                            </div>
                        </div>

                        <span class="px-3 py-1 rounded-full text-xs font-extrabold"
                              :class="{
                                  'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 border border-amber-200 dark:border-amber-800': selectedItem && selectedItem.status === 'pending',
                                  'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800': selectedItem && selectedItem.status === 'disetujui',
                                  'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400 border border-rose-200 dark:border-rose-800': selectedItem && selectedItem.status === 'ditolak'
                              }" x-text="selectedItem ? selectedItem.status.toUpperCase() : ''"></span>
                    </div>

                    {{-- Toggle View Mode (Hanya Data Berubah VS Semua Field) --}}
                    <div class="flex items-center justify-between pb-1">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-slate-800 dark:text-white">Daftar Komparasi Field</span>
                            <span class="px-2 py-0.5 text-[10px] bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300 font-extrabold rounded-full"
                                  x-text="changedFieldsCount + ' Field Berubah'"></span>
                        </div>

                        <div class="flex items-center gap-2 bg-gray-100 dark:bg-slate-800 p-1 rounded-lg">
                            <button type="button" @click="showOnlyChanged = true"
                                    :class="showOnlyChanged ? 'bg-white dark:bg-slate-900 text-blue-600 dark:text-blue-400 shadow-sm font-bold' : 'text-gray-500 hover:text-slate-800 dark:hover:text-white'"
                                    class="px-3 py-1 text-xs rounded-md transition-colors">
                                Hanya Data Berubah
                            </button>
                            <button type="button" @click="showOnlyChanged = false"
                                    :class="!showOnlyChanged ? 'bg-white dark:bg-slate-900 text-blue-600 dark:text-blue-400 shadow-sm font-bold' : 'text-gray-500 hover:text-slate-800 dark:hover:text-white'"
                                    class="px-3 py-1 text-xs rounded-md transition-colors">
                                Tampilkan Semua (17)
                            </button>
                        </div>
                    </div>

                    {{-- Table Side-by-Side Comparison --}}
                    <div class="border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden shadow-sm">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-slate-800 border-b border-gray-200 dark:border-gray-700 text-slate-700 dark:text-gray-300">
                                    <th class="p-3 font-bold w-1/3">ATRIBUT / FIELD</th>
                                    <th class="p-3 font-bold w-1/3 bg-gray-50 dark:bg-slate-900">DATA SAAT INI (LAMA)</th>
                                    <th class="p-3 font-bold w-1/3 bg-amber-50/70 dark:bg-amber-900/20 text-amber-900 dark:text-amber-200">USULAN BARU</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                <template x-for="field in filteredFields" :key="field.key">
                                    <tr :class="isFieldChanged(field.key) ? 'bg-amber-50/50 dark:bg-amber-900/15' : ''">
                                        <td class="p-3 font-bold text-slate-800 dark:text-slate-200" x-text="field.label"></td>
                                        <td class="p-3 bg-gray-50/50 dark:bg-slate-900/40 text-gray-600 dark:text-gray-400" x-text="getDisplayValue(field.key, 'lama')"></td>
                                        <td class="p-3 font-semibold" :class="isFieldChanged(field.key) ? 'bg-amber-100/60 dark:bg-amber-900/30 text-amber-950 dark:text-amber-200 font-bold' : 'text-slate-800 dark:text-slate-200'">
                                            <span x-text="getDisplayValue(field.key, 'baru')"></span>
                                            <span x-show="isFieldChanged(field.key)" class="ml-1.5 px-1.5 py-0.5 text-[9px] bg-amber-500 text-white rounded font-bold uppercase">Berubah</span>
                                        </td>
                                    </tr>
                                </template>

                                <template x-if="filteredFields.length === 0">
                                    <tr>
                                        <td colspan="3" class="p-6 text-center text-gray-400 italic">
                                            Tidak ada perubahan data atribut (kecuali tanggal/foto profil).
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    {{-- Preview Foto Profil Jika Diusulkan Foto Baru --}}
                    <template x-if="selectedItem && selectedItem.data_baru && selectedItem.data_baru.foto_temp">
                        <div class="p-4 bg-gray-50 dark:bg-slate-900/50 border border-gray-200 dark:border-gray-800 rounded-xl space-y-2">
                            <span class="text-xs font-bold text-slate-800 dark:text-white block">Komparasi Foto Profil Baru:</span>
                            <div class="flex items-center gap-6 justify-center pt-2">
                                <div class="text-center space-y-1">
                                    <div class="w-20 h-20 rounded-xl overflow-hidden border-2 border-gray-300 dark:border-gray-700 bg-gray-100 mx-auto">
                                        <img :src="selectedItem.pegawai && selectedItem.pegawai.foto ? '/storage/' + selectedItem.pegawai.foto : 'https://ui-avatars.com/api/?name=P'" class="w-full h-full object-cover">
                                    </div>
                                    <span class="text-[10px] text-gray-400 font-medium">Foto Lama</span>
                                </div>

                                <svg class="w-5 h-5 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>

                                <div class="text-center space-y-1">
                                    <div class="w-20 h-20 rounded-xl overflow-hidden border-2 border-amber-500 bg-gray-100 mx-auto shadow-md">
                                        <img :src="'/storage/' + selectedItem.data_baru.foto_temp" class="w-full h-full object-cover">
                                    </div>
                                    <span class="text-[10px] text-amber-600 dark:text-amber-400 font-bold">Foto Usulan Baru</span>
                                </div>
                            </div>
                        </div>
                    </template>

                    <div x-show="selectedItem && selectedItem.catatan_admin" class="p-4 bg-rose-50/50 dark:bg-rose-900/10 rounded-xl border border-rose-200 dark:border-rose-800/40">
                        <span class="text-xs font-bold text-rose-700 dark:text-rose-400 block mb-1">Catatan Admin / Rejection Reason:</span>
                        <p class="text-xs text-rose-900 dark:text-rose-200 italic" x-text="selectedItem ? selectedItem.catatan_admin : ''"></p>
                    </div>
                </div>

                {{-- Modal Footer Actions --}}
                <div class="px-4 sm:px-6 py-4 bg-gray-50 dark:bg-slate-900/50 border-t border-gray-100 dark:border-gray-800 flex flex-col-reverse sm:flex-row justify-between items-stretch sm:items-center gap-3 shrink-0">
                    <button type="button" @click="modalDetailOpen = false" class="px-4 py-2.5 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-xl text-xs hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors text-center">Tutup</button>
                    
                    <div x-show="selectedItem && selectedItem.status === 'pending'" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3">
                        <button type="button" @click="openTolakModal()" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl text-xs shadow-md shadow-rose-500/20 transition-all flex items-center justify-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            Tolak Pengajuan
                        </button>
                        <form :action="'/admin/verifikasi-perubahan/' + (selectedItem ? selectedItem.id : '') + '/setujui'" method="POST">
                            @csrf
                            <button type="submit" class="w-full px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs shadow-md shadow-emerald-500/20 transition-all flex items-center justify-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Setujui & Terapkan Perubahan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </template>

    {{-- MODAL PROMPT ALASAN PENOLAKAN --}}
    <template x-teleport="body">
        <div x-show="modalTolakOpen"
             class="flex items-center justify-center p-4 sm:p-6"
             x-cloak
             style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 100000;"
             @keydown.escape.window="modalTolakOpen = false">

            <div x-show="modalTolakOpen"
                 x-transition.opacity
                 @click="modalTolakOpen = false"
                 class="absolute inset-0 bg-slate-900/60 dark:bg-black/80 backdrop-blur-sm"></div>

            <div x-show="modalTolakOpen"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="relative max-w-md w-full bg-white dark:bg-[#111111] shadow-2xl rounded-2xl border border-gray-100 dark:border-gray-800 p-6 space-y-4 z-10 overflow-hidden">
                <h3 class="font-bold text-slate-900 dark:text-white text-base">Tolak Pengajuan Perubahan Data</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400">Berikan catatan alasan penolakan agar pegawai mengetahui hal yang perlu diperbaiki.</p>
                
                <form :action="'/admin/verifikasi-perubahan/' + (selectedItem ? selectedItem.id : '') + '/tolak'" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-gray-300 mb-1">Catatan Penolakan</label>
                        <textarea name="catatan_admin" rows="3" required placeholder="Contoh: Lampiran foto kurang jelas / data NIP tidak sesuai" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white text-xs focus:ring-2 focus:ring-rose-500 outline-none"></textarea>
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="modalTolakOpen = false" class="px-4 py-2 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-xl text-xs hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl text-xs shadow-md shadow-rose-500/20 transition-all">Kirim Penolakan</button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('verifikasiCrud', () => ({
        modalDetailOpen: false,
        modalTolakOpen: false,
        showOnlyChanged: true,
        selectedItem: null,
        allPengajuans: @json($pengajuans->items()),
        allJabatans: @json($allJabatans),
        allBidangs: @json($allBidangs),

        compareFields: [
            { key: 'username', label: 'Username' },
            { key: 'email', label: 'Email' },
            { key: 'nama', label: 'Nama Lengkap' },
            { key: 'gelar_depan', label: 'Gelar Depan' },
            { key: 'gelar_belakang', label: 'Gelar Belakang' },
            { key: 'nip', label: 'NIP' },
            { key: 'nik', label: 'NIK' },
            { key: 'tempat_lahir', label: 'Tempat Lahir' },
            { key: 'tanggal_lahir', label: 'Tanggal Lahir' },
            { key: 'jenis_kelamin', label: 'Jenis Kelamin' },
            { key: 'agama', label: 'Agama' },
            { key: 'status_kepegawaian', label: 'Status Kepegawaian' },
            { key: 'bidang_id', label: 'Bidang / Unit Kerja' },
            { key: 'jabatan_id', label: 'Jabatan' },
            { key: 'golongan', label: 'Golongan' },
            { key: 'status_pernikahan', label: 'Status Pernikahan' },
            { key: 'alamat', label: 'Alamat Domisili' },
        ],

        get filteredFields() {
            if (!this.showOnlyChanged) return this.compareFields;
            return this.compareFields.filter(f => this.isFieldChanged(f.key));
        },

        get changedFieldsCount() {
            return this.compareFields.filter(f => this.isFieldChanged(f.key)).length;
        },

        openDetailModalById(id) {
            const found = this.allPengajuans.find(i => i.id == id);
            if (found) {
                this.selectedItem = found;
                this.showOnlyChanged = true;
                this.modalDetailOpen = true;
            }
        },

        openDetailModal(item) {
            this.selectedItem = item;
            this.showOnlyChanged = true;
            this.modalDetailOpen = true;
        },

        openTolakModal() {
            this.modalTolakOpen = true;
        },

        getDisplayValue(key, type) {
            if (!this.selectedItem) return '-';
            const data = type === 'lama' ? (this.selectedItem.data_lama || {}) : (this.selectedItem.data_baru || {});
            let val = data[key];

            if (val === null || val === undefined || val === '') return '-';

            if (key === 'bidang_id') {
                const b = this.allBidangs[val];
                return b ? b.nama_bidang + ' (' + b.singkatan + ')' : val;
            }

            if (key === 'jabatan_id') {
                const j = this.allJabatans[val];
                return j ? j.nama_jabatan : val;
            }

            if (key === 'tanggal_lahir') {
                return new Date(val).toLocaleDateString('id-ID', {day:'numeric',month:'long',year:'numeric'});
            }

            return val;
        },

        isFieldChanged(key) {
            if (!this.selectedItem) return false;
            const lama = this.getDisplayValue(key, 'lama');
            const baru = this.getDisplayValue(key, 'baru');
            return String(lama).trim() !== String(baru).trim();
        }
    }));
});
</script>
@endsection
