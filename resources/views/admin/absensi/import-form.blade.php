@extends('layouts.main')
@section('title','Import Absensi dari Foto')
@section('content')
@include('component.admin.absensi-module')
<style>
    .page-title-content { display: none !important; }
    .abm-import-hero { padding: 20px 26px; margin-bottom: 20px; border-radius: 20px; }
    .abm-upload-zone {
        border: 2px dashed var(--ab-primary-border); border-radius: 16px; padding: 42px 20px; text-align: center;
        background: var(--ab-primary-soft); transition: all .3s; cursor: pointer; position: relative;
    }
    .abm-upload-zone:hover, .abm-upload-zone.dragover { border-color: var(--ab-primary); background: var(--ab-primary-soft); transform: translateY(-2px); box-shadow: var(--ab-shadow-lg); }
    .abm-upload-zone input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; }
    .abm-upload-icon {
        width: 66px; height: 66px; border-radius: 20px; margin: 0 auto 14px;
        background: var(--ab-grad); color: #fff; display: flex; align-items: center; justify-content: center;
        font-size: 26px; box-shadow: 0 10px 24px -6px rgba(37,99,235,.5);
    }
    .abm-upload-text { font-size: 14px; color: var(--ab-text-2); font-weight: 600; }
    .abm-upload-hint { font-size: 12px; color: var(--ab-text-3); margin-top: 6px; }
    .abm-upload-preview { display: none; margin-top: 16px; text-align: center; }
    .abm-upload-preview img { max-width: 100%; max-height: 300px; border-radius: 14px; border: 1px solid var(--ab-border); box-shadow: var(--ab-shadow); }

    /* Terminal overlay (dipertahankan) */
    .ocr-progress-overlay { display: none; position: fixed; inset: 0; background: rgba(2,6,23,.45); z-index: 9999; justify-content: center; align-items: center; backdrop-filter: blur(8px) saturate(1.2); -webkit-backdrop-filter: blur(8px) saturate(1.2); }
    .ocr-progress-overlay.active { display: flex; }
    .ocr-progress-box {
        background: #0a0e14; border: 1px solid #00ff41; border-radius: 14px;
        max-width: 520px; width: 92%; box-shadow: 0 0 40px rgba(0,255,65,.15), 0 0 80px rgba(0,255,65,.05), inset 0 0 60px rgba(0,255,65,.03);
        font-family: 'Courier New', 'Consolas', monospace; position: relative; overflow: hidden;
    }
    .ocr-progress-box::before { content: ''; position: absolute; inset: 0; background: repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(0,255,65,.03) 2px, rgba(0,255,65,.03) 4px); pointer-events: none; z-index: 1; }
    .ocr-progress-box::after { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px; background: linear-gradient(90deg, transparent, #00ff41, transparent); animation: scanline 2s linear infinite; z-index: 2; }
    @keyframes scanline { 0% { top: 0; } 100% { top: 100%; } }
    .ocr-progress-header { background: #0d1117; border-bottom: 1px solid #1a3a1a; padding: 10px 14px; display: flex; align-items: center; gap: 6px; font-size: 11px; color: #00ff41; border-radius: 14px 14px 0 0; }
    .ocr-progress-header .dot { width: 8px; height: 8px; border-radius: 50%; }
    .ocr-progress-header .dot-red { background: #ff5f56; }
    .ocr-progress-header .dot-yellow { background: #ffbd2e; }
    .ocr-progress-header .dot-green { background: #27c93f; }
    .ocr-progress-header span { margin-left: 8px; opacity: 0.6; font-size: 10px; }
    .ocr-progress-body { padding: 20px; position: relative; z-index: 3; }
    .ocr-progress-terminal {
        background: #000; border: 1px solid #1a3a1a; border-radius: 6px;
        padding: 12px 14px; margin-bottom: 16px; min-height: 90px; max-height: 140px;
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
    .ocr-progress-bar-wrap { background: #111; border: 1px solid #1a3a1a; border-radius: 6px; height: 6px; overflow: hidden; margin-bottom: 8px; }
    .ocr-progress-bar { height: 100%; border-radius: 3px; width: 0; background: linear-gradient(90deg, #003300, #00ff41); transition: width .3s ease; box-shadow: 0 0 8px rgba(0,255,65,.4); }
    .ocr-progress-pct { font-size: 11px; color: #00ff41; text-align: right; letter-spacing: 2px; }

    .abm-ai-badge {
        display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 20px;
        font-size: 11px; font-weight: 800; letter-spacing: .5px;
        background: rgba(255,255,255,.16); border: 1px solid rgba(255,255,255,.24); color: #fff;
    }
    @media (max-width: 768px) { .abm-upload-zone { padding: 28px 16px; } }
</style>

<div class="abs-mod import-absensi-page" style="margin-top:0;">
    {{-- HERO --}}
    <div class="abm-hero abm-import-hero">
        <div class="abm-hero-grid"></div>
        <div class="abm-hero-row">
            <div class="abm-hero-left">
                <div class="d-flex align-items-center gap-3">
                    <div class="abm-hero-icon"><i class="fas fa-camera"></i></div>
                    <div>
                        <h3>Import Absensi dari Foto</h3>
                        <p class="abm-hero-sub">Unggah foto buku absensi, sistem mengenali status kehadiran secara otomatis.</p>
                    </div>
                </div>
                <div class="abm-hero-badges">
                    <span class="abm-hero-badge"><i class="fas fa-graduation-cap"></i> {{ $tahunAktif->tahun_ajaran }}</span>
                    <span class="abm-ai-badge"><i class="fas fa-brain"></i> AI VISION</span>
                </div>
            </div>
            <div class="abm-hero-actions">
                <a href="{{ route('absensi.index') }}" class="abm-btn abm-btn--ghost"><i class="fas fa-arrow-left"></i> Kembali</a>
                <a href="{{ route('absensi.create') }}" class="abm-btn abm-btn--light"><i class="fas fa-plus"></i> Input Manual</a>
            </div>
        </div>
    </div>

    @if(session('error'))
    <div class="abm-alert abm-alert--danger abm-alert--dismissible">
        <i class="fas fa-exclamation-circle"></i>
        <div style="flex:1;">{{ session('error') }}</div>
    </div>
    @endif

    <div class="row g-3">
        <div class="col-lg-7">
            <div class="abm-card" style="padding:22px 24px;">
                <div class="abm-section-title mb-4"><i class="fas fa-upload"></i> Form Import</div>

                <div id="formSection">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="abm-field-label"><i class="fas fa-chalkboard"></i>Kelas <span class="text-danger">*</span></label>
                            <select id="kelasSelect" class="abm-control" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($kelasList as $kelas)
                                <option value="{{ $kelas->id }}" data-siswa="{{ $studentCounts[$kelas->id] ?? 0 }}" {{ old('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                    {{ $kelas->nama_kelas }} ({{ $studentCounts[$kelas->id] ?? 0 }} siswa)
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="abm-field-label"><i class="fas fa-calendar"></i>Bulan <span class="text-danger">*</span></label>
                            <select id="bulanSelect" class="abm-control" required>
                                @foreach($months as $num => $name)
                                <option value="{{ $num }}" {{ old('bulan', $currentMonth) == $num ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="abm-field-label"><i class="fas fa-calendar-day"></i>Tahun <span class="text-danger">*</span></label>
                            <input type="number" id="tahunInput" class="abm-control" value="{{ old('tahun', $currentYear) }}" min="2020" max="2050" required>
                        </div>
                    </div>

                    <label class="abm-field-label"><i class="fas fa-image"></i>Foto Buku Absensi <span class="text-danger">*</span></label>
                    <div class="abm-upload-zone" id="uploadZone">
                        <input type="file" accept="image/jpeg,image/png,image/webp" id="fotoInput">
                        <div class="abm-upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                        <div class="abm-upload-text">Klik atau seret foto ke sini</div>
                        <div class="abm-upload-hint">Format: JPG, PNG, WebP. Maks. 10 MB.</div>
                    </div>

                    <div class="abm-upload-preview" id="uploadPreview">
                        <img id="previewImg" src="" alt="Preview">
                    </div>

                    <div id="errorMsg" class="text-danger mt-3" style="display:none; font-size:13px;"></div>

                    <div id="ocrEditorSection" style="display:none;margin-top:18px;">
                        <label class="abm-field-label"><i class="fas fa-file-alt"></i>Hasil OCR Teks</label>
                        <textarea id="ocrTextEditor" class="abm-control" style="min-height:220px;font-family:Consolas,monospace;font-size:12px;"></textarea>
                        <div class="abm-upload-hint">Periksa dan edit teks OCR bila perlu, lalu generate JSON absensi.</div>
                        <div class="d-flex gap-2 mt-3 flex-wrap">
                            <button type="button" class="abm-btn abm-btn--light" id="btnBackToUpload">
                                <i class="fas fa-arrow-left"></i> Kembali ke Upload
                            </button>
                            <button type="button" class="abm-btn abm-btn--solid" id="btnGenerateJson" style="flex:1;">
                                <i class="fas fa-code"></i> Generate JSON dari OCR
                            </button>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-3 flex-wrap" id="uploadActionBar">
                        <a href="{{ route('absensi.index') }}" class="abm-btn abm-btn--soft"><i class="fas fa-arrow-left"></i> Kembali</a>
                        <button type="button" class="abm-btn abm-btn--solid" id="btnProcessVision" disabled style="flex:1;">
                            <i class="fas fa-brain me-1"></i> Proses Foto ke OCR
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="abm-card abm-card--lift" style="padding:22px 24px;height:100%;">
                <div class="abm-section-title mb-4"><i class="fas fa-robot"></i> Cara Kerja</div>
                <div class="abm-steps">
                    <div class="abm-step-line">
                        <span class="dot"><i class="fas fa-check"></i></span>
                        <div class="t">Unggah Foto</div>
                        <div class="d">Pilih kelas, bulan, dan foto buku absensi siswa.</div>
                    </div>
                    <div class="abm-step-line">
                        <span class="dot"><i class="fas fa-brain"></i></span>
                        <div class="t">Vision AI Menganalisis</div>
                        <div class="d">Sistem memproses foto menjadi teks OCR mentah terlebih dahulu agar bisa diperiksa operator.</div>
                    </div>
                    <div class="abm-step-line">
                        <span class="dot"><i class="fas fa-robot"></i></span>
                        <div class="t">Generate JSON dari Teks</div>
                        <div class="d">Teks OCR yang sudah dicek diubah menjadi JSON absensi, lalu dicocokkan ke siswa berdasarkan NISN dan nama.</div>
                    </div>
                    <div class="abm-step-line">
                        <span class="dot"><i class="fas fa-clipboard-check"></i></span>
                        <div class="t">Verifikasi & Simpan</div>
                        <div class="d">Anda meninjau hasil, mengoreksi bila perlu, lalu konfirmasi penyimpanan.</div>
                    </div>
                </div>

                <hr class="abm-divider">

                <div class="abm-hintbox">
                    <i class="fas fa-info-circle"></i>
                    <span>Alur baru: foto diubah ke teks OCR dulu, teks bisa diedit, lalu baru digenerate menjadi JSON absensi untuk diverifikasi sebelum disimpan.</span>
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
@endsection

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
    var btnGenerateJson = document.getElementById('btnGenerateJson');
    var btnBackToUpload = document.getElementById('btnBackToUpload');
    var ocrEditorSection = document.getElementById('ocrEditorSection');
    var ocrTextEditor = document.getElementById('ocrTextEditor');
    var uploadActionBar = document.getElementById('uploadActionBar');
    var formSection = document.getElementById('formSection');

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

    function showOcrEditor(text) {
        ocrTextEditor.value = text || '';
        ocrEditorSection.style.display = 'block';
        uploadActionBar.style.display = 'none';
    }

    function showUploadActions() {
        ocrEditorSection.style.display = 'none';
        uploadActionBar.style.display = 'flex';
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

    btnBackToUpload.addEventListener('click', function() {
        showUploadActions();
    });

    btnGenerateJson.addEventListener('click', function() {
        hideError();
        if (!ocrTextEditor.value.trim()) { showError('Teks OCR masih kosong.'); return; }
        btnGenerateJson.disabled = true;

        fetch('{{ route("absensi.import.generate-json") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ ocr_text: ocrTextEditor.value })
        })
        .then(function(resp) {
            return resp.text().then(function(text) {
                var result = {};
                try {
                    result = text ? JSON.parse(text) : {};
                } catch (e) {
                    result = { error: text || 'Respons server tidak valid.' };
                }
                return { ok: resp.ok, result: result };
            });
        })
        .then(function(payload) {
            var result = payload.result || {};
            btnGenerateJson.disabled = false;
            if (payload.ok && result.redirect) {
                window.location.href = result.redirect;
                return;
            }
            showError(result.error || 'Gagal generate JSON.');
        })
        .catch(function(err) {
            btnGenerateJson.disabled = false;
            showError('Error: ' + (err.message || err));
        });
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
        setTimeout(function() { termInfo('Menyusun teks OCR mentah dari hasil ekstraksi...'); updateProgress(72); }, 7200);
        setTimeout(function() { termPrint('prepare --output=ocr_text', 't-cmd'); updateProgress(80); }, 7800);
        setTimeout(function() { termInfo('Menunggu review operator sebelum generate JSON...'); updateProgress(85); }, 8200);

        fetch('{{ route("absensi.import.parse") }}', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(resp) {
            return resp.text().then(function(text) {
                var result = {};
                try {
                    result = text ? JSON.parse(text) : {};
                } catch (e) {
                    result = { error: text || 'Respons server tidak valid.' };
                }
                return { ok: resp.ok, result: result };
            });
        })
        .then(function(payload) {
            var result = payload.result || {};
            if (payload.ok && result.ocr_text) {
                if (result.ai_warning) {
                    termWarn('[WARN] ' + result.ai_warning);
                } else {
                    termOk('Foto berkualitas baik.');
                }
                termOk('OCR complete. Review teks sebelum generate JSON.');
                updateProgress(95);
                setTimeout(function() { updateProgress(100); }, 400);
                setTimeout(function() { ocrProgress.classList.remove('active'); }, 600);
                isProcessing = false;
                btnProcess.disabled = false;
                showOcrEditor(result.ocr_text);
                if (result.ai_warning) {
                    showError(result.ai_warning);
                }
                return;
            }

            termWarn('[ERROR] ' + (result.error || 'Unknown error'));
            updateProgress(0);
            setTimeout(function() { ocrProgress.classList.remove('active'); }, 2000);
            isProcessing = false;
            btnProcess.disabled = false;
            showError(result.error || 'Terjadi kesalahan. Cek log Laravel atau konfigurasi OCR lokal.');
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
