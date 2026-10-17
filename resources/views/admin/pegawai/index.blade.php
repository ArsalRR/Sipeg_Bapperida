@extends('layouts.admin')

@section('title', 'Manajemen Pegawai')

@section('content')
<div x-data="pegawaiCrud()">
    <div class="max-w-7xl mx-auto space-y-6">

        @include('admin.pegawai.partials._header')

        <div class="bg-white dark:bg-[#111111] rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
            @include('admin.pegawai.partials._toolbar')
            @include('admin.pegawai.partials._table')
            @include('admin.pegawai.partials._cards')
            @include('admin.pegawai.partials._pagination')
        </div>
    </div>

    @include('admin.pegawai.partials._modal')

    {{-- Form hapus (disubmit dari confirmDelete) --}}
    <form id="delete-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
</div>

{{-- Data & URL dari server. Logika ada di public/js/admin/pegawai-crud.js --}}
<script>
window.PEGAWAI_CONFIG = {
    pegawais:     @json($pegawais),
    jabatans:     @json($jabatans),
    bidangs:      @json($bidangs),
    users:        @json($users),
    storageBase:  '/storage/',
    baseUrl:      @json(url('/admin/pegawais')),
    exportPdfUrl: @json(route('admin.pegawais.export-pdf')),
    storeUrl:     @json(route('admin.pegawais.store')),
};
</script>
<script src="{{ asset('js/pegawai.js') }}?v={{ filemtime(public_path('js/pegawai.js')) }}"></script>
@if($errors->any())
<script>
window.addEventListener('load', () => {
    const errs = @json($errors->all());
    const esc = (v) => String(v).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    Swal.fire({
        icon: 'error',
        title: 'Data belum valid',
        html: '<ul style="text-align:left;margin-left:1rem;list-style:disc">' + errs.map(m => '<li>' + esc(m) + '</li>').join('') + '</ul>',
        confirmButtonColor: '#3085d6'
    });
});
</script>
@endif
@endsection
