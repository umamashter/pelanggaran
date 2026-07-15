<div class="col-12 mb-3">
    <h4 class="fw-bold" style="color: #0f172a;">Assalamu'alaikum, Admin!</h4>
    <p class="text-muted" style="font-size: 14px;">Hari ini {{ date('d F Y') }}</p>
</div>

<div class="col-12 mb-4">
    <div class="card header-card">
        <div class="card-body d-flex align-items-center gap-3">
            <div class="header-icon green">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div>
                <p class="text-white-50 mb-0" style="font-size:13px;">Tahun Ajaran Aktif</p>
                <h4 class="text-white fw-bold mb-0">
                    {{ $tahunAktif->tahun_ajaran }} ({{ $tahunAktif->semester ?? '-' }})
                </h4>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="glass-stat">
            <div class="gs-left">
                <div class="gs-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#138F5B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z" fill="rgba(19,143,91,0.08)"/>
                        <path d="M6 12v5c0 1.1 2.7 2 6 2s6-.9 6-2v-5"/>
                    </svg>
                </div>
                <div class="gs-chart">
                    <div class="bar" style="height:10px"></div>
                    <div class="bar" style="height:18px"></div>
                    <div class="bar" style="height:7px"></div>
                    <div class="bar" style="height:22px"></div>
                    <div class="bar" style="height:14px"></div>
                </div>
            </div>
            <div class="gs-body">
                <span class="gs-number">{{ $siswas->count() }}</span>
                <span class="gs-label">SISWA</span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="glass-stat">
            <div class="gs-left">
                <div class="gs-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#0a4f47" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="10" cy="7" r="3.5" fill="rgba(10,79,71,0.08)"/>
                        <path d="M3 21v-2a4 4 0 014-4h6a4 4 0 014 4v2"/>
                        <rect x="16" y="3" width="5" height="8" rx="2" fill="rgba(10,79,71,0.06)"/>
                        <path d="M21 6l-5 2"/>
                    </svg>
                </div>
                <div class="gs-chart">
                    <div class="bar" style="height:14px"></div>
                    <div class="bar" style="height:8px"></div>
                    <div class="bar" style="height:20px"></div>
                    <div class="bar" style="height:12px"></div>
                    <div class="bar" style="height:18px"></div>
                </div>
            </div>
            <div class="gs-body">
                <span class="gs-number">{{ $walikelas->count() }}</span>
                <span class="gs-label">GURU</span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="glass-stat">
            <div class="gs-left">
                <div class="gs-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#1aa86f" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="7" r="3" fill="rgba(26,168,111,0.08)"/>
                        <circle cx="17" cy="9" r="2.5" fill="rgba(26,168,111,0.06)"/>
                        <path d="M3 21v-2a4 4 0 014-4h4a4 4 0 014 4v2"/>
                        <path d="M16 8l2-1 3 1"/>
                    </svg>
                </div>
                <div class="gs-chart">
                    <div class="bar" style="height:5px"></div>
                    <div class="bar" style="height:16px"></div>
                    <div class="bar" style="height:22px"></div>
                    <div class="bar" style="height:10px"></div>
                    <div class="bar" style="height:13px"></div>
                </div>
            </div>
            <div class="gs-body">
                <span class="gs-number">{{ $users->count() }}</span>
                <span class="gs-label">USER</span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="glass-stat">
            <div class="gs-left">
                <div class="gs-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#0F7A6A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" fill="rgba(15,122,106,0.08)"/>
                        <line x1="9" y1="9" x2="15" y2="9" stroke-width="2"/>
                        <line x1="9" y1="13" x2="13" y2="13" stroke-width="2"/>
                        <path d="M7 17l2-2 2 2 4-4"/>
                    </svg>
                </div>
                <div class="gs-chart">
                    <div class="bar" style="height:12px"></div>
                    <div class="bar" style="height:20px"></div>
                    <div class="bar" style="height:6px"></div>
                    <div class="bar" style="height:16px"></div>
                    <div class="bar" style="height:10px"></div>
                </div>
            </div>
            <div class="gs-body">
                <span class="gs-number">{{ $peraturan->count() }}</span>
                <span class="gs-label">PELANGGARAN</span>
            </div>
        </div>
    </div>
</div>

</div>

