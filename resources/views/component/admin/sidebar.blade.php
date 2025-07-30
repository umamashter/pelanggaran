<div class="l-sidebar">
    <div class="logo">
        <img src="../img/smkn1.png" width="35" class="animate__animated animate__fadeInDown">
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
                        <div class="ic animate__backInLeft animate__animated">
                            <i class='bx bxs-grid-alt'></i>
                        </div>
                        <div class="c-menu-item__title animate__backInLeft animate__animated">
                            <span>Dashboard</span>
                        </div>
                    </a>
                </li>

                <li class="c-menu__item {{ request()->is('master-siswa*', 'pelanggaran*') ? 'is-active' : '' }} has-submenu"
                    data-toggle="tooltip" title="Master Siswa">
                    <a class="c-menu__item__inner" href="/master-siswa">
                        <div class="ic animate__backInLeft animate__animated">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div class="c-menu-item__title animate__backInLeft animate__animated">
                            <span>Master Siswa</span>
                        </div>
                    </a>
                </li>

                <li class="c-menu__item {{ request()->is('master-guru*') ? 'is-active' : '' }} has-submenu"
                    data-toggle="tooltip" title="Master Guru">
                    <a class="c-menu__item__inner" href="/master-guru">
                        <div class="ic animate__backInLeft animate__animated">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="c-menu-item__title animate__backInLeft animate__animated">
                            <span>Master Guru</span>
                        </div>
                    </a>
                </li>

                <li class="c-menu__item {{ request()->is('master-user*') ? 'is-active' : '' }} has-submenu"
                    data-toggle="tooltip" title="Master User">
                    <a class="c-menu__item__inner" href="/master-user">
                        <div class="ic animate__backInLeft animate__animated">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="c-menu-item__title animate__backInLeft animate__animated">
                            <span>Master User</span>
                        </div>
                    </a>
                </li>
                <li class="c-menu__item {{ request()->is('kelas*') ? 'is-active' : '' }} has-submenu"
                    data-toggle="tooltip" title="Kelas">
                    <a class="c-menu__item__inner" href="{{ route('kelas.index') }}">
                        <div class="ic animate__backInLeft animate__animated">
                            <i class="fas fa-chalkboard"></i>
                        </div>
                        <div class="c-menu-item__title animate__backInLeft animate__animated">
                            <span>Master Kelas</span>
                        </div>
                    </a>
                </li>
                <li class="c-menu__item {{ request()->is('peraturan*') ? 'is-active' : '' }} has-submenu"
                    data-toggle="tooltip" title="Peraturan">
                    <a class="c-menu__item__inner" href="/peraturan">
                        <div class="ic animate__backInLeft animate__animated">
                            <i class="fas fa-balance-scale"></i>
                        </div>
                        <div class="c-menu-item__title animate__backInLeft animate__animated">
                            <span>Master <br>pelanggaran <br></span>
                        </div>
                    </a>
                </li>
                <li class="c-menu__item {{ request()->is('tindak-lanjut*') ? 'is-active' : '' }} has-submenu"
                   data-toggle="tooltip" title="Tindakan">
                   <a class="c-menu__item__inner" href="{{ route('tindak-lanjut.index') }}">
                       <div class="ic animate__backInLeft animate__animated">
                            <i class="fas fa-gavel"></i> {{-- Icon palu sebagai simbol tindakan --}}
                       </div>
                       <div class="c-menu-item__title animate__backInLeft animate__animated">
                          <span>Master <br>Tindakan/Sanksi <br></span>
                       </div>
                   </a>
                 </li>
                <li class="c-menu__item {{ request()->is('master-histori*') ? 'is-active' : '' }} has-submenu"
                    data-toggle="tooltip" title="Master Histori">
                    <a class="c-menu__item__inner" href="/master-histori">
                        <div class="ic animate__backInLeft animate__animated">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="c-menu-item__title animate__backInLeft animate__animated">
                            <span>Master Histori</span>
                        </div>
                    </a>
                </li>

                <li class="c-menu__item {{ request()->is('penanganan*') ? 'is-active' : '' }} has-submenu"
                    data-toggle="tooltip" title="Penanganan">
                    <a class="c-menu__item__inner" href="/penanganan">
                        <div class="ic animate__backInLeft animate__animated">
                            <i class="fas fa-user-cog"></i>
                        </div>
                        <div class="c-menu-item__title animate__backInLeft animate__animated">
                            <span>Penanganan</span>
                        </div>
                    </a>
                </li>                
            </ul>
        </nav>
    </div>
</div>
