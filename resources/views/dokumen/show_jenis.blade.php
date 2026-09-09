@extends('layouts.admin')

@section('title', 'Dokumen - ' . $jenisDokumen->nama_jenis)

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 p-4 rounded-xl flex items-center justify-between text-sm">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error') || $errors->any())
        <div class="bg-rose-50 dark:bg-rose-900/30 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 p-4 rounded-xl text-sm space-y-1">
            <div class="font-semibold">Terjadi Kesalahan:</div>
            <ul class="list-disc list-inside">
                @if(session('error'))
                    <li>{{ session('error') }}</li>
                @endif
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('dokumen.file.index') }}" class="w-10 h-10 bg-white dark:bg-[#111111] border border-gray-200 dark:border-gray-800 rounded-xl flex items-center justify-center text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 transition-colors shrink-0 shadow-sm" title="Kembali ke Kategori Dokumen">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-bold text-[10px] rounded-md uppercase tracking-wider">Kategori</span>
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white">{{ $jenisDokumen->nama_jenis }}</h2>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $jenisDokumen->deskripsi ?? 'Daftar dokumen kepegawaian yang diunggah.' }}</p>
            </div>
        </div>

        <button onclick="openModalUpload()" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl shadow-md shadow-blue-500/20 transition-all flex items-center justify-center gap-2 shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
            Upload {{ $jenisDokumen->nama_jenis }} Baru
        </button>
    </div>
    <div class="bg-white dark:bg-[#111111] rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
        <div class="p-5 md:p-6 bg-gray-50/50 dark:bg-slate-900/30 border-b border-gray-100 dark:border-gray-800">
            <form method="GET" action="{{ route('dokumen.file.showJenis', $jenisDokumen->id) }}" class="flex flex-col md:flex-row items-center gap-3">
                
                @if(in_array(auth()->user()->role, ['admin', 'superadmin']))
                <div class="w-full md:w-64">
                    <select name="pegawai_id" onchange="this.form.submit()" class="w-full px-3.5 py-2.5 bg-white dark:bg-[#18181b] border border-gray-200 dark:border-gray-700 rounded-xl text-xs font-medium text-slate-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-600">
                        <option value="">-- Semua Pegawai --</option>
                        @foreach($pegawais as $p)
                            <option value="{{ $p->id }}" {{ request('pegawai_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="w-full md:w-40">
                    <select name="tahun" onchange="this.form.submit()" class="w-full px-3.5 py-2.5 bg-white dark:bg-[#18181b] border border-gray-200 dark:border-gray-700 rounded-xl text-xs font-medium text-slate-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-600">
                        <option value="semua">Semua Tahun</option>
                        @php
                            $currentYear = date('Y');
                            $years = range($currentYear, $currentYear - 10);
                        @endphp
                        @foreach($years as $y)
                            <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full md:flex-1 relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari keterangan dokumen..." class="w-full pl-3.5 pr-10 py-2.5 bg-white dark:bg-[#18181b] border border-gray-200 dark:border-gray-700 rounded-xl text-xs text-slate-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-600">
                    <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-blue-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                </div>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-slate-900/50 text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                        <th class="py-4 px-6">Dokumen & Keterangan</th>
                        @if(in_array(auth()->user()->role, ['admin', 'superadmin']))
                            <th class="py-4 px-6">Pegawai</th>
                        @endif
                        <th class="py-4 px-6 text-center">Tahun</th>
                        <th class="py-4 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-xs font-medium">
                    @forelse($dokumens as $dok)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-900/30 transition-colors">
                        <td class="py-4 px-6 font-semibold text-slate-900 dark:text-white">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-lg flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 dark:text-white text-xs">{{ $dok->jenis_dokumen }}</div>
                                    <div class="text-[11px] text-gray-500 dark:text-gray-400 font-normal mt-0.5">
                                        {{ $dok->keterangan ?? 'Tidak ada keterangan' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        @if(in_array(auth()->user()->role, ['admin', 'superadmin']))
                            <td class="py-4 px-6 text-gray-600 dark:text-gray-400 font-medium">
                                {{ $dok->pegawai->nama ?? '-' }}
                            </td>
                        @endif
                        <td class="py-4 px-6 text-center text-gray-600 dark:text-gray-400 font-bold">
                            {{ $dok->tahun }}
                        </td>
                        <td class="py-4 px-6 text-center">
                            <div class="flex items-center justify-center gap-2">
                                @if($dok->file_path)
                                    <a href="{{ route('dokumen.file.download', $dok->id) }}" target="_blank" class="px-3.5 py-1.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-lg text-[11px] font-bold hover:bg-blue-100 transition-colors flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        Buka File
                                    </a>
                                @endif

                                <form action="{{ route('dokumen.file.destroy', $dok->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus dokumen ini?')" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/30 rounded-lg transition-colors" title="Hapus Dokumen">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-12 text-center text-gray-400 dark:text-gray-500">
                            Belum ada file dokumen {{ $jenisDokumen->nama_jenis }} yang diunggah.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($dokumens->hasPages())
            <div class="p-4 border-t border-gray-100 dark:border-gray-800">
                {{ $dokumens->withQueryString()->links() }}
            </div>
        @endif

    </div>
</div>

@push('modals')
<div id="modalUpload" class="fixed inset-0 z-[9999] hidden bg-slate-900/60 backdrop-blur-sm overflow-y-auto p-4 sm:p-6 md:p-10 flex items-center justify-center min-h-screen">
    <div class="bg-white dark:bg-[#18181b] rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden border border-gray-100 dark:border-gray-800 transform transition-all my-auto">
        <div class="bg-blue-600 px-6 py-4 flex justify-between items-center text-white">
            <h3 class="font-bold text-base">Upload Dokumen - {{ $jenisDokumen->nama_jenis }}</h3>
            <button onclick="closeModalUpload()" class="text-white/80 hover:text-white text-xl font-bold">&times;</button>
        </div>
        <form action="{{ route('dokumen.file.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="jenis_dokumen" value="{{ $jenisDokumen->nama_jenis }}">

            @if(in_array(auth()->user()->role, ['admin', 'superadmin']))
            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-gray-300 mb-1">Pegawai <span class="text-rose-500">*</span></label>
                <select name="pegawai_id" required class="w-full px-3.5 py-2.5 border border-gray-300 dark:border-gray-700 rounded-xl text-xs bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                    <option value="">-- Pilih Pegawai --</option>
                    @foreach($pegawais as $p)
                        <option value="{{ $p->id }}">{{ $p->nama }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-gray-300 mb-1">Keterangan / Uraian Dokumen</label>
                <input type="text" name="keterangan" placeholder="Misal: SK Kenaikan Pangkat Golongan IV/a" class="w-full px-3.5 py-2.5 border border-gray-300 dark:border-gray-700 rounded-xl text-xs bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-gray-300 mb-1">Tahun <span class="text-rose-500">*</span></label>
                <input type="number" name="tahun" value="{{ date('Y') }}" required class="w-full px-3.5 py-2.5 border border-gray-300 dark:border-gray-700 rounded-xl text-xs bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
            </div>
            <div class="space-y-1.5 pt-1">
                <label class="block text-xs font-semibold text-slate-700 dark:text-gray-300">File Dokumen (PDF) <span class="text-rose-500">*</span></label>
                <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-3.5 bg-gray-50/50 dark:bg-slate-900/50">
                    <input type="file" name="file_upload" accept=".pdf,application/pdf" required class="block w-full text-xs text-slate-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900/40 dark:file:text-blue-400">
                </div>
                <p class="text-[11px] text-gray-500 dark:text-gray-400 italic">
                    *Format file wajib <strong>PDF</strong>. Maksimal ukuran file <strong>5 MB</strong>.
                </p>
            </div>
            <div class="pt-3">
                <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-blue-500/30 transition-all uppercase tracking-wider">
                    SIMPAN DOKUMEN
                </button>
            </div>
        </form>

    </div>
</div>
@endpush

<script>
    function openModalUpload() {
        document.getElementById('modalUpload').classList.remove('hidden');
    }
    function closeModalUpload() {
        document.getElementById('modalUpload').classList.add('hidden');
    }
</script>
@endsection
