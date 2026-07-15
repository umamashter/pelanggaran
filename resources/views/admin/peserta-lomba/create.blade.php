@extends('layouts.main')
@section('title', 'Tambah Peserta Lomba')
@push('css')
<style>
    .create-peserta-page {
        font-family: 'Inter', 'Poppins', system-ui, sans-serif;
        max-width: 680px;
        margin: 14px auto 0;
        padding: 0 22px;
    }
    .breadcrumb-cu {
        margin-bottom: 20px;
    }
    .breadcrumb-cu .breadcrumb {
        background: transparent;
        padding: 0;
        margin: 0;
    }
    .breadcrumb-cu .breadcrumb-item {
        font-size: 13px;
    }
    .breadcrumb-cu .breadcrumb-item a {
        color: #64748b;
        text-decoration: none;
        transition: color .2s;
    }
    .breadcrumb-cu .breadcrumb-item a:hover {
        color: #16a34a;
    }
    .breadcrumb-cu .breadcrumb-item.active {
        color: #1e293b;
        font-weight: 500;
    }
    .breadcrumb-cu .breadcrumb-item+.breadcrumb-item::before {
        color: #cbd5e1;
    }
    .create-card {
        border: none;
        border-radius: 18px;
        box-shadow: 0 4px 16px rgba(0,0,0,.06), 0 2px 8px rgba(0,0,0,.04);
    }
    .create-card-header {
        padding: 14px 16px 12px;
        border-bottom: 1px solid #f1f5f9;
    }
    .create-card-body {
        padding: 12px 16px 14px;
    }
    .form-label-cu {
        font-weight: 600;
        font-size: 13px;
        color: #374151;
        margin-bottom: 5px;
        display: block;
    }
    .invalid-feedback-cu {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 6px;
        font-size: 13px;
        color: #dc2626;
        font-weight: 500;
    }
    .input-group-cu .form-control.is-invalid,
    .input-group-cu .form-select.is-invalid {
        border-color: #dc2626;
        background-image: none;
    }
    .input-group-cu .form-control.is-invalid:focus,
    .input-group-cu .form-select.is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(220,38,38,.1);
    }
    .input-group-cu {
        position: relative;
    }
    .input-group-cu .form-control,
    .input-group-cu .form-select {
        height: 38px;
        padding-left: 36px;
        border-radius: 8px;
        border: 1.5px solid #e2e8f0;
        font-size: 13px;
        transition: border .2s, box-shadow .2s;
    }
    .input-group-cu .form-control:focus,
    .input-group-cu .form-select:focus {
        border-color: #16a34a;
        box-shadow: 0 0 0 3px rgba(22,163,74,.12);
    }
    .input-group-cu .form-control::placeholder {
        color: #94a3b8;
        font-size: 13px;
    }
    .input-group-cu-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 13px;
        z-index: 4;
        pointer-events: none;
    }
    .alert-cu {
        border: none;
        border-radius: 12px;
        padding: 14px 20px;
        font-size: 14px;
        margin-bottom: 20px;
    }
    .alert-cu.alert-success {
        background: #f0fdf4;
        color: #16a34a;
        border-left: 4px solid #16a34a;
    }
    .alert-cu.alert-danger {
        background: #fef2f2;
        color: #991b1b;
        border-left: 4px solid #dc2626;
    }
    .alert-cu.alert-danger ul {
        padding-left: 20px;
        margin: 0;
    }
    .alert-cu.alert-danger ul li {
        list-style: disc;
    }
    .btn-cu {
        height: 36px;
        padding: 0 20px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        transition: all .25s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
        border: none;
        gap: 6px;
    }
    .btn-cu-secondary {
        background: #f1f5f9;
        color: #475569;
        border: 1.5px solid #e2e8f0;
    }
    .btn-cu-secondary:hover {
        background: #e2e8f0;
        color: #334155;
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(0,0,0,.08);
    }
    .btn-cu-primary {
        background: linear-gradient(135deg, #16a34a, #22c55e);
        color: #fff;
        box-shadow: 0 2px 8px rgba(22,163,74,.25);
    }
    .btn-cu-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(22,163,74,.35);
        color: #fff;
    }
    .btn-cu:active {
        transform: translateY(0);
    }
    .form-actions-cu {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-top: 20px;
        padding-top: 14px;
        border-top: 1px solid #f1f5f9;
    }
    .btn-header-ms.btn-simpan-ms.btn-compact {
        height: 34px; padding: 0 14px; font-size: 12px; border-radius: 8px;
    }
    .compact-grid {
        row-gap: 6px;
    }
    .loading-indicator {
        display: none;
        font-size: 13px;
        color: #64748b;
        margin-top: 6px;
        align-items: center;
        gap: 6px;
    }
    .loading-indicator.show {
        display: flex;
    }
    @media (max-width: 768px) {
        .create-peserta-page {
            margin-top: 12px;
            padding: 0 14px;
        }
        .create-card-header {
            padding: 12px 14px 10px;
        }
        .create-card-body {
            padding: 12px 14px 16px;
        }
        .form-actions-cu {
            flex-direction: column;
        }
        .form-actions-cu .btn-cu {
            width: 100%;
        }
    }
    @media (max-width: 480px) {
        .create-card-header {
            padding: 12px 14px 10px;
        }
        .create-card-body {
            padding: 12px 14px 16px;
        }
        .input-group-cu .form-control,
        .input-group-cu .form-select {
            height: 36px;
            font-size: 12px;
        }
        .btn-cu {
            height: 34px;
            padding: 0 16px;
            font-size: 12px;
        }
    }
