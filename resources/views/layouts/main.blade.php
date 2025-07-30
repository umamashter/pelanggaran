<!DOCTYPE html>
<html lang="en">

<head>
    <base href="../../">
    @include('component.head')
    <title>E-Violation | @yield('title')</title>
    <link rel="stylesheet" href="../css/nav-side-bar.css">
    <link rel="stylesheet" href="../css/pages.css">
    <!-- <link rel="stylesheet" href="../css/datatables.css"> -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('css')

</head>

<body class="sidebar-is-reduced sidebar-is-expanded">

    @if (Auth::user()->info == true)
        @if (Auth::user()->role == 1)
            <div class="sidebar-is-reduced">
                @include('component.admin.sidebar')
                @include('component.admin.navbar')
            </div>
        @endif
        @if (Auth::user()->role == 2)
            <div class="sidebar-is-reduced">
                @include('component.guru.sidebar')
                @include('component.guru.navbar')
            </div>
        @endif
        @if (Auth::user()->role == 3)
            <div class="sidebar-is-reduced">
                @include('component.siswa.sidebar')
                @include('component.siswa.navbar')
            </div>
        @endif
        @if (Auth::user()->role == 4)
            <div class="sidebar-is-reduced">
                @include('component.bk.sidebar')
                @include('component.bk.navbar')
            </div>
        @endif
    @endif

    <main class="l-main">
        <div class="content-wrapper content-wrapper--with-bg">

            <div class="page-title-content animate__animated animate__fadeInDown">
                <h1 style="margin-bottom: 0; font-weight: 600;">@yield('title')</h1>
            </div>
            @if (auth()->user()->role == 1)
                @yield('content')
            @endif
            @if (auth()->user()->role == 2)
                @yield('content')
            @endif
            @if (auth()->user()->role == 3)
                @yield('content')
            @endif
            @if (auth()->user()->role == 4)
                @yield('content')
            @endif
        </div>
        @include('component.footer')

    </main>

    @include('component.script')
    @include('sweetalert::alert')

</body>

</html>
