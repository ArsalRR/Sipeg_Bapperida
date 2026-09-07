@extends('layouts.admin')

@section('title', 'Manajemen User')

@section('content')
<div x-data="userCrud()" class="max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Daftar User</h2>
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
                Tambah User
            </button>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white dark:bg-[#111111] rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
        
        <!-- Table Controls (Search & Show Entries) -->
        <div class="p-4 border-b border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-500 dark:text-gray-400">Tampilkan</span>
                <select x-model="perPage" class="border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white px-3 py-1.5 focus:ring-2 focus:ring-blue-600 outline-none">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="100">100</option>
                </select>
                <span class="text-sm text-gray-500 dark:text-gray-400">baris</span>
            </div>
            
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" x-model="search" placeholder="Cari data..." class="w-full sm:w-64 pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
            </div>
        </div>

        <!-- The Table -->
        <div class="overflow-x-auto" id="printable-area">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-[#1a1a1a] border-b border-gray-100 dark:border-gray-800">
                        <th @click="sortBy('username')" class="cursor-pointer px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-blue-600 select-none">
                            <div class="flex items-center gap-1">Username <span x-html="sortIcon('username')"></span></div>
                        </th>
                        <th @click="sortBy('email')" class="cursor-pointer px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-blue-600 select-none">
                            <div class="flex items-center gap-1">Email <span x-html="sortIcon('email')"></span></div>
                        </th>
                        <th @click="sortBy('role')" class="cursor-pointer px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-blue-600 select-none">
                            <div class="flex items-center gap-1">Role <span x-html="sortIcon('role')"></span></div>
                        </th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400">Status</th>
                        <th @click="sortBy('pegawai_nama')" class="cursor-pointer px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-blue-600 select-none">
                            <div class="flex items-center gap-1">Nama Pegawai <span x-html="sortIcon('pegawai_nama')"></span></div>
                        </th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400 text-right print:hidden">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    <template x-for="user in paginatedUsers" :key="user.id">
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-[#18181b] transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-slate-900 dark:text-white" x-text="user.username"></td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400" x-text="user.email"></td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold"
                                    :class="{
                                        'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400': user.role === 'superadmin',
                                        'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400': user.role === 'admin',
                                        'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400': user.role === 'user'
                                    }" x-text="user.role.charAt(0).toUpperCase() + user.role.slice(1)">
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider"
                                    :class="user.is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400'">
                                    <span x-text="user.is_active ? 'Aktif' : 'Menunggu'"></span>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400" x-text="user.pegawai ? user.pegawai.nama : '-'"></td>
                            <td class="px-6 py-4 text-right space-x-1 print:hidden">
                                <template x-if="!user.is_active">
                                    <form :action="'{{ url('/admin/users') }}/' + user.id + '/activate'" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-2 py-1 text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white rounded transition-colors">
                                            Setujui
                                        </button>
                                    </form>
                                </template>
                                <button @click="openEditModal(user, user.pegawai)" class="text-blue-600 hover:text-blue-800 dark:text-blue-500 dark:hover:text-blue-400 p-1">
                                    Edit
                                </button>
                                <button @click="confirmDelete('{{ url('/admin/users') }}/' + user.id)" class="text-red-600 hover:text-red-800 dark:text-red-500 dark:hover:text-red-400 p-1">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    </template>
                    <template x-if="paginatedUsers.length === 0">
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">Tidak ada data yang ditemukan.</td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row justify-between items-center gap-4">
            <span class="text-sm text-gray-500 dark:text-gray-400">
                Menampilkan <span x-text="filteredUsers.length > 0 ? ((currentPage - 1) * perPage) + 1 : 0"></span> 
                sampai <span x-text="Math.min(currentPage * perPage, filteredUsers.length)"></span> 
                dari <span x-text="filteredUsers.length"></span> baris
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

    <!-- Modal Form -->
    <div x-show="modalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Overlay -->
            <div x-show="modalOpen" @click="modalOpen = false" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/50 dark:bg-black/80 backdrop-blur-sm" aria-hidden="true"></div>

            <div x-show="modalOpen" x-transition class="relative inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-[#111111] shadow-xl rounded-2xl border border-gray-100 dark:border-gray-800">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white" x-text="isEdit ? 'Edit User' : 'Tambah User'"></h3>
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
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Username</label>
                            <input type="text" name="username" x-model="form.username" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 focus:outline-none">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                            <input type="email" name="email" x-model="form.email" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password</label>
                            <input type="password" name="password" :required="!isEdit" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 focus:outline-none">
                            <p x-show="isEdit" class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah password.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Role</label>
                            <select name="role" x-model="form.role" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 focus:outline-none">
                                <option value="superadmin">Superadmin</option>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hubungkan Pegawai (Opsional)</label>
                            <select name="pegawai_id" x-model="form.pegawai_id" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 focus:outline-none">
                                <option value="">-- Tidak Dihubungkan --</option>
                                <template x-if="isEdit && currentPegawai">
                                    <option :value="currentPegawai.id" x-text="currentPegawai.nama + ' (Saat Ini)'"></option>
                                </template>
                                @foreach($pegawais as $pegawai)
                                    <option value="{{ $pegawai->id }}">{{ $pegawai->nama }} - {{ $pegawai->nip }}</option>
                                @endforeach
                            </select>
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

    <!-- Hidden Delete Form -->
    <form id="delete-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('userCrud', () => ({
        allUsers: @json($users),
        search: '',
        perPage: 5,
        currentPage: 1,
        sortCol: 'username',
        sortAsc: true,
        
        modalOpen: false,
        isEdit: false,
        formAction: '',
        currentPegawai: null,
        form: {
            username: '',
            email: '',
            role: 'user',
            pegawai_id: ''
        },

        init() {
            this.$watch('search', () => { this.currentPage = 1; });
            this.$watch('perPage', () => { this.currentPage = 1; });
        },

        get filteredUsers() {
            let result = this.allUsers;
            
            // Search filter
            if (this.search) {
                const q = this.search.toLowerCase();
                result = result.filter(u => 
                    (u.username && u.username.toLowerCase().includes(q)) || 
                    (u.email && u.email.toLowerCase().includes(q)) || 
                    (u.role && u.role.toLowerCase().includes(q)) || 
                    (u.pegawai && u.pegawai.nama && u.pegawai.nama.toLowerCase().includes(q))
                );
            }
            
            // Sorting
            result = result.sort((a, b) => {
                let valA = a[this.sortCol] || '';
                let valB = b[this.sortCol] || '';
                
                if (this.sortCol === 'pegawai_nama') {
                    valA = a.pegawai ? a.pegawai.nama : '';
                    valB = b.pegawai ? b.pegawai.nama : '';
                }
                
                if (typeof valA === 'string') valA = valA.toLowerCase();
                if (typeof valB === 'string') valB = valB.toLowerCase();
                
                if (valA < valB) return this.sortAsc ? -1 : 1;
                if (valA > valB) return this.sortAsc ? 1 : -1;
                return 0;
            });
            
            return result;
        },
        
        get paginatedUsers() {
            const start = (this.currentPage - 1) * this.perPage;
            return this.filteredUsers.slice(start, start + parseInt(this.perPage));
        },
        
        get totalPages() {
            return Math.ceil(this.filteredUsers.length / this.perPage);
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
            // Header
            csvContent += "Username,Email,Role,Nama Pegawai\n";
            // Rows
            this.filteredUsers.forEach(function(rowArray) {
                let pegawai = rowArray.pegawai ? rowArray.pegawai.nama : '-';
                let row = `"${rowArray.username}","${rowArray.email}","${rowArray.role}","${pegawai}"`;
                csvContent += row + "\n";
            });
            
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "Data_User_SIMPEG.csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        },

        printPdf() {
            const printContent = document.getElementById('printable-area').innerHTML;
            const originalContent = document.body.innerHTML;
            
            // Simpan state CSS untuk print
            const style = document.createElement('style');
            style.innerHTML = `
                @media print {
                    body { visibility: hidden; }
                    #print-section { visibility: visible; position: absolute; left: 0; top: 0; width: 100%; }
                    .print\\:hidden { display: none !important; }
                }
            `;
            document.head.appendChild(style);

            const printSection = document.createElement('div');
            printSection.id = 'print-section';
            printSection.innerHTML = '<h2 style="font-size:24px; font-weight:bold; margin-bottom: 20px;">Laporan Data User SIMPEG</h2>' + printContent;
            document.body.appendChild(printSection);

            window.print();

            document.body.removeChild(printSection);
            document.head.removeChild(style);
        },

        openCreateModal() {
            this.isEdit = false;
            this.formAction = '{{ route("admin.users.store") }}';
            this.form = { username: '', email: '', role: 'user', pegawai_id: '' };
            this.currentPegawai = null;
            this.modalOpen = true;
        },
        openEditModal(user, pegawai) {
            this.isEdit = true;
            this.formAction = `/admin/users/${user.id}`;
            this.form = { 
                username: user.username, 
                email: user.email, 
                role: user.role, 
                pegawai_id: pegawai ? pegawai.id : '' 
            };
            this.currentPegawai = pegawai;
            this.modalOpen = true;
        },
        confirmDelete(url) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data Pegawai terkait juga akan ikut dihapus permanen!",
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
