@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    @php
        $isAdmin = in_array(auth()->user()->role, ['admin', 'superadmin']);
        $nama = auth()->user()->pegawai?->nama_lengkap ?? auth()->user()->username;

        $links = $isAdmin
            ? [
                ['Data Pegawai',          'Kelola seluruh data pegawai.',          route('admin.pegawais.index')],
                ['Verifikasi Perubahan',  'Tinjau pengajuan perubahan data.',      route('admin.verifikasi.index')],
                ['Data Jabatan',          'Atur master jabatan.',                  route('admin.jabatans.index')],
                ['Peta Jabatan',          'Lihat struktur jabatan per unit.',      route('admin.jabatans.peta')],
            ]
            : [
                ['Data Keluarga',  'Lengkapi dan perbarui data keluarga.', route('keluarga.index')],
                ['Cetak Form KP4', 'Cetak formulir KP4 Anda.',             route('dokumen.kp4')],
                ['Menu File',      'Kelola dokumen pribadi.',              route('dokumen.file.index')],
                ['Edit Profil',    'Ubah data profil dan foto.',           route('profile.index')],
            ];
    @endphp

    <div class="max-w-7xl mx-auto space-y-6">
        @if($isAdmin)
            @php
                $stats = [
                    ['Total Pegawai',       $totalPegawai,  route('admin.pegawais.index'), 'Pegawai terdata'],
                    ['Menunggu Verifikasi', $totalMenunggu, route('admin.users.index'),    'Perlu ditinjau'],
                    ['Master Jabatan',      $totalJabatan,  route('admin.jabatans.index'), 'Jabatan terdaftar'],
                ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
                @foreach($stats as [$label, $total, $url, $hint])
                    <a href="{{ $url }}"
                       class="group block bg-white dark:bg-neutral-900 rounded-xl border border-slate-200 dark:border-neutral-800 border-l-4 border-l-blue-900 dark:border-l-blue-500 p-6 shadow-sm transition-colors hover:bg-blue-50 hover:border-blue-200 dark:hover:bg-neutral-800 dark:hover:border-neutral-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-900 dark:focus-visible:ring-blue-400">
                        <div class="flex items-start justify-between gap-3">
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ $label }}</p>
                            @if($url === route('admin.users.index') && $total > 0)
                                <span class="px-2 py-0.5 text-[11px] font-semibold rounded-full bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-400">Perlu tindakan</span>
                            @endif
                        </div>
                        <h3 class="mt-2 text-4xl font-bold tracking-tight text-blue-900 dark:text-white">{{ number_format($total) }}</h3>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                            {{ $hint }}
                            <span class="inline-block transition-transform group-hover:translate-x-0.5" aria-hidden="true">&rarr;</span>
                        </p>
                    </a>
                @endforeach
            </div>
        @endif

        {{-- Akses cepat --}}
        <section class="bg-white dark:bg-neutral-900 rounded-xl border border-slate-200 dark:border-neutral-800 overflow-hidden transition-colors duration-300">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-neutral-800">
                <h3 class="text-base font-semibold text-slate-900 dark:text-white">Akses Cepat</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Pintasan ke menu yang paling sering dipakai.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-px bg-slate-200 dark:bg-neutral-800">
                @foreach($links as [$title, $desc, $url])
                    <a href="{{ $url }}"
                       class="group flex items-center justify-between gap-4 px-6 py-4 bg-white dark:bg-neutral-900 transition-colors hover:bg-blue-50 dark:hover:bg-neutral-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-blue-900 dark:focus-visible:ring-blue-400">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-slate-900 dark:text-white group-hover:text-blue-900 dark:group-hover:text-white">{{ $title }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ $desc }}</p>
                        </div>
                        <span class="shrink-0 text-slate-400 dark:text-slate-500 transition-transform group-hover:translate-x-0.5 group-hover:text-blue-900 dark:group-hover:text-blue-300" aria-hidden="true">&rarr;</span>
                    </a>
                @endforeach
            </div>
        </section>

    </div>
@endsection