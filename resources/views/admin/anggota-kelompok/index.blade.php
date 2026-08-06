@extends('layouts.main')
@section('title', 'Anggota Kelompok')
@section('content')
@include('component.admin.lomba-workspace')

<style>
    .page-title-content { display: none !important; }

    .lw-kl-ava { width: 38px; height: 38px; border-radius: 11px; color: #fff; display: inline-flex; align-items: center; justify-content: center; font-size: 15px; flex-shrink: 0; }
    .lw-kl-name { font-weight: 700; font-size: 13px; color: var(--lw-text); line-height: 1.25; }
    .lw-kl-name a { color: inherit; text-decoration: none; }
    .lw-kl-name a:hover { color: var(--lw-primary); }
    .lw-kl-code { font-size: 10.5px; color: var(--lw-text-3); font-weight: 600; }

    .lw-count-badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; background: var(--lw-green-soft); color: var(--lw-green); white-space: nowrap; }

    .lw-act-row { display: inline-flex; gap: 6px; }
    .lw-iact { width: 32px; height: 32px; border-radius: 9px; border: 1px solid var(--lw-border); background: var(--lw-card); color: var(--lw-text-2); display: inline-flex; align-items: center; justify-content: center; font-size: 13px; transition: all .18s ease; text-decoration: none; }
    .lw-iact:hover { transform: translateY(-2px); }
    .lw-iact.info:hover { border-color: var(--lw-sky-border); color: var(--lw-sky); background: var(--lw-sky-soft); }
    .lw-iact.edit:hover { border-color: var(--lw-accent-border); color: var(--lw-accent); background: var(--lw-accent-soft); }
    .lw-iact.del:hover { border-color: var(--lw-red-border); color: var(--lw-red); background: var(--lw-red-soft); }
    .lw-iact.is-off { opacity: .4; cursor: not-allowed; }
    .lw-iact.is-off:hover { transform: none; box-shadow: none; color: var(--lw-text-2); background: var(--lw-card); border-color: var(--lw-border); }
</style>

<div class="lw-mod jd-page-kl">

@php
    $total = $kelompoks->total();
    $sesiMap = $kelompoks->getCollection()->mapWithKeys(function ($k) {
        $s = $k->lomba->sesiLomba ?? null;
        return [$k->id => $s];
    });
@endphp

<div class="lw-hero">
    <div class="lw-hero-grid">
        <div class="lw-hero-left">
            <span class="lw-hero-icon"><i class="bi bi-person-vcard-fill"></i></span>
            <div>
                <h1 class="lw-hero-title">Anggota Kelompok</h1>
                <p class="lw-hero-sub">Kelola anggota setiap kelompok lomba — tambah, perbarui, dan hapus susunan tim.</p>
                <div class="lw-hero-badges">
                    <span class="lw-hero-badge"><i class="bi bi-hash"></i>{{ $total }} kelompok</span>
                    <span class="lw-hero-badge"><i class="bi bi-people-fill"></i>Min. 2 siswa per tim</span>
                </div>
            </div>
        </div>
        <div class="lw-hero-right">
            <a href="{{ route('kelompok-lomba.print-preview', request()->query()) }}" target="_blank" rel="noopener" class="lw-btn lw-btn--light" title="Print Preview"><i class="bi bi-printer-fill"></i></a>
            <a href="{{ route('anggota-kelompok.create') }}" class="lw-btn lw-btn--accent"><i class="bi bi-plus-lg"></i> Tambah</a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="lw-alert lw-alert--ok"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="lw-alert lw-alert--err"><i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}</div>
@endif

