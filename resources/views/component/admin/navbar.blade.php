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
                    <div class="text-small d-inline-flex ms-1">{{ strtok(auth()->user()->name, ' ') }}</div>
                </a>

                <div class="dropdown-menu dropdown-menu-end me-2" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item py-2" href="/">
                        {{ __('Kembali') }}
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
<ul class="hp-ul" for=checki style="z-index: 1;">
    <a href="/home">
        <li class="{{ request()->is('home*') ? 'active' : '' }} hp-li" title="Dashboard">
            <i class='bx bxs-grid-alt'></i>
            Dashboard
        </li>
    </a>
    <a href="/master-siswa">
        <li class="{{ request()->is('master-siswa*', 'pelanggaran*') ? 'active' : '' }} hp-li" title="Master Siswa">
            <i class='fas fa-user-graduate'></i>
            Master Siswa
        </li>
    </a>
    <a href="/master-guru">
        <li class="{{ request()->is('master-guru*') ? 'active' : '' }} hp-li" title="Master Guru">
            <i class="fas fa-user-tie"></i>
            Master Guru
        </li>
    </a>
    <a href="/master-user">
        <li class="{{ request()->is('master-user*') ? 'active' : '' }} hp-li" title="Master User">
            <i class="fas fa-users"></i>
            Master User
        </li>
    </a>
    <a href="/kelas">
        <li class="{{ request()->is('kelas*') ? 'active' : '' }} hp-li" title="kelas">
            <i class="fas fa-chalkboard"></i>
            Master Kelas
        </li>
    </a>
    <a href="/peraturan">
        <li class="{{ request()->is('peraturan*') ? 'active' : '' }} hp-li" title="peraturan">
            <i class="fas fa-balance-scale"></i>
            Master Pelanggaran
        </li>
    </a>
    <a href="/tindak-lanjut">
        <li class="{{ request()->is('tindak-lanjut*') ? 'active' : '' }} hp-li" title="tindak-lanjut">
            <i class="fas fa-gavel"></i>
            Tindakan/Sanksi  
        </li>
    </a>
    
    <a href="/master-histori">
        <li class="{{ request()->is('master-histori*') ? 'active' : '' }} hp-li" title="Master Histori">
            <i class="fas fa-calendar-alt"></i>
            Master Histori
        </li>
    </a>
    <a href="/penanganan">
        <li class="{{ request()->is('penanganan*') ? 'active' : '' }} hp-li" title="Penanganan">
            <i class="fas fa-user-cog"></i>
            Penanganan
        </li>
    </a>
</ul>
