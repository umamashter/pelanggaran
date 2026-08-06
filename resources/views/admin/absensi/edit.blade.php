@extends('layouts.main')
@section('title','Edit Absensi')
@section('content')
@include('component.admin.absensi-module')
<style>
    .page-title-content { display: none !important; }
    .abm-edit-hero { padding: 20px 26px; margin-bottom: 20px; border-radius: 20px; }
    .abm-changed-badge {
        display: inline-flex; align-items: center; gap: 5px;
        background: var(--ab-amber-soft); color: var(--ab-amber); border: 1px solid var(--ab-amber-border);
        border-radius: 20px; padding: 3px 10px; font-size: 10.5px; font-weight: 800; letter-spacing: .3px;
    }
    .abm-student.has-changed { border-color: var(--ab-amber); box-shadow: 0 0 0 3px var(--ab-amber-soft); }
    html.dark-mode .abm-student.has-changed { box-shadow: 0 0 0 3px rgba(251,191,36,.14); }
</style>

<div class="abs-mod master-absensi-page">
    {{-- ===== HERO ===== --}}
    <div class="abm-hero abm-edit-hero">
        <div class="abm-hero-grid"></div>
        <div class="abm-hero-row">
            <div class="abm-hero-left">
                <div class="d-flex align-items-center gap-3">
                    <div class="abm-hero-icon"><i class="fas fa-edit"></i></div>
                    <div>
                        <h3>Edit Absensi</h3>
                        <p class="abm-hero-sub">Perbaiki kehadiran siswa yang sudah tercatat.</p>
                    </div>
                </div>
            </div>
            <div class="abm-hero-badges" style="margin-top:0;">
                <span class="abm-hero-badge"><i class="fas fa-calendar-day"></i> {{ $absensi->tanggal->translatedFormat('d F Y') }}</span>
                <span class="abm-hero-badge"><i class="fas fa-chalkboard"></i> {{ $absensi->kelas?->nama_kelas ?? '-' }}</span>
            </div>
        </div>
    </div>

    @if(session('error'))
    <div class="abm-alert abm-alert--danger">
        <i class="fas fa-exclamation-circle"></i>
        <div>{{ session('error') }}</div>
    </div>
    @endif

    @if($errors->any())
    <div class="abm-alert abm-alert--danger">
        <i class="fas fa-exclamation-circle"></i>
        <div>
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="abm-card" style="padding:14px 18px;margin-bottom:16px;">
        <div class="d-flex flex-wrap align-items-center gap-3">
            <div class="abm-kelas-avatar c1" style="width:44px;height:44px;">{{ substr($absensi->kelas?->nama_kelas ?? 'AB', 0, 2) }}</div>
            <div style="flex:1;min-width:160px;">
                <div class="abm-kelas-name">{{ $absensi->kelas?->nama_kelas ?? '-' }}</div>
                <div class="abm-kelas-meta">{{ $absensi->tahunAjaran?->tahun_ajaran ?? '-' }}</div>
            </div>
            <span class="abm-chip abm-chip--blue"><i class="fas fa-users"></i> {{ $siswas->count() }} Siswa</span>
            @if($absensi->user)
            <span class="abm-chip abm-chip--muted"><i class="fas fa-user-tie"></i> {{ $absensi->user->name }}</span>
            @endif
            @if(isset($singleSiswaId) && $singleSiswaId)
            <span class="abm-chip abm-chip--warn"><i class="fas fa-user-edit"></i> Edit Satu Siswa</span>
            @endif
        </div>
    </div>

    <form id="absensiForm" action="{{ route('absensi.update', $absensi->id) }}" method="POST">
        @csrf
        @method('PUT')
        @if(!empty($singleSiswaId))
        <input type="hidden" name="siswa" value="{{ $singleSiswaId }}">
        @endif

        <div class="abm-card" style="padding:18px 20px 0;">
            <div class="abm-toolbar" style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:16px;">
                <div class="abm-search" style="flex:1 1 220px;max-width:340px;">
                    <i class="fas fa-search"></i>
                    <input type="text" id="siswaSearch" placeholder="Cari nama atau NISN siswa..." autocomplete="off">
                </div>
                <div class="abm-counter">
                    <span class="abm-counter-item h"><i class="fas fa-circle" style="font-size:7px;"></i> Hadir <b id="cH">0</b></span>
                    <span class="abm-counter-item i"><i class="fas fa-circle" style="font-size:7px;"></i> Izin <b id="cI">0</b></span>
                    <span class="abm-counter-item s"><i class="fas fa-circle" style="font-size:7px;"></i> Sakit <b id="cS">0</b></span>
                    <span class="abm-counter-item a"><i class="fas fa-circle" style="font-size:7px;"></i> Alpha <b id="cA">0</b></span>
                </div>
                <span class="abm-chip abm-chip--warn ms-auto"><i class="fas fa-sync-alt"></i> <span id="changedCount">0</span> perubahan</span>
            </div>

            <div class="abm-student-list">
                @foreach($siswas as $siswa)
                @php
                    $currentStatus = $detailMap[$siswa->id] ?? 'H';
                    $statusKey = strtolower($currentStatus);
                    $avatarClass = 'c' . ($loop->index % 6);
                @endphp
                <div class="abm-student is-on-{{ $statusKey }}" data-id="{{ $siswa->id }}" data-original="{{ $currentStatus }}" data-name="{{ strtolower($siswa->nama) }}" data-nisn="{{ $siswa->nisn }}" tabindex="0">
                    <span class="abm-student-no">{{ $loop->iteration }}</span>
                    <div class="abm-student-avatar {{ $avatarClass }}">{{ substr($siswa->nama, 0, 1) }}</div>
                    <div class="abm-student-main">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="abm-student-name">{{ $siswa->nama }}</span>
                            <span class="abm-changed-badge" style="display:none;"><i class="fas fa-edit"></i> BERUBAH</span>
                        </div>
                        <div class="abm-student-nisn">NISN: {{ $siswa->nisn }}</div>
                    </div>
                    <div class="abm-stepper" role="radiogroup" aria-label="Status {{ $siswa->nama }}">
                        <button type="button" class="abm-step {{ $currentStatus == 'H' ? 'is-on-h' : '' }}" data-status="H" title="Hadir"><span class="k">H</span>Hadir</button>
                        <button type="button" class="abm-step {{ $currentStatus == 'I' ? 'is-on-i' : '' }}" data-status="I" title="Izin"><span class="k">I</span>Izin</button>
                        <button type="button" class="abm-step {{ $currentStatus == 'S' ? 'is-on-s' : '' }}" data-status="S" title="Sakit"><span class="k">S</span>Sakit</button>
                        <button type="button" class="abm-step {{ $currentStatus == 'A' ? 'is-on-a' : '' }}" data-status="A" title="Alpha"><span class="k">A</span>Alpha</button>
                    </div>
                    <div class="abm-keterangan-wrap {{ $currentStatus == 'H' ? 'is-hidden' : '' }}">
                        <input type="text" class="abm-keterangan" name="keterangan[{{ $siswa->id }}]" placeholder="Catatan (opsional)" value="{{ $keteranganMap[$siswa->id] ?? '' }}" {{ $currentStatus == 'H' ? 'disabled' : '' }}>
                    </div>
                    <button type="button" class="abm-undo" title="Kembalikan ke status awal" style="display:none;"><i class="fas fa-undo"></i></button>
                    <input type="hidden" name="status[{{ $siswa->id }}]" value="{{ $currentStatus }}">
                </div>
                @endforeach
            </div>
        </div>

        <div class="abm-actionbar">
            <div class="abm-actionbar-count">Total <b id="actTotal">0</b> siswa</div>
            <div style="margin-left:auto;display:flex;gap:8px;flex-wrap:wrap;">
                <button type="button" id="btnReset" class="abm-btn abm-btn--soft"><i class="fas fa-undo-alt"></i> Reset Semua</button>
                <a href="{{ route('absensi.riwayat') }}" class="abm-btn abm-btn--outline"><i class="fas fa-arrow-left"></i> Kembali</a>
                <button type="button" id="btnSimpan" class="abm-btn abm-btn--solid">
                    <i class="fas fa-save"></i> {{ $siswas->count() === 1 ? 'Update Status Siswa' : 'Update Absensi' }}
                </button>
            </div>
        </div>
    </form>
