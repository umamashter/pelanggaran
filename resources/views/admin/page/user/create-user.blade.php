@extends('layouts.main')
@section('title', 'Tambah User')
@include('component.admin.absensi-module')

<style>
    /* ============================================================
       TAMBAH USER — Create User Workspace
       Built on the shared ABSENSI design system (.abs-mod / .abm-*)
       ============================================================ */
    .cu-mod { margin-top: 22px; }
    .cu-breadcrumb { display: flex; align-items: center; gap: 8px; font-size: 12.5px; color: var(--ab-text-3); font-weight: 600; margin-bottom: 14px; }
    .cu-breadcrumb a { color: var(--ab-text-3); text-decoration: none; transition: color .2s; }
    .cu-breadcrumb a:hover { color: var(--ab-primary); }
    .cu-breadcrumb i { font-size: 10px; }
    .cu-breadcrumb .active { color: var(--ab-text-2); }

    .cu-workspace { display: grid; grid-template-columns: minmax(0, 1fr) 340px; gap: 20px; align-items: start; }
    .cu-card {
        background: var(--ab-card); border: 1px solid var(--ab-border);
        border-radius: 18px; box-shadow: var(--ab-shadow); overflow: hidden;
    }
    .cu-card-head {
        display: flex; align-items: center; gap: 14px;
        padding: 20px 24px; border-bottom: 1px solid var(--ab-border-soft);
    }
    .cu-card-icon {
        width: 48px; height: 48px; border-radius: 14px; flex-shrink: 0; color: #fff;
        background: var(--ab-grad); display: flex; align-items: center; justify-content: center;
        font-size: 20px; box-shadow: 0 8px 18px -6px rgba(37,99,235,.45);
    }
    .cu-card-title { font-size: 16px; font-weight: 800; color: var(--ab-text); margin: 0; }
    .cu-card-sub { font-size: 12px; color: var(--ab-text-3); margin-top: 3px; }
    .cu-card-body { padding: 24px; }

    .cu-form-grid { display: grid; gap: 18px; }
    .cu-float { position: relative; }
    .cu-float > label {
        position: absolute; left: 15px; top: 50%; transform: translateY(-50%);
        font-size: 13px; color: var(--ab-text-3); font-weight: 500; pointer-events: none;
        transition: all .2s cubic-bezier(.4,0,.2,1); background: transparent; z-index: 1;
    }
    .cu-float input, .cu-float select {
        width: 100%; border: 1.5px solid var(--ab-border); background: var(--ab-card);
        border-radius: 12px; padding: 22px 14px 8px; font-size: 13.5px; color: var(--ab-text);
        transition: border-color .2s, box-shadow .2s; line-height: 1.5; height: 54px;
    }
    .cu-float select { padding: 8px 14px; }
    .cu-float input::placeholder { color: transparent; }
    .cu-float input:focus, .cu-float select:focus { outline: none; border-color: var(--ab-primary); box-shadow: 0 0 0 3px var(--ab-primary-soft); }
    .cu-float input:focus ~ label, .cu-float input:not(:placeholder-shown) ~ label,
    .cu-float select ~ label {
        top: 8px; transform: translateY(0); font-size: 10px; font-weight: 700;
        text-transform: uppercase; letter-spacing: .5px; color: var(--ab-primary);
    }
    .cu-float.is-error input { border-color: var(--ab-red); box-shadow: 0 0 0 3px var(--ab-red-soft); }
    .cu-float.is-error select { border-color: var(--ab-red); box-shadow: 0 0 0 3px var(--ab-red-soft); }
    .cu-feedback { display: none; margin-top: 6px; font-size: 12px; font-weight: 600; color: var(--ab-red); align-items: center; gap: 6px; }
    .cu-feedback.is-on { display: flex; }
    .cu-shake { animation: cuShake .4s ease; }
    @keyframes cuShake { 0%,100% { transform: translateX(0); } 20% { transform: translateX(-6px); } 40% { transform: translateX(6px); } 60% { transform: translateX(-4px); } 80% { transform: translateX(4px); } }

    .cu-hintbox {
        display: flex; align-items: flex-start; gap: 12px;
        background: var(--ab-primary-soft); border: 1px solid var(--ab-primary-border);
        border-radius: 12px; padding: 11px 14px; font-size: 12px; color: var(--ab-text-2); line-height: 1.6;
    }
    .cu-hintbox i { color: var(--ab-primary); font-size: 15px; flex-shrink: 0; margin-top: 1px; }

    /* ---------- Preview card ---------- */
    .cu-preview {
        border-radius: 16px; border: 1.5px solid var(--ab-primary-border);
        background: var(--ab-primary-soft); padding: 18px; animation: cuPop .3s ease;
    }
    @keyframes cuPop { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
    .cu-preview-label { font-size: 11px; font-weight: 800; color: var(--ab-primary); text-transform: uppercase; letter-spacing: .5px; margin-bottom: 12px; display: flex; align-items: center; gap: 7px; }
    .cu-preview-head { display: flex; align-items: center; gap: 12px; margin-bottom: 14px; }
    .cu-preview-avatar {
        width: 48px; height: 48px; border-radius: 14px; flex-shrink: 0; color: #fff;
        display: flex; align-items: center; justify-content: center; font-size: 15px; font-weight: 800;
        background: linear-gradient(135deg, #2563eb, #60a5fa); box-shadow: 0 4px 10px -2px rgba(37,99,235,.4);
    }
    .cu-preview-name { font-size: 14px; font-weight: 800; color: var(--ab-text); line-height: 1.3; word-break: break-word; }
    .cu-preview-sub { font-size: 11px; color: var(--ab-text-3); margin-top: 2px; }
    .cu-preview-row { display: flex; justify-content: space-between; align-items: center; gap: 10px; padding: 9px 0; border-bottom: 1px dashed var(--ab-primary-border); font-size: 12.5px; }
    .cu-preview-row:last-child { border-bottom: none; }
    .cu-preview-row .k { color: var(--ab-text-2); font-weight: 600; }
    .cu-preview-row .v { color: var(--ab-text); font-weight: 800; text-align: right; word-break: break-word; }
    .cu-preview-row .v.mono { font-family: 'Fira Code', monospace; font-size: 11.5px; }
    .cu-preview-badge { display: inline-flex; align-items: center; gap: 6px; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; background: var(--ab-amber-soft); color: var(--ab-amber); border: 1px solid var(--ab-amber-border); }

    .cu-actions {
        display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;
        margin-top: 22px; padding-top: 18px; border-top: 1px dashed var(--ab-border);
    }

    .cu-btn-loading { pointer-events: none; opacity: .85; }
    .cu-btn-loading .cu-spin { display: inline-block; width: 14px; height: 14px; border: 2px solid rgba(255,255,255,.4); border-top-color: #fff; border-radius: 50%; animation: cuSpin .6s linear infinite; }
    .cu-btn-loading i { display: none; }
    @keyframes cuSpin { to { transform: rotate(360deg); } }

    @media (max-width: 991.98px) {
        .cu-workspace { grid-template-columns: 1fr; }
    }
    @media (max-width: 575.98px) {
        .cu-card-body { padding: 18px 16px; }
        .cu-card-head { padding: 16px 18px; }
        .cu-actions { flex-direction: column-reverse; }
        .cu-actions .abm-btn { width: 100%; }
    }
    @media (prefers-reduced-motion: reduce) {
        .cu-mod *, .cu-mod *::before, .cu-mod *::after { animation: none !important; transition: none !important; }
    }
</style>

@section('content')
<div class="abs-mod cu-mod create-user-page">

    {{-- ===== BREADCRUMB ===== --}}
    <div class="cu-breadcrumb">
        <a href="/home"><i class="fas fa-home me-1"></i>Dashboard</a>
        <i class="fas fa-chevron-right"></i>
        <a href="{{ url('/master-user') }}">Master User</a>
        <i class="fas fa-chevron-right"></i>
        <span class="active">Tambah User</span>
    </div>

    {{-- ===== COMPACT HERO ===== --}}
    <div class="abm-hero" style="padding:20px 24px 18px;margin-bottom:18px;">
        <div class="abm-hero-grid"></div>
        <div class="abm-hero-row">
            <div class="abm-hero-left">
                <div class="d-flex align-items-center gap-3">
                    <div class="abm-hero-icon"><i class="fas fa-user-plus"></i></div>
                    <div>
                        <div class="abm-chip abm-chip--blue mb-2"><i class="fas fa-address-book"></i> Workspace Baru</div>
                        <h3>Tambah User Baru</h3>
                        <p class="abm-hero-sub">Buat akun pengguna baru. Password awal otomatis <b>password</b> dan dapat diubah kapan saja.</p>
                    </div>
                </div>
            </div>
            <div class="abm-hero-right">
                <div class="abm-hero-actions">
                    <a href="{{ url('/master-user') }}" class="abm-btn abm-btn--ghost"><i class="fas fa-arrow-left"></i> Kembali ke Master User</a>
                </div>
            </div>
        </div>
    </div>

    <div class="cu-workspace">

        {{-- ===== FORM CARD ===== --}}
        <div class="cu-card">
            <div class="cu-card-head">
                <div class="cu-card-icon"><i class="fas fa-user-plus"></i></div>
                <div>
                    <h4 class="cu-card-title">Lengkapi Data Pengguna</h4>
                    <div class="cu-card-sub">Field bertanda <span class="text-danger">*</span> wajib diisi.</div>
                </div>
            </div>
            <div class="cu-card-body">
                @if(session('success'))
                    <div class="abm-alert" style="background:var(--ab-green-soft);color:#15803d;border:1px solid var(--ab-green-border);margin-bottom:18px;">
                        <i class="fas fa-check-circle"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="abm-alert abm-alert--danger">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div>
                            <strong>Terdapat kesalahan pada form.</strong>
                            <ul style="margin:6px 0 0;padding-left:18px;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ route('user.store') }}" method="POST" id="cuForm" novalidate>
                    @csrf
                    <div class="cu-form-grid">
                        <div class="cu-float" id="cuNameWrap">
                            <input type="text" name="name" id="cuName" value="{{ old('name') }}" placeholder=" " autocomplete="off" maxlength="255">
                            <label for="cuName">Nama Lengkap *</label>
                            <div class="cu-feedback" id="cuNameFb"><i class="fas fa-exclamation-circle"></i><span></span></div>
                        </div>

                        <div class="cu-float" id="cuEmailWrap">
                            <input type="text" name="email" id="cuEmail" value="{{ old('email') }}" placeholder=" " autocomplete="off" maxlength="255">
                            <label for="cuEmail">Alamat Email *</label>
                            <div class="cu-feedback" id="cuEmailFb"><i class="fas fa-exclamation-circle"></i><span></span></div>
                        </div>

                        <div class="cu-float" id="cuRoleWrap">
                            <select name="role" id="cuRole" required>
                                <option value="" selected disabled>Pilih Role</option>
                                <option value="1" {{ old('role') == '1' ? 'selected' : '' }}>Admin</option>
                                <option value="2" {{ old('role') == '2' ? 'selected' : '' }}>Guru</option>
                                <option value="3" {{ old('role') == '3' ? 'selected' : '' }}>Siswa</option>
                                <option value="5" {{ old('role') == '5' ? 'selected' : '' }}>Kepala Sekolah</option>
                            </select>
                            <label for="cuRole">Peran / Role *</label>
                            <div class="cu-feedback" id="cuRoleFb"><i class="fas fa-exclamation-circle"></i><span></span></div>
                        </div>

                        <div class="cu-float" id="cuNisnWrap" style="display:none;">
                            <input type="text" name="nisn" id="cuNisn" value="{{ old('nisn') }}" placeholder=" " autocomplete="off" maxlength="10" inputmode="numeric">
                            <label for="cuNisn">NISN (10 digit)</label>
                            <div class="cu-feedback" id="cuNisnFb"><i class="fas fa-exclamation-circle"></i><span></span></div>
                        </div>
                    </div>

                    <div class="cu-actions">
                        <a href="{{ url('/master-user') }}" class="abm-btn abm-btn--outline"><i class="fas fa-arrow-left"></i> Kembali</a>
                        <button type="submit" class="abm-btn abm-btn--solid us-ripple cu-ripple" id="cuBtnSubmit"><i class="fas fa-save"></i> Simpan User</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ===== PREVIEW SIDE ===== --}}
        <div style="display:grid;gap:16px;">
            <div class="cu-preview">
                <div class="cu-preview-label"><i class="fas fa-id-card"></i> Preview Akun</div>
                <div class="cu-preview-head">
                    <span class="cu-preview-avatar" id="cuPreviewAvatar">U</span>
                    <div style="min-width:0;">
                        <div class="cu-preview-name" id="cuPreviewName">Nama lengkap</div>
                        <div class="cu-preview-sub"><i class="fas fa-at"></i> <span id="cuPreviewUsername">username</span></div>
                    </div>
                </div>
                <div class="cu-preview-row"><span class="k">Password Awal</span><span class="v mono" id="cuPreviewPassword">password</span></div>
                <div class="cu-preview-row"><span class="k">Role</span><span class="v" id="cuPreviewRole">—</span></div>
                <div class="cu-preview-row"><span class="k">Status</span><span class="v"><span class="cu-preview-badge"><i class="fas fa-user-clock"></i> Belum Terdaftar</span></span></div>
            </div>
            <div class="cu-hintbox">
                <i class="fas fa-info-circle"></i>
                <div>
                    Password awal user adalah <b>password</b>. Username dibuat otomatis dari email. Setelah akun dibuat, atur ulang password lewat menu <b>Ubah Password</b> di halaman Master User.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const ROLE_LABEL = { '1': 'Admin', '2': 'Guru', '3': 'Siswa', '5': 'Kepala Sekolah' };

    function initials(name) {
        const parts = (name || 'User').trim().split(/\s+/).filter(Boolean);
        return ((parts[0]?.[0] || 'U') + (parts[1]?.[0] || '')).toUpperCase();
    }

    /* ================= preview ================= */
    function updatePreview() {
        const name = document.getElementById('cuName').value.trim();
        const email = document.getElementById('cuEmail').value.trim();
        const role = document.getElementById('cuRole').value;
        document.getElementById('cuPreviewName').textContent = name || 'Nama lengkap';
        document.getElementById('cuPreviewAvatar').textContent = initials(name);
        document.getElementById('cuPreviewUsername').textContent = email ? email.split('@')[0] : 'username';
        document.getElementById('cuPreviewRole').textContent = role ? ROLE_LABEL[role] : '—';
    }
    ['cuName', 'cuEmail', 'cuRole'].forEach(function(id) {
        document.getElementById(id).addEventListener('input', updatePreview);
        document.getElementById(id).addEventListener('change', updatePreview);
    });

    /* ================= NISN toggle ================= */
    const roleSelect = document.getElementById('cuRole');
    function toggleNisn() {
        document.getElementById('cuNisnWrap').style.display = (roleSelect.value == '3') ? '' : 'none';
        if (roleSelect.value == '3') {
            setTimeout(function() { document.getElementById('cuNisn').focus(); }, 60);
        }
    }
    roleSelect.addEventListener('change', function() { toggleNisn(); updatePreview(); });
    toggleNisn();

    /* ================= inline validation ================= */
    function setError(id, msg) {
        const fb = document.getElementById(id + 'Fb');
        const wrap = document.getElementById(id + 'Wrap');
        if (msg) {
            fb.querySelector('span').textContent = msg;
            fb.classList.add('is-on');
            wrap.classList.add('is-error');
        } else {
            fb.classList.remove('is-on');
            wrap.classList.remove('is-error');
        }
    }
    const NAME_RE = /^[a-zA-Z\s\.,;\'\-]+$/;
    const EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    function validateField(id) {
        const el = document.getElementById(id);
        if (id === 'cuName') {
            const v = el.value.trim();
            if (!v) setError(id, 'Nama lengkap wajib diisi.');
            else if (v.length > 255) setError(id, 'Nama maksimal 255 karakter.');
            else if (!NAME_RE.test(v)) setError(id, 'Nama hanya boleh berisi huruf, spasi, dan tanda baca.');
            else setError(id, '');
        } else if (id === 'cuEmail') {
            const v = el.value.trim();
            if (!v) setError(id, 'Alamat email wajib diisi.');
            else if (!EMAIL_RE.test(v)) setError(id, 'Format email tidak valid.');
            else setError(id, '');
        } else if (id === 'cuNisn') {
            const v = el.value.trim();
            if (!v) setError(id, 'NISN wajib diisi untuk siswa.');
            else if (!/^\d{10}$/.test(v)) setError(id, 'NISN harus tepat 10 digit angka.');
            else setError(id, '');
        } else if (id === 'cuRole') {
            setError(id, el.value ? '' : 'Role wajib dipilih.');
        }
    }

    ['cuName', 'cuEmail', 'cuNisn'].forEach(function(id) {
        const el = document.getElementById(id);
        el.addEventListener('blur', function() { validateField(id); });
        el.addEventListener('input', function() {
            if (document.getElementById(id + 'Fb').classList.contains('is-on')) validateField(id);
        });
    });

    /* ================= submit ================= */
    document.getElementById('cuForm').addEventListener('submit', function(e) {
        validateField('cuName');
        validateField('cuEmail');
        validateField('cuRole');
        if (roleSelect.value == '3') validateField('cuNisn');
        const anyErr = document.querySelectorAll('#cuForm .cu-feedback.is-on').length > 0;
        if (anyErr) {
            e.preventDefault();
            const first = document.querySelector('#cuForm .cu-float.is-error');
            if (first) {
                first.classList.add('cu-shake');
                setTimeout(function() { first.classList.remove('cu-shake'); }, 450);
                first.querySelector('input, select').focus();
            }
            return;
        }
        const btn = document.getElementById('cuBtnSubmit');
        btn.classList.add('cu-btn-loading');
        btn.innerHTML = '<span class="cu-spin"></span> Menyimpan...';
    });
});
</script>
@endpush
