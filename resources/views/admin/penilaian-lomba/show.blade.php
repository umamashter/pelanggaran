@extends('layouts.main')
@section('title', 'Detail Penilaian Lomba')
@section('content')
@include('component.admin.lomba-workspace')

<style>
    .page-title-content { display: none !important; }
    .pl-mod { --pl-radius: 16px; }

    .pl-detail { max-width: 1240px; margin: 0 auto; }
    .pl-status-chip { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 999px; font-size: 11.5px; font-weight: 700; border: 1px solid transparent; }
    .pl-status-chip.ok { background: var(--lw-green-soft); color: var(--lw-green); border-color: var(--lw-green-border); }
    .pl-status-chip.info { background: var(--lw-navy-soft); color: var(--lw-primary); border-color: var(--lw-navy-border); }
    .pl-status-chip.warn { background: var(--lw-amber-soft); color: var(--lw-amber); border-color: var(--lw-amber-border); }
    .pl-status-chip.violet { background: var(--lw-violet-soft); color: var(--lw-violet); border-color: var(--lw-violet-border); }
    .pl-status-chip.red { background: var(--lw-red-soft); color: var(--lw-red); border-color: var(--lw-red-border); }
    .pl-layout { display: grid; grid-template-columns: minmax(0, 1.45fr) minmax(320px, .9fr); gap: 20px; align-items: start; }
    .pl-stack { display: flex; flex-direction: column; gap: 20px; }
    .pl-panel { background: var(--lw-card); border: 1px solid var(--lw-border); border-radius: 16px; box-shadow: var(--lw-shadow); overflow: hidden; position: relative; }
    .pl-panel::before { content: ""; position: absolute; inset: 0 0 auto 0; height: 1px; background: linear-gradient(90deg, transparent, rgba(43,60,120,.28), transparent); opacity: .9; pointer-events: none; }
    .pl-panel-head { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 18px 20px; border-bottom: 1px solid var(--lw-border); flex-wrap: wrap; }
    .pl-panel-head b { font-size: 14.5px; font-weight: 800; color: var(--lw-text); display: inline-flex; align-items: center; gap: 8px; }
    .pl-panel-head b i { color: var(--lw-primary); }
    .pl-panel-sub { font-size: 11.5px; color: var(--lw-text-3); margin-top: 2px; }
    .pl-panel-body { padding: 20px; }
    .pl-meta-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; }
    .pl-meta { display: flex; align-items: center; gap: 12px; padding: 16px; background: linear-gradient(180deg, rgba(255,255,255,.7), rgba(255,255,255,.52)); border: 1px solid var(--lw-border); border-radius: 16px; box-shadow: var(--lw-shadow); min-width: 0; backdrop-filter: blur(10px); }
    html.dark-mode .pl-meta { background: linear-gradient(180deg, rgba(255,255,255,.06), rgba(255,255,255,.04)); }
    .pl-meta-icon { flex-shrink: 0; width: 42px; height: 42px; border-radius: 14px; display: inline-flex; align-items: center; justify-content: center; font-size: 17px; }
    .pl-meta-icon.blue { background: var(--lw-navy-soft); color: var(--lw-primary); }
    .pl-meta-icon.green { background: var(--lw-green-soft); color: var(--lw-green); }
    .pl-meta-icon.amber { background: var(--lw-amber-soft); color: var(--lw-amber); }
    .pl-meta-icon.violet { background: var(--lw-violet-soft); color: var(--lw-violet); }
    .pl-meta .l { font-size: 10px; font-weight: 700; color: var(--lw-text-3); text-transform: uppercase; letter-spacing: .5px; }
    .pl-meta .v { font-size: 13.5px; font-weight: 800; color: var(--lw-text); margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .pl-total-card { padding: 22px; border-radius: 18px; background: linear-gradient(135deg, rgba(43,60,120,.12), rgba(231,166,21,.05)); border: 1px solid var(--lw-primary-border); box-shadow: var(--lw-shadow); }
    .pl-total-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .55px; color: var(--lw-text-3); }
    .pl-total-value { font-size: clamp(34px, 5vw, 48px); font-weight: 800; line-height: 1; letter-spacing: -1.5px; margin-top: 10px; color: var(--lw-text); font-variant-numeric: tabular-nums; }
    .pl-total-meta { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-top: 18px; }
    .pl-total-item { padding: 12px; border-radius: 14px; background: var(--lw-card); border: 1px solid var(--lw-border); }
    .pl-total-item .k { font-size: 10px; text-transform: uppercase; letter-spacing: .5px; color: var(--lw-text-3); font-weight: 700; }
    .pl-total-item .v { margin-top: 5px; font-size: 16px; font-weight: 800; color: var(--lw-text); }
    .pl-chart { margin-top: 18px; display: flex; align-items: end; gap: 12px; min-height: 184px; }
    .pl-chart-col { flex: 1 1 0; min-width: 0; display: flex; flex-direction: column; align-items: center; gap: 10px; }
    .pl-chart-bar-wrap { width: 100%; height: 140px; border-radius: 16px; background: linear-gradient(180deg, var(--lw-bg), transparent); display: flex; align-items: end; padding: 8px; }
    .pl-chart-bar { width: 100%; border-radius: 12px 12px 10px 10px; background: var(--lw-grad); min-height: 10px; box-shadow: inset 0 1px 0 rgba(255,255,255,.28); transition: height .55s cubic-bezier(.22,.61,.36,1); }
    .pl-chart-name { max-width: 100%; font-size: 11px; font-weight: 700; color: var(--lw-text-2); text-align: center; line-height: 1.35; }
    .pl-chart-score { font-size: 12px; font-weight: 800; color: var(--lw-text); }
    .pl-accordion { display: flex; flex-direction: column; gap: 12px; }
    .pl-judge-card { border: 1px solid var(--lw-border); border-radius: 16px; background: var(--lw-card); overflow: hidden; transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease; }
    .pl-judge-card:hover { border-color: var(--lw-primary-border); box-shadow: var(--lw-shadow); transform: translateY(-1px); }
    .pl-judge-card[open] { border-color: var(--lw-primary-border); box-shadow: var(--lw-shadow-lg); }
    .pl-judge-summary { list-style: none; display: flex; align-items: center; justify-content: space-between; gap: 14px; padding: 16px 18px; cursor: pointer; }
    .pl-judge-summary::-webkit-details-marker { display: none; }
    .pl-judge-main { display: flex; align-items: center; gap: 12px; min-width: 0; }
    .pl-avatar { width: 44px; height: 44px; border-radius: 14px; display: inline-flex; align-items: center; justify-content: center; background: var(--lw-grad); color: #fff; font-size: 16px; font-weight: 800; flex-shrink: 0; }
    .pl-judge-name { font-size: 13.5px; font-weight: 800; color: var(--lw-text); }
    .pl-judge-sub { font-size: 11.5px; color: var(--lw-text-3); margin-top: 2px; }
    .pl-judge-side { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; justify-content: flex-end; }
    .pl-judge-total { text-align: right; }
    .pl-judge-total .l { font-size: 10px; text-transform: uppercase; letter-spacing: .5px; color: var(--lw-text-3); font-weight: 700; }
    .pl-judge-total .v { margin-top: 3px; font-size: 18px; font-weight: 800; color: var(--lw-text); }
    .pl-caret { width: 34px; height: 34px; border-radius: 12px; border: 1px solid var(--lw-border); display: inline-flex; align-items: center; justify-content: center; color: var(--lw-text-2); background: var(--lw-bg); transition: transform .2s ease; }
    .pl-judge-card[open] .pl-caret { transform: rotate(180deg); color: var(--lw-primary); }
    .pl-judge-body { padding: 0 18px 18px; }
    .pl-breakdown { display: grid; gap: 10px; }
    .pl-breakdown-item { display: grid; grid-template-columns: minmax(0,1fr) 84px; gap: 14px; align-items: center; padding: 12px 14px; border-radius: 14px; background: var(--lw-bg); border: 1px solid var(--lw-border); }
    .pl-breakdown-head { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 8px; }
    .pl-breakdown-name { font-size: 12.5px; font-weight: 700; color: var(--lw-text); }
    .pl-breakdown-val { font-size: 12.5px; font-weight: 800; color: var(--lw-text); }
    .pl-breakdown-bar { height: 8px; border-radius: 999px; background: rgba(148,163,184,.16); overflow: hidden; }
    .pl-breakdown-bar span { display: block; height: 100%; border-radius: 999px; background: var(--lw-grad); }
    .pl-breakdown-score { font-size: 18px; font-weight: 800; color: var(--lw-primary); text-align: right; }
    .pl-ang-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 12px; }
    .pl-member-card { display: flex; align-items: center; gap: 12px; padding: 14px; border-radius: 16px; border: 1px solid var(--lw-border); background: var(--lw-bg); }
    .pl-member-avatar { width: 42px; height: 42px; border-radius: 14px; background: linear-gradient(135deg,#3b82f6,#2b3c78); color: #fff; display: inline-flex; align-items: center; justify-content: center; font-weight: 800; flex-shrink: 0; }
    .pl-member-meta { min-width: 0; }
    .pl-member-name { font-size: 13px; font-weight: 800; color: var(--lw-text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .pl-member-sub { font-size: 11.5px; color: var(--lw-text-3); margin-top: 2px; }
    .pl-hero-btn { text-decoration: none !important; }
    @media (max-width: 1199.98px) { .pl-layout { grid-template-columns: 1fr; } }
    @media (max-width: 767.98px) { .pl-meta-grid, .pl-total-meta { grid-template-columns: 1fr; } .pl-breakdown-item { grid-template-columns: 1fr; } .pl-judge-summary { align-items: flex-start; } .pl-judge-side { width: 100%; justify-content: space-between; } }
</style>

@php
    $pl = $penilaianLomba->pesertaLomba;
    $lomba = $pl->lomba ?? null;
    $jenis = $lomba->jenis ?? null;
    $isTim = $jenis === 'Tim';
    $kelompok = $pl->kelompokLomba;
    $nama = $isTim
        ? ($kelompok->nama_kelompok ?? '-')
        : ($pl->student->user->name ?? $pl->student->nama ?? '-');
    $sesiNama = $lomba->sesiLomba->nama ?? '-';

    $juriList = [];
    $matrix = [];
    foreach ($allPenilaian as $jg) {
        $jid = $jg->juri->id;
        $juriList[$jid] = [
            'nama'  => $jg->juri->guru->nama ?? ('Juri #' . $jid),
            'total' => (int) $jg->total,
            'short' => \Illuminate\Support\Str::limit($jg->juri->guru->nama ?? ('Juri #' . $jid), 14, ''),
            'initial' => mb_strtoupper(mb_substr(trim($jg->juri->guru->nama ?? ('Juri ' . $jid)), 0, 1)),
        ];
        foreach ($jg->penilaian as $p) {
            $aid = $p->aspek_penilaian_id;
            if (!isset($matrix[$aid])) {
                $matrix[$aid] = ['nama' => ($p->aspekPenilaian->nama_aspek ?? 'Aspek #' . $aid)];
            }
            $matrix[$aid][$jid] = (float) $p->nilai;
        }
    }
    foreach ($matrix as $aid => &$row) {
        $vals = [];
        foreach ($juriList as $jid => $j) {
            if (isset($row[$jid])) $vals[] = (float) $row[$jid];
        }
        $row['avg'] = count($vals) ? array_sum($vals) / count($vals) : 0;
    }
    unset($row);

    $maxJudgeTotal = collect($juriList)->max('total') ?: 1;
    $rataRataJuri = $jumlahJuri ? round($totalSemua / $jumlahJuri, 2) : 0;
    $hasil = (bool) $pl->hasil;
    $locked = (bool) $penilaianLomba->is_haflah_selesai;
    $totalJuri = (int) ($lomba->juri->count() ?? 0);
    if ($hasil) {
        $status = ['label' => 'Sudah Diproses Hasil', 'cls' => 'violet', 'ic' => 'bi-box-arrow-up-right'];
    } elseif ($locked) {
        $status = ['label' => 'Terkunci', 'cls' => 'red', 'ic' => 'bi-lock-fill'];
    } elseif ($totalJuri > 0 && $jumlahJuri >= $totalJuri) {
        $status = ['label' => 'Lengkap Dinilai', 'cls' => 'ok', 'ic' => 'bi-check-circle-fill'];
    } else {
        $status = ['label' => 'Sebagian Dinilai', 'cls' => 'info', 'ic' => 'bi-hourglass-split'];
    }
    $statusBadgeCls = $status['cls'] === 'ok' ? 'lw-hero-badge--ok' : ($status['cls'] === 'warn' ? 'lw-hero-badge--warn' : '');
@endphp

<div class="lw-mod pl-mod">
    <div class="pl-detail">

        {{-- HERO --}}
        <div class="lw-hero">
            <div class="lw-hero-grid">
                <div class="lw-hero-left">
                    <span class="lw-hero-icon"><i class="bi bi-star-fill"></i></span>
                    <div>
                        <h1 class="lw-hero-title">{{ $nama }}</h1>
                        <p class="lw-hero-sub">{{ $lomba->nama ?? '-' }} &middot; {{ $jenis ?? '-' }} &middot; Sesi {{ $sesiNama }}</p>
                        <div class="lw-hero-badges">
                            <span class="lw-hero-badge {{ $statusBadgeCls }}"><i class="bi {{ $status['ic'] }}"></i>{{ $status['label'] }}</span>
                            <span class="lw-hero-badge"><i class="bi bi-gavel"></i>{{ $jumlahJuri }} juri menilai</span>
                            <span class="lw-hero-badge"><i class="bi bi-calculator"></i>Total {{ $totalSemua }}</span>
                        </div>
                    </div>
                </div>
                <div class="lw-hero-right">
                    @if(!$locked && !$hasil)
                    <a href="{{ route('penilaian-lomba.edit', $penilaianLomba->id) }}" class="lw-btn lw-btn--light pl-hero-btn"><i class="bi bi-pencil"></i> Edit Nilai</a>
                    @endif
                    <a href="{{ route('penilaian-lomba.index') }}" class="lw-btn lw-btn--light pl-hero-btn"><i class="bi bi-arrow-left"></i> Kembali</a>
                </div>
            </div>
        </div>

        @if($locked)
            <div class="lw-alert lw-alert--warn"><i class="bi bi-lock-fill"></i> Haflatul Imtihan sudah selesai — data penilaian terkunci dan tidak dapat diubah.</div>
        @elseif($hasil)
            <div class="lw-alert lw-alert--accent"><i class="bi bi-box-arrow-up-right"></i> Hasil lomba sudah diproses — data penilaian ini sudah dikunci.</div>
        @endif

        {{-- KPI --}}
        <div class="lw-kpi-grid">
            <div class="lw-card lw-kpi"><span class="lw-kpi-icon violet"><i class="bi bi-calculator"></i></span><div class="lw-kpi-main"><div class="lw-kpi-num" data-count="{{ $totalSemua }}">{{ $totalSemua }}</div><div class="lw-kpi-label">Total Nilai</div><div class="lw-kpi-sub">Semua juri digabung</div></div></div>
            <div class="lw-card lw-kpi"><span class="lw-kpi-icon navy"><i class="bi bi-gavel"></i></span><div class="lw-kpi-main"><div class="lw-kpi-num" data-count="{{ $jumlahJuri }}">{{ $jumlahJuri }}</div><div class="lw-kpi-label">Juri Menilai</div><div class="lw-kpi-sub">Dari {{ $totalJuri }} juri lomba</div></div></div>
            <div class="lw-card lw-kpi"><span class="lw-kpi-icon sky"><i class="bi bi-graph-up"></i></span><div class="lw-kpi-main"><div class="lw-kpi-num">{{ $rataRataJuri }}</div><div class="lw-kpi-label">Rata-rata / Juri</div><div class="lw-kpi-sub">Total dibagi jumlah juri</div></div></div>
            <div class="lw-card lw-kpi"><span class="lw-kpi-icon green"><i class="bi bi-list-check"></i></span><div class="lw-kpi-main"><div class="lw-kpi-num" data-count="{{ count($matrix) }}">{{ count($matrix) }}</div><div class="lw-kpi-label">Aspek Dinilai</div><div class="lw-kpi-sub">Bobot rubrik lomba</div></div></div>
        </div>

        <div class="pl-layout">
            <div class="pl-stack">
                <div class="pl-panel">
                    <div class="pl-panel-head">
                        <div>
                            <b><i class="bi bi-grid-1x2"></i> Ringkasan Penilaian</b>
                            <div class="pl-panel-sub">Konteks lomba, jenis penilaian, dan status lock dalam satu dashboard detail.</div>
                        </div>
                        <span class="pl-status-chip {{ $status['cls'] }}"><i class="bi {{ $status['ic'] }}"></i>{{ $status['label'] }}</span>
                    </div>
                    <div class="pl-panel-body">
                        <div class="pl-meta-grid">
                            <div class="pl-meta"><span class="pl-meta-icon blue"><i class="bi bi-trophy"></i></span><div><div class="l">Lomba</div><div class="v" title="{{ $lomba->nama ?? '-' }}">{{ $lomba->nama ?? '-' }}</div></div></div>
                            <div class="pl-meta"><span class="pl-meta-icon green"><i class="bi bi-calendar-event"></i></span><div><div class="l">Sesi</div><div class="v">{{ $sesiNama }}</div></div></div>
                            <div class="pl-meta"><span class="pl-meta-icon {{ $isTim ? 'amber' : 'blue' }}"><i class="bi {{ $isTim ? 'bi-people' : 'bi-person' }}"></i></span><div><div class="l">Jenis</div><div class="v">{{ $jenis ?? '-' }}</div></div></div>
                            <div class="pl-meta"><span class="pl-meta-icon violet"><i class="bi bi-person-badge"></i></span><div><div class="l">{{ $isTim ? 'Kelompok' : 'Peserta' }}</div><div class="v" title="{{ $nama }}">{{ $nama }}</div></div></div>
                        </div>
                    </div>
                </div>

                <div class="pl-panel">
                    <div class="pl-panel-head">
                        <div>
                            <b><i class="bi bi-clipboard-data"></i> Breakdown Juri</b>
                            <div class="pl-panel-sub">Gunakan accordion untuk meninjau total, status, dan rincian aspek per juri.</div>
                        </div>
                        <span class="lw-chip lw-chip--navy"><i class="bi bi-hash"></i>{{ $jumlahJuri }} juri</span>
                    </div>
                    <div class="pl-panel-body">
                        @if($allPenilaian->isEmpty())
                            <div class="lw-empty">
                                <div class="lw-empty-illus"><div class="ring"></div><div class="core"><i class="bi bi-clipboard"></i></div></div>
                                <div class="lw-empty-title">Belum ada penilaian</div>
                                <div class="lw-empty-sub">Juri belum menginput nilai untuk peserta ini.</div>
                            </div>
                        @else
                            <div class="pl-accordion">
                                @foreach($allPenilaian as $jg)
                                @php
                                    $juriNamaFull = $jg->juri->guru->nama ?? ('Juri #' . $jg->juri->id);
                                    $juriStatus = $jg->total > 0 ? ['label' => 'Sudah Menilai', 'cls' => 'ok', 'ic' => 'bi-check-circle-fill'] : ['label' => 'Belum Lengkap', 'cls' => 'warn', 'ic' => 'bi-hourglass-split'];
                                @endphp
                                <details class="pl-judge-card" {{ $loop->first ? 'open' : '' }}>
                                    <summary class="pl-judge-summary">
                                        <div class="pl-judge-main">
                                            <span class="pl-avatar">{{ mb_strtoupper(mb_substr(trim($juriNamaFull), 0, 1)) }}</span>
                                            <div>
                                                <div class="pl-judge-name">{{ $juriNamaFull }}</div>
                                                <div class="pl-judge-sub">{{ $jg->penilaian->count() }} aspek dinilai</div>
                                            </div>
                                        </div>
                                        <div class="pl-judge-side">
                                            <span class="pl-status-chip {{ $juriStatus['cls'] }}"><i class="bi {{ $juriStatus['ic'] }}"></i>{{ $juriStatus['label'] }}</span>
                                            <div class="pl-judge-total">
                                                <div class="l">Jumlah Nilai</div>
                                                <div class="v">{{ number_format($jg->total, 1) }}</div>
                                            </div>
                                            <span class="pl-caret"><i class="bi bi-chevron-down"></i></span>
                                        </div>
                                    </summary>
                                    <div class="pl-judge-body">
                                        <div class="pl-breakdown">
                                            @foreach($jg->penilaian as $detail)
                                            @php $nilai = (float) $detail->nilai; @endphp
                                            <div class="pl-breakdown-item">
                                                <div>
                                                    <div class="pl-breakdown-head">
                                                        <span class="pl-breakdown-name">{{ $detail->aspekPenilaian->nama_aspek ?? 'Aspek Penilaian' }}</span>
                                                        <span class="pl-breakdown-val">{{ number_format($nilai, 1) }}/100</span>
                                                    </div>
                                                    <div class="pl-breakdown-bar"><span style="width:{{ min(100, $nilai) }}%;"></span></div>
                                                </div>
                                                <div class="pl-breakdown-score">{{ number_format($nilai, 0) }}</div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </details>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                @if($isTim && $kelompok && $kelompok->anggota->count())
                <div class="pl-panel">
                    <div class="pl-panel-head">
                        <div>
                            <b><i class="bi bi-people"></i> Panel Anggota Tim</b>
                            <div class="pl-panel-sub">Lihat anggota kelompok beserta konteks kelas dan jenjang secara cepat.</div>
                        </div>
                        @if($kelompok->id)
                        <a href="{{ route('kelompok-lomba.show', $kelompok->id) }}" class="lw-btn lw-btn--soft lw-btn--sm"><i class="bi bi-box-arrow-up-right"></i> Detail Kelompok</a>
                        @endif
                    </div>
                    <div class="pl-panel-body">
                        <div class="pl-ang-grid">
                            @foreach($kelompok->anggota as $anggota)
                            @php
                                $anggotaNama = $anggota->student->user->name ?? $anggota->student->nama ?? 'Anggota';
                                $kelasNama = $anggota->student->kelas->nama_kelas ?? $anggota->student->kelas->nama ?? '-';
                                $jenjangNama = $anggota->student->kelas->jenjang->nama ?? '-';
                            @endphp
                            <div class="pl-member-card">
                                <span class="pl-member-avatar">{{ mb_strtoupper(mb_substr(trim($anggotaNama), 0, 1)) }}</span>
                                <div class="pl-member-meta">
                                    <div class="pl-member-name">{{ $anggotaNama }}</div>
                                    <div class="pl-member-sub">Kelas {{ $kelasNama }} &middot; Jenjang {{ $jenjangNama }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <div class="pl-stack">
                <div class="pl-total-card">
                    <div class="pl-total-label">Total Score</div>
                    <div class="pl-total-value">{{ number_format($totalSemua, 1) }}</div>
                    <div class="pl-total-meta">
                        <div class="pl-total-item"><div class="k">Rata-rata</div><div class="v">{{ number_format($rataRataJuri, 1) }}</div></div>
                        <div class="pl-total-item"><div class="k">Jumlah Juri</div><div class="v">{{ $jumlahJuri }}</div></div>
                        <div class="pl-total-item"><div class="k">Jumlah Aspek</div><div class="v">{{ count($matrix) }}</div></div>
                    </div>
                    @if(count($juriList))
                    <div class="pl-chart" aria-label="Distribusi skor per juri">
                        @foreach($juriList as $jid => $j)
                        @php $height = $maxJudgeTotal > 0 ? max(10, round(($j['total'] / $maxJudgeTotal) * 100)) : 10; @endphp
                        <div class="pl-chart-col">
                            <div class="pl-chart-score">{{ number_format($j['total'], 1) }}</div>
                            <div class="pl-chart-bar-wrap"><div class="pl-chart-bar" style="height: {{ $height }}%;"></div></div>
                            <div class="pl-chart-name" title="{{ $j['nama'] }}">{{ $j['short'] }}</div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    document.querySelectorAll('.lw-kpi-num[data-count]').forEach(function (el) {
        if (window.LW && LW.counter) LW.counter(el);
    });
    document.querySelectorAll('.lw-btn').forEach(function (b) {
        b.addEventListener('click', function (e) { if (window.LW && LW.ripple) LW.ripple(e); });
    });
})();
</script>
@endpush
