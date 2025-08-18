@extends('layouts.main')
@section('title', 'Master Tindakan')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
      <div class="bg-secondary card-header d-flex justify-content-between align-items-center mb-3">
          <h4 class="fw-bold text-white">Tindak / Sanksi</h4>          
      </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="datatable" class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Tindak Lanjut</th>
                            <th>Tingkatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->tindak_lanjut }}</td>
                            <td>{{ $item->tingkatan }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $item->id }}">Edit</button>                                
                        </tr>
                        @endforeach
                        @if($data->isEmpty())
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada data.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ========== Modal Tambah ========== -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('tindak-lanjut.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header bg-secondary">
                <h5 class="modal-title text-white">Tambah Tindakan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Tindak Lanjut</label>
                    <input type="text" name="tindak_lanjut" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tingkatan</label>
                    <select name="tingkatan" class="form-control" required>
                        <option value="">-- Pilih Tingkatan --</option>
                        <option value="Ringan">Ringan</option>
                        <option value="Sedang">Sedang</option>
                        <option value="Berat">Berat</option>
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

<!-- ========== Modal Edit ========== -->
@foreach($data as $item)
<div class="modal fade" id="modalEdit{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('tindak-lanjut.update', $item->id) }}" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header bg-secondary">
                <h5 class="modal-title text-white">Edit Tindakan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Tindak Lanjut</label>
                    <input type="text" name="tindak_lanjut" class="form-control" value="{{ $item->tindak_lanjut }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tingkatan</label>
                    {{-- Select dibuat disable agar tidak bisa dipilih ulang --}}
                    <select class="form-control" disabled>
                        <option value="Ringan" {{ $item->tingkatan == 'Ringan' ? 'selected' : '' }}>Ringan</option>
                        <option value="Sedang" {{ $item->tingkatan == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                        <option value="Berat" {{ $item->tingkatan == 'Berat' ? 'selected' : '' }}>Berat</option>
                    </select>
                    {{-- Hidden input untuk mengirim data tingkatan --}}
                    <input type="hidden" name="tingkatan" value="{{ $item->tingkatan }}">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-warning">Update</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            </div>
        </form>
    </div>
</div>
@endforeach

<!-- ========== Modal Hapus ========== -->

@endsection

@push('scripts')
<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#datatable').DataTable({
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ entri",
                "zeroRecords": "Data tidak ditemukan",
                "info": "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                "infoEmpty": "Tidak ada data tersedia",
                "infoFiltered": "(difilter dari total _MAX_ data)"
            }
        });
    });
</script>
@endpush
