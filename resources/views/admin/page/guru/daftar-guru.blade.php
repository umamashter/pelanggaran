@extends('layouts.main')
@section('title', 'Master Guru')
@push('css')
    <style>
        label.error {
            opacity: 1;
            color: #ff3b3b;
            font-size: 13px;
        }
    </style>
@endpush
@section('content')
    <div class="card shadow px-0 animate__animated animate__fadeInDown">
        <div class="card-header bg-gradient bg-info">
            <h3 class="fw-bolder mt-2 d-inline-flex text-white animate__animated animate__fadeInRight" style="animation-delay: 0.5s;">List Walikelas</h3>
            <button type="button" class="btn btn-md btn-light text-info float-end animate__animated animate__fadeInLeft" style="animation-delay: 0.5s;" data-bs-toggle="modal"
                data-bs-target="#myModal">
                <i class="fas fa-user-plus me-1"></i> Tambah
            </button>
        </div>

        <div class="card-body">
            @if (session()->has('errors'))
                <ul>
                    <li>{{ session('errors') }}</li>
                </ul>
            @endif
            <table id="table_data_user" class="table table-bordered display" cellspacing="0" width="100%">
                <thead class="thead-inverse">
                    <th>No</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Aksi</th>
                </thead>
                <tbody>
                    @foreach ($wali_kelas as $guru)
                        <tr>
                            <td scope="row">
                                {{ ($wali_kelas->currentpage() - 1) * $wali_kelas->perpage() + $loop->index + 1 }}
                            </td>
                            <td>{{ $guru->name }}</td>
                            <td>{{ $guru->kelas->nama_kelas }}</td>
                            <td>
                                <button type="submit" class="clickind btn btn-sm btn-danger animate__animated animate__flip"
                                style="animation-delay: 1s;" id="show_confirm" onclick="deleteGuru({{ $guru->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-gradient bg-success fs-4 fw-bold text-light py-2">
                    <h4 class="modal-title" id="myModalLabel">Tambah WaliKelas</h4>
                </div>
                <div class="modal-body">
                    <form action="" method="post" id="editform">
                        @csrf
                        <div>
                            <label for="name" class="mb-0">Nama</label>
                            <input type="text" class="form-control-sm form-control" name="name" id="name">
                            <div id="nameMsg"></div>

                        </div>
                        <div class="mt-2">
                            <label for="user_id" class="mb-0">User</label>
                            <select class="select2 form-select form-select-sm mb-2" id="user_id" name="user_id" style="width: 100%;">
                                <option value="" selected>Pilih User</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->email }}</option>
                                @endforeach
                            </select>
                            <div id="userMsg"></div>
                        </div>

                        <div class="mt-2">
                            <label for="kelas_id" class="mb-0">Kelas</label>
                            <select class="select2 mb-2" id="kelas_id" name="kelas_id" style="width: 100%;">
                                <option value="" selected>Pilih Kelas</option>

                                @foreach ($kelas as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_kelas }}</option>
                                @endforeach
                            </select>
                            <div id="kelasMsg"></div>

                        </div>
                </div>
                <div class="modal-footer py-2" style="padding: 12px;">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button onclick="tambahGuru(event)" class="btn btn-sm btn-success" id="tambah">Tambah</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2();
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
                'columnDefs': [{
                        orderable: false,
                        responsivePriority: 1,
                        targets: 1
                    },
                    {
                        orderable: false,
                        responsivePriority: 2,
                        targets: 3
                    },
                ],
            });

        });

        function tambahGuru(event) {
            $('form#editform').validate({ // initialize the plugin
                rules: {
                    name: {
                        required: true,
                        maxlength: 255
                    },
                    kelas_id: {
                        required: true,
                    },
                    user_id: {
                        required: true,
                    },
                },
                messages: {
                    name: {
                        required: "* Nama harus diisi!",
                        maxlength: "* Nama maksimal 255 karakter!"
                    },
                    kelas_id: {
                        required: "* Kelas harus dipilih!",
                    },
                    user_id: {
                        required: "* User harus dipilih!",
                    }
                },
                errorPlacement: function(error, element) {
                    if (element.attr("name") == "name") {

                        $("#nameMsg").html(error);
                    }
                    if (element.attr("name") == "kelas_id") {

                        $("#kelasMsg").html(error);
                    }
                    if (element.attr("name") == "user_id") {

                        $("#userMsg").html(error);
                    }
                },
                submitHandler: function(form) {
                    $("#tambah").attr("disabled", true);
                    let name = $('input#name').val();
                    let user = $('select#user_id').val();
                    let kelas = $('select#kelas_id').val();
                    $.ajax({
                        url: "/master-guru/store",
                        type: "POST",
                        data: {
                            _token: $('meta[name=csrf-token]').attr("content"),
                            name,
                            user,
                            kelas,
                        },
                        success: function(res) {
                            if (res.success) {
                                swal(
                                    'Guru berhasil dibuat!',
                                    "",
                                    'success'
                                ).then((result) => {
                                    window.location.reload();
                                });
                                console.log(res)
                            } else {
                                console.log(res.errors)
                    $("#tambah").attr("disabled", false);

                                $.each(res.errors, function(key, val) {

                                    swal({
                                        title: "Data tidak valid!",
                                        icon: "warning",
                                        dangerMode: true,
                                        button: true,
                                    });
                                });
                            }
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText);
                    $("#tambah").attr("disabled", false);

                            swal({
                                title: "Data tidak valid!",
                                icon: "warning",
                                dangerMode: true,
                                button: true,
                            });
                        }
                    });
                }
            });
        }

        function deleteGuru(guru) {
            event.preventDefault();
            swal({
                    title: `Yakin ingin menghapus?`,
                    text: "Hapus Permanen Guru",
                    icon: "warning",
                    buttons: [true, "Yakin"],
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: "/master-guru/" + guru,
                            type: "POST",
                            data: {
                                _token: $('meta[name=csrf-token]').attr("content"),
                                guruId: guru
                            },
                            success: function(res) {
                                if (res.success) {
                                    swal(
                                        'Guru berhasil dihapus!',
                                        "",
                                        'success'
                                    ).then((result) => {
                                        window.location.reload();
                                    });
                                    console.log(res)
                                }
                            },
                            error: function(error) {
                                console.log(error)
                            }
                        });
                    }

                });
        }
    </script>
@endpush
