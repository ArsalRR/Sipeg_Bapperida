@extends('layouts.admin')

@section('title', 'Manajemen Jabatan')

@section('content')
<div x-data="jabatanCrud()" class="max-w-7xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Daftar Jabatan</h2>
        <button @click="openCreateModal()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-sm transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Tambah Jabatan
        </button>
    </div>
    <div class="bg-white dark:bg-[#111111] rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">

        <div class="p-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center gap-4">
            <div class="relative w-full sm:w-64">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" x-model="search" placeholder="Cari nama jabatan..." class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-[#1a1a1a] border-b border-gray-100 dark:border-gray-800">
                        <th class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400">Nama Jabatan</th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400">Jenis Jabatan</th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400 text-center">Jumlah Maks</th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400 text-center">Terisi</th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    <template x-for="j in filteredJabatan" :key="j.id">
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-[#18181b] transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-slate-900 dark:text-white" x-text="j.nama_jabatan"></td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400" x-text="j.jenis_jabatan"></td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 text-center" x-text="j.jumlah !== null ? j.jumlah : '∞'"></td>
                            <td class="px-6 py-4 text-sm text-center">
                                <span :class="j.jumlah !== null && j.pegawais_count >= j.jumlah ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'" class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" x-text="j.pegawais_count + (j.jumlah !== null ? '/' + j.jumlah : '')"></span>
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
    </div>
    <div x-show="modalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="modalOpen" @click="modalOpen = false" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/50 dark:bg-black/80 backdrop-blur-sm" aria-hidden="true"></div>

            <div x-show="modalOpen" x-transition class="relative inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-[#111111] shadow-xl rounded-2xl border border-gray-100 dark:border-gray-800">
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

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Jabatan</label>
                            <input type="text" name="nama_jabatan" x-model="form.nama_jabatan" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jenis Jabatan</label>
                            <select name="jenis_jabatan" x-model="form.jenis_jabatan" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                                <option value="Struktural">Struktural</option>
                                <option value="Fungsional">Fungsional</option>
                                <option value="Pelaksana">Pelaksana</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jumlah Maksimum Pegawai</label>
                            <input type="number" name="jumlah" x-model="form.jumlah" min="1" placeholder="Kosongkan jika tidak dibatasi" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Kosongkan bila tidak ada batas maksimal.</p>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" @click="modalOpen = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-[#111111] dark:text-gray-300 dark:border-gray-700 dark:hover:bg-slate-800">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
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
        isEdit: false,
        formAction: '',
        form: {
            nama_jabatan: '',
            jenis_jabatan: 'Struktural',
            jumlah: ''
        },

        get filteredJabatan() {
            if (!this.search) return this.allJabatan;
            const q = this.search.toLowerCase();
            return this.allJabatan.filter(j => j.nama_jabatan.toLowerCase().includes(q));
        },

        openCreateModal() {
            this.isEdit = false;
            this.formAction = '{{ route("admin.jabatans.store") }}';
            this.form = { nama_jabatan: '', jenis_jabatan: 'Struktural', jumlah: '' };
            this.modalOpen = true;
        },
        openEditModal(j) {
            this.isEdit = true;
            this.formAction = `/admin/jabatans/${j.id}`;
            this.form = { nama_jabatan: j.nama_jabatan, jenis_jabatan: j.jenis_jabatan, jumlah: j.jumlah || '' };
            this.modalOpen = true;
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
