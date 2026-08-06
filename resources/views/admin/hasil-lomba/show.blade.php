@extends('layouts.main')
@section('title', 'Detail Hasil Lomba')

@push('css')
<style>
    .page-title-content { display: none !important; }
    .hl-mod { --hl-radius: 16px; }
    .hl-wrap { max-width: 1280px; margin: 0 auto; }
    .hl-layout { display: grid; grid-template-columns: minmax(0, 1.45fr) minmax(320px, .85fr); gap: 20px; align-items: start; }
    .hl-panel, .hl-summary-card { background: var(--lw-card); border: 1px solid var(--lw-border); border-radius: 18px; box-shadow: var(--lw-shadow); overflow: hidden; }
    .hl-panel-head { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 18px 20px; border-bottom: 1px solid var(--lw-border); flex-wrap: wrap; }
    .hl-panel-head b { font-size: 14.5px; font-weight: 800; color: var(--lw-text); display: inline-flex; align-items: center; gap: 8px; }
    .hl-panel-head b i { color: var(--lw-primary); }
    .hl-panel-sub { font-size: 11.5px; color: var(--lw-text-3); margin-top: 2px; }
    .hl-panel-body { padding: 20px; }
    .hl-status { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 999px; font-size: 11.5px; font-weight: 700; border: 1px solid transparent; }
    .hl-status.green { background: var(--lw-green-soft); color: var(--lw-green); border-color: var(--lw-green-border); }
    .hl-status.blue { background: var(--lw-primary-soft); color: var(--lw-primary); border-color: var(--lw-primary-border); }
    .hl-status.violet { background: var(--lw-violet-soft); color: var(--lw-violet); border-color: var(--lw-violet-border); }
    .hl-summary-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .hl-summary-item { padding: 14px; border-radius: 16px; background: var(--lw-bg); border: 1px solid var(--lw-border); }
    .hl-summary-item .k { font-size: 10px; text-transform: uppercase; letter-spacing: .5px; color: var(--lw-text-3); font-weight: 700; }
    .hl-summary-item .v { margin-top: 4px; font-size: 15px; font-weight: 800; color: var(--lw-text); }
    .hl-podium-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; }
    .hl-podium-card { display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; min-height: 210px; padding: 18px 14px; border-radius: 20px; border: 1px solid var(--lw-border); background: var(--lw-bg); }
    .hl-podium-card .icon { font-size: 30px; line-height: 1; }
    .hl-podium-card .rank { margin-top: 10px; font-size: 11px; text-transform: uppercase; letter-spacing: .5px; color: var(--lw-text-3); font-weight: 700; }
    .hl-podium-card .name { margin-top: 8px; font-size: 15px; font-weight: 800; color: var(--lw-text); }
    .hl-podium-card .score { margin-top: 4px; font-size: 17px; font-weight: 800; color: var(--lw-primary); }
    .hl-podium-card.gold { background: linear-gradient(180deg,#fff8db,#fef3c7); border-color: rgba(217,119,6,.28); }
    .hl-podium-card.silver { background: linear-gradient(180deg,#f8fafc,#e2e8f0); }
    .hl-podium-card.bronze { background: linear-gradient(180deg,#ffedd5,#fed7aa); }
    .hl-compare { display: grid; gap: 10px; }
    .hl-compare-item { display: grid; grid-template-columns: minmax(0,1fr) 120px; gap: 12px; align-items: center; padding: 12px 14px; border-radius: 16px; background: var(--lw-card); border: 1px solid var(--lw-border); }
    .hl-compare-head { display: flex; justify-content: space-between; gap: 10px; margin-bottom: 8px; }
    .hl-compare-name { font-size: 12.5px; font-weight: 700; color: var(--lw-text); }
    .hl-compare-val { font-size: 12px; font-weight: 800; color: var(--lw-text); }
    .hl-compare-bar { height: 8px; border-radius: 999px; background: rgba(148,163,184,.16); overflow: hidden; }
    .hl-compare-bar span { display: block; height: 100%; border-radius: 999px; background: var(--lw-grad); }
    .hl-compare-score { text-align: right; font-size: 18px; font-weight: 800; color: var(--lw-primary); }
    .hl-table-list { display: grid; gap: 10px; }
    .hl-table-item { display: grid; grid-template-columns: 64px minmax(0,1fr) 110px 120px; gap: 12px; align-items: center; padding: 14px; border-radius: 16px; border: 1px solid var(--lw-border); background: var(--lw-bg); }
    .hl-rank { width: 48px; height: 48px; border-radius: 16px; display: inline-flex; align-items: center; justify-content: center; background: var(--lw-card); border: 1px solid var(--lw-border); font-weight: 800; color: var(--lw-text); }
    .hl-name { font-size: 13.5px; font-weight: 800; color: var(--lw-text); }
    .hl-sub { font-size: 11.5px; color: var(--lw-text-3); margin-top: 2px; }
    .hl-score { font-size: 16px; font-weight: 800; color: var(--lw-primary); text-align: right; }
    .hl-badge { display: inline-flex; align-items: center; justify-content: center; gap: 6px; padding: 6px 12px; border-radius: 999px; font-size: 11px; font-weight: 700; background: var(--lw-card); border: 1px solid var(--lw-border); }
    .hl-badge.gold { background: #fff8db; color: #a16207; }
    .hl-badge.silver { background: #f8fafc; color: #475569; }
    .hl-badge.bronze { background: #ffedd5; color: #9a3412; }
    .hl-badge.neutral { color: var(--lw-text-2); }
    .hl-juri-grid { display: grid; gap: 10px; }
    .hl-juri-card { padding: 14px; border-radius: 16px; background: var(--lw-bg); border: 1px solid var(--lw-border); }
    .hl-juri-name { font-size: 13px; font-weight: 800; color: var(--lw-text); }
    .hl-juri-sub { margin-top: 4px; font-size: 11.5px; color: var(--lw-text-3); }
    .hl-accordion { display: grid; gap: 12px; }
    .hl-acc { border: 1px solid var(--lw-border); border-radius: 16px; background: var(--lw-card); overflow: hidden; }
    .hl-acc summary { list-style: none; padding: 16px 18px; display: flex; align-items: center; justify-content: space-between; gap: 12px; cursor: pointer; }
    .hl-acc summary::-webkit-details-marker { display: none; }
    .hl-acc-main { min-width: 0; }
    .hl-acc-title { font-size: 13px; font-weight: 800; color: var(--lw-text); }
    .hl-acc-sub { font-size: 11.5px; color: var(--lw-text-3); margin-top: 2px; }
    .hl-acc-body { padding: 0 18px 18px; }
    .hl-break-grid { display: grid; gap: 10px; }
    .hl-break-item { padding: 12px 14px; border-radius: 14px; background: var(--lw-bg); border: 1px solid var(--lw-border); }
    .hl-break-item b { display: block; font-size: 12.5px; color: var(--lw-text); margin-bottom: 8px; }
    .hl-break-row { display: flex; justify-content: space-between; gap: 10px; font-size: 11.5px; color: var(--lw-text-2); padding: 4px 0; }
    @media (max-width: 1199.98px) { .hl-layout { grid-template-columns: 1fr; } }
    @media (max-width: 767.98px) { .hl-summary-grid, .hl-podium-grid { grid-template-columns: 1fr; } .hl-table-item, .hl-compare-item { grid-template-columns: 1fr; } .hl-score, .hl-compare-score { text-align: left; } }
</style>
@endpush

@section('content')
@include('component.admin.lomba-workspace')

@php
    $pl = $hasilLomba->pesertaLomba;
    $isIndividu = $pl->isIndividu();
    $status = $hasilLomba->is_haflah_selesai
        ? ['label' => 'Haflah Selesai', 'cls' => 'violet', 'ic' => 'bi-lock-fill']
        : (($hasilLomba->total_nilai ?? 0) == ($hasilLomba->total_dari_penilaian ?? 0)
            ? ['label' => 'Sudah Final', 'cls' => 'blue', 'ic' => 'bi-patch-check-fill']
            : ['label' => 'Siap Diumumkan', 'cls' => 'green', 'ic' => 'bi-megaphone-fill']);

    $ranking = \App\Models\HasilLomba::with(['pesertaLomba.student.user', 'pesertaLomba.kelompokLomba'])
        ->where('lomba_id', $hasilLomba->lomba_id)
        ->orderBy('peringkat')
        ->get();

    $maxScore = $ranking->max('total_nilai') ?: 1;

    $penilaianRows = \App\Models\PenilaianLomba::with(['juriLomba.guru', 'aspekPenilaian', 'pesertaLomba.student.user', 'pesertaLomba.kelompokLomba'])
        ->whereIn('peserta_lomba_id', $ranking->pluck('peserta_lomba_id'))
        ->get()
        ->groupBy('peserta_lomba_id');
@endphp

<div class="lw-mod hl-mod">
    <div class="hl-wrap">
        <div class="lw-hero">
            <div class="lw-hero-grid">
                <div class="lw-hero-left">
                    <span class="lw-hero-icon"><i class="bi bi-award-fill"></i></span>
                    <div>
                        <h1 class="lw-hero-title">{{ $hasilLomba->lomba->nama ?? 'Detail Hasil Lomba' }}</h1>
                        <p class="lw-hero-sub">{{ $isIndividu ? 'Individu' : 'Kelompok' }} • Sesi {{ $hasilLomba->lomba->sesiLomba->nama ?? '-' }} • dashboard verifikasi hasil akhir kompetisi.</p>
                        <div class="lw-hero-badges">
                            <span class="lw-hero-badge"><i class="bi {{ $isIndividu ? 'bi-person' : 'bi-people' }}"></i>{{ $isIndividu ? 'Individu' : 'Kelompok' }}</span>
                            <span class="lw-hero-badge"><i class="bi {{ $status['ic'] }}"></i>{{ $status['label'] }}</span>
                            <span class="lw-hero-badge"><i class="bi bi-gavel"></i>{{ $hasilLomba->lomba->juri->count() ?? 0 }} juri</span>
                        </div>
                    </div>
                </div>
                <div class="lw-hero-right">
                    <a href="{{ route('hasil-lomba.index', ['tab' => $isIndividu ? 'individu' : 'kelompok']) }}" class="lw-btn lw-btn--light"><i class="bi bi-arrow-left"></i> Kembali</a>
                    <a href="{{ route('hasil-lomba.edit', $hasilLomba->id) }}" class="lw-btn lw-btn--light"><i class="bi bi-arrow-repeat"></i> Sinkronisasi</a>
                </div>
            </div>
        </div>

        @if($hasilLomba->is_haflah_selesai)
            <div class="lw-alert lw-alert--warn"><i class="bi bi-lock-fill"></i> Haflah selesai — seluruh aksi nonaktif dan hasil dianggap final.</div>
        @endif

        <div class="hl-layout">
            <div class="hl-panel">
                <div class="hl-panel-head">
                    <div>
                        <b><i class="bi bi-grid-1x2"></i> Competition Summary</b>
                        <div class="hl-panel-sub">Ringkasan lomba, podium, ranking, dan breakdown skor peserta.</div>
                    </div>
                    <span class="hl-status {{ $status['cls'] }}"><i class="bi {{ $status['ic'] }}"></i>{{ $status['label'] }}</span>
                </div>
                <div class="hl-panel-body">
                    <div class="hl-summary-grid mb-4">
                        <div class="hl-summary-item"><div class="k">Nama Lomba</div><div class="v">{{ $hasilLomba->lomba->nama ?? '-' }}</div></div>
                        <div class="hl-summary-item"><div class="k">Jenis</div><div class="v">{{ $isIndividu ? 'Individu' : 'Kelompok' }}</div></div>
                        <div class="hl-summary-item"><div class="k">Sesi</div><div class="v">{{ $hasilLomba->lomba->sesiLomba->nama ?? '-' }}</div></div>
                        <div class="hl-summary-item"><div class="k">Jumlah Peserta</div><div class="v">{{ $ranking->count() }}</div></div>
                        <div class="hl-summary-item"><div class="k">Jumlah Juri</div><div class="v">{{ $hasilLomba->lomba->juri->count() ?? 0 }}</div></div>
                        <div class="hl-summary-item"><div class="k">Peserta Terpilih</div><div class="v">{{ $isIndividu ? ($pl->student->user->name ?? '-') : ($pl->kelompokLomba->nama_kelompok ?? '-') }}</div></div>
                    </div>

                    <div class="hl-panel mb-4">
                        <div class="hl-panel-head">
                            <div>
                                <b><i class="bi bi-trophy"></i> Podium</b>
                                <div class="hl-panel-sub">3 hasil terbaik ditampilkan lebih dulu untuk verifikasi cepat.</div>
                            </div>
                        </div>
                        <div class="hl-panel-body">
                            <div class="hl-podium-grid">
                                @foreach([2,1,3] as $rank)
                                    @php $row = $ranking->firstWhere('peringkat', $rank); @endphp
                                    <div class="hl-podium-card {{ $rank === 1 ? 'gold' : ($rank === 2 ? 'silver' : 'bronze') }}">
                                        <div class="icon"><i class="bi {{ $rank === 1 ? 'bi-trophy-fill' : ($rank === 2 ? 'bi-award-fill' : 'bi-award') }}"></i></div>
                                        <div class="rank">Juara {{ $rank }}</div>
                                        <div class="name">{{ $row ? ($row->pesertaLomba->isIndividu() ? ($row->pesertaLomba->student->user->name ?? '-') : ($row->pesertaLomba->kelompokLomba->nama_kelompok ?? '-')) : '-' }}</div>
                                        <div class="score">{{ $row ? number_format($row->total_nilai, 1) : '0' }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="hl-panel mb-4">
                        <div class="hl-panel-head">
                            <div>
                                <b><i class="bi bi-bar-chart"></i> Score Comparison</b>
                                <div class="hl-panel-sub">Perbandingan total nilai seluruh peserta, diurutkan dari tertinggi ke terendah.</div>
                            </div>
                        </div>
                        <div class="hl-panel-body">
                            <div class="hl-compare">
                                @foreach($ranking as $row)
                                @php
                                    $rowName = $row->pesertaLomba->isIndividu() ? ($row->pesertaLomba->student->user->name ?? '-') : ($row->pesertaLomba->kelompokLomba->nama_kelompok ?? '-');
                                    $width = $maxScore > 0 ? max(8, round(($row->total_nilai / $maxScore) * 100)) : 8;
                                @endphp
                                <div class="hl-compare-item">
                                    <div>
                                        <div class="hl-compare-head"><span class="hl-compare-name">#{{ $row->peringkat }} • {{ $rowName }}</span><span class="hl-compare-val">{{ number_format($row->total_nilai, 1) }}</span></div>
                                        <div class="hl-compare-bar"><span style="width:{{ $width }}%;"></span></div>
                                    </div>
                                    <div class="hl-compare-score">{{ number_format($row->total_nilai, 0) }}</div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="hl-panel mb-4">
                        <div class="hl-panel-head">
                            <div>
                                <b><i class="bi bi-list-ol"></i> Ranking</b>
                                <div class="hl-panel-sub">Daftar seluruh peserta/kelompok beserta total nilai dan status juara.</div>
                            </div>
                        </div>
                        <div class="hl-panel-body">
                            <div class="hl-table-list">
                                @foreach($ranking as $row)
                                @php
                                    $rowName = $row->pesertaLomba->isIndividu() ? ($row->pesertaLomba->student->user->name ?? '-') : ($row->pesertaLomba->kelompokLomba->nama_kelompok ?? '-');
                                    $badgeCls = $row->peringkat == 1 ? 'gold' : ($row->peringkat == 2 ? 'silver' : ($row->peringkat == 3 ? 'bronze' : 'neutral'));
                                @endphp
                                <div class="hl-table-item">
                                    <span class="hl-rank">#{{ $row->peringkat }}</span>
                                    <div><div class="hl-name">{{ $rowName }}</div><div class="hl-sub">{{ $row->pesertaLomba->isIndividu() ? 'Peserta Individu' : 'Kelompok Lomba' }}</div></div>
                                    <div class="hl-score">{{ number_format($row->total_nilai, 1) }}</div>
                                    <span class="hl-badge {{ $badgeCls }}">{{ $row->juara ?? 'Finalis' }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="hl-panel">
                        <div class="hl-panel-head">
                            <div>
                                <b><i class="bi bi-ui-checks-grid"></i> Score Breakdown</b>
                                <div class="hl-panel-sub">Accordion detail nilai per peserta berdasarkan setiap juri dan total skor.</div>
                            </div>
                        </div>
                        <div class="hl-panel-body">
                            <div class="hl-accordion">
                                @foreach($ranking as $row)
                                @php
                                    $rowName = $row->pesertaLomba->isIndividu() ? ($row->pesertaLomba->student->user->name ?? '-') : ($row->pesertaLomba->kelompokLomba->nama_kelompok ?? '-');
                                    $penilaians = $penilaianRows->get($row->peserta_lomba_id, collect());
                                @endphp
                                <details class="hl-acc" {{ $loop->first ? 'open' : '' }}>
                                    <summary>
                                        <div class="hl-acc-main">
                                            <div class="hl-acc-title">#{{ $row->peringkat }} • {{ $rowName }}</div>
                                            <div class="hl-acc-sub">Total {{ number_format($row->total_nilai, 1) }} • {{ $penilaians->count() }} entri nilai</div>
                                        </div>
                                        <span class="hl-badge neutral">{{ $row->juara ?? 'Finalis' }}</span>
                                    </summary>
                                    <div class="hl-acc-body">
                                        <div class="hl-break-grid">
                                            @forelse($penilaians->groupBy('juri_lomba_id') as $jid => $group)
                                            <div class="hl-break-item">
                                                <b>{{ optional($group->first()->juriLomba->guru)->nama ?? ('Juri #' . $jid) }}</b>
                                                @foreach($group as $detail)
                                                <div class="hl-break-row"><span>{{ $detail->aspekPenilaian->nama_aspek ?? 'Aspek' }}</span><span>{{ number_format($detail->nilai, 1) }}</span></div>
                                                @endforeach
                                                <div class="hl-break-row" style="padding-top:8px;border-top:1px dashed var(--lw-border);font-weight:800;"><span>Total</span><span>{{ number_format($group->sum('nilai'), 1) }}</span></div>
                                            </div>
                                            @empty
                                            <div class="hl-break-item">Belum ada data penilaian.</div>
                                            @endforelse
                                        </div>
                                    </div>
                                </details>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hl-summary-card">
                <div class="hl-panel-head">
                    <div>
                        <b><i class="bi bi-person-badge"></i> Juri Summary</b>
                        <div class="hl-panel-sub">Daftar juri, jumlah penilaian, dan kontribusi pada lomba ini.</div>
                    </div>
                </div>
                <div class="hl-panel-body">
                    <div class="hl-juri-grid">
                        @foreach($hasilLomba->lomba->juri as $juri)
                        @php
                            $countPenilaian = \App\Models\PenilaianLomba::where('juri_lomba_id', $juri->id)->whereIn('peserta_lomba_id', $ranking->pluck('peserta_lomba_id'))->count();
                        @endphp
                        <div class="hl-juri-card">
                            <div class="hl-juri-name">{{ $juri->guru->nama ?? ('Juri #' . $juri->id) }}</div>
                            <div class="hl-juri-sub">Jumlah Penilaian: {{ $countPenilaian }} • Kontribusi: {{ $ranking->count() ? round(($countPenilaian / max(1, $ranking->count())) * 100) : 0 }}%</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
