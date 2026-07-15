@extends('layouts.main')
@section('title', 'Kode Recovery 2FA')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">

            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4">

                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <i class="fas fa-exclamation-triangle fs-1" style="color: #f59e0b;"></i>
                        </div>
                        <h4 class="fw-bold" style="color: var(--ms-text);">
                            Simpan Kode Recovery!
                        </h4>
                        <p class="text-muted" style="font-size: 14px;">
                            Kode berikut hanya ditampilkan <strong>satu kali</strong>.
                            Simpan di tempat aman. Setiap kode hanya bisa digunakan sekali.
                        </p>
                    </div>

                    @if (session()->has('success'))
                    <div class="alert alert-success d-flex align-items-center gap-2 py-2 px-3" style="border-radius: 10px;">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                    </div>
                    @endif

                    <div class="bg-light rounded-3 p-4 mb-4">
                        <div class="row g-2">
                            @foreach ($codes as $code)
                            <div class="col-6">
                                <code class="d-block text-center py-2 px-3 bg-white border rounded-3 fw-bold"
                                    style="font-family: 'Courier New', monospace; font-size: 14px; letter-spacing: 1px; user-select: all;">
                                    {{ $code }}
                                </code>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-success py-2 fw-semibold"
                            style="border-radius: 10px;" onclick="window.print()">
                            <i class="fas fa-print me-1"></i>
                            Cetak Kode
                        </button>
                        <a href="{{ route('home') }}" class="btn btn-success py-2 fw-semibold"
                            style="border-radius: 10px;">
                            <i class="fas fa-check-circle me-1"></i>
                            Saya sudah menyimpannya
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
