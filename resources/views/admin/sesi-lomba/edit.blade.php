@extends('layouts.main')
@section('title', 'Edit Sesi Lomba')
@push('css')
<style>
    .edit-sesi-page { font-family: 'Inter', 'Poppins', system-ui, sans-serif; max-width: 680px; margin: 22px auto 0; padding: 0 16px; }
    .breadcrumb-cu { margin-bottom: 20px; }
    .breadcrumb-cu .breadcrumb { background: transparent; padding: 0; margin: 0; }
    .breadcrumb-cu .breadcrumb-item { font-size: 13px; }
    .breadcrumb-cu .breadcrumb-item a { color: #64748b; text-decoration: none; transition: color .2s; }
    .breadcrumb-cu .breadcrumb-item a:hover { color: #16a34a; }
    .breadcrumb-cu .breadcrumb-item.active { color: #1e293b; font-weight: 500; }
    .breadcrumb-cu .breadcrumb-item+.breadcrumb-item::before { color: #cbd5e1; }
    .create-card { border: none; border-radius: 18px; box-shadow: 0 4px 16px rgba(0,0,0,.06), 0 2px 8px rgba(0,0,0,.04); }
    .create-card-header { padding: 24px 28px 20px; border-bottom: 1px solid #f1f5f9; }
    .create-card-body { padding: 24px 28px 28px; }
    .form-label-cu { font-weight: 600; font-size: 14px; color: #374151; margin-bottom: 8px; display: block; }
    .invalid-feedback-cu { display: flex; align-items: center; gap: 6px; margin-top: 6px; font-size: 13px; color: #dc2626; font-weight: 500; }
    .input-group-cu .form-control.is-invalid, .input-group-cu .form-select.is-invalid { border-color: #dc2626; background-image: none; }
    .input-group-cu .form-control.is-invalid:focus, .input-group-cu .form-select.is-invalid:focus { box-shadow: 0 0 0 3px rgba(220,38,38,.1); }
    .input-group-cu { position: relative; }
    .input-group-cu .form-control, .input-group-cu .form-select { height: 46px; padding-left: 42px; border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: 14px; transition: border .2s, box-shadow .2s; }
    .input-group-cu .form-control:focus, .input-group-cu .form-select:focus { border-color: #16a34a; box-shadow: 0 0 0 3px rgba(22,163,74,.12); }
    .input-group-cu .form-control[readonly] { background: #f1f5f9; cursor: not-allowed; color: #64748b; }
    .input-group-cu .form-control[readonly]:focus { border-color: #e2e8f0; box-shadow: none; }
    .input-group-cu .form-control::placeholder { color: #94a3b8; font-size: 13px; }
    .input-group-cu-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 15px; z-index: 4; pointer-events: none; }
    textarea.form-control { height: auto; padding-top: 12px; }
    textarea.form-control + .input-group-cu-icon { top: 18px; transform: none; }
    .alert-cu { border: none; border-radius: 12px; padding: 14px 20px; font-size: 14px; margin-bottom: 20px; }
    .alert-cu.alert-danger { background: #fef2f2; color: #991b1b; border-left: 4px solid #dc2626; }
    .alert-cu.alert-danger ul { padding-left: 20px; margin: 0; }
    .alert-cu.alert-danger ul li { list-style: disc; }
    .btn-cu { height: 44px; padding: 0 28px; border-radius: 10px; font-size: 14px; font-weight: 600; transition: all .25s; display: inline-flex; align-items: center; justify-content: center; white-space: nowrap; border: none; gap: 8px; }
    .btn-cu-secondary { background: #f1f5f9; color: #475569; border: 1.5px solid #e2e8f0; }
    .btn-cu-secondary:hover { background: #e2e8f0; color: #334155; transform: translateY(-1px); box-shadow: 0 3px 8px rgba(0,0,0,.08); }
    .btn-cu-primary { background: linear-gradient(135deg, #16a34a, #22c55e); color: #fff; box-shadow: 0 2px 8px rgba(22,163,74,.25); }
    .btn-cu-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(22,163,74,.35); color: #fff; }
    .form-actions-cu { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-top: 32px; padding-top: 20px; border-top: 1px solid #f1f5f9; }
    @media (max-width: 768px) { .edit-sesi-page { margin-top: 16px; padding: 0 12px; } .create-card-header { padding: 18px 20px 16px; } .create-card-body { padding: 18px 20px 22px; } .form-actions-cu { flex-direction: column; } .form-actions-cu .btn-cu { width: 100%; } }
    @media (max-width: 480px) { .create-card-header { padding: 14px 16px 12px; } .create-card-body { padding: 14px 16px 18px; } .input-group-cu .form-control, .input-group-cu .form-select { height: 42px; font-size: 13px; } .btn-cu { height: 40px; padding: 0 20px; font-size: 13px; } }
</style>
@endpush
@section('content')
@include('component.admin.ms-style')
<div class="edit-sesi-page">

    <nav aria-label="breadcrumb" class="breadcrumb-cu">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home me-1"></i>Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('sesi-lomba.index') }}">Sesi Lomba</a></li>
            <li class="breadcrumb-item active">Edit Sesi Lomba</li>
        </ol>
    </nav>

    <div class="card create-card">
        <div class="create-card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon" style="width:48px;height:48px;font-size:22px;"><i class="fas fa-edit"></i></div>
                <div>
                    <h4 class="mb-0 fw-bold" style="color:#1e293b;font-size:18px;">Edit Sesi Lomba</h4>
                    <span style="font-size:13px;color:#64748b;">Ubah jadwal sesi lomba.</span>
                </div>
            </div>
        </div>
        <div class="create-card-body">

            @if ($errors->any())
                <div class="alert alert-cu alert-danger">
                    <i class="fas fa-exclamation-triangle me-1"></i> Terdapat kesalahan pada form:
                    <ul class="mt-2">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <form action="{{ route('sesi-lomba.update', $sesiLomba->id) }}" method="POST">
                @csrf @method('PUT')

                <div class="mb-4">
                    <label class="form-label-cu">Haflatul Imtihan</label>
                    <div class="input-group-cu">
                        <i class="fas fa-calendar-alt input-group-cu-icon"></i>
                        <select name="haflah_id" class="form-select @error('haflah_id') is-invalid @enderror">
                            <option value="">-- Pilih Haflatul Imtihan --</option>
                            @foreach($haflatuls as $h)
                            <option value="{{ $h->id }}" {{ old('haflah_id', $sesiLomba->haflah_id)==$h->id ? 'selected' : '' }}>{{ $h->nama_acara }}</option>
                            @endforeach
                        </select>
                        @error('haflah_id')<div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label-cu">Nama Sesi</label>
                    <div class="input-group-cu">
                        <i class="fas fa-tag input-group-cu-icon"></i>
                        <select name="nama" id="nama_sesi" class="form-select @error('nama') is-invalid @enderror">
                            <option value="">-- Pilih Nama Sesi --</option>
                            @foreach($sesis as $s)
                            <option value="{{ $s->nama }}"
                                data-tanggal="{{ $s->tanggal }}"
                                data-jam-mulai="{{ $s->jam_mulai }}"
                                data-jam-selesai="{{ $s->jam_selesai }}"
                                {{ old('nama', $sesiLomba->nama)==$s->nama ? 'selected' : '' }}>
                                {{ $s->nama }}@if($s->jam_mulai || $s->jam_selesai) ({{ $s->jam_mulai ? \Carbon\Carbon::parse($s->jam_mulai)->format('H:i') : '??' }}-{{ $s->jam_selesai ? \Carbon\Carbon::parse($s->jam_selesai)->format('H:i') : '??' }})@endif
                            </option>
                            @endforeach
                        </select>
                        @error('nama')<div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>
                </div>

                <div id="detail-sesi" class="row g-4" style="display:none;">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="form-label-cu">Tanggal</label>
                            <div class="input-group-cu">
                                <i class="fas fa-calendar-day input-group-cu-icon"></i>
                                <input type="date" name="tanggal" id="input_tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', $sesiLomba->tanggal) }}" readonly onfocus="this.blur()">
                                @error('tanggal')<div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-4">
                            <label class="form-label-cu">Jam Mulai</label>
                            <div class="input-group-cu">
                                <i class="fas fa-play input-group-cu-icon"></i>
                                <input type="time" name="jam_mulai" id="jam_mulai" class="form-control @error('jam_mulai') is-invalid @enderror" value="{{ old('jam_mulai', $sesiLomba->jam_mulai) }}" readonly onfocus="this.blur()">
                                @error('jam_mulai')<div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-4">
                            <label class="form-label-cu">Jam Selesai</label>
                            <div class="input-group-cu">
                                <i class="fas fa-stop input-group-cu-icon"></i>
                                <input type="time" name="jam_selesai" id="jam_selesai" class="form-control @error('jam_selesai') is-invalid @enderror" value="{{ old('jam_selesai', $sesiLomba->jam_selesai) }}" readonly onfocus="this.blur()">
                                @error('jam_selesai')<div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label-cu">Keterangan</label>
                    <div class="input-group-cu">
                        <i class="fas fa-align-left input-group-cu-icon" style="top:18px;transform:none;"></i>
                        <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="4" placeholder="Masukkan keterangan (opsional)" style="padding-left:42px;padding-top:12px;height:auto;">{{ old('keterangan', $sesiLomba->keterangan) }}</textarea>
                        @error('keterangan')<div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-actions-cu">
                    <a href="{{ route('sesi-lomba.index') }}" class="btn btn-cu btn-cu-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                    <button type="submit" class="btn btn-cu btn-cu-primary"><i class="fas fa-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $('#nama_sesi').on('change', function() {
        var opt = $(this).find(':selected');
        var val = opt.val();
        if (!val) {
            $('#detail-sesi').hide();
            $('#input_tanggal, #jam_mulai, #jam_selesai').prop('readonly', false).val('');
            return;
        }
        var tanggal = opt.data('tanggal');
        var jamMulai = opt.data('jam-mulai');
        var jamSelesai = opt.data('jam-selesai');
        if (tanggal) $('#input_tanggal').val(tanggal);
        if (jamMulai) $('#jam_mulai').val(jamMulai);
        if (jamSelesai) $('#jam_selesai').val(jamSelesai);
        $('#input_tanggal, #jam_mulai, #jam_selesai').prop('readonly', true);
        $('#detail-sesi').show();
    });
    $(function() {
        var sel = $('#nama_sesi').find(':selected');
        if (sel.val()) $('#nama_sesi').trigger('change');
    });
</script>
@endpush

