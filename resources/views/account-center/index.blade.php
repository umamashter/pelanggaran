@extends('layouts.main')

@section('title', 'Profil Saya')

@push('css')
<style>
    .account-page {
        --account-primary: #0369a1;
        --account-secondary: #0ea5e9;
        --account-accent: #16a34a;
        --account-bg: #f0f9ff;
        --account-border: #bae6fd;
        --account-ink: #0c4a6e;
        --account-muted: #64748b;
    }
    .account-hero {
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #082f49 0%, #0369a1 55%, #16a34a 100%);
        color: #fff;
        border-radius: 28px;
        padding: 28px;
        box-shadow: 0 24px 50px rgba(3, 105, 161, .20);
    }
    .account-hero::after {
        content: '';
        position: absolute;
        inset: auto -60px -80px auto;
        width: 220px;
        height: 220px;
        border-radius: 999px;
        background: radial-gradient(circle, rgba(255,255,255,.24), rgba(255,255,255,0));
        pointer-events: none;
    }
    .account-avatar {
        width: 96px;
        height: 96px;
        border-radius: 28px;
        object-fit: cover;
        background: rgba(255,255,255,.18);
        border: 3px solid rgba(255,255,255,.35);
        box-shadow: 0 16px 34px rgba(8, 47, 73, .24);
    }
    .hero-kicker {
        letter-spacing: .08em;
        text-transform: uppercase;
        font-size: 12px;
        font-weight: 700;
        color: rgba(255,255,255,.72);
    }
    .hero-subtitle {
        color: rgba(255,255,255,.78);
        max-width: 620px;
    }
    .hero-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
        margin-top: 20px;
    }
    .hero-stat {
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.12);
        border-radius: 18px;
        padding: 14px 16px;
        backdrop-filter: blur(8px);
    }
    .hero-stat-label {
        font-size: 12px;
        color: rgba(255,255,255,.72);
        margin-bottom: 4px;
    }
    .hero-stat-value {
        font-size: 20px;
        font-weight: 800;
        line-height: 1.1;
    }
    .soft-card {
        border: 1px solid rgba(186, 230, 253, .9);
        border-radius: 24px;
        background: #fff;
        box-shadow: 0 18px 40px rgba(15, 23, 42, .06);
        overflow: hidden;
    }
    .soft-card .card-header {
        border-bottom: 1px solid #e2e8f0;
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        border-radius: 24px 24px 0 0 !important;
        padding: 18px 22px;
    }
    .soft-card .card-body {
        padding: 22px;
    }
    .nav-account {
        gap: 10px;
    }
    .nav-account .nav-link {
        border-radius: 16px;
        color: var(--account-ink);
        font-weight: 700;
        margin-right: 0;
        padding: 12px 16px;
        background: #fff;
        border: 1px solid var(--account-border);
        transition: all .2s ease;
    }
    .nav-account .nav-link:hover,
    .nav-account .nav-link:focus {
        background: #e0f2fe;
        color: var(--account-primary);
    }
    .nav-account .nav-link.active {
        background: linear-gradient(135deg, var(--account-primary), var(--account-accent));
        color: #fff;
        border-color: transparent;
        box-shadow: 0 12px 24px rgba(3, 105, 161, .24);
    }
    .meta-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 13px;
        border-radius: 999px;
        background: rgba(255,255,255,.14);
        color: #fff;
        font-size: 13px;
        margin-right: 8px;
        margin-bottom: 8px;
        border: 1px solid rgba(255,255,255,.12);
    }
    .section-copy {
        color: var(--account-muted);
        font-size: 14px;
    }
    .field-hint {
        font-size: 12px;
        color: var(--account-muted);
        margin-top: 6px;
    }
    .account-page .form-label {
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 8px;
    }
    .account-page .form-control,
    .account-page .form-select {
        min-height: 46px;
        border-radius: 14px;
        border-color: #cbd5e1;
        box-shadow: none;
    }
    .account-page textarea.form-control {
        min-height: 120px;
    }
    .account-page .form-control:focus,
    .account-page .form-select:focus,
    .account-page .form-check-input:focus {
        border-color: var(--account-secondary);
        box-shadow: 0 0 0 .2rem rgba(14, 165, 233, .16);
    }
    .summary-list {
        display: grid;
        gap: 12px;
    }
    .summary-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 12px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        background: #f8fafc;
    }
    .summary-item span {
        color: var(--account-muted);
        font-size: 13px;
    }
    .summary-item strong {
        color: #0f172a;
        text-align: right;
    }
    .info-list {
        display: grid;
        gap: 14px;
    }
    .info-item {
        padding-bottom: 14px;
        border-bottom: 1px solid #e2e8f0;
    }
    .info-item:last-child {
        padding-bottom: 0;
        border-bottom: 0;
    }
    .info-item small {
        display: block;
        color: var(--account-muted);
        margin-bottom: 4px;
    }
    .quick-action {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 14px 16px;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        text-decoration: none;
        color: inherit;
        transition: all .2s ease;
    }
    .quick-action:hover {
        border-color: var(--account-border);
        background: #f8fbff;
        transform: translateY(-1px);
    }
    .quick-action-icon {
        width: 42px;
        height: 42px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #e0f2fe;
        color: var(--account-primary);
        flex-shrink: 0;
    }
    .timeline-item {
        position: relative;
        padding-left: 18px;
        margin-bottom: 18px;
    }
    .timeline-item:before {
        content: '';
        position: absolute;
        left: 6px;
        top: 32px;
        bottom: -16px;
        width: 2px;
        background: #e2e8f0;
    }
    .timeline-dot {
        width: 30px;
        height: 30px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #e0f2fe;
        color: var(--account-primary);
        flex-shrink: 0;
    }
    .timeline-item:last-child:before {
        display: none;
    }
    .device-card {
        border-radius: 18px;
        border: 1px solid #dbeafe;
        background: #fff;
        padding: 16px;
    }
    .security-score-card {
        background: linear-gradient(135deg, #082f49 0%, #0f3f67 50%, #0369a1 100%);
        color: #fff;
        border-radius: 24px;
        padding: 24px;
        box-shadow: 0 20px 40px rgba(8, 47, 73, .24);
    }
    .security-progress {
        height: 10px;
        border-radius: 999px;
        background: rgba(255,255,255,.18);
        overflow: hidden;
    }
    .security-progress-bar {
        height: 100%;
        border-radius: inherit;
        background: linear-gradient(90deg, #38bdf8, #4ade80);
    }
    .security-metric {
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 16px;
        background: #fff;
        height: 100%;
    }
    .security-metric-label {
        color: var(--account-muted);
        font-size: 13px;
        margin-bottom: 6px;
    }
    .security-metric-value {
        font-weight: 800;
        color: #0f172a;
    }
    .stack-list {
        display: grid;
        gap: 14px;
    }
    .stack-card {
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 16px;
        background: #fff;
    }
    .stack-card--active {
        border-color: #86efac;
        background: linear-gradient(180deg, #f0fdf4 0%, #ffffff 100%);
    }
    .badge-soft {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        background: #e0f2fe;
        color: var(--account-primary);
    }
    .badge-soft--success {
        background: #dcfce7;
        color: #15803d;
    }
    .device-meta {
        color: var(--account-muted);
        font-size: 13px;
    }
    @media (max-width: 991.98px) {
        .hero-grid {
            grid-template-columns: 1fr;
        }
    }
    @media (prefers-reduced-motion: reduce) {
        .nav-account .nav-link,
        .quick-action {
            transition: none;
        }
    }
</style>
@endpush

@section('content')
@php
    $activeTab = request('tab', 'profil');
    $preferences = $preferences ?? [];
    $genderLabel = [
        'Laki-laki' => 'Laki-laki',
        'Perempuan' => 'Perempuan',
        'L' => 'Laki-laki',
        'P' => 'Perempuan',
    ][$user->gender ?? ''] ?? '-';
@endphp

<div class="account-page">
<div class="account-hero mb-4">
    <div class="d-flex flex-column flex-lg-row gap-4 align-items-lg-center justify-content-between position-relative" style="z-index:1;">
        <div class="d-flex align-items-start gap-3">
            <img src="{{ $user->avatar_url }}" alt="Avatar {{ $user->name }}" class="account-avatar">
            <div>
                <div class="hero-kicker mb-2">Profil Saya</div>
                <h2 class="mb-2">{{ $user->name }}</h2>
                <div class="hero-subtitle mb-3">Kelola identitas akun, keamanan masuk, preferensi pribadi, dan perangkat yang masih terhubung dalam satu halaman.</div>
                <div class="opacity-75">{{ $user->username }} @if($user->email) · {{ $user->email }} @endif</div>
            </div>
        </div>
        <div class="text-lg-end">
            <div class="meta-chip"><i class="bi bi-person-badge"></i> Profil {{ $security['profile'] }}</div>
            <div class="meta-chip"><i class="bi bi-shield-lock"></i> 2FA {{ $security['two_factor'] }}</div>
            <div class="meta-chip"><i class="bi bi-envelope-check"></i> Email {{ $security['email'] }}</div>
        </div>
    </div>
    <div class="hero-grid position-relative" style="z-index:1;">
        <div class="hero-stat">
            <div class="hero-stat-label">Sesi aktif</div>
            <div class="hero-stat-value">{{ $security['sessions'] }}</div>
        </div>
        <div class="hero-stat">
            <div class="hero-stat-label">Perangkat tepercaya</div>
            <div class="hero-stat-value">{{ $security['trusted_devices'] }}</div>
        </div>
        <div class="hero-stat">
            <div class="hero-stat-label">Status akun</div>
            <div class="hero-stat-value">{{ $security['profile'] }}</div>
        </div>
    </div>
</div>

<ul class="nav nav-pills nav-account mb-4 flex-wrap" role="tablist">
    <li class="nav-item"><a class="nav-link {{ $activeTab === 'profil' ? 'active' : '' }}" href="{{ route('profil-saya.index', ['tab' => 'profil']) }}">Informasi Profil</a></li>
    <li class="nav-item"><a class="nav-link {{ $activeTab === 'keamanan' ? 'active' : '' }}" href="{{ route('profil-saya.index', ['tab' => 'keamanan']) }}">Keamanan Akun</a></li>
    <li class="nav-item"><a class="nav-link {{ $activeTab === 'preferensi' ? 'active' : '' }}" href="{{ route('profil-saya.index', ['tab' => 'preferensi']) }}">Preferensi</a></li>
    <li class="nav-item"><a class="nav-link {{ $activeTab === 'aktivitas' ? 'active' : '' }}" href="{{ route('profil-saya.index', ['tab' => 'aktivitas']) }}">Aktivitas Saya</a></li>
    <li class="nav-item"><a class="nav-link {{ $activeTab === 'perangkat' ? 'active' : '' }}" href="{{ route('profil-saya.index', ['tab' => 'perangkat']) }}">Perangkat Saya</a></li>
</ul>

<div class="row g-4">
    <div class="col-12 col-xl-8">
        @if($activeTab === 'profil')
        <div class="card soft-card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <strong>Informasi Profil</strong>
                    <div class="section-copy mt-1">Perbarui identitas utama agar data akun tetap valid dan mudah dikenali.</div>
                </div>
                <small class="text-muted">Status: {{ $security['profile'] }}</small>
            </div>
            <div class="card-body">
                <form action="{{ route('profil-saya.profile') }}" method="POST" class="row g-3">
                    @csrf
                    <div class="col-md-6">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        <div class="field-hint">Gunakan nama yang tampil di profil dan dashboard.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" value="{{ old('username', $user->username) }}" required>
                        <div class="field-hint">Username dipakai saat login jika tidak menggunakan email.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
                        <div class="field-hint">Pastikan email aktif agar notifikasi dan verifikasi tidak gagal.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">No. HP</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                        <div class="field-hint">Nomor ini dapat dipakai untuk komunikasi penting sekolah.</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="gender" class="form-select">
                            <option value="">-</option>
                            <option value="Laki-laki" {{ old('gender', $user->gender) === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('gender', $user->gender) === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date', optional($user->birth_date)->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status Profil</label>
                        <input type="text" class="form-control" value="{{ $security['profile'] }}" disabled>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Alamat</label>
                        <textarea name="address" rows="4" class="form-control">{{ old('address', $user->address) }}</textarea>
                        <div class="field-hint">Isi alamat domisili terbaru untuk kebutuhan administrasi.</div>
                    </div>
                    <div class="col-12 d-flex justify-content-end">
                        <button class="btn btn-success px-4">Simpan Profil</button>
                    </div>
                </form>
            </div>
        </div>
        @elseif($activeTab === 'keamanan')
        @php
            $securityScore = 0;
            $securityScore += str_contains(strtolower($security['email']), 'terverifikasi') ? 20 : 0;
            $securityScore += str_contains(strtolower($security['password']), 'aktif') ? 20 : 0;
            $securityScore += str_contains(strtolower($security['two_factor']), 'aktif') ? 25 : 0;
            $securityScore += (int) $security['sessions'] > 0 ? 15 : 0;
            $securityScore += (int) $security['trusted_devices'] > 0 ? 20 : 0;
        @endphp
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card soft-card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <strong>Keamanan Akun</strong>
                            <div class="section-copy mt-1">Pantau kesehatan akun dan lakukan tindakan cepat saat ada risiko.</div>
                        </div>
                        <span class="badge-soft"><i class="bi bi-shield-lock"></i> Skor {{ $securityScore }}/100</span>
                    </div>
                    <div class="card-body">
                        <div class="security-score-card mb-4">
                            <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-3">
                                <div>
                                    <div class="small text-white-50 mb-1">Health keamanan</div>
                                    <div style="font-size:42px;font-weight:800;line-height:1;">{{ $securityScore }}/100</div>
                                    <div class="mt-2" style="font-size:13px;opacity:.85;">Penilaian ini dihitung dari email, password, 2FA, sesi aktif, dan perangkat tepercaya.</div>
                                </div>
                                <span class="meta-chip">2FA {{ $security['two_factor'] }}</span>
                            </div>
                            <div class="security-progress" aria-hidden="true">
                                <div class="security-progress-bar" style="width: {{ $securityScore }}%;"></div>
                            </div>
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6"><div class="security-metric"><div class="security-metric-label">Password</div><div class="security-metric-value">{{ $security['password'] }}</div></div></div>
                            <div class="col-md-6"><div class="security-metric"><div class="security-metric-label">2FA</div><div class="security-metric-value">{{ $security['two_factor'] }}</div></div></div>
                            <div class="col-md-6"><div class="security-metric"><div class="security-metric-label">Sesi aktif</div><div class="security-metric-value">{{ $security['sessions'] }}</div></div></div>
                            <div class="col-md-6"><div class="security-metric"><div class="security-metric-label">Perangkat tepercaya</div><div class="security-metric-value">{{ $security['trusted_devices'] }}</div></div></div>
                        </div>
                        <div class="stack-list">
                            <a href="{{ route('2fa.setup') }}" class="quick-action">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="quick-action-icon"><i class="bi bi-shield-check"></i></span>
                                    <div>
                                        <div class="fw-semibold">Kelola autentikasi 2 faktor</div>
                                        <div class="section-copy">Aktifkan atau cek ulang perangkat untuk login yang lebih aman.</div>
                                    </div>
                                </div>
                                <i class="bi bi-chevron-right text-muted"></i>
                            </a>
                            <a href="{{ route('2fa.recovery-codes') }}" class="quick-action">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="quick-action-icon"><i class="bi bi-key"></i></span>
                                    <div>
                                        <div class="fw-semibold">Lihat recovery codes</div>
                                        <div class="section-copy">Simpan kode cadangan agar tetap bisa masuk saat OTP tidak tersedia.</div>
                                    </div>
                                </div>
                                <i class="bi bi-chevron-right text-muted"></i>
                            </a>
                            <a href="{{ route('login-history.index') }}" class="quick-action">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="quick-action-icon"><i class="bi bi-clock-history"></i></span>
                                    <div>
                                        <div class="fw-semibold">Tinjau riwayat login</div>
                                        <div class="section-copy">Periksa kapan dan dari mana akun terakhir diakses.</div>
                                    </div>
                                </div>
                                <i class="bi bi-chevron-right text-muted"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card soft-card h-100">
                    <div class="card-header">
                        <strong>Ganti Password</strong>
                        <div class="section-copy mt-1">Gunakan password yang kuat dan berbeda dari akun lain.</div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profil-saya.password') }}" method="POST" class="row g-3">
                            @csrf
                            <div class="col-12">
                                <label class="form-label">Password Saat Ini</label>
                                <input type="password" name="current_password" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Password Baru</label>
                                <input type="password" name="password" class="form-control" required>
                                <div class="field-hint">Minimal gunakan kombinasi huruf, angka, dan simbol yang mudah Anda ingat.</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                            <div class="col-12 d-flex justify-content-end">
                                <button class="btn btn-success px-4">Ubah Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @elseif($activeTab === 'preferensi')
        <div class="card soft-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>Preferensi</strong>
                <small class="text-muted">Simpan tampilan dan notifikasi pribadi</small>
            </div>
            <div class="card-body">
                <form action="{{ route('profil-saya.preferences') }}" method="POST" class="row g-3">
                    @csrf
                    <div class="col-md-6">
                        <label class="form-label">Tema</label>
                        <select name="theme" class="form-select">
                            <option value="system" {{ old('theme', $preferences['theme']) === 'system' ? 'selected' : '' }}>Sistem</option>
                            <option value="light" {{ old('theme', $preferences['theme']) === 'light' ? 'selected' : '' }}>Terang</option>
                            <option value="dark" {{ old('theme', $preferences['theme']) === 'dark' ? 'selected' : '' }}>Gelap</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Bahasa</label>
                        <select name="language" class="form-select">
                            <option value="id" {{ old('language', $preferences['language']) === 'id' ? 'selected' : '' }}>Indonesia</option>
                            <option value="en" {{ old('language', $preferences['language']) === 'en' ? 'selected' : '' }}>English</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Timezone</label>
                        <input type="text" name="timezone" class="form-control" value="{{ old('timezone', $preferences['timezone']) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Format Tanggal</label>
                        <select name="date_format" class="form-select">
                            <option value="d/m/Y" {{ old('date_format', $preferences['date_format']) === 'd/m/Y' ? 'selected' : '' }}>31/12/2026</option>
                            <option value="m/d/Y" {{ old('date_format', $preferences['date_format']) === 'm/d/Y' ? 'selected' : '' }}>12/31/2026</option>
                            <option value="Y-m-d" {{ old('date_format', $preferences['date_format']) === 'Y-m-d' ? 'selected' : '' }}>2026-12-31</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label d-block">Notifikasi Browser</label>
                        <div class="form-check form-switch">
                            <input type="hidden" name="notify_browser" value="0">
                            <input class="form-check-input" type="checkbox" name="notify_browser" value="1" {{ old('notify_browser', $preferences['notify_browser']) ? 'checked' : '' }}>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label d-block">Notifikasi Email</label>
                        <div class="form-check form-switch">
                            <input type="hidden" name="notify_email" value="0">
                            <input class="form-check-input" type="checkbox" name="notify_email" value="1" {{ old('notify_email', $preferences['notify_email']) ? 'checked' : '' }}>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label d-block">Notifikasi WhatsApp</label>
                        <div class="form-check form-switch">
                            <input type="hidden" name="notify_whatsapp" value="0">
                            <input class="form-check-input" type="checkbox" name="notify_whatsapp" value="1" {{ old('notify_whatsapp', $preferences['notify_whatsapp']) ? 'checked' : '' }}>
                        </div>
                    </div>
                    <div class="col-12 d-flex justify-content-end">
                        <button class="btn btn-success px-4">Simpan Preferensi</button>
                    </div>
                </form>
            </div>
        </div>
        @elseif($activeTab === 'aktivitas')
        <div class="card soft-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>Aktivitas Saya</strong>
                <a href="{{ route('login-history.index') }}" class="btn btn-sm btn-outline-success">Lihat semua login</a>
            </div>
            <div class="card-body">
                @forelse($timeline as $item)
                    <div class="timeline-item d-flex gap-3">
                        <div class="timeline-dot mt-1"><i class="bi {{ $item['icon'] }}"></i></div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between flex-wrap gap-2">
                                <strong>{{ $item['title'] }}</strong>
                                <small class="text-muted">{{ optional($item['occurred_at'])->diffForHumans() }}</small>
                            </div>
                            <div class="text-muted">{{ $item['description'] }}</div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">Belum ada aktivitas tercatat.</div>
                @endforelse
            </div>
        </div>
        @elseif($activeTab === 'perangkat')
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card soft-card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <strong>Perangkat Aktif</strong>
                            <div class="section-copy mt-1">Cek sesi yang masih login dan cabut akses yang tidak Anda kenali.</div>
                        </div>
                        <a href="{{ route('active-sessions.index') }}" class="btn btn-sm btn-outline-success">Kelola semua</a>
                    </div>
                    <div class="card-body">
                        <div class="stack-list">
                        @forelse($sessions as $session)
                            <div class="stack-card {{ $session['is_current'] ? 'stack-card--active' : '' }}">
                                <div class="d-flex justify-content-between gap-3 flex-wrap">
                                    <div>
                                        <div class="fw-semibold">{{ $session['browser'] ?? 'Unknown' }} · {{ $session['os'] ?? 'Unknown' }}</div>
                                        <div class="device-meta mt-1">{{ $session['device'] ?? 'Desktop' }} · {{ $session['ip'] ?? '-' }}</div>
                                    </div>
                                    <div class="text-end">
                                        <div class="device-meta">Aktif {{ optional($session['last_activity'])->diffForHumans() }}</div>
                                        @if($session['is_current'])
                                            <span class="badge-soft badge-soft--success mt-2"><i class="bi bi-check-circle"></i> Sesi ini</span>
                                        @endif
                                    </div>
                                </div>
                                @if(!$session['is_current'])
                                    <form action="{{ route('active-sessions.revoke', $session['id']) }}" method="POST" class="mt-3">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-danger">Logout perangkat</button>
                                    </form>
                                @endif
                            </div>
                        @empty
                            <div class="text-muted">Belum ada sesi aktif.</div>
                        @endforelse
                        </div>
                        <form action="{{ route('active-sessions.revoke-others') }}" method="POST" class="mt-3">
                            @csrf
                            <button class="btn btn-success">Logout perangkat lain</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card soft-card h-100">
                    <div class="card-header">
                        <strong>Perangkat Tepercaya</strong>
                        <div class="section-copy mt-1">Atur perangkat yang boleh diperlakukan sebagai perangkat aman.</div>
                    </div>
                    <div class="card-body">
                        <div class="stack-list">
                        @forelse($devices as $device)
                            <div class="stack-card">
                                <div class="d-flex justify-content-between align-items-start gap-2">
                                    <div>
                                        <div class="fw-semibold">{{ $device->browser ?? 'Unknown' }} · {{ $device->os ?? 'Unknown' }}</div>
                                        <div class="device-meta mt-1">{{ $device->device ?? 'Desktop' }}</div>
                                        <div class="device-meta">Terakhir aktif: {{ optional($device->last_seen_at)->diffForHumans() }}</div>
                                    </div>
                                    <span class="badge-soft {{ $device->is_trusted ? 'badge-soft--success' : '' }}">{{ $device->is_trusted ? 'Tepercaya' : 'Biasa' }}</span>
                                </div>
                                <div class="d-flex flex-wrap gap-2 mt-3">
                                    @if($device->is_trusted)
                                        <form action="{{ route('active-sessions.untrust', $device->id) }}" method="POST">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-secondary">Cabut tepercaya</button>
                                        </form>
                                    @else
                                        <form action="{{ route('active-sessions.trust', $device->id) }}" method="POST">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-success">Tandai tepercaya</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-muted">Belum ada perangkat tersimpan.</div>
                        @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-12 col-xl-4">
        <div class="card soft-card mb-4">
            <div class="card-header"><strong>Ringkasan Akun</strong></div>
            <div class="card-body">
                <div class="summary-list">
                    <div class="summary-item"><span>Status profil</span><strong>{{ $security['profile'] }}</strong></div>
                    <div class="summary-item"><span>Status email</span><strong>{{ $security['email'] }}</strong></div>
                    <div class="summary-item"><span>Keamanan 2FA</span><strong>{{ $security['two_factor'] }}</strong></div>
                    <div class="summary-item"><span>Perangkat tepercaya</span><strong>{{ $security['trusted_devices'] }} perangkat</strong></div>
                    <div class="summary-item"><span>Jenis kelamin</span><strong>{{ $genderLabel }}</strong></div>
                </div>
            </div>
        </div>

        <div class="card soft-card mb-4">
            <div class="card-header"><strong>Aksi Cepat</strong></div>
            <div class="card-body d-grid gap-3">
                <a href="{{ route('2fa.setup') }}" class="quick-action">
                    <div class="d-flex align-items-center gap-3">
                        <span class="quick-action-icon"><i class="bi bi-shield-check"></i></span>
                        <div>
                            <div class="fw-semibold">Kelola autentikasi 2 faktor</div>
                            <div class="section-copy">Aktifkan atau perbarui perlindungan login akun Anda.</div>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right text-muted"></i>
                </a>
                <a href="{{ route('login-history.index') }}" class="quick-action">
                    <div class="d-flex align-items-center gap-3">
                        <span class="quick-action-icon"><i class="bi bi-clock-history"></i></span>
                        <div>
                            <div class="fw-semibold">Cek riwayat login</div>
                            <div class="section-copy">Pantau aktivitas masuk terakhir dan lokasi akses akun.</div>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right text-muted"></i>
                </a>
                <a href="{{ route('active-sessions.index') }}" class="quick-action">
                    <div class="d-flex align-items-center gap-3">
                        <span class="quick-action-icon"><i class="bi bi-laptop"></i></span>
                        <div>
                            <div class="fw-semibold">Kelola perangkat aktif</div>
                            <div class="section-copy">Cabut sesi lama dan tinjau perangkat yang masih terhubung.</div>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right text-muted"></i>
                </a>
            </div>
        </div>

        <div class="card soft-card mb-4">
            <div class="card-header"><strong>Foto Profil</strong></div>
            <div class="card-body text-center">
                <img src="{{ $user->avatar_url }}" alt="Avatar {{ $user->name }}" class="rounded-circle mb-3" style="width:120px;height:120px;object-fit:cover;">
                <div class="section-copy mb-3">Gunakan foto yang jelas agar akun mudah dikenali.</div>
                <form action="{{ route('profil-saya.photo') }}" method="POST" enctype="multipart/form-data" class="mb-3">
                    @csrf
                    <input type="file" name="avatar" class="form-control mb-3" accept="image/*" required>
                    <button class="btn btn-success w-100">Upload Foto</button>
                </form>
                <form action="{{ route('profil-saya.photo.destroy') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger w-100">Hapus Foto</button>
                </form>
            </div>
        </div>

        <div class="card soft-card">
            <div class="card-header"><strong>Identitas Singkat</strong></div>
            <div class="card-body">
                <div class="info-list">
                    <div class="info-item"><small>Nama Lengkap</small><strong>{{ $user->name }}</strong></div>
                    <div class="info-item"><small>Username</small><strong>{{ $user->username }}</strong></div>
                    <div class="info-item"><small>Email</small><strong>{{ $user->email ?: '-' }}</strong></div>
                    <div class="info-item"><small>No. HP</small><strong>{{ $user->phone ?: '-' }}</strong></div>
                    <div class="info-item"><small>Tanggal Lahir</small><strong>{{ optional($user->birth_date)->format('d M Y') ?: '-' }}</strong></div>
                    <div class="info-item"><small>Alamat</small><strong>{{ $user->address ?: '-' }}</strong></div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
