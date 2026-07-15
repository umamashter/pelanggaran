@extends('layouts.main')

@section('title','Data Kurikulum')

@section('content')

<div class="card shadow">

    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">

        <h4 class="mb-0">
            Data Kurikulum
        </h4>

        <button
            class="btn btn-light"
            data-bs-toggle="modal"
            data-bs-target="#modalTambah">

            <i class="fas fa-plus"></i>
            Tambah Kurikulum

        </button>

    </div>

    <div class="card-body">

        @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

        @endif

        <div class="table-responsive">

            <table class="table table-bordered table-striped align-middle">

                <thead class="table-success">

                    <tr>

                        <th width="60">
                            No
                        </th>

                        <th>
                            Nama Kurikulum
                        </th>

                        <th>
                            Keterangan
                        </th>

                        <th width="120">
                            Status
                        </th>

                        <th width="250">
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($kurikulums as $kurikulum)

                    <tr>

                        <td>
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            {{ $kurikulum->nama_kurikulum }}
                        </td>

                        <td>
                            {{ $kurikulum->keterangan }}
                        </td>

                        <td>

                            @if($kurikulum->aktif)

                            <span class="badge bg-success">

                                Aktif

                            </span>

                            @else

                            <span class="badge bg-secondary">

                                Tidak Aktif

                            </span>

                            @endif

                        </td>

                        <td>

                            @if(!$kurikulum->aktif)

                            <a
                                href="{{ route('kurikulum.aktifkan',$kurikulum->id) }}"
                                class="btn btn-success btn-sm">

                                <i class="fas fa-check"></i>
                                Aktifkan

                            </a>

                            @endif

                            <button
                                class="btn btn-warning btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#edit{{ $kurikulum->id }}">

                                <i class="fas fa-edit"></i>
                                Edit

                            </button>

                            <form
                                action="{{ route('kurikulum.destroy',$kurikulum->id) }}"
                                method="POST"
                                class="d-inline">

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus data ini?')">

                                    <i class="fas fa-trash"></i>
                                    Hapus

                                </button>

                            </form>

                        </td>

                    </tr>

                    {{-- Modal Edit --}}

                    <div
                        class="modal fade"
                        id="edit{{ $kurikulum->id }}"
                        tabindex="-1">

                        <div class="modal-dialog">

                            <div class="modal-content">

                                <form
                                    action="{{ route('kurikulum.update',$kurikulum->id) }}"
                                    method="POST">

                                    @csrf
                                    @method('PUT')

                                    <div class="modal-header bg-warning">

                                        <h5 class="modal-title">

                                            Edit Kurikulum

                                        </h5>

                                        <button
                                            type="button"
                                            class="btn-close"
                                            data-bs-dismiss="modal"></button>

                                    </div>

                                    <div class="modal-body">

                                        <div class="mb-3">

                                            <label class="form-label">

                                                Nama Kurikulum

                                            </label>

                                            <input
                                                type="text"
                                                name="nama_kurikulum"
                                                value="{{ $kurikulum->nama_kurikulum }}"
                                                class="form-control"
                                                required>

                                        </div>

                                        <div class="mb-3">

                                            <label class="form-label">

                                                Keterangan

                                            </label>

                                            <textarea
                                                name="keterangan"
                                                class="form-control"
                                                rows="3">{{ $kurikulum->keterangan }}</textarea>

                                        </div>

                                    </div>

                                    <div class="modal-footer">

                                        <button
                                            type="button"
                                            class="btn btn-secondary"
                                            data-bs-dismiss="modal">

                                            Tutup

                                        </button>

                                        <button
                                            type="submit"
                                            class="btn btn-primary">

                                            Update

                                        </button>

                                    </div>

                                </form>

                            </div>

                        </div>

                    </div>

                    @empty

                    <tr>

                        <td colspan="5">

                            Belum ada data kurikulum.

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

{{-- Modal Tambah --}}

<div
    class="modal fade"
    id="modalTambah"
    tabindex="-1">

    ```
    <div class="modal-dialog">

        <div class="modal-content">

            <form
                action="{{ route('kurikulum.store') }}"
                method="POST">

                @csrf

                <div class="modal-header bg-success text-white">

                    <h5 class="modal-title">

                        Tambah Kurikulum

                    </h5>

                    <button
                        type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">

                            Nama Kurikulum

                        </label>

                        <input
                            type="text"
                            name="nama_kurikulum"
                            class="form-control"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">

                            Keterangan

                        </label>

                        <textarea
                            name="keterangan"
                            class="form-control"
                            rows="3"></textarea>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Tutup

                    </button>

                    <button
                        type="submit"
                        class="btn btn-success">

                        Simpan

                    </button>

                </div>

            </form>

        </div>

    </div>
</div>

@endsection