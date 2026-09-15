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
    <div class="bg-white dark:bg-[#111111] rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
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
        <div class="hidden sm:block overflow-x-auto" id="printable-area">
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
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold whitespace-nowrap"
                                    :class="{
                                        'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400': user.role === 'superadmin',
                                        'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400': user.role === 'admin',
                                        'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400': user.role === 'user'
                                    }" x-text="user.role.charAt(0).toUpperCase() + user.role.slice(1)">
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider whitespace-nowrap"
                                    :class="user.is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400'">
                                    <span x-text="user.is_active ? 'Aktif' : 'Menunggu'"></span>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400" x-text="user.pegawai ? user.pegawai.nama : '-'"></td>
                            <td class="px-6 py-4 text-right print:hidden">
                                <div class="flex items-center justify-end gap-1">
                                    <template x-if="!user.is_active">
                                        <div class="relative group">
                                            <button type="button" @click="confirmActivate('{{ url('/admin/users') }}/' + user.id + '/activate', user.username)" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-emerald-600 hover:text-emerald-800 hover:bg-emerald-50 dark:text-emerald-400 dark:hover:text-emerald-300 dark:hover:bg-emerald-900/30 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            </button>
                                            <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1.5 px-2 py-0.5 text-[10px] font-medium bg-gray-800 text-white rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">Setujui</span>
                                        </div>
                                    </template>
                                    <div class="relative group">
                                        <button @click="openEditModal(user, user.pegawai)" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-blue-600 hover:text-blue-800 hover:bg-blue-50 dark:text-blue-400 dark:hover:text-blue-300 dark:hover:bg-blue-900/30 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1.5 px-2 py-0.5 text-[10px] font-medium bg-gray-800 text-white rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">Edit</span>
                                    </div>
                                    <div class="relative group">
                                        <button @click="confirmDelete('{{ url('/admin/users') }}/' + user.id)" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-red-500 hover:text-red-700 hover:bg-red-50 dark:text-red-400 dark:hover:text-red-300 dark:hover:bg-red-900/30 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                        <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1.5 px-2 py-0.5 text-[10px] font-medium bg-gray-800 text-white rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">Hapus</span>
                                    </div>
                                </div>
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

        {{-- LAYAR MOBILE: CARD ACCORDION LIST UNTUK MANAJEMEN USER --}}
        <div class="block sm:hidden p-3 space-y-3">
            <template x-for="user in paginatedUsers" :key="user.id">
                <div class="bg-white dark:bg-[#18181b] border border-gray-200 dark:border-gray-800 rounded-2xl overflow-hidden transition-all shadow-sm">
                    
                    {{-- Card Header Mobile --}}
                    <button @click="expandedId = (expandedId === user.id ? null : user.id)" type="button" class="w-full p-4 flex items-center justify-between text-left hover:bg-gray-50 dark:hover:bg-slate-800/40 transition-colors">
                        <div class="flex items-center gap-3 min-w-0">
                            {{-- User Avatar / Initial --}}
                            <div class="shrink-0 w-11 h-11 rounded-full bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 font-bold flex items-center justify-center text-sm border border-blue-200 dark:border-blue-800/50">
                                <span x-text="user.username.charAt(0).toUpperCase()"></span>
                            </div>

                            {{-- Username & Badges --}}
                            <div class="min-w-0 flex-1">
                                <h3 class="text-sm font-bold text-slate-900 dark:text-white truncate leading-tight" x-text="user.username"></h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 truncate" x-text="user.email"></p>
                                <div class="flex items-center gap-1.5 mt-1.5 flex-wrap">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold whitespace-nowrap"
                                        :class="{
                                            'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400': user.role === 'superadmin',
                                            'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400': user.role === 'admin',
                                            'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400': user.role === 'user'
                                        }" x-text="user.role.charAt(0).toUpperCase() + user.role.slice(1)">
                                    </span>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider whitespace-nowrap"
                                        :class="user.is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400'">
                                        <span x-text="user.is_active ? 'Aktif' : 'Menunggu'"></span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Chevron Icon --}}
                        <div class="ml-2 shrink-0 p-1 text-gray-400">
                            <svg class="w-5 h-5 transition-transform duration-200" :class="expandedId === user.id ? 'rotate-180 text-blue-600 dark:text-blue-400' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>

                    {{-- Card Expanded Detail Body --}}
                    <div x-show="expandedId === user.id" x-collapse class="px-4 pb-4 pt-2 border-t border-gray-100 dark:border-gray-800/80 space-y-3">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs pt-1">
                            <div class="bg-gray-50 dark:bg-[#111111] p-2.5 rounded-xl border border-gray-100 dark:border-gray-800">
                                <span class="text-gray-400 dark:text-gray-500 block text-[10px] uppercase font-semibold">Email</span>
                                <span class="font-medium text-slate-800 dark:text-slate-200" x-text="user.email || '-'"></span>
                            </div>
                            <div class="bg-gray-50 dark:bg-[#111111] p-2.5 rounded-xl border border-gray-100 dark:border-gray-800">
                                <span class="text-gray-400 dark:text-gray-500 block text-[10px] uppercase font-semibold">Pegawai Terhubung</span>
                                <span class="font-medium text-slate-800 dark:text-slate-200" x-text="user.pegawai ? user.pegawai.nama : 'Tidak dihubungkan'"></span>
                            </div>
                        </div>

                        {{-- Tombol Aksi Mobile --}}
                        <div class="flex items-center gap-2 pt-2">
                            <template x-if="!user.is_active">
                                <button type="button" @click="confirmActivate('{{ url('/admin/users') }}/' + user.id + '/activate', user.username)" class="flex-1 py-2.5 px-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold flex items-center justify-center gap-1 transition-colors shadow-sm whitespace-nowrap">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    <span>Setujui</span>
                                </button>
                            </template>
                            <button @click="openEditModal(user, user.pegawai)" type="button" class="flex-1 py-2.5 px-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold flex items-center justify-center gap-1 transition-colors shadow-sm whitespace-nowrap">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                <span>Edit</span>
                            </button>
                            <button @click="confirmDelete('{{ url('/admin/users') }}/' + user.id)" type="button" class="flex-1 py-2.5 px-2 bg-red-50 hover:bg-red-100 dark:bg-rose-900/20 dark:hover:bg-rose-900/30 text-red-600 dark:text-rose-400 border border-red-200 dark:border-rose-900/40 rounded-xl text-xs font-semibold flex items-center justify-center gap-1 transition-colors whitespace-nowrap">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                <span>Hapus</span>
                            </button>
                        </div>
                    </div>
                </div>
            </template>

            <template x-if="paginatedUsers.length === 0">
                <div class="p-6 text-center text-gray-500 dark:text-gray-400 text-sm">Tidak ada data yang ditemukan.</div>
            </template>
        </div>
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

    <div x-show="modalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="modalOpen" @click="modalOpen = false" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/50 dark:bg-black/80 backdrop-blur-sm" aria-hidden="true"></div>

            <div x-show="modalOpen" x-transition class="relative inline-block w-full max-w-lg p-6 my-8 text-left align-middle transition-all transform bg-white dark:bg-[#111111] shadow-xl rounded-2xl border border-gray-100 dark:border-gray-800">
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
                            <input type="password" name="password" x-model="form.password" autocomplete="new-password" :required="!isEdit" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 focus:outline-none">
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

                        <div x-data="{ openPegawaiDropdown: false, pegawaiSearch: '' }">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hubungkan Pegawai (Opsional)</label>
                            <input type="hidden" name="pegawai_id" :value="form.pegawai_id">
                            
                            <div class="relative" @click.outside="openPegawaiDropdown = false">
                                {{-- Trigger Button --}}
                                <button type="button" @click="openPegawaiDropdown = !openPegawaiDropdown; pegawaiSearch = ''"
                                    class="w-full px-4 py-2 text-left border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none flex items-center justify-between">
                                    <span x-text="getSelectedPegawaiLabel()"></span>
                                    <svg class="w-4 h-4 text-gray-400 shrink-0 transition-transform" :class="openPegawaiDropdown ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>

                                {{-- Dropdown Menu --}}
                                <div x-show="openPegawaiDropdown" x-transition x-cloak
                                    class="absolute left-0 right-0 mt-1.5 bg-white dark:bg-[#18181b] border border-gray-200 dark:border-gray-700 rounded-xl shadow-xl z-50 p-2 space-y-1 max-h-60 flex flex-col">
                                    
                                    {{-- Search Box --}}
                                    <div class="relative shrink-0 p-1">
                                        <input type="text" x-model="pegawaiSearch" placeholder="Cari nama pegawai atau NIP..."
                                            class="w-full pl-8 pr-3 py-1.5 text-xs border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                                        <svg class="w-3.5 h-3.5 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    </div>

                                    {{-- Options List --}}
                                    <div class="overflow-y-auto flex-1 divide-y divide-gray-50 dark:divide-gray-800">
                                        {{-- Option: Tidak dihubungkan --}}
                                        <button type="button" @click="form.pegawai_id = ''; openPegawaiDropdown = false"
                                            class="w-full text-left px-3 py-2 text-xs rounded-md hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors flex items-center justify-between"
                                            :class="form.pegawai_id === '' ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-semibold' : 'text-gray-700 dark:text-gray-300'">
                                            <span>-- Tidak Dihubungkan --</span>
                                            <svg x-show="form.pegawai_id === ''" class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </button>

                                        {{-- Option: saat ini --}}
                                        <template x-if="isEdit && currentPegawai && currentPegawai.id && (pegawaiSearch === '' || currentPegawai.nama.toLowerCase().includes(pegawaiSearch.toLowerCase()) || (currentPegawai.nip && currentPegawai.nip.toLowerCase().includes(pegawaiSearch.toLowerCase())))">
                                            <button type="button" @click="form.pegawai_id = currentPegawai.id; openPegawaiDropdown = false"
                                                class="w-full text-left px-3 py-2 text-xs rounded-md hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors flex items-center justify-between"
                                                :class="form.pegawai_id == currentPegawai.id ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-semibold' : 'text-gray-700 dark:text-gray-300'">
                                                <span x-text="currentPegawai.nama + ' (NIP: ' + (currentPegawai.nip || 'Belum diisi') + ') — [Saat Ini]'"></span>
                                                <svg x-show="form.pegawai_id == currentPegawai.id" class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            </button>
                                        </template>

                                        {{-- List available pegawais A-Z --}}
                                        <template x-for="p in getFilteredSortedPegawais(pegawaiSearch)" :key="p.id">
                                            <button type="button" @click="form.pegawai_id = p.id; openPegawaiDropdown = false"
                                                class="w-full text-left px-3 py-2 text-xs rounded-md hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors flex items-center justify-between"
                                                :class="form.pegawai_id == p.id ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-semibold' : 'text-gray-700 dark:text-gray-300'">
                                                <span x-text="p.nama + ' (NIP: ' + (p.nip || 'Belum diisi') + ')'"></span>
                                                <svg x-show="form.pegawai_id == p.id" class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            </button>
                                        </template>
                                    </div>
                                </div>
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
    <form id="activate-form" method="POST" style="display: none;">
        @csrf
        @method('PATCH')
    </form>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('userCrud', () => ({
        allUsers: @json($users),
        allPegawais: @json($pegawais),
        search: '',
        perPage: 5,
        currentPage: 1,
        sortCol: 'username',
        sortAsc: true,
        expandedId: null,

        getSelectedPegawaiLabel() {
            if (!this.form.pegawai_id) return '-- Tidak Dihubungkan --';
            if (this.isEdit && this.currentPegawai && this.form.pegawai_id == this.currentPegawai.id) {
                return this.currentPegawai.nama + ' (NIP: ' + (this.currentPegawai.nip || 'Belum diisi') + ') — [Saat Ini]';
            }
            const found = this.allPegawais.find(p => p.id == this.form.pegawai_id);
            return found ? (found.nama + ' (NIP: ' + (found.nip || 'Belum diisi') + ')') : '-- Tidak Dihubungkan --';
        },

        getFilteredSortedPegawais(query) {
            let list = [...this.allPegawais];
            if (query && query.trim() !== '') {
                const q = query.toLowerCase();
                list = list.filter(p =>
                    (p.nama && p.nama.toLowerCase().includes(q)) ||
                    (p.nip && p.nip.toLowerCase().includes(q))
                );
            }
            return list.sort((a, b) => (a.nama || '').localeCompare(b.nama || '', undefined, { sensitivity: 'base' }));
        },
        
        modalOpen: false,
        isEdit: false,
        formAction: '',
        currentPegawai: null,
        form: {
            username: '',
            email: '',
            password: '',
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
            let rows = '';
            this.filteredUsers.forEach(function(u, idx) {
                let pegawai = u.pegawai ? u.pegawai.nama : '-';
                let role = u.role ? (u.role.charAt(0).toUpperCase() + u.role.slice(1)) : '-';
                let status = u.is_active ? 'Aktif' : 'Menunggu';
                rows += `<tr>
                    <td style="border: 1px solid #000000; text-align: center; vertical-align: middle;">${idx + 1}</td>
                    <td style="border: 1px solid #000000; mso-number-format:'\\@'; vertical-align: middle;">${u.username || '-'}</td>
                    <td style="border: 1px solid #000000; vertical-align: middle;">${u.email || '-'}</td>
                    <td style="border: 1px solid #000000; vertical-align: middle;">${role}</td>
                    <td style="border: 1px solid #000000; text-align: center; vertical-align: middle;">${status}</td>
                    <td style="border: 1px solid #000000; vertical-align: middle;">${pegawai}</td>
                </tr>`;
            });

            const excelTemplate = `
                <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                    <!--[if gte mso 9]>
                    <` + `xml>
                        <` + `x:ExcelWorkbook>
                            <` + `x:ExcelWorksheets>
                                <` + `x:ExcelWorksheet>
                                    <` + `x:Name>Data User</` + `x:Name>
                                    <` + `x:WorksheetOptions>
                                        <` + `x:DisplayGridlines/>
                                    </` + `x:WorksheetOptions>
                                </` + `x:ExcelWorksheet>
                            </` + `x:ExcelWorksheets>
                        </` + `x:ExcelWorkbook>
                    </` + `xml>
                    <![endif]-->
                </head>
                <body>
                    <table border="1" style="border-collapse: collapse; font-family: Arial, sans-serif; font-size: 11px;">
                        <thead>
                            <tr style="background-color: #000000; color: #ffffff;">
                                <th style="border: 1px solid #000000; padding: 8px; text-align: center; background-color: #000000; color: #ffffff;">No</th>
                                <th style="border: 1px solid #000000; padding: 8px; text-align: center; background-color: #000000; color: #ffffff;">Username</th>
                                <th style="border: 1px solid #000000; padding: 8px; text-align: center; background-color: #000000; color: #ffffff;">Email</th>
                                <th style="border: 1px solid #000000; padding: 8px; text-align: center; background-color: #000000; color: #ffffff;">Role</th>
                                <th style="border: 1px solid #000000; padding: 8px; text-align: center; background-color: #000000; color: #ffffff;">Status</th>
                                <th style="border: 1px solid #000000; padding: 8px; text-align: center; background-color: #000000; color: #ffffff;">Nama Pegawai Terhubung</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${rows}
                        </tbody>
                    </table>
                </body>
                </html>`;

            const blob = new Blob([excelTemplate], { type: 'application/vnd.ms-excel;charset=utf-8' });
            const link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = `Data_User_SIMPEG_${new Date().toISOString().split('T')[0]}.xls`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        },

        printPdf() {
            let rows = '';
            this.filteredUsers.forEach((u, idx) => {
                let pegawai = u.pegawai ? u.pegawai.nama : '-';
                let role = u.role ? (u.role.charAt(0).toUpperCase() + u.role.slice(1)) : '-';
                let status = u.is_active ? 'Aktif' : 'Menunggu';
                rows += `
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 8px; text-align: center;">${idx + 1}</td>
                        <td style="padding: 8px; font-weight: bold;">${u.username || '-'}</td>
                        <td style="padding: 8px;">${u.email || '-'}</td>
                        <td style="padding: 8px;">${role}</td>
                        <td style="padding: 8px;">${status}</td>
                        <td style="padding: 8px;">${pegawai}</td>
                    </tr>
                `;
            });

            const printTemplate = `
                <div style="font-family: sans-serif; padding: 20px;">
                    <h2 style="font-size: 20px; font-weight: bold; margin-bottom: 16px; text-align: center;">Laporan Data User SIMPEG</h2>
                    <table style="width: 100%; border-collapse: collapse; font-size: 12px; text-align: left;">
                        <thead>
                            <tr style="background-color: #f1f5f9; border-bottom: 2px solid #cbd5e1;">
                                <th style="padding: 8px; text-align: center; width: 40px;">No</th>
                                <th style="padding: 8px;">Username</th>
                                <th style="padding: 8px;">Email</th>
                                <th style="padding: 8px;">Role</th>
                                <th style="padding: 8px;">Status</th>
                                <th style="padding: 8px;">Nama Pegawai</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${rows}
                        </tbody>
                    </table>
                </div>
            `;

            const style = document.createElement('style');
            style.innerHTML = `
                @media print {
                    body > *:not(#print-section) { display: none !important; }
                    #print-section { display: block !important; position: absolute; left: 0; top: 0; width: 100%; }
                }
            `;
            document.head.appendChild(style);

            const printSection = document.createElement('div');
            printSection.id = 'print-section';
            printSection.innerHTML = printTemplate;
            document.body.appendChild(printSection);

            window.print();

            document.body.removeChild(printSection);
            document.head.removeChild(style);
        },

        openCreateModal() {
            this.isEdit = false;
            this.formAction = '{{ route("admin.users.store") }}';
            this.form = { username: '', email: '', password: '', role: 'user', pegawai_id: '' };
            this.currentPegawai = null;
            this.modalOpen = true;
        },
        openEditModal(user, pegawai) {
            this.isEdit = true;
            this.formAction = `/admin/users/${user.id}`;
            this.form = { 
                username: user.username, 
                email: user.email, 
                password: '',
                role: user.role, 
                pegawai_id: pegawai ? pegawai.id : '' 
            };
            this.currentPegawai = pegawai;
            this.modalOpen = true;
        },
        confirmActivate(url, username) {
            Swal.fire({
                title: 'Konfirmasi Persetujuan',
                text: `Apakah Anda yakin ingin menyetujui akun "${username}"? Akun akan langsung aktif dan pengguna dapat login.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#059669',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Setujui!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = document.getElementById('activate-form');
                    form.action = url;
                    form.submit();
                }
            })
        },
        confirmDelete(url) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Akun user ini akan dihapus.",
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
