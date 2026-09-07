@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="max-w-7xl mx-auto space-y-8">
        
        <!-- Cards Row -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Card 1: Total Pegawai -->
            <div class="bg-white dark:bg-[#111111] rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 flex items-center gap-6 transition-all duration-300 hover:shadow-md">
                <!-- Glowing Icon -->
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
            </div>

            <!-- Card 2: Menunggu Verifikasi -->
            <div class="bg-white dark:bg-[#111111] rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 flex items-center gap-6 transition-all duration-300 hover:shadow-md">
                <!-- Glowing Icon -->
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
            </div>

            <!-- Card 3: Master Jabatan -->
            <div class="bg-white dark:bg-[#111111] rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 flex items-center gap-6 transition-all duration-300 hover:shadow-md">
                <!-- Glowing Icon -->
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
            </div>
            
        </div>
        
        <!-- Table Area Preview -->
        <div class="bg-white dark:bg-[#111111] rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden mt-8 transition-colors duration-300">
            <div class="px-8 py-6 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">Daftar Pegawai Terbaru</h3>
                <button class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-sm shadow-blue-500/30 transition-colors">
                    + Tambah Pegawai
                </button>
            </div>
            <div class="p-8 text-center text-gray-500 dark:text-gray-400 py-24 bg-gray-50/50 dark:bg-[#111111]">
                <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-700 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                <p class="text-lg">Data pegawai akan ditampilkan dalam format tabel modern di sini.</p>
                <p class="text-sm mt-2">Gunakan komponen tabel dengan style yang serupa (border tipis, tanpa garis vertikal).</p>
            </div>
        </div>

    </div>
@endsection
