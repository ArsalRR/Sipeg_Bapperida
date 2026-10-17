<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Laporan Data Pegawai Bapperida</title>
@php
    $jumlahKolom = count($headers);
    $fontSize    = $jumlahKolom > 11 ? '7px' : ($jumlahKolom > 8 ? '8px' : '9px');
    $centered    = ['bidang', 'status', 'status_kerja', 'jk', 'pernikahan'];
    $nowrap      = ['nip', 'nik', 'mkg'];
@endphp
<style>
    @page { margin: 28px 30px 42px 30px; }
    body { font-family: DejaVu Sans, sans-serif; font-size: {{ $fontSize }}; color: #111111; }
    h1 { font-size: 14px; margin: 0 0 2px 0; }
    .sub { font-size: 8.5px; color: #555555; margin: 0 0 10px 0; }
    table { width: 100%; border-collapse: collapse; }
    thead { display: table-header-group; }
    tr { page-break-inside: avoid; }
    th, td { border: 1px solid #999999; padding: 4px 5px; text-align: left; vertical-align: top; }
    th { background-color: #e5e7eb; color: #111111; font-weight: bold; }
    tbody tr:nth-child(even) td { background-color: #f6f6f6; }
    .c { text-align: center; }
    .nw { white-space: nowrap; }
    .footer { position: fixed; bottom: -26px; left: 0; right: 0; text-align: right; font-size: 8px; color: #777777; }
    .pagenum:before { content: counter(page); }
</style>
</head>
<body>
    <div class="footer">Halaman <span class="pagenum"></span></div>

    <h1>Laporan Data Pegawai Bapperida Kota Pekalongan</h1>
    <p class="sub">
        Dicetak pada: {{ $tanggalCetak }} | Total: {{ $rows->count() }} pegawai
        @if($search !== '') | Filter: "{{ $search }}" @endif
    </p>

    <table>
        <thead>
            <tr>
                <th class="c" style="width: 22px;">No</th>
                @foreach($headers as $key => $label)
                    <th class="{{ in_array($key, $centered, true) ? 'c' : '' }}">{{ $label }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $i => $row)
                <tr>
                    <td class="c">{{ $i + 1 }}</td>
                    @foreach($headers as $key => $label)
                        <td class="{{ in_array($key, $centered, true) ? 'c' : '' }} {{ in_array($key, $nowrap, true) ? 'nw' : '' }}">{{ $row[$key] }}</td>
                    @endforeach
                </tr>
            @empty
                <tr><td colspan="{{ $jumlahKolom + 1 }}" class="c">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>