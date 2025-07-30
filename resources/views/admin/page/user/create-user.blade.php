@extends('layouts.main') {{-- Ganti jika kamu pakai layout lain --}}

@section('content')
<div class="container mt-4">
    <h3>Tambah User Baru</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('user.store') }}" method="POST">
        @csrf

        <div class="form-group mb-3">
            <label for="name">Nama Lengkap</label>
            <input type="text" name="name" class="form-control" required placeholder="Masukkan nama lengkap">
        </div>

        <div class="form-group mb-3">
            <label for="nisn">NISN</label>
            <input type="text" name="nisn" class="form-control" required maxlength="Masukan nisn mak10">
        </div>

        <div class="form-group mb-3">
            <label for="email">Alamat Email</label>
            <input type="email" name="email" class="form-control" required placeholder="Masukkan email aktif">
        </div>

        <div class="form-group mb-3">
            <label for="role">Peran / Role</label>
            <select name="role" class="form-control" required>
                <option value="1">Admin</option>
                <option value="2">Guru</option>
                <option value="3">Siswa</option>                                
            </select>
        </div>
        

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ url('/master-user') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
<script>
document.querySelector('select[name="role"]').addEventListener('change', function () {
    const nisnInput = document.getElementById('form-nisn');
    if (this.value == 3) {
        nisnInput.style.display = 'block';
    } else {
        nisnInput.style.display = 'none';
    }
});
</script>

