@extends('layouts.main')
@section('title', 'Tambah Aspek Penilaian')
@push('css')
<style>
    .create-aspek-page {
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
    .btn-cu-outline {
        background: transparent;
        color: #16a34a;
        border: 1.5px solid #16a34a;
    }
    .btn-cu-outline:hover {
        background: #f0fdf4;
        transform: translateY(-1px);
    }
    .btn-cu-danger {
        background: transparent;
        color: #ef4444;
        border: 1.5px solid #fca5a5;
        width: 38px;
        height: 38px;
        padding: 0;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all .2s;
    }
    .btn-cu-danger:hover {
        background: #fef2f2;
        border-color: #ef4444;
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
    .aspek-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        background: #f8fafc;
        border-radius: 12px;
        border: 1.5px solid #e2e8f0;
        transition: all .2s;
    }
    .aspek-item:hover {
        border-color: #cbd5e1;
        background: #f1f5f9;
    }
    .aspek-number {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        background: #e2e8f0;
        color: #475569;
        font-size: 13px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .aspek-item .form-control {
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        height: 42px;
        font-size: 14px;
        padding: 0 14px;
        transition: all .2s;
    }
    .aspek-item .form-control:focus {
        border-color: #22c55e;
        box-shadow: 0 0 0 3px rgba(34,197,94,.12);
    }
    .aspek-item .form-control::placeholder {
        color: #94a3b8;
    }
    #addRowBtn {
        height: 38px;
        padding: 0 16px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    @media (max-width: 768px) {
        .create-aspek-page {
            margin-top: 16px;
            padding: 0 12px;
        }
        .create-card-header, .create-card-body {
            padding: 18px 20px;
        }
        .form-actions-cu {
            flex-direction: column;
        }
        .form-actions-cu .btn-cu {
            width: 100%;
        }
        .aspek-item {
            padding: 10px 12px;
        }
    }
</style>
@endpush
@section('content')
<div class="create-aspek-page">

    <nav aria-label="breadcrumb" class="breadcrumb-cu">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/home"><i class="fas fa-home me-1"></i>Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('aspek-penilaian.index') }}">Aspek Penilaian</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Tambah Aspek</li>
        </ol>
    </nav>

    <div class="card create-card">
        <div class="create-card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon" style="width:48px;height:48px;font-size:22px;">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold" style="color: #1e293b; font-size: 18px;">Tambah Aspek Penilaian</h4>
                    <span style="font-size: 13px; color: #64748b;">Buat beberapa aspek penilaian untuk lomba sekaligus.</span>
                </div>
            </div>
        </div>

        <div class="create-card-body">

            @if ($errors->any())
            <div class="alert alert-danger" style="border:none;border-radius:12px;padding:14px 20px;font-size:14px;background:#fef2f2;color:#991b1b;border-left:4px solid #dc2626;margin-bottom:20px;">
                <i class="fas fa-exclamation-triangle me-1"></i> Terdapat kesalahan:
                <ul class="mt-2 mb-0" style="padding-left:20px;">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('aspek-penilaian.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="form-label-cu"><i class="fas fa-trophy me-1" style="color:#16a34a;"></i> Lomba</label>
                    <select name="lomba_id" class="form-control @error('lomba_id') is-invalid @enderror" style="border:1.5px solid #e2e8f0;border-radius:10px;height:44px;font-size:14px;" required>
                        <option value="">-- Pilih Lomba --</option>
                        @foreach($lombas as $l)
                        <option value="{{ $l->id }}" {{ old('lomba_id') == $l->id ? 'selected' : '' }}>{{ $l->nama }}</option>
                        @endforeach
                    </select>
                    @error('lomba_id')
                    <div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex align-items-center justify-content-between mb-3">
                    <label class="form-label-cu mb-0"><i class="fas fa-list me-1" style="color:#16a34a;"></i> Daftar Aspek</label>
                    <button type="button" class="btn btn-cu-outline" id="addRowBtn">
                        <i class="fas fa-plus"></i> Tambah Baris
                    </button>
                </div>

                <div id="aspekRows" style="display:flex;flex-direction:column;gap:10px;">
                    @php $oldCount = old('nama_aspek') ? count(old('nama_aspek')) : 4; @endphp
                    @for($i = 0; $i < max($oldCount, 4); $i++)
                    <div class="aspek-item">
                        <span class="aspek-number">{{ $i + 1 }}</span>
                        <input type="text" name="nama_aspek[]" class="form-control" placeholder="Nama aspek penilaian" value="{{ old('nama_aspek.'.$i) }}" required style="flex:1;">
                        <button type="button" class="btn-cu-danger removeRow" title="Hapus"><i class="fas fa-times"></i></button>
                    </div>
                    @endfor
                </div>
                @error('nama_aspek.*')
                <div class="invalid-feedback-cu" style="margin-top:8px;"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                @enderror

                <div class="form-actions-cu">
                    <a href="{{ route('aspek-penilaian.index') }}" class="btn btn-cu btn-cu-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-cu btn-cu-primary">
                        <i class="fas fa-save"></i> Simpan Semua
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection
@push('scripts')
<script>
    document.getElementById('addRowBtn').addEventListener('click', function() {
        var container = document.getElementById('aspekRows');
        var num = container.children.length + 1;
        var row = document.createElement('div');
        row.className = 'aspek-item';
        row.innerHTML = '<span class="aspek-number">' + num + '</span>' +
            '<input type="text" name="nama_aspek[]" class="form-control" placeholder="Nama aspek penilaian" required style="flex:1;">' +
            '<button type="button" class="btn-cu-danger removeRow" title="Hapus"><i class="fas fa-times"></i></button>';
        container.appendChild(row);
        renumberRows();
        attachRemoveHandler(row.querySelector('.removeRow'));
    });

    function renumberRows() {
        var items = document.querySelectorAll('.aspek-item');
        items.forEach(function(item, idx) {
            item.querySelector('.aspek-number').textContent = idx + 1;
        });
    }

    function attachRemoveHandler(btn) {
        btn.addEventListener('click', function() {
            var rows = document.querySelectorAll('.aspek-item');
            if (rows.length <= 1) return;
            this.closest('.aspek-item').remove();
            renumberRows();
        });
    }

    document.querySelectorAll('.removeRow').forEach(attachRemoveHandler);
</script>
@endpush
