<aside class="sidebar sidebar--flyout" id="sidebar" data-nav="flyout">
    <div class="sidebar-header">
        <button class="sidebar-toggler" id="sidebarToggler" type="button" aria-label="Toggle sidebar">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <a href="/" class="sidebar-brand">
            <span class="sidebar-brand-icon">
                <img src="../img/logo2.png" alt="MIS Nurul Ulum">
            </span>
            <span class="sidebar-brand-text">
                <span class="sidebar-brand-name">Siakad</span>
                <span class="sidebar-brand-sub">Nurul Ulum Patapan</span>
            </span>
        </a>
    </div>

    <div class="sidebar-body">
        <nav class="sidebar-nav" aria-label="Navigasi Kepala Sekolah">
            <ul class="sidebar-menu">

                <li class="menu-item{{ request()->is('home*') ? ' is-active' : '' }}">
                    <a href="/home" class="menu-link" title="Dashboard">
                        <span class="menu-icon"><i class='bx bxs-grid-alt'></i></span>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </li>

                <li class="menu-item has-submenu{{ request()->is('kepsek/siswa*', 'kepsek/guru*') ? ' has-active' : '' }}">
                    <a href="#" class="menu-link menu-toggle" title="Data" data-flyout-toggle aria-haspopup="true" aria-expanded="false">
                        <span class="menu-icon"><i class="fas fa-database"></i></span>
                        <span class="menu-text">Data</span>
                        <span class="menu-arrow"><i class="fas fa-chevron-down"></i></span>
                    </a>
                    <ul class="menu-submenu">
                        <li class="menu-submenu-title">Data</li>
                        <li class="menu-submenu-item{{ request()->is('kepsek/siswa*') ? ' is-active' : '' }}">
                            <a href="{{ route('kepsek.siswa') }}" class="menu-submenu-link">Data Siswa</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('kepsek/guru*') ? ' is-active' : '' }}">
                            <a href="{{ route('kepsek.guru') }}" class="menu-submenu-link">Data Guru</a>
                        </li>
                    </ul>
                </li>

                <li class="menu-item has-submenu{{ request()->is('kepsek/absensi-siswa*', 'kepsek/absensi-guru*') ? ' has-active' : '' }}">
                    <a href="#" class="menu-link menu-toggle" title="Absensi" data-flyout-toggle aria-haspopup="true" aria-expanded="false">
                        <span class="menu-icon"><i class="fas fa-clipboard-check"></i></span>
                        <span class="menu-text">Absensi</span>
                        <span class="menu-arrow"><i class="fas fa-chevron-down"></i></span>
                    </a>
                    <ul class="menu-submenu">
                        <li class="menu-submenu-title">Absensi</li>
                        <li class="menu-submenu-item{{ request()->is('kepsek/absensi-siswa*') ? ' is-active' : '' }}">
                            <a href="{{ route('kepsek.absensi-siswa') }}" class="menu-submenu-link">Absensi Siswa</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('kepsek/absensi-guru*') ? ' is-active' : '' }}">
                            <a href="{{ route('kepsek.absensi-guru') }}" class="menu-submenu-link">Absensi Guru</a>
                        </li>
                    </ul>
                </li>

                <li class="menu-item has-submenu{{ request()->is('kepsek/laporan*') ? ' has-active' : '' }}">
                    <a href="#" class="menu-link menu-toggle" title="Laporan" data-flyout-toggle aria-haspopup="true" aria-expanded="false">
                        <span class="menu-icon"><i class="fas fa-file-alt"></i></span>
                        <span class="menu-text">Laporan</span>
                        <span class="menu-arrow"><i class="fas fa-chevron-down"></i></span>
                    </a>
                    <ul class="menu-submenu">
                        <li class="menu-submenu-title">Laporan</li>
                        <li class="menu-submenu-item{{ request()->is('kepsek/laporan/pelanggaran*') ? ' is-active' : '' }}">
                            <a href="{{ route('kepsek.laporan-pelanggaran') }}" class="menu-submenu-link">Pelanggaran</a>
                        </li>
                    </ul>
                </li>

                <li class="menu-item has-submenu{{ request()->is('kepsek/profil-madrasah*') ? ' has-active' : '' }}">
                    <a href="#" class="menu-link menu-toggle" title="Profil" data-flyout-toggle aria-haspopup="true" aria-expanded="false">
                        <span class="menu-icon"><i class="fas fa-building"></i></span>
                        <span class="menu-text">Profil</span>
                        <span class="menu-arrow"><i class="fas fa-chevron-down"></i></span>
                    </a>
                    <ul class="menu-submenu">
                        <li class="menu-submenu-title">Profil</li>
                        <li class="menu-submenu-item{{ request()->is('kepsek/profil-madrasah*') ? ' is-active' : '' }}">
                            <a href="{{ route('kepsek.profil-madrasah') }}" class="menu-submenu-link">Profil Madrasah</a>
                        </li>
                    </ul>
                </li>

            </ul>
        </nav>
    </div>
</aside>
