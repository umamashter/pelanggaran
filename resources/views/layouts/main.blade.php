<!DOCTYPE html>
<html lang="en">
<head>
    <script>
        (function(){var k='theme-preference',h=document.documentElement;if(localStorage.getItem(k)==='dark'||(!localStorage.getItem(k)&&window.matchMedia&&window.matchMedia('(prefers-color-scheme:dark)').matches))h.classList.add('dark-mode');})();
    </script>
    <base href="../../">
    @include('component.head')
    <title>MIS Nurul Ulum | @yield('title')</title>
    <link rel="stylesheet" href="../css/nav-side-bar.css">
    <link rel="stylesheet" href="../css/pages.css">
    <!-- <link rel="stylesheet" href="../css/datatables.css"> -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('css')
    <style>
        /* Global theme — Master Siswa design system */
        :root {
            --ms-primary: #16a34a;
            --ms-primary-dark: #15803d;
            --ms-primary-light: #dcfce7;
            --ms-bg: #f5f7fb;
            --ms-border: #e2e8f0;
            --ms-text: #1e293b;
            --ms-text-soft: #64748b;
        }

        body {
            font-family: 'Inter', 'Poppins', system-ui, sans-serif;
            background: var(--ms-bg);
        }

        .header-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: linear-gradient(135deg, #16a34a, #22c55e);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 24px;
            box-shadow: 0 4px 14px rgba(22, 163, 74, .3);
            flex-shrink: 0;
        }

        .badge-modern {
            display: inline-flex;
            align-items: center;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            white-space: nowrap;
        }

        .badge-ta {
            background: #eff6ff;
            color: #1d4ed8;
        }

        .badge-semester {
            background: #f0fdf4;
            color: #16a34a;
        }

        .table-card {
            border: none;
            border-radius: 18px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, .06), 0 2px 8px rgba(0, 0, 0, .04);
        }

        .table-card .card-body {
            padding: 16px 20px 20px;
        }

        .table-card .card-header {
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            padding: 16px 20px;
        }

        .info-card-modern {
            background: #eff6ff;
            border-left: 4px solid #3b82f6;
            border-radius: 12px;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #1e40af;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .06);
        }

        .info-card-modern .info-icon {
            font-size: 18px;
            color: #3b82f6;
            flex-shrink: 0;
        }

        .btn-filter {
            background: #fff;
            border: 1.5px solid var(--ms-border);
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 500;
            color: #475569;
            transition: all .25s;
        }

        .btn-filter:hover {
            border-color: var(--ms-primary);
            color: var(--ms-primary);
            background: var(--ms-primary-light);
        }

        .hide-nav-logo .l-header .c-header-icon.lol.logo {
            display: none !important;
        }

        @media (min-width: 769px) {
            .hide-nav-hamburger .l-header .js-hamburger {
                display: none !important;
            }
        }

        .l-header .c-header-icon.lol.logo {
            display: none !important;
        }

        @media (min-width: 769px) {
            .l-header .js-hamburger {
                display: none !important;
            }
        }

        /* Mobile navigation: hide old sidebar, show hamburger + dropdown */
        @media (max-width: 768px) {
            .c-header-icon-hp {
                display: none !important;
            }

            #checki:checked~.hp-ul {
                display: block !important;
            }

            /* Sembunyikan sidebar lama (guru/siswa/bk) di mobile */
            .l-sidebar {
                display: none !important;
            }

            .l-sidebar+.sidebar-spacer {
                display: none !important;
            }
        }

        @media (min-width: 769px) {

            .c-header-icon-hp,
            #checki,
            .hp-ul {
                display: none !important;
            }
        }

        .l-main {
            padding: 12px 24px !important;
        }

        .page-title-content {
            margin-bottom: 12px !important;
        }

        /* DataTables global styling */
        .dataTables_wrapper .dataTables_filter {
            float: none;
            text-align: right;
            margin-bottom: 8px;
        }

        .dataTables_wrapper .dataTables_filter label {
            position: relative;
            display: inline-flex;
            align-items: center;
            font-size: 0;
            line-height: 0;
            color: transparent;
        }

        .dataTables_wrapper .dataTables_filter label input {
            font-size: 14px;
            line-height: normal;
            color: var(--ms-text);
            height: 40px;
            border: 1.5px solid var(--ms-border);
            border-radius: 24px;
            padding: 0 16px 0 40px;
            width: 300px;
            background: #f8fafc url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2394a3b8' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z'/%3E%3C/svg%3E") 14px center no-repeat;
            background-size: 16px;
            transition: all .25s;
            outline: none;
        }

        .dataTables_wrapper .dataTables_filter label input:focus {
            border-color: var(--ms-primary);
            box-shadow: 0 0 0 3px rgba(22, 163, 74, .1);
            background-color: #fff;
        }

        .dataTables_wrapper .dataTables_length {
            float: left;
            font-size: 14px;
            color: var(--ms-text-soft);
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }

        .dataTables_wrapper .dataTables_info {
            font-size: 13px;
            color: var(--ms-text-soft);
            padding: 12px 0 0;
        }

        .dataTables_wrapper .dataTables_paginate {
            padding: 8px 0 0;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 8px !important;
            font-size: 13px;
            padding: 4px 12px !important;
            margin: 0 2px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: var(--ms-primary) !important;
            border-color: var(--ms-primary) !important;
            color: #fff !important;
        }

        @media print {

            form,
            button {
                display: none !important;
                /* Sembunyikan form & tombol cetak */
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            table,
            th,
            td {
                border: 1px solid #000;
                padding: 5px;
            }

            body {
                background: #fff;
            }
        }
    </style>
    <link rel="stylesheet" href="../css/dark-mode.css">
    <link rel="stylesheet" href="../css/loading.css">
</head>

<body>
    @include('component.loading')

    @include('component.admin.context-panel')

    @if (Auth::user()->info == true || request()->is('profil-saya*'))
    <div class="app-layout">

        @if (Auth::user()->role == 1)
        @include('component.admin.sidebar')
        <div class="sidebar-spacer"></div>
        <div class="app-content">
            @include('component.admin.navbar')
            <main class="l-main">
                <div class="content-wrapper content-wrapper--with-bg">
                    @yield('content')
                </div>
                @include('component.footer')
            </main>
        </div>
        @endif

        @if (Auth::user()->role == 2)
        @include('component.guru.sidebar')
        <div class="sidebar-spacer"></div>
        <div class="app-content">
            @include('component.guru.navbar')
            <main class="l-main">
                <div class="content-wrapper content-wrapper--with-bg">
                    <div class="page-title-content animate__animated animate__fadeInDown">
                        <h1 style="margin-bottom: 0; font-weight: 600;">@yield('title')</h1>
                    </div>
                    @yield('content')
                </div>
                @include('component.footer')
            </main>
        </div>
        @endif

        @if (Auth::user()->role == 3)
        @include('component.siswa.sidebar')
        <div class="sidebar-spacer"></div>
        <div class="app-content">
            @include('component.siswa.navbar')
            <main class="l-main">
                <div class="content-wrapper content-wrapper--with-bg">
                    <div class="page-title-content animate__animated animate__fadeInDown">
                        <h1 style="margin-bottom: 0; font-weight: 600;">@yield('title')</h1>
                    </div>
                    @yield('content')
                </div>
                @include('component.footer')
            </main>
        </div>
        @endif

        @if (Auth::user()->role == 4)
        @include('component.bk.sidebar')
        <div class="sidebar-spacer"></div>
        <div class="app-content">
            @include('component.bk.navbar')
            <main class="l-main">
                <div class="content-wrapper content-wrapper--with-bg">
                    <div class="page-title-content animate__animated animate__fadeInDown">
                        <h1 style="margin-bottom: 0; font-weight: 600;">@yield('title')</h1>
                    </div>
                    @yield('content')
                </div>
                @include('component.footer')
            </main>
        </div>
        @endif

    </div>
    @endif

    @include('component.script')
    @include('sweetalert::alert')
    <script src="../js/loading.js"></script>

</body>

</html>