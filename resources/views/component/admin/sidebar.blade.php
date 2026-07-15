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
        <nav class="sidebar-nav" aria-label="Navigasi utama">
            <ul class="sidebar-menu">

                {{-- Dashboard --}}
                <li class="menu-item{{ request()->is('home*') ? ' is-active' : '' }}">
                    <a href="/home" class="menu-link" title="Dashboard">
                        <span class="menu-icon"><i class='bx bxs-grid-alt'></i></span>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </li>

                {{-- Data Master --}}
                <li class="menu-item has-submenu{{ request()->is('master-user*', 'master-guru*', 'master-siswa*', 'mata-pelajaran*', 'kelas*', 'tahun-ajaran*', 'arsip-tahun-ajaran*', 'wali-kelas*', 'semester*', 'profil-madrasah*', 'alumni*') ? ' has-active' : '' }}">
                    <a href="#" class="menu-link menu-toggle" title="Data Master" data-flyout-toggle aria-haspopup="true" aria-expanded="false">
                        <span class="menu-icon"><i class="fas fa-database"></i></span>
                        <span class="menu-text">Data Master</span>
                        <span class="menu-arrow"><i class="fas fa-chevron-down"></i></span>
                    </a>
                    <ul class="menu-submenu">
                        <li class="menu-submenu-title">Data Master</li>
                        <li class="menu-submenu-item{{ request()->is('master-user*') ? ' is-active' : '' }}">
                            <a href="/master-user" class="menu-submenu-link">Master User</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('master-guru*') ? ' is-active' : '' }}">
                            <a href="/master-guru" class="menu-submenu-link">Master Guru</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('master-siswa*') ? ' is-active' : '' }}">
                            <a href="/master-siswa" class="menu-submenu-link">Master Siswa</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('mata-pelajaran*') ? ' is-active' : '' }}">
                            <a href="/mata-pelajaran" class="menu-submenu-link">Mata Pelajaran</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('kelas*') ? ' is-active' : '' }}">
                            <a href="{{ route('kelas.index') }}" class="menu-submenu-link">Master Kelas</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('wali-kelas*') ? ' is-active' : '' }}">
                            <a href="{{ route('wali-kelas.index') }}" class="menu-submenu-link">Wali Kelas</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('tahun-ajaran*', 'arsip-tahun-ajaran*') ? ' is-active' : '' }}">
                            <a href="{{ route('tahun-ajaran.index') }}" class="menu-submenu-link">Tahun Ajaran</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('semester*') ? ' is-active' : '' }}">
                            <a href="{{ route('semester.index') }}" class="menu-submenu-link">Semester</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('profil-madrasah*') ? ' is-active' : '' }}">
                            <a href="{{ route('profil-madrasah.index') }}" class="menu-submenu-link">Profil Madrasah</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('alumni*') ? ' is-active' : '' }}">
                            <a href="{{ route('alumni.index') }}" class="menu-submenu-link">Alumni</a>
                        </li>
                    </ul>
                </li>

                {{-- Akademik --}}
                <li class="menu-item has-submenu{{ request()->is('pengampu-mapel*', 'jadwal-pelajaran*', 'jadwal-jenjang*', 'jadwal-per-kelas*', 'jadwal-grid*', 'jadwal-siswa*', 'absensi*', 'penilaian', 'penilaian/*', 'penilaian-riwayat*', 'penilaian-hasil*') ? ' has-active' : '' }}">
                    <a href="#" class="menu-link menu-toggle" title="Akademik" data-flyout-toggle aria-haspopup="true" aria-expanded="false">
                        <span class="menu-icon"><i class="fas fa-school"></i></span>
                        <span class="menu-text">Akademik</span>
                        <span class="menu-arrow"><i class="fas fa-chevron-down"></i></span>
                    </a>
                    <ul class="menu-submenu">
                        <li class="menu-submenu-title">Akademik</li>
                        <li class="menu-submenu-item{{ request()->is('pengampu-mapel*') ? ' is-active' : '' }}">
                            <a href="/pengampu-mapel" class="menu-submenu-link">Guru Mapel</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('jadwal-pelajaran*', 'jadwal-jenjang*', 'jadwal-per-kelas*', 'jadwal-grid*') ? ' is-active' : '' }}">
                            <a href="{{ route('jadwal-pelajaran.index') }}" class="menu-submenu-link">Jadwal</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('jadwal-siswa*') ? ' is-active' : '' }}">
                            <a href="{{ route('jadwal-siswa') }}" class="menu-submenu-link">Jadwal Siswa</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('absensi*') ? ' is-active' : '' }}">
                            <a href="{{ route('absensi.index') }}" class="menu-submenu-link">Absensi</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('penilaian', 'penilaian/*', 'penilaian-riwayat*', 'penilaian-hasil*') ? ' is-active' : '' }}">
                            <a href="{{ route('penilaian.index') }}" class="menu-submenu-link">Penilaian</a>
                        </li>
                    </ul>
                </li>

                {{-- Pelanggaran --}}
                <li class="menu-item has-submenu{{ request()->is('peraturan*', 'tindak-lanjut*', 'penanganan*', 'master-histori*') ? ' has-active' : '' }}">
                    <a href="#" class="menu-link menu-toggle" title="Pelanggaran" data-flyout-toggle aria-haspopup="true" aria-expanded="false">
                        <span class="menu-icon"><i class="fas fa-gavel"></i></span>
                        <span class="menu-text">Pelanggaran</span>
                        <span class="menu-arrow"><i class="fas fa-chevron-down"></i></span>
                    </a>
                    <ul class="menu-submenu">
                        <li class="menu-submenu-title">Pelanggaran</li>
                        <li class="menu-submenu-item{{ request()->is('peraturan*') ? ' is-active' : '' }}">
                            <a href="/peraturan" class="menu-submenu-link">Peraturan</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('tindak-lanjut*') ? ' is-active' : '' }}">
                            <a href="{{ route('tindak-lanjut.index') }}" class="menu-submenu-link">Tindakan</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('penanganan*') ? ' is-active' : '' }}">
                            <a href="/penanganan" class="menu-submenu-link">Penanganan</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('master-histori*') ? ' is-active' : '' }}">
                            <a href="/master-histori" class="menu-submenu-link">Histori</a>
                        </li>
                    </ul>
                </li>

                {{-- Haflah / Lomba --}}
                <li class="menu-item has-submenu{{ request()->is('haflatul-imtihan*', 'sesi*', 'sesi-lomba*', 'kategori-lomba*', 'lomba*', 'peserta-lomba*', 'kelompok-lomba*', 'juri-lomba*', 'aspek-penilaian*', 'penilaian-lomba*', 'hasil-lomba*') ? ' has-active' : '' }}">
                    <a href="#" class="menu-link menu-toggle" title="Haflah" data-flyout-toggle aria-haspopup="true" aria-expanded="false">
                        <span class="menu-icon"><i class="fas fa-award"></i></span>
                        <span class="menu-text">Haflah</span>
                        <span class="menu-arrow"><i class="fas fa-chevron-down"></i></span>
                    </a>
                    <ul class="menu-submenu">
                        <li class="menu-submenu-item{{ request()->is('haflatul-imtihan*') ? ' is-active' : '' }}">
                            <a href="{{ route('haflatul-imtihan.index') }}" class="menu-submenu-link">Haflatul Imtihan</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('sesi', 'sesi/*') ? ' is-active' : '' }}">
                            <a href="{{ route('sesi.index') }}" class="menu-submenu-link">Daftar Sesi</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('sesi-lomba*') ? ' is-active' : '' }}">
                            <a href="{{ route('sesi-lomba.index') }}" class="menu-submenu-link">Sesi Lomba</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('kategori-lomba*') ? ' is-active' : '' }}">
                            <a href="{{ route('kategori-lomba.index') }}" class="menu-submenu-link">Kategori Lomba</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('lomba') || request()->is('lomba/*') ? ' is-active' : '' }}">
                            <a href="{{ route('lomba.index') }}" class="menu-submenu-link">Lomba</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('kelompok-lomba*') ? ' is-active' : '' }}">
                            <a href="{{ route('kelompok-lomba.index') }}" class="menu-submenu-link">Kelompok</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('peserta-lomba*', 'kelompok-lomba*', 'anggota-kelompok*') ? ' is-active' : '' }}">
                            <a href="#" class="menu-submenu-link" data-context-panel="peserta">
                                Peserta Lomba <span class="menu-arrow"><i class="fas fa-chevron-down"></i></span>
                            </a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('juri-lomba*') ? ' is-active' : '' }}">
                            <a href="{{ route('juri-lomba.index') }}" class="menu-submenu-link">Juri</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('aspek-penilaian*') ? ' is-active' : '' }}">
                            <a href="{{ route('aspek-penilaian.index') }}" class="menu-submenu-link">Aspek Penilaian</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('penilaian-lomba*') ? ' is-active' : '' }}">
                            <a href="{{ route('penilaian-lomba.index') }}" class="menu-submenu-link">Penilaian</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('hasil-lomba*') ? ' is-active' : '' }}">
                            <a href="{{ route('hasil-lomba.index') }}" class="menu-submenu-link">Hasil</a>
                        </li>
                    </ul>
                </li>

                {{-- Informasi --}}
                <li class="menu-item has-submenu{{ request()->is('pengumuman*', 'galery*') ? ' has-active' : '' }}">
                    <a href="#" class="menu-link menu-toggle" title="Informasi" data-flyout-toggle aria-haspopup="true" aria-expanded="false">
                        <span class="menu-icon"><i class="fas fa-info-circle"></i></span>
                        <span class="menu-text">Informasi</span>
                        <span class="menu-arrow"><i class="fas fa-chevron-down"></i></span>
                    </a>
                    <ul class="menu-submenu">
                        <li class="menu-submenu-title">Informasi</li>
                        <li class="menu-submenu-item{{ request()->is('pengumuman*') ? ' is-active' : '' }}">
                            <a href="{{ route('pengumuman.index') }}" class="menu-submenu-link">Pengumuman</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('galery*') ? ' is-active' : '' }}">
                            <a href="{{ route('galery.index') }}" class="menu-submenu-link">Galeri</a>
                        </li>
                    </ul>
                </li>

                {{-- Laporan --}}
                <li class="menu-item has-submenu{{ request()->is('laporan*') ? ' has-active' : '' }}">
                    <a href="#" class="menu-link menu-toggle" title="Laporan" data-flyout-toggle aria-haspopup="true" aria-expanded="false">
                        <span class="menu-icon"><i class="fas fa-file-alt"></i></span>
                        <span class="menu-text">Laporan</span>
                        <span class="menu-arrow"><i class="fas fa-chevron-down"></i></span>
                    </a>
                    <ul class="menu-submenu">
                        <li class="menu-submenu-title">Laporan</li>
                        <li class="menu-submenu-item{{ request()->is('laporan*') ? ' is-active' : '' }}">
                            <a href="{{ route('laporan.rekap-periode') }}" class="menu-submenu-link">Laporan Pelanggaran</a>
                        </li>
                    </ul>
                </li>

                {{-- Pengaturan --}}
                <li class="menu-item has-submenu{{ request()->is('2fa*', 'admin/keamanan*', 'admin/riwayat-login*', 'riwayat-login*', 'perangkat*', 'admin/kebijakan-2fa*') ? ' has-active' : '' }}">
                    <a href="#" class="menu-link menu-toggle" title="Pengaturan" data-flyout-toggle aria-haspopup="true" aria-expanded="false">
                        <span class="menu-icon"><i class="fas fa-cog"></i></span>
                        <span class="menu-text">Pengaturan</span>
                        <span class="menu-arrow"><i class="fas fa-chevron-down"></i></span>
                    </a>
                    <ul class="menu-submenu">
                        <li class="menu-submenu-title">Pengaturan</li>
                        <li class="menu-submenu-item{{ request()->is('admin/keamanan*') ? ' is-active' : '' }}">
                            <a href="{{ route('admin.security-dashboard.index') }}" class="menu-submenu-link">Keamanan</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('admin/riwayat-login*', 'riwayat-login*') ? ' is-active' : '' }}">
                            <a href="{{ route('admin.login-history.index') }}" class="menu-submenu-link">Riwayat Login</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('perangkat*') ? ' is-active' : '' }}">
                            <a href="{{ route('active-sessions.index') }}" class="menu-submenu-link">Perangkat Aktif</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('2fa*') ? ' is-active' : '' }}">
                            <a href="{{ route('2fa.setup') }}" class="menu-submenu-link">Keamanan 2FA</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('admin/kebijakan-2fa*') ? ' is-active' : '' }}">
                            <a href="{{ route('admin.2fa-policy.index') }}" class="menu-submenu-link">Kebijakan 2FA</a>
                        </li>
                    </ul>
                </li>

            </ul>
        </nav>
    </div>
</aside>