@extends('layouts.main')
@section('title', 'Detail Penilaian Lomba')
@push('css')
<style>
    .detail-penilaian-page {
        font-family: 'Inter', 'Poppins', system-ui, sans-serif;
        max-width: 820px;
        margin: 22px auto 0;
        padding: 0 16px;
    }
    .breadcrumb-cu { margin-bottom: 20px; }
    .breadcrumb-cu .breadcrumb { background: transparent; padding: 0; margin: 0; }
    .breadcrumb-cu .breadcrumb-item { font-size: 13px; }
    .breadcrumb-cu .breadcrumb-item a { color: #64748b; text-decoration: none; }
    .breadcrumb-cu .breadcrumb-item a:hover { color: #16a34a; }
    .breadcrumb-cu .breadcrumb-item.active { color: #1e293b; font-weight: 500; }
    .breadcrumb-cu .breadcrumb-item+.breadcrumb-item::before { color: #cbd5e1; }
    .detail-card { border: none; border-radius: 18px; box-shadow: 0 4px 16px rgba(0,0,0,.06), 0 2px 8px rgba(0,0,0,.04); }
    .detail-card-header { padding: 24px 28px 20px; border-bottom: 1px solid #f1f5f9; }
    .detail-card-body { padding: 24px 28px 28px; }
    .info-label { font-size: 11px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 4px; }
    .info-value { font-weight: 700; font-size: 15px; color: #1e293b; }

    .meta-row { display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 24px; }
    .meta-chip { display: inline-flex; align-items: center; gap: 8px; padding: 10px 16px; border-radius: 12px; background: #f8fafc; border: 1.5px solid #e2e8f0; font-size: 13px; }
    .meta-chip .meta-icon { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0; }
    .meta-chip .meta-icon.green { background: #dcfce7; color: #16a34a; }
    .meta-chip .meta-icon.blue { background: #dbeafe; color: #2563eb; }
    .meta-chip .meta-icon.amber { background: #fef3c7; color: #d97706; }
    .meta-chip .meta-text .label { font-size: 10px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: .5px; }
    .meta-chip .meta-text .val { font-weight: 700; color: #1e293b; font-size: 14px; }

    .total-box { display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; border-radius: 14px; background: linear-gradient(135deg, #f0fdf4, #dcfce7); border: 1.5px solid #bbf7d0; margin-bottom: 28px; }
    .total-box .total-label { font-size: 13px; font-weight: 600; color: #166534; }
    .total-box .total-value { font-size: 28px; font-weight: 800; color: #16a34a; }

    .juri-section { margin-bottom: 20px; border: 1.5px solid #e2e8f0; border-radius: 14px; overflow: hidden; }
    .juri-section:last-child { margin-bottom: 0; }
    .juri-header { display: flex; align-items: center; gap: 10px; padding: 14px 18px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
    .juri-badge { width: 32px; height: 32px; border-radius: 8px; background: linear-gradient(135deg, #eff6ff, #dbeafe); color: #2563eb; font-size: 13px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .juri-name { font-weight: 700; font-size: 14px; color: #1e293b; }
    .juri-total { margin-left: auto; font-weight: 800; font-size: 15px; color: #16a34a; }
    .score-table { width: 100%; border-collapse: collapse; }
    .score-table td { padding: 10px 16px; font-size: 13px; border-bottom: 1px solid #f1f5f9; }
    .score-table tr:last-child td { border-bottom: none; }
    .score-table td:first-child { width: 32px; text-align: center; color: #94a3b8; font-weight: 600; }
    .score-table td:nth-child(2) { font-weight: 500; color: #334155; }
    .score-table td:last-child { text-align: right; width: 80px; }
    .score-table td:last-child .badge { font-size: 13px; font-weight: 700; padding: 4px 14px; border-radius: 8px; background: linear-gradient(135deg, #16a34a, #22c55e); color: #fff; }

    .anggota-list { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 6px; }
    .anggota-chip { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 8px; background: #f1f5f9; font-size: 11px; font-weight: 500; color: #475569; }
    .anggota-chip i { font-size: 10px; color: #94a3b8; }

    .btn-cu { height: 40px; padding: 0 22px; border-radius: 10px; font-size: 13px; font-weight: 600; transition: all .25s; display: inline-flex; align-items: center; justify-content: center; white-space: nowrap; border: none; gap: 6px; text-decoration: none; }
    .btn-cu-secondary { background: #f1f5f9; color: #475569; border: 1.5px solid #e2e8f0; }
    .btn-cu-secondary:hover { background: #e2e8f0; color: #334155; transform: translateY(-1px); box-shadow: 0 3px 8px rgba(0,0,0,.08); }
    @media (max-width: 768px) {
        .detail-penilaian-page { margin-top: 16px; padding: 0 12px; }
        .detail-card-header, .detail-card-body { padding: 18px 20px; }
        .meta-row { gap: 8px; }
        .meta-chip { flex: 1 1 calc(50% - 8px); min-width: 0; }
        .total-box .total-value { font-size: 22px; }
    }
</style>
@endpush
@section('content')
@include('component.admin.ms-style')
<div class="detail-penilaian-page">

    <nav aria-label="breadcrumb" class="breadcrumb-cu">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home me-1"></i>Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('penilaian-lomba.index') }}">Penilaian Lomba</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail Penilaian</li>
        </ol>
    </nav>

    <div class="card detail-card">
        <div class="detail-card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon" style="width:48px;height:48px;font-size:22px;">
                    <i class="fas fa-star"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold" style="color: #1e293b; font-size: 18px;">Detail Penilaian</h4>
                    <span style="font-size: 13px; color: #64748b;">Rekap penilaian dari semua juri.</span>
                </div>
            </div>
        </div>

        <div class="detail-card-body">

            @php
                $pl = $penilaianLomba->pesertaLomba;
                $lombaDetail = $pl->lomba ?? null;
                $jenisDetail = $lombaDetail->jenis ?? null;
                $kelompokDetail = $pl->kelompokLomba;
                $isTim = $jenisDetail === 'Tim';
            @endphp

            {{-- Meta chips --}}
            <div class="meta-row">
                <div class="meta-chip">
                    <div class="meta-icon green"><i class="fas fa-trophy"></i></div>
                    <div class="meta-text">
                        <div class="label">Lomba</div>
                        <div class="val">{{ $lombaDetail->nama ?? '-' }}</div>
                    </div>
                </div>
                <div class="meta-chip">
                    <div class="meta-icon {{ $isTim ? 'amber' : 'blue' }}"><i class="fas {{ $isTim ? 'fa-users' : 'fa-user' }}"></i></div>
                    <div class="meta-text">
                        <div class="label">Peserta</div>
                        <div class="val">
                            @if($isTim && $kelompokDetail)
                                {{ $kelompokDetail->nama_kelompok }}
                            @else
                                {{ $pl->student->user->name ?? '-' }}
                            @endif
                        </div>
                    </div>
                </div>
                <div class="meta-chip">
                    <div class="meta-icon blue"><i class="fas fa-gavel"></i></div>
                    <div class="meta-text">
                        <div class="label">Juri</div>
                        <div class="val">{{ $jumlahJuri }} orang</div>
                    </div>
                </div>
            </div>

            {{-- Anggota kelompok (Tim only) --}}
            @if($isTim && $kelompokDetail && $kelompokDetail->anggota->count())
            <div class="mb-4">
                <div class="info-label mb-2"><i class="fas fa-users me-1" style="color:#16a34a;"></i> Anggota Kelompok</div>
                <div class="anggota-list">
                    @foreach($kelompokDetail->anggota as $anggota)
                    <span class="anggota-chip">
                        <i class="fas fa-user"></i>
                        {{ $anggota->student->user->name ?? $anggota->student->nama ?? 'Anggota' }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Total Nilai --}}
            <div class="total-box">
                <div class="total-label"><i class="fas fa-calculator me-1"></i> Total Nilai (Semua Juri)</div>
                <div class="total-value">{{ $totalSemua }}</div>
            </div>

            {{-- Penilaian Per Juri --}}
            <h6 class="fw-bold mb-3" style="color:#1e293b;font-size:14px;">
                <i class="fas fa-clipboard-list me-1" style="color:#16a34a;"></i> Penilaian Per Juri
            </h6>

            @forelse($allPenilaian as $juriGroup)
            <div class="juri-section">
                <div class="juri-header">
                    <div class="juri-badge">{{ $loop->iteration }}</div>
                    <div class="juri-name">{{ $juriGroup->juri->guru->nama ?? 'Juri #' . $juriGroup->juri->id }}</div>
                    <div class="juri-total">{{ $juriGroup->total }}</div>
                </div>
                <table class="score-table">
                    <tbody>
                        @foreach($juriGroup->penilaian as $p)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $p->aspekPenilaian->nama_aspek ?? '-' }}</td>
                            <td><span class="badge">{{ $p->nilai }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @empty
            <div class="text-center" style="padding:32px 20px;color:#94a3b8;">
                <i class="fas fa-clipboard d-block mb-2" style="font-size:28px;color:#cbd5e1;"></i>
                Belum ada penilaian
            </div>
            @endforelse

            <a href="{{ route('penilaian-lomba.index') }}" class="btn btn-cu btn-cu-secondary mt-4">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>

        </div>
    </div>

</div>
@endsection
