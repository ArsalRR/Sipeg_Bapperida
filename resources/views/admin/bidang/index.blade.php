@extends('layouts.admin')

@section('title', 'Data Bidang / Unit Kerja')

@section('content')
<div x-data="{ 
    modalOpen: false, 
    isEdit: false, 
    search: '',
    expandedId: null,
    form: { id: null, nama_bidang: '', singkatan: '' },
    openCreate() {
        this.isEdit = false;
        this.form = { id: null, nama_bidang: '', singkatan: '' };
        this.modalOpen = true;
    },
    openEdit(bidang) {
        this.isEdit = true;
        this.form = { id: bidang.id, nama_bidang: bidang.nama_bidang, singkatan: bidang.singkatan };
        this.modalOpen = true;
    }
}" class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Data Bidang & Unit Kerja</h1>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Kelola master bidang, unit kerja, dan singkatan hirarki BAPPERIDA.</p>
        </div>
        <div>
            <button @click="openCreate()" class="w-full sm:w-auto px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/20 transition-all text-xs flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Bidang / Unit Baru
            </button>
        </div>
    </div>

    <!-- Alert Flash -->
    @if(session('success'))
    <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-xl text-emerald-700 dark:text-emerald-400 text-xs flex items-center gap-2">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div class="p-4 bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 rounded-xl text-rose-700 dark:text-rose-400 text-xs flex items-center gap-2">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <!-- Table Card Container -->
    <div class="bg-white dark:bg-[#111111] rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
        
        <!-- Search Input Bar -->
        <div class="p-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center gap-4">
            <div class="relative w-full sm:w-64">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" x-model="search" placeholder="Cari nama bidang..." class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none text-sm">
            </div>
        </div>

        <!-- LAYAR DESKTOP: TABEL -->
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700 dark:text-gray-300">
                <thead class="bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white uppercase font-bold tracking-wider border-b border-gray-100 dark:border-gray-800">
                    <tr>
                        <th class="px-6 py-4 w-12 text-center">No</th>
                        <th class="px-6 py-4">Nama Bidang / Unit Kerja</th>
                        <th class="px-6 py-4">Singkatan</th>
                        <th class="px-6 py-4 text-center">Jumlah Pegawai</th>
                        <th class="px-6 py-4 text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($bidangs as $index => $b)
                    <tr x-show="!search || '{{ strtolower($b->nama_bidang . ' ' . $b->singkatan) }}'.includes(search.toLowerCase())" class="hover:bg-gray-50/50 dark:hover:bg-slate-900/50 transition-colors">
                        <td class="px-6 py-4 text-center font-medium">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 font-bold text-slate-900 dark:text-white">
                            {{ $b->nama_bidang }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-mono font-bold bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-200 dark:border-blue-800/50">
                                {{ $b->singkatan }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center font-semibold">
                            {{ $b->pegawais_count }} Orang
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button @click="openEdit({{ json_encode($b) }})" class="p-2 text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <form action="{{ route('admin.bidangs.destroy', $b->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data bidang ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg transition-colors" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-400">Belum ada data bidang / unit kerja.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- LAYAR MOBILE: CARD ACCORDION LIST UNTUK DAFTAR BIDANG / UNIT -->
        <div class="block sm:hidden p-3 space-y-3">
            @forelse($bidangs as $b)
            <div x-show="!search || '{{ strtolower($b->nama_bidang . ' ' . $b->singkatan) }}'.includes(search.toLowerCase())" class="bg-white dark:bg-[#18181b] border border-gray-200 dark:border-gray-800 rounded-2xl overflow-hidden transition-all shadow-sm">
                
                {{-- Card Header Mobile --}}
                <button @click="expandedId = (expandedId === {{ $b->id }} ? null : {{ $b->id }})" type="button" class="w-full p-4 flex items-center justify-between text-left hover:bg-gray-50 dark:hover:bg-slate-800/40 transition-colors">
                    <div class="flex items-center gap-3 min-w-0">
                        {{-- Icon Bidang --}}
                        <div class="shrink-0 w-11 h-11 rounded-full bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 font-bold flex items-center justify-center border border-blue-200 dark:border-blue-800/50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>

                        {{-- Nama Bidang & Singkatan --}}
                        <div class="min-w-0 flex-1">
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white truncate leading-tight">{{ $b->nama_bidang }}</h3>
                            <div class="flex items-center gap-1.5 mt-1.5 flex-wrap">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-mono font-bold bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 whitespace-nowrap">{{ $b->singkatan }}</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 whitespace-nowrap">{{ $b->pegawais_count }} Pegawai</span>
                            </div>
                        </div>
                    </div>

                    {{-- Chevron Icon --}}
                    <div class="ml-2 shrink-0 p-1 text-gray-400">
                        <svg class="w-5 h-5 transition-transform duration-200" :class="expandedId === {{ $b->id }} ? 'rotate-180 text-blue-600 dark:text-blue-400' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </button>

                {{-- Card Expanded Detail Body --}}
                <div x-show="expandedId === {{ $b->id }}" x-collapse class="px-4 pb-4 pt-2 border-t border-gray-100 dark:border-gray-800/80 space-y-3">
                    <div class="grid grid-cols-2 gap-2 text-xs pt-1">
                        <div class="col-span-2 bg-gray-50 dark:bg-[#111111] p-2.5 rounded-xl border border-gray-100 dark:border-gray-800">
                            <span class="text-gray-400 dark:text-gray-500 block text-[10px] uppercase font-semibold">Nama Bidang / Unit Kerja</span>
                            <span class="font-medium text-slate-800 dark:text-slate-200">{{ $b->nama_bidang }}</span>
                        </div>
                        <div class="bg-gray-50 dark:bg-[#111111] p-2.5 rounded-xl border border-gray-100 dark:border-gray-800">
                            <span class="text-gray-400 dark:text-gray-500 block text-[10px] uppercase font-semibold">Kode / Singkatan</span>
                            <span class="font-mono font-bold text-blue-600 dark:text-blue-400">{{ $b->singkatan }}</span>
                        </div>
                        <div class="bg-gray-50 dark:bg-[#111111] p-2.5 rounded-xl border border-gray-100 dark:border-gray-800">
                            <span class="text-gray-400 dark:text-gray-500 block text-[10px] uppercase font-semibold">Jumlah Pegawai</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200">{{ $b->pegawais_count }} Orang</span>
                        </div>
                    </div>

                    {{-- Tombol Aksi Mobile --}}
                    <div class="flex items-center gap-2 pt-2">
                        <button @click="openEdit({{ json_encode($b) }})" type="button" class="flex-1 py-2.5 px-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold flex items-center justify-center gap-1 transition-colors shadow-sm whitespace-nowrap">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            <span>Edit</span>
                        </button>
                        <form action="{{ route('admin.bidangs.destroy', $b->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data bidang ini?');" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full py-2.5 px-2 bg-red-50 hover:bg-red-100 dark:bg-rose-900/20 dark:hover:bg-rose-900/30 text-red-600 dark:text-rose-400 border border-red-200 dark:border-rose-900/40 rounded-xl text-xs font-semibold flex items-center justify-center gap-1 transition-colors whitespace-nowrap">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                <span>Hapus</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-6 text-center text-gray-500 dark:text-gray-400 text-sm">Belum ada data bidang / unit kerja.</div>
            @endforelse
        </div>

    </div>

    <!-- Modal Form (Tambah / Edit) -->
    <div x-show="modalOpen" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" style="display: none;">
        <div @click.away="modalOpen = false" class="bg-white dark:bg-[#18181b] rounded-2xl shadow-2xl w-full max-w-md overflow-hidden border border-gray-100 dark:border-gray-800">
            <div class="bg-blue-600 px-6 py-4 flex justify-between items-center text-white">
                <h3 class="font-bold text-sm" x-text="isEdit ? 'Edit Data Bidang / Unit' : 'Tambah Bidang / Unit Baru'"></h3>
                <button @click="modalOpen = false" class="text-white/80 hover:text-white text-xl font-bold">&times;</button>
            </div>
            <form :action="isEdit ? '{{ url('admin/bidangs') }}/' + form.id : '{{ route('admin.bidangs.store') }}'" method="POST" class="p-6 space-y-4">
                @csrf
                <template x-if="isEdit">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-gray-300 mb-1">Nama Bidang / Unit Kerja <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_bidang" x-model="form.nama_bidang" placeholder="Misal: Bidang Pemerintahan dan Pembangunan Manusia" required class="w-full px-3.5 py-2.5 border border-gray-300 dark:border-gray-700 rounded-xl text-xs bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-gray-300 mb-1">Singkatan / Kode Unit <span class="text-rose-500">*</span></label>
                    <input type="text" name="singkatan" x-model="form.singkatan" placeholder="Misal: ppm, umum, ekonomi, litbang, ppepd" required class="w-full px-3.5 py-2.5 border border-gray-300 dark:border-gray-700 rounded-xl text-xs bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none font-mono">
                    <p class="text-[11px] text-gray-400 mt-1">Singkatan ini digunakan sebagai referensi relasi unit kerja pada Data Jabatan & Pegawai (contoh: <code>ppm</code>, <code>umum</code>, <code>sekretariat</code>, dll).</p>
                </div>

                <div class="pt-3 flex items-center justify-end gap-2">
                    <button type="button" @click="modalOpen = false" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-slate-700 dark:text-gray-300 font-semibold rounded-xl text-xs transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs shadow-lg shadow-blue-500/30 transition-all uppercase tracking-wider">
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
