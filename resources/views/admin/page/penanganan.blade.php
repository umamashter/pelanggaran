@extends('layouts.main')
@section('title', 'Penanganan')
@section('content')
    <div class="row justify-content-center">
        <div class="card shadow px-0">
            <div class="card-header bg-gradient bg-secondary">
                <h3 class="fw-bolder mt-2 text-white">
                    Penanganan
                </h3>
            </div>
            <div class="card-body">
                <table id="table_data_user" class="table table-bordered display nowrap" cellspacing="0" width="100%">
                    <thead class="thead-inverse">
                        <tr>
                            <th>No.</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Tindak Lanjut</th>
                            <th>Status</th>
                            <th>Berkas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($penanganan as $tindak)
                            <tr>
                                <td scope="row">
                                    {{ ($penanganan->currentpage() - 1) * $penanganan->perpage() + $loop->index + 1 }}
                                </td>
                                <td>{{ $tindak->siswa->nama }}</td>
                                <td>{{ $tindak->history->kelasSnapshot->nama_kelas }}</td>
                                <td>{{ $tindak->pesan->tindak_lanjut }}</td>
                                <td>
                                    @if ($tindak->pesan->tingkatan == 'Ringan' || $tindak->pesan->tingkatan == 'Sedang')
                                        @if ($tindak->status == 0)
                                            <form action="/penanganan/{{ $tindak->id }}" method="post">
                                                @csrf
                                                <button type="submit" class="btn btn-primary btn-sm">Konfirmasi</button>
                                            </form>
                                        @else
                                            <button class="btn btn-secondary btn-sm" disabled>Terkonfirmasi -
                                                {{ $tindak->created_at->format('d/m/Y') }}</button>
                                        @endif
                                    @else
                                        @if ($tindak->status == 0)
                                            <a href="#modalCenter{{ $tindak->id }}" role="button"
                                                class="btn btn-sm btn-info" data-bs-toggle="modal">Belum Terlaksana</a>
                                        @endif
                                        @if ($tindak->status == 1)
                                            <form action="/penanganan/{{ $tindak->id }}" method="post">
                                                @csrf
                                                <button class="btn text-dark btn-warning btn-sm">Terkirim Belum
                                                    terlaksana</button>
                                            </form>
                                        @endif
                                        @if ($tindak->status == 2)
                                            <button class="btn btn-secondary btn-sm" disabled>Terkonfirmasi -
                                                {{ $tindak->created_at->format('d/m/Y') }}</button>
                                        @endif
                                    @endif
                                </td>
                                
                                <td>
                                    @if ($tindak->berkas)
                                        <a href="/storage/surat/{{ $tindak->berkas }} " target="_blank"
                                            rel="noopener noreferrer">{{ $tindak->berkas }}</a>
                                    @else
                                        Tidak ada berkas
                                    @endif

                                </td>
                            </tr>
                            <div id="modalCenter{{ $tindak->id }}" class="modal fade" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-md modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header py-2 bg-info text-white">
                                            <h5 class="modal-title ps-2">Form Pesan</h5>
                                        </div>
                                        <div class="modal-body">
                                            <form action="/penanganan/{{ $tindak->id }}" method="post">
                                                @csrf
                                                <div class="form-floating mb-2">
                                                    <input required type="date" name="date"
                                                        class="form-control form-input-lg @error('date') is-invalid @enderror"
                                                        value="{{ old('date') }}">
                                                    <label for="date">Date</label>
                                                    @error('date')
                                                        <div class="invalid-feedback mb-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="form-floating mb-2">
                                                    <input required type="time" name="time"
                                                        class="form-control form-input-lg @error('time') is-invalid @enderror"
                                                        value="{{ old('time') }}">
                                                    <label for="time">Time</label>
                                                    @error('time')
                                                        <div class="invalid-feedback mb-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                        </div>
                                        <div class="modal-footer py-2">
                                            <button type="button" class="btn btn-sm  btn-secondary"
                                                data-bs-dismiss="modal">Tutup</button>
                                            <button type="submit" class="btn btn-sm btn-primary">Kirim</button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#table_data_user').DataTable({
                pagingType: 'simple_numbers',
                responsive: true,
                processing: true,
                scrollX: true,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian.json",
                    paginate: {
                        first: '«',
                        previous: '‹',
                        next: '›',
                        last: '»'
                    },
                    aria: {
                        paginate: {
                            first: 'First',
                            previous: 'Previous',
                            next: 'Next',
                            last: 'Last'
                        }
                    },
                },
                "columnDefs": [{
                        "orderable": false,
                        "targets": 1
                    },
                    {
                        "orderable": false,
                        "targets": 3
                    },
                    {
                        "orderable": false,
                        "targets": 4
                    },
                ],
                "pageLength": 10
            });
        });
    </script>
@endpush
