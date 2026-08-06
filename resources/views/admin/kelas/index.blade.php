@extends('layouts.main')

@section('title', 'Manajemen Kelas')

@section('content')
@include('component.admin.kelas-module')

@php
    $todayLabel = now()->translatedFormat('l, d F Y');
    $taLabel = optional($tahunAktifGlobal)->tahun_ajaran;
    $semesterLabel = optional(optional($tahunAktifGlobal)->semesterAktif)->nama;

    $totalKelas = $kelas->count();
    $totalSiswaAktif = $kelas->sum(fn($item) => $item->siswaAktif->count());
    $totalJenjang = $kelas->pluck('jenjang.kode')->filter()->unique()->count();
    $totalTingkat = $kelas->pluck('tingkat')->unique()->count();

    $miCount = $kelas->filter(fn($item) => optional($item->jenjang)->kode === 'MI')->count();
    $mtsCount = $kelas->filter(fn($item) => optional($item->jenjang)->kode === 'MTs')->count();
    $maCount = $kelas->filter(fn($item) => optional($item->jenjang)->kode === 'MA')->count();

    $kelasTerisi = $kelas->filter(fn($item) => $item->siswaAktif->count() > 0)->count();
    $coverage = $totalKelas > 0 ? round(($kelasTerisi / $totalKelas) * 100) : 0;
    $lastUpdate = $kelas->sortByDesc('updated_at')->first()?->updated_at;
    $lastUpdateLabel = $lastUpdate ? $lastUpdate->translatedFormat('d F Y') : 'Belum ada data';
@endphp

