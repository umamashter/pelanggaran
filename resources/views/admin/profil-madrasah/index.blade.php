@extends('layouts.main')
@section('title', 'Profil Madrasah')
@section('content')
<style>
.page-title-content {
    display: none !important;
}
:root {
    --ms-primary: #16a34a;
    --ms-primary-dark: #15803d;
    --ms-primary-light: #dcfce7;
}
.profil-page {
    font-family: 'Inter', 'Poppins', system-ui, sans-serif;
    margin-top: 22px;
}
.form-card {
    border: none;
    border-radius: 20px;
    box-shadow: 0 4px 16px rgba(0,0,0,.05), 0 2px 8px rgba(0,0,0,.03);
    overflow: hidden;
}
.form-card .card-body {
    padding: 24px 28px;
}
.form-section-title {
    font-size: 15px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 16px;
    padding-bottom: 8px;
    border-bottom: 2px solid var(--ms-primary-light);
}
.form-label-custom {
    font-size: 13px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 6px;
}
.input-group-cu {
    position: relative;
}
.input-group-cu .form-control,
.input-group-cu .form-select {
    height: 46px;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    padding: 0 16px;
    font-size: 14px;
    color: #1e293b;
    background: #f8fafc;
    transition: all .25s;
    width: 100%;
    box-shadow: none;
}
.input-group-cu .form-control:focus,
.input-group-cu .form-select:focus {
    border-color: var(--ms-primary);
    box-shadow: 0 0 0 4px rgba(22,163,74,.1);
    background-color: #fff;
}
.input-group-cu textarea.form-control {
    height: auto;
    padding: 12px 16px;
}
.misi-item {
    display: flex;
    gap: 8px;
    margin-bottom: 8px;
    align-items: flex-start;
}
.misi-item .form-control {
    flex: 1;
}
.misi-item .btn-remove-misi {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    border: none;
    background: #fef2f2;
    color: #dc2626;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    cursor: pointer;
    transition: all .2s;
    flex-shrink: 0;
    margin-top: 5px;
}
.misi-item .btn-remove-misi:hover {
    background: #dc2626;
    color: #fff;
}
.btn-add-misi {
    border: 2px dashed #cbd5e1;
    border-radius: 12px;
    padding: 10px;
    background: transparent;
    color: #94a3b8;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all .25s;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}
.btn-add-misi:hover {
    border-color: var(--ms-primary);
    color: var(--ms-primary);
    background: var(--ms-primary-light);
}
.btn-save {
    padding: 10px 32px;
    border: none;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    background: linear-gradient(135deg, #16a34a, #15803d);
    color: #fff;
    box-shadow: 0 4px 14px rgba(22,163,74,.3);
    transition: all .3s;
    cursor: pointer;
}
.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 28px rgba(22,163,74,.4);
}
.alert-modern-ms {
    border: none;
    border-radius: 12px;
    padding: 14px 20px;
    font-size: 14px;
    margin-bottom: 20px;
}
.alert-modern-ms.alert-success {
    background: #f0fdf4;
    color: #16a34a;
    border-left: 4px solid #16a34a;
}
.current-foto {
    width: 120px;
    height: 120px;
    border-radius: 12px;
    object-fit: cover;
    border: 2px solid #e2e8f0;
}
</style>

<div class="profil-page">
    <div class="d-flex align-items-center gap-3 mb-4">
        <div style="width:52px;height:52px;border-radius:14px;background:linear-gradient(135deg,#16a34a,#22c55e);display:flex;align-items:center;justify-content:center;color:#fff;font-size:24px;box-shadow:0 4px 14px rgba(22,163,74,.3);flex-shrink:0">
            <i class="fas fa-school"></i>
        </div>
        <div>
            <h4 class="fw-bold mb-1" style="color:#1e293b;font-size:20px">Profil Madrasah</h4>
            <p style="color:#64748b;font-size:13px;margin:0">Kelola informasi profil madrasah yang tampil di halaman depan</p>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-modern-ms alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-modern-ms alert-danger alert-dismissible fade show" style="background:#fef2f2;color:#dc2626;border-left-color:#dc2626">
        <i class="fas fa-exclamation-circle me-1"></i>
        @foreach($errors->all() as $err)
        {{ $err }}<br>
        @endforeach
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <form action="{{ route('profil-madrasah.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-4">

            {{-- Identitas --}}
            <div class="col-lg-8">
                <div class="card form-card">
                    <div class="card-body">
                        <div class="form-section-title"><i class="fas fa-info-circle me-2" style="color:var(--ms-primary)"></i>Identitas Madrasah</div>
                        <div class="mb-3">
                            <label class="form-label-custom">Nama Madrasah</label>
                            <div class="input-group-cu">
                                <input type="text" name="nama_madrasah" class="form-control" value="{{ $profil->nama_madrasah }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label-custom">Visi</label>
                            <div class="input-group-cu">
                                <textarea name="visi" class="form-control" rows="3" required>{{ $profil->visi }}</textarea>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label-custom">Alamat</label>
                            <div class="input-group-cu">
                                <textarea name="alamat" class="form-control" rows="2" required>{{ $profil->alamat }}</textarea>
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label-custom">Telepon</label>
                                <div class="input-group-cu">
                                    <input type="text" name="telepon" class="form-control" value="{{ $profil->telepon }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom">Email</label>
                                <div class="input-group-cu">
                                    <input type="email" name="email" class="form-control" value="{{ $profil->email }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label-custom">Google Maps Embed URL</label>
                            <div class="input-group-cu">
                                <input type="text" name="map_embed" class="form-control" value="{{ $profil->map_embed }}" placeholder="https://www.google.com/maps/embed?pb=...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Foto --}}
            <div class="col-lg-4">
                <div class="card form-card">
                    <div class="card-body">
                        <div class="form-section-title"><i class="fas fa-image me-2" style="color:var(--ms-primary)"></i>Foto Madrasah</div>
                        <div class="text-center mb-3">
                            @if($profil->foto)
                            <img src="{{ asset('storage/' . $profil->foto) }}" class="current-foto mb-2" alt="Foto">
                            @else
                            <div style="width:120px;height:120px;border-radius:12px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;margin:0 auto 8px;border:2px dashed #cbd5e1">
                                <i class="fas fa-image" style="font-size:36px;color:#94a3b8"></i>
                            </div>
                            @endif
                            <p style="font-size:12px;color:#94a3b8;margin-bottom:8px">Biarkan kosong jika tidak ingin mengganti</p>
                        </div>
                        <div class="input-group-cu">
                            <input type="file" name="foto" class="form-control" accept="image/*">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Misi --}}
            <div class="col-12">
                <div class="card form-card">
                    <div class="card-body">
                        <div class="form-section-title"><i class="fas fa-flag me-2" style="color:var(--ms-primary)"></i>Misi</div>
                        <div id="misiContainer">
                            @foreach($profil->misi as $m)
                            <div class="misi-item">
                                <input type="text" name="misi_items[]" class="form-control" value="{{ $m->item }}" placeholder="Butir misi..." required>
                                <button type="button" class="btn-remove-misi" onclick="this.parentElement.remove()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn-add-misi mt-2" onclick="tambahMisi()">
                            <i class="fas fa-plus"></i> Tambah Butir Misi
                        </button>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="col-12 text-end">
                <button type="submit" class="btn-save">
                    <i class="fas fa-save me-1"></i> Simpan Perubahan
                </button>
            </div>

        </div>
    </form>
</div>

<script>
    function tambahMisi() {
        const container = document.getElementById('misiContainer');
        const div = document.createElement('div');
        div.className = 'misi-item';
        div.innerHTML =
            '<input type="text" name="misi_items[]" class="form-control" placeholder="Butir misi..." required>' +
            '<button type="button" class="btn-remove-misi" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>';
        container.appendChild(div);
    }
</script>
@endsection
