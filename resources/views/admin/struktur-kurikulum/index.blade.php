@extends('layouts.main')

@section('title','Struktur Kurikulum')

@section('content')

<div class="card shadow">

    ```
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

        <h4 class="mb-0">
            Struktur Kurikulum
        </h4>

        <button
            class="btn btn-light"
            data-bs-toggle="modal"
            data-bs-target="#modalTambah">

            <i class="fas fa-plus"></i>
            Tambah Data

        </button>

    </div>

    <div class="card-body">

        @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

        @endif

        <div class="table-responsive">

            <table class="table table-bordered table-striped">

                <thead class="table-primary">

                    <tr>

                        <th>No</th>
                        <th>Kurikulum</th>
                        <th>Kelas</th>
                        <th>Mata Pelajaran</th>
                        <th>JP</th>
                        <th width="180">
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($datas as $data)

                    <tr>

                        <td>
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            {{ $data->kurikulum->nama_kurikulum }}
                        </td>

                        <td>
                            {{ $data->kelas->nama_kelas }}
                        </td>

                        <td>
                            {{ $data->mataPelajaran->nama_mapel }}
                        </td>

                        <td>
                            {{ $data->jam_pelajaran }}
                        </td>

                        <td>

                            <button
                                class="btn btn-warning btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#edit{{ $data->id }}">

                                Edit

                            </button>

                            <form
                                action="{{ route('struktur-kurikulum.destroy',$data->id) }}"
                                method="POST"
                                class="d-inline">

                                @csrf
                                @method('DELETE')

                                <button
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Hapus data?')">

                                    Hapus

                                </button>

                            </form>

                        </td>

                    </tr>

                    {{-- Modal Edit --}}

                    <div
                        class="modal fade"
                        id="edit{{ $data->id }}">

                        <div class="modal-dialog">

                            <div class="modal-content">

                                <form
                                    action="{{ route('struktur-kurikulum.update',$data->id) }}"
                                    method="POST">

                                    @csrf
                                    @method('PUT')

                                    <div class="modal-header bg-warning">

                                        <h5>
                                            Edit Struktur Kurikulum
                                        </h5>

                                    </div>

                                    <div class="modal-body">

                                        <div class="mb-3">

                                            <label>
                                                Kurikulum
                                            </label>

                                            <select
                                                name="kurikulum_id"
                                                class="form-control">

                                                @foreach($kurikulums as $kurikulum)

                                                <option
                                                    value="{{ $kurikulum->id }}"
                                                    {{ $data->kurikulum_id == $kurikulum->id ? 'selected' : '' }}>

                                                    {{ $kurikulum->nama_kurikulum }}

                                                </option>

                                                @endforeach

                                            </select>

                                        </div>

                                        <div class="mb-3">

                                            <label>
                                                Kelas
                                            </label>

                                            <select
                                                name="kelas_id"
                                                class="form-control">

                                                @foreach($kelas as $item)

                                                <option
                                                    value="{{ $item->id }}"
                                                    {{ $data->kelas_id == $item->id ? 'selected' : '' }}>

                                                    {{ $item->nama_kelas }}

                                                </option>

                                                @endforeach

                                            </select>

                                        </div>

                                        <div class="mb-3">

                                            <label>
                                                Mata Pelajaran
                                            </label>

                                            <select
                                                name="mata_pelajaran_id"
                                                class="form-control">

                                                @foreach($mapels as $mapel)

                                                <option
                                                    value="{{ $mapel->id }}"
                                                    {{ $data->mata_pelajaran_id == $mapel->id ? 'selected' : '' }}>

                                                    {{ $mapel->nama_mapel }}

                                                </option>

                                                @endforeach

                                            </select>

                                        </div>

                                        <div class="mb-3">

                                            <label>
                                                Jam Pelajaran
                                            </label>

                                            <input
                                                type="number"
                                                name="jam_pelajaran"
                                                value="{{ $data->jam_pelajaran }}"
                                                class="form-control">

                                        </div>

                                    </div>

                                    <div class="modal-footer">

                                        <button
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

                        <td
                            colspan="6"
                            class="text-center">

                            Belum ada data.

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>
    ```

</div>

{{-- Modal Tambah --}}

<div
    class="modal fade"
    id="modalTambah">

    ```
    <div class="modal-dialog">

        <div class="modal-content">

            <form
                action="{{ route('struktur-kurikulum.store') }}"
                method="POST">

                @csrf

                <div class="modal-header bg-primary text-white">

                    <h5>
                        Tambah Struktur Kurikulum
                    </h5>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label>
                            Kurikulum
                        </label>

                        <select
                            name="kurikulum_id"
                            class="form-control"
                            required>

                            <option value="">
                                Pilih Kurikulum
                            </option>

                            @foreach($kurikulums as $kurikulum)

                            <option value="{{ $kurikulum->id }}">

                                {{ $kurikulum->nama_kurikulum }}

                            </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="mb-3">

                        <label>
                            Kelas
                        </label>

                        <select
                            name="kelas_id"
                            class="form-control"
                            required>

                            <option value="">
                                Pilih Kelas
                            </option>

                            @foreach($kelas as $item)

                            <option value="{{ $item->id }}">

                                {{ $item->nama_kelas }}

                            </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="mb-3">

                        <label>
                            Mata Pelajaran
                        </label>

                        <select
                            name="mata_pelajaran_id"
                            class="form-control"
                            required>

                            <option value="">
                                Pilih Mata Pelajaran
                            </option>

                            @foreach($mapels as $mapel)

                            <option value="{{ $mapel->id }}">

                                {{ $mapel->nama_mapel }}

                            </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="mb-3">

                        <label>
                            Jam Pelajaran
                        </label>

                        <input
                            type="number"
                            name="jam_pelajaran"
                            class="form-control"
                            required>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        class="btn btn-success">

                        Simpan

                    </button>

                </div>

            </form>

        </div>

    </div>
    ```

</div>

@endsection