</style>
@endpush
@section('content')
@include('component.admin.ms-style')
<div class="create-peserta-page">

    <nav aria-label="breadcrumb" class="breadcrumb-cu">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/home"><i class="fas fa-home me-1"></i>Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('peserta-lomba.index') }}">Peserta Lomba</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Tambah Peserta Lomba</li>
        </ol>
    </nav>

    <div class="card create-card">
        <div class="create-card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon" style="width:34px;height:34px;font-size:16px;">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold" style="color: #1e293b; font-size: 15px;">Tambah Peserta Lomba</h4>
                    <span style="font-size: 12px; color: #64748b;">Daftarkan peserta lomba (individu atau kelompok).</span>
                </div>
            </div>
        </div>

        <div class="create-card-body">

            @if(session('success'))
                <div class="alert alert-cu alert-success">
                    <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-cu alert-danger">
                    <i class="fas fa-exclamation-triangle me-1"></i> Terdapat kesalahan pada form:
                    <ul class="mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('peserta-lomba.store') }}" method="POST" id="formPeserta">
                @csrf

                <div class="row g-2 compact-grid">
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label class="form-label-cu">Lomba</label>
                            <div class="input-group-cu">
                                <i class="fas fa-trophy input-group-cu-icon"></i>
                                <select name="lomba_id" class="form-select @error('lomba_id') is-invalid @enderror" id="lomba_id">
                                    <option value="">-- Pilih Lomba --</option>
                                    @foreach($lombas as $l)
                                    <option value="{{ $l->id }}" data-kelas-min="{{ $l->kelas_min }}" data-kelas-max="{{ $l->kelas_max }}" data-jenis="{{ $l->jenis }}" {{ old('lomba_id')==$l->id ? 'selected' : '' }}>{{ $l->nama }}</option>
                                    @endforeach
                                </select>
                                @error('lomba_id')
                                <div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>
                            <div id="infoAturan" style="display:none;font-size:12px;color:#c2410c;margin-top:6px;"><i class="fas fa-info-circle me-1"></i> Hanya untuk <span id="infoKelasRange"></span></div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-2">
                            <label class="form-label-cu">Status</label>
                            <div class="input-group-cu">
                                <i class="fas fa-flag input-group-cu-icon"></i>
                                <select name="status" class="form-select @error('status') is-invalid @enderror">
                                    <option value="">-- Pilih Status --</option>
                                    <option value="Terdaftar" {{ old('status')=='Terdaftar' ? 'selected' : '' }}>Terdaftar</option>
                                    <option value="Hadir" {{ old('status')=='Hadir' ? 'selected' : '' }}>Hadir</option>
                                    <option value="Tidak Hadir" {{ old('status')=='Tidak Hadir' ? 'selected' : '' }}>Tidak Hadir</option>
                                    <option value="Diskualifikasi" {{ old('status')=='Diskualifikasi' ? 'selected' : '' }}>Diskualifikasi</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6" id="field-jenjang">
                        <div class="mb-2">
                            <label class="form-label-cu">Jenjang</label>
                            <div class="input-group-cu">
                                <i class="fas fa-layer-group input-group-cu-icon"></i>
                                <select id="jenjang_id" class="form-select">
                                    <option value="">Pilih Jenjang</option>
                                    @foreach($jenjangs as $j)
                                    <option value="{{ $j->id }}" {{ old('jenjang_id')==$j->id ? 'selected' : '' }}>{{ $j->nama_jenjang }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6" id="field-kelas">
                        <div class="mb-2">
                            <label class="form-label-cu">Kelas</label>
                            <div class="input-group-cu">
                                <i class="fas fa-door-open input-group-cu-icon"></i>
                                <select id="kelas_id" class="form-select" disabled>
                                    <option value="">Pilih Kelas</option>
                                </select>
                            </div>
                            <div class="loading-indicator" id="loadingKelas">
                                <div class="spinner-border spinner-border-sm" role="status"></div>
                                <span>Memuat data kelas...</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12" id="field-siswa">
                        <div class="mb-2">
                            <label class="form-label-cu">Siswa</label>
                            <div class="input-group-cu">
                                <i class="fas fa-user-graduate input-group-cu-icon"></i>
                                <select name="student_id" id="siswa_id" class="form-select @error('student_id') is-invalid @enderror" disabled>
                                    <option value="">Pilih Siswa</option>
                                </select>
                                @error('student_id')
                                <div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>
                            <div class="loading-indicator" id="loadingSiswa">
                                <div class="spinner-border spinner-border-sm" role="status"></div>
                                <span>Memuat data siswa...</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12" id="field-kelompok" style="display:none;">
                        <div class="mb-2">
                            <label class="form-label-cu">Kelompok</label>
                            <div class="input-group-cu">
                                <i class="fas fa-users input-group-cu-icon"></i>
                                <select name="kelompok_lomba_id" id="kelompok_lomba_id" class="form-select @error('kelompok_lomba_id') is-invalid @enderror" disabled>
                                    <option value="">Pilih Kelompok</option>
                                </select>
                                @error('kelompok_lomba_id')
                                <div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>
                            <div class="loading-indicator" id="loadingKelompok">
                                <div class="spinner-border spinner-border-sm" role="status"></div>
                                <span>Memuat data kelompok...</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions-cu">
                    <a href="{{ route('peserta-lomba.index') }}" class="btn btn-header-ms btn-simpan-ms btn-compact" style="background:#fef3c7;color:#92400e;">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-header-ms btn-simpan-ms btn-compact">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    var $lomba    = $('#lomba_id');
    var $jenjang  = $('#jenjang_id');
    var $kelas    = $('#kelas_id');
    var $siswa    = $('#siswa_id');
    var $kelompok = $('#kelompok_lomba_id');
    var $fieldJenjang = $('#field-jenjang');
    var $fieldKelas   = $('#field-kelas');
    var $fieldSiswa   = $('#field-siswa');
    var $fieldKelompok = $('#field-kelompok');
    var $loadKelas = $('#loadingKelas');
    var $loadSiswa = $('#loadingSiswa');
    var $loadKelompok = $('#loadingKelompok');
    var $infoAturan = $('#infoAturan');
    var $infoRange = $('#infoKelasRange');

    var kelasMin = null, kelasMax = null;
    var kelasData = [];

    function resetKelas() {
        $kelas.prop('disabled', true).html('<option value="">Pilih Kelas</option>');
        resetSiswa();
    }

    function resetSiswa() {
        $siswa.prop('disabled', true).html('<option value="">Pilih Siswa</option>').removeClass('is-invalid');
    }

    function resetKelompok() {
        $kelompok.prop('disabled', true).html('<option value="">Pilih Kelompok</option>').removeClass('is-invalid');
    }

    function filterKelas() {
        var opts = '<option value="">Pilih Kelas</option>';
        $.each(kelasData, function (i, k) {
            var t = parseInt(k.tingkat);
            if ((kelasMin === null || t >= kelasMin) && (kelasMax === null || t <= kelasMax)) {
                opts += '<option value="' + k.id + '">' + k.nama_kelas + '</option>';
            }
        });
        $kelas.html(opts).prop('disabled', opts === '<option value="">Pilih Kelas</option>');
    }

    function toggleMode(jenis) {
        if (jenis === 'Tim') {
            $fieldJenjang.hide();
            $fieldKelas.hide();
            $fieldSiswa.hide();
            $fieldKelompok.show();
            $infoAturan.hide();
            loadKelompok($lomba.val());
        } else {
            $fieldJenjang.show();
            $fieldKelas.show();
            $fieldSiswa.show();
            $fieldKelompok.hide();
            resetKelompok();
            if ($jenjang.val()) $jenjang.trigger('change');
        }
    }

    function loadKelompok(lombaId) {
        resetKelompok();
        if (!lombaId) return;

        $.ajax({
            url: '{{ route("peserta-lomba.get-kelompok", "") }}/' + lombaId,
            type: 'GET',
            dataType: 'json',
            beforeSend: function () {
                $loadKelompok.addClass('show');
            },
            success: function (data) {
                var opts = '<option value="">Pilih Kelompok</option>';
                $.each(data, function (i, k) {
                    opts += '<option value="' + k.id + '">' + k.text + '</option>';
                });
                $kelompok.html(opts).prop('disabled', false);
                var oldKel = '{{ old("kelompok_lomba_id") }}';
                if (oldKel) { $kelompok.val(oldKel); }
            },
            complete: function () {
                $loadKelompok.removeClass('show');
            }
        });
    }

    $lomba.on('change', function () {
        var opt = $(this).find(':selected');
        var jenis = opt.data('jenis') || '';
        kelasMin = opt.data('kelas-min') || null;
        kelasMax = opt.data('kelas-max') || null;

        if (kelasMin && kelasMax && jenis !== 'Tim') {
            $infoRange.text(kelasMin === kelasMax ? 'Kelas ' + kelasMin : 'Kelas ' + kelasMin + ' - ' + kelasMax);
            $infoAturan.show();
        } else {
            $infoAturan.hide();
        }

        toggleMode(jenis);

        if (jenis !== 'Tim' && $jenjang.val()) $jenjang.trigger('change');
    });

    $jenjang.on('change', function () {
        var id = $(this).val();
        resetKelas();
        if (!id) return;

        $.ajax({
            url: '{{ url("/get-kelas") }}/' + id,
            type: 'GET',
            dataType: 'json',
            beforeSend: function () {
                $loadKelas.addClass('show');
            },
            success: function (data) {
                kelasData = data;
                filterKelas();
                var oldKelas = '{{ old("kelas_id") }}';
                if (oldKelas) { $kelas.val(oldKelas).trigger('change'); }
            },
            complete: function () {
                $loadKelas.removeClass('show');
            }
        });
    });

    $kelas.on('change', function () {
        var id = $(this).val();
        resetSiswa();
        if (!id) return;

        $.ajax({
            url: '{{ url("/get-siswa") }}/' + id,
            type: 'GET',
            data: { lomba_id: $lomba.val() },
            dataType: 'json',
            beforeSend: function () {
                $loadSiswa.addClass('show');
            },
            success: function (data) {
                var siswaList = Array.isArray(data) ? data : (data.siswa || []);
                var opts = '<option value="">Pilih Siswa</option>';
                $.each(siswaList, function (i, s) {
                    var nama = s.nama || (s.user ? s.user.name : '-');
                    opts += '<option value="' + s.id + '">' + nama + ' (' + s.nisn + ')</option>';
                });
                $siswa.html(opts).prop('disabled', false);
                var oldSiswa = '{{ old("student_id") }}';
                if (oldSiswa) { $siswa.val(oldSiswa); }
            },
            complete: function () {
                $loadSiswa.removeClass('show');
            }
        });
    });

    var oldLomba = '{{ old("lomba_id") }}';
    if (oldLomba) { $lomba.val(oldLomba); }
    var oldJenjang = '{{ old("jenjang_id") }}';
    if (oldJenjang) { $jenjang.val(oldJenjang).trigger('change'); }

    $('#formPeserta').on('submit', function (e) {
        var jenis = $lomba.find(':selected').data('jenis') || '';
        if (jenis === 'Tim') {
            if (!$kelompok.val()) { e.preventDefault(); $kelompok.focus(); return false; }
        } else {
            if (!$jenjang.val()) { e.preventDefault(); $jenjang.focus(); return false; }
            if (!$kelas.val()) { e.preventDefault(); $kelas.focus(); return false; }
            if (!$siswa.val()) { e.preventDefault(); $siswa.focus(); return false; }
        }
    });

    if (oldLomba) {
        var initialJenis = $lomba.find(':selected').data('jenis') || '';
        toggleMode(initialJenis);
    }
});
</script>
@endpush
