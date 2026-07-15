@extends('layouts.main')
@section('title', 'Edit Anggota Kelompok')
@push('css')
<style>
    .edit-anggota-page {
        font-family: 'Inter', 'Poppins', system-ui, sans-serif;
        max-width: 720px;
        margin: 22px auto 0;
        padding: 0 16px;
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
    .edit-card {
        border: none;
        border-radius: 18px;
        box-shadow: 0 4px 16px rgba(0,0,0,.06), 0 2px 8px rgba(0,0,0,.04);
    }
    .edit-card-header {
        padding: 24px 28px 20px;
        border-bottom: 1px solid #f1f5f9;
    }
    .edit-card-body {
        padding: 24px 28px 28px;
    }
    .form-label-cu {
        font-weight: 600;
        font-size: 14px;
        color: #374151;
        margin-bottom: 8px;
        display: block;
    }
    .input-group-cu {
        position: relative;
    }
    .input-group-cu .form-control,
    .input-group-cu .form-select {
        height: 46px;
        padding-left: 42px;
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        font-size: 14px;
        transition: border .2s, box-shadow .2s;
    }
    .input-group-cu .form-control:focus,
    .input-group-cu .form-select:focus {
        border-color: #16a34a;
        box-shadow: 0 0 0 3px rgba(22,163,74,.12);
    }
    .input-group-cu-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        z-index: 4;
        pointer-events: none;
    }
    .select2-multi-cu {
        padding-left: 42px !important;
    }
    .select2-multi-cu + .select2-container .select2-selection--multiple {
        min-height: 46px;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        padding: 4px 8px 4px 36px;
        font-size: 14px;
    }
    .select2-multi-cu + .select2-container .select2-selection--multiple .select2-selection__choice {
        background: #16a34a;
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 2px 8px;
        font-size: 13px;
        margin: 3px 4px 3px 0;
    }
    .select2-multi-cu + .select2-container .select2-selection--multiple .select2-selection__choice__remove {
        color: rgba(255,255,255,.7);
        margin-right: 4px;
    }
    .select2-multi-cu + .select2-container .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: #fff;
    }
    .btn-cu {
        height: 44px;
        padding: 0 28px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        transition: all .25s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
        border: none;
        gap: 8px;
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
    .form-actions-cu {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-top: 32px;
        padding-top: 20px;
        border-top: 1px solid #f1f5f9;
    }
    @media (max-width: 768px) {
        .edit-anggota-page {
            margin-top: 16px;
            padding: 0 12px;
        }
        .edit-card-header {
            padding: 18px 20px 16px;
        }
        .edit-card-body {
            padding: 18px 20px 22px;
        }
        .form-actions-cu {
            flex-direction: column;
        }
        .form-actions-cu .btn-cu {
            width: 100%;
        }
    }
</style>
@endpush
@section('content')
@include('component.admin.ms-style')
<div class="edit-anggota-page">

    <nav aria-label="breadcrumb" class="breadcrumb-cu">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/home"><i class="fas fa-home me-1"></i>Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('anggota-kelompok.index') }}">Anggota Kelompok</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Edit Anggota Kelompok</li>
        </ol>
    </nav>

    <div class="card edit-card">
        <div class="edit-card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon" style="width:48px;height:48px;font-size:22px;">
                    <i class="fas fa-users-cog"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold" style="color: #1e293b; font-size: 18px;">Edit Anggota Kelompok</h4>
                    <span style="font-size: 13px; color: #64748b;">Kelola anggota kelompok lomba (minimal 2 siswa).</span>
                </div>
            </div>
        </div>

        <div class="edit-card-body">

            @if ($errors->any())
                <div class="alert alert-modern-ms alert-danger">
                    <i class="fas fa-exclamation-triangle me-1"></i> Terdapat kesalahan pada form:
                    <ul class="mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('anggota-kelompok.update', $anggotaKelompok->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="form-label-cu">Kelompok Lomba</label>
                            <div class="input-group-cu">
                                <i class="fas fa-users input-group-cu-icon"></i>
                                <select name="kelompok_lomba_id" id="kelompok_lomba_id" class="form-select @error('kelompok_lomba_id') is-invalid @enderror">
                                    @foreach($kelompokLombas as $kl)
                                    <option value="{{ $kl->id }}" data-kelas-min="{{ $kl->lomba->kelas_min ?? '' }}" data-kelas-max="{{ $kl->lomba->kelas_max ?? '' }}" {{ old('kelompok_lomba_id', $anggotaKelompok->kelompok_lomba_id) == $kl->id ? 'selected' : '' }}>{{ $kl->nama_kelompok }}</option>
                                    @endforeach
                                </select>
                                <div id="infoAturan" style="display:none;margin-top:8px;font-size:13px;color:#c2410c;"><i class="fas fa-info-circle me-1"></i> Lomba ini hanya untuk <strong><span id="infoKelasRange"></span></strong></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="form-label-cu">Siswa <small class="text-muted">(minimal 2)</small></label>
                            <div class="input-group-cu">
                                <i class="fas fa-user-graduate input-group-cu-icon" style="z-index:5;"></i>
                                <select name="student_ids[]" class="form-select select2-multi-cu @error('student_ids') is-invalid @enderror" multiple disabled></select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions-cu">
                    <a href="{{ route('anggota-kelompok.index') }}" class="btn btn-cu btn-cu-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-cu btn-cu-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
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
    var $kelompok = $('#kelompok_lomba_id');
    var $infoAturan = $('#infoAturan');
    var $infoRange = $('#infoKelasRange');
    var oldStudentIds = @json(old('student_ids', $currentMemberIds));
    var oldKelompokId = @json(old('kelompok_lomba_id', $anggotaKelompok->kelompok_lomba_id));
    var $siswa = $('.select2-multi-cu');

    $siswa.select2({
        placeholder: 'Cari dan pilih siswa...',
        width: '100%'
    });

    function escapeHtml(text) {
        return String(text)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function setStudentOptions(items) {
        if ($siswa.hasClass('select2-hidden-accessible')) {
            $siswa.select2('destroy');
        }

        var html = '';
        if (!items.length) {
            html = '<option value="">Tidak ada siswa sesuai aturan lomba</option>';
        } else {
            $.each(items, function (i, item) {
                var selected = oldStudentIds.map(String).indexOf(String(item.id)) !== -1 ? 'selected' : '';
                html += '<option value="' + item.id + '" ' + selected + '>' + escapeHtml(item.text) + '</option>';
            });
        }

        $siswa.html(html).prop('disabled', false).trigger('change');
        $siswa.select2({
            placeholder: 'Cari dan pilih siswa...',
            width: '100%'
        });
    }

    function resetStudents(placeholder) {
        if ($siswa.hasClass('select2-hidden-accessible')) {
            $siswa.select2('destroy');
        }

        $siswa.html('').prop('disabled', true).trigger('change');
        $siswa.select2({
            placeholder: placeholder || 'Pilih kelompok terlebih dahulu...',
            width: '100%'
        });
    }

    function loadStudents(kelompokId) {
        if (!kelompokId) {
            resetStudents('Pilih kelompok terlebih dahulu...');
            return;
        }

        $.ajax({
            url: '{{ url("/anggota-kelompok/get-siswa") }}/' + kelompokId,
            type: 'GET',
            dataType: 'json',
            data: { selected_ids: oldStudentIds },
            success: function (response) {
                if (response.kelas_min && response.kelas_max) {
                    $infoRange.text(response.kelas_min == response.kelas_max ? 'Kelas ' + response.kelas_min : 'Kelas ' + response.kelas_min + ' - ' + response.kelas_max);
                    $infoAturan.show();
                } else {
                    $infoAturan.hide();
                }

                setStudentOptions(response.students || []);
            }
        });
    }

    $kelompok.on('change', function () {
        var opt = $(this).find(':selected');
        var min = opt.data('kelas-min');
        var max = opt.data('kelas-max');
        if (min && max) {
            $infoRange.text(min === max ? 'Kelas ' + min : 'Kelas ' + min + ' - ' + max);
            $infoAturan.show();
        } else {
            $infoAturan.hide();
        }

        loadStudents($(this).val());
    });

    if (oldKelompokId) {
        $kelompok.val(oldKelompokId);
    }
    $kelompok.trigger('change');
});
</script>
@endpush
