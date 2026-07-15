<div class="l-sidebar">
    <div class="logo">
        <img src="../img/logo2.png" width="32" height="32" style="object-fit:contain;" class="animate__animated animate__fadeInDown">
        <div class="text-logo animate__animated animate__fadeInLeft" style="animation-delay: 1s">
            <span>E - Book</span>
        </div>
    </div>

    <div class="l-sidebar__content">
        <nav class="c-menu js-menu">
            <ul class="u-list">

                <li class="c-menu__item {{ request()->is('home*') ? 'is-active' : '' }} has-submenu"
                    data-toggle="tooltip" title="Dashboard">
                    <a class="c-menu__item__inner" href="/home">
                        <div class="ic">
                            <i class='bx bxs-grid-alt'></i>
                        </div>
                        <div class="c-menu-item__title"><span>Dashboard</span></div>
                    </a>
                </li>
                <li class="c-menu__item {{ request()->is('bk/daftar-siswa*', 'bk/pelanggaran*') ? 'is-active' : '' }} has-submenu"
                    data-toggle="tooltip" title="Daftar Siswa">
                    <a class="c-menu__item__inner" href="/bk/daftar-siswa">
                        <div class="ic animate__backInLeft animate__animated">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div class="c-menu-item__title animate__backInLeft animate__animated">
                            <span>Master Siswa</span>
                        </div>
                    </a>
                </li>
                <li class="c-menu__item {{ request()->is('bk/penanganan*') ? 'is-active' : '' }} has-submenu"
                    data-toggle="tooltip" title="Penanganan">
                    <a class="c-menu__item__inner" href="/bk/penanganan">
                        <div class="ic animate__backInLeft animate__animated">
                            <i class="fas fa-user-cog"></i>
                        </div>
                        <div class="c-menu-item__title animate__backInLeft animate__animated">
                            <span>Penanganan</span>
                        </div>
                    </a>
                </li>
                <li>
                    <a class="c-menu__item__inner" href="{{ route('2fa.setup') }}">
                        <div class="ic">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <span>Keamanan 2FA</span>
                    </a>
                </li>
            </ul>

        </nav>
    </div>
</div>
