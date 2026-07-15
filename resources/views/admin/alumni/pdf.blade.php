<!DOCTYPE html>

<html>

<head>
    <meta charset="utf-8">

    ```
    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 12px;
        }

        .header {
            text-align: center;
            line-height: 1.3;
        }

        .yayasan {
            font-size: 14px;
            font-weight: bold;
        }

        .madrasah {
            font-size: 18px;
            font-weight: bold;
        }

        .alamat {
            font-size: 11px;
        }

        .line1 {
            border-top: 2px solid black;
            margin-top: 5px;
        }

        .line2 {
            border-top: 1px solid black;
            margin-top: 2px;
            margin-bottom: 15px;
        }

        .judul {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        .info {
            margin-bottom: 5px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .data-table,
        .data-table th,
        .data-table td {
            border: 1px solid black;
        }

        .data-table th,
        .data-table td {
            padding: 6px;
        }

        .data-table th {
            background: #efefef;
            text-align: center;
        }

        .ttd {
            width: 250px;
            margin-top: 40px;
            float: right;
            text-align: center;
        }
    </style>
    ```

</head>

<body>

    ```
    <div class="header">

        <div class="yayasan">
            YAYASAN PENDIDIKAN NURUL ULUM
        </div>

        <div class="madrasah">
            MADRASAH IBTIDAIYAH NURUL ULUM
        </div>
        <div class="madrasah">
            PATAPAN GULUK GULUK SUMENEP MADURA
        </div>

        <div class="alamat">
            Jl. Datuk Idris Patapan Guluk-Guluk Sumenep Madura 69463
        </div>

        <div class="alamat">
            NPSN : .....................
        </div>

    </div>

    <div class="line1"></div>
    <div class="line2"></div>

    <div class="judul">
        LAPORAN DATA ALUMNI
    </div>

    <div class="info">
        <strong>Tahun Ajaran :</strong>
        {{ $tahunAjaran->tahun_ajaran ?? 'Semua Tahun Ajaran' }}
    </div>

    <div class="info">
        <strong>Jumlah Alumni :</strong>
        {{ $alumni->count() }} Siswa
    </div>

    <table class="data-table">

        <thead>
            <tr>
                <th width="7%">No</th>
                <th width="15%">NISN</th>
                <th>Nama</th>
                <th width="20%">Kelas Terakhir</th>
                <th width="20%">Tahun Ajaran</th>
            </tr>
        </thead>

        <tbody>

            @forelse($alumni as $i => $a)

            <tr>
                <td align="center">{{ $i + 1 }}</td>
                <td>{{ $a->nisn }}</td>
                <td>{{ $a->nama }}</td>
                <td align="center">{{ $a->kelas->nama_kelas ?? '-' }}</td>
                <td align="center">{{ $a->tahunAjaran->tahun_ajaran ?? '-' }}</td>
            </tr>

            @empty

            <tr>
                <td colspan="5" align="center">
                    Tidak ada data alumni
                </td>
            </tr>

            @endforelse

        </tbody>

    </table>

    <div class="ttd">

        <p>
            {{ date('d-m-Y') }}
        </p>

        <p>
            Kepala Madrasah
        </p>

        <br><br><br>

        <strong>
            _______________________
        </strong>

    </div>
    ```

</body>

</html>