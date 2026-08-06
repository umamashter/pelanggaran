@extends('layouts.data')
@section('title', 'Verifikasi Keamanan · Zero Trust')

@section('datas')

<style>
    /* ============================================================
       MIS NURUL ULUM — 2FA CHALLENGE (Zero Trust Security)
       Design system yang sama dengan Dashboard & Security modules:
       `--zt-*` tokens memirror `--ab-*` (primary blue + emerald
       untuk status keamanan/sukses, glass card, radius 20-22px,
       soft shadow, dark mode via html.dark-mode).
       ============================================================ */
    :root {
        --zt-primary: #2563eb;
        --zt-primary-2: #3b82f6;
        --zt-primary-3: #60a5fa;
        --zt-primary-dark: #1d4ed8;
        --zt-primary-soft: #eff6ff;
        --zt-primary-border: rgba(37, 99, 235, .22);

        --zt-grad: linear-gradient(135deg, #2563eb, #3b82f6);
        --zt-grad-security: linear-gradient(135deg, #16a34a, #2563eb);

        --zt-green: #16a34a;
        --zt-green-2: #22c55e;
        --zt-green-soft: #f0fdf4;
        --zt-green-border: rgba(22, 163, 74, .32);

        --zt-bg: #f8fafc;
        --zt-card: #ffffff;
        --zt-border: #e2e8f0;
        --zt-border-soft: #eef2f7;
        --zt-text: #0f172a;
        --zt-text-2: #475569;
        --zt-text-3: #94a3b8;

        --zt-red: #dc2626;       --zt-red-soft: #fef2f2;   --zt-red-border: #fecaca;
        --zt-amber: #d97706;     --zt-amber-soft: #fffbeb; --zt-amber-border: #fde68a;
        --zt-sky: #0284c7;       --zt-sky-soft: #f0f9ff;   --zt-sky-border: #bae6fd;

        --zt-shadow: 0 6px 18px -6px rgba(15, 23, 42, .08);
        --zt-shadow-lg: 0 24px 60px -20px rgba(15, 23, 42, .22);
        --zt-shadow-glow: 0 0 0 1px rgba(37, 99, 235, .06), 0 24px 80px -20px rgba(37, 99, 235, .18);

        --zt-radius: 22px;
        --zt-radius-md: 12px;

        --zt-font: 'Inter', system-ui, -apple-system, 'Segoe UI', sans-serif;
    }

    html.dark-mode {
        --zt-primary: #3DA9FC;
        --zt-primary-2: #2EA8FF;
        --zt-primary-3: #6ec9ff;
        --zt-primary-dark: #2EA8FF;
        --zt-primary-soft: rgba(61, 169, 252, .14);
        --zt-primary-border: rgba(61, 169, 252, .35);

        --zt-grad: linear-gradient(135deg, #2EA8FF, #00E5FF);
        --zt-grad-security: linear-gradient(135deg, #34d399, #2EA8FF);

        --zt-green: #34d399;
        --zt-green-2: #6ee7b7;
        --zt-green-soft: rgba(52, 211, 153, .12);
        --zt-green-border: rgba(52, 211, 153, .35);

        --zt-bg: #0b1220;
        --zt-card: rgba(13, 25, 38, .9);
        --zt-border: rgba(255, 255, 255, .1);
        --zt-border-soft: rgba(255, 255, 255, .06);
        --zt-text: #f8fafc;
        --zt-text-2: #cbd5e1;
        --zt-text-3: #7d96a6;

        --zt-red: #f87171;       --zt-red-soft: rgba(248, 113, 113, .12);   --zt-red-border: rgba(248, 113, 113, .35);
        --zt-amber: #fbbf24;     --zt-amber-soft: rgba(251, 191, 36, .12);  --zt-amber-border: rgba(251, 191, 36, .35);
        --zt-sky: #38bdf8;       --zt-sky-soft: rgba(56, 189, 248, .12);    --zt-sky-border: rgba(56, 189, 248, .35);

        --zt-shadow: 0 6px 18px -6px rgba(0, 0, 0, .35);
        --zt-shadow-lg: 0 24px 60px -20px rgba(0, 0, 0, .6);
        --zt-shadow-glow: 0 0 0 1px rgba(61, 169, 252, .1), 0 24px 80px -20px rgba(0, 0, 0, .55);
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    html { scroll-behavior: smooth; }

    body {
        background:
            radial-gradient(ellipse at 18% 6%, var(--zt-primary-soft) 0%, transparent 55%),
            radial-gradient(ellipse at 84% 92%, var(--zt-green-soft) 0%, transparent 55%),
            var(--zt-bg) !important;
        font-family: var(--zt-font) !important;
        -webkit-font-smoothing: antialiased;
        overflow-x: hidden !important;
        overflow-y: auto !important;
        min-height: 100vh !important;
        color: var(--zt-text);
    }

    ::selection { background: rgba(37, 99, 235, .16); }
    html.dark-mode ::selection { background: rgba(61, 169, 252, .28); }

    .page-wrap {
        position: relative;
        z-index: 2;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 28px;
    }

    /* ============================================================
       L1 — ENCRYPTION GRID (halus, tidak mengganggu)
       ============================================================ */
    .bg-grid {
        position: fixed;
        inset: 0;
        z-index: 0;
        pointer-events: none;
        background-image:
            linear-gradient(var(--zt-primary-border) 1px, transparent 1px),
            linear-gradient(90deg, var(--zt-primary-border) 1px, transparent 1px);
        background-size: 64px 64px;
        opacity: .5;
        animation: gridMove 48s linear infinite;
        -webkit-mask-image: radial-gradient(ellipse at center, #000 30%, transparent 78%);
        mask-image: radial-gradient(ellipse at center, #000 30%, transparent 78%);
    }
    html.dark-mode .bg-grid { opacity: .28; }
    @keyframes gridMove { 0% { background-position: 0 0; } 100% { background-position: 64px 64px; } }

    /* ============================================================
       L1b — VIGNETTE + DEPTH
       ============================================================ */
    .vignette {
        position: fixed;
        inset: 0;
        z-index: 0;
        pointer-events: none;
        background:
            radial-gradient(ellipse at center, transparent 38%, rgba(148, 163, 184, .10) 100%),
            radial-gradient(ellipse at center, var(--zt-primary-border) 0%, transparent 62%);
    }
    html.dark-mode .vignette {
        background:
            radial-gradient(ellipse at center, transparent 40%, rgba(5, 8, 22, .72) 100%),
            radial-gradient(ellipse at center, rgba(61, 169, 252, .06) 0%, transparent 60%);
    }

    /* ============================================================
       L1c — AMBIENT HALO (napas)
       ============================================================ */
    .ambient-halo {
        position: fixed;
        top: 50%; left: 50%;
        width: 760px; height: 760px;
        transform: translate(-50%, -50%);
        z-index: 0;
        pointer-events: none;
        background: radial-gradient(circle, var(--zt-primary-border) 0%, rgba(37, 99, 235, .05) 38%, transparent 65%);
        animation: haloBreathe 8s ease-in-out infinite;
    }
    html.dark-mode .ambient-halo { background: radial-gradient(circle, rgba(61, 169, 252, .08) 0%, rgba(46, 168, 255, .04) 38%, transparent 65%); }
    @keyframes haloBreathe {
        0%, 100% { opacity: .6;  transform: translate(-50%, -50%) scale(1); }
        50%      { opacity: 1;   transform: translate(-50%, -50%) scale(1.06); }
    }

    /* ============================================================
       L2 — PARTICLES (sangat halus)
       ============================================================ */
    .particles { position: fixed; inset: 0; z-index: 0; pointer-events: none; }
    .particle {
        position: absolute;
        border-radius: 50%;
        background: var(--zt-primary-2);
        box-shadow: 0 0 6px var(--zt-primary-border);
        opacity: .3;
        animation: particleFloat 14s ease-in-out infinite;
    }
    html.dark-mode .particle { opacity: .18; }
    @keyframes particleFloat {
        0%   { transform: translateY(0) scale(1); opacity: 0; }
        20%  { opacity: .2; }
        50%  { transform: translateY(-50px) scale(1.25); opacity: .28; }
        80%  { opacity: .1; }
        100% { transform: translateY(-100px) scale(.7); opacity: 0; }
    }

    /* ============================================================
       L3 — HOLOGRAM SHIELD + SCAN (dekorasi)
       ============================================================ */
    .hologram-shield {
        position: fixed;
        top: 50%; left: 50%;
        width: 520px; height: 600px;
        transform: translate(-50%, -50%);
        z-index: 0;
        pointer-events: none;
        opacity: .07;
        filter: blur(1px);
        animation: hologramFloat 18s ease-in-out infinite;
        color: var(--zt-primary);
    }
    .hologram-shield svg { width: 100%; height: 100%; }
    .hologram-shield::after {
        content: '';
        position: absolute;
        left: 50%; top: 36%;
        transform: translateX(-50%);
        width: 380px; height: 420px;
        background: linear-gradient(to bottom, var(--zt-primary) 0%, transparent 92%);
        filter: blur(30px);
        opacity: .1;
        clip-path: polygon(40% 0%, 60% 0%, 100% 100%, 0% 100%);
        animation: projectorLight 8s ease-in-out infinite;
    }
    html.dark-mode .hologram-shield { opacity: .05; }
    @keyframes hologramFloat {
        0%, 100% { transform: translate(-50%, -50%) scale(1) rotate(0deg); opacity: .06; }
        50%      { transform: translate(-50%, -52%) scale(1.03) rotate(2deg); opacity: .1; }
    }

    .shield-scan {
        position: absolute;
        left: 50%; top: 0;
        transform: translateX(-50%);
        width: 64%;
        height: 130px;
        background: linear-gradient(to bottom, transparent, rgba(37, 99, 235, .4), transparent);
        filter: blur(6px);
        animation: shieldScanner 4.5s ease-in-out infinite;
    }
    html.dark-mode .shield-scan { background: linear-gradient(to bottom, transparent, rgba(61, 169, 252, .35), transparent); }
    @keyframes shieldScanner {
        0%   { top: -10%; opacity: 0; }
        20%  { opacity: .6; }
        50%  { top: 88%; opacity: .6; }
        80%  { opacity: .35; }
        100% { top: -10%; opacity: 0; }
    }

    /* ============================================================
       L4 — SECURITY RADAR (rings halus)
       ============================================================ */
    .radar {
        position: fixed;
        top: 50%; left: 50%;
        width: 680px; height: 680px;
        transform: translate(-50%, -50%);
        z-index: 0;
        pointer-events: none;
    }
    .radar-ring {
        position: absolute;
        top: 50%; left: 50%;
        border-radius: 50%;
        border: 1px solid var(--zt-primary-border);
        transform: translate(-50%, -50%);
        animation: radarRotate 26s linear infinite;
    }
    .radar-ring--1 { width: 100%; height: 100%; animation-duration: 26s; }
    .radar-ring--2 { width: 78%;  height: 78%;  animation-duration: 22s; animation-direction: reverse; }
    .radar-ring--3 { width: 56%;  height: 56%;  animation-duration: 18s; }
    .radar-ring--4 { width: 34%;  height: 34%;  animation-duration: 16s; animation-direction: reverse; }
    .radar-ring--5 { width: 16%;  height: 16%;  animation-duration: 14s; }
    html.dark-mode .radar-ring { border-color: rgba(61, 169, 252, .08); }
    @keyframes radarRotate {
        0%   { transform: translate(-50%, -50%) rotate(0deg) scale(1); opacity: .16; }
        50%  { transform: translate(-50%, -50%) rotate(180deg) scale(.96); opacity: .06; }
        100% { transform: translate(-50%, -50%) rotate(360deg) scale(1); opacity: .16; }
    }

    /* ============================================================
       L5 — FLOATING SECURITY ICONS
       ============================================================ */
    .float-icon {
        position: fixed;
        z-index: 0;
        pointer-events: none;
        font-size: 96px;
        opacity: .07;
        color: var(--zt-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        animation: securityFloat 18s ease-in-out infinite;
    }
    .float-icon i { position: relative; z-index: 2; text-shadow: 0 0 18px currentColor, 0 0 36px currentColor; }
    .float-icon::before {
        content: '';
        position: absolute;
        left: 50%; top: 50%;
        transform: translate(-50%, -50%);
        width: 115%; height: 115%;
        border-radius: 50%;
        background: radial-gradient(circle, currentColor 0%, transparent 68%);
        opacity: .2;
        filter: blur(12px);
        z-index: 1;
    }
    .float-icon::after {
        content: '';
        position: absolute;
        left: 50%; top: 52%;
        transform: translateX(-50%);
        width: 170px; height: 260px;
        background: linear-gradient(to bottom, currentColor 0%, transparent 92%);
        filter: blur(18px);
        opacity: .1;
        clip-path: polygon(42% 0%, 58% 0%, 100% 100%, 0% 100%);
        z-index: 0;
        animation: projectorLight 7s ease-in-out infinite;
    }
    html.dark-mode .float-icon { opacity: .045; }
    @keyframes projectorLight {
        0%, 100% { opacity: .12; transform: translateX(-50%) scaleY(.9) scaleX(1); }
        50%      { opacity: .32; transform: translateX(-48%) scaleY(1.15) scaleX(1.04); }
    }
    @keyframes securityFloat {
        0%, 100% { transform: translateY(0) rotate(0deg) scale(1); }
        50%      { transform: translateY(-18px) rotate(5deg) scale(1.04); }
    }
    .float-icon--1 { top: 12%;  left: 7%;   font-size: 120px; color: var(--zt-primary-2); }
    .float-icon--2 { top: 18%;  right: 9%;  font-size: 100px; animation-delay: 2s;  color: var(--zt-primary); }
    .float-icon--3 { bottom: 14%; left: 11%; font-size: 110px; animation-delay: 4s;  color: var(--zt-green); }
    .float-icon--4 { bottom: 18%; right: 7%; font-size: 96px;  animation-delay: 1s;  color: var(--zt-primary-3); }
    .float-icon--5 { top: 44%;   left: 3%;   font-size: 84px;  animation-delay: 3s;  color: var(--zt-green-2); }
    .float-icon--6 { top: 40%;   right: 3%;  font-size: 90px;  animation-delay: 5s;  color: var(--zt-primary-2); }
    .float-icon--7 { top: 5%;    left: 44%;  font-size: 82px;  animation-delay: 6s;  color: var(--zt-green); }
    .float-icon--8 { bottom: 5%; left: 45%;  font-size: 88px;  animation-delay: 2.5s; color: var(--zt-primary-3); }

    /* ============================================================
       L6 — SCANLINE SWEEP + HUD CORNERS
       ============================================================ */
    .scanline {
        position: fixed;
        left: 0; right: 0;
        height: 240px;
        z-index: 1;
        pointer-events: none;
        background: linear-gradient(to bottom, transparent, rgba(37, 99, 235, .05), transparent);
        animation: scanSweep 14s linear infinite;
    }
    html.dark-mode .scanline { background: linear-gradient(to bottom, transparent, rgba(61, 169, 252, .03), transparent); }
    @keyframes scanSweep { 0% { top: -240px; } 100% { top: 100%; } }

    .hud-bracket {
        position: fixed;
        width: 34px; height: 34px;
        z-index: 3;
        pointer-events: none;
        opacity: .35;
    }
    html.dark-mode .hud-bracket { opacity: .22; }
    .hud-bracket--tl { top: 22px; left: 22px;  border-top: 1px solid var(--zt-primary); border-left: 1px solid var(--zt-primary); }
    .hud-bracket--tr { top: 22px; right: 22px; border-top: 1px solid var(--zt-primary); border-right: 1px solid var(--zt-primary); }
    .hud-bracket--bl { bottom: 22px; left: 22px;  border-bottom: 1px solid var(--zt-primary); border-left: 1px solid var(--zt-primary); }
    .hud-bracket--br { bottom: 22px; right: 22px; border-bottom: 1px solid var(--zt-primary); border-right: 1px solid var(--zt-primary); }

    /* ============================================================
       THEME TOGGLE (pojok kanan atas)
       ============================================================ */
    .zt-theme-toggle {
        position: fixed !important;
        top: 22px !important;
        right: 22px !important;
        z-index: 10 !important;
        width: 42px; height: 42px;
        display: inline-flex; align-items: center; justify-content: center;
        border-radius: 12px;
        background: var(--zt-card) !important;
        border: 1px solid var(--zt-border) !important;
        box-shadow: var(--zt-shadow) !important;
        color: var(--zt-text-2) !important;
        transition: all .25s ease !important;
    }
    .zt-theme-toggle:hover {
        color: var(--zt-primary) !important;
        transform: translateY(-2px);
        box-shadow: var(--zt-shadow-lg) !important;
        border-color: var(--zt-primary-border) !important;
    }
    .zt-theme-toggle i { font-size: 16px; }

    /* ============================================================
       L7 — CARD WRAP + GRADIENT BORDER GLOW
       ============================================================ */
    .card-wrap {
        position: relative;
        border-radius: var(--zt-radius);
        width: 100%;
        max-width: 360px;
        z-index: 2;
        transition: transform .35s cubic-bezier(.4, 0, .2, 1);
    }
    .card-wrap::before {
        content: '';
        position: absolute;
        inset: -1.5px;
        border-radius: calc(var(--zt-radius) + 2px);
        background: conic-gradient(
            from 0deg at 50% 50%,
            transparent 0deg, transparent 210deg,
            var(--zt-primary) 235deg, var(--zt-green) 258deg,
            var(--zt-primary-2) 282deg, transparent 305deg, transparent 360deg
        );
        animation: encryptBorder 9s linear infinite;
        z-index: 0;
        opacity: .32;
        transition: opacity .4s ease;
    }
    .card-wrap::after {
        content: '';
        position: absolute;
        inset: -1.5px;
        border-radius: calc(var(--zt-radius) + 2px);
        background: conic-gradient(
            from 180deg at 50% 50%,
            transparent 0deg, transparent 210deg,
            rgba(37, 99, 235, .4) 235deg, rgba(22, 163, 74, .35) 258deg,
            rgba(37, 99, 235, .3) 282deg, transparent 305deg, transparent 360deg
        );
        animation: encryptBorder 9s linear infinite reverse;
        z-index: 0;
        opacity: .18;
        transition: opacity .4s ease;
    }
    html.dark-mode .card-wrap::before {
        background: conic-gradient(
            from 0deg at 50% 50%,
            transparent 0deg, transparent 210deg,
            #2EA8FF 235deg, #34d399 258deg, #00E5FF 282deg, transparent 305deg, transparent 360deg
        );
    }
    .card-wrap:hover { transform: translateY(-4px); }
    .card-wrap:hover::before { opacity: .5; }
    .card-wrap:hover::after { opacity: .3; }
    @keyframes encryptBorder { to { transform: rotate(360deg); } }

    /* Glass card */
    .card-security {
        position: relative;
        z-index: 1;
        background: rgba(255, 255, 255, .72);
        backdrop-filter: blur(26px) saturate(170%);
        -webkit-backdrop-filter: blur(26px) saturate(170%);
        border: 1px solid var(--zt-border);
        border-radius: var(--zt-radius);
        padding: 20px 18px 16px;
        overflow: hidden;
        box-shadow:
            var(--zt-shadow-glow),
            inset 0 1px 0 rgba(255, 255, 255, .7);
        animation: secureEntry .7s cubic-bezier(.16, 1, .3, 1) both;
        transition: box-shadow .4s ease, border-color .4s ease, background .4s ease;
    }
    html.dark-mode .card-security {
        background: rgba(10, 20, 33, .72);
        border-color: var(--zt-border);
        box-shadow: var(--zt-shadow-glow), inset 0 1px 0 rgba(255, 255, 255, .04);
    }
    .card-wrap:hover .card-security {
        border-color: var(--zt-primary-border);
        box-shadow:
            0 0 0 1px rgba(37, 99, 235, .08), 0 30px 90px -22px rgba(37, 99, 235, .28),
            inset 0 1px 0 rgba(255, 255, 255, .8);
    }
    @keyframes secureEntry {
        0%   { opacity: 0; transform: translateY(34px) scale(.96); filter: blur(8px); }
        100% { opacity: 1; transform: translateY(0) scale(1); filter: blur(0); }
    }

    /* ============================================================
       WATERMARK — logo madrasah (foto buram, opacity rendah,
       diperkecil & ditengah sebagai watermark di belakang konten)
       ============================================================ */
    .zt-watermark {
        position: absolute;
        inset: 0;
        margin: auto;
        z-index: -1;
        width: 56%;
        height: 56%;
        object-fit: contain;
        opacity: .28;
        filter: blur(1px) saturate(.8);
        pointer-events: none;
        user-select: none;
        -webkit-user-drag: none;
        animation: ztFadeIn .9s ease backwards;
        animation-delay: .08s;
    }
    html.dark-mode .zt-watermark { opacity: .18; }

    /* ============================================================
       ENTRANCE ANIMATIONS — animasi berurutan untuk tiap elemen
       (fill `backwards`: selama delay tampil frame awal, setelah
       selesai kembali ke state natural agar hover tetap berfungsi)
       ============================================================ */
    @keyframes ztFadeUp     { 0% { opacity: 0; transform: translateY(16px); } 100% { opacity: 1; transform: translateY(0); } }
    @keyframes ztFadeIn     { 0% { opacity: 0; } 100% { opacity: 1; } }
    @keyframes ztPop        { 0% { opacity: 0; transform: scale(.6); } 100% { opacity: 1; transform: scale(1); } }
    @keyframes ztSlideDown  { 0% { opacity: 0; transform: translateY(-12px); } 100% { opacity: 1; transform: translateY(0); } }

    .icon-shield-wrap { animation: ztPop .5s cubic-bezier(.34, 1.56, .64, 1) backwards; animation-delay: .06s; }
    .zt-badge         { animation: ztSlideDown .45s cubic-bezier(.16, 1, .3, 1) backwards; animation-delay: .14s; }
    .header-title     { animation: ztFadeUp .5s cubic-bezier(.16, 1, .3, 1) backwards; animation-delay: .2s; }
    .header-subtitle  { animation: ztFadeIn .55s ease backwards; animation-delay: .27s; }
    .status-badge     { animation: ztFadeUp .5s cubic-bezier(.16, 1, .3, 1) backwards; animation-delay: .33s; }
    .otp-panel .zt-label { animation: ztFadeIn .4s ease backwards; animation-delay: .42s; }
    .otp-box          { animation: ztPop .4s cubic-bezier(.34, 1.56, .64, 1) backwards; }
    .zt-hint          { animation: ztFadeIn .4s ease backwards; animation-delay: .72s; }
    .toggle-wrap      { animation: ztFadeUp .5s cubic-bezier(.16, 1, .3, 1) backwards; animation-delay: .82s; }
    .btn-verify       { animation: ztFadeUp .5s cubic-bezier(.16, 1, .3, 1) backwards; animation-delay: .92s; }
    .footer-auth      { animation: ztFadeIn .5s ease backwards; animation-delay: 1.02s; }
    .recovery-panel.is-active .zt-recovery,
    .recovery-panel.is-active .zt-label,
    .recovery-panel.is-active .zt-hint { animation: ztPop .35s cubic-bezier(.34, 1.56, .64, 1) backwards; }
    .recovery-panel.is-active .zt-label { animation-name: ztFadeIn; animation-delay: .05s; }
    .recovery-panel.is-active .zt-recovery { animation-delay: .1s; }
    .recovery-panel.is-active .zt-hint { animation-delay: .16s; }

    /* ============================================================
       CARD HEADER — shield, badge, title, status
       ============================================================ */
    .header-wrap { text-align: center; margin-bottom: 14px; }

    .icon-shield-wrap {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 44px; height: 44px;
        margin-bottom: 8px;
        border-radius: 13px;
        background: var(--zt-grad-security);
        color: #fff;
        font-size: 20px;
        box-shadow: 0 12px 26px -10px rgba(22, 163, 74, .55);
    }
    .icon-shield-wrap::before,
    .icon-shield-wrap::after {
        content: '';
        position: absolute;
        inset: -5px;
        border-radius: 18px;
        border: 1.5px solid var(--zt-green-border);
        animation: shieldRing 3s ease-in-out infinite;
    }
    .icon-shield-wrap::after { inset: -10px; animation-delay: 1s; }
    html.dark-mode .icon-shield-wrap { box-shadow: 0 14px 30px -10px rgba(52, 211, 153, .45); }
    @keyframes shieldRing {
        0%, 100% { transform: scale(1); opacity: .5; }
        50%      { transform: scale(1.12); opacity: 0; }
    }

    .zt-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 9px;
        border-radius: 50px;
        font-size: 8.5px;
        font-weight: 700;
        letter-spacing: 1.4px;
        text-transform: uppercase;
        color: var(--zt-primary);
        background: var(--zt-primary-soft);
        border: 1px solid var(--zt-primary-border);
        margin-bottom: 5px;
    }

    .header-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--zt-text);
        letter-spacing: -.3px;
        margin-bottom: 3px;
    }
    .header-subtitle {
        font-size: 11px;
        color: var(--zt-text-2);
        line-height: 1.5;
        max-width: 270px;
        margin: 0 auto 8px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 11px;
        border-radius: 50px;
        font-size: 9.5px;
        font-weight: 600;
        letter-spacing: .3px;
        color: var(--zt-green);
        background: var(--zt-green-soft);
        border: 1px solid var(--zt-green-border);
    }
    .status-badge .pulse-dot {
        width: 7px; height: 7px;
        border-radius: 50%;
        background: var(--zt-green);
        box-shadow: 0 0 10px var(--zt-green);
        animation: dotPulse 1.6s ease-in-out infinite;
    }
    @keyframes dotPulse { 0%, 100% { opacity: 1; } 50% { opacity: .3; } }

    /* ============================================================
       FORM
       ============================================================ */
    .form-section { margin-top: 12px; }

    .zt-label {
        display: block;
        font-size: 9.5px;
        font-weight: 700;
        color: var(--zt-text-2);
        margin-bottom: 5px;
        letter-spacing: .6px;
        text-transform: uppercase;
    }

    /* --- OTP boxes --- */
    .otp-panel, .recovery-panel {
        transition: opacity .28s ease, transform .28s ease, visibility .28s ease;
    }
    .otp-panel.is-hidden {
        opacity: 0;
        transform: translateX(-14px);
        visibility: hidden;
        position: absolute;
        pointer-events: none;
    }
    .recovery-panel {
        opacity: 0;
        transform: translateX(14px);
        visibility: hidden;
        position: absolute;
        top: 0; left: 0; right: 0;
        pointer-events: none;
    }
    .recovery-panel.is-active {
        opacity: 1;
        transform: translateX(0);
        visibility: visible;
        position: static;
        pointer-events: auto;
    }

    .otp-row {
        display: flex;
        justify-content: center;
        gap: 6px;
    }
    .otp-box {
        width: 40px; height: 44px;
        border-radius: 9px;
        border: 1.5px solid var(--zt-border);
        background: var(--zt-card);
        text-align: center;
        font-size: 21px;
        font-weight: 700;
        font-family: var(--zt-font);
        color: var(--zt-text);
        caret-color: var(--zt-primary);
        box-shadow: var(--zt-shadow);
        transition: border-color .2s ease, box-shadow .2s ease, transform .15s ease, background .2s ease;
        outline: none;
    }
    .otp-box:hover { border-color: var(--zt-primary-border); }
    .otp-box:focus {
        border-color: var(--zt-primary);
        box-shadow: 0 0 0 3px var(--zt-primary-soft), 0 8px 22px -8px var(--zt-primary-border);
        transform: translateY(-1px);
        background: #fff;
    }
    html.dark-mode .otp-box:focus { background: var(--zt-card); box-shadow: 0 0 0 3px rgba(61, 169, 252, .14), 0 8px 22px -8px rgba(61, 169, 252, .3); }
    .otp-box.is-filled {
        border-color: var(--zt-green-border);
        animation: popIn .18s cubic-bezier(.34, 1.56, .64, 1);
    }
    .otp-box.is-invalid {
        border-color: var(--zt-red);
        box-shadow: 0 0 0 3px var(--zt-red-soft);
        animation: shakeX .4s ease;
    }
    @keyframes popIn {
        0% { transform: scale(.9); }
        100% { transform: scale(1); }
    }
    @keyframes shakeX {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        50% { transform: translateX(5px); }
        75% { transform: translateX(-3px); }
    }

    .zt-hint {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        font-size: 9.5px;
        color: var(--zt-text-3);
        margin-top: 6px;
        text-align: center;
    }
    .zt-hint i { color: var(--zt-primary); }

    /* --- Recovery --- */
    .zt-recovery {
        width: 100%;
        height: 44px !important;
        border-radius: 9px !important;
        border: 1.5px solid var(--zt-border) !important;
        background: var(--zt-card) !important;
        color: var(--zt-text) !important;
        font-family: 'Consolas', 'JetBrains Mono', 'Courier New', monospace !important;
        font-size: 15px !important;
        font-weight: 600 !important;
        letter-spacing: 1.5px;
        text-align: center;
        text-transform: lowercase;
        box-shadow: var(--zt-shadow) !important;
        caret-color: var(--zt-primary);
        transition: all .25s ease;
        outline: none;
    }
    .zt-recovery::placeholder { color: var(--zt-text-3); letter-spacing: 1.5px; }
    .zt-recovery:focus {
        border-color: var(--zt-primary) !important;
        box-shadow: 0 0 0 3px var(--zt-primary-soft), 0 8px 22px -8px var(--zt-primary-border) !important;
    }
    html.dark-mode .zt-recovery:focus { box-shadow: 0 0 0 3px rgba(61, 169, 252, .14), 0 8px 22px -8px rgba(61, 169, 252, .3) !important; }

    /* --- Toggle switch --- */
    .toggle-wrap {
        display: flex;
        align-items: center;
        gap: 9px;
        margin: 10px 0;
        cursor: pointer;
        user-select: none;
        border-radius: 10px;
        padding: 4px 2px;
    }
    .toggle-wrap:focus-visible {
        outline: 2px solid var(--zt-primary-3);
        outline-offset: 2px;
    }
    .toggle-track {
        width: 38px; height: 22px;
        border-radius: 12px;
        background: rgba(15, 23, 42, .12);
        border: 1.5px solid var(--zt-border);
        position: relative;
        transition: all .3s cubic-bezier(.4, 0, .2, 1);
        flex-shrink: 0;
    }
    html.dark-mode .toggle-track { background: rgba(255, 255, 255, .08); border-color: var(--zt-border); }
    .toggle-track .toggle-thumb {
        width: 16px; height: 16px;
        border-radius: 50%;
        background: var(--zt-text-3);
        position: absolute; top: 2px; left: 2px;
        transition: all .3s cubic-bezier(.4, 0, .2, 1);
    }
    .toggle-track.active {
        background: var(--zt-primary-soft);
        border-color: var(--zt-primary);
        box-shadow: 0 0 16px var(--zt-primary-border);
    }
    html.dark-mode .toggle-track.active { background: rgba(61, 169, 252, .12); }
    .toggle-track.active .toggle-thumb {
        left: 17px;
        background: var(--zt-primary);
        box-shadow: 0 0 12px var(--zt-primary-border);
    }
    .toggle-label { font-size: 11px; color: var(--zt-text-2); font-weight: 500; transition: color .2s ease; }
    .toggle-label.active-label { color: var(--zt-primary); font-weight: 600; }

    /* --- Alerts (glass notification) --- */
    .zt-alert {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 9px 11px;
        border-radius: 9px;
        font-size: 11px;
        line-height: 1.5;
        margin-bottom: 10px;
        border-left: 3px solid;
        animation: alertDown .45s cubic-bezier(.16, 1, .3, 1) both;
    }
    .zt-alert i { flex-shrink: 0; margin-top: 1px; font-size: 13px; }
    @keyframes alertDown {
        0% { opacity: 0; transform: translateY(-14px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .zt-alert--error   { background: var(--zt-red-soft);   border-left-color: var(--zt-red);   color: var(--zt-red); }
    .zt-alert--success { background: var(--zt-green-soft); border-left-color: var(--zt-green); color: var(--zt-green); }
    .zt-alert--warning { background: var(--zt-amber-soft); border-left-color: var(--zt-amber); color: var(--zt-amber); }
    .zt-alert--info    { background: var(--zt-sky-soft);   border-left-color: var(--zt-sky);   color: var(--zt-sky); }

    /* ============================================================
       BUTTON — gradient emerald→blue, ripple, loading
       ============================================================ */
    .btn-verify {
        position: relative;
        overflow: hidden;
        width: 100%;
        height: 42px;
        border: none !important;
        border-radius: 9px !important;
        background: var(--zt-grad-security) !important;
        color: #fff !important;
        font-size: 13px;
        font-weight: 700;
        font-family: var(--zt-font);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        letter-spacing: .3px;
        transition: transform .25s cubic-bezier(.4, 0, .2, 1), box-shadow .25s ease, filter .25s ease;
        box-shadow: 0 10px 28px -8px rgba(22, 163, 74, .55), 0 0 50px rgba(37, 99, 235, .12);
    }
    html.dark-mode .btn-verify { color: #001019 !important; box-shadow: 0 10px 28px -8px rgba(52, 211, 153, .5); }
    .btn-verify::before {
        content: '';
        position: absolute;
        top: 0; left: -120%;
        width: 60%; height: 100%;
        background: linear-gradient(100deg, transparent, rgba(255, 255, 255, .35), transparent);
        transform: skewX(-18deg);
        transition: left .7s ease;
        pointer-events: none;
    }
    .btn-verify:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 16px 40px -10px rgba(22, 163, 74, .65), 0 0 70px rgba(37, 99, 235, .16);
        filter: saturate(1.05);
    }
    .btn-verify:hover:not(:disabled)::before { left: 160%; }
    .btn-verify:active:not(:disabled) { transform: translateY(0) scale(.99); }
    .btn-verify:focus-visible {
        outline: 2px solid var(--zt-primary-3);
        outline-offset: 3px;
    }
    .btn-verify:disabled { opacity: .85; cursor: not-allowed; }
    .btn-verify .spinner-load {
        display: none;
        width: 15px; height: 15px;
        border: 2.5px solid rgba(255, 255, 255, .25);
        border-top-color: #fff;
        border-radius: 50%;
        animation: spinLoader .7s linear infinite;
    }
    html.dark-mode .btn-verify .spinner-load { border-color: rgba(0, 16, 25, .25); border-top-color: #001019; }
    .btn-verify.loading .spinner-load { display: inline-block; }
    @keyframes spinLoader { to { transform: rotate(360deg); } }
    .btn-verify.loading::after {
        content: '';
        position: absolute;
        left: 0; bottom: 0;
        height: 3px;
        width: 100%;
        background: linear-gradient(90deg, transparent, #fff, transparent);
        background-size: 200% 100%;
        animation: progressShimmer 1.2s linear infinite;
    }
    html.dark-mode .btn-verify.loading::after { background: linear-gradient(90deg, transparent, #001019, transparent); background-size: 200% 100%; }
    @keyframes progressShimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }

    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, .4);
        transform: scale(0);
        animation: rippleAnim .6s ease-out forwards;
        pointer-events: none;
    }
    html.dark-mode .ripple { background: rgba(0, 16, 25, .3); }
    @keyframes rippleAnim {
        to { transform: scale(3.2); opacity: 0; }
    }

    /* ============================================================
       CARD FOOTER
       ============================================================ */
    .footer-auth {
        margin-top: 12px;
        padding-top: 10px;
        border-top: 1px solid var(--zt-border-soft);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        flex-wrap: wrap;
    }
    .footer-auth a {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: var(--zt-text-2) !important;
        font-size: 11px;
        font-weight: 600;
        text-decoration: none;
        transition: color .2s ease, transform .2s ease;
        padding: 6px 10px;
        border-radius: 8px;
    }
    .footer-auth a:hover { color: var(--zt-primary) !important; transform: translateX(-2px); background: var(--zt-primary-soft); }
    .footer-meta {
        display: flex;
        align-items: center;
        gap: 7px;
        font-size: 9.5px;
        color: var(--zt-text-3);
        font-weight: 500;
    }
    .footer-meta .dot { opacity: .5; }
    .footer-meta .powered {
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .footer-meta .powered i { color: var(--zt-green); font-size: 10px; }

    /* ============================================================
       RESPONSIVE
       ============================================================ */
    @media (max-width: 480px) {
        .page-wrap { padding: 16px; }
        .card-security { padding: 18px 14px 14px; }
        .header-title { font-size: 17px; }
        .otp-row { gap: 6px; }
        .otp-box { width: 38px; height: 42px; font-size: 19px; }
        .btn-verify { height: 42px; }
        .footer-auth { justify-content: center; }
        .hologram-shield { width: 340px; height: 400px; opacity: .04; }
        .radar { width: 380px; height: 380px; }
        .ambient-halo { width: 460px; height: 460px; }
        .float-icon { font-size: 60px !important; }
        .hud-bracket { width: 24px; height: 24px; }
    }

    /* ============================================================
       ACCESSIBILITY / MOTION
       ============================================================ */
    :focus-visible { outline: 2px solid var(--zt-primary-3); outline-offset: 2px; }
    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after {
            animation-duration: .001s !important;
            animation-iteration-count: 1 !important;
            animation-delay: 0s !important;
            transition-duration: .001s !important;
        }
    }
</style>

<div class="page-wrap">

    {{-- Theme toggle (syncs via component.script → localStorage: theme-preference) --}}
    <a href="#" class="theme-toggle zt-theme-toggle" title="Ganti tema" aria-label="Ganti tema">
        <i class="fas fa-moon"></i>
    </a>

    {{-- L1: Encryption grid --}}
    <div class="bg-grid"></div>

    {{-- L1b: Vignette + depth --}}
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

    {{-- L3: Hologram shield + scan + cone projection --}}
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

    {{-- L4: Security radar --}}
    <div class="radar">
        <div class="radar-ring radar-ring--1"></div>
        <div class="radar-ring radar-ring--2"></div>
        <div class="radar-ring radar-ring--3"></div>
        <div class="radar-ring radar-ring--4"></div>
        <div class="radar-ring radar-ring--5"></div>
    </div>

    {{-- L5: Floating security icons --}}
    <div class="float-icon float-icon--1"><i class="bi bi-shield-check"></i></div>
    <div class="float-icon float-icon--2"><i class="bi bi-lock"></i></div>
    <div class="float-icon float-icon--3"><i class="bi bi-fingerprint"></i></div>
    <div class="float-icon float-icon--4"><i class="bi bi-key"></i></div>
    <div class="float-icon float-icon--5"><i class="bi bi-eye"></i></div>
    <div class="float-icon float-icon--6"><i class="bi bi-shield-lock"></i></div>
    <div class="float-icon float-icon--7"><i class="bi bi-person-check"></i></div>
    <div class="float-icon float-icon--8"><i class="bi bi-patch-check"></i></div>

    {{-- L6: Scanline + HUD framing --}}
    <div class="scanline"></div>
    <div class="hud-bracket hud-bracket--tl"></div>
    <div class="hud-bracket hud-bracket--tr"></div>
    <div class="hud-bracket hud-bracket--bl"></div>
    <div class="hud-bracket hud-bracket--br"></div>

    {{-- L7: Main card --}}
    <div class="card-wrap">
        <div class="card-security">

            {{-- Watermark: logo madrasah (foto buram, opacity rendah) --}}
            <img src="{{ asset('img/logo2.png') }}" alt="" aria-hidden="true"
                class="zt-watermark" draggable="false">

            {{-- Header: shield + badge + title + status --}}
            <div class="header-wrap">
                <div class="icon-shield-wrap">
                    <i class="bi bi-shield-check" aria-hidden="true"></i>
                </div>
                <div>
                    <span class="zt-badge"><i class="bi bi-shield-lock"></i> Zero Trust</span>
                    <h1 class="header-title">Verifikasi Keamanan</h1>
                    <p class="header-subtitle">Masukkan kode autentikasi untuk melanjutkan ke Dashboard.</p>
                    <span class="status-badge">
                        <span class="pulse-dot"></span> Koneksi Aman &middot; 256-bit Encryption
                    </span>
                </div>
            </div>

            <form method="POST" action="{{ route('2fa.verify') }}" id="verifyForm" novalidate>
                @csrf

                {{-- Single real field: TOTP atau recovery dikirim lewat field yang sama (kontrak backend) --}}
                <input type="hidden" name="one_time_password" id="otpValue">

                {{-- Mode 1: OTP boxes --}}
                <div class="form-section otp-panel" id="otpPanel">
                    <label class="zt-label" for="otpBox-0">Kode Autentikasi</label>
                    <div class="otp-row" role="group" aria-label="Masukkan kode autentikasi 6 digit">
                        @for($i = 0; $i < 6; $i++)
                        <input type="text" class="otp-box" id="otpBox-{{ $i }}" inputmode="numeric"
                            autocomplete="one-time-code" maxlength="1" pattern="[0-9]"
                            aria-label="Digit ke-{{ $i + 1 }}"
                            data-index="{{ $i }}"
                            style="animation-delay: {{ number_format(0.46 + $i * 0.05, 2) }}s">
                        @endfor
                    </div>
                    <div class="zt-hint">
                        <i class="bi bi-info-circle"></i>
                        Buka aplikasi Google Authenticator lalu masukkan 6 digit kode
                    </div>
                </div>

                {{-- Mode 2: Recovery code (cross-dissolve) --}}
                <div class="form-section recovery-panel" id="recoveryPanel">
                    <label class="zt-label" for="recoveryInput">Kode Recovery</label>
                    <input type="text" id="recoveryInput" class="form-control zt-recovery"
                        placeholder="1a2b3c4d5e" autocomplete="off" spellcheck="false"
                        aria-describedby="recoveryHint">
                    <div class="zt-hint" id="recoveryHint">
                        <i class="bi bi-key"></i>
                        Gunakan salah satu kode cadangan yang Anda simpan saat mengaktifkan 2FA
                    </div>
                </div>

                {{-- Toggle OTP ⇄ Recovery --}}
                <div class="toggle-wrap" id="toggleRecovery" role="switch" aria-checked="false"
                    tabindex="0" aria-label="Gunakan Kode Recovery">
                    <div class="toggle-track" id="toggleTrack"><div class="toggle-thumb"></div></div>
                    <span class="toggle-label" id="toggleLabel">Gunakan Kode Recovery</span>
                </div>

                {{-- Session feedback --}}
                @if (session()->has('error'))
                <div class="zt-alert zt-alert--error" role="alert">
                    <i class="bi bi-x-circle-fill"></i>
                    <span>{{ session('error') }}</span>
                </div>
                @endif

                @if (session()->has('success'))
                <div class="zt-alert zt-alert--success" role="status">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>{{ session('success') }}</span>
                </div>
                @endif

                @if ($errors->any())
                <div class="zt-alert zt-alert--error" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
                @endif

                <button type="submit" class="btn-verify" id="verifyBtn">
                    <i class="bi bi-shield-lock-fill" id="verifyIcon"></i>
                    <span id="verifyText">Verifikasi Identitas</span>
                    <span class="spinner-load" aria-hidden="true"></span>
                </button>
            </form>

            {{-- Card footer --}}
            <div class="footer-auth">
                <a href="{{ route('login') }}">
                    <i class="bi bi-arrow-left"></i>
                    Kembali ke Login
                </a>
                <div class="footer-meta">
                    <span>Zero Trust Security</span>
                    <span class="dot">&middot;</span>
                    <span class="powered"><i class="bi bi-google"></i> Google Authenticator</span>
                </div>
            </div>

        </div>
    </div>

</div>

<script>
    (function() {
        'use strict';
        var form = document.getElementById('verifyForm');
        var otpValue = document.getElementById('otpValue');
        var otpPanel = document.getElementById('otpPanel');
        var recoveryPanel = document.getElementById('recoveryPanel');
        var recoveryInput = document.getElementById('recoveryInput');
        var toggle = document.getElementById('toggleRecovery');
        var toggleTrack = document.getElementById('toggleTrack');
        var toggleLabel = document.getElementById('toggleLabel');
        var btn = document.getElementById('verifyBtn');
        var btnIcon = document.getElementById('verifyIcon');
        var btnText = document.getElementById('verifyText');
        var boxes = Array.prototype.slice.call(document.querySelectorAll('.otp-box'));
        var isRecovery = false;

        /* ---- toggle OTP ⇄ recovery ---- */
        function setRecoveryMode(active) {
            isRecovery = active;
            toggleTrack.classList.toggle('active', active);
            toggleLabel.classList.toggle('active-label', active);
            toggle.setAttribute('aria-checked', active ? 'true' : 'false');
            otpPanel.classList.toggle('is-hidden', active);
            recoveryPanel.classList.toggle('is-active', active);
            if (active) {
                recoveryInput.focus();
            } else {
                var firstEmpty = boxes.find(function(b) { return !b.value; }) || boxes[0];
                firstEmpty.focus();
            }
        }

        function toggleClick() { setRecoveryMode(!isRecovery); }
        toggle.addEventListener('click', toggleClick);
        toggle.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                toggleClick();
            }
        });

        /* ---- OTP boxes: auto-advance, backspace, arrows, paste ---- */
        function moveFocus(index, offset) {
            var next = boxes[index + offset];
            if (next) next.focus();
        }

        function syncOtpValue() {
            otpValue.value = boxes.map(function(b) { return b.value; }).join('');
        }

        boxes.forEach(function(box, i) {
            box.addEventListener('input', function() {
                var v = box.value.replace(/[^0-9]/g, '');
                box.value = v;
                if (v) {
                    box.classList.add('is-filled');
                    box.classList.remove('is-invalid');
                    if (i < 5) boxes[i + 1].focus();
                } else {
                    box.classList.remove('is-filled');
                }
                syncOtpValue();
            });

            box.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace') {
                    if (!box.value && i > 0) {
                        e.preventDefault();
                        boxes[i - 1].value = '';
                        boxes[i - 1].classList.remove('is-filled');
                        boxes[i - 1].focus();
                        syncOtpValue();
                    }
                } else if (e.key === 'ArrowLeft') {
                    e.preventDefault();
                    moveFocus(i, -1);
                } else if (e.key === 'ArrowRight') {
                    e.preventDefault();
                    moveFocus(i, 1);
                } else if (e.key.length === 1 && !/[0-9]/.test(e.key)) {
                    e.preventDefault();
                }
            });

            box.addEventListener('paste', function(e) {
                e.preventDefault();
                var paste = (e.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '');
                if (!paste) return;
                var from = i;
                for (var k = 0; k < paste.length && from + k < 6; k++) {
                    boxes[from + k].value = paste[k];
                    boxes[from + k].classList.add('is-filled');
                }
                syncOtpValue();
                var target = from + paste.length < 6 ? boxes[from + paste.length] : boxes[5];
                target.focus();
            });
        });

        /* ---- submit: isi one_time_password sesuai mode ---- */
        form.addEventListener('submit', function(e) {
            var value = '';
            if (isRecovery) {
                value = recoveryInput.value.replace(/[^a-fA-F0-9]/g, '').toLowerCase();
                if (!value) {
                    e.preventDefault();
                    recoveryInput.classList.add('is-invalid');
                    recoveryInput.focus();
                    showInlineError(recoveryPanel, 'Masukkan kode recovery terlebih dahulu.');
                    return;
                }
                recoveryInput.classList.remove('is-invalid');
            } else {
                boxes.forEach(function(b) {
                    if (b.classList.contains('is-invalid')) b.classList.remove('is-invalid');
                });
                value = otpValue.value;
                if (value.length < 6) {
                    e.preventDefault();
                    var firstEmpty = boxes.find(function(b) { return !b.value; });
                    if (firstEmpty) {
                        firstEmpty.classList.add('is-invalid');
                        firstEmpty.focus();
                    }
                    showInlineError(otpPanel, 'Masukkan 6 digit kode dari Google Authenticator.');
                    return;
                }
            }
            otpValue.value = value;

            btn.classList.add('loading');
            btn.disabled = true;
            btnIcon.style.display = 'none';
            btnText.textContent = 'Memverifikasi...';
        });

        /* ---- inline error hint ---- */
        var inlineErr = null;
        function showInlineError(container, message) {
            if (inlineErr) inlineErr.remove();
            inlineErr = document.createElement('div');
            inlineErr.className = 'zt-alert zt-alert--error';
            inlineErr.setAttribute('role', 'alert');
            inlineErr.style.marginTop = '12px';
            inlineErr.innerHTML = '<i class="bi bi-exclamation-circle-fill"></i><span></span>';
            inlineErr.querySelector('span').textContent = message;
            container.appendChild(inlineErr);
            setTimeout(function() { if (inlineErr) { inlineErr.remove(); inlineErr = null; } }, 4000);
        }

        /* ---- ripple ---- */
        btn.addEventListener('pointerdown', function(e) {
            if (btn.disabled) return;
            var rect = btn.getBoundingClientRect();
            var size = Math.max(rect.width, rect.height);
            var ripple = document.createElement('span');
            ripple.className = 'ripple';
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = (e.clientX - rect.left - size / 2) + 'px';
            ripple.style.top = (e.clientY - rect.top - size / 2) + 'px';
            btn.appendChild(ripple);
            ripple.addEventListener('animationend', function() { ripple.remove(); });
        });

        /* ---- init: focus kotak pertama ---- */
        boxes[0].focus();
    })();
</script>

@endsection
