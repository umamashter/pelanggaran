@extends('layouts.data')
@section('title', 'Login')

@section('datas')
<style>
    :root {
        --lg-primary: #2563eb;
        --lg-primary-2: #3b82f6;
        --lg-primary-dark: #1d4ed8;
        --lg-primary-soft: rgba(37, 99, 235, .10);
        --lg-ink: #0f172a;
        --lg-ink-2: #475569;
        --lg-ink-3: #94a3b8;
        --lg-line: #e2e8f0;
        --lg-bg: #f8fafc;
        --lg-white: #ffffff;
        --lg-radius: 16px;
        --lg-t: .25s cubic-bezier(.22, .61, .36, 1);
    }

    html, body {
        margin: 0 !important;
        padding: 0 !important;
        min-height: 100%;
    }

    body {
        min-height: 100vh !important;
        overflow-x: hidden !important;
        font-family: 'Poppins', system-ui, sans-serif !important;
        color: var(--lg-ink);
        background: var(--lg-bg) !important;
    }

    .lg-page {
        min-height: 100vh;
        display: flex;
        align-items: stretch;
    }

    /* ============================================================
       KIRI — Foto sekolah (50% layar di PC)
       ============================================================ */
    .lg-visual {
        position: relative;
        flex: 1.1 1 0;
        min-width: 0;
        overflow: hidden;
        isolation: isolate;
    }

    .lg-visual > img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        animation: lgZoom 1.8s cubic-bezier(.16, 1, .3, 1) both;
    }

    .lg-visual-scrim {
        position: absolute;
        inset: 0;
        z-index: 1;
        background:
            linear-gradient(180deg, rgba(2, 6, 23, .62) 0%, rgba(2, 6, 23, .12) 38%, rgba(2, 6, 23, .08) 60%, rgba(2, 6, 23, .82) 100%);
        animation: lgFadeIn .9s ease backwards;
        animation-delay: .1s;
    }

    .lg-visual-scrim::after {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(120% 90% at 50% 115%, rgba(37, 99, 235, .28), transparent 55%);
    }

    .lg-visual-top,
    .lg-visual-bottom {
        position: absolute;
        left: 0;
        right: 0;
        z-index: 2;
        padding: 34px 40px;
    }

    .lg-visual-top {
        top: 0;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
    }

    .lg-brand {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        padding: 10px 16px 10px 10px;
        border-radius: 999px;
        background: rgba(255, 255, 255, .10);
        border: 1px solid rgba(255, 255, 255, .16);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    .lg-brand-logo {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: rgba(255, 255, 255, .92);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .lg-brand-logo img {
        width: 30px;
        height: auto;
        object-fit: contain;
    }

    .lg-brand-meta strong {
        display: block;
        font-size: 13.5px;
        font-weight: 700;
        color: #fff;
        line-height: 1.2;
    }

    .lg-brand-meta span {
        display: block;
        font-size: 10.5px;
        font-weight: 500;
        color: rgba(255, 255, 255, .72);
        margin-top: 2px;
        letter-spacing: .02em;
    }

    .lg-year-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        border-radius: 999px;
        background: rgba(2, 6, 23, .38);
        border: 1px solid rgba(255, 255, 255, .18);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        color: #e2e8f0;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }

    .lg-year-pill i {
        color: #93c5fd;
    }

    .lg-visual-bottom {
        bottom: 0;
        display: grid;
        gap: 10px;
    }

    .lg-visual-bottom h1 {
        margin: 0;
        font-size: clamp(26px, 3.2vw, 42px);
        font-weight: 800;
        line-height: 1.08;
        letter-spacing: -.03em;
        color: #fff;
        max-width: 640px;
    }

    .lg-visual-bottom p {
        margin: 0;
        font-size: 14px;
        line-height: 1.6;
        color: rgba(255, 255, 255, .82);
        max-width: 560px;
    }

    /* ============================================================
       KANAN — Form login
       ============================================================ */
    .lg-form {
        flex: 1 1 0;
        min-width: 0;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
        background:
            radial-gradient(720px 520px at 88% -6%, rgba(37, 99, 235, .10), transparent 60%),
            radial-gradient(640px 460px at -8% 108%, rgba(96, 165, 250, .10), transparent 60%),
            var(--lg-bg);
    }

    .lg-form::before {
        content: '';
        position: absolute;
        inset: 0;
        pointer-events: none;
        background-image:
            linear-gradient(var(--lg-line) 1px, transparent 1px),
            linear-gradient(90deg, var(--lg-line) 1px, transparent 1px);
        background-size: 34px 34px;
        mask-image: radial-gradient(circle at 62% 46%, rgba(0, 0, 0, .35), transparent 78%);
        -webkit-mask-image: radial-gradient(circle at 62% 46%, rgba(0, 0, 0, .35), transparent 78%);
        opacity: .6;
    }

    .lg-card {
        position: relative;
        width: 100%;
        max-width: 360px;
    }

    .lg-card-head {
        text-align: center;
        margin-bottom: 16px;
    }

    .lg-logo {
        width: 84px;
        height: 84px;
        margin: 0 auto 12px;
        border-radius: 22px;
        background: var(--lg-white);
        border: 1px solid var(--lg-line);
        box-shadow: 0 18px 40px -18px rgba(37, 99, 235, .28);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .lg-logo img {
        width: 54px;
        height: auto;
        object-fit: contain;
    }

    .lg-title {
        margin: 0;
        font-size: 23px;
        font-weight: 800;
        letter-spacing: -.03em;
        color: var(--lg-ink);
    }

    .lg-subtitle {
        margin: 4px 0 0;
        font-size: 12.5px;
        line-height: 1.5;
        color: var(--lg-ink-2);
    }

    .lg-field {
        margin-bottom: 12px;
    }

    .lg-field > label {
        display: block;
        margin-bottom: 5px;
        font-size: 12.5px;
        font-weight: 600;
        color: var(--lg-ink);
    }

    .lg-control {
        position: relative;
        display: flex;
        align-items: center;
    }

    .lg-control > i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--lg-ink-3);
        font-size: 15px;
        pointer-events: none;
        transition: color var(--lg-t);
    }

    .lg-control input {
        width: 100%;
        height: 42px;
        padding: 0 46px 0 40px;
        border-radius: 11px;
        border: 1.5px solid var(--lg-line);
        background: var(--lg-white);
        color: var(--lg-ink);
        font-size: 13.5px;
        font-family: inherit;
        outline: none;
        transition: border-color var(--lg-t), box-shadow var(--lg-t), background var(--lg-t);
    }

    .lg-control input::placeholder {
        color: var(--lg-ink-3);
    }

    .lg-control input:hover {
        border-color: #cbd5e1;
    }

    .lg-control input:focus {
        border-color: var(--lg-primary);
        box-shadow: 0 0 0 4px var(--lg-primary-soft);
    }

    .lg-control input:focus ~ i,
    .lg-control:focus-within > i {
        color: var(--lg-primary);
    }

    .lg-control.has-eye input {
        padding-right: 48px;
    }

    .lg-eye {
        position: absolute;
        right: 5px;
        top: 50%;
        transform: translateY(-50%);
        width: 36px;
        height: 36px;
        border: none;
        border-radius: 10px;
        background: transparent;
        color: var(--lg-ink-3);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        cursor: pointer;
        transition: background var(--lg-t), color var(--lg-t);
    }

    .lg-eye:hover,
    .lg-eye:focus-visible {
        background: var(--lg-primary-soft);
        color: var(--lg-primary);
        outline: none;
    }

    .lg-helper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-top: 6px;
        min-height: 18px;
        flex-wrap: wrap;
    }

    .lg-caps {
        display: none;
        align-items: center;
        gap: 7px;
        font-size: 11.5px;
        font-weight: 600;
        color: #c2410c;
    }

    .lg-caps.show {
        display: inline-flex;
    }

    .lg-forgot {
        margin-left: auto;
        color: var(--lg-primary);
        text-decoration: none;
        font-size: 12.5px;
        font-weight: 600;
        transition: color var(--lg-t);
    }

    .lg-forgot:hover {
        color: var(--lg-primary-dark);
        text-decoration: underline;
    }

    .lg-options {
        display: flex;
        align-items: center;
        margin: 0 0 12px;
    }

    .lg-remember {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 500;
        color: var(--lg-ink-2);
        user-select: none;
    }

    .lg-remember input[type="checkbox"] {
        width: 17px;
        height: 17px;
        accent-color: var(--lg-primary);
        cursor: pointer;
        margin: 0;
    }

    .lg-alert {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 14px;
        padding: 12px 14px;
        border-radius: 12px;
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #b91c1c;
        font-size: 13px;
        font-weight: 500;
        animation: lgFadeUp .35s cubic-bezier(.16, 1, .3, 1) backwards, lgShake .4s ease both;
        animation-delay: .56s, .64s;
    }

    .lg-alert i {
        flex-shrink: 0;
        margin-top: 1px;
    }

    .lg-submit {
        position: relative;
        width: 100%;
        height: 42px;
        border: none;
        border-radius: 11px;
        background: linear-gradient(135deg, var(--lg-primary), var(--lg-primary-2));
        color: #fff;
        font-size: 14px;
        font-weight: 700;
        font-family: inherit;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        cursor: pointer;
        overflow: hidden;
        box-shadow: 0 18px 34px -16px rgba(37, 99, 235, .55);
        transition: transform var(--lg-t), box-shadow var(--lg-t), filter var(--lg-t);
    }

    .lg-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 24px 44px -18px rgba(37, 99, 235, .6);
        filter: saturate(1.05);
    }

    .lg-submit:active {
        transform: translateY(0);
    }

    .lg-submit:focus-visible {
        outline: none;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, .18), 0 18px 34px -16px rgba(37, 99, 235, .55);
    }

    .lg-submit:disabled {
        cursor: wait;
        transform: none;
        opacity: .88;
    }

    .lg-submit .spinner-border {
        width: 17px;
        height: 17px;
        border-width: 2px;
    }

    .lg-progress {
        position: absolute;
        left: 0;
        bottom: 0;
        height: 3px;
        width: 0;
        background: linear-gradient(90deg, rgba(255,255,255,.15), rgba(255,255,255,.9), rgba(255,255,255,.15));
        opacity: 0;
        transition: opacity var(--lg-t);
    }

    .lg-submit.is-loading .lg-progress {
        width: 100%;
        opacity: 1;
        animation: lgProgress 1.2s linear infinite;
    }

    .lg-ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, .32);
        transform: scale(0);
        animation: lgRipple .6s ease-out;
        pointer-events: none;
    }

    .lg-foot {
        margin-top: 14px;
        text-align: center;
    }

    .lg-foot > a {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--lg-ink-2);
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: color var(--lg-t);
    }

    .lg-foot > a:hover {
        color: var(--lg-primary);
    }

    .lg-foot p {
        margin: 8px 0 0;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        gap: 8px;
        font-size: 11px;
        color: var(--lg-ink-3);
    }

    .lg-dot {
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background: #cbd5e1;
    }

    /* Autofill */
    .lg-control input:-webkit-autofill,
    .lg-control input:-webkit-autofill:hover,
    .lg-control input:-webkit-autofill:focus {
        -webkit-text-fill-color: var(--lg-ink);
        -webkit-box-shadow: 0 0 0 1000px #fff inset;
        transition: background-color 99999s ease-in-out 0s;
        caret-color: var(--lg-ink);
    }

    /* ============================================================
       ANIMASI
       ============================================================ */
    @keyframes lgCardIn {
        from { opacity: 0; transform: translateY(18px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes lgFadeUp {
        from { opacity: 0; transform: translateY(14px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes lgFadeDown {
        from { opacity: 0; transform: translateY(-14px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes lgFadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes lgPop {
        from { opacity: 0; transform: scale(.6); }
        to { opacity: 1; transform: scale(1); }
    }

    @keyframes lgPopIcon {
        from { opacity: 0; transform: translateY(-50%) scale(.6); }
        to { opacity: 1; transform: translateY(-50%) scale(1); }
    }

    @keyframes lgZoom {
        from { transform: scale(1.08); }
        to { transform: scale(1); }
    }

    @keyframes lgGridIn {
        from { opacity: 0; }
        to { opacity: .6; }
    }

    @keyframes lgShake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-4px); }
        75% { transform: translateX(4px); }
    }

    @keyframes lgRipple {
        to { transform: scale(4); opacity: 0; }
    }

    @keyframes lgProgress {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    /* ============================================================
       ENTRANCE ANIMATIONS — animasi berurutan untuk tiap elemen
       (fill `backwards`: selama delay tampil frame awal, setelah
       selesai kembali ke state natural agar hover tetap berfungsi)
       ============================================================ */
    .lg-form::before          { animation: lgGridIn .9s ease backwards; animation-delay: .15s; }

    .lg-brand                 { animation: lgFadeDown .6s cubic-bezier(.16, 1, .3, 1) backwards; animation-delay: .05s; }
    .lg-year-pill             { animation: lgFadeIn .5s ease backwards; animation-delay: .18s; }
    .lg-visual-bottom h1      { animation: lgFadeUp .6s cubic-bezier(.16, 1, .3, 1) backwards; animation-delay: .24s; }
    .lg-visual-bottom p       { animation: lgFadeIn .6s ease backwards; animation-delay: .34s; }

    .lg-logo                  { animation: lgPop .55s cubic-bezier(.34, 1.56, .64, 1) backwards; animation-delay: .06s; }
    .lg-title                 { animation: lgFadeUp .5s cubic-bezier(.16, 1, .3, 1) backwards; animation-delay: .14s; }
    .lg-subtitle              { animation: lgFadeIn .5s ease backwards; animation-delay: .21s; }

    .lg-field:nth-of-type(1)                { animation: lgFadeUp .5s cubic-bezier(.16, 1, .3, 1) backwards; animation-delay: .29s; }
    .lg-field:nth-of-type(1) .lg-control > i { animation: lgPopIcon .4s cubic-bezier(.34, 1.56, .64, 1) backwards; animation-delay: .35s; }
    .lg-field:nth-of-type(2)                { animation: lgFadeUp .5s cubic-bezier(.16, 1, .3, 1) backwards; animation-delay: .38s; }
    .lg-field:nth-of-type(2) .lg-control > i { animation: lgPopIcon .4s cubic-bezier(.34, 1.56, .64, 1) backwards; animation-delay: .44s; }
    .lg-eye                   { animation: lgFadeIn .4s ease backwards; animation-delay: .47s; }
    .lg-helper                { animation: lgFadeIn .45s ease backwards; animation-delay: .52s; }

    .lg-options               { animation: lgFadeIn .5s ease backwards; animation-delay: .56s; }
    .lg-submit                { animation: lgFadeUp .5s cubic-bezier(.16, 1, .3, 1) backwards; animation-delay: .66s; }
    .lg-foot                  { animation: lgFadeIn .5s ease backwards; animation-delay: .76s; }
    .lg-foot p                { animation: lgFadeIn .5s ease backwards; animation-delay: .84s; }

    /* ============================================================
       DARK MODE
       ============================================================ */
    html.dark-mode body {
        background: #0b1220 !important;
    }

    html.dark-mode .lg-form {
        background:
            radial-gradient(720px 520px at 88% -6%, rgba(37, 99, 235, .16), transparent 60%),
            radial-gradient(640px 460px at -8% 108%, rgba(59, 130, 246, .12), transparent 60%),
            #0b1220;
    }

    html.dark-mode .lg-form::before {
        --lg-line: rgba(148, 163, 184, .10);
    }

    html.dark-mode .lg-control input {
        background: rgba(255, 255, 255, .05);
        border-color: rgba(255, 255, 255, .10);
        color: #f8fafc;
    }

    html.dark-mode .lg-control input:hover {
        border-color: rgba(255, 255, 255, .18);
    }

    html.dark-mode .lg-control input:focus {
        border-color: rgba(59, 130, 246, .5);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, .16);
    }

    html.dark-mode .lg-control input:-webkit-autofill,
    html.dark-mode .lg-control input:-webkit-autofill:hover,
    html.dark-mode .lg-control input:-webkit-autofill:focus {
        -webkit-text-fill-color: #f8fafc;
        -webkit-box-shadow: 0 0 0 1000px rgba(15, 23, 42, .94) inset;
    }

    html.dark-mode .lg-logo {
        background: rgba(255, 255, 255, .05);
        border-color: rgba(255, 255, 255, .10);
    }

    html.dark-mode .lg-title { color: #f8fafc; }
    html.dark-mode .lg-subtitle,
    html.dark-mode .lg-remember,
    html.dark-mode .lg-foot > a,
    html.dark-mode .lg-field > label { color: #94a3b8; }
    html.dark-mode .lg-foot p { color: #64748b; }
    html.dark-mode .lg-remember { color: #cbd5e1; }
    html.dark-mode .lg-dot { background: rgba(255, 255, 255, .18); }
    html.dark-mode .lg-control > i { color: #64748b; }
    html.dark-mode .lg-eye { color: #64748b; }

    html.dark-mode .lg-alert {
        background: rgba(127, 29, 29, .30);
        border-color: rgba(239, 68, 68, .24);
        color: #fecaca;
    }

    /* ============================================================
       RESPONSIVE
       ============================================================ */
    @media (max-width: 992px) {
        .lg-page {
            flex-direction: column;
        }

        .lg-visual {
            flex: none;
            height: 260px;
        }

        .lg-visual-top {
            padding: 20px 22px;
        }

        .lg-visual-bottom {
            padding: 18px 22px;
        }

        .lg-visual-bottom h1 {
            font-size: 24px;
        }

        .lg-visual-bottom p {
            display: none;
        }

        .lg-form {
            padding: 34px 22px 44px;
        }
    }

    @media (max-width: 480px) {
        .lg-visual {
            height: 200px;
        }

        .lg-year-pill {
            display: none;
        }

        .lg-form {
            padding: 28px 18px 40px;
        }

        .lg-title {
            font-size: 23px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after {
            animation: none !important;
            transition: none !important;
            scroll-behavior: auto !important;
        }
    }
</style>

@php
    $namaMadrasah = $profil ? $profil->nama : 'MIS Nurul Ulum';
    $tahunAjaranAktif = isset($tahun_ajaran) && $tahun_ajaran ? $tahun_ajaran->tahun_ajaran : ((isset($tahunAktif) && $tahunAktif) ? $tahunAktif->tahun_ajaran : date('Y') . '/' . (date('Y') + 1));
@endphp

<div class="lg-page">

    {{-- KIRI: foto sekolah --}}
    <section class="lg-visual" aria-label="Gambar gedung madrasah">
        <img src="{{ asset('img/bg1.jpg.jpeg') }}" alt="Gedung {{ $namaMadrasah }}">
        <div class="lg-visual-scrim"></div>

        <div class="lg-visual-top">
            <div class="lg-brand">
                <span class="lg-brand-logo"><img src="{{ asset('img/logo2.png') }}" alt="Logo {{ $namaMadrasah }}"></span>
                <span class="lg-brand-meta">
                    <strong>{{ $namaMadrasah }}</strong>
                    <span>Sistem Informasi Madrasah</span>
                </span>
            </div>
            <span class="lg-year-pill"><i class="bi bi-mortarboard-fill"></i> TA {{ $tahunAjaranAktif }}</span>
        </div>

        <div class="lg-visual-bottom">
            <h1>{{ $namaMadrasah }}</h1>
            <p>Selamat datang di layanan digital madrasah. Masuk untuk mengelola akademik, absensi, dan administrasi dengan cepat dan aman.</p>
        </div>
    </section>

    {{-- KANAN: form login --}}
    <section class="lg-form" aria-label="Form login">
        <div class="lg-card">
            <div class="lg-card-head">
                <span class="lg-logo"><img src="{{ asset('img/logo2.png') }}" alt="Logo {{ $namaMadrasah }}"></span>
                <h2 class="lg-title">Masuk</h2>
                <p class="lg-subtitle">Silakan masuk dengan akun Anda untuk mengakses dashboard.</p>
            </div>

            <form method="POST" action="{{ route('login') }}" id="loginForm" novalidate>
                @csrf

                <div class="lg-field">
                    <label for="username">Username / Email</label>
                    <div class="lg-control">
                        <input type="text" id="username" name="username" value="{{ old('username') }}"
                               placeholder="Masukkan username atau email" required autocomplete="username"
                               aria-label="Username atau Email">
                        <i class="bi bi-person"></i>
                    </div>
                </div>

                <div class="lg-field">
                    <label for="password">Password</label>
                    <div class="lg-control has-eye">
                        <input type="password" id="password" name="password"
                               placeholder="Masukkan password" required aria-label="Password">
                        <i class="bi bi-lock"></i>
                        <button type="button" class="lg-eye" id="togglePasswordBtn" aria-label="Tampilkan atau sembunyikan password">
                            <i class="bi bi-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    <div class="lg-helper">
                        <span class="lg-caps" id="capsLockHint"><i class="bi bi-exclamation-triangle"></i> Caps Lock aktif</span>
                        <a href="#" class="lg-forgot" onclick="event.preventDefault();alert('Fitur lupa password belum tersedia. Silakan hubungi admin.')">Lupa password?</a>
                    </div>
                </div>

                <div class="lg-options">
                    <label class="lg-remember">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span>Ingat saya</span>
                    </label>
                </div>

                @if (session()->has('error'))
                <div class="lg-alert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span>{{ session('error') }}</span>
                </div>
                @endif

                <button type="submit" class="lg-submit" id="loginBtn">
                    <i class="bi bi-box-arrow-in-right" id="loginBtnIcon"></i>
                    <span id="loginBtnText">Masuk</span>
                    <span class="lg-progress" aria-hidden="true"></span>
                </button>
            </form>

            <div class="lg-foot">
                <a href="/"><i class="bi bi-arrow-left"></i> Kembali ke beranda</a>
                <p>
                    <span>&copy; {{ date('Y') }} {{ $namaMadrasah }}</span>
                    <span class="lg-dot"></span>
                    <span>v1.0.0</span>
                </p>
            </div>
        </div>
    </section>
</div>

<script>
    document.getElementById('togglePasswordBtn')?.addEventListener('click', function () {
        const input = document.getElementById('password');
        const icon = document.getElementById('toggleIcon');
        if (!input || !icon) return;
        const show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        icon.className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
        input.focus();
    });

    document.getElementById('password')?.addEventListener('keyup', function (e) {
        const hint = document.getElementById('capsLockHint');
        if (hint) hint.classList.toggle('show', e.getModifierState('CapsLock'));
    });

    document.getElementById('loginBtn')?.addEventListener('click', function (e) {
        const rect = this.getBoundingClientRect();
        const ripple = document.createElement('span');
        ripple.className = 'lg-ripple';
        const size = Math.max(rect.width, rect.height);
        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = (e.clientX - rect.left - size / 2) + 'px';
        ripple.style.top = (e.clientY - rect.top - size / 2) + 'px';
        this.appendChild(ripple);
        setTimeout(function () { ripple.remove(); }, 600);
    });

    document.getElementById('loginForm')?.addEventListener('submit', function () {
        const btn = document.getElementById('loginBtn');
        const icon = document.getElementById('loginBtnIcon');
        const text = document.getElementById('loginBtnText');
        if (!btn) return;
        btn.disabled = true;
        btn.classList.add('is-loading');
        if (icon) icon.className = 'spinner-border spinner-border-sm';
        if (text) text.textContent = 'Memverifikasi...';
    });
</script>
@endsection
