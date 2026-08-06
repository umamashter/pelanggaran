@extends('layouts.main')
@section('title','Absensi Siswa')
@section('content')
@include('component.admin.absensi-module')
<style>
    .page-title-content { display: none !important; }
    .abs-mod .abm-kpi.total { --ab-kpi-glow: rgba(37,99,235,.08); --ab-kpi-wm: #2563eb; }
    .abs-mod .abm-kpi.done { --ab-kpi-glow: rgba(22,163,74,.08); --ab-kpi-wm: #16a34a; }
    .abs-mod .abm-kpi.pending { --ab-kpi-glow: rgba(217,119,6,.08); --ab-kpi-wm: #d97706; }
    .abs-mod .abm-kpi.coverage { --ab-kpi-glow: rgba(124,58,237,.08); --ab-kpi-wm: #7c3aed; }
    .abm-kelas-progress { height: 4px; border-radius: 4px; overflow: hidden; background: var(--ab-border); }
    .abm-kelas-progress > span { display: block; height: 100%; width: 0; border-radius: 4px; transition: width 1.2s cubic-bezier(.22,1,.36,1); }
    .abm-kelas-progress.ok > span { background: linear-gradient(90deg, #16a34a, #4ade80); }
    .abm-kelas-progress.waiting > span { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
    .abm-seg { display:inline-flex; align-items:center; gap:6px; padding:5px; border-radius:999px; background:var(--ab-border-soft); border:1px solid var(--ab-border); }
    .abm-seg button { border:0; background:transparent; color:var(--ab-text-3); border-radius:999px; padding:8px 14px; font-size:12px; font-weight:700; display:inline-flex; align-items:center; gap:8px; transition:all .2s ease; }
    .abm-seg button.is-active { background:#fff; color:var(--ab-text); box-shadow:0 8px 20px -14px rgba(15,23,42,.45); }
    html.dark-mode .abm-seg button.is-active { background:rgba(255,255,255,.08); color:var(--ab-text); }
    .abm-listcard { display:none; }
    .abm-table-wrap { overflow:hidden; border:1px solid var(--ab-border); border-radius:18px; background:var(--ab-card); box-shadow:var(--ab-shadow); }
    .abm-table-scroll { overflow-x:auto; }
    .abm-table { width:100%; border-collapse:separate; border-spacing:0; min-width:980px; }
    .abm-table thead th { font-size:11px; text-transform:uppercase; letter-spacing:.4px; font-weight:800; color:var(--ab-text-3); background:var(--ab-border-soft); padding:13px 14px; border-bottom:1px solid var(--ab-border); }
    .abm-table tbody td { padding:14px; border-bottom:1px solid var(--ab-border); color:var(--ab-text); font-size:13px; vertical-align:middle; }
    .abm-table tbody tr:last-child td { border-bottom:0; }
    .abm-table .num { width:56px; color:var(--ab-text-3); font-variant-numeric:tabular-nums; }
</style>

<div class="abs-mod master-siswa-page">
    {{-- ===== HERO ===== --}}
    <div class="abm-hero">
        <div class="abm-hero-grid"></div>
        <div class="abm-hero-row">
            <div class="abm-hero-left">
                <div class="d-flex align-items-center gap-3">
                    <div class="abm-hero-icon"><i class="fas fa-clipboard-check"></i></div>
                    <div>
                        <h3>Absensi Siswa</h3>
                        <p class="abm-hero-sub">Kelola kehadiran siswa harian per kelas.</p>
                    </div>
                </div>
                <div class="abm-hero-badges">
                    <span class="abm-hero-badge"><i class="fas fa-calendar-day"></i> {{ now()->translatedFormat('l, d F Y') }}</span>
                    <span class="abm-hero-badge"><i class="fas fa-graduation-cap"></i> {{ $tahunAktif->tahun_ajaran }}</span>
                    @if(isset($tahunAktif->semesterAktif))
                    <span class="abm-hero-badge"><i class="fas fa-bookmark"></i> {{ $tahunAktif->semesterAktif->nama ?? '-' }}</span>
                    @endif
                    @if($isJumat)
                    <span class="abm-hero-badge"><i class="fas fa-moon"></i> Hari Libur</span>
                    @endif
                </div>
            </div>
            <div class="abm-hero-right">
                <div class="abm-hero-clock">
                    <i class="fas fa-clock" style="font-size:22px;opacity:.9;"></i>
                    <div>
                        <div class="abm-clock-time" id="liveClock">--:--:--</div>
                        <div class="abm-clock-date" id="liveClockDate">{{ now()->translatedFormat('l, d F Y') }}</div>
                    </div>
                </div>
                <div class="abm-hero-actions">
                    <a href="{{ route('absensi.rekap') }}" class="abm-btn abm-btn--ghost"><i class="fas fa-file-alt"></i> Rekap</a>
                    <a href="{{ route('absensi.riwayat') }}" class="abm-btn abm-btn--ghost"><i class="fas fa-calendar-check"></i> Riwayat</a>
                    <a href="{{ route('absensi.import') }}" class="abm-btn abm-btn--ghost"><i class="fas fa-camera"></i> Import Foto</a>
                    <a href="{{ route('absensi.create') }}" class="abm-btn abm-btn--light"><i class="fas fa-plus"></i> Input Absensi</a>
                </div>
            </div>
        </div>
    </div>

    @if($isJumat)
    <div class="abm-alert abm-alert--warn" style="align-items:center;">
        <i class="fas fa-mug-hot"></i>
        <div>
            <strong>Jumat — Hari Libur</strong>
            <span style="opacity:.9;"> Hari ini adalah hari libur tetap madrasah. Tidak ada kegiatan belajar mengajar dan tidak ada absensi siswa.</span>
        </div>
    </div>
    @endif

    @php
        $totalKelas = $kelasList->count();
        $sudahAbsen = count($absensiHariIni);
        $belumAbsen = $totalKelas - $sudahAbsen;
        $persenAbsen = $totalKelas > 0 ? round(($sudahAbsen / $totalKelas) * 100) : 0;
    @endphp

    {{-- ===== KPI ===== --}}
    <div class="abm-kpi-grid">
        <div class="abm-kpi total">
            <i class="fas fa-layer-group abm-kpi-watermark"></i>
            <div class="abm-kpi-icon blue"><i class="fas fa-layer-group"></i></div>
            <div class="abm-kpi-info">
                <div class="abm-kpi-num" data-count="{{ $totalKelas }}">0</div>
                <div class="abm-kpi-label">Total Kelas</div>
            </div>
        </div>
        <div class="abm-kpi done">
            <i class="fas fa-check-circle abm-kpi-watermark"></i>
            <div class="abm-kpi-icon green"><i class="fas fa-check-circle"></i></div>
            <div class="abm-kpi-info">
                <div class="abm-kpi-num" data-count="{{ $sudahAbsen }}">0</div>
                <div class="abm-kpi-label">Sudah Diabsen</div>
            </div>
        </div>
        <div class="abm-kpi pending">
            <i class="fas fa-clock abm-kpi-watermark"></i>
            <div class="abm-kpi-icon amber"><i class="fas fa-clock"></i></div>
            <div class="abm-kpi-info">
                <div class="abm-kpi-num" data-count="{{ $belumAbsen }}">0</div>
                <div class="abm-kpi-label">Belum Diabsen</div>
            </div>
        </div>
        <div class="abm-kpi coverage">
            <i class="fas fa-percent abm-kpi-watermark"></i>
            <div class="abm-kpi-icon violet"><i class="fas fa-chart-line"></i></div>
            <div class="abm-kpi-info">
                <div class="abm-kpi-num" data-count="{{ $persenAbsen }}">0<span style="font-size:14px;margin-left:1px;">%</span></div>
                <div class="abm-progress mt-2"><span data-width="{{ $persenAbsen }}"></span></div>
                <div class="abm-kpi-label" style="margin-top:7px;">Cakupan Absensi</div>
            </div>
        </div>
    </div>

    {{-- ===== KELAS CARDS ===== --}}
    <div class="abm-card" style="padding:18px 20px 22px;margin-bottom:10px;">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div class="abm-section-title">
                <i class="fas fa-school"></i> Daftar Kelas
                <span class="abm-chip abm-chip--blue">{{ $totalKelas }}</span>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <div class="abm-seg" role="group" aria-label="Mode tampilan absensi siswa">
                    <button type="button" class="is-active" data-view="grid"><i class="fas fa-th-large"></i> Grid</button>
                    <button type="button" data-view="list"><i class="fas fa-list"></i> List</button>
                </div>
                <div>
                    @if($belumAbsen > 0)
                    <span class="abm-chip abm-chip--warn"><i class="fas fa-exclamation-triangle"></i> {{ $belumAbsen }} kelas belum diabsen</span>
                    @else
                    <span class="abm-chip abm-chip--ok"><i class="fas fa-check-circle"></i> Semua sudah diabsen</span>
                    @endif
                </div>
            </div>
        </div>

        @if($kelasList->isEmpty())
        <div class="abm-empty">
            <i class="fas fa-inbox"></i>
            <div class="abm-empty-title">Belum ada kelas</div>
            <div class="abm-empty-sub">Tidak ada kelas terdaftar pada tahun ajaran aktif.</div>
        </div>
        @else
        <div class="abm-kelas-grid" id="abmGrid">
            @foreach($kelasList as $kelas)
            @php
                $siswaCount = $kelas->siswaAktif()->where('tahun_ajaran_id', $tahunAktif->id)->count();
                $sudahAbsenKelas = in_array($kelas->id, $absensiHariIni);
                $absensiId = $absensiMap[$kelas->id] ?? null;
                $avatarClass = 'c' . ($loop->index % 6);
                $waliNama = $kelas->waliKelas?->guru?->nama ?? '-';
            @endphp
            <div class="abm-kelas {{ $sudahAbsenKelas ? 'is-done' : '' }}">
                <div class="abm-kelas-top">
                    <div class="abm-kelas-avatar {{ $avatarClass }}">{{ substr($kelas->nama_kelas, 0, 2) }}</div>
                    <div style="flex:1;min-width:0;">
                        <div class="d-flex justify-content-between align-items-start gap-2">
                            <div class="abm-kelas-name">{{ $kelas->nama_kelas }}</div>
                            @if($isJumat)
                                <span class="abm-chip abm-chip--muted"><i class="fas fa-moon"></i> Libur</span>
                            @elseif($sudahAbsenKelas)
                                <span class="abm-chip abm-chip--ok"><i class="fas fa-check-circle"></i> Selesai</span>
                            @else
                                <span class="abm-chip abm-chip--warn"><i class="fas fa-hourglass-half abm-pulse"></i> Belum</span>
                            @endif
                        </div>
                        <div class="abm-kelas-meta">{{ $kelas->jenjang->nama ?? '-' }} · Kelas {{ $kelas->tingkat ?? '-' }}</div>
                    </div>
                </div>

                <div class="abm-kelas-body">
                    <div class="abm-kelas-stat">
                        <div class="v abm-num">{{ $siswaCount }}</div>
                        <div class="l">Siswa</div>
                    </div>
                    <div class="abm-kelas-stat">
                        <div class="v abm-num">{{ $sudahAbsenKelas ? '100' : '0' }}%</div>
                        <div class="l">Selesai Hari Ini</div>
                    </div>
                </div>

                <div class="abm-kelas-wali" title="Wali Kelas">
                    <i class="fas fa-user-tie"></i> <span>Wali: <strong>{{ $waliNama }}</strong></span>
                </div>

                <div class="abm-kelas-progress {{ $sudahAbsenKelas ? 'ok' : 'waiting' }}">
                    <span data-width="{{ $sudahAbsenKelas ? 100 : 0 }}"></span>
                </div>

                <div class="abm-kelas-actions">
                    @if($sudahAbsenKelas)
                        <a href="{{ route('absensi.edit', $absensiId) }}" class="abm-quick abm-quick--edit" title="Edit Absensi"><i class="fas fa-edit"></i> Edit</a>
                    @elseif($isJumat)
                        <span class="abm-quick is-disabled" title="Hari Jumat — Libur Madrasah"><i class="fas fa-moon"></i> Input</span>
                    @else
                        <a href="{{ route('absensi.create', ['kelas_id' => $kelas->id, 'tanggal' => now()->toDateString()]) }}" class="abm-quick abm-quick--input" title="Input Absensi"><i class="fas fa-clipboard-list"></i> Input</a>
                    @endif
                    <a href="{{ route('absensi.riwayat', ['kelas_id' => $kelas->id]) }}" class="abm-quick abm-quick--hist" title="Riwayat"><i class="fas fa-history"></i> Riwayat</a>
                    <a href="{{ route('absensi.cetak-buku-pdf', ['tahun_ajaran_id' => $tahunAktif->id, 'kelas_id' => $kelas->id, 'bulan' => now()->format('Y-m')]) }}" target="_blank" class="abm-quick abm-quick--print" title="Cetak Buku Absensi"><i class="fas fa-print"></i> Cetak</a>
                </div>
            </div>
            @endforeach
        </div>

        <div class="abm-listcard" id="abmList">
            <div class="abm-table-wrap">
                <div class="abm-table-scroll">
                    <table class="abm-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kelas</th>
                                <th>Jenjang</th>
                                <th>Wali Kelas</th>
                                <th>Siswa</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kelasList as $kelas)
                            @php
                                $siswaCount = $kelas->siswaAktif()->where('tahun_ajaran_id', $tahunAktif->id)->count();
                                $sudahAbsenKelas = in_array($kelas->id, $absensiHariIni);
                                $absensiId = $absensiMap[$kelas->id] ?? null;
                                $waliNama = $kelas->waliKelas?->guru?->nama ?? '-';
                            @endphp
                            <tr>
                                <td class="num">{{ $loop->iteration }}</td>
                                <td><strong>{{ $kelas->nama_kelas }}</strong><div style="font-size:11px;color:var(--ab-text-3);">Kelas {{ $kelas->tingkat ?? '-' }}</div></td>
                                <td>{{ $kelas->jenjang->nama ?? '-' }}</td>
                                <td>{{ $waliNama }}</td>
                                <td>{{ $siswaCount }}</td>
                                <td>
                                    @if($isJumat)
                                        <span class="abm-chip abm-chip--muted"><i class="fas fa-moon"></i> Libur</span>
                                    @elseif($sudahAbsenKelas)
                                        <span class="abm-chip abm-chip--ok"><i class="fas fa-check-circle"></i> Selesai</span>
                                    @else
                                        <span class="abm-chip abm-chip--warn"><i class="fas fa-hourglass-half"></i> Belum</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="abm-kelas-actions" style="border-top:0;padding-top:0;min-width:260px;">
                                        @if($sudahAbsenKelas)
                                            <a href="{{ route('absensi.edit', $absensiId) }}" class="abm-quick abm-quick--edit" title="Edit Absensi"><i class="fas fa-edit"></i> Edit</a>
                                        @elseif($isJumat)
                                            <span class="abm-quick is-disabled" title="Hari Jumat — Libur Madrasah"><i class="fas fa-moon"></i> Input</span>
                                        @else
                                            <a href="{{ route('absensi.create', ['kelas_id' => $kelas->id, 'tanggal' => now()->toDateString()]) }}" class="abm-quick abm-quick--input" title="Input Absensi"><i class="fas fa-clipboard-list"></i> Input</a>
                                        @endif
                                        <a href="{{ route('absensi.riwayat', ['kelas_id' => $kelas->id]) }}" class="abm-quick abm-quick--hist" title="Riwayat"><i class="fas fa-history"></i> Riwayat</a>
                                        <a href="{{ route('absensi.cetak-buku-pdf', ['tahun_ajaran_id' => $tahunAktif->id, 'kelas_id' => $kelas->id, 'bulan' => now()->format('Y-m')]) }}" target="_blank" class="abm-quick abm-quick--print" title="Cetak Buku Absensi"><i class="fas fa-print"></i> Cetak</a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    function animateCount(el, target) {
        if (prefersReduced) { el.textContent = target; return; }
        const start = performance.now();
        const duration = 900;
        const frame = function(now) {
            const p = Math.min((now - start) / duration, 1);
            const eased = 1 - Math.pow(1 - p, 3);
            el.textContent = Math.round(target * eased);
            if (p < 1) requestAnimationFrame(frame);
        };
        requestAnimationFrame(frame);
    }

    $('.abm-kpi-num[data-count]').each(function() {
        animateCount(this, parseInt($(this).attr('data-count'), 10) || 0);
    });

    $('.abm-progress > span[data-width], .abm-kelas-progress > span[data-width]').each(function() {
        const w = parseInt(this.getAttribute('data-width'), 10) || 0;
        requestAnimationFrame(function() { setTimeout(function() { this.style.width = w + '%'; }.bind(this), 200); }.bind(this));
    });

    const grid = document.getElementById('abmGrid');
    const list = document.getElementById('abmList');
    const viewButtons = Array.from(document.querySelectorAll('.abm-seg button'));
    let view = 'grid';

    viewButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            viewButtons.forEach(function(node) { node.classList.remove('is-active'); });
            btn.classList.add('is-active');
            view = btn.dataset.view || 'grid';
            if (grid) grid.style.display = view === 'grid' ? 'grid' : 'none';
            if (list) list.style.display = view === 'list' ? 'block' : 'none';
        });
    });

    const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    function updateClock() {
        const now = new Date();
        const pad = function(n) { return String(n).padStart(2, '0'); };
        const time = pad(now.getHours()) + ':' + pad(now.getMinutes()) + ':' + pad(now.getSeconds());
        const date = dayNames[now.getDay()] + ', ' + now.getDate() + ' ' + monthNames[now.getMonth()] + ' ' + now.getFullYear();
        const timeEl = document.getElementById('liveClock');
        const dateEl = document.getElementById('liveClockDate');
        if (timeEl) timeEl.textContent = time;
        if (dateEl) dateEl.textContent = date;
    }
    updateClock();
    setInterval(updateClock, 1000);
});
</script>
@endpush
