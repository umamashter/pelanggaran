<!DOCTYPE html>

<html>

<head>
    <table width="100%" style="border:none; margin-bottom:10px;">
        <tr>
            <td width="15%" align="center" style="border:none;">
                <img src="{{ asset('imG/logo2.png') }}" width="80">
            </td>
            <td align="center" style="border:none;">

                <h2 style="margin:0;">
                    YAYASAN PENDIDIKAN NURUL ULUM
                </h2>
                <h2 style="margin:0;">
                    MADRASAH IBTIDAIYAH NURUL ULUM
                </h2>

                <h2 style="margin:0;">
                    PATAPAN GULUK-GULUK SUMENEP
                </h2>

                <p style="margin:0;">
                    Jl. Datuk Idris Patapan Guluk-Guluk Sumenep 69463
                </p>

            </td>
        </tr>

    </table>

    <hr style="border:2px solid black;">


    <style>
        @page {
            size: A4 portrait;
            margin: 15mm;
        }

        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
        }

        h2,
        h3 {
            text-align: center;
        }
    </style>
</head>

<body onload="window.print()">
    <h2>
        JADWAL PELAJARAN
    </h2>
    <h3>
        KELAS {{ strtoupper($kelas->nama_kelas) }}
    </h3>
    <p style="text-align:center;">
        Tahun Ajaran {{ date('Y') }}/{{ date('Y') + 1 }}
    </p>


    @php
    $hariList = [
        'Senin',
        'Selasa',
        'Rabu',
        'Kamis',
        'Sabtu',
        'Ahad'
    ];
    @endphp

    @foreach($hariList as $hari)

    <h4>{{ $hari }}</h4>

    <table>

        <thead>

            <tr>
                <th>No</th>
                <th>Jam</th>
                <th>Mata Pelajaran</th>
                <th>Guru</th>
            </tr>

        </thead>

        <tbody>

            @php
            $jadwalHari = $jadwals->where('hari',$hari);
            @endphp

            @forelse($jadwalHari as $jadwal)

            <tr>

                <td>{{ $loop->iteration }}</td>

                <td>
                    {{ substr($jadwal->jam_mulai,0,5) }}
                    -
                    {{ substr($jadwal->jam_selesai,0,5) }}
                </td>

                <td>
                    {{ $jadwal->mapel->nama_mapel ?? '-' }}
                </td>

                <td>
                    {{ $jadwal->guru->nama ?? '-' }}
                </td>

            </tr>

            @empty

            <tr>

                <td colspan="4">
                    Belum ada jadwal
                </td>

            </tr>

            @endforelse

        </tbody>

    </table>

    @endforeach
    ```

</body>

</html>