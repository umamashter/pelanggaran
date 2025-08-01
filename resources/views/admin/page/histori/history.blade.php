@extends('layouts.main')
@section('title', 'Master Histori')
@section('content')
    <div class="row justify-content-center">
        <div class="card shadow px-0">
            <div class="card-header bg-warning">
                <h3 class="fw-bolder mt-2 d-inline">
                    Histori {{ $siswa->nama }}
                </h3>
                @if ($histories)
                    <form action="/master-histori/{{ $siswa->id }}" method="get" id="form_history" class="float-end">
                        <input type="date" name="tanggal" id="tanggal" onchange="history()"
                            class="form-control form-control-sm" value="{{ request('tanggal') }}">
                    </form>
                @endif
            </div>
            <div class="card-body py-0">
                @if (request('tanggal'))
                    <b>
                        <p class="text-dark mb-1 mt-3 ml-1">Di urutkan berdasarkan : {{ $tanggal }}
                        </p>
                    </b>
                    @forelse ($histories as $history)
                        <div class="list-group mt-2" style="margin-bottom: 0.75rem">
                            <div class="border-hover list-group-item list-group-item-action flex-column align-items-start py-0"
                                style="background-color: #ffd8ab84; border-radius: 6px;">
                                <div class="d-flex w-100 mt-1 mb-1 align-items-center"
                                    style="justify-content: space-between; flex-wrap: wrap;">
                                    <a class="linkind">
                                        <small class="me-1">
                                            <b>{{ $history->siswa->nama }} -
                                                {{ $history->siswa->kelas->nama_kelas }}
                                            </b>
                                        </small>
                                    </a>
                                    <a><small>{{ $history->created_at->diffForHumans() }}</small></a>
                                </div>
                                <div class="row">
                                    <div class="col-lg-10">
                                        <p class="mb-1 h6 text-dark ">{{ $history->rule->nama }}</p>
                                        <div class="text-danger d-inline-flex mb-2">
                                            <b>+{{ $history->rule->poin }}</b>
                                        </div>                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <h5 class="text-secondary text-center py-1 mt-3">Histori tidak ada</h5>
                    @endforelse
                @else
                    @forelse ($tanggal as $tgl)
                        <b>
                            <p class="text-dark mb-1 mt-3 ml-1">Tanggal : {{ date('d-m-Y', strtotime($tgl)) }}</p>
                        </b>
                        @foreach ($histories as $history)
                            @if ($history->getAttribute('tanggal') == $tgl)
                                <div class="list-group mt-2" style="margin-bottom: 0.75rem">
                                    <div class="border-hover list-group-item list-group-item-action flex-column align-items-start py-0"
                                        style="background-color: #ffd8ab84; border-radius: 6px;">
                                        <div class="d-flex w-100 mt-1 mb-1 align-items-center"
                                            style="justify-content: space-between; flex-wrap: wrap;">
                                            <a class="linkind">
                                                <small class="me-1">
                                                    <b>{{ $history->siswa->nama }} -
                                                        {{ $history->siswa->kelas->nama_kelas }}
                                                    </b>
                                                </small>
                                            </a>
                                            <a><small>{{ $history->created_at->diffForHumans() }}</small></a>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-10 position-relative">
                                                <p class="mb-1 h6 text-dark ">{{ $history->rule->nama }}</p>
                                                <div class="text-danger d-inline-flex mb-2">
                                                    <b>+{{ $history->rule->poin }}</b>
                                                </div>
                                                <form action="{{ route('poin.destroy', $history->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus histori ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger position-absolute top-0 end-0" data-bs-toggle="modal" data-bs-target="#hapusModal{{ $history->id }}">
                                                        <i class="fas fa-trash"></i>  Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Modal Konfirmasi -->
<div class="modal fade" id="hapusModal{{ $history->id }}" tabindex="-1" aria-labelledby="hapusModalLabel{{ $history->id }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="hapusModalLabel{{ $history->id }}">Konfirmasi Hapus</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        Apakah Anda yakin ingin menghapus histori pelanggaran <strong>{{ $history->rule->nama }}</strong> dari <strong>{{ $history->siswa->nama }}</strong>?
      </div>
      <div class="modal-footer">
        <form action="{{ route('poin.destroy', $history->id) }}" method="POST">
          @csrf
          @method('DELETE')
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger">Hapus</button>
        </form>
      </div>
    </div>
  </div>
</div>

                            @endif
                        @endforeach
                    @empty
                        <h5 class="text-secondary text-center py-1 mt-4">Histori tidak ada</h5>
                    @endforelse
                @endif

            </div>
            <div class="text-end card-footer mt-3">
                <div class="mx-4 text-decoration-none float-right pagination">
                    {{ $histories->links() }}
                </div>
            </div>

        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function history() {
            $("form#form_history").submit();
        }
    </script>
@endpush
