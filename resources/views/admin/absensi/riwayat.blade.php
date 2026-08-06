@extends('layouts.main')
@section('title','Rekap Absensi Bulanan')
@section('content')
@include('component.admin.absensi-module')
<style>
    .page-title-content { display: none !important; }
    .abm-riwayat-hero { padding: 22px 28px; margin-bottom: 20px; border-radius: 22px; }

    .matrix-card { background: var(--ab-card); border: 1px solid var(--ab-border); border-radius: 18px; box-shadow: var(--ab-shadow); overflow: hidden; }
    .matrix-card-header { padding: 16px 20px; border-bottom: 1px solid var(--ab-border-soft); display: flex; justify-content: space-between; align-items: center; }
    .matrix-scroll { overflow-x: auto; -webkit-overflow-scrolling: touch; scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent; }
    .matrix-scroll::-webkit-scrollbar { height: 6px; }
    .matrix-scroll::-webkit-scrollbar-track { background: transparent; }
    .matrix-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
    .matrix-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

    .matrix-table { border-collapse: separate; border-spacing: 0; width: 100%; min-width: max-content; }
    .matrix-table thead th {
        background: linear-gradient(180deg, var(--ab-border-soft), var(--ab-border-soft));
        color: var(--ab-text-2); font-weight: 700; font-size: 10px;
        text-transform: uppercase; letter-spacing: .4px; padding: 12px 6px;
        border-bottom: 2px solid var(--ab-border); text-align: center; white-space: nowrap;
        position: sticky; top: 0; z-index: 2;
    }
    .matrix-table thead th.group-date {
        background: linear-gradient(180deg, var(--ab-primary-soft), var(--ab-border-soft));
        color: var(--ab-text-2); font-size: 10px; letter-spacing: .5px; padding: 8px 4px;
        color: var(--ab-primary);
    }
    .matrix-table thead th.group-rekap { background: linear-gradient(180deg, var(--ab-amber-soft), var(--ab-border-soft)); font-size: 10px; color: var(--ab-amber); letter-spacing: .5px; padding: 8px 4px; }
    .matrix-table thead th.sticky-col { position: sticky; left: 0; z-index: 4; background: var(--ab-border-soft); }
    .matrix-table thead th.sticky-nis { position: sticky; z-index: 4; background: var(--ab-border-soft); }
    .matrix-table thead th.sticky-nama { position: sticky; z-index: 4; background: var(--ab-border-soft); }
    .matrix-table thead th.date-h { background: var(--ab-card); font-size: 10px; font-weight: 700; color: var(--ab-text-2); padding: 6px 2px; min-width: 26px; }
    .matrix-table thead th.rekap-h { font-size: 10px; font-weight: 700; padding: 6px 4px; min-width: 26px; }
    .matrix-table thead th.rekap-h-a { color: var(--ab-text-3); }
    .matrix-table thead th.rekap-h-i { color: var(--ab-amber); }
    .matrix-table thead th.rekap-h-s { color: var(--ab-sky); }

    .matrix-table tbody td { padding: 8px 4px; border-bottom: 1px solid var(--ab-border-soft); text-align: center; vertical-align: middle; white-space: nowrap; transition: all .15s; }
    .matrix-table tbody td.sticky-col {
        position: sticky; left: 0; z-index: 1; background: var(--ab-card);
        font-weight: 700; font-size: 11px; color: var(--ab-text-3); text-align: center;
        border-right: 2px solid var(--ab-border); min-width: 36px;
    }
    .matrix-table tbody td.sticky-nis {
        position: sticky; z-index: 1; background: var(--ab-card); font-size: 11px; color: var(--ab-text-2); text-align: left; padding-left: 10px;
        border-right: 2px solid var(--ab-border); font-weight: 500; font-variant-numeric: tabular-nums;
    }
    .matrix-table tbody td.sticky-nama {
        position: sticky; z-index: 1; background: var(--ab-card); font-size: 12px; color: var(--ab-text); text-align: left; padding-left: 10px;
        font-weight: 700; min-width: 140px;
    }
    .matrix-table tbody tr:hover td { background: var(--ab-primary-soft) !important; }
    .matrix-table tbody tr:nth-child(even) td { background: color-mix(in srgb, var(--ab-card) 60%, var(--ab-border-soft)); }
    html.dark-mode .matrix-table tbody tr:nth-child(even) td { background: rgba(255,255,255,.02); }
    .matrix-table tbody tr:last-child td { border-bottom: none; }

    .status-cell { width: 28px; min-width: 28px; max-width: 32px; }
    .status-badge {
        display: inline-flex; align-items: center; justify-content: center;
        width: 26px; height: 26px; border-radius: 8px; font-size: 10px; font-weight: 800;
        line-height: 1; transition: all .2s;
    }
    .status-badge.clickable { cursor: pointer; }
    .status-badge.clickable:hover { transform: scale(1.25); box-shadow: 0 4px 12px rgba(0,0,0,.18); }
    .libur-cell {
        display: inline-flex; align-items: center; justify-content: center;
        width: auto; min-width: 56px; height: 26px; border-radius: 8px;
        font-size: 9px; font-weight: 800; letter-spacing: .5px;
        background: var(--ab-border-soft); color: var(--ab-text-3);
    }
    .s-H { background: linear-gradient(135deg, #dcfce7, #bbf7d0); color: #15803d; box-shadow: 0 2px 6px rgba(22,163,74,.14); }
    .s-I { background: linear-gradient(135deg, #fef3c7, #fde68a); color: #b45309; box-shadow: 0 2px 6px rgba(217,119,6,.14); }
    .s-S { background: linear-gradient(135deg, #e0f2fe, #bae6fd); color: #0369a1; box-shadow: 0 2px 6px rgba(2,132,199,.14); }
    .s-A { background: linear-gradient(135deg, #fee2e2, #fecaca); color: #dc2626; box-shadow: 0 2px 6px rgba(220,38,38,.14); }
    .s-null { background: transparent; color: var(--ab-text-3); }
    html.dark-mode .s-H { background: rgba(52,211,153,.18); color: #4ade80; }
    html.dark-mode .s-I { background: rgba(251,191,36,.16); color: #fbbf24; }
    html.dark-mode .s-S { background: rgba(56,189,248,.16); color: #38bdf8; }
    html.dark-mode .s-A { background: rgba(248,113,113,.16); color: #f87171; }

    .rekap-cell { width: 28px; min-width: 28px; font-size: 12px; font-weight: 800; font-variant-numeric: tabular-nums; }
    .rekap-A { color: var(--ab-text-2); }
    .rekap-I { color: var(--ab-amber); }
    .rekap-S { color: var(--ab-sky); }

    .matrix-table tfoot td { background: var(--ab-border-soft); font-weight: 800; font-size: 12px; padding: 12px 4px; border-top: 2px solid var(--ab-border); text-align: center; color: var(--ab-text); }
    .matrix-table tfoot td:first-child { position: sticky; left: 0; }

    .abm-heat-day { position: relative; }
    .abm-heat-day .bar { height: 4px; border-radius: 4px; margin: 6px auto 0; background: var(--ab-border); width: 70%; }
    .abm-heat-day .bar span { display: block; height: 100%; border-radius: 4px; background: var(--ab-grad); }

    /* Detail modal */
    .detail-modal .modal-content { border: none; border-radius: 18px; overflow: hidden; box-shadow: 0 25px 60px -12px rgba(0,0,0,.18); }
    .detail-modal .modal-header { border-bottom: none; padding: 22px 28px 14px; }
    .detail-modal .modal-title { font-size: 16px; font-weight: 700; display: flex; align-items: center; gap: 10px; }
    .detail-modal .modal-body { padding: 0 28px 24px; }
    .detail-modal .modal-footer { border-top: 1px solid var(--ab-border-soft); padding: 16px 28px; display: flex; gap: 8px; justify-content: flex-end; }
    .detail-status-badge { display: inline-flex; align-items: center; gap: 7px; padding: 7px 16px; border-radius: 10px; font-size: 13px; font-weight: 700; }
    .detail-status-badge.status-hadir { background: linear-gradient(135deg, #dcfce7, #bbf7d0); color: #15803d; }
    .detail-status-badge.status-izin { background: linear-gradient(135deg, #fef3c7, #fde68a); color: #b45309; }
    .detail-status-badge.status-sakit { background: linear-gradient(135deg, #e0f2fe, #bae6fd); color: #0369a1; }
    .detail-status-badge.status-alpha { background: linear-gradient(135deg, #fee2e2, #fecaca); color: #dc2626; }
    html.dark-mode .detail-status-badge.status-hadir { background: rgba(52,211,153,.18); color: #4ade80; }
    html.dark-mode .detail-status-badge.status-izin { background: rgba(251,191,36,.16); color: #fbbf24; }
    html.dark-mode .detail-status-badge.status-sakit { background: rgba(56,189,248,.16); color: #38bdf8; }
    html.dark-mode .detail-status-badge.status-alpha { background: rgba(248,113,113,.16); color: #f87171; }
    .detail-row { display: flex; padding: 11px 0; border-bottom: 1px solid var(--ab-border-soft); }
    .detail-row:last-child { border-bottom: none; }
    .detail-label { flex: 0 0 140px; font-size: 12px; font-weight: 600; color: var(--ab-text-3); display: flex; align-items: center; gap: 7px; }
    .detail-label i { font-size: 11px; color: var(--ab-text-3); width: 14px; text-align: center; }
    .detail-value { flex: 1; font-size: 13px; color: var(--ab-text); font-weight: 600; }

    .detail-modal.modal-header-green .modal-header { background: linear-gradient(135deg, #f0fdf4, #dcfce7); }
    .detail-modal.modal-header-green .modal-title { color: #166534; }
    .detail-modal.modal-header-yellow .modal-header { background: linear-gradient(135deg, #fffbeb, #fef3c7); }
    .detail-modal.modal-header-yellow .modal-title { color: #92400e; }
    .detail-modal.modal-header-red .modal-header { background: linear-gradient(135deg, #fee2e2, #fecaca); }
    .detail-modal.modal-header-red .modal-title { color: #991b1b; }
    .detail-modal.modal-header-blue .modal-header { background: linear-gradient(135deg, #e0f2fe, #bae6fd); }
    .detail-modal.modal-header-blue .modal-title { color: #075985; }
    .detail-modal.modal-header-gray .modal-header { background: var(--ab-border-soft); }
    .detail-modal.modal-header-gray .modal-title { color: var(--ab-text-2); }
    html.dark-mode .detail-modal .modal-title { color: var(--ab-text); }

    @media (max-width: 768px) {
        .abm-riwayat-hero { padding: 18px 16px; }
        .matrix-table thead th { font-size: 9px; padding: 8px 3px; }
        .matrix-table tbody td { padding: 6px 2px; font-size: 10px; }
        .status-badge { width: 22px; height: 22px; font-size: 9px; }
        .matrix-card-header { padding: 14px 16px; }
    }

    @media print {
        .page-title-content, .l-sidebar, .abm-riwayat-hero, .abm-heatmap, .abm-summary, .abm-filtercard, .legend-card, .matrix-card-header { display: none !important; }
        .matrix-card { box-shadow: none !important; border-radius: 0 !important; border: 1px solid #ccc; }
        .matrix-scroll { overflow: visible !important; }
        .matrix-table { font-size: 8px !important; min-width: unset !important; }
        .matrix-table thead th { position: static !important; background: #e5e7eb !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .matrix-table tbody td.sticky-col, .matrix-table tbody td.sticky-nis, .matrix-table tbody td.sticky-nama { position: static !important; background: #fff !important; border-right: 1px solid #ccc !important; }
        .status-badge, .s-H, .s-I, .s-S, .s-A { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        body { margin: 0; padding: 8px; }
    }
</style>

<div class="abs-mod riwayat-page" style="margin-top:0;">
    {{-- HERO --}}
    <div class="abm-hero abm-riwayat-hero">
        <div class="abm-hero-grid"></div>
        <div class="abm-hero-row">
            <div class="abm-hero-left">
                <div class="d-flex align-items-center gap-3">
                    <div class="abm-hero-icon"><i class="fas fa-calendar-check"></i></div>
                    <div>
                        <h3>Rekap Absensi Bulanan</h3>
                        <p class="abm-hero-sub">Matriks kehadiran siswa per bulan.</p>
                    </div>
                </div>
                <div class="abm-hero-badges">
                    <span class="abm-hero-badge"><i class="fas fa-graduation-cap"></i> {{ $tahunAktif->tahun_ajaran }}</span>
                    @if($selectedKelasId && $kelas)
                    <span class="abm-hero-badge"><i class="fas fa-chalkboard"></i> {{ $kelas->nama_kelas }}</span>
                    @endif
                </div>
            </div>
            <div class="abm-hero-right">
                <div class="abm-hero-actions">
                    <a href="{{ route('absensi.index') }}" class="abm-btn abm-btn--ghost"><i class="fas fa-arrow-left"></i> Kembali</a>
                    @if($selectedKelasId && $kelas)
                    <a href="{{ route('absensi.riwayat.pdf', ['kelas_id' => $kelas->id, 'bulan' => $bulan]) }}" class="abm-btn abm-btn--ghost" target="_blank"><i class="fas fa-file-pdf"></i> PDF</a>
                    @endif
                    <a href="{{ route('absensi.create') }}" class="abm-btn abm-btn--light"><i class="fas fa-plus"></i> Input</a>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTER --}}
    <div class="abm-card abm-filtercard" style="padding:16px 20px;margin-bottom:20px;">
        <form method="GET" id="riwayatFilterForm" class="row g-3 align-items-end">
            <div class="col-lg-4 col-md-6">
                <label class="abm-field-label"><i class="fas fa-chalkboard"></i>Kelas</label>
                <select name="kelas_id" class="abm-control" id="filterKelas" required>
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($kelasList as $item)
                    <option value="{{ $item->id }}" {{ request('kelas_id') == $item->id ? 'selected' : '' }}>{{ $item->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-4 col-md-6">
                <label class="abm-field-label"><i class="fas fa-calendar"></i>Bulan</label>
                <input type="month" name="bulan" class="abm-control" id="filterBulan" value="{{ $bulan }}">
            </div>
        </form>
    </div>

    @if($selectedKelasId && $kelas)
    @php
        $tanggalAwal = \Carbon\Carbon::parse($bulan . '-01');
        $hariDalamBulan = $tanggalAwal->daysInMonth;
        $bulanLabel = $tanggalAwal->translatedFormat('F Y');

        $dayStats = [];
        $totalAbsent = ['I' => 0, 'S' => 0, 'A' => 0];
        for ($d = 1; $d <= $hariDalamBulan; $d++) {
            $tgl = $tanggalAwal->copy()->day($d)->format('Y-m-d');
            $isFriday = isset($fridaySet[$tgl]);
            if ($isFriday) continue;
            $cnt = ['H' => 0, 'I' => 0, 'S' => 0, 'A' => 0, 'N' => 0];
            foreach ($siswas as $s) {
                $st = $matrixData[$s->id][$tgl] ?? null;
                if ($st === null) $cnt['N']++;
                else $cnt[$st]++;
            }
            $totalAbsent['I'] += $cnt['I'];
            $totalAbsent['S'] += $cnt['S'];
            $totalAbsent['A'] += $cnt['A'];
            $dayStats[$d] = ['tgl' => $tgl, 'H' => $cnt['H'], 'I' => $cnt['I'], 'S' => $cnt['S'], 'A' => $cnt['A'], 'N' => $cnt['N']];
        }
        $sumTotal = $siswas->count() * count($dayStats);
        $sumPct = $sumTotal > 0 ? round(array_sum(array_column($dayStats, 'H')) / $sumTotal * 100) : 0;
    @endphp

    {{-- LAPORAN --}}
    <div class="abm-card" style="padding:14px 20px;margin-bottom:16px;text-align:center;">
        <div style="font-size:13px;font-weight:800;color:var(--ab-primary);letter-spacing:.5px;text-transform:uppercase;">Absensi Siswa MI Nurul Ulum</div>
        <div style="font-size:12px;color:var(--ab-text-2);margin-top:3px;">Kelas {{ strtoupper($kelas->nama_kelas) }} &middot; Bulan {{ strtoupper($bulanLabel) }} &middot; Tahun Ajaran {{ $tahunAktif->tahun_ajaran }}</div>
    </div>

    {{-- SUMMARY + HEATMAP --}}
    <div class="abm-card" style="padding:18px 20px;margin-bottom:16px;">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div class="abm-section-title"><i class="fas fa-chart-line"></i> Ringkasan Bulanan</div>
            <div class="abm-counter">
                <span class="abm-counter-item h">Hadir <b>{{ array_sum(array_column($dayStats, 'H')) }}</b></span>
                <span class="abm-counter-item i">Izin <b>{{ $totalAbsent['I'] }}</b></span>
                <span class="abm-counter-item s">Sakit <b>{{ $totalAbsent['S'] }}</b></span>
                <span class="abm-counter-item a">Alpha <b>{{ $totalAbsent['A'] }}</b></span>
                <span class="abm-chip abm-chip--blue" style="font-size:12px;"><i class="fas fa-percent"></i> Kehadiran {{ $sumPct }}%</span>
            </div>
        </div>
        <div class="abm-progress mb-3"><span data-w="{{ $sumPct }}" style="background:{{ $sumPct >= 80 ? 'linear-gradient(90deg,#16a34a,#4ade80)' : 'linear-gradient(90deg,#d97706,#fbbf24)' }};"></span></div>
        <div class="abm-heatmap">
            @foreach($dayStats as $d => $st)
            @php
                $absent = $st['I'] + $st['S'] + $st['A'];
                $pctAbsent = $siswas->count() > 0 ? round($absent / $siswas->count() * 100) : 0;
                $tone = $pctAbsent === 0 ? 'var(--ab-green-soft)' : ($pctAbsent <= 20 ? 'var(--ab-amber-soft)' : 'var(--ab-red-soft)');
                $barColor = $pctAbsent === 0 ? 'linear-gradient(90deg,#16a34a,#4ade80)' : ($pctAbsent <= 20 ? 'linear-gradient(90deg,#d97706,#fbbf24)' : 'linear-gradient(90deg,#dc2626,#f87171)');
            @endphp
            <div class="abm-heat-cell" style="background:{{ $tone }};" title="{{ \Carbon\Carbon::parse($st['tgl'])->translatedFormat('d F Y') }} — tidak hadir {{ $absent }} siswa">
                <div class="day">{{ $d }}</div>
                <div class="bar"><span style="width:{{ $pctAbsent }}%;background:{{ $barColor }};"></span></div>
                <div class="n">{{ $absent > 0 ? $absent . ' abs' : 'OK' }}</div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- MATRIX --}}
    <div class="matrix-card">
        <div class="matrix-card-header">
            <div class="abm-section-title">
                <i class="fas fa-table"></i> Matriks Absensi
                <span class="abm-chip abm-chip--blue">{{ $siswas->count() }} siswa</span>
            </div>
        </div>
        <div class="matrix-scroll">
            <table class="matrix-table">
                <thead>
                    <tr>
                        <th class="sticky-col" rowspan="2" style="width:36px;">No</th>
                        <th class="sticky-nis" rowspan="2" style="width:70px;position:sticky;left:36px;">NIS</th>
                        <th class="sticky-nama" rowspan="2" style="width:140px;position:sticky;left:106px;">Nama Siswa</th>
                        <th class="group-date" colspan="{{ $hariDalamBulan }}">Tanggal</th>
                        <th class="group-rekap" colspan="3">Tidak Masuk</th>
                    </tr>
                    <tr>
                        @for($d = 1; $d <= $hariDalamBulan; $d++)
                        <th class="date-h">{{ $d }}</th>
                        @endfor
                        <th class="rekap-h rekap-h-a">A</th>
                        <th class="rekap-h rekap-h-i">I</th>
                        <th class="rekap-h rekap-h-s">S</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswas as $siswa)
                    @php
                        $data = $matrixData[$siswa->id] ?? [];
                        $rekap = $data['_rekap'] ?? ['A' => 0, 'I' => 0, 'S' => 0];
                        $meta = $detailMeta[$siswa->id] ?? [];
                    @endphp
                    <tr>
                        <td class="sticky-col">{{ $loop->iteration }}</td>
                        <td class="sticky-nis" style="position:sticky;left:36px;">{{ $siswa->nisn }}</td>
                        <td class="sticky-nama" style="position:sticky;left:106px;">{{ $siswa->nama }}</td>
                        @for($d = 1; $d <= $hariDalamBulan; $d++)
                        @php
                            $tgl = $tanggalAwal->copy()->day($d)->format('Y-m-d');
                            $isFriday = isset($fridaySet[$tgl]);
                            $status = $isFriday ? null : ($data[$tgl] ?? null);
                            $m = $isFriday ? null : ($meta[$tgl] ?? null);
                        @endphp
                        <td class="status-cell">
                            @if($isFriday)
                                <span class="libur-cell" title="Hari Jumat — Libur Madrasah">LIBUR</span>
                            @elseif($status && $m)
                                <span class="status-badge s-{{ $status }} clickable"
                                    onclick="showDetail(this)"
                                    data-nama="{{ $siswa->nama }}"
                                    data-nisn="{{ $siswa->nisn }}"
                                    data-tanggal="{{ \Carbon\Carbon::parse($tgl)->translatedFormat('d F Y') }}"
                                    data-status="{{ $status }}"
                                    data-status-text="{{ $status === 'H' ? 'Hadir' : ($status === 'I' ? 'Izin' : ($status === 'S' ? 'Sakit' : 'Alpha')) }}"
                                    data-user="{{ $m['user_name'] }}"
                                    data-waktu="{{ $m['created_at'] }}"
                                    data-keterangan="{{ $m['keterangan'] }}"
                                    data-absensi-id="{{ $m['absensi_id'] }}"
                                    data-student-id="{{ $siswa->id }}"
                                >{{ $status }}</span>
                            @else
                                <span class="status-badge s-null">-</span>
                            @endif
                        </td>
                        @endfor
                        <td class="rekap-cell rekap-A">{{ $rekap['A'] }}</td>
                        <td class="rekap-cell rekap-I">{{ $rekap['I'] }}</td>
                        <td class="rekap-cell rekap-S">{{ $rekap['S'] }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ 3 + $hariDalamBulan + 3 }}">
                            <div class="abm-empty">
                                <i class="fas fa-user-slash"></i>
                                <div class="abm-empty-title">Tidak Ada Data</div>
                                <div class="abm-empty-sub">Belum ada data siswa di kelas {{ $kelas->nama_kelas }}</div>
                            </div>
                        </td>
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
                        <td class="sticky-col" colspan="3" style="text-align:right;padding-right:12px;font-weight:800;color:var(--ab-text-2);">TOTAL</td>
                        @for($d = 1; $d <= $hariDalamBulan; $d++)
                        <td></td>
                        @endfor
                        <td class="rekap-cell rekap-A">{{ $totalA }}</td>
                        <td class="rekap-cell rekap-I">{{ $totalI }}</td>
                        <td class="rekap-cell rekap-S">{{ $totalS }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

    <div class="abm-card legend-card" style="padding:14px 20px;margin-top:16px;display:flex;flex-wrap:wrap;gap:16px;align-items:center;">
        <span style="font-size:11px;font-weight:800;color:var(--ab-text-3);text-transform:uppercase;letter-spacing:.5px;">Keterangan</span>
        <div style="display:flex;flex-wrap:wrap;gap:14px;">
            <span style="display:inline-flex;align-items:center;gap:6px;font-size:12px;color:var(--ab-text-2);font-weight:500;"><span class="status-badge s-H">H</span> Hadir</span>
            <span style="display:inline-flex;align-items:center;gap:6px;font-size:12px;color:var(--ab-text-2);font-weight:500;"><span class="status-badge s-I">I</span> Izin</span>
            <span style="display:inline-flex;align-items:center;gap:6px;font-size:12px;color:var(--ab-text-2);font-weight:500;"><span class="status-badge s-S">S</span> Sakit</span>
            <span style="display:inline-flex;align-items:center;gap:6px;font-size:12px;color:var(--ab-text-2);font-weight:500;"><span class="status-badge s-A">A</span> Alpha</span>
            <span style="display:inline-flex;align-items:center;gap:6px;font-size:12px;color:var(--ab-text-2);font-weight:500;"><span class="status-badge libur-cell" style="min-width:24px;font-size:8px;">J</span> Jumat (Libur)</span>
            <span style="display:inline-flex;align-items:center;gap:6px;font-size:12px;color:var(--ab-text-2);font-weight:500;"><span class="status-badge s-null">-</span> Belum diisi</span>
        </div>
    </div>

    @else
    <div class="abm-card" style="margin-bottom:10px;">
        <div class="abm-empty">
            <i class="fas fa-calendar-times"></i>
            <div class="abm-empty-title">Pilih Kelas & Bulan</div>
            <div class="abm-empty-sub">Silakan pilih kelas dan bulan untuk menampilkan rekap absensi bulanan.</div>
        </div>
    </div>
    @endif
</div>

{{-- Modal Detail --}}
<div class="modal fade detail-modal" id="detailAbsensiModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-clipboard-list"></i> Detail Absensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="detail-row"><div class="detail-label"><i class="fas fa-user"></i> Nama Siswa</div><div class="detail-value" id="modalNama">-</div></div>
                <div class="detail-row"><div class="detail-label"><i class="fas fa-id-card"></i> NIS</div><div class="detail-value" id="modalNisn">-</div></div>
                <div class="detail-row"><div class="detail-label"><i class="fas fa-calendar"></i> Tanggal</div><div class="detail-value" id="modalTanggal">-</div></div>
                <div class="detail-row"><div class="detail-label"><i class="fas fa-info-circle"></i> Status</div><div class="detail-value" id="modalStatus">-</div></div>
                <div class="detail-row"><div class="detail-label"><i class="fas fa-user-check"></i> Dicatat Oleh</div><div class="detail-value" id="modalUser">-</div></div>
                <div class="detail-row"><div class="detail-label"><i class="fas fa-clock"></i> Waktu Dicatat</div><div class="detail-value" id="modalWaktu">-</div></div>
                <div class="detail-row"><div class="detail-label"><i class="fas fa-comment-dots"></i> Keterangan</div><div class="detail-value" id="modalKeterangan">-</div></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="abm-btn abm-btn--soft" data-bs-dismiss="modal">Tutup</button>
                <a href="#" class="abm-btn abm-btn--solid" id="modalEditBtn"><i class="fas fa-pen"></i> Edit Absensi</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showDetail(el) {
    var status = el.getAttribute('data-status');
    var modal = $('#detailAbsensiModal');
    var modalHeader = modal.find('.modal-header');
    var headerClass = '';

    modal.find('#modalNama').text(el.getAttribute('data-nama'));
    modal.find('#modalNisn').text(el.getAttribute('data-nisn'));
    modal.find('#modalTanggal').text(el.getAttribute('data-tanggal'));
    modal.find('#modalUser').text(el.getAttribute('data-user'));
    modal.find('#modalWaktu').text(el.getAttribute('data-waktu'));
    modal.find('#modalKeterangan').text(el.getAttribute('data-keterangan') || '-');

    if (status === 'H') {
        modal.find('#modalStatus').html('<span class="detail-status-badge status-hadir"><i class="fas fa-check-circle"></i> Hadir</span>');
        headerClass = 'modal-header-green';
    } else if (status === 'I') {
        modal.find('#modalStatus').html('<span class="detail-status-badge status-izin"><i class="fas fa-clipboard-check"></i> Izin</span>');
        headerClass = 'modal-header-yellow';
    } else if (status === 'S') {
        modal.find('#modalStatus').html('<span class="detail-status-badge status-sakit"><i class="fas fa-heartbeat"></i> Sakit</span>');
        headerClass = 'modal-header-blue';
    } else {
        modal.find('#modalStatus').html('<span class="detail-status-badge status-alpha"><i class="fas fa-times-circle"></i> Alpha</span>');
        headerClass = 'modal-header-red';
    }

    modalHeader.removeClass('modal-header-green modal-header-yellow modal-header-blue modal-header-red modal-header-gray');
    modalHeader.addClass(headerClass);

    var absensiId = el.getAttribute('data-absensi-id');
    var studentId = el.getAttribute('data-student-id');
    modal.find('#modalEditBtn').attr('href', '{{ url("absensi") }}/' + absensiId + '/edit?siswa=' + studentId);

    var bsModal = new bootstrap.Modal(document.getElementById('detailAbsensiModal'));
    bsModal.show();
}

$(document).ready(function() {
    $('.abm-progress > span[data-w]').each(function() {
        var w = parseInt(this.getAttribute('data-w'), 10) || 0;
        setTimeout(function() { this.style.width = w + '%'; }.bind(this), 150);
    });

    var form = document.getElementById('riwayatFilterForm');
    var filterKelas = document.getElementById('filterKelas');
    var filterBulan = document.getElementById('filterBulan');
    if (filterKelas) {
        filterKelas.addEventListener('change', function() { if (this.value) form.submit(); });
    }
    if (filterBulan) {
        filterBulan.addEventListener('change', function() { if (filterKelas.value) form.submit(); });
    }
});
</script>
@endpush
