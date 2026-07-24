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

    @if(session('error'))
    <div class="alert alert-danger d-flex align-items-center gap-2" style="border-radius:12px;font-size:13px;border:none;background:linear-gradient(135deg,#fef2f2,#fee2e2);color:#991b1b;">
        <i class="fas fa-exclamation-circle"></i>
        <div>{{ session('error') }}</div>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger d-flex align-items-center gap-2" style="border-radius:12px;font-size:13px;border:none;background:linear-gradient(135deg,#fef2f2,#fee2e2);color:#991b1b;">
        <i class="fas fa-exclamation-circle"></i>
        <div>
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="info-card-modern mb-4">
        <div class="info-item"><i class="fas fa-chalkboard"></i><span><strong>Kelas :</strong> {{ $absensi->kelas?->nama_kelas ?? '-' }}</span></div>
        <div class="info-item"><i class="fas fa-graduation-cap"></i><span><strong>Tahun Ajaran :</strong> {{ $absensi->tahunAjaran?->tahun_ajaran ?? '-' }}</span></div>
        <div class="info-item"><i class="fas fa-users"></i><span><strong>Jumlah Siswa :</strong> {{ $siswas->count() }}</span></div>
    </div>

    <div class="card form-card">
        <div class="card-body">
            <form id="absensiForm" action="{{ route('absensi.update', $absensi->id) }}" method="POST">
                @csrf
                @method('PUT')
                @if(!empty($singleSiswaId))
                <input type="hidden" name="siswa" value="{{ $singleSiswaId }}">
                @endif

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
                    <button type="button" id="btnSimpan" class="btn-update-ms">
                        <i class="fas fa-save"></i> {{ $siswas->count() === 1 ? 'Update Status Siswa' : 'Update Absensi' }}
                    </button>
                    <a href="{{ route('absensi.riwayat') }}" class="btn-kembali-ms">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;border:none;box-shadow:0 8px 32px rgba(0,0,0,.12);">
            <div class="modal-body p-4">
                <div class="text-center mb-3">
                    <div class="d-inline-flex align-items-center justify-content-center" style="width:56px;height:56px;border-radius:14px;background:linear-gradient(135deg,#f59e0b,#d97706);box-shadow:0 4px 14px rgba(245,158,11,.3);">
                        <i class="fas fa-clipboard-check text-white" style="font-size:24px;"></i>
                    </div>
                    <h5 class="mt-3 fw-bold" style="color:#1e293b;">Konfirmasi Update Absensi</h5>
                </div>
                <div style="background:#f8fafc;border-radius:12px;padding:16px 20px;margin-bottom:16px;">
                    <div class="d-flex justify-content-between mb-2">
                        <span style="color:#64748b;font-size:13px;"><i class="fas fa-chalkboard me-1"></i>Kelas</span>
                        <strong style="color:#1e293b;font-size:13px;">{{ $absensi->kelas?->nama_kelas ?? '-' }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span style="color:#64748b;font-size:13px;"><i class="fas fa-calendar me-1"></i>Tanggal</span>
                        <strong style="color:#1e293b;font-size:13px;">{{ $absensi->tanggal->translatedFormat('d F Y') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span style="color:#64748b;font-size:13px;"><i class="fas fa-users me-1"></i>Total Siswa</span>
                        <strong style="color:#1e293b;font-size:13px;">{{ $siswas->count() }} Siswa</strong>
                    </div>
                </div>
                <div class="d-flex justify-content-center gap-3 mb-3">
                    <div class="text-center">
                        <div style="font-size:22px;font-weight:700;color:#16a34a;" id="confirmH">0</div>
                        <div style="font-size:11px;font-weight:600;color:#64748b;text-transform:uppercase;">Hadir</div>
                    </div>
                    <div class="text-center">
                        <div style="font-size:22px;font-weight:700;color:#d97706;" id="confirmI">0</div>
                        <div style="font-size:11px;font-weight:600;color:#64748b;text-transform:uppercase;">Izin</div>
                    </div>
                    <div class="text-center">
                        <div style="font-size:22px;font-weight:700;color:#dc2626;" id="confirmS">0</div>
                        <div style="font-size:11px;font-weight:600;color:#64748b;text-transform:uppercase;">Sakit</div>
                    </div>
                    <div class="text-center">
                        <div style="font-size:22px;font-weight:700;color:#64748b;" id="confirmA">0</div>
                        <div style="font-size:11px;font-weight:600;color:#64748b;text-transform:uppercase;">Alpha</div>
                    </div>
                </div>
                <p class="text-center" style="color:#64748b;font-size:13px;">Apakah Anda yakin ingin memperbarui absensi ini?</p>
            </div>
            <div class="modal-footer border-0 pt-0 pb-4 px-4" style="justify-content:center;gap:8px;">
                <button type="button" class="btn" data-bs-dismiss="modal" style="padding:8px 20px;border-radius:10px;font-size:13px;font-weight:500;border:1.5px solid #e2e8f0;background:#fff;color:#475569;">Batal</button>
                <button type="button" class="btn" id="confirmBtn" style="padding:8px 20px;border-radius:10px;font-size:13px;font-weight:600;border:none;background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;box-shadow:0 2px 8px rgba(245,158,11,.25);">
                    <i class="fas fa-save me-1"></i>Ya, Update Absensi
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('absensiForm');
    var btnSimpan = document.getElementById('btnSimpan');
    var confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));

    btnSimpan.addEventListener('click', function() {
        var h = 0, i = 0, s = 0, a = 0;
        document.querySelectorAll('select[name^="status"]').forEach(function(sel) {
            switch(sel.value) {
                case 'H': h++; break;
                case 'I': i++; break;
                case 'S': s++; break;
                case 'A': a++; break;
            }
        });

        document.getElementById('confirmH').textContent = h;
        document.getElementById('confirmI').textContent = i;
        document.getElementById('confirmS').textContent = s;
        document.getElementById('confirmA').textContent = a;

        confirmModal.show();
    });

    document.getElementById('confirmBtn').addEventListener('click', function() {
        confirmModal.hide();
        form.submit();
    });
});
</script>
@endpush
