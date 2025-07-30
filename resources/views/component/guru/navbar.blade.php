<header class="l-header">
    <div class="l-header__inner clearfix">
        <div class="c-header-icon js-hamburger" style="border-left: 0; border-right: 1px solid #ccc;">
            <div class="hamburger-toggle">
                <span class="bar-top"></span>
                <span class="bar-mid"></span>
                <span class="bar-bot"></span>
            </div>
        </div>

        <div class="c-header-icon lol logo" style="border-left: 0; border-right: 1px solid #fff;">
            <img src="../img/smkn1.png" width="35">
        </div>

        <div class="c-title">
            <h1>@yield('title')</h1>
        </div>

        {{-- <div class="c-search"></div> --}}

        <div class="ms-auto navbar-nav">
            <!-- Authentication Links -->
            <div class="nav-item dropdown px-3">
                <a id="navbarDropdown" class="name-tag nav-link dropdown-toggle c-header-icon userDropdown me-2"
                    href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                    v-pre>
                    <!-- <i class='bx bxs-user-circle'></i> -->
                    <div class="text-small d-inline-flex ms-1 ">{{ strtok(auth()->user()->name, ' ') }}</div>
                </a>

                <div class="dropdown-menu dropdown-menu-end me-2" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item py-2" href="/">
                        {{ __('Kembali') }}
                    </a>

                    <a class="dropdown-item py-2" href="/guru/ubah-pass">
                        {{ __('Ubah Password') }}
                    </a>

                    <a class="dropdown-item py-2" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>

        <div class="none c-header-icon-hp">
            <i class='bx bx-menu-alt-right' id="btn"></i>
        </div>

    </div>
</header>

<input type="checkbox" id="checki" class="ch">
<ul class="hp-ul" for=checki style="z-index: 2;">
    <a href="/home">
        <li class="{{ request()->is('home*') ? 'active' : '' }} hp-li" title="Dashboard">
            <i class='bx bxs-grid-alt'></i>
            Dashboard
        </li>
    </a>
    <a href="/guru/daftar-siswa">
        <li class="{{ request()->is('guru/daftar-siswa*') ? 'active' : '' }} hp-li" title="Daftar Siswa">
            <i class="fas fa-user-graduate"></i>
            Daftar Siswa
        </li>
    </a>
    <a href="/guru/histori">
        <li class="{{ request()->is('guru/histori*') ? 'active' : '' }} hp-li" title="Histori Siswa">
            <i class="fas fa-calendar-alt"></i>
            Histori Siswa
        </li>
    </a>
    <a href="/guru/penanganan">
        <li class="{{ request()->is('guru/penanganan*') ? 'active' : '' }} hp-li" title="Penanganan">
            <i class="fas fa-user-cog"></i>
            Penanganan
        </li>
    </a>
</ul>
