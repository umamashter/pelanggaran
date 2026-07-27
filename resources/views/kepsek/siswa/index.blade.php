@extends('layouts.main')
@section('title', 'Data Siswa (Read Only)')
@push('css')
@include('component.admin.ms-style')
<style>
    .badge-ta { background: #eff6ff; color: #1d4ed8; }
    .badge-semester { background: #f0fdf4; color: #16a34a; }
    .ms-search-box { position: relative; }
    .ms-search-box .ms-search-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 13px; pointer-events: none; }
    .ms-search-box .ms-search-input { height: 32px; width: 240px; border: 1.5px solid var(--ms-border); border-radius: 10px; background: #f8fafc; color: var(--ms-text); font-size: 12px; padding: 0 12px 0 34px; outline: none; transition: all .2s; }
    .ms-search-box .ms-search-input:focus { border-color: var(--ms-primary); box-shadow: 0 0 0 3px rgba(22,163,74,.08); background: #fff; }
    @media (max-width: 575.98px) { .action-group-ms { display: inline-flex !important; gap: 4px !important; grid-template-columns: unset !important; } .action-group-ms .btn { width: 28px !important; height: 28px !important; font-size: 11px !important; } }
</style>
@endpush

@section('content')
<div class="master-siswa-page">
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4 d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon"><i class="fas fa-user-graduate"></i></div>
                <div>
                    <h4 class="mb-1 fw-bold" style="color: var(--ms-text); font-size: 20px;">Data Siswa</h4>
                    <div class="d-flex flex-wrap gap-2 mt-1">
                        <span class="badge-modern badge-ta"><i class="fas fa-graduation-cap me-1"></i>{{ $siswas->count() }} Siswa</span>
                        @if(isset($jenjang))
                        <span class="badge-modern" style="background:#f0fdf4;color:#16a34a;"><i class="fas fa-school me-1"></i>{{ $jenjang->kode }} - {{ $jenjang->nama_jenjang }}</span>
                        @endif
                        @if($semesterDipilih)
                        <span class="badge-modern badge-semester"><i class="fas fa-bookmark me-1"></i>{{ $semesterDipilih->nama ?? '-' }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div>
                <span style="font-size:11px;font-weight:600;padding:4px 12px;border-radius:20px;background:#f1f5f9;color:#64748b;display:inline-flex;align-items:center;gap:4px;"><i class="fas fa-eye"></i> Hanya Melihat</span>
            </div>
        </div>
    </div>

    <div class="card table-card" style="border:none;border-radius:18px;box-shadow:0 4px 16px rgba(0,0,0,.06),0 2px 8px rgba(0,0,0,.04);">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <div style="font-size:16px;font-weight:700;color:var(--ms-text,#1e293b);display:flex;align-items:center;gap:10px;">
                    <i class="fas fa-table" style="color:var(--ms-primary,#16a34a);font-size:18px;"></i> Daftar Siswa
                </div>
                <div class="ms-search-box">
                    <i class="fas fa-search ms-search-icon"></i>
                    <input type="text" id="searchInput" class="ms-search-input" placeholder="Cari nama, NISN, atau kelas...">
                </div>
            </div>

            <table id="table_siswa" class="table table-ms display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NISN</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Jenjang</th>
                        <th>Jenis Kelamin</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($siswas as $siswa)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><code>{{ $siswa->nisn }}</code></td>
                        <td>{{ $siswa->nama }}</td>
                        <td>{{ $siswa->riwayatDipilih?->kelas?->nama_kelas ?? '-' }}</td>
                        <td>{{ $siswa->riwayatDipilih?->kelas?->jenjang?->kode ?? '-' }}</td>
                        <td>{{ $siswa->jenis_kelamin ?? '-' }}</td>
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
    var table = $('#table_siswa').DataTable({
        pagingType: 'simple_numbers',
        responsive: false,
        scrollX: true,
        searching: false,
        lengthChange: false,
        language: {
            url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian.json",
            paginate: { first: '«', previous: '‹', next: '›', last: '»' }
        },
        pageLength: 15,
        order: []
    });

    $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
    });
});
</script>
@endpush
