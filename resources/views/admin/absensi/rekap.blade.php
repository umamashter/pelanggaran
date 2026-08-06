@extends('layouts.main')
@section('title','Rekap Absensi')
@section('content')
@include('component.admin.absensi-module')
<style>
    .page-title-content { display: none !important; }
    .abm-rekap-hero { padding: 20px 26px; margin-bottom: 20px; border-radius: 20px; }
    .abm-distbar {
        display: flex; height: 12px; border-radius: 8px; overflow: hidden; background: var(--ab-border);
    }
    .abm-distbar span { height: 100%; transition: width 1s cubic-bezier(.22,1,.36,1); }
    .abm-distbar .h { background: linear-gradient(90deg, #16a34a, #4ade80); }
    .abm-distbar .i { background: linear-gradient(90deg, #d97706, #fbbf24); }
    .abm-distbar .s { background: linear-gradient(90deg, #0284c7, #38bdf8); }
    .abm-distbar .a { background: linear-gradient(90deg, #dc2626, #f87171); }
    .abm-dist-legend { display: flex; flex-wrap: wrap; gap: 14px; font-size: 11.5px; color: var(--ab-text-2); font-weight: 600; }
    .abm-dist-legend i { font-size: 9px; margin-right: 5px; }
    .abm-rank-item {
        display: flex; align-items: center; gap: 12px; padding: 9px 12px; border-radius: 12px;
        background: var(--ab-border-soft); margin-bottom: 8px;
    }
    .abm-rank-item:last-child { margin-bottom: 0; }
    .abm-medal {
        width: 30px; height: 30px; border-radius: 10px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px; color: #fff;
    }
    .abm-medal.g1 { background: linear-gradient(135deg, #f59e0b, #fbbf24); box-shadow: 0 4px 10px -2px rgba(245,158,11,.5); }
    .abm-medal.g2 { background: linear-gradient(135deg, #94a3b8, #cbd5e1); box-shadow: 0 4px 10px -2px rgba(148,163,184,.5); }
    .abm-medal.g3 { background: linear-gradient(135deg, #d97706, #f59e0b); box-shadow: 0 4px 10px -2px rgba(217,119,6,.5); }
    .abm-medal.g4 { background: linear-gradient(135deg, #2563eb, #60a5fa); box-shadow: 0 4px 10px -2px rgba(37,99,235,.4); }
    .abm-rekap-table { width: 100%; border-collapse: separate; border-spacing: 0 6px; }
    .abm-rekap-table thead th {
        font-size: 10.5px; font-weight: 800; text-transform: uppercase; letter-spacing: .6px;
        color: var(--ab-text-3); padding: 2px 12px 6px; white-space: nowrap; text-align: center;
    }
    .abm-rekap-table thead th:first-child { text-align: left; }
    .abm-rekap-table tbody td {
        background: var(--ab-border-soft); padding: 10px 12px; font-size: 13px; color: var(--ab-text-2);
        border-top: 1px solid var(--ab-border); border-bottom: 1px solid var(--ab-border); vertical-align: middle;
    }
    .abm-rekap-table tbody td:first-child { border-left: 1px solid var(--ab-border); border-top-left-radius: 12px; border-bottom-left-radius: 12px; text-align: left; }
    .abm-rekap-table tbody td:last-child { border-right: 1px solid var(--ab-border); border-top-right-radius: 12px; border-bottom-right-radius: 12px; }
    .abm-rekap-table tbody tr:hover td { background: var(--ab-primary-soft); }
    .abm-rekap-table tfoot td {
        padding: 12px; font-size: 13px; font-weight: 800; color: var(--ab-text);
        background: var(--ab-primary-soft); border-radius: 12px; text-align: center;
    }
    .abm-badge-count {
        display: inline-flex; align-items: center; justify-content: center; min-width: 30px;
        padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 800; font-variant-numeric: tabular-nums;
    }
    .abm-badge-count.h { background: var(--ab-green-soft); color: var(--ab-green); }
    .abm-badge-count.i { background: var(--ab-amber-soft); color: var(--ab-amber); }
    .abm-badge-count.s { background: var(--ab-sky-soft); color: var(--ab-sky); }
    .abm-badge-count.a { background: var(--ab-red-soft); color: var(--ab-red); }
    .abm-pct-cell { display: flex; align-items: center; gap: 8px; }
    .abm-pct-cell .abm-progress { flex: 1; min-width: 60px; }
    .abm-pct-cell b { font-size: 12px; color: var(--ab-text); min-width: 38px; text-align: right; font-variant-numeric: tabular-nums; }
</style>

<div class="abs-mod master-absensi-page">
    {{-- ===== HERO ===== --}}
    <div class="abm-hero abm-rekap-hero">
        <div class="abm-hero-grid"></div>
        <div class="abm-hero-row">
            <div class="abm-hero-left">
                <div class="d-flex align-items-center gap-3">
                    <div class="abm-hero-icon"><i class="fas fa-file-chart-line"></i></div>
                    <div>
                        <h3>Rekap Absensi Siswa</h3>
                        <p class="abm-hero-sub">Analisis kehadiran siswa per kelas dan periode.</p>
                    </div>
                </div>
            </div>
            <div class="abm-hero-badges" style="margin-top:0;">
                <span class="abm-hero-badge"><i class="fas fa-graduation-cap"></i> {{ $tahunAktif->tahun_ajaran }}</span>
                @if(isset($tahunAktif->semesterAktif))
                <span class="abm-hero-badge"><i class="fas fa-bookmark"></i> {{ $tahunAktif->semesterAktif->nama ?? '-' }}</span>
                @endif
            </div>
        </div>
    </div>

    {{-- ===== FILTER ===== --}}
    <div class="abm-card" style="padding:18px 20px;margin-bottom:20px;">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="abm-field-label"><i class="fas fa-chalkboard"></i>Kelas</label>
                <select name="kelas_id" class="abm-control" required>
                    <option value="">Pilih Kelas</option>
                    @foreach($kelasList as $item)
                    <option value="{{ $item->id }}" {{ request('kelas_id') == $item->id ? 'selected' : '' }}>{{ $item->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="abm-field-label"><i class="fas fa-calendar"></i>Bulan</label>
                <input type="month" name="bulan" value="{{ request('bulan', $bulan ?? '') }}" class="abm-control">
            </div>
            <div class="col-md-2">
                <label class="abm-field-label"><i class="fas fa-calendar-day"></i>Dari Tanggal</label>
                <input type="date" name="tanggal_awal" value="{{ request('tanggal_awal', $tanggalAwal ?? '') }}" class="abm-control">
            </div>
            <div class="col-md-2">
                <label class="abm-field-label"><i class="fas fa-calendar-day"></i>Sampai Tanggal</label>
                <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir', $tanggalAkhir ?? '') }}" class="abm-control">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="abm-btn abm-btn--solid" style="height:46px;"><i class="fas fa-search"></i> Filter</button>
                @if(request('kelas_id') && !empty($rekapData))
                <a href="{{ route('absensi.rekap.pdf', ['kelas_id' => request('kelas_id'), 'bulan' => request('bulan'), 'tanggal_awal' => request('tanggal_awal'), 'tanggal_akhir' => request('tanggal_akhir')]) }}" target="_blank" class="abm-btn abm-btn--outline" style="height:46px;" title="Cetak PDF">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>
                <button type="button" class="abm-btn abm-btn--soft" style="height:46px;" id="btnCsv" title="Unduh Excel (CSV)"><i class="fas fa-file-excel"></i> Excel</button>
                <button type="button" class="abm-btn abm-btn--soft" style="height:46px;" id="btnPrint" title="Cetak"><i class="fas fa-print"></i> Print</button>
                @endif
            </div>
        </form>
    </div>

    @if(request('kelas_id'))
        @if(empty($rekapData))
        <div class="abm-card" style="margin-bottom:10px;">
            <div class="abm-empty">
                <i class="fas fa-clipboard"></i>
                <div class="abm-empty-title">Belum Ada Data Absensi</div>
                <div class="abm-empty-sub">Belum ada data absensi untuk kelas ini pada periode yang dipilih.</div>
            </div>
        </div>
        @else
        @php
            $sumH = collect($rekapData)->sum('hadir');
            $sumI = collect($rekapData)->sum('izin');
            $sumS = collect($rekapData)->sum('sakit');
            $sumA = collect($rekapData)->sum('alpa');
            $sumTot = $sumH + $sumI + $sumS + $sumA;
            $pctH = $sumTot > 0 ? round($sumH / $sumTot * 100) : 0;
            $pctI = $sumTot > 0 ? round($sumI / $sumTot * 100) : 0;
            $pctS = $sumTot > 0 ? round($sumS / $sumTot * 100) : 0;
            $pctA = $sumTot > 0 ? round($sumA / $sumTot * 100) : 0;
            $ranked = collect($rekapData)->map(function ($d) {
                $t = $d['hadir'] + $d['izin'] + $d['sakit'] + $d['alpa'];
                return ['nama' => $d['siswa']->nama, 'nisn' => $d['siswa']->nisn, 'hadir' => $d['hadir'], 'pct' => $t > 0 ? round($d['hadir'] / $t * 100, 1) : 0];
            })->sortByDesc('hadir')->sortByDesc('pct')->take(5)->values();
        @endphp

        {{-- KPI --}}
        <div class="abm-kpi-grid">
            <div class="abm-kpi"><i class="fas fa-check-circle abm-kpi-watermark"></i>
                <div class="abm-kpi-icon green"><i class="fas fa-check-circle"></i></div>
                <div class="abm-kpi-info"><div class="abm-kpi-num abm-num">{{ $sumH }}</div><div class="abm-kpi-label">Hadir</div></div>
            </div>
            <div class="abm-kpi"><i class="fas fa-door-open abm-kpi-watermark"></i>
                <div class="abm-kpi-icon amber"><i class="fas fa-door-open"></i></div>
                <div class="abm-kpi-info"><div class="abm-kpi-num abm-num">{{ $sumI }}</div><div class="abm-kpi-label">Izin</div></div>
            </div>
            <div class="abm-kpi"><i class="fas fa-thermometer-half abm-kpi-watermark"></i>
                <div class="abm-kpi-icon sky"><i class="fas fa-thermometer-half"></i></div>
                <div class="abm-kpi-info"><div class="abm-kpi-num abm-num">{{ $sumS }}</div><div class="abm-kpi-label">Sakit</div></div>
            </div>
            <div class="abm-kpi"><i class="fas fa-user-slash abm-kpi-watermark"></i>
                <div class="abm-kpi-icon rose"><i class="fas fa-user-slash"></i></div>
                <div class="abm-kpi-info"><div class="abm-kpi-num abm-num">{{ $sumA }}</div><div class="abm-kpi-label">Alpha</div></div>
            </div>
        </div>

        {{-- Analisis + Ranking --}}
        <div class="row g-3 mb-3">
            <div class="col-lg-7">
                <div class="abm-card abm-card--lift" style="padding:18px 20px;height:100%;">
                    <div class="abm-section-title mb-3"><i class="fas fa-chart-pie"></i> Distribusi Kehadiran</div>
                    <div class="abm-distbar" data-bars>
                        <span class="h" data-w="{{ $pctH }}" title="Hadir {{ $pctH }}%"></span>
                        <span class="i" data-w="{{ $pctI }}" title="Izin {{ $pctI }}%"></span>
                        <span class="s" data-w="{{ $pctS }}" title="Sakit {{ $pctS }}%"></span>
                        <span class="a" data-w="{{ $pctA }}" title="Alpha {{ $pctA }}%"></span>
                    </div>
                    <div class="abm-dist-legend mt-3">
                        <span><i class="fas fa-circle" style="color:#16a34a;"></i> Hadir {{ $pctH }}%</span>
                        <span><i class="fas fa-circle" style="color:#d97706;"></i> Izin {{ $pctI }}%</span>
                        <span><i class="fas fa-circle" style="color:#0284c7;"></i> Sakit {{ $pctS }}%</span>
                        <span><i class="fas fa-circle" style="color:#dc2626;"></i> Alpha {{ $pctA }}%</span>
                    </div>
                    @if($effectiveDays > 0)
                    <div class="d-flex align-items-center gap-2 mt-4" style="padding:10px 14px;background:var(--ab-green-soft);border-radius:12px;border:1px solid var(--ab-green-border);font-size:12.5px;color:var(--ab-green);font-weight:600;">
                        <i class="fas fa-calendar-check"></i>
                        <span>Hari Efektif: <strong>{{ $effectiveDays }}</strong> hari (tidak termasuk hari Jumat)</span>
                    </div>
                    @endif
                </div>
            </div>
            <div class="col-lg-5">
                <div class="abm-card abm-card--lift" style="padding:18px 20px;height:100%;">
                    <div class="abm-section-title mb-3"><i class="fas fa-trophy"></i> Peringkat Kehadiran</div>
                    @foreach($ranked as $i => $r)
                    <div class="abm-rank-item">
                        <div class="abm-medal g{{ min($i + 1, 4) }}">{{ $i + 1 }}</div>
                        <div style="flex:1;min-width:0;">
                            <div style="font-size:12.5px;font-weight:700;color:var(--ab-text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $r['nama'] }}</div>
                            <div style="font-size:10.5px;color:var(--ab-text-3);">NISN {{ $r['nisn'] }} · {{ $r['hadir'] }} hadir</div>
                        </div>
                        <strong style="color:var(--ab-green);font-size:13px;">{{ $r['pct'] }}%</strong>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="abm-card" style="padding:18px 20px 22px;margin-bottom:10px;">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <div class="abm-section-title">
                    <i class="fas fa-table"></i> Rekap {{ $kelas->nama_kelas ?? '' }}
                    <span class="abm-chip abm-chip--blue">{{ collect($rekapData)->count() }}</span>
                </div>
                <span class="abm-chip abm-chip--muted"><i class="fas fa-user-graduate"></i> {{ count($rekapData) }} siswa</span>
            </div>
            <div style="overflow-x:auto;">
                <table class="abm-rekap-table" id="rekapTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th style="text-align:left;">Nama</th>
                            <th>Hadir</th>
                            <th>Izin</th>
                            <th>Sakit</th>
                            <th>Alpha</th>
                            <th>Total</th>
                            <th>Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $rTot = ['h'=>0,'i'=>0,'s'=>0,'a'=>0]; @endphp
                        @foreach($rekapData as $data)
                        @php
                            $rTot['h'] += $data['hadir']; $rTot['i'] += $data['izin'];
                            $rTot['s'] += $data['sakit']; $rTot['a'] += $data['alpa'];
                            $t = $data['total'];
                            $pct = $t > 0 ? round($data['hadir'] / $t * 100) : 0;
                        @endphp
                        <tr>
                            <td style="text-align:center;width:44px;color:var(--ab-text-3);font-weight:600;">{{ $loop->iteration }}</td>
                            <td>
                                <div style="font-weight:700;color:var(--ab-text);font-size:13px;">{{ $data['siswa']->nama }}</div>
                                <div style="font-size:10.5px;color:var(--ab-text-3);">NISN {{ $data['siswa']->nisn }}</div>
                            </td>
                            <td style="text-align:center;"><span class="abm-badge-count h">{{ $data['hadir'] }}</span></td>
                            <td style="text-align:center;"><span class="abm-badge-count i">{{ $data['izin'] }}</span></td>
                            <td style="text-align:center;"><span class="abm-badge-count s">{{ $data['sakit'] }}</span></td>
                            <td style="text-align:center;"><span class="abm-badge-count a">{{ $data['alpa'] }}</span></td>
                            <td style="text-align:center;"><strong style="color:var(--ab-text);">{{ $t }}</strong></td>
                            <td style="min-width:140px;">
                                <div class="abm-pct-cell">
                                    <div class="abm-progress"><span data-w="{{ $pct }}" style="background:{{ $pct >= 80 ? 'linear-gradient(90deg,#16a34a,#4ade80)' : ($pct >= 60 ? 'linear-gradient(90deg,#d97706,#fbbf24)' : 'linear-gradient(90deg,#dc2626,#f87171)') }};"></span></div>
                                    <b>{{ $pct }}%</b>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2">TOTAL</td>
                            <td style="color:var(--ab-green);">{{ $rTot['h'] }}</td>
                            <td style="color:var(--ab-amber);">{{ $rTot['i'] }}</td>
                            <td style="color:var(--ab-sky);">{{ $rTot['s'] }}</td>
                            <td style="color:var(--ab-red);">{{ $rTot['a'] }}</td>
                            <td style="color:var(--ab-text);">{{ $sumTot }}</td>
                            <td style="color:var(--ab-green);">{{ $pctH }}%</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        @endif
    @else
    <div class="abm-card" style="margin-bottom:10px;">
        <div class="abm-empty">
            <i class="fas fa-filter"></i>
            <div class="abm-empty-title">Pilih Kelas Terlebih Dahulu</div>
            <div class="abm-empty-sub">Gunakan filter di atas untuk menampilkan rekap absensi siswa.</div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.abm-distbar[data-bars] span[data-w]').each(function() {
        var w = parseInt(this.getAttribute('data-w'), 10) || 0;
        setTimeout(function() { this.style.width = w + '%'; }.bind(this), 150);
    });
    $('.abm-pct-cell .abm-progress > span[data-w]').each(function() {
        var w = parseInt(this.getAttribute('data-w'), 10) || 0;
        setTimeout(function() { this.style.width = w + '%'; }.bind(this), 150);
    });

    document.getElementById('btnCsv').addEventListener('click', function() {
        var rows = [['No', 'NISN', 'Nama', 'Hadir', 'Izin', 'Sakit', 'Alpha', 'Total', 'Kehadiran(%)']];
        $('#rekapTable tbody tr').each(function() {
            var cells = [];
            $(this).find('td').each(function() {
                var txt = $(this).text().trim().replace(/\s+/g, ' ');
                cells.push(txt.replace(/,/g, '.'));
            });
            rows.push(cells);
        });
        var csv = rows.map(function(r) { return r.join(','); }).join('\n');
        var blob = new Blob(["\ufeff" + csv], { type: 'text/csv;charset=utf-8;' });
        var link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'rekap-absensi.csv';
        link.click();
        URL.revokeObjectURL(link.href);
    });

    document.getElementById('btnPrint').addEventListener('click', function() { window.print(); });
});
</script>
@endpush
