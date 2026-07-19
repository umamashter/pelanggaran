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
            size: 210mm 330mm portrait;
            margin: 4mm 4mm 4mm 4mm;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            height: 100%;
            font-family: 'Times New Roman', Times, serif;
            line-height: 1.1;
            color: #000;
        }

        .page-wrap {
            display: flex;
            flex-direction: column;
            height: 285mm;
        }

        /* ===== HEADER ===== */
        .header-title {
            font-size: 14pt;
            font-weight: bold;
            text-align: center;
            letter-spacing: 2px;
            margin-bottom: 0;
        }

        .header-sub {
            font-size: 12pt;
            font-weight: bold;
            text-align: center;
            margin-bottom: 0;
        }

        .header-tahun {
            font-size: 11pt;
            font-weight: bold;
            text-align: center;
            margin-bottom: 4px;
        }

        /* ===== TABEL: SATU ATURAN GLOBAL 10pt ===== */
        table.utama {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            flex: 1;
        }

        table.utama th,
        table.utama td {
            border: 1px solid #000 !important;
            font-size: 10pt !important;
            line-height: 1.1;
        }

        table.utama th {
            background: #fff !important;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
            padding: 1px 1px;
        }

        table.utama td {
            padding: 1px;
            vertical-align: middle;
            text-align: center;
        }

        table.utama thead tr:first-child th {
            height: 10mm;
        }

        table.utama thead tr:nth-child(2) th {
            height: 4mm;
        }

        table.utama tbody tr {
            height: 5mm;
        }

        table.utama tbody tr.row-istirahat {
            height: 5mm;
            letter-spacing: 2px;
        }

        table.utama small {
            font-size: 10pt !important;
        }

        /* ===== BOTTOM ROW: KD (kiri) + TTD (kanan) ===== */
        .bottom-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 2px;
            flex-shrink: 0;
        }

        /* ===== LEGENDA KD (kiri) ===== */
        .legenda-kd {
            width: 70%;
            padding: 2px 4px;
        }

        .legenda-kd-title {
            font-size: 10pt;
            font-weight: bold;
            margin-bottom: 0;
        }

        .legenda-kd-grid {
            column-count: 2;
            column-gap: 4px;
            font-size: 10pt;
            line-height: 1.1;
        }

        .legenda-kd-item {
            break-inside: avoid;
            display: flex;
            gap: 2px;
            margin-bottom: 0;
        }

        .legenda-kd-item .kode {
            width: 14px;
            flex-shrink: 0;
            text-align: right;
            font-weight: 600;
        }

        .legenda-kd-item .nama {
            flex: 1;
        }

        /* ===== TTD (kanan) ===== */
        .ttd-area {
            width: 28%;
            text-align: right;
            font-size: 10pt;
            line-height: 1.3;
            padding-right: 2px;
        }

        .ttd-area .tanggal {
            white-space: nowrap;
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

                <table class="utama">
                    <colgroup>
                        <col style="width:9%">
                        <col style="width:3%">
                        <col style="width:12%">
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
                            <td class="cell-jam fw-bold">{{ $jamKe }}</td>
                            <td class="cell-waktu nowrap">{{ $jamSlot[$jamKe]['mulai'] }} - {{ $jamSlot[$jamKe]['selesai'] }}</td>
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

                <div class="bottom-row">
                    <div class="legenda-kd">
                        <div class="legenda-kd-title">KD</div>
                        <div class="legenda-kd-grid">
                            @foreach($guruAlfa as $g)
                            <div class="legenda-kd-item">
                                <span class="kode">{{ $guruKodeMap[$g->id] }}</span>
                                <span class="nama">= {{ $g->nama }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="ttd-area">
                        <div class="tanggal">Guluk-Guluk, {{ tanggal_indonesia(\Carbon\Carbon::now()->format('Y-m-d')) }}</div>
                        <div style="margin-top:2px;">Kepala {{ $jenjangLabel }} Nurul Ulum,</div>
                        <div style="height:48px;"></div>
                        @if($jenjangLabel === 'MI')
                        <div>Ach. Fathorrosi, S.Pd.I</div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
        @endforeach

    </div>

</body>
</html>
