@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="max-w-7xl mx-auto space-y-8">
        
        @if(in_array(auth()->user()->role, ['admin', 'superadmin']))
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <a href="{{ route('admin.pegawais.index') }}" class="bg-white dark:bg-[#111111] rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 flex items-center gap-6 transition-all duration-300 hover:shadow-md hover:-translate-y-1 block">
                <div class="relative flex-shrink-0">
                    <div class="absolute inset-0 bg-green-500 opacity-20 blur-xl rounded-full"></div>
                    <div class="relative w-14 h-14 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-green-500/30">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 tracking-wide mb-1">Total Pegawai</p>
                    <h3 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">{{ number_format($totalPegawai) }}</h3>
                </div>
            </a>

            <a href="{{ route('admin.users.index') }}" class="bg-white dark:bg-[#111111] rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 flex items-center gap-6 transition-all duration-300 hover:shadow-md hover:-translate-y-1 block">
                <div class="relative flex-shrink-0">
                    <div class="absolute inset-0 bg-amber-500 opacity-20 blur-xl rounded-full"></div>
                    <div class="relative w-14 h-14 bg-gradient-to-br from-amber-400 to-amber-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-amber-500/30">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 tracking-wide mb-1">Menunggu Verifikasi</p>
                    <h3 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">{{ number_format($totalMenunggu) }}</h3>
                </div>
            </a>
            <a href="{{ route('admin.jabatans.index') }}" class="bg-white dark:bg-[#111111] rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 flex items-center gap-6 transition-all duration-300 hover:shadow-md hover:-translate-y-1 block">
                <div class="relative flex-shrink-0">
                    <div class="absolute inset-0 bg-purple-500 opacity-20 blur-xl rounded-full"></div>
                    <div class="relative w-14 h-14 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-purple-500/30">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 tracking-wide mb-1">Master Jabatan</p>
                    <h3 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">{{ number_format($totalJabatan) }}</h3>
                </div>
            </a>
            
        </div>
    
        <div class="bg-white dark:bg-[#111111] rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden mt-8 transition-colors duration-300">
            <div class="px-8 py-6 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">Panel Administrasi Sistem</h3>
            </div>
            <div class="p-8 text-center text-gray-500 dark:text-gray-400 py-16 bg-gray-50/50 dark:bg-[#111111]">
                <svg class="w-12 h-12 mx-auto text-blue-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                <p class="text-lg font-semibold text-slate-900 dark:text-white">Selamat Datang di Panel Admin SIMPEG ASN</p>
                <p class="text-sm mt-2 max-w-md mx-auto">Gunakan menu di navigasi sebelah kiri untuk mengelola data pegawai, verifikasi akun user, serta master jabatan.</p>
            </div>
        </div>

        @else
        <div class="bg-white dark:bg-[#111111] rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-8 space-y-2">
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Selamat Datang!</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm">Gunakan menu navigasi untuk mengelola data pegawai dan keluarga.</p>
        </div>
        @endif

    </div>
@endsection
