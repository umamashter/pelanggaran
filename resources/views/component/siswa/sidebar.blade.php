<style>
    .text-logo {
    display: flex;
    flex-direction: column;
    line-height: 1.2;
}

.text-logo .main-title {
    font-size: 18px;
    font-weight: bold;
    color: #fff;
}

.text-logo .sub-title {
    font-size: 13px;
    font-weight: normal;
    color: #ccc; /* abu-abu agar tidak menyaingi judul */
    margin-top: 2px; /* beri jarak */
}

</style>
<div class="l-sidebar">
    <div class="logo">
        <img src="../img/smkn1.png" width="35" class="animate__animated animate__fadeInDown">
        <div class="text-logo animate__animated animate__fadeInLeft" style="animation-delay: 1s">
            <span>E - Poin</span>
            <div class="sub-title">Nurul Ulum Patapan</div>
        </div>
    </div>

    <div class="l-sidebar__content">
        <nav class="c-menu js-menu">
            <ul class="u-list">

                <li class="c-menu__item {{ request()->is('home*', 'ubah-pass*') ? 'is-active' : '' }}  has-submenu"
                    data-toggle="tooltip" title="Dashboard">
                    <a class="c-menu__item__inner" href="/home">
                        <div class="ic">
                            <i class='bx bxs-grid-alt'></i>
                        </div>
                        <div class="c-menu-item__title"><span>Dashboard</span></div>
                    </a>
                </li>

                <li class="c-menu__item {{ request()->is('tata-tertib*') ? 'is-active' : '' }}" data-toggle="tooltip" title="Tata Tertib">
                        <a class="c-menu__item__inner" href="{{ route('siswa.peraturan') }}">
                            <div class="ic">
                                <i class="fas fa-gavel"></i>
                            </div>
                            <div class="c-menu-item__title"><span>Tata Tertib</span></div>
                        </a>
                    </li>

                <li class="c-menu__item {{ request()->is('histori*') ? 'is-active' : '' }}  has-submenu"
                    data-toggle="tooltip" title="Histori">
                    <a class="c-menu__item__inner" href="/histori">
                        <div class="ic">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="c-menu-item__title"><span>Histori</span></div>
                    </a>
                </li>

                <li class="c-menu__item {{ request()->is('pesan*') ? 'is-active' : '' }}  has-submenu"
                    data-toggle="tooltip" title="Pesan">
                    <a class="c-menu__item__inner" href="/pesan">
                        <div class="ic">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="c-menu-item__title"><span>Pesan</span></div>
                    </a>
                </li>                    
            </ul>

        </nav>
    </div>
</div>
