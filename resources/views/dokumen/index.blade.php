@extends('layouts.admin')

@section('title', 'Repositori Dokumen')

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
    <div class="space-y-3">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Kategori Dokumen</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400">Klik salah satu jenis dokumen di bawah untuk langsung mengunggah file.</p>
            </div>
            @if(in_array(auth()->user()->role, ['admin', 'superadmin']))
                <button onclick="openModalTambahJenis()" class="text-xs font-semibold text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                     Tambah Master Jenis
                </button>
            @endif
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            @foreach($jenisDokumens as $jd)
                @php
                    $count = $dokumenCounts[$jd->nama_jenis] ?? 0;
                @endphp
                <div class="bg-white dark:bg-[#111111] p-4 rounded-2xl border border-gray-100 dark:border-gray-800 hover:border-blue-500 dark:hover:border-blue-500 hover:shadow-md transition-all group relative overflow-hidden flex flex-col justify-between min-h-[120px]">
                    <div class="flex items-start justify-between gap-2">
                        <a href="{{ route('dokumen.file.showJenis', $jd->id) }}" class="w-9 h-9 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </a>
                        
                        <div class="flex items-center gap-1.5">
                            <span class="text-[11px] font-bold text-gray-400 dark:text-gray-500 group-hover:text-blue-600 transition-colors mr-1">
                                {{ $count }} File
                            </span>
                            @if(in_array(auth()->user()->role, ['admin', 'superadmin']))
                                <button type="button" onclick="openModalEditJenis('{{ $jd->id }}', '{{ addslashes($jd->nama_jenis) }}', '{{ addslashes($jd->deskripsi ?? '') }}')" class="text-gray-400 hover:text-amber-500 transition-colors p-1" title="Edit Jenis Dokumen">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                <form action="{{ route('dokumen.file.destroyJenis', $jd->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus jenis dokumen ini?')" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-rose-500 transition-colors p-1" title="Hapus Jenis Dokumen">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <a href="{{ route('dokumen.file.showJenis', $jd->id) }}" class="mt-3">
                        <h4 class="font-bold text-xs text-slate-900 dark:text-white group-hover:text-blue-600 transition-colors line-clamp-1">
                            {{ $jd->nama_jenis }}
                        </h4>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 line-clamp-1 mt-0.5">
                            {{ $jd->deskripsi ?? 'Dokumen Kepegawaian' }}
                        </p>
                    </a>

                    <a href="{{ route('dokumen.file.showJenis', $jd->id) }}" class="mt-2 text-[10px] font-semibold text-blue-600 dark:text-blue-400 opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-1">
                        <span>Lihat & Upload File &rarr;</span>
                    </a>
                </div>
            @endforeach
        </div>

    </div>


</div>

