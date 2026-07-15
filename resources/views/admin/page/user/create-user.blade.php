@extends('layouts.main')
@section('title', 'Tambah User')
@push('css')
<style>
    :root {
        --cu-primary: #16a34a;
        --cu-primary-dark: #15803d;
        --cu-primary-light: #dcfce7;
        --cu-bg: #f5f7fb;
        --cu-border: #e2e8f0;
        --cu-text: #1e293b;
        --cu-text-soft: #64748b;
    }

    .create-user-page {
        font-family: 'Inter', 'Poppins', system-ui, sans-serif;
        max-width: 780px;
        margin: 22px auto 0;
        padding: 0 16px;
    }

    /* ===== Breadcrumb ===== */
    .breadcrumb-cu {
        margin-bottom: 20px;
    }

    .breadcrumb-cu .breadcrumb {
        background: transparent;
        padding: 0;
        margin: 0;
    }

    .breadcrumb-cu .breadcrumb-item {
        font-size: 13px;
    }

    .breadcrumb-cu .breadcrumb-item a {
        color: var(--cu-text-soft);
        text-decoration: none;
        transition: color .2s;
    }

    .breadcrumb-cu .breadcrumb-item a:hover {
        color: var(--cu-primary);
    }

    .breadcrumb-cu .breadcrumb-item.active {
        color: var(--cu-text);
        font-weight: 500;
    }

    .breadcrumb-cu .breadcrumb-item+.breadcrumb-item::before {
        color: #cbd5e1;
    }

    /* ===== Header Icon ===== */
    .header-icon-cu {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: linear-gradient(135deg, #16a34a, #22c55e);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 22px;
        box-shadow: 0 4px 14px rgba(22, 163, 74, .3);
        flex-shrink: 0;
    }

    /* ===== Card ===== */
    .create-card {
        border: none;
        border-radius: 18px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, .06), 0 2px 8px rgba(0, 0, 0, .04);
        transition: box-shadow .25s;
    }

    .create-card:hover {
        box-shadow: 0 6px 24px rgba(0, 0, 0, .08), 0 2px 10px rgba(0, 0, 0, .04);
    }

    .create-card-header {
        padding: 24px 28px 20px;
        border-bottom: 1px solid #f1f5f9;
    }

    .create-card-body {
        padding: 24px 28px 28px;
    }

    /* ===== Form Labels ===== */
    .form-label-cu {
        font-weight: 600;
        font-size: 14px;
        color: #374151;
        margin-bottom: 8px;
        display: block;
    }

    /* ===== Input Group with Icon ===== */
    .input-group-cu {
        position: relative;
    }

    .input-group-cu-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        z-index: 4;
        font-size: 15px;
        pointer-events: none;
        transition: color .25s;
    }

    .input-group-cu:focus-within .input-group-cu-icon {
        color: var(--cu-primary);
    }

    .input-cu {
        height: 46px;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 0 16px 0 42px;
        font-size: 14px;
        color: var(--cu-text);
        background: #f8fafc;
        transition: all .25s;
        width: 100%;
        box-shadow: none;
        outline: none;
    }

    .input-cu:focus {
        border-color: var(--cu-primary);
        box-shadow: 0 0 0 3px rgba(22, 163, 74, .1);
        background-color: #fff;
    }

    .input-cu::placeholder {
        color: #94a3b8;
        font-size: 13px;
    }

    /* override Bootstrap */
    .input-group-cu .form-control,
    .input-group-cu .form-select {
        height: 46px;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 0 16px 0 42px;
        font-size: 14px;
        color: var(--cu-text);
        background: #f8fafc;
        transition: all .25s;
        width: 100%;
        box-shadow: none;
    }

    .input-group-cu .form-control:focus,
    .input-group-cu .form-select:focus {
        border-color: var(--cu-primary);
        box-shadow: 0 0 0 3px rgba(22, 163, 74, .1);
        background-color: #fff;
    }

    .input-group-cu .form-control::placeholder {
        color: #94a3b8;
        font-size: 13px;
    }

    .input-group-cu .form-select {
        padding-right: 36px;
        -moz-padding-start: 42px;
        cursor: pointer;
        appearance: auto;
    }

    /* ===== Inline Validation Error ===== */
    .invalid-feedback-cu {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 6px;
        font-size: 13px;
        color: #dc2626;
        font-weight: 500;
    }

    .input-cu.is-invalid,
    .input-group-cu .form-control.is-invalid {
        border-color: #dc2626;
        background-image: none;
    }

    .input-cu.is-invalid:focus,
    .input-group-cu .form-control.is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(220, 38, 38, .1);
    }

    /* ===== Alert ===== */
    .alert-cu {
        border: none;
        border-radius: 12px;
        padding: 14px 20px;
        font-size: 14px;
        margin-bottom: 20px;
    }

    .alert-cu.alert-success {
        background: #f0fdf4;
        color: #16a34a;
        border-left: 4px solid #16a34a;
    }

    .alert-cu.alert-danger {
        background: #fef2f2;
        color: #991b1b;
        border-left: 4px solid #dc2626;
    }

    .alert-cu.alert-danger ul {
        padding-left: 20px;
        margin: 0;
    }

    .alert-cu.alert-danger ul li {
        list-style: disc;
    }

    /* ===== Buttons ===== */
    .btn-cu {
        height: 44px;
        padding: 0 28px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        transition: all .25s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
        border: none;
        gap: 8px;
    }

    .btn-cu-secondary {
        background: #f1f5f9;
        color: #475569;
        border: 1.5px solid #e2e8f0;
    }

    .btn-cu-secondary:hover {
        background: #e2e8f0;
        color: #334155;
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(0, 0, 0, .08);
    }

    .btn-cu-primary {
        background: linear-gradient(135deg, #16a34a, #22c55e);
        color: #fff;
        box-shadow: 0 2px 8px rgba(22, 163, 74, .25);
    }

    .btn-cu-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(22, 163, 74, .35);
        color: #fff;
    }

    .btn-cu:active {
        transform: translateY(0);
    }

    /* ===== Button Layout ===== */
    .form-actions-cu {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-top: 32px;
        padding-top: 20px;
        border-top: 1px solid #f1f5f9;
    }

    /* ===== Responsive ===== */
    @media (max-width: 768px) {
        .create-user-page {
            margin-top: 16px;
            padding: 0 12px;
        }

        .create-card-header {
            padding: 18px 20px 16px;
        }

        .create-card-body {
            padding: 18px 20px 22px;
        }

        .form-actions-cu {
            flex-direction: column;
        }

        .form-actions-cu .btn-cu {
            width: 100%;
        }

        .header-icon-cu {
            width: 42px;
            height: 42px;
            font-size: 18px;
        }
    }

    @media (max-width: 480px) {
        .create-card-header {
            padding: 14px 16px 12px;
        }

        .create-card-body {
            padding: 14px 16px 18px;
        }

        .input-group-cu .form-control,
        .input-group-cu .form-select,
        .input-cu {
            height: 42px;
            font-size: 13px;
        }

        .btn-cu {
            height: 40px;
            padding: 0 20px;
            font-size: 13px;
        }
    }
