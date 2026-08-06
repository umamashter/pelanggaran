<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>MIS Nurul Ulum</title>
    @include('component.head')
    <meta content="" name="description">
    <meta content="" name="keywords">

    <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../assets/vendor/animate.css/animate.min.css" rel="stylesheet">
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="../assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="../assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">

    <style>
        /* ============================================================
           MIS NURUL ULUM — LANDING PAGE DESIGN SYSTEM (blue)
           Shares the dashboard palette: primary #2563eb, neutral grays,
           white cards, glassmorphism, radius 18-22px.
           ============================================================ */
        :root {
            --ld-primary: #2563eb;
            --ld-primary-2: #3b82f6;
            --ld-primary-3: #60a5fa;
            --ld-primary-dark: #1d4ed8;
            --ld-primary-soft: #eff6ff;
            --ld-primary-border: rgba(37, 99, 235, .22);
            --ld-grad: linear-gradient(135deg, #2563eb, #3b82f6);
            --ld-grad-soft: linear-gradient(135deg, #3b82f6, #60a5fa);
            --ld-bg: #f8fafc;
            --ld-card: #ffffff;
            --ld-border: #e2e8f0;
            --ld-border-soft: #eef2f7;
            --ld-text: #0f172a;
            --ld-text-2: #475569;
            --ld-text-3: #94a3b8;
            --ld-shadow: 0 6px 18px -6px rgba(15, 23, 42, .08);
            --ld-shadow-lg: 0 22px 48px -18px rgba(15, 23, 42, .18);
            --ld-radius: 18px;
            --ld-green: #16a34a;   --ld-green-soft: #f0fdf4;
            --ld-red: #dc2626;     --ld-red-soft: #fef2f2;
            --ld-amber: #d97706;   --ld-amber-soft: #fffbeb;
            --ld-sky: #0284c7;     --ld-sky-soft: #f0f9ff;
        }
        html.dark-mode {
            --ld-primary: #3DA9FC;
            --ld-primary-2: #2EA8FF;
            --ld-primary-3: #6ec9ff;
            --ld-primary-dark: #2EA8FF;
            --ld-primary-soft: rgba(61, 169, 252, .14);
            --ld-primary-border: rgba(61, 169, 252, .35);
            --ld-grad: linear-gradient(135deg, #2EA8FF, #00E5FF);
            --ld-grad-soft: linear-gradient(135deg, #2EA8FF, #00E5FF);
            --ld-bg: #071A24;
            --ld-card: #0D2F38;
            --ld-border: rgba(255, 255, 255, .1);
            --ld-border-soft: rgba(255, 255, 255, .06);
            --ld-text: #f8fafc;
            --ld-text-2: #cbd5e1;
            --ld-text-3: #7d96a6;
            --ld-shadow: 0 6px 18px -6px rgba(0, 0, 0, .35);
            --ld-shadow-lg: 0 22px 48px -18px rgba(0, 0, 0, .5);
            --ld-green: #34d399;   --ld-green-soft: rgba(52, 211, 153, .12);
            --ld-red: #f87171;     --ld-red-soft: rgba(248, 113, 113, .12);
            --ld-amber: #fbbf24;   --ld-amber-soft: rgba(251, 191, 36, .12);
            --ld-sky: #38bdf8;     --ld-sky-soft: rgba(56, 189, 248, .12);
        }

        /* ---------- Base ---------- */
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Poppins', 'Open Sans', sans-serif;
            -webkit-font-smoothing: antialiased;
            background: var(--ld-bg);
        }
        ::selection { background: rgba(37, 99, 235, .18); }
        html.dark-mode ::selection { background: rgba(61, 169, 252, .28); }

        ::-webkit-scrollbar { width: 10px; height: 10px; }
        ::-webkit-scrollbar-track { background: var(--ld-border-soft); }
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, var(--ld-primary-3), var(--ld-primary));
            border-radius: 8px;
            border: 2px solid var(--ld-border-soft);
        }
        ::-webkit-scrollbar-thumb:hover { background: var(--ld-primary-2); }

        /* ============================================================
           HEADER — floating glass, shrink on scroll
           ============================================================ */
        #header {
            background: rgba(248, 250, 252, .88) !important;
            border-bottom: 1px solid var(--ld-border) !important;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, .6), 0 6px 24px -14px rgba(15, 23, 42, .18) !important;
            padding: 8px 0 !important;
            transition: padding .3s ease, box-shadow .3s ease, background .3s ease !important;
        }
        #header.header-scrolled {
            padding: 5px 0 !important;
            box-shadow: 0 12px 32px -16px rgba(37, 99, 235, .4) !important;
        }
        html.dark-mode #header { background: rgba(7, 26, 36, .82) !important; }
        html.dark-mode #header.header-scrolled { box-shadow: 0 14px 34px -16px rgba(0, 0, 0, .6) !important; }
        /* dark-mode.css forces #header position:relative globally; restore fixed on landing */
        html.dark-mode body #header {
            position: fixed !important;
            top: 0;
            right: 0;
            left: 0;
            z-index: 1030 !important;
        }

        #header .logo { line-height: 1; flex-shrink: 0; white-space: nowrap; position: relative; z-index: 1000; }
        #header .logo a {
            font-weight: 900;
            font-size: 20px;
            font-family: 'Poppins', sans-serif;
            letter-spacing: 1.2px;
            display: flex;
            align-items: center;
            gap: 12px;
            background: linear-gradient(135deg, var(--ld-text) 55%, var(--ld-primary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: none;
        }
        html.dark-mode #header .logo a {
            background: linear-gradient(135deg, #f8fafc 55%, #3DA9FC);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        #header .logo a::before {
            content: '';
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--ld-primary);
            display: inline-block;
            box-shadow: 0 0 16px rgba(37, 99, 235, .55);
            flex-shrink: 0;
            -webkit-text-fill-color: initial;
        }
        html.dark-mode #header .logo a::before {
            background: #3DA9FC;
            box-shadow: 0 0 16px rgba(61, 169, 252, .6);
        }

        .navbar .nav-link {
            position: relative;
            font-weight: 600 !important;
            padding: 7px 11px !important;
            border-radius: 12px !important;
            transition: all .3s ease !important;
            font-size: 12.5px !important;
            color: var(--ld-text-2) !important;
        }
        .navbar .nav-link::after {
            content: '';
            position: absolute;
            left: 14px;
            right: 14px;
            bottom: 3px;
            height: 2px;
            border-radius: 2px;
            background: var(--ld-grad);
            transform: scaleX(0);
            transform-origin: center;
            transition: transform .3s ease;
        }
        .navbar .nav-link:hover,
        .navbar .nav-link.active { color: var(--ld-primary) !important; }
        .navbar .nav-link:hover::after,
        .navbar .nav-link.active::after { transform: scaleX(1); }
        #navbar:not(.navbar-mobile) .nav-link::after { display: block; }

        .navbar ul li a:focus,
        .navbar ul li a:active,
        .navbar ul li a:focus-visible {
            outline: none !important;
            box-shadow: none !important;
        }
        /* template adds 20px left padding per item; tighten so the nav never crowds the logo */
        .navbar > ul > li { padding: 3px 0 3px 10px; }
        #header .container { flex-wrap: nowrap; }

        .appointment-btn {
            background: var(--ld-grad) !important;
            color: #fff !important;
            font-weight: 700 !important;
            border-radius: 50px !important;
            padding: 8px 20px !important;
            border: none !important;
            font-size: 13px !important;
            box-shadow: 0 8px 20px -6px rgba(37, 99, 235, .45) !important;
            transition: all .3s ease !important;
        }
        .appointment-btn:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 12px 28px -8px rgba(37, 99, 235, .55) !important;
            color: #fff !important;
        }
        html.dark-mode .appointment-btn { color: #001019 !important; box-shadow: 0 8px 20px -6px rgba(46, 168, 255, .5) !important; }
        html.dark-mode .appointment-btn:hover { color: #001019 !important; box-shadow: 0 12px 28px -8px rgba(0, 229, 255, .6) !important; }

        /* Theme toggle inside light header — compact so it doesn't stretch the bar tall */
        #header .theme-toggle { color: var(--ld-text-2) !important; }
        html.dark-mode #header .theme-toggle { color: rgba(0, 229, 255, .85) !important; }
        #header .theme-toggle { width: 32px !important; height: 32px !important; font-size: 14px !important; }
        #header .theme-toggle i { font-size: 14px !important; }

        /* Mobile navigation */
        .mobile-nav-toggle { color: var(--ld-text) !important; font-size: 28px; }
        #navbar.navbar-mobile {
            background: rgba(15, 23, 42, .45);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
        }
        #navbar.navbar-mobile ul {
            background: rgba(248, 250, 252, .96);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border-radius: var(--ld-radius);
            border: 1px solid var(--ld-border);
            box-shadow: var(--ld-shadow-lg);
            padding: 12px 8px;
            top: 64px;
        }
        #navbar.navbar-mobile a,
        #navbar.navbar-mobile a:focus {
            color: var(--ld-text-2) !important;
            border: none !important;
            border-radius: 12px;
        }
        #navbar.navbar-mobile a:hover,
        #navbar.navbar-mobile .active,
        #navbar.navbar-mobile li:hover > a {
            color: var(--ld-primary) !important;
            background: var(--ld-primary-soft);
        }
        html.dark-mode #navbar.navbar-mobile { background: rgba(7, 26, 36, .5); }
        html.dark-mode #navbar.navbar-mobile ul { background: rgba(7, 26, 36, .96); border-color: rgba(255, 255, 255, .1); }
        html.dark-mode #navbar.navbar-mobile a,
        html.dark-mode #navbar.navbar-mobile a:focus { color: #cbd5e1 !important; }
        html.dark-mode #navbar.navbar-mobile a:hover,
        html.dark-mode #navbar.navbar-mobile .active,
        html.dark-mode #navbar.navbar-mobile li:hover > a { color: #3DA9FC !important; }

        /* ============================================================
           HERO — blue gradient, two-column, glass floating cards
           ============================================================ */
        #hero {
            background: linear-gradient(135deg, #1e40af 0%, #2563eb 48%, #3b82f6 100%) !important;
            background-size: 180% 180% !important;
            animation: gradientShift 18s ease infinite;
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            margin-bottom: 0 !important;
            padding-top: 96px;
            padding-bottom: 64px;
        }
        #hero .container { position: relative; z-index: 2; }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        #hero::before {
            content: '';
            position: absolute;
            width: 800px;
            height: 800px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 255, 255, .07) 0%, transparent 70%);
            top: -200px;
            right: -200px;
            animation: floatOrb 12s ease-in-out infinite;
            pointer-events: none;
        }
        #hero::after {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(147, 197, 253, .10) 0%, transparent 70%);
            bottom: -150px;
            left: -150px;
            animation: floatOrb 10s ease-in-out infinite reverse;
            pointer-events: none;
        }

        .hero-particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, .035);
            pointer-events: none;
        }
        .hero-particle:nth-child(1) { width: 300px; height: 300px; top: 10%; left: 5%; animation: floatParticle 18s ease-in-out infinite; }
        .hero-particle:nth-child(2) { width: 200px; height: 200px; bottom: 15%; right: 10%; animation: floatParticle 14s ease-in-out infinite reverse; }
        .hero-particle:nth-child(3) { width: 120px; height: 120px; top: 60%; left: 60%; background: rgba(147, 197, 253, .06); animation: floatParticle 20s ease-in-out infinite 2s; }
        .hero-particle:nth-child(4) { width: 80px; height: 80px; top: 20%; right: 35%; background: rgba(255, 255, 255, .05); animation: floatParticle 16s ease-in-out infinite 1s; }
        .hero-particle:nth-child(5) { width: 160px; height: 160px; bottom: 30%; left: 30%; background: rgba(147, 197, 253, .05); animation: floatParticle 22s ease-in-out infinite 3s; }

        @keyframes floatOrb {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(50px, -50px) scale(1.05); }
        }
        @keyframes floatParticle {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(40px, -30px) rotate(120deg); }
            66% { transform: translate(-20px, 40px) rotate(240deg); }
        }

        .hero-logo-wrap { position: relative; display: inline-block; }
        .hero-logo-wrap::before {
            content: '';
            position: absolute;
            inset: -8px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, .2);
            animation: pulseRing 3s ease-in-out infinite;
        }
        .hero-logo-wrap::after {
            content: '';
            position: absolute;
            inset: -16px;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, .12);
            animation: pulseRing 3s ease-in-out infinite 1s;
        }
        @keyframes pulseRing {
            0%, 100% { transform: scale(1); opacity: .5; }
            50% { transform: scale(1.1); opacity: 0; }
        }

        .hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 16px;
            border-radius: 50px;
            background: rgba(255, 255, 255, .12);
            border: 1px solid rgba(255, 255, 255, .24);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            color: #fff;
            font-size: 11.5px;
            font-weight: 600;
            letter-spacing: .8px;
            text-transform: uppercase;
        }

        #hero h1 {
            color: #fff !important;
            font-size: 3.2rem;
            font-weight: 800;
            font-family: 'Poppins', sans-serif;
            line-height: 1.15;
            text-shadow: 0 12px 32px rgba(0, 0, 0, .18);
        }

        .hero-title-line {
            display: inline-block;
            overflow: hidden;
            white-space: nowrap;
            border-right: 3px solid #93c5fd;
            animation: typewriter 2.6s steps(26) 1s forwards, blink 1s step-end 5.5s forwards;
            max-width: 0;
            font-size: 1.45rem;
            color: rgba(255, 255, 255, .85);
            font-weight: 600;
        }
        @keyframes typewriter { to { max-width: 100%; } }
        @keyframes blink {
            0%, 100% { border-color: #93c5fd; }
            50% { border-color: transparent; }
        }

        .hero-brand {
            font-family: 'Poppins', sans-serif;
            letter-spacing: 2px;
            color: #fff !important;
            font-weight: 800 !important;
            margin-top: 10px;
        }

        .hero-sub {
            color: rgba(255, 255, 255, .78);
            font-size: 15.5px;
            line-height: 1.75;
            max-width: 540px;
        }

        #hero .btn-get-started {
            background: #fff !important;
            color: var(--ld-primary-dark) !important;
            font-weight: 800 !important;
            border-radius: 50px !important;
            padding: 10px 24px !important;
            border: none !important;
            font-size: 13.5px !important;
            letter-spacing: .2px !important;
            box-shadow: 0 10px 30px -8px rgba(0, 0, 0, .35), inset 0 1px 0 rgba(255, 255, 255, .7) !important;
            transition: all .3s ease !important;
        }
        #hero .btn-get-started:hover {
            transform: translateY(-3px) !important;
            box-shadow: 0 16px 40px -10px rgba(0, 0, 0, .4) !important;
            color: var(--ld-primary) !important;
        }

        .btn-hero-outline {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 24px;
            border-radius: 50px;
            border: 1.5px solid rgba(255, 255, 255, .45);
            color: #fff;
            font-weight: 700;
            font-size: 13.5px;
            background: rgba(255, 255, 255, .08);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            transition: all .3s ease;
        }
        .btn-hero-outline:hover { background: rgba(255, 255, 255, .18); color: #fff; transform: translateY(-3px); border-color: #fff; }

        .hero-visual { position: relative; min-height: 480px; }
        .hero-glass-card {
            position: absolute;
            background: rgba(255, 255, 255, .12);
            border: 1px solid rgba(255, 255, 255, .22);
            border-radius: 20px;
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            box-shadow: 0 24px 60px -20px rgba(0, 0, 0, .45);
        }
        .hero-glass-main { top: 18%; left: 50%; margin-left: -150px; width: 300px; padding: 28px 22px; text-align: center; animation: heroFloat 7s ease-in-out infinite; }
        .hero-glass-main .hero-glass-icon {
            width: 74px;
            height: 74px;
            margin: 0 auto 14px;
            border-radius: 22px;
            background: var(--ld-grad);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            color: #fff;
            box-shadow: 0 14px 30px -10px rgba(37, 99, 235, .6);
        }
        .hero-glass-main strong { display: block; color: #fff; font-size: 17px; font-weight: 800; }
        .hero-glass-main span {
            display: block;
            color: rgba(255, 255, 255, .75);
            font-size: 12.5px;
            margin-top: 6px;
            max-width: 240px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-inline: auto;
        }
        .hero-glass-float { display: flex; align-items: center; gap: 12px; padding: 13px 16px; width: 230px; }
        .hero-glass-float i { font-size: 18px; color: #dbeafe; }
        .hero-glass-float strong { display: block; color: #fff; font-size: 13px; }
        .hero-glass-float span { display: block; color: rgba(255, 255, 255, .68); font-size: 11px; }
        .hero-glass-float-1 { top: 0; left: 0; animation: heroFloat 8s ease-in-out infinite 1s; }
        .hero-glass-float-2 { top: 56%; right: 0; animation: heroFloat 9s ease-in-out infinite .5s; }
        .hero-glass-float-3 { bottom: 0; left: 6%; animation: heroFloat 7.5s ease-in-out infinite 1.5s; }
        @keyframes heroFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-12px); }
        }
        .hero-blob { position: absolute; border-radius: 50%; filter: blur(60px); opacity: .5; pointer-events: none; }
        .hero-blob-1 { width: 220px; height: 220px; background: rgba(29, 78, 216, .55); top: 10%; right: 2%; animation: heroBlob 12s ease-in-out infinite; }
        .hero-blob-2 { width: 180px; height: 180px; background: rgba(147, 197, 253, .4); bottom: 0; left: 8%; animation: heroBlob 14s ease-in-out infinite 2s; }
        @keyframes heroBlob {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(20px, -20px) scale(1.08); }
        }

        @keyframes heroFadeUp {
            from { opacity: 0; transform: translateY(36px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes heroZoom {
            from { opacity: 0; transform: scale(.86); }
            to { opacity: 1; transform: scale(1); }
        }

        .scroll-indicator {
            position: absolute;
            bottom: 26px;
            left: 0;
            right: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            animation: fadeInUp 1s ease 2.2s both;
            z-index: 2;
        }
        .scroll-indicator span { font-size: 10px; color: rgba(255, 255, 255, .5); text-transform: uppercase; letter-spacing: 2px; }
        .scroll-indicator .chevron {
            width: 24px;
            height: 24px;
            border-right: 2px solid rgba(255, 255, 255, .4);
            border-bottom: 2px solid rgba(255, 255, 255, .4);
            transform: rotate(45deg);
            animation: bounceChevron 2s ease-in-out infinite;
        }
        @keyframes bounceChevron {
            0%, 100% { transform: rotate(45deg) translate(0, 0); opacity: .3; }
            50% { transform: rotate(45deg) translate(4px, 4px); opacity: .8; }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ============================================================
           SECTIONS
           ============================================================ */
        .profile-section,
        .announcement-section,
        .galery-section,
        .calendar-section,
        .faq.section-bg {
            background: var(--ld-bg);
            padding: 96px 0;
        }

        .section-title h2 {
            font-weight: 800 !important;
            color: var(--ld-text) !important;
            font-size: 32px !important;
            position: relative;
            display: inline-block;
        }
        .section-title h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 3px;
            border-radius: 3px;
            background: var(--ld-grad);
            transition: width .8s ease;
        }
        .section-title h2.animate-underline::after { width: 64px; }
        .section-title p { color: var(--ld-text-3) !important; font-size: 15px; }

        .section-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            border-radius: 50px;
            background: var(--ld-primary-soft);
            color: var(--ld-primary);
            font-size: 11.5px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            margin-bottom: 14px;
        }

        /* ---------- Profil ---------- */
        .visi-card {
            background: linear-gradient(135deg, var(--ld-primary-soft), var(--ld-card));
            border-radius: var(--ld-radius);
            padding: 26px;
            border-left: 4px solid var(--ld-primary);
            border-top: 1px solid var(--ld-border);
            border-right: 1px solid var(--ld-border);
            border-bottom: 1px solid var(--ld-border);
            box-shadow: var(--ld-shadow);
            margin-bottom: 20px;
            transition: all .3s ease;
        }
        .visi-card:hover { transform: translateY(-4px); box-shadow: var(--ld-shadow-lg); }
        .visi-card h5 { color: var(--ld-primary); font-weight: 700; }
        .visi-card p { color: var(--ld-text-2); font-size: 14px; margin-bottom: 0; line-height: 1.8; }

        .profile-img-wrap {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--ld-shadow-lg);
            border: 1px solid var(--ld-border);
            background: var(--ld-card);
            height: 100%;
        }
        .profile-img-wrap img { width: 100%; height: 100%; transition: transform .6s ease; }
        .profile-img-wrap:hover img { transform: scale(1.02); }

        .card-modern {
            background: var(--ld-card);
            border: 1px solid var(--ld-border);
            border-radius: var(--ld-radius);
            box-shadow: var(--ld-shadow);
            transition: all .3s ease;
            overflow: hidden;
        }
        .card-modern:hover { transform: translateY(-4px); box-shadow: var(--ld-shadow-lg); }
        .card-modern .card-body { padding: 26px; }
        .card-modern h5 { color: var(--ld-text); font-weight: 700; }

        .contact-item { display: flex; align-items: center; gap: 14px; padding: 13px 0; border-bottom: 1px solid var(--ld-border-soft); }
        .contact-item:last-child { border-bottom: none; }
        .contact-item .icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: var(--ld-primary-soft);
            color: var(--ld-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }
        .contact-item .icon.green { background: var(--ld-primary-soft); color: var(--ld-primary); }
        .contact-item .label { font-size: 11.5px; color: var(--ld-text-3); margin-bottom: 2px; }
        .contact-item .value { font-size: 14px; font-weight: 600; color: var(--ld-text); margin-bottom: 0; }

        .map-wrap {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--ld-shadow);
            border: 1px solid var(--ld-border);
            height: 100%;
        }
        .map-wrap iframe { width: 100%; height: 100%; min-height: 320px; border: 0; }

        /* ---------- Pengumuman ---------- */
        .announcement-card {
            background: var(--ld-card);
            border: 1px solid var(--ld-border);
            border-radius: var(--ld-radius);
            box-shadow: var(--ld-shadow);
            transition: all .35s cubic-bezier(.4, 0, .2, 1);
            overflow: hidden;
            position: relative;
        }
        .announcement-card::before {
            content: '';
            position: absolute;
            inset: 0 0 auto 0;
            height: 4px;
            background: var(--ld-grad);
            opacity: .85;
        }
        .announcement-card:hover { transform: translateY(-6px); box-shadow: var(--ld-shadow-lg); border-color: var(--ld-primary-border); }
        .announcement-card .card-body { padding: 24px; }
        .announcement-date { font-size: 12px; color: var(--ld-text-3); display: flex; align-items: center; gap: 8px; margin-bottom: 10px; }
        .announcement-date i { color: var(--ld-primary); }
        .announcement-date .date-chip { background: var(--ld-primary-soft); color: var(--ld-primary); padding: 3px 11px; border-radius: 50px; font-weight: 700; font-size: 11px; }
        .announcement-card h5 { font-weight: 700; color: var(--ld-text); margin-bottom: 8px; font-size: 16px; line-height: 1.45; }
        .announcement-card p { font-size: 13px; color: var(--ld-text-2); margin-bottom: 0; line-height: 1.7; }

        .empty-state {
            border: 2px dashed var(--ld-border);
            border-radius: 20px;
            padding: 60px 20px;
            background: var(--ld-card);
        }
        .empty-state i { font-size: 3rem; color: var(--ld-text-3); opacity: .5; }
        .empty-state p { color: var(--ld-text-3); font-weight: 600; }
        .empty-sub { color: var(--ld-text-3); font-size: 13px; }

        /* ---------- Galeri ---------- */
        .galery-masonry { column-count: 4; column-gap: 18px; max-width: 1140px; margin-inline: auto; }
        .galery-card {
            position: relative;
            display: block;
            text-decoration: none;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: var(--ld-shadow);
            border: 1px solid var(--ld-border);
            background: var(--ld-primary-soft);
            break-inside: avoid;
            margin-bottom: 18px;
            cursor: pointer;
            transition: all .35s cubic-bezier(.4, 0, .2, 1);
        }
        .galery-card:hover { transform: translateY(-5px); box-shadow: var(--ld-shadow-lg); border-color: var(--ld-primary-border); }
        .galery-card img {
            width: 100%;
            height: auto;
            display: block;
            aspect-ratio: 4 / 3;
            object-fit: cover;
            opacity: 0;
            transition: opacity .5s ease, transform .6s ease;
            background: linear-gradient(100deg, var(--ld-primary-soft) 30%, var(--ld-card) 50%, var(--ld-primary-soft) 70%);
            background-size: 200% 100%;
            animation: shimmer 1.6s infinite;
        }
        .galery-card img.loaded { opacity: 1; animation: none; background: transparent; }
        .galery-card:nth-child(3n) img { aspect-ratio: 1 / 1; }
        .galery-card:hover img { transform: scale(1.06); }
        @keyframes shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        .galery-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(15, 23, 42, .82), transparent 55%);
            display: flex;
            align-items: flex-end;
            padding: 16px;
            opacity: 0;
            transition: opacity .3s ease;
        }
        .galery-card:hover .galery-overlay { opacity: 1; }
        .galery-overlay h5 { color: #fff; font-size: 14px; margin: 0; font-weight: 600; }
        .galery-zoom {
            position: absolute;
            top: 14px;
            right: 14px;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .9);
            color: var(--ld-primary-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, .3);
        }
        /* ---------- Kalender ---------- */
        .month-card {
            background: var(--ld-card);
            border: 1px solid var(--ld-border);
            border-radius: 18px;
            box-shadow: var(--ld-shadow);
            overflow: hidden;
            transition: all .35s cubic-bezier(.4, 0, .2, 1);
        }
        .month-card:hover { transform: translateY(-6px); box-shadow: var(--ld-shadow-lg); border-color: var(--ld-primary-border); }
        .month-header {
            background: var(--ld-grad);
            color: #fff;
            text-align: center;
            font-weight: 700;
            font-size: 15px;
            padding: 13px 8px;
            letter-spacing: .5px;
        }
        .month-days-header {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            padding: 9px 6px 5px;
            background: var(--ld-primary-soft);
            border-bottom: 1px solid var(--ld-border);
        }
        .month-days-header span { text-align: center; font-size: 10px; font-weight: 700; color: var(--ld-text-3); text-transform: uppercase; }
        .month-days-grid { display: grid; grid-template-columns: repeat(7, 1fr); padding: 4px 6px 12px; gap: 2px; }
        .month-days-grid .day {
            text-align: center;
            font-size: 12px;
            padding: 5px 0;
            color: var(--ld-text);
            border-radius: 8px;
            transition: background .2s, color .2s;
        }
        .month-days-grid .day:hover { background: var(--ld-primary-soft); color: var(--ld-primary); cursor: default; }
        .month-days-grid .weekend { color: var(--ld-red); font-weight: 600; }
        .month-days-grid .highlight {
            background: var(--ld-grad);
            color: #fff;
            font-weight: 700;
            box-shadow: 0 6px 14px -4px rgba(37, 99, 235, .5);
        }
        .month-days-grid .highlight:hover { background: var(--ld-primary-dark); color: #fff; }
        .month-days-grid .empty { display: block; }

        .legend { display: flex; justify-content: center; gap: 24px; flex-wrap: wrap; margin-top: 36px; }
        .legend-item { display: inline-flex; align-items: center; gap: 8px; font-size: 12.5px; color: var(--ld-text-2); }
        .legend-dot { width: 12px; height: 12px; border-radius: 50%; }
        .legend-dot.today { background: var(--ld-grad); box-shadow: 0 2px 8px rgba(37, 99, 235, .5); }
        .legend-dot.weekend { background: var(--ld-red); }

        /* ---------- FAQ ---------- */
        .faq .faq-list { padding: 0; }
        .faq .faq-list li {
            background: var(--ld-card);
            border: 1px solid var(--ld-border);
            border-radius: var(--ld-radius);
            box-shadow: var(--ld-shadow);
            padding: 22px 24px;
            transition: all .3s ease;
        }
        .faq .faq-list li:hover { border-color: var(--ld-primary-border); box-shadow: var(--ld-shadow-lg); }
        .faq .faq-list a {
            color: var(--ld-text);
            font-weight: 600;
            font-size: 15.5px;
            padding: 0 40px 0 46px;
            font-family: 'Poppins', sans-serif;
        }
        .faq .faq-list a.collapsed { color: var(--ld-text); }
        .faq .faq-list a.collapsed:hover { color: var(--ld-primary); }
        .faq .faq-list .icon-help { left: 0; color: var(--ld-primary); font-size: 26px; }
        .faq .faq-list .icon-show,
        .faq .faq-list .icon-close { color: var(--ld-primary); font-size: 22px; right: 6px; }
        .faq .faq-list p { color: var(--ld-text-2); font-size: 14px; line-height: 1.8; padding: 12px 0 0 46px; }

        /* ============================================================
           FOOTER — darker navy, 3 columns
           ============================================================ */
        #footer {
            background: linear-gradient(135deg, #0f172a, #1e3a8a) !important;
            color: rgba(255, 255, 255, .8);
            position: relative;
            overflow: hidden;
        }
        #footer::before {
            content: '';
            position: absolute;
            width: 480px;
            height: 480px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(59, 130, 246, .18), transparent 70%);
            top: -160px;
            right: -120px;
            pointer-events: none;
        }
        #footer .footer-top { background: transparent !important; padding: 56px 0 30px; position: relative; }
        #footer h3, #footer h4 { color: #fff !important; font-weight: 700; font-family: 'Poppins', sans-serif; }
        #footer .footer-brand { display: flex; align-items: center; gap: 12px; font-size: 20px; }
        #footer .footer-brand img { width: 44px; height: 44px; border-radius: 12px; background: rgba(255, 255, 255, .08); padding: 6px; object-fit: contain; }
        #footer .footer-brand em { font-style: normal; background: linear-gradient(135deg, #93c5fd, #60a5fa); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        #footer .footer-contact p { color: rgba(255, 255, 255, .68); line-height: 1.9; }
        #footer .footer-links ul li a::before { content: '›'; margin-right: 8px; transition: margin .3s ease; }
        #footer .footer-links ul li a:hover::before { margin-right: 14px; }
        #footer a { color: rgba(255, 255, 255, .62) !important; transition: all .3s ease; }
        #footer a:hover { color: #93c5fd !important; }
        .footer-quick { list-style: none; padding: 0; margin: 0; }
        .footer-quick li { display: flex; align-items: flex-start; gap: 12px; padding: 8px 0; font-size: 13.5px; color: rgba(255, 255, 255, .7); }
        .footer-quick li i { color: #60a5fa; margin-top: 3px; }
        .footer-social { display: flex; gap: 10px; margin-top: 18px; }
        .footer-social a {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, .08);
            border: 1px solid rgba(255, 255, 255, .14);
            color: #fff !important;
            font-size: 15px;
            transition: all .3s ease;
        }
        .footer-social a:hover { background: var(--ld-grad); color: #fff !important; transform: translateY(-3px); }
        #footer .copyright { color: rgba(255, 255, 255, .55); font-size: 13.5px; }
        #footer .copyright strong { color: #93c5fd; }

        body > .back-to-top {
            background: var(--ld-grad) !important;
            color: #fff !important;
            border: none !important;
            box-shadow: 0 10px 24px -8px rgba(37, 99, 235, .5) !important;
            transition: all .3s ease !important;
        }
        body > .back-to-top:hover { background: var(--ld-primary-dark) !important; color: #fff !important; transform: translateY(-3px); }
        html.dark-mode body > .back-to-top { color: #001019 !important; }

        /* ---------- Reveal ---------- */
        .reveal { opacity: 0; transform: translateY(30px); transition: all .6s ease-out; }
        .reveal.active { opacity: 1; transform: translateY(0); }
        .reveal-delay-1 { transition-delay: .1s; }
        .reveal-delay-2 { transition-delay: .2s; }
        .reveal-delay-3 { transition-delay: .3s; }
        .reveal-delay-4 { transition-delay: .4s; }

        /* ============================================================
           RESPONSIVE
           ============================================================ */
        @media (min-width: 992px) and (max-width: 1199px) {
            #header .logo a { font-size: 16px; letter-spacing: .8px; }
            .navbar .nav-link { font-size: 11.5px !important; padding: 6px 8px !important; }
            .navbar > ul > li { padding-left: 6px; }
            #header .appointment-btn { padding: 7px 16px; font-size: 12px; }
        }
        @media (max-width: 991px) {
            #header .container { padding-top: 6px; padding-bottom: 6px; }
            #header .logo a { font-size: 17px; letter-spacing: 1px; }
            #header .appointment-btn { font-size: 11px; padding: 7px 14px; }
            #hero { min-height: 80vh; padding-top: 96px; }
            #hero h1 { font-size: 2rem !important; }
            #hero .hero-title-line { font-size: 1.05rem !important; }
            #hero h4.hero-brand { font-size: 1.15rem !important; }
            #hero .hero-logo-wrap img { width: 84px !important; }
            .hero-sub { font-size: 14px; margin-inline: auto; }
            .hero-eyebrow { font-size: 10.5px; }
            .profile-section, .announcement-section, .galery-section, .calendar-section, .faq.section-bg { padding: 72px 0; }
            .section-title h2 { font-size: 26px !important; }
            .galery-masonry { column-count: 2; }
            .month-days-header span { font-size: 9px; }
            .month-days-grid .day { font-size: 10px; padding: 4px 0; }
            .month-header { font-size: 13px; padding: 11px 6px; }
            .map-wrap iframe { min-height: 260px; }
        }
        @media (max-width: 480px) {
            .galery-masonry { column-count: 1; }
            #hero .btn-get-started,
            #hero .btn-hero-outline { font-size: 12.5px; padding: 10px 18px; width: 100%; justify-content: center; }
        }

        /* ============================================================
           REDUCED MOTION
           ============================================================ */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: .001s !important;
                animation-iteration-count: 1 !important;
                transition-duration: .001s !important;
                scroll-behavior: auto !important;
            }
            .reveal { opacity: 1 !important; transform: none !important; }
            .hero-title-line { animation: none !important; max-width: none !important; border-right: none !important; }
            #hero, .hero-particle, .hero-blob, .hero-glass-card,
            .hero-logo-wrap::before, .hero-logo-wrap::after { animation: none !important; }
        }
    </style>

    <script>
        function reveal() {
            var reveals = document.querySelectorAll(".reveal");
            for (var i = 0; i < reveals.length; i++) {
                var windowHeight = window.innerHeight;
                var elementTop = reveals[i].getBoundingClientRect().top;
                var elementVisible = 50;
                if (elementTop < windowHeight - elementVisible) {
                    reveals[i].classList.add("active");
                } else {
                    reveals[i].classList.remove("active");
                }
            }
            var titles = document.querySelectorAll(".section-title h2");
            for (var j = 0; j < titles.length; j++) {
                var titleTop = titles[j].getBoundingClientRect().top;
                if (titleTop < window.innerHeight - 80) {
                    titles[j].classList.add("animate-underline");
                }
            }
        }
        window.addEventListener("scroll", reveal);
        reveal();

        document.querySelectorAll('.month-card, .announcement-card, .visi-card, .contact-item').forEach(function(el, i) {
            el.style.setProperty('--card-delay', (i * 0.1) + 's');
        });
    </script>
    <link href="../css/dark-mode.css" rel="stylesheet">
    <link href="../css/loading.css" rel="stylesheet">

    <style>
        /* ============================================================
           PRELOADER + LOADING — landing overrides (blue), loaded after
           ../css/loading.css so these win.
           ============================================================ */
        #loading-progress .bar {
            background: linear-gradient(90deg, var(--ld-primary), var(--ld-primary-3), var(--ld-primary)) !important;
            background-size: 200% 100%;
        }
        html.dark-mode #loading-progress .bar { background: linear-gradient(90deg, #2EA8FF, #00E5FF, #2EA8FF) !important; }

        .loading-fullscreen { background: rgba(248, 250, 252, .92) !important; }
        html.dark-mode .loading-fullscreen { background: rgba(7, 26, 36, .92) !important; }
        .loading-fullscreen .loader-spinner { border-color: #e2e8f0; border-top-color: var(--ld-primary) !important; }
        html.dark-mode .loading-fullscreen .loader-spinner { border-color: #334155; border-top-color: #3DA9FC !important; }
        .loading-fullscreen .loader-text { color: var(--ld-text-2) !important; }
        html.dark-mode .loading-fullscreen .loader-text { color: #cbd5e1 !important; }
        .loading-fullscreen .loader-dots span { background: var(--ld-primary) !important; }
        html.dark-mode .loading-fullscreen .loader-dots span { background: #3DA9FC !important; }

        #preloader { background: linear-gradient(135deg, #f8fafc, #eff6ff) !important; }
        #preloader:before {
            content: '';
            position: fixed;
            top: calc(50% - 52px);
            left: calc(50% - 52px);
            width: 104px;
            height: 104px;
            border: none;
            border-radius: 0;
            background: url('../img/logo2.png') center/contain no-repeat;
            -webkit-animation: none;
            animation: none;
        }
        #preloader:after {
            content: '';
            position: fixed;
            top: calc(50% - 34px);
            left: calc(50% - 34px);
            width: 68px;
            height: 68px;
            border-radius: 50%;
            border: 4px solid rgba(37, 99, 235, .15);
            border-top-color: var(--ld-primary);
            animation: rotatePreloader 1s linear infinite;
        }
        @keyframes rotatePreloader { to { transform: rotate(360deg); } }
        html.dark-mode #preloader { background: linear-gradient(135deg, #071A24, #0D2F38) !important; }
        html.dark-mode #preloader:after { border-color: rgba(255, 255, 255, .14); border-top-color: #3DA9FC; }
    </style>
</head>

<body>
    @include('component.loading')

    <!-- ======= Header ======= -->
    <header id="header" class="fixed-top">
        <div class="container d-flex align-items-center">
            <h1 class="logo me-auto"><a href="/">MIS NURUL ULUM</a></h1>
            <nav id="navbar" class="navbar order-last order-lg-0">
                <ul>
                    <li><a class="nav-link scrollto animate__animated animate__fadeInDownBig" style="animation-delay:.5s;" href="#">Beranda</a></li>
                    <li><a class="nav-link scrollto animate__animated animate__fadeInDownBig" style="animation-delay:.4s;" href="#profil">Profil</a></li>
                    <li><a class="nav-link scrollto animate__animated animate__fadeInDownBig" style="animation-delay:.3s;" href="#pengumuman">Pengumuman</a></li>
                    <li><a class="nav-link scrollto animate__animated animate__fadeInDownBig" style="animation-delay:.25s;" href="#galery">Galery</a></li>
                    <li><a class="nav-link scrollto animate__animated animate__fadeInDownBig" style="animation-delay:.2s;" href="#kalender">Kalender Masehi</a></li>
                    <li><a class="nav-link scrollto animate__animated animate__fadeInDownBig" style="animation-delay:.1s;" href="#faq">FAQ</a></li>
                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
            </nav>

            <div class="d-flex align-items-center gap-2">
                <a class="theme-toggle" href="#" title="Ganti tema">
                    <i class="fas fa-moon"></i>
                </a>
                @guest
                <a href="/login" class="appointment-btn scrollto animate__animated animate__fadeInRight">Masuk</a>
                @else
                <a href="/home" class="appointment-btn scrollto animate__animated animate__fadeInRight">Dashboard</a>
                @endguest
            </div>
        </div>
    </header>

    <!-- ======= Hero ======= -->
    <section id="hero" class="d-flex align-items-center">
        <div class="hero-particle"></div>
        <div class="hero-particle"></div>
        <div class="hero-particle"></div>
        <div class="hero-particle"></div>
        <div class="hero-particle"></div>
        <div class="container position-relative">
            <div class="row align-items-center g-4">
                <div class="col-lg-7 text-center text-lg-start">
                    <div class="hero-logo-wrap d-inline-block" style="animation:heroZoom .7s ease .1s both;">
                        <img src="{{ asset('img/logo2.png') }}" alt="Logo MIS Nurul Ulum" style="width:110px; height:auto; filter:drop-shadow(0 6px 16px rgba(0,0,0,.25));">
                    </div>
                    <div style="animation:heroFadeUp .6s ease .25s both;">
                        <span class="hero-eyebrow mt-4"><i class="fas fa-graduation-cap"></i>Portal Resmi</span>
                    </div>
                    <h1 class="fw-bold text-white mt-3 mb-2" style="animation:heroFadeUp .6s ease .35s both;">
                        Selamat Datang
                    </h1>
                    <div style="animation:heroFadeUp .6s ease .45s both;">
                        <h2 class="hero-title-line">Sistem Informasi Akademik</h2>
                    </div>
                    <h4 class="hero-brand" style="animation:heroFadeUp .6s ease .9s both;">
                        MIS Nurul Ulum
                    </h4>
                    <p class="hero-sub mt-3 mb-4" style="animation:heroFadeUp .6s ease 1s both;">
                        Portal informasi resmi MIS Nurul Ulum — profil madrasah, pengumuman, galeri kegiatan, dan kalender akademik dalam satu halaman.
                    </p>
                    <div class="d-flex flex-wrap gap-3 align-items-center justify-content-center justify-content-lg-start" style="animation:heroFadeUp .6s ease 1.1s both;">
                        <a href="#profil" class="btn-get-started scrollto d-inline-block hero-mulai-btn">
                            <i class="fas fa-rocket me-2"></i>Mulai Sekarang
                        </a>
                        <a href="#pengumuman" class="btn-hero-outline scrollto">
                            <i class="fas fa-bullhorn me-2"></i>Lihat Pengumuman
                        </a>
                    </div>
                </div>
                <div class="col-lg-5 d-none d-lg-block">
                    <div class="hero-visual" style="animation:heroFadeUp .8s ease .6s both;">
                        <span class="hero-blob hero-blob-1"></span>
                        <span class="hero-blob hero-blob-2"></span>
                        <div class="hero-glass-card hero-glass-main">
                            <div class="hero-glass-icon"><i class="fas fa-school"></i></div>
                            <strong>MIS Nurul Ulum</strong>
                            <span>{{ $profil->alamat ?? 'Patapan, Paiton' }}</span>
                        </div>
                        <div class="hero-glass-card hero-glass-float hero-glass-float-1">
                            <i class="fas fa-calendar-check"></i>
                            <div><strong>Kalender {{ date('Y') }}</strong><span>Agenda tahunan madrasah</span></div>
                        </div>
                        <div class="hero-glass-card hero-glass-float hero-glass-float-2">
                            <i class="fas fa-megaphone"></i>
                            <div><strong>Pengumuman</strong><span>Informasi terbaru sekolah</span></div>
                        </div>
                        <div class="hero-glass-card hero-glass-float hero-glass-float-3">
                            <i class="fas fa-images"></i>
                            <div><strong>Galeri</strong><span>Dokumentasi kegiatan</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="scroll-indicator">
            <span>Scroll</span>
            <div class="chevron"></div>
        </div>
    </section>

    <main id="main">

        <!-- ======= Profil ======= -->
        <section id="profil" class="profile-section">
            <div class="container">
                <div class="section-title text-center mb-5 reveal">
                    <span class="section-badge"><i class="fas fa-school"></i> Tentang Kami</span>
                    <h2>Profil Madrasah</h2>
                    <p>Mengenal lebih dekat MIS Nurul Ulum Patapan</p>
                </div>

                <div class="row g-4">
                    <!-- Visi Misi -->
                    <div class="col-lg-6 reveal">
                        <div class="visi-card">
                            <h5><i class="fas fa-bullseye me-2"></i>Visi</h5>
                            <p>{{ $profil->visi ?? '...' }}</p>
                        </div>
                        <div class="visi-card">
                            <h5><i class="fas fa-flag me-2"></i>Misi</h5>
                            <p>
                                @if($profil && $profil->misi->count())
                                    @foreach($profil->misi as $m)
                                    {{ $loop->iteration }}. {{ $m->item }}@if(!$loop->last)<br>@endif
                                    @endforeach
                                @else
                                ...
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Foto Madrasah -->
                    <div class="col-lg-6 reveal">
                        <div class="profile-img-wrap">
                            <img src="{{ $profil && $profil->foto ? asset('storage/' . $profil->foto) : asset('img/logo2.png') }}" alt="Madrasah" loading="lazy"
                                style="background:linear-gradient(135deg, var(--ld-primary-soft), var(--ld-card)); padding:{{ $profil && $profil->foto ? '0' : '60px' }}; object-fit:{{ $profil && $profil->foto ? 'cover' : 'contain' }};">
                        </div>
                    </div>

                    <!-- Kontak -->
                    <div class="col-lg-6 reveal">
                        <div class="card card-modern h-100">
                            <div class="card-body">
                                <h5 class="fw-bold mb-3">
                                    <i class="fas fa-address-card me-2" style="color:var(--ld-primary);"></i>
                                    Kontak &amp; Alamat
                                </h5>
                                <div class="contact-item">
                                    <div class="icon green"><i class="fas fa-map-marker-alt"></i></div>
                                    <div>
                                        <p class="label">Alamat</p>
                                        <p class="value">{{ $profil->alamat ?? '...' }}</p>
                                    </div>
                                </div>
                                <div class="contact-item">
                                    <div class="icon green"><i class="fas fa-phone-alt"></i></div>
                                    <div>
                                        <p class="label">Telepon</p>
                                        <p class="value">{{ $profil->telepon ?? '...' }}</p>
                                    </div>
                                </div>
                                <div class="contact-item">
                                    <div class="icon green"><i class="fas fa-envelope"></i></div>
                                    <div>
                                        <p class="label">Email</p>
                                        <p class="value">{{ $profil->email ?? '...' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Google Map -->
                    <div class="col-lg-6 reveal">
                        <div class="map-wrap h-100">
                            <iframe
                                src="{{ $profil->map_embed ?? '' }}"
                                allowfullscreen loading="lazy">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ======= Pengumuman ======= -->
        <section id="pengumuman" class="announcement-section">
            <div class="container">
                <div class="section-title text-center mb-5 reveal">
                    <span class="section-badge"><i class="fas fa-bullhorn"></i> Berita Sekolah</span>
                    <h2>Pengumuman</h2>
                    <p>Informasi dan berita terbaru dari MIS Nurul Ulum</p>
                </div>

                <div class="row g-4">
                    @forelse($pengumuman as $p)
                    <div class="col-lg-4 col-md-6 reveal">
                        <div class="card announcement-card">
                            <div class="card-body">
                                <div class="announcement-date">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span class="date-chip">{{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('d F Y') }}</span>
                                </div>
                                <h5>{{ $p->judul }}</h5>
                                <p>{{ Str::limit(strip_tags($p->isi), 120) }}</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 reveal">
                        <div class="empty-state text-center">
                            <i class="fas fa-bullhorn"></i>
                            <p class="mt-3 mb-1">Belum ada pengumuman</p>
                            <span class="empty-sub">Informasi baru akan tampil di sini</span>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- ======= Galery ======= -->
        <section id="galery" class="galery-section">
            <div class="container">
                <div class="section-title text-center mb-5 reveal">
                    <span class="section-badge"><i class="fas fa-images"></i> Dokumentasi</span>
                    <h2>Galery</h2>
                    <p>Momen kegiatan di MIS Nurul Ulum</p>
                </div>

                <div class="galery-masonry reveal">
                    @forelse($galery as $g)
                    <a href="{{ asset($g->foto) }}" class="galery-card galelry-lightbox" data-glightbox="type: image; title: {{ $g->judul }}">
                        <img src="{{ asset($g->foto) }}" alt="{{ $g->judul }}" loading="lazy">
                        <div class="galery-overlay">
                            <span class="galery-zoom"><i class="fas fa-expand"></i></span>
                            <h5>{{ $g->judul }}</h5>
                        </div>
                    </a>
                    @empty
                    <div class="empty-state text-center">
                        <i class="fas fa-images"></i>
                        <p class="mt-3 mb-1">Belum ada foto galeri</p>
                        <span class="empty-sub">Dokumentasi kegiatan akan tampil di sini</span>
                    </div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- ======= Kalender Masehi ======= -->
        <section id="kalender" class="calendar-section">
            <div class="container">
                <div class="section-title text-center mb-5 reveal">
                    <span class="section-badge"><i class="fas fa-calendar-alt"></i> Agenda</span>
                    <h2>Kalender Masehi {{ date('Y') }}</h2>
                    <p>Kalender tahun {{ date('Y') }} masehi</p>
                </div>

                @php
                    $months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                    $days = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];
                    $year = date('Y');
                @endphp

                <div class="row g-4 justify-content-center reveal">
                    @foreach($months as $i => $month)
                        @php
                            $m = $i + 1;
                            $firstDay = \Carbon\Carbon::create($year, $m, 1);
                            $daysInMonth = $firstDay->daysInMonth;
                            $startDow = $firstDay->dayOfWeek;
                        @endphp
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="month-card">
                                <div class="month-header">{{ $month }} {{ $year }}</div>
                                <div class="month-days-header">
                                    @foreach($days as $d)
                                        <span>{{ $d }}</span>
                                    @endforeach
                                </div>
                                <div class="month-days-grid">
                                    @for($k = 0; $k < $startDow; $k++)
                                        <span class="empty"></span>
                                    @endfor
                                    @for($d = 1; $d <= $daysInMonth; $d++)
                                        @php
                                            $date = \Carbon\Carbon::create($year, $m, $d);
                                            $isToday = $date->isToday();
                                            $isWeekend = $date->isWeekend();
                                        @endphp
                                        <span class="day {{ $isWeekend ? 'weekend' : '' }} {{ $isToday ? 'highlight' : '' }}">{{ $d }}</span>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="legend reveal">
                    <span class="legend-item"><span class="legend-dot today"></span> Hari ini</span>
                    <span class="legend-item"><span class="legend-dot weekend"></span> Akhir pekan</span>
                </div>
            </div>
        </section>

        <!-- ======= FAQ ======= -->
        <section id="faq" class="faq section-bg">
            <div class="container">
                <div class="section-title py-1 reveal">
                    <span class="section-badge"><i class="fas fa-question-circle"></i> Bantuan</span>
                    <h2>FAQ</h2>
                    <p>Pertanyaan yang sering diajukan</p>
                </div>
                <div class="faq-list">
                    <ul>
                        <li class="reveal">
                            <i class="bx bx-help-circle icon-help"></i>
                            <a data-bs-toggle="collapse" class="collapse" data-bs-target="#faq-list-1">
                                Bagaimana cara mendaftar PPDB di MIS Nurul Ulum?
                                <i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i>
                            </a>
                            <div id="faq-list-1" class="collapse show" data-bs-parent=".faq-list">
                                <p>Pendaftaran dapat dilakukan secara langsung datang ke madrasah atau melalui website resmi kami pada halaman Pengumuman. Persyaratan dan jadwal pendaftaran akan diinformasikan melalui website ini.</p>
                            </div>
                        </li>
                        <li class="reveal">
                            <i class="bx bx-help-circle icon-help"></i>
                            <a data-bs-toggle="collapse" data-bs-target="#faq-list-2" class="collapsed">
                                Apa saja program unggulan di MIS Nurul Ulum?
                                <i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i>
                            </a>
                            <div id="faq-list-2" class="collapse" data-bs-parent=".faq-list">
                                <p>MIS Nurul Ulum memiliki program unggulan seperti Tahfidz Al-Qur'an, pembiasaan sholat dhuha dan dhuhur berjamaah, ekstrakurikuler pramuka, drumband, serta kegiatan seni dan olahraga.</p>
                            </div>
                        </li>
                        <li class="reveal">
                            <i class="bx bx-help-circle icon-help"></i>
                            <a data-bs-toggle="collapse" data-bs-target="#faq-list-3" class="collapsed">
                                Bagaimana cara menghubungi pihak madrasah?
                                <i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i>
                            </a>
                            <div id="faq-list-3" class="collapse" data-bs-parent=".faq-list">
                                <p>Anda dapat menghubungi kami melalui telepon di nomor yang tertera pada halaman Profil, atau datang langsung ke madrasah pada jam kerja. Informasi kontak lengkap tersedia di bagian bawah website ini.</p>
                            </div>
                        </li>
                        <li class="reveal">
                            <i class="bx bx-help-circle icon-help"></i>
                            <a data-bs-toggle="collapse" data-bs-target="#faq-list-4" class="collapsed">
                                Apakah MIS Nurul Ulum menerima siswa baru setiap tahun?
                                <i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i>
                            </a>
                            <div id="faq-list-4" class="collapse" data-bs-parent=".faq-list">
                                <p><b>Ya.</b> Kami menerima peserta didik baru setiap tahun ajaran. Informasi mengenai PPDB akan diumumkan melalui website ini dan papan pengumuman madrasah.</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

    </main>

    <!-- ======= Footer ======= -->
    <footer id="footer">
        <div class="footer-top">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-5 col-md-6 footer-contact">
                        <h3 class="reveal footer-brand">
                            <img src="{{ asset('img/logo2.png') }}" alt="Logo MIS Nurul Ulum">
                            <span>MIS <em>Nurul Ulum</em></span>
                        </h3>
                        <p class="reveal">
                            {{ $profil->alamat ?? '...' }}<br><br>
                            <strong>Phone :</strong> {{ $profil->telepon ?? '...' }}<br>
                            <strong>Email :</strong> {{ $profil->email ?? '...' }}
                        </p>
                    </div>
                    <div class="col-lg-3 col-md-6 footer-links">
                        <h4 class="reveal">Menu Halaman</h4>
                        <ul class="reveal">
                            <li><i class="bx bx-chevron-right"></i> <a href="#">Home</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="#profil">Profil</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="#pengumuman">Pengumuman</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="#galery">Galery</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="#kalender">Kalender Masehi</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="#faq">FAQ</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-4 col-md-6 footer-contact">
                        <h4 class="reveal">Hubungi Kami</h4>
                        <ul class="footer-quick reveal">
                            <li><i class="fas fa-map-marker-alt"></i> {{ $profil->alamat ?? '...' }}</li>
                            <li><i class="fas fa-phone-alt"></i> {{ $profil->telepon ?? '...' }}</li>
                            <li><i class="fas fa-envelope"></i> {{ $profil->email ?? '...' }}</li>
                        </ul>
                        <div class="footer-social reveal">
                            <a href="#" aria-label="Facebook" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" aria-label="Instagram" title="Instagram"><i class="fab fa-instagram"></i></a>
                            <a href="#" aria-label="WhatsApp" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container d-md-flex py-4">
            <div class="me-md-auto text-center text-md-start">
                <div class="copyright">
                    &copy; <strong>Nurul Ulum</strong>.
                </div>
            </div>
        </div>
    </footer>

    <div id="preloader"></div>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    @include('component.footer')
    @include('component.script')

    <script>
        $(document).on('click', '.btn_nisn', function() {
            var url = "/api";
            var nisn = $("input#nisn").val();
            $.get(url + "/" + nisn, function(data) {
                    $('td#nama').html(data.nama);
                    $('td#kelas').html(data.kelas.nama_kelas);
                    $('label#poin_field').html(data.poin + " Poin");
                    var formattedDate = new Date(data.updated_at);
                    var d = formattedDate.getDate();
                    var m = formattedDate.getMonth();
                    m += 1;
                    var y = formattedDate.getFullYear();
                    $("label#update_poin").html("Diperbarui pada " + d + "-" + m + "-" + y);
                    var elems = $('#alertSuccess').html("Data Ditampilkan..");
                    var bNisn = $('#before_nisn');
                    var aNisn = $('#after_nisn');
                    for (var i = 0; i < elems.length; i += 1) {
                        elems[i].style.display = 'block';
                    }
                    for (var i = 0; i < bNisn.length; i += 1) {
                        bNisn[i].style.display = 'none';
                    }
                    for (var i = 0; i < aNisn.length; i += 1) {
                        aNisn[i].style.display = 'block';
                    }
                    setTimeout(() => {
                        elems.fadeOut('slow');
                    }, 2000);
                })
                .fail(function() {
                    swal({
                        title: "Data tidak ditemukan!",
                        icon: "warning",
                        dangerMode: true,
                        button: true,
                    });
                });
        });

        function history() {
            $("form#form_history").submit();
        }

        document.querySelectorAll('.galery-card img').forEach(function(img) {
            if (img.complete) {
                img.classList.add('loaded');
            } else {
                img.addEventListener('load', function() {
                    img.classList.add('loaded');
                });
            }
        });
    </script>

    <script src="../assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="../assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="../assets/vendor/php-email-form/validate.js"></script>
    <script src="../assets/js/main.js"></script>
    <script src="../js/loading.js"></script>

</body>
</html>
