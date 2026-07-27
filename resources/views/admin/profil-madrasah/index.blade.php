@extends('layouts.main')
@section('title', 'Profil Madrasah')
@section('content')
<style>
.page-title-content { display: none !important; }
:root { --ms-primary: #16a34a; --ms-primary-dark: #15803d; --ms-primary-light: #dcfce7; }
.profil-page { font-family: 'Inter', 'Poppins', system-ui, sans-serif; margin-top: 22px; }
.form-card { border: none; border-radius: 20px; box-shadow: 0 4px 16px rgba(0,0,0,.05), 0 2px 8px rgba(0,0,0,.03); overflow: hidden; }
.form-card .card-body { padding: 24px 28px; }
.form-section-title { font-size: 15px; font-weight: 700; color: #0f172a; margin-bottom: 16px; padding-bottom: 8px; border-bottom: 2px solid var(--ms-primary-light); }
.form-label-custom { font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px; }
.input-group-cu .form-control,
.input-group-cu .form-select {
    height: 46px; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 0 16px;
    font-size: 14px; color: #1e293b; background: #f8fafc; transition: all .25s; width: 100%; box-shadow: none;
}
.input-group-cu .form-control:focus,
.input-group-cu .form-select:focus { border-color: var(--ms-primary); box-shadow: 0 0 0 4px rgba(22,163,74,.1); background-color: #fff; }
.input-group-cu textarea.form-control { height: auto; padding: 12px 16px; }

/* Tabs */
.profil-tabs { border-bottom: 2px solid #e2e8f0; margin-bottom: 24px; }
.profil-tabs .nav-link {
    font-size: 13px; font-weight: 600; color: #94a3b8; padding: 12px 20px;
    border: none; border-bottom: 2px solid transparent; margin-bottom: -2px; transition: all .25s;
}
.profil-tabs .nav-link:hover { color: #64748b; }
.profil-tabs .nav-link.active { color: var(--ms-primary); border-bottom-color: var(--ms-primary); background: none; }
.profil-tabs .nav-link i { margin-right: 6px; font-size: 14px; }

/* Misi */
.misi-item { display: flex; gap: 8px; margin-bottom: 8px; align-items: flex-start; }
.misi-item .form-control { flex: 1; }
.misi-item .btn-remove-misi {
    width: 36px; height: 36px; border-radius: 10px; border: none; background: #fef2f2;
    color: #dc2626; display: flex; align-items: center; justify-content: center;
    font-size: 14px; cursor: pointer; transition: all .2s; flex-shrink: 0; margin-top: 5px;
}
.misi-item .btn-remove-misi:hover { background: #dc2626; color: #fff; }
.btn-add-misi {
    border: 2px dashed #cbd5e1; border-radius: 12px; padding: 10px; background: transparent;
    color: #94a3b8; font-size: 13px; font-weight: 600; cursor: pointer; transition: all .25s;
    width: 100%; display: flex; align-items: center; justify-content: center; gap: 6px;
}
.btn-add-misi:hover { border-color: var(--ms-primary); color: var(--ms-primary); background: var(--ms-primary-light); }

.btn-save {
    padding: 10px 32px; border: none; border-radius: 12px; font-size: 14px; font-weight: 600;
    background: linear-gradient(135deg, #16a34a, #15803d); color: #fff;
    box-shadow: 0 4px 14px rgba(22,163,74,.3); transition: all .3s; cursor: pointer;
}
.btn-save:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(22,163,74,.4); }

.alert-modern-ms { border: none; border-radius: 12px; padding: 14px 20px; font-size: 14px; margin-bottom: 20px; }
.alert-modern-ms.alert-success { background: #f0fdf4; color: #16a34a; border-left: 4px solid #16a34a; }
.current-foto { width: 120px; height: 120px; border-radius: 12px; object-fit: cover; border: 2px solid #e2e8f0; }
</style>

<div class="profil-page">
    <div class="d-flex align-items-center gap-3 mb-4">
        <div style="width:52px;height:52px;border-radius:14px;background:linear-gradient(135deg,#16a34a,#22c55e);display:flex;align-items:center;justify-content:center;color:#fff;font-size:24px;box-shadow:0 4px 14px rgba(22,163,74,.3);flex-shrink:0">
            <i class="fas fa-school"></i>
        </div>
        <div>
            <h4 class="fw-bold mb-1" style="color:#1e293b;font-size:20px">Profil Madrasah</h4>
            <p style="color:#64748b;font-size:13px;margin:0">Kelola informasi lengkap profil madrasah</p>
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

    <form action="{{ route('profil-madrasah.update') }}" method="POST" enctype="multipart/form-data" id="profilForm">
        @csrf

        {{-- Tab Navigation --}}
        <ul class="nav profil-tabs" id="profilTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="tab-identitas" data-bs-toggle="tab" data-bs-target="#panel-identitas" type="button" role="tab">
                    <i class="fas fa-id-card"></i>Identitas Sekolah
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-yayasan" data-bs-toggle="tab" data-bs-target="#panel-yayasan" type="button" role="tab">
                    <i class="fas fa-building"></i>Data Yayasan
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-kontak" data-bs-toggle="tab" data-bs-target="#panel-kontak" type="button" role="tab">
                    <i class="fas fa-map-marker-alt"></i>Alamat & Kontak
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-kepsek" data-bs-toggle="tab" data-bs-target="#panel-kepsek" type="button" role="tab">
                    <i class="fas fa-user-tie"></i>Kepala Sekolah
                </button>
            </li>
        </ul>

        <div class="tab-content" id="profilTabContent">

            {{-- Tab 1: Identitas Sekolah --}}
            <div class="tab-pane fade show active" id="panel-identitas" role="tabpanel">
                <div class="card form-card">
                    <div class="card-body">
                        <div class="form-section-title"><i class="fas fa-id-card me-2" style="color:var(--ms-primary)"></i>Identitas Sekolah / Madrasah</div>
                        <div class="mb-3">
                            <label class="form-label-custom">Nama Sekolah / Madrasah</label>
                            <div class="input-group-cu">
                                <input type="text" name="nama_madrasah" class="form-control" value="{{ old('nama_madrasah', $profil->nama_madrasah) }}" required>
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label-custom">NPSN</label>
                                <div class="input-group-cu">
                                    <input type="text" name="npsn" class="form-control" value="{{ old('npsn', $profil->npsn) }}" placeholder="Nomor Pokok Sekolah Nasional">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">NSM</label>
                                <div class="input-group-cu">
                                    <input type="text" name="nsm" class="form-control" value="{{ old('nsm', $profil->nsm) }}" placeholder="Nomor Statistik Madrasah">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">NIS / NSS</label>
                                <div class="input-group-cu">
                                    <input type="text" name="nis_nss" class="form-control" value="{{ old('nis_nss', $profil->nis_nss) }}" placeholder="Jika ada">
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-3">
                                <label class="form-label-custom">Jenjang Pendidikan</label>
                                <div class="input-group-cu">
                                    <select name="jenjang" class="form-select">
                                        <option value="">-- Pilih --</option>
                                        @foreach(['PAUD', 'TK', 'RA', 'MI', 'SD', 'Mts', 'SMP', 'MA', 'SMA', 'SMK'] as $j)
                                        <option value="{{ $j }}" {{ old('jenjang', $profil->jenjang) === $j ? 'selected' : '' }}>{{ $j }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label-custom">Status Sekolah</label>
                                <div class="input-group-cu">
                                    <select name="status_sekolah" class="form-select">
                                        <option value="">-- Pilih --</option>
                                        <option value="Negeri" {{ old('status_sekolah', $profil->status_sekolah) === 'Negeri' ? 'selected' : '' }}>Negeri</option>
                                        <option value="Swasta" {{ old('status_sekolah', $profil->status_sekolah) === 'Swasta' ? 'selected' : '' }}>Swasta</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label-custom">Status Akreditasi</label>
                                <div class="input-group-cu">
                                    <select name="status_akreditasi" class="form-select">
                                        <option value="">-- Pilih --</option>
                                        @foreach(['A', 'B', 'C', 'D', 'Belum Akreditasi'] as $a)
                                        <option value="{{ $a }}" {{ old('status_akreditasi', $profil->status_akreditasi) === $a ? 'selected' : '' }}>{{ $a }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label-custom">Tahun Berdiri</label>
                                <div class="input-group-cu">
                                    <input type="number" name="tahun_berdiri" class="form-control" value="{{ old('tahun_berdiri', $profil->tahun_berdiri) }}" placeholder="contoh: 2005" min="1900" max="{{ date('Y') + 1 }}">
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label-custom">Kurikulum yang Digunakan</label>
                                <div class="input-group-cu">
                                    <select name="kurikulum" class="form-select">
                                        <option value="">-- Pilih --</option>
                                        @foreach(['Kurikulum Merdeka', 'Kurikulum 2013', 'Kurikulum 2006 (KTSP)', 'Kurikulum Pesantren'] as $k)
                                        <option value="{{ $k }}" {{ old('kurikulum', $profil->kurikulum) === $k ? 'selected' : '' }}>{{ $k }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-0">
                            <label class="form-label-custom">Visi</label>
                            <div class="input-group-cu">
                                <textarea name="visi" class="form-control" rows="3" required>{{ old('visi', $profil->visi) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab 2: Data Yayasan --}}
            <div class="tab-pane fade" id="panel-yayasan" role="tabpanel">
                <div class="card form-card">
                    <div class="card-body">
                        <div class="form-section-title"><i class="fas fa-building me-2" style="color:var(--ms-primary)"></i>Data Yayasan</div>
                        <div class="mb-3">
                            <label class="form-label-custom">Nama Yayasan</label>
                            <div class="input-group-cu">
                                <input type="text" name="nama_yayasan" class="form-control" value="{{ old('nama_yayasan', $profil->nama_yayasan) }}" placeholder="Nama yayasan pemilik madrasah">
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label-custom">Nomor Akta Yayasan</label>
                                <div class="input-group-cu">
                                    <input type="text" name="nomor_akta_yayasan" class="form-control" value="{{ old('nomor_akta_yayasan', $profil->nomor_akta_yayasan) }}" placeholder="Nomor akta notaris">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">No. SK Kemenkumham</label>
                                <div class="input-group-cu">
                                    <input type="text" name="nomor_sk_kemenkumham" class="form-control" value="{{ old('nomor_sk_kemenkumham', $profil->nomor_sk_kemenkumham) }}" placeholder="SK Kementerian Hukum dan HAM">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">Tahun Berdiri Yayasan</label>
                                <div class="input-group-cu">
                                    <input type="number" name="tahun_berdiri_yayasan" class="form-control" value="{{ old('tahun_berdiri_yayasan', $profil->tahun_berdiri_yayasan) }}" placeholder="contoh: 2000" min="1900" max="{{ date('Y') + 1 }}">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label-custom">Alamat Yayasan</label>
                            <div class="input-group-cu">
                                <textarea name="alamat_yayasan" class="form-control" rows="2" placeholder="Alamat lengkap kantor yayasan">{{ old('alamat_yayasan', $profil->alamat_yayasan) }}</textarea>
                            </div>
                        </div>
                        <div class="mb-0">
                            <label class="form-label-custom">Nama Ketua Yayasan</label>
                            <div class="input-group-cu">
                                <input type="text" name="nama_ketua_yayasan" class="form-control" value="{{ old('nama_ketua_yayasan', $profil->nama_ketua_yayasan) }}" placeholder="Nama lengkap ketua yayasan">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab 3: Alamat & Kontak --}}
            <div class="tab-pane fade" id="panel-kontak" role="tabpanel">
                <div class="card form-card">
                    <div class="card-body">
                        <div class="form-section-title"><i class="fas fa-map-marker-alt me-2" style="color:var(--ms-primary)"></i>Alamat & Kontak</div>
                        <div class="mb-3">
                            <label class="form-label-custom">Alamat Lengkap</label>
                            <div class="input-group-cu">
                                <textarea name="alamat" class="form-control" rows="2" required>{{ old('alamat', $profil->alamat) }}</textarea>
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label-custom">Desa / Kelurahan</label>
                                <div class="input-group-cu">
                                    <input type="text" name="desa_kelurahan" class="form-control" value="{{ old('desa_kelurahan', $profil->desa_kelurahan) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">Kecamatan</label>
                                <div class="input-group-cu">
                                    <input type="text" name="kecamatan" class="form-control" value="{{ old('kecamatan', $profil->kecamatan) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">Kabupaten / Kota</label>
                                <div class="input-group-cu">
                                    <input type="text" name="kabupaten_kota" class="form-control" value="{{ old('kabupaten_kota', $profil->kabupaten_kota) }}">
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label-custom">Provinsi</label>
                                <div class="input-group-cu">
                                    <input type="text" name="provinsi" class="form-control" value="{{ old('provinsi', $profil->provinsi) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom">Kode Pos</label>
                                <div class="input-group-cu">
                                    <input type="text" name="kode_pos" class="form-control" value="{{ old('kode_pos', $profil->kode_pos) }}">
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label-custom">Telepon</label>
                                <div class="input-group-cu">
                                    <input type="text" name="telepon" class="form-control" value="{{ old('telepon', $profil->telepon) }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">Email</label>
                                <div class="input-group-cu">
                                    <input type="email" name="email" class="form-control" value="{{ old('email', $profil->email) }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">WhatsApp</label>
                                <div class="input-group-cu">
                                    <input type="text" name="whatsapp" class="form-control" value="{{ old('whatsapp', $profil->whatsapp) }}" placeholder="08xxxxxxxxxx">
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label-custom">Website</label>
                                <div class="input-group-cu">
                                    <input type="url" name="website" class="form-control" value="{{ old('website', $profil->website) }}" placeholder="https://">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom">Google Maps Embed URL</label>
                                <div class="input-group-cu">
                                    <input type="text" name="map_embed" class="form-control" value="{{ old('map_embed', $profil->map_embed) }}" placeholder="https://www.google.com/maps/embed?pb=...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab 4: Kepala Sekolah --}}
            <div class="tab-pane fade" id="panel-kepsek" role="tabpanel">
                <div class="card form-card">
                    <div class="card-body">
                        <div class="form-section-title"><i class="fas fa-user-tie me-2" style="color:var(--ms-primary)"></i>Data Kepala Sekolah / Madrasah</div>
                        <div class="mb-3">
                            <label class="form-label-custom">Nama Kepala Sekolah</label>
                            <div class="input-group-cu">
                                <input type="text" name="nama_kepala_sekolah" class="form-control" value="{{ old('nama_kepala_sekolah', $profil->nama_kepala_sekolah) }}" placeholder="Nama lengkap">
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label-custom">NIP / NIY</label>
                                <div class="input-group-cu">
                                    <input type="text" name="nip_niy" class="form-control" value="{{ old('nip_niy', $profil->nip_niy) }}" placeholder="Nomor Induk Pegawai / Yayasan">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">NPK</label>
                                <div class="input-group-cu">
                                    <input type="text" name="npk" class="form-control" value="{{ old('npk', $profil->npk) }}" placeholder="Jika ada">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">NUPTK</label>
                                <div class="input-group-cu">
                                    <input type="text" name="nuptk" class="form-control" value="{{ old('nuptk', $profil->nuptk) }}" placeholder="Nomor Unik Pendidik dan Tenaga Kependidikan">
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label-custom">No. SK Pengangkatan</label>
                                <div class="input-group-cu">
                                    <input type="text" name="nomor_sk_pengangkatan" class="form-control" value="{{ old('nomor_sk_pengangkatan', $profil->nomor_sk_pengangkatan) }}" placeholder="Nomor SK">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">Tanggal SK</label>
                                <div class="input-group-cu">
                                    <input type="date" name="tanggal_sk" class="form-control" value="{{ old('tanggal_sk', $profil->tanggal_sk ? $profil->tanggal_sk->format('Y-m-d') : '') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">Pendidikan Terakhir</label>
                                <div class="input-group-cu">
                                    <select name="pendidikan_terakhir" class="form-select">
                                        <option value="">-- Pilih --</option>
                                        @foreach(['SD', 'SMP', 'SMA/SMK', 'D1', 'D2', 'D3', 'D4', 'S1', 'S2', 'S3'] as $p)
                                        <option value="{{ $p }}" {{ old('pendidikan_terakhir', $profil->pendidikan_terakhir) === $p ? 'selected' : '' }}>{{ $p }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Foto & Misi (always visible below tabs) --}}
        <div class="row g-4 mt-2">
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
            <div class="col-lg-8">
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
        </div>

        {{-- Submit --}}
        <div class="col-12 text-end mt-4 mb-4">
            <button type="submit" class="btn-save">
                <i class="fas fa-save me-1"></i> Simpan Perubahan
            </button>
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
