@extends('layouts.main')
@section('title', 'Tambah Massal Peserta')
@push('css')
<style>
    .massal-page { font-family: 'Inter', 'Poppins', system-ui, sans-serif; max-width: 640px; margin: 22px auto 0; padding: 0 16px; }
    .breadcrumb-cu { margin-bottom: 20px; }
    .breadcrumb-cu .breadcrumb { background: transparent; padding: 0; margin: 0; }
    .breadcrumb-cu .breadcrumb-item { font-size: 13px; }
    .breadcrumb-cu .breadcrumb-item a { color: #64748b; text-decoration: none; transition: color .2s; }
    .breadcrumb-cu .breadcrumb-item a:hover { color: #16a34a; }
    .breadcrumb-cu .breadcrumb-item.active { color: #1e293b; font-weight: 500; }
    .breadcrumb-cu .breadcrumb-item+.breadcrumb-item::before { color: #cbd5e1; }
    .create-card { border: none; border-radius: 18px; box-shadow: 0 4px 16px rgba(0,0,0,.06), 0 2px 8px rgba(0,0,0,.04); }
    .create-card-header { padding: 16px 24px 14px; border-bottom: 1px solid #f1f5f9; }
    .create-card-body { padding: 16px 24px 20px; }
    .form-label-cu { font-weight: 600; font-size: 13px; color: #374151; margin-bottom: 4px; display: block; }
    .invalid-feedback-cu { display: flex; align-items: center; gap: 6px; margin-top: 6px; font-size: 13px; color: #dc2626; font-weight: 500; }
    .input-group-cu { position: relative; }
    .input-group-cu .form-control, .input-group-cu .form-select { height: 46px; padding-left: 42px; border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: 14px; transition: border .2s, box-shadow .2s; width: 100%; }
    .input-group-cu .form-control:focus, .input-group-cu .form-select:focus { border-color: #16a34a; box-shadow: 0 0 0 3px rgba(22,163,74,.12); }
    .input-group-cu .form-control.is-invalid, .input-group-cu .form-select.is-invalid { border-color: #dc2626; }
    .input-group-cu-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 15px; z-index: 4; pointer-events: none; }
    .alert-cu { border: none; border-radius: 12px; padding: 14px 20px; font-size: 14px; margin-bottom: 20px; }
    .alert-cu.alert-success { background: #f0fdf4; color: #16a34a; border-left: 4px solid #16a34a; }
    .alert-cu.alert-danger { background: #fef2f2; color: #991b1b; border-left: 4px solid #dc2626; }
    .alert-cu.alert-danger ul { padding-left: 20px; margin: 0; }
    .alert-cu.alert-danger ul li { list-style: disc; }
    .btn-cu { height: 38px; padding: 0 22px; border-radius: 10px; font-size: 13px; font-weight: 600; transition: all .25s; display: inline-flex; align-items: center; justify-content: center; border: none; gap: 8px; }
    .btn-cu-secondary { background: #f1f5f9; color: #475569; border: 1.5px solid #e2e8f0; }
    .btn-cu-secondary:hover { background: #e2e8f0; color: #334155; }
    .btn-cu-primary { background: linear-gradient(135deg, #16a34a, #22c55e); color: #fff; box-shadow: 0 2px 8px rgba(22,163,74,.25); }
    .btn-cu-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(22,163,74,.35); color: #fff; }
    .btn-header-ms.btn-simpan-ms.btn-compact { height: 34px; padding: 0 14px; font-size: 12px; border-radius: 8px; }
    .loading-indicator { display: none; font-size: 13px; color: #64748b; margin-top: 6px; align-items: center; gap: 6px; }
    .loading-indicator.show { display: flex; }
    #infoAturan { font-size: 12px; color: #c2410c; margin-top: 6px; }
    .form-actions-cu { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-top: 20px; padding-top: 12px; border-top: 1px solid #f1f5f9; }
    @media (max-width: 768px) { .massal-page { margin-top: 16px; padding: 0 12px; } .create-card-header { padding: 14px 18px 12px; } .create-card-body { padding: 14px 18px 16px; } .form-actions-cu { flex-direction: column; } .form-actions-cu .btn-cu { width: 100%; } }
</style>
@endpush
@section('content')
@include('component.admin.ms-style')
<div class="massal-page">

    <nav aria-label="breadcrumb" class="breadcrumb-cu">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home me-1"></i>Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('peserta-lomba.index') }}">Peserta Lomba</a></li>
            <li class="breadcrumb-item active">Tambah Massal</li>
        </ol>
    </nav>

    <div class="card create-card">
        <div class="create-card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon" style="width:48px;height:48px;font-size:22px;"><i class="fas fa-users"></i></div>
                <div>
                    <h4 class="mb-0 fw-bold" style="color:#1e293b;font-size:18px;">Tambah Massal Peserta</h4>
                    <span style="font-size:13px;color:#64748b;">Daftarkan semua siswa dari satu kelas ke lomba sekaligus.</span>
                </div>
            </div>
        </div>
        <div class="create-card-body">

            @if(session('info'))
                <div class="alert alert-cu" style="background:#eff6ff;color:#1d4ed8;border-left:4px solid #3b82f6;"><i class="fas fa-info-circle me-1"></i> {{ session('info') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-cu alert-danger">
                    <i class="fas fa-exclamation-triangle me-1"></i> Terdapat kesalahan:
                    <ul class="mt-2">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <form action="{{ route('peserta-lomba.massal') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label-cu">Lomba</label>
                    <div class="input-group-cu">
                        <i class="fas fa-trophy input-group-cu-icon"></i>
                        <select name="lomba_id" id="lomba_id" class="form-select @error('lomba_id') is-invalid @enderror">
                            <option value="">-- Pilih Lomba --</option>
                            @foreach($lombas as $l)
                            <option value="{{ $l->id }}" data-kelas-min="{{ $l->kelas_min }}" data-kelas-max="{{ $l->kelas_max }}" {{ old('lomba_id')==$l->id ? 'selected' : '' }}>{{ $l->nama }}</option>
                            @endforeach
                        </select>
                        @error('lomba_id')<div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>
                    <div id="infoAturan" style="display:none;"><i class="fas fa-info-circle me-1"></i> Hanya untuk <span id="infoKelasRange"></span></div>
                </div>

                <div class="row g-2">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label-cu">Jenjang</label>
                            <div class="input-group-cu">
                                <i class="fas fa-layer-group input-group-cu-icon"></i>
                                <select id="jenjang_id" class="form-select">
                                    <option value="">Pilih Jenjang</option>
                                    @foreach($jenjangs as $j)
                                    <option value="{{ $j->id }}">{{ $j->nama_jenjang }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label-cu">Kelas</label>
                            <div class="input-group-cu">
                                <i class="fas fa-door-open input-group-cu-icon"></i>
                                <select id="kelas_id" class="form-select" disabled>
                                    <option value="">Pilih Kelas</option>
                                </select>
                            </div>
                            <div class="loading-indicator" id="loadingKelas"><div class="spinner-border spinner-border-sm" role="status"></div> Memuat data kelas...</div>
                        </div>
                    </div>
                </div>

                <div id="previewContainer" style="display:none;" class="mb-3">
                    <div id="previewSummary" class="alert alert-cu" style="background:#f0fdf4;color:#16a34a;border-left:4px solid #16a34a;margin-bottom:12px;"></div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <label style="font-size:13px;display:flex;align-items:center;gap:6px;cursor:pointer;">
                            <input type="checkbox" id="checkAll" checked> <strong>Pilih Semua</strong>
                        </label>
                        <span id="selectedCount" style="font-size:12px;color:#64748b;"></span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-ms table-bordered table-sm" style="font-size:13px;">
                            <thead><tr><th style="width:40px;"><input type="checkbox" id="checkAllHead" checked></th><th>No</th><th>NISN</th><th>Nama</th></tr></thead>
                            <tbody id="previewBody"></tbody>
                        </table>
                    </div>
                </div>

                <div class="form-actions-cu">
                    <a href="{{ route('peserta-lomba.index') }}" class="btn btn-header-ms btn-simpan-ms btn-compact" style="background:#fef3c7;color:#92400e;"><i class="fas fa-arrow-left"></i> Kembali</a>
                    <button type="submit" class="btn btn-header-ms btn-simpan-ms btn-compact" id="btnSubmit"><i class="fas fa-save"></i> Simpan</button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
$(document).ready(function () {
    var $lomba     = $('#lomba_id');
    var $jenjang   = $('#jenjang_id');
    var $kelas     = $('#kelas_id');
    var $loadKelas = $('#loadingKelas');
    var $infoAturan = $('#infoAturan');
    var $infoRange = $('#infoKelasRange');

    var kelasMin = null, kelasMax = null;
    var kelasData = [];

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

    $lomba.on('change', function () {
        var opt = $(this).find(':selected');
        kelasMin = opt.data('kelas-min') || null;
        kelasMax = opt.data('kelas-max') || null;
        if (kelasMin && kelasMax) {
            $infoRange.text(kelasMin === kelasMax ? 'Kelas ' + kelasMin : 'Kelas ' + kelasMin + ' - ' + kelasMax);
            $infoAturan.show();
        } else {
            $infoAturan.hide();
        }
        $kelas.val('').trigger('change');
        if ($jenjang.val()) $jenjang.trigger('change');
    });

    $jenjang.on('change', function () {
        var id = $(this).val();
        $kelas.prop('disabled', true).html('<option value="">Pilih Kelas</option>');
        if (!id) return;
        $.ajax({
            url: '{{ url("/get-kelas") }}/' + id,
            type: 'GET',
            dataType: 'json',
            beforeSend: function () { $loadKelas.addClass('show'); },
            success: function (data) {
                kelasData = data;
                filterKelas();
            },
            complete: function () { $loadKelas.removeClass('show'); }
        });
    });

    var $previewContainer = $('#previewContainer');
    var $previewSummary   = $('#previewSummary');
    var $previewBody     = $('#previewBody');
    var $btnSubmit       = $('#btnSubmit');
    var $checkAll        = $('#checkAll');
    var $checkAllHead    = $('#checkAllHead');
    var $selectedCount   = $('#selectedCount');
    var $form            = $('form');

    function updateSelectedCount() {
        var checks = $previewBody.find('input.siswa-check');
        if (checks.length === 0) return;
        var total = checks.length;
        var checked = checks.filter(':checked').length;
        $selectedCount.text(checked + ' dari ' + total + ' dipilih');
        $btnSubmit.prop('disabled', checked === 0);
        $checkAll.prop('checked', checked === total);
        $checkAllHead.prop('checked', checked === total);
    }

    $(document).on('change', '#checkAll, #checkAllHead', function () {
        $previewBody.find('input.siswa-check').prop('checked', $(this).prop('checked'));
        updateSelectedCount();
    });

    $(document).on('change', '.siswa-check', updateSelectedCount);

    $form.on('submit', function (e) {
        var checked = $previewBody.find('input.siswa-check:checked');
        if (checked.length === 0) {
            e.preventDefault();
            return false;
        }
        checked.each(function () {
            $form.append('<input type="hidden" name="student_ids[]" value="' + $(this).val() + '">');
        });
    });

    function loadPreview() {
        var lombaId = $lomba.val();
        var kelasId = $kelas.val();
        if (!lombaId || !kelasId) {
            $previewContainer.hide();
            return;
        }
        $.ajax({
            url: '{{ url("/get-siswa") }}/' + kelasId,
            type: 'GET',
            data: { lomba_id: lombaId },
            dataType: 'json',
            beforeSend: function () {
                $previewBody.html('<tr><td colspan="4" class="text-center text-muted py-2"><div class="spinner-border spinner-border-sm me-1"></div> Memuat data...</td></tr>');
                $previewContainer.show();
            },
            success: function (data) {
                var siswaList = Array.isArray(data) ? data : (data.siswa || []);
                var totalSiswa = data.total_siswa || siswaList.length;
                var sudahTerdaftar = data.sudah_terdaftar || 0;
                var eligible = data.eligible || siswaList.length;

                if (eligible === 0) {
                    $previewSummary.html('<i class="fas fa-info-circle me-1"></i> Semua siswa dari kelas ini sudah terdaftar di lomba tersebut.');
                    $previewBody.html('<tr><td colspan="4" class="text-center text-warning py-2">Tidak ada siswa yang perlu ditambahkan.</td></tr>');
                    $btnSubmit.prop('disabled', true);
                } else {
                    $previewSummary.html('<i class="fas fa-check-circle me-1"></i> <strong>' + eligible + '</strong> dari <strong>' + totalSiswa + '</strong> siswa akan ditambahkan' +
                        (sudahTerdaftar > 0 ? ' (<span style="color:#64748b;">' + sudahTerdaftar + ' sudah terdaftar</span>)' : '') +
                        '.');
                    var html = '';
                    $.each(siswaList, function (i, s) {
                        var nama = s.nama || (s.user ? s.user.name : '-');
                        html += '<tr><td><input type="checkbox" class="siswa-check" value="' + s.id + '" checked></td><td>' + (i + 1) + '</td><td>' + s.nisn + '</td><td>' + nama + '</td></tr>';
                    });
                    $previewBody.html(html);
                    $btnSubmit.prop('disabled', false);
                    updateSelectedCount();
                }
            },
            error: function () {
                $previewBody.html('<tr><td colspan="4" class="text-center text-danger py-2">Gagal memuat data.</td></tr>');
            }
        });
    }

    $lomba.on('change', function () {
        if ($kelas.val()) loadPreview();
    });

    $kelas.on('change', function () {
        if ($lomba.val() && $(this).val()) loadPreview();
    });

    var oldLomba = '{{ old("lomba_id") }}';
    if (oldLomba) { $lomba.val(oldLomba); }
});
</script>
@endpush
