<div class="col-12">
    @include('component.admin.absensi-module')
    <div class="abs-mod dash-dashboard" id="dashDashboard">

        {{-- ============================================================
             SKELETON LOADING (ditampilkan ~500ms, lalu di-reveal)
             ============================================================ --}}
        <div class="dash-skeleton" id="dashSkeleton" aria-hidden="true">
            <div class="dash-skel dash-skel--hero"></div>
            <div class="dash-skel-grid4">
                <div class="dash-skel"></div>
                <div class="dash-skel"></div>
                <div class="dash-skel"></div>
                <div class="dash-skel"></div>
            </div>
            <div class="dash-skel-grid2">
                <div class="dash-skel" style="height:220px"></div>
                <div class="dash-skel" style="height:220px"></div>
            </div>
            <div class="dash-skel-grid2">
                <div class="dash-skel" style="height:280px"></div>
                <div class="dash-skel" style="height:280px"></div>
            </div>
        </div>

        {{-- ============================================================
             KONTEN UTAMA
             ============================================================ --}}
        <div class="dash-content">
            <div class="dash-reveal" id="dashReveal">

                {{-- ---------- HERO + KPI + KALENDER ---------- --}}
                <div class="dash-cols dash-cols--83 dash-cols--top dash-mb">
                    <div class="dash-main-col">
                        <div class="abm-hero">
                            <div class="abm-hero-grid"></div>
                            <div class="abm-hero-row">
                                <div class="abm-hero-left">
                                    <h3>{{ $greeting }}, {{ $namaAdmin }} 👋</h3>
                                    <p class="abm-hero-sub">Selamat datang kembali. Berikut ringkasan aktivitas madrasah hari ini.</p>
                                </div>
                                <div class="abm-hero-clock">
                                    <i class="fas fa-clock abm-clock-icon"></i>
                                    <div>
                                        <div class="abm-clock-time" id="liveClock">--:--:--</div>
                                        <div class="abm-clock-date" id="liveClockDate">{{ $tanggalSekarang }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="abm-hero-badges">
                                <span class="abm-hero-badge"><i class="fas fa-calendar-day"></i> {{ $tanggalSekarang }}</span>
                                @if ($tahunAktif)
                                <span class="abm-hero-badge"><i class="fas fa-graduation-cap"></i> {{ $tahunAktif->tahun_ajaran }}</span>
                                @if ($tahunAktif->semesterAktif)
                                <span class="abm-hero-badge"><i class="fas fa-bookmark"></i> {{ $tahunAktif->semesterAktif->nama }}</span>
                                @endif
                                @endif
                            </div>
                        </div>

                        {{-- ---------- KPI STATISTICS ---------- --}}
                        @php
                        $siswaPct = $totalSiswa > 0 ? (int) round(($siswaPerJenjang->max('total') / $totalSiswa) * 100) : 0;
                        $guruPct = $totalGuru > 0 ? (int) round((($totalGuru - $guruBelumAbsen) / $totalGuru) * 100) : 0;
                        $kelasPct = $totalKelas > 0 ? (int) round(($sessionsHariIni / $totalKelas) * 100) : 0;
                        @endphp
                        <div class="abm-kpi-grid">
                            <div class="abm-kpi dash-kpi siswa">
                                <i class="fas fa-user-graduate abm-kpi-watermark"></i>
                                <div class="dash-kpi-top">
                                    <span class="dash-kpi-label">Total Siswa</span>
                                    <div class="abm-kpi-icon blue"><i class="fas fa-user-graduate"></i></div>
                                </div>
                                <div class="abm-kpi-num" data-count="{{ $totalSiswa }}">0</div>
                                <div class="dash-kpi-body">
                                    <div class="dash-gauge" data-gauge="{{ $siswaPct }}" role="img" aria-label="Siswa terpusat di jenjang terbesar: {{ $siswaPct }} persen">
                                        <svg viewBox="0 0 42 42" aria-hidden="true">
                                            <circle class="dash-gauge-track" cx="21" cy="21" r="15.915"></circle>
                                            <circle class="dash-gauge-bar" cx="21" cy="21" r="15.915"></circle>
                                        </svg>
                                        <div class="dash-gauge-value">{{ $siswaPct }}%</div>
                                    </div>
                                    <div class="dash-kpi-foot">
                                        <span class="dash-kpi-sub"><i class="fas fa-layer-group"></i> {{ $siswaPerJenjang->count() }} jenjang terisi</span>
                                    </div>
                                </div>
                            </div>
                            <div class="abm-kpi dash-kpi guru">
                                <i class="fas fa-user-tie abm-kpi-watermark"></i>
                                <div class="dash-kpi-top">
                                    <span class="dash-kpi-label">Total Guru</span>
                                    <div class="abm-kpi-icon violet"><i class="fas fa-user-tie"></i></div>
                                </div>
                                <div class="abm-kpi-num" data-count="{{ $totalGuru }}">0</div>
                                <div class="dash-kpi-body">
                                    <div class="dash-gauge" data-gauge="{{ $guruPct }}" role="img" aria-label="Guru sudah absen hari ini: {{ $guruPct }} persen">
                                        <svg viewBox="0 0 42 42" aria-hidden="true">
                                            <circle class="dash-gauge-track" cx="21" cy="21" r="15.915"></circle>
                                            <circle class="dash-gauge-bar" cx="21" cy="21" r="15.915"></circle>
                                        </svg>
                                        <div class="dash-gauge-value">{{ $guruPct }}%</div>
                                    </div>
                                    <div class="dash-kpi-foot">
                                        @if ($guruBelumAbsen > 0)
                                        <span class="dash-kpi-sub warn"><i class="fas fa-hourglass-half"></i> {{ $guruBelumAbsen }} guru belum absen</span>
                                        @else
                                        <span class="dash-kpi-sub ok"><i class="fas fa-check-circle"></i> Semua guru sudah absen</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="abm-kpi dash-kpi kelas">
                                <i class="fas fa-school abm-kpi-watermark"></i>
                                <div class="dash-kpi-top">
                                    <span class="dash-kpi-label">Total Kelas</span>
                                    <div class="abm-kpi-icon sky"><i class="fas fa-school"></i></div>
                                </div>
                                <div class="abm-kpi-num" data-count="{{ $totalKelas }}">0</div>
                                <div class="dash-kpi-body">
                                    <div class="dash-gauge" data-gauge="{{ $kelasPct }}" role="img" aria-label="Kelas dengan sesi hari ini: {{ $kelasPct }} persen">
                                        <svg viewBox="0 0 42 42" aria-hidden="true">
                                            <circle class="dash-gauge-track" cx="21" cy="21" r="15.915"></circle>
                                            <circle class="dash-gauge-bar" cx="21" cy="21" r="15.915"></circle>
                                        </svg>
                                        <div class="dash-gauge-value">{{ $kelasPct }}%</div>
                                    </div>
                                    <div class="dash-kpi-foot">
                                        <span class="dash-kpi-sub"><i class="fas fa-chalkboard-teacher"></i> {{ $sessionsHariIni }} sesi hari ini</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="abm-card abm-card--lift dash-panel dash-cal-panel">
                        <div class="dash-panel-head">
                            <div>
                                <h2 class="dash-panel-title">Kalender</h2>
                                <div class="dash-panel-sub">{{ $kalender['nama_bulan'] }} {{ $kalender['tahun'] }}</div>
                            </div>
                        </div>
                        <div class="dash-cal">
                            <div class="dash-cal-row dash-cal-head">
                                <span>Ahad</span><span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span>
                            </div>
                            <div class="dash-cal-row">
                                @for ($i = 0; $i < $kalender['hari_awal']; $i++)
                                    <span class="dash-cal-empty" aria-hidden="true"></span>
                                    @endfor
                                    @for ($day = 1; $day <= $kalender['jumlah_hari']; $day++)
                                        @php
                                        $weekIndex=($kalender['hari_awal'] + $day - 1) % 7;
                                        $isToday=$day===$kalender['hari_ini'];
                                        $isHoliday=$weekIndex===5;
                                        @endphp
                                        <span class="dash-cal-day {{ $isToday ? 'is-today' : '' }} {{ $isHoliday && !$isToday ? 'is-holiday' : '' }}"
                                        @if ($isHoliday) data-bs-toggle="tooltip" title="Hari libur madrasah" @endif>
                                        {{ $day }}
                                        @if ($isHoliday && !$isToday)<i class="fas fa-moon" aria-hidden="true"></i>@endif
                                        </span>
                                        @endfor
                            </div>
                        </div>
                        <div class="dash-cal-legend">
                            <span class="dash-cal-key"><span class="dash-cal-key-box today"></span> Hari ini</span>
                            <span class="dash-cal-key"><span class="dash-cal-key-box holiday"></span> Libur madrasah</span>
                        </div>
                    </div>
                </div>

                {{-- ---------- GRAFIK KEHADIRAN + AKSI CEPAT ---------- --}}
                <div class="dash-cols dash-cols--83 dash-mb">
                    <div class="abm-card abm-card--lift dash-panel">
                        <div class="dash-panel-head">
                            <div>
                                <h2 class="dash-panel-title">Kehadiran Siswa</h2>
                                <div class="dash-panel-sub">Persentase hadir harian (H) dari seluruh catatan absensi.</div>
                            </div>
                            <span class="abm-chip abm-chip--blue"><i class="fas fa-calendar-alt"></i> 30 Hari</span>
                        </div>
                        <div class="dash-chart-box">
                            <canvas id="dashAttendanceChart" aria-label="Grafik kehadiran siswa 30 hari terakhir" role="img"></canvas>
                        </div>
                        <div class="dash-legend-row">
                            <span class="dash-legend"><i class="fas fa-square-full" style="color:#2563eb"></i> % Hadir</span>
                            <span class="dash-legend"><i class="fas fa-square-full" style="color:#16a34a"></i> Hadir <b class="dash-legend-num" data-count="{{ $komposisiHariIni['H'] }}">0</b></span>
                            <span class="dash-legend"><i class="fas fa-square-full" style="color:#d97706"></i> Izin <b class="dash-legend-num" data-count="{{ $komposisiHariIni['I'] }}">0</b></span>
                            <span class="dash-legend"><i class="fas fa-square-full" style="color:#0284c7"></i> Sakit <b class="dash-legend-num" data-count="{{ $komposisiHariIni['S'] }}">0</b></span>
                            <span class="dash-legend"><i class="fas fa-square-full" style="color:#dc2626"></i> Alpha <b class="dash-legend-num" data-count="{{ $komposisiHariIni['A'] }}">0</b></span>
                        </div>
                    </div>

                    <div class="abm-card abm-card--lift dash-panel">
                        <div class="dash-panel-head">
                            <div>
                                <h2 class="dash-panel-title">Aksi Cepat</h2>
                                <div class="dash-panel-sub">Pintasan ke tugas paling umum.</div>
                            </div>
                        </div>
                        <div class="dash-quick-grid" role="navigation" aria-label="Aksi cepat">
                            <a href="{{ route('absensi.create') }}" class="dash-quick" data-bs-toggle="tooltip" title="Buka halaman input absensi">
                                <span class="dash-quick-icon blue"><i class="fas fa-clipboard-list"></i></span>
                                <span class="dash-quick-name">Input Absensi</span>
                                <span class="dash-quick-sub">Catat kehadiran siswa</span>
                            </a>
                            <a href="{{ route('absensi.import') }}" class="dash-quick" data-bs-toggle="tooltip" title="Import absensi dari foto / OCR">
                                <span class="dash-quick-icon violet"><i class="fas fa-file-image"></i></span>
                                <span class="dash-quick-name">Import OCR</span>
                                <span class="dash-quick-sub">Absensi lewat foto</span>
                            </a>
                            <a href="/master-siswa" class="dash-quick" data-bs-toggle="tooltip" title="Kelola data siswa">
                                <span class="dash-quick-icon green"><i class="fas fa-user-graduate"></i></span>
                                <span class="dash-quick-name">Master Siswa</span>
                                <span class="dash-quick-sub">Data siswa madrasah</span>
                            </a>
                            <a href="{{ route('master-guru.index') }}" class="dash-quick" data-bs-toggle="tooltip" title="Kelola data guru">
                                <span class="dash-quick-icon amber"><i class="fas fa-user-tie"></i></span>
                                <span class="dash-quick-name">Master Guru</span>
                                <span class="dash-quick-sub">Data tenaga pendidik</span>
                            </a>
                            <a href="{{ route('kelas.create') }}" class="dash-quick" data-bs-toggle="tooltip" title="Tambah rombongan belajar baru">
                                <span class="dash-quick-icon sky"><i class="fas fa-school"></i></span>
                                <span class="dash-quick-name">Tambah Kelas</span>
                                <span class="dash-quick-sub">Rombongan belajar</span>
                            </a>
                            <a href="{{ route('pengumuman.index') }}" class="dash-quick" data-bs-toggle="tooltip" title="Kelola pengumuman sekolah">
                                <span class="dash-quick-icon rose"><i class="fas fa-bullhorn"></i></span>
                                <span class="dash-quick-name">Pengumuman</span>
                                <span class="dash-quick-sub">Informasi sekolah</span>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- ---------- PANEL BAWAH: 3 KOLOM ---------- --}}
                <div class="dash-cols dash-cols--3 dash-mb">

                    {{-- ---------- PERLU PERHATIAN ---------- --}}
                    <div class="abm-card abm-card--lift dash-panel">
                        <div class="dash-panel-head">
                            <div>
                                <h2 class="dash-panel-title">Perlu Perhatian</h2>
                                <div class="dash-panel-sub">Hal yang membutuhkan tindakan dari Anda hari ini.</div>
                            </div>
                            @if ($jumlahPenangananPending > 0)
                            <span class="abm-chip abm-chip--danger"><i class="fas fa-exclamation-circle"></i> {{ $jumlahPenangananPending }} menunggu</span>
                            @elseif ($jumlahAlphaHariIni > 0)
                            <span class="abm-chip abm-chip--warn"><i class="fas fa-user-clock"></i> {{ $jumlahAlphaHariIni }} alpha</span>
                            @elseif ($guruBelumAbsen > 0)
                            <span class="abm-chip abm-chip--warn"><i class="fas fa-user-clock"></i> {{ $guruBelumAbsen }} belum</span>
                            @else
                            <span class="abm-chip abm-chip--ok"><i class="fas fa-shield-alt"></i> Semua aman</span>
                            @endif
                        </div>

                        <div class="dash-attendance">
                            <div class="dash-att-head">
                                <span class="dash-att-title"><i class="fas fa-clipboard-check"></i> Kehadiran Siswa</span>
                                <span class="abm-kpi-num dash-att-pct" data-count="{{ $persenHadirHariIni }}" data-suffix="%">0%</span>
                            </div>
                            <div class="dash-kpi-bar"><span data-width="{{ $persenHadirHariIni }}"></span></div>
                            <div class="dash-att-foot">
                                <span class="dash-kpi-sub ok"><i class="fas fa-check-circle"></i> {{ $hadirHariIni }} dari {{ $totalAbsenHariIni }} siswa hadir</span>
                                @if ($komposisiHariIni['A'] > 0)
                                <span class="abm-chip abm-chip--danger dash-kpi-chip"><i class="fas fa-user-clock"></i> {{ $komposisiHariIni['A'] }} alpha</span>
                                @else
                                <span class="abm-chip abm-chip--ok dash-kpi-chip"><i class="fas fa-shield-alt"></i> Kehadiran terkendali</span>
                                @endif
                            </div>
                        </div>

                        <div class="dash-notif-list">
                            @forelse ($penangananPending as $item)
                            <div class="dash-notif dash-notif--danger">
                                <span class="dash-notif-icon"><i class="fas fa-exclamation-triangle"></i></span>
                                <div class="dash-notif-body">
                                    <strong>{{ $item->siswa->nama ?? 'Siswa' }}</strong>
                                    <span>{{ $item->pesan->tingkatan ?? 'Tindak lanjut' }} — {{ $item->pesan->tindak_lanjut ?? 'Penanganan belum dikonfirmasi' }}</span>
                                </div>
                                <div class="dash-notif-extra">
                                    <span class="abm-chip abm-chip--danger"><i class="fas fa-hourglass-half"></i> Belum</span>
                                    <a href="/penanganan" class="dash-notif-link" data-bs-toggle="tooltip" title="Buka halaman penanganan">Tinjau <i class="fas fa-arrow-right"></i></a>
                                </div>
                            </div>
                            @empty
                            @if ($jumlahAlphaHariIni > 0)
                            <div class="dash-notif dash-notif--warn">
                                <span class="dash-notif-icon"><i class="fas fa-user-clock"></i></span>
                                <div class="dash-notif-body">
                                    <strong>{{ $jumlahAlphaHariIni }} siswa alpa hari ini</strong>
                                    <span>Status absensi A tercatat pada absensi hari ini.</span>
                                </div>
                                <div class="dash-notif-extra">
                                    <span class="abm-chip abm-chip--warn"><i class="fas fa-user-clock"></i> Alpha</span>
                                    <a href="{{ route('absensi.index') }}" class="dash-notif-link">Cek <i class="fas fa-arrow-right"></i></a>
                                </div>
                            </div>
                            @endif
                            @if ($guruBelumAbsen > 0)
                            <div class="dash-notif dash-notif--warn">
                                <span class="dash-notif-icon"><i class="fas fa-user-clock"></i></span>
                                <div class="dash-notif-body">
                                    <strong>{{ $guruBelumAbsen }} guru belum absen hari ini</strong>
                                    <span>Belum ada catatan kehadiran guru untuk hari ini.</span>
                                </div>
                                <div class="dash-notif-extra">
                                    <span class="abm-chip abm-chip--warn"><i class="fas fa-clock"></i> Belum</span>
                                    <a href="{{ route('admin.absensi-guru.index') }}" class="dash-notif-link">Cek <i class="fas fa-arrow-right"></i></a>
                                </div>
                            </div>
                            @endif
                            @if ($jumlahPenangananPending === 0 && $jumlahAlphaHariIni === 0 && $guruBelumAbsen === 0)
                            <div class="dash-all-safe">
                                <span class="dash-all-safe-icon"><i class="fas fa-shield-alt"></i></span>
                                <div class="dash-all-safe-title">Semua aman</div>
                                <div class="dash-all-safe-sub">Tidak ada penanganan pending, siswa alpha, atau guru yang belum absen.</div>
                            </div>
                            @endif
                            @endforelse
                        </div>
                    </div>

                    {{-- ---------- AKTIVITAS TERBARU ---------- --}}
                    <div class="abm-card abm-card--lift dash-panel">
                        <div class="dash-panel-head">
                            <div>
                                <h2 class="dash-panel-title">Aktivitas Terbaru</h2>
                                <div class="dash-panel-sub">Riwayat login pengguna ke sistem.</div>
                            </div>
                            <a href="{{ route('admin.login-history.index') }}" class="dash-link-more" data-bs-toggle="tooltip" title="Lihat seluruh riwayat login">Lihat Semua <i class="fas fa-arrow-right"></i></a>
                        </div>
                        <div class="dash-feed">
                            @forelse ($aktivitasTerbaru as $log)
                            @php $avatarClass = 'c' . ($loop->index % 6); @endphp
                            <div class="dash-feed-item">
                                <span class="dash-avatar {{ $avatarClass }}">{{ mb_strtoupper(mb_substr($log->user->name ?? 'U', 0, 1)) }}</span>
                                <div class="dash-feed-body">
                                    <div class="dash-feed-name">{{ $log->user->name ?? 'Pengguna' }}</div>
                                    <div class="dash-feed-meta">
                                        <span><i class="fas fa-globe"></i> {{ $log->browser ?? 'Browser' }}</span>
                                        @if ($log->os)<span><i class="fas fa-desktop"></i> {{ $log->os }}</span>@endif
                                        <span><i class="fas fa-clock"></i> {{ $log->login_at ? $log->login_at->locale('id')->diffForHumans() : '—' }}</span>
                                    </div>
                                </div>
                                <span class="abm-chip abm-chip--ok"><i class="fas fa-check-circle"></i> Berhasil</span>
                            </div>
                            @empty
                            <div class="abm-empty">
                                <i class="fas fa-clock-history"></i>
                                <div class="abm-empty-title">Belum ada aktivitas</div>
                                <div class="abm-empty-sub">Belum ada login yang tercatat ke sistem.</div>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- ---------- PENGUMUMAN ---------- --}}
                    <div class="abm-card abm-card--lift dash-panel">
                        <div class="dash-panel-head">
                            <div>
                                <h2 class="dash-panel-title">Pengumuman</h2>
                                <div class="dash-panel-sub">Informasi terbaru yang telah diterbitkan.</div>
                            </div>
                            <a href="{{ route('pengumuman.index') }}" class="dash-link-more" data-bs-toggle="tooltip" title="Kelola pengumuman">Kelola <i class="fas fa-arrow-right"></i></a>
                        </div>
                        <div class="dash-news-grid">
                            @forelse ($pengumumanDashboard as $pg)
                            <div class="dash-news-item">
                                <span class="dash-news-icon"><i class="fas fa-bullhorn"></i></span>
                                <div class="dash-news-body">
                                    <div class="dash-news-title">{{ $pg->judul }}</div>
                                    <div class="dash-news-excerpt">{{ \Illuminate\Support\Str::limit(strip_tags($pg->isi ?? ''), 110) }}</div>
                                    <div class="dash-news-meta">
                                        <span class="abm-chip abm-chip--ok"><i class="fas fa-check-circle"></i> Published</span>
                                        <span class="dash-news-date"><i class="fas fa-calendar-day"></i> {{ $pg->tanggal ? $pg->tanggal->translatedFormat('d M Y') : '—' }}</span>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="abm-empty" style="grid-column:1 / -1;">
                                <i class="fas fa-bullhorn"></i>
                                <div class="abm-empty-title">Belum ada pengumuman</div>
                                <div class="abm-empty-sub">Pengumuman yang diterbitkan akan tampil di sini.</div>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- ---------- TOAST ---------- --}}
        <div class="dash-toast-wrap">
            <div class="dash-toast" id="dashToast" role="status" aria-live="polite">
                <span class="dash-toast-icon"><i class="fas fa-sparkles"></i></span>
                <div class="dash-toast-body">
                    <strong>Selamat datang kembali!</strong>
                    <span>Ringkasan aktivitas madrasah hari ini sudah siap.</span>
                </div>
                <button type="button" class="dash-toast-close" id="dashToastClose" aria-label="Tutup notifikasi"><i class="fas fa-times"></i></button>
            </div>
        </div>
    </div>
