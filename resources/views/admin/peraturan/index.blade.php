@extends('layouts.main')
@section('title', 'Daftar Peraturan')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Peraturan</h5>
            <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="fas fa-balance-scale"></i> Tambah Peraturan
            </button>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="datatable">
                    <thead class="table-light">
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
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $item->id }}">Edit</button>
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalHapus{{ $item->id }}">Hapus</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah">
    <div class="modal-dialog">
        <form action="/peraturan" method="POST" class="modal-content">
            @csrf
            <input type="hidden" name="form_type" value="tambah">
            <div class="modal-header bg-secondary">
                <h5 class="modal-title text-white">Tambah Peraturan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                {{-- Error Tambah --}}
                @if ($errors->any() && old('form_type')==='tambah')
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-3">
                    <label>Nama</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
                </div>
                <div class="mb-3">
                    <label>Poin</label>
                    <input type="number" name="poin" class="form-control" value="{{ old('poin') }}" required>
                </div>
                <div class="mb-3">
                    <label>Jenis Peraturan</label>
                    <select name="jenis_peraturan_id" class="form-select" required>
                        <option value="">-- Pilih Jenis --</option>
                        @foreach($jenisPeraturan as $jenis)
                        <option value="{{ $jenis->id }}" {{ old('jenis_peraturan_id') == $jenis->id ? 'selected' : '' }}>{{ $jenis->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Simpan</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit & Hapus -->
@foreach ($peraturan as $item)
<!-- Modal Edit -->
<div class="modal fade" id="modalEdit{{ $item->id }}">
    <div class="modal-dialog">
        <form action="/peraturan/{{ $item->id }}" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <input type="hidden" name="form_type" value="edit">
            <input type="hidden" name="id" value="{{ $item->id }}">
            <div class="modal-header bg-secondary">
                <h5 class="modal-title text-white">Edit Peraturan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                {{-- Error Edit --}}
                @if ($errors->any() && old('form_type')==='edit' && old('id')==$item->id)
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-3">
                    <label>Nama</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama', $item->nama) }}" required>
                </div>
                <div class="mb-3">
                    <label>Poin</label>
                    <input type="number" name="poin" class="form-control" value="{{ old('poin', $item->poin) }}" required>
                </div>
                <div class="mb-3">
                    <label>Jenis Peraturan</label>
                    <select name="jenis_peraturan_id" class="form-select" required>
                        @foreach($jenisPeraturan as $jenis)
                        <option value="{{ $jenis->id }}" {{ old('jenis_peraturan_id', $item->jenis_peraturan_id) == $jenis->id ? 'selected' : '' }}>{{ $jenis->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Update</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Hapus -->
<div class="modal fade" id="modalHapus{{ $item->id }}">
    <div class="modal-dialog">
        <form action="/peraturan/{{ $item->id }}" method="POST" class="modal-content">
            @csrf
            @method('DELETE')
            <div class="modal-header bg-secondary">
                <h5 class="modal-title text-white">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Yakin ingin menghapus <strong>{{ $item->nama }}</strong>?
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-danger">Hapus</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            </div>
        </form>
    </div>
</div>
@endforeach

@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {
    $('#datatable').DataTable({
        responsive: true,
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ entri",
            zeroRecords: "Data tidak ditemukan",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
            infoEmpty: "Tidak ada data tersedia",
            infoFiltered: "(difilter dari _MAX_ total entri)"
        }
    });

    // Script untuk menampilkan modal yang sesuai jika validasi gagal
    let formType = "{{ old('form_type') ?? '' }}";
    let editId = "{{ old('id') ?? '' }}";

    if(formType === 'tambah') {
        let modalTambah = document.getElementById('modalTambah');
        if(modalTambah) new bootstrap.Modal(modalTambah).show();
    } else if(formType === 'edit' && editId) {
        let modalEdit = document.getElementById('modalEdit' + editId);
        if(modalEdit) new bootstrap.Modal(modalEdit).show();
    }
});
</script>
@endpush
