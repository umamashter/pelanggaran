@extends('layouts.main')
@section('title','Detail Siswa')
@section('content')
<style>
.page-title-content {
    display: none !important;
}
:root {
    --ms-primary: #16a34a;
    --ms-primary-dark: #15803d;
    --ms-primary-light: #dcfce7;
    --ms-bg: #f5f7fb;
    --ms-border: #e2e8f0;
    --ms-text: #1e293b;
    --ms-text-soft: #64748b;
}
.detail-siswa-page {
    font-family: 'Inter', 'Poppins', system-ui, sans-serif;
    margin-top: 22px;
}
.student-card {
    border: none;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0,0,0,.06), 0 2px 8px rgba(0,0,0,.04);
}
.student-header {
    background: linear-gradient(135deg, #16a34a, #22c55e);
    color: #fff;
    padding: 30px;
}
.avatar {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    background: rgba(255,255,255,.2);
    color: #fff;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 42px;
    font-weight: bold;
    margin: auto;
    border: 3px solid rgba(255,255,255,.3);
}
.info-card {
    border: none;
    border-radius: 14px;
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
    height: 100%;
    border: 1px solid var(--ms-border);
    transition: all .2s;
}
.info-card:hover {
    box-shadow: 0 4px 16px rgba(0,0,0,.08);
    border-color: #cbd5e1;
}
.info-card .card-body {
    padding: 16px 20px;
}
.info-title {
    color: var(--ms-text-soft);
    font-size: 12px;
    margin-bottom: 4px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: .3px;
}
.info-value {
    font-weight: 600;
    color: var(--ms-text);
    font-size: 15px;
}
.section-title {
    font-weight: 700;
    color: var(--ms-text);
    font-size: 17px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.badge-modern {
    display: inline-flex;
    align-items: center;
    padding: 4px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    white-space: nowrap;
}
.badge-kelas-header {
    background: rgba(255,255,255,.2);
    color: #fff;
    font-size: 13px;
    font-weight: 600;
    padding: 6px 16px;
    border-radius: 20px;
    display: inline-block;
}
.kelas-aktif-card {
    border: none;
    border-radius: 14px;
    border-left: 4px solid var(--ms-primary);
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
}
.kelas-aktif-card .card-body {
    padding: 20px;
}
.kelas-aktif-label {
    font-size: 12px;
    font-weight: 500;
    color: var(--ms-text-soft);
    text-transform: uppercase;
    letter-spacing: .3px;
    margin-bottom: 4px;
}
.kelas-aktif-value {
    font-weight: 700;
    color: var(--ms-text);
    font-size: 16px;
}
.table-riwayat-wrap {
    border-radius: 14px;
    overflow: hidden;
    border: 1px solid var(--ms-border);
}
.table-riwayat-wrap table {
    margin-bottom: 0;
}
.table-riwayat-wrap thead th {
    background: #f8fafc;
    color: #475569;
    font-weight: 600;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: .4px;
    padding: 11px 14px;
    border-bottom: 2px solid var(--ms-border);
    white-space: nowrap;
    text-align: center;
}
.table-riwayat-wrap tbody td {
    padding: 10px 14px;
    font-size: 13px;
    color: #334155;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}
.table-riwayat-wrap tbody tr:last-child td {
    border-bottom: none;
}
.table-riwayat-wrap tbody tr:hover td {
    background: #f8fafc;
}
.table-riwayat-wrap tbody tr:nth-child(even) td {
    background: #fafbfc;
}
.table-riwayat-wrap tbody tr:nth-child(even):hover td {
    background: #f1f5f9;
}
.status-aktif {
    display: inline-block;
    padding: 4px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    background: #f0fdf4;
    color: #16a34a;
}
.status-nonaktif {
    display: inline-block;
    padding: 4px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    background: #f1f5f9;
    color: #64748b;
}
.btn-kembali-ms {
    padding: 10px 24px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 500;
    border: 1.5px solid var(--ms-border);
    background: #fff;
    color: #475569;
    transition: all .25s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
}
.btn-kembali-ms:hover {
    border-color: var(--ms-primary);
    color: var(--ms-primary);
    background: var(--ms-primary-light);
}
@media (max-width: 768px) {
    .student-header { padding: 20px; }
    .avatar { width: 70px; height: 70px; font-size: 32px; }
    .student-header h2 { font-size: 20px; }
    .info-card .card-body { padding: 12px 16px; }
    .kelas-aktif-card .card-body { padding: 16px; }
}
</style>

<div class="detail-siswa-page">

    <div class="card student-card">

        <div class="student-header">
            <div class="row align-items-center">
                <div class="col-md-2 text-center mb-3 mb-md-0">
                    <div class="avatar">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                </div>
                <div class="col-md-10">
                    <h2 class="mb-2 fw-bold">{{ $siswa->nama }}</h2>
                    <p class="mb-2" style="opacity:.9;">
                        <i class="fas fa-id-card me-1"></i>
                        NISN : {{ $siswa->nisn }}
                    </p>
                    @if($siswa->kelasAktif)
                    <span class="badge-kelas-header">
                        <i class="fas fa-chalkboard me-1"></i>
                        {{ $siswa->kelasAktif->kelas->tingkat }}
                        {{ $siswa->kelasAktif->kelas->nama_kelas }}
                    </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="card-body p-4">

            <h5 class="section-title">
                <i class="fas fa-user-circle" style="color:var(--ms-primary);"></i>
                Biodata Siswa
            </h5>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card info-card">
                        <div class="card-body">
                            <div class="info-title">Tempat, Tanggal Lahir</div>
                            <div class="info-value">{{ $siswa->ttl }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card info-card">
                        <div class="card-body">
                            <div class="info-title">Jenis Kelamin</div>
                            <div class="info-value">{{ $siswa->jk }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card info-card">
                        <div class="card-body">
                            <div class="info-title">Agama</div>
                            <div class="info-value">{{ $siswa->agama }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card info-card">
                        <div class="card-body">
                            <div class="info-title">Alamat</div>
                            <div class="info-value">{{ $siswa->alamat }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card info-card">
                        <div class="card-body">
                            <div class="info-title">Nama Ibu</div>
                            <div class="info-value">{{ $siswa->n_ibu }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card info-card">
                        <div class="card-body">
                            <div class="info-title">Nama Ayah</div>
                            <div class="info-value">{{ $siswa->n_ayah }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-4" style="border-color:var(--ms-border);">

            <h5 class="section-title">
                <i class="fas fa-school" style="color:var(--ms-primary);"></i>
                Kelas Aktif
            </h5>

            @if($siswa->kelasAktif)
            <div class="card kelas-aktif-card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="kelas-aktif-label">Kelas</div>
                            <div class="kelas-aktif-value">
                                {{ $siswa->kelasAktif->kelas->tingkat }}
                                {{ $siswa->kelasAktif->kelas->nama_kelas }}
                            </div>
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="kelas-aktif-label">Tahun Ajaran</div>
                            <div class="kelas-aktif-value">
                                {{ $siswa->kelasAktif->tahunAjaran->tahun_ajaran }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="kelas-aktif-label">Status</div>
                            <span class="status-aktif">
                                <i class="fas fa-check-circle me-1"></i>
                                Aktif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <hr class="my-4" style="border-color:var(--ms-border);">

            <h5 class="section-title">
                <i class="fas fa-history" style="color:#f59e0b;"></i>
                Riwayat Kelas
            </h5>

            <div class="table-responsive table-riwayat-wrap">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" width="60">No</th>
                            <th>Tahun Ajaran</th>
                            <th>Kelas</th>
                            <th class="text-center" width="140">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($siswa->riwayatKelas as $riwayat)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $riwayat->tahunAjaran->tahun_ajaran }}</td>
                            <td>
                                {{ $riwayat->kelas->tingkat }}
                                {{ $riwayat->kelas->nama_kelas }}
                            </td>
                            <td class="text-center">
                                @if($riwayat->aktif)
                                <span class="status-aktif">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Aktif
                                </span>
                                @else
                                <span class="status-nonaktif">
                                    <i class="fas fa-minus-circle me-1"></i>
                                    Tidak Aktif
                                </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                <a href="{{ url()->previous() }}" class="btn-kembali-ms">
                    <i class="fas fa-arrow-left"></i>
                    Kembali
                </a>
            </div>

        </div>

    </div>

</div>
@endsection
