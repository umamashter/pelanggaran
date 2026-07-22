@extends('layouts.main')
@section('title','Edit Absensi')
@section('content')
<style>
.page-title-content { display: none !important; }
:root { --ms-primary: #f59e0b; --ms-primary-dark: #d97706; --ms-primary-light: #fffbeb; --ms-border: #e2e8f0; --ms-text: #1e293b; }
.master-absensi-page { font-family: 'Inter', 'Poppins', system-ui, sans-serif; margin-top: 22px; }
.header-icon { width: 52px; height: 52px; border-radius: 14px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 24px; box-shadow: 0 4px 14px rgba(0,0,0,.15); flex-shrink: 0; }
.header-icon.warning { background: linear-gradient(135deg, #f59e0b, #d97706); box-shadow: 0 4px 14px rgba(245,158,11,.3); }
.badge-modern { display: inline-flex; align-items: center; padding: 4px 14px; border-radius: 20px; font-size: 12px; font-weight: 500; white-space: nowrap; }
.info-card-modern { background: #fffbeb; border-left: 4px solid #f59e0b; border-radius: 12px; padding: 16px 20px; display: flex; flex-wrap: wrap; align-items: center; gap: 16px 24px; font-size: 14px; color: #92400e; box-shadow: 0 1px 3px rgba(0,0,0,.06); }
.info-card-modern .info-item { display: flex; align-items: center; gap: 8px; }
.info-card-modern .info-item i { color: #f59e0b; font-size: 16px; width: 18px; text-align: center; }
.form-card { border: none; border-radius: 18px; box-shadow: 0 4px 16px rgba(0,0,0,.06), 0 2px 8px rgba(0,0,0,.04); }
.form-card .card-body { padding: 16px 20px 20px; }
.table-form { border-collapse: collapse; width: 100% !important; border: 1px solid var(--ms-border); border-radius: 12px; margin: 0 !important; }
.table-form thead th { background: #f8fafc; color: #475569; font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: .4px; padding: 11px 14px; border-bottom: 2px solid var(--ms-border); white-space: nowrap; text-align: center; }
.table-form tbody td { padding: 10px 14px; font-size: 13px; color: #334155; border-bottom: 1px solid #f1f5f9; vertical-align: middle; line-height: 1.5; }
.table-form tbody tr:last-child td { border-bottom: none; }
.table-form tbody tr:hover td { background: #f8fafc; }
.table-form .form-select, .table-form .form-control { border-radius: 8px; border: 1.5px solid var(--ms-border); font-size: 13px; height: 38px; padding: 0 28px 0 12px; background-color: #fff; transition: all .2s; color: var(--ms-text); cursor: pointer; width: 100%; }
.table-form .form-select:focus, .table-form .form-control:focus { border-color: var(--ms-primary); box-shadow: 0 0 0 3px rgba(245,158,11,.1); }
.btn-update-ms { padding: 10px 28px; border-radius: 10px; font-size: 14px; font-weight: 600; border: none; background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; transition: all .25s; box-shadow: 0 4px 14px rgba(245,158,11,.3); display: inline-flex; align-items: center; gap: 8px; }
.btn-update-ms:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(245,158,11,.4); color: #fff; }
.btn-kembali-ms { padding: 10px 20px; border-radius: 10px; font-size: 14px; font-weight: 500; border: 1.5px solid var(--ms-border); background: #fff; color: #475569; transition: all .25s; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; }
.btn-kembali-ms:hover { border-color: #f59e0b; color: #f59e0b; background: #fffbeb; }
@media (max-width: 768px) { .info-card-modern { flex-direction: column; gap: 8px; align-items: flex-start; } .table-form thead th { font-size: 11px; padding: 9px 8px; } .table-form tbody td { padding: 8px; font-size: 12px; } }
</style>

<div class="master-absensi-page">
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon warning"><i class="fas fa-edit"></i></div>
                <div>
                    <h4 class="mb-1 fw-bold" style="color: var(--ms-text); font-size: 20px;">Edit Absensi</h4>
                    <span class="badge-modern" style="background:#fffbeb;color:#d97706;">
                        <i class="fas fa-calendar-day me-1"></i>
                        {{ $absensi->tanggal->translatedFormat('d F Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="info-card-modern mb-4">
        <div class="info-item"><i class="fas fa-chalkboard"></i><span><strong>Kelas :</strong> {{ $absensi->kelas->nama_kelas }}</span></div>
        <div class="info-item"><i class="fas fa-graduation-cap"></i><span><strong>Tahun Ajaran :</strong> {{ $absensi->tahunAjaran->tahun_ajaran }}</span></div>
        <div class="info-item"><i class="fas fa-users"></i><span><strong>Jumlah Siswa :</strong> {{ $siswas->count() }}</span></div>
    </div>

    <div class="card form-card">
        <div class="card-body">
            <form action="{{ route('absensi.update', $absensi->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="table-responsive">
                    <table class="table table-form">
                        <thead>
                            <tr>
                                <th width="60">No</th>
                                <th>NISN</th>
                                <th>Nama Siswa</th>
                                <th width="180">Status</th>
                                <th width="200">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($siswas as $siswa)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $siswa->nisn }}</td>
                                <td>{{ $siswa->nama }}</td>
                                <td>
                                    <select name="status[{{ $siswa->id }}]" class="form-select">
                                        <option value="H" {{ ($detailMap[$siswa->id] ?? 'H') == 'H' ? 'selected' : '' }}>Hadir</option>
                                        <option value="I" {{ ($detailMap[$siswa->id] ?? 'H') == 'I' ? 'selected' : '' }}>Izin</option>
                                        <option value="S" {{ ($detailMap[$siswa->id] ?? 'H') == 'S' ? 'selected' : '' }}>Sakit</option>
                                        <option value="A" {{ ($detailMap[$siswa->id] ?? 'H') == 'A' ? 'selected' : '' }}>Alpha</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="keterangan[{{ $siswa->id }}]" class="form-control" placeholder="Keterangan (opsional)" value="{{ $keteranganMap[$siswa->id] ?? '' }}">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn-update-ms">
                        <i class="fas fa-save"></i> Update Absensi
                    </button>
                    <a href="{{ route('absensi.riwayat') }}" class="btn-kembali-ms">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
