@extends('layouts.data')
@section('title', 'Login')

@section('datas')

<style>
    :root {
        --primary: #16a34a;
        --primary-dark: #15803d;
        --primary-light: #22c55e;
        --radius: 16px;
        --radius-sm: 10px;
        --transition: .3s cubic-bezier(.4, 0, .2, 1);
    }

    body {
        overflow-x: hidden !important;
        min-height: 100vh !important;
        font-family: 'Poppins', sans-serif !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    /* ===== LIGHT THEME (default) ===== */
    body {
        background: #f0fdf4 !important;
    }

    .login-page {
        display: flex;
        min-height: 100vh;
        position: relative;
        font-family: 'Poppins', sans-serif;
    }

    .login-left {
        flex: 1;
        display: flex;
        flex-direction: column;
        position: relative;
        overflow: hidden;
        background: url("{{ asset('img/bg1.jpg.jpeg') }}") center / cover no-repeat;
        padding: 0;
    }



    .login-left .hero-section {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 60px 40px 40px;
        position: relative;
        z-index: 1;
    }



    .login-left .hero-school-name {
        font-size: 28px;
        font-weight: 800;
        color: #fff;
        text-shadow: 0 2px 12px rgba(0, 0, 0, .5);
        letter-spacing: -.5px;
        margin-bottom: 8px;
        animation: fadeInDown .8s ease .3s both;
    }

    .login-left .hero-decoration {
        position: absolute;
        width: 400px;
        height: 400px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(22, 163, 74, .08) 0%, transparent 70%);
        top: -100px;
        right: -100px;
        pointer-events: none;
        animation: floatShape 8s ease-in-out infinite;
    }

    .login-left .hero-decoration-2 {
        position: absolute;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(34, 197, 94, .05) 0%, transparent 70%);
        bottom: -80px;
        left: -80px;
        pointer-events: none;
        animation: floatShape 10s ease-in-out infinite reverse;
    }

    .login-left .hero-decoration-3 {
        position: absolute;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(22, 163, 74, .06) 0%, transparent 70%);
        top: 40%;
        left: 10%;
        pointer-events: none;
        animation: floatShape 12s ease-in-out infinite 2s;
    }

    @keyframes floatShape {

        0%,
        100% {
            transform: translate(0, 0) scale(1);
        }

        50% {
            transform: translate(30px, -30px) scale(1.05);
        }
    }

    .login-left .pattern-overlay {
        position: absolute;
        inset: 0;
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2316a34a' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        pointer-events: none;
        z-index: 0;
    }

    /* ===== RIGHT PANEL (Light) ===== */
    .login-right {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 40px;
        background: #fff;
        position: relative;
    }

    .login-right .login-container {
        width: 100%;
        max-width: 300px;
        animation: fadeInUp .8s ease .3s both;
    }

    .login-right .brand-section {
        text-align: center;
        margin-bottom: 32px;
    }

    .login-right .brand-section img {
        width: 110px;
        height: auto;
        margin-bottom: 14px;
    }

    .login-right .brand-section .app-name {
        font-size: 22px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -.3px;
        margin-bottom: 4px;
    }

    .login-right .brand-section .app-subtitle {
        font-size: 13px;
        color: #64748b;
        font-weight: 400;
        margin-bottom: 0;
    }

    .login-right .form-label {
        font-size: 13px;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 6px;
    }

    .login-right .input-group-custom {
        position: relative;
        margin-bottom: 14px;
    }

    .login-right .input-group-custom .input-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 16px;
        pointer-events: none;
        z-index: 2;
        transition: color .2s;
    }

    .login-right .input-group-custom .form-control {
        width: 100%;
        height: 42px !important;
        background: #f8fafc !important;
        border: 1.5px solid #e2e8f0 !important;
        border-radius: var(--radius-sm) !important;
        padding: 0 42px !important;
        color: #0f172a !important;
        font-size: 14px !important;
        font-family: 'Poppins', sans-serif !important;
        transition: all .25s;
        outline: none;
        box-shadow: none !important;
    }

    .login-right .input-group-custom .form-control::placeholder {
        color: #cbd5e1 !important;
    }

    .login-right .input-group-custom .form-control:focus {
        border-color: var(--primary) !important;
        box-shadow: 0 0 0 3px rgba(22, 163, 74, .1) !important;
        background: #fff !important;
    }

    .login-right .input-group-custom .form-control:focus~.input-icon {
        color: var(--primary);
    }

    .login-right .input-group-custom .password-toggle {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #94a3b8;
        font-size: 16px;
        z-index: 3;
        transition: color .2s;
        background: none;
        border: none;
        padding: 0;
        line-height: 1;
    }

    .login-right .input-group-custom .password-toggle:hover {
        color: var(--primary);
    }

    .login-right .form-options {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 22px;
    }

    .login-right .form-options .remember-label {
        font-size: 13px;
        color: #64748b;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        user-select: none;
    }

    .login-right .form-options .remember-label input[type="checkbox"] {
        width: 16px;
        height: 16px;
        accent-color: var(--primary);
        cursor: pointer;
    }

    .login-right .form-options .forgot-link {
        font-size: 13px;
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
        transition: color .2s;
    }

    .login-right .form-options .forgot-link:hover {
        color: #0f172a;
        text-decoration: underline;
    }

    .btn-login-custom {
        width: 100%;
        height: 42px;
        border: none !important;
        border-radius: var(--radius-sm) !important;
        background: linear-gradient(135deg, var(--primary), var(--primary-light)) !important;
        color: #fff !important;
        font-size: 15px;
        font-weight: 700;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: all var(--transition);
        box-shadow: 0 4px 20px rgba(22, 163, 74, .35) !important;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        letter-spacing: .3px;
        padding: 0 24px;
        position: relative;
        overflow: hidden;
    }

    .btn-login-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(22, 163, 74, .45) !important;
        background: linear-gradient(135deg, var(--primary-dark), var(--primary)) !important;
        color: #fff !important;
    }

    .btn-login-custom:active {
        transform: translateY(0);
    }

    .ripple-effect {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, .35);
        transform: scale(0);
        animation: rippleAnim .6s ease-out;
        pointer-events: none;
    }

    @keyframes rippleAnim {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }

    .alert-custom {
        background: #fef2f2 !important;
        border: 1px solid #fecaca !important;
        border-radius: var(--radius-sm) !important;
        padding: 12px 16px !important;
        font-size: 13px !important;
        color: #dc2626 !important;
        margin-bottom: 18px !important;
        display: flex !important;
        align-items: center;
        gap: 10px;
    }

    .login-right .login-footer {
        text-align: center;
        margin-top: 32px;
        padding-top: 20px;
        border-top: 1px solid #e2e8f0;
    }

    .login-right .login-footer a {
        color: #64748b !important;
        font-size: 13px;
        text-decoration: none;
        transition: color .2s;
        display: inline-flex;
        align-items: center;
    }

    .login-right .login-footer a:hover {
        color: var(--primary) !important;
    }

    .login-right .login-footer .footer-info {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
        margin-top: 8px;
        font-size: 10px;
        color: #94a3b8;
    }

    .login-right .login-footer .footer-info span {
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .login-right .login-footer .footer-info .divider-dot {
        width: 3px;
        height: 3px;
        border-radius: 50%;
        background: #d1d5db;
    }

    .login-right .login-footer .footer-info a {
        font-size: 10px;
    }

    .login-right .input-group-custom .form-control:-webkit-autofill,
    .login-right .input-group-custom .form-control:-webkit-autofill:hover,
    .login-right .input-group-custom .form-control:-webkit-autofill:focus {
        -webkit-text-fill-color: #0f172a !important;
        -webkit-box-shadow: 0 0 0 1000px #fff inset !important;
        transition: background-color 99999s ease-in-out 0s;
        caret-color: #0f172a;
    }

    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ===== DARK THEME (via html.dark-mode from existing system) ===== */
    html.dark-mode body {
        background: #0f172a !important;
    }

    html.dark-mode .login-left .hero-school-name {
        color: #f1f5f9;
    }


    html.dark-mode .login-left .pattern-overlay {
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2322c55e' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    html.dark-mode .login-right {
        background: linear-gradient(135deg, #0f172a 0%, #1a2e35 50%, #1e293b 100%);
    }

    html.dark-mode .login-right .brand-section .app-name {
        color: #f1f5f9;
    }

    html.dark-mode .login-right .brand-section .app-subtitle {
        color: #94a3b8;
    }

    html.dark-mode .login-right .form-label {
        color: #e2e8f0;
    }

    html.dark-mode .login-right .input-group-custom .form-control {
        background: rgba(255, 255, 255, .06) !important;
        border: 1.5px solid rgba(255, 255, 255, .12) !important;
        color: #f1f5f9 !important;
    }

    html.dark-mode .login-right .input-group-custom .form-control:hover {
        border-color: var(--primary) !important;
        box-shadow: 0 0 0 3px rgba(22, 163, 74, .2) !important;
        background: rgba(255, 255, 255, .08) !important;
    }

    html.dark-mode .login-right .input-group-custom .form-control:hover~.input-icon {
        color: #22c55e;
    }

    html.dark-mode .login-right .input-group-custom .form-control::placeholder {
        color: rgba(255, 255, 255, .3) !important;
    }

    html.dark-mode .login-right .input-group-custom .form-control:focus {
        border-color: var(--primary) !important;
        box-shadow: 0 0 0 3px rgba(22, 163, 74, .3) !important;
        background: rgba(255, 255, 255, .08) !important;
    }

    html.dark-mode .login-right .input-group-custom .form-control:focus~.input-icon {
        color: #22c55e;
    }

    html.dark-mode .login-right .input-group-custom .input-icon {
        color: rgba(255, 255, 255, .35);
    }

    html.dark-mode .login-right .input-group-custom .password-toggle {
        color: rgba(255, 255, 255, .35);
    }

    html.dark-mode .login-right .input-group-custom .password-toggle:hover {
        color: #22c55e;
    }

    html.dark-mode .login-right .form-options .remember-label {
        color: #94a3b8;
    }

    html.dark-mode .login-right .form-options .forgot-link {
        color: #22c55e;
    }

    html.dark-mode .login-right .form-options .forgot-link:hover {
        color: #f1f5f9;
    }

    html.dark-mode .login-right .login-footer {
        border-top-color: rgba(255, 255, 255, .08);
    }

    html.dark-mode .login-right .login-footer a {
        color: #94a3b8 !important;
    }

    html.dark-mode .login-right .login-footer a:hover {
        color: #f1f5f9 !important;
    }

    html.dark-mode .login-right .login-footer .footer-info {
        color: #64748b;
    }

    html.dark-mode .login-right .login-footer .footer-info a {
        color: #94a3b8 !important;
    }

    html.dark-mode .login-right .login-footer .footer-info .divider-dot {
        background: rgba(255, 255, 255, .2);
    }

    html.dark-mode .alert-custom {
        background: rgba(239, 68, 68, .12) !important;
        border-color: rgba(239, 68, 68, .2) !important;
        color: #fca5a5 !important;
    }

    html.dark-mode .login-right .input-group-custom .form-control:-webkit-autofill,
    html.dark-mode .login-right .input-group-custom .form-control:-webkit-autofill:hover,
    html.dark-mode .login-right .input-group-custom .form-control:-webkit-autofill:focus {
        -webkit-text-fill-color: #fff !important;
        -webkit-box-shadow: 0 0 0 1000px rgba(30, 41, 59, .9) inset !important;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 992px) {
        .login-right {
            padding: 32px;
        }
    }

    @media (max-width: 768px) {
        .login-page {
            flex-direction: column;
        }

        .login-left {
            display: none;
        }

        .login-right {
            padding: 24px 20px;
        }

        .login-right .brand-section {
            margin-bottom: 24px;
        }

        .login-right .brand-section img {
            width: 80px;
        }

        .login-right .brand-section .app-name {
            font-size: 18px;
        }

        .login-right .input-group-custom .form-control {
            height: 40px !important;
            font-size: 13px !important;
        }

        .btn-login-custom {
            height: 40px;
            font-size: 14px;
        }
    }

    @media (max-width: 480px) {
        .login-right {
            padding: 20px 16px;
        }

        .login-right .login-footer .footer-info {
            font-size: 9px;
        }
    }
</style>

<div class="login-page">

    {{-- ===== LEFT PANEL ===== --}}
    <div class="login-left">


        <div class="hero-section">
            <h1 class="hero-school-name">{{ $profil ? $profil->nama : 'MIS Nurul Ulum' }}</h1>
        </div>

    </div>

    {{-- ===== RIGHT PANEL ===== --}}
    <div class="login-right">
        <div class="login-container">

            <div class="brand-section">
                <img src="{{ asset('img/logo2.png') }}" alt="MIS Nurul Ulum">
                <h1 class="app-name">MIS Nurul Ulum</h1>
                <p class="app-subtitle">Sistem Informasi Akademik</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="input-group-custom">
                    <label class="form-label">Username atau Email</label>
                    <div style="position: relative;">
                        <input type="text" name="username" value="{{ old('username') }}"
                            class="form-control" placeholder="Masukkan username atau email"
                            required autocomplete="username">
                        <i class="fas fa-user input-icon"></i>
                    </div>
                </div>

                <div class="input-group-custom">
                    <label class="form-label">Kata Sandi</label>
                    <div style="position: relative;">
                        <input type="password" name="password" id="password"
                            class="form-control" placeholder="Masukkan password"
                            required>
                        <i class="fas fa-lock input-icon"></i>
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="form-options">
                    <label class="remember-label">
                        <input type="checkbox" name="remember" id="remember">
                        Ingat Saya
                    </label>
                    <a href="#" class="forgot-link" onclick="event.preventDefault();alert('Fitur lupa password belum tersedia. Silakan hubungi admin.')">Lupa Password?</a>
                </div>

                @if (session()->has('error'))
                <div class="alert-custom">
                    <i class="fas fa-exclamation-circle" style="flex-shrink:0;"></i>
                    <span>{{ session('error') }}</span>
                </div>
                @endif

                <button type="submit" class="btn-login-custom" id="loginBtn">
                    <i class="fas fa-sign-in-alt"></i>
                    Masuk
                </button>

            </form>

            <div class="login-footer">
                <a href="/">
                    <i class="fas fa-arrow-left"></i>
                    Kembali ke Beranda
                </a>
                <div class="footer-info">
                    <span>&copy; {{ date('Y') }} MIS Nurul Ulum</span>
                    <span class="divider-dot"></span>
                    <span>v1.0.0</span>
                    <span class="divider-dot"></span>
                    <span>Developed by <a href="#">Nurul Ulum Team</a></span>
                </div>
            </div>

        </div>
    </div>

</div>

<script>
    function togglePassword() {
        const input = document.getElementById('password');
        const icon = document.getElementById('toggleIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    document.getElementById('loginBtn')?.addEventListener('click', function(e) {
        const rect = this.getBoundingClientRect();
        const ripple = document.createElement('span');
        ripple.className = 'ripple-effect';
        const size = Math.max(rect.width, rect.height);
        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = (e.clientX - rect.left - size / 2) + 'px';
        ripple.style.top = (e.clientY - rect.top - size / 2) + 'px';
        this.appendChild(ripple);
        setTimeout(() => ripple.remove(), 600);
    });
</script>

@endsection