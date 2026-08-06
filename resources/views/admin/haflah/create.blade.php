@extends('layouts.main')
@section('title','Tambah Haflatul Imtihan')
@section('content')

@include('component.admin.lomba-workspace')

<style>
    .page-title-content { display: none !important; }

    .lw-form-card { max-width: 920px; }

    /* Summary di langkah terakhir */
    .lw-summary { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .lw-summary-cell .lbl { display: flex; align-items: center; gap: 6px; }

    @media (max-width: 575.98px) {
        .lw-summary { grid-template-columns: 1fr; }
        .lw-wizard-nav .lw-btn { flex: 1; }
    }
</style>

<div class="lw-mod jd-page-haflah">

{{-- ===== HERO ===== --}}
<div class="lw-hero">
    <div class="lw-hero-grid">
        <div class="lw-hero-left">
            <div class="lw-hero-icon"><i class="bi bi-plus-lg"></i></div>
            <div>
                <h1 class="lw-hero-title">Tambah Haflatul Imtihan</h1>
                <p class="lw-hero-sub">Buat penyelenggaraan haflatul imtihan baru untuk tahun ajaran aktif melalui 3 langkah singkat.</p>
                <div class="lw-hero-badges">
                    <span class="lw-hero-badge"><i class="bi bi-mortarboard-fill"></i> {{ $tahunAktif?->tahun_ajaran ?? 'Belum ada TA aktif' }}</span>
                    <span class="lw-hero-badge lw-hero-badge--warn"><i class="bi bi-clock"></i> Status awal: Persiapan</span>
                </div>
            </div>
        </div>
        <div class="lw-hero-right">
            <a href="{{ route('haflatul-imtihan.index') }}" class="lw-btn lw-btn--light"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>
    </div>
</div>

{{-- ===== ERRORS ===== --}}
@if($errors->any())
<div class="lw-alert lw-alert--err">
    <i class="bi bi-exclamation-circle-fill"></i>
    <div style="flex:1;min-width:0;">
        <b>Periksa kembali data yang dimasukkan</b>
        <ul class="mb-0 ps-3" style="font-size:12.5px;line-height:1.7;margin-top:4px;">
            @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    <button type="button" class="lw-alert-close" onclick="this.closest('.lw-alert').remove()">&times;</button>
</div>
@endif

@if(session('error'))
<div class="lw-alert lw-alert--warn">
    <i class="bi bi-exclamation-triangle-fill"></i>
    <div style="flex:1;min-width:0;"><b>Perhatian</b> &middot; <span>{{ session('error') }}</span></div>
    <button type="button" class="lw-alert-close" onclick="this.closest('.lw-alert').remove()">&times;</button>
</div>
@endif

{{-- ===== SUDAH ADA ===== --}}
@if($sudahAda)
<div class="lw-alert lw-alert--accent">
    <i class="bi bi-check-circle-fill"></i>
    <div style="flex:1;min-width:0;">
        <b>Haflatul Imtihan tahun ini sudah ada</b>
        <span>Tahun ajaran <b>{{ $tahunAktif?->tahun_ajaran ?? '-' }}</b> sudah memiliki data Haflatul Imtihan. Tidak dapat membuat lebih dari satu.</span>
    </div>
    <a href="{{ route('haflatul-imtihan.index') }}" class="lw-btn lw-btn--sm lw-btn--soft" style="margin-left:auto;flex-shrink:0;"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>
@else
{{-- ===== FORM / WIZARD ===== --}}
<div class="lw-card lw-card-pad lw-form-card">
    <form id="hfCreateForm" action="{{ route('haflatul-imtihan.store') }}" method="POST"
        onsubmit="return !this.submit.disabled && (this.submit.disabled=true, document.querySelector('#hfSubmitBtn').innerHTML='<i class=\'bi bi-arrow-repeat\' style=\'animation:lwSpin 1s linear infinite;\'></i> Menyimpan...', sessionStorage.setItem('sia_navigating','true'), true)">
        @csrf

        {{-- Stepper --}}
        <div class="lw-stepper">
            <div class="lw-step active" data-step="1">
                <div class="lw-step-dot">1</div>
                <div class="lw-step-txt"><b>Informasi Acara</b><span>Nama &amp; tahun ajaran</span></div>
            </div>
            <div class="lw-step-line"></div>
            <div class="lw-step" data-step="2">
                <div class="lw-step-dot">2</div>
                <div class="lw-step-txt"><b>Jadwal</b><span>Tanggal berlangsung</span></div>
            </div>
            <div class="lw-step-line"></div>
            <div class="lw-step" data-step="3">
                <div class="lw-step-dot">3</div>
                <div class="lw-step-txt"><b>Tinjauan</b><span>Konfirmasi data</span></div>
            </div>
        </div>

        {{-- STEP 1 --}}
        <div class="lw-wizard-pane is-show" data-pane="1">
            <div class="lw-form-section"><i class="bi bi-info-circle-fill"></i> Informasi Haflah</div>
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label"><i class="bi bi-calendar-week me-1" style="color:var(--lw-primary);font-size:12px;"></i> Tahun Ajaran</label>
                    <input type="text" class="lw-control" value="{{ $tahunAktif?->tahun_ajaran ?? '-' }}" readonly>
                    <input type="hidden" name="tahun_ajaran_id" value="{{ $tahunAktif->id ?? '' }}">
                    <div class="lw-lock-note"><i class="bi bi-lock-fill"></i> Menggunakan tahun ajaran aktif</div>
                    @error('tahun_ajaran_id')<div class="lw-form-err"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <div class="lw-status-note">
                        <i class="bi bi-clock" style="margin-top:2px;"></i>
                        <div>Status berjalan otomatis: <b>Persiapan</b> &rarr; <b>Aktif</b> (saat tanggal mulai) &rarr; <b>Selesai</b> (setelah tanggal selesai).</div>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label" for="hfNama"><i class="bi bi-tag-fill me-1" style="color:var(--lw-primary);font-size:12px;"></i> Nama Acara</label>
                    <input type="text" id="hfNama" name="nama_acara" class="lw-control"
                        value="{{ old('nama_acara', 'Haflatul Imtihan dan Akhirussanah') }}" placeholder="Contoh: Haflatul Imtihan dan Akhirussanah" autocomplete="off">
                    <div class="lw-form-err d-none" id="err-nama"><i class="bi bi-exclamation-circle-fill"></i> Nama acara minimal 3 karakter.</div>
                    @error('nama_acara')<div class="lw-form-err"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        {{-- STEP 2 --}}
        <div class="lw-wizard-pane" data-pane="2">
            <div class="lw-form-section"><i class="bi bi-calendar-week"></i> Jadwal Penyelenggaraan</div>
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label" for="hfMulai"><i class="bi bi-calendar-event me-1" style="color:var(--lw-primary);font-size:12px;"></i> Tanggal Mulai</label>
                    <input type="date" id="hfMulai" name="tanggal_mulai" class="lw-control" value="{{ old('tanggal_mulai') }}">
                    <div class="lw-form-err d-none" id="err-mulai"><i class="bi bi-exclamation-circle-fill"></i> Tanggal mulai wajib diisi.</div>
                    @error('tanggal_mulai')<div class="lw-form-err"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="hfSelesai"><i class="bi bi-calendar-check me-1" style="color:var(--lw-primary);font-size:12px;"></i> Tanggal Selesai</label>
                    <input type="date" id="hfSelesai" name="tanggal_selesai" class="lw-control" value="{{ old('tanggal_selesai') }}">
                    <div class="lw-form-err d-none" id="err-selesai"><i class="bi bi-exclamation-circle-fill"></i> Tanggal selesai wajib diisi dan tidak boleh sebelum tanggal mulai.</div>
                    @error('tanggal_selesai')<div class="lw-form-err"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        {{-- STEP 3 --}}
        <div class="lw-wizard-pane" data-pane="3">
            <div class="lw-form-section"><i class="bi bi-eye-fill"></i> Tinjauan &amp; Simpan</div>
            <div class="lw-preview" id="hfPreview">
                <div class="lw-preview-icon"><i class="bi bi-award-fill"></i></div>
                <div>
                    <div class="lw-preview-name" id="hfPrevName">Haflatul Imtihan dan Akhirussanah</div>
                    <div class="lw-preview-meta" id="hfPrevMeta">
                        <span><i class="bi bi-mortarboard-fill"></i> <b id="hfPrevTa">{{ $tahunAktif?->tahun_ajaran ?? '-' }}</b></span>
                        <span><i class="bi bi-calendar-event"></i> <b id="hfPrevMulai">—</b></span>
                        <span><i class="bi bi-calendar-check"></i> <b id="hfPrevSelesai">—</b></span>
                        <span><i class="bi bi-flag-fill"></i> <b>Persiapan</b></span>
                    </div>
                </div>
            </div>

            <div class="lw-summary" style="margin-top:16px;">
                <div class="lw-summary-cell">
                    <div class="lbl"><i class="bi bi-tag-fill"></i> Nama Acara</div>
                    <div class="v" id="hfSumNama">—</div>
                </div>
                <div class="lw-summary-cell">
                    <div class="lbl"><i class="bi bi-calendar-week"></i> Tahun Ajaran</div>
                    <div class="v">{{ $tahunAktif?->tahun_ajaran ?? '-' }}</div>
                </div>
                <div class="lw-summary-cell">
                    <div class="lbl"><i class="bi bi-calendar-event"></i> Tanggal Mulai</div>
                    <div class="v" id="hfSumMulai">—</div>
                </div>
                <div class="lw-summary-cell">
                    <div class="lbl"><i class="bi bi-calendar-check"></i> Tanggal Selesai</div>
                    <div class="v" id="hfSumSelesai">—</div>
                </div>
            </div>

            <div class="lw-status-note" style="margin-top:16px;">
                <i class="bi bi-clock" style="margin-top:2px;"></i>
                <div>Setelah disimpan, haflah masuk status <b>Persiapan</b> dan otomatis menjadi <b>Aktif</b> saat tanggal mulai tiba.</div>
            </div>
        </div>

        {{-- NAV --}}
        <div class="lw-wizard-nav">
            <button type="button" class="lw-btn" id="hfBackBtn"><i class="bi bi-arrow-left"></i> Kembali</button>
            <span class="spacer"></span>
            <button type="button" class="lw-btn lw-btn--soft" id="hfNextBtn">Berikutnya <i class="bi bi-arrow-right"></i></button>
            <button type="submit" class="lw-btn lw-btn--solid d-none" id="hfSubmitBtn"><i class="bi bi-save"></i> Simpan Haflah</button>
        </div>
    </form>
</div>
@endif

</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        var current = 1;
        var maxStep = 3;

        function fmtDate(v) {
            if (!v) { return '—'; }
            var d = new Date(v + 'T00:00:00');
            if (isNaN(d)) { return v; }
            return d.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
        }
        function fmtDateShort(v) {
            if (!v) { return '—'; }
            var d = new Date(v + 'T00:00:00');
            if (isNaN(d)) { return v; }
            return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
        }

        function showErr(id, show) {
            var el = document.getElementById(id);
            if (!el) { return; }
            if (show) { el.classList.remove('d-none'); } else { el.classList.add('d-none'); }
        }
        function setInvalid(input, bad) {
            if (!input) { return; }
            if (bad) { input.classList.add('is-invalid'); } else { input.classList.remove('is-invalid'); }
        }

        function validateStep(n) {
            var ok = true;
            if (n === 1) {
                var nama = document.getElementById('hfNama');
                var bad = nama.value.trim().length < 3;
                showErr('err-nama', bad);
                setInvalid(nama, bad);
                if (bad) { ok = false; }
            }
            if (n === 2) {
                var mulai = document.getElementById('hfMulai');
                var selesai = document.getElementById('hfSelesai');
                var badMulai = !mulai.value;
                var badSelesai = !selesai.value || (mulai.value && selesai.value < mulai.value);
                showErr('err-mulai', badMulai);
                showErr('err-selesai', badSelesai);
                setInvalid(mulai, badMulai);
                setInvalid(selesai, badSelesai);
                if (badMulai || badSelesai) { ok = false; }
            }
            return ok;
        }

        function refreshPreview() {
            var nama = document.getElementById('hfNama').value.trim() || 'Haflatul Imtihan dan Akhirussanah';
            var mulai = document.getElementById('hfMulai').value;
            var selesai = document.getElementById('hfSelesai').value;
            document.getElementById('hfPrevName').textContent = nama;
            document.getElementById('hfPrevMulai').textContent = fmtDateShort(mulai);
            document.getElementById('hfPrevSelesai').textContent = fmtDateShort(selesai);
            document.getElementById('hfSumNama').textContent = nama;
            document.getElementById('hfSumMulai').textContent = fmtDate(mulai);
            document.getElementById('hfSumSelesai').textContent = fmtDate(selesai);
            var preview = document.getElementById('hfPreview');
            if (mulai && selesai) { preview.classList.add('has-date'); } else { preview.classList.remove('has-date'); }
        }

        function goTo(n) {
            if (n < current) {
                // back: no validation
            } else if (n > current) {
                if (!validateStep(current)) {
                    LW.toast('err', 'Periksa isian', 'Lengkapi data dengan benar sebelum lanjut.');
                    return;
                }
            }
            current = n;
            $('.lw-wizard-pane').each(function() {
                $(this).toggleClass('is-show', $(this).data('pane') == current);
            });
            $('.lw-step').each(function() {
                var s = $(this).data('step');
                $(this).toggleClass('active', s == current);
                $(this).toggleClass('done', s < current);
            });
            $('.lw-step-line').each(function() {
                $(this).toggleClass('done', ($(this).index('.lw-step-line') + 1) < current);
            });
            $('#hfBackBtn').toggle(current > 1);
            $('#hfNextBtn').toggleClass('d-none', current >= maxStep);
            $('#hfSubmitBtn').toggleClass('d-none', current < maxStep);
            if (current === maxStep) { refreshPreview(); }
        }

        $('#hfNextBtn').on('click', function() { goTo(current + 1); });
        $('#hfBackBtn').on('click', function() { goTo(current - 1); });
        $('#hfNama, #hfMulai, #hfSelesai').on('input change', refreshPreview);

        // inline re-validate on change
        $('#hfNama').on('input', function() {
            if ($(this).hasClass('is-invalid')) {
                showErr('err-nama', $(this).val().trim().length < 3);
            }
        });
        $('#hfMulai, #hfSelesai').on('change', function() {
            if ($(this).hasClass('is-invalid')) {
                var mulai = $('#hfMulai').val();
                var selesai = $('#hfSelesai').val();
                showErr('err-mulai', !mulai);
                showErr('err-selesai', !selesai || (mulai && selesai < mulai));
            }
        });

        goTo(1);
    });
</script>
@endpush