<div class="kls-page" id="klsIndex">

    {{-- ===================== HERO ===================== --}}
    <section class="kls-hero" aria-labelledby="klsHeroTitle">
        <div class="kls-hero-main">
            <div class="kls-crumb" aria-label="Breadcrumb">
                <span><i class="bi bi-grid-1x2-fill"></i> Akademik</span>
                <i class="bi bi-chevron-right"></i>
                <a href="{{ route('kelas.index') }}">Manajemen Kelas</a>
            </div>

            <span class="kls-eyebrow"><i class="bi bi-easel2-fill"></i> Modul Kelas</span>
            <h1 id="klsHeroTitle" class="kls-hero-title">Manajemen Kelas</h1>
            <p class="kls-hero-desc">
                Pusat pengelolaan kelas madrasah untuk Master Siswa, Wali Kelas, Jadwal Pelajaran, hingga pelaporan.
                Dioptimalkan agar Anda bisa bekerja cepat di awal dan akhir tahun ajaran.
            </p>

            <div class="kls-hero-chips">
                <span class="kls-chip kls-chip--blue"><i class="bi bi-calendar-event"></i> {{ $todayLabel }}</span>
                @if($taLabel)
                    <span class="kls-chip kls-chip--green"><i class="bi bi-mortarboard-fill"></i> TA {{ $taLabel }}</span>
                @endif
                @if($semesterLabel)
                    <span class="kls-chip"><i class="bi bi-layers-half"></i> Semester {{ $semesterLabel }}</span>
                @endif
                <span class="kls-chip kls-chip--violet"><i class="bi bi-database-check"></i> Update terakhir {{ $lastUpdateLabel }}</span>
            </div>

            <div class="kls-hero-stats">
                <div class="kls-hero-stat">
                    <div class="k">Total Kelas</div>
                    <div class="v" data-counter="{{ $totalKelas }}">{{ $totalKelas }}</div>
                    <div class="s">Seluruh kelas aktif</div>
                </div>
                <div class="kls-hero-stat">
                    <div class="k">Siswa Aktif</div>
                    <div class="v" data-counter="{{ $totalSiswaAktif }}">{{ $totalSiswaAktif }}</div>
                    <div class="s">Akumulasi lintas kelas</div>
                </div>
                <div class="kls-hero-stat">
                    <div class="k">Jenjang</div>
                    <div class="v" data-counter="{{ $totalJenjang }}">{{ $totalJenjang }}</div>
                    <div class="s">MI · MTs · MA</div>
                </div>
            </div>
        </div>

        <aside class="kls-hero-aside" aria-label="Ringkasan jenjang">
            <div class="kls-hero-panel">
                <h4><i class="bi bi-grid-3x3-gap me-1"></i> Distribusi Jenjang</h4>
                <p>Kelas terisi oleh siswa aktif dihitung sebagai cakupan kesiapan operasional.</p>
                <div class="kls-mini-grid">
                    <div class="kls-mini-stat">
                        <div class="k">MI</div>
                        <div class="v" data-counter="{{ $miCount }}">{{ $miCount }}</div>
                    </div>
                    <div class="kls-mini-stat">
                        <div class="k">MTs</div>
                        <div class="v" data-counter="{{ $mtsCount }}">{{ $mtsCount }}</div>
                    </div>
                    <div class="kls-mini-stat">
                        <div class="k">MA</div>
                        <div class="v" data-counter="{{ $maCount }}">{{ $maCount }}</div>
                    </div>
                    <div class="kls-mini-stat">
                        <div class="k">Tingkat</div>
                        <div class="v" data-counter="{{ $totalTingkat }}">{{ $totalTingkat }}</div>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <button type="button" class="kls-btn kls-btn--primary" data-bs-toggle="modal" data-bs-target="#klsModalTambah">
                    <i class="bi bi-plus-lg"></i> Tambah Kelas
                </button>
                <a href="{{ route('wali-kelas.index') }}" class="kls-btn kls-btn--secondary">
                    <i class="bi bi-person-video3"></i> Wali Kelas
                </a>
            </div>
        </aside>
    </section>

    {{-- ===================== KPI CARDS ===================== --}}
    <section class="kls-kpi-grid" aria-label="Statistik kelas">
        <article class="kls-kpi" data-bs-toggle="tooltip" data-bs-placement="top" title="Seluruh kelas aktif yang dapat dikelola">
            <div class="kls-kpi-top">
                <span class="kls-kpi-ico blue"><i class="bi bi-collection-fill"></i></span>
                <span class="kls-kpi-tag"><i class="bi bi-circle-fill" style="font-size:6px"></i> Total</span>
            </div>
            <div class="kls-kpi-num" data-counter="{{ $totalKelas }}">{{ $totalKelas }}</div>
            <div class="kls-kpi-label">Total Kelas</div>
            <div class="kls-kpi-sub">{{ $totalTingkat }} tingkat aktif</div>
            <div class="kls-kpi-bar"><span data-width="100"></span></div>
        </article>

        <article class="kls-kpi" data-bs-toggle="tooltip" data-bs-placement="top" title="Jumlah kelas jenjang Madrasah Ibtidaiyah">
            <div class="kls-kpi-top">
                <span class="kls-kpi-ico green"><i class="bi bi-mortarboard-fill"></i></span>
                <span class="kls-kpi-tag">MI</span>
            </div>
            <div class="kls-kpi-num" data-counter="{{ $miCount }}">{{ $miCount }}</div>
            <div class="kls-kpi-label">Kelas MI</div>
            <div class="kls-kpi-sub">Madrasah Ibtidaiyah</div>
            <div class="kls-kpi-bar"><span data-width="{{ $totalKelas ? round($miCount / $totalKelas * 100) : 0 }}"></span></div>
        </article>

        <article class="kls-kpi" data-bs-toggle="tooltip" data-bs-placement="top" title="Jumlah kelas jenjang Madrasah Tsanawiyah">
            <div class="kls-kpi-top">
                <span class="kls-kpi-ico info"><i class="bi bi-building-fill"></i></span>
                <span class="kls-kpi-tag">MTs</span>
            </div>
            <div class="kls-kpi-num" data-counter="{{ $mtsCount }}">{{ $mtsCount }}</div>
            <div class="kls-kpi-label">Kelas MTs</div>
            <div class="kls-kpi-sub">Madrasah Tsanawiyah</div>
            <div class="kls-kpi-bar"><span data-width="{{ $totalKelas ? round($mtsCount / $totalKelas * 100) : 0 }}"></span></div>
        </article>

        <article class="kls-kpi" data-bs-toggle="tooltip" data-bs-placement="top" title="Jumlah kelas jenjang Madrasah Aliyah">
            <div class="kls-kpi-top">
                <span class="kls-kpi-ico amber"><i class="bi bi-award-fill"></i></span>
                <span class="kls-kpi-tag">MA</span>
            </div>
            <div class="kls-kpi-num" data-counter="{{ $maCount }}">{{ $maCount }}</div>
            <div class="kls-kpi-label">Kelas MA</div>
            <div class="kls-kpi-sub">Madrasah Aliyah</div>
            <div class="kls-kpi-bar"><span data-width="{{ $totalKelas ? round($maCount / $totalKelas * 100) : 0 }}"></span></div>
        </article>

        <article class="kls-kpi" data-bs-toggle="tooltip" data-bs-placement="top" title="Jumlah siswa yang terdaftar aktif di seluruh kelas">
            <div class="kls-kpi-top">
                <span class="kls-kpi-ico violet"><i class="bi bi-people-fill"></i></span>
                <span class="kls-kpi-tag"><i class="bi bi-circle-fill" style="font-size:6px"></i> Aktif</span>
            </div>
            <div class="kls-kpi-num" data-counter="{{ $totalSiswaAktif }}">{{ $totalSiswaAktif }}</div>
            <div class="kls-kpi-label">Total Siswa Aktif</div>
            <div class="kls-kpi-sub">{{ $kelasTerisi }} kelas terisi</div>
            <div class="kls-kpi-bar"><span data-width="{{ $coverage }}"></span></div>
        </article>
    </section>

    {{-- ===================== TOOLBAR ===================== --}}
    <section class="kls-toolbar" aria-label="Toolbar manajemen kelas">
        <div class="kls-toolbar-top">
            <div class="kls-toolbar-title">
                <span class="kls-kpi-ico blue"><i class="bi bi-sliders2"></i></span>
                <div>
                    <h2>Daftar Kelas</h2>
                    <p id="klsResultInfo">Menampilkan {{ $totalKelas }} kelas</p>
                </div>
            </div>
            <div class="kls-seg" role="group" aria-label="Mode tampilan">
                <button type="button" class="is-active" data-view="grid"><i class="bi bi-grid-3x3-gap-fill"></i> Grid</button>
                <button type="button" data-view="list"><i class="bi bi-list-ul"></i> List</button>
            </div>
        </div>

        <div class="kls-toolbar-row">
            <div class="kls-field">
                <label for="klsSearch">Cari Kelas</label>
                <div class="kls-input-wrap">
                    <i class="bi bi-search"></i>
                    <input type="search" id="klsSearch" class="kls-input" placeholder="Cari nama kelas, jenjang, tingkat, atau wali kelas..." aria-label="Cari kelas">
                </div>
            </div>

            <div class="kls-field">
                <label for="klsJenjang">Jenjang</label>
                <select id="klsJenjang" class="kls-select" aria-label="Filter jenjang">
                    <option value="">Semua Jenjang</option>
                    <option value="MI">MI</option>
                    <option value="MTs">MTs</option>
                    <option value="MA">MA</option>
                </select>
            </div>

            <div class="kls-field">
                <label for="klsLength">Data / Halaman</label>
                <select id="klsLength" class="kls-select" aria-label="Jumlah data per halaman">
                    <option value="5">5</option>
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>

            <div class="kls-field">
                <label>&nbsp;</label>
                <button type="button" id="klsReset" class="kls-btn kls-btn--secondary">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </button>
            </div>

            <div class="kls-field">
                <label>&nbsp;</label>
                <button type="button" class="kls-btn kls-btn--primary" data-bs-toggle="modal" data-bs-target="#klsModalTambah">
                    <i class="bi bi-plus-lg"></i> Tambah Kelas
                </button>
            </div>
        </div>
    </section>

    {{-- ===================== FLASH / ALERTS ===================== --}}
    <div class="mt-3 d-grid gap-2">
        @if(session('success'))
            <div class="kls-alert ok" role="status">
                <i class="bi bi-check-circle-fill"></i>
                <div><b>Perubahan berhasil</b>{{ session('success') }}</div>
            </div>
        @endif
        @if(session('error'))
            <div class="kls-alert err" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div><b>Aksi belum dapat diproses</b>{{ session('error') }}</div>
            </div>
        @endif
        @if($errors->any())
            <div class="kls-alert err" role="alert">
                <i class="bi bi-shield-exclamation"></i>
                <div><b>Form perlu diperiksa kembali</b>
                    @foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach
                </div>
            </div>
        @endif
    </div>

    {{-- ===================== SKELETON ===================== --}}
    <div class="kls-skeleton" id="klsSkeleton" aria-hidden="true">
        <div class="kls-skel-card"></div>
        <div class="kls-skel-card"></div>
        <div class="kls-skel-card"></div>
    </div>

    @if($kelas->count() > 0)
        {{-- ===================== GRID VIEW ===================== --}}
        <div class="kls-grid" id="klsGrid" aria-live="polite">
            @foreach($kelas as $k)
                @php
                    $kode = optional($k->jenjang)->kode ?? '';
                    $kodeClass = strtolower($kode) ?: 'default';
                    $count = $k->siswaAktif->count();
                    $status = $count > 0 ? 'Aktif' : 'Kosong';
                    $statusCls = $count > 0 ? 'green' : 'neutral';
                    $wali = optional(optional($k->waliKelas)->guru)->nama ?? 'Belum ditetapkan';
                    $capPct = min(100, round($count / 40 * 100));
                    $capCls = $count >= 30 ? 'red' : ($count >= 16 ? 'amber' : 'green');
                    $searchText = strtolower(($kode ?? '') . ' ' . ($k->nama_kelas ?? '') . ' tingkat ' . $k->tingkat . ' ' . $wali);
                @endphp
                <article class="kls-classcard"
                         data-search="{{ $searchText }}"
                         data-jenjang="{{ $kode }}"
                         data-kelas="{{ $k->nama_kelas }}"
                         data-tingkat="{{ $k->tingkat }}">
                    <div class="kls-classcard-top">
                        <div class="kls-classcard-title">
                            <span class="kls-class-ico {{ in_array($kodeClass, ['mi','mts','ma']) ? $kodeClass : 'default' }}" aria-hidden="true">
                                <i class="bi bi-easel2-fill"></i>
                            </span>
                            <div>
                                <div class="kls-classcard-name">Kelas {{ $k->nama_kelas }}</div>
                                <div class="kls-classcard-meta">Tingkat {{ $k->tingkat }}</div>
                            </div>
                        </div>
                        <span class="kls-chip kls-chip--{{ $statusCls }}">
                            <i class="bi {{ $count > 0 ? 'bi-check-circle-fill' : 'bi-inbox' }}"></i> {{ $status }}
                        </span>
                    </div>

                    <div class="kls-classcard-body">
                        <div class="kls-classcard-row">
                            <i class="bi bi-building"></i>
                            <span>Jenjang <b>{{ $kode ?: '-' }}</b></span>
                        </div>
                        <div class="kls-classcard-row">
                            <i class="bi bi-person-video3"></i>
                            <span>Wali Kelas <b>{{ $wali }}</b></span>
                        </div>
                        <div class="kls-classcard-row">
                            <i class="bi bi-people"></i>
                            <span>Siswa Aktif <b>{{ $count }} siswa</b></span>
                        </div>
                        <div class="kls-capacity">
                            <div class="kls-capacity-top">
                                <span>Kapasitas kelas</span>
                                <b>{{ $count }}/40</b>
                            </div>
                            <div class="kls-progress {{ $capCls }}"><span data-fill="{{ $capPct }}"></span></div>
                        </div>
                    </div>

                    <div class="kls-classcard-foot">
                        <span class="kls-updated"><i class="bi bi-clock-history"></i> {{ $k->updated_at->diffForHumans() }}</span>
                        <div class="kls-actions">
                            <a href="{{ route('kelas.show', $k->id) }}" class="kls-icon-btn kls-icon-btn--blue" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat detail" aria-label="Lihat detail kelas {{ $k->nama_kelas }}">
                                <i class="bi bi-eye"></i>
                            </a>
                            <button type="button" class="kls-icon-btn kls-icon-btn--amber" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit kelas" aria-label="Edit kelas {{ $k->nama_kelas }}" data-bs-toggle="modal" data-bs-target="#klsModalEdit{{ $k->id }}">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <a href="{{ route('kelas.show', $k->id) }}#klsSiswa" class="kls-icon-btn kls-icon-btn--green" data-bs-toggle="tooltip" data-bs-placement="top" title="Daftar siswa" aria-label="Daftar siswa kelas {{ $k->nama_kelas }}">
                                <i class="bi bi-people"></i>
                            </a>
                            <a href="{{ route('jadwal-pelajaran.per-kelas', $k->id) }}" class="kls-icon-btn kls-icon-btn--violet" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat jadwal" aria-label="Jadwal kelas {{ $k->nama_kelas }}">
                                <i class="bi bi-calendar-week"></i>
                            </a>
                            <a href="{{ route('jadwal-pelajaran.cetak', $k->id) }}" target="_blank" class="kls-icon-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Cetak jadwal" aria-label="Cetak jadwal kelas {{ $k->nama_kelas }}">
                                <i class="bi bi-printer"></i>
                            </a>
                            <button type="button" class="kls-icon-btn kls-icon-btn--red" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus kelas" aria-label="Hapus kelas {{ $k->nama_kelas }}" data-bs-toggle="modal" data-bs-target="#klsModalHapus{{ $k->id }}">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        {{-- ===================== LIST VIEW ===================== --}}
        <div class="kls-listcard">
            <div class="kls-card kls-table-wrap">
                <div class="kls-table-scroll">
                    <table id="klsTable" class="kls-table" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kelas</th>
                                <th>Jenjang</th>
                                <th>Tingkat</th>
                                <th>Wali Kelas</th>
                                <th>Siswa Aktif</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kelas as $k)
                                @php
                                    $kode = optional($k->jenjang)->kode ?? '';
                                    $kodeClass = strtolower($kode) ?: 'default';
                                    $count = $k->siswaAktif->count();
                                    $status = $count > 0 ? 'Aktif' : 'Kosong';
                                    $wali = optional(optional($k->waliKelas)->guru)->nama ?? '-';
                                @endphp
                                <tr>
                                    <td class="num">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <span class="kls-class-ico" style="width:40px;height:40px;font-size:17px;border-radius:12px;background:linear-gradient(135deg,{{ $kode==='MI' ? '#16a34a,#4ade80' : ($kode==='MTs' ? '#2563eb,#60a5fa' : ($kode==='MA' ? '#dc6803,#fbbf24' : '#475467,#98a2b3')) }})"><i class="bi bi-easel2-fill"></i></span>
                                            <div>
                                                <div style="font-weight:800;color:var(--kls-text);font-size:14px;">Kelas {{ $k->nama_kelas }}</div>
                                                <div style="font-size:11px;color:var(--kls-text-3);">Update {{ $k->updated_at->diffForHumans() }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="kls-chip kls-chip--blue">{{ $kode ?: '-' }}</span></td>
                                    <td><span class="kls-chip">{{ $k->tingkat }}</span></td>
                                    <td>{{ $wali }}</td>
                                    <td><span class="kls-chip kls-chip--green"><i class="bi bi-people"></i> {{ $count }}</span></td>
                                    <td><span class="kls-chip kls-chip--{{ $count > 0 ? 'green' : 'neutral' }}">{{ $status }}</span></td>
                                    <td>
                                        <div class="kls-actions">
                                            <a href="{{ route('kelas.show', $k->id) }}" class="kls-icon-btn kls-icon-btn--blue" data-bs-toggle="tooltip" data-bs-placement="top" title="Detail"><i class="bi bi-eye"></i></a>
                                            <button type="button" class="kls-icon-btn kls-icon-btn--amber" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit" data-bs-toggle="modal" data-bs-target="#klsModalEdit{{ $k->id }}"><i class="bi bi-pencil-square"></i></button>
                                            <button type="button" class="kls-icon-btn kls-icon-btn--red" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus" data-bs-toggle="modal" data-bs-target="#klsModalHapus{{ $k->id }}"><i class="bi bi-trash3"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        {{-- ===================== EMPTY STATE ===================== --}}
        <div class="kls-card">
            <div class="kls-empty">
                <div class="kls-empty-illus"><i class="bi bi-easel2"></i></div>
                <h4>Belum ada kelas terdaftar</h4>
                <p>Bangun struktur kelas madrasah di sini agar Master Siswa, Wali Kelas, Jadwal, dan Penilaian memiliki referensi yang konsisten.</p>
                <div class="mt-4">
                    <button type="button" class="kls-btn kls-btn--primary" data-bs-toggle="modal" data-bs-target="#klsModalTambah">
                        <i class="bi bi-plus-lg"></i> Tambah Kelas Pertama
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- ===================== MODAL TAMBAH ===================== --}}
    <div class="modal fade kls-modal" id="klsModalTambah" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <form id="klsFormTambah" action="{{ route('kelas.store') }}" method="POST" novalidate>
                    @csrf
                    <div class="kls-modal-head">
                        <div class="kls-modal-head-inner">
                            <span class="kls-modal-ico blue"><i class="bi bi-plus-lg"></i></span>
                            <div>
                                <h3 class="kls-modal-title">Tambah Kelas Baru</h3>
                                <p class="kls-modal-sub">Buat kelas baru. Sistem otomatis mencegah tingkat di luar rentang jenjang dan kombinasi yang duplikat.</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>

                    <div class="kls-modal-body">
                        <div class="kls-form-grid" id="klsAddFields">
                            <div class="form-floating">
                                <input type="text" name="nama_kelas" id="addNama" class="form-control" placeholder="Nama Kelas" value="{{ old('nama_kelas') }}" required maxlength="10">
                                <label for="addNama">Nama Kelas <span class="text-muted" style="font-weight:500">(maks 10 karakter)</span></label>
                                <div class="kls-field-msg" data-msg-for="nama_kelas"></div>
                            </div>
                            <div class="form-floating">
                                <select name="jenjang_id" id="addJenjang" class="form-select" required>
                                    <option value="" disabled {{ old('jenjang_id') ? '' : 'selected' }}>Pilih Jenjang</option>
                                    @foreach($jenjangs as $jenjang)
                                        <option value="{{ $jenjang->id }}" {{ old('jenjang_id') == $jenjang->id ? 'selected' : '' }}>
                                            {{ $jenjang->kode }} — {{ $jenjang->nama_jenjang }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="addJenjang">Jenjang</label>
                                <div class="kls-field-msg" data-msg-for="jenjang_id"></div>
                            </div>
                            <div class="form-floating">
                                <input type="number" name="tingkat" id="addTingkat" class="form-control" placeholder="Tingkat" value="{{ old('tingkat') }}" min="1" max="12" required>
                                <label for="addTingkat">Tingkat</label>
                                <div class="kls-field-msg" data-msg-for="tingkat"></div>
                            </div>
                        </div>

                        <div id="klsAddSuccess" class="kls-success-anim" style="display:none;">
                            <div class="kls-success-ring"><i class="bi bi-check-lg"></i></div>
                            <h4>Kelas Berhasil Ditambahkan</h4>
                            <p>Menyimpan data kelas...</p>
                        </div>
                    </div>

                    <div class="kls-modal-foot">
                        <button type="button" class="kls-btn kls-btn--ghost" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="kls-btn kls-btn--primary" id="klsBtnAdd">
                            <i class="bi bi-check2-circle"></i> Simpan Kelas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach($kelas as $k)
        @php
            $kode = optional($k->jenjang)->kode ?? '';
            $count = $k->siswaAktif->count();
            $hasStudents = $count > 0;
            $wali = optional(optional($k->waliKelas)->guru)->nama ?? '-';
        @endphp

        {{-- ===================== MODAL EDIT ===================== --}}
        <div class="modal fade kls-modal" id="klsModalEdit{{ $k->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <form id="klsFormEdit{{ $k->id }}" action="{{ route('kelas.update', $k->id) }}" method="POST" novalidate>
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" value="{{ $k->id }}">

                        <div class="kls-modal-head">
                            <div class="kls-modal-head-inner">
                                <span class="kls-modal-ico amber"><i class="bi bi-pencil-square"></i></span>
                                <div>
                                    <h3 class="kls-modal-title">Edit Kelas</h3>
                                    <p class="kls-modal-sub">Perbarui identitas kelas. Perubahan yang belum disimpan ditandai dengan highlight biru.</p>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>

                        <div class="kls-modal-body">
                            <div class="kls-form-grid" data-original-nama="{{ $k->nama_kelas }}" data-original-jenjang="{{ $k->jenjang_id }}" data-original-tingkat="{{ $k->tingkat }}">
                                <div class="form-floating">
                                    <input type="text" name="nama_kelas" class="form-control" placeholder="Nama Kelas" value="{{ $k->nama_kelas }}" required maxlength="10" data-edit-field>
                                    <label>Nama Kelas <span class="text-muted" style="font-weight:500">(maks 10 karakter)</span></label>
                                </div>
                                <div class="form-floating">
                                    <select name="jenjang_id" class="form-select" required data-edit-field>
                                        @foreach($jenjangs as $jenjang)
                                            <option value="{{ $jenjang->id }}" {{ $k->jenjang_id == $jenjang->id ? 'selected' : '' }}>{{ $jenjang->kode }} — {{ $jenjang->nama_jenjang }}</option>
                                        @endforeach
                                    </select>
                                    <label>Jenjang</label>
                                </div>
                                <div class="form-floating">
                                    <input type="number" name="tingkat" class="form-control" placeholder="Tingkat" value="{{ $k->tingkat }}" min="1" max="12" required data-edit-field>
                                    <label>Tingkat</label>
                                </div>
                                <div id="klsEditPreview{{ $k->id }}" class="kls-alert" style="display:none;">
                                    <i class="bi bi-arrow-repeat"></i>
                                    <div><b>Ada perubahan</b><span>Kelas <strong id="prevNama{{ $k->id }}">{{ $k->nama_kelas }}</strong> · <span id="prevJenjang{{ $k->id }}">{{ $kode }}</span> · Tingkat <span id="prevTingkat{{ $k->id }}">{{ $k->tingkat }}</span></span></div>
                                </div>
                            </div>
                        </div>

                        <div class="kls-modal-foot">
                            <button type="button" class="kls-btn kls-btn--ghost" data-kls-reset="{{ $k->id }}">
                                <i class="bi bi-arrow-counterclockwise"></i> Reset
                            </button>
                            <button type="button" class="kls-btn kls-btn--secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="kls-btn kls-btn--primary" data-kls-save="{{ $k->id }}">
                                <i class="bi bi-check2-circle"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ===================== MODAL HAPUS ===================== --}}
        <div class="modal fade kls-modal" id="klsModalHapus{{ $k->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="kls-modal-body text-center pb-2">
                        <div class="kls-confirm-ico {{ $hasStudents ? 'amber' : 'red' }}">
                            <i class="bi {{ $hasStudents ? 'bi-info-circle' : 'bi-trash3' }}"></i>
                        </div>
                        <div class="kls-confirm-title">{{ $hasStudents ? 'Kelas Masih Digunakan' : 'Hapus Kelas Ini?' }}</div>
                        <p class="kls-confirm-text">
                            {{ $hasStudents
                                ? 'Kelas ini masih memiliki siswa aktif sehingga tidak dapat dihapus. Tinjau daftar siswanya terlebih dahulu.'
                                : 'Kelas ' . $k->tingkat . $k->nama_kelas . ' tidak memiliki siswa aktif. Data yang dihapus tidak dapat dikembalikan.' }}
                        </p>
                        <div class="kls-card mt-3" style="text-align:left;padding:14px 16px;">
                            <div class="d-flex justify-content-between py-1"><span class="kls-info-row .k" style="color:var(--kls-text-3);font-size:12px">Nama Kelas</span><b style="font-size:13px">{{ $k->tingkat }}{{ $k->nama_kelas }}</b></div>
                            <div class="d-flex justify-content-between py-1"><span style="color:var(--kls-text-3);font-size:12px">Jenjang</span><b style="font-size:13px">{{ $kode ?: '-' }}</b></div>
                            <div class="d-flex justify-content-between py-1"><span style="color:var(--kls-text-3);font-size:12px">Siswa Aktif</span><b style="font-size:13px">{{ $count }} siswa</b></div>
                            <div class="d-flex justify-content-between py-1"><span style="color:var(--kls-text-3);font-size:12px">Wali Kelas</span><b style="font-size:13px">{{ $wali }}</b></div>
                        </div>
                    </div>
                    <div class="kls-modal-foot">
                        <button type="button" class="kls-btn kls-btn--ghost" data-bs-dismiss="modal">{{ $hasStudents ? 'Tutup' : 'Batal' }}</button>
                        @if($hasStudents)
                            <a href="{{ route('kelas.show', $k->id) }}#klsSiswa" class="kls-btn kls-btn--primary">
                                <i class="bi bi-people"></i> Lihat Daftar Siswa
                            </a>
                        @else
                            <form action="{{ route('kelas.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Hapus kelas {{ $k->nama_kelas }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="kls-btn kls-btn--danger">
                                    <i class="bi bi-trash3"></i> Ya, Hapus
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Toast stack --}}
    <div class="kls-toasts" id="klsToasts" aria-live="polite" aria-atomic="true"></div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var $grid = $('#klsGrid');
    var $list = $('.kls-listcard');
    var $tableEl = $('#klsTable');
    var view = 'grid';
    var table = null;
    var searchTimer = null;

    /* ---------- Init DataTable (list view) ---------- */
    if ($tableEl.length) {
        table = $tableEl.DataTable({
            pagingType: 'simple_numbers',
            responsive: false,
            pageLength: parseInt($('#klsLength').val(), 10) || 10,
            lengthMenu: [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
            dom: 'rt<"d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2 pt-3 px-3 pb-3"lip>',
            language: {
                url: '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian.json',
                zeroRecords: 'Kelas tidak ditemukan',
                info: 'Menampilkan _START_–_END_ dari _TOTAL_ kelas',
                infoEmpty: 'Tidak ada data',
                infoFiltered: '(difilter dari _MAX_)',
                paginate: { first: '«', previous: '‹', next: '›', last: '»' }
            },
            columnDefs: [
                { orderable: false, targets: [0, 7] },
                { searchable: false, targets: [0, 7] }
            ],
            drawCallback: function() {
                var info = table.page.info();
                $('#klsResultInfo').text('Menampilkan ' + info.recordsDisplay + ' dari ' + info.recordsTotal + ' kelas');
            }
        });
    }

    /* ---------- Debounced search ---------- */
    $('#klsSearch').on('input', function() {
        clearTimeout(searchTimer);
        var q = this.value;
        searchTimer = setTimeout(function() { applySearch(q); }, 220);
    });
    function applySearch(q) {
        q = (q || '').trim().toLowerCase();
        if (view === 'list' && table) {
            table.search(q).draw();
            return;
        }
        var total = 0;
        $grid.find('.kls-classcard').each(function() {
            var match = !q || ($(this).data('search') || '').indexOf(q) !== -1;
            var jn = $('#klsJenjang').val();
            if (jn && $(this).data('jenjang') !== jn) match = false;
            $(this).toggle(match);
            if (match) total++;
        });
        $('#klsResultInfo').text('Menampilkan ' + total + ' dari ' + $grid.find('.kls-classcard').length + ' kelas');
    }

    /* ---------- Jenjang filter ---------- */
    $('#klsJenjang').on('change', function() {
        if (view === 'list' && table) {
            table.column(2).search(this.value).draw();
            return;
        }
        applySearch($('#klsSearch').val());
    });

    /* ---------- Page length ---------- */
    $('#klsLength').on('change', function() {
        if (table) table.page.len(parseInt(this.value, 10)).draw();
    });

    /* ---------- Reset ---------- */
    $('#klsReset').on('click', function() {
        $('#klsSearch').val('');
        $('#klsJenjang').val('');
        $('#klsLength').val('10');
        if (table) {
            table.search('').column(2).search('').page.len(10).draw();
        }
        applySearch('');
        initTooltips();
    });

    /* ---------- Grid / List toggle ---------- */
    $('.kls-seg button').on('click', function() {
        $('.kls-seg button').removeClass('is-active');
        $(this).addClass('is-active');
        view = $(this).data('view');
        if (view === 'grid') {
            $list.hide();
            $grid.show();
            applySearch($('#klsSearch').val());
        } else {
            $grid.hide();
            $list.show();
            if (table) {
                table.column(2).search($('#klsJenjang').val() || '').draw();
                applySearch($('#klsSearch').val());
            }
        }
        initTooltips();
    });

    /* ---------- Skeleton reveal ---------- */
    var $skel = $('#klsSkeleton');
    if ($skel.length) {
        $grid.hide(); $list.hide();
        setTimeout(function() {
            $skel.fadeOut(200, function() {
                if (view === 'grid') $grid.fadeIn(200);
                else $list.fadeIn(200);
            });
        }, 450);
    }

    /* ---------- Animate counters & progress bars ---------- */
    function animateCounters() {
        var prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        $('[data-counter]').each(function() {
            var target = parseInt($(this).attr('data-counter'), 10) || 0;
            if (prefersReduced || target === 0) { $(this).text(target); return; }
            var el = $(this), cur = 0;
            var timer = setInterval(function() {
                cur += Math.max(1, Math.ceil(target / 24));
                if (cur >= target) { cur = target; clearInterval(timer); }
                el.text(cur);
            }, 28);
        });
        $('[data-width]').each(function() {
            var w = parseFloat($(this).attr('data-width')) || 0;
            var el = $(this);
            setTimeout(function() { el.css('width', w + '%'); }, 120);
        });
        $('.kls-classcard .kls-progress > span').each(function() {
            var w = parseFloat($(this).attr('data-fill')) || 0;
            var el = $(this);
            setTimeout(function() { el.css('width', w + '%'); }, 220);
        });
    }

    /* ---------- Tooltips ---------- */
    function initTooltips() {
        $('[data-bs-toggle="tooltip"]').each(function() {
            if (bootstrap.Tooltip.getInstance(this)) return;
            new bootstrap.Tooltip(this);
        });
    }

    /* ---------- Toasts ---------- */
    function showToast(type, title, text) {
        var stack = $('#klsToasts')[0];
        if (!stack) return;
        var toast = document.createElement('div');
        toast.className = 'kls-toast ' + (type === 'success' ? 'ok' : 'err');
        toast.innerHTML = '<span class="kls-toast-ico"><i class="bi ' + (type === 'success' ? 'bi-check2-circle' : 'bi-exclamation-octagon') + '"></i></span><div><div class="kls-toast-t">' + title + '</div><div class="kls-toast-s">' + text + '</div></div>';
        stack.appendChild(toast);
        requestAnimationFrame(function() { toast.classList.add('is-show'); });
        setTimeout(function() {
            toast.classList.remove('is-show');
            setTimeout(function() { toast.remove(); }, 260);
        }, 3400);
    }

    /* ---------- Add form: inline validation + loading + success ---------- */
    $('#klsFormTambah').on('submit', function(e) {
        var form = this;
        var ok = true;
        $('.kls-field-msg', form).each(function() { $(this).html(''); $(this).removeClass('ok err'); });
        $(form).find('.form-control, .form-select').removeClass('is-invalid');

        var nama = $('#addNama').val().trim();
        var jenjang = $('#addJenjang').val();
        var tingkat = $('#addTingkat').val();

        if (!nama) { markErr($('#addNama'), 'Nama kelas wajib diisi.'); ok = false; }
        else if (nama.length > 10) { markErr($('#addNama'), 'Maksimal 10 karakter.'); ok = false; }
        if (!jenjang) { markErr($('#addJenjang'), 'Pilih jenjang.'); ok = false; }
        if (!tingkat) { markErr($('#addTingkat'), 'Tingkat wajib diisi.'); ok = false; }

        if (!ok) { e.preventDefault(); return; }

        /* loading state */
        var btn = $('#klsBtnAdd');
        btn.prop('disabled', true).html('<span class="kls-spinner"></span> Menyimpan...');

        /* success animation then submit */
        e.preventDefault();
        $('#klsAddFields').fadeOut(150);
        $('#klsAddSuccess').fadeIn(200);
        setTimeout(function() { form.submit(); }, 750);
    });
    function markErr($el, msg) {
        $el.addClass('is-invalid');
        $el.closest('.form-floating').find('.kls-field-msg').addClass('err').html('<i class="bi bi-exclamation-circle"></i> ' + msg);
    }

    /* ---------- Edit form: live preview + highlight + reset ---------- */
    $('[data-kls-save]').each(function() {
        var id = $(this).attr('data-kls-save');
        var form = $('#klsFormEdit' + id);
        var fields = form.find('[data-edit-field]');
        var panel = $('#klsEditPreview' + id);

        function refresh() {
            var nama = form.find('[name=nama_kelas]').val().trim();
            var jenjangId = form.find('[name=jenjang_id]').val();
            var tingkat = form.find('[name=tingkat]').val();
            var jenjangKode = form.find('[name=jenjang_id] option:selected').text().split('—')[0].trim();
            var oNama = form.find('.kls-form-grid').data('original-nama');
            var oJenjang = String(form.find('.kls-form-grid').data('original-jenjang'));
            var oTingkat = String(form.find('.kls-form-grid').data('original-tingkat'));
            $('#prevNama' + id).text(nama || oNama);
            $('#prevJenjang' + id).text(jenjangKode || '-');
            $('#prevTingkat' + id).text(tingkat || oTingkat);
            var changed = (nama !== oNama) || (String(jenjangId) !== oJenjang) || (String(tingkat) !== oTingkat);
            panel.toggle(changed);
        }
        fields.on('input change keyup', refresh);

        form.on('submit', function(e) {
            var ok = true;
            $(form).find('[data-edit-field]').removeClass('is-invalid');
            if (!form.find('[name=nama_kelas]').val().trim()) { form.find('[name=nama_kelas]').addClass('is-invalid'); ok = false; }
            if (!form.find('[name=tingkat]').val()) { form.find('[name=tingkat]').addClass('is-invalid'); ok = false; }
            if (!ok) { e.preventDefault(); return; }
            var btn = $(this).find('[data-kls-save]');
            btn.prop('disabled', true).html('<span class="kls-spinner"></span> Menyimpan...');
        });

        form.on('reset-form', function() {
            form.find('[name=nama_kelas]').val(form.find('.kls-form-grid').data('original-nama'));
            form.find('[name=jenjang_id]').val(form.find('.kls-form-grid').data('original-jenjang'));
            form.find('[name=tingkat]').val(form.find('.kls-form-grid').data('original-tingkat'));
            refresh();
        });
    });
    $('[data-kls-reset]').on('click', function() {
        $('#klsFormEdit' + $(this).attr('data-kls-reset')).trigger('reset-form');
    });

    /* ---------- Open edit modal: re-show preview ---------- */
    $('[data-bs-target^="#klsModalEdit"]').on('click', function() {
        var id = $(this).attr('data-bs-target').replace('#klsModalEdit', '');
        $('#klsFormEdit' + id).trigger('reset-form');
    });

    /* ---------- Flash toasts ---------- */
    @if(session('success'))
        showToast('success', 'Aksi berhasil', @json(session('success')));
    @endif
    @if(session('error'))
        showToast('error', 'Perlu perhatian', @json(session('error')));
    @endif

    /* ---------- Reopen modal after validation error ---------- */
    @if($errors->any())
        var errOldId = @json(old('id'));
        var target = errOldId ? '#klsModalEdit' + errOldId : '#klsModalTambah';
        if ($(target).length) new bootstrap.Modal($(target)[0]).show();
    @endif

    initTooltips();
    animateCounters();
});
</script>
@endpush
