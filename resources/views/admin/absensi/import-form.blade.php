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
.select-card { border: none; border-radius: 18px; box-shadow: 0 4px 16px rgba(0,0,0,.06), 0 2px 8px rgba(0,0,0,.04); max-width: 650px; }
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
.upload-preview { display: none; margin-top: 16px; }
.upload-preview img { max-width: 100%; max-height: 300px; border-radius: 10px; border: 1px solid var(--ms-border); }
.btn-simpan-ms { padding: 10px 28px; border-radius: 10px; font-size: 14px; font-weight: 600; border: none; background: linear-gradient(135deg, #16a34a, #22c55e); color: #fff; transition: all .25s; box-shadow: 0 4px 14px rgba(22,163,74,.3); display: inline-flex; align-items: center; gap: 8px; }
.btn-simpan-ms:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(22,163,74,.4); color: #fff; }
.btn-simpan-ms:disabled { opacity: .6; cursor: not-allowed; transform: none; box-shadow: none; }
.btn-kembali-ms { padding: 10px 20px; border-radius: 10px; font-size: 14px; font-weight: 500; border: 1.5px solid var(--ms-border); background: #fff; color: #475569; transition: all .25s; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; }
.btn-kembali-ms:hover { border-color: var(--ms-primary); color: var(--ms-primary); background: var(--ms-primary-light); }
/* Progress overlay */
.ocr-progress-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.55); z-index: 9999; justify-content: center; align-items: center; }
.ocr-progress-overlay.active { display: flex; }
.ocr-progress-box { background: #fff; border-radius: 18px; padding: 36px 40px; max-width: 440px; width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,.2); text-align: center; }
.ocr-progress-icon { width: 56px; height: 56px; border-radius: 50%; background: linear-gradient(135deg, #16a34a, #22c55e); display: flex; align-items: center; justify-content: center; margin: 0 auto 18px; color: #fff; font-size: 22px; animation: pulse 1.5s infinite; }
@keyframes pulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.08); } }
.ocr-progress-title { font-size: 16px; font-weight: 700; color: var(--ms-text); margin-bottom: 8px; }
.ocr-progress-msg { font-size: 13px; color: var(--ms-text-soft); margin-bottom: 18px; min-height: 36px; }
.ocr-progress-bar-wrap { background: #e2e8f0; border-radius: 8px; height: 10px; overflow: hidden; }
.ocr-progress-bar { background: linear-gradient(135deg, #16a34a, #22c55e); height: 100%; border-radius: 8px; transition: width .3s ease; width: 0; }
.ocr-progress-pct { font-size: 13px; font-weight: 600; color: var(--ms-primary); margin-top: 10px; }
.ocr-engine-missing { display: none; background: #fef2f2; border-left: 4px solid #dc2626; border-radius: 12px; padding: 16px 20px; font-size: 13px; color: #991b1b; margin-bottom: 20px; }
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
        <div><i class="fas fa-info-circle"></i> Unggah foto buku absensi. Sistem akan membaca simbol otomatis di browser: <strong>"." = Hadir</strong>, I = Izin, S = Sakit, A = Alpha. Sel kosong akan ditandai sebagai <strong>"Perlu Diperiksa" (?)</strong>. Anda bisa mengoreksi semua data sebelum menyimpan.</div>
    </div>

    <div class="ocr-engine-missing" id="ocrEngineMissing">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>File OCR engine tidak ditemukan.</strong> Pastikan file <code>public/js/ocr-engine.js</code> telah diupload ke server. Hubungi administrator.
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
                            <option value="{{ $kelas->id }}" {{ old('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                {{ $kelas->nama_kelas }}
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

                <div class="d-flex gap-2 mt-4">
                    <a href="{{ route('absensi.index') }}" class="btn-kembali-ms">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="button" class="btn-simpan-ms" id="submitBtn" disabled>
                        <i class="fas fa-cogs"></i> Baca Foto
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Progress Overlay -->
<div class="ocr-progress-overlay" id="ocrProgress">
    <div class="ocr-progress-box">
        <div class="ocr-progress-icon"><i class="fas fa-eye"></i></div>
        <div class="ocr-progress-title">Memproses Foto Absensi</div>
        <div class="ocr-progress-msg" id="progressMsg">Menyiapkan...</div>
        <div class="ocr-progress-bar-wrap">
            <div class="ocr-progress-bar" id="progressBar"></div>
        </div>
        <div class="ocr-progress-pct" id="progressPct">0%</div>
    </div>
</div>

@push('scripts')
<script>
(function() {
    var script = document.createElement('script');
    script.src = '{{ asset("js/ocr-engine.js") }}?v={{ time() }}';
    script.onerror = function() {
        document.getElementById('ocrEngineMissing').style.display = 'block';
        document.getElementById('submitBtn').disabled = true;
        console.error('Gagal memuat ocr-engine.js dari: ' + script.src);
    };
    document.head.appendChild(script);
})();
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var fotoInput = document.getElementById('fotoInput');
    var uploadZone = document.getElementById('uploadZone');
    var uploadPreview = document.getElementById('uploadPreview');
    var previewImg = document.getElementById('previewImg');
    var submitBtn = document.getElementById('submitBtn');
    var kelasSelect = document.getElementById('kelasSelect');
    var bulanSelect = document.getElementById('bulanSelect');
    var tahunInput = document.getElementById('tahunInput');
    var errorMsg = document.getElementById('errorMsg');
    var ocrProgress = document.getElementById('ocrProgress');
    var progressMsg = document.getElementById('progressMsg');
    var progressBar = document.getElementById('progressBar');
    var progressPct = document.getElementById('progressPct');

    var selectedFile = null;

    function showError(msg) {
        errorMsg.textContent = msg;
        errorMsg.style.display = 'block';
    }
    function hideError() {
        errorMsg.style.display = 'none';
    }

    function validateForm() {
        if (!selectedFile) { submitBtn.disabled = true; return; }
        if (!kelasSelect.value) { submitBtn.disabled = true; return; }
        submitBtn.disabled = false;
    }

    fotoInput.addEventListener('change', function(e) {
        var file = e.target.files[0];
        if (!file) return;

        if (file.size > 10 * 1024 * 1024) {
            showError('Ukuran file melebihi 10 MB.');
            fotoInput.value = '';
            return;
        }

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
    bulanSelect.addEventListener('change', validateForm);
    tahunInput.addEventListener('input', validateForm);

    ['dragenter', 'dragover'].forEach(function(evt) {
        uploadZone.addEventListener(evt, function(e) {
            e.preventDefault();
            uploadZone.classList.add('dragover');
        });
    });
    ['dragleave', 'drop'].forEach(function(evt) {
        uploadZone.addEventListener(evt, function(e) {
            e.preventDefault();
            uploadZone.classList.remove('dragover');
        });
    });
    uploadZone.addEventListener('drop', function(e) {
        var files = e.dataTransfer.files;
        if (files.length > 0) {
            fotoInput.files = files;
            fotoInput.dispatchEvent(new Event('change'));
        }
    });

    submitBtn.addEventListener('click', function() {
        hideError();

        if (typeof OCREngine === 'undefined') {
            showError('OCR engine belum siap. Pastikan koneksi internet aktif untuk memuat Tesseract.js.');
            return;
        }

        if (!selectedFile) { showError('Pilih foto terlebih dahulu.'); return; }
        if (!kelasSelect.value) { showError('Pilih kelas terlebih dahulu.'); return; }

        ocrProgress.classList.add('active');
        submitBtn.disabled = true;

        var reader = new FileReader();
        reader.onload = function(ev) {
            var imageSrc = ev.target.result;
            runOCRPipeline(imageSrc);
        };
        reader.readAsDataURL(selectedFile);
    });

    function updateProgress(pct, msg) {
        progressBar.style.width = pct + '%';
        progressPct.textContent = pct + '%';
        if (msg) progressMsg.textContent = msg;
    }

    async function runOCRPipeline(imageSrc) {
        try {
            updateProgress(1, 'Menyiapkan OCR engine...');

            var ocrResult = await OCREngine.processImage(imageSrc, updateProgress);

            if (!ocrResult.success) {
                ocrProgress.classList.remove('active');
                submitBtn.disabled = false;
                showError(ocrResult.error || 'Gagal membaca foto.');
                return;
            }

            updateProgress(95, 'Mengirim hasil ke server...');

            var formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('kelas_id', kelasSelect.value);
            formData.append('bulan', bulanSelect.value);
            formData.append('tahun', tahunInput.value);
            formData.append('ocr_data', JSON.stringify(ocrResult));

            var response = await fetch('{{ route("absensi.import.browser") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            var result = await response.json();

            if (result.redirect) {
                updateProgress(100, 'Selesai!');
                window.location.href = result.redirect;
                return;
            }

            ocrProgress.classList.remove('active');
            submitBtn.disabled = false;
            showError(result.error || 'Terjadi kesalahan.');

        } catch (err) {
            ocrProgress.classList.remove('active');
            submitBtn.disabled = false;
            showError('Error: ' + (err.message || err));
        }
    }
});
</script>
@endpush
@endsection
