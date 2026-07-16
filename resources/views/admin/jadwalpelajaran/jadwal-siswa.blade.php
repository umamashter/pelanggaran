@extends('layouts.main')

@section('title', 'Jadwal Siswa')

@section('content')

<style>
    .hero-header {
        background: linear-gradient(135deg, #2e7d32 0%, #66bb6a 50%, #a5d6a7 100%);
        border-radius: 16px;
        padding: 30px 35px;
        position: relative;
        overflow: hidden;
    }

    .hero-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 400px;
        height: 400px;
        background: rgba(255, 255, 255, .06);
        border-radius: 50%;
    }

    .hero-header::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: 10%;
        width: 250px;
        height: 250px;
        background: rgba(255, 255, 255, .05);
        border-radius: 50%;
    }

    .stat-card {
        background: rgba(255, 255, 255, .15);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        padding: 15px 20px;
        position: relative;
        z-index: 1;
    }

    .stat-card .stat-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: rgba(255, 255, 255, .2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: #fff;
    }

    .jenjang-card {
        border: none;
        border-radius: 16px;
        overflow: hidden;
        transition: all .35s cubic-bezier(.4,0,.2,1);
        background: #fff;
    }

    .jenjang-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 40px rgba(46, 125, 50, .15) !important;
    }

    .jenjang-card .card-top {
        height: 6px;
    }

    .jenjang-card .card-body {
        padding: 24px 20px 20px;
    }

    .jenjang-icon {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 14px;
        font-size: 28px;
        color: #fff;
    }

    .kelas-list {
        list-style: none;
        padding: 0;
        margin: 14px -8px 0;
    }

    .kelas-list li {
        padding: 3px 0;
    }

    .kelas-list a {
        text-decoration: none;
        display: flex;
        align-items: center;
        padding: 8px 12px;
        border-radius: 10px;
        transition: all .2s;
        font-weight: 500;
        font-size: 14px;
    }

    .kelas-list a .badge-kelas {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        margin-right: 10px;
        flex-shrink: 0;
    }

    .total-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(46, 125, 50, .08);
        color: #2e7d32;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }

    .print-btn-card {
        width: 100%;
        border-radius: 10px;
        padding: 8px;
        font-size: 13px;
        font-weight: 600;
        transition: all .25s;
        border: 2px solid #e8f5e9;
        color: #2e7d32;
        background: #f1f8e9;
    }

    .print-btn-card:hover {
        background: #2e7d32;
        color: #fff;
        border-color: #2e7d32;
    }

    .btn-cetak-semua {
        background: rgba(255, 255, 255, .2);
        border: 1.5px solid rgba(255, 255, 255, .4);
        color: #fff;
        border-radius: 10px;
        padding: 8px 20px;
        font-weight: 600;
        font-size: 14px;
        transition: all .25s;
    }

    .btn-cetak-semua:hover {
        background: rgba(255, 255, 255, .3);
        color: #fff;
    }

    @media (max-width: 575.98px) {
        .hero-header {
            padding: 20px 16px;
            border-radius: 12px;
        }
        .hero-header h3 {
            font-size: 16px !important;
        }
        .hero-header h3 i {
            display: none;
        }
        .hero-header p {
            font-size: 11px !important;
        }
        .hero-header .d-flex {
            flex-direction: column !important;
            align-items: flex-start !important;
        }
        .hero-header .d-flex > div:last-child {
            width: 100%;
            flex-wrap: wrap;
            gap: 8px !important;
        }
        .stat-card {
            padding: 10px 14px;
            flex: 1;
            min-width: 0;
        }
        .stat-card .stat-icon {
            width: 34px;
            height: 34px;
            font-size: 14px;
        }
        .stat-card .fw-bold {
            font-size: 16px !important;
        }
        .stat-card .text-white-50 {
            font-size: 10px !important;
        }
        .btn-cetak-semua {
            width: 100%;
            justify-content: center;
            padding: 10px;
            font-size: 13px;
        }
    }
