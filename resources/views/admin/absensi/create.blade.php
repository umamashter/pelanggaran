@extends('layouts.main')
@section('title','Input Absensi')
@section('content')
<style>
.page-title-content { display: none !important; }
:root { --ms-primary: #16a34a; --ms-primary-dark: #15803d; --ms-primary-light: #dcfce7; --ms-bg: #f5f7fb; --ms-border: #e2e8f0; --ms-text: #1e293b; --ms-text-soft: #64748b; }
.master-absensi-page { font-family: 'Inter', 'Poppins', system-ui, sans-serif; margin-top: 22px; }
.header-icon { width: 52px; height: 52px; border-radius: 14px; background: linear-gradient(135deg, #16a34a, #22c55e); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 24px; box-shadow: 0 4px 14px rgba(22,163,74,.3); flex-shrink: 0; }
.badge-modern { display: inline-flex; align-items: center; padding: 4px 14px; border-radius: 20px; font-size: 12px; font-weight: 500; white-space: nowrap; }
.badge-ta { background: #f0fdf4; color: #16a34a; }
.select-card { border: none; border-radius: 18px; box-shadow: 0 4px 16px rgba(0,0,0,.06), 0 2px 8px rgba(0,0,0,.04); max-width: 600px; }
.select-card .card-body { padding: 24px 28px; }
.select-card .card-title { font-size: 16px; font-weight: 700; color: var(--ms-text); margin-bottom: 20px; }
.select-card .form-label { font-weight: 600; font-size: 13px; color: #475569; margin-bottom: 6px; }
.select-card .form-control, .select-card .form-select { border-radius: 10px; border: 1.5px solid var(--ms-border); font-size: 13px; height: 42px; padding: 0 14px; background-color: #f8fafc; transition: all .2s; color: var(--ms-text); }
.select-card .form-control:focus, .select-card .form-select:focus { border-color: var(--ms-primary); box-shadow: 0 0 0 3px rgba(22,163,74,.1); background-color: #fff; }
.info-card-modern { background: #f0fdf4; border-left: 4px solid #16a34a; border-radius: 12px; padding: 16px 20px; display: flex; flex-wrap: wrap; align-items: center; gap: 16px 24px; font-size: 14px; color: #166534; box-shadow: 0 1px 3px rgba(0,0,0,.06); }
.info-card-modern .info-item { display: flex; align-items: center; gap: 8px; }
.info-card-modern .info-item i { color: #16a34a; font-size: 16px; width: 18px; text-align: center; }
.form-card { border: none; border-radius: 18px; box-shadow: 0 4px 16px rgba(0,0,0,.06), 0 2px 8px rgba(0,0,0,.04); }
.form-card .card-body { padding: 16px 20px 20px; }
.table-form { border-collapse: collapse; width: 100% !important; border: 1px solid var(--ms-border); border-radius: 12px; margin: 0 !important; }
.table-form thead th { background: #f8fafc; color: #475569; font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: .4px; padding: 11px 14px; border-bottom: 2px solid var(--ms-border); white-space: nowrap; text-align: center; }
.table-form tbody td { padding: 10px 14px; font-size: 13px; color: #334155; border-bottom: 1px solid #f1f5f9; vertical-align: middle; line-height: 1.5; }
.table-form tbody tr:last-child td { border-bottom: none; }
.table-form tbody tr:hover td { background: #f8fafc; }
.table-form .form-select, .table-form .form-control { border-radius: 8px; border: 1.5px solid var(--ms-border); font-size: 13px; height: 38px; padding: 0 28px 0 12px; background-color: #fff; transition: all .2s; color: var(--ms-text); cursor: pointer; width: 100%; }
.table-form .form-select:focus, .table-form .form-control:focus { border-color: var(--ms-primary); box-shadow: 0 0 0 3px rgba(22,163,74,.1); }
.btn-simpan-ms { padding: 10px 28px; border-radius: 10px; font-size: 14px; font-weight: 600; border: none; background: linear-gradient(135deg, #16a34a, #22c55e); color: #fff; transition: all .25s; box-shadow: 0 4px 14px rgba(22,163,74,.3); display: inline-flex; align-items: center; gap: 8px; }
.btn-simpan-ms:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(22,163,74,.4); color: #fff; }
.btn-tampilkan-ms { padding: 10px 28px; border-radius: 10px; font-size: 14px; font-weight: 600; border: none; background: linear-gradient(135deg, #16a34a, #22c55e); color: #fff; transition: all .25s; box-shadow: 0 4px 14px rgba(22,163,74,.3); display: inline-flex; align-items: center; gap: 8px; }
.btn-tampilkan-ms:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(22,163,74,.4); color: #fff; }
.btn-kembali-ms { padding: 10px 20px; border-radius: 10px; font-size: 14px; font-weight: 500; border: 1.5px solid var(--ms-border); background: #fff; color: #475569; transition: all .25s; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; }
.btn-kembali-ms:hover { border-color: var(--ms-primary); color: var(--ms-primary); background: var(--ms-primary-light); }
@media (max-width: 768px) { .info-card-modern { flex-direction: column; gap: 8px; align-items: flex-start; } .table-form thead th { font-size: 11px; padding: 9px 8px; } .table-form tbody td { padding: 8px; font-size: 12px; } .select-card .card-body { padding: 20px; } }
</style>

<div class="master-absensi-page">
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon"><i class="fas fa-clipboard-list"></i></div>
                <div>
                    <h4 class="mb-1 fw-bold" style="color: var(--ms-text); font-size: 20px;">Input Absensi</h4>
                    <span class="badge-modern badge-ta">
                        <i class="fas fa-graduation-cap me-1"></i>{{ $tahunAktif->tahun_ajaran }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    @if(!isset($kelas))
    {{-- STEP 1: Pilih Kelas & Tanggal --}}
    <div class="card select-card">
        <div class="card-body">
            <div class="card-title"><i class="fas fa-calendar-check me-2" style="color:var(--ms-primary);"></i>Pilih Kelas & Tanggal</div>
            <form method="GET" action="{{ route('absensi.create') }}">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label"><i class="fas fa-chalkboard me-1" style="color:var(--ms-primary);"></i>Kelas <span class="text-danger">*</span></label>
                        <select name="kelas_id" class="form-select" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelasList as $item)
                            <option value="{{ $item->id }}" {{ request('kelas_id') == $item->id ? 'selected' : '' }}>{{ $item->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><i class="fas fa-calendar me-1" style="color:var(--ms-primary);"></i>Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal', now()->toDateString()) }}" required
                            @if($tahunAktif->tanggal_mulai) min="{{ $tahunAktif->tanggal_mulai }}" @endif
                            @if($tahunAktif->tanggal_selesai) max="{{ $tahunAktif->tanggal_selesai }}" @endif
                        >
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn-tampilkan-ms">
                        <i class="fas fa-search"></i> Tampilkan Siswa
                    </button>
                    <a href="{{ route('absensi.index') }}" class="btn-kembali-ms">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>

    @else
    {{-- STEP 2: Form Absensi --}}
    <div class="info-card-modern mb-4">
        <div class="info-item"><i class="fas fa-chalkboard"></i><span><strong>Kelas :</strong> {{ $kelas->nama_kelas }}</span></div>
        <div class="info-item"><i class="fas fa-calendar-day"></i><span><strong>Tanggal :</strong> {{ \Carbon\Carbon::parse(request('tanggal'))->translatedFormat('d F Y') }}</span></div>
        <div class="info-item"><i class="fas fa-users"></i><span><strong>Jumlah Siswa :</strong> {{ $siswas->count() }}</span></div>
    </div>

    @if($siswas->isEmpty())
    <div class="card form-card">
        <div class="card-body text-center py-5">
            <i class="fas fa-user-slash fa-3x mb-3" style="color:#cbd5e1;"></i>
            <h5 style="color:var(--ms-text-soft);">Tidak Ada Siswa Aktif</h5>
            <p style="color:var(--ms-text-soft);font-size:13px;">Tidak ditemukan siswa aktif di kelas {{ $kelas->nama_kelas }} pada tahun ajaran {{ $tahunAktif->tahun_ajaran }}.</p>
            <a href="{{ route('absensi.create') }}" class="btn-kembali-ms mt-2">
                <i class="fas fa-arrow-left"></i> Pilih Kelas Lain
            </a>
        </div>
    </div>
    @else
    <div class="card form-card">
        <div class="card-body">
            <form action="{{ route('absensi.store') }}" method="POST">
                @csrf
                <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">
                <input type="hidden" name="tanggal" value="{{ request('tanggal') }}">

                @if($existingAbsensi)
                <div class="alert alert-warning d-flex align-items-center gap-2" style="border-radius:10px;font-size:13px;">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>
                        Absensi untuk kelas ini pada tanggal <strong>{{ $existingAbsensi->tanggal->translatedFormat('d F Y') }}</strong> sudah ada. Data akan diupdate.
                        <a href="{{ route('absensi.edit', $existingAbsensi->id) }}" style="font-weight:600;text-decoration:underline;">Buka Halaman Edit</a>
                    </div>
                </div>
                @endif

                <div style="overflow-x:auto;">
                    <table class="table table-form">
                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th>NISN</th>
                                <th>Nama Siswa</th>
                                <th width="160">Status</th>
                                <th width="200">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($siswas as $siswa)
                            @php
                                $currentStatus = $existingAbsensi ? ($existingAbsensi->details->where('student_id', $siswa->id)->first()?->status ?? 'H') : 'H';
                                $currentKet = $existingAbsensi ? ($existingAbsensi->details->where('student_id', $siswa->id)->first()?->keterangan ?? '') : '';
                            @endphp
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $siswa->nisn }}</td>
                                <td>{{ $siswa->nama }}</td>
                                <td>
                                    <select name="status[{{ $siswa->id }}]" class="form-select">
                                        <option value="H" {{ $currentStatus == 'H' ? 'selected' : '' }}>Hadir</option>
                                        <option value="I" {{ $currentStatus == 'I' ? 'selected' : '' }}>Izin</option>
                                        <option value="S" {{ $currentStatus == 'S' ? 'selected' : '' }}>Sakit</option>
                                        <option value="A" {{ $currentStatus == 'A' ? 'selected' : '' }}>Alpha</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="keterangan[{{ $siswa->id }}]" class="form-control" placeholder="Keterangan (opsional)" value="{{ $currentKet }}">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn-simpan-ms">
                        <i class="fas fa-save"></i> {{ $existingAbsensi ? 'Update Absensi' : 'Simpan Absensi' }}
                    </button>
                    <a href="{{ route('absensi.create') }}" class="btn-kembali-ms">
                        <i class="fas fa-arrow-left"></i> Pilih Kelas Lain
                    </a>
                </div>
            </form>
        </div>
    </div>
    @endif
    @endif
</div>
@endsection
