@extends('layouts.admin')

@section('title', 'Peta Jabatan BAPPERIDA')

@section('content')
<div class="space-y-4">
    <!-- Top Action Header (Hidden in Print) -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 print:hidden px-2">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white">Peta Jabatan BAPPERIDA</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Lampiran IV Keputusan Wali Kota Pekalongan Nomor 000.8.2/0123 Tahun 2025</p>
        </div>
        <div class="flex items-center gap-2 w-full sm:w-auto">
            <a href="{{ route('admin.jabatans.index') }}" class="px-3 sm:px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-slate-700 dark:text-gray-200 font-semibold rounded-lg shadow-sm transition-colors text-xs sm:text-sm flex items-center justify-center gap-1.5 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
            <button onclick="window.print()" class="flex-1 sm:flex-none px-3 sm:px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-sm transition-colors text-xs sm:text-sm flex items-center justify-center gap-2 text-center">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Cetak Peta Jabatan</span>
            </button>
        </div>
    </div>

    <!-- Scrollable Horizontal Outer Wrapper -->
    <div class="overflow-x-auto bg-gray-100 dark:bg-[#0a0a0a] p-4 rounded-2xl border border-gray-200 dark:border-gray-800 print:p-0 print:border-none print:bg-white">
        
        <!-- CANVAS PETA JABATAN (Fixed Landscape Width 1240px to guarantee 100% 1-to-1 match) -->
        <div id="peta-canvas" style="width: 1240px; min-width: 1240px; margin: 0 auto; background-color: #ffffff !important; color: #000000 !important; font-family: Arial, Helvetica, sans-serif; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); box-sizing: border-box;" class="print:w-full print:p-0 print:shadow-none">
            
            <!-- HEADER TOP: TITLE & LAMPIRAN + REKAP TABEL -->
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; border-bottom: 2px solid #000000; padding-bottom: 12px; color: #000000 !important;">
                <!-- Left Title -->
                <div style="width: 48%;">
                    <h1 style="font-size: 13px; font-weight: bold; margin: 0; line-height: 1.4; color: #000000 !important; font-family: Arial, sans-serif; text-transform: uppercase;">
                        Peta Jabatan Badan Perencanaan Pembangunan, Riset, dan Inovasi Daerah
                    <br>Kota Pekalongan </br>
                </h1>
                </div>

                <!-- Right Lampiran & Rekap Table -->
                <div style="width: 50%; display: flex; justify-content: flex-end; gap: 16px; font-size: 11px; color: #000000 !important;">
                    <!-- Lampiran Text -->
                    <div style="font-size: 9.5px; font-family: 'Times New Roman', Times, serif; line-height: 1.3; color: #000000 !important;">
                        <p style="font-weight: bold; margin: 0;">LAMPIRAN IV</p>
                        <p style="margin: 0;">KEPUTUSAN WALI KOTA PEKALONGAN</p>
                        <p style="margin: 0;">NOMOR 000.8.2/0123 TAHUN 2025</p>
                        <p style="margin: 0;">TENTANG</p>
                        <p style="margin: 0;">PENETAPAN PETA JABATAN</p>
                        <p style="margin: 0;">DI LINGKUNGAN PEMERINTAH KOTA PEKALONGAN</p>
                    </div>

                    <!-- Rekapitulasi Table -->
                    <div style="border: 1px solid #000000; width: 230px; font-size: 9px; font-family: Arial, sans-serif; background-color: #ffffff !important; color: #000000 !important;">
                        <div style="padding: 3px 6px; border-bottom: 1px solid #000000; font-size: 9px; background-color: #ffffff !important;">
                            <div style="display: flex; justify-content: space-between; font-weight: bold; color: #000000 !important;">
                                <span>JUMLAH ASN</span>
                                <span>: {{ $totalAsn }} ORANG</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding-left: 8px; color: #000000 !important;">
                                <span>PNS</span>
                                <span>: {{ $totalPns }} ORANG</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding-left: 8px; color: #000000 !important;">
                                <span>PPPK</span>
                                <span>: {{ $totalPppk }} ORANG</span>
                            </div>
                        </div>
                        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 8.5px; color: #000000 !important; background-color: #ffffff !important;">
                            <thead>
                                <tr style="border-bottom: 1px solid #000000; text-align: center; font-weight: bold; background-color: #e5e7eb !important; color: #000000 !important;">
                                    <th style="padding: 2px 4px; text-align: left; border-right: 1px solid #000000; color: #000000 !important;">NAMA JABATAN</th>
                                    <th style="padding: 2px; width: 20px; border-right: 1px solid #000000; color: #000000 !important;">B</th>
                                    <th style="padding: 2px; width: 20px; border-right: 1px solid #000000; color: #000000 !important;">K</th>
                                    <th style="padding: 2px; width: 24px; color: #000000 !important;">+/-</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totB = 0; $totK = 0;
                                @endphp
                                @foreach(['JPT PRATAMA', 'ADMINISTRATOR', 'PENGAWAS', 'FUNGSIONAL', 'PELAKSANA'] as $cat)
                                    @php
                                        $catB = $rekapJenis[$cat]['B'] ?? 0;
                                        $catK = $rekapJenis[$cat]['K'] ?? 0;
                                        $catS = $catB - $catK;
                                        $totB += $catB;
                                        $totK += $catK;
                                    @endphp
                                    <tr style="border-bottom: 1px solid #000000; color: #000000 !important;">
                                        <td style="padding: 2px 4px; border-right: 1px solid #000000; font-weight: bold; color: #000000 !important;">{{ $cat }}</td>
                                        <td style="text-align: center; border-right: 1px solid #000000; color: #000000 !important;">{{ $catB }}</td>
                                        <td style="text-align: center; border-right: 1px solid #000000; color: #000000 !important;">{{ $catK }}</td>
                                        <td style="text-align: center; font-weight: bold; color: #000000 !important;">{{ $catS > 0 ? '+'.$catS : $catS }}</td>
                                    </tr>
                                @endforeach
                                <tr style="font-weight: bold; background-color: #e5e7eb !important; color: #000000 !important;">
                                    <td style="padding: 2px 4px; border-right: 1px solid #000000; color: #000000 !important;">TOTAL</td>
                                    <td style="text-align: center; border-right: 1px solid #000000; color: #000000 !important;">{{ $totB }}</td>
                                    <td style="text-align: center; border-right: 1px solid #000000; color: #000000 !important;">{{ $totK }}</td>
                                    <td style="text-align: center; color: #000000 !important;">{{ ($totB - $totK) > 0 ? '+'.($totB - $totK) : ($totB - $totK) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TREE DIAGRAM CONTAINER (Width 1200px) -->
            <div style="width: 1200px; display: flex; flex-direction: column; align-items: flex-start; margin-top: 10px; color: #000000 !important; position: relative;">
                
                <!-- LEVEL 1: KEPALA BADAN (Top box centered at X = 300px, padding-left: 140px -> box width 320px spans X = 140px to 460px) -->
                @php $dKepala = $getJData('Kepala Badan', 1, 1, 14); @endphp
                <div style="width: 100%; display: flex; flex-direction: column; align-items: flex-start; padding-left: 140px; box-sizing: border-box;">
                    <div style="width: 320px; background-color: #00a8e8 !important; color: #ffffff !important; border: 1.5px solid #000000; padding: 6px 10px; text-align: center; box-sizing: border-box;">
                        <div style="font-weight: bold; font-size: 10px; text-transform: uppercase; line-height: 1.3; color: #ffffff !important;">{{ $dKepala['nama'] ?? 'Kepala Badan Perencanaan Pembangunan, Riset, dan Inovasi Daerah' }}</div>
                        <div style="font-size: 8.5px; margin-top: 2px; color: #ffffff !important;">Kelas : {{ $dKepala['kelas'] ?? 14 }}</div>
                    </div>
                </div>

                <!-- MIDDLE SECTION: MAIN VERTICAL STEM & BRANCHES (Height: 325px) -->
                <div style="position: relative; width: 1200px; height: 325px;">
                    
                    <!-- SVG CONNECTOR OVERLAY FOR Crisp Anti-aliased Lines -->
                    <svg style="position: absolute; left: 0; top: 0; width: 1200px; height: 325px; pointer-events: none; z-index: 1;">
                        <!-- Main stem from Kepala Badan center (X=300, Y=0) all the way down to bottom bus bar (X=300, Y=325) -->
                        <line x1="300" y1="0" x2="300" y2="325" stroke="#000000" stroke-width="1.5" />

                        <!-- Horizontal branch to Sekretaris Badan (X=300 to X=430 at Y=25) -->
                        <line x1="300" y1="25" x2="430" y2="25" stroke="#000000" stroke-width="1.5" />

                        <!-- Vertical stem from Sekretaris center (X=595, Y=45) down between Subbags to Fungsional table (Y=215) -->
                        <line x1="595" y1="45" x2="595" y2="215" stroke="#000000" stroke-width="1.5" />

                        <!-- Subbag Bus Bar (Horizontal T-junction from Subbag 1 center X=455 to Subbag 2 center X=767 at Y=65) -->
                        <line x1="455" y1="65" x2="767" y2="65" stroke="#000000" stroke-width="1.5" />

                        <!-- Vertical stems down to Subbag 1 (X=455) & Subbag 2 (X=767) from Y=65 to Y=78 -->
                        <line x1="455" y1="65" x2="455" y2="78" stroke="#000000" stroke-width="1.5" />
                        <line x1="767" y1="65" x2="767" y2="78" stroke="#000000" stroke-width="1.5" />

                        <!-- Horizontal branch from central stem (X=595 at Y=215) to left edge of Fungsional table (X=640) -->
                        <line x1="595" y1="215" x2="640" y2="215" stroke="#000000" stroke-width="1.5" />

                        <!-- Horizontal branch to JF Perencana Ahli Madya (X=265 to X=300 at Y=95) -->
                        <line x1="265" y1="95" x2="300" y2="95" stroke="#000000" stroke-width="1.5" />
                    </svg>

                    <!-- Sekretaris Box (spans X = 430px to X = 760px) -->
                    @php $dSekretaris = $getJData('Sekretaris Badan', 1, 1, 12); @endphp
                    <div style="position: absolute; left: 430px; top: 5px; width: 330px; background-color: #008040 !important; color: #ffffff !important; border: 1.5px solid #000000; padding: 6px 10px; text-align: center; box-sizing: border-box; z-index: 5;">
                        <div style="font-weight: bold; font-size: 10px; text-transform: uppercase; line-height: 1.3; color: #ffffff !important;">{{ $dSekretaris['nama'] ?? 'Sekretaris Badan Perencanaan Pembangunan, Riset, dan Inovasi Daerah' }}</div>
                        <div style="font-size: 8.5px; margin-top: 2px; color: #ffffff !important;">Kelas : {{ $dSekretaris['kelas'] ?? 12 }}</div>
                    </div>

                    <!-- 2 SUBBAGS SIDE-BY-SIDE (YELLOW BOXES - top Y = 82px) -->
                    @php
                        $dSubbag1 = $getJData('Umum dan Kepegawaian', 1, 1, 9);
                        $dSubbag2 = $getJData('Perencanaan Evaluasi', 1, 1, 9);
                    @endphp
                    <div style="position: absolute; left: 335px; top: 82px; width: 560px; display: flex; justify-content: space-between; align-items: flex-start; z-index: 5;">
                        
                        <!-- SUBBAG 1 (Umum & Kepegawaian, width 240px) -->
                        <div style="width: 240px; border: 1.5px solid #000000; background-color: #ffffff !important; font-size: 8px; color: #000000 !important; box-sizing: border-box;">
                            <div style="background-color: #ffd700 !important; color: #000000 !important; font-weight: bold; text-align: center; padding: 5px 4px; border-bottom: 1.5px solid #000000; font-size: 8.5px; line-height: 1.2;">
                                <div>{{ $dSubbag1['nama'] ?? 'Kepala Sub Bagian Umum dan Kepegawaian' }}</div>
                                <div style="font-size: 8px; margin-top: 1px; color: #000000 !important;">Kelas : {{ $dSubbag1['kelas'] ?? 9 }}</div>
                            </div>
                            <table style="width: 100%; border-collapse: collapse; color: #000000 !important; background-color: #ffffff !important; table-layout: fixed;">
                                <thead>
                                    <tr style="border-bottom: 1.5px solid #000000; background-color: #e5e7eb !important; text-align: center; font-weight: bold; color: #000000 !important;">
                                        <th style="padding: 4px; text-align: left; border-right: 1.5px solid #000000; color: #000000 !important; width: 140px; font-size: 8px;">Jabatan</th>
                                        <th style="padding: 4px 2px; width: 22px; border-right: 1.5px solid #000000; color: #000000 !important; font-size: 8px;">Kelas</th>
                                        <th style="padding: 4px 2px; width: 16px; border-right: 1.5px solid #000000; color: #000000 !important; font-size: 8px;">B</th>
                                        <th style="padding: 4px 2px; width: 16px; border-right: 1.5px solid #000000; color: #000000 !important; font-size: 8px;">K</th>
                                        <th style="padding: 4px 2px; width: 22px; color: #000000 !important; font-size: 8px;">- / +</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($getChildrenData('Umum dan Kepegawaian') as $r)
    <tr style="border-bottom: 1px solid #000000; color: #000000 !important;">
        <td style="padding: 4px; border-right: 1.5px solid #000000; color: #000000 !important; word-break: break-word; font-size: 8px; line-height: 1.2;">{{ $r['nama'] }}</td>
        <td style="padding: 4px 2px; text-align: center; border-right: 1.5px solid #000000; font-weight: bold; color: #000000 !important; font-size: 8px;">{{ $r['kelas'] }}</td>
        <td style="padding: 4px 2px; text-align: center; border-right: 1.5px solid #000000; color: #000000 !important; font-size: 8px;">{{ $r['B'] }}</td>
        <td style="padding: 4px 2px; text-align: center; border-right: 1.5px solid #000000; color: #000000 !important; font-size: 8px;">{{ $r['K'] }}</td>
        <td style="padding: 4px 2px; text-align: center; font-weight: bold; color: #000000 !important; font-size: 8px;">{{ $r['selisih'] > 0 ? '+'.$r['selisih'] : $r['selisih'] }}</td>
    </tr>
@endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- SUBBAG 2 (Perencanaan Evaluasi dan Keuangan, width 255px) -->
                        <div style="width: 255px; display: flex; flex-direction: column; align-items: center; box-sizing: border-box;">
                            <div style="width: 100%; border: 1.5px solid #000000; background-color: #ffffff !important; font-size: 8px; color: #000000 !important; box-sizing: border-box;">
                                <div style="background-color: #ffd700 !important; color: #000000 !important; font-weight: bold; text-align: center; padding: 5px 4px; border-bottom: 1.5px solid #000000; font-size: 8.5px; line-height: 1.2;">
                                    <div>{{ $dSubbag2['nama'] ?? 'Kepala Sub Bagian Perencanaan Evaluasi dan Keuangan' }}</div>
                                    <div style="font-size: 8px; margin-top: 1px; color: #000000 !important;">Kelas : {{ $dSubbag2['kelas'] ?? 9 }}</div>
                                </div>
                                <table style="width: 100%; border-collapse: collapse; color: #000000 !important; background-color: #ffffff !important; table-layout: fixed;">
                                    <thead>
                                        <tr style="border-bottom: 1.5px solid #000000; background-color: #e5e7eb !important; text-align: center; font-weight: bold; color: #000000 !important;">
                                            <th style="padding: 4px; text-align: left; border-right: 1.5px solid #000000; color: #000000 !important; width: 155px; font-size: 8px;">Jabatan</th>
                                            <th style="padding: 4px 2px; width: 22px; border-right: 1.5px solid #000000; color: #000000 !important; font-size: 8px;">Kelas</th>
                                            <th style="padding: 4px 2px; width: 16px; border-right: 1.5px solid #000000; color: #000000 !important; font-size: 8px;">B</th>
                                            <th style="padding: 4px 2px; width: 16px; border-right: 1.5px solid #000000; color: #000000 !important; font-size: 8px;">K</th>
                                            <th style="padding: 4px 2px; width: 22px; color: #000000 !important; font-size: 8px;">- / +</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($getChildrenData('Perencanaan Evaluasi dan Keuangan') as $r)
                                        <tr style="border-bottom: 1px solid #000000; color: #000000 !important;">
                                            <td style="padding: 4px; border-right: 1.5px solid #000000; color: #000000 !important; word-break: break-word; font-size: 8px; line-height: 1.2;">{{ $r['nama'] }}</td>
                                            <td style="padding: 4px 2px; text-align: center; border-right: 1.5px solid #000000; font-weight: bold; color: #000000 !important; font-size: 8px;">{{ $r['kelas'] }}</td>
                                            <td style="padding: 4px 2px; text-align: center; border-right: 1.5px solid #000000; color: #000000 !important; font-size: 8px;">{{ $r['B'] }}</td>
                                            <td style="padding: 4px 2px; text-align: center; border-right: 1.5px solid #000000; color: #000000 !important; font-size: 8px;">{{ $r['K'] }}</td>
                                            <td style="padding: 4px 2px; text-align: center; font-weight: bold; color: #000000 !important; font-size: 8px;">{{ $r['selisih'] > 0 ? '+'.$r['selisih'] : $r['selisih'] }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Standalone Box below Subbag 2 for Fungsional Perencanaan -->
                            <div style="height: 15px;"></div>
                            <div style="width: 100%; border: 1.5px solid #000000; background-color: #ffffff !important; font-size: 8px; color: #000000 !important; box-sizing: border-box;">
                                <table style="width: 100%; border-collapse: collapse; color: #000000 !important; background-color: #ffffff !important; table-layout: fixed;">
                                    <thead>
                                        <tr style="border-bottom: 1.5px solid #000000; background-color: #e5e7eb !important; text-align: center; font-weight: bold; color: #000000 !important;">
                                            <th style="padding: 4px; text-align: left; border-right: 1.5px solid #000000; color: #000000 !important; width: 155px; font-size: 8px;">Jabatan Fungsional</th>
                                            <th style="padding: 4px 2px; width: 22px; border-right: 1.5px solid #000000; color: #000000 !important; font-size: 8px;">Kelas</th>
                                            <th style="padding: 4px 2px; width: 16px; border-right: 1.5px solid #000000; color: #000000 !important; font-size: 8px;">B</th>
                                            <th style="padding: 4px 2px; width: 16px; border-right: 1.5px solid #000000; color: #000000 !important; font-size: 8px;">K</th>
                                            <th style="padding: 4px 2px; width: 22px; color: #000000 !important; font-size: 8px;">- / +</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($fungsionalPerencanaan as $fungsional)
                                        @php
                                            $b = $fungsional->bezetting;
                                            $k = $fungsional->kebutuhan ?? $fungsional->jumlah ?? 0;
                                            $selisih = $b - $k;
                                        @endphp
                                        <tr style="text-align: center; color: #000000 !important; border-bottom: 1.5px solid #000000;">
                                            <td style="padding: 4px; text-align: left; border-right: 1.5px solid #000000; font-weight: bold; color: #000000 !important; word-break: break-word; font-size: 8px; line-height: 1.2;">{{ $fungsional->nama_jabatan }}</td>
                                            <td style="padding: 4px 2px; border-right: 1.5px solid #000000; font-weight: bold; color: #000000 !important; font-size: 8px;">{{ $fungsional->kelas_jabatan }}</td>
                                            <td style="padding: 4px 2px; border-right: 1.5px solid #000000; color: #000000 !important; font-size: 8px;">{{ $b }}</td>
                                            <td style="padding: 4px 2px; border-right: 1.5px solid #000000; color: #000000 !important; font-size: 8px;">{{ $k }}</td>
                                            <td style="padding: 4px 2px; font-weight: bold; color: #000000 !important; font-size: 8px;">{{ $selisih > 0 ? '+'.$selisih : $selisih }}</td>
                                        </tr>
                                        @endforeach
                                        @if($fungsionalPerencanaan->isEmpty())
                                        <tr style="text-align: center;">
                                            <td colspan="5" style="padding: 4px; font-size: 8px; color: #9ca3af;">Tidak ada</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                    <!-- 2. JF STANDALONE (Branch to the left at Y = 82px) -->
                    <!-- Table Box (spans X = 25px to X = 265px at top Y = 82px) -->
                    <div style="position: absolute; left: 25px; top: 82px; width: 240px; border: 1.5px solid #000000; background-color: #ffffff !important; font-size: 8px; color: #000000 !important; z-index: 5; box-sizing: border-box;">
                        <table style="width: 100%; border-collapse: collapse; color: #000000 !important; background-color: #ffffff !important; table-layout: fixed;">
                            <thead>
                                <tr style="border-bottom: 1.5px solid #000000; background-color: #e5e7eb !important; text-align: center; font-weight: bold; color: #000000 !important;">
                                    <th style="padding: 4px; text-align: left; border-right: 1.5px solid #000000; color: #000000 !important; width: 140px; font-size: 8px;">Jabatan</th>
                                    <th style="padding: 4px 2px; width: 22px; border-right: 1.5px solid #000000; color: #000000 !important; font-size: 8px;">Kls</th>
                                    <th style="padding: 4px 2px; width: 16px; border-right: 1.5px solid #000000; color: #000000 !important; font-size: 8px;">B</th>
                                    <th style="padding: 4px 2px; width: 16px; border-right: 1.5px solid #000000; color: #000000 !important; font-size: 8px;">K</th>
                                    <th style="padding: 4px 2px; width: 22px; color: #000000 !important; font-size: 8px;">+ / -</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($fungsionalSekretariat as $fungsional)
                                @php
                                    $b = $fungsional->bezetting;
                                    $k = $fungsional->kebutuhan ?? $fungsional->jumlah ?? 0;
                                    $selisih = $b - $k;
                                @endphp
                                <tr style="text-align: center; color: #000000 !important; border-bottom: 1.5px solid #000000;">
                                    <td style="padding: 4px; text-align: left; border-right: 1.5px solid #000000; font-weight: bold; color: #000000 !important; word-break: break-word; font-size: 8px; line-height: 1.2;">{{ $fungsional->nama_jabatan }}</td>
                                    <td style="padding: 4px 2px; border-right: 1.5px solid #000000; font-weight: bold; color: #000000 !important; font-size: 8px;">{{ $fungsional->kelas_jabatan }}</td>
                                    <td style="padding: 4px 2px; border-right: 1.5px solid #000000; color: #000000 !important; font-size: 8px;">{{ $b }}</td>
                                    <td style="padding: 4px 2px; border-right: 1.5px solid #000000; color: #000000 !important; font-size: 8px;">{{ $k }}</td>
                                    <td style="padding: 4px 2px; font-weight: bold; color: #000000 !important; font-size: 8px;">{{ $selisih > 0 ? '+'.$selisih : $selisih }}</td>
                                </tr>
                                @endforeach
                                @if($fungsionalSekretariat->isEmpty())
                                <tr style="text-align: center;">
                                    <td colspan="5" style="padding: 4px; font-size: 8px; color: #9ca3af;">Tidak ada</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                </div>

                <!-- BOTTOM LEVEL: 4 BIDANG HORIZONTAL BUS BAR & COLUMNS -->
                <div style="position: relative; width: 1200px; display: flex; flex-direction: column; align-items: flex-start; margin-top: 0px;">
                    
                    <!-- SVG BUS BAR OVERLAY for Bottom 4 Bidangs -->
                    <svg style="position: absolute; left: 0; top: 0; width: 1200px; height: 25px; pointer-events: none; z-index: 1;">
                        <!-- Horizontal Bus Bar spanning from Bidang 1 center (X=135) to Bidang 4 center (X=1035) at Y=0 -->
                        <line x1="135" y1="0" x2="1035" y2="0" stroke="#000000" stroke-width="1.5" />

                        <!-- Vertical stems down into each 4 Bidang boxes (Y=0 to Y=25) -->
                        <line x1="135" y1="0" x2="135" y2="25" stroke="#000000" stroke-width="1.5" />
                        <line x1="435" y1="0" x2="435" y2="25" stroke="#000000" stroke-width="1.5" />
                        <line x1="735" y1="0" x2="735" y2="25" stroke="#000000" stroke-width="1.5" />
                        <line x1="1035" y1="0" x2="1035" y2="25" stroke="#000000" stroke-width="1.5" />
                    </svg>

                    <!-- 4 BIDANG COLUMNS SIDE-BY-SIDE (Container Width 1200px, 4x 270px columns with gap) -->
                    <div style="width: 1200px; display: flex; justify-content: space-between; align-items: flex-start; margin-top: 0px;">
                            
                            <!-- BIDANG 1 (Center X = 135px) -->
                            @php $dBidang1 = $getJData('Pemerintahan dan Pembangunan Manusia', 1, 1, 11, 'ppm'); @endphp
                            <div style="width: 270px; display: flex; flex-direction: column; align-items: center; box-sizing: border-box;">
                                <div style="width: 1px; height: 25px; opacity: 0;"></div>
                                <div style="width: 100%; border: 1.5px solid #000000; background-color: #ffffff !important; font-size: 8px; color: #000000 !important; box-sizing: border-box;">
                                    <div style="background-color: #4caf50 !important; color: #ffffff !important; font-weight: bold; text-align: center; padding: 5px 4px; border-bottom: 1.5px solid #000000; font-size: 8.5px; line-height: 1.2; height: 44px; display: flex; flex-direction: column; justify-content: center; box-sizing: border-box;">
                                        <div>{{ $dBidang1['nama'] ?? 'Kepala Bidang Pemerintahan dan Pembangunan Manusia' }}</div>
                                        <div style="font-size: 8px; margin-top: 2px; color: #ffffff !important;">Kelas : {{ $dBidang1['kelas'] ?? 11 }}</div>
                                    </div>
                                    <table style="width: 100%; border-collapse: collapse; color: #000000 !important; background-color: #ffffff !important; table-layout: fixed;">
                                        <thead>
                                            <tr style="border-bottom: 1.5px solid #000000; background-color: #e5e7eb !important; text-align: center; font-weight: bold; color: #000000 !important;">
                                                <th style="padding: 4px; text-align: left; border-right: 1.5px solid #000000; color: #000000 !important; width: 170px; font-size: 8px;">Jabatan</th>
                                                <th style="padding: 4px 2px; width: 22px; border-right: 1.5px solid #000000; color: #000000 !important; font-size: 8px;">Kls</th>
                                                <th style="padding: 4px 2px; width: 16px; border-right: 1.5px solid #000000; color: #000000 !important; font-size: 8px;">B</th>
                                                <th style="padding: 4px 2px; width: 16px; border-right: 1.5px solid #000000; color: #000000 !important; font-size: 8px;">K</th>
                                                <th style="padding: 4px 2px; width: 22px; color: #000000 !important; font-size: 8px;">+ / -</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           @foreach($getChildrenData('Pemerintahan dan Pembangunan Manusia') as $r)
    <tr style="border-bottom: 1px solid #000000; color: #000000 !important;">
        <td style="padding: 4px; border-right: 1.5px solid #000000; color: #000000 !important; word-break: break-word; font-size: 8px; line-height: 1.2;">{{ $r['nama'] }}</td>
        <td style="padding: 4px 2px; text-align: center; border-right: 1.5px solid #000000; font-weight: bold; color: #000000 !important; font-size: 8px;">{{ $r['kelas'] }}</td>
        <td style="padding: 4px 2px; text-align: center; border-right: 1.5px solid #000000; color: #000000 !important; font-size: 8px;">{{ $r['B'] }}</td>
        <td style="padding: 4px 2px; text-align: center; border-right: 1.5px solid #000000; color: #000000 !important; font-size: 8px;">{{ $r['K'] }}</td>
        <td style="padding: 4px 2px; text-align: center; font-weight: bold; color: #000000 !important; font-size: 8px;">{{ $r['selisih'] > 0 ? '+'.$r['selisih'] : $r['selisih'] }}</td>
    </tr>
@endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- BIDANG 2 (Center X = 435px) -->
                            @php $dBidang2 = $getJData('Perekonomian', 1, 1, 11, 'ekonomi'); @endphp
                            <div style="width: 270px; display: flex; flex-direction: column; align-items: center; box-sizing: border-box;">
                                <div style="width: 1px; height: 25px; opacity: 0;"></div>
                                <div style="width: 100%; border: 1.5px solid #000000; background-color: #ffffff !important; font-size: 8px; color: #000000 !important; box-sizing: border-box;">
                                    <div style="background-color: #4caf50 !important; color: #ffffff !important; font-weight: bold; text-align: center; padding: 5px 4px; border-bottom: 1.5px solid #000000; font-size: 8.5px; line-height: 1.2; height: 44px; display: flex; flex-direction: column; justify-content: center; box-sizing: border-box;">
                                        <div>{{ $dBidang2['nama'] ?? 'Kepala Bidang Perekonomian, SDA, Infrastruktur & Kewilayahan' }}</div>
                                        <div style="font-size: 8px; margin-top: 2px; color: #ffffff !important;">Kelas : {{ $dBidang2['kelas'] ?? 11 }}</div>
                                    </div>
                                    <table style="width: 100%; border-collapse: collapse; color: #000000 !important; background-color: #ffffff !important; table-layout: fixed;">
                                        <thead>
                                            <tr style="border-bottom: 1.5px solid #000000; background-color: #e5e7eb !important; text-align: center; font-weight: bold; color: #000000 !important;">
                                                <th style="padding: 4px; text-align: left; border-right: 1.5px solid #000000; color: #000000 !important; width: 170px; font-size: 8px;">Jabatan</th>
                                                <th style="padding: 4px 2px; width: 22px; border-right: 1.5px solid #000000; color: #000000 !important; font-size: 8px;">Kls</th>
                                                <th style="padding: 4px 2px; width: 16px; border-right: 1.5px solid #000000; color: #000000 !important; font-size: 8px;">B</th>
                                                <th style="padding: 4px 2px; width: 16px; border-right: 1.5px solid #000000; color: #000000 !important; font-size: 8px;">K</th>
                                                <th style="padding: 4px 2px; width: 22px; color: #000000 !important; font-size: 8px;">+ / -</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($getChildrenData('Perekonomian') as $r)
    <tr style="border-bottom: 1px solid #000000; color: #000000 !important;">
        <td style="padding: 4px; border-right: 1.5px solid #000000; color: #000000 !important; word-break: break-word; font-size: 8px; line-height: 1.2;">{{ $r['nama'] }}</td>
        <td style="padding: 4px 2px; text-align: center; border-right: 1.5px solid #000000; font-weight: bold; color: #000000 !important; font-size: 8px;">{{ $r['kelas'] }}</td>
        <td style="padding: 4px 2px; text-align: center; border-right: 1.5px solid #000000; color: #000000 !important; font-size: 8px;">{{ $r['B'] }}</td>
        <td style="padding: 4px 2px; text-align: center; border-right: 1.5px solid #000000; color: #000000 !important; font-size: 8px;">{{ $r['K'] }}</td>
        <td style="padding: 4px 2px; text-align: center; font-weight: bold; color: #000000 !important; font-size: 8px;">{{ $r['selisih'] > 0 ? '+'.$r['selisih'] : $r['selisih'] }}</td>
    </tr>
@endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- BIDANG 3 (Center X = 735px) -->
                            @php $dBidang3 = $getJData('Pengendalian dan Evaluasi', 1, 1, 11, 'ppepd'); @endphp
                            <div style="width: 270px; display: flex; flex-direction: column; align-items: center; box-sizing: border-box;">
                                <div style="width: 1px; height: 25px; opacity: 0;"></div>
                                <div style="width: 100%; border: 1.5px solid #000000; background-color: #ffffff !important; font-size: 8px; color: #000000 !important; box-sizing: border-box;">
                                    <div style="background-color: #4caf50 !important; color: #ffffff !important; font-weight: bold; text-align: center; padding: 5px 4px; border-bottom: 1.5px solid #000000; font-size: 8.5px; line-height: 1.2; height: 44px; display: flex; flex-direction: column; justify-content: center; box-sizing: border-box;">
                                        <div>{{ $dBidang3['nama'] ?? 'Kepala Bidang Perencanaan, Pengendalian & Evaluasi' }}</div>
                                        <div style="font-size: 8px; margin-top: 2px; color: #ffffff !important;">Kelas : {{ $dBidang3['kelas'] ?? 11 }}</div>
                                    </div>
                                    <table style="width: 100%; border-collapse: collapse; color: #000000 !important; background-color: #ffffff !important; table-layout: fixed;">
                                        <thead>
                                            <tr style="border-bottom: 1.5px solid #000000; background-color: #e5e7eb !important; text-align: center; font-weight: bold; color: #000000 !important;">
                                                <th style="padding: 4px; text-align: left; border-right: 1.5px solid #000000; color: #000000 !important; width: 170px; font-size: 8px;">Jabatan</th>
                                                <th style="padding: 4px 2px; width: 22px; border-right: 1.5px solid #000000; color: #000000 !important; font-size: 8px;">Kls</th>
                                                <th style="padding: 4px 2px; width: 16px; border-right: 1.5px solid #000000; color: #000000 !important; font-size: 8px;">B</th>
                                                <th style="padding: 4px 2px; width: 16px; border-right: 1.5px solid #000000; color: #000000 !important; font-size: 8px;">K</th>
                                                <th style="padding: 4px 2px; width: 22px; color: #000000 !important; font-size: 8px;">+ / -</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($getChildrenData('Pengendalian dan Evaluasi') as $r)
    <tr style="border-bottom: 1px solid #000000; color: #000000 !important;">
        <td style="padding: 4px; border-right: 1.5px solid #000000; color: #000000 !important; word-break: break-word; font-size: 8px; line-height: 1.2;">{{ $r['nama'] }}</td>
        <td style="padding: 4px 2px; text-align: center; border-right: 1.5px solid #000000; font-weight: bold; color: #000000 !important; font-size: 8px;">{{ $r['kelas'] }}</td>
        <td style="padding: 4px 2px; text-align: center; border-right: 1.5px solid #000000; color: #000000 !important; font-size: 8px;">{{ $r['B'] }}</td>
        <td style="padding: 4px 2px; text-align: center; border-right: 1.5px solid #000000; color: #000000 !important; font-size: 8px;">{{ $r['K'] }}</td>
        <td style="padding: 4px 2px; text-align: center; font-weight: bold; color: #000000 !important; font-size: 8px;">{{ $r['selisih'] > 0 ? '+'.$r['selisih'] : $r['selisih'] }}</td>
    </tr>
@endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- BIDANG 4 (Center X = 1035px) -->
                            @php $dBidang4 = $getJData('Riset dan Inovasi', 1, 1, 11, 'litbang'); @endphp
                            <div style="width: 270px; display: flex; flex-direction: column; align-items: center; box-sizing: border-box;">
                                <div style="width: 1px; height: 25px; opacity: 0;"></div>
                                <div style="width: 100%; border: 1.5px solid #000000; background-color: #ffffff !important; font-size: 8px; color: #000000 !important; box-sizing: border-box;">
                                    <div style="background-color: #4caf50 !important; color: #ffffff !important; font-weight: bold; text-align: center; padding: 5px 4px; border-bottom: 1.5px solid #000000; font-size: 8.5px; line-height: 1.2; height: 44px; display: flex; flex-direction: column; justify-content: center; box-sizing: border-box;">
                                        <div>{{ $dBidang4['nama'] ?? 'Kepala Bidang Riset dan Inovasi Daerah' }}</div>
                                        <div style="font-size: 8px; margin-top: 2px; color: #ffffff !important;">Kelas : {{ $dBidang4['kelas'] ?? 11 }}</div>
                                    </div>
                                    <table style="width: 100%; border-collapse: collapse; color: #000000 !important; background-color: #ffffff !important; table-layout: fixed;">
                                        <thead>
                                            <tr style="border-bottom: 1.5px solid #000000; background-color: #e5e7eb !important; text-align: center; font-weight: bold; color: #000000 !important;">
                                                <th style="padding: 4px; text-align: left; border-right: 1.5px solid #000000; color: #000000 !important; width: 170px; font-size: 8px;">Jabatan</th>
                                                <th style="padding: 4px 2px; width: 22px; border-right: 1.5px solid #000000; color: #000000 !important; font-size: 8px;">Kls</th>
                                                <th style="padding: 4px 2px; width: 16px; border-right: 1.5px solid #000000; color: #000000 !important; font-size: 8px;">B</th>
                                                <th style="padding: 4px 2px; width: 16px; border-right: 1.5px solid #000000; color: #000000 !important; font-size: 8px;">K</th>
                                                <th style="padding: 4px 2px; width: 22px; color: #000000 !important; font-size: 8px;">+ / -</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($getChildrenData('Riset dan Inovasi') as $r)
    <tr style="border-bottom: 1px solid #000000; color: #000000 !important;">
        <td style="padding: 4px; border-right: 1.5px solid #000000; color: #000000 !important; word-break: break-word; font-size: 8px; line-height: 1.2;">{{ $r['nama'] }}</td>
        <td style="padding: 4px 2px; text-align: center; border-right: 1.5px solid #000000; font-weight: bold; color: #000000 !important; font-size: 8px;">{{ $r['kelas'] }}</td>
        <td style="padding: 4px 2px; text-align: center; border-right: 1.5px solid #000000; color: #000000 !important; font-size: 8px;">{{ $r['B'] }}</td>
        <td style="padding: 4px 2px; text-align: center; border-right: 1.5px solid #000000; color: #000000 !important; font-size: 8px;">{{ $r['K'] }}</td>
        <td style="padding: 4px 2px; text-align: center; font-weight: bold; color: #000000 !important; font-size: 8px;">{{ $r['selisih'] > 0 ? '+'.$r['selisih'] : $r['selisih'] }}</td>
    </tr>
@endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                    </div>
                </div>

            </div>

            <!-- FOOTER SIGNATURE SECTION -->
            <div style="display: flex; justify-content: flex-end; margin-top: 40px; padding-right: 40px; font-size: 11px; font-family: Arial, sans-serif; color: #000000 !important;">
                <div style="text-align: center; width: 250px; color: #000000 !important;">
                    <p style="font-weight: bold; margin: 0; color: #000000 !important;">WALI KOTA PEKALONGAN,</p>
                    <p style="font-size: 9px; color: #555555 !important; margin-top: 20px;">TTD</p>
                    <p style="font-size: 9px; color: #555555 !important; margin-bottom: 25px;">STEMPEL</p>
                    <p style="font-weight: bold; text-decoration: underline; text-transform: uppercase; margin: 0; color: #000000 !important;">ACHMAD AFZAN ARSLAN DJUNAID</p>
                </div>
            </div>

        </div>

    </div>
</div>

<style>
@page {
    size: A4 landscape;
    margin: 0;
}
@media print {
    html, body {
        width: 100% !important;
        height: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        background: #ffffff !important;
        color: #000000 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        overflow: hidden !important;
    }
    
    /* Sembunyikan elemen navigasi & layout bawaan admin */
    #sidebar, header, nav, footer, .print\:hidden, aside {
        display: none !important;
    }
    
    main, .space-y-4, .overflow-x-auto {
        padding: 0 !important;
        margin: 0 !important;
        border: none !important;
        background: transparent !important;
        overflow: visible !important;
        width: 100% !important;
        height: 100% !important;
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
    }
    
    /* Canvas peta diskala presisi & diposisikan tepat di tengah kertas */
    #peta-canvas {
        width: 1240px !important;
        min-width: 1240px !important;
        max-width: 1240px !important;
        box-shadow: none !important;
        border: none !important;
        padding: 10px !important;
        margin: 0 auto !important;
        transform: scale(0.77);
        transform-origin: center center;
    }
}
</style>
@endsection
