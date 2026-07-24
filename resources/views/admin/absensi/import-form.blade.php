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
        <div><i class="fas fa-info-circle"></i> Unggah foto buku absensi. Sistem akan membaca simbol otomatis: <strong>"." = Hadir</strong>, I = Izin, S = Sakit, A = Alpha. Sel kosong akan ditandai sebagai "Perlu Diperiksa".</div>
    </div>

    <div class="card select-card">
        <div class="card-body">
            <div class="card-title"><i class="fas fa-upload me-2" style="color: var(--ms-primary);"></i>Form Import</div>

            <form action="{{ route('absensi.import.process') }}" method="POST" enctype="multipart/form-data" id="importForm">
                @csrf

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Kelas <span class="text-danger">*</span></label>
                        <select name="kelas_id" class="form-select" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" {{ old('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                {{ $kelas->nama_kelas }}
                            </option>
                            @endforeach
                        </select>
                        @error('kelas_id')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Bulan <span class="text-danger">*</span></label>
                        <select name="bulan" class="form-select" required>
                            @foreach($months as $num => $name)
                            <option value="{{ $num }}" {{ old('bulan', $currentMonth) == $num ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tahun <span class="text-danger">*</span></label>
                        <input type="number" name="tahun" class="form-control" value="{{ old('tahun', $currentYear) }}" min="2020" max="2050" required>
                    </div>
                </div>

                <label class="form-label">Foto Buku Absensi <span class="text-danger">*</span></label>
                <div class="upload-zone" id="uploadZone">
                    <input type="file" name="foto" accept="image/jpeg,image/png,image/webp" id="fotoInput" required>
                    <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                    <div class="upload-text">Klik atau seret foto ke sini</div>
                    <div class="upload-hint">Format: JPG, PNG, WebP. Maks. {{ config('ocr.max_file_size', 10) }} MB.</div>
                </div>

                <div class="upload-preview" id="uploadPreview">
                    <img id="previewImg" src="" alt="Preview">
                </div>

                @error('foto')<div class="text-danger mt-2"><small>{{ $message }}</small></div>@enderror

                <div class="d-flex gap-2 mt-4">
                    <a href="{{ route('absensi.index') }}" class="btn-kembali-ms">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn-simpan-ms" id="submitBtn" disabled>
                        <i class="fas fa-cogs"></i> Baca Foto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fotoInput = document.getElementById('fotoInput');
    const uploadZone = document.getElementById('uploadZone');
    const uploadPreview = document.getElementById('uploadPreview');
    const previewImg = document.getElementById('previewImg');
    const submitBtn = document.getElementById('submitBtn');
    const importForm = document.getElementById('importForm');

    fotoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        if (file.size > {{ config('ocr.max_file_size', 10) * 1024 * 1024 }}) {
            alert('Ukuran file melebihi {{ config('ocr.max_file_size', 10) }} MB.');
            fotoInput.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(ev) {
            previewImg.src = ev.target.result;
            uploadPreview.style.display = 'block';
            submitBtn.disabled = false;
        };
        reader.readAsDataURL(file);
    });

    // Drag and drop
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
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fotoInput.files = files;
            fotoInput.dispatchEvent(new Event('change'));
        }
    });

    // Submit loading state
    importForm.addEventListener('submit', function() {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
    });
});
</script>
@endpush
@endsection
