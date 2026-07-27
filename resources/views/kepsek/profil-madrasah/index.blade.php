@extends('layouts.main')
@section('title', 'Profil Madrasah (Read Only)')
@section('content')
<style>
    .page-title-content { display: none !important; }
    :root { --ms-primary: #16a34a; --ms-primary-dark: #15803d; --ms-primary-light: #dcfce7; }
    .profil-page { font-family: 'Inter', 'Poppins', system-ui, sans-serif; margin-top: 22px; }
    .form-card { border: none; border-radius: 20px; box-shadow: 0 4px 16px rgba(0,0,0,.05), 0 2px 8px rgba(0,0,0,.03); overflow: hidden; }
    .form-card .card-body { padding: 24px 28px; }
    .form-section-title { font-size: 15px; font-weight: 700; color: #0f172a; margin-bottom: 16px; padding-bottom: 8px; border-bottom: 2px solid var(--ms-primary-light); }
    .form-label-custom { font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px; }
    .detail-value { font-size: 14px; color: #1e293b; padding: 10px 16px; background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; min-height: 46px; display: flex; align-items: center; }
    .profil-tabs { border-bottom: 2px solid #e2e8f0; margin-bottom: 24px; }
    .profil-tabs .nav-link { font-size: 13px; font-weight: 600; color: #94a3b8; padding: 12px 20px; border: none; border-bottom: 2px solid transparent; margin-bottom: -2px; }
    .profil-tabs .nav-link.active { color: var(--ms-primary); border-bottom-color: var(--ms-primary); background: none; }
    .profil-tabs .nav-link i { margin-right: 6px; }
</style>

<div class="profil-page">
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4 d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon"><i class="fas fa-building"></i></div>
                <div>
                    <h4 class="mb-1 fw-bold" style="color: var(--ms-text); font-size: 20px;">Profil Madrasah</h4>
                    <span class="badge-modern badge-ta"><i class="fas fa-info-circle me-1"></i>Informasi Umum</span>
                </div>
            </div>
            <div>
                <span style="font-size:11px;font-weight:600;padding:4px 12px;border-radius:20px;background:#f1f5f9;color:#64748b;display:inline-flex;align-items:center;gap:4px;"><i class="fas fa-eye"></i> Hanya Melihat</span>
            </div>
        </div>
    </div>

    <div class="card form-card mb-4">
        <div class="card-body">
            <ul class="nav profil-tabs" role="tablist">
                <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab-identitas"><i class="fas fa-university"></i> Identitas</a></li>
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-yayasan"><i class="fas fa-landmark"></i> Yayasan</a></li>
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-kontak"><i class="fas fa-map-marker-alt"></i> Alamat & Kontak</a></li>
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-kepsek"><i class="fas fa-user-tie"></i> Kepala Sekolah</a></li>
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-misi"><i class="fas fa-bullseye"></i> Visi & Misi</a></li>
            </ul>

            <div class="tab-content pt-3">
                <div class="tab-pane fade show active" id="tab-identitas">
                    <div class="row g-3">
                        @php $fields = [
                            'Nama Madrasah' => $profil->nama_madrasah,
                            'NPSN' => $profil->npsn,
                            'NSS' => $profil->nss,
                            'Status' => $profil->status_sekolah,
                            'Tahun Berdiri' => $profil->tahun_berdiri,
                            'Akreditasi' => $profil->akreditasi,
                        ]; @endphp
                        @foreach($fields as $label => $value)
                        <div class="col-md-6">
                            <label class="form-label-custom">{{ $label }}</label>
                            <div class="detail-value">{{ $value ?? '-' }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-yayasan">
                    <div class="row g-3">
                        @php $fields = [
                            'Nama Yayasan' => $profil->nama_yayasan,
                            'Nama Ketua Yayasan' => $profil->nama_ketua_yayasan,
                            'No HP Ketua' => $profil->no_hp_ketua_yayasan,
                        ]; @endphp
                        @foreach($fields as $label => $value)
                        <div class="col-md-6">
                            <label class="form-label-custom">{{ $label }}</label>
                            <div class="detail-value">{{ $value ?? '-' }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-kontak">
                    <div class="row g-3">
                        @php $fields = [
                            'Alamat' => $profil->alamat,
                            'Kelurahan/Desa' => $profil->kelurahan_desa,
                            'Kecamatan' => $profil->kecamatan,
                            'Kabupaten/Kota' => $profil->kabupaten_kota,
                            'Provinsi' => $profil->provinsi,
                            'Kode Pos' => $profil->kode_pos,
                            'Telepon' => $profil->telepon,
                            'Email' => $profil->email,
                            'Website' => $profil->website,
                        ]; @endphp
                        @foreach($fields as $label => $value)
                        <div class="col-md-6">
                            <label class="form-label-custom">{{ $label }}</label>
                            <div class="detail-value">{{ $value ?? '-' }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-kepsek">
                    <div class="row g-3">
                        @php $fields = [
                            'Nama Kepala Sekolah' => $profil->kepala_sekolah_nama,
                            'NIP' => $profil->kepala_sekolah_nip,
                            'No HP' => $profil->kepala_sekolah_hp,
                        ]; @endphp
                        @foreach($fields as $label => $value)
                        <div class="col-md-6">
                            <label class="form-label-custom">{{ $label }}</label>
                            <div class="detail-value">{{ $value ?? '-' }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-misi">
                    <div class="mb-4">
                        <label class="form-label-custom">Visi</label>
                        <div class="detail-value" style="white-space:pre-wrap;">{{ $profil->visi ?? '-' }}</div>
                    </div>
                    <div>
                        <label class="form-label-custom">Misi</label>
                        @if($profil->misi && $profil->misi->count())
                        <ol style="padding-left:20px;">
                            @foreach($profil->misi->sortBy('urutan') as $m)
                            <li style="padding:8px 0;border-bottom:1px solid #f1f5f9;font-size:14px;color:#1e293b;">{{ $m->item }}</li>
                            @endforeach
                        </ol>
                        @else
                        <div class="detail-value">-</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($profil->foto)
    <div class="card form-card">
        <div class="card-body">
            <div class="form-section-title"><i class="fas fa-image me-2"></i>Foto Profil</div>
            <img src="{{ asset('storage/' . $profil->foto) }}" alt="Foto Profil" style="max-width:300px;border-radius:12px;border:2px solid #e2e8f0;">
        </div>
    </div>
    @endif
</div>
@endsection
