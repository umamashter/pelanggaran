<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
@php
        $jenjangSingkat = ['Madrasah Ibtidaiyah' => 'MI', 'Madrasah Tsanawiyah' => 'MTs', 'Madrasah Aliyah' => 'MA'];
        $jenjangNama = optional($jenjangCetak)->nama_jenjang;
        $jenjangLabel = $jenjangNama ? ($jenjangSingkat[$jenjangNama] ?? strtoupper($jenjangNama)) : 'SEMUA JENJANG';
        $maxPerChunk = 3;
        $chunks = $kelasList->chunk($maxPerChunk);

        $totalHari = count($hariUrut);
        $totalJam = count($jamSlot);
        $rowsPerDay = $totalJam + 1;
        $totalBodyRows = $totalHari * $rowsPerDay;
    @endphp
    <style>
        @page {
            size: A4 portrait;
            margin: 5mm 4mm 5mm 5mm;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            height: 100%;
            font-family: 'Times New Roman', Times, serif;
            font-size: 9px;
            line-height: 1.2;
            color: #000;
        }

        .page-wrap {
            display: flex;
            flex-direction: column;
            height: 277mm;
        }

        .header-title {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            letter-spacing: 2px;
            margin-bottom: 1px;
        }

        .header-sub {
            font-size: 11px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 1px;
        }

        .header-tahun {
            font-size: 10px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 3px;
        }

        .bagian-label {
            text-align: center;
            font-size: 9px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        table.utama {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            flex: 1;
        }

        table.utama,
        table.utama th,
        table.utama td {
            border: 1px solid #000 !important;
        }

        table.utama thead tr:first-child th {
            height: 14mm;
        }

        table.utama thead tr:nth-child(2) th {
            height: 6mm;
        }

        table.utama tbody tr {
            height: calc((277mm - 14mm - 6mm - {{ $totalBodyRows }}mm) / {{ $totalBodyRows }});
        }

        table.utama tbody tr.row-istirahat {
            height: 6mm;
        }

        table.utama th {
            background: #fff !important;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
            padding: 2px 1px;
            font-size: 9px;
        }

        table.utama td {
            padding: 1px;
            vertical-align: middle;
            text-align: center;
            font-size: 8px;
        }

        .cell-mapel {
            font-weight: 600;
            font-size: 13px;
            line-height: 1.2;
        }

        .cell-hari {
            font-weight: 600;
            font-size: 10px;
            text-transform: uppercase;
            text-align: center;
            vertical-align: middle;
            padding: 2px 3px;
        }

        .cell-guru {
            font-size: 7px;
            font-weight: bold;
            color: #333;
            text-align: center;
            vertical-align: middle;
            padding: 1px 0 !important;
        }

        .th-mapel {
            font-size: 8px;
        }

        .th-kd {
            font-size: 6px;
            white-space: nowrap;
        }

        .row-istirahat td {
            background: #f0f0f0 !important;
            font-weight: bold;
            font-size: 9px;
            letter-spacing: 2px;
        }

        .footer-area {
            flex-shrink: 0;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .footer-left {
            flex: 1;
            min-width: 0;
        }

        .legenda-wrap {
            column-count: 4;
            column-gap: 12px;
            font-size: 8px;
            line-height: 1.6;
        }

        .legenda-title {
            font-weight: bold;
            font-size: 8px;
            margin-bottom: 2px;
            column-span: all;
        }

        .legenda-item {
            break-inside: avoid;
            display: flex;
            gap: 4px;
            margin-bottom: 1px;
        }

        .legenda-item .kode {
            width: 16px;
            flex-shrink: 0;
            text-align: right;
            font-weight: 600;
        }

        .legenda-item .nama {
            flex: 1;
        }

        .footer-right {
            flex-shrink: 0;
            text-align: right;
            font-size: 9px;
            line-height: 1.4;
        }

        .footer-right .nama-guru {
            font-weight: bold;
            text-decoration: underline;
        }

        .nowrap { white-space: nowrap; }

        .page-section {
            page-break-after: always;
        }

        .page-section:last-of-type {
            page-break-after: auto;
        }

        @media print {
            .no-print { display: none !important; }
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            table.utama { page-break-inside: auto; }
            tr { page-break-inside: avoid; page-break-after: auto; }
        }
    </style>
</head>
<body>

    <div class="container-fluid p-0">

        <div class="no-print text-end mb-1">
            <button class="btn btn-success btn-sm" onclick="window.print()">
                <i class="fas fa-print"></i> Cetak / PDF
            </button>
            <a href="{{ route('jadwal-siswa') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        @foreach($chunks as $chunkIdx => $chunkKelas)
        <div class="{{ !$loop->last ? 'page-section' : '' }}">
            <div class="page-wrap">

                <div class="header-title">JADWAL MATA PELAJARAN</div>
                <div class="header-sub"><strong>{{ $jenjangLabel }}</strong> NURUL ULUM PATAPAN GULUK GULUK SUMENEP</div>
                <div class="header-tahun">TAHUN PELAJARAN {{ $tahunAjaran->tahun_ajaran }}</div>
                @if($chunks->count() > 1)
                <div class="bagian-label">Bagian {{ $chunkIdx + 1 }} dari {{ $chunks->count() }}</div>
                @endif

                <table class="utama">
                    <colgroup>
                        <col style="width:9%">
                        <col style="width:3%">
                        <col style="width:7%">
                        @foreach($chunkKelas as $kelas)
                        <col>
                        <col style="width:40px">
                        @endforeach
                    </colgroup>
                    <thead>
                        <tr>
                            <th rowspan="2">Hari</th>
                            <th rowspan="2">Jam</th>
                            <th rowspan="2">Waktu</th>
                            @foreach($chunkKelas as $kelas)
                            <th colspan="2">
                                KELAS {{ angka_romawi((int) $kelas->nama_kelas) }}
                                @if($kelas->jenjang)
                                <br><small>{{ $kelas->jenjang->nama_jenjang }}</small>
                                @endif
                            </th>
                            @endforeach
                        </tr>
                        <tr>
                            @foreach($chunkKelas as $kelas)
                            <th class="th-mapel">Mata Pelajaran</th>
                            <th class="th-kd">KD</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($hariUrut as $hari)
                        @php
                        $jamKeys = array_keys($jamSlot);
                        @endphp
                        @foreach($jamKeys as $idx => $jamKe)
                        <tr>
                            @if($idx == 0)
                            <td rowspan="{{ count($jamSlot) + 1 }}" class="cell-hari">{{ $hari }}</td>
                            @endif
                            <td class="fw-bold">{{ $jamKe }}</td>
                            <td class="nowrap">{{ $jamSlot[$jamKe]['mulai'] }} - {{ $jamSlot[$jamKe]['selesai'] }}</td>
                            @foreach($chunkKelas as $kelas)
                            @php
                            $cell = $matrix[$hari][$jamKe][$kelas->id] ?? null;
                            @endphp
                            <td class="cell-mapel">{{ $cell['mapel'] ?? '' }}</td>
                            <td class="cell-guru">{{ $cell['kode_guru'] ?? '' }}</td>
                            @endforeach
                        </tr>
                        @if($jamKe == 2)
                        <tr class="row-istirahat">
                            <td class="fw-bold">-</td>
                            <td>09:30-10:00</td>
                            <td colspan="{{ $chunkKelas->count() * 2 }}" class="text-center fw-bold">
                                I S T I R A H A T
                            </td>
                        </tr>
                        @endif
                        @endforeach
                        @endforeach
                    </tbody>
                </table>

                <div class="footer-area">
                    <div class="footer-left">
                        <div class="legenda-wrap">
                            <div class="legenda-title">KD</div>
                            @foreach($guruAlfa as $g)
                            <div class="legenda-item">
                                <span class="kode">{{ $guruKodeMap[$g->id] }}</span>
                                <span class="nama">= {{ $g->nama }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="footer-right">
                        <div>Guluk-Guluk, {{ tanggal_indonesia(\Carbon\Carbon::now()->format('Y-m-d')) }}</div>
                        <div style="margin-top:2px;">Kepala {{ $jenjangLabel }} Nurul Ulum,</div>
                        <div style="height:30px;"></div>
                        <div class="nama-guru">___________________________</div>
                    </div>
                </div>

            </div>
        </div>
        @endforeach

    </div>

</body>
</html>
