@extends('layouts.main')
@section('title', 'Master Siswa')
@section('content')
    <div class="card shadow px-0">
        <div class="card-header bg-secondary bg-primary">
            <h3 class="fw-bolder mt-2 d-inline-flex text-white">List Siswa</h3>
            <div class="dropdown float-right">
                <button class="btn bg-gradient btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown"
                    aria-expanded="false">Filter Kelas</button>
                <ul class="dropdown-menu" style="max-height: 180px; overflow-y: auto;
                border: 1px solid #999; border-radius: 7px;">
                    @foreach ($kelas as $item)
                        <li>
                            <a class="dropdown-item text-decoration-none author" style="padding: 5px 0 5px 15px;"
                            href="/master-siswa?kelas={{ $item->nama_kelas }}">
                                {{ $item->nama_kelas }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="card-body">
            <table id="table_data_user" class="table table-bordered display md:nowrap" cellspacing="0" width="100%" style="z-index: 2;">
                <thead class="thead-inverse">
                    <th>No.</th>
                    <th>Nisn</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Poin</th>
                    <th>Aksi</th>
                </thead>
                <tbody>
                    @foreach ($siswas as $siswa)
                        <tr>
                            <td scope="row">{{ ($siswas->currentpage() - 1) * $siswas->perpage() + $loop->index + 1 }}
                            </td>
                            <td>{{ $siswa->nisn }}</td>
                            <td>{{ $siswa->nama }}</td>
                            <td>{{ $siswa->kelas->nama_kelas }}</td>
                            <td><a href="/master-histori/{{ $siswa->id }}"  
                                    @if ($siswa->poin == 0) style="color:green;" @endif
                                    @if ($siswa->poin <= 55) style="color:#fcbc05;" @endif
                                    @if ($siswa->poin <= 149) style="color:#fd5d03;" @endif
                                    @if ($siswa->poin >= 150) style="color:red;" @endif>
                                    <b>{{ $siswa->poin }}</b>
                                </a>
                            </td>
                            <td data-label="Posisi">
                                <a href="#modalCenter{{ $siswa->id }}" role="button"
                                    class="clickind btn btn-sm btn-info mb-1 "
                                    style="" data-bs-toggle="modal">
                                    <i class="fas fa-info"></i></a>
                                <a href="/pelanggaran/tambah/{{ $siswa->nisn }}"
                                    class="clickind btn btn-sm btn-danger mb-1 "
                                    style="">
                                    <i class="fas fa-plus"></i></a>
                                <a href="/pelanggaran/kurang/{{ $siswa->nisn }}"
                                    class="clickind btn btn-sm btn-success mb-1 "
                                    style="">
                                    <i class="fas fa-minus"></i></a>
                                <a href="#modalEdit{{ $siswa->id }}"
                                    class="clickind btn btn-sm btn-warning mb-1 "
                                    style=""
                                    data-bs-toggle="modal">
                                    <i class="fas fa-edit"></i>
                                    </a>
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
                                            <div class="col-7">
                                                {{ $siswa->ttl }}
                                            </div>
                                        </div>
                                        <div class="row ing ps-2 py-1">
                                            <div class="col-4 dem">JK</div>
                                            <div class="pisah">:</div>
                                            <div class="col-7">
                                                {{ $siswa->jk }}
                                            </div>
                                        </div>
                                        <div class="row ing ps-2 py-1">
                                            <div class="col-4 dem">Agama</div>
                                            <div class="pisah">:</div>
                                            <div class="col-7">
                                                {{ $siswa->agama }}
                                            </div>
                                        </div>
                                        <div class="row ing ps-2 py-1">
                                            <div class="col-4 dem">Alamat</div>
                                            <div class="pisah">:</div>
                                            <div class="col-7">
                                                {{ $siswa->alamat }}
                                            </div>
                                        </div>
                                        <div class="row ing ps-2 py-1">
                                            <div class="col-4 dem">No.Telp</div>
                                            <div class="pisah">:</div>
                                            <div class="col-7">
                                                <a class="linkind" style="color: darkblue"
                                                    href="tel:{{ $siswa->no_telp }}">
                                                    {{ $siswa->no_telp }}
                                                </a>
                                            </div>
                                        </div>
                                        <div class="row ing ps-2 py-1">
                                            <div class="col-4 dem">No.Telp Rumah</div>
                                            <div class="pisah">:</div>
                                            <div class="col-7">
                                                <a class="linkind" style="color: darkblue"
                                                    href="tel:{{ $siswa->no_telp_rumah }}">
                                                    {{ $siswa->no_telp_rumah }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer p-2 bg-light">
                                        <button type="button" class="btn btn-sm btn-secondary"
                                            data-bs-dismiss="modal">Kembali</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal Edit Siswa -->
                        <div class="modal fade" id="modalEdit{{ $siswa->id }}" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <form action="{{ route('siswa.update', $siswa->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Kelas: {{ $siswa->nama }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Dropdown Kelas -->
                                        <div class="mb-3">
                                            <label for="kelas_id" class="form-label">Pilih Kelas Baru</label>
                                            <select name="kelas_id" class="form-select" required>
                                                @foreach($kelas as $k)
                                                    <option value="{{ $k->id }}" {{ $siswa->kelas_id == $k->id ? 'selected' : '' }}>
                                                        {{ $k->nama_kelas }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    </div>
                                </div>
                            </form>
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
                        "width": "30%",
                        "targets": 2
                    },
                    {
                        "orderable": false,
                        "targets": 5
                    },
                ],
                "pageLength": 10
            });
        });
    </script>
@endpush
