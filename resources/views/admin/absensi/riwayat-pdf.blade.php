<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Absensi {{ $kelas->nama_kelas }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Arial', sans-serif; font-size: 9px; color: #1e293b; }
        .header { text-align: center; margin-bottom: 12px; border-bottom: 2px solid #1e293b; padding-bottom: 8px; }
        .header h4 { font-size: 13px; font-weight: 700; margin-bottom: 2px; }
        .header p { font-size: 10px; color: #475569; margin: 0; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #94a3b8; padding: 4px 3px; text-align: center; font-size: 8px; }
        th { background: #e2e8f0; font-weight: 700; font-size: 8px; }
        th.group-header { background: #cbd5e1; }
        td { font-size: 8px; }
        .status-H { background: #dcfce7; color: #16a34a; font-weight: 700; }
        .status-I { background: #fef3c7; color: #d97706; font-weight: 700; }
        .status-S { background: #fee2e2; color: #dc2626; font-weight: 700; }
        .status-A { background: #e2e8f0; color: #64748b; font-weight: 700; }
        .status-null { color: #cbd5e1; }
        .status-libur { background: #f1f5f9; color: #94a3b8; font-weight: 700; font-size: 7px; }
        .rekap-A { color: #64748b; font-weight: 700; }
        .rekap-I { color: #d97706; font-weight: 700; }
        .rekap-S { color: #dc2626; font-weight: 700; }
        .text-left { text-align: left; }
        .text-bold { font-weight: 700; }
        tfoot td { background: #f1f5f9; font-weight: 700; border-top: 2px solid #1e293b; }
        .footer { margin-top: 16px; font-size: 9px; color: #475569; }
        .signature-section {
            width: 100%; margin-top: 30px; border-collapse: collapse;
        }
        .signature-section td { vertical-align: top; width: 50%; padding: 0 10px; }
        .sig-date {
            font-size: 9px; color: #475569; margin-bottom: 4px;
        }
        .sig-label {
            font-size: 9px; color: #475569; line-height: 1.6;
        }
        .sig-space { height: 50px; }
        .sig-line {
            width: 160px; border-bottom: 1px solid #1e293b; margin: 0 0 6px;
        }
        .sig-name {
            font-size: 10px; font-weight: 700; color: #1e293b;
        }
    </style>
</head>
<body>
    <div class="header">
        <h4>ABSENSI SISWA MI NURUL ULUM</h4>
        <p>KELAS {{ strtoupper($kelas->nama_kelas) }} &middot; BULAN {{ strtoupper($bulanLabel) }} &middot; TAHUN AJARAN {{ $tahunAktif->tahun_ajaran }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width:22px;">No</th>
                <th rowspan="2" style="width:50px;">NIS</th>
                <th rowspan="2" style="width:90px;" class="text-left">Nama Siswa</th>
                <th class="group-header" colspan="{{ $hariDalamBulan }}">Tanggal</th>
                <th class="group-header" colspan="3">Tidak Masuk</th>
            </tr>
            <tr>
                @for($d = 1; $d <= $hariDalamBulan; $d++)
                @php $pdfTgl = $tanggalAwal->copy()->day($d); @endphp
                <th style="width:18px;background:{{ $pdfTgl->isFriday() ? '#f1f5f9' : '#e2e8f0' }};color:{{ $pdfTgl->isFriday() ? '#94a3b8' : '#1e293b' }};">{{ $d }}</th>
                @endfor
                <th style="width:18px;">A</th>
                <th style="width:18px;">I</th>
                <th style="width:18px;">S</th>
            </tr>
        </thead>
        <tbody>
            @forelse($siswas as $siswa)
            @php
                $data = $matrixData[$siswa->id] ?? [];
                $rekap = $data['_rekap'] ?? ['A' => 0, 'I' => 0, 'S' => 0];
            @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $siswa->nisn }}</td>
                <td class="text-left text-bold">{{ $siswa->nama }}</td>
                @for($d = 1; $d <= $hariDalamBulan; $d++)
                @php
                    $tgl = $tanggalAwal->copy()->day($d)->format('Y-m-d');
                    $pdfIsFriday = isset($fridaySet[$tgl]);
                    $status = $pdfIsFriday ? null : ($data[$tgl] ?? null);
                @endphp
                <td class="{{ $pdfIsFriday ? 'status-libur' : ($status ? 'status-'.$status : 'status-null') }}">{{ $pdfIsFriday ? 'LIBUR' : ($status ?? '-') }}</td>
                @endfor
                <td class="rekap-A">{{ $rekap['A'] }}</td>
                <td class="rekap-I">{{ $rekap['I'] }}</td>
                <td class="rekap-S">{{ $rekap['S'] }}</td>

            </tr>
            @empty
            <tr>
                <td colspan="{{ 3 + $hariDalamBulan + 3 }}" style="text-align:center;padding:20px;">Tidak ada data siswa</td>
            </tr>
            @endforelse
        </tbody>
        @if($siswas->count())
        @php
            $totalA = 0; $totalI = 0; $totalS = 0;
            foreach($siswas as $s) {
                $r = $matrixData[$s->id]['_rekap'] ?? ['A'=>0,'I'=>0,'S'=>0];
                $totalA += $r['A']; $totalI += $r['I']; $totalS += $r['S'];
            }
        @endphp
        <tfoot>
            <tr>
                <td colspan="3" style="text-align:right;">Total</td>
                @for($d = 1; $d <= $hariDalamBulan; $d++)
                <td></td>
                @endfor
                <td class="rekap-A">{{ $totalA }}</td>
                <td class="rekap-I">{{ $totalI }}</td>
                <td class="rekap-S">{{ $totalS }}</td>
            </tr>
        </tfoot>
        @endif
    </table>

    <table class="signature-section">
        <tr>
            <td>
                <div class="sig-date">Guluk-Guluk, ..............................................</div>
                <div class="sig-space"></div>
                <div class="sig-label">Wali Kelas {{ strtoupper($kelas->nama_kelas) }}</div>
                <div class="sig-line"></div>
                <div class="sig-name">{{ $waliKelasName ?? 'Mahbubah, S.Pd' }}</div>
            </td>
            <td>
                <div class="sig-label">Mengetahui,<br>Kepala MI. Nurul Ulum Patapan</div>
                <div class="sig-space"></div>
                <div class="sig-line"></div>
                <div class="sig-name">Ach. Fathorrosi, S.Pd.I</div>
            </td>
        </tr>
    </table>
</body>
</html>
