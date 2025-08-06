@extends('layouts.main')
@section('title', 'Histori')
@section('content')
    <div class="row justify-content-center">
        <div class="card shadow px-0">
            <div class="card-header bg-gradient bg-secondary">
                <h3 class="fw-bolder mt-2 text-white">
                    Histori {{ Auth::user()->name }}
                </h3>
            </div>
            <div class="card-body py-0">
                @if ($histories->count())
                    @foreach ($tanggal as $tgl)
                        <b><p class="text-dark mb-1 mt-3 ml-1">{{ $tgl }}</p></b>
                        @foreach ($histories as $history)
                            @if ($history->getAttribute('tanggal') == $tgl)
                                <div class="list-group mb-2">
                                    <div class="border-hover list-group-item list-group-item-action flex-column align-items-start py-0 px-3"
                                        style="background-color: #ffd8ab84; border-radius: 6px;">
                                        <div class="histori-part row" style="margin-bottom: .5rem;">
                                            <div class="col-lg-2 row" style="margin-top: .5rem;">
                                                <small class="px-0"
                                                    style="height: 20px; width: auto;">{{ $history->created_at->diffForHumans() }}</small>
                                            </div>
                                            <div class="col-lg-10" style="margin-top: .5rem;">
                                                <p class="mb-1 h6 text-dark ">{{ $history->rule->nama }}</p>
                                                <div class="text-danger d-inline-flex">
                                                    <b>+{{ $history->rule->poin }}</b>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endforeach
                @else
                    <h5 class="text-secondary text-center py-1 mt-4">Histori tidak ada</h5>
                @endif

            </div>
            <div class="text-end card-footer mt-3">
                <div class="mx-4 text-decoration-none float-right">
                    {{ $histories->links() }}
                </div>
            </div>

        </div>
    </div>
@endsection