</div>

@push('css')
<style>
    .page-title-content {
        display: none !important;
    }

    /* ============================================================
       DASHBOARD — ekstensi desain (mengikuti token --ab-* Absensi)
       ============================================================ */
    .abs-mod.dash-dashboard {
        position: relative;
        background: var(--ab-bg);
        border-radius: 24px;
        padding: 22px;
    }

    .dash-mb {
        margin-bottom: 18px;
    }

    /* ---------- Reveal (fade + lift bertahap) ---------- */
    .dash-reveal>* {
        opacity: 0;
        transform: translateY(14px);
        transition: opacity .55s cubic-bezier(.22, 1, .36, 1), transform .55s cubic-bezier(.22, 1, .36, 1);
    }

    .dash-reveal.is-ready>*:nth-child(1) {
        transition-delay: .04s;
    }

    .dash-reveal.is-ready>*:nth-child(2) {
        transition-delay: .10s;
    }

    .dash-reveal.is-ready>*:nth-child(3) {
        transition-delay: .16s;
    }

    .dash-reveal.is-ready>*:nth-child(4) {
        transition-delay: .22s;
    }

    .dash-reveal.is-ready>*:nth-child(5) {
        transition-delay: .28s;
    }

    .dash-reveal.is-ready>*:nth-child(6) {
        transition-delay: .34s;
    }

    .dash-reveal.is-ready>* {
        opacity: 1;
        transform: none;
    }

    /* ---------- Skeleton loading ---------- */
    .dash-skeleton {
        display: none;
    }

    .dash-dashboard.is-loading .dash-skeleton {
        display: block;
    }

    .dash-dashboard.is-loading .dash-content {
        display: none;
    }

    .dash-skel {
        position: relative;
        overflow: hidden;
        height: 96px;
        border-radius: 18px;
        background: var(--ab-border-soft);
    }

    .dash-skel::after {
        content: '';
        position: absolute;
        inset: 0;
        transform: translateX(-100%);
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .55), transparent);
        animation: dash-shimmer 1.4s infinite;
    }

    html.dark-mode .dash-skel::after {
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .06), transparent);
    }

    @keyframes dash-shimmer {
        100% {
            transform: translateX(100%);
        }
    }

    .dash-skel--hero {
        height: 150px;
        margin-bottom: 20px;
    }

    .dash-skel-grid4 {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 18px;
    }

    .dash-skel-grid2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
        margin-bottom: 18px;
    }

    /* ---------- Panel / kartu ---------- */
    .dash-panel {
        padding: 16px 18px;
        height: 100%;
    }

    .dash-panel-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 12px;
    }

    .dash-panel-title {
        font-size: 15px;
        font-weight: 800;
        color: var(--ab-text);
        margin: 0;
    }

    .dash-panel-sub {
        font-size: 12px;
        color: var(--ab-text-3);
        margin-top: 2px;
    }

    /* ---------- Hero: jam realtime selalu di samping sapaan ---------- */
    .dash-dashboard .abm-hero-row {
        flex-wrap: nowrap;
        justify-content: flex-start;
        align-items: center;
    }

    .dash-dashboard .abm-hero-left {
        flex: 0 1 auto;
        min-width: 0;
    }

    .dash-dashboard .abm-hero-clock {
        flex-shrink: 0;
    }

    .dash-dashboard .abm-clock-icon {
        font-size: 22px;
        color: rgba(255, 255, 255, .95);
        animation: dashClockFloat 4s ease-in-out infinite;
    }

    @keyframes dashClockFloat {
        0%,
        100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-3px);
        }
    }

    .dash-dashboard .abm-clock-time {
        perspective: 220px;
    }

    .dash-dashboard .abm-hero-clock {
        transition: box-shadow .25s ease, border-color .25s ease, transform .3s cubic-bezier(.34, 1.56, .64, 1);
    }

    .dash-dashboard .abm-hero-clock:hover {
        transform: scale(1.02);
        border-color: rgba(255, 255, 255, .5);
        box-shadow: 0 16px 32px -12px rgba(37, 99, 235, .4), inset 0 1px 0 rgba(255, 255, 255, .3);
    }

    .dash-dashboard .abm-hero-badge {
        transition: transform .25s cubic-bezier(.34, 1.56, .64, 1), box-shadow .25s ease, background .25s ease;
    }

    .dash-dashboard .abm-hero-badge:hover {
        transform: translateY(-3px);
        background: rgba(255, 255, 255, .22);
        box-shadow: 0 12px 24px -10px rgba(37, 99, 235, .5);
    }

    .dash-dashboard .abm-hero-badge i {
        display: inline-block;
        transition: transform .18s ease;
    }

    .dash-dashboard .abm-hero-badge:hover i {
        transform: rotate(8deg);
    }

    .dash-dashboard .abm-hero i {
        display: inline-block;
        transition: transform .18s ease;
    }

    .dash-dashboard .abm-hero i:hover {
        transform: scale(1.08);
    }

    .dash-wave {
        display: inline-block;
        transform-origin: 70% 70%;
        animation: dashWave 2.5s ease-in-out .4s;
    }

    @keyframes dashWave {
        0% {
            transform: rotate(0deg);
        }
        7% {
            transform: rotate(16deg);
        }
        13% {
            transform: rotate(-8deg);
        }
        18% {
            transform: rotate(12deg);
        }
        25% {
            transform: rotate(0deg);
        }
        32% {
            transform: rotate(16deg);
        }
        38% {
            transform: rotate(-8deg);
        }
        43% {
            transform: rotate(12deg);
        }
        50% {
            transform: rotate(0deg);
        }
        57% {
            transform: rotate(16deg);
        }
        63% {
            transform: rotate(-8deg);
        }
        68% {
            transform: rotate(12deg);
        }
        75% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(0deg);
        }
    }

    .abm-hero-grid::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, transparent 0%, rgba(255, 255, 255, 0) 40%, rgba(255, 255, 255, .16) 50%, rgba(255, 255, 255, 0) 60%, transparent 100%);
        animation: dashGridFlow 25s linear infinite alternate;
    }

    @keyframes dashGridFlow {
        0% {
            transform: translateX(-100%);
        }
        100% {
            transform: translateX(100%);
        }
    }

    .dash-date-swap {
        animation: dashDateSwap .5s ease;
    }

    @keyframes dashDateSwap {
        from {
            opacity: 0;
            transform: translateY(4px);
        }
        to {
            opacity: 1;
            transform: none;
        }
    }

    .dash-link-more {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 700;
        color: var(--ab-primary);
        text-decoration: none !important;
        white-space: nowrap;
    }

    .dash-link-more:hover {
        color: var(--ab-violet);
    }

    .dash-link-more i {
        font-size: 10px;
    }

    /* ---------- KPI ---------- */
    .dash-kpi.siswa {
        --dash-kpi-color: #2563eb;
        --dash-kpi-soft: #eff6ff;
        --dash-kpi-border: rgba(37, 99, 235, .22);
        --ab-kpi-glow: rgba(37, 99, 235, .08);
    }

    .dash-kpi.guru {
        --dash-kpi-color: #7c3aed;
        --dash-kpi-soft: #f5f3ff;
        --dash-kpi-border: rgba(124, 58, 237, .22);
        --ab-kpi-glow: rgba(124, 58, 237, .08);
    }

    .dash-kpi.kelas {
        --dash-kpi-color: #0284c7;
        --dash-kpi-soft: #f0f9ff;
        --dash-kpi-border: rgba(2, 132, 199, .22);
        --ab-kpi-glow: rgba(2, 132, 199, .08);
    }

    .dash-kpi.kehadiran {
        --dash-kpi-color: #16a34a;
        --dash-kpi-soft: #f0fdf4;
        --dash-kpi-border: rgba(22, 163, 74, .22);
        --ab-kpi-glow: rgba(22, 163, 74, .08);
    }

    html.dark-mode .dash-kpi.siswa {
        --dash-kpi-soft: rgba(37, 99, 235, .14);
        --dash-kpi-border: rgba(61, 169, 252, .35);
        --ab-kpi-glow: rgba(37, 99, 235, .12);
    }

    html.dark-mode .dash-kpi.guru {
        --dash-kpi-soft: rgba(124, 58, 237, .14);
        --dash-kpi-border: rgba(167, 139, 250, .35);
        --ab-kpi-glow: rgba(124, 58, 237, .12);
    }

    html.dark-mode .dash-kpi.kelas {
        --dash-kpi-soft: rgba(56, 189, 248, .12);
        --dash-kpi-border: rgba(56, 189, 248, .35);
        --ab-kpi-glow: rgba(56, 189, 248, .12);
    }

    html.dark-mode .dash-kpi.kehadiran {
        --dash-kpi-soft: rgba(52, 211, 153, .12);
        --dash-kpi-border: rgba(52, 211, 153, .35);
        --ab-kpi-glow: rgba(52, 211, 153, .12);
    }

    .abm-kpi.dash-kpi {
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        align-items: stretch;
        gap: 0;
        padding: 12px 16px 10px;
        border-radius: 14px;
        min-height: 100px;
        --ab-kpi-wm: var(--dash-kpi-color);
    }

    .abm-kpi.dash-kpi::after {
        content: '';
        position: absolute;
        top: -20px;
        right: -20px;
        width: 84px;
        height: 84px;
        border-radius: 50%;
        background: var(--ab-kpi-glow, rgba(37, 99, 235, .08));
    }

    .dash-kpi-top {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin-bottom: 8px;
    }

    .dash-kpi-label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
        color: var(--ab-text-2);
    }

    .abm-kpi.dash-kpi .abm-kpi-num {
        font-size: 22px;
        font-weight: 800;
        line-height: 1;
        color: var(--ab-text);
        letter-spacing: -.5px;
        font-variant-numeric: tabular-nums;
    }

    .dash-kpi-foot {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-top: auto;
        padding-top: 8px;
    }

    .dash-kpi-sub {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: var(--ab-text-3);
        line-height: 1.4;
    }

    .dash-kpi-sub i {
        font-size: 11px;
    }

    .dash-kpi-sub.ok {
        color: var(--ab-green);
    }

    .dash-kpi-sub.warn {
        color: var(--ab-amber);
        animation: dashWarnPulse 4s ease-in-out infinite;
    }

    .dash-kpi-body {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-top: 8px;
    }

    .dash-kpi-body .dash-kpi-foot {
        flex: 1;
        margin-top: 0;
    }

    .dash-gauge {
        position: relative;
        flex-shrink: 0;
        width: 58px;
        height: 58px;
    }

    .dash-gauge svg {
        width: 100%;
        height: 100%;
        display: block;
        transform: rotate(-90deg);
    }

    .dash-gauge-track {
        fill: none;
        stroke: var(--dash-kpi-soft);
        stroke-width: 6;
    }

    .dash-gauge-bar {
        fill: none;
        stroke: var(--dash-kpi-color);
        stroke-width: 6;
        stroke-linecap: round;
        stroke-dasharray: 0 100;
        transition: stroke-dasharray 1.4s cubic-bezier(.22, 1, .36, 1);
    }

    .dash-gauge-value {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 800;
        color: var(--ab-text);
        font-variant-numeric: tabular-nums;
    }

    .dash-kpi-bar {
        position: relative;
        overflow: hidden;
        height: 6px;
        border-radius: 6px;
        background: var(--dash-kpi-soft);
        margin-top: 8px;
    }

    .dash-kpi-bar>span {
        display: block;
        height: 100%;
        width: 0;
        border-radius: 6px;
        background: linear-gradient(90deg, var(--dash-kpi-color), var(--dash-kpi-color));
        transition: width 1s cubic-bezier(.22, 1, .36, 1);
    }

    .dash-kpi-chip {
        margin-top: 0;
    }

    /* ---------- Ringkasan kehadiran (di panel Perlu Perhatian) ---------- */
    .dash-dashboard .abm-kpi-grid {
        grid-template-columns: repeat(3, 1fr);
    }

    .dash-attendance {
        display: flex;
        flex-direction: column;
        gap: 10px;
        padding: 14px 16px;
        border-radius: 14px;
        margin-bottom: 12px;
        border: 1px solid var(--ab-border);
        background: var(--ab-card);
    }

    .dash-att-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
    }

    .dash-att-title {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 800;
        color: var(--ab-text);
    }

    .dash-att-title i {
        color: var(--ab-green);
        font-size: 14px;
    }

    .dash-att-pct {
        font-size: 20px;
        color: var(--ab-green);
    }

    .dash-att-foot {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        flex-wrap: wrap;
    }

    /* ---------- Notifikasi (perlu perhatian) ---------- */
    .dash-notif-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .dash-notif {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 14px;
        border-radius: 14px;
        border: 1px solid var(--ab-border);
        background: var(--ab-card);
        transition: transform .25s cubic-bezier(.4, 0, .2, 1), box-shadow .25s, border-color .25s;
    }

    .dash-notif:hover {
        transform: translateY(-2px);
        box-shadow: var(--ab-shadow);
    }

    .dash-notif--danger {
        border-color: var(--ab-red-border);
    }

    .dash-notif--warn {
        border-color: var(--ab-amber-border);
    }

    .dash-notif-icon {
        flex-shrink: 0;
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
    }

    .dash-notif--danger .dash-notif-icon {
        background: var(--ab-red-soft);
        color: var(--ab-red);
    }

    .dash-notif--warn .dash-notif-icon {
        background: var(--ab-amber-soft);
        color: var(--ab-amber);
    }

    .dash-notif-body {
        flex: 1;
        min-width: 0;
    }

    .dash-notif-body strong {
        font-size: 13px;
        color: var(--ab-text);
        display: block;
    }

    .dash-notif-body span {
        font-size: 11.5px;
        color: var(--ab-text-3);
        display: block;
        margin-top: 2px;
    }

    .dash-notif-extra {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }

    .dash-notif-link {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
        font-weight: 700;
        color: var(--ab-primary);
        text-decoration: none !important;
    }

    .dash-notif-link:hover {
        color: var(--ab-violet);
    }

    .dash-all-safe {
        text-align: center;
        padding: 34px 16px;
    }

    .dash-all-safe-icon {
        width: 60px;
        height: 60px;
        margin: 0 auto 12px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        background: var(--ab-green-soft);
        color: var(--ab-green);
    }

    .dash-all-safe-title {
        font-size: 15px;
        font-weight: 800;
        color: var(--ab-text);
    }

    .dash-all-safe-sub {
        font-size: 12px;
        color: var(--ab-text-3);
        margin-top: 4px;
    }

    /* ---------- Aksi cepat ---------- */
    .dash-quick-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }

    .dash-quick {
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        padding: 14px;
        border-radius: 16px;
        border: 1px solid var(--ab-border);
        background: var(--ab-border-soft);
        text-decoration: none !important;
        min-height: 108px;
        transition: transform .25s cubic-bezier(.4, 0, .2, 1), box-shadow .25s, border-color .25s;
    }

    .dash-quick:hover {
        transform: translateY(-3px);
        box-shadow: var(--ab-shadow-lg);
        border-color: var(--ab-primary-border);
    }

    .dash-quick-icon {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        color: #fff;
    }

    .dash-quick-icon.blue {
        background: linear-gradient(135deg, #2563eb, #60a5fa);
        box-shadow: 0 6px 14px -4px rgba(37, 99, 235, .4);
    }

    .dash-quick-icon.violet {
        background: linear-gradient(135deg, #7c3aed, #a855f7);
        box-shadow: 0 6px 14px -4px rgba(124, 58, 237, .4);
    }

    .dash-quick-icon.green {
        background: linear-gradient(135deg, #16a34a, #22c55e);
        box-shadow: 0 6px 14px -4px rgba(22, 163, 74, .4);
    }

    .dash-quick-icon.amber {
        background: linear-gradient(135deg, #d97706, #f59e0b);
        box-shadow: 0 6px 14px -4px rgba(217, 119, 6, .4);
    }

    .dash-quick-icon.sky {
        background: linear-gradient(135deg, #0284c7, #0ea5e9);
        box-shadow: 0 6px 14px -4px rgba(2, 132, 199, .4);
    }

    .dash-quick-icon.rose {
        background: linear-gradient(135deg, #dc2626, #f87171);
        box-shadow: 0 6px 14px -4px rgba(220, 38, 38, .4);
    }

    .dash-quick-name {
        font-size: 12.5px;
        font-weight: 800;
        color: var(--ab-text);
    }

    .dash-quick-sub {
        font-size: 10.5px;
        color: var(--ab-text-3);
        margin-top: 2px;
    }

    /* ---------- Grafik ---------- */
    .dash-chart-box {
        position: relative;
        height: 300px;
    }

    .dash-legend-row {
        display: flex;
        flex-wrap: wrap;
        gap: 8px 16px;
        margin-top: 14px;
        padding-top: 14px;
        border-top: 1px dashed var(--ab-border);
    }

    .dash-legend {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 11.5px;
        font-weight: 600;
        color: var(--ab-text-2);
    }

    .dash-legend i {
        font-size: 10px;
    }

    /* ---------- Kalender ---------- */
    .dash-cal {
        border: 1px solid var(--ab-border);
        border-radius: 14px;
        padding: 8px;
        background: var(--ab-border-soft);
    }

    .dash-cal-row {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 4px;
    }

    .dash-cal-head span {
        text-align: center;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: var(--ab-text-3);
        padding: 2px 0;
    }

    .dash-cal-day,
    .dash-cal-empty {
        text-align: center;
        font-size: 11px;
        padding: 4px 0;
        border-radius: 8px;
    }

    .dash-cal-day {
        position: relative;
        color: var(--ab-text);
        font-weight: 600;
        transition: background .2s, transform .2s;
    }

    .dash-cal-day:hover {
        background: var(--ab-card);
    }

    .dash-cal-day.is-today {
        background: var(--ab-grad);
        color: #fff;
        font-weight: 800;
        box-shadow: 0 6px 16px -6px rgba(37, 99, 235, .5);
    }

    .dash-cal-day.is-holiday {
        color: var(--ab-amber);
    }

    .dash-cal-day.is-holiday i {
        position: absolute;
        top: 1px;
        right: 3px;
        font-size: 8px;
    }

    .dash-cal-legend {
        display: flex;
        gap: 16px;
        margin-top: 8px;
        flex-wrap: wrap;
    }

    .dash-cal-key {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 11px;
        color: var(--ab-text-3);
    }

    .dash-cal-key-box {
        width: 12px;
        height: 12px;
        border-radius: 4px;
    }

    .dash-cal-key-box.today {
        background: var(--ab-grad);
    }

    .dash-cal-key-box.holiday {
        background: var(--ab-amber-soft);
        border: 1px solid var(--ab-amber-border);
    }

    /* ---------- Aktivitas (feed) ---------- */
    .dash-feed {
        display: flex;
        flex-direction: column;
    }

    .dash-feed-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 0;
        border-bottom: 1px solid var(--ab-border);
    }

    .dash-feed-item:last-child {
        border-bottom: 0;
    }

    .dash-avatar {
        flex-shrink: 0;
        width: 38px;
        height: 38px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 800;
        color: #fff;
    }

    .dash-avatar.c0 {
        background: linear-gradient(135deg, #2563eb, #60a5fa);
        box-shadow: 0 6px 14px -4px rgba(37, 99, 235, .4);
    }

    .dash-avatar.c1 {
        background: linear-gradient(135deg, #7c3aed, #a855f7);
        box-shadow: 0 6px 14px -4px rgba(124, 58, 237, .4);
    }

    .dash-avatar.c2 {
        background: linear-gradient(135deg, #0ea5e9, #22d3ee);
        box-shadow: 0 6px 14px -4px rgba(2, 132, 199, .4);
    }

    .dash-avatar.c3 {
        background: linear-gradient(135deg, #16a34a, #4ade80);
        box-shadow: 0 6px 14px -4px rgba(22, 163, 74, .4);
    }

    .dash-avatar.c4 {
        background: linear-gradient(135deg, #d97706, #f59e0b);
        box-shadow: 0 6px 14px -4px rgba(217, 119, 6, .4);
    }

    .dash-avatar.c5 {
        background: linear-gradient(135deg, #db2777, #f472b6);
        box-shadow: 0 6px 14px -4px rgba(219, 39, 119, .4);
    }

    .dash-feed-body {
        flex: 1;
        min-width: 0;
    }

    .dash-feed-name {
        font-size: 13px;
        font-weight: 700;
        color: var(--ab-text);
    }

    .dash-feed-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        font-size: 11px;
        color: var(--ab-text-3);
        margin-top: 2px;
    }

    .dash-feed-meta i {
        font-size: 10px;
    }

    /* ---------- Pengumuman ---------- */
    .dash-news-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    .dash-news-item {
        display: flex;
        gap: 12px;
        padding: 14px;
        border-radius: 14px;
        border: 1px solid var(--ab-border);
        background: var(--ab-border-soft);
        transition: transform .25s cubic-bezier(.4, 0, .2, 1), box-shadow .25s, border-color .25s;
    }

    .dash-news-item:hover {
        transform: translateY(-2px);
        box-shadow: var(--ab-shadow);
        border-color: var(--ab-primary-border);
    }

    .dash-news-icon {
        flex-shrink: 0;
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        background: var(--ab-primary-soft);
        color: var(--ab-primary);
    }

    .dash-news-body {
        min-width: 0;
    }

    .dash-news-title {
        font-size: 13px;
        font-weight: 800;
        color: var(--ab-text);
    }

    .dash-news-excerpt {
        font-size: 11.5px;
        color: var(--ab-text-3);
        margin-top: 3px;
        line-height: 1.45;
    }

    .dash-news-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 8px;
        flex-wrap: wrap;
    }

    .dash-news-date {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 11px;
        color: var(--ab-text-3);
    }

    /* ---------- Ripple ---------- */
    .dash-ripple {
        position: absolute;
        border-radius: 50%;
        pointer-events: none;
        background: rgba(255, 255, 255, .35);
        transform: scale(0);
        animation: dash-ripple .6s ease-out forwards;
    }

    @keyframes dash-ripple {
        to {
            transform: scale(2.6);
            opacity: 0;
        }
    }

    /* ---------- Toast ---------- */
    .dash-toast-wrap {
        position: fixed;
        top: 12px;
        right: 12px;
        z-index: 1300;
    }

    .dash-toast {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 320px;
        max-width: 420px;
        background: var(--ab-card);
        border: 1px solid var(--ab-border);
        border-left: 4px solid var(--ab-green);
        border-radius: 16px;
        padding: 14px 14px 14px 16px;
        box-shadow: 0 24px 60px -16px rgba(15, 23, 42, .35);
        opacity: 0;
        transform: translateY(-16px);
        transition: opacity .4s ease, transform .4s ease;
    }

    .dash-toast.is-show {
        opacity: 1;
        transform: none;
    }

    .dash-toast-icon {
        flex-shrink: 0;
        width: 38px;
        height: 38px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        background: var(--ab-green-soft);
        color: var(--ab-green);
    }

    .dash-toast-body {
        flex: 1;
        min-width: 0;
    }

    .dash-toast-body strong {
        font-size: 13px;
        color: var(--ab-text);
        display: block;
    }

    .dash-toast-body span {
        font-size: 11.5px;
        color: var(--ab-text-3);
        display: block;
        margin-top: 2px;
    }

    .dash-toast-close {
        border: 0;
        background: transparent;
        color: var(--ab-text-3);
        font-size: 13px;
        cursor: pointer;
        padding: 6px;
        border-radius: 8px;
        line-height: 1;
        transition: color .2s, background .2s;
    }

    .dash-toast-close:hover {
        color: var(--ab-red);
        background: var(--ab-red-soft);
    }

    /* ---------- Grid kolom ---------- */
    .dash-cols {
        display: grid;
        gap: 18px;
    }

    .dash-cols--83,
    .dash-cols--3 {
        grid-template-columns: 1fr;
    }

    .dash-main-col {
        display: flex;
        flex-direction: column;
        gap: 18px;
        min-width: 0;
    }

    .dash-main-col .abm-hero {
        margin-bottom: 0;
    }

    .dash-main-col .abm-kpi-grid {
        margin-bottom: 0;
        align-content: start;
    }

    /* ---------- Baris atas: kolom kanan (kalender) sejajar dengan KPI ---------- */
    .dash-cols--top {
        align-items: stretch;
    }

    .dash-cols--top>.dash-panel {
        display: flex;
        flex-direction: column;
        min-height: 0;
    }

    .dash-cols--top .dash-cal {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    /* ---------- Panel bawah 3 kolom: kompak & seragam ---------- */
    .dash-cols--3 {
        align-items: stretch;
    }

    .dash-cols--3>.dash-panel {
        display: flex;
        flex-direction: column;
    }

    .dash-cols--3 .dash-attendance {
        padding: 10px 14px;
        gap: 8px;
        margin-bottom: 10px;
    }

    .dash-cols--3 .dash-att-pct {
        font-size: 18px;
    }

    .dash-cols--3 .dash-notif-list,
    .dash-cols--3 .dash-feed,
    .dash-cols--3 .dash-news-grid {
        max-height: 200px;
        overflow-y: auto;
        scrollbar-width: thin;
    }

    .dash-cols--3 .dash-notif {
        padding: 10px 12px;
    }

    .dash-cols--3 .dash-feed-item {
        padding: 9px 0;
    }

    .dash-cols--3 .dash-news-grid {
        grid-template-columns: 1fr;
    }

    /* ---------- Responsive ---------- */
    @media (min-width: 992px) {
        .dash-cols--top {
            grid-template-columns: minmax(0, 1fr) 300px;
        }
    }

    @media (min-width: 768px) and (max-width: 991.98px) {
        .dash-cols--83 {
            grid-template-columns: 1fr 1fr;
        }

        .dash-cols--top {
            grid-template-columns: 1fr;
        }

        .dash-news-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (min-width: 1200px) {
        .dash-cols--83:not(.dash-cols--top) {
            grid-template-columns: 2fr 1fr;
        }

        .dash-cols--3 {
            grid-template-columns: 1fr 1fr 1fr;
        }
    }

    @media (max-width: 575.98px) {
        .abs-mod.dash-dashboard {
            padding: 14px;
            border-radius: 18px;
        }

        .dash-skel-grid4 {
            grid-template-columns: 1fr 1fr;
        }

        .dash-skel-grid2 {
            grid-template-columns: 1fr;
        }

        .dash-chart-box {
            height: 230px;
        }

        .dash-panel {
            padding: 16px;
        }

        .dash-news-grid {
            grid-template-columns: 1fr;
        }

        .dash-toast-wrap {
            top: 72px;
            left: 12px;
            right: 12px;
        }

        .dash-toast {
            min-width: 0;
            width: 100%;
        }
    }

    /* ---------- Premium polish ---------- */
    .dash-digit {
        display: inline-block;
        will-change: transform;
    }

    .dash-digit.is-new {
        animation: dashDigitTick .25s cubic-bezier(.34, 1.56, .64, 1);
    }

    @keyframes dashDigitTick {
        0% {
            transform: translateY(-38%) rotateX(80deg);
            opacity: 0;
            filter: blur(3px);
        }
        60% {
            filter: blur(1px);
        }
        100% {
            transform: none;
            opacity: 1;
            filter: blur(0);
        }
    }

    .abm-kpi.dash-kpi .abm-kpi-watermark {
        right: -4px;
        bottom: -12px;
        font-size: 140px;
        opacity: .05;
        animation: dashWatermarkFloat 5s ease-in-out infinite;
    }

    @keyframes dashWatermarkFloat {
        0%,
        100% {
            transform: translateY(0) rotate(0deg);
        }
        50% {
            transform: translateY(-8px) rotate(4deg);
        }
    }

    .abm-kpi.dash-kpi .dash-gauge {
        transition: transform .35s cubic-bezier(.34, 1.56, .64, 1);
    }

    .abm-kpi.dash-kpi:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 44px -14px var(--ab-kpi-glow, rgba(37, 99, 235, .22)), var(--ab-shadow-lg);
        border-color: var(--dash-kpi-border);
    }

    .abm-kpi.dash-kpi:hover .dash-gauge {
        transform: scale(1.06);
    }

    .abm-kpi.dash-kpi:hover .abm-kpi-icon {
        transform: rotate(8deg) scale(1.08);
    }

    @keyframes dashWarnPulse {
        0%,
        100% {
            opacity: 1;
        }
        50% {
            opacity: .55;
        }
    }

    @keyframes dashCardIn {
        from {
            opacity: 0;
            transform: translateY(20px) scale(.98);
        }
        to {
            opacity: 1;
            transform: none;
        }
    }

    .dash-reveal.is-ready .dash-cal-panel {
        animation: dashSlideRight .5s cubic-bezier(.22, 1, .36, 1) both;
    }

    @keyframes dashSlideRight {
        from {
            opacity: 0;
            transform: translateX(48px);
        }
        to {
            opacity: 1;
            transform: none;
        }
    }

    .dash-cal-day {
        transition: background .2s, transform .2s cubic-bezier(.34, 1.56, .64, 1);
    }

    .dash-cal-day:hover {
        transform: scale(1.18);
    }

    .dash-cal-day.is-today::after {
        content: '';
        position: absolute;
        inset: -2px;
        border-radius: 10px;
        pointer-events: none;
        animation: dashTodayPulse 2.4s ease-out infinite;
    }

    @keyframes dashTodayPulse {
        0% {
            box-shadow: 0 0 0 0 rgba(37, 99, 235, .35);
        }
        70% {
            box-shadow: 0 0 0 7px rgba(37, 99, 235, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(37, 99, 235, 0);
        }
    }

    .dash-quick-icon {
        transition: transform .3s cubic-bezier(.34, 1.56, .64, 1);
    }

    .dash-quick:hover .dash-quick-icon {
        transform: rotate(-6deg) scale(1.1);
    }

    .dash-reveal.is-ready .dash-quick-grid .dash-quick {
        animation: dashCardIn .45s cubic-bezier(.16, 1, .3, 1) both;
    }

    .dash-reveal.is-ready .dash-quick-grid .dash-quick:nth-child(1) {
        animation-delay: .08s;
    }

    .dash-reveal.is-ready .dash-quick-grid .dash-quick:nth-child(2) {
        animation-delay: .14s;
    }

    .dash-reveal.is-ready .dash-quick-grid .dash-quick:nth-child(3) {
        animation-delay: .20s;
    }

    .dash-reveal.is-ready .dash-quick-grid .dash-quick:nth-child(4) {
        animation-delay: .26s;
    }

    .dash-reveal.is-ready .dash-quick-grid .dash-quick:nth-child(5) {
        animation-delay: .32s;
    }

    .dash-reveal.is-ready .dash-quick-grid .dash-quick:nth-child(6) {
        animation-delay: .38s;
    }

    .dash-reveal.is-ready .dash-feed-item {
        animation: dashCardIn .45s cubic-bezier(.16, 1, .3, 1) both;
    }

    .dash-reveal.is-ready .dash-feed-item:nth-child(1) {
        animation-delay: .10s;
    }

    .dash-reveal.is-ready .dash-feed-item:nth-child(2) {
        animation-delay: .16s;
    }

    .dash-reveal.is-ready .dash-feed-item:nth-child(3) {
        animation-delay: .22s;
    }

    .dash-reveal.is-ready .dash-feed-item:nth-child(4) {
        animation-delay: .28s;
    }

    .dash-reveal.is-ready .dash-feed-item:nth-child(5) {
        animation-delay: .34s;
    }

    .dash-reveal.is-ready .dash-feed-item:nth-child(6) {
        animation-delay: .40s;
    }

    /* ---------- Chart tooltip (fade + scale) ---------- */
    #chartjs-tooltip {
        position: absolute;
        z-index: 99;
        background: #0f172a;
        color: #fff;
        border-radius: 10px;
        padding: 8px 12px;
        box-shadow: 0 12px 28px -10px rgba(15, 23, 42, .4);
        font-size: 12px;
        font-weight: 600;
        line-height: 1.45;
        opacity: 0;
        pointer-events: none;
        transform: translate(-50%, -100%) scale(.92);
        transform-origin: bottom center;
        transition: opacity .15s ease, transform .2s cubic-bezier(.34, 1.56, .64, 1);
    }

    #chartjs-tooltip.is-show {
        transform: translate(-50%, -100%) scale(1);
    }

    #chartjs-tooltip .dash-tt-title {
        font-size: 10.5px;
        font-weight: 500;
        color: rgba(255, 255, 255, .72);
        margin-bottom: 3px;
    }

    #chartjs-tooltip .dash-tt-row {
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    #chartjs-tooltip .dash-tt-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    @media (prefers-reduced-motion: reduce) {
        #chartjs-tooltip {
            transition: none;
        }
    }

    /* ---------- Reduced motion ---------- */
    @media (prefers-reduced-motion: reduce) {
        .dash-dashboard * {
            transition: none !important;
            animation: none !important;
        }

        .dash-reveal>* {
            opacity: 1 !important;
            transform: none !important;
        }

        .dash-dashboard .abm-kpi:hover,
        .dash-dashboard .abm-card:hover,
        .dash-dashboard .dash-quick:hover,
        .dash-dashboard .dash-notif:hover,
        .dash-dashboard .dash-news-item:hover,
        .dash-dashboard .abm-hero-badge:hover,
        .dash-dashboard .abm-hero-clock:hover {
            transform: none !important;
        }
    }
</style>
<noscript>
    <style>
        .dash-reveal>* {
            opacity: 1 !important;
            transform: none !important;
        }
    </style>
</noscript>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    (function() {
        'use strict';
        const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const root = document.getElementById('dashDashboard');
        if (!root) return;

        const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        /* ---------- Jam realtime ---------- */
        function renderClock(el, value) {
            const prev = el.getAttribute('data-time') || '';
            el.setAttribute('data-time', value);
            if (prefersReduced) {
                el.textContent = value;
                return;
            }
            let html = '';
            for (let i = 0; i < value.length; i++) {
                const ch = value.charAt(i);
                const isNew = prev.charAt(i) !== ch;
                html += '<span class="dash-digit' + (isNew ? ' is-new' : '') + '">' + ch + '</span>';
            }
            el.innerHTML = html;
            el.querySelectorAll('.dash-digit.is-new').forEach(function(span) {
                span.addEventListener('animationend', function() {
                    this.classList.remove('is-new');
                });
            });
        }

        function updateClock() {
            const now = new Date();
            const pad = function(n) {
                return String(n).padStart(2, '0');
            };
            const time = pad(now.getHours()) + ':' + pad(now.getMinutes()) + ':' + pad(now.getSeconds());
            const date = dayNames[now.getDay()] + ', ' + now.getDate() + ' ' + monthNames[now.getMonth()] + ' ' + now.getFullYear();
            const t = document.getElementById('liveClock');
            const d = document.getElementById('liveClockDate');
            if (t) renderClock(t, time);
            if (d && d.textContent !== date) {
                d.classList.remove('dash-date-swap');
                void d.offsetWidth;
                d.textContent = date;
                d.classList.add('dash-date-swap');
            }
        }
        updateClock();
        setInterval(updateClock, 1000);

        /* ---------- Counter animation ---------- */
        function animateCount(el, target, suffix) {
            if (prefersReduced) {
                el.textContent = target.toLocaleString('id-ID') + suffix;
                el.setAttribute('data-count', target);
                return;
            }
            const from = parseInt(String(el.textContent).replace(/[^\d]/g, ''), 10) || 0;
            const start = performance.now();
            const duration = 1200;
            const frame = function(now) {
                const p = Math.min((now - start) / duration, 1);
                const eased = 1 - Math.pow(1 - p, 3);
                const val = Math.round(from + (target - from) * eased);
                el.textContent = val.toLocaleString('id-ID') + suffix;
                if (p < 1) {
                    requestAnimationFrame(frame);
                } else {
                    el.setAttribute('data-count', target);
                }
            };
            requestAnimationFrame(frame);
        }
        document.querySelectorAll('.abm-kpi-num[data-count]').forEach(function(el) {
            const target = parseInt(el.getAttribute('data-count'), 10) || 0;
            const suffix = el.getAttribute('data-suffix') || '';
            animateCount(el, target, suffix);
        });
        document.querySelectorAll('.dash-legend-num[data-count]').forEach(function(el) {
            const target = parseInt(el.getAttribute('data-count'), 10) || 0;
            animateCount(el, target, '');
        });

        /* ---------- Progress animation ---------- */
        document.querySelectorAll('.abm-progress > span[data-width], .dash-kpi-bar > span[data-width]').forEach(function(span) {
            const w = parseInt(span.getAttribute('data-width'), 10) || 0;
            requestAnimationFrame(function() {
                setTimeout(function() {
                    span.style.width = w + '%';
                }, 250);
            });
        });

        /* ---------- Gauge lingkaran (KPI) ---------- */
        function animateGauge(g, pct, delay) {
            const clamped = Math.min(Math.max(parseInt(pct, 10) || 0, 0), 100);
            const bar = g.querySelector('.dash-gauge-bar');
            const val = g.querySelector('.dash-gauge-value');
            if (val) val.textContent = clamped + '%';
            if (!bar) return;
            if (prefersReduced) {
                bar.style.strokeDasharray = clamped + ' 100';
                return;
            }
            requestAnimationFrame(function() {
                setTimeout(function() {
                    bar.style.strokeDasharray = clamped + ' 100';
                }, delay || 250);
            });
        }
        document.querySelectorAll('.dash-gauge[data-gauge]').forEach(function(g) {
            animateGauge(g, g.getAttribute('data-gauge'));
        });

        /* ---------- Grafik kehadiran 30 hari ---------- */
        const canvas = document.getElementById('dashAttendanceChart');
        if (canvas) {
            const labels = @json($labels30);
            const data = @json($hadir30);
            const filled = data.map(function(v) {
                return v === null ? 0 : v;
            });
            const hasData = data.some(function(v) {
                return v !== null && v > 0;
            });

            const gradient = canvas.getContext('2d').createLinearGradient(0, 0, 0, 260);
            gradient.addColorStop(0, 'rgba(37,99,235,0.28)');
            gradient.addColorStop(1, 'rgba(37,99,235,0.02)');

            /* ---------- Tooltip HTML (fade + scale) ---------- */
            const tooltipEl = document.createElement('div');
            tooltipEl.id = 'chartjs-tooltip';
            document.body.appendChild(tooltipEl);

            /* ---------- Garis "berjalan": reveal kiri → kanan ---------- */
            const dashProgressive = {
                id: 'dashProgressive',
                beforeDraw: function(chart) {
                    const wipe = chart.$dashWipe === undefined ? 0 : chart.$dashWipe;
                    if (wipe >= 1) return;
                    const area = chart.chartArea;
                    const ctx = chart.ctx;
                    const x = area.left + (area.right - area.left) * wipe;
                    ctx.save();
                    ctx.beginPath();
                    ctx.rect(area.left, area.top, Math.max(x - area.left, 1), area.height);
                    ctx.clip();
                },
                afterDraw: function(chart) {
                    const wipe = chart.$dashWipe === undefined ? 0 : chart.$dashWipe;
                    if (wipe >= 1) return;
                    chart.ctx.restore();
                }
            };

            new Chart(canvas, {
                plugins: [dashProgressive],
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: '% Hadir',
                        data: hasData ? filled : [],
                        borderColor: '#2563eb',
                        backgroundColor: gradient,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 3,
                        pointHoverRadius: 8,
                        pointHoverBorderWidth: 2,
                        pointBackgroundColor: '#2563eb',
                        pointBorderColor: 'rgba(37,99,235,.35)',
                        pointHoverBackgroundColor: '#2563eb',
                        pointHoverBorderColor: '#ffffff',
                        borderWidth: 2.5,
                        spanGaps: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: prefersReduced ? false : {
                        duration: 1100,
                        easing: 'easeInOutQuart',
                        onProgress: function(event) {
                            const chart = event.chart;
                            chart.$dashWipe = Math.min((event.currentStep || 0) / Math.max(event.numSteps || 1, 1), 1);
                        },
                        onComplete: function(event) {
                            event.chart.$dashWipe = 1;
                        },
                        y: {
                            type: 'number',
                            duration: 1100,
                            easing: 'easeInOutQuart',
                            from: 0
                        },
                        active: {
                            duration: 200,
                            easing: 'easeOutBack'
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                pointStyle: 'circle',
                                boxWidth: 8,
                                boxHeight: 8,
                                padding: 14,
                                font: {
                                    size: 11.5,
                                    weight: '600'
                                },
                                color: '#64748b'
                            }
                        },
                        tooltip: {
                            enabled: false,
                            external: function(context) {
                                const model = context.tooltip;
                                if (!model || model.opacity === 0) {
                                    tooltipEl.style.opacity = 0;
                                    tooltipEl.classList.remove('is-show');
                                    return;
                                }
                                if (model.body) {
                                    const rows = model.body.map(function(b) {
                                        return b.lines.join(' ');
                                    });
                                    let html = '';
                                    if (model.title && model.title.length) {
                                        html += '<div class="dash-tt-title">' + model.title[0] + '</div>';
                                    }
                                    rows.forEach(function(line, i) {
                                        const color = model.labelColors[i] ? model.labelColors[i].backgroundColor : '#2563eb';
                                        html += '<div class="dash-tt-row"><span class="dash-tt-dot" style="background:' + color + '"></span>' + line + '</div>';
                                    });
                                    tooltipEl.innerHTML = html;
                                }
                                const pos = context.chart.canvas.getBoundingClientRect();
                                tooltipEl.style.left = (pos.left + window.pageXOffset + model.caretX) + 'px';
                                tooltipEl.style.top = (pos.top + window.pageYOffset + model.caretY - 14) + 'px';
                                tooltipEl.style.opacity = model.opacity;
                                tooltipEl.classList.add('is-show');
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#94a3b8',
                                maxTicksLimit: 10,
                                maxRotation: 0,
                                font: {
                                    size: 11
                                }
                            },
                            animation: {
                                type: 'number',
                                duration: 850,
                                easing: 'easeOutQuart',
                                delay: 120
                            }
                        },
                        y: {
                            beginAtZero: true,
                            max: 100,
                            grid: {
                                color: 'rgba(148,163,184,0.15)'
                            },
                            ticks: {
                                color: '#94a3b8',
                                callback: function(v) {
                                    return v + '%';
                                }
                            },
                            animation: {
                                type: 'number',
                                duration: 850,
                                easing: 'easeOutQuart',
                                delay: 120
                            }
                        }
                    }
                }
            });
        }

        /* ---------- Ripple ---------- */
        function ripple(e, el) {
            if (prefersReduced) return;
            const rect = el.getBoundingClientRect();
            const d = Math.max(rect.width, rect.height);
            const span = document.createElement('span');
            span.className = 'dash-ripple';
            span.style.width = span.style.height = d + 'px';
            span.style.left = (e.clientX - rect.left - d / 2) + 'px';
            span.style.top = (e.clientY - rect.top - d / 2) + 'px';
            el.appendChild(span);
            setTimeout(function() {
                span.remove();
            }, 600);
        }
        root.querySelectorAll('.dash-quick, .abm-btn, .btn').forEach(function(el) {
            el.addEventListener('click', function(e) {
                ripple(e, this);
            });
        });

        /* ---------- Tooltip ---------- */
        if (window.bootstrap) {
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function(el) {
                new bootstrap.Tooltip(el, {
                    trigger: 'hover focus'
                });
            });
        }

        /* ---------- Skeleton → reveal ---------- */
        const reveal = document.getElementById('dashReveal');

        /* ---------- Entrance hero greeting (GSAP) ---------- */
        function runHeroEntrance() {
            const hero = root.querySelector('.abm-hero');
            if (!hero) return;
            const h3 = hero.querySelector('h3');
            if (h3 && h3.textContent.indexOf('👋') !== -1 && !h3.querySelector('.dash-wave')) {
                h3.innerHTML = h3.innerHTML.replace('👋', '<span class="dash-wave">👋</span>');
            }
            if (prefersReduced || !window.gsap) return;
            const sub = hero.querySelector('.abm-hero-sub');
            const badges = hero.querySelectorAll('.abm-hero-badge');
            const tl = gsap.timeline();
            tl.from(hero, {
                y: 32,
                scale: 0.98,
                autoAlpha: 0,
                duration: 0.6,
                ease: 'expo.out',
                clearProps: 'transform,opacity,visibility'
            });
            if (h3) tl.from(h3, {
                x: -16,
                autoAlpha: 0,
                duration: 0.5,
                ease: 'power2.out',
                clearProps: 'transform,opacity,visibility'
            }, 0.15);
            if (sub) tl.from(sub, {
                autoAlpha: 0,
                duration: 0.5,
                ease: 'power1.out',
                clearProps: 'transform,opacity,visibility'
            }, 0.3);
            if (badges.length) tl.from(badges, {
                y: 12,
                scale: 0.95,
                autoAlpha: 0,
                duration: 0.5,
                ease: 'power2.out',
                stagger: 0.12,
                clearProps: 'transform,opacity,visibility'
            }, 0.45);
        }

        /* ---------- Entrance KPI (GSAP) ---------- */
        function runKpiEntrance() {
            const cards = root.querySelectorAll('.abm-kpi-grid .abm-kpi');
            const foots = root.querySelectorAll('.abm-kpi-grid .dash-kpi-foot');
            if (!cards.length) return;
            if (prefersReduced || !window.gsap) {
                cards.forEach(function(c) {
                    c.style.opacity = '1';
                    c.style.transform = '';
                });
                foots.forEach(function(f) {
                    f.style.opacity = '1';
                });
                return;
            }
            gsap.from(cards, {
                y: 24,
                scale: 0.96,
                autoAlpha: 0,
                duration: 0.5,
                ease: 'power2.out',
                stagger: 0.12,
                clearProps: 'transform,opacity,visibility'
            });
            gsap.from(foots, {
                y: 6,
                autoAlpha: 0,
                duration: 0.4,
                ease: 'power1.out',
                delay: 0.5,
                stagger: 0.12,
                clearProps: 'transform,opacity,visibility'
            });
        }

        function boot() {
            root.classList.add('is-loading');
            setTimeout(function() {
                root.classList.remove('is-loading');
                if (reveal) reveal.classList.add('is-ready');
                runHeroEntrance();
                runKpiEntrance();
                setTimeout(showToast, 650);
            }, 500);
        }

        /* ---------- Live update KPI (tanpa refresh card) ---------- */
        function swapKpiFooter(foot, html) {
            if (!foot) return;
            if (prefersReduced) {
                foot.innerHTML = html;
                return;
            }
            foot.style.transition = 'opacity .25s ease, transform .25s ease';
            foot.style.opacity = '0';
            foot.style.transform = 'translateY(4px)';
            setTimeout(function() {
                foot.innerHTML = html;
                foot.style.opacity = '1';
                foot.style.transform = '';
            }, 140);
        }

        function refreshKpis(next) {
            const nums = root.querySelectorAll('.abm-kpi-num[data-count]');
            const gauges = root.querySelectorAll('.dash-gauge[data-gauge]');
            if (nums[0]) animateCount(nums[0], next.totalSiswa, '');
            if (nums[1]) animateCount(nums[1], next.totalGuru, '');
            if (nums[2]) animateCount(nums[2], next.totalKelas, '');
            if (gauges[0]) animateGauge(gauges[0], next.siswaPct, 0);
            if (gauges[1]) animateGauge(gauges[1], next.guruPct, 0);
            if (gauges[2]) animateGauge(gauges[2], next.kelasPct, 0);
            swapKpiFooter(root.querySelector('.dash-kpi.siswa .dash-kpi-foot'),
                '<span class="dash-kpi-sub"><i class="fas fa-layer-group"></i> ' + next.jenjangTerisi + ' jenjang terisi</span>');
            swapKpiFooter(root.querySelector('.dash-kpi.guru .dash-kpi-foot'),
                next.guruBelumAbsen > 0
                    ? '<span class="dash-kpi-sub warn"><i class="fas fa-hourglass-half"></i> ' + next.guruBelumAbsen + ' guru belum absen</span>'
                    : '<span class="dash-kpi-sub ok"><i class="fas fa-check-circle"></i> Semua guru sudah absen</span>');
            swapKpiFooter(root.querySelector('.dash-kpi.kelas .dash-kpi-foot'),
                '<span class="dash-kpi-sub"><i class="fas fa-chalkboard-teacher"></i> ' + next.sessionsHariIni + ' sesi hari ini</span>');
        }

        window.dashKpi = { refresh: refreshKpis };

        /* ---------- Toast ---------- */
        const toast = document.getElementById('dashToast');
        const toastClose = document.getElementById('dashToastClose');
        let toastTimer = null;

        function showToast() {
            if (!toast || prefersReduced) return;
            toast.classList.add('is-show');
            toastTimer = setTimeout(hideToast, 3800);
        }

        function hideToast() {
            if (!toast) return;
            toast.classList.remove('is-show');
            if (toastTimer) clearTimeout(toastTimer);
        }
        if (toastClose) toastClose.addEventListener('click', hideToast);

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', boot);
        } else {
            boot();
        }
    })();
</script>
@endpush