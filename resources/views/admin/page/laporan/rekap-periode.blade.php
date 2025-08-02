@extends('layouts.main')
@section('title', 'Rekap Pelanggaran Tahun Ajaran')

@section('content')
    <h3 class="mb-4 fw-bold text-center">Rekap Pelanggaran Tahun Ajaran</h3>

    <form method="GET" action="{{ route('laporan.rekap-periode') }}" class="row g-3 mb-4">
        <div class="col-md-4">
            <label class="form-label">Tahun Ajaran</label>
            <select name="tahun_ajaran" class="form-select">
                <option value="2024/2025" {{ request('tahun_ajaran') == '2024/2025' ? 'selected' : '' }}>2024/2025</option>
                <option value="2025/2026" {{ request('tahun_ajaran') == '2025/2026' ? 'selected' : '' }}>2025/2026</option>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Bulan (Opsional)</label>
            <input type="month" name="bulan" value="{{ request('bulan') }}" class="form-control">
        </div>

        <div class="col-md-4">
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

        <div class="col-md-4">
            <label class="form-label">NISN</label>
            <input type="text" name="nisn" value="{{ request('nisn') }}" class="form-control" placeholder="Contoh: 12345678">
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-dark">Tampilkan</button>
            @if($histories->count())
                <a href="{{ route('laporan.exportPdf', request()->query()) }}" class="btn btn-dark ms-2">
                    Cetak PDF
                </a>
                <button onclick="window.print()" class="btn btn-outline-dark ms-2">
                    Cetak Browser
                </button>
            @endif
        </div>
    </form>

    @if($histories->count())
        <div class="table-responsive">
            <table class="table table-striped table-bordered border-dark" style="background: #fff; color: #000;">
                <thead style="background: #000; color: #fff;">
                    <tr class="text-center">
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
    @else
        <div class="alert alert-secondary text-center">
            Tidak ada data pelanggaran untuk periode ini.
        </div>
    @endif
@endsection
