@extends('layouts.main')
@section('title', 'Laporan Pelanggaran (Read Only)')
@push('css')
@include('component.admin.ms-style')
<style>
    .filter-card { border: none; border-radius: 18px; box-shadow: 0 4px 16px rgba(0,0,0,.06); }
    .filter-card .form-label { font-weight: 600; font-size: 13px; color: #475569; }
    .filter-card .form-select, .filter-card .form-control { border-radius: 10px; border: 1.5px solid var(--ms-border); font-size: 13px; height: 40px; padding: 0 14px; background: #f8fafc; }
    .filter-card .form-select:focus, .filter-card .form-control:focus { border-color: var(--ms-primary); box-shadow: 0 0 0 3px rgba(22,163,74,.1); }
    .btn-filter-ms { padding: 8px 20px; border-radius: 10px; font-size: 13px; font-weight: 600; border: none; background: linear-gradient(135deg, #16a34a, #22c55e); color: #fff; display: inline-flex; align-items: center; gap: 6px; height: 40px; }
</style>
@endpush

@section('content')
<div class="master-siswa-page">
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4 d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon"><i class="fas fa-file-alt"></i></div>
                <div>
                    <h4 class="mb-1 fw-bold" style="color: var(--ms-text); font-size: 20px;">Laporan Pelanggaran</h4>
                    <span class="badge-modern badge-ta"><i class="fas fa-list me-1"></i>{{ $histories->count() }} Data</span>
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

    <div class="card filter-card mb-4">
        <div class="card-body">
            <form method="GET" class="d-flex flex-wrap gap-3 align-items-end">
                <div class="flex-grow-1" style="min-width:150px;">
                    <label class="form-label">Tahun Ajaran</label>
                    <select name="tahun_ajaran" class="form-select">
                        @for($y = date('Y'); $y >= date('Y') - 3; $y--)
                        <option value="{{ $y }}/{{ $y+1 }}" {{ $tahunAjaran == "$y/".($y+1) ? 'selected' : '' }}>{{ $y }}/{{ $y+1 }}</option>
                        @endfor
                    </select>
                </div>
                <div style="min-width:150px;">
                    <label class="form-label">Bulan</label>
                    <input type="month" name="bulan" class="form-control" value="{{ $bulan }}">
                </div>
                <div style="min-width:150px;">
                    <label class="form-label">NISN</label>
                    <input type="text" name="nisn" class="form-control" value="{{ $nisn }}" placeholder="Cari NISN...">
                </div>
                <button type="submit" class="btn btn-filter-ms"><i class="fas fa-search"></i> Filter</button>
            </form>
        </div>
    </div>

    <div class="card table-card" style="border:none;border-radius:18px;box-shadow:0 4px 16px rgba(0,0,0,.06),0 2px 8px rgba(0,0,0,.04);">
        <div class="card-body">
            <table id="laporanTable" class="table table-ms display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th style="text-align:center;">No</th>
                        <th>Tanggal</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Pelanggaran</th>
                        <th style="text-align:center;">Poin</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($histories as $h)
                    <tr>
                        <td style="text-align:center;">{{ $loop->iteration }}</td>
                        <td>{{ \Carbon\Carbon::parse($h->tanggal)->translatedFormat('d M Y') }}</td>
                        <td>{{ $h->siswa?->nama ?? '-' }}</td>
                        <td>{{ $h->kelasSnapshot?->nama_kelas ?? '-' }}</td>
                        <td>{{ $h->rule?->nama_peraturan ?? '-' }}</td>
                        <td style="text-align:center;font-weight:700;color:#dc2626;">{{ $h->rule?->poin ?? 0 }}</td>
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
    $('#laporanTable').DataTable({
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
