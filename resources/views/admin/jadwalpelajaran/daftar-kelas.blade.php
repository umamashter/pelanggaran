@extends('layouts.main')

@section('title','Jadwal Per Kelas')

@section('content')

<style>
    .kelas-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }

    @media (max-width: 768px) {
        .kelas-grid {
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }

        .kelas-card h5 {
            font-size: 14px;
        }

        .kelas-card p {
            font-size: 11px;
        }

        .kelas-icon {
            width: 50px;
            height: 50px;
        }

        .kelas-icon i {
            font-size: 20px;
        }
    }

    .kelas-card,
    .kelas-card:hover,
    .kelas-card:focus,
    .kelas-card:active {
        text-decoration: none !important;
        color: inherit;
    }

    .kelas-card h5,
    .kelas-card p {
        text-decoration: none !important;
    }

    .kelas-card:hover h5,
    .kelas-card:hover p {
        text-decoration: none !important;
    }

    .kelas-card {
        transition: all .3s ease;
        border-radius: 16px;
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .kelas-card .card {
        transition: all .3s ease;
        border: 2px solid transparent;
    }

    .kelas-card:hover .card {
        transform: translateY(-5px);
        background: #37C61E;
        color: white;
        box-shadow: 0 10px 25px rgba(55, 198, 30, .3);
    }

    .kelas-card:hover .kelas-icon {
        background: white;
    }

    .kelas-card:hover .kelas-icon i {
        color: #37C61E;
    }

    .kelas-card:hover .text-muted {
        color: white !important;
    }

    .kelas-card:hover .btn-warning {
        background: white;
        border-color: white;
        color: #37C61E;
    }

    .kelas-card.active .card {
        background: #37C61E;
        color: white;
        border-color: #37C61E;
    }

    .kelas-card.active .kelas-icon {
        background: white;
    }

    .kelas-card.active .kelas-icon i {
        color: #37C61E;
    }

    .kelas-card.active .text-muted {
        color: white !important;
    }

    .kelas-card.active .btn-warning {
        background: white;
        border-color: white;
        color: #37C61E;
    }

    .bg-jadwal {
        background: #37C61E;
        border-bottom: 3px solid #37C61E;
    }

    .card {
        border: none;
        border-radius: 16px;
        overflow: hidden;
    }

    .kelas-card {
        transition: all .3s ease;
        border-radius: 16px;
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .kelas-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, .12);
        color: inherit;
    }

    .kelas-icon {
        width: 70px;
        height: 70px;
        background: #EFEE3C;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: auto;
    }

    .kelas-icon i {
        font-size: 28px;
        color: #333;
    }

    .jumlah-kelas {
        font-size: 32px;
        font-weight: 700;
    }
</style>

<div class="card shadow-sm">

    <div class="card-header bg-jadwal">

        <div class="d-flex justify-content-between align-items-center">

            <h4 class="mb-0 fw-bold text-white">
                <i class="fas fa-calendar-week me-2"></i>
                Jadwal Per Kelas
            </h4>

            <span class="badge bg-dark fs-6">
                {{ $kelas->count() }} Kelas
            </span>

        </div>

    </div>

    <div class="card-body">

        {{-- Daftar Kelas --}}
        <div class="row">

            @foreach($kelas as $item)

            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">

                <a href="{{ route('jadwal-pelajaran.perkelas',$item->id) }}"
                    class="kelas-card {{ request()->route('id') == $item->id ? 'active' : '' }}">

                    <div class="card shadow-sm h-100">

                        <div class="card-body text-center">

                            <div class="kelas-icon mb-3">

                                <i class="fas fa-school"></i>

                            </div>

                            <h5 class="fw-bold mb-2">
                                {{ $item->nama_kelas }}
                            </h5>

                            <p class="text-muted mb-3">
                                Lihat Jadwal Pelajaran
                            </p>

                            <span class="btn btn-warning btn-sm">

                                <i class="fas fa-eye me-1"></i>
                                Buka Jadwal

                            </span>

                        </div>

                    </div>

                </a>

            </div>

            @endforeach

        </div>

        {{-- Jika Tidak Ada Data --}}
        @if($kelas->count() == 0)

        <div class="text-center py-5">

            <i class="fas fa-school fa-4x text-secondary mb-3"></i>

            <h5 class="text-muted">
                Belum Ada Data Kelas
            </h5>

        </div>

        @endif

    </div>

</div>

@endsection