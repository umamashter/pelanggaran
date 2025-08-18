@extends('layouts.main')
@section('title', 'Laporan Pelanggaran')

@section('content')
<div class="container-fluid px-4">
    <h3 class="mb-4 fw-bold text-center">Data Pelanggaran Tahun Ajaran</h3>

    {{-- Form Filter --}}
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            Filter Laporan
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('laporan.rekap-periode') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Tahun Ajaran</label>
                    <select name="tahun_ajaran" class="form-select">
                        <option value="2024/2025" {{ request('tahun_ajaran') == '2024/2025' ? 'selected' : '' }}>2024/2025</option>
                        <option value="2025/2026" {{ request('tahun_ajaran') == '2025/2026' ? 'selected' : '' }}>2025/2026</option>
                        <option value="2026/2027" {{ request('tahun_ajaran') == '2026/2027' ? 'selected' : '' }}>2026/2027</option>
                        <option value="2027/2028" {{ request('tahun_ajaran') == '2027/2028' ? 'selected' : '' }}>2027/2028</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Bulan (Opsional)</label>
                    <input type="month" name="bulan" value="{{ request('bulan') }}" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Kelas</label>
                    <select name="kelas_id" class="form-select">
                        <option value="">-- Semua Kelas --</option>
                        @foreach(App\Models\Kelas::all() as $kelas)
                            <option value="{{ $kelas->id }}" {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                {{ $kelas->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">NISN</label>
                    <input type="text" name="nisn" value="{{ request('nisn') }}" class="form-control" placeholder="Contoh: 12345678">
                </div>

                <div class="col-12 d-flex flex-wrap gap-2 mt-3">
                    <button type="submit" class="btn btn-dark">Tampilkan</button>                    
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Data --}}
    @if($histories->count())
    <div class="card">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            Hasil Laporan
                <div class="d-flex flex-wrap gap-2 ">                    
                    @if($histories->count())
                        <a href="{{ route('laporan.exportPdf', request()->query()) }}" class="btn btn-outline-secondary" style="color: #fff;">
                            Cetak PDF
                        </a>

                    @endif
                </div>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-striped table-bordered" style="background: #fff; color: #000;">
                <thead class="table-dark text-center">
                    <tr>
                        <th>Nama Siswa</th>
                        <th>Kelas Saat Pelanggaran</th>
                        <th>Jenis Pelanggaran</th>
                        <th>Poin</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($histories as $history)
                        <tr>
                            <td>{{ $history->siswa->nama }}</td>
                            <td>{{ $history->kelasSnapshot->nama_kelas ?? '-' }}</td>
                            <td>{{ $history->rule->nama }}</td>
                            <td class="text-center">{{ $history->rule->poin }}</td>
                            <td>{{ \Carbon\Carbon::parse($history->tanggal)->format('d M Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="alert alert-secondary text-center">
        Tidak ada data pelanggaran untuk periode ini.
    </div>
    @endif
</div>
@endsection
