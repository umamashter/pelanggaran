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
        :root {
            --ms-primary: #16a34a;
            --ms-primary-dark: #15803d;
            --ms-primary-light: #dcfce7;
            --ms-bg: #f8fafc;
        }

        #header {
            background: linear-gradient(135deg, rgba(45, 85, 92, 0.92), rgba(45, 85, 92, 0.8)) !important;
            backdrop-filter: blur(20px) !important;
            -webkit-backdrop-filter: blur(16px) !important;
            border-bottom: 1px solid rgba(255,255,255,.06) !important;
            box-shadow: 0 4px 30px rgba(0,0,0,.1);
            padding: 6px 0 !important;
        }

        #header .logo a {
            font-weight: 900;
            font-size: 22px;
            font-family: 'Poppins', sans-serif;
            letter-spacing: 1.5px;
            display: flex;
            align-items: center;
            gap: 12px;
            background: linear-gradient(135deg, #fff 60%, #22c55e);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: none;
        }

        #header .logo a::before {
            content: '';
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #22c55e;
            display: inline-block;
            box-shadow: 0 0 16px rgba(34,197,94,.7);
            flex-shrink: 0;
            -webkit-text-fill-color: initial;
        }

        .navbar .nav-link {
            font-weight: 500 !important;
            padding: 7px 14px !important;
            border-radius: 20px !important;
            transition: all .3s ease !important;
            font-size: 12px !important;
            color: rgba(255,255,255,.7) !important;
        }

        .navbar .nav-link:hover,
        .navbar .nav-link:focus,
        .navbar .nav-link:active {
            background: rgba(255,255,255,.08) !important;
            color: #fff !important;
            outline: none !important;
        }

        .navbar .nav-link.active {
            background: rgba(34,197,94,.15) !important;
            color: #22c55e !important;
        }

        .navbar ul li a:focus,
        .navbar ul li a:active,
        .navbar ul li a:focus-visible {
            outline: none !important;
            box-shadow: none !important;
            color: inherit !important;
        }

        .appointment-btn {
            background: linear-gradient(135deg, #22c55e, #16a34a) !important;
            color: #fff !important;
            font-weight: 700 !important;
            border-radius: 20px !important;
            padding: 8px 24px !important;
            box-shadow: 0 4px 16px rgba(22,163,74,.35) !important;
            transition: all .3s ease !important;
            border: none !important;
            font-size: 14px !important;
        }

        .appointment-btn:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 28px rgba(22,163,74,.45) !important;
            color: #fff !important;
        }

        #hero {
            background: linear-gradient(135deg, #2D575D 0%, #16a34a 50%, #2D575D 100%) !important;
            background-size: 200% 200% !important;
            background-attachment: scroll !important;
            animation: gradientShift 15s ease infinite;
            height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            margin-bottom: 0 !important;
        }

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
            background: radial-gradient(circle, rgba(255,255,255,.06) 0%, transparent 70%);
            top: -200px;
            right: -200px;
            animation: floatOrb 12s ease-in-out infinite;
        }

        #hero::after {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(34,197,94,.08) 0%, transparent 70%);
            bottom: -150px;
            left: -150px;
            animation: floatOrb 10s ease-in-out infinite reverse;
        }

        .hero-particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,.04);
            pointer-events: none;
        }
        .hero-particle:nth-child(1) {
            width: 300px; height: 300px;
            top: 10%; left: 5%;
            animation: floatParticle 18s ease-in-out infinite;
        }
        .hero-particle:nth-child(2) {
            width: 200px; height: 200px;
            bottom: 15%; right: 10%;
            animation: floatParticle 14s ease-in-out infinite reverse;
        }
        .hero-particle:nth-child(3) {
            width: 120px; height: 120px;
            top: 60%; left: 60%;
            background: rgba(34,197,94,.06);
            animation: floatParticle 20s ease-in-out infinite 2s;
        }
        .hero-particle:nth-child(4) {
            width: 80px; height: 80px;
            top: 20%; right: 35%;
            background: rgba(255,255,255,.06);
            animation: floatParticle 16s ease-in-out infinite 1s;
        }
        .hero-particle:nth-child(5) {
            width: 160px; height: 160px;
            bottom: 30%; left: 30%;
            background: rgba(34,197,94,.05);
            animation: floatParticle 22s ease-in-out infinite 3s;
        }

        @keyframes floatOrb {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(50px, -50px) scale(1.05); }
        }

        @keyframes floatParticle {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(40px, -30px) rotate(120deg); }
            66% { transform: translate(-20px, 40px) rotate(240deg); }
        }

        .hero-logo-wrap {
            position: relative;
            display: inline-block;
        }
        .hero-logo-wrap::before {
            content: '';
            position: absolute;
            inset: -8px;
            border-radius: 50%;
            border: 2px solid rgba(255,255,255,.15);
            animation: pulseRing 3s ease-in-out infinite;
        }
        .hero-logo-wrap::after {
            content: '';
            position: absolute;
            inset: -16px;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,.08);
            animation: pulseRing 3s ease-in-out infinite 1s;
        }
        @keyframes pulseRing {
            0%, 100% { transform: scale(1); opacity: .5; }
            50% { transform: scale(1.1); opacity: 0; }
        }

        .scroll-indicator {
            position: absolute;
            bottom: 30px;
            left: 0;
            right: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            animation: fadeInUp 1s ease 2s both;
        }
        .scroll-indicator span {
            font-size: 10px;
            color: rgba(255,255,255,.4);
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .scroll-indicator .chevron {
            width: 24px;
            height: 24px;
            border-right: 2px solid rgba(255,255,255,.3);
            border-bottom: 2px solid rgba(255,255,255,.3);
            transform: rotate(45deg);
            animation: bounceChevron 2s ease-in-out infinite;
        }
        @keyframes bounceChevron {
            0%, 100% { transform: rotate(45deg) translate(0, 0); opacity: .3; }
            50% { transform: rotate(45deg) translate(4px, 4px); opacity: .8; }
        }

        .hero-title-line {
            display: inline-block;
            overflow: hidden;
            white-space: nowrap;
            border-right: 3px solid #22c55e;
            animation: typewriter 2.5s steps(20) 1s forwards, blink 1s step-end 5.5s forwards;
            max-width: 0;
        }
        @keyframes typewriter {
            to { max-width: 100%; }
        }
        @keyframes blink {
            0%, 100% { border-color: #22c55e; }
            50% { border-color: transparent; }
        }

        #hero h1 {
            color: #fff !important;
            font-size: 3rem;
            font-weight: 800;
            font-family: 'Poppins', sans-serif;
        }

        #hero h2 {
            color: rgba(255,255,255,.75) !important;
        }

        #hero .btn-get-started {
            background: linear-gradient(135deg, #22c55e, #16a34a) !important;
            color: #fff !important;
            font-weight: 700 !important;
            border-radius: 50px !important;
            padding: 14px 40px !important;
            box-shadow: 0 8px 30px rgba(22,163,74,.4) !important;
            border: none !important;
            font-size: 16px !important;
            letter-spacing: .5px !important;
            transition: all .3s ease !important;
        }

        #hero .btn-get-started:hover {
            transform: translateY(-3px) !important;
            box-shadow: 0 12px 40px rgba(22,163,74,.5) !important;
            background: linear-gradient(135deg, #16a34a, #15803d) !important;
        }

        .section-title h2 {
            font-weight: 800 !important;
            color: #0f172a !important;
            font-size: 32px !important;
            position: relative;
            display: inline-block;
        }
        .section-title h2::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 3px;
            background: linear-gradient(90deg, #16a34a, #22c55e);
            border-radius: 2px;
            transition: width .8s ease;
        }
        .section-title h2.animate-underline::after {
            width: 60px;
        }

        .section-title p {
            color: #64748b !important;
        }

        .card-modern {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0,0,0,.06), 0 2px 8px rgba(0,0,0,.04);
            transition: all .3s;
            overflow: hidden;
        }

        .card-modern:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(0,0,0,.1);
        }

        .card-modern .card-body {
            padding: 24px;
        }

        .icon-ms {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
        }

        .icon-ms.green {
            background: var(--ms-primary-light);
            color: var(--ms-primary);
        }

        .icon-ms.gold {
            background: var(--ms-primary-light);
            color: var(--ms-primary);
        }

        .icon-ms.blue {
            background: #eff6ff;
            color: #2563eb;
        }

        .profile-section {
            background: #fff;
            padding: 80px 0;
        }

        .profile-section .visi-card {
            background: linear-gradient(135deg, var(--ms-primary-light), #fff);
            border-radius: 16px;
            padding: 28px;
            border-left: 4px solid var(--ms-primary);
            margin-bottom: 20px;
        }

        .profile-section .visi-card h5 {
            color: var(--ms-primary);
            font-weight: 700;
        }

        .profile-section .visi-card p {
            color: #334155;
            font-size: 14px;
            margin-bottom: 0;
        }

        .profile-img-wrap {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0,0,0,.1);
        }

        .profile-img-wrap img {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .contact-item:last-child {
            border-bottom: none;
        }

        .contact-item .icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        .contact-item .icon.green {
            background: var(--ms-primary-light);
            color: var(--ms-primary);
        }

        .contact-item .label {
            font-size: 12px;
            color: #94a3b8;
            margin-bottom: 0;
        }

        .contact-item .value {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0;
        }

        .map-wrap {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0,0,0,.06);
        }

        .map-wrap iframe {
            width: 100%;
            height: 300px;
            border: 0;
        }

        .announcement-section {
            background: #f8fafc;
            padding: 80px 0;
        }

        .announcement-card {
            border: none;
            border-radius: 14px;
            box-shadow: 0 2px 12px rgba(0,0,0,.05);
            transition: all .3s;
            overflow: hidden;
        }

        .announcement-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0,0,0,.08);
        }

        .announcement-card .card-body {
            padding: 20px;
        }

        .announcement-date {
            font-size: 12px;
            color: #94a3b8;
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 8px;
        }

        .announcement-date i {
            color: var(--ms-primary);
        }

        .announcement-card h5 {
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .announcement-card p {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 0;
        }

        .calendar-section {
            background: #fff;
            padding: 80px 0;
        }

        .calendar-table-wrap {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0,0,0,.06);
            border: 1px solid #e2e8f0;
        }

        .calendar-table-wrap table {
            margin-bottom: 0;
        }

        .calendar-table-wrap thead th {
            background: var(--ms-primary);
            color: #fff;
            font-weight: 600;
            font-size: 13px;
            padding: 12px 16px;
            text-align: center;
            border: none;
        }

        .calendar-table-wrap tbody td {
            padding: 12px 16px;
            font-size: 13px;
            color: #334155;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .calendar-table-wrap tbody tr:last-child td {
            border-bottom: none;
        }

        .calendar-table-wrap tbody tr:hover td {
            background: #f8fafc;
        }

        .event-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
        }

        .month-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0,0,0,.06);
            border: 1px solid #e2e8f0;
            overflow: hidden;
            transition: transform .3s ease, box-shadow .3s ease;
        }
        .month-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(22, 163, 74, .12);
        }
        .month-header {
            background: linear-gradient(135deg, #16a34a, #22c55e);
            color: #fff;
            text-align: center;
            font-weight: 700;
            font-size: 15px;
            padding: 12px 8px;
            letter-spacing: .5px;
        }
        .month-days-header {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            padding: 8px 6px 4px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }
        .month-days-header span {
            text-align: center;
            font-size: 10px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
        }
        .month-days-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            padding: 4px 6px 10px;
            gap: 2px;
        }
        .month-days-grid .day {
            text-align: center;
            font-size: 12px;
            padding: 4px 0;
            color: #334155;
            border-radius: 6px;
            transition: background .2s;
        }
        .month-days-grid .day:hover {
            background: #dcfce7;
            cursor: default;
        }
        .month-days-grid .weekend {
            color: #ef4444;
            font-weight: 600;
        }
        .month-days-grid .highlight {
            background: #16a34a;
            color: #fff;
            font-weight: 700;
        }
        .month-days-grid .highlight:hover {
            background: #15803d;
        }
        .month-days-grid .empty {
            display: block;
        }
        @media (max-width: 768px) {
            .month-days-header span { font-size: 9px; }
            .month-days-grid .day { font-size: 10px; padding: 3px 0; }
            .month-header { font-size: 13px; padding: 10px 6px; }
        }

        .event-badge.academic { background: #dcfce7; color: #16a34a; }
        .event-badge.holiday { background: #fef2f2; color: #dc2626; }
        .event-badge.exam { background: var(--ms-primary-light); color: var(--ms-primary); }
        .event-badge.national { background: #dbeafe; color: #2563eb; }
        .event-badge.islamic { background: #f0fdf4; color: #2D575D; }

        #footer {
            background: linear-gradient(135deg, #2D575D, #16a34a) !important;
            color: rgba(255,255,255,.8);
        }

        #footer .footer-top {
            background: transparent !important;
            padding: 40px 0;
        }

        #footer h3, #footer h4 {
            color: #fff !important;
            font-weight: 700;
        }

        #footer a {
            color: rgba(255,255,255,.6) !important;
            transition: all .3s ease;
            position: relative;
        }

        #footer .footer-links ul li a::before {
            content: '›';
            margin-right: 6px;
            transition: margin .3s ease;
        }
        #footer .footer-links ul li a:hover::before {
            margin-right: 12px;
        }

        #footer a:hover {
            color: #22c55e !important;
        }

        #footer .footer-contact p {
            color: rgba(255,255,255,.7);
        }

        #footer .copyright strong {
            color: #22c55e;
        }

        body > .back-to-top {
            background: #16a34a !important;
            color: #fff !important;
            transition: all .3s ease !important;
        }
        body > .back-to-top:hover {
            background: #22c55e !important;
            transform: translateY(-3px);
            box-shadow: 0 4px 16px rgba(22,163,74,.4);
        }

        .section-bg {
            background: #f8fafc !important;
        }

        @media (max-width: 768px) {
            #hero h1 { font-size: 2rem; }
            #hero { min-height: 70vh; }
        }

        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all .6s ease-out;
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        .reveal-delay-1 { transition-delay: .1s; }
        .reveal-delay-2 { transition-delay: .2s; }
        .reveal-delay-3 { transition-delay: .3s; }
        .reveal-delay-4 { transition-delay: .4s; }

        .month-card, .announcement-card {
            transition: all .4s cubic-bezier(.4,0,.2,1);
        }
        .month-card:hover, .announcement-card:hover {
            transform: translateY(-6px) scale(1.01);
            box-shadow: 0 12px 32px rgba(22,163,74,.12);
        }

        .card-modern:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(0,0,0,.1);
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ===== Galery ===== */
        .galery-section {
            background: #f8fafc;
            padding: 80px 0;
        }
        .galery-card {
            position: relative;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0,0,0,.08);
            aspect-ratio: 4 / 3;
            cursor: pointer;
        }
        .galery-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .5s ease;
        }
        .galery-card:hover img {
            transform: scale(1.08);
        }
        .galery-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0,0,0,.72), transparent 62%);
            display: flex;
            align-items: flex-end;
            padding: 14px;
            opacity: 0;
            transition: opacity .3s ease;
        }
        .galery-card:hover .galery-overlay {
            opacity: 1;
        }
        .galery-overlay h5 {
            color: #fff;
            font-size: 14px;
            margin: 0;
            font-weight: 600;
        }
        html.dark-mode .galery-section {
            background: var(--bg-body) !important;
        }
        html.dark-mode .galery-card {
            box-shadow: 0 4px 16px rgba(0,0,0,.3);
        }

        @media (max-width: 991px) {
            .mobile-nav-toggle {
                display: none !important;
            }
            #header .container {
                padding-top: 8px;
                padding-bottom: 8px;
                gap: 8px;
            }
            #header .logo {
                font-size: 14px;
                flex: 1;
                width: auto;
                text-align: left;
            }
            #header .logo a {
                font-size: 14px;
            }
            #header .navbar {
                display: none;
            }
            #header .appointment-btn {
                font-size: 10px;
                padding: 3px 8px;
                border-radius: 5px;
                white-space: nowrap;
            }
            #header .d-flex.align-items-center.gap-2 {
                gap: 6px;
            }
            #hero .btn-get-started,
            #hero .hero-mulai-btn {
                font-size: 10px !important;
                padding: 6px 14px !important;
                margin-top: 12px !important;
            }
            #hero .hero-logo-wrap img {
                width: 70px !important;
            }
            #hero h1 {
                font-size: 1.3rem !important;
                margin-top: 1rem !important;
            }
            #hero h2 {
                font-size: 1rem !important;
            }
            #hero h4 {
                font-size: 0.85rem !important;
                margin-top: 0.5rem !important;
            }
            #preloader:before {
                border-color: #16a34a !important;
                border-top-color: #dcfce7 !important;
            }
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
</head>

