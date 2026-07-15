@extends('layouts.main')
@section('title', 'Kebijakan 2FA per Role')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">

            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4">

                    <h4 class="fw-bold mb-1" style="color: var(--ms-text);">
                        <i class="fas fa-shield-halved me-2" style="color: var(--ms-primary);"></i>
                        Kebijakan 2FA per Role
                    </h4>
                    <p class="text-muted mb-4" style="font-size: 14px;">
                        Tentukan role mana yang wajib mengaktifkan Autentikasi Dua Faktor. User pada role yang wajib
                        tidak dapat mengakses Dashboard sebelum menyelesaikan setup 2FA.
                    </p>

                    @if (session('success'))
                    <div class="alert alert-success d-flex align-items-center gap-2 py-2">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                    </div>
                    @endif
                    @if (session('error'))
                    <div class="alert alert-danger d-flex align-items-center gap-2 py-2">
                        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Role</th>
                                    <th>Status 2FA</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($policies as $policy)
                                <tr>
                                    <td>
                                        <span class="fw-semibold">{{ $policy->role_label }}</span>
                                    </td>
                                    <td>
                                        @if ($policy->require_2fa)
                                        <span class="badge bg-success-subtle text-success">
                                            <i class="fas fa-check me-1"></i> Wajib
                                        </span>
                                        @else
                                        <span class="badge bg-secondary-subtle text-secondary">
                                            <i class="fas fa-xmark me-1"></i> Tidak Wajib
                                        </span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <form method="POST" action="{{ route('admin.2fa-policy.toggle', $policy->role) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm @if($policy->require_2fa) btn-outline-warning @else btn-outline-success @endif" style="border-radius:8px;">
                                                @if ($policy->require_2fa)
                                                <i class="fas fa-xmark me-1"></i> Nonaktifkan Kewajiban
                                                @else
                                                <i class="fas fa-check me-1"></i> Jadikan Wajib
                                                @endif
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="alert alert-info d-flex align-items-center gap-2 mt-3" style="border-radius:10px;">
                        <i class="fas fa-circle-info text-info"></i>
                        <span style="font-size:13px;">
                            Perubahan berlaku seketika. User role wajib yang belum setup 2FA akan diarahkan ke halaman setup
                            saat mengakses Dashboard — tanpa perlu menulis ulang kode.
                        </span>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection