@extends('layouts.main') {{-- Ganti jika kamu pakai layout lain --}}

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0">
                <div class="card-header bg-secondary text-white fw-bold">
                    <i class="fas fa-user-plus me-2"></i> Tambah User Baru
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif


                    <form action="{{ route('user.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" required value="{{ old('name') }}" required placeholder="Masukkan nama lengkap">
                        </div>

                        <div class="mb-3" id="form-nisn">
                            <label for="nisn" class="form-label">NISN</label>
                            <input type="text" name="nisn" class="form-control" maxlength="10" placeholder="Masukkan NISN (maks 10 angka)">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Alamat Email</label>
                            <input type="email" name="email" class="form-control" required placeholder="Masukkan email aktif">
                        </div>

                        <div class="mb-4">
                            <label for="role" class="form-label">Peran / Role</label>
                            <select name="role" class="form-select" required>
                                <option value="1">Admin</option>
                                <option value="2">Guru</option>
                                <option value="3">Siswa</option>                                
                            </select>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ url('/master-user') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-1"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script>
document.addEventListener('DOMContentLoaded', function () {
        const roleSelect = document.querySelector('select[name="role"]');
        const nisnField = document.getElementById('form-nisn');

        function toggleNISNField() {
            if (roleSelect.value == '3') {
                nisnField.style.display = 'block';
            } else {
                nisnField.style.display = 'none';
            }
        }

        roleSelect.addEventListener('change', toggleNISNField);
        toggleNISNField(); // Panggil sekali saat halaman dimuat
    });
</script>

