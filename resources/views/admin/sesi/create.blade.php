@extends('layouts.main')
@section('title', 'Tambah Nama Sesi')

@push('css')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    :root {
        --sesi-surface: #ffffff;
        --sesi-surface-alt: #f8fafc;
        --sesi-border: #e8ecf1;
        --sesi-text: #0f172a;
        --sesi-muted: #64748b;
        --sesi-green: #16a34a;
        --sesi-green-bg: #ecfdf5;
        --sesi-radius: 14px;
        --sesi-radius-lg: 22px;
        --sesi-shadow: 0 4px 16px rgba(15,23,42,.06);
        --sesi-shadow-lg: 0 18px 50px rgba(15,23,42,.08);
        --sesi-transition: 200ms cubic-bezier(.4,0,.2,1);
        --sesi-font: 'Plus Jakarta Sans', 'Inter', system-ui, sans-serif;
    }

    .dark-mode {
        --sesi-surface: #1e293b;
        --sesi-surface-alt: #0f172a;
        --sesi-border: #334155;
        --sesi-text: #f1f5f9;
        --sesi-muted: #94a3b8;
        --sesi-green-bg: #052e16;
        --sesi-shadow: 0 4px 16px rgba(0,0,0,.3);
        --sesi-shadow-lg: 0 18px 50px rgba(0,0,0,.4);
    }

    .sesi-form-wrapper {
        font-family: var(--sesi-font);
        max-width: 1040px;
        margin: 18px auto 0;
        padding: 0 16px 32px;
        color: var(--sesi-text);
    }
    .sesi-form-wrapper * { font-family: inherit; }

    .sesi-breadcrumb {
        margin-bottom: 16px;
    }
    .sesi-breadcrumb ol {
        background: transparent;
        padding: 0;
        margin: 0;
    }
    .sesi-breadcrumb .breadcrumb-item { font-size: 13px; font-weight: 500; }
    .sesi-breadcrumb .breadcrumb-item a { color: var(--sesi-muted); text-decoration: none; transition: color var(--sesi-transition); }
    .sesi-breadcrumb .breadcrumb-item a:hover { color: var(--sesi-green); }
    .sesi-breadcrumb .breadcrumb-item.active { color: var(--sesi-text); font-weight: 700; }

    .sesi-form-shell {
        border: 1px solid var(--sesi-border);
        border-radius: var(--sesi-radius-lg);
        background: var(--sesi-surface);
        box-shadow: var(--sesi-shadow-lg);
        overflow: hidden;
    }

    .sesi-form-hero {
        padding: 24px 28px;
        border-bottom: 1px solid var(--sesi-border);
        background: linear-gradient(135deg, rgba(236,253,245,.85), rgba(248,250,252,.95));
    }
    .dark-mode .sesi-form-hero { background: linear-gradient(135deg, rgba(5,46,22,.5), rgba(15,23,42,.95)); }

    .sesi-form-hero .eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 5px 14px;
        border-radius: 999px;
        background: var(--sesi-green-bg);
        color: var(--sesi-green);
        font-size: 12px;
        font-weight: 700;
    }
    .sesi-form-hero h1 {
        font-size: clamp(24px, 2.8vw, 34px);
        font-weight: 800;
        letter-spacing: -.03em;
        color: var(--sesi-text);
        margin: 10px 0 6px;
        line-height: 1.15;
    }
    .sesi-form-hero .subtitle {
        color: var(--sesi-muted);
        font-size: 14px;
        line-height: 1.6;
        margin: 0;
    }
    .sesi-meta-chip {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        min-height: 34px;
        padding: 0 13px;
        border-radius: 999px;
        border: 1px solid var(--sesi-border);
        background: var(--sesi-surface);
        color: var(--sesi-muted);
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }

    .sesi-form-body {
        padding: 24px 28px;
    }

    .sesi-field-label {
        display: block;
        margin-bottom: 7px;
        font-size: 13px;
        font-weight: 700;
        color: var(--sesi-text);
    }
    .sesi-field-label.required::after {
        content: ' *';
        color: #ef4444;
    }

    .sesi-field-wrap {
        position: relative;
    }
    .sesi-field-wrap input {
        min-height: 46px;
        border-radius: 13px;
        border: 1.5px solid var(--sesi-border);
        background: var(--sesi-surface);
        color: var(--sesi-text);
        padding-left: 42px;
        font-size: 14px;
        width: 100%;
        transition: all var(--sesi-transition);
        box-shadow: none;
    }
    .sesi-field-wrap input:focus {
        border-color: rgba(22,163,74,.5);
        box-shadow: 0 0 0 4px rgba(22,163,74,.1);
        outline: none;
    }
    .sesi-field-wrap input.is-invalid {
        border-color: #ef4444;
        background-image: none;
        box-shadow: 0 0 0 4px rgba(239,68,68,.08);
    }
    .sesi-field-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 15px;
        pointer-events: none;
        z-index: 2;
    }
    .sesi-help-text {
        margin-top: 6px;
        font-size: 12px;
        color: var(--sesi-muted);
        line-height: 1.5;
    }
    .sesi-inline-error {
        margin-top: 6px;
        font-size: 12px;
        font-weight: 600;
        color: #dc2626;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .sesi-form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid var(--sesi-border);
    }

    .btn-sesi {
        min-height: 44px;
        border-radius: 999px;
        padding: 0 20px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 700;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all var(--sesi-transition);
        text-decoration: none;
        white-space: nowrap;
    }
    .btn-sesi-primary {
        background: linear-gradient(135deg, #16a34a, #22c55e);
        color: #fff;
        box-shadow: 0 8px 20px rgba(22,163,74,.22);
    }
    .btn-sesi-primary:hover { transform: translateY(-1px); box-shadow: 0 14px 28px rgba(22,163,74,.3); color: #fff; }
    .btn-sesi-secondary {
        background: var(--sesi-surface);
        color: var(--sesi-text);
        border-color: var(--sesi-border);
    }
    .btn-sesi-secondary:hover { background: var(--sesi-surface-alt); transform: translateY(-1px); box-shadow: var(--sesi-shadow); }

    .sesi-error-banner {
        border: none;
        border-radius: var(--sesi-radius);
        background: #fef2f2;
        color: #991b1b;
        border-left: 4px solid #ef4444;
        box-shadow: 0 8px 20px rgba(239,68,68,.06);
        padding: 14px 18px;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 20px;
    }
    .sesi-error-banner ul { margin: 8px 0 0; padding-left: 18px; }

    .sesi-panel {
        border-radius: var(--sesi-radius);
        border: 1px solid var(--sesi-border);
        background: var(--sesi-surface);
        box-shadow: var(--sesi-shadow);
    }
    .sesi-panel-head {
        padding: 16px 18px 12px;
        border-bottom: 1px solid var(--sesi-border);
    }
    .sesi-panel-body { padding: 18px; }

    .sesi-preview-item {
        padding: 11px 14px;
        border-radius: 12px;
        background: var(--sesi-surface-alt);
        border: 1px solid var(--sesi-border);
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
    }
    .sesi-preview-item:last-child { margin-bottom: 0; }
    .sesi-preview-item .k { font-size: 12px; color: var(--sesi-muted); font-weight: 600; }
    .sesi-preview-item .v { font-size: 13px; font-weight: 700; color: var(--sesi-text); }

    .sesi-timeline-bar {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 14px;
    }
    .sesi-timeline-bar .tl-line {
        flex: 1;
        height: 8px;
        border-radius: 999px;
        background: linear-gradient(90deg, #dbeafe, #bbf7d0);
        position: relative;
        overflow: hidden;
    }
    .sesi-timeline-bar .tl-line::before {
        content: '';
        position: absolute;
        inset: 0;
        width: 55%;
        background: linear-gradient(90deg, #16a34a, #22c55e);
        border-radius: inherit;
    }
    .sesi-badge-empty { display: inline-flex; align-items: center; gap: 6px; min-height: 30px; padding: 0 11px; border-radius: 999px; background: var(--sesi-surface-alt); color: var(--sesi-muted); font-size: 12px; font-weight: 600; }
    .sesi-badge-green { display: inline-flex; align-items: center; gap: 6px; min-height: 30px; padding: 0 11px; border-radius: 999px; background: var(--sesi-green-bg); color: #15803d; font-size: 12px; font-weight: 700; }

    @media (max-width: 991.98px) { .sesi-form-wrapper { padding-inline: 12px; } }
    @media (max-width: 767.98px) {
        .sesi-form-hero, .sesi-form-body { padding: 16px; }
        .sesi-form-actions { flex-direction: column-reverse; }
        .sesi-form-actions .btn-sesi { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')
@include('component.admin.ms-style')

<div class="sesi-form-wrapper">
    <nav aria-label="breadcrumb" class="sesi-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home"><i class="bi bi-house-door me-1"></i>Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('sesi.index') }}">Daftar Sesi</a></li>
            <li class="breadcrumb-item active">Tambah Nama Sesi</li>
        </ol>
    </nav>

    <div class="sesi-form-shell">
        <div class="sesi-form-hero row g-3 align-items-center">
            <div class="col-lg-8">
                <span class="eyebrow"><i class="bi bi-plus-circle-fill"></i> Quick Create</span>
                <h1>Tambah Nama Sesi</h1>
                <p class="subtitle">Sesi baru akan otomatis terkait dengan Haflah aktif dan siap dipilih di modul Sesi Lomba.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <span class="sesi-meta-chip"><i class="bi bi-shield-check"></i> Validasi backend tetap aktif</span>
            </div>
        </div>

        <div class="sesi-form-body">
            @if ($errors->any())
                <div class="sesi-error-banner">
                    <strong class="d-block"><i class="bi bi-exclamation-triangle-fill me-1"></i>Terdapat kesalahan pada form</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row g-4">
                <div class="col-xl-7">
                    <form action="{{ route('sesi.store') }}" method="POST" id="sesiCreateForm" novalidate>
                        @csrf

                        <div class="mb-4">
                            <label class="sesi-field-label required" for="nama">Nama Sesi</label>
                            <div class="sesi-field-wrap">
                                <i class="bi bi-tag-fill sesi-field-icon"></i>
                                <input type="text" id="nama" name="nama"
                                       class="@error('nama') is-invalid @enderror"
                                       value="{{ old('nama') }}"
                                       placeholder="Contoh: Sesi Pagi Hari 1"
                                       maxlength="255"
                                       data-preview="nama">
                            </div>
                            <div class="sesi-help-text">Nama pendek dan jelas — digunakan sebagai identitas di seluruh modul lomba.</div>
                            @error('nama')<div class="sesi-inline-error"><i class="bi bi-exclamation-circle-fill"></i>{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-4">
                            <label class="sesi-field-label" for="tanggal">Tanggal</label>
                            <div class="sesi-field-wrap">
                                <i class="bi bi-calendar2-week-fill sesi-field-icon"></i>
                                <input type="date" id="tanggal" name="tanggal"
                                       class="@error('tanggal') is-invalid @enderror"
                                       value="{{ old('tanggal') }}"
                                       data-preview="tanggal">
                            </div>
                            <div class="sesi-help-text">Opsional — tapi sangat direkomendasikan untuk timeline distribusi sesi.</div>
                            @error('tanggal')<div class="sesi-inline-error"><i class="bi bi-exclamation-circle-fill"></i>{{ $message }}</div>@enderror
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="sesi-field-label" for="jam_mulai">Jam Mulai</label>
                                <div class="sesi-field-wrap">
                                    <i class="bi bi-play-fill sesi-field-icon"></i>
                                    <input type="time" id="jam_mulai" name="jam_mulai"
                                           class="@error('jam_mulai') is-invalid @enderror"
                                           value="{{ old('jam_mulai') }}"
                                           data-preview="jam_mulai">
                                </div>
                                @error('jam_mulai')<div class="sesi-inline-error"><i class="bi bi-exclamation-circle-fill"></i>{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="sesi-field-label" for="jam_selesai">Jam Selesai</label>
                                <div class="sesi-field-wrap">
                                    <i class="bi bi-stop-fill sesi-field-icon"></i>
                                    <input type="time" id="jam_selesai" name="jam_selesai"
                                           class="@error('jam_selesai') is-invalid @enderror"
                                           value="{{ old('jam_selesai') }}"
                                           data-preview="jam_selesai">
                                </div>
                                @error('jam_selesai')<div class="sesi-inline-error"><i class="bi bi-exclamation-circle-fill"></i>{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="sesi-form-actions">
                            <a href="{{ route('sesi.index') }}" class="btn-sesi btn-sesi-secondary"><i class="bi bi-arrow-left"></i> Kembali ke Daftar</a>
                            <button type="submit" class="btn-sesi btn-sesi-primary" data-submit-button>
                                <span class="btn-label"><i class="bi bi-save2-fill"></i> Simpan Sesi</span>
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                            </button>
                        </div>
                    </form>
                </div>

                <div class="col-xl-5">
                    <div class="sesi-panel mb-4">
                        <div class="sesi-panel-head d-flex align-items-center justify-content-between gap-3">
                            <div>
                                <div class="fw-bold" style="color:var(--sesi-text);font-size:14px;">Live Preview</div>
                                <div class="text-muted small" style="font-size:12px;">Kartu sesi yang terlihat di dashboard.</div>
                            </div>
                            <span class="sesi-badge-green" id="previewStatusBadge"><i class="bi bi-check2-circle"></i>Dapat Diedit</span>
                        </div>
                        <div class="sesi-panel-body">
                            <div class="sesi-preview-item"><span class="k">Nama</span><span class="v" data-preview-out="nama">-</span></div>
                            <div class="sesi-preview-item"><span class="k">Tanggal</span><span class="v" data-preview-out="tanggal">Belum Ditentukan</span></div>
                            <div class="sesi-preview-item"><span class="k">Jam</span><span class="v" data-preview-out="jam">Fleksibel</span></div>
                            <div class="sesi-timeline-bar">
                                <span class="sesi-badge-empty"><i class="bi bi-clock-history"></i>Pagi</span>
                                <div class="tl-line"></div>
                                <span class="sesi-badge-empty"><i class="bi bi-brightness-high"></i>Siang</span>
                            </div>
                        </div>
                    </div>

                    <div class="sesi-panel">
                        <div class="sesi-panel-head">
                            <div class="fw-bold" style="color:var(--sesi-text);font-size:14px;"><i class="bi bi-lightbulb me-2" style="color:#f59e0b;"></i>Tips Operasional</div>
                        </div>
                        <div class="sesi-panel-body" style="font-size:13px;">
                            <ul class="mb-0 ps-3 lh-lg" style="color:var(--sesi-muted);">
                                <li>Gunakan nama sesi yang konsisten dengan pola haflah (misal: <strong>Sesi Pagi 1</strong>).</li>
                                <li>Tanggal dan jam boleh dikosongkan — sesi tetap bisa dipilih di Sesi Lomba.</li>
                                <li>Sesi yang sudah dipakai lomba akan <strong>terkunci</strong> dan tidak bisa diedit.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var form = document.getElementById('sesiCreateForm');
    var submitBtn = document.querySelector('[data-submit-button]');
    var namaEl = document.getElementById('nama');
    var tglEl = document.getElementById('tanggal');
    var jamMulaiEl = document.getElementById('jam_mulai');
    var jamSelesaiEl = document.getElementById('jam_selesai');

    var previewOut = {
        nama: document.querySelector('[data-preview-out="nama"]'),
        tanggal: document.querySelector('[data-preview-out="tanggal"]'),
        jam: document.querySelector('[data-preview-out="jam"]')
    };

    function fmtTanggal(v) {
        if (!v) return 'Belum Ditentukan';
        try { return new Intl.DateTimeFormat('id-ID', {day:'numeric',month:'short',year:'numeric'}).format(new Date(v+'T00:00:00')); }
        catch(e) { return v; }
    }
    function fmtJam() {
        var s = (jamMulaiEl?.value || '');
        var e = (jamSelesaiEl?.value || '');
        if (!s && !e) return 'Fleksibel';
        return (s || '??') + ' - ' + (e || '??');
    }
    function syncPreview() {
        if (previewOut.nama) previewOut.nama.textContent = (namaEl?.value?.trim() || '-');
        if (previewOut.tanggal) previewOut.tanggal.textContent = fmtTanggal(tglEl?.value || '');
        if (previewOut.jam) previewOut.jam.textContent = fmtJam();
    }

    [namaEl, tglEl, jamMulaiEl, jamSelesaiEl].forEach(function(el) {
        if (el) { el.addEventListener('input', syncPreview); el.addEventListener('change', syncPreview); }
    });
    syncPreview();

    if (form && submitBtn) {
        form.addEventListener('submit', function () {
            submitBtn.disabled = true;
            submitBtn.querySelector('.btn-label')?.classList.add('d-none');
            var spinner = submitBtn.querySelector('.spinner-border');
            if (spinner) spinner.classList.remove('d-none');
        });
    }
})();
</script>
@endpush
@endsection
