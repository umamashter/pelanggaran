@extends('layouts.main')
@section('title','Absensi Guru (Read Only)')
@section('content')
<style>
    .page-title-content { display: none !important; }
    :root { --ms-primary: #16a34a; --ms-primary-dark: #15803d; --ms-primary-light: #dcfce7; --ms-border: #e2e8f0; --ms-text: #1e293b; --ms-text-soft: #64748b; }
    .master-absensi-page { font-family: 'Inter', 'Poppins', system-ui, sans-serif; margin-top: 22px; }
    .header-icon { width: 52px; height: 52px; border-radius: 14px; background: linear-gradient(135deg, #16a34a, #22c55e); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 24px; box-shadow: 0 4px 14px rgba(22,163,74,.3); flex-shrink: 0; }
    .badge-modern { display: inline-flex; align-items: center; padding: 4px 14px; border-radius: 20px; font-size: 12px; font-weight: 500; white-space: nowrap; }
    .badge-ta { background: #f0fdf4; color: #16a34a; }
    .table-card { border: none; border-radius: 18px; box-shadow: 0 4px 16px rgba(0,0,0,.06), 0 2px 8px rgba(0,0,0,.04); }
    #absensiGuruTable { border-collapse: collapse; width: 100% !important; border: 1px solid var(--ms-border); border-radius: 12px; margin: 0 !important; }
    #absensiGuruTable thead th { background: #f8fafc; color: #475569; font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: .4px; padding: 11px 14px; border-bottom: 2px solid var(--ms-border); white-space: nowrap; text-align: center; }
    #absensiGuruTable tbody td { padding: 10px 14px; font-size: 13px; color: #334155; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    #absensiGuruTable tbody tr:hover td { background: #f8fafc; }
    .foto-thumb { width: 36px; height: 36px; border-radius: 8px; object-fit: cover; border: 2px solid var(--ms-border); }
    .badge-jarak { display: inline-block; padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: 600; }
    .badge-jarak.valid { background: #f0fdf4; color: #16a34a; }
</style>

<div class="master-absensi-page">
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="header-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                    <div>
                        <h4 class="mb-1 fw-bold" style="color: var(--ms-text); font-size: 20px;">Absensi Guru</h4>
                        <span class="badge-modern badge-ta"><i class="fas fa-clipboard-check me-1"></i>{{ $absensis->count() }} Data</span>
                        @if(isset($jenjang))
                        <span class="badge-modern" style="background:#f0fdf4;color:#16a34a;"><i class="fas fa-school me-1"></i>{{ $jenjang->kode }} - {{ $jenjang->nama_jenjang }}</span>
                        @endif
                    </div>
                </div>
                <div>
                    <span style="font-size:11px;font-weight:600;padding:4px 12px;border-radius:20px;background:#f1f5f9;color:#64748b;display:inline-flex;align-items:center;gap:4px;"><i class="fas fa-eye"></i> Hanya Melihat</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card table-card">
        <div class="card-body">
            <div style="font-size:16px;font-weight:700;color:var(--ms-text);margin-bottom:12px;display:flex;align-items:center;gap:10px;">
                <i class="fas fa-table" style="color:var(--ms-primary);font-size:18px;"></i> Riwayat Absensi
            </div>

            <table id="absensiGuruTable" class="table table-ms display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th style="text-align:center;">No</th>
                        <th>Tanggal</th>
                        <th>Nama Guru</th>
                        <th style="text-align:center;">Status</th>
                        <th style="text-align:center;">Foto</th>
                        <th style="text-align:center;">Jarak</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($absensis as $absensi)
                    <tr>
                        <td style="text-align:center;">{{ $loop->iteration }}</td>
                        <td>{{ \Carbon\Carbon::parse($absensi->tanggal)->translatedFormat('d M Y') }}</td>
                        <td>{{ $absensi->user->name ?? '-' }}</td>
                        <td style="text-align:center;">
                            <span class="badge-modern {{ $absensi->status == 'Hadir' ? 'badge-ta' : '' }}" style="{{ $absensi->status == 'Sakit' ? 'background:#fffbeb;color:#d97706;' : ($absensi->status == 'Izin' ? 'background:#eff6ff;color:#2563eb;' : '') }}">
                                {{ $absensi->status }}
                            </span>
                        </td>
                        <td style="text-align:center;">
                            @if($absensi->foto)
                                <img src="{{ asset('storage/' . $absensi->foto) }}" alt="Foto" class="foto-thumb">
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td style="text-align:center;">
                            @if($absensi->jarak)
                                <span class="badge-jarak valid">{{ $absensi->jarak }}m</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#absensiGuruTable').DataTable({
        pagingType: 'simple_numbers',
        responsive: false,
        scrollX: true,
        language: {
            url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian.json",
            paginate: { first: '«', previous: '‹', next: '›', last: '»' }
        },
        pageLength: 15,
        order: []
    });
});
</script>
@endpush
