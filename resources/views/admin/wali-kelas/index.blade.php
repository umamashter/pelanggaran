@extends('layouts.main')

@section('title', 'Wali Kelas')

@section('content')

@include('component.admin.ms-style')
<style>
    .wali-kelas-page {
        font-family: 'Inter', 'Poppins', system-ui, sans-serif;
        margin-top: 22px;
    }

    .btn-header-ms.btn-simpan-ms.btn-compact {
        height: 36px;
        padding: 0 8px;
        font-size: 10px;
        border-radius: 8px;
        gap: 3px;
    }

    .btn-header-ms.btn-simpan-ms.btn-compact i {
        font-size: 10px;
    }

    .select2-container--default .select2-selection--single {
        height: 44px !important;
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 10px !important;
        padding: 8px 12px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 42px !important;
    }
</style>

<div class="wali-kelas-page">

    {{-- HEADER CARD --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="header-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div>
                        <h4 class="mb-1 fw-bold" style="color: #1e293b; font-size: 20px;">
                            Wali Kelas
                        </h4>
                        <div class="d-flex flex-wrap gap-2 mt-1">
                            <span class="badge-modern badge-ta">
                                <i class="fas fa-address-book me-1"></i>
                                {{ $waliKelas->total() }} Wali Kelas
                            </span>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <button type="button" class="btn btn-header-ms btn-simpan-ms btn-compact"
                        data-bs-toggle="modal" data-bs-target="#modalTambah">
                        <i class="fas fa-user-plus me-1"></i> Tambah
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN TABLE --}}
    <div class="card table-card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="table_wali_kelas" class="table table-ms display" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Guru</th>
                            <th>Kelas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($waliKelas as $item)
                        <tr>
                            <td>{{ ($waliKelas->currentpage() - 1) * $waliKelas->perpage() + $loop->index + 1 }}</td>
                            <td>
                                <span class="fw-semibold">{{ $item->guru->nama ?? '-' }}</span>
                                @if ($item->guru?->kode_guru)
                                <br><small class="text-muted">{{ $item->guru->kode_guru }}</small>
                                @endif
                            </td>
                            <td>{{ $item->kelas->nama_kelas ?? '-' }}</td>
                            <td>
                                <div class="action-group-ms">
                                    <button type="button" onclick="deleteWaliKelas({{ $item->id }})"
                                        class="btn btn-outline-danger">
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
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-white fw-bold" id="modalTambahLabel">
                    <i class="fas fa-user-plus me-1"></i> Tambah Wali Kelas
                </h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formTambah" method="post">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="guru_id" class="form-label fw-semibold">Guru</label>
                        <select class="select2 form-select" id="guru_id" name="guru_id"
                            style="width: 100%; border-radius: 10px;">
                            <option value="">Pilih Guru</option>
                            @foreach ($gurus as $guru)
                            <option value="{{ $guru->id }}">
                                {{ $guru->nama }} @if($guru->kode_guru)({{ $guru->kode_guru }})@endif
                            </option>
                            @endforeach
                        </select>
                        <div id="guruMsg" class="text-danger small mt-1"></div>
                    </div>
                    <div class="mb-3">
                        <label for="kelas_id" class="form-label fw-semibold">Kelas</label>
                        <select class="select2 form-select" id="kelas_id" name="kelas_id"
                            style="width: 100%; border-radius: 10px;">
                            <option value="">Pilih Kelas</option>
                            @foreach ($kelas as $item)
                            <option value="{{ $item->id }}">{{ $item->nama_kelas }}</option>
                            @endforeach
                        </select>
                        <div id="kelasMsg" class="text-danger small mt-1"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-success" id="btnSimpan"
                        style="border-radius: 8px;">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.select2').select2({
        dropdownParent: $('#modalTambah')
    });

    var table = $('#table_wali_kelas').DataTable({
        pagingType: 'simple_numbers',
        responsive: true,
        processing: true,
        pageLength: 10,
        lengthMenu: [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
        language: {
            url: '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian.json',
            search: 'Cari:',
            lengthMenu: 'Tampilkan _MENU_ data',
            zeroRecords: 'Data tidak ditemukan',
            info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
            infoEmpty: 'Tidak ada data',
            infoFiltered: '(difilter dari _MAX_ total data)',
            paginate: { first: '«', previous: '‹', next: '›', last: '»' },
            aria: { paginate: { first: 'First', previous: 'Previous', next: 'Next', last: 'Last' } }
        },
        columnDefs: [
            { orderable: false, targets: [0, 1, 3] },
            { responsivePriority: 1, targets: 1 },
            { responsivePriority: 2, targets: 3 }
        ]
    });

    $('#table_wali_kelas_filter input').attr('placeholder', 'Cari wali kelas...');

    $('#formTambah').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var data = form.serialize();

        $('#guruMsg').html('');
        $('#kelasMsg').html('');

        $.ajax({
            url: '/wali-kelas/store',
            type: 'POST',
            data: data,
            beforeSend: function() {
                $('#btnSimpan').prop('disabled', true).text('Menyimpan...');
            },
            success: function(res) {
                if (res.success) {
                    swal('Berhasil!', res.message, 'success')
                        .then(function() { window.location.reload(); });
                }
            },
            error: function(xhr) {
                $('#btnSimpan').prop('disabled', false).text('Simpan');
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    if (errors.guru_id) {
                        $('#guruMsg').html(errors.guru_id.join(', '));
                    }
                    if (errors.kelas_id) {
                        $('#kelasMsg').html(errors.kelas_id.join(', '));
                    }
                } else {
                    swal('Error!', 'Terjadi kesalahan server', 'error');
                }
            }
        });
    });
});

function deleteWaliKelas(id) {
    swal({
        title: 'Yakin ingin menghapus?',
        text: 'Wali Kelas akan dihapus permanen',
        icon: 'warning',
        buttons: [true, 'Yakin'],
        dangerMode: true
    }).then(function(willDelete) {
        if (willDelete) {
            $.ajax({
                url: '/wali-kelas/' + id,
                type: 'DELETE',
                data: { _token: $('meta[name=csrf-token]').attr('content') },
                success: function(res) {
                    if (res.success) {
                        swal('Berhasil!', res.message, 'success')
                            .then(function() { window.location.reload(); });
                    }
                },
                error: function() {
                    swal('Error!', 'Terjadi kesalahan server', 'error');
                }
            });
        }
    });
}
</script>
@endpush
