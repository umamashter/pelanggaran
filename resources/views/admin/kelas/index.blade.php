@extends('layouts.main')
@section('title', 'Master Kelas')
@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-secondary bg-primary d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-white">Manajemen Kelas</h5>
            <button class="btn btn-sm bg-white" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="fas fa-chalkboard"></i> Tambah Kelas                            
            </button>
        </div>

        <div class="card-body">
            {{-- Alert --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Tabel --}}
            <div class="table-responsive">
                <table id="kelasTable" class="table table-bordered table-hover align-middle">
                    <thead class="table-light text-center">
                        <tr>
                            <th>No</th>
                            <th>Nama Kelas</th>
                            <th style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kelas as $index => $k)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $k->nama_kelas }}</td>
                            <td class="text-center text-nowrap">
                                <button class="btn btn-warning btn-sm me-1" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $k->id }}">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>
                                <form action="{{ route('kelas.destroy', $k->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>

                        {{-- Modal Edit --}}
                        <div class="modal fade" id="modalEdit{{ $k->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content ">
                                    <form action="{{ route('kelas.update', $k->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header bg-secondary">
                                            <h5 class="modal-title text-white">Edit Kelas</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="nama_kelas">Nama Kelas</label>
                                                <input type="text" class="form-control" name="nama_kelas"
                                                    value="{{ $k->nama_kelas }}" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('kelas.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-secondary">
                    <h5 class="modal-title text-white">Tambah Kelas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_kelas">Nama Kelas</label>
                        <input type="text" class="form-control" name="nama_kelas"
                            placeholder="Masukkan nama kelas" required
                            value="{{ old('nama_kelas') }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script Datatable --}}
@push('scripts')
<script>
    $(document).ready(function () {
        $('#kelasTable').DataTable({
            pageLength: 5,
            lengthMenu: [5, 10, 25, 50, 100],
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                zeroRecords: "Tidak ditemukan data yang cocok",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data",
                infoFiltered: "(difilter dari _MAX_ total data)"
            }
        });
    });
</script>
@endpush
@endsection
