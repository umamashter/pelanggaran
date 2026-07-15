<style>
    body {
        font-family: sans-serif;
        font-size: 11px;
    }

    .judul {
        text-align: center;
        margin-bottom: 15px;
    }

    .judul h2,
    .judul h3,
    .judul p {
        margin: 2px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table,
    th,
    td {
        border: 1px solid #000;
    }

    th {
        background: #eaeaea;
        text-align: center;
    }

    th,
    td {
        padding: 5px;
        vertical-align: middle;
    }

    .text-center {
        text-align: center;
    }
</style>

<div class="judul">

    <h2>MADRASAH IBTIDAIYAH NURUL ULUM</h2>

    <h3>DATA JADWAL PELAJARAN</h3>

    @if($jadwals->count())

    <p>
        Tahun Ajaran :
        {{ $jadwals->first()->tahunAjaran->tahun_ajaran ?? '-' }}
        -
        Semester
        {{ optional($jadwals->first()->tahunAjaran->semesterAktif)->nama ?? '-' }}
    </p>

    @endif

</div>

<table>

    <thead>

        <tr>

            <th width="5%">No</th>

            <th width="8%">Jenjang</th>

            <th width="10%">Kelas</th>

            <th width="22%">Mata Pelajaran</th>

            <th width="18%">Guru</th>

            <th width="10%">Hari</th>

            <th width="7%">Jam Ke</th>

            <th width="15%">Waktu</th>

        </tr>

    </thead>

    <tbody>

        @forelse($jadwals as $jadwal)

        <tr>

            <td class="text-center">
                {{ $loop->iteration }}
            </td>

            <td class="text-center">
                {{ $jadwal->jenjang->nama_jenjang ?? '-' }}
            </td>

            <td class="text-center">
                {{ $jadwal->kelas->nama_kelas ?? '-' }}
            </td>

            <td>
                {{ $jadwal->mapel->nama_mapel ?? '-' }}
            </td>

            <td>
                {{ $jadwal->guru->nama ?? '-' }}
            </td>

            <td class="text-center">
                {{ $jadwal->hari }}
            </td>

            <td class="text-center">
                {{ $jadwal->jam_ke }}
            </td>

            <td class="text-center">
                {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}
                -
                {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
            </td>

        </tr>

        @empty

        <tr>

            <td colspan="8" class="text-center">
                Tidak ada data jadwal.
            </td>

        </tr>

        @endforelse

    </tbody>

</table>

<br><br>

<table width="100%" style="border:none;">
    <tr style="border:none;">
        <td style="border:none;"></td>

        <td style="border:none; text-align:center; width:250px;">
            <p>
                {{ now()->translatedFormat('d F Y') }}
            </p>

            <p>
                Kepala Madrasah
            </p>

            <br><br><br>

            <strong>
                _______________________
            </strong>
        </td>
    </tr>
</table>