<body>
    @include('component.loading')

    <!-- ======= Header ======= -->
    <header id="header" class="fixed-top">
        <div class="container d-flex align-items-center">
            <h1 class="logo me-auto"><a href="/">MIS NURUL ULUM</a></h1>
            <nav id="navbar" class="navbar order-last order-lg-0">
                <ul>
                    <li><a class="nav-link scrollto animate__animated animate__fadeInDownBig"
                            style="animation-delay:.5s; color:#dee2e6;" href="#">Beranda</a></li>
                    <li><a class="nav-link scrollto animate__animated animate__fadeInDownBig"
                            style="animation-delay:.4s; color:#dee2e6;" href="#profil">Profil</a></li>
                    <li><a class="nav-link scrollto animate__animated animate__fadeInDownBig"
                            style="animation-delay:.3s; color:#dee2e6;" href="#pengumuman">Pengumuman</a></li>
                    <li><a class="nav-link scrollto animate__animated animate__fadeInDownBig"
                            style="animation-delay:.25s; color:#dee2e6;" href="#galery">Galery</a></li>
                    <li><a class="nav-link scrollto animate__animated animate__fadeInDownBig"
                            style="animation-delay:.2s; color:#dee2e6;" href="#kalender">Kalender Masehi</a></li>
                    <li><a class="nav-link scrollto animate__animated animate__fadeInDownBig"
                            style="animation-delay:.1s; color:#dee2e6;" href="#faq">FAQ</a></li>
                    <li class="d-flex align-items-center" style="margin-left:4px;">
                        <a class="theme-toggle" href="#" title="Ganti tema">
                            <i class="fas fa-moon"></i>
                        </a>
                    </li>
                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
            </nav>

            <div class="d-flex align-items-center gap-2">
                <a class="theme-toggle d-lg-none" href="#" title="Ganti tema" style="color:rgba(255,255,255,.7);font-size:16px;">
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
        <div class="container text-center position-relative">
            <div class="text-center mt-3">
                <div class="hero-logo-wrap">
                    <img src="{{ asset('img/logo2.png') }}" style="width:120px; height:auto; filter:drop-shadow(0 4px 12px rgba(0,0,0,.2));">
                </div>
            </div>
            <h1 class="fw-bold mt-4 text-white" style="animation:fadeInUp .8s ease 1.2s both;">
                Selamat Datang
            </h1>
            <h2 class="hero-title-line" style="font-size:1.5rem; color:rgba(255,255,255,.75); display:inline-block;">
                Sistem Informasi Akademik
            </h2>
            <h4 class="text-white mt-2 fw-bold" style="font-family: 'Poppins', sans-serif; letter-spacing:2px; animation:fadeInUp .6s ease .8s both;">
                MIS Nurul Ulum
            </h4>
            <div style="animation:fadeInUp .8s ease 2s both;" class="d-md-block">
                <a href="#profil"
                    class="btn-get-started scrollto mt-3 d-inline-block hero-mulai-btn"
                    style="display:inline-block;">
                    <i class="fas fa-rocket me-2"></i>Mulai Sekarang
                </a>
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
                        <div class="visi-card" style="border-left-color:var(--ms-primary);">
                            <h5 style="color:var(--ms-primary);"><i class="fas fa-flag me-2"></i>Misi</h5>
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
                            <img src="{{ $profil && $profil->foto ? asset('storage/' . $profil->foto) : asset('img/logo2.png') }}" alt="Madrasah" style="background:#f1f5f9; padding:60px; object-fit:contain;">
                        </div>
                    </div>

                    <!-- Kontak -->
                    <div class="col-lg-6 reveal">
                        <div class="card card-modern h-100">
                            <div class="card-body">
                                <h5 class="fw-bold mb-3" style="color:#0f172a;">
                                    <i class="fas fa-address-card me-2" style="color:var(--ms-primary);"></i>
                                    Kontak & Alamat
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
                                    {{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('d F Y') }}
                                </div>
                                <h5>{{ $p->judul }}</h5>
                                <p>{{ Str::limit(strip_tags($p->isi), 120) }}</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-bullhorn" style="font-size:3rem;color:#d1d5db;"></i>
                        <p class="mt-3 text-muted">Belum ada pengumuman</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- ======= Galery ======= -->
        <section id="galery" class="galery-section">
            <div class="container">
                <div class="section-title text-center mb-5 reveal">
                    <h2>Galery</h2>
                    <p>Momen kegiatan di MIS Nurul Ulum</p>
                </div>

                <div class="row g-3">
                    @forelse($galery as $g)
                    <div class="col-lg-3 col-md-6 reveal">
                        <div class="galery-card">
                            <img src="{{ asset($g->foto) }}" alt="{{ $g->judul }}" loading="lazy">
                            <div class="galery-overlay">
                                <h5>{{ $g->judul }}</h5>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-images" style="font-size:3rem;color:#d1d5db;"></i>
                        <p class="mt-3 text-muted">Belum ada foto galeri</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- ======= Kalender Masehi ======= -->
        <section id="kalender" class="calendar-section">
            <div class="container">
                <div class="section-title text-center mb-5 reveal">
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
            </div>
        </section>

        <!-- ======= FAQ ======= -->
        <section id="faq" class="faq section-bg">
            <div class="container">
                <div class="section-title py-1 reveal">
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
                        <li class="reveal" data-aos-delay="100">
                            <i class="bx bx-help-circle icon-help"></i>
                            <a data-bs-toggle="collapse" data-bs-target="#faq-list-2" class="collapsed">
                                Apa saja program unggulan di MIS Nurul Ulum?
                                <i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i>
                            </a>
                            <div id="faq-list-2" class="collapse" data-bs-parent=".faq-list">
                                <p>MIS Nurul Ulum memiliki program unggulan seperti Tahfidz Al-Qur'an, pembiasaan sholat dhuha dan dhuhur berjamaah, ekstrakurikuler pramuka, drumband, serta kegiatan seni dan olahraga.</p>
                            </div>
                        </li>
                        <li class="reveal" data-aos-delay="200">
                            <i class="bx bx-help-circle icon-help"></i>
                            <a data-bs-toggle="collapse" data-bs-target="#faq-list-3" class="collapsed">
                                Bagaimana cara menghubungi pihak madrasah?
                                <i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i>
                            </a>
                            <div id="faq-list-3" class="collapse" data-bs-parent=".faq-list">
                                <p>Anda dapat menghubungi kami melalui telepon di nomor yang tertera pada halaman Profil, atau datang langsung ke madrasah pada jam kerja. Informasi kontak lengkap tersedia di bagian bawah website ini.</p>
                            </div>
                        </li>
                        <li class="reveal" data-aos-delay="300">
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
                <div class="row">
                    <div class="col-lg-5 col-md-6 footer-contact">
                        <h3 class="reveal">E-Book</h3>
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
