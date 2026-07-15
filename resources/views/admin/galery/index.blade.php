@extends('layouts.main')
@section('title', 'Galery')

@section('content')
<style>
.page-title-content { display: none !important; }
:root {
    --ms-primary: #16a34a;
    --ms-primary-dark: #15803d;
    --ms-primary-light: #dcfce7;
    --ms-bg: #f5f7fb;
    --ms-border: #e2e8f0;
    --ms-text: #1e293b;
    --ms-text-soft: #64748b;
}
.header-icon {
    width: 52px; height: 52px; border-radius: 14px;
    background: linear-gradient(135deg, #16a34a, #22c55e);
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 24px;
    box-shadow: 0 4px 14px rgba(22,163,74,.3); flex-shrink: 0;
}
.badge-modern {
    display: inline-flex; align-items: center;
    padding: 4px 14px; border-radius: 20px;
    font-size: 12px; font-weight: 500; white-space: nowrap;
}
.btn-header-ms {
    padding: 8px 20px; border-radius: 10px; font-size: 13px;
    font-weight: 600; transition: all .25s; white-space: nowrap;
    display: inline-flex; align-items: center; gap: 6px;
    border: none; height: auto;
}
.btn-header-ms:hover { transform: translateY(-2px); }
.btn-header-ms:active { transform: translateY(0); }
.btn-header-ms.btn-tambah-ms {
    background: #fff; border: 1.5px solid var(--ms-border); color: #475569;
}
.btn-header-ms.btn-tambah-ms:hover {
    border-color: #16a34a; color: #16a34a;
    background: #f0fdf4; box-shadow: 0 3px 8px rgba(0,0,0,.08);
}
.table-card { border: none; border-radius: 18px; box-shadow: 0 4px 16px rgba(0,0,0,.06), 0 2px 8px rgba(0,0,0,.04); }
.alert-modern-ms {
    border-radius: 12px; padding: 12px 18px;
    font-size: 13px; border: none;
}
.btn-aksi {
    padding: 5px 12px; border-radius: 8px; font-size: 11px;
    font-weight: 600; transition: all .2s;
    border: none; display: inline-flex; align-items: center; gap: 4px;
}
.btn-aksi:hover { transform: translateY(-1px); }
.btn-aksi-edit { background: #dbeafe; color: #2563eb; }
.btn-aksi-edit:hover { background: #bfdbfe; }
.btn-aksi-hapus { background: #fee2e2; color: #dc2626; }
.btn-aksi-hapus:hover { background: #fecaca; }
.status-badge { font-size: 11px; padding: 3px 12px; border-radius: 20px; font-weight: 500; }
.status-draft { background: #fef3c7; color: #d97706; }
.status-published { background: #dcfce7; color: #16a34a; }
.galery-thumb {
    width: 56px; height: 42px; border-radius: 8px; object-fit: cover;
    box-shadow: 0 2px 6px rgba(0,0,0,.1);
}
.content-preview {
    max-height: 40px; overflow: hidden;
    display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical;
}
.upload-preview {
    max-width: 100%; max-height: 180px; border-radius: 10px;
    object-fit: cover; box-shadow: 0 2px 8px rgba(0,0,0,.1);
}
@media (max-width: 768px) {
    .table-card { border-radius: 12px; }
}
</style>

<div class="container-fluid px-0 py-3" style="font-family:'Inter','Poppins',system-ui,sans-serif;">
    <div class="card border-0 shadow-sm mb-4" style="border-radius:16px;">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="header-icon"><i class="fas fa-images"></i></div>
                    <div>
                        <h4 class="mb-1 fw-bold" style="color:var(--ms-text);font-size:20px;">Galery</h4>
                        <div class="d-flex flex-wrap gap-2 mt-1">
                            <span class="badge-modern" style="background:#f0fdf4;color:#16a34a;">
                                <i class="fas fa-list me-1"></i> {{ $galery->total() }} Foto
                            </span>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-header-ms btn-tambah-ms" data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <i class="fas fa-plus me-1"></i> Tambah Foto
                </button>
            </div>
        </div>
    </div>

    <div class="card table-card">
        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-modern-ms alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size:13px;">
                    <thead class="table-light">
                        <tr>
                            <th style="width:50px;">No</th>
                            <th style="width:80px;">Foto</th>
                            <th>Judul</th>
                            <th>Status</th>
                            <th style="width:140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($galery as $i => $g)
                        <tr>
                            <td>{{ ($galery->currentPage() - 1) * $galery->perPage() + $loop->iteration }}</td>
                            <td>
                                <img src="{{ asset($g->foto) }}" alt="{{ $g->judul }}" class="galery-thumb">
                            </td>
                            <td>
                                <span style="font-weight:600;color:#1e293b;">{{ $g->judul }}</span>
                                @if($g->deskripsi)
                                <div class="content-preview text-muted" style="font-size:12px;">{{ strip_tags($g->deskripsi) }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="status-badge {{ $g->status == 'Published' ? 'status-published' : 'status-draft' }}">
                                    {{ $g->status == 'Published' ? 'Terbit' : 'Draft' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn-aksi btn-aksi-edit" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $g->id }}">
                                        <i class="fas fa-pen"></i> Edit
                                    </button>
                                    <form action="{{ route('galery.destroy', $g->id) }}" method="POST" onsubmit="return confirm('Hapus foto ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn-aksi btn-aksi-hapus"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        {{-- Modal Edit --}}
                        <div class="modal fade" id="modalEdit{{ $g->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <form action="{{ route('galery.update', $g->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf @method('PUT')
                                        <div class="modal-header" style="background:linear-gradient(135deg,#16a34a,#22c55e);">
                                            <h5 class="modal-title text-white fw-bold"><i class="fas fa-edit me-1"></i> Edit Foto</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="text-center mb-3">
                                                <img src="{{ asset($g->foto) }}" class="upload-preview" alt="preview">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Ganti Foto (opsional)</label>
                                                <input type="file" name="foto" class="form-control" accept="image/jpg,image/jpeg,image/png,image/webp">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Judul</label>
                                                <input type="text" name="judul" class="form-control" value="{{ $g->judul }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Deskripsi</label>
                                                <textarea name="deskripsi" class="form-control" rows="4">{{ $g->deskripsi }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Status</label>
                                                <select name="status" class="form-select" required>
                                                    <option value="Draft" {{ $g->status == 'Draft' ? 'selected' : '' }}>Draft</option>
                                                    <option value="Published" {{ $g->status == 'Published' ? 'selected' : '' }}>Terbit</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn" style="background:#16a34a;color:#fff;border:none;border-radius:10px;">
                                                <i class="fas fa-save me-1"></i> Simpan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="fas fa-images" style="font-size:3rem;color:#d1d5db;"></i>
                                <p class="mt-3 mb-0 fw-semibold text-muted">Belum ada foto galeri</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $galery->links() }}</div>
        </div>
    </div>
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="{{ route('galery.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header" style="background:linear-gradient(135deg,#16a34a,#22c55e);">
                    <h5 class="modal-title text-white fw-bold"><i class="fas fa-plus me-1"></i> Tambah Foto</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Foto</label>
                        <input type="file" name="foto" id="fotoInput" class="form-control" accept="image/jpg,image/jpeg,image/png,image/webp" required>
                        <img id="fotoPreview" class="upload-preview mt-2" style="display:none;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul</label>
                        <input type="text" name="judul" class="form-control" placeholder="Judul foto" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="4" placeholder="Deskripsi singkat (opsional)"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="Draft">Draft</option>
                            <option value="Published" selected>Terbit</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn" style="background:#16a34a;color:#fff;border:none;border-radius:10px;">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
(function() {
    var input = document.getElementById('fotoInput');
    var preview = document.getElementById('fotoPreview');
    if (!input || !preview) return;
    input.addEventListener('change', function() {
        var file = this.files[0];
        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
        }
    });
})();
</script>
@endsection