<div class="row g-3 mb-4">
    {{-- Chart Pelanggaran --}}
    <div class="col-lg-6">
        <div class="card table-card h-100">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                <h5 class="fw-bold mb-0">Tren Pelanggaran Tahun {{ date('Y') }}</h5>
            </div>
            <div class="card-body">
                <div id="chartPelanggaran"></div>
            </div>
        </div>
    </div>
    
    {{-- Penanganan Terbaru --}}
    <div class="col-lg-6">
        <div class="card table-card h-100">
            <div class="card-header d-flex align-items-center gap-2" style="background:#f8fafc;border-bottom:1px solid #e2e8f0;padding:16px 20px;">
                <i class="fas fa-handshake" style="color:#138F5B;"></i>
                <h5 class="fw-bold mb-0" style="color:#0f172a;">Penanganan Terbaru</h5>
            </div>
            <div class="card-body p-3">
                @if ($penanganan->count())
                    @foreach ($penanganan as $msg)
                    <div class="list-group-item-custom d-flex align-items-start gap-3 p-3 mb-2" style="border:1px solid #e2e8f0;border-radius:12px;border-left:4px solid #138F5B;">
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <a href="/master-histori/{{ $msg->siswa->id }}" class="fw-bold" style="color:#0f172a;font-size:14px;">
                                    {{ $msg->siswa->nama }} — {{ $msg->siswa->kelas->nama_kelas }}
                                </a>
                                <small style="color:#94a3b8;font-size:11px;">{{ $msg->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-1" style="color:#334155;font-size:13px;">{{ $msg->pesan->tindak_lanjut }}</p>
                            @php
                                $status = $msg->status;
                                $tingkatan = $msg->pesan->tingkatan;
                            @endphp
                            @if ($tingkatan == 'Ringan' || $tingkatan == 'Sedang')
                                <span class="badge-modern {{ $status == 0 ? 'warning' : 'success' }}">
                                    {{ $status == 0 ? 'Belum Terkonfirmasi' : 'Terkonfirmasi' }}
                                </span>
                            @else
                                @if ($status == 0)
                                    <span class="badge-modern warning">Belum Terkonfirmasi</span>
                                @elseif ($status == 1)
                                    <span class="badge-modern warning">Belum Terlaksana</span>
                                @elseif ($status == 2)
                                    <span class="badge-modern success">Terkonfirmasi</span>
                                @endif
                            @endif
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-4" style="color:#94a3b8;">
                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                        Tidak ada penanganan
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    var options = {
        series: [{
            name: 'Pelanggaran',
            data: {{ json_encode(array_values($chartData)) }}
        }],
        chart: {
            type: 'bar',
            height: 350
        },
        plotOptions: {
            bar: {
                borderRadius: 4,
                horizontal: false,
            }
        },
        dataLabels: { enabled: false },
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
        },
        colors: ['#138F5B']
    };

    var chart = new ApexCharts(document.querySelector("#chartPelanggaran"), options);
    chart.render();
</script>
@endpush

<style>
    .page-title-content { display: none !important; }
    .header-card {
        background: linear-gradient(135deg, #138F5B, #0a4f47);
        border: none;
        border-radius: 18px;
    }
    .header-card .header-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
        background: rgba(255,255,255,.15);
        color: #fff;
    }
    /* ── Glass Stat Cards (matching Haflatul Imtihan) ── */
    .glass-stat {
        position: relative;
        border-radius: 20px;
        padding: 20px 22px;
        background: linear-gradient(145deg, rgba(255,255,255,0.55) 0%, rgba(248,250,252,0.30) 100%);
        border: 1px solid rgba(255,255,255,0.70);
        backdrop-filter: blur(18px) saturate(160%);
        -webkit-backdrop-filter: blur(18px) saturate(160%);
        box-shadow:
            0 1px 2px rgba(0,0,0,0.03),
            0 4px 12px rgba(0,0,0,0.04),
            0 12px 32px -8px rgba(0,0,0,0.06),
            inset 0 1px 0 rgba(255,255,255,0.80);
        transition: all .4s cubic-bezier(.2,.8,.2,1);
        display: flex;
        align-items: flex-start;
        gap: 16px;
        overflow: hidden;
    }
    .glass-stat::before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 20px;
        padding: 1px;
        background: linear-gradient(160deg, rgba(255,255,255,0.95), rgba(255,255,255,0.20) 40%, transparent 70%);
        -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        pointer-events: none;
    }
    .glass-stat::after {
        content: '';
        position: absolute;
        top: -60%;
        right: -20%;
        width: 180px;
        height: 180px;
        border-radius: 50%;
        pointer-events: none;
        z-index: 0;
        opacity: 0.6;
    }
    .glass-stat:hover {
        transform: translateY(-4px);
        box-shadow:
            0 1px 2px rgba(0,0,0,0.03),
            0 8px 24px rgba(0,0,0,0.06),
            0 20px 48px -12px rgba(0,0,0,0.10),
            inset 0 1px 0 rgba(255,255,255,0.90);
        border-color: rgba(255,255,255,0.85);
    }

    .gs-left {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
        z-index: 1;
    }
    .gs-icon {
        width: 48px;
        height: 48px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        background: linear-gradient(145deg, rgba(255,255,255,0.90), rgba(255,255,255,0.40));
        box-shadow:
            0 2px 8px rgba(0,0,0,0.06),
            0 8px 24px -4px rgba(0,0,0,0.08),
            inset 0 1px 0 rgba(255,255,255,0.95),
            inset 0 -1px 0 rgba(0,0,0,0.04);
    }
    .gs-icon svg {
        width: 24px;
        height: 24px;
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.12));
    }

    .gs-chart {
        display: flex;
        align-items: flex-end;
        gap: 3px;
        height: 22px;
        z-index: 1;
    }
    .gs-chart .bar {
        width: 4px;
        border-radius: 3px 3px 1px 1px;
        transition: height .6s cubic-bezier(.2,.8,.2,1);
        position: relative;
    }
    .gs-chart .bar::after {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: inherit;
        background: inherit;
        filter: blur(4px);
        opacity: 0.5;
        transform: scaleY(1.4) scaleX(1.6);
        z-index: -1;
    }

    .gs-body {
        display: flex;
        flex-direction: column;
        flex: 1;
        z-index: 1;
    }
    .gs-body .gs-number {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1;
        letter-spacing: -.5px;
    }
    .gs-body .gs-label {
        font-size: 10.5px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin-top: 4px;
    }

    /* Per-card accent colors */
    .glass-stat:nth-child(1) .gs-label { color: #138F5B; }
    .glass-stat:nth-child(1)::after { background: radial-gradient(circle, rgba(19,143,91,0.08) 0%, transparent 70%); }
    .glass-stat:nth-child(1) .gs-chart .bar { background: linear-gradient(to top, #138F5B, #6ee7b7); }

    .glass-stat:nth-child(2) .gs-label { color: #0a4f47; }
    .glass-stat:nth-child(2)::after { background: radial-gradient(circle, rgba(10,79,71,0.08) 0%, transparent 70%); }
    .glass-stat:nth-child(2) .gs-chart .bar { background: linear-gradient(to top, #0a4f47, #5eead4); }

    .glass-stat:nth-child(3) .gs-label { color: #1aa86f; }
    .glass-stat:nth-child(3)::after { background: radial-gradient(circle, rgba(26,168,111,0.08) 0%, transparent 70%); }
    .glass-stat:nth-child(3) .gs-chart .bar { background: linear-gradient(to top, #1aa86f, #86efac); }

    .glass-stat:nth-child(4) .gs-label { color: #0F7A6A; }
    .glass-stat:nth-child(4)::after { background: radial-gradient(circle, rgba(15,122,106,0.08) 0%, transparent 70%); }
    .glass-stat:nth-child(4) .gs-chart .bar { background: linear-gradient(to top, #0F7A6A, #2dd4bf); }

    html.dark-mode .glass-stat {
        background: linear-gradient(145deg, rgba(255,255,255,0.08) 0%, rgba(255,255,255,0.03) 100%);
        border-color: rgba(255,255,255,0.10);
        box-shadow:
            0 1px 0 rgba(255,255,255,0.04) inset,
            0 4px 16px rgba(0,0,0,0.20),
            0 12px 32px -8px rgba(0,0,0,0.30),
            inset 0 1px 0 rgba(255,255,255,0.06);
    }
    html.dark-mode .glass-stat::before {
        background: linear-gradient(160deg, rgba(255,255,255,0.12), rgba(255,255,255,0.03) 40%, transparent 70%);
    }
    html.dark-mode .glass-stat:hover {
        border-color: rgba(255,255,255,0.18);
        box-shadow:
            0 1px 0 rgba(255,255,255,0.06) inset,
            0 8px 24px rgba(0,0,0,0.25),
            0 24px 56px -12px rgba(0,0,0,0.35),
            inset 0 1px 0 rgba(255,255,255,0.08);
    }
    html.dark-mode .gs-icon {
        background: linear-gradient(145deg, rgba(255,255,255,0.12), rgba(255,255,255,0.04)) !important;
        box-shadow:
            0 2px 8px rgba(0,0,0,0.20),
            0 8px 24px -4px rgba(0,0,0,0.15),
            inset 0 1px 0 rgba(255,255,255,0.10) !important;
    }
    html.dark-mode .gs-icon svg { filter: drop-shadow(0 2px 6px rgba(0,0,0,0.30)); }
    html.dark-mode .gs-body .gs-number { color: #f1f5f9; }
    html.dark-mode .gs-body .gs-label { opacity: 0.85; }
    .badge-modern {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
    }
    .badge-modern.success { background: #d1f5e3; color: #138F5B; }
    .badge-modern.warning { background: #fef3c7; color: #d97706; }
    .list-group-item-custom {
        transition: all .2s ease;
    }
    .list-group-item-custom:hover {
        background: #f8fafc;
        transform: translateX(3px);
    }
</style>
