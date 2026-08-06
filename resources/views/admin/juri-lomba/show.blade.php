@extends('layouts.main')
@section('title', 'Detail Juri Lomba')
@section('content')
@include('component.admin.lomba-workspace')

<style>
    .page-title-content { display: none !important; }

    /* ---------- Juri Lomba — Detail Dashboard ---------- */
    .ljs-detail { max-width: 1080px; margin: 0 auto; }

    .ljs-judge-card { position: relative; display: flex; align-items: center; gap: 14px; padding: 14px 16px;
        background: var(--lw-card); border: 1px solid var(--lw-border); border-radius: 14px;
        box-shadow: var(--lw-shadow); transition: all .2s ease; overflow: hidden; }
    .ljs-judge-card + .ljs-judge-card { margin-top: 10px; }
    .ljs-judge-card::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; background: var(--lw-border); }
    .ljs-judge-card:hover { transform: translateY(-2px); border-color: var(--lw-primary-border); box-shadow: var(--lw-shadow-lg); }
    .ljs-judge-card.done::before { background: var(--lw-green); }
    .ljs-judge-card.pending::before { background: var(--lw-amber); }
    .ljs-judge-card.focus::before { background: var(--lw-primary); }
    .ljs-judge-avatar { width: 46px; height: 46px; border-radius: 13px; display: inline-flex; align-items: center; justify-content: center;
        font-size: 15px; font-weight: 800; color: #fff; flex-shrink: 0; box-shadow: 0 3px 10px -2px rgba(15,23,42,.35); }
    .ljs-judge-info { flex: 1; min-width: 0; }
    .ljs-judge-name { font-size: 14px; font-weight: 700; color: var(--lw-text); display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
    .ljs-judge-nip { font-size: 11.5px; color: var(--lw-text-3); margin-top: 2px; }
    .ljs-judge-count { font-size: 12.5px; font-weight: 800; color: var(--lw-text-2); font-variant-numeric: tabular-nums; white-space: nowrap; }
    .ljs-judge-count small { font-weight: 500; color: var(--lw-text-3); }
    .ljs-judge-meta { display: flex; align-items: center; gap: 14px; }

    .ljs-ring { position: relative; width: 150px; height: 150px; flex-shrink: 0; }
    .ljs-ring svg { transform: rotate(-90deg); }
    .ljs-ring .bg { fill: none; stroke: var(--lw-bg); stroke-width: 11; }
    .ljs-ring .fg { fill: none; stroke: url(#ljsRingGrad); stroke-width: 11; stroke-linecap: round;
        stroke-dasharray: 376.99; stroke-dashoffset: 376.99; transition: stroke-dashoffset 1s cubic-bezier(.22,1,.36,1); }
    .ljs-ring-center { position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; }
    .ljs-ring-center .big { font-size: 30px; font-weight: 800; color: var(--lw-text); line-height: 1; font-variant-numeric: tabular-nums; }
    .ljs-ring-center .small { font-size: 10px; font-weight: 700; color: var(--lw-text-3); text-transform: uppercase; letter-spacing: .5px; margin-top: 4px; }

    .ljs-progress-bar { height: 9px; border-radius: 999px; background: var(--lw-bg); border: 1px solid var(--lw-border); overflow: hidden; }
    .ljs-progress-fill { height: 100%; width: 0%; border-radius: 999px; background: var(--lw-grad); transition: width 1s cubic-bezier(.22,1,.36,1); position: relative; }
    .ljs-progress-fill::after { content: ''; position: absolute; inset: 0; background: linear-gradient(90deg, transparent, rgba(255,255,255,.4), transparent);
        background-size: 200% 100%; animation: lwShine 1.6s linear infinite; }
</style>

@php
    $isLocked = $juriLomba->is_haflah_selesai;
    $totalJuri = $allJuri->count();
    $totalPenilaian = $allJuri->sum('penilaian_count');
    $sudahMenilai = $allJuri->filter(fn ($j) => $j->penilaian_count > 0)->count();
    $progress = $totalJuri > 0 ? min(100, round($sudahMenilai / $totalJuri * 100)) : 0;
    $rataPenilaian = $totalJuri > 0 ? round($totalPenilaian / $totalJuri, 1) : 0;
    $semuaSelesai = $totalJuri > 0 && $sudahMenilai === $totalJuri;
    $hasPenilaian = $totalPenilaian > 0;
@endphp

<div class="lw-mod ljs-detail">

    <a href="{{ route('juri-lomba.index') }}" class="lw-back mb-3"><i class="bi bi-arrow-left"></i> Kembali ke Juri Lomba</a>

    @if($isLocked)
        <div class="lw-lock-banner">
            <i class="bi bi-lock-fill"></i>
            <div><b>Data Terkunci</b> — Haflah telah selesai. Assignment ini tidak dapat diubah lagi.</div>
        </div>
    @endif

    {{-- DETAIL HERO --}}
    <div class="lw-detail-hero">
        <div class="lw-detail-hero-grid">
            <div style="display:flex;gap:16px;align-items:center;min-width:0;">
                <div class="lw-detail-avatar"><i class="bi bi-trophy-fill"></i></div>
                <div style="min-width:0;">
                    <h1 class="lw-detail-title">{{ $juriLomba->lomba->nama ?? '-' }}</h1>
                    <p class="lw-detail-sub">Detail penugasan juri dan progres penilaian kompetisi ini</p>
                    <div class="lw-detail-meta">
                        <span class="lw-hero-badge"><i class="bi bi-tags-fill"></i>{{ $juriLomba->lomba->jenis ?? '-' }}</span>
                        <span class="lw-hero-badge"><i class="bi bi-people-fill"></i>{{ $totalJuri }} juri</span>
                        <span class="lw-hero-badge {{ $isLocked ? 'lw-hero-badge--warn' : ($semuaSelesai ? 'lw-hero-badge--ok' : '') }}">
                            <i class="bi {{ $isLocked ? 'bi-lock-fill' : ($semuaSelesai ? 'bi-check-circle-fill' : 'bi-arrow-clockwise') }}"></i>
                            {{ $isLocked ? 'Terkunci' : ($semuaSelesai ? 'Selesai' : 'Berlangsung') }}
                        </span>
                    </div>
                </div>
            </div>
            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                @if(!$isLocked)
                    @if($hasPenilaian)
                        <button type="button" class="lw-btn lw-btn--light" disabled title="Tidak dapat diubah karena sudah terdapat data penilaian."><i class="bi bi-pencil-square"></i> Terkunci</button>
                    @else
                        <a href="{{ route('juri-lomba.edit', $juriLomba->id) }}" class="lw-btn lw-btn--light"><i class="bi bi-pencil-square"></i> Edit Assignment</a>
                    @endif
                @endif
            </div>
        </div>
    </div>

    {{-- SUMMARY --}}
    <div class="lw-stat-grid">
        <div class="lw-card lw-stat">
            <span class="lw-stat-icon navy"><i class="bi bi-person-badge"></i></span>
            <div><div class="lw-stat-num" data-count="{{ $totalJuri }}">{{ $totalJuri }}</div><div class="lw-stat-label">Total Juri</div></div>
        </div>
        <div class="lw-card lw-stat">
            <span class="lw-stat-icon violet"><i class="bi bi-star-fill"></i></span>
            <div><div class="lw-stat-num" data-count="{{ $totalPenilaian }}">{{ $totalPenilaian }}</div><div class="lw-stat-label">Total Penilaian</div></div>
        </div>
        <div class="lw-card lw-stat">
            <span class="lw-stat-icon green"><i class="bi bi-check-circle-fill"></i></span>
            <div><div class="lw-stat-num" data-count="{{ $sudahMenilai }}">{{ $sudahMenilai }}</div><div class="lw-stat-label">Sudah Menilai</div></div>
        </div>
        <div class="lw-card lw-stat">
            <span class="lw-stat-icon amber"><i class="bi bi-bar-chart-line-fill"></i></span>
            <div><div class="lw-stat-num">{{ $rataPenilaian }}</div><div class="lw-stat-label">Rata-rata / Juri</div></div>
        </div>
    </div>

    {{-- PROGRESS + JUDGE LIST --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-lg-4">
            <div class="lw-card lw-card-pad d-flex flex-column align-items-center" style="height:100%;">
                <div class="lw-section-title mb-3 align-self-start"><i class="bi bi-pie-chart-fill"></i> Progres Penilaian</div>
                <div class="ljs-ring">
                    <svg viewBox="0 0 150 150">
                        <defs>
                            <linearGradient id="ljsRingGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                                <stop offset="0%" stop-color="#2b3c78"/>
                                <stop offset="100%" stop-color="#e7a615"/>
                            </linearGradient>
                        </defs>
                        <circle class="bg" r="60" cx="75" cy="75"/>
                        <circle class="fg" r="60" cx="75" cy="75" data-progress="{{ $progress }}"/>
                    </svg>
                    <div class="ljs-ring-center"><span class="big" data-count="{{ $progress }}">{{ $progress }}</span><span class="small">Progres %</span></div>
                </div>
                <div style="width:100%;margin-top:18px;">
                    <div class="ljs-progress-bar"><div class="ljs-progress-fill" data-w="{{ $progress }}" style="width:0%;"></div></div>
                </div>
                <div style="display:flex;gap:18px;margin-top:16px;width:100%;justify-content:center;">
                    <div style="text-align:center;"><div style="font-size:18px;font-weight:800;color:var(--lw-green);font-variant-numeric:tabular-nums;">{{ $sudahMenilai }}</div><div style="font-size:10px;font-weight:700;color:var(--lw-text-3);text-transform:uppercase;letter-spacing:.4px;">Aktif</div></div>
                    <div style="text-align:center;"><div style="font-size:18px;font-weight:800;color:var(--lw-amber);font-variant-numeric:tabular-nums;">{{ $totalJuri - $sudahMenilai }}</div><div style="font-size:10px;font-weight:700;color:var(--lw-text-3);text-transform:uppercase;letter-spacing:.4px;">Pending</div></div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-8">
            <div class="lw-card lw-card-pad">
                <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap mb-3">
                    <div>
                        <div class="lw-section-title" style="margin-bottom:0;"><i class="bi bi-person-check-fill"></i> Judge Activity</div>
                        <div class="lw-section-sub" style="margin:2px 0 0;">Status penilaian per juri pada kompetisi ini</div>
                    </div>
                    <span class="lw-chip {{ $semuaSelesai ? 'lw-chip--green' : 'lw-chip--navy' }}"><i class="bi {{ $semuaSelesai ? 'bi-flag-fill' : 'bi-list-check' }}"></i>{{ $sudahMenilai }}/{{ $totalJuri }} juri selesai</span>
                </div>

                @forelse($allJuri as $j)
                    @php
                        $done = $j->penilaian_count > 0;
                        $isFocus = $j->id === $juriLomba->id;
                        $cardState = $done ? 'done' : ($isFocus ? 'focus' : 'pending');
                        $stateLabel = $done ? 'Sudah Menilai' : ($isFocus ? 'Assignment Fokus' : 'Belum Menilai');
                        $stateIcon = $done ? 'bi-check-circle-fill' : ($isFocus ? 'bi-bank' : 'bi-clock');
                        $stateChip = $done ? 'lw-chip--green' : ($isFocus ? 'lw-chip--navy' : 'lw-chip--amber');
                    @endphp
                    <div class="ljs-judge-card {{ $cardState }}">
                        <span class="ljs-judge-avatar" style="background:{{ lw_ava_color($j->guru->nama ?? '-') }};">{{ lw_initial($j->guru->nama ?? '-') }}</span>
                        <div class="ljs-judge-info">
                            <div class="ljs-judge-name">
                                {{ $j->guru->nama ?? '-' }}
                                @if($isFocus)
                                    <span class="lw-chip lw-chip--navy lw-chip-mini">Fokus</span>
                                @endif
                            </div>
                            <div class="ljs-judge-nip">{{ $j->guru->nip ?? '-' }}</div>
                        </div>
                        <div class="ljs-judge-meta">
                            <span class="ljs-judge-count">{{ $j->penilaian_count }} <small>penilaian</small></span>
                            <span class="lw-chip {{ $stateChip }}" style="white-space:nowrap;"><i class="bi {{ $stateIcon }}"></i>{{ $stateLabel }}</span>
                        </div>
                    </div>
                @empty
                    <div class="lw-empty" style="padding:36px 20px;">
                        <div class="lw-empty-illus" style="width:104px;height:104px;"><div class="ring"></div><div class="core"><i class="bi bi-person-slash"></i></div></div>
                        <div class="lw-empty-title">Belum Ada Juri</div>
                        <div class="lw-empty-sub">Belum ada juri ditugaskan untuk lomba ini.</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- TIMELINE --}}
    <div class="lw-card lw-card-pad">
        <div class="lw-section-title mb-3"><i class="bi bi-clock-history"></i> Timeline Aktivitas</div>
        <div class="lw-timeline">
            <div class="lw-tl-item is-done">
                <div class="lw-tl-dot"><i class="bi bi-person-plus-fill"></i></div>
                <div class="lw-tl-card">
                    <div class="lw-tl-main">
                        <div class="lw-tl-name">Juri Ditugaskan</div>
                        <div class="lw-tl-desc">{{ $totalJuri }} juri ditugaskan untuk lomba ini</div>
                    </div>
                    <span class="lw-chip lw-chip--green lw-chip-mini lw-tl-tag"><i class="bi bi-check-lg"></i> Selesai</span>
                </div>
            </div>
            @if($hasPenilaian)
                <div class="lw-tl-item is-current">
                    <div class="lw-tl-dot"><i class="bi bi-pencil-fill"></i></div>
                    <div class="lw-tl-card">
                        <div class="lw-tl-main">
                            <div class="lw-tl-name">Penilaian Dimulai</div>
                            <div class="lw-tl-desc">{{ $totalPenilaian }} penilaian telah dilakukan oleh {{ $sudahMenilai }} juri</div>
                        </div>
                        <span class="lw-chip lw-chip--navy lw-chip-mini lw-tl-tag"><i class="bi bi-arrow-clockwise"></i> Berlangsung</span>
                    </div>
                </div>
                <div class="lw-tl-item {{ $semuaSelesai ? 'is-done' : '' }}">
                    <div class="lw-tl-dot"><i class="bi {{ $semuaSelesai ? 'bi-check-lg' : 'bi-hourglass-split' }}"></i></div>
                    <div class="lw-tl-card">
                        <div class="lw-tl-main">
                            <div class="lw-tl-name">Penilaian Selesai</div>
                            <div class="lw-tl-desc">{{ $semuaSelesai ? 'Seluruh juri telah menyelesaikan penilaian.' : $sudahMenilai . ' dari ' . $totalJuri . ' juri telah selesai menilai.' }}</div>
                        </div>
                        @if($semuaSelesai)
                            <span class="lw-chip lw-chip--green lw-chip-mini lw-tl-tag"><i class="bi bi-flag-fill"></i> Selesai</span>
                        @endif
                    </div>
                </div>
            @else
                <div class="lw-tl-item">
                    <div class="lw-tl-dot"><i class="bi bi-hourglass-split"></i></div>
                    <div class="lw-tl-card">
                        <div class="lw-tl-main">
                            <div class="lw-tl-name">Menunggu Penilaian</div>
                            <div class="lw-tl-desc">Belum ada penilaian tercatat. Penilaian dimulai setelah peserta diinput.</div>
                        </div>
                        <span class="lw-chip lw-chip--amber lw-chip-mini lw-tl-tag"><i class="bi bi-clock"></i> Menunggu</span>
                    </div>
                </div>
            @endif
        </div>
    </div>

</div>

@push('scripts')
<script>
(function () {
    /* ---------- progress ring + bar ---------- */
    setTimeout(function () {
        var ringFg = document.querySelector('.ljs-ring .fg');
        var ringPct = ringFg ? parseFloat(ringFg.dataset.progress) || 0 : 0;
        if (ringFg) { ringFg.style.strokeDashoffset = 376.99 - (376.99 * ringPct / 100); }
        document.querySelectorAll('.ljs-progress-fill').forEach(function (f) { f.style.width = f.dataset.w + '%'; });
    }, 300);
})();
</script>
@endpush
@endsection