</div>

{{-- Modal Konfirmasi --}}
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:18px;border:none;box-shadow:0 8px 32px rgba(0,0,0,.14);">
            <div class="modal-body abm-modal-body text-center">
                <div class="abm-modal-icon"><i class="fas fa-clipboard-check"></i></div>
                <h5 class="mt-3 fw-bold" style="color:var(--ab-text);">Konfirmasi Update Absensi</h5>
                <div style="background:var(--ab-border-soft);border-radius:12px;padding:14px 18px;margin:16px 0;">
                    <div class="d-flex justify-content-between mb-2">
                        <span style="color:var(--ab-text-3);font-size:13px;"><i class="fas fa-chalkboard me-1"></i>Kelas</span>
                        <strong style="color:var(--ab-text);font-size:13px;">{{ $absensi->kelas?->nama_kelas ?? '-' }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span style="color:var(--ab-text-3);font-size:13px;"><i class="fas fa-calendar me-1"></i>Tanggal</span>
                        <strong style="color:var(--ab-text);font-size:13px;">{{ $absensi->tanggal->translatedFormat('d F Y') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span style="color:var(--ab-text-3);font-size:13px;"><i class="fas fa-users me-1"></i>Total Siswa</span>
                        <strong style="color:var(--ab-text);font-size:13px;">{{ $siswas->count() }} Siswa</strong>
                    </div>
                </div>
                <div class="abm-counter" style="justify-content:center;margin-bottom:14px;">
                    <span class="abm-counter-item h">Hadir <b id="confirmH">0</b></span>
                    <span class="abm-counter-item i">Izin <b id="confirmI">0</b></span>
                    <span class="abm-counter-item s">Sakit <b id="confirmS">0</b></span>
                    <span class="abm-counter-item a">Alpha <b id="confirmA">0</b></span>
                </div>
                <p style="color:var(--ab-text-2);font-size:13px;margin-bottom:0;">Apakah Anda yakin ingin memperbarui absensi ini?</p>
            </div>
            <div class="modal-footer border-0 pt-0 pb-4 px-4" style="justify-content:center;gap:8px;">
                <button type="button" class="abm-btn abm-btn--soft" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="abm-btn abm-btn--solid" id="confirmBtn"><i class="fas fa-save me-1"></i>Ya, Update Absensi</button>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('absensiForm');
    if (!form) return;

    var rows = Array.prototype.slice.call(document.querySelectorAll('.abm-student'));
    var total = rows.length;

    function countStatus() {
        var h = 0, i = 0, s = 0, a = 0, changed = 0;
        rows.forEach(function(row) {
            var input = row.querySelector('input[type="hidden"][name^="status"]');
            switch (input.value) {
                case 'H': h++; break;
                case 'I': i++; break;
                case 'S': s++; break;
                case 'A': a++; break;
            }
            if (input.value !== row.getAttribute('data-original')) changed++;
        });
        document.getElementById('cH').textContent = h;
        document.getElementById('cI').textContent = i;
        document.getElementById('cS').textContent = s;
        document.getElementById('cA').textContent = a;
        document.getElementById('actTotal').textContent = total;
        document.getElementById('changedCount').textContent = changed;
    }

    function syncChanged(row) {
        var input = row.querySelector('input[type="hidden"][name^="status"]');
        var isChanged = input.value !== row.getAttribute('data-original');
        row.classList.toggle('has-changed', isChanged);
        row.querySelector('.abm-changed-badge').style.display = isChanged ? 'inline-flex' : 'none';
        row.querySelector('.abm-undo').style.display = isChanged ? 'inline-flex' : 'none';
    }

    function setStatus(row, status) {
        row.classList.remove('is-on-h', 'is-on-i', 'is-on-s', 'is-on-a');
        row.classList.add('is-on-' + status.toLowerCase());
        row.querySelectorAll('.abm-step').forEach(function(btn) {
            btn.classList.remove('is-on-h', 'is-on-i', 'is-on-s', 'is-on-a');
            if (btn.getAttribute('data-status') === status) btn.classList.add('is-on-' + status.toLowerCase());
        });
        row.querySelector('input[type="hidden"][name^="status"]').value = status;
        var ket = row.querySelector('.abm-keterangan-wrap');
        var ketInput = row.querySelector('.abm-keterangan');
        if (status === 'H') {
            ket.classList.add('is-hidden');
            ketInput.disabled = true;
        } else {
            ket.classList.remove('is-hidden');
            ketInput.disabled = false;
        }
        syncChanged(row);
        countStatus();
    }

    function restore(row) {
        setStatus(row, row.getAttribute('data-original'));
    }

    rows.forEach(function(row) {
        row.querySelectorAll('.abm-step').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                setStatus(row, this.getAttribute('data-status'));
            });
        });
        row.querySelector('.abm-undo').addEventListener('click', function(e) {
            e.stopPropagation();
            restore(row);
        });
        row.addEventListener('click', function() {
            rows.forEach(function(r) { r.classList.remove('is-selected'); });
            row.classList.add('is-selected');
        });
    });

    var search = document.getElementById('siswaSearch');
    if (search) {
        search.addEventListener('input', function() {
            var q = this.value.trim().toLowerCase();
            rows.forEach(function(row) {
                var hay = row.getAttribute('data-name') + ' ' + row.getAttribute('data-nisn');
                row.style.display = hay.indexOf(q) !== -1 ? '' : 'none';
            });
        });
    }

    document.getElementById('btnReset').addEventListener('click', function() {
        if (!confirm('Kembalikan semua status ke data awal?')) return;
        rows.forEach(restore);
    });

    var btnSimpan = document.getElementById('btnSimpan');
    var confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));

    btnSimpan.addEventListener('click', function() {
        var h = 0, i = 0, s = 0, a = 0;
        rows.forEach(function(row) {
            switch (row.querySelector('input[type="hidden"][name^="status"]').value) {
                case 'H': h++; break;
                case 'I': i++; break;
                case 'S': s++; break;
                case 'A': a++; break;
            }
        });
        document.getElementById('confirmH').textContent = h;
        document.getElementById('confirmI').textContent = i;
        document.getElementById('confirmS').textContent = s;
        document.getElementById('confirmA').textContent = a;
        confirmModal.show();
    });

    document.getElementById('confirmBtn').addEventListener('click', function() {
        confirmModal.hide();
        form.submit();
    });

    countStatus();
});
</script>
@endpush
