@extends('layouts.main')
@section('title', 'Master User')
@push('css')
@include('component.admin.ms-style')
<style>
    #change_pass_form label.error {
        opacity: 1;
        color: #ff3b3b;
        font-size: 13px;
    }

    /* Master User — compact header button */
    .btn-header-ms.btn-simpan-ms.btn-compact {
        height: 34px;
        padding: 0 14px;
        font-size: 12px;
        border-radius: 8px;
    }

    /* Master User — badge role */
    .badge-role {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        white-space: nowrap;
    }
    .badge-role.admin { background: #fef3c7; color: #d97706; }
    .badge-role.guru { background: #dbeafe; color: #2563eb; }
    .badge-role.siswa { background: #f0fdf4; color: #16a34a; }
    .badge-role.bk { background: #f0e7ff; color: #7c3aed; }

    /* Master User — alert error */
    .alert-error-mu {
        background: #fef2f2;
        border-left: 4px solid #dc2626;
        border-radius: 12px;
        padding: 12px 20px;
        margin-bottom: 16px;
        font-size: 14px;
        color: #991b1b;
        list-style: none;
    }
    .alert-error-mu li { list-style: none; }

    @media (max-width: 575.98px) {
        .dataTables_scroll {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .dataTables_scrollHead {
            position: sticky;
            top: 0;
            z-index: 10;
        }
    }
</style>
@endpush
@section('content')
<div class="master-user-page">

    {{-- ===== HEADER CARD ===== --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">

            <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">

                {{-- Left: Icon + Title + Badge --}}
                <div class="d-flex align-items-center gap-3">
                    <div class="header-icon">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <div>
                        <h4 class="mb-1 fw-bold" style="color: var(--ms-text); font-size: 20px;">
                            Master User
                        </h4>
                        <div class="d-flex flex-wrap gap-2 mt-1">
                            <span class="badge-modern badge-ta">
                                <i class="fas fa-address-book me-1"></i>
                                {{ $users->total() }} User
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Right: Buttons --}}
                <div class="d-flex align-items-center gap-2" style="flex-wrap:nowrap;">

                    <button
                        class="btn btn-header-ms btn-simpan-ms btn-compact"
                        style="background:#e0f2fe;color:#0369a1;"
                        data-bs-toggle="modal"
                        data-bs-target="#importUser" title="Import Excel">
                        <i class="fas fa-file-excel"></i> Import
                    </button>

                    <a href="{{ url('/master-user/create') }}" class="btn btn-header-ms btn-simpan-ms btn-compact">
                        <i class="fas fa-user-plus"></i> Tambah
                    </a>

                </div>

            </div>

        </div>
    </div>

    {{-- ===== MAIN TABLE CARD ===== --}}
    <div class="card table-card">
        <div class="card-body">

            @if (session()->has('errors'))
            <ul class="alert-error-mu">
                <li>{{ session('errors') }}</li>
            </ul>
            @endif

            <table id="table_data_user" class="table table-ms display" cellspacing="0" width="100%">
                <thead class="thead-inverse">
                    <th>No</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td scope="row">
                            {{ ($users->currentpage() - 1) * $users->perpage() + $loop->index + 1 }}
                        </td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}</td>
                        @if ($user->info == 1)
                        <td><span class="badge-status-ms aktif"><i class="fas fa-check-circle me-1"></i> Aktif</span></td>
                        @else
                        <td><span class="badge-status-ms nonaktif"><i class="fas fa-minus-circle me-1"></i> Tidak Aktif</span></td>
                        @endif
                        @if ($user->role == 1)
                        <td><span class="badge-role admin">Admin</span></td>
                        @endif
                        @if ($user->role == 2)
                        <td><span class="badge-role guru">Guru</span></td>
                        @endif
                        @if ($user->role == 3)
                        <td><span class="badge-role siswa">Siswa</span></td>
                        @endif
                        @if ($user->role == 4)
                        <td><span class="badge-role bk">BK</span></td>
                        @endif
                        <td>
                            <div class="action-group-ms">
                                <button class="btn btn-outline-warning open_modal" title="Edit" value="{{ $user->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-outline-danger" title="Hapus"
                                    onclick="deleteUser({{ $user->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
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

<div class="modal fade"
    id="importUser"
    tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form
                action="{{ route('user.import') }}"
                method="POST"
                enctype="multipart/form-data">

                @csrf

                <div class="modal-header bg-success text-white">

                    <h5 class="modal-title">
                        Import User
                    </h5>

                    <button
                        type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <label>
                        File Excel
                    </label>

                    <input
                        type="file"
                        name="file"
                        class="form-control"
                        accept=".xlsx,.xls,.csv"
                        required>

                    <small class="text-muted">

                        Format:
                        nisn | name | email | role

                    </small>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Batal

                    </button>

                    <button
                        type="submit"
                        class="btn btn-success">

                        Import

                    </button>

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
            responsive: false,
            scrollX: true,
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
                    targets: 1
                },
                {
                    orderable: false,
                    targets: 2
                },
                {
                    orderable: false,
                    targets: 5
                },
            ],
        });

        $('#table_data_user_filter input').attr('placeholder', 'Cari user...');
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
            $('input#username').val(data.username);
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
