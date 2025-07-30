@extends('layouts.main')
@section('title', 'Daftar Siswa')
@section('content')
    <div class="card shadow px-0">
        <div class="card-header bg-gradient bg-success text-white">
            <h3 class="fw-bolder mt-2 d-inline-flex">
                List Siswa {{ $wali_kelas->kelas->nama_kelas }}
            </h3>
        </div>

        <div class="card-body">
            <table id="table_data_user" class="table table-bordered display nowrap" cellspacing="0" width="100%">
                <thead class="thead-inverse">
                    <tr>
                        <th>No</th>
                        <th>Nisn</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Poin</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($siswas as $siswa)
                        <tr>
                            <td scope="row">
                                {{ ($siswas->currentpage() - 1) * $siswas->perpage() + $loop->index + 1 }}
                            </td>
                            <td>{{ $siswa->nisn }}</td>
                            <td>{{ $siswa->nama }}</td>
                            <td>{{ $siswa->kelas->nama_kelas }}</td>
                            <td><a href="/guru/histori/{{ $siswa->id }}"
                                @if ($siswa->poin == 0) class="text-success" @endif
                                @if ($siswa->poin <= 55) style="color:#fcbc05;" @endif
                                @if ($siswa->poin <= 149) style="color:#fd5d03;" @endif
                                @if ($siswa->poin >= 150) class="text-danger" @endif>
                                    <b>{{ $siswa->poin }}</b>
                                </a>
                            </td>
                            <td data-label="Posisi">
                                <a href="#modalCenter{{ $siswa->id }}" role="button" class="clickind btn btn-sm btn-info"
                                    data-bs-toggle="modal"><i class="fas fa-info"></i></a>
                            </td>
                        </tr>
                        {{-- Modal Detail --}}
                        <div id="modalCenter{{ $siswa->id }}" class="modal fade" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-md modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header py-2 bg-info text-white">
                                        <h5 class="modal-title ps-2">Detail Siswa</h5>
                                        {{-- <button type="button" class="btn-close p-1 m-2" data-bs-dismiss="modal"></button> --}}
                                    </div>
                                    <div class="modal-body">
                                        <div class="row ing ps-2 py-1">
                                            <div class="col-4 dem">TTL</div>
                                            <div class="pisah">:</div>
                                            <div class="col-7">{{ $siswa->ttl }}</div>
                                        </div>
                                        <div class="row ing ps-2 py-1">
                                            <div class="col-4 dem">JK</div>
                                            <div class="pisah">:</div>
                                            <div class="col-7">{{ $siswa->jk }}</div>
                                        </div>
                                        <div class="row ing ps-2 py-1">
                                            <div class="col-4 dem">Agama</div>
                                            <div class="pisah">:</div>
                                            <div class="col-7">{{ $siswa->agama }}</div>
                                        </div>
                                        <div class="row ing ps-2 py-1">
                                            <div class="col-4 dem">Alamat</div>
                                            <div class="pisah">:</div>
                                            <div class="col-7">{{ $siswa->alamat }}</div>
                                        </div>
                                        <div class="row ing ps-2 py-1">
                                            <div class="col-4 dem">No.Telp</div>
                                            <div class="pisah">:</div>
                                            <div class="col-7">{{ $siswa->no_telp }}</div>
                                        </div>
                                        <div class="row ing ps-2 py-1">
                                            <div class="col-4 dem">No.Telp Rumah</div>
                                            <div class="pisah">:</div>
                                            <div class="col-7">{{ $siswa->no_telp_rumah }}</div>
                                        </div>
                                    </div>
                                    <div class="modal-footer p-2 bg-light">
                                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Kembali</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
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
                        "targets": 2
                    },
                    {
                        "orderable": false,
                        "targets": 5
                    },
                ],
            });
        });
    </script>
@endpush
