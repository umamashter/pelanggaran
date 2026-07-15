@extends('layouts.main')
@section('title', 'Detail Anggota Kelompok')
@section('content')
@include('component.admin.ms-style')
<div class="master-siswa-page">
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon"><i class="fas fa-eye"></i></div>
                <div><h4 class="mb-0 fw-bold" style="color: var(--ms-text); font-size: 20px;">Detail Anggota Kelompok</h4></div>
            </div>
        </div>
    </div>
    <div class="card table-card">
        <div class="card-body">
            <table class="table table-ms">
                <tr><th>Kelompok</th><td>{{ $anggotaKelompok->kelompokLomba->nama_kelompok ?? '-' }}</td></tr>
                <tr><th>Siswa</th><td>{{ $anggotaKelompok->student->user->name ?? '-' }}</td></tr>
                <tr><th>NISN</th><td>{{ $anggotaKelompok->student->nisn ?? '-' }}</td></tr>
                <tr><th>Tingkat</th><td>{{ $anggotaKelompok->student->kelasAktif->kelas->tingkat ?? '-' }}</td></tr>
                <tr><th>Jenjang</th><td>{{ $anggotaKelompok->student->kelasAktif->kelas->jenjang->nama_jenjang ?? '-' }}</td></tr>
            </table>
            <a href="{{ route('anggota-kelompok.edit', $anggotaKelompok->id) }}" class="btn btn-header-ms btn-simpan-ms mt-3"><i class="fas fa-edit me-1"></i> Edit</a>
        </div>
    </div>
</div>
@endsection
