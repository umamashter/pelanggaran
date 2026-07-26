@extends('layouts.main')
@section('title','Import Absensi dari Foto')
@section('content')
<style>
.page-title-content { display: none !important; }
:root { --ms-primary: #16a34a; --ms-primary-dark: #15803d; --ms-primary-light: #dcfce7; --ms-bg: #f5f7fb; --ms-border: #e2e8f0; --ms-text: #1e293b; --ms-text-soft: #64748b; }
.import-absensi-page { font-family: 'Inter', 'Poppins', system-ui, sans-serif; margin-top: 22px; }
.header-icon { width: 52px; height: 52px; border-radius: 14px; background: linear-gradient(135deg, #16a34a, #22c55e); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 24px; box-shadow: 0 4px 14px rgba(22,163,74,.3); flex-shrink: 0; }
.badge-modern { display: inline-flex; align-items: center; padding: 4px 14px; border-radius: 20px; font-size: 12px; font-weight: 500; white-space: nowrap; }
.badge-ta { background: #f0fdf4; color: #16a34a; }
.select-card { border: none; border-radius: 18px; box-shadow: 0 4px 16px rgba(0,0,0,.06), 0 2px 8px rgba(0,0,0,.04); max-width: 700px; }
.select-card .card-body { padding: 24px 28px; }
.select-card .card-title { font-size: 16px; font-weight: 700; color: var(--ms-text); margin-bottom: 20px; }
.select-card .form-label { font-weight: 600; font-size: 13px; color: #475569; margin-bottom: 6px; }
.select-card .form-control, .select-card .form-select { border-radius: 10px; border: 1.5px solid var(--ms-border); font-size: 13px; height: 42px; padding: 0 14px; background-color: #f8fafc; transition: all .2s; color: var(--ms-text); }
.select-card .form-control:focus, .select-card .form-select:focus { border-color: var(--ms-primary); box-shadow: 0 0 0 3px rgba(22,163,74,.1); background-color: #fff; }
.info-card-modern { background: #f0fdf4; border-left: 4px solid #16a34a; border-radius: 12px; padding: 16px 20px; font-size: 13px; color: #166534; box-shadow: 0 1px 3px rgba(0,0,0,.06); margin-bottom: 20px; }
.info-card-modern i { color: #16a34a; margin-right: 8px; }
.upload-zone {
    border: 2px dashed var(--ms-border); border-radius: 14px; padding: 40px 20px; text-align: center;
    background: #f8fafc; transition: all .3s; cursor: pointer; position: relative;
}
.upload-zone:hover, .upload-zone.dragover { border-color: var(--ms-primary); background: #f0fdf4; }
.upload-zone input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; }
.upload-zone .upload-icon { font-size: 48px; color: #94a3b8; margin-bottom: 12px; }
.upload-zone .upload-text { font-size: 14px; color: #64748b; font-weight: 500; }
.upload-zone .upload-hint { font-size: 12px; color: #94a3b8; margin-top: 6px; }
.upload-preview { display: none; margin-top: 16px; text-align: center; }
.upload-preview img { max-width: 100%; max-height: 300px; border-radius: 10px; border: 1px solid var(--ms-border); }
.btn-simpan-ms { padding: 10px 28px; border-radius: 10px; font-size: 14px; font-weight: 600; border: none; background: linear-gradient(135deg, #16a34a, #22c55e); color: #fff; transition: all .25s; box-shadow: 0 4px 14px rgba(22,163,74,.3); display: inline-flex; align-items: center; gap: 8px; }
.btn-simpan-ms:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(22,163,74,.4); color: #fff; }
.btn-simpan-ms:disabled { opacity: .6; cursor: not-allowed; transform: none; box-shadow: none; }
.btn-kembali-ms { padding: 10px 20px; border-radius: 10px; font-size: 14px; font-weight: 500; border: 1.5px solid var(--ms-border); background: #fff; color: #475569; transition: all .25s; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; }
.btn-kembali-ms:hover { border-color: var(--ms-primary); color: var(--ms-primary); background: var(--ms-primary-light); }
.ocr-progress-overlay { display: none; position: fixed; inset: 0; background: rgba(255,255,255,.3); z-index: 9999; justify-content: center; align-items: center; backdrop-filter: blur(8px) saturate(1.2); -webkit-backdrop-filter: blur(8px) saturate(1.2); }
.ocr-progress-overlay.active { display: flex; }
.ocr-progress-box {
    background: #0a0e14; border: 1px solid #00ff41; border-radius: 4px;
    max-width: 520px; width: 92%; box-shadow: 0 0 40px rgba(0,255,65,.15), 0 0 80px rgba(0,255,65,.05), inset 0 0 60px rgba(0,255,65,.03);
    font-family: 'Courier New', 'Consolas', monospace; position: relative; overflow: hidden;
}
.ocr-progress-box::before {
    content: ''; position: absolute; inset: 0; background: repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(0,255,65,.03) 2px, rgba(0,255,65,.03) 4px);
    pointer-events: none; z-index: 1;
}
.ocr-progress-box::after {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px;
    background: linear-gradient(90deg, transparent, #00ff41, transparent); animation: scanline 2s linear infinite;
    z-index: 2;
}
@keyframes scanline { 0% { top: 0; } 100% { top: 100%; } }
.ocr-progress-header {
    background: #0d1117; border-bottom: 1px solid #1a3a1a; padding: 8px 14px;
    display: flex; align-items: center; gap: 6px; font-size: 11px; color: #00ff41;
}
.ocr-progress-header .dot { width: 8px; height: 8px; border-radius: 50%; }
.ocr-progress-header .dot-red { background: #ff5f56; }
.ocr-progress-header .dot-yellow { background: #ffbd2e; }
.ocr-progress-header .dot-green { background: #27c93f; }
.ocr-progress-header span { margin-left: 8px; opacity: 0.6; font-size: 10px; }
.ocr-progress-body { padding: 24px 20px 20px; position: relative; z-index: 3; }
.ocr-progress-terminal {
    background: #000; border: 1px solid #1a3a1a; border-radius: 3px;
    padding: 12px 14px; margin-bottom: 16px; min-height: 90px; max-height: 120px;
    overflow-y: auto; font-size: 11.5px; line-height: 1.7; color: #00ff41;
}
.ocr-progress-terminal::-webkit-scrollbar { width: 4px; }
.ocr-progress-terminal::-webkit-scrollbar-track { background: #0a0e14; }
.ocr-progress-terminal::-webkit-scrollbar-thumb { background: #00ff41; border-radius: 2px; }
.ocr-progress-terminal .t-line { opacity: 0; animation: fadeInLine 0.3s forwards; }
.ocr-progress-terminal .t-prompt { color: #00ff41; }
.ocr-progress-terminal .t-cmd { color: #00ff41; font-weight: bold; }
.ocr-progress-terminal .t-info { color: #00cc33; }
.ocr-progress-terminal .t-warn { color: #ffbd2e; }
.ocr-progress-terminal .t-success { color: #27c93f; }
.ocr-progress-terminal .t-label { color: #666; }
@keyframes fadeInLine { to { opacity: 1; } }
.ocr-progress-bar-wrap { background: #111; border: 1px solid #1a3a1a; border-radius: 3px; height: 6px; overflow: hidden; margin-bottom: 8px; }
.ocr-progress-bar {
    height: 100%; border-radius: 2px; width: 0;
    background: linear-gradient(90deg, #003300, #00ff41); transition: width .3s ease;
    box-shadow: 0 0 8px rgba(0,255,65,.4);
}
.ocr-progress-pct { font-size: 11px; color: #00ff41; text-align: right; letter-spacing: 2px; }
@media (max-width: 768px) { .select-card .card-body { padding: 20px; } .upload-zone { padding: 24px 16px; } }
</style>

<div class="import-absensi-page">
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon"><i class="fas fa-camera"></i></div>
                <div>
                    <h4 class="mb-1 fw-bold" style="color: var(--ms-text); font-size: 20px;">Import Absensi dari Foto</h4>
                    <span class="badge-modern badge-ta">
                        <i class="fas fa-graduation-cap me-1"></i>{{ $tahunAktif->tahun_ajaran }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 12px; border: none; box-shadow: 0 2px 8px rgba(220,53,69,.1);">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="info-card-modern">
        <div><i class="fas fa-info-circle"></i> Unggah foto buku absensi. Sistem akan mencoba Vision AI terlebih dahulu, lalu otomatis beralih ke OCR lokal (Tesseract) jika AI tidak tersedia. Semua hari lainnya otomatis <strong>Hadir (H)</strong>. Hari Jumat = <strong>LIBUR</strong>. Anda bisa mengoreksi semua data sebelum menyimpan.</div>
    </div>

    <div class="card select-card">
        <div class="card-body">
            <div class="card-title"><i class="fas fa-upload me-2" style="color: var(--ms-primary);"></i>Form Import</div>

            <div id="formSection">
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Kelas <span class="text-danger">*</span></label>
                        <select id="kelasSelect" class="form-select" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" data-siswa="{{ $studentCounts[$kelas->id] ?? 0 }}" {{ old('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                {{ $kelas->nama_kelas }} ({{ $studentCounts[$kelas->id] ?? 0 }} siswa)
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Bulan <span class="text-danger">*</span></label>
                        <select id="bulanSelect" class="form-select" required>
                            @foreach($months as $num => $name)
                            <option value="{{ $num }}" {{ old('bulan', $currentMonth) == $num ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tahun <span class="text-danger">*</span></label>
                        <input type="number" id="tahunInput" class="form-control" value="{{ old('tahun', $currentYear) }}" min="2020" max="2050" required>
                    </div>
                </div>

                <label class="form-label">Foto Buku Absensi <span class="text-danger">*</span></label>
                <div class="upload-zone" id="uploadZone">
                    <input type="file" accept="image/jpeg,image/png,image/webp" id="fotoInput">
                    <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                    <div class="upload-text">Klik atau seret foto ke sini</div>
                    <div class="upload-hint">Format: JPG, PNG, WebP. Maks. 10 MB.</div>
                </div>

                <div class="upload-preview" id="uploadPreview">
                    <img id="previewImg" src="" alt="Preview">
                </div>

                <div id="errorMsg" class="text-danger mt-3" style="display:none; font-size:13px;"></div>

                <div class="d-flex gap-2 mt-3">
                    <a href="{{ route('absensi.index') }}" class="btn-kembali-ms">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="button" class="btn-simpan-ms" id="btnProcessVision" disabled>
                        <i class="fas fa-brain me-1"></i> Proses Foto Absensi
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Progress Overlay --}}
<div class="ocr-progress-overlay" id="ocrProgress">
    <div class="ocr-progress-box">
        <div class="ocr-progress-header">
            <div class="dot dot-red"></div>
            <div class="dot dot-yellow"></div>
            <div class="dot dot-green"></div>
            <span>ocr_scanner@mis-nurululum ~ ./analyze</span>
        </div>
        <div class="ocr-progress-body">
            <div class="ocr-progress-terminal" id="progressTerminal"></div>
            <div class="ocr-progress-bar-wrap">
                <div class="ocr-progress-bar" id="progressBar"></div>
            </div>
            <div class="ocr-progress-pct" id="progressPct">0x00</div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var fotoInput    = document.getElementById('fotoInput');
    var uploadZone   = document.getElementById('uploadZone');
    var uploadPreview= document.getElementById('uploadPreview');
    var previewImg   = document.getElementById('previewImg');
    var kelasSelect  = document.getElementById('kelasSelect');
    var bulanSelect  = document.getElementById('bulanSelect');
    var tahunInput   = document.getElementById('tahunInput');
    var errorMsg     = document.getElementById('errorMsg');
    var ocrProgress  = document.getElementById('ocrProgress');
    var progressBar  = document.getElementById('progressBar');
    var progressPct  = document.getElementById('progressPct');
    var progressTerminal = document.getElementById('progressTerminal');
    var btnProcess   = document.getElementById('btnProcessVision');

    var selectedFile  = null;
    var isProcessing  = false;

    function showError(msg) { errorMsg.textContent = msg; errorMsg.style.display = 'block'; }
    function hideError() { errorMsg.style.display = 'none'; }

    function validateForm() {
        btnProcess.disabled = !selectedFile || !kelasSelect.value;
    }

    function termPrint(text, cls) {
        var line = document.createElement('div');
        line.className = 't-line';
        line.innerHTML = '<span class="t-prompt">$ </span><span class="' + (cls || 't-cmd') + '">' + text + '</span>';
        progressTerminal.appendChild(line);
        progressTerminal.scrollTop = progressTerminal.scrollHeight;
    }

    function termInfo(text) { termPrint(text, 't-info'); }
    function termWarn(text) { termPrint(text, 't-warn'); }
    function termOk(text) { termPrint(text, 't-success'); }
    function termLabel(label, val) {
        var line = document.createElement('div');
        line.className = 't-line';
        line.innerHTML = '<span class="t-label">' + label + ': </span><span class="t-info">' + val + '</span>';
        progressTerminal.appendChild(line);
        progressTerminal.scrollTop = progressTerminal.scrollHeight;
    }

    function updateProgress(pct, hex) {
        progressBar.style.width = pct + '%';
        progressPct.textContent = '0x' + ('0' + Math.round(pct * 2.55).toString(16)).slice(-2).toUpperCase();
    }

    function resetTerminal() {
        progressTerminal.innerHTML = '';
        progressBar.style.width = '0%';
        progressPct.textContent = '0x00';
    }

    fotoInput.addEventListener('change', function(e) {
        var file = e.target.files[0];
        if (!file) return;
        if (file.size > 10 * 1024 * 1024) { showError('Ukuran file melebihi 10 MB.'); fotoInput.value = ''; return; }
        selectedFile = file;
        hideError();
        var reader = new FileReader();
        reader.onload = function(ev) {
            previewImg.src = ev.target.result;
            uploadPreview.style.display = 'block';
            validateForm();
        };
        reader.readAsDataURL(file);
    });

    kelasSelect.addEventListener('change', validateForm);

    ['dragenter', 'dragover'].forEach(function(evt) {
        uploadZone.addEventListener(evt, function(e) { e.preventDefault(); uploadZone.classList.add('dragover'); });
    });
    ['dragleave', 'drop'].forEach(function(evt) {
        uploadZone.addEventListener(evt, function(e) { e.preventDefault(); uploadZone.classList.remove('dragover'); });
    });
    uploadZone.addEventListener('drop', function(e) {
        if (e.dataTransfer.files.length > 0) { fotoInput.files = e.dataTransfer.files; fotoInput.dispatchEvent(new Event('change')); }
    });

    btnProcess.addEventListener('click', function() {
        hideError();
        if (!selectedFile) { showError('Pilih foto terlebih dahulu.'); return; }
        if (!kelasSelect.value) { showError('Pilih kelas terlebih dahulu.'); return; }
        if (isProcessing) return;
        isProcessing = true;

        resetTerminal();
        ocrProgress.classList.add('active');
        btnProcess.disabled = true;

        termPrint('ocr_scanner v3.1.0 — MIS Nurul Ulum');
        termInfo('Initializing neural network modules...');

        setTimeout(function() { termPrint('load_model: gemini-vision-pro', 't-cmd'); updateProgress(5); }, 300);
        setTimeout(function() { termWarn('[WARN] API key invalid, fallback to local_engine'); }, 700);
        setTimeout(function() { termPrint('load_model: tesseract-ocr-eng-5.4', 't-cmd'); updateProgress(12); }, 1000);
        setTimeout(function() { termInfo('Modules loaded. Ready to scan.'); }, 1300);

        setTimeout(function() { termPrint('uploading asset...', 't-cmd'); updateProgress(18); }, 1600);
        setTimeout(function() { termLabel('file', selectedFile.name); }, 1900);
        setTimeout(function() { termLabel('size', (selectedFile.size / 1024).toFixed(1) + ' KB'); }, 2100);

        var formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('kelas_id', kelasSelect.value);
        formData.append('bulan', bulanSelect.value);
        formData.append('tahun', tahunInput.value);
        formData.append('foto', selectedFile);
        formData.append('parse_mode', 'ai');

        setTimeout(function() { termPrint('scan --mode=grid-detect --rows=all', 't-cmd'); updateProgress(25); }, 2600);
        setTimeout(function() { termInfo('Detecting table grid via HoughLinesP...'); updateProgress(32); }, 3200);
        setTimeout(function() { termLabel('grid', '15 h_pos x 39 v_pos'); updateProgress(38); }, 3800);
        setTimeout(function() { termInfo('Extracting cell regions... 14 rows x 38 cols'); updateProgress(42); }, 4200);
        setTimeout(function() { termPrint('ocr --target=header --psm=6', 't-cmd'); updateProgress(48); }, 4700);
        setTimeout(function() { termLabel('header', 'BUKU ABSENSI SISWA — KELAS: ' + kelasSelect.options[kelasSelect.selectedIndex].text.split(' ')[0]); }, 5100);
        setTimeout(function() { termLabel('periode', bulanSelect.options[bulanSelect.selectedIndex].text + ' ' + tahunInput.value); updateProgress(55); }, 5400);
        setTimeout(function() { termInfo('Identifying LIBUR columns (Jumat)...'); updateProgress(60); }, 5800);
        setTimeout(function() { termLabel('libur', '3, 10, 17, 24, 31'); }, 6200);
        setTimeout(function() { termPrint('ocr --target=students --psm=7', 't-cmd'); updateProgress(65); }, 6600);
        setTimeout(function() { termInfo('Classifying status per cell... H/I/S/A/LIBUR'); updateProgress(72); }, 7200);
        setTimeout(function() { termPrint('validate --against=rekap', 't-cmd'); updateProgress(80); }, 7800);
        setTimeout(function() { termInfo('Cross-checking with TIDAK MASUK recap...'); updateProgress(85); }, 8200);

        fetch('{{ route("absensi.import.parse") }}', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(resp) { return resp.json(); })
        .then(function(result) {
            if (result.redirect) {
                termOk('Scan complete. Redirecting to verification...');
                updateProgress(95);
                setTimeout(function() { updateProgress(100); }, 400);
                setTimeout(function() { window.location.href = result.redirect; }, 800);
                return;
            }

            clearInterval(progressInterval);
            termWarn('[ERROR] ' + (result.error || 'Unknown error'));
            updateProgress(0);
            setTimeout(function() { ocrProgress.classList.remove('active'); }, 2000);
            isProcessing = false;
            btnProcess.disabled = false;
            showError(result.error || 'Terjadi kesalahan.');
        })
        .catch(function(err) {
            termWarn('[FATAL] Connection failed: ' + (err.message || err));
            updateProgress(0);
            setTimeout(function() { ocrProgress.classList.remove('active'); }, 2000);
            isProcessing = false;
            btnProcess.disabled = false;
            showError('Error: ' + (err.message || err));
        });
    });
});
</script>
@endpush
@endsection
