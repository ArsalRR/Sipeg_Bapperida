@extends('layouts.admin')

@section('title', 'Cetak Dokumen KP4')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="bg-white dark:bg-[#111111] rounded-2xl p-5 md:p-6 shadow-sm border border-gray-100 dark:border-gray-800 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        
        <form id="formFilterKp4" method="GET" action="{{ route('dokumen.kp4') }}" class="flex-1 w-full flex flex-col sm:flex-row sm:items-center gap-4">
            @if(in_array(auth()->user()->role, ['admin', 'superadmin']))
            <div class="w-full sm:w-72 2md:w-[380px] space-y-1.5">
                <label class="block text-xs font-bold text-blue-600 dark:text-blue-400">
                    Pilih Pegawai untuk Cetak Form KP4:
                </label>
                <select name="pegawai_id" onchange="this.form.submit()" class="w-full px-4 py-2.5 border-2 border-blue-500 rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-medium focus:ring-2 focus:ring-blue-600 outline-none shadow-sm">
                    @foreach($allPegawais as $p)
                        <option value="{{ $p->id }}" {{ optional($pegawai)->id == $p->id ? 'selected' : '' }}>
                            {{ $p->nama_lengkap ?? $p->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            @else
            <div>
                <label class="block text-xs font-bold text-blue-600 dark:text-blue-400 mb-1">
                    Cetak Dokumen Form KP4 Pegawai:
                </label>
                <div class="text-sm font-bold text-slate-900 dark:text-white">
                    {{ auth()->user()->pegawai?->nama_lengkap ?? auth()->user()->username }}
                </div>
            </div>
            @endif

            <div class="w-full sm:w-44 space-y-1.5">
                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400">
                    Tahun KP4:
                </label>
                <select name="tahun" onchange="this.form.submit()" class="w-full px-3.5 py-2.5 border border-gray-300 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-semibold focus:ring-2 focus:ring-blue-600 outline-none shadow-sm">
                    @foreach($availableYears as $y)
                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>
                            Tahun {{ $y }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>

        <div class="shrink-0 w-full md:w-auto flex justify-end">
            <button onclick="triggerPrint()" class="w-full md:w-auto px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-blue-500/20 transition-all flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak ke A4
            </button>
        </div>
    </div>

    <div class="bg-[#525659] dark:bg-[#1a1a1a] rounded-2xl p-4 md:p-10 shadow-inner overflow-x-auto flex justify-center min-h-[850px]">
        
        <div id="kp4-document-sheet" class="bg-white text-black p-[15mm] shadow-2xl rounded-sm w-[210mm] min-h-[297mm] box-border text-[11pt] font-['Times_New_Roman',Times,serif] leading-[1.3] relative shrink-0">
            
            <div class="text-right mb-1">
                <span class="inline-block border-[1.5px] border-black px-2 py-0.5 font-bold text-[10pt]">Form KP4</span>
            </div>

            <div class="text-center font-bold text-[12pt] uppercase tracking-wide">SURAT KETERANGAN</div>
            <div class="text-center font-bold text-[11pt] uppercase tracking-wide mb-3">UNTUK MENDAPATKAN PEMBAYARAN TUNJANGAN KELUARGA</div>
            <div class="border-b-2 border-black mb-4"></div>

            <div class="mb-2">Saya yang bertanda tangan dibawah ini :</div>

            @php
                // Jika ada riwayat yang berlaku sebelum/pada 03 Januari tahun berjalan, gunakan data riwayat tersebut
                $gelarDepan = ($activeHistory && isset($activeHistory->gelar_depan)) ? $activeHistory->gelar_depan : ($pegawai->gelar_depan ?? null);
                $gelarBelakang = ($activeHistory && isset($activeHistory->gelar_belakang)) ? $activeHistory->gelar_belakang : ($pegawai->gelar_belakang ?? null);
                
                $namaRaw = ($activeHistory && isset($activeHistory->nama) && $activeHistory->nama) ? $activeHistory->nama : ($pegawai->nama ?? '-');
                $namaDisplay = ($gelarDepan ? $gelarDepan . ' ' : '') . $namaRaw . ($gelarBelakang ? ', ' . $gelarBelakang : '');
                
                $nip = ($activeHistory && isset($activeHistory->nip) && $activeHistory->nip) ? $activeHistory->nip : ($pegawai->nip ?? '-');
                $tempatLahir = ($activeHistory && isset($activeHistory->tempat_lahir) && $activeHistory->tempat_lahir) ? $activeHistory->tempat_lahir : ($pegawai->tempat_lahir ?? '-');
                $tglLahirObj = ($activeHistory && isset($activeHistory->tanggal_lahir) && $activeHistory->tanggal_lahir) ? $activeHistory->tanggal_lahir : ($pegawai->tanggal_lahir ?? null);
                $jenisKelamin = ($activeHistory && isset($activeHistory->jenis_kelamin) && $activeHistory->jenis_kelamin) ? $activeHistory->jenis_kelamin : ($pegawai->jenis_kelamin ?? '-');
                $agama = ($activeHistory && isset($activeHistory->agama) && $activeHistory->agama) ? $activeHistory->agama : ($pegawai->agama ?? '-');

                $statusKepegawaian = $activeHistory ? ($activeHistory->status_kepegawaian ?? $pegawai->status_kepegawaian) : ($pegawai->status_kepegawaian ?? '-');
                $jabatanNama = $activeHistory ? ($activeHistory->jabatan->nama_jabatan ?? ($pegawai->jabatan->nama_jabatan ?? '-')) : ($pegawai->jabatan->nama_jabatan ?? '-');
                $golongan = $activeHistory ? ($activeHistory->golongan ?? $pegawai->golongan) : ($pegawai->golongan ?? '-');
            @endphp



            <table class="w-full mb-3 text-[11pt] border-collapse">
                <tr>
                    <td class="w-[25px] align-top py-0.5">1.</td>
                    <td class="w-[220px] align-top py-0.5">Nama lengkap</td>
                    <td class="w-[15px] align-top py-0.5">:</td>
                    <td class="align-top py-0.5"><strong>{{ $namaDisplay }}</strong></td>
                </tr>
                <tr>
                    <td class="align-top py-0.5">2.</td>
                    <td class="align-top py-0.5">NIP / NRK</td>
                    <td class="align-top py-0.5">:</td>
                    <td class="align-top py-0.5">{{ $nip }}</td>
                </tr>
                <tr>
                    <td class="align-top py-0.5">3.</td>
                    <td class="align-top py-0.5">Tempat / Tanggal Lahir</td>
                    <td class="align-top py-0.5">:</td>
                    <td class="align-top py-0.5">
                        {{ $tempatLahir }}, 
                        {{ $tglLahirObj ? $tglLahirObj->translatedFormat('d F Y') : '-' }}
                    </td>
                </tr>
                <tr>
                    <td class="align-top py-0.5">4.</td>
                    <td class="align-top py-0.5">Jenis Kelamin</td>
                    <td class="align-top py-0.5">:</td>
                    <td class="align-top py-0.5">{{ $jenisKelamin }}</td>
                </tr>
                <tr>
                    <td class="align-top py-0.5">5.</td>
                    <td class="align-top py-0.5">Agama</td>
                    <td class="align-top py-0.5">:</td>
                    <td class="align-top py-0.5">{{ $agama }}</td>
                </tr>
                <tr>
                    <td class="align-top py-0.5">6.</td>
                    <td class="align-top py-0.5">Status Kepegawaian</td>
                    <td class="align-top py-0.5">:</td>
                    <td class="align-top py-0.5">{{ $statusKepegawaian }}</td>
                </tr>
                <tr>
                    <td class="align-top py-0.5">7.</td>
                    <td class="align-top py-0.5">Jabatan Struktural / Fungsional</td>
                    <td class="align-top py-0.5">:</td>
                    <td class="align-top py-0.5">{{ $jabatanNama }}</td>
                </tr>
                <tr>
                    <td class="align-top py-0.5">8.</td>
                    <td class="align-top py-0.5">Pangkat / Golongan</td>
                    <td class="align-top py-0.5">:</td>
                    <td class="align-top py-0.5">{{ $golongan }}</td>
                </tr>
                <tr>
                    <td class="align-top py-0.5">9.</td>
                    <td class="align-top py-0.5">Pada Unit Kerja</td>
                    <td class="align-top py-0.5">:</td>
                    <td class="align-top py-0.5">Badan Perencanaan Pembangunan, Riset dan Inovasi Daerah Kota Pekalongan</td>
                </tr>
                <tr>
                    <td class="align-top py-0.5">10.</td>
                    <td class="align-top py-0.5">Masa Kerja Golongan</td>
                    <td class="align-top py-0.5">:</td>
                    <td class="align-top py-0.5">-</td>
                </tr>
                <tr>
                    <td class="align-top py-0.5">11.</td>
                    <td class="align-top py-0.5">Digaji menurut</td>
                    <td class="align-top py-0.5">:</td>
                    <td class="align-top py-0.5">PP Nomor 5 Tahun 2024</td>
                </tr>
                <tr>
                    <td class="align-top py-0.5">12.</td>
                    <td class="align-top py-0.5">Alamat / tempat tinggal</td>
                    <td class="align-top py-0.5">:</td>
                    <td class="align-top py-0.5">{{ $pegawai->alamat ?? '-' }}</td>
                </tr>
            </table>

            <div class="mb-2">Menerangkan dengan sesungguhnya bahwa saya mempunyai susunan keluarga sebagai berikut:</div>
            <table class="w-full border-collapse mb-5 text-[9.5pt]">
                <thead>
                    <tr>
                        <th rowspan="2" class="border border-black p-1.5 w-[30px] font-bold uppercase bg-gray-50 text-center">NO</th>
                        <th rowspan="2" class="border border-black p-1.5 font-bold uppercase bg-gray-50 text-center">NAMA ISTRI / SUAMI / ANAK</th>
                        <th rowspan="2" class="border border-black p-1.5 font-bold uppercase bg-gray-50 text-center">TEMPAT LAHIR</th>
                        <th colspan="2" class="border border-black p-1.5 font-bold uppercase bg-gray-50 text-center">TANGGAL (Tgl/Bln/Thn)</th>
                        <th rowspan="2" class="border border-black p-1.5 font-bold uppercase bg-gray-50 text-center">PEKERJAAN / SEKOLAH</th>
                        <th rowspan="2" class="border border-black p-1.5 font-bold uppercase bg-gray-50 text-center">HUBUNGAN</th>
                        <th rowspan="2" class="border border-black p-1.5 font-bold uppercase bg-gray-50 text-center">KET. TUNJANGAN</th>
                    </tr>
                    <tr>
                        <th class="border border-black p-1 font-bold uppercase bg-gray-50 text-center">LAHIR</th>
                        <th class="border border-black p-1 font-bold uppercase bg-gray-50 text-center">PERKAWINAN</th>
                    </tr>
                </thead>
                <tbody>
                    @if($pegawai && $pegawai->keluargas && $pegawai->keluargas->count() > 0)
                        @foreach($pegawai->keluargas as $index => $k)
                            <tr>
                                <td class="border border-black p-1.5 text-center">{{ $index + 1 }}</td>
                                <td class="border border-black p-1.5 text-left font-bold">{{ $k->nama }}</td>
                                <td class="border border-black p-1.5 text-center">{{ $k->tempat_lahir ?? '-' }}</td>
                                <td class="border border-black p-1.5 text-center">{{ $k->tanggal_lahir ? $k->tanggal_lahir->format('d/m/Y') : '-' }}</td>
                                <td class="border border-black p-1.5 text-center">{{ in_array($k->hubungan, ['Suami','Istri']) && $k->tanggal_perkawinan ? $k->tanggal_perkawinan->format('d/m/Y') : '-' }}</td>
                                <td class="border border-black p-1.5 text-center">{{ $k->pekerjaan ?? '-' }}</td>
                                <td class="border border-black p-1.5 text-center">{{ $k->hubungan }}</td>
                                <td class="border border-black p-1.5 text-center">{{ $k->tunjangan }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="border border-black p-6 text-center text-gray-500 italic">Tidak ada data keluarga yang terdaftar</td>
                        </tr>
                    @endif
                </tbody>
            </table>

            <div class="text-justify mb-6 leading-relaxed">
                Keterangan ini saya buat dengan sesungguhnya dan apabila keterangan ini ternyata <strong>tidak benar (palsu)</strong>, saya bersedia dituntut dimuka pengadilan berdasarkan Undang-undang yang berlaku, dan bersedia mengembalikan semua penghasilan yang telah saya terima yang seharusnya bukan menjadi hak saya.
            </div>

            <table class="w-full border-collapse mt-10 text-[11pt] signature-block">
                <tr>
                    <td class="w-1/2 text-center align-top">
                        Mengetahui:<br>
                        <strong>Kepala BAPPERIDA Kota Pekalongan</strong>
                        <div class="h-[75px]"></div>
                        <div class="font-bold underline">ANDRIANTO, S.T., M.T.</div>
                        <div>NIP. 197301111998031006</div>
                    </td>
                    <td class="w-1/2 text-center align-top">
                        Pekalongan, {{ $tanggalSurat }}<br>
                        <strong>Pegawai yang bersangkutan,</strong>
                        <div class="h-[75px]"></div>
                        <div class="font-bold underline">{{ $namaDisplay }}</div>
                        <div>NIP. {{ $nip != '-' ? $nip : '............................................' }}</div>
                    </td>
                </tr>
            </table>

        </div>
    </div>

</div>

<script>
function triggerPrint() {
    const printSection = document.getElementById('kp4-document-sheet').cloneNode(true);

    const style = document.createElement('style');
    style.innerHTML = `
        @media print {
            @page {
                size: A4;
                margin: 15mm; /* margin ini otomatis berulang di SETIAP halaman, termasuk halaman 2, 3, dst */
            }
            body * { visibility: hidden; }
            #print-area, #print-area * { visibility: visible; }
            #print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            /* Karena margin sudah diatur lewat @page, padding manual di sheet dimatikan saat print
               supaya tidak dobel margin */
            #print-area #kp4-document-sheet {
                width: 100% !important;
                min-height: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
            }
            /* Cegah SATU BARIS tabel terpotong di tengah saat pindah halaman.
               Sengaja tidak diterapkan ke <table> itu sendiri, supaya tabel yang
               panjang (misal data keluarga) tetap boleh mengalir ke halaman
               berikutnya secara wajar tanpa menyisakan ruang kosong besar. */
            tr {
                page-break-inside: avoid;
                break-inside: avoid;
            }
            thead {
                display: table-header-group; /* header tabel ikut terulang di tiap halaman */
            }
            .signature-block {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }
        }
    `;

    const printArea = document.createElement('div');
    printArea.id = 'print-area';
    printArea.appendChild(printSection);

    document.body.appendChild(printArea);
    document.head.appendChild(style);

    window.print();

    document.body.removeChild(printArea);
    document.head.removeChild(style);
}
</script>
@endsection