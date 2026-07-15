<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Print Preview Kelompok Lomba</title>
    <style>
        @page { margin: 10mm 8mm; }
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 9.5px;
            color: #111;
        }
        .header {
            text-align: center;
            margin-bottom: 8px;
            line-height: 1.25;
        }
        .header h1,
        .header h2,
        .header p {
            margin: 0;
        }
        .header h1 {
            font-size: 15px;
            font-weight: 700;
            letter-spacing: .4px;
        }
        .header h2 {
            margin-top: 4px;
            font-size: 11px;
            font-weight: 700;
        }
        .header .meta { margin-top: 4px; font-size: 9.5px; }
        .sheet { width: 100%; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td { border: 0.5px solid #666; padding: 4px 5px; vertical-align: middle; line-height: 1.15; }
        thead th { background: #f7d54a; color: #111; text-align: center; font-weight: 700; font-size: 9px; }
        td.center { text-align: center; }
        td.merged-cell { width: 58px; padding: 4px 5px; text-align: center; font-weight: 700; }
        .session-date { display: block; margin-top: 3px; font-size: 8px; font-weight: 400; }
        .footer { margin-top: 8px; text-align: right; font-size: 8.5px; color: #444; }
        .empty-row td { text-align: center; padding: 10px 5px; }
    </style>
</head>
<body onload="window.print()">
    @php
        $tahun = $tahunAjaran ?: '-';
    @endphp

    <div class="header">
        <h1>JADWAL KELOMPOK LOMBA</h1>
        <h2>HAFLATUL IMTIHAN</h2>
        <div class="meta">Tahun Ajaran : {{ $tahun }}</div>
    </div>

    <div class="sheet">
        <table>
            <thead>
                <tr>
                    <th style="width:34px;">Sesi/Tgl</th>
                    <th>Lomba</th>
                    <th>Nama Kelompok</th>
                    <th>Nama</th>
                    <th style="width:82px;">Kelas</th>
                    <th style="width:58px;">Jenjang</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jadwal as $sesi)
                    @php $sesiPrinted = false; @endphp
                    @foreach($sesi['lombas'] as $lomba)
                        @php $lombaPrinted = false; @endphp
                        @foreach($lomba['kelompoks'] as $kelompok)
                            @php $groupPrinted = false; @endphp
                            @foreach($kelompok['anggota'] as $anggota)
                                @php $student = $anggota ? $anggota->student : null; @endphp
                                <tr>
                                    @if(!$sesiPrinted)
                                        <td class="merged-cell" rowspan="{{ $sesi['rowspan'] }}">
                                            {{ $sesi['nama'] }}
                                            <span class="session-date">{{ $sesi['tanggal'] }}</span>
                                        </td>
                                        @php $sesiPrinted = true; @endphp
                                    @endif

                                    @if(!$lombaPrinted)
                                        <td class="merged-cell" rowspan="{{ $lomba['rowspan'] }}">{{ $lomba['nama'] }}</td>
                                        @php $lombaPrinted = true; @endphp
                                    @endif

                                    @if(!$groupPrinted)
                                        <td class="merged-cell" rowspan="{{ $kelompok['rowspan'] }}">{{ $kelompok['nama_kelompok'] ?? '-' }}</td>
                                        @php $groupPrinted = true; @endphp
                                    @endif

                                    <td>{{ optional(optional($student)->user)->name ?? '-' }}</td>
                                    <td class="center">{{ optional(optional(optional($student)->kelasAktif)->kelas)->nama_kelas ?? '-' }}</td>
                                    <td class="center">{{ optional(optional(optional(optional($student)->kelasAktif)->kelas)->jenjang)->nama_jenjang ?? '-' }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endforeach
                @empty
                    <tr class="empty-row"><td colspan="6">Tidak ada data kelompok.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }}</div>
</body>
</html>
