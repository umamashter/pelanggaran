<div class="nav-item dropdown px-2" style="border-right:1px solid #e2e8f0;">
    <a id="haflahDropdown" class="name-tag nav-link dropdown-toggle c-header-icon d-flex align-items-center gap-2"
        href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre
        style="font-size:13px;font-weight:600;color:#16a34a;white-space:nowrap;">
        <i class="fas fa-award"></i>
        <span>{{ $haflahAktif->nama_acara ?? 'Pilih Haflah' }} @if($haflahAktif && $haflahAktif->tahunAjaran)<small style="font-size:11px;color:#94a3b8;"> ({{ $haflahAktif->tahunAjaran->tahun_ajaran }})</small>@endif</span>
    </a>

    <div class="dropdown-menu dropdown-menu-start" aria-labelledby="haflahDropdown" style="min-width:220px;">
        @foreach($semuaHaflah as $h)
        <a class="dropdown-item py-2 d-flex align-items-center gap-2 {{ session('haflah_id') == $h->id ? 'active' : '' }}"
            href="{{ route('haflah.aktifkan', $h->id) }}">
            <i class="fas fa-{{ $h->status == 'Aktif' ? 'check-circle text-success' : 'circle text-muted' }}" style="font-size:12px;"></i>
            <span>{{ $h->nama_acara }}</span>
            <small style="font-size:10px;color:#94a3b8;">{{ $h->tahunAjaran->tahun_ajaran ?? '' }}</small>
            @if($h->status == 'Aktif')
            <span class="ms-auto badge bg-success" style="font-size:10px;">Aktif</span>
            @endif
        </a>
        @endforeach
        <div class="dropdown-divider"></div>
        <a class="dropdown-item py-2" href="{{ route('haflatul-imtihan.index') }}">
            <i class="fas fa-cog me-2"></i> Kelola Haflah
        </a>
    </div>
</div>
