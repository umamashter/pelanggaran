@extends('layouts.main')
@section('title', 'Detail Peserta Lomba')
@section('content')
@include('component.admin.lomba-workspace')

<style>
    .page-title-content { display: none !important; }
    .lw-detail-wrap { max-width: 960px; }

    .lw-pipe { display: grid; grid-template-columns: 1fr 40px 1fr 40px 1fr; gap: 0; align-items: stretch; }
    .lw-pipe .arr { display: flex; align-items: center; justify-content: center; color: var(--lw-border); font-size: 18px; }
    .lw-pipe .arr.done { color: var(--lw-green); }
    .lw-stage { border-radius: 14px; border: 1px solid var(--lw-border); background: var(--lw-card); padding: 14px; text-align: center; transition: all .2s ease; }
    .lw-stage .ic { width: 40px; height: 40px; border-radius: 12px; margin: 0 auto 8px; display: inline-flex; align-items: center; justify-content: center; font-size: 17px; background: var(--lw-bg); color: var(--lw-text-3); }
    .lw-stage.done { border-color: var(--lw-green-border); background: var(--lw-green-soft); }
    .lw-stage.done .ic { background: var(--lw-green); color: #fff; }
    .lw-stage .t { font-size: 12.5px; font-weight: 800; color: var(--lw-text); }
    .lw-stage .d { font-size: 11px; color: var(--lw-text-3); margin-top: 3px; }
    .lw-stage .d b { color: var(--lw-text); font-size: 14px; font-variant-numeric: tabular-nums; }

    .lw-pen-item { display: flex; align-items: center; gap: 12px; padding: 12px 14px; border-radius: 12px; border: 1px solid var(--lw-border); background: var(--lw-bg); margin-bottom: 8px; }
    .lw-pen-item .score { flex-shrink: 0; width: 44px; height: 44px; border-radius: 12px; background: var(--lw-grad); color: #fff; display: inline-flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px; font-variant-numeric: tabular-nums; }
    .lw-pen-item .info { flex: 1; min-width: 0; }
    .lw-pen-item .info .nm { font-size: 12.5px; font-weight: 700; color: var(--lw-text); }
    .lw-pen-item .info .meta { font-size: 11px; color: var(--lw-text-3); }
    .lw-pen-item .cat { font-size: 11px; color: var(--lw-text-2); font-style: italic; }

    .lw-member-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(230px, 1fr)); gap: 8px; }
    .lw-member { display: flex; align-items: center; gap: 10px; padding: 9px 11px; border-radius: 11px; border: 1px solid var(--lw-border); background: var(--lw-bg); }
    .lw-member .nm { font-size: 12.5px; font-weight: 700; color: var(--lw-text); }
    .lw-member .id { font-size: 10.5px; color: var(--lw-text-3); }

    .lw-empty-mini { text-align: center; padding: 20px 12px; color: var(--lw-text-3); font-size: 12px; }

    @media (max-width: 767.98px) {
        .lw-pipe { grid-template-columns: 1fr; gap: 8px; }
        .lw-pipe .arr { transform: rotate(90deg); }
    }
</style>

<div class="lw-mod jd-page-pl-show">

@php
    $isIndividu = $pesertaLomba->isIndividu();
    $student = $pesertaLomba->student;
    $kelompok = $pesertaLomba->kelompokLomba;
    $statusMeta = [
        'Terdaftar'      => ['cls' => 'lw-chip--navy',  'ic' => 'bi-person-check-fill'],
        'Hadir'          => ['cls' => 'lw-chip--green', 'ic' => 'bi-check-circle-fill'],
        'Tidak Hadir'    => ['cls' => 'lw-chip--red',   'ic' => 'bi-x-circle-fill'],
        'Diskualifikasi' => ['cls' => 'lw-chip--amber', 'ic' => 'bi-slash-circle-fill'],
    ];
    $statusSm = $statusMeta[$pesertaLomba->status] ?? ['cls' => 'lw-chip--slate', 'ic' => 'bi-circle-fill'];
    $userName = $isIndividu ? ($student->user->name ?? $student->nama ?? '-') : ($kelompok->nama_kelompok ?? '-');
    $subtitle = $isIndividu ? ('NISN ' . ($student->nisn ?? '-')) : ($kelompok->kode_kelompok ?? '-');
    $penilaian = $pesertaLomba->penilaian;
    $hasil = $pesertaLomba->hasil;
    $penIds = $penilaian->pluck('juri_lomba_id')->filter();
    $aspekIds = $penilaian->pluck('aspek_penilaian_id')->filter();
    $juriMap = $penIds->isNotEmpty()
        ? \App\Models\JuriLomba::withoutGlobalScope(\App\Models\Scopes\HaflahScope::class)
            ->whereIn('id', $penIds)->with('guru')->get()->keyBy('id') : collect();
    $aspekMap = $aspekIds->isNotEmpty()
        ? \App\Models\AspekPenilaian::withoutGlobalScope(\App\Models\Scopes\HaflahScope::class)
            ->whereIn('id', $aspekIds)->get()->keyBy('id') : collect();
    $tgl = optional($pesertaLomba->lomba->sesiLomba)->tanggal ? \Carbon\Carbon::parse($pesertaLomba->lomba->sesiLomba->tanggal)->translatedFormat('d F Y') : '-';
    $sesiNama = optional($pesertaLomba->lomba->sesiLomba)->nama ?? '-';