{{-- TOOLBAR --}}
<div class="lw-toolbar">
    <form method="GET" action="{{ route('anggota-kelompok.index') }}" autocomplete="off" style="display:contents;">
        <div class="lw-search" style="min-width:180px;">
            <i class="bi bi-search"></i>
            <input type="search" name="search" value="{{ request('search') }}" class="lw-control" placeholder="Cari nama kelompok...">
        </div>
        <div class="lw-filter"><label>Haflah</label>
            <select name="haflah_id" class="lw-select" onchange="this.form.submit()">
                <option value="">Semua Haflah</option>
                @foreach($haflatuls as $h)
                    <option value="{{ $h->id }}" {{ request('haflah_id') == $h->id ? 'selected' : '' }}>{{ $h->nama_acara }}</option>
                @endforeach
            </select>
        </div>
        <div class="lw-filter"><label>Lomba</label>
            <select name="lomba_id" class="lw-select" onchange="this.form.submit()">
                <option value="">Semua Lomba</option>
                @foreach($lombas as $l)
                    <option value="{{ $l->id }}" {{ request('lomba_id') == $l->id ? 'selected' : '' }}>{{ $l->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="lw-toolbar-actions">
            <a href="{{ route('anggota-kelompok.index') }}" class="lw-btn lw-btn--ghost"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
        </div>
    </form>
</div>

{{-- TABLE --}}
<div class="lw-card lw-card-pad" style="margin-bottom:18px;">
    @if($kelompoks->isEmpty())
        <div class="lw-empty">
            <div class="lw-empty-illus"><div class="ring"></div><div class="ring-2"></div><div class="core"><i class="bi bi-person-vcard"></i></div></div>
            <div class="lw-empty-title">Belum Ada Anggota Kelompok</div>
            <p class="lw-empty-sub">Pilih kelompok tim dan daftarkan minimal 2 siswa sebagai anggota.</p>
            <a href="{{ route('anggota-kelompok.create') }}" class="lw-btn lw-btn--solid"><i class="bi bi-plus-lg"></i> Tambah Anggota</a>
        </div>
    @else
    <div class="table-responsive">
        <table class="table table-lw">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kelompok</th>
                    <th>Lomba</th>
                    <th>Sesi</th>
                    <th>Tanggal</th>
                    <th>Kelas</th>
                    <th>Jumlah Siswa</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kelompoks as $kelompok)
                    @php
                        $firstAnggota = $kelompok->anggota->first();
                        $l = $kelompok->lomba;
                        $sesi = $sesiMap[$kelompok->id] ?? null;
                        $isLocked = $kelompok->is_haflah_selesai || $kelompok->penilaian_lombas_count > 0;
                        $kelasBadge = ($l && $l->kelas_min && $l->kelas_max)
                            ? ['label' => 'Kelas ' . ($l->kelas_min == $l->kelas_max ? $l->kelas_min : $l->kelas_min.' - '.$l->kelas_max), 'class' => 'lw-chip--amber']
                            : ['label' => 'Semua Kelas', 'class' => 'lw-chip--green'];
                    @endphp
                    <tr>
                        <td>{{ $kelompoks->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="lw-kl-ava" style="background:{{ lw_ava_color($kelompok->nama_kelompok) }};"><i class="bi bi-people-fill"></i></span>
                                <div>
                                    <div class="lw-kl-name"><a href="{{ route('kelompok-lomba.show', $kelompok->id) }}">{{ $kelompok->nama_kelompok }}</a></div>
                                    <div class="lw-kl-code"><i class="bi bi-hash"></i>{{ $kelompok->kode_kelompok ?? 'Otomatis' }}</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="lw-chip lw-chip--navy lw-chip-mini"><i class="bi bi-trophy-fill"></i>{{ $l->nama ?? '-' }}</span></td>
                        <td>{{ $sesi->nama ?? '-' }}</td>
                        <td>{{ $sesi && !empty($sesi->tanggal) ? \Carbon\Carbon::parse($sesi->tanggal)->translatedFormat('d M Y') : '-' }}</td>
                        <td><span class="lw-chip {{ $kelasBadge['class'] }} lw-chip-mini">{{ $kelasBadge['label'] }}</span></td>
                        <td><span class="lw-count-badge"><i class="bi bi-person-vcard-fill"></i>{{ $kelompok->anggota_count }} siswa</span></td>
                        <td>
                            <div class="lw-act-row">
                                @if($firstAnggota)
                                    <a href="{{ route('kelompok-lomba.show', $kelompok->id) }}" class="lw-iact info" title="Detail"><i class="bi bi-eye"></i></a>
                                    @if($isLocked)
                                        <span class="lw-iact is-off" title="{{ $kelompok->penilaian_lombas_count > 0 ? 'Sudah memiliki penilaian - tidak dapat diubah' : 'Haflah selesai - terkunci' }}"><i class="bi {{ $kelompok->penilaian_lombas_count > 0 ? 'bi-ban' : 'bi-lock-fill' }}"></i></span>
                                    @else
                                        <a href="{{ route('anggota-kelompok.edit', $firstAnggota->id) }}" class="lw-iact edit" title="Kelola Anggota"><i class="bi bi-people-fill"></i></a>
                                        <button type="button" class="lw-iact del" data-ak-delete data-ak-nama="{{ e($kelompok->nama_kelompok) }}" data-ak-url="{{ route('anggota-kelompok.hapus-semua', $kelompok->id) }}" title="Hapus semua anggota"><i class="bi bi-trash"></i></button>
                                    @endif
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center" style="padding:40px 20px;color:var(--lw-text-3);"><i class="bi bi-person-vcard mb-2 d-block" style="font-size:28px;opacity:.4;"></i>Tidak ada data yang cocok dengan filter.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="lw-pagi">
        <div class="lw-pagi-info">Menampilkan {{ $kelompoks->firstItem() ?? 0 }}-{{ $kelompoks->lastItem() ?? 0 }} dari {{ $total }} entri</div>
        <div>{{ $kelompoks->onEachSide(1)->links() }}</div>
    </div>
    @endif
</div>

</div>

<form id="akDeleteForm" method="POST" class="d-none">@csrf @method('DELETE')</form>

@push('scripts')
<script>
(function () {
    var deleteForm = document.getElementById('akDeleteForm');
    document.querySelectorAll('[data-ak-delete]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var nama = btn.dataset.akNama;
            if (!nama) return;
            LW.confirm('Hapus Semua Anggota?', 'Semua anggota kelompok "' + nama + '" akan dihapus. Tindakan ini tidak dapat dibatalkan.', 'bi-trash').then(function (ok) {
                if (ok) { deleteForm.action = btn.dataset.akUrl; deleteForm.submit(); }
            });
        });
    });
})();
</script>
@endpush
@endsection
