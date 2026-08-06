<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Pelajaran Kelas {{ $kelas->nama_kelas }}</title>
    <style>
        @page { size: A4 portrait; margin: 15mm; }

        * { box-sizing: border-box; }
        body { font-family: Arial, Helvetica, sans-serif; color: #000; font-size: 12pt; line-height: 1.35; }

        .kop {
            width: 100%; border-collapse: collapse; border: none; margin-bottom: 8px;
        }
        .kop td { border: none; vertical-align: middle; }
        .kop .logo { width: 90px; text-align: center; }
        .kop .logo img { width: 78px; }
        .kop .inst { text-align: center; }
        .kop .inst .yayasan { font-size: 13pt; font-weight: bold; letter-spacing: 1px; margin: 0; }
        .kop .inst .madrasah { font-size: 20pt; font-weight: bold; letter-spacing: 1px; margin: 2px 0; }
        .kop .inst .alamat { font-size: 10pt; margin: 2px 0; }

        hr.rule { border: none; border-top: 2.5px solid #000; margin: 0 0 10px; }

        .judul { text-align: center; font-size: 16pt; font-weight: bold; margin: 12px 0 2px; letter-spacing: 1px; }
        .sub { text-align: center; font-size: 12.5pt; font-weight: bold; margin: 0 0 2px; }
        .ta { text-align: center; font-size: 11pt; margin: 0 0 14px; }

        table.data { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
        table.data th, table.data td { border: 1px solid #000; padding: 6px 8px; font-size: 11pt; }
        table.data th { background: #f1f5f9; text-align: center; }
        table.data td.no, table.data td.jam { text-align: center; }
        table.data td.empty { text-align: center; font-style: italic; }

        h4.day { font-size: 12.5pt; margin: 10px 0 5px; text-decoration: underline; }
    </style>
</head>
<body onload="window.print()">

    <table class="kop">
        <tr>
            <td class="logo">
                <img src="{{ asset('imG/logo2.png') }}" alt="Logo">
            </td>
            <td class="inst">
                <p class="yayasan">YAYASAN PENDIDIKAN NURUL ULUM</p>
                <p class="madrasah">MADRASAH IBTIDAIYAH NURUL ULUM</p>
                <p class="alamat">Jl. Datuk Idris, Patapan, Guluk-Guluk, Sumenep, Jawa Timur 69463</p>
            </td>
        </tr>
    </table>
    <hr class="rule">

    <div class="judul">JADWAL PELAJARAN</div>
    <div class="sub">KELAS {{ strtoupper($kelas->nama_kelas) }} @if($kelas->jenjang) &mdash; {{ strtoupper($kelas->jenjang->nama_jenjang) }} @endif</div>
    <div class="ta">Tahun Pelajaran {{ $tahunAktifGlobal->tahun_ajaran ?? date('Y') . '/' . (date('Y') + 1) }}</div>

    @php
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Sabtu', 'Ahad'];
    @endphp

    @foreach($hariList as $hari)
        <h4 class="day">{{ $hari }}</h4>
        <table class="data">
            <thead>
                <tr>
                    <th width="6%">No</th>
                    <th width="18%">Jam</th>
                    <th>Mata Pelajaran</th>
                    <th width="30%">Guru</th>
                </tr>
            </thead>
            <tbody>
                @php $jadwalHari = $jadwals->where('hari', $hari); @endphp
                @forelse($jadwalHari as $jadwal)
                <tr>
                    <td class="no">{{ $loop->iteration }}</td>
                    <td class="jam">{{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}</td>
                    <td>{{ $jadwal->mapel->nama_mapel ?? '-' }}</td>
                    <td>{{ $jadwal->guru->nama ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="empty">Belum ada jadwal</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    @endforeach

</body>
</html>