</style>

{{-- Hero Header --}}
<div class="hero-header mb-4">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

        <div>
            <h3 class="text-white fw-bold mb-1" style="position:relative;z-index:1;">
                <i class="fas fa-calendar-alt me-2"></i>
                Jadwal Pelajaran Siswa
            </h3>
            <p class="text-white-50 mb-0" style="position:relative;z-index:1;font-size:14px;">
                Lihat dan cetak jadwal per jenjang
            </p>
        </div>

        <div class="d-flex gap-3" style="position:relative;z-index:1;">
            <div class="stat-card d-flex align-items-center gap-3">
                <div class="stat-icon">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div>
                    <div class="text-white-50" style="font-size:11px;">Jenjang</div>
                    <div class="text-white fw-bold" style="font-size:20px;">{{ $jenjangs->count() }}</div>
                </div>
            </div>
            <div class="stat-card d-flex align-items-center gap-3">
                <div class="stat-icon">
                    <i class="fas fa-school"></i>
                </div>
                <div>
                    <div class="text-white-50" style="font-size:11px;">Kelas</div>
                    <div class="text-white fw-bold" style="font-size:20px;">{{ collect($kelasPerJenjang)->flatten()->count() }}</div>
                </div>
            </div>
            <a href="{{ route('jadwal-siswa.cetak') }}" target="_blank" class="btn-cetak-semua d-flex align-items-center gap-2">
                <i class="fas fa-print"></i>
                Cetak Semua
            </a>
        </div>

    </div>

</div>

{{-- Jenjang Cards --}}
<div class="row g-4">

    @forelse($jenjangs as $j)

    @php
    $warna = ['#2e7d32','#1565c0','#6a1b9a','#e65100','#00838f','#c62828'];
    $icons = ['fas fa-school','fas fa-book-reader','fas fa-graduation-cap','fas fa-chalkboard','fas fa-flask','fas fa-music'];
    $idx = $loop->index % 6;
    $bgCard = ['#e8f5e9','#e3f2fd','#f3e5f5','#fff3e0','#e0f7fa','#ffebee'];
    @endphp

    <div class="col-xl-4 col-md-6">

        <div class="card shadow-sm jenjang-card h-100">

            <div class="card-top" style="background:{{ $warna[$idx] }};"></div>

            <div class="card-body d-flex flex-column">

                <div class="text-center">

                    <div class="jenjang-icon" style="background:{{ $warna[$idx] }};">
                        <i class="{{ $icons[$idx] }}"></i>
                    </div>

                    <h5 class="fw-bold mb-1">{{ $j->nama_jenjang }}</h5>

                    <span class="total-badge">
                        <i class="fas fa-school"></i>
                        {{ $kelasPerJenjang[$j->id]->count() }} Kelas
                    </span>

                </div>

                <ul class="kelas-list flex-grow-1">
                    @foreach($kelasPerJenjang[$j->id] as $k)
                    <li>
                        <a href="{{ route('jadwal-siswa.kelas', $k->id) }}" style="color:#333;">
                            <span class="badge-kelas" style="background:{{ $bgCard[$idx] }};color:{{ $warna[$idx] }};">
                                {{ $loop->iteration }}
                            </span>
                            Kelas {{ $k->nama_kelas }}
                            <i class="fas fa-chevron-right ms-auto" style="font-size:11px;color:#bbb;transition:.2s;"></i>
                        </a>
                    </li>
                    @endforeach
                </ul>

                <a href="{{ route('jadwal-siswa.cetak', $j->id) }}" target="_blank" class="print-btn-card text-center mt-3">
                    <i class="fas fa-print me-1"></i>
                    Cetak Jadwal
                </a>

            </div>

        </div>

    </div>

    @empty

    <div class="col-12 text-center py-5">
        <div class="text-muted">
            <i class="fas fa-calendar-times fa-3x mb-3 d-block"></i>
            Belum ada data jenjang.
        </div>
    </div>

    @endforelse

</div>

@endsection
