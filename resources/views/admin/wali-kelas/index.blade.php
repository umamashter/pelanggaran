@extends('layouts.main')

@section('title', 'Wali Kelas')

@section('content')
@include('component.admin.kelas-module')

@php
    $todayLabel = now()->translatedFormat('l, d F Y');
    $totalWali = $waliKelas->total();
    $totalKelas = $totalWali + $kelas->count();
    $coverage = $totalKelas > 0 ? (int) round(($totalWali / $totalKelas) * 100) : 0;
    $avatarClasses = ['blue', 'green', 'amber', 'violet', 'red', 'info'];
    $avatarCount = count($avatarClasses);
@endphp

<div class="kls-page">

    {{-- ===================== BREADCRUMB ===================== --}}
    <nav class="kls-crumb" aria-label="breadcrumb">
        <a href="{{ route('kelas.index') }}">Kelas</a>
        <i class="bi bi-chevron-right"></i>
        <span>Wali Kelas</span>
    </nav>

    {{-- ===================== HERO ===================== --}}
    <header class="kls-hero">
        <div class="kls-hero-main">
            <div class="kls-eyebrow"><i class="bi bi-person-badge-fill"></i> Class Advisor Management</div>
            <h1 class="kls-hero-title">Penugasan Wali Kelas</h1>
            <p class="kls-hero-desc">
                Kelola penugasan guru sebagai wali kelas agar setiap kelas memiliki pendamping akademik
                yang jelas untuk tahun ajaran berjalan.
            </p>
            <div class="kls-hero-chips">
                <span class="kls-chip"><i class="bi bi-calendar3"></i> {{ $todayLabel }}</span>
                <span class="kls-chip kls-chip--green"><i class="bi bi-person-check-fill"></i> {{ $totalWali }} penugasan aktif</span>
                <span class="kls-chip kls-chip--violet"><i class="bi bi-easel-fill"></i> {{ $totalKelas }} total kelas</span>
            </div>
            <div class="kls-hero-stats">
                <div class="kls-hero-stat">
                    <div class="k">Penugasan</div>
                    <div class="v"><span data-counter="{{ $totalWali }}">0</span></div>
                    <div class="s">Wali kelas aktif</div>
                </div>
                <div class="kls-hero-stat">
                    <div class="k">Guru Wali</div>
                    <div class="v"><span data-counter="{{ $totalWali }}">0</span></div>
                    <div class="s">Guru sudah ditugaskan</div>
                </div>
                <div class="kls-hero-stat">
                    <div class="k">Coverage</div>
                    <div class="v"><span data-counter="{{ $coverage }}">0</span><small style="font-size:13px;color:var(--kls-text-3)">%</small></div>
                    <div class="s">Kelas ber-wali vs total</div>
                </div>
            </div>
        </div>

        <aside class="kls-hero-aside">
            <div class="kls-hero-panel">
                <h4>Progress Coverage</h4>
                <p>Persentase kelas yang sudah memiliki wali kelas terhadap total kelas terdaftar.</p>
                <div class="kls-mini-grid">
                    <div class="kls-mini-stat">
                        <div class="k">Ber-wali</div>
                        <div class="v">{{ $totalWali }}</div>
                    </div>
                    <div class="kls-mini-stat">
                        <div class="k">Belum</div>
                        <div class="v">{{ $kelas->count() }}</div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="kls-capacity-top mb-1">
                        <span>Coverage</span>
                        <b>{{ $coverage }}%</b>
                    </div>
                    <div class="kls-progress {{ $coverage >= 80 ? 'green' : ($coverage >= 40 ? 'amber' : 'red') }}">
                        <span data-width="{{ $coverage }}"></span>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <button type="button" class="kls-btn kls-btn--secondary" id="refreshWaliKelas">
                    <i class="bi bi-arrow-clockwise"></i> Refresh
                </button>
                <button type="button" class="kls-btn kls-btn--primary" data-bs-toggle="modal" data-bs-target="#klsModalTambah">
                    <i class="bi bi-person-plus"></i> Tambah Wali Kelas
                </button>
            </div>
        </aside>
    </header>

    {{-- ===================== KPI ===================== --}}
    <section class="kls-kpi-grid">
        <div class="kls-kpi">
            <div class="kls-kpi-top">
                <span class="kls-kpi-ico blue"><i class="bi bi-person-badge-fill"></i></span>
                <span class="kls-kpi-tag">Total</span>
            </div>
            <div class="kls-kpi-num"><span data-counter="{{ $totalWali }}">0</span></div>
            <div class="kls-kpi-label">Wali Kelas</div>
            <div class="kls-kpi-sub">Penugasan aktif</div>
        </div>
        <div class="kls-kpi">
            <div class="kls-kpi-top">
                <span class="kls-kpi-ico green"><i class="bi bi-person-check-fill"></i></span>
                <span class="kls-kpi-tag">Selesai</span>
            </div>
            <div class="kls-kpi-num"><span data-counter="{{ $totalWali }}">0</span></div>
            <div class="kls-kpi-label">Guru Menjadi Wali</div>
            <div class="kls-kpi-sub">Sudah bertugas</div>
        </div>
        <div class="kls-kpi">
            <div class="kls-kpi-top">
                <span class="kls-kpi-ico violet"><i class="bi bi-easel-fill"></i></span>
                <span class="kls-kpi-tag">Total</span>
            </div>
            <div class="kls-kpi-num"><span data-counter="{{ $totalKelas }}">0</span></div>
            <div class="kls-kpi-label">Total Kelas</div>
            <div class="kls-kpi-sub">Terdaftar</div>
        </div>
        <div class="kls-kpi">
            <div class="kls-kpi-top">
                <span class="kls-kpi-ico amber"><i class="bi bi-percent"></i></span>
                <span class="kls-kpi-tag">Cakupan</span>
            </div>
            <div class="kls-kpi-num">{{ $coverage }}<small style="font-size:14px;font-weight:700;color:var(--kls-text-3)">%</small></div>
            <div class="kls-kpi-label">Coverage Assignment</div>
            <div class="kls-kpi-bar"><span data-width="{{ $coverage }}"></span></div>
        </div>
        <div class="kls-kpi">
            <div class="kls-kpi-top">
                <span class="kls-kpi-ico info"><i class="bi bi-hourglass-split"></i></span>
                <span class="kls-kpi-tag">Perlu</span>
            </div>
            <div class="kls-kpi-num"><span data-counter="{{ $kelas->count() }}">0</span></div>
            <div class="kls-kpi-label">Kelas Tanpa Wali</div>
            <div class="kls-kpi-sub">Menunggu penugasan</div>
        </div>
    </section>

    {{-- ===================== TOOLBAR ===================== --}}
    <div class="kls-toolbar">
        <div class="kls-toolbar-top">
            <div class="kls-toolbar-title">
                <h2>Daftar Penugasan Wali Kelas</h2>
                <p>Guru ditugaskan sebagai pendamping akademik sebuah kelas.</p>
            </div>
            <span class="kls-chip kls-chip--green"><i class="bi bi-check-circle"></i> Assigned</span>
        </div>
        <div class="kls-toolbar-row">
            <div class="kls-field">
                <label for="waliSearch">Cari Guru / Kelas</label>
                <div class="kls-input-wrap">
                    <i class="bi bi-search"></i>
                    <input type="search" id="waliSearch" class="kls-input" placeholder="Ketik nama guru atau kelas..." autocomplete="off">
                </div>
            </div>
            <div class="kls-field">
                <label>&nbsp;</label>
                <button type="button" class="kls-btn kls-btn--secondary" id="resetWaliSearch">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </button>
            </div>
            <div class="kls-field" style="grid-column: span 2;">
                <label>&nbsp;</label>
                <button type="button" class="kls-btn kls-btn--primary" data-bs-toggle="modal" data-bs-target="#klsModalTambah">
                    <i class="bi bi-person-plus"></i> Tambah Wali Kelas
                </button>
            </div>
        </div>
    </div>

    {{-- ===================== CARD GRID ===================== --}}
    @if($waliKelas->count() > 0)
        <div class="kls-grid" id="waliGrid">
            @foreach($waliKelas as $item)
                @php
                    $guruName = $item->guru->nama ?? '-';
                    $guruKode = $item->guru->kode_guru ?? null;
                    $kelasObj = $item->kelas;
                    $kelasName = $kelasObj->nama_kelas ?? '-';
                    $tingkat = $kelasObj->tingkat ?? null;
                    $jenjangObj = $kelasObj->jenjang ?? null;
                    $jenjangName = $jenjangObj->nama ?? ($jenjangObj->nama_jenjang ?? '-');
                    $jenjangKode = $jenjangObj->kode ?? '';
                    $jenjangClass = strtolower($jenjangKode) ?: 'default';
                    $inits = '';
                    $words = preg_split('/\s+/', trim((string) $guruName));
                    foreach (array_slice($words, 0, 2) as $w) { if ($w !== '') $inits .= mb_strtoupper(mb_substr($w, 0, 1)); }
                    $inits = $inits ?: 'WK';
                    $avClass = $avatarClasses[($loop->index) % $avatarCount];
                    $searchKey = mb_strtolower($guruName . ' ' . $kelasName . ' ' . $jenjangName . ' ' . $tingkat . ' ' . $guruKode);
                @endphp
                <article class="kls-wali card kls-classcard" data-search="{{ $searchKey }}">
                    <div class="kls-classcard-top">
                        <div class="kls-classcard-title">
                            <span class="kls-avatar {{ $avClass }}" style="width:44px;height:44px;font-size:14px;border-radius:14px;">{{ $inits }}</span>
                            <div class="min-width-0">
                                <div class="kls-classcard-name" style="font-size:14px;">{{ $guruName }}</div>
                                <div class="kls-classcard-meta">{{ $guruKode ? 'Kode ' . $guruKode : 'Guru' }}</div>
                            </div>
                        </div>
                        <span class="kls-chip kls-chip--green"><i class="bi bi-check-circle"></i> Assigned</span>
                    </div>

                    <div class="kls-classcard-body">
                        <div class="kls-classcard-row">
                            <i class="bi bi-easel-fill"></i>
                            <span>Kelas <b>{{ $kelasName }}</b></span>
                        </div>
                        <div class="kls-classcard-row">
                            <i class="bi bi-layers-fill"></i>
                            <span>Jenjang <b>{{ $jenjangName }}</b></span>
                        </div>
                        <div class="kls-classcard-row">
                            <i class="bi bi-bar-chart-fill"></i>
                            <span>Tingkat <b>{{ $tingkat ?: '-' }}</b></span>
                        </div>
                    </div>

                    <div class="kls-classcard-foot">
                        <span class="kls-chip {{ $jenjangClass === 'mi' ? 'kls-chip--green' : ($jenjangClass === 'mts' ? 'kls-chip--blue' : ($jenjangClass === 'ma' ? 'kls-chip--amber' : 'kls-chip--neutral')) }}">
                            <i class="bi bi-bookmark-fill"></i> {{ $jenjangKode ?: $jenjangName }}
                        </span>
                        <div class="kls-actions">
                            <button type="button"
                                    class="kls-icon-btn kls-icon-btn--red"
                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus penugasan"
                                    aria-label="Hapus penugasan {{ $guruName }}"
                                    onclick="openDeleteWali({{ $item->id }}, @json($guruName), @json($kelasName))">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="d-flex justify-content-end pt-3">
            {{ $waliKelas->links() }}
        </div>
    @else
        <div class="kls-card" style="margin-top:18px;">
            <div class="kls-empty">
                <div class="kls-empty-illus"><i class="bi bi-person-badge"></i></div>
                <h4>Belum ada penugasan wali kelas</h4>
                <p>Pasangkan guru dengan kelas agar setiap kelas memiliki pendamping akademik yang jelas.</p>
                <div class="mt-4">
                    <button type="button" class="kls-btn kls-btn--primary" data-bs-toggle="modal" data-bs-target="#klsModalTambah">
                        <i class="bi bi-person-plus"></i> Tambah Wali Kelas Pertama
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- ===================== MODAL TAMBAH ===================== --}}
    <div class="modal fade kls-modal" id="klsModalTambah" tabindex="-1" aria-labelledby="klsModalTambahLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <form id="klsFormTambah" method="post" novalidate>
                    @csrf
                    <div class="kls-modal-head">
                        <div class="kls-modal-head-inner">
                            <span class="kls-modal-ico blue"><i class="bi bi-person-plus"></i></span>
                            <div>
                                <h3 class="kls-modal-title" id="klsModalTambahLabel">Tambah Wali Kelas</h3>
                                <p class="kls-modal-sub">Pasangkan guru dengan kelas. Guru yang tampil belum menjadi wali dan kelas yang tampil belum memiliki wali.</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>

                    <div class="kls-modal-body">
                        <div class="kls-form-grid">
                            <div class="form-floating">
                                <select class="form-select" id="guru_id" name="guru_id" style="height:auto;" required>
                                    <option value="">Pilih Guru</option>
                                    @foreach ($gurus as $guru)
                                        <option value="{{ $guru->id }}">{{ $guru->nama }}@if($guru->kode_guru) ({{ $guru->kode_guru }})@endif</option>
                                    @endforeach
                                </select>
                                <label for="guru_id">Guru <span class="text-muted" style="font-weight:500">(belum menjadi wali)</span></label>
                                <div class="kls-field-msg" id="guruMsg"></div>
                            </div>
                            <div class="form-floating">
                                <select class="form-select" id="kelas_id" name="kelas_id" style="height:auto;" required>
                                    <option value="">Pilih Kelas</option>
                                    @foreach ($kelas as $item)
                                        <option value="{{ $item->id }}">Kelas {{ $item->nama_kelas }}</option>
                                    @endforeach
                                </select>
                                <label for="kelas_id">Kelas <span class="text-muted" style="font-weight:500">(belum memiliki wali)</span></label>
                                <div class="kls-field-msg" id="kelasMsg"></div>
                            </div>
                        </div>
                    </div>

                    <div class="kls-modal-foot">
                        <span style="font-size:11.5px;color:var(--kls-text-3);margin-right:auto;">Validasi dilakukan secara realtime saat menyimpan.</span>
                        <button type="button" class="kls-btn kls-btn--secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="kls-btn kls-btn--primary" id="btnSimpan">
                            <i class="bi bi-check-lg"></i> Simpan Penugasan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ===================== MODAL KONFIRMASI HAPUS ===================== --}}
    <div class="modal fade kls-modal" id="klsModalHapus" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
            <div class="modal-content">
                <div class="kls-modal-body" style="text-align:center;padding:30px 26px 24px;">
                    <div class="kls-confirm-ico red"><i class="bi bi-trash3"></i></div>
                    <div class="kls-confirm-title">Hapus penugasan wali kelas?</div>
                    <div class="kls-confirm-text" id="klsHapusText"></div>
                </div>
                <div class="kls-modal-foot" style="justify-content:center;">
                    <button type="button" class="kls-btn kls-btn--secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="kls-btn kls-btn--danger" id="btnHapusYakin">
                        <i class="bi bi-trash3"></i> Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Toast stack --}}
    <div class="kls-toasts" id="klsToasts" aria-live="polite" aria-atomic="true"></div>
