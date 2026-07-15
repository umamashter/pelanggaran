@extends('layouts.main')
@section('title', 'Pengumuman')

@section('content')
<style>
.page-title-content { display: none !important; }
.content-preview {
    max-height: 60px; overflow: hidden;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
}
.status-badge { font-size: 11px; padding: 3px 12px; border-radius: 20px; font-weight: 500; }
.status-draft { background: #fef3c7; color: #d97706; }
.status-published { background: #dcfce7; color: #16a34a; }
</style>

<div class="container-fluid px-0 py-3" style="font-family:'Inter','Poppins',system-ui,sans-serif;">
    <div class="card border-0 shadow-sm mb-4" style="border-radius:16px;">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="header-icon"><i class="fas fa-bullhorn"></i></div>
                    <div>
                        <h4 class="mb-1 fw-bold" style="color:var(--ms-text);font-size:20px;">Pengumuman</h4>
                        <div class="d-flex flex-wrap gap-2 mt-1">
                            <span class="badge-modern" style="background:#f0fdf4;color:#16a34a;">
                                <i class="fas fa-list me-1"></i> {{ $pengumuman->total() }} Pengumuman
                            </span>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-header-ms btn-simpan-ms btn-compact" data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <i class="fas fa-plus me-1"></i> Tambah
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
                <table class="table table-ms display align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width:50px;">No</th>
                            <th>Judul</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th style="width:140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengumuman as $i => $p)
                        <tr>
                            <td>{{ ($pengumuman->currentPage() - 1) * $pengumuman->perPage() + $loop->iteration }}</td>
                            <td>
                                <span style="font-weight:600;color:#1e293b;">{{ $p->judul }}</span>
                                <div class="content-preview text-muted" style="font-size:12px;">{{ strip_tags($p->isi) }}</div>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('d F Y') }}</td>
                            <td>
                                <span class="status-badge {{ $p->status == 'Published' ? 'status-published' : 'status-draft' }}">
                                    {{ $p->status == 'Published' ? 'Terbit' : 'Draft' }}
                                </span>
                            </td>
                            <td>
                                <div class="action-group-ms">
                                    <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $p->id }}">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <form action="{{ route('pengumuman.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus pengumuman ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-outline-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        {{-- Modal Edit --}}
                        <div class="modal fade" id="modalEdit{{ $p->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <form action="{{ route('pengumuman.update', $p->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-header" style="background:linear-gradient(135deg,#16a34a,#22c55e);">
                                            <h5 class="modal-title text-white fw-bold"><i class="fas fa-edit me-1"></i> Edit Pengumuman</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Judul</label>
                                                <input type="text" name="judul" class="form-control" value="{{ $p->judul }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Tanggal</label>
                                                <input type="date" name="tanggal" class="form-control" value="{{ $p->tanggal->format('Y-m-d') }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Status</label>
                                                <select name="status" class="form-select" required>
                                                    <option value="Draft" {{ $p->status == 'Draft' ? 'selected' : '' }}>Draft</option>
                                                    <option value="Published" {{ $p->status == 'Published' ? 'selected' : '' }}>Terbit</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Isi</label>
                                                <textarea name="isi" class="form-control" rows="8" required>{{ $p->isi }}</textarea>
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
                                <i class="fas fa-bullhorn" style="font-size:3rem;color:#d1d5db;"></i>
                                <p class="mt-3 mb-0 fw-semibold text-muted">Belum ada pengumuman</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $pengumuman->links() }}</div>
        </div>
    </div>
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="{{ route('pengumuman.store') }}" method="POST">
                @csrf
                <div class="modal-header" style="background:linear-gradient(135deg,#16a34a,#22c55e);">
                    <h5 class="modal-title text-white fw-bold"><i class="fas fa-plus me-1"></i> Tambah Pengumuman</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul</label>
                        <input type="text" name="judul" class="form-control" placeholder="Judul pengumuman" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="Draft">Draft</option>
                            <option value="Published" selected>Terbit</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Isi</label>
                        <textarea name="isi" class="form-control" rows="8" placeholder="Isi pengumuman..." required></textarea>
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
@endsection
