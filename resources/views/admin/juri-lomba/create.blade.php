@extends('layouts.main')
@section('title', 'Tambah Juri Lomba')
@push('css')
<style>
    .create-juri-page {
        font-family: 'Inter', 'Poppins', system-ui, sans-serif;
        max-width: 680px;
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
    .create-card {
        border: none;
        border-radius: 18px;
        box-shadow: 0 4px 16px rgba(0,0,0,.06), 0 2px 8px rgba(0,0,0,.04);
    }
    .create-card-header {
        padding: 24px 28px 20px;
        border-bottom: 1px solid #f1f5f9;
    }
    .create-card-body {
        padding: 24px 28px 28px;
    }
    .form-label-cu {
        font-weight: 600;
        font-size: 14px;
        color: #374151;
        margin-bottom: 8px;
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
    .input-group-cu .form-control::placeholder {
        color: #94a3b8;
        font-size: 13px;
    }
    .input-group-cu-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 15px;
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
    .btn-cu:active {
        transform: translateY(0);
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
        .create-juri-page {
            margin-top: 16px;
            padding: 0 12px;
        }
        .create-card-header {
            padding: 18px 20px 16px;
        }
        .create-card-body {
            padding: 18px 20px 22px;
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
            padding: 14px 16px 12px;
        }
        .create-card-body {
            padding: 14px 16px 18px;
        }
        .input-group-cu .form-control,
        .input-group-cu .form-select {
            height: 42px;
            font-size: 13px;
        }
        .btn-cu {
            height: 40px;
            padding: 0 20px;
            font-size: 13px;
        }
    }
</style>
@endpush
@section('content')
@include('component.admin.ms-style')
<div class="create-juri-page">

    <nav aria-label="breadcrumb" class="breadcrumb-cu">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/home"><i class="fas fa-home me-1"></i>Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('juri-lomba.index') }}">Juri Lomba</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Tambah Juri Lomba</li>
        </ol>
    </nav>

    <div class="card create-card">
        <div class="create-card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon" style="width:48px;height:48px;font-size:22px;">
                    <i class="fas fa-gavel"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold" style="color: #1e293b; font-size: 18px;">Tambah Juri Lomba</h4>
                    <span style="font-size: 13px; color: #64748b;">Tetapkan juri untuk lomba.</span>
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

            <form action="{{ route('juri-lomba.store') }}" method="POST">
                @csrf

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="form-label-cu">Lomba</label>
                            <div class="input-group-cu">
                                <i class="fas fa-trophy input-group-cu-icon"></i>
                                <select name="lomba_id" class="form-select @error('lomba_id') is-invalid @enderror">
                                    <option value="">-- Pilih Lomba --</option>
                                    @foreach($lombas as $l)
                                    <option value="{{ $l->id }}" {{ old('lomba_id')==$l->id ? 'selected' : '' }}>{{ $l->nama }}</option>
                                    @endforeach
                                </select>
                                @error('lomba_id')
                                <div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="form-label-cu">Nama Juri</label>
                            <div class="input-group-cu" style="display:flex;gap:8px;">
                                <div style="flex:1;">
                                    <i class="fas fa-user-tie input-group-cu-icon"></i>
                                    <select id="guru_select" class="form-select @error('guru_id') is-invalid @enderror" style="height:46px;padding-left:42px;border-radius:10px;border:1.5px solid #e2e8f0;font-size:14px;width:100%;">
                                        <option value="">-- Pilih Guru --</option>
                                        @foreach($gurus as $g)
                                        <option value="{{ $g->id }}" data-nama="{{ $g->nama }}">{{ $g->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="button" id="btnTambahJuri" class="btn btn-cu btn-cu-primary" style="height:46px;padding:0 18px;white-space:nowrap;">
                                    <i class="fas fa-plus"></i> Tambah
                                </button>
                            </div>
                            @error('guru_id')
                            <div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror

                            <div id="juri-list" style="margin-top:12px;"></div>
                            <div id="juri-empty" style="margin-top:12px;padding:12px 16px;background:#f8fafc;border:1.5px dashed #e2e8f0;border-radius:10px;font-size:13px;color:#94a3b8;text-align:center;">
                                <i class="fas fa-info-circle me-1"></i> Belum ada juri yang dipilih.
                            </div>
                            <div id="juri-error" style="display:none;margin-top:6px;" class="invalid-feedback-cu">
                                <i class="fas fa-exclamation-circle"></i> <span>Pilih minimal satu guru.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions-cu">
                    <a href="{{ route('juri-lomba.index') }}" class="btn btn-cu btn-cu-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-cu btn-cu-primary">
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
(function() {
    var guruSelect = document.getElementById('guru_select');
    var btnTambah  = document.getElementById('btnTambahJuri');
    var juriList   = document.getElementById('juri-list');
    var juriEmpty  = document.getElementById('juri-empty');
    var juriError  = document.getElementById('juri-error');
    var selected   = {};
    var counter    = 0;

    function renderList() {
        var ids = Object.keys(selected);
        if (ids.length === 0) {
            juriList.innerHTML = '';
            juriEmpty.style.display = 'block';
            return;
        }
        juriEmpty.style.display = 'none';
        var html = '<div style="border:1.5px solid #e2e8f0;border-radius:12px;overflow:hidden;">';
        html += '<table class="table table-bordered mb-0" style="margin:0;font-size:13px;">';
        html += '<thead style="background:#f8fafc;"><tr><th style="width:40px;text-align:center;padding:8px 6px;">No</th><th style="padding:8px 10px;">Nama Guru</th><th style="width:50px;text-align:center;padding:8px 6px;"></th></tr></thead><tbody>';
        ids.forEach(function(id, idx) {
            html += '<tr>';
            html += '<td style="text-align:center;vertical-align:middle;padding:8px 6px;">' + (idx + 1) + '</td>';
            html += '<td style="vertical-align:middle;padding:8px 10px;">' + selected[id] + '</td>';
            html += '<td style="text-align:center;vertical-align:middle;padding:6px;">';
            html += '<button type="button" class="btn btn-sm btn-outline-danger" onclick="window.__removeJuri(' + id + ')" title="Hapus" style="padding:2px 8px;font-size:11px;"><i class="fas fa-times"></i></button>';
            html += '</td></tr>';
        });
        html += '</tbody></table></div>';
        juriList.innerHTML = html;
        syncInputs();
    }

    function syncInputs() {
        var old = document.querySelectorAll('input[name="guru_id[]"]');
        old.forEach(function(el) { el.remove(); });
        Object.keys(selected).forEach(function(id) {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'guru_id[]';
            input.value = id;
            juriList.appendChild(input);
        });
    }

    window.__removeJuri = function(id) {
        delete selected[id];
        renderList();
    };

    btnTambah.addEventListener('click', function() {
        var opt = guruSelect.options[guruSelect.selectedIndex];
        if (!opt || !opt.value) return;
        var id = opt.value;
        var nama = opt.getAttribute('data-nama') || opt.text;
        if (selected[id]) {
            guruSelect.selectedIndex = 0;
            return;
        }
        selected[id] = nama;
        guruSelect.selectedIndex = 0;
        renderList();
    });

    var form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (Object.keys(selected).length === 0) {
                e.preventDefault();
                juriError.style.display = 'flex';
                return;
            }
            juriError.style.display = 'none';
            syncInputs();
        });
    }
})();
</script>
@endpush
