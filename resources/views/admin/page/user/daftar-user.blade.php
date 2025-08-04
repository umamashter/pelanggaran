@extends('layouts.main')
@section('title', 'Master user')
@push('css')
    <style>
        #change_pass_form label.error {
            opacity: 1;
            color: #ff3b3b;
            font-size: 13px;
        }
    </style>
@endpush
@section('content')
    <div class="card shadow px-0 ">
        <div class="card-header bg-secondary bg-primary">
            <h3 class="fw-bolder mt-2 d-inline-flex text-white "
                style="">List User</h3>
                <a href="{{ url('/master-user/create') }}" class="btn btn-sm btn-light text-secondary fw-bold">
                    <i class="fas fa-user-plus" style="color: #6c757d;"></i> Tambah User
                </a>
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
                    <th>Email</th>
                    <th>Registrasi</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </thead>
                <tbody>
                    @foreach ($users->skip(1) as $user)
                        <tr>
                            <td scope="row">
                                {{ ($users->currentpage() - 1) * $users->perpage() + $loop->index + 1 }}
                            </td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            @if ($user->info == 1)
                                <td class="text-success">Sudah</td>
                            @else
                                <td class="text-danger">Belum</td>
                            @endif
                            @if ($user->role == 1)
                                <td class="text-primary" style="font-weight:600;">Admin</td>
                            @endif
                            @if ($user->role == 2)
                                <td class="text-info" style="font-weight:500;">Guru</td>
                            @endif
                            @if ($user->role == 3)
                                <td class="text-secondary" style="font-weight:500;">Siswa</td>
                            @endif
                            @if ($user->role == 4)
                                <td class="text-success" style="font-weight:500;">BK</td>
                            @endif
                            <td>
                                <button
                                    class="btn clickind btn-sm btn-warning btn-detail "
                                    style="" value="{{ $user->id }}"><i
                                        class="fas fa-pen"></i></button>
                                {{-- <form action="/master-user/" method="post" id="form"
                                    class="d-inline">
                                    @csrf --}}
                                <button type="button" onclick="deleteUser({{ $user->id }})"
                                    class="btn clickind btn-sm btn-danger "
                                    style="" id="show_confirm"><i class="fas fa-trash"></i></button>
                                {{-- </form> --}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @include('admin.page.user.edit_user')
    <div class="modal fade" id="exampleModalToggle2" data-bs-keyboard="false" aria-hidden="true"
        aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <form action="" method="post" id="change_pass_form">
                    <div class="modal-header bg-danger bg-gradient fs-4 fw-bold text-light" style="padding: 10px 15px;">
                        <h5 class="modal-title" id="exampleModalToggleLabel2">Ubah Password</h5>
                    </div>
                    <div class="modal-body">
                        <div>
                            <label for="password">Password</label>
                            <input minlength=8 type="password" class="form-control" name="password" id="password" required
                                onkeydown="return (event.keyCode!=13);">
                        </div>
                        <div class="mt-2">
                            <label for="password">Confirm Password</label>
                            <input minlength=8 type="password" class="form-control" name="password_confirm"
                                id="password_confirm" required onkeydown="return (event.keyCode!=13);">
                        </div>
                    </div>

                    <div class="modal-footer" style="padding: 10px 15px;">
                        <button class="btn btn-sm btn-secondary" data-bs-target="#myModal" data-bs-toggle="modal"
                            data-bs-dismiss="modal">Kembali</button>
                        <button type="submit" onclick="editPass(event)" class="btn btn-sm btn-success"
                            id="btn-pass">Perbarui</button>
                    </div>
                </form>
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
                        targets: 2
                    },
                    {
                        orderable: false,
                        responsivePriority: 2,
                        targets: 5
                    },
                ],
            });

        });

        // Edit user
        $(document).on('click', '.open_modal', function() {
            var url = "/master-user";
            var user_id = $(this).val();
            $.get(url + '/' + user_id + '/' + 'edit', function(data) {
                //success data
                const target = "{{ url('master-user/:id') }}".replace(':id', data.id)
                const pass = "{{ url('change-pass/:id') }}".replace(':id', data.id)
                $('input#user_id').val(data.id);
                $('input#nisn').val(data.nisn);
                $('input#name').val(data.name);
                $('input#email').val(data.email);
                $('select#role').val(data.role).change();
                if (data.info == '0') {
                    $('input#info2').prop('checked', true);
                } else {
                    $('input#info').prop('checked', true);
                };
                $('#editform').attr('action', target);
                $('#change_pass_form').attr('action', pass);
                $('#myModal').modal('show');
            })
        });

        function editUser(event) {
            var url = $('form#editform').attr('action');
            $('form#editform').validate({ // initialize the plugin
                rules: {
                    nisn: {
                        number: true,
                        required: true,
                        minlength: 8,
                        maxlength: 10,
                    },
                    name: {
                        required: true,
                        maxlength: 255
                    },
                    email: {
                        required: true,
                        maxlength: 255,
                        email: true
                    },
                    role: {
                        required: true,
                    },
                },
                messages: {
                    nisn: {
                        number: "* Nisn harus berupa angka!",
                        required: "* Nisn harus diisi!",
                        minlength: "* Nisn minimal 8 digit!",
                        maxlength: "* Nisn maksimal 10 digit!",
                    },
                    name: {
                        required: "* Nama harus diisi!",
                        maxlength: "* Nama maksimal 255 karakter!"
                    },
                    email: {
                        required: "* Email harus diisi!",
                        maxlength: "* Email maksimal 255 karakter!",
                        email: "* Masukkan Email yang valid",
                    }
                },
                submitHandler: function(form) {
                    $("#btn-update").attr("disabled", true);

                    let nisn = $('input#nisn').val();
                    let name = $('input#name').val();
                    let email = $('input#email').val();
                    let role = $('select#role').val();
                    let info = $("input[type='radio'][name='info']:checked").val();
                    $.ajax({
                        url: url,
                        type: "PUT",
                        data: {
                            _token: $('meta[name=csrf-token]').attr("content"),
                            nisn,
                            name,
                            email,
                            role,
                            info
                        },
                        success: function(res) {
                            if (res.success) {
                                swal(
                                    'User berhasil diubah!',
                                    "",
                                    'success'
                                ).then((result) => {
                                    window.location.reload();
                                });
                                console.log(res);
                            } else {
                                console.log(res.errors);
                                $.each(res.errors, function(key, val) {

                                    swal({
                                        title: "Nisn atau Email sudah digunakan!",
                                        icon: "warning",
                                        dangerMode: true,
                                        button: true,
                                    });
                                });
                            }
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText);
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


        function editPass(event) {

            var url = $('#change_pass_form').attr('action');
            $('form#change_pass_form').validate({ // initialize the plugin
                rules: {
                    password: {
                        required: true,
                        minlength: 8,
                    },
                    password_confirm: {
                        required: true,
                        minlength: 8,
                        equalTo: "#password"
                    }
                },
                messages: {
                    password: {
                        required: "* Password harus diisi!",
                        minlength: "* Password minimal 8 karakter!",
                    },
                    password_confirm: {
                        required: "* Confirm Password harus diisi!",
                        minlength: "* Password minimal 8 karakter!",
                        equalTo: "* Confirm password tidak valid"
                    }
                },
                submitHandler: function(form) {
                    $("#btn-pass").attr("disabled", true);
                    var password = $('input#password').val();

                    $.ajax({
                        url: url,
                        type: "PUT",
                        data: {
                            _token: $('meta[name=csrf-token]').attr("content"),
                            password: password
                        },
                        success: function(res) {
                            if (res.success) {

                                swal(
                                    'Password berhasil diubah!',
                                    "",
                                    'success'
                                ).then((result) => {

                                    window.location.reload();

                                });
                                console.log(res)
                            }
                        },
                        error: function(errors) {
                            console.log(errors);
                            swal({
                                title: "Password tidak valid!",
                                icon: "warning",
                                dangerMode: true,
                                button: true,
                            });
                        }
                    });
                }
            });
        }



        function deleteUser(user) {
            event.preventDefault();
            swal({
                    title: `Yakin ingin menghapus?`,
                    text: "Hapus Permanen User",
                    icon: "warning",
                    buttons: [true, "Yakin"],
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        // form.submit();
                        // setTimeout(() => {
                        //     swal("User berhasil dihapus!", "", "success");
                        // }, 1100);
                        $.ajax({
                            url: "/master-user/" + user,
                            type: "POST",
                            data: {
                                _token: $('meta[name=csrf-token]').attr("content"),
                                userId: user
                            },
                            success: function(res) {
                                if (res.success) {
                                    swal(
                                        'User berhasil dihapus!',
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
