@extends('layouts.admin')

@section('title', 'Peta Jabatan BAPPERIDA')

@section('content')
<div class="space-y-4">
    <!-- Top Action Header (Hidden in Print) -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 print:hidden px-2">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Peta Jabatan BAPPERIDA</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Lampiran IV Keputusan Wali Kota Pekalongan Nomor 000.8.2/0123 Tahun 2025</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.jabatans.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-slate-700 dark:text-gray-200 font-semibold rounded-lg shadow-sm transition-colors text-sm flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
            <button onclick="window.print()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-sm transition-colors text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Peta Jabatan (Landscape)
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
                    <h1 style="font-size: 15px; font-weight: bold; margin: 0; line-height: 1.4; color: #000000 !important; font-family: Arial, sans-serif; text-transform: uppercase;">
                        Peta Jabatan Badan Perencanaan Pembangunan, Riset, dan Inovasi Daerah<br>Kota Pekalongan
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
                
                <!-- LEVEL 1: KEPALA BADAN (Top box centered at X = 295px, padding-left: 135px -> box spans X = 135px to 455px) -->
                @php $dKepala = $getJData('Kepala Badan', 1, 1, 14); @endphp
                <div style="width: 100%; display: flex; flex-direction: column; align-items: flex-start; padding-left: 135px; box-sizing: border-box;">
                    <div style="width: 320px; background-color: #00a8e8 !important; color: #ffffff !important; border: 1.5px solid #000000; padding: 5px 8px; text-align: center; box-sizing: border-box;">
                        <div style="font-weight: bold; font-size: 10px; text-transform: uppercase; line-height: 1.2; color: #ffffff !important;">Kepala Badan Perencanaan Pembangunan, Riset, dan Inovasi Daerah</div>
                        <div style="font-size: 9px; margin-top: 2px; color: #ffffff !important;">Kelas : {{ $dKepala['kelas'] ?? 14 }}</div>
                    </div>
                </div>

                <!-- MIDDLE SECTION: MAIN VERTICAL STEM & BRANCHES (Height: 310px to eliminate any collision with bottom 4 Bidangs) -->
                <div style="position: relative; width: 100%; height: 310px;">
                    
                    <!-- Main Vertical Stem down from Kepala Badan center (X = 295px) all the way down to bottom 4 Bidangs (Y = 0 to Y = 310px) -->
                    <div style="position: absolute; left: 295px; top: 0; width: 2px; height: 310px; background-color: #000000 !important;"></div>

                    <!-- 1. SEKRETARIS BADAN BRANCH (Branch to the right at Y = 15px) -->
                    @php $dSekretaris = $getJData('Sekretaris Badan', 1, 1, 12); @endphp
                    <!-- Horizontal line from Stem (X = 295px) to Sekretaris Box (X = 430px) -> width 135px -->
                    <div style="position: absolute; left: 295px; top: 32px; width: 135px; height: 2px; background-color: #000000 !important;"></div>
                    <!-- Sekretaris Box (spans X = 430px to X = 760px) -->
                    <div style="position: absolute; left: 430px; top: 12px; width: 330px; background-color: #008040 !important; color: #ffffff !important; border: 1.5px solid #000000; padding: 5px 8px; text-align: center; box-sizing: border-box; z-index: 5;">
                        <div style="font-weight: bold; font-size: 10px; text-transform: uppercase; line-height: 1.2; color: #ffffff !important;">Sekretaris Badan Perencanaan Pembangunan, Riset, dan Inovasi Daerah</div>
                        <div style="font-size: 8.5px; margin-top: 2px; color: #ffffff !important;">Kelas : {{ $dSekretaris['kelas'] ?? 12 }}</div>
                    </div>

                    <!-- Vertical line down from Sekretaris center (X = 430 + 165 = 595px) down to Subbag bus bar (Y = 48px to Y = 68px) -->
                    <div style="position: absolute; left: 595px; top: 48px; width: 2px; height: 20px; background-color: #000000 !important;"></div>

                    <!-- Subbag Bus Bar (Horizontal line from Subbag 1 center X = 500px to Subbag 2 center X = 770px at Y = 68px) -->
                    <div style="position: absolute; left: 500px; top: 68px; width: 270px; height: 2px; background-color: #000000 !important;"></div>

                    <!-- Vertical stems down to Subbag 1 (X = 500px) & Subbag 2 (X = 770px) from Y = 68px to Y = 82px -->
                    <div style="position: absolute; left: 500px; top: 68px; width: 2px; height: 14px; background-color: #000000 !important;"></div>
                    <div style="position: absolute; left: 770px; top: 68px; width: 2px; height: 14px; background-color: #000000 !important;"></div>

                    <!-- 2 SUBBAGS SIDE-BY-SIDE (YELLOW BOXES - top Y = 82px) -->
                    @php
                        $dSubbag1 = $getJData('Umum dan Kepegawaian', 1, 1, 9);
                        $dSubbag2 = $getJData('Perencanaan Evaluasi', 1, 1, 9);
                    @endphp
                    <div style="position: absolute; left: 380px; top: 82px; width: 520px; display: flex; justify-content: space-between; align-items: flex-start; z-index: 5;">
                        
                        <!-- SUBBAG 1 (Umum & Kepegawaian, spans X = 380px to 620px) -->
                        <div style="width: 240px; border: 1px solid #000000; background-color: #ffffff !important; font-size: 8px; color: #000000 !important;">
                            <div style="background-color: #ffd700 !important; color: #000000 !important; font-weight: bold; text-align: center; padding: 4px 2px; border-bottom: 1px solid #000000; font-size: 9px; line-height: 1.2;">
                                <div>Kepala Sub Bagian Umum dan Kepegawaian</div>
                                <div style="font-size: 8px; margin-top: 1px; color: #000000 !important;">Kelas : {{ $dSubbag1['kelas'] ?? 9 }}</div>
                            </div>
                            <table style="width: 100%; border-collapse: collapse; color: #000000 !important; background-color: #ffffff !important;">
                                <thead>
                                    <tr style="border-bottom: 1px solid #000000; background-color: #e5e7eb !important; text-align: center; font-weight: bold; color: #000000 !important;">
                                        <th style="padding: 2px; text-align: left; border-right: 1px solid #000000; color: #000000 !important;">Jabatan</th>
                                        <th style="padding: 2px; width: 22px; border-right: 1px solid #000000; color: #000000 !important;">Kelas</th>
                                        <th style="padding: 2px; width: 14px; border-right: 1px solid #000000; color: #000000 !important;">B</th>
                                        <th style="padding: 2px; width: 14px; border-right: 1px solid #000000; color: #000000 !important;">K</th>
                                        <th style="padding: 2px; width: 20px; color: #000000 !important;">+ / -</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($getChildrenData('Umum dan Kepegawaian', [
                                        ['nama' => 'JF Arsiparis Pelaksana', 'b' => 0, 'k' => 1, 'kls' => 6],
                                        ['nama' => 'Penelaah Teknis Kebijakan', 'b' => 0, 'k' => 2, 'kls' => 7],
                                        ['nama' => 'Pengolah Data dan Informasi', 'b' => 1, 'k' => 1, 'kls' => 6],
                                        ['nama' => 'Pengadministrasi Perkantoran', 'b' => 1, 'k' => 1, 'kls' => 5],
                                    ]) as $row)
                                        @php $r = $getJData($row['nama'], $row['b'], $row['k'], $row['kls']); @endphp
                                        <tr style="border-bottom: 1px solid #000000; color: #000000 !important;">
                                            <td style="padding: 2px; border-right: 1px solid #000000; color: #000000 !important;">{{ $row['nama'] }}</td>
                                            <td style="text-align: center; border-right: 1px solid #000000; font-weight: bold; color: #000000 !important;">{{ $r['kelas'] }}</td>
                                            <td style="text-align: center; border-right: 1px solid #000000; color: #000000 !important;">{{ $r['B'] }}</td>
                                            <td style="text-align: center; border-right: 1px solid #000000; color: #000000 !important;">{{ $r['K'] }}</td>
                                            <td style="text-align: center; font-weight: bold; color: #000000 !important;">{{ $r['selisih'] > 0 ? '+'.$r['selisih'] : $r['selisih'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- SUBBAG 2 (Perencanaan Evaluasi dan Keuangan, spans X = 645px to 900px) -->
                        <div style="width: 255px; display: flex; flex-direction: column; align-items: center;">
                            <div style="width: 100%; border: 1px solid #000000; background-color: #ffffff !important; font-size: 8px; color: #000000 !important;">
                                <div style="background-color: #ffd700 !important; color: #000000 !important; font-weight: bold; text-align: center; padding: 4px 2px; border-bottom: 1px solid #000000; font-size: 9px; line-height: 1.2;">
                                    <div>Kepala Sub Bagian Perencanaan Evaluasi dan Keuangan</div>
                                    <div style="font-size: 8px; margin-top: 1px; color: #000000 !important;">Kelas : {{ $dSubbag2['kelas'] ?? 9 }}</div>
                                </div>
                                <table style="width: 100%; border-collapse: collapse; color: #000000 !important; background-color: #ffffff !important;">
                                    <thead>
                                        <tr style="border-bottom: 1px solid #000000; background-color: #e5e7eb !important; text-align: center; font-weight: bold; color: #000000 !important;">
                                            <th style="padding: 2px; text-align: left; border-right: 1px solid #000000; color: #000000 !important;">Jabatan</th>
                                            <th style="padding: 2px; width: 22px; border-right: 1px solid #000000; color: #000000 !important;">Kelas</th>
                                            <th style="padding: 2px; width: 14px; border-right: 1px solid #000000; color: #000000 !important;">B</th>
                                            <th style="padding: 2px; width: 14px; border-right: 1px solid #000000; color: #000000 !important;">K</th>
                                            <th style="padding: 2px; width: 20px; color: #000000 !important;">+ / -</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($getChildrenData('Perencanaan Evaluasi dan Keuangan', [
                                            ['nama' => 'JF Pranata Komputer Pertama', 'b' => 1, 'k' => 1, 'kls' => 8],
                                            ['nama' => 'Penelaah Teknis Kebijakan', 'b' => 0, 'k' => 2, 'kls' => 7],
                                            ['nama' => 'Pengolah Data dan Informasi', 'b' => 1, 'k' => 2, 'kls' => 6],
                                        ]) as $row)
                                            @php $r = $getJData($row['nama'], $row['b'], $row['k'], $row['kls']); @endphp
                                            <tr style="border-bottom: 1px solid #000000; color: #000000 !important;">
                                                <td style="padding: 2px; border-right: 1px solid #000000; color: #000000 !important;">{{ $row['nama'] }}</td>
                                                <td style="text-align: center; border-right: 1px solid #000000; font-weight: bold; color: #000000 !important;">{{ $r['kelas'] }}</td>
                                                <td style="text-align: center; border-right: 1px solid #000000; color: #000000 !important;">{{ $r['B'] }}</td>
                                                <td style="text-align: center; border-right: 1px solid #000000; color: #000000 !important;">{{ $r['K'] }}</td>
                                                <td style="text-align: center; font-weight: bold; color: #000000 !important;">{{ $r['selisih'] > 0 ? '+'.$r['selisih'] : $r['selisih'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Connected Box below Subbag 2 (JF Pranata Komputer Muda) -->
                            @php $dPrakomMuda = $getJData('JF Pranata Komputer Muda', 0, 1, 9); @endphp
                            <div style="width: 2px; height: 6px; background-color: #000000 !important;"></div>
                            <div style="width: 100%; border: 1px solid #000000; background-color: #ffffff !important; font-size: 8px; color: #000000 !important;">
                                <table style="width: 100%; border-collapse: collapse; color: #000000 !important; background-color: #ffffff !important;">
                                    <thead>
                                        <tr style="border-bottom: 1px solid #000000; background-color: #e5e7eb !important; text-align: center; font-weight: bold; color: #000000 !important;">
                                            <th style="padding: 2px; text-align: left; border-right: 1px solid #000000; color: #000000 !important;">Jabatan Fungsional</th>
                                            <th style="padding: 2px; width: 22px; border-right: 1px solid #000000; color: #000000 !important;">Kelas</th>
                                            <th style="padding: 2px; width: 14px; border-right: 1px solid #000000; color: #000000 !important;">B</th>
                                            <th style="padding: 2px; width: 14px; border-right: 1px solid #000000; color: #000000 !important;">K</th>
                                            <th style="padding: 2px; width: 20px; color: #000000 !important;">+ / -</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr style="text-align: center; color: #000000 !important;">
                                            <td style="padding: 2px; text-align: left; border-right: 1px solid #000000; font-weight: bold; color: #000000 !important;">JF Pranata Komputer Muda</td>
                                            <td style="padding: 2px; border-right: 1px solid #000000; font-weight: bold; color: #000000 !important;">{{ $dPrakomMuda['kelas'] }}</td>
                                            <td style="padding: 2px; border-right: 1px solid #000000; color: #000000 !important;">{{ $dPrakomMuda['B'] }}</td>
                                            <td style="padding: 2px; border-right: 1px solid #000000; color: #000000 !important;">{{ $dPrakomMuda['K'] }}</td>
                                            <td style="padding: 2px; font-weight: bold; color: #000000 !important;">{{ $dPrakomMuda['selisih'] > 0 ? '+'.$dPrakomMuda['selisih'] : $dPrakomMuda['selisih'] }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                    <!-- 2. JF PERENCANA AHLI MADYA (Branch to the left at Y = 82px) -->
                    @php $dPerencanaMadya = $getJData('JF Perencana Ahli Madya', 2, 2, 11); @endphp
                    <!-- Horizontal line from Table right side (X = 260px) to Main Stem (X = 295px) -> width 35px at Y = 98px -->
                    <div style="position: absolute; left: 260px; top: 98px; width: 35px; height: 2px; background-color: #000000 !important;"></div>
                    <!-- Table Box (spans X = 25px to X = 260px at top Y = 82px) -->
                    <div style="position: absolute; left: 25px; top: 82px; width: 235px; border: 1px solid #000000; background-color: #ffffff !important; font-size: 8px; color: #000000 !important; z-index: 5;">
                        <table style="width: 100%; border-collapse: collapse; color: #000000 !important; background-color: #ffffff !important;">
                            <thead>
                                <tr style="border-bottom: 1px solid #000000; background-color: #e5e7eb !important; text-align: center; font-weight: bold; color: #000000 !important;">
                                    <th style="padding: 2px; text-align: left; border-right: 1px solid #000000; color: #000000 !important;">Jabatan Fungsional</th>
                                    <th style="padding: 2px; width: 22px; border-right: 1px solid #000000; color: #000000 !important;">Kelas</th>
                                    <th style="padding: 2px; width: 14px; border-right: 1px solid #000000; color: #000000 !important;">B</th>
                                    <th style="padding: 2px; width: 14px; border-right: 1px solid #000000; color: #000000 !important;">K</th>
                                    <th style="padding: 2px; width: 20px; color: #000000 !important;">+ / -</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="text-align: center; color: #000000 !important;">
                                    <td style="padding: 2px; text-align: left; border-right: 1px solid #000000; font-weight: bold; color: #000000 !important;">JF Perencana Ahli Madya</td>
                                    <td style="padding: 2px; border-right: 1px solid #000000; font-weight: bold; color: #000000 !important;">{{ $dPerencanaMadya['kelas'] }}</td>
                                    <td style="padding: 2px; border-right: 1px solid #000000; color: #000000 !important;">{{ $dPerencanaMadya['B'] }}</td>
                                    <td style="padding: 2px; border-right: 1px solid #000000; color: #000000 !important;">{{ $dPerencanaMadya['K'] }}</td>
                                    <td style="padding: 2px; font-weight: bold; color: #000000 !important;">{{ $dPerencanaMadya['selisih'] > 0 ? '+'.$dPerencanaMadya['selisih'] : $dPerencanaMadya['selisih'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>

                <!-- BOTTOM LEVEL: 4 BIDANG HORIZONTAL BUS BAR & COLUMNS -->
                <div style="position: relative; width: 1200px; display: flex; flex-direction: column; align-items: flex-start; margin-top: 0px;">
                    
                    <!-- Main Horizontal Bus Bar spanning across all 4 Bidangs (From X = 86px to X = 1046px -> Width = 960px) -->
                    <div style="position: absolute; left: 86px; top: 0; width: 960px; height: 2px; background-color: #000000 !important;"></div>

                    <!-- 4 BIDANG COLUMNS SIDE-BY-SIDE (Container Width 1132px so center of 4 columns line up with bus bar) -->
                    <div style="width: 1132px; display: flex; justify-content: space-between; align-items: flex-start; margin-top: 0px;">
                            
                            <!-- BIDANG 1 (Center X = 86px) -->
                            @php $dBidang1 = $getJData('Pemerintahan dan Pembangunan Manusia', 1, 1, 11); @endphp
                            <div style="width: 172px; display: flex; flex-direction: column; align-items: center;">
                                <div style="width: 2px; height: 14px; background-color: #000000 !important;"></div>
                                <div style="width: 100%; border: 1px solid #000000; background-color: #ffffff !important; font-size: 8px; color: #000000 !important;">
                                    <div style="background-color: #4caf50 !important; color: #ffffff !important; font-weight: bold; text-align: center; padding: 4px 2px; border-bottom: 1px solid #000000; font-size: 8.5px; line-height: 1.2;">
                                        <div>Kepala Bidang Pemerintahan dan Pembangunan Manusia</div>
                                        <div style="font-size: 7.5px; margin-top: 1px; color: #ffffff !important;">Kelas : {{ $dBidang1['kelas'] ?? 11 }}</div>
                                    </div>
                                    <table style="width: 100%; border-collapse: collapse; color: #000000 !important; background-color: #ffffff !important;">
                                        <thead>
                                            <tr style="border-bottom: 1px solid #000000; background-color: #e5e7eb !important; text-align: center; font-weight: bold; color: #000000 !important;">
                                                <th style="padding: 1px 2px; text-align: left; border-right: 1px solid #000000; color: #000000 !important;">Jabatan</th>
                                                <th style="padding: 1px; width: 18px; border-right: 1px solid #000000; color: #000000 !important;">Kls</th>
                                                <th style="padding: 1px; width: 12px; border-right: 1px solid #000000; color: #000000 !important;">B</th>
                                                <th style="padding: 1px; width: 12px; border-right: 1px solid #000000; color: #000000 !important;">K</th>
                                                <th style="padding: 1px; width: 16px; color: #000000 !important;">+/-</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($getChildrenData('Pemerintahan dan Pembangunan Manusia', [
                                                ['nama' => 'JF Perencana Ahli Muda', 'b' => 1, 'k' => 2, 'kls' => 10],
                                                ['nama' => 'JF Perencana Ahli Pertama', 'b' => 0, 'k' => 1, 'kls' => 8],
                                                ['nama' => 'JF Pranata Komputer Muda', 'b' => 0, 'k' => 1, 'kls' => 9],
                                                ['nama' => 'JF Pranata Komputer Pertama', 'b' => 1, 'k' => 1, 'kls' => 8],
                                                ['nama' => 'Penelaah Teknis Kebijakan', 'b' => 1, 'k' => 2, 'kls' => 7],
                                                ['nama' => 'Pengolah Data dan Informasi', 'b' => 0, 'k' => 1, 'kls' => 6],
                                            ]) as $row)
                                                @php $r = $getJData($row['nama'], $row['b'], $row['k'], $row['kls']); @endphp
                                                <tr style="border-bottom: 1px solid #000000; color: #000000 !important;">
                                                    <td style="padding: 1px 2px; border-right: 1px solid #000000; color: #000000 !important;">{{ $row['nama'] }}</td>
                                                    <td style="text-align: center; border-right: 1px solid #000000; font-weight: bold; color: #000000 !important;">{{ $r['kelas'] }}</td>
                                                    <td style="text-align: center; border-right: 1px solid #000000; color: #000000 !important;">{{ $r['B'] }}</td>
                                                    <td style="text-align: center; border-right: 1px solid #000000; color: #000000 !important;">{{ $r['K'] }}</td>
                                                    <td style="text-align: center; font-weight: bold; color: #000000 !important;">{{ $r['selisih'] > 0 ? '+'.$r['selisih'] : $r['selisih'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- BIDANG 2 -->
                            @php $dBidang2 = $getJData('Perekonomian', 1, 1, 11); @endphp
                            <div style="width: 172px; display: flex; flex-direction: column; align-items: center;">
                                <div style="width: 2px; height: 14px; background-color: #000000 !important;"></div>
                                <div style="width: 100%; border: 1px solid #000000; background-color: #ffffff !important; font-size: 8px; color: #000000 !important;">
                                    <div style="background-color: #4caf50 !important; color: #ffffff !important; font-weight: bold; text-align: center; padding: 4px 2px; border-bottom: 1px solid #000000; font-size: 8.5px; line-height: 1.2;">
                                        <div>Kepala Bidang Perekonomian, SDA, Infrastruktur & Kewilayahan</div>
                                        <div style="font-size: 7.5px; margin-top: 1px; color: #ffffff !important;">Kelas : {{ $dBidang2['kelas'] ?? 11 }}</div>
                                    </div>
                                    <table style="width: 100%; border-collapse: collapse; color: #000000 !important; background-color: #ffffff !important;">
                                        <thead>
                                            <tr style="border-bottom: 1px solid #000000; background-color: #e5e7eb !important; text-align: center; font-weight: bold; color: #000000 !important;">
                                                <th style="padding: 1px 2px; text-align: left; border-right: 1px solid #000000; color: #000000 !important;">Jabatan</th>
                                                <th style="padding: 1px; width: 18px; border-right: 1px solid #000000; color: #000000 !important;">Kls</th>
                                                <th style="padding: 1px; width: 12px; border-right: 1px solid #000000; color: #000000 !important;">B</th>
                                                <th style="padding: 1px; width: 12px; border-right: 1px solid #000000; color: #000000 !important;">K</th>
                                                <th style="padding: 1px; width: 16px; color: #000000 !important;">+/-</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($getChildrenData('Perekonomian', [
                                                ['nama' => 'JF Perencana Ahli Muda', 'b' => 2, 'k' => 2, 'kls' => 10],
                                                ['nama' => 'JF Perencana Ahli Pertama', 'b' => 1, 'k' => 2, 'kls' => 8],
                                                ['nama' => 'JF Pranata Komputer Muda', 'b' => 0, 'k' => 1, 'kls' => 9],
                                                ['nama' => 'JF Pranata Komputer Pertama', 'b' => 0, 'k' => 1, 'kls' => 8],
                                                ['nama' => 'Penelaah Teknis Kebijakan', 'b' => 2, 'k' => 2, 'kls' => 7],
                                                ['nama' => 'Pengolah Data dan Informasi', 'b' => 0, 'k' => 1, 'kls' => 6],
                                            ]) as $row)
                                                @php $r = $getJData($row['nama'], $row['b'], $row['k'], $row['kls']); @endphp
                                                <tr style="border-bottom: 1px solid #000000; color: #000000 !important;">
                                                    <td style="padding: 1px 2px; border-right: 1px solid #000000; color: #000000 !important;">{{ $row['nama'] }}</td>
                                                    <td style="text-align: center; border-right: 1px solid #000000; font-weight: bold; color: #000000 !important;">{{ $r['kelas'] }}</td>
                                                    <td style="text-align: center; border-right: 1px solid #000000; color: #000000 !important;">{{ $r['B'] }}</td>
                                                    <td style="text-align: center; border-right: 1px solid #000000; color: #000000 !important;">{{ $r['K'] }}</td>
                                                    <td style="text-align: center; font-weight: bold; color: #000000 !important;">{{ $r['selisih'] > 0 ? '+'.$r['selisih'] : $r['selisih'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- BIDANG 3 -->
                            @php $dBidang3 = $getJData('Pengendalian dan Evaluasi', 1, 1, 11); @endphp
                            <div style="width: 172px; display: flex; flex-direction: column; align-items: center;">
                                <div style="width: 2px; height: 14px; background-color: #000000 !important;"></div>
                                <div style="width: 100%; border: 1px solid #000000; background-color: #ffffff !important; font-size: 8px; color: #000000 !important;">
                                    <div style="background-color: #4caf50 !important; color: #ffffff !important; font-weight: bold; text-align: center; padding: 4px 2px; border-bottom: 1px solid #000000; font-size: 8.5px; line-height: 1.2;">
                                        <div>Kepala Bidang Perencanaan, Pengendalian & Evaluasi</div>
                                        <div style="font-size: 7.5px; margin-top: 1px; color: #ffffff !important;">Kelas : {{ $dBidang3['kelas'] ?? 11 }}</div>
                                    </div>
                                    <table style="width: 100%; border-collapse: collapse; color: #000000 !important; background-color: #ffffff !important;">
                                        <thead>
                                            <tr style="border-bottom: 1px solid #000000; background-color: #e5e7eb !important; text-align: center; font-weight: bold; color: #000000 !important;">
                                                <th style="padding: 1px 2px; text-align: left; border-right: 1px solid #000000; color: #000000 !important;">Jabatan</th>
                                                <th style="padding: 1px; width: 18px; border-right: 1px solid #000000; color: #000000 !important;">Kls</th>
                                                <th style="padding: 1px; width: 12px; border-right: 1px solid #000000; color: #000000 !important;">B</th>
                                                <th style="padding: 1px; width: 12px; border-right: 1px solid #000000; color: #000000 !important;">K</th>
                                                <th style="padding: 1px; width: 16px; color: #000000 !important;">+/-</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($getChildrenData('Pengendalian dan Evaluasi', [
                                                ['nama' => 'JF Perencana Ahli Muda', 'b' => 0, 'k' => 1, 'kls' => 10],
                                                ['nama' => 'JF Perencana Ahli Pertama', 'b' => 1, 'k' => 1, 'kls' => 8],
                                                ['nama' => 'JF Pranata Komputer Ahli Muda', 'b' => 0, 'k' => 1, 'kls' => 9],
                                                ['nama' => 'JF Pranata Komputer Ahli Pertama', 'b' => 2, 'k' => 2, 'kls' => 8],
                                                ['nama' => 'Penelaah Teknis Kebijakan', 'b' => 2, 'k' => 2, 'kls' => 7],
                                                ['nama' => 'Pengolah Data dan Informasi', 'b' => 1, 'k' => 1, 'kls' => 6],
                                            ]) as $row)
                                                @php $r = $getJData($row['nama'], $row['b'], $row['k'], $row['kls']); @endphp
                                                <tr style="border-bottom: 1px solid #000000; color: #000000 !important;">
                                                    <td style="padding: 1px 2px; border-right: 1px solid #000000; color: #000000 !important;">{{ $row['nama'] }}</td>
                                                    <td style="text-align: center; border-right: 1px solid #000000; font-weight: bold; color: #000000 !important;">{{ $r['kelas'] }}</td>
                                                    <td style="text-align: center; border-right: 1px solid #000000; color: #000000 !important;">{{ $r['B'] }}</td>
                                                    <td style="text-align: center; border-right: 1px solid #000000; color: #000000 !important;">{{ $r['K'] }}</td>
                                                    <td style="text-align: center; font-weight: bold; color: #000000 !important;">{{ $r['selisih'] > 0 ? '+'.$r['selisih'] : $r['selisih'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- BIDANG 4 -->
                            @php $dBidang4 = $getJData('Riset dan Inovasi', 1, 1, 11); @endphp
                            <div style="width: 172px; display: flex; flex-direction: column; align-items: center;">
                                <div style="width: 2px; height: 14px; background-color: #000000 !important;"></div>
                                <div style="width: 100%; border: 1px solid #000000; background-color: #ffffff !important; font-size: 8px; color: #000000 !important;">
                                    <div style="background-color: #4caf50 !important; color: #ffffff !important; font-weight: bold; text-align: center; padding: 4px 2px; border-bottom: 1px solid #000000; font-size: 8.5px; line-height: 1.2;">
                                        <div>Kepala Bidang Riset dan Inovasi Daerah</div>
                                        <div style="font-size: 7.5px; margin-top: 1px; color: #ffffff !important;">Kelas : {{ $dBidang4['kelas'] ?? 11 }}</div>
                                    </div>
                                    <table style="width: 100%; border-collapse: collapse; color: #000000 !important; background-color: #ffffff !important;">
                                        <thead>
                                            <tr style="border-bottom: 1px solid #000000; background-color: #e5e7eb !important; text-align: center; font-weight: bold; color: #000000 !important;">
                                                <th style="padding: 1px 2px; text-align: left; border-right: 1px solid #000000; color: #000000 !important;">Jabatan</th>
                                                <th style="padding: 1px; width: 18px; border-right: 1px solid #000000; color: #000000 !important;">Kls</th>
                                                <th style="padding: 1px; width: 12px; border-right: 1px solid #000000; color: #000000 !important;">B</th>
                                                <th style="padding: 1px; width: 12px; border-right: 1px solid #000000; color: #000000 !important;">K</th>
                                                <th style="padding: 1px; width: 16px; color: #000000 !important;">+/-</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($getChildrenData('Riset dan Inovasi', [
                                                ['nama' => 'JF Peneliti Ahli Muda', 'b' => 0, 'k' => 2, 'kls' => 9],
                                                ['nama' => 'JF Peneliti Ahli Pertama', 'b' => 2, 'k' => 2, 'kls' => 8],
                                                ['nama' => 'JF Pranata Komputer Ahli Muda', 'b' => 0, 'k' => 1, 'kls' => 9],
                                                ['nama' => 'JF Pranata Komputer Ahli Pertama', 'b' => 1, 'k' => 1, 'kls' => 8],
                                                ['nama' => 'JF Analis Data Ilmiah Ahli Pertama', 'b' => 0, 'k' => 1, 'kls' => 8],
                                                ['nama' => 'JF Penata Penerbitan Ilmiah Ahli Pertama', 'b' => 0, 'k' => 1, 'kls' => 8],
                                                ['nama' => 'Pengolah Data dan Informasi', 'b' => 0, 'k' => 1, 'kls' => 6],
                                            ]) as $row)
                                                @php $r = $getJData($row['nama'], $row['b'], $row['k'], $row['kls']); @endphp
                                                <tr style="border-bottom: 1px solid #000000; color: #000000 !important;">
                                                    <td style="padding: 1px 2px; border-right: 1px solid #000000; color: #000000 !important;">{{ $row['nama'] }}</td>
                                                    <td style="text-align: center; border-right: 1px solid #000000; font-weight: bold; color: #000000 !important;">{{ $r['kelas'] }}</td>
                                                    <td style="text-align: center; border-right: 1px solid #000000; color: #000000 !important;">{{ $r['B'] }}</td>
                                                    <td style="text-align: center; border-right: 1px solid #000000; color: #000000 !important;">{{ $r['K'] }}</td>
                                                    <td style="text-align: center; font-weight: bold; color: #000000 !important;">{{ $r['selisih'] > 0 ? '+'.$r['selisih'] : $r['selisih'] }}</td>
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
    margin: 4mm;
}
@media print {
    body {
        background: white !important;
        color: black !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    #sidebar, header, nav, .print\:hidden {
        display: none !important;
    }
    main {
        padding: 0 !important;
        margin: 0 !important;
    }
    #peta-canvas {
        box-shadow: none !important;
        border: none !important;
        width: 100% !important;
        padding: 0 !important;
    }
}
</style>
@endsection
