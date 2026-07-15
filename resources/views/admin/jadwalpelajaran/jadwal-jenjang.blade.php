@extends('layouts.main')

@section('title', 'Jadwal Per Jenjang')

@section('content')

<style>
    .bg-jadwal {
        background: rgb(86, 179, 67);
    }

    .jenjang-card {
        transition: .3s;
        border: none;
        border-radius: 15px;
        overflow: hidden;
    }

    .jenjang-card:hover {
        transform: translateY(-5px);
    }

    .icon-box {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: rgba(86, 179, 67, .1);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: auto;
    }

    .icon-box i {
        font-size: 35px;
        color: rgb(86, 179, 67);
    }

    .btn-detail {
        background: rgb(86, 179, 67);
        color: white;
        border: none;
    }

    .btn-detail:hover {
        background: rgb(70, 150, 55);
        color: white;
    }
</style>

<div class="card shadow-sm">

    <div class="card-header bg-jadwal text-white">

        <div class="d-flex justify-content-between align-items-center">

            <h4 class="mb-0 fw-bold">
                <i class="fas fa-calendar-alt me-2"></i>
                Jadwal Pelajaran Jenjang
            </h4>

        </div>

    </div>

    <div class="card-body">

        <div class="row">

            @forelse($jenjangs as $j)

            <div class="col-md-4 mb-4">

                <div class="card shadow-sm jenjang-card">

                    <div class="card-body text-center">

                        <div class="icon-box mb-3">

                            <i class="fas fa-school"></i>

                        </div>

                        <h4 class="fw-bold">
                            {{ $j->nama_jenjang }}
                        </h4>

                        <p class="text-muted">
                            Jadwal seluruh kelas {{ $j->nama_jenjang }}
                        </p>

                        <a href="{{ route('jadwal-jenjang.detail', $j->kode) }}"
                            class="btn btn-detail">

                            <i class="fas fa-eye me-1"></i>
                            Lihat Jadwal

                        </a>

                    </div>

                </div>

            </div>

            @empty

            <div class="col-12 text-center py-5">
                <p class="text-muted">Belum ada data jenjang.</p>
            </div>

            @endforelse

        </div>

    </div>

</div>

@endsection