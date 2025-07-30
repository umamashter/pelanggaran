@extends('layouts.main')
@section('title', 'Data Peraturan')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Daftar Peraturan</h1>
    <!-- Tombol Trigger Modal Tambah -->
    <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
        + Tambah Peraturan
    </button>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Peraturan</th>
                <th>Poin</th>
                <th>Jenis</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($peraturan as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->poin }}</td>
                    <td>{{ $item->jenisPeraturan->nama ?? '-' }}</td>
                    <td>
                        <!-- Tombol Edit -->
                        <button type="button" class="btn btn-warning btn-sm"
                            data-bs-toggle="modal" data-bs-target="#modalEdit{{ $item->id }}">
                            Edit
                        </button>

                        <!-- Tombol Hapus -->
                        <button type="button" class="btn btn-danger btn-sm"
                            data-bs-toggle="modal" data-bs-target="#modalHapus{{ $item->id }}">
                            Hapus
                        </button>
                    </td>
                </tr>

                <!-- Modal Edit -->
                <div class="modal fade" id="modalEdit{{ $item->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form action="/peraturan/{{ $item->id }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Peraturan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label>Nama</label>
                                        <input type="text" name="nama" class="form-control" value="{{ $item->nama }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Poin</label>
                                        <input type="number" name="poin" class="form-control" value="{{ $item->poin }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Jenis Peraturan</label>
                                        <select name="jenis_peraturan_id" class="form-select">
                                            @foreach ($jenisPeraturan as $jenis)
                                                <option value="{{ $jenis->id }}" {{ $item->jenis_peraturan_id == $jenis->id ? 'selected' : '' }}>
                                                    {{ $jenis->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Modal Hapus -->
                <div class="modal fade" id="modalHapus{{ $item->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form action="/peraturan/{{ $item->id }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Yakin ingin menghapus <strong>{{ $item->nama }}</strong>?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-danger">Hapus</button>
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

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form action="/peraturan" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Peraturan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Poin</label>
                        <input type="number" name="poin" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Jenis Peraturan</label>
                        <select name="jenis_peraturan_id" class="form-select" required>
                            @foreach ($jenisPeraturan as $jenis)
                                <option value="{{ $jenis->id }}">{{ $jenis->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
