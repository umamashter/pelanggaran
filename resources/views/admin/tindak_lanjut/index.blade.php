@extends('layouts.main')

@section('content')
<div class="container">
    <h4 class="mb-3">Data Tindak Lanjut</h4>

    <!-- Tombol Tambah -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">+ Tambah Tindakan</button>

    <!-- Tabel Data -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
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
                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalHapus{{ $item->id }}">Hapus</button>
                    </td>
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

<!-- ========== Modal Tambah ========== -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('tindak-lanjut.store') }}" method="POST" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Tambah Tindakan</h5>
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

<!-- ========== Modal Edit (Per Item) ========== -->
@foreach($data as $item)
<div class="modal fade" id="modalEdit{{ $item->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('tindak-lanjut.update', $item->id) }}" method="POST" class="modal-content">
      @csrf
      @method('PUT')
      <div class="modal-header bg-warning">
        <h5 class="modal-title">Edit Tindakan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Tindak Lanjut</label>
          <input type="text" name="tindak_lanjut" class="form-control" value="{{ $item->tindak_lanjut }}" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Tingkatan</label>
          <select name="tingkatan" class="form-control" required>
            <option value="Ringan" {{ $item->tingkatan == 'Ringan' ? 'selected' : '' }}>Ringan</option>
            <option value="Sedang" {{ $item->tingkatan == 'Sedang' ? 'selected' : '' }}>Sedang</option>
            <option value="Berat" {{ $item->tingkatan == 'Berat' ? 'selected' : '' }}>Berat</option>
          </select>
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

<!-- ========== Modal Hapus (Per Item) ========== -->
@foreach($data as $item)
<div class="modal fade" id="modalHapus{{ $item->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('tindak-lanjut.destroy', $item->id) }}" method="POST" class="modal-content">
      @csrf
      @method('DELETE')
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">Konfirmasi Hapus</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <p>Yakin ingin menghapus <strong>{{ $item->tindak_lanjut }}</strong>?</p>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
      </div>
    </form>
  </div>
</div>
@endforeach

@endsection