</style>
@endpush

@section('content')
<div class="create-user-page">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="breadcrumb-cu">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/home"><i class="fas fa-home me-1"></i>Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ url('/master-user') }}">Master User</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Tambah User</li>
        </ol>
    </nav>

    {{-- Card --}}
    <div class="card create-card">
        <div class="create-card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon-cu">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold" style="color: #1e293b; font-size: 18px;">Tambah User Baru</h4>
                    <span style="font-size: 13px; color: #64748b;">Lengkapi data pengguna baru.</span>
                </div>
            </div>
        </div>

        <div class="create-card-body">

            {{-- Success --}}
            @if(session('success'))
                <div class="alert alert-cu alert-success">
                    <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                </div>
            @endif

            {{-- Errors --}}
            @if ($errors->any())
                <div class="alert alert-cu alert-danger">
                    <i class="fas fa-exclamation-triangle me-1"></i> Terdapat kesalahan pada form:
                    <ul class="mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form --}}
            <form action="{{ route('user.store') }}" method="POST">
                @csrf

                {{-- Nama --}}
                <div class="mb-4">
                    <label for="name" class="form-label-cu">Nama Lengkap</label>
                    <div class="input-group-cu">
                        <i class="fas fa-user input-group-cu-icon"></i>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" required value="{{ old('name') }}" placeholder="Masukkan nama lengkap">
                        @error('name')
                        <div class="invalid-feedback-cu">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>

                {{-- NISN --}}
                <div class="mb-4" id="form-nisn">
                    <label for="nisn" class="form-label-cu">NISN</label>
                    <div class="input-group-cu">
                        <i class="fas fa-id-card input-group-cu-icon"></i>
                        <input type="text" name="nisn" class="form-control @error('nisn') is-invalid @enderror" maxlength="10" placeholder="Masukkan NISN (maks 10 angka)">
                        @error('nisn')
                        <div class="invalid-feedback-cu">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>

                {{-- Email --}}
                <div class="mb-4">
                    <label for="email" class="form-label-cu">Alamat Email</label>
                    <div class="input-group-cu">
                        <i class="fas fa-envelope input-group-cu-icon"></i>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" required placeholder="Masukkan email aktif">
                        @error('email')
                        <div class="invalid-feedback-cu">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>

                {{-- Role --}}
                <div class="mb-4">
                    <label for="role" class="form-label-cu">Peran / Role</label>
                    <div class="input-group-cu">
                        <i class="fas fa-user-tag input-group-cu-icon"></i>
                        <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                            <option value="1">Admin</option>
                            <option value="2">Guru</option>
                            <option value="3">Siswa</option>
                        </select>
                        @error('role')
                        <div class="invalid-feedback-cu">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>

                {{-- Actions --}}
                <div class="form-actions-cu">
                    <a href="{{ url('/master-user') }}" class="btn btn-cu btn-cu-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-cu btn-cu-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>

            </form>

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
        toggleNISNField();
    });
</script>