@endphp

<div class="lw-detail-wrap">
    <div class="lw-breadcrumb" style="margin-bottom:16px;">
        <a href="{{ route('peserta-lomba.index') }}">Peserta Lomba</a> <i class="bi bi-chevron-right"></i> <span>Detail</span>
    </div>

    <div class="lw-detail-hero">
        <div class="lw-detail-hero-grid">
            <div class="d-flex align-items-center gap-3" style="min-width:0;">
                <span class="lw-detail-avatar">{{ strtoupper(mb_substr($userName, 0, 1)) }}</span>
                <div style="min-width:0;">
                    <h1 class="lw-detail-title">{{ $userName }}</h1>
                    <div class="lw-detail-sub">
                        <i class="bi {{ $isIndividu ? 'bi-person-vcard' : 'bi-people-fill' }}"></i>{{ $subtitle }}
                        &middot;
                        <i class="bi bi-trophy-fill"></i>{{ $pesertaLomba->lomba->nama ?? '-' }}
                    </div>
                    <div class="lw-detail-meta">
                        <span class="lw-hero-badge"><i class="bi {{ $statusSm['ic'] }}"></i>{{ $pesertaLomba->status }}</span>
                        <span class="lw-hero-badge"><i class="bi {{ $isIndividu ? 'bi-person-fill' : 'bi-people-fill' }}"></i>{{ $isIndividu ? 'Individu' : 'Tim' }}</span>
                        <span class="lw-hero-badge"><i class="bi bi-hash"></i>No. Urut {{ $pesertaLomba->nomor_urut }}</span>
                    </div>
                </div>
            </div>
            <div class="lw-detail-meta">
                <a href="{{ route('peserta-lomba.index') }}" class="lw-btn lw-btn--light"><i class="bi bi-arrow-left"></i> Kembali</a>
                <a href="{{ route('peserta-lomba.edit', $pesertaLomba->id) }}" class="lw-btn lw-btn--accent"><i class="bi bi-pencil"></i> Edit</a>
            </div>
        </div>
    </div>

    {{-- INFORMASI PESERTA --}}
    <div class="lw-card lw-card-pad" style="margin-bottom:18px;">
        <div class="lw-form-section"><i class="bi bi-info-circle-fill"></i> Informasi Peserta</div>
        <div class="lw-info-grid">
            <div class="lw-info-cell">
                <div class="lbl"><i class="bi bi-trophy-fill"></i>Lomba</div>
                <div class="val">{{ $pesertaLomba->lomba->nama ?? '-' }}</div>
                <div class="lw-help-text" style="margin-top:3px;">{{ $isIndividu ? 'Kategori individu' : 'Kategori tim' }}</div>
            </div>
            <div class="lw-info-cell">
                <div class="lbl"><i class="bi bi-calendar-event"></i>Sesi</div>
                <div class="val">{{ $sesiNama }}</div>
                <div class="lw-help-text" style="margin-top:3px;">{{ $tgl }}</div>
            </div>
            <div class="lw-info-cell">
                <div class="lbl"><i class="bi bi-hash"></i>Nomor Urut</div>
                <div class="val">{{ $pesertaLomba->nomor_urut }}</div>
                <div class="lw-help-text" style="margin-top:3px;">Urutan penampilan</div>
            </div>
            <div class="lw-info-cell">
                <div class="lbl"><i class="bi bi-flag-fill"></i>Status</div>
                <div class="val"><span class="lw-chip {{ $statusSm['cls'] }}"><i class="bi {{ $statusSm['ic'] }}"></i>{{ $pesertaLomba->status }}</span></div>
            </div>
            @if($isIndividu && $student)
                <div class="lw-info-cell">
                    <div class="lbl"><i class="bi bi-person-vcard-fill"></i>NISN</div>
                    <div class="val">{{ $student->nisn ?? '-' }}</div>
                </div>
                <div class="lw-info-cell">
                    <div class="lbl"><i class="bi bi-mortarboard-fill"></i>Kelas</div>
                    <div class="val">{{ $student->kelasAktif->kelas->nama_kelas ?? '-' }}</div>
                    <div class="lw-help-text" style="margin-top:3px;">{{ $student->kelasAktif->kelas->jenjang->nama_jenjang ?? '-' }}</div>
                </div>
            @endif
            @if(!$isIndividu && $kelompok)
                <div class="lw-info-cell">
                    <div class="lbl"><i class="bi bi-hash"></i>Kode Kelompok</div>
                    <div class="val">{{ $kelompok->kode_kelompok ?? '-' }}</div>
                </div>
                <div class="lw-info-cell">
                    <div class="lbl"><i class="bi bi-people-fill"></i>Anggota</div>
                    <div class="val">{{ $kelompok->anggota->count() }} siswa</div>
                    <div class="lw-help-text" style="margin-top:3px;">Anggota terdaftar</div>
                </div>
            @endif
        </div>
    </div>

    @if(!$isIndividu && $kelompok && $kelompok->anggota->isNotEmpty())
        <div class="lw-card lw-card-pad" style="margin-bottom:18px;">
            <div class="lw-form-section"><i class="bi bi-people-fill"></i> Anggota Kelompok ({{ $kelompok->anggota->count() }})</div>
            <div class="lw-member-grid">
                @foreach($kelompok->anggota as $ang)
                    @php $mName = $ang->student->user->name ?? $ang->student->nama ?? '-'; @endphp
                    <div class="lw-member">
                        <span class="lw-avatar lw-avatar--sm" style="background:{{ lw_ava_color($mName) }};">{{ strtoupper(mb_substr($mName, 0, 1)) }}</span>
                        <div><div class="nm">{{ $mName }}</div><div class="id">NISN {{ $ang->student->nisn ?? '-' }}</div></div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- RELASI : PESERTA → PENILAIAN → HASIL --}}
    <div class="lw-card lw-card-pad" style="margin-bottom:18px;">
        <div class="lw-form-section"><i class="bi bi-diagram-3"></i> Alur Penilaian</div>
        <div class="lw-pipe">
            <div class="lw-stage done">
                <span class="ic"><i class="bi bi-person-check-fill"></i></span>
                <div class="t">Peserta</div>
                <div class="d"><b>{{ $pesertaLomba->nomor_urut }}</b> &middot; {{ $pesertaLomba->status }}</div>
            </div>
            <div class="arr done"><i class="bi bi-arrow-right"></i></div>
            <div class="lw-stage {{ $penilaian->isNotEmpty() ? 'done' : '' }}">
                <span class="ic"><i class="bi bi-star-fill"></i></span>
                <div class="t">Penilaian</div>
                <div class="d"><b>{{ $penilaian->count() }}</b> penilaian</div>
            </div>
            <div class="arr {{ $hasil ? 'done' : '' }}"><i class="bi bi-arrow-right"></i></div>
            <div class="lw-stage {{ $hasil ? 'done' : '' }}">
                <span class="ic"><i class="bi bi-medal-fill"></i></span>
                <div class="t">Hasil</div>
                <div class="d">
                    @if($hasil)
                        <b>{{ $hasil->peringkat ? '#' . $hasil->peringkat : '-' }}</b> &middot; {{ $hasil->juara ?: 'Selesai' }}
                    @else
                        <span style="color:var(--lw-text-3);">Belum ada</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- PENILAIAN DETAIL --}}
    <div class="lw-card lw-card-pad" style="margin-bottom:18px;">
        <div class="lw-form-section"><i class="bi bi-star-fill"></i> Rincian Penilaian</div>
        @if($penilaian->isEmpty())
            <div class="lw-empty-mini"><i class="bi bi-inbox" style="font-size:26px;display:block;margin-bottom:8px;color:var(--lw-text-3);"></i>Belum ada penilaian untuk peserta ini.</div>
        @else
            @foreach($penilaian as $pen)
                @php
                    $juri = $juriMap->get($pen->juri_lomba_id);
                    $aspek = $aspekMap->get($pen->aspek_penilaian_id);
                    $juriNama = $juri ? ($juri->guru->nama ?? 'Juri #' . $pen->juri_lomba_id) : 'Juri';
                    $aspekNama = $aspek->nama_aspek ?? 'Aspek';
                @endphp
                <div class="lw-pen-item">
                    <span class="score">{{ number_format($pen->nilai, 0, ',', '.') }}</span>
                    <div class="info">
                        <div class="nm">{{ $aspekNama }}</div>
                        <div class="meta"><i class="bi bi-person"></i> {{ $juriNama }} &middot; {{ \Carbon\Carbon::parse($pen->created_at)->translatedFormat('d M Y') }}</div>
                    </div>
                    @if($pen->catatan)<span class="cat"><i class="bi bi-chat-left-text"></i> {{ $pen->catatan }}</span>@endif
                </div>
            @endforeach
        @endif
    </div>

    {{-- HASIL --}}
    <div class="lw-card lw-card-pad" style="margin-bottom:18px;">
        <div class="lw-form-section"><i class="bi bi-medal-fill"></i> Hasil Lomba</div>
        @if($hasil)
            <div class="lw-info-grid">
                <div class="lw-info-cell">
                    <div class="lbl"><i class="bi bi-hash"></i>Peringkat</div>
                    <div class="val">#{{ $hasil->peringkat ?? '-' }}</div>
                </div>
                <div class="lw-info-cell">
                    <div class="lbl"><i class="bi bi-award"></i>Juara</div>
                    <div class="val">{{ $hasil->juara ?: '-' }}</div>
                </div>
                <div class="lw-info-cell">
                    <div class="lbl"><i class="bi bi-calculator"></i>Total Nilai</div>
                    <div class="val">{{ number_format($hasil->total_nilai, 2, ',', '.') }}</div>
                </div>
            </div>
        @else
            <div class="lw-empty-mini"><i class="bi bi-trophy" style="font-size:26px;display:block;margin-bottom:8px;color:var(--lw-text-3);"></i>Hasil belum dirilis untuk peserta ini.</div>
        @endif
    </div>

    {{-- NAVIGASI CEPAT --}}
    <div class="lw-card lw-card-pad" style="margin-bottom:18px;">
        <div class="lw-form-section"><i class="bi bi-compass-fill"></i> Navigasi Cepat</div>
        <div class="lw-qn-grid">
            <a href="{{ route('penilaian-lomba.index') }}" class="lw-qn-card lw-qn--navy" style="text-decoration:none;">
                <span class="lw-qn-ic"><i class="bi bi-star-fill"></i></span>
                <span class="lw-qn-body"><span class="lw-qn-name">Penilaian Lomba</span><span class="lw-qn-sub">Input &amp; kelola nilai</span></span>
                <i class="bi bi-chevron-right lw-qn-arrow"></i>
            </a>
            <a href="{{ route('hasil-lomba.index') }}" class="lw-qn-card lw-qn--violet" style="text-decoration:none;">
                <span class="lw-qn-ic"><i class="bi bi-medal-fill"></i></span>
                <span class="lw-qn-body"><span class="lw-qn-name">Hasil Lomba</span><span class="lw-qn-sub">Peringkat &amp; juara</span></span>
                <i class="bi bi-chevron-right lw-qn-arrow"></i>
            </a>
            <a href="{{ route('peserta-lomba.edit', $pesertaLomba->id) }}" class="lw-qn-card lw-qn--green" style="text-decoration:none;">
                <span class="lw-qn-ic"><i class="bi bi-pencil-square"></i></span>
                <span class="lw-qn-body"><span class="lw-qn-name">Edit Peserta</span><span class="lw-qn-sub">Ubah data pendaftaran</span></span>
                <i class="bi bi-chevron-right lw-qn-arrow"></i>
            </a>
        </div>
    </div>

    <div class="lw-wizard-nav">
        <a href="{{ route('peserta-lomba.index') }}" class="lw-btn"><i class="bi bi-arrow-left"></i> Kembali ke Daftar</a>
    </div>
</div>

</div>
@endsection
