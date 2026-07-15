@extends('layouts.main')
@section('title', 'Tambah Penilaian Lomba')

@push('css')
<style>
    .create-penilaian-page {
        font-family: 'Inter', 'Poppins', system-ui, sans-serif;
        max-width: 640px;
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
    .create-card-header { padding: 20px 24px 16px; border-bottom: 1px solid #f1f5f9; }
    .create-card-body { padding: 20px 24px 24px; }
    .form-row-cu { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .form-field-cu { margin-bottom: 16px; }
    .form-label-cu { font-weight: 600; font-size: 13px; color: #374151; margin-bottom: 6px; display: block; }
    .invalid-feedback-cu { display: flex; align-items: center; gap: 6px; margin-top: 4px; font-size: 12px; color: #dc2626; font-weight: 500; }
    .btn-cu { height: 38px; padding: 0 22px; border-radius: 10px; font-size: 13px; font-weight: 600; transition: all .25s; display: inline-flex; align-items: center; justify-content: center; white-space: nowrap; border: none; gap: 6px; }
    .btn-cu-secondary { background: #f1f5f9; color: #475569; border: 1.5px solid #e2e8f0; }
    .btn-cu-secondary:hover { background: #e2e8f0; color: #334155; transform: translateY(-1px); box-shadow: 0 3px 8px rgba(0,0,0,.08); }
    .btn-cu-primary { background: linear-gradient(135deg, #16a34a, #22c55e); color: #fff; box-shadow: 0 2px 8px rgba(22,163,74,.25); }
    .btn-cu-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(22,163,74,.35); color: #fff; }
    .form-actions-cu { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-top: 20px; padding-top: 16px; border-top: 1px solid #f1f5f9; }
    select.form-control-cu, input.form-control-cu { border: 1.5px solid #e2e8f0; border-radius: 10px; height: 40px; font-size: 13px; padding: 0 12px; width: 100%; transition: border .2s, box-shadow .2s; }
    select.form-control-cu:focus, input.form-control-cu:focus { border-color: #16a34a; box-shadow: 0 0 0 3px rgba(22,163,74,.12); outline: none; }
    .empty-peserta-alert {
        background: #fffbeb; border: 1.5px solid #fde68a; border-radius: 10px;
        padding: 10px 14px; font-size: 12px; color: #92400e; display: flex; align-items: center; gap: 8px;
    }
    #aspek-table-wrapper .table { font-size: 13px; }
    #aspek-table-wrapper .table th { font-size: 12px; padding: 8px 6px; }
    #aspek-table-wrapper .table td { padding: 6px; }
    @media (max-width: 768px) {
        .create-penilaian-page { margin-top: 16px; padding: 0 12px; }
        .create-card-header, .create-card-body { padding: 18px 16px; }
        .form-row-cu { grid-template-columns: 1fr; gap: 0; }
        .form-actions-cu { flex-direction: column; }
        .form-actions-cu .btn-cu { width: 100%; }
    }
</style>
@endpush

@section('content')
<div class="create-penilaian-page">

    <nav aria-label="breadcrumb" class="breadcrumb-cu">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home me-1"></i>Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('penilaian-lomba.index') }}">Penilaian Lomba</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah Penilaian</li>
        </ol>
    </nav>

    <div class="card create-card">
        <div class="create-card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon" style="width:48px;height:48px;font-size:22px;">
                    <i class="fas fa-star"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold" style="color: #1e293b; font-size: 18px;">Tambah Penilaian Lomba</h4>
                    <span style="font-size: 13px; color: #64748b;">Pilih sesi, lomba, peserta, juri, lalu input nilai.</span>
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

            @if(empty($mode))
            <div class="row g-3" style="margin-bottom:20px;">
                <div class="col-md-6">
                    <a href="{{ route('penilaian-lomba.create', ['mode' => 'individu']) }}" class="text-decoration-none">
                        <div class="card h-100" style="border:1.5px solid #e2e8f0;border-radius:18px;box-shadow:0 4px 16px rgba(0,0,0,.05);">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="header-icon" style="width:52px;height:52px;font-size:22px;"><i class="fas fa-user-graduate"></i></div>
                                    <div>
                                        <div class="fw-bold" style="color:#1e293b;font-size:18px;">Penilaian Individu</div>
                                        <div style="color:#64748b;font-size:13px;">Untuk lomba per peserta.</div>
                                    </div>
                                </div>
                                <div style="color:#475569;font-size:14px;">Pilih juri, peserta, lalu isi aspek nilai.</div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('penilaian-lomba.create', ['mode' => 'tim']) }}" class="text-decoration-none">
                        <div class="card h-100" style="border:1.5px solid #e2e8f0;border-radius:18px;box-shadow:0 4px 16px rgba(0,0,0,.05);">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="header-icon" style="width:52px;height:52px;font-size:22px;"><i class="fas fa-users"></i></div>
                                    <div>
                                        <div class="fw-bold" style="color:#1e293b;font-size:18px;">Penilaian Tim</div>
                                        <div style="color:#64748b;font-size:13px;">Untuk lomba kelompok.</div>
                                    </div>
                                </div>
                                <div style="color:#475569;font-size:14px;">Pilih juri, kelompok, lalu isi aspek nilai.</div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            @else

            <form action="{{ route('penilaian-lomba.store') }}" method="POST" id="penilaianForm">
                @csrf

                <div class="form-row-cu">
                    {{-- Step 1: Sesi --}}
                    <div class="form-field-cu">
                        <label class="form-label-cu"><i class="fas fa-calendar-day me-1" style="color:#16a34a;"></i> Sesi Lomba</label>
                        <select id="sesi_lomba_id" class="form-control-cu" required>
                            <option value="">-- Pilih Sesi --</option>
                            @foreach($sesiLombas as $s)
                            <option value="{{ $s->id }}">{{ $s->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Step 2: Lomba --}}
                    <div class="form-field-cu">
                        <label class="form-label-cu"><i class="fas fa-trophy me-1" style="color:#16a34a;"></i> Lomba</label>
                        <select id="lomba_id" class="form-control-cu" required disabled>
                            <option value="">-- Pilih Sesi Dulu --</option>
                        </select>
                        <input type="hidden" name="lomba_id" id="lomba_id_hidden" value="">
                        <input type="hidden" id="juri_lomba_id_tim_hidden" value="">
                        <div id="jenis-info" style="display:none;margin-top:4px;font-size:12px;">
                            <i class="fas fa-tag me-1"></i> Jenis: <strong id="jenis-badge"></strong>
                        </div>
                    </div>
                </div>

                @if($mode === 'individu')
                <div class="form-row-cu">
                    {{-- Step 3: Juri --}}
                    <div class="form-field-cu" id="section-juri-individu">
                        <label class="form-label-cu"><i class="fas fa-gavel me-1" style="color:#16a34a;"></i> Juri</label>
                        <select name="juri_lomba_id" id="juri_lomba_id" class="form-control-cu @error('juri_lomba_id') is-invalid @enderror" disabled>
                            <option value="">-- Pilih Lomba Dulu --</option>
                        </select>
                        @error('juri_lomba_id')<div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    {{-- Step 4: Peserta --}}
                    <div class="form-field-cu" id="section-individu" style="display:none;">
                        <label class="form-label-cu"><i class="fas fa-user-graduate me-1" style="color:#16a34a;"></i> Peserta</label>
                        <select name="peserta_lomba_id" id="peserta_lomba_id" class="form-control-cu @error('peserta_lomba_id') is-invalid @enderror" disabled>
                            <option value="">-- Pilih Juri Dulu --</option>
                        </select>
                        <div id="peserta-empty-alert" style="display:none;margin-top:4px;">
                            <div class="empty-peserta-alert">
                                <i class="fas fa-circle-info"></i> Semua peserta sudah memiliki nilai.
                            </div>
                        </div>
                        @error('peserta_lomba_id')<div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>
                </div>
                @else
                <div class="form-row-cu" id="section-tim" style="display:none;">
                    <div class="form-field-cu" id="section-juri-tim">
                        <label class="form-label-cu"><i class="fas fa-gavel me-1" style="color:#16a34a;"></i> Juri</label>
                        <select name="juri_lomba_id" id="juri_lomba_id_tim_display" class="form-control-cu @error('juri_lomba_id') is-invalid @enderror" disabled>
                            <option value="">-- Pilih Lomba Dulu --</option>
                        </select>
                        @error('juri_lomba_id')<div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    <div class="form-field-cu">
                        <label class="form-label-cu"><i class="fas fa-users me-1" style="color:#16a34a;"></i> Nama Kelompok</label>
                        <select name="kelompok_lomba_id" id="kelompok_lomba_id" class="form-control-cu @error('kelompok_lomba_id') is-invalid @enderror" disabled>
                            <option value="">-- Pilih Lomba Dulu --</option>
                        </select>
                        @error('kelompok_lomba_id')<div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>
                </div>
                @endif

                {{-- Step 4: Aspek + Nilai --}}
                <div id="aspek-table-wrapper" style="display:none;">
                    <div class="form-field-cu">
                        <label class="form-label-cu"><i class="fas fa-clipboard-list me-1" style="color:#16a34a;"></i> Aspek Penilaian</label>
                        <div style="font-size:12px;color:#64748b;margin-bottom:8px;">
                            <i class="fas fa-info-circle me-1"></i> Masukkan nilai untuk setiap aspek (0 - 100).
                        </div>
                        <div class="table-responsive" style="border:1.5px solid #e2e8f0;border-radius:12px;overflow:hidden;">
                            <table class="table table-bordered mb-0" style="min-width:420px;">
                                <thead style="background:#f8fafc;">
                                    <tr>
                                        <th style="width:40px;text-align:center;font-size:12px;padding:8px 6px;">No</th>
                                        <th style="font-size:12px;padding:8px 6px;">Aspek</th>
                                        <th style="width:100px;text-align:center;font-size:12px;padding:8px 6px;">Nilai</th>
                                    </tr>
                                </thead>
                                <tbody id="aspek-table-body">
                                    <tr><td colspan="3" style="text-align:center;padding:16px;color:#94a3b8;font-size:13px;">Pilih lomba terlebih dahulu</td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div id="aspek-error" class="invalid-feedback-cu" style="display:none;margin-top:4px;">
                            <i class="fas fa-exclamation-circle"></i> <span></span>
                        </div>
                    </div>
                </div>

                <div class="form-actions-cu">
                    <a href="{{ route('penilaian-lomba.index') }}" class="btn btn-cu btn-cu-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-cu btn-cu-primary" id="submitBtn" disabled>
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>

            </form>
            @endif

        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function stubNode() {
        return {
            style: { display: 'none' },
            disabled: true,
            value: '',
            innerHTML: '',
            addEventListener: function() {},
            dispatchEvent: function() {}
        };
    }

    var sesiSelect      = document.getElementById('sesi_lomba_id') || stubNode();
    var lombaSelect     = document.getElementById('lomba_id') || stubNode();
    var lombaHidden     = document.getElementById('lomba_id_hidden') || stubNode();
    var juriSelect      = document.getElementById('juri_lomba_id') || stubNode();
    var teamJuriSelect  = document.getElementById('juri_lomba_id_tim_display') || stubNode();
    var teamJuriHidden  = document.getElementById('juri_lomba_id_tim_hidden') || stubNode();
    var pesertaSelect   = document.getElementById('peserta_lomba_id') || stubNode();
    var kelompokSelect  = document.getElementById('kelompok_lomba_id') || stubNode();
    var aspekWrapper    = document.getElementById('aspek-table-wrapper') || stubNode();
    var aspekBody       = document.getElementById('aspek-table-body') || stubNode();
    var submitBtn       = document.getElementById('submitBtn') || stubNode();
    var emptyAlert      = document.getElementById('peserta-empty-alert') || stubNode();
    var jenisInfo       = document.getElementById('jenis-info') || stubNode();
    var jenisBadge      = document.getElementById('jenis-badge') || stubNode();
    var juriIndividuBox = document.getElementById('section-juri-individu') || stubNode();
    var juriTimBox      = document.getElementById('section-juri-tim') || stubNode();
    var sectionIndividu = document.getElementById('section-individu') || stubNode();
    var sectionTim      = document.getElementById('section-tim') || stubNode();
    var lombaDataMap    = {};
    var currentJenis    = '';

    function resetSelect(el, placeholder) {
        el.innerHTML = '<option value="">' + placeholder + '</option>';
        el.disabled = true;
    }

    function loadSelect(url, selectEl, placeholder, dataMap, onLoaded) {
        selectEl.innerHTML = '<option value="">Memuat...</option>';
        selectEl.disabled = true;
        fetch(url)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                selectEl.innerHTML = '<option value="">' + placeholder + '</option>';
                data.forEach(function(item) {
                    var opt = document.createElement('option');
                    opt.value = item.id;
                    opt.textContent = item.text;
                    selectEl.appendChild(opt);
                    if (dataMap) dataMap[item.id] = item;
                });
                selectEl.disabled = false;
                if (typeof onLoaded === 'function') {
                    onLoaded(data);
                }
            })
            .catch(function() {
                selectEl.innerHTML = '<option value="">Gagal memuat data</option>';
                selectEl.disabled = false;
                if (typeof onLoaded === 'function') {
                    onLoaded([]);
                }
            });
    }

    function renderAspekTable(data) {
        if (!data || data.length === 0) {
            aspekBody.innerHTML = '<tr><td colspan="3" style="text-align:center;padding:16px;color:#94a3b8;font-size:13px;">Belum ada aspek penilaian untuk lomba ini</td></tr>';
            aspekWrapper.style.display = 'block';
            return;
        }
        var html = '';
        data.forEach(function(item, idx) {
            html += '<tr>' +
                '<td style="text-align:center;vertical-align:middle;padding:6px;font-size:12px;">' + (idx + 1) + '</td>' +
                '<td style="vertical-align:middle;padding:6px;font-size:13px;">' + item.nama_aspek + '</td>' +
                '<td style="vertical-align:middle;padding:4px 6px;">' +
                    '<input type="number" step="0.01" name="nilai[]" class="form-control form-control-sm aspek-nilai" ' +
                    'style="border:1.5px solid #e2e8f0;border-radius:8px;height:34px;font-size:13px;text-align:center;width:100%;" ' +
                    'placeholder="0" min="0" max="100">' +
                    '<input type="hidden" name="aspek_penilaian_id[]" value="' + item.id + '">' +
                '</td>' +
            '</tr>';
        });
        aspekBody.innerHTML = html;
        aspekWrapper.style.display = 'block';
    }

    function loadPeserta(lombaId, juriId) {
        pesertaSelect.innerHTML = '<option value="">Memuat...</option>';
        pesertaSelect.disabled = true;
        emptyAlert.style.display = 'none';

        var url = '{{ url("/penilaian-lomba/get-peserta") }}/' + lombaId + '?juri_lomba_id=' + juriId;
        fetch(url)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                pesertaSelect.innerHTML = '<option value="">-- Pilih Peserta --</option>';
                if (data.length === 0) {
                    emptyAlert.style.display = 'block';
                    aspekWrapper.style.display = 'none';
                    submitBtn.disabled = true;
                } else {
                    data.forEach(function(item) {
                        var opt = document.createElement('option');
                        opt.value = item.id;
                        opt.textContent = item.text;
                        pesertaSelect.appendChild(opt);
                    });
                    pesertaSelect.disabled = false;
                    submitBtn.disabled = false;
                }
            })
            .catch(function() {
                pesertaSelect.innerHTML = '<option value="">Gagal memuat data</option>';
            });
    }

    function loadAspek(lombaId) {
        aspekWrapper.style.display = 'none';
        aspekBody.innerHTML = '<tr><td colspan="3" style="text-align:center;padding:16px;color:#94a3b8;font-size:13px;">Memuat...</td></tr>';

        fetch('{{ url("/penilaian-lomba/get-aspek") }}/' + lombaId)
            .then(function(r) { return r.json(); })
            .then(function(data) { renderAspekTable(data); })
            .catch(function() {
                aspekBody.innerHTML = '<tr><td colspan="3" style="text-align:center;padding:16px;color:#dc2626;font-size:13px;">Gagal memuat aspek penilaian</td></tr>';
                aspekWrapper.style.display = 'block';
            });
    }

    // Step 1 → Step 2: Sesi change → load Lomba
    sesiSelect.addEventListener('change', function() {
        var sesiId = this.value;
        resetSelect(lombaSelect, '-- Pilih Sesi Dulu --');
        resetSelect(juriSelect, '-- Pilih Lomba Dulu --');
        resetSelect(pesertaSelect, '-- Pilih Juri Dulu --');
        resetSelect(kelompokSelect, '-- Pilih Lomba Dulu --');
        aspekWrapper.style.display = 'none';
        emptyAlert.style.display = 'none';
        jenisInfo.style.display = 'none';
        juriIndividuBox.style.display = 'block';
        juriTimBox.style.display = 'none';
        sectionIndividu.style.display = 'none';
        sectionTim.style.display = 'none';
        currentJenis = '';
        teamJuriHidden.value = '';
        teamJuriHidden.disabled = true;
        teamJuriSelect.innerHTML = '<option value="">-- Pilih Lomba Dulu --</option>';
        teamJuriSelect.disabled = true;
        juriSelect.disabled = true;
        submitBtn.disabled = true;
        lombaHidden.value = '';
        lombaDataMap = {};

        if (!sesiId) return;

        loadSelect('{{ url("/penilaian-lomba/get-lomba") }}/' + sesiId + '{{ $mode ? "?jenis=" . ucfirst($mode) : "" }}', lombaSelect, '-- Pilih Lomba --', lombaDataMap);
    });

    // Step 2 → Step 3: Lomba change → toggle Individu/Tim + load Aspek
    lombaSelect.addEventListener('change', function() {
        var lombaId = this.value;
        resetSelect(juriSelect, '-- Pilih Lomba Dulu --');
        resetSelect(pesertaSelect, '-- Pilih Juri Dulu --');
        resetSelect(kelompokSelect, '-- Pilih Lomba Dulu --');
        aspekWrapper.style.display = 'none';
        emptyAlert.style.display = 'none';
        juriIndividuBox.style.display = 'block';
        juriTimBox.style.display = 'none';
        sectionIndividu.style.display = 'none';
        sectionTim.style.display = 'none';
        teamJuriHidden.value = '';
        teamJuriHidden.disabled = true;
        teamJuriSelect.innerHTML = '<option value="">-- Pilih Lomba Dulu --</option>';
        teamJuriSelect.disabled = true;
        juriSelect.disabled = true;
        submitBtn.disabled = true;

        if (!lombaId) { jenisInfo.style.display = 'none'; lombaHidden.value = ''; return; }

        lombaHidden.value = lombaId;
        var data = lombaDataMap[lombaId];
        if (data && data.jenis) {
            currentJenis = data.jenis;
            jenisBadge.textContent = data.jenis === 'Individu' ? 'Individu' : 'Kelompok (Tim)';
            jenisInfo.style.display = 'block';

            if (data.jenis === 'Individu') {
                juriIndividuBox.style.display = 'block';
                juriTimBox.style.display = 'none';
                sectionIndividu.style.display = 'block';
                teamJuriHidden.disabled = true;
                loadSelect('{{ url("/penilaian-lomba/get-juri") }}/' + lombaId, juriSelect, '-- Pilih Juri --', null, function() {
                    juriSelect.dispatchEvent(new Event('change'));
                });
                teamJuriHidden.value = '';
            } else {
                juriIndividuBox.style.display = 'none';
                juriTimBox.style.display = 'block';
                sectionTim.style.display = 'block';
                juriSelect.disabled = true;
                teamJuriHidden.disabled = false;
                loadSelect('{{ url("/penilaian-lomba/get-kelompok") }}/' + lombaId, kelompokSelect, '-- Pilih Kelompok --', null, function() {
                    submitBtn.disabled = !(kelompokSelect.value && teamJuriHidden.value);
                });
                loadSelect('{{ url("/penilaian-lomba/get-juri") }}/' + lombaId, teamJuriSelect, '-- Pilih Juri --', null, function(data) {
                    teamJuriHidden.value = teamJuriSelect.value || '';
                    submitBtn.disabled = !(kelompokSelect.value && teamJuriHidden.value);
                });
            }
        }

        loadAspek(lombaId);
    });

    // Step 3 (Individu): Juri change → load Peserta
    juriSelect.addEventListener('change', function() {
        if (currentJenis !== 'Individu') {
            return;
        }
        var juriId = this.value;
        var lombaId = lombaSelect.value;
        resetSelect(pesertaSelect, '-- Pilih Juri Dulu --');
        emptyAlert.style.display = 'none';

        if (!juriId || !lombaId) {
            submitBtn.disabled = true;
            return;
        }

        loadPeserta(lombaId, juriId);
    });

    // Step 3 (Individu): Peserta change → enable submit
    pesertaSelect.addEventListener('change', function() {
        if (this.value) {
            submitBtn.disabled = false;
        } else {
            submitBtn.disabled = true;
        }
    });

    // Step 3 (Tim): Kelompok change → enable submit
    kelompokSelect.addEventListener('change', function() {
        if (currentJenis === 'Tim') {
            submitBtn.disabled = !(this.value && teamJuriHidden.value);
            return;
        }

        submitBtn.disabled = !this.value;
    });

    teamJuriSelect.addEventListener('change', function() {
        teamJuriHidden.value = this.value || '';
        if (currentJenis === 'Tim') {
            if (this.value && lombaSelect.value) {
                loadSelect('{{ url("/penilaian-lomba/get-kelompok") }}/' + lombaSelect.value + '?juri_lomba_id=' + this.value, kelompokSelect, '-- Pilih Kelompok --', null, function() {
                    submitBtn.disabled = !(kelompokSelect.value && teamJuriHidden.value);
                });
            }
            submitBtn.disabled = !(kelompokSelect.value && teamJuriHidden.value);
        }
    });

    // Form submit validation
    var formEl = document.getElementById('penilaianForm');
    if (formEl) formEl.addEventListener('submit', function(e) {
        var inputs = document.querySelectorAll('.aspek-nilai');
        var hasValue = false;
        for (var i = 0; i < inputs.length; i++) {
            if (inputs[i].value !== '' && parseFloat(inputs[i].value) >= 0) {
                hasValue = true;
                break;
            }
        }
        if (!hasValue) {
            e.preventDefault();
            var errEl = document.getElementById('aspek-error');
            errEl.querySelector('span').textContent = 'Minimal satu aspek harus diisi nilai.';
            errEl.style.display = 'flex';
            aspekWrapper.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
</script>
@endpush
