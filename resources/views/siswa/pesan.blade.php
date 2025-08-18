@extends('layouts.main')
@section('title', 'Pesan')
@section('content')
    <div class="row justify-content-center">
        <div class="card shadow px-0">
            <div class="card-header bg-gradient bg-secondary">
                <h3 class="fw-bolder mt-2 text-white">
                    Kotak Pesan
                </h3>
            </div>
            <div class="card-body py-0">
                @if ($pesan->count())

                    @foreach ($pesan as $msg)
                        <div class="list-group my-2">
                            <div class="border-hover list-group-item list-group-item-action flex-column align-items-start py-1 px-3 mt-2 mb-1"
                                style="background-color: #ffd8ab84; border-radius: 6px;">
                                <div class="histori-part row" style="margin-bottom: .5rem;">
                                    <div class="col-lg-2 row" style="margin-top: .5rem;">
                                        <small class="px-0"
                                            style="height: 20px; width: auto;">{{ $msg->created_at->diffForHumans() }}</small>
                                    </div>
                                    <div class="col-lg-10">
                                        <p class="mb-1 mt-2 h6 text-dark ">{{ $msg->pesan->tindak_lanjut }}</p>
                                        <div class="text-danger d-inline-flex">
                                            @if ($msg->pesan->tingkatan == 'Ringan' || $msg->pesan->tingkatan == 'Sedang')
                                                @if ($msg->status == 0)
                                                    <p class="mb-0">Belum Terkonfirmasi</p>
                                                @else
                                                    <p class="mb-0">Terkonfirmasi - {{ $msg->created_at->format('d/m/Y') }}</p>
                                                @endif
                                            @else
                                                @if ($msg->status == 0)
                                                    <p class="mb-0">Belum Terlaksana</p>
                                                @else
                                                    <a class="text-danger" href="/storage/surat/{{ $msg->berkas }}" target="_blank"
                                                        rel="noopener noreferrer">{{ $msg->berkas }}
                                                    </a>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <h5 class="text-secondary text-center py-1 mt-4">Pesan tidak ada</h5>
                @endif

            </div>
            <div class="text-end card-footer mt-3">
                <div class="mx-4 text-decoration-none float-right">
                    {{ $pesan->links() }}
                </div>
            </div>

        </div>
    </div>
@endsection
