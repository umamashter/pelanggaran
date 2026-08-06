@extends('layouts.main')
@section('title', 'Detail Sesi Lomba')
@section('content')
@include('component.admin.lomba-workspace')

<style>
    .page-title-content { display: none !important; }

    .lw-detail-wrap { max-width: 900px; }

    .lw-detail-meta .lw-hero-badge { color: #fff; }
    .lw-detail-meta .lw-hero-badge--ok { background: rgba(14, 159, 110, .35); border-color: rgba(14, 159, 110, .6); }
    .lw-detail-meta .lw-hero-badge--lock { background: rgba(220, 76, 76, .35); border-color: rgba(220, 76, 76, .6); }

    .lw-sesi-tl { display: flex; align-items: center; gap: 0; padding: 14px 16px; background: var(--lw-bg);
        border: 1px solid var(--lw-border); border-radius: 14px; }
    .lw-sesi-tl .point { display: flex; flex-direction: column; align-items: center; gap: 4px; flex-shrink: 0; }
    .lw-sesi-tl .point .dot { width: 12px; height: 12px; border-radius: 999px; background: var(--lw-green); box-shadow: 0 0 0 4px var(--lw-green-soft); }
    .lw-sesi-tl .point .time { font-size: 11px; font-weight: 700; color: var(--lw-text); }
    .lw-sesi-tl .bar { flex: 1; height: 6px; border-radius: 999px; background: var(--lw-grad); margin: 0 6px; position: relative; }
    .lw-sesi-tl .bar .dur { position: absolute; top: -18px; left: 50%; transform: translateX(-50%); font-size: 10px;
        font-weight: 700; color: var(--lw-text-3); white-space: nowrap; }
</style>

@php
    $isLocked = $sesiLomba->is_haflah_selesai;
    $lombaCount = $sesiLomba->lombas->count();
    $haflahNama = $sesiLomba->haflatulImtihan->nama_acara ?? '-';
    $statusLabel = $isLocked ? 'Haflah Selesai' : ($lombaCount > 0 ? 'Dipakai Lomba' : 'Belum Dipakai');

    $jamMulai = \Carbon\Carbon::parse($sesiLomba->jam_mulai);
    $jamSelesai = \Carbon\Carbon::parse($sesiLomba->jam_selesai);
    $durasiMenit = $jamMulai->diffInMinutes($jamSelesai);
    $durasiLabel = floor($durasiMenit/60).' jam '.($durasiMenit%60).' menit';
@endphp

<div class="lw-mod jd-page-sesilomba">

<div class="lw-detail-wrap">
    <div class="lw-breadcrumb" style="margin-bottom:16px;">
        <a href="{{ route('sesi-lomba.index') }}">Sesi Lomba</a> <i class="bi bi-chevron-right"></i> <span>Detail</span>
    </div>

    <div class="lw-detail-hero">
        <div class="lw-detail-hero-grid">
            <div class="d-flex align-items-center gap-3" style="min-width:0;">
                <span class="lw-detail-avatar"><i class="bi bi-calendar-week"></i></span>
                <div style="min-width:0;">
                    <div class="lw-detail-sub" style="opacity:.8;font-weight:600;text-transform:uppercase;letter-spacing:.5px;font-size:10px;margin-bottom:2px;">Session Insight</div>
                    <h1 class="lw-detail-title">{{ $sesiLomba->nama }}</h1>
                    <div class="lw-detail-sub">{{ $haflahNama }}</div>
                </div>
            </div>
            <div class="lw-detail-meta">
                <span class="lw-hero-badge {{ $isLocked ? 'lw-hero-badge--lock' : ($lombaCount > 0 ? '' : 'lw-hero-badge--ok') }}">
                    <i class="bi {{ $isLocked ? 'bi-x-circle-fill' : ($lombaCount > 0 ? 'bi-diagram-3-fill' : 'bi-check2-circle') }}"></i>{{ $statusLabel }}
                </span>
                <span class="lw-hero-badge"><i class="bi bi-calendar-day"></i>{{ \Carbon\Carbon::parse($sesiLomba->tanggal)->isoFormat('D MMM YYYY') }}</span>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-7">
            <div class="lw-card lw-card-pad" style="margin-bottom:14px;">
                <div class="lw-form-section"><i class="bi bi-info-circle-fill"></i> Informasi Sesi</div>
                <div class="lw-info-grid">
                    <div class="lw-info-cell">
                        <div class="lbl"><i class="bi bi-building"></i> Haflatul Imtihan</div>
                        <div class="val">{{ $haflahNama }}</div>
                    </div>
                    <div class="lw-info-cell">
                        <div class="lbl"><i class="bi bi-tag-fill"></i> Nama Sesi</div>
                        <div class="val">{{ $sesiLomba->nama }}</div>
                    </div>
                    <div class="lw-info-cell">
                        <div class="lbl"><i class="bi bi-calendar2-week"></i> Tanggal</div>
                        <div class="val">{{ \Carbon\Carbon::parse($sesiLomba->tanggal)->isoFormat('D MMM YYYY') }}</div>
                    </div>
                    <div class="lw-info-cell">
                        <div class="lbl"><i class="bi bi-clock"></i> Jam Mulai</div>
                        <div class="val">{{ $jamMulai->format('H:i') }}</div>
                    </div>
                    <div class="lw-info-cell">
                        <div class="lbl"><i class="bi bi-stopwatch"></i> Jam Selesai</div>
                        <div class="val">{{ $jamSelesai->format('H:i') }}</div>
                    </div>
                    <div class="lw-info-cell">
                        <div class="lbl"><i class="bi bi-hourglass-split"></i> Durasi</div>
                        <div class="val">{{ $durasiLabel }}</div>
                    </div>
                    @if($sesiLomba->keterangan)
                    <div class="lw-info-cell" style="grid-column:1 / -1;">
                        <div class="lbl"><i class="bi bi-align-left"></i> Keterangan</div>
                        <div class="val" style="font-weight:500;font-size:12.5px;">{{ $sesiLomba->keterangan }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="lw-card lw-card-pad">
                <div class="lw-form-section"><i class="bi bi-arrow-left-right"></i> Linimasa Sesi</div>
                <div class="lw-sesi-tl">
                    <div class="point">
                        <span class="dot"></span>
                        <span class="time">{{ $jamMulai->format('H:i') }}</span>
                    </div>
                    <div class="bar"><span class="dur">{{ $durasiLabel }}</span></div>
                    <div class="point">
                        <span class="dot"></span>
                        <span class="time">{{ $jamSelesai->format('H:i') }}</span>
                    </div>
                </div>
                <div class="lw-note" style="margin-top:12px;">
                    <i class="bi bi-info-circle"></i>
                    <span>{{ $isLocked ? 'Haflah telah selesai — sesi tidak dapat diubah.' : ($lombaCount > 0 ? 'Sesi sudah dipakai '.$lombaCount.' lomba.' : 'Sesi masih bebas digunakan.') }}</span>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="lw-card lw-card-pad">
                <div class="lw-form-section"><i class="bi bi-trophy-fill"></i> Lomba Menggunakan Sesi Ini</div>
                <div class="lw-stat" style="padding:0 0 14px;">
                    <span class="lw-stat-icon navy"><i class="bi bi-trophy"></i></span>
                    <div>
                        <div class="lw-stat-num">{{ $lombaCount }}</div>
                        <div class="lw-stat-label">Total Lomba</div>
                    </div>
                </div>
                @if($lombaCount > 0)
                    <div class="lw-breakdown">
                        @foreach($sesiLomba->lombas->take(5) as $lomba)
                            <div class="lw-breakdown-item">
                                <span class="lw-breakdown-name"><i class="bi bi-diagram-3-fill"></i> {{ $lomba->nama }}</span>
                                <a href="{{ route('lomba.show', $lomba->id) }}" class="lw-btn lw-btn--xs lw-btn--soft"><i class="bi bi-eye"></i></a>
                            </div>
                        @endforeach
                        @if($sesiLomba->lombas->count() > 5)
                            <div class="lw-help-text" style="text-align:center;">+{{ $sesiLomba->lombas->count() - 5 }} lomba lainnya</div>
                        @endif
                    </div>
                @else
                    <div class="lw-empty" style="padding:20px 14px;">
                        <span class="lw-empty-illus" style="width:74px;height:74px;margin:0 auto 12px;">
                            <span class="core" style="inset:14px;font-size:26px;"><i class="bi bi-trophy"></i></span>
                        </span>
                        <div class="lw-empty-title">Belum ada lomba</div>
                        <p class="lw-empty-sub" style="margin-bottom:0;">Sesi ini belum dipakai oleh lomba apapun.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="lw-wizard-nav">
        <a href="{{ route('sesi-lomba.index') }}" class="lw-btn"><i class="bi bi-arrow-left"></i> Kembali ke Daftar</a>
        <span class="spacer"></span>
        <a href="{{ route('sesi-lomba.edit', $sesiLomba->id) }}" class="lw-btn lw-btn--solid"><i class="bi bi-pencil"></i> Edit Sesi</a>
    </div>
</div>

</div>
@endsection
