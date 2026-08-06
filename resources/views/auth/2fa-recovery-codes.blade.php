@extends('layouts.main')
@section('title', 'Kode Recovery 2FA')

@push('css')
<style>
    .page-title-content { display:none !important; }
    .sec-wrap { max-width: 960px; margin: 0 auto; }
    .sec-card { background:var(--jd-card); border:1px solid var(--jd-border); border-radius:20px; box-shadow:var(--jd-shadow); overflow:hidden; }
    .sec-body { padding:24px; }
    .code-grid { display:grid; grid-template-columns:repeat(2, 1fr); gap:12px; }
    .code-item { padding:14px; border-radius:16px; background:var(--jd-bg); border:1px solid var(--jd-border); text-align:center; }
    .code-item code { font-size:15px; font-weight:800; letter-spacing:1px; color:var(--jd-text); user-select:all; }
    @media (max-width: 767.98px) { .code-grid { grid-template-columns:1fr; } }
</style>
@endpush

@section('content')
@include('component.admin.jadwal-module')
<div class="jd-mod">
    <div class="sec-wrap">
        <div class="jd-hero mb-4">
            <div class="jd-hero-grid">
                <div class="jd-hero-left">
                    <span class="jd-hero-icon"><i class="bi bi-key-fill"></i></span>
                    <div>
                        <h1 class="jd-hero-title">Recovery Codes Vault</h1>
                        <p class="jd-hero-sub">Simpan kode cadangan ini dengan aman. Kode hanya ditampilkan satu kali.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="sec-card">
            <div class="sec-body">
                @if (session()->has('success'))<div class="jd-alert jd-alert--ok"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>@endif
                <div class="jd-alert jd-alert--warn"><i class="bi bi-exclamation-triangle-fill"></i> Setiap recovery code hanya dapat digunakan satu kali. Simpan, cetak, atau copy sekarang.</div>
                <div class="code-grid mb-4">
                    @foreach ($codes as $code)
                    <div class="code-item"><code>{{ $code }}</code></div>
                    @endforeach
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <button type="button" class="jd-btn jd-btn--outline" onclick="window.print()"><i class="bi bi-printer"></i> Print</button>
                    <button type="button" class="jd-btn jd-btn--soft" onclick="navigator.clipboard.writeText(@json(implode("\n", $codes)))"><i class="bi bi-clipboard"></i> Copy</button>
                    <a href="{{ route('home') }}" class="jd-btn jd-btn--success"><i class="bi bi-check2-circle"></i> Saya sudah menyimpannya</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
