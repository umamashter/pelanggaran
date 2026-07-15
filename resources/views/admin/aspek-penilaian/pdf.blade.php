<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Form Penilaian {{ $lomba->nama }}</title>
    <style>
        @page { margin: 8mm 6mm; }
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 8.5px;
            color: #111;
        }
        .header {
            text-align: center;
            margin-bottom: 6px;
            line-height: 1.2;
        }
        .header h1 { margin: 0; font-size: 14px; font-weight: 700; }
        .header h2 { margin: 2px 0 0; font-size: 11px; font-weight: 700; }
        .header .meta { margin-top: 3px; font-size: 9px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 0.5px solid #555; padding: 3px 4px; vertical-align: middle; line-height: 1.15; }
        thead th { background: #f0d060; color: #111; text-align: center; font-weight: 700; font-size: 8px; }
        td.center { text-align: center; }
        td.empty-cell { height: 28px; }
        .footer { margin-top: 6px; text-align: right; font-size: 8px; color: #555; }
        .no-data td { text-align: center; padding: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>FORM PENILAIAN</h1>
        <h2>{{ strtoupper($lomba->nama) }}</h2>
        <div class="meta">
            Sesi: {{ $lomba->sesiLomba->nama ?? '-' }} |
            Tanggal: {{ $lomba->sesiLomba->tanggal ?? '-' }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:28px;">No</th>
                <th style="width:60px;">NISN</th>
                <th>Nama Peserta</th>
                @foreach($lomba->aspekPenilaians as $aspek)
                <th style="width:60px;">{{ $aspek->nama_aspek }}</th>
                @endforeach
                <th style="width:40px;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lomba->peserta as $i => $p)
            <tr>
                <td class="center">{{ $i + 1 }}</td>
                <td class="center">{{ $p->isIndividu() ? ($p->student->nisn ?? '-') : '-' }}</td>
                <td>
                    @if($p->isIndividu())
                        {{ $p->student->user->name ?? $p->student->nama ?? '-' }}
                    @else
                        {{ $p->kelompokLomba->nama_kelompok ?? '-' }}
                    @endif
                </td>
                @foreach($lomba->aspekPenilaians as $aspek)
                <td class="empty-cell"></td>
                @endforeach
                <td class="empty-cell center"></td>
            </tr>
            @endforeach
            @if($lomba->peserta->isEmpty())
            <tr class="no-data"><td colspan="{{ 3 + $lomba->aspekPenilaians->count() }}">Tidak ada peserta.</td></tr>
            @endif
        </tbody>
    </table>

    <div class="footer">Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }}</div>
</body>
</html>
