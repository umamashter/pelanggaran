@extends('layouts.data')
@section('title', 'Verifikasi Keamanan · Zero Trust')

@section('datas')

<style>
    /* ============================================================
       COLOR PALETTE
       ============================================================ */
    :root {
        --bg-1: #f8fafc;
        --bg-2: #e6f7ee;
        --bg-3: #d4f3e2;
        --cyan: #1aa86f;
        --cyan-2: #16a066;
        --blue: #138F5B;
        --indigo: #0a4f47;
        --text-primary: #0f172a;
        --text-secondary: rgba(71, 85, 105, 0.85);
        --border-subtle: rgba(148, 163, 184, 0.08);
        --radius-lg: 24px;
        --radius-md: 14px;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        background:
            radial-gradient(ellipse at 18% 8%, var(--bg-2) 0%, transparent 55%),
            radial-gradient(ellipse at 82% 92%, var(--bg-3) 0%, transparent 55%),
            var(--bg-1) !important;
        font-family: 'Inter', system-ui, -apple-system, sans-serif !important;
        overflow: hidden !important;
        min-height: 100vh !important;
    }

    /* ============================================================
       PAGE WRAPPER
       ============================================================ */
    .page-wrap {
        position: relative;
        z-index: 2;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
    }

    /* ============================================================
       LAYER 1 — ENCRYPTION GRID
       ============================================================ */
    .bg-grid {
        position: fixed;
        inset: 0;
        z-index: 0;
        pointer-events: none;
        background-image:
            linear-gradient(rgba(19, 143, 91, 0.08) 1px, transparent 1px),
            linear-gradient(90deg, rgba(19, 143, 91, 0.08) 1px, transparent 1px);
        background-size: 60px 60px;
        animation: gridMove 40s linear infinite;
    }
    @keyframes gridMove {
        0% { background-position: 0 0; }
        100% { background-position: 60px 60px; }
    }

    /* ============================================================
       LAYER 1b — VIGNETTE + DEPTH FOG (focus center, darken edges)
       ============================================================ */
    .vignette {
        position: fixed;
        inset: 0;
        z-index: 0;
        pointer-events: none;
        background:
            radial-gradient(ellipse at center, rgba(19, 143, 91, 0.07) 0%, transparent 55%),
            radial-gradient(ellipse at center, rgba(19, 143, 91, 0.04) 0%, transparent 70%);
    }

    /* ============================================================
       LAYER 1c — AMBIENT HALO (pooled projection glow behind card)
       ============================================================ */
    .ambient-halo {
        position: fixed;
        top: 50%; left: 50%;
        width: 720px; height: 720px;
        transform: translate(-50%, -50%);
        z-index: 0;
        pointer-events: none;
        background: radial-gradient(circle, rgba(19, 143, 91, 0.1) 0%, rgba(19, 143, 91, 0.05) 35%, transparent 65%);
        animation: haloBreathe 8s ease-in-out infinite;
    }
    @keyframes haloBreathe {
        0%, 100% { opacity: 0.55; transform: translate(-50%, -50%) scale(1); }
        50%      { opacity: 1;    transform: translate(-50%, -50%) scale(1.08); }
    }

    /* ============================================================
       LAYER 2 — DIGITAL PARTICLES (far depth)
       ============================================================ */
    .particles {
        position: fixed;
        inset: 0;
        z-index: 0;
        pointer-events: none;
    }
    .particle {
        position: absolute;
        border-radius: 50%;
        background: var(--blue);
        box-shadow: 0 0 6px rgba(19, 143, 91, 0.6);
        opacity: 0.35;
        animation: particleFloat 12s ease-in-out infinite;
    }
    @keyframes particleFloat {
        0%   { transform: translateY(0) scale(1); opacity: 0; }
        15%  { opacity: 0.22; }
        50%  { transform: translateY(-60px) scale(1.3); opacity: 0.3; }
        85%  { opacity: 0.12; }
        100% { transform: translateY(-120px) scale(0.7); opacity: 0; }
    }

    /* ============================================================
       LAYER 3 — HOLOGRAM SHIELD + SCAN + CONE PROJECTION
       ============================================================ */
    .hologram-shield {
        position: fixed;
        top: 50%; left: 50%;
        width: 480px; height: 560px;
        transform: translate(-50%, -50%);
        z-index: 0;
        pointer-events: none;
        opacity: 0.12;
        filter: blur(1px);
        animation: hologramFloat 16s ease-in-out infinite;
        color: var(--blue);
    }
    .hologram-shield svg { width: 100%; height: 100%; }
    /* cone projection beam from the big shield */
    .hologram-shield::after {
        content: '';
        position: absolute;
        left: 50%; top: 38%;
        transform: translateX(-50%);
        width: 360px; height: 400px;
        background: linear-gradient(to bottom, var(--blue) 0%, transparent 92%);
        filter: blur(28px);
        opacity: 0.12;
        clip-path: polygon(40% 0%, 60% 0%, 100% 100%, 0% 100%);
        animation: projectorLight 7s ease-in-out infinite;
    }
    @keyframes hologramFloat {
        0%, 100% { transform: translate(-50%, -50%) scale(1) rotate(0deg); opacity: 0.1; }
        50%      { transform: translate(-50%, -52%) scale(1.03) rotate(2deg); opacity: 0.15; }
    }

    .shield-scan {
        position: absolute;
        left: 50%; top: 0;
        transform: translateX(-50%);
        width: 64%;
        height: 120px;
        background: linear-gradient(to bottom, transparent, rgba(26, 168, 111, 0.5), transparent);
        filter: blur(6px);
        animation: shieldScanner 4s ease-in-out infinite;
    }
    @keyframes shieldScanner {
        0%   { top: -10%; opacity: 0; }
        20%  { opacity: 0.7; }
        50%  { top: 88%; opacity: 0.7; }
        80%  { opacity: 0.4; }
        100% { top: -10%; opacity: 0; }
    }

    /* ============================================================
       LAYER 4 — SECURITY RADAR (rings)
       ============================================================ */
    .radar {
        position: fixed;
        top: 50%; left: 50%;
        width: 640px; height: 640px;
        transform: translate(-50%, -50%);
        z-index: 0;
        pointer-events: none;
    }
    .radar-ring {
        position: absolute;
        top: 50%; left: 50%;
        border-radius: 50%;
        border: 1px solid rgba(19, 143, 91, 0.12);
        transform: translate(-50%, -50%);
        animation: radarRotate 24s linear infinite;
    }
    .radar-ring--1 { width: 100%; height: 100%; animation-duration: 24s; }
    .radar-ring--2 { width: 78%;  height: 78%;  animation-duration: 20s; animation-direction: reverse; }
    .radar-ring--3 { width: 56%;  height: 56%;  animation-duration: 16s; }
    .radar-ring--4 { width: 34%;  height: 34%;  animation-duration: 14s; animation-direction: reverse; }
    .radar-ring--5 { width: 16%;  height: 16%;  animation-duration: 12s; }
    @keyframes radarRotate {
        0%   { transform: translate(-50%, -50%) rotate(0deg) scale(1); opacity: 0.18; }
        50%  { transform: translate(-50%, -50%) rotate(180deg) scale(0.96); opacity: 0.08; }
        100% { transform: translate(-50%, -50%) rotate(360deg) scale(1); opacity: 0.18; }
    }

    /* ============================================================
       LAYER 5 — FLOATING SECURITY ICONS (hologram + CONE BEAM)
       ============================================================ */
    .float-icon {
        position: fixed;
        z-index: 0;
        pointer-events: none;
        font-size: 92px;
        opacity: 0.1;
        color: var(--blue);
        display: flex;
        align-items: center;
        justify-content: center;
        animation: securityFloat 16s ease-in-out infinite;
    }
    .float-icon i {
        position: relative;
        z-index: 2;
        text-shadow: 0 0 18px currentColor, 0 0 36px currentColor;
    }
    /* hologram aura behind icon */
    .float-icon::before {
        content: '';
        position: absolute;
        left: 50%; top: 50%;
        transform: translate(-50%, -50%);
        width: 115%; height: 115%;
        border-radius: 50%;
        background: radial-gradient(circle, currentColor 0%, transparent 68%);
        opacity: 0.18;
        filter: blur(10px);
        z-index: 1;
    }
    /* CONE PROJECTION BEAM — narrows at icon, widens downward */
    .float-icon::after {
        content: '';
        position: absolute;
        left: 50%; top: 52%;
        transform: translateX(-50%);
        width: 180px;
        height: 280px;
        background: linear-gradient(to bottom, currentColor 0%, transparent 92%);
        filter: blur(16px);
        opacity: 0.12;
        clip-path: polygon(42% 0%, 58% 0%, 100% 100%, 0% 100%);
        z-index: 0;
        animation: projectorLight 6s ease-in-out infinite;
    }
    @keyframes projectorLight {
        0%, 100% { opacity: 0.15; transform: translateX(-50%) scaleY(0.9) scaleX(1); }
        50%      { opacity: 0.4;  transform: translateX(-48%) scaleY(1.15) scaleX(1.04); }
    }
    @keyframes securityFloat {
        0%, 100% { transform: translateY(0) rotate(0deg) scale(1); }
        50%      { transform: translateY(-22px) rotate(6deg) scale(1.05); }
    }
    .float-icon--1 { top: 11%;  left: 7%;   font-size: 118px; animation-delay: 0s;   color: var(--cyan); }
    .float-icon--2 { top: 17%;  right: 9%;  font-size: 100px; animation-delay: 2s;   color: var(--blue); }
    .float-icon--3 { bottom: 13%; left: 11%; font-size: 110px; animation-delay: 4s;  color: var(--indigo); }
    .float-icon--4 { bottom: 17%; right: 7%; font-size: 95px;  animation-delay: 1s;  color: var(--cyan-2); }
    .float-icon--5 { top: 44%;   left: 3%;   font-size: 84px;  animation-delay: 3s;  color: var(--blue); }
    .float-icon--6 { top: 40%;   right: 3%;  font-size: 90px;  animation-delay: 5s;  color: var(--cyan); }
    .float-icon--7 { top: 5%;    left: 44%;  font-size: 80px;  animation-delay: 6s;  color: var(--indigo); }
    .float-icon--8 { bottom: 5%; left: 45%;  font-size: 86px;  animation-delay: 2.5s; color: var(--cyan-2); }

    /* ============================================================
       LAYER 6 — SOC MONITORING SWEEP (foreground scanline)
       ============================================================ */
    .scanline {
        position: fixed;
        left: 0; right: 0;
        height: 220px;
        z-index: 1;
        pointer-events: none;
        background: linear-gradient(to bottom, transparent, rgba(19, 143, 91, 0.06), transparent);
        animation: scanSweep 12s linear infinite;
    }
    @keyframes scanSweep {
        0%   { top: -220px; }
        100% { top: 100%; }
    }

    /* ============================================================
       LAYER 6b — HUD CORNER FRAMING (SOC interface)
       ============================================================ */
    .hud-bracket {
        position: fixed;
        width: 38px; height: 38px;
        z-index: 3;
        pointer-events: none;
        opacity: 0.45;
    }
    .hud-bracket--tl { top: 22px; left: 22px;  border-top: 1px solid var(--blue); border-left: 1px solid var(--blue); }
    .hud-bracket--tr { top: 22px; right: 22px; border-top: 1px solid var(--blue); border-right: 1px solid var(--blue); }
    .hud-bracket--bl { bottom: 22px; left: 22px;  border-bottom: 1px solid var(--blue); border-left: 1px solid var(--blue); }
    .hud-bracket--br { bottom: 22px; right: 22px; border-bottom: 1px solid var(--blue); border-right: 1px solid var(--blue); }

    /* ============================================================
       LAYER 7 — CARD WRAP + BORDER GLOW
       ============================================================ */
    .card-wrap {
        position: relative;
        border-radius: var(--radius-lg);
        width: 100%;
        max-width: 320px;
        z-index: 2;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    /* Border Glow layer 1 — clockwise */
    .card-wrap::before {
        content: '';
        position: absolute;
        inset: -2px;
        border-radius: calc(var(--radius-lg) + 2px);
        background: conic-gradient(
            from 0deg at 50% 50%,
            transparent 0deg, transparent 200deg,
            var(--cyan) 230deg, var(--blue) 255deg, var(--indigo) 275deg,
            transparent 300deg, transparent 360deg
        );
        animation: encryptBorder 8s linear infinite;
        z-index: 0;
        opacity: 0.38;
        transition: opacity 0.4s ease;
    }
    /* Border Glow layer 2 — counter-clockwise */
    .card-wrap::after {
        content: '';
        position: absolute;
        inset: -2px;
        border-radius: calc(var(--radius-lg) + 2px);
        background: conic-gradient(
            from 180deg at 50% 50%,
            transparent 0deg, transparent 200deg,
            rgba(26, 168, 111, 0.5) 230deg, rgba(19, 143, 91, 0.3) 255deg,
            rgba(26, 168, 111, 0.5) 275deg, transparent 300deg, transparent 360deg
        );
        animation: encryptBorder 8s linear infinite reverse;
        z-index: 0;
        opacity: 0.22;
        transition: opacity 0.4s ease;
    }
    .card-wrap:hover { transform: translateY(-5px); }
    .card-wrap:hover::before { opacity: 0.62; }
    .card-wrap:hover::after { opacity: 0.42; }
    @keyframes encryptBorder { to { transform: rotate(360deg); } }

    /* Glassmorphism card */
    .card-security {
        position: relative;
        z-index: 1;
        background: rgba(255, 255, 255, 0.32);
        backdrop-filter: blur(28px) saturate(180%);
        -webkit-backdrop-filter: blur(28px) saturate(180%);
        border: 1px solid rgba(19, 143, 91, 0.25);
        border-radius: var(--radius-lg);
        padding: 24px 22px 20px;
        box-shadow:
            0 24px 80px rgba(10, 79, 71, 0.18),
            inset 0 1px 0 rgba(255, 255, 255, 0.6),
            0 0 60px rgba(19, 143, 91, 0.08);
        animation: secureEntry 0.9s cubic-bezier(0.16, 1, 0.3, 1) both;
        transition: box-shadow 0.4s ease, border-color 0.4s ease, background 0.4s ease;
    }
    .card-wrap:hover .card-security {
        background: rgba(255, 255, 255, 0.42);
        box-shadow:
            0 32px 100px rgba(10, 79, 71, 0.24),
            inset 0 1px 0 rgba(255, 255, 255, 0.7),
            0 0 90px rgba(19, 143, 91, 0.14);
        border-color: rgba(19, 143, 91, 0.5);
    }
    @keyframes secureEntry {
        0%   { opacity: 0; transform: translateY(40px) scale(0.94); filter: blur(10px); }
        100% { opacity: 1; transform: translateY(0) scale(1); filter: blur(0); }
    }

    /* ============================================================
       SHIELD ICON (header)
       ============================================================ */
    .header-wrap { text-align: center; margin-bottom: 16px; }
    .icon-shield-wrap {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 52px; height: 52px;
        margin-bottom: 8px;
    }
    .icon-shield-wrap svg {
        width: 46px; height: 46px;
        position: relative; z-index: 1;
        filter: drop-shadow(0 0 18px rgba(26, 168, 111, 0.28));
        animation: shieldPulse 3s ease-in-out infinite;
    }
    .icon-shield-bg {
        position: absolute;
        width: 100%; height: 100%;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(26, 168, 111, 0.15) 0%, transparent 70%);
        animation: shieldGlow 3s ease-in-out infinite;
    }
    @keyframes shieldGlow {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50%      { transform: scale(1.35); opacity: 0.95; }
    }
    @keyframes shieldPulse {
        0%, 100% { transform: scale(1); filter: drop-shadow(0 0 20px rgba(26, 168, 111, 0.28)); }
        50%      { transform: scale(1.06); filter: drop-shadow(0 0 42px rgba(26, 168, 111, 0.5)); }
    }

    .header-title {
        font-size: 16px; font-weight: 800;
        color: var(--text-primary);
        letter-spacing: -0.3px; margin-bottom: 3px;
    }
    .header-subtitle {
        font-size: 11px; color: var(--text-secondary);
        line-height: 1.5; max-width: 250px;
        margin: 0 auto; font-weight: 400;
    }
    .trust-badge {
        display: inline-flex; align-items: center; gap: 5px;
        margin-top: 9px; padding: 3px 10px;
        border-radius: 20px; font-size: 9px; font-weight: 600;
        letter-spacing: 0.5px; text-transform: uppercase;
        color: var(--blue);
        background: rgba(19, 143, 91, 0.08);
        border: 1px solid rgba(19, 143, 91, 0.2);
    }
    .trust-badge .pulse-dot {
        width: 6px; height: 6px; border-radius: 50%;
        background: var(--blue); box-shadow: 0 0 8px var(--blue);
        animation: dotPulse 1.6s ease-in-out infinite;
    }
    @keyframes dotPulse { 0%,100%{opacity:1;} 50%{opacity:0.3;} }

    /* ============================================================
       FORM / INPUT
       ============================================================ */
    .form-group-custom { margin-bottom: 12px; }
    .form-group-custom label {
        display: block; font-size: 10px; font-weight: 600;
        color: rgba(51, 65, 85, 0.9);
        margin-bottom: 5px; letter-spacing: 0.5px; text-transform: uppercase;
    }
    .form-group-custom .input-wrap { position: relative; }
    .form-group-custom .form-control {
        width: 100%; height: 38px !important;
        background: rgba(255, 255, 255, 0.5) !important;
        border: 1.5px solid rgba(19, 143, 91, 0.3) !important;
        border-radius: var(--radius-md) !important;
        padding: 0 12px !important;
        color: var(--text-primary) !important;
        font-size: 12px !important;
        font-family: 'Inter', system-ui, sans-serif !important;
        caret-color: var(--blue) !important;
        transition: all 0.3s ease; outline: none;
        box-shadow: 0 0 12px rgba(19, 143, 91, 0.06) !important;
    }
    .form-group-custom .form-control::placeholder {
        color: rgba(71, 85, 105, 0.5) !important; font-weight: 400;
    }
    .form-group-custom .form-control:focus {
        border-color: var(--blue) !important;
        box-shadow: 0 0 0 3px rgba(19, 143, 91, 0.15), 0 0 24px rgba(19, 143, 91, 0.14) !important;
        background: rgba(255, 255, 255, 0.75) !important;
    }
    .input-otp {
        text-align: center; letter-spacing: 5px;
        font-weight: 700; font-size: 14px !important;
    }
    .input-recovery { display: none; }
    .input-recovery.active { display: block; }

    .toggle-wrap {
        display: flex; align-items: center; gap: 8px;
        margin-bottom: 11px; cursor: pointer; user-select: none;
    }
    .toggle-track {
        width: 34px; height: 20px; border-radius: 10px;
        background: rgba(15, 23, 42, 0.1);
        border: 1.5px solid rgba(15, 23, 42, 0.15);
        position: relative; transition: all 0.3s ease; flex-shrink: 0;
    }
    .toggle-track .toggle-thumb {
        width: 14px; height: 14px; border-radius: 50%;
        background: rgba(51, 65, 85, 0.5);
        position: absolute; top: 2px; left: 2px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .toggle-track.active {
        background: rgba(19, 143, 91, 0.15);
        border-color: var(--blue);
        box-shadow: 0 0 16px rgba(19, 143, 91, 0.18);
    }
    .toggle-track.active .toggle-thumb {
        left: 16px; background: var(--blue);
        box-shadow: 0 0 16px rgba(19, 143, 91, 0.4);
    }
    .toggle-label { font-size: 11px; color: var(--text-secondary); font-weight: 500; }
    .toggle-label.active-label { color: var(--cyan); }

    .alert-cs {
        display: flex; align-items: center; gap: 8px;
        padding: 8px 10px; border-radius: var(--radius-md);
        font-size: 11px; margin-bottom: 11px;
    }
    .alert-cs--error {
        background: rgba(239, 68, 68, 0.08);
        border: 1px solid rgba(239, 68, 68, 0.15); color: #fca5a5;
    }
    .alert-cs--success {
        background: rgba(34, 197, 94, 0.08);
        border: 1px solid rgba(34, 197, 94, 0.15); color: #86efac;
    }

    /* ============================================================
       BUTTON (premium + shine)
       ============================================================ */
    .btn-verify {
        position: relative; overflow: hidden;
        width: 100%; height: 38px; border: none !important;
        border-radius: var(--radius-md) !important;
        background: linear-gradient(135deg, var(--cyan), var(--blue)) !important;
        color: #fff !important; font-size: 12px; font-weight: 700;
        font-family: 'Inter', system-ui, sans-serif;
        cursor: pointer;
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 24px rgba(26, 168, 111, 0.28), 0 0 40px rgba(26, 168, 111, 0.08) !important;
        display: flex; align-items: center; justify-content: center;
        gap: 8px; letter-spacing: 0.3px;
    }
    .btn-verify::before {
        content: ''; position: absolute; top: 0; left: -100%;
        width: 100%; height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.35), transparent);
        transition: left 0.6s ease;
    }
    .btn-verify:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 44px rgba(26, 168, 111, 0.4), 0 0 70px rgba(26, 168, 111, 0.14) !important;
        background: linear-gradient(135deg, var(--cyan-2), var(--indigo)) !important;
    }
    .btn-verify:hover::before { left: 100%; }
    .btn-verify:active { transform: translateY(0) scale(1); }
    .btn-verify .spinner-load {
        display: none; width: 13px; height: 13px;
        border: 2.5px solid rgba(255, 255, 255, 0.2);
        border-top-color: #fff; border-radius: 50%;
        animation: spinLoader 0.7s linear infinite;
    }
    .btn-verify.loading .spinner-load { display: inline-block; }
    @keyframes spinLoader { to { transform: rotate(360deg); } }

    /* ============================================================
       FOOTER
       ============================================================ */
    .footer-auth {
        text-align: center; margin-top: 14px; padding-top: 11px;
        border-top: 1px solid rgba(15, 23, 42, 0.08);
    }
    .footer-auth a {
        color: rgba(15, 23, 42, 0.9) !important; font-size: 11px;
        text-decoration: none; transition: color 0.2s;
        display: inline-flex; align-items: center; gap: 5px;
    }
    .footer-auth a:hover { color: var(--blue) !important; }
    .footer-auth .copyright {
        display: block; margin-top: 6px; font-size: 9px;
        color: rgba(15, 23, 42, 0.5);
    }

    /* ============================================================
       RESPONSIVE
       ============================================================ */
    @media (max-width: 768px) {
        .card-security { padding: 22px 20px 18px; }
        .header-title { font-size: 15px; }
        .icon-shield-wrap { width: 48px; height: 48px; }
        .icon-shield-wrap svg { width: 42px; height: 42px; }
        .form-group-custom .form-control { height: 36px !important; font-size: 11px !important; }
        .btn-verify { height: 36px; font-size: 11px; }
        .hologram-shield { width: 360px; height: 420px; }
        .radar { width: 420px; height: 420px; }
        .ambient-halo { width: 520px; height: 520px; }
        .float-icon--7, .float-icon--8 { display: none; }
        .hud-bracket { width: 28px; height: 28px; }
    }
    @media (max-width: 480px) {
        .page-wrap { padding: 14px; }
        .card-security { padding: 20px 18px 18px; }
        .header-title { font-size: 14px; }
        .header-subtitle { font-size: 10px; }
        .icon-shield-wrap { width: 44px; height: 44px; margin-bottom: 7px; }
        .icon-shield-wrap svg { width: 40px; height: 40px; }
        .form-group-custom { margin-bottom: 10px; }
        .form-group-custom .form-control { height: 34px !important; font-size: 11px !important; }
        .input-otp { font-size: 13px !important; letter-spacing: 4px; }
        .btn-verify { height: 34px; font-size: 11px; }
        .hologram-shield { width: 290px; height: 350px; opacity: 0.05; }
        .radar { width: 300px; height: 300px; }
        .ambient-halo { width: 380px; height: 380px; }
        .float-icon { font-size: 58px !important; }
        .float-icon::after { height: 200px; width: 130px; }
        .bg-grid { background-size: 40px 40px; }
        .hud-bracket { width: 22px; height: 22px; }
    }

    /* ============================================================
       THEME TOGGLE (page corner)
       ============================================================ */
    .theme-toggle--2fa {
        position: fixed !important;
        top: 20px !important;
        right: 20px !important;
        z-index: 10 !important;
        background: rgba(255, 255, 255, 0.55) !important;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(19, 143, 91, 0.25) !important;
        box-shadow: 0 4px 18px rgba(10, 79, 71, 0.12) !important;
        color: var(--blue) !important;
        transition: all .3s ease !important;
    }
    .theme-toggle--2fa:hover {
        color: var(--indigo) !important;
        background: rgba(255, 255, 255, 0.8) !important;
        transform: rotate(15deg);
    }
    html.dark-mode .theme-toggle--2fa {
        background: rgba(10, 16, 32, 0.6) !important;
        border-color: rgba(26, 168, 111, 0.25) !important;
        color: var(--cyan) !important;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.4) !important;
    }
    html.dark-mode .theme-toggle--2fa:hover {
        background: rgba(10, 16, 32, 0.85) !important;
        color: #fff !important;
    }

    /* ============================================================
       DARK MODE — restore cyber dark theme (html.dark-mode)
       Toggles via <html class="dark-mode"> (localStorage: theme-preference)
       ============================================================ */
    html.dark-mode body {
        background:
            radial-gradient(ellipse at 18% 8%, #071122 0%, transparent 55%),
            radial-gradient(ellipse at 82% 92%, #0A1020 0%, transparent 55%),
            #050816 !important;
    }

    html.dark-mode .bg-grid {
        background-image:
            linear-gradient(rgba(26, 168, 111, 0.04) 1px, transparent 1px),
            linear-gradient(90deg, rgba(26, 168, 111, 0.04) 1px, transparent 1px);
    }
    html.dark-mode .vignette {
        background:
            radial-gradient(ellipse at center, transparent 38%, rgba(5, 8, 22, 0.88) 100%),
            radial-gradient(ellipse at center, rgba(26, 168, 111, 0.05) 0%, transparent 55%);
    }
    html.dark-mode .ambient-halo {
        background: radial-gradient(circle, rgba(26, 168, 111, 0.07) 0%, rgba(19, 143, 91, 0.03) 35%, transparent 65%);
    }
    html.dark-mode .particle {
        background: #1aa86f;
        box-shadow: 0 0 6px #1aa86f;
        opacity: 0.2;
    }

    html.dark-mode .hologram-shield {
        color: #1aa86f;
        opacity: 0.07;
        filter: blur(1.5px);
    }
    html.dark-mode .hologram-shield::after {
        background: linear-gradient(to bottom, #1aa86f 0%, transparent 92%);
        opacity: 0.09;
    }
    @keyframes hologramFloatDark {
        0%, 100% { transform: translate(-50%, -50%) scale(1) rotate(0deg); opacity: 0.06; }
        50%      { transform: translate(-50%, -52%) scale(1.03) rotate(2deg); opacity: 0.09; }
    }
    html.dark-mode .hologram-shield { animation-name: hologramFloatDark; }

    html.dark-mode .radar-ring { border-color: rgba(26, 168, 111, 0.06); }

    html.dark-mode .float-icon { color: #1aa86f; opacity: 0.06; }
    html.dark-mode .float-icon--2,
    html.dark-mode .float-icon--5 { color: #138F5B; }
    html.dark-mode .float-icon--3,
    html.dark-mode .float-icon--7 { color: #0a4f47; }
    html.dark-mode .float-icon--4,
    html.dark-mode .float-icon--8 { color: #16a066; }

    html.dark-mode .scanline {
        background: linear-gradient(to bottom, transparent, rgba(26, 168, 111, 0.04), transparent);
    }
    html.dark-mode .hud-bracket { opacity: 0.28; }
    html.dark-mode .hud-bracket--tl { border-top-color: #1aa86f; border-left-color: #1aa86f; }
    html.dark-mode .hud-bracket--tr { border-top-color: #1aa86f; border-right-color: #1aa86f; }
    html.dark-mode .hud-bracket--bl { border-bottom-color: #1aa86f; border-left-color: #1aa86f; }
    html.dark-mode .hud-bracket--br { border-bottom-color: #1aa86f; border-right-color: #1aa86f; }

    /* Card → dark glass */
    html.dark-mode .card-security {
        background: rgba(10, 16, 32, 0.6);
        border-color: rgba(26, 168, 111, 0.25);
        box-shadow:
            0 24px 80px rgba(0, 0, 0, 0.55),
            inset 0 1px 0 rgba(255, 255, 255, 0.04),
            0 0 60px rgba(26, 168, 111, 0.06);
    }
    html.dark-mode .card-wrap:hover .card-security {
        background: rgba(10, 16, 32, 0.6);
        box-shadow:
            0 32px 100px rgba(0, 0, 0, 0.65),
            inset 0 1px 0 rgba(255, 255, 255, 0.04),
            0 0 90px rgba(26, 168, 111, 0.1);
        border-color: rgba(26, 168, 111, 0.5);
    }

    html.dark-mode .header-title { color: #f1f5f9; }
    html.dark-mode .header-subtitle { color: rgba(148, 163, 184, 0.8); }
    html.dark-mode .icon-shield-wrap svg {
        filter: drop-shadow(0 0 20px rgba(26, 168, 111, 0.28));
    }

    html.dark-mode .form-group-custom label { color: rgba(148, 163, 184, 0.9); }
    html.dark-mode .form-group-custom .form-control {
        background: rgba(15, 23, 42, 0.55) !important;
        border-color: rgba(26, 168, 111, 0.3) !important;
        color: #f1f5f9 !important;
        caret-color: #1aa86f !important;
        box-shadow: 0 0 12px rgba(26, 168, 111, 0.05) !important;
    }
    html.dark-mode .form-group-custom .form-control::placeholder {
        color: rgba(148, 163, 184, 0.3) !important;
    }
    html.dark-mode .form-group-custom .form-control:focus {
        border-color: #1aa86f !important;
        box-shadow: 0 0 0 3px rgba(26, 168, 111, 0.14), 0 0 24px rgba(26, 168, 111, 0.12) !important;
        background: rgba(15, 23, 42, 0.75) !important;
    }

    html.dark-mode .toggle-track {
        background: rgba(255, 255, 255, 0.08);
        border-color: rgba(255, 255, 255, 0.15);
    }
    html.dark-mode .toggle-track .toggle-thumb { background: rgba(255, 255, 255, 0.4); }
    html.dark-mode .toggle-track.active {
        background: rgba(26, 168, 111, 0.12);
        border-color: #1aa86f;
        box-shadow: 0 0 16px rgba(26, 168, 111, 0.15);
    }
    html.dark-mode .toggle-track.active .toggle-thumb {
        background: #1aa86f;
        box-shadow: 0 0 16px rgba(26, 168, 111, 0.4);
    }
    html.dark-mode .toggle-label { color: rgba(148, 163, 184, 0.8); }
    html.dark-mode .toggle-label.active-label { color: #1aa86f; }

    html.dark-mode .trust-badge {
        color: #1aa86f;
        background: rgba(26, 168, 111, 0.06);
        border-color: rgba(26, 168, 111, 0.15);
    }
    html.dark-mode .trust-badge .pulse-dot {
        background: #1aa86f;
        box-shadow: 0 0 8px #1aa86f;
    }

    html.dark-mode .footer-auth { border-top-color: rgba(148, 163, 184, 0.06); }
    html.dark-mode .footer-auth a { color: rgba(241, 245, 249, 0.9) !important; }
    html.dark-mode .footer-auth a:hover { color: #1aa86f !important; }
    html.dark-mode .footer-auth .copyright { color: rgba(241, 245, 249, 0.5); }
</style>

<div class="page-wrap">

    {{-- Theme toggle (syncs with main page via localStorage: theme-preference) --}}
    <a href="#" class="theme-toggle theme-toggle--2fa" title="Ganti tema" aria-label="Ganti tema">
        <i class="fas fa-moon"></i>
    </a>

    {{-- L1: Encryption Grid --}}
    <div class="bg-grid"></div>

    {{-- L1b: Vignette + depth fog --}}
    <div class="vignette"></div>

    {{-- L1c: Ambient halo --}}
    <div class="ambient-halo"></div>

    {{-- L2: Particles --}}
    <div class="particles">
        @for($i = 0; $i < 48; $i++)
            <span class="particle" style="
                left: {{ ($i * 37) % 100 }}%;
                top: {{ ($i * 61) % 100 }}%;
                width: {{ 2 + ($i % 4) }}px;
                height: {{ 2 + ($i % 4) }}px;
                animation-delay: {{ ($i % 12) * 0.7 }}s;
                animation-duration: {{ 8 + ($i % 6) * 2 }}s;
            "></span>
        @endfor
    </div>

    {{-- L3: Hologram Shield + scan + cone projection --}}
    <div class="hologram-shield">
        <svg viewBox="0 0 100 120" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M50 4 L90 22 L90 56 C90 84 70 104 50 112 C30 104 10 84 10 56 L10 22 Z"
                stroke="currentColor" stroke-width="2" stroke-linejoin="round" fill="none"/>
            <path d="M50 16 L80 30 L80 56 C80 76 66 92 50 98 C34 92 20 76 20 56 L20 30 Z"
                stroke="currentColor" stroke-width="1.4" fill="none" opacity="0.6"/>
            <path d="M34 60 L46 72 L66 48"
                stroke="currentColor" stroke-width="3" stroke-linecap="round"
                stroke-linejoin="round" fill="none"/>
        </svg>
        <div class="shield-scan"></div>
    </div>

    {{-- L4: Radar --}}
    <div class="radar">
        <div class="radar-ring radar-ring--1"></div>
        <div class="radar-ring radar-ring--2"></div>
        <div class="radar-ring radar-ring--3"></div>
        <div class="radar-ring radar-ring--4"></div>
        <div class="radar-ring radar-ring--5"></div>
    </div>

    {{-- L5: Floating security icons (hologram + cone beam) --}}
    <div class="float-icon float-icon--1"><i class="fas fa-shield-alt"></i></div>
    <div class="float-icon float-icon--2"><i class="fas fa-lock"></i></div>
    <div class="float-icon float-icon--3"><i class="fas fa-fingerprint"></i></div>
    <div class="float-icon float-icon--4"><i class="fas fa-key"></i></div>
    <div class="float-icon float-icon--5"><i class="fas fa-eye"></i></div>
    <div class="float-icon float-icon--6"><i class="fas fa-cloud"></i></div>
    <div class="float-icon float-icon--7"><i class="fas fa-server"></i></div>
    <div class="float-icon float-icon--8"><i class="fas fa-user-shield"></i></div>

    {{-- L6: SOC sweep + HUD framing --}}
    <div class="scanline"></div>
    <div class="hud-bracket hud-bracket--tl"></div>
    <div class="hud-bracket hud-bracket--tr"></div>
    <div class="hud-bracket hud-bracket--bl"></div>
    <div class="hud-bracket hud-bracket--br"></div>

    {{-- L7: Card --}}
    <div class="card-wrap">
        <div class="card-security">

            <div class="header-wrap">
                <div class="icon-shield-wrap">
                    <div class="icon-shield-bg"></div>
                    <svg viewBox="0 0 72 84" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M36 4 L66 18 L66 44 C66 66 52 78 36 82 C20 78 6 66 6 44 L6 18 Z"
                            fill="url(#shieldGrad)" stroke="currentColor" stroke-width="1.5"
                            style="color: var(--cyan); opacity: 0.95;"/>
                        <path d="M28 44 L34 50 L44 38"
                            stroke="#0a4f47" stroke-width="3" stroke-linecap="round"
                            stroke-linejoin="round" fill="none" style="opacity: 0.95;"/>
                        <defs>
                            <linearGradient id="shieldGrad" x1="36" y1="4" x2="36" y2="82" gradientUnits="userSpaceOnUse">
                                <stop offset="0%" stop-color="#1aa86f" stop-opacity="0.25"/>
                                <stop offset="100%" stop-color="#138F5B" stop-opacity="0.06"/>
                            </linearGradient>
                        </defs>
                    </svg>
                </div>

                <h1 class="header-title">Verifikasi Keamanan</h1>
                <p class="header-subtitle">
                    Autentikasi Dua Faktor melindungi akun Anda dengan lapisan verifikasi tambahan berbasis Zero Trust.
                </p>
                <span class="trust-badge">
                    <span class="pulse-dot"></span> Koneksi Terenkripsi · 256-bit
                </span>
            </div>

            <form method="POST" action="{{ route('2fa.verify') }}" id="verifyForm">
                @csrf

                <div class="form-group-custom" id="otpGroup">
                    <label>Kode Autentikasi</label>
                    <div class="input-wrap">
                        <input type="text" name="one_time_password"
                            class="form-control input-otp"
                            placeholder="000000" inputmode="numeric"
                            maxlength="6" required autofocus>
                    </div>
                </div>

                <div class="form-group-custom input-recovery" id="recoveryGroup">
                    <label>Kode Recovery</label>
                    <div class="input-wrap">
                        <input type="text" name="recovery_code"
                            class="form-control"
                            placeholder="XXXXX-XXXXX" disabled>
                    </div>
                </div>

                <div class="toggle-wrap" id="toggleRecovery">
                    <div class="toggle-track" id="toggleTrack">
                        <div class="toggle-thumb"></div>
                    </div>
                    <span class="toggle-label">Gunakan Kode Recovery</span>
                </div>

                @if (session()->has('error'))
                <div class="alert-cs alert-cs--error">
                    <i class="fas fa-exclamation-circle" style="flex-shrink:0;"></i>
                    <span>{{ session('error') }}</span>
                </div>
                @endif

                @if (session()->has('success'))
                <div class="alert-cs alert-cs--success">
                    <i class="fas fa-check-circle" style="flex-shrink:0;"></i>
                    <span>{{ session('success') }}</span>
                </div>
                @endif

                <button type="submit" class="btn-verify" id="verifyBtn">
                    <i class="fas fa-shield-alt"></i>
                    <span>Verifikasi Identitas</span>
                    <span class="spinner-load"></span>
                </button>

            </form>

            <div class="footer-auth">
                <a href="{{ route('login') }}">
                    <i class="fas fa-arrow-left"></i>
                    Kembali ke Login
                </a>
                <span class="copyright">&copy; 2026 MIS Nurul Ulum &middot; Zero Trust Security</span>
            </div>

        </div>
    </div>

</div>

<script>
    (function() {
        var toggle = document.getElementById('toggleRecovery');
        var track = document.getElementById('toggleTrack');
        var otpGroup = document.getElementById('otpGroup');
        var otpInput = otpGroup.querySelector('input');
        var recoveryGroup = document.getElementById('recoveryGroup');
        var recoveryInput = recoveryGroup.querySelector('input');
        var form = document.getElementById('verifyForm');
        var btn = document.getElementById('verifyBtn');
        var label = toggle.querySelector('.toggle-label');

        toggle.addEventListener('click', function() {
            var active = track.classList.toggle('active');
            label.classList.toggle('active-label', active);
            recoveryGroup.classList.toggle('active', active);
            recoveryInput.disabled = !active;
            otpInput.disabled = active;
            otpInput.required = !active;
            recoveryInput.required = active;

            if (active) {
                otpGroup.style.display = 'none';
                recoveryInput.focus();
            } else {
                otpGroup.style.display = 'block';
                setTimeout(function() { otpInput.focus(); }, 100);
            }
        });

        form.addEventListener('submit', function() {
            btn.classList.add('loading');
            btn.querySelector('i').style.display = 'none';
            btn.querySelector('span').textContent = 'Memverifikasi...';
            btn.disabled = true;
        });
    })();
</script>

@endsection
