@extends('layouts.main')
@section('title', 'Tambah Hasil Lomba')
@push('css')
<style>
    .page-title-content { display: none !important; }
    .create-page {
        font-family: 'Inter', 'Poppins', system-ui, sans-serif;
        max-width: 780px;
        margin: 22px auto 0;
        padding: 0 16px;
    }
    .breadcrumb-cu { margin-bottom: 20px; }
    .breadcrumb-cu .breadcrumb { background: transparent; padding: 0; margin: 0; }
    .breadcrumb-cu .breadcrumb-item { font-size: 13px; }
    .breadcrumb-cu .breadcrumb-item a { color: #64748b; text-decoration: none; }
    .breadcrumb-cu .breadcrumb-item a:hover { color: #16a34a; }
    .breadcrumb-cu .breadcrumb-item.active { color: #1e293b; font-weight: 500; }
    .breadcrumb-cu .breadcrumb-item+.breadcrumb-item::before { color: #cbd5e1; }
    .create-card { border: none; border-radius: 18px; box-shadow: 0 4px 16px rgba(0,0,0,.06), 0 2px 8px rgba(0,0,0,.04); }
    .create-card-header { padding: 24px 28px 20px; border-bottom: 1px solid #f1f5f9; }
    .create-card-body { padding: 24px 28px 28px; }
    .form-label-cu { font-weight: 600; font-size: 14px; color: #374151; margin-bottom: 8px; display: block; }
    .btn-cu { height: 44px; padding: 0 28px; border-radius: 10px; font-size: 14px; font-weight: 600; transition: all .25s; display: inline-flex; align-items: center; justify-content: center; white-space: nowrap; border: none; gap: 8px; }
    .btn-cu-secondary { background: #f1f5f9; color: #475569; border: 1.5px solid #e2e8f0; }
    .btn-cu-secondary:hover { background: #e2e8f0; color: #334155; transform: translateY(-1px); box-shadow: 0 3px 8px rgba(0,0,0,.08); }
    .btn-cu-primary { background: linear-gradient(135deg, #16a34a, #22c55e); color: #fff; box-shadow: 0 2px 8px rgba(22,163,74,.25); }
    .btn-cu-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(22,163,74,.35); color: #fff; }
    .form-actions-cu { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-top: 32px; padding-top: 20px; border-top: 1px solid #f1f5f9; }
    select.form-control-cu { border: 1.5px solid #e2e8f0; border-radius: 10px; height: 46px; font-size: 14px; padding: 0 14px; width: 100%; transition: border .2s, box-shadow .2s; }
    select.form-control-cu:focus { border-color: #16a34a; box-shadow: 0 0 0 3px rgba(22,163,74,.12); outline: none; }
    input.form-control-cu-nilai { border: 1.5px solid #e2e8f0; border-radius: 8px; height: 38px; font-size: 14px; text-align: center; width: 100%; padding: 0 8px; transition: border .2s; }
    input.form-control-cu-nilai:focus { border-color: #16a34a; box-shadow: 0 0 0 3px rgba(22,163,74,.12); outline: none; }

    /* ---- Checkbox styles ---- */
    .ck-wrap { display:flex; align-items:center; justify-content:center; }
    .ck-wrap input[type="checkbox"] {
        width:18px; height:18px; accent-color:#16a34a; cursor:pointer;
        border-radius:4px; border:1.5px solid #cbd5e1; transition:all .2s;
    }
    .ck-wrap input[type="checkbox"]:hover { border-color:#16a34a; }
    .ck-all-wrap { display:flex; align-items:center; justify-content:center; }
    .ck-all-wrap input[type="checkbox"] {
        width:18px; height:18px; accent-color:#16a34a; cursor:pointer;
        border-radius:4px; border:1.5px solid #cbd5e1;
    }
    .peserta-selected-info {
        font-size:12px; color:#64748b; margin-top:10px; padding:8px 12px;
        background:#f8fafc; border-radius:8px; border:1px solid #e2e8f0;
    }
    .peserta-selected-info strong { color:#16a34a; }
    .btn-cu-primary:disabled, .btn-cu-primary[disabled] {
        opacity:.5; cursor:not-allowed; transform:none !important;
        box-shadow:none !important;
    }
    @media (max-width: 768px) {
        .create-page { margin-top: 16px; padding: 0 12px; }
        .create-card-header, .create-card-body { padding: 18px 20px; }
        .form-actions-cu { flex-direction: column; }
        .form-actions-cu .btn-cu { width: 100%; }
    }
</style>
@endpush
@section('content')
<div class="create-page">

    <nav aria-label="breadcrumb" class="breadcrumb-cu">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home me-1"></i>Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('hasil-lomba.index') }}">Hasil Lomba</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah Hasil</li>
        </ol>
    </nav>

    <div class="card create-card">
        <div class="create-card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon" style="width:48px;height:48px;font-size:22px;border-radius:14px;background:linear-gradient(135deg,#16a34a,#22c55e);display:flex;align-items:center;justify-content:center;color:#fff;font-size:22px;flex-shrink:0;">
                    <i class="fas fa-medal"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold" style="color:#1e293b;font-size:18px;">Tambah Hasil Lomba</h4>
                    <span style="font-size:13px;color:#64748b;">Pilih lomba, lalu isi peringkat & juara untuk setiap peserta.</span>
                </div>
            </div>
        </div>

        <div class="create-card-body">

            @if(session('success'))
                <div class="alert alert-success" style="border:none;border-radius:12px;padding:14px 20px;font-size:14px;background:#f0fdf4;color:#16a34a;border-left:4px solid #16a34a;margin-bottom:20px;">
                    <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger" style="border:none;border-radius:12px;padding:14px 20px;font-size:14px;background:#fef2f2;color:#991b1b;border-left:4px solid #dc2626;margin-bottom:20px;">
                    <i class="fas fa-exclamation-triangle me-1"></i> Terdapat kesalahan:
                    <ul class="mt-2 mb-0" style="padding-left:20px;">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <form action="{{ route('hasil-lomba.store') }}" method="POST" id="hasilForm">
                @csrf

                <div class="mb-4">
                    <label class="form-label-cu"><i class="fas fa-trophy me-1" style="color:#16a34a;"></i> Lomba</label>
                    <select id="lomba_id" name="lomba_id" class="form-control-cu" required>
                        <option value="">-- Pilih Lomba --</option>
                        @foreach($lombas as $l)
                        <option value="{{ $l->id }}">{{ $l->nama }}</option>
                        @endforeach
                    </select>
                    @if($lombas->isEmpty())
                    <div style="font-size:13px;color:#94a3b8;margin-top:6px;">
                        <i class="fas fa-info-circle me-1"></i> Tidak ada lomba yang memiliki data penilaian.
                    </div>
                    @endif
                </div>

                <div id="peserta-wrapper" style="display:none;">
                    <div class="mb-3">
                        <label class="form-label-cu"><i class="fas fa-users me-1" style="color:#16a34a;"></i> Peserta</label>
                        <div style="font-size:13px;color:#64748b;margin-bottom:12px;">
                            <i class="fas fa-info-circle me-1"></i> Total nilai diambil otomatis dari penilaian lomba.
                        </div>
                        <div class="table-responsive" style="border:1.5px solid #e2e8f0;border-radius:12px;overflow:hidden;">
                            <table class="table table-bordered mb-0" style="min-width:560px;">
                                <thead style="background:#f8fafc;">
                                    <tr>
                                        <th class="ck-all-wrap" style="width:46px;text-align:center;font-size:13px;padding:10px 4px;">
                                            <input type="checkbox" id="ck-all" title="Pilih semua">
                                        </th>
                                        <th style="width:50px;text-align:center;font-size:13px;padding:10px 8px;">No</th>
                                        <th style="font-size:13px;padding:10px 8px;">Peserta</th>
                                        <th style="width:120px;text-align:center;font-size:13px;padding:10px 8px;">Total Nilai</th>
                                        <th style="width:80px;text-align:center;font-size:13px;padding:10px 8px;">Peringkat</th>
                                        <th style="font-size:13px;padding:10px 8px;">Juara</th>
                                    </tr>
                                </thead>
                                <tbody id="peserta-body">
                                    <tr><td colspan="6" style="text-align:center;padding:20px;color:#94a3b8;font-size:14px;">Pilih lomba terlebih dahulu</td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div id="peserta-error" class="invalid-feedback-cu" style="display:none;margin-top:8px;color:#dc2626;font-size:13px;font-weight:500;">
                            <i class="fas fa-exclamation-circle"></i> <span></span>
                        </div>
                    </div>

                    <div id="peserta-selected-info" class="peserta-selected-info" style="display:none;">
                        <i class="fas fa-check-circle" style="color:#16a34a;"></i>
                        <span id="selected-count">0</span> peserta dipilih
                    </div>

                    <div class="form-actions-cu">
                        <a href="{{ route('hasil-lomba.index') }}" class="btn btn-cu btn-cu-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-cu btn-cu-primary" id="btn-simpan" disabled>
                            <i class="fas fa-save"></i> Simpan (<span id="btn-count">0</span>)
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection
@push('scripts')
<script>
    var lombaSelect = document.getElementById('lomba_id');
    var pesertaWrapper = document.getElementById('peserta-wrapper');
    var pesertaBody = document.getElementById('peserta-body');
    var ckAll = document.getElementById('ck-all');
    var selectedInfo = document.getElementById('peserta-selected-info');
    var selectedCountEl = document.getElementById('selected-count');
    var btnCount = document.getElementById('btn-count');
    var btnSimpan = document.getElementById('btn-simpan');

    var allPeserta = [];

    function renderTable(data) {
        allPeserta = data;
        ckAll.checked = false;
        var html = '';
        data.forEach(function(item, idx) {
            var peringkat = idx + 1;
            html += '<tr data-id="' + item.id + '">' +
                '<td class="ck-wrap" style="text-align:center;vertical-align:middle;padding:10px 4px;">' +
                    '<input type="checkbox" class="ck-peserta" data-id="' + item.id + '" data-text="' + item.text.replace(/"/g, '&quot;') + '" data-nilai="' + item.total_nilai + '">' +
                '</td>' +
                '<td style="text-align:center;vertical-align:middle;padding:10px 8px;font-size:13px;">' + peringkat + '</td>' +
                '<td style="vertical-align:middle;padding:10px 8px;font-size:14px;">' + item.text + '</td>' +
                '<td style="text-align:center;vertical-align:middle;padding:10px 8px;font-size:14px;font-weight:600;">' + item.total_nilai + '</td>' +
                '<td style="text-align:center;vertical-align:middle;padding:10px 8px;font-size:14px;font-weight:700;color:#16a34a;">' + peringkat + '</td>' +
                '<td style="vertical-align:middle;padding:10px 8px;font-size:14px;">Juara ' + peringkat + '</td>' +
            '</tr>';
        });
        pesertaBody.innerHTML = html;
        pesertaWrapper.style.display = 'block';
        updateUI();

        pesertaBody.querySelectorAll('.ck-peserta').forEach(function(ck) {
            ck.addEventListener('change', function() {
                updateUI();
            });
        });
    }

    function updateUI() {
        var checked = pesertaBody.querySelectorAll('.ck-peserta:checked');
        var count = checked.length;

        selectedCountEl.textContent = count;
        btnCount.textContent = count;
        selectedInfo.style.display = count > 0 ? 'block' : 'none';
        btnSimpan.disabled = count === 0;

        var allCks = pesertaBody.querySelectorAll('.ck-peserta');
        ckAll.checked = allCks.length > 0 && count === allCks.length;

        syncHiddenInputs();
    }

    function syncHiddenInputs() {
        pesertaBody.querySelectorAll('input[name="peserta_lomba_id[]"]').forEach(function(el) { el.remove(); });
        pesertaBody.querySelectorAll('.ck-peserta:checked').forEach(function(ck) {
            var hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'peserta_lomba_id[]';
            hidden.value = ck.dataset.id;
            ck.closest('tr').appendChild(hidden);
        });
    }

    ckAll.addEventListener('change', function() {
        var state = this.checked;
        pesertaBody.querySelectorAll('.ck-peserta').forEach(function(ck) { ck.checked = state; });
        updateUI();
    });

    lombaSelect.addEventListener('change', function() {
        var lombaId = this.value;
        pesertaWrapper.style.display = 'none';
        allPeserta = [];
        pesertaBody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:20px;color:#94a3b8;font-size:14px;">Memuat...</td></tr>';

        if (!lombaId) {
            pesertaBody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:20px;color:#94a3b8;font-size:14px;">Pilih lomba terlebih dahulu</td></tr>';
            return;
        }

        fetch('{{ route("hasil-lomba.get-peserta", "") }}/' + lombaId)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (!data || data.length === 0) {
                    pesertaBody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:20px;color:#94a3b8;font-size:14px;">Tidak ada peserta dengan penilaian</td></tr>';
                    return;
                }
                data.sort(function(a, b) { return b.total_nilai - a.total_nilai; });
                renderTable(data);
            })
            .catch(function() {
                pesertaBody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:20px;color:#dc2626;font-size:14px;">Gagal memuat data peserta</td></tr>';
            });
    });
</script>
@endpush