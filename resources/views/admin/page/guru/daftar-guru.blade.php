@extends('layouts.main')
@section('title', 'Master Guru')
@push('css')
@include('component.admin.ms-style')
<style>
    .btn-header-ms.btn-simpan-ms.btn-compact {
        height: 34px;
        padding: 0 14px;
        font-size: 12px;
        border-radius: 8px;
    }
</style>
@endpush
@section('content')

<div class="master-siswa-page">

    {{-- ===== HEADER CARD ===== --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4 d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">

            <div class="d-flex align-items-center gap-3">
                <div class="header-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                <div>
                    <h4 class="mb-1 fw-bold" style="color: var(--ms-text); font-size: 20px;">Master Guru</h4>
                    <span class="badge-modern badge-ta">
                        <i class="fas fa-address-book me-1"></i>
                        {{ $wali_kelas->total() }} Wali Kelas
                    </span>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2" style="flex-wrap:nowrap;">
                <button type="button" class="btn btn-header-ms btn-simpan-ms btn-compact"
                    data-bs-toggle="modal" data-bs-target="#myModal">
                    <i class="fas fa-user-plus"></i> Tambah
                </button>
            </div>

        </div>
    </div>

    {{-- ===== MAIN TABLE CARD ===== --}}
    <div class="card table-card">
        <div class="card-body">

            @if (session()->has('errors'))
            <div class="alert alert-modern-ms alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-triangle me-1"></i> {{ session('errors') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <div class="table-responsive">
                <table id="table_data_user" class="table table-ms display" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Guru</th>
                            <th>Nama</th>
                            <th>NIP</th>
                            <th>No HP</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($wali_kelas as $guru)
                        <tr>
                            <td>{{ ($wali_kelas->currentpage() - 1) * $wali_kelas->perpage() + $loop->index + 1 }}</td>
                            <td>{{ $guru->guru->kode_guru ?? '-' }}</td>
                            <td>{{ $guru->name }}</td>
                            <td>{{ $guru->guru->nip ?? '-' }}</td>
                            <td>{{ $guru->guru->no_hp ?? '-' }}</td>
                            <td>
                                <div class="action-group-ms">
                                    <button type="button" class="btn btn-outline-warning"
                                        data-bs-toggle="modal" data-bs-target="#myModal">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger" title="Hapus"
                                        onclick="deleteGuru({{ $guru->id }})">
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

</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #16a34a, #22c55e);">
                <h4 class="modal-title text-white fw-bold" id="myModalLabel">
                    <i class="fas fa-user-plus me-1"></i> Tambah Wali Kelas
                </h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="" method="post" id="editform">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Nama</label>
                        <input type="text" class="form-control" name="name" id="name"
                            style="border-radius: 10px; height: 44px;">
                        <div id="nameMsg"></div>
                    </div>
                    <div class="mb-3">
                        <label for="user_id" class="form-label fw-semibold">User</label>
                        <select class="select2 form-select" id="user_id" name="user_id"
                            style="width: 100%; border-radius: 10px;">
                            <option value="" selected>Pilih User</option>
                            @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->email }}</option>
                            @endforeach
                        </select>
                        <div id="userMsg"></div>
                    </div>
                    <div class="mb-3">
                        <label for="kelas_id" class="form-label fw-semibold">Kelas</label>
                        <select class="select2 form-select" id="kelas_id" name="kelas_id"
                            style="width: 100%; border-radius: 10px;">
                            <option value="" selected>Pilih Kelas</option>
                            @foreach ($kelas as $item)
                            <option value="{{ $item->id }}">{{ $item->nama_kelas }}</option>
                            @endforeach
                        </select>
                        <div id="kelasMsg"></div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button onclick="tambahGuru(event)" class="btn btn-success" id="tambah"
                    style="border-radius: 8px;">Tambah</button>
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
            pageLength: 10,
            lengthMenu: [
                [5, 10, 25, 50, 100],
                [5, 10, 25, 50, 100]
            ],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian.json",
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                zeroRecords: "Data tidak ditemukan",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data",
                infoFiltered: "(difilter dari _MAX_ total data)",
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
                }
            },
            'columnDefs': [{
                    orderable: false,
                    responsivePriority: 1,
                    targets: 1
                },
                {
                    orderable: false,
                    responsivePriority: 2,
                    targets: 5
                },
            ],
        });

        $('#table_data_user_filter input').attr('placeholder', 'Cari guru...');
    });

    function tambahGuru(event) {
        $('form#editform').validate({
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