</div>
@endsection

@push('css')
<style>
    .kls-wali .min-width-0 { min-width: 0; }
    .kls-wali .kls-classcard-name { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 220px; }
    .pagination { flex-wrap: wrap; gap: 4px; }
    .pagination .page-link {
        border: 1px solid var(--kls-border) !important;
        border-radius: 8px !important;
        background: var(--kls-surface) !important;
        color: var(--kls-text-2) !important;
        font-size: 12.5px;
        font-weight: 700;
        min-width: 36px;
        text-align: center;
        margin: 0 2px;
        transition: all .15s ease;
    }
    .pagination .page-link:hover {
        border-color: var(--kls-primary-border) !important;
        background: var(--kls-primary-soft) !important;
        color: var(--kls-primary-dark) !important;
    }
    .pagination .page-item.active .page-link {
        background: var(--kls-primary) !important;
        border-color: var(--kls-primary) !important;
        color: #fff !important;
    }
    .pagination .page-item.disabled .page-link { opacity: .4; }
    .kls-modal .form-select option { color: var(--kls-text); }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    var grid = $('#waliGrid');

    function showToast(type, title, text) {
        var stack = $('#klsToasts')[0];
        if (!stack) return;
        var toast = document.createElement('div');
        toast.className = 'kls-toast ' + (type === 'success' ? 'ok' : 'err');
        toast.innerHTML = '<span class="kls-toast-ico"><i class="bi ' + (type === 'success' ? 'bi-check2-circle' : 'bi-exclamation-octagon') + '"></i></span><div><div class="kls-toast-t">' + title + '</div><div class="kls-toast-s">' + text + '</div></div>';
        stack.appendChild(toast);
        requestAnimationFrame(function() { toast.classList.add('is-show'); });
        setTimeout(function() {
            toast.classList.remove('is-show');
            setTimeout(function() { toast.remove(); }, 260);
        }, 3400);
    }

    function markErr(id, msg) {
        var $msg = $('#' + id);
        $msg.html('<i class="bi bi-exclamation-circle"></i> ' + msg).addClass('err');
        $msg.closest('.form-floating').find('.form-select').addClass('is-invalid');
    }

    function clearErrs() {
        $('#guruMsg, #kelasMsg').html('').removeClass('ok err');
        $('#klsModalTambah').find('.form-select').removeClass('is-invalid');
    }

    function initTooltips() {
        $('[data-bs-toggle="tooltip"]').each(function() {
            if (bootstrap.Tooltip.getInstance(this)) return;
            new bootstrap.Tooltip(this);
        });
    }

    function animateCounters() {
        var prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        $('[data-counter]').each(function() {
            var target = parseInt($(this).attr('data-counter'), 10) || 0;
            if (prefersReduced || target === 0) { $(this).text(target); return; }
            var el = $(this), cur = 0;
            var timer = setInterval(function() {
                cur += Math.max(1, Math.ceil(target / 24));
                if (cur >= target) { cur = target; clearInterval(timer); }
                el.text(cur);
            }, 28);
        });
        $('[data-width]').each(function() {
            var w = parseFloat($(this).attr('data-width')) || 0;
            var el = $(this);
            setTimeout(function() { el.css('width', w + '%'); }, 120);
        });
    }

    /* ---------- Live filter on cards ---------- */
    function applyFilter(q) {
        q = (q || '').trim().toLowerCase();
        var total = 0;
        grid.find('.kls-classcard').each(function() {
            var match = !q || ($(this).data('search') || '').indexOf(q) !== -1;
            $(this).toggle(match);
            if (match) total++;
        });
    }

    $('#waliSearch').on('input', function() { applyFilter(this.value); });
    $('#resetWaliSearch').on('click', function() {
        $('#waliSearch').val('');
        applyFilter('');
    });

    /* ---------- Refresh ---------- */
    $('#refreshWaliKelas').on('click', function() {
        window.location.reload();
    });

    /* ---------- Select2 ---------- */
    $('#guru_id, #kelas_id').select2({
        dropdownParent: $('#klsModalTambah')
    });

    /* ---------- Submit add ---------- */
    $('#klsFormTambah').on('submit', function(e) {
        e.preventDefault();
        clearErrs();
        var form = $(this);
        var ok = true;
        if (!$('#guru_id').val()) { markErr('guruMsg', 'Pilih guru.'); ok = false; }
        if (!$('#kelas_id').val()) { markErr('kelasMsg', 'Pilih kelas.'); ok = false; }
        if (!ok) return;

        $.ajax({
            url: '{{ route("wali-kelas.store") }}',
            type: 'POST',
            data: form.serialize(),
            beforeSend: function() {
                $('#btnSimpan').prop('disabled', true).html('<span class="kls-spinner"></span> Menyimpan...');
            },
            success: function(res) {
                if (res.success) {
                    showToast('success', 'Berhasil', res.message);
                    $('#klsModalTambah').modal('hide');
                    setTimeout(function() { window.location.reload(); }, 700);
                }
            },
            error: function(xhr) {
                $('#btnSimpan').prop('disabled', false).html('<i class="bi bi-check-lg"></i> Simpan Penugasan');
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors || {};
                    if (errors.guru_id) markErr('guruMsg', errors.guru_id.join(', '));
                    if (errors.kelas_id) markErr('kelasMsg', errors.kelas_id.join(', '));
                } else {
                    showToast('error', 'Error', 'Terjadi kesalahan server.');
                }
            }
        });
    });

    /* ---------- Delete confirm ---------- */
    var pendingDelete = null;
    window.openDeleteWali = function(id, guruName, kelasName) {
        pendingDelete = id;
        $('#klsHapusText').text(guruName + ' akan dilepas dari kelas ' + kelasName + '. Tindakan ini tidak dapat dibatalkan.');
        $('#klsModalHapus').modal('show');
    };

    $('#btnHapusYakin').on('click', function() {
        if (!pendingDelete) return;
        var btn = $(this);
        btn.prop('disabled', true).html('<span class="kls-spinner"></span> Menghapus...');
        $.ajax({
            url: '/wali-kelas/' + pendingDelete,
            type: 'DELETE',
            data: { _token: $('meta[name=csrf-token]').attr('content') },
            success: function(res) {
                if (res.success) {
                    $('#klsModalHapus').modal('hide');
                    showToast('success', 'Berhasil', res.message);
                    setTimeout(function() { window.location.reload(); }, 700);
                }
            },
            error: function() {
                btn.prop('disabled', false).html('<i class="bi bi-trash3"></i> Ya, Hapus');
                $('#klsModalHapus').modal('hide');
                showToast('error', 'Error', 'Terjadi kesalahan server.');
            }
        });
    });

    /* ---------- Reinit tooltips when modal hidden ---------- */
    $('#klsModalTambah, #klsModalHapus').on('hidden.bs.modal', function() {
        initTooltips();
    });

    initTooltips();
    animateCounters();
});
</script>
@endpush
