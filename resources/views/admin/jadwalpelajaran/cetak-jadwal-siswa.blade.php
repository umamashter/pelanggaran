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
        $headerMadrasah = strtoupper(optional($profil)->nama_madrasah ?: 'NURUL ULUM');
        $headerAlamat = optional($profil)->alamat ?: 'Patapan, Guluk-Guluk, Sumenep';
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
        .no-print-screen {
            display: flex; align-items: center; justify-content: space-between; gap: 10px; flex-wrap: wrap;
            margin-bottom: 14px; padding: 12px 16px; border-radius: 14px; background: #0f172a; color: #fff;
            box-shadow: 0 12px 28px -12px rgba(15,23,42,.55);
        }
        .no-print-screen .title { font-size: 14px; font-weight: 700; display: inline-flex; align-items: center; gap: 8px; }
        .no-print-screen .actions { display: flex; gap: 8px; flex-wrap: wrap; }
        .no-print-screen .btn {
            display: inline-flex; align-items: center; gap: 6px; border: none; border-radius: 10px;
            font-size: 12.5px; font-weight: 600; padding: 9px 16px; cursor: pointer; text-decoration: none;
        }
        .no-print-screen .btn-primary { background: #2563eb; color: #fff; }
        .no-print-screen .btn-primary:hover { background: #1d4ed8; }
        .no-print-screen .btn-ghost { background: rgba(255,255,255,.12); color: #fff; }
        .no-print-screen .btn-ghost:hover { background: rgba(255,255,255,.2); }
        @media print { .no-print-screen { display: none !important; } }
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

        <div class="no-print-screen">
            <div class="title">
                <i class="fas fa-calendar-alt"></i> Cetak Jadwal Mata Pelajaran
                <span style="font-size:11px;opacity:.75;font-weight:500;">&mdash; {{ $jenjangLabel }} &middot; {{ $tahunAjaran->tahun_ajaran }}</span>
            </div>
            <div class="actions">
                <button class="btn btn-primary" onclick="window.print()">
                    <i class="fas fa-print"></i> Cetak / PDF
                </button>
                <a href="{{ route('jadwal-pelajaran.index') }}" class="btn btn-ghost">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        @foreach($chunks as $chunkIdx => $chunkKelas)
        <div class="{{ !$loop->last ? 'page-section' : '' }}">
            <div class="page-wrap">

                <div class="header-title">JADWAL MATA PELAJARAN</div>
                <div class="header-sub"><strong>{{ $jenjangLabel }}</strong> {{ $headerMadrasah }}</div>
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
                        <div class="tanggal">Guluk-Guluk, {{ tanggal_indonesia(optional($semesterGanjil)->tanggal_mulai ? $semesterGanjil->tanggal_mulai->format('Y-m-d') : now()->format('Y-m-d'), false) }}</div>
                        <div style="margin-top:2px;">Kepala {{ $jenjangLabel }} {{ $headerMadrasah }},</div>
                        <div style="height:48px;"></div>
                        @php
                            $kepalaTtd = optional($profil)->nama_kepala_sekolah;
                            if (!$kepalaTtd) {
                                $kepalaTtd = $jenjangLabel === 'MI' ? 'Ach. Fathorrosi, S.Pd.I'
                                    : ($jenjangLabel === 'MTs' ? 'Nasir, S.Pd.I'
                                    : ($jenjangLabel === 'MA' ? 'Minhaji, S.Kom' : ''));
                            }
                        @endphp
                        @if($kepalaTtd)
                        <div>{{ $kepalaTtd }}</div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
        @endforeach

    </div>

</body>
</html>