@push('modals')
<div id="modalUpload" class="fixed inset-0 z-[9999] hidden bg-slate-900/60 backdrop-blur-sm overflow-y-auto p-4 sm:p-6 md:p-10 flex items-center justify-center min-h-screen">
    <div class="bg-white dark:bg-[#18181b] rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden border border-gray-100 dark:border-gray-800 transform transition-all my-auto">
        
        <div class="bg-blue-600 px-6 py-4 flex justify-between items-center text-white">
            <h3 class="font-bold text-base">Upload Dokumen Baru</h3>
            <button onclick="closeModalUpload()" class="text-white/80 hover:text-white text-xl font-bold">&times;</button>
        </div>
        <form action="{{ route('dokumen.file.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf

            @if(in_array(auth()->user()->role, ['admin', 'superadmin']))
            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-gray-300 mb-1">Pegawai <span class="text-rose-500">*</span></label>
                <select name="pegawai_id" required class="w-full px-3.5 py-2 border border-gray-300 dark:border-gray-700 rounded-xl text-xs bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                    <option value="">-- Pilih Pegawai --</option>
                    @foreach($pegawais as $p)
                        <option value="{{ $p->id }}">{{ $p->nama }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-gray-300 mb-1">Jenis Dokumen</label>
                <div class="px-4 py-2.5 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-xl flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    <span id="displayNamaJenis" class="text-xs font-bold text-blue-700 dark:text-blue-300">
                        Dokumen Kepegawaian
                    </span>
                </div>
                <input type="hidden" id="inputJenisDokumen" name="jenis_dokumen" value="" required>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-gray-300 mb-1">Keterangan / Uraian Dokumen</label>
                <input type="text" name="keterangan" placeholder="Misal: SK Kenaikan Pangkat Golongan IV/a, Ijazah S1, dll." class="w-full px-3.5 py-2.5 border border-gray-300 dark:border-gray-700 rounded-xl text-xs bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
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
                    SIMPAN
                </button>
            </div>
        </form>

    </div>
</div>

@if(in_array(auth()->user()->role, ['admin', 'superadmin']))
<div id="modalTambahJenis" class="fixed inset-0 z-[9999] hidden bg-slate-900/60 backdrop-blur-sm overflow-y-auto p-4 flex items-center justify-center min-h-screen">
    <div class="bg-white dark:bg-[#18181b] rounded-2xl shadow-2xl w-full max-w-md overflow-hidden border border-gray-100 dark:border-gray-800 my-auto">
        <div class="bg-blue-600 px-6 py-4 flex justify-between items-center text-white">
            <h3 class="font-bold text-sm">Tambah Master Jenis Dokumen</h3>
            <button onclick="closeModalTambahJenis()" class="text-white/80 hover:text-white text-xl font-bold">&times;</button>
        </div>
        <form action="{{ route('dokumen.file.storeJenis') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-gray-300 mb-1">Nama Jenis Dokumen <span class="text-rose-500">*</span></label>
                <input type="text" name="nama_jenis" placeholder="Misal: SK Izin Belajar, SK Cukai, dll." required class="w-full px-3.5 py-2.5 border border-gray-300 dark:border-gray-700 rounded-xl text-xs bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-gray-300 mb-1">Deskripsi Singkat</label>
                <input type="text" name="deskripsi" placeholder="Keterangan singkat jenis dokumen" class="w-full px-3.5 py-2.5 border border-gray-300 dark:border-gray-700 rounded-xl text-xs bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
            </div>
            <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-lg shadow-blue-500/30 transition-all uppercase tracking-wider">
                SIMPAN KATEGORI
            </button>
        </form>
    </div>
</div>

<div id="modalEditJenis" class="fixed inset-0 z-[9999] hidden bg-slate-900/60 backdrop-blur-sm overflow-y-auto p-4 flex items-center justify-center min-h-screen">
    <div class="bg-white dark:bg-[#18181b] rounded-2xl shadow-2xl w-full max-w-md overflow-hidden border border-gray-100 dark:border-gray-800 my-auto">
        <div class="bg-blue-600 px-6 py-4 flex justify-between items-center text-white">
            <h3 class="font-bold text-sm">Edit Master Jenis Dokumen</h3>
            <button onclick="closeModalEditJenis()" class="text-white/80 hover:text-white text-xl font-bold">&times;</button>
        </div>
        <form id="formEditJenis" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-gray-300 mb-1">Nama Jenis Dokumen <span class="text-rose-500">*</span></label>
                <input type="text" id="edit_nama_jenis" name="nama_jenis" required class="w-full px-3.5 py-2.5 border border-gray-300 dark:border-gray-700 rounded-xl text-xs bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-gray-300 mb-1">Deskripsi Singkat</label>
                <input type="text" id="edit_deskripsi" name="deskripsi" class="w-full px-3.5 py-2.5 border border-gray-300 dark:border-gray-700 rounded-xl text-xs bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
            </div>
            <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-lg shadow-blue-500/30 transition-all uppercase tracking-wider">
                PERBARUI KATEGORI
            </button>
        </form>
    </div>
</div>
@endif

<script>
    function openModalUpload() {
        // Default ke jenis dokumen pertama jika tidak dipassing dari card
        const defaultJenis = '{{ count($jenisDokumens) > 0 ? addslashes($jenisDokumens[0]->nama_jenis) : "Dokumen Kepegawaian" }}';
        openModalUploadWithJenis(defaultJenis);
    }

    function openModalUploadWithJenis(namaJenis) {
        const displaySpan = document.getElementById('displayNamaJenis');
        const hiddenInput = document.getElementById('inputJenisDokumen');
        
        if (displaySpan) displaySpan.textContent = namaJenis;
        if (hiddenInput) hiddenInput.value = namaJenis;

        document.getElementById('modalUpload').classList.remove('hidden');
    }


    function closeModalUpload() {
        document.getElementById('modalUpload').classList.add('hidden');
    }

    function openModalTambahJenis() {
        document.getElementById('modalTambahJenis').classList.remove('hidden');
    }

    function closeModalTambahJenis() {
        document.getElementById('modalTambahJenis').classList.add('hidden');
    }

    function openModalEditJenis(id, namaJenis, deskripsi) {
        const form = document.getElementById('formEditJenis');
        if (form) {
            form.action = '{{ url("/menu-file/jenis") }}/' + id;
            document.getElementById('edit_nama_jenis').value = namaJenis;
            document.getElementById('edit_deskripsi').value = deskripsi;
            document.getElementById('modalEditJenis').classList.remove('hidden');
        }
    }

    function closeModalEditJenis() {
        document.getElementById('modalEditJenis').classList.add('hidden');
    }
</script>
@endpush
@endsection

