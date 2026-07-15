@extends('layouts.main')
@section('title', 'Perangkat Aktif')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-md-12">

            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-body p-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div>
                        <h4 class="fw-bold mb-1" style="color: var(--ms-text);">
                            <i class="fas fa-laptop me-2" style="color: var(--ms-primary);"></i>
                            Perangkat Aktif
                        </h4>
                        <p class="text-muted mb-0" style="font-size: 14px;">
                            Daftar perangkat yang sedang login ke akun Anda. Anda dapat logout perangkat lain secara manual.
                        </p>
                    </div>
                    <form method="POST" action="{{ route('active-sessions.revoke-others') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger py-2 fw-semibold" style="border-radius: 10px;"
                            onclick="return confirm('Logout semua perangkat lain kecuali perangkat Anda saat ini?')">
                            <i class="fas fa-power-off me-1"></i> Logout Perangkat Lain
                        </button>
                    </form>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success d-flex align-items-center gap-2">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger d-flex align-items-center gap-2">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            @if (empty($sessions))
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body text-center text-muted py-5">
                        <i class="fas fa-inbox fa-2x mb-3 d-block" style="opacity:.4;"></i>
                        Tidak ada perangkat aktif.
                    </div>
                </div>
            @else
                <div class="row g-3">
                    @php
                        $deviceIcons = [
                            'desktop' => 'fa-desktop',
                            'tablet'  => 'fa-tablet-screen-button',
                            'mobile'  => 'fa-mobile-screen',
                            'bot'     => 'fa-robot',
                        ];
                    @endphp
                    @foreach ($sessions as $s)
                        <div class="col-md-6">
                            <div class="card shadow-sm border-0 rounded-3 h-100 @if($s['is_current']) border-success @endif">
                                <div class="card-body p-4">

                                    <div class="d-flex align-items-start gap-3 mb-3">
                                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                                            style="width: 52px; height: 52px; background: @if($s['is_current']) var(--ms-primary-light) @else #f1f5f9 @endif; color: @if($s['is_current']) var(--ms-primary) @else #64748b @endif;">
                                            <i class="fas {{ $deviceIcons[$s['device_kind']] ?? 'fa-desktop' }} fa-lg"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                                <span class="fw-bold" style="font-size: 16px; color: var(--ms-text);">
                                                    {{ $s['browser'] }}
                                                </span>
                                                @if ($s['is_current'])
                                                    <span class="badge bg-success-subtle text-success" style="font-size:11px;">
                                                        <i class="fas fa-circle me-1" style="font-size:7px;"></i> Perangkat ini
                                                    </span>
                                                @endif
                                                @if ($s['is_trusted'])
                                                    <span class="badge bg-primary-subtle text-primary" style="font-size:11px;">
                                                        <i class="fas fa-shield-halved me-1"></i> Tepercaya
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="text-muted" style="font-size: 13px;">
                                                {{ $s['os'] }} &middot; {{ $s['device'] }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3" style="font-size: 13px;">
                                        <div class="d-flex justify-content-between py-1 border-bottom">
                                            <span class="text-muted">IP Address</span>
                                            <code class="fw-semibold">{{ $s['ip'] ?? '—' }}</code>
                                        </div>
                                        <div class="d-flex justify-content-between py-1 border-bottom">
                                            <span class="text-muted">Aktivitas terakhir</span>
                                            <span>{{ $s['last_activity']?->diffForHumans() }}</span>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2 flex-wrap">
                                        @if ($s['fingerprint_id'])
                                            @if ($s['is_trusted'])
                                                <form method="POST" action="{{ route('active-sessions.untrust', $s['fingerprint_id']) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;">
                                                        <i class="fas fa-xmark me-1"></i> Cabut Kepercayaan
                                                    </button>
                                                </form>
                                            @else
                                                <form method="POST" action="{{ route('active-sessions.trust', $s['fingerprint_id']) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-primary" style="border-radius:8px;">
                                                        <i class="fas fa-shield-halved me-1"></i> Tandai Tepercaya
                                                    </button>
                                                </form>
                                            @endif
                                        @endif

                                        @if (!$s['is_current'])
                                            <form method="POST" action="{{ route('active-sessions.revoke', $s['id']) }}" class="d-inline ms-auto">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:8px;"
                                                    onclick="return confirm('Logout perangkat ini?')">
                                                    <i class="fas fa-power-off me-1"></i> Logout
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
