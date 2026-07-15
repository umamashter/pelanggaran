@extends('layouts.main')
@section('title', 'Riwayat Login Saya')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-md-12">

            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4">

                    <h4 class="fw-bold mb-1" style="color: var(--ms-text);">
                        <i class="fas fa-clock-rotate-left me-2" style="color: var(--ms-primary);"></i>
                        Riwayat Login Saya
                    </h4>
                    <p class="text-muted mb-4" style="font-size: 14px;">
                        Daftar aktivitas login pada akun Anda. Riwayat dicatat otomatis setiap percobaan login.
                    </p>

                    @if ($histories->isEmpty())
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-inbox fa-2x mb-3 d-block" style="opacity:.4;"></i>
                            Belum ada riwayat login.
                        </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" style="font-size: 13.5px;">
                            <thead class="table-light">
                                <tr>
                                    <th>Waktu Login</th>
                                    <th>Status</th>
                                    <th>OTP</th>
                                    <th>Perangkat</th>
                                    <th>IP Address</th>
                                    <th>Waktu Logout</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($histories as $h)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $h->login_at?->format('d M Y, H:i') }}</div>
                                        <div class="text-muted" style="font-size:11px;">{{ $h->login_at?->diffForHumans() }}</div>
                                    </td>
                                    <td>{!! $h->login_status_badge !!}</td>
                                    <td>{!! $h->otp_status_badge !!}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-secondary-subtle text-secondary rounded-pill" style="font-size:11px;">
                                                <i class="fas fa-{{ $h->device_kind === 'mobile' ? 'mobile-screen' : ($h->device_kind === 'tablet' ? 'tablet-screen-button' : 'desktop') }}"></i>
                                            </span>
                                            <span>
                                                <span class="fw-semibold">{{ $h->browser }}</span>
                                                <span class="text-muted d-block" style="font-size:11px;">{{ $h->os }} · {{ $h->device }}</span>
                                            </span>
                                        </div>
                                    </td>
                                    <td><code>{{ $h->ip_address }}</code></td>
                                    <td>
                                        @if ($h->logout_at)
                                            <span class="text-muted">{{ $h->logout_at->format('d M Y, H:i') }}</span>
                                        @else
                                            <span class="badge bg-success-subtle text-success" style="font-size:11px;">
                                                <span class="spinner-border spinner-border-sm me-1" style="width:8px;height:8px;"></span>Aktif
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="text-muted" style="font-size:12px;">
                            Menampilkan {{ $histories->firstItem() ?? 0 }}–{{ $histories->lastItem() ?? 0 }} dari {{ $histories->total() }} riwayat
                        </span>
                        {{ $histories->links() }}
                    </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</div>
@endsection