<style>
    @page { margin: 10mm 10mm; }
    .content-page { padding: 10mm 18mm; }

    .header { text-align: center; margin-bottom: 8px; padding-bottom: 6px; }
    .header h2 { font-size: 15px; font-weight: 800; letter-spacing: 0.5px; margin-bottom: 2px; }
    .header h3 { font-size: 12px; font-weight: 700; margin-bottom: 2px; }
    .header p { font-size: 11px; color: #475569; margin: 0; }

    .sub-header { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 10px; font-weight: 700; }
    .sub-header span { padding: 3px 10px; background: #f1f5f9; border-radius: 4px; }

    table.content-table { border-collapse: collapse; width: 100%; }
    table.content-table th, table.content-table td { border: 1px solid #94a3b8; text-align: center; vertical-align: middle; }

    table.content-table th { background: #e2e8f0; font-weight: 700; font-size: 8.5px; padding: 9px 2px; }
    table.content-table th.group-header { background: #cbd5e1; font-size: 9px; }

    table.content-table td { font-size: 9px; padding: 9px 2px; }

    .col-no { width: 30px; font-size: 9px; }
    .col-nisn { width: 65px; font-size: 9px; }
    .col-nama { text-align: left; padding-left: 4px !important; font-weight: 600; font-size: 9px; max-width: 100px; word-wrap: break-word; white-space: normal; }
    .col-tgl { font-size: 9px; padding: 5px 2px; width: 18px; min-width: 18px; max-width: 18px; }
    .col-rekap { width: 20px; font-size: 9px; font-weight: 700; }

    tfoot td { background: #f1f5f9; font-weight: 700; border-top: 2.5px solid #1e293b; font-size: 9px; }

    .legend { margin-top: 8px; font-size: 9px; color: #475569; display: flex; gap: 14px; }
    .legend span { font-weight: 600; }

    .signature-section { width: 100%; margin-top: 18px; border-collapse: collapse; }
    .signature-section td { vertical-align: top; width: 50%; padding: 0 10px; border: none; }
    .sig-date { font-size: 9px; color: #475569; margin-bottom: 3px; }
    .sig-label { font-size: 9px; color: #475569; line-height: 1.6; }
    .sig-space { height: 35px; }
    .sig-line { width: 140px; border-bottom: 1px solid #1e293b; margin: 0 0 5px; }
    .sig-name { font-size: 10px; font-weight: 700; color: #1e293b; }
</style>

<div class="content-page">
    <div class="header">
        <h2>ABSENSI SISWA M. NURUL ULUM</h2>
        <h3>PATAPAN GULUK-GULUK SUMENEP MADURA 69463</h3>
        <p>TAHUN PELAJARAN {{ $tahunAjaran->tahun_ajaran }}</p>
    </div>

    <div class="sub-header">
        <span>Kelas : {{ strtoupper($kelas->nama_kelas) }}</span>
        <span>Bulan : {{ strtoupper($bulanLabel) }}</span>
    </div>

    <table class="content-table">
        <thead>
            <tr>
                <th class="col-no" rowspan="2">NO</th>
                <th class="col-nisn" rowspan="2">NISN</th>
                <th class="col-nama" rowspan="2">NAMA SISWA</th>
                <th class="group-header" colspan="{{ $hariDalamBulan }}">TANGGAL</th>
                <th class="group-header" colspan="3">TIDAK MASUK</th>
            </tr>
            <tr>
                @for($d = 1; $d <= $hariDalamBulan; $d++)
                @php $hTgl = $tanggalAwal->copy()->day($d); @endphp
                <th class="col-tgl" style="width:18px;min-width:18px;max-width:18px;background:{{ $hTgl->isFriday() ? '#f1f5f9' : '#e2e8f0' }};color:{{ $hTgl->isFriday() ? '#94a3b8' : '#1e293b' }};">{{ $d }}</th>
                @endfor
                <th class="col-rekap" style="background:#fee2e2;color:#dc2626;">A</th>
                <th class="col-rekap" style="background:#fef3c7;color:#d97706;">I</th>
                <th class="col-rekap" style="background:#dcfce7;color:#16a34a;">S</th>
            </tr>
        </thead>
        <tbody>
            @forelse($siswas as $siswa)
            <tr>
                <td class="col-no">{{ $loop->iteration }}</td>
                <td class="col-nisn">{{ $siswa->nisn }}</td>
                <td class="col-nama">{{ $siswa->nama }}</td>
                @for($d = 1; $d <= $hariDalamBulan; $d++)
                @php $hTgl2 = $tanggalAwal->copy()->day($d); @endphp
                <td class="col-tgl" style="width:18px;min-width:18px;max-width:18px;{{ $hTgl2->isFriday() ? 'background:#f1f5f9;color:#94a3b8;' : '' }}">{{ $hTgl2->isFriday() ? 'L' : '' }}</td>
                @endfor
                <td class="col-rekap"></td>
                <td class="col-rekap"></td>
                <td class="col-rekap"></td>
            </tr>
            @empty
            <tr>
                <td colspan="{{ 3 + $hariDalamBulan + 3 }}" style="text-align:center;padding:10px;">Tidak ada data siswa</td>
            </tr>
            @endforelse
            @php $blankRows = max(0, 15 - $siswas->count()); @endphp
            @for($i = 0; $i < $blankRows; $i++)
            <tr>
                <td class="col-no">&nbsp;</td>
                <td class="col-nisn"></td>
                <td class="col-nama"></td>
                @for($d = 1; $d <= $hariDalamBulan; $d++)
                @php $hTgl3 = $tanggalAwal->copy()->day($d); @endphp
                <td class="col-tgl" style="width:18px;min-width:18px;max-width:18px;{{ $hTgl3->isFriday() ? 'background:#f1f5f9;color:#94a3b8;' : '' }}">{{ $hTgl3->isFriday() ? 'L' : '' }}</td>
                @endfor
                <td class="col-rekap"></td>
                <td class="col-rekap"></td>
                <td class="col-rekap"></td>
            </tr>
            @endfor
        </tbody>
    </table>

    <div class="legend">
        <span>Keterangan :</span>
        <span>&bull; = HADIR</span>
        <span>I = IZIN</span>
        <span>S = SAKIT</span>
        <span>A = ALPHA</span>
        <span>L = HARI LIBUR (JUMAT)</span>
    </div>

    <table class="signature-section">
        <tr>
            <td>
                <div class="sig-date">Guluk-Guluk, ..............................................</div>
                <div class="sig-space"></div>
                <div class="sig-label">Wali Kelas {{ strtoupper($kelas->nama_kelas) }}</div>
                <div class="sig-line"></div>
                <div class="sig-name">{{ $waliKelasName }}</div>
            </td>
            <td>
                <div class="sig-label">Mengetahui,<br>Kepala MI Nurul Ulum Patapan</div>
                <div class="sig-space"></div>
                <div class="sig-line"></div>
                <div class="sig-name">Ach. Fathorrosi, S.Pd.I</div>
            </td>
        </tr>
    </table>
</div>
