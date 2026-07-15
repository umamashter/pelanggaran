@extends('layouts.main')

@section('title', 'Profil Saya')

@push('css')
<style>
    .account-hero {
        background: linear-gradient(135deg, #0f766e, #16a34a);
        color: #fff;
        border-radius: 24px;
        padding: 24px;
        box-shadow: 0 20px 40px rgba(15,118,110,.18);
    }
    .account-avatar {
        width: 92px;
        height: 92px;
        border-radius: 24px;
        object-fit: cover;
        background: rgba(255,255,255,.18);
        border: 3px solid rgba(255,255,255,.35);
    }
    .soft-card {
        border: 0;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(15,23,42,.06);
    }
    .soft-card .card-header {
        border-bottom: 1px solid #e2e8f0;
        background: #fff;
        border-radius: 20px 20px 0 0 !important;
    }
    .nav-account .nav-link {
        border-radius: 14px;
        color: #475569;
        font-weight: 600;
        margin-right: 6px;
    }
    .nav-account .nav-link.active {
        background: #16a34a;
        color: #fff;
        box-shadow: 0 8px 18px rgba(22,163,74,.22);
    }
    .meta-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 12px;
        border-radius: 999px;
        background: rgba(255,255,255,.14);
        color: #fff;
        font-size: 13px;
        margin-right: 8px;
        margin-bottom: 8px;
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
        width: 28px;
        height: 28px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #e8f5e9;
        color: #16a34a;
        flex-shrink: 0;
    }
    .timeline-item:last-child:before {
        display: none;
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

<div class="account-hero mb-4">
    <div class="d-flex flex-column flex-lg-row gap-3 align-items-lg-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <img src="{{ $user->avatar_url }}" alt="Avatar" class="account-avatar">
            <div>
                <div class="small text-white-50 mb-1">Account Center</div>
                <h3 class="mb-1">{{ $user->name }}</h3>
                <div class="opacity-75">{{ $user->username }} @if($user->email) · {{ $user->email }} @endif</div>
            </div>
        </div>
        <div>
            <div class="meta-chip"><i class="bi bi-person-badge"></i> {{ $security['profile'] }}</div>
            <div class="meta-chip"><i class="bi bi-shield-lock"></i> 2FA {{ $security['two_factor'] }}</div>
            <div class="meta-chip"><i class="bi bi-envelope-check"></i> Email {{ $security['email'] }}</div>
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
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>Informasi Profil</strong>
                <small class="text-muted">Lengkapi data akun Anda</small>
            </div>
            <div class="card-body">
                <form action="{{ route('profil-saya.profile') }}" method="POST" class="row g-3">
                    @csrf
                    <div class="col-md-6">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" value="{{ old('username', $user->username) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">No. HP</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
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
                    </div>
                    <div class="col-12 d-flex justify-content-end">
                        <button class="btn btn-success px-4">Simpan Profil</button>
                    </div>
                </form>
            </div>
        </div>
        @elseif($activeTab === 'keamanan')
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card soft-card h-100">
                    <div class="card-header"><strong>Status Keamanan</strong></div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between px-0"><span>Email</span><strong>{{ $security['email'] }}</strong></li>
                            <li class="list-group-item d-flex justify-content-between px-0"><span>Password</span><strong>{{ $security['password'] }}</strong></li>
                            <li class="list-group-item d-flex justify-content-between px-0"><span>2FA</span><strong>{{ $security['two_factor'] }}</strong></li>
                            <li class="list-group-item d-flex justify-content-between px-0"><span>Sesi aktif</span><strong>{{ $security['sessions'] }}</strong></li>
                            <li class="list-group-item d-flex justify-content-between px-0"><span>Perangkat tepercaya</span><strong>{{ $security['trusted_devices'] }}</strong></li>
                        </ul>
                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <a href="{{ route('login-history.index') }}" class="btn btn-outline-success btn-sm">Riwayat Login</a>
                            <a href="{{ route('active-sessions.index') }}" class="btn btn-outline-success btn-sm">Perangkat Aktif</a>
                            <a href="{{ route('2fa.setup') }}" class="btn btn-outline-success btn-sm">Kelola 2FA</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card soft-card h-100">
                    <div class="card-header"><strong>Ganti Password</strong></div>
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
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <strong>Perangkat Aktif</strong>
                        <a href="{{ route('active-sessions.index') }}" class="btn btn-sm btn-outline-success">Kelola semua</a>
                    </div>
                    <div class="card-body">
                        @forelse($sessions as $session)
                            <div class="border rounded-4 p-3 mb-3 {{ $session['is_current'] ? 'border-success bg-light' : '' }}">
                                <div class="d-flex justify-content-between gap-3 flex-wrap">
                                    <div>
                                        <div class="fw-semibold">{{ $session['browser'] ?? 'Unknown' }} · {{ $session['os'] ?? 'Unknown' }}</div>
                                        <div class="text-muted small">{{ $session['device'] ?? 'Desktop' }} · {{ $session['ip'] ?? '-' }}</div>
                                    </div>
                                    <div class="text-end">
                                        <div class="small text-muted">Aktif {{ optional($session['last_activity'])->diffForHumans() }}</div>
                                        @if($session['is_current'])
                                            <span class="badge bg-success">Sesi ini</span>
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
                        <form action="{{ route('active-sessions.revoke-others') }}" method="POST" class="mt-2">
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
                    </div>
                    <div class="card-body">
                        @forelse($devices as $device)
                            <div class="border rounded-4 p-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start gap-2">
                                    <div>
                                        <div class="fw-semibold">{{ $device->browser ?? 'Unknown' }} · {{ $device->os ?? 'Unknown' }}</div>
                                        <div class="small text-muted">{{ $device->device ?? 'Desktop' }}</div>
                                        <div class="small text-muted">Terakhir: {{ optional($device->last_seen_at)->diffForHumans() }}</div>
                                    </div>
                                    <span class="badge {{ $device->is_trusted ? 'bg-success' : 'bg-secondary' }}">{{ $device->is_trusted ? 'Tepercaya' : 'Biasa' }}</span>
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
        @endif
    </div>

    <div class="col-12 col-xl-4">
        <div class="card soft-card mb-4">
            <div class="card-header"><strong>Ringkasan</strong></div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2"><span>Status profil</span><strong>{{ $security['profile'] }}</strong></div>
                <div class="d-flex justify-content-between mb-2"><span>Email</span><strong>{{ $security['email'] }}</strong></div>
                <div class="d-flex justify-content-between mb-2"><span>2FA</span><strong>{{ $security['two_factor'] }}</strong></div>
                <div class="d-flex justify-content-between mb-2"><span>Perangkat</span><strong>{{ $security['trusted_devices'] }} tepercaya</strong></div>
                <div class="d-flex justify-content-between"><span>Gender</span><strong>{{ $genderLabel }}</strong></div>
            </div>
        </div>

        <div class="card soft-card mb-4">
            <div class="card-header"><strong>Foto Profil</strong></div>
            <div class="card-body text-center">
                <img src="{{ $user->avatar_url }}" alt="Avatar" class="rounded-circle mb-3" style="width:120px;height:120px;object-fit:cover;">
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
            <div class="card-header"><strong>Identitas</strong></div>
            <div class="card-body">
                <div class="mb-2"><small class="text-muted d-block">Nama Lengkap</small><strong>{{ $user->name }}</strong></div>
                <div class="mb-2"><small class="text-muted d-block">Username</small><strong>{{ $user->username }}</strong></div>
                <div class="mb-2"><small class="text-muted d-block">Email</small><strong>{{ $user->email ?: '-' }}</strong></div>
                <div class="mb-2"><small class="text-muted d-block">No. HP</small><strong>{{ $user->phone ?: '-' }}</strong></div>
                <div class="mb-2"><small class="text-muted d-block">Tanggal Lahir</small><strong>{{ optional($user->birth_date)->format('d M Y') ?: '-' }}</strong></div>
                <div><small class="text-muted d-block">Alamat</small><strong>{{ $user->address ?: '-' }}</strong></div>
            </div>
        </div>
    </div>
</div>
@endsection
