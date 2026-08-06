@extends('layouts.main')
@section('title', 'Riwayat Login — Semua Pengguna')

@push('css')
<style>
    .page-title-content { display: none !important; }
    .audit-mod { --audit-radius: 16px; }
    .audit-wrap { max-width: 1280px; margin: 0 auto; }
    .audit-toolbar { top: 78px; }
    .audit-hero-grid { display:grid; grid-template-columns:1.25fr .95fr; gap:20px; align-items:stretch; }
    .audit-hero-box, .audit-risk-box { background: var(--jd-card); border:1px solid var(--jd-border); border-radius:18px; box-shadow:var(--jd-shadow); padding:22px; }
    .audit-stat-big { font-size: clamp(36px, 5vw, 52px); font-weight: 800; color: var(--jd-text); line-height: 1; letter-spacing: -1.5px; }
    .audit-stat-label { margin-top: 6px; font-size: 11px; color: var(--jd-text-3); text-transform: uppercase; letter-spacing: .5px; font-weight: 700; }
    .audit-stat-sub { margin-top: 4px; font-size: 12px; color: var(--jd-text-2); }
    .audit-split { display:grid; grid-template-columns: repeat(3, 1fr); gap:10px; margin-top:18px; }
    .audit-split-item { padding:12px; border-radius:14px; background:var(--jd-bg); border:1px solid var(--jd-border); }
    .audit-split-item .n { font-size:18px; font-weight:800; color:var(--jd-text); }
    .audit-split-item .l { font-size:10px; color:var(--jd-text-3); text-transform:uppercase; letter-spacing:.5px; }
    .audit-kpis { display:grid; grid-template-columns: repeat(6, 1fr); gap:12px; margin:22px 0; }
    .audit-kpi { background:var(--jd-card); border:1px solid var(--jd-border); border-radius:16px; box-shadow:var(--jd-shadow); padding:14px; display:flex; gap:12px; align-items:center; transition:transform .2s ease, box-shadow .2s ease; }
    .audit-kpi:hover { transform:translateY(-3px); box-shadow:var(--jd-shadow-lg); }
    .audit-kpi-icon { width:40px; height:40px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:18px; flex-shrink:0; }
    .audit-kpi-icon.green { background:var(--jd-green-soft); color:var(--jd-green); }
    .audit-kpi-icon.red { background:var(--jd-red-soft); color:var(--jd-red); }
    .audit-kpi-icon.amber { background:var(--jd-amber-soft); color:var(--jd-amber); }
    .audit-kpi-icon.blue { background:var(--jd-primary-soft); color:var(--jd-primary); }
    .audit-kpi-icon.violet { background:var(--jd-violet-soft); color:var(--jd-violet); }
    .audit-kpi-num { font-size:18px; font-weight:800; color:var(--jd-text); line-height:1.2; }
    .audit-kpi-label { font-size:10px; text-transform:uppercase; letter-spacing:.5px; color:var(--jd-text-3); font-weight:700; }
    .audit-risk-list { display:grid; gap:10px; margin-top:14px; }
    .audit-risk-item { padding:12px 14px; border-radius:14px; border:1px solid var(--jd-border); background:var(--jd-bg); display:flex; justify-content:space-between; gap:10px; align-items:center; }
    .audit-risk-item strong { color:var(--jd-text); font-size:13px; }
    .audit-feed { position:relative; margin-top: 10px; }
    .audit-feed::before { content:""; position:absolute; left:31px; top:0; bottom:0; width:2px; background:linear-gradient(180deg, var(--jd-primary-border), transparent); }
    .audit-group { margin-bottom: 26px; }
    .audit-group-title { position:sticky; top:138px; z-index:5; display:inline-flex; align-items:center; gap:8px; padding:7px 12px; border-radius:999px; background:var(--jd-card); border:1px solid var(--jd-border); font-size:11px; font-weight:700; color:var(--jd-text-2); box-shadow:var(--jd-shadow); margin-bottom:14px; }
    .audit-event { position:relative; display:grid; grid-template-columns: 78px minmax(0,1fr); gap:14px; margin-bottom:16px; }
    .audit-node { position:relative; z-index:2; width:64px; }
    .audit-dot { width:64px; height:64px; border-radius:20px; display:flex; align-items:center; justify-content:center; font-size:24px; color:#fff; box-shadow:var(--jd-shadow); }
    .audit-dot.green { background:linear-gradient(135deg,#16a34a,#4ade80); }
    .audit-dot.red { background:linear-gradient(135deg,#dc2626,#f87171); }
    .audit-dot.amber { background:linear-gradient(135deg,#d97706,#fbbf24); }
    .audit-dot.blue { background:linear-gradient(135deg,#2563eb,#60a5fa); }
    .audit-dot.violet { background:linear-gradient(135deg,#7c3aed,#a78bfa); }
    .audit-card { position:relative; overflow:hidden; background:linear-gradient(180deg, rgba(255,255,255,.9), rgba(255,255,255,.76)); border:1px solid var(--jd-border); border-radius:18px; box-shadow:var(--jd-shadow); padding:18px; transition:transform .22s ease, box-shadow .22s ease, border-color .22s ease; }
    html.dark-mode .audit-card { background:linear-gradient(180deg, rgba(255,255,255,.06), rgba(255,255,255,.04)); }
    .audit-card:hover { transform:translateY(-3px); box-shadow:var(--jd-shadow-lg); border-color:var(--jd-primary-border); }
    .audit-card.warn { border-color:var(--jd-amber-border); background:linear-gradient(180deg, rgba(251,191,36,.08), rgba(255,255,255,.78)); }
    .audit-head { display:flex; justify-content:space-between; gap:14px; align-items:flex-start; flex-wrap:wrap; }
    .audit-user { display:flex; gap:12px; min-width:0; }
    .audit-avatar { width:48px; height:48px; border-radius:16px; display:flex; align-items:center; justify-content:center; font-size:17px; font-weight:800; color:#fff; flex-shrink:0; background:var(--jd-grad); }
    .audit-name { font-size:15px; font-weight:800; color:var(--jd-text); }
    .audit-role { font-size:11px; color:var(--jd-text-3); text-transform:uppercase; letter-spacing:.5px; margin-top:2px; }
    .audit-meta { display:flex; flex-wrap:wrap; gap:8px; margin-top:8px; }
    .audit-chip { display:inline-flex; align-items:center; gap:6px; padding:5px 10px; border-radius:999px; font-size:10.5px; font-weight:700; border:1px solid transparent; }
    .audit-chip.green { background:var(--jd-green-soft); color:var(--jd-green); border-color:var(--jd-green-border); }
    .audit-chip.red { background:var(--jd-red-soft); color:var(--jd-red); border-color:var(--jd-red-border); }
    .audit-chip.amber { background:var(--jd-amber-soft); color:var(--jd-amber); border-color:var(--jd-amber-border); }
    .audit-chip.blue { background:var(--jd-primary-soft); color:var(--jd-primary); border-color:var(--jd-primary-border); }
    .audit-chip.violet { background:var(--jd-violet-soft); color:var(--jd-violet); border-color:var(--jd-violet-border); }
    .audit-chip.gray { background:var(--jd-bg); color:var(--jd-text-2); border-color:var(--jd-border); }
    .audit-time { text-align:right; }
    .audit-time .rel { font-size:13px; font-weight:800; color:var(--jd-text); }
    .audit-time .abs { font-size:11px; color:var(--jd-text-3); margin-top:3px; }
    .audit-grid { display:grid; grid-template-columns: repeat(4, 1fr); gap:10px; margin-top:16px; }
    .audit-info { padding:12px; border-radius:14px; background:var(--jd-bg); border:1px solid var(--jd-border); }
    .audit-info .k { font-size:10px; text-transform:uppercase; letter-spacing:.5px; color:var(--jd-text-3); font-weight:700; }
    .audit-info .v { margin-top:4px; font-size:13px; font-weight:700; color:var(--jd-text); word-break:break-word; }
    .audit-unknown { padding:12px; border-radius:14px; background:var(--jd-red-soft); border:1px solid var(--jd-red-border); color:var(--jd-red); margin-top:14px; font-size:12px; font-weight:700; }
    .audit-footer { display:flex; justify-content:space-between; align-items:center; gap:10px; margin-top:14px; padding-top:12px; border-top:1px solid var(--jd-border); flex-wrap:wrap; }
    .audit-severity { display:inline-flex; align-items:center; gap:6px; padding:6px 12px; border-radius:999px; font-size:11px; font-weight:700; }
    .audit-severity.low { background:var(--jd-green-soft); color:var(--jd-green); border:1px solid var(--jd-green-border); }
    .audit-severity.medium { background:var(--jd-amber-soft); color:var(--jd-amber); border:1px solid var(--jd-amber-border); }
    .audit-severity.high { background:var(--jd-red-soft); color:var(--jd-red); border:1px solid var(--jd-red-border); }
    .audit-pager { display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap; margin-top:20px; }
    .audit-pager-info { font-size:12px; color:var(--jd-text-3); }
    .audit-pager-wrap .pagination { margin:0; }
    .audit-empty { background:var(--jd-card); border:1px dashed var(--jd-border); border-radius:18px; box-shadow:var(--jd-shadow); padding:42px 20px; text-align:center; }
    .audit-empty-illus { width:88px; height:88px; margin:0 auto 16px; border-radius:28px; display:flex; align-items:center; justify-content:center; background:linear-gradient(135deg, rgba(37,99,235,.14), rgba(37,99,235,.05)); border:1px solid var(--jd-primary-border); color:var(--jd-primary); font-size:32px; }
    @media (max-width: 1199.98px) { .audit-hero-grid, .audit-kpis { grid-template-columns:1fr 1fr; } .audit-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 767.98px) { .audit-hero-grid, .audit-kpis, .audit-split, .audit-grid { grid-template-columns:1fr; } .audit-event { grid-template-columns:1fr; } .audit-feed::before { display:none; } .audit-time { text-align:left; } }
</style>
@endpush

@section('content')
@include('component.admin.jadwal-module')

@php
    $today = \Carbon\Carbon::now()->translatedFormat('l, d F Y');
    $all = $histories->getCollection();
    $successCount = $all->where('login_status', 'success')->count();
    $failedCount = $all->where('login_status', 'failed')->count();
    $throttledCount = $all->where('login_status', 'throttled')->count();
    $otpSuccessCount = $all->where('otp_status', 'success')->count();
    $otpFailedCount = $all->where('otp_status', 'failed')->count();
    $activeCount = $all->whereNull('logout_at')->count();
    $userActiveCount = $all->whereNotNull('user_id')->pluck('user_id')->unique()->count();

    $grouped = $all->groupBy(function ($h) {
        $loginAt = $h->login_at;
        if (!$loginAt) return 'Tanpa Tanggal';
        if ($loginAt->isToday()) return 'Hari Ini';
        if ($loginAt->isYesterday()) return 'Kemarin';
        if ($loginAt->greaterThanOrEqualTo(now()->startOfWeek())) return 'Minggu Ini';
        return 'Bulan Ini';
    });
@endphp

<div class="jd-mod audit-mod">
    <div class="audit-wrap">
        <div class="jd-hero">
            <div class="jd-hero-grid">
                <div class="jd-hero-left">
                    <span class="jd-hero-icon"><i class="bi bi-shield-exclamation"></i></span>
                    <div>
                        <h1 class="jd-hero-title">Authentication Audit Center</h1>
                        <p class="jd-hero-sub">Pusat audit login untuk investigasi keamanan, review OTP challenge, dan deteksi aktivitas mencurigakan secara cepat.</p>
                        <div class="jd-hero-badges">
                            <span class="jd-hero-badge"><i class="bi bi-calendar-event"></i>{{ $today }}</span>
                            <span class="jd-hero-badge"><i class="bi bi-database"></i>{{ $histories->total() }} total aktivitas</span>
                        </div>
                    </div>
                </div>
                <div class="jd-hero-right">
                    <a href="{{ route('admin.login-history.index', request()->query()) }}" class="jd-btn jd-btn--light"><i class="bi bi-arrow-clockwise"></i> Refresh</a>
                    <button type="button" class="jd-btn jd-btn--light" onclick="window.print()"><i class="bi bi-download"></i> Export</button>
                    <a href="{{ route('admin.security-dashboard.index') }}" class="jd-btn jd-btn--light"><i class="bi bi-shield-lock"></i> Security Dashboard</a>
                </div>
            </div>
        </div>

        <div class="audit-hero-grid">
            <div class="audit-hero-box">
                <div class="audit-stat-big" data-count="{{ $histories->total() }}">{{ $histories->total() }}</div>
                <div class="audit-stat-label">Total Login Events</div>
                <div class="audit-stat-sub">Login berhasil, gagal, terblokir, session aktif, dan OTP challenge.</div>
                <div class="audit-split">
                    <div class="audit-split-item"><div class="n" data-count="{{ $successCount }}">{{ $successCount }}</div><div class="l">Berhasil</div></div>
                    <div class="audit-split-item"><div class="n" data-count="{{ $failedCount }}">{{ $failedCount }}</div><div class="l">Gagal</div></div>
                    <div class="audit-split-item"><div class="n" data-count="{{ $throttledCount }}">{{ $throttledCount }}</div><div class="l">Terblokir</div></div>
                </div>
            </div>
            <div class="audit-risk-box">
                <div class="audit-stat-label" style="margin-top:0;">Perlu Perhatian</div>
                <div class="audit-risk-list">
                    <div class="audit-risk-item"><strong>Session Aktif</strong><span>{{ $activeCount }}</span></div>
                    <div class="audit-risk-item"><strong>OTP Challenge</strong><span>{{ $otpSuccessCount + $otpFailedCount }}</span></div>
                    <div class="audit-risk-item"><strong>Unknown User</strong><span>{{ $all->whereNull('user_id')->count() }}</span></div>
                    <div class="audit-risk-item"><strong>User Aktif</strong><span>{{ $userActiveCount }}</span></div>
                </div>
            </div>
        </div>

        <div class="audit-kpis">
            <div class="audit-kpi"><span class="audit-kpi-icon green"><i class="bi bi-check2-circle"></i></span><div><div class="audit-kpi-num" data-count="{{ $successCount }}">{{ $successCount }}</div><div class="audit-kpi-label">Login Berhasil</div></div></div>
            <div class="audit-kpi"><span class="audit-kpi-icon red"><i class="bi bi-x-circle"></i></span><div><div class="audit-kpi-num" data-count="{{ $failedCount }}">{{ $failedCount }}</div><div class="audit-kpi-label">Login Gagal</div></div></div>
            <div class="audit-kpi"><span class="audit-kpi-icon amber"><i class="bi bi-slash-circle"></i></span><div><div class="audit-kpi-num" data-count="{{ $throttledCount }}">{{ $throttledCount }}</div><div class="audit-kpi-label">Terblokir</div></div></div>
            <div class="audit-kpi"><span class="audit-kpi-icon blue"><i class="bi bi-shield-check"></i></span><div><div class="audit-kpi-num" data-count="{{ $otpSuccessCount }}">{{ $otpSuccessCount }}</div><div class="audit-kpi-label">OTP Berhasil</div></div></div>
            <div class="audit-kpi"><span class="audit-kpi-icon violet"><i class="bi bi-shield-x"></i></span><div><div class="audit-kpi-num" data-count="{{ $otpFailedCount }}">{{ $otpFailedCount }}</div><div class="audit-kpi-label">OTP Gagal</div></div></div>
            <div class="audit-kpi"><span class="audit-kpi-icon blue"><i class="bi bi-person-badge"></i></span><div><div class="audit-kpi-num" data-count="{{ $userActiveCount }}">{{ $userActiveCount }}</div><div class="audit-kpi-label">User Aktif</div></div></div>
        </div>

        <form id="loginHistoryFilter" method="GET" class="jd-toolbar audit-toolbar" autocomplete="off">
            <div class="jd-filter jd-filter--perpage"><label>Per Page</label>
                <select name="per_page" class="jd-select">
                    @foreach ([10, 15, 25, 50, 100] as $opt)
                        <option value="{{ $opt }}" {{ $perPage === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                </select>
            </div>
            <div class="jd-filter"><label>Status</label>
                <select name="status" class="jd-select">
                    <option value="">Semua status</option>
                    <option value="success" @if(request('status')==='success') selected @endif>Berhasil</option>
                    <option value="failed" @if(request('status')==='failed') selected @endif>Gagal</option>
                    <option value="throttled" @if(request('status')==='throttled') selected @endif>Terblokir</option>
                </select>
            </div>
            <div class="jd-search"><i class="bi bi-search"></i>
                <input type="search" name="search" value="{{ request('search') }}" class="jd-control" placeholder="Cari nama / username / email user...">
            </div>
            <div class="jd-toolbar-actions">
                <a href="{{ route('admin.login-history.index') }}" class="jd-btn jd-btn--ghost"><i class="bi bi-arrow-counterclockwise"></i> Reset Filter</a>
                <a href="{{ route('admin.login-history.index', request()->query()) }}" class="jd-btn jd-btn--soft"><i class="bi bi-arrow-clockwise"></i> Refresh</a>
            </div>
        </form>

        @if ($histories->isEmpty())
            <div class="audit-empty">
                <div class="audit-empty-illus"><i class="bi bi-inbox"></i></div>
                <div class="jd-empty-title">Belum ada aktivitas login</div>
                <div class="jd-empty-sub">Audit timeline akan muncul di sini setelah sistem mencatat percobaan login.</div>
            </div>
        @else
            <div class="audit-feed">
                @foreach($grouped as $group => $items)
                <div class="audit-group">
                    <div class="audit-group-title"><i class="bi bi-calendar3"></i>{{ $group }}</div>
                    @foreach ($items as $h)
                        @php
                            $unknown = !$h->user;
                            $suspicious = $unknown || $h->is_new_ip || $h->is_new_device || $h->login_status !== 'success';
                            $statusCls = $h->login_status === 'success' ? 'green' : ($h->login_status === 'failed' ? 'red' : 'amber');
                            $statusText = $h->login_status === 'success' ? 'Login Berhasil' : ($h->login_status === 'failed' ? 'Login Gagal' : 'Login Terblokir');
                            $otpCls = $h->otp_status === 'success' ? 'blue' : ($h->otp_status === 'failed' ? 'red' : 'gray');
                            $otpText = $h->otp_status === 'success' ? 'OTP Berhasil' : ($h->otp_status === 'failed' ? 'OTP Gagal' : 'Tidak Menggunakan OTP');
                            $severity = $unknown ? ['cls' => 'high', 'text' => 'Risiko Tinggi'] : (($h->login_status !== 'success' || $h->is_new_ip || $h->is_new_device) ? ['cls' => 'medium', 'text' => 'Perlu Perhatian'] : ['cls' => 'low', 'text' => 'Normal']);
                            $name = $h->user->name ?? 'Unknown User';
                            $role = $h->user->role ?? null;
                            $roleLabel = $role === 1 ? 'Admin' : ($role === 2 ? 'Guru' : ($role === 3 ? 'Siswa' : ($role === 4 ? 'BK' : ($role === 5 ? 'Kepala Sekolah' : 'Tidak diketahui'))));
                            $deviceKind = strtolower((string) $h->device_kind);
                            $deviceIcon = str_contains($deviceKind, 'mobile') ? 'bi-phone' : (str_contains($deviceKind, 'tablet') ? 'bi-tablet' : 'bi-pc-display');
                            $attempted = $h->metadata['attempted'] ?? '-';
                        @endphp
                        <article class="audit-event">
                            <div class="audit-node">
                                <div class="audit-dot {{ $statusCls === 'green' ? 'green' : ($statusCls === 'red' ? 'red' : 'amber') }}"><i class="bi {{ $deviceIcon }}"></i></div>
                            </div>
                            <div class="audit-card {{ $suspicious ? 'warn' : '' }}">
                                <div class="audit-head">
                                    <div class="audit-user">
                                        <div class="audit-avatar">{{ mb_strtoupper(mb_substr($name, 0, 1)) }}</div>
                                        <div>
                                            <div class="audit-name">{{ $name }}</div>
                                            <div class="audit-role">{{ $unknown ? 'User Tidak Ditemukan' : $roleLabel }}</div>
                                            <div class="audit-meta">
                                                <span class="audit-chip {{ $statusCls }}"><i class="bi bi-shield-check"></i>{{ $statusText }}</span>
                                                <span class="audit-chip {{ $otpCls }}"><i class="bi bi-key"></i>{{ $otpText }}</span>
                                                @if($h->logout_at)
                                                    <span class="audit-chip gray"><i class="bi bi-box-arrow-right"></i>Logout {{ $h->logout_at->format('H:i') }}</span>
                                                @else
                                                    <span class="audit-chip violet"><i class="bi bi-broadcast-pin"></i>Aktif Sekarang</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="audit-time">
                                        <div class="rel">{{ $h->login_at?->diffForHumans() }}</div>
                                        <div class="abs">{{ $h->login_at?->format('d M Y • H:i:s') }}</div>
                                    </div>
                                </div>

                                <div class="audit-grid">
                                    <div class="audit-info"><div class="k">Browser</div><div class="v">{{ $h->browser }}</div></div>
                                    <div class="audit-info"><div class="k">OS</div><div class="v">{{ $h->os }}</div></div>
                                    <div class="audit-info"><div class="k">Device</div><div class="v">{{ $h->device }}</div></div>
                                    <div class="audit-info"><div class="k">IP Address</div><div class="v"><code>{{ $h->ip_address }}</code></div></div>
                                </div>

                                @if($unknown)
                                    <div class="audit-unknown"><i class="bi bi-exclamation-triangle-fill me-1"></i> Attempted Username / Email: {{ $attempted }}</div>
                                @endif

                                <div class="audit-footer">
                                    <span class="audit-severity {{ $severity['cls'] }}"><i class="bi bi-activity"></i>{{ $severity['text'] }}</span>
                                    <button type="button" class="jd-btn jd-btn--outline jd-btn--sm" data-bs-toggle="modal" data-bs-target="#detailModal"
                                        data-name="{{ $name }}"
                                        data-role="{{ $unknown ? 'User Tidak Ditemukan' : $roleLabel }}"
                                        data-time="{{ $h->login_at?->format('d M Y H:i:s') }}"
                                        data-browser="{{ $h->browser }}"
                                        data-os="{{ $h->os }}"
                                        data-device="{{ $h->device }}"
                                        data-ip="{{ $h->ip_address }}"
                                        data-status="{{ $statusText }}"
                                        data-otp="{{ $otpText }}"
                                        data-logout="{{ $h->logout_at ? $h->logout_at->format('d M Y H:i:s') : 'Aktif Sekarang' }}"
                                        data-attempted="{{ $attempted }}"><i class="bi bi-search"></i> Lihat Detail Lengkap</button>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
                @endforeach
            </div>

            <div class="audit-pager">
                <div class="audit-pager-info">Menampilkan <b>{{ $histories->firstItem() ?? 0 }}</b>–<b>{{ $histories->lastItem() ?? 0 }}</b> dari <b>{{ $histories->total() }}</b> aktivitas</div>
                <div class="audit-pager-wrap">{{ $histories->onEachSide(1)->links() }}</div>
            </div>
        @endif
    </div>
</div>

<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:20px; overflow:hidden;">
            <div class="modal-header border-0 pb-0"><h5 class="modal-title fw-bold">Detail Login</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="row g-3" id="detailGrid"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const form = document.getElementById('loginHistoryFilter');
    if (form) {
        function applyFilter() {
            const params = new URLSearchParams();
            const data = new FormData(form);
            for (const [k, v] of data.entries()) if (v) params.append(k, v);
            window.location.search = params.toString();
        }
        let debounce;
        form.querySelectorAll('select').forEach(el => el.addEventListener('change', applyFilter));
        form.querySelectorAll('input[type="search"], input[type="text"]').forEach(el => {
            el.addEventListener('input', function () {
                clearTimeout(debounce);
                debounce = setTimeout(applyFilter, 350);
            });
        });
    }

    document.querySelectorAll('[data-count]').forEach(function (el) {
        const target = parseFloat(el.dataset.count) || 0;
        let t0 = null, dur = 900;
        function step(ts) {
            if (!t0) t0 = ts;
            const p = Math.min(1, (ts - t0) / dur);
            const eased = 1 - Math.pow(1 - p, 3);
            el.textContent = Math.round(target * eased).toLocaleString('id-ID');
            if (p < 1) requestAnimationFrame(step);
        }
        requestAnimationFrame(step);
    });

    const detailModal = document.getElementById('detailModal');
    if (detailModal) {
        detailModal.addEventListener('show.bs.modal', function (event) {
            const btn = event.relatedTarget;
            const fields = [
                ['User', btn.getAttribute('data-name')],
                ['Role', btn.getAttribute('data-role')],
                ['Tanggal', btn.getAttribute('data-time')],
                ['Browser', btn.getAttribute('data-browser')],
                ['OS', btn.getAttribute('data-os')],
                ['Device', btn.getAttribute('data-device')],
                ['IP', btn.getAttribute('data-ip')],
                ['Status', btn.getAttribute('data-status')],
                ['OTP', btn.getAttribute('data-otp')],
                ['Logout', btn.getAttribute('data-logout')],
                ['Attempted', btn.getAttribute('data-attempted')]
            ];
            document.getElementById('detailGrid').innerHTML = fields.map(function (item) {
                return '<div class="col-md-6"><div class="audit-info h-100"><div class="k">' + item[0] + '</div><div class="v">' + (item[1] || '-') + '</div></div></div>';
            }).join('');
        });
    }

    document.querySelectorAll('.jd-btn').forEach(function (btn) {
        btn.addEventListener('click', function (e) { if (window.JD) JD.ripple(e); });
    });
})();
</script>
@endpush
