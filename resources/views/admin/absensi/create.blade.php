@extends('layouts.main')
@section('title','Input Absensi')
@section('content')
@include('component.admin.absensi-module')
<style>
    .page-title-content { display: none !important; }
    .abm-create-hero { padding: 20px 26px; margin-bottom: 20px; border-radius: 20px; }
    .abm-step1-card { max-width: 640px; margin: 0 auto; padding: 26px 28px; }
    .abm-kbd {
        display: inline-flex; align-items: center; gap: 5px; font-size: 10.5px; color: var(--ab-text-3);
        background: var(--ab-border-soft); border-radius: 8px; padding: 4px 8px; font-weight: 600;
        border: 1px solid var(--ab-border);
    }
    .abm-key-hint { display: flex; flex-wrap: wrap; gap: 6px; align-items: center; }
    .abm-progress-wrap { flex: 1 1 200px; min-width: 160px; }
    .abm-progress-wrap .abm-progress { margin-top: 6px; }
    .abm-progress-label { font-size: 11.5px; color: var(--ab-text-3); font-weight: 600; }
    .abm-progress-label b { color: var(--ab-primary); }
</style>

<div class="abs-mod master-absensi-page">
    {{-- ===== HERO ===== --}}
    <div class="abm-hero abm-create-hero">
        <div class="abm-hero-grid"></div>
        <div class="abm-hero-row">
            <div class="abm-hero-left">
                <div class="d-flex align-items-center gap-3">
                    <div class="abm-hero-icon"><i class="fas fa-clipboard-list"></i></div>
                    <div>
                        <h3>Input Absensi</h3>
                        <p class="abm-hero-sub">Catat kehadiran siswa per kelas, cepat dan mudah.</p>
                    </div>
                </div>
            </div>
            <div class="abm-hero-badges" style="margin-top:0;">
                <span class="abm-hero-badge"><i class="fas fa-graduation-cap"></i> {{ $tahunAktif->tahun_ajaran }}</span>
                @if(isset($tahunAktif->semesterAktif))
                <span class="abm-hero-badge"><i class="fas fa-bookmark"></i> {{ $tahunAktif->semesterAktif->nama ?? '-' }}</span>
                @endif
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

@if(!isset($kelas))
    {{-- ===== STEP 1: PILIH KELAS & TANGGAL ===== --}}
    <div class="abm-card abm-step1-card">
        <div class="text-center mb-4">
            <div class="abm-modal-icon"><i class="fas fa-calendar-check"></i></div>
            <h5 class="fw-bold mt-3 mb-1" style="color:var(--ab-text);">Pilih Kelas & Tanggal</h5>
            <p style="color:var(--ab-text-3);font-size:12.5px;margin-bottom:0;">Langkah 1 dari 2 — tentukan kelas dan tanggal absensi.</p>
        </div>
        <form method="GET" action="{{ route('absensi.create') }}">
            <div class="mb-3">
                <label class="abm-field-label"><i class="fas fa-chalkboard"></i>Kelas <span class="text-danger">*</span></label>
                <select name="kelas_id" class="abm-control" required>
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($kelasList as $item)
                    <option value="{{ $item->id }}" {{ request('kelas_id') == $item->id ? 'selected' : '' }}>
                        {{ $item->nama_kelas }}{{ isset($item->jenjang) ? ' — ' . $item->jenjang->kode : '' }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="abm-field-label"><i class="fas fa-calendar"></i>Tanggal <span class="text-danger">*</span></label>
                <input type="date" name="tanggal" class="abm-control" value="{{ request('tanggal', now()->toDateString()) }}" required
                    @if($tahunAktif->tanggal_mulai) min="{{ $tahunAktif->tanggal_mulai }}" @endif
                    @if($tahunAktif->tanggal_selesai) max="{{ $tahunAktif->tanggal_selesai }}" @else max="{{ now()->toDateString() }}" @endif
                    id="tanggalInput">
            </div>
            <div class="abm-hintbox mb-4">
                <i class="fas fa-info-circle"></i>
                <span>Hari <strong>Jumat</strong> libur madrasah dan tanggal di luar tahun ajaran aktif tidak dapat dipilih.</span>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <button type="submit" class="abm-btn abm-btn--solid" style="flex:1;">
                    <i class="fas fa-search"></i> Tampilkan Siswa
                </button>
                <button type="button" class="abm-btn abm-btn--outline" data-bs-toggle="modal" data-bs-target="#modalCetakBuku">
                    <i class="fas fa-print"></i> Cetak Buku
                </button>
                <a href="{{ route('absensi.index') }}" class="abm-btn abm-btn--soft">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>

    {{-- Modal Cetak Buku Absensi --}}
    <div class="modal fade" id="modalCetakBuku" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:18px;border:none;box-shadow:0 20px 60px rgba(0,0,0,.18);overflow:hidden;">
                <div class="modal-header" style="background:var(--ab-grad);color:#fff;border:none;padding:16px 20px;">
                    <h6 class="modal-title fw-bold" style="font-size:15px;"><i class="fas fa-print me-2"></i>Cetak Buku Absensi Siswa</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="GET" action="{{ route('absensi.cetak-buku-pdf') }}" target="_blank">
                    <div class="modal-body" style="padding:20px;">
                        <input type="hidden" name="tahun_ajaran_id" value="{{ $tahunAktif->id }}">
                        <div class="mb-3">
                            <label class="abm-field-label">Tahun Pelajaran</label>
                            <input type="text" class="abm-control" value="{{ $tahunAktif->tahun_ajaran }}" disabled style="background:var(--ab-border-soft);">
                        </div>
                        <div class="mb-3">
                            <label class="abm-field-label">Kelas <span class="text-danger">*</span></label>
                            <select name="kelas_id" class="abm-control" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($kelasList as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kelas }}{{ isset($k->jenjang) ? ' (' . $k->jenjang->kode . ')' : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="abm-field-label">Bulan <span class="text-danger">*</span></label>
                            <select name="bulan" class="abm-control" required>
                                <option value="">-- Pilih Bulan --</option>
                                @php
                                    $bulanNames = [
                                        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                                        '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                                        '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                                    ];
                                    $currentMonth = now()->format('m');
                                @endphp
                                @foreach($bulanNames as $num => $name)
                                <option value="{{ now()->year }}-{{ $num }}" {{ $num === $currentMonth ? 'selected' : '' }}>{{ $name }} {{ now()->year }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top:1px solid var(--ab-border);padding:12px 20px;">
                        <button type="button" class="abm-btn abm-btn--soft" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="abm-btn abm-btn--solid"><i class="fas fa-print me-1"></i> Cetak PDF</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@else
    {{-- ===== STEP 2: FORM ABSENSI ===== --}}
    <div class="abm-card" style="padding:16px 18px;margin-bottom:16px;">
        <div class="d-flex flex-wrap align-items-center gap-3">
            <div class="abm-kelas-avatar c0" style="width:44px;height:44px;">{{ substr($kelas->nama_kelas, 0, 2) }}</div>
            <div style="flex:1;min-width:160px;">
                <div class="abm-kelas-name">{{ $kelas->nama_kelas }}</div>
                <div class="abm-kelas-meta">{{ $kelas->jenjang->nama ?? '-' }}</div>
            </div>
            <span class="abm-chip abm-chip--blue"><i class="fas fa-calendar-day"></i> {{ \Carbon\Carbon::parse(request('tanggal'))->translatedFormat('d F Y') }}</span>
            <span class="abm-chip abm-chip--ok"><i class="fas fa-users"></i> {{ $siswas->count() }} Siswa</span>
            @if($existingAbsensi)
            <span class="abm-chip abm-chip--warn"><i class="fas fa-sync-alt"></i> Mode Update</span>
            @endif
        </div>
    </div>

    @if($existingAbsensi)
    <div class="abm-alert abm-alert--warn">
        <i class="fas fa-exclamation-triangle"></i>
        <div>
            Absensi untuk kelas ini pada tanggal <strong>{{ $existingAbsensi->tanggal->translatedFormat('d F Y') }}</strong> sudah ada. Data akan diupdate.
            <a href="{{ route('absensi.edit', $existingAbsensi->id) }}" style="font-weight:700;text-decoration:underline;">Buka Halaman Edit</a>
        </div>
    </div>
    @endif

    @if($siswas->isEmpty())
    <div class="abm-card" style="margin-bottom:10px;">
        <div class="abm-empty">
            <i class="fas fa-user-slash"></i>
            <div class="abm-empty-title">Tidak Ada Siswa Aktif</div>
            <div class="abm-empty-sub">Tidak ditemukan siswa aktif di kelas {{ $kelas->nama_kelas }} pada tahun ajaran {{ $tahunAktif->tahun_ajaran }}.</div>
            <a href="{{ route('absensi.create') }}" class="abm-btn abm-btn--soft mt-3"><i class="fas fa-arrow-left"></i> Pilih Kelas Lain</a>
        </div>
    </div>
    @else
    <form id="absensiForm" action="{{ route('absensi.store') }}" method="POST">
        @csrf
        <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">
        <input type="hidden" name="tanggal" value="{{ request('tanggal') }}">

        <div class="abm-card" style="padding:18px 20px 0;">
            <div class="abm-toolbar">
                <div class="abm-search">
                    <i class="fas fa-search"></i>
                    <input type="text" id="siswaSearch" placeholder="Cari nama atau NISN siswa..." autocomplete="off">
                </div>
                <div class="abm-counter">
                    <span class="abm-counter-item h"><i class="fas fa-circle" style="font-size:7px;"></i> Hadir <b id="cH">0</b></span>
                    <span class="abm-counter-item i"><i class="fas fa-circle" style="font-size:7px;"></i> Izin <b id="cI">0</b></span>
                    <span class="abm-counter-item s"><i class="fas fa-circle" style="font-size:7px;"></i> Sakit <b id="cS">0</b></span>
                    <span class="abm-counter-item a"><i class="fas fa-circle" style="font-size:7px;"></i> Alpha <b id="cA">0</b></span>
                </div>
                <div class="abm-key-hint ms-auto">
                    <span class="abm-kbd"><i class="fas fa-mouse-pointer"></i> klik baris</span>
                    <span class="abm-kbd">↑ ↓ pilih</span>
                    <span class="abm-kbd">1 Hadir</span>
                    <span class="abm-kbd">2 Izin</span>
                    <span class="abm-kbd">3 Sakit</span>
                    <span class="abm-kbd">4 Alpha</span>
                </div>
            </div>

            <div class="abm-student-list">
                @foreach($siswas as $siswa)
                @php
                    $currentStatus = $existingAbsensi ? ($existingAbsensi->details->where('student_id', $siswa->id)->first()?->status ?? 'H') : 'H';
                    $currentKet = $existingAbsensi ? ($existingAbsensi->details->where('student_id', $siswa->id)->first()?->keterangan ?? '') : '';
                    $statusKey = strtolower($currentStatus);
                    $avatarClass = 'c' . ($loop->index % 6);
                @endphp
                <div class="abm-student is-on-{{ $statusKey }}" data-id="{{ $siswa->id }}" data-name="{{ strtolower($siswa->nama) }}" data-nisn="{{ $siswa->nisn }}" tabindex="0">
                    <span class="abm-student-no">{{ $loop->iteration }}</span>
                    <div class="abm-student-avatar {{ $avatarClass }}">{{ substr($siswa->nama, 0, 1) }}</div>
                    <div class="abm-student-main">
                        <div class="abm-student-name">{{ $siswa->nama }}</div>
                        <div class="abm-student-nisn">NISN: {{ $siswa->nisn }}</div>
                    </div>
                    <div class="abm-stepper" role="radiogroup" aria-label="Status {{ $siswa->nama }}">
                        <button type="button" class="abm-step {{ $currentStatus == 'H' ? 'is-on-h' : '' }}" data-status="H" title="Hadir">
                            <span class="k">H</span>Hadir
                        </button>
                        <button type="button" class="abm-step {{ $currentStatus == 'I' ? 'is-on-i' : '' }}" data-status="I" title="Izin">
                            <span class="k">I</span>Izin
                        </button>
                        <button type="button" class="abm-step {{ $currentStatus == 'S' ? 'is-on-s' : '' }}" data-status="S" title="Sakit">
                            <span class="k">S</span>Sakit
                        </button>
                        <button type="button" class="abm-step {{ $currentStatus == 'A' ? 'is-on-a' : '' }}" data-status="A" title="Alpha">
                            <span class="k">A</span>Alpha
                        </button>
                    </div>
                    <div class="abm-keterangan-wrap {{ $currentStatus == 'H' ? 'is-hidden' : '' }}">
                        <input type="text" class="abm-keterangan" name="keterangan[{{ $siswa->id }}]" placeholder="Catatan (opsional)" value="{{ $currentKet }}" {{ $currentStatus == 'H' ? 'disabled' : '' }}>
                    </div>
                    <input type="hidden" name="status[{{ $siswa->id }}]" value="{{ $currentStatus }}">
                </div>
                @endforeach
            </div>
        </div>

        <div class="abm-actionbar">
            <div class="abm-actionbar-count">Total <b id="actTotal">0</b> siswa</div>
            <div class="abm-progress-wrap">
                <div class="abm-progress-label"><b id="actDone">0</b>/<span id="actTotal2">0</span> diinput</div>
                <div class="abm-progress"><span id="actBar"></span></div>
            </div>
            <div style="margin-left:auto;display:flex;gap:8px;flex-wrap:wrap;">
                <button type="button" id="btnReset" class="abm-btn abm-btn--soft"><i class="fas fa-undo-alt"></i> Reset</button>
                <a href="{{ route('absensi.create') }}" class="abm-btn abm-btn--outline"><i class="fas fa-arrow-left"></i> Ganti Kelas</a>
                <button type="button" id="btnSimpan" class="abm-btn abm-btn--solid">
                    <i class="fas fa-save"></i> {{ $existingAbsensi ? 'Update Absensi' : 'Simpan Absensi' }}
                </button>
            </div>
        </div>
    </form>
    @endif
@endif
</div>

{{-- Modal Konfirmasi --}}
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:18px;border:none;box-shadow:0 8px 32px rgba(0,0,0,.14);">
            <div class="modal-body abm-modal-body text-center">
                <div class="abm-modal-icon"><i class="fas fa-clipboard-check"></i></div>
                <h5 class="mt-3 fw-bold" style="color:var(--ab-text);" id="confirmTitle">Konfirmasi Simpan Absensi</h5>
                <div style="background:var(--ab-border-soft);border-radius:12px;padding:14px 18px;margin:16px 0;">
                    <div class="d-flex justify-content-between mb-2">
                        <span style="color:var(--ab-text-3);font-size:13px;"><i class="fas fa-chalkboard me-1"></i>Kelas</span>
                        <strong style="color:var(--ab-text);font-size:13px;" id="confirmKelas">-</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span style="color:var(--ab-text-3);font-size:13px;"><i class="fas fa-calendar me-1"></i>Tanggal</span>
                        <strong style="color:var(--ab-text);font-size:13px;" id="confirmTanggal">-</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span style="color:var(--ab-text-3);font-size:13px;"><i class="fas fa-users me-1"></i>Total Siswa</span>
                        <strong style="color:var(--ab-text);font-size:13px;" id="confirmTotal">-</strong>
                    </div>
                </div>
                <div class="abm-counter" style="justify-content:center;margin-bottom:14px;">
                    <span class="abm-counter-item h">Hadir <b id="confirmH">0</b></span>
                    <span class="abm-counter-item i">Izin <b id="confirmI">0</b></span>
                    <span class="abm-counter-item s">Sakit <b id="confirmS">0</b></span>
                    <span class="abm-counter-item a">Alpha <b id="confirmA">0</b></span>
                </div>
                <p style="color:var(--ab-text-2);font-size:13px;margin-bottom:0;">Apakah Anda yakin ingin menyimpan absensi ini?</p>
            </div>
            <div class="modal-footer border-0 pt-0 pb-4 px-4" style="justify-content:center;gap:8px;">
                <button type="button" class="abm-btn abm-btn--soft" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="abm-btn abm-btn--solid" id="confirmBtn"><i class="fas fa-save me-1"></i>Ya, Simpan Absensi</button>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var tanggalInput = document.getElementById('tanggalInput');
    var disabledDates = @json($disabledDates ?? []);

    if (tanggalInput) {
        tanggalInput.addEventListener('change', function() {
            var val = this.value;
            if (val && disabledDates.indexOf(val) !== -1) {
                this.setCustomValidity('Tanggal ini tidak tersedia (Jumat/libur/tanggal depan).');
                this.reportValidity();
                this.value = '';
            } else {
                this.setCustomValidity('');
            }
        });
    }

    var form = document.getElementById('absensiForm');
    if (!form) return;

    var btnSimpan = document.getElementById('btnSimpan');
    var confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
    var isExisting = {{ $existingAbsensi ? 'true' : 'false' }};

    if (isExisting) {
        document.getElementById('confirmTitle').textContent = 'Konfirmasi Update Absensi';
        document.getElementById('confirmBtn').innerHTML = '<i class="fas fa-save me-1"></i>Ya, Update Absensi';
    }

    var kelasName = '{{ $kelas->nama_kelas ?? "-" }}';
    var tanggalRaw = '{{ request("tanggal") }}';
    var tanggalFormatted = '';
    if (tanggalRaw) {
        var parts = tanggalRaw.split('-');
        var months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        tanggalFormatted = parseInt(parts[2]) + ' ' + months[parseInt(parts[1])-1] + ' ' + parts[0];
    }

    var rows = Array.prototype.slice.call(document.querySelectorAll('.abm-student'));
    var total = rows.length;

    function countStatus() {
        var h = 0, i = 0, s = 0, a = 0, done = 0;
        rows.forEach(function(row) {
            var val = row.querySelector('input[type="hidden"][name^="status"]').value;
            if (val === 'H') { h++; done++; }
            else if (val === 'I') { i++; done++; }
            else if (val === 'S') { s++; done++; }
            else if (val === 'A') { a++; done++; }
        });
        document.getElementById('cH').textContent = h;
        document.getElementById('cI').textContent = i;
        document.getElementById('cS').textContent = s;
        document.getElementById('cA').textContent = a;
        document.getElementById('actTotal').textContent = total;
        document.getElementById('actTotal2').textContent = total;
        document.getElementById('actDone').textContent = done;
        var bar = document.getElementById('actBar');
        if (bar) bar.style.width = (total ? Math.round(done / total * 100) : 0) + '%';
    }

    function setStatus(row, status, focus) {
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
            ketInput.value = '';
        } else {
            ket.classList.remove('is-hidden');
            ketInput.disabled = false;
        }
        if (focus) row.focus();
        countStatus();
    }

    rows.forEach(function(row) {
        row.querySelectorAll('.abm-step').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                setStatus(row, this.getAttribute('data-status'), false);
            });
        });
        row.addEventListener('click', function() {
            rows.forEach(function(r) { r.classList.remove('is-selected'); });
            row.classList.add('is-selected');
        });
    });

    document.addEventListener('keydown', function(e) {
        if (['INPUT', 'TEXTAREA', 'SELECT'].indexOf(document.activeElement.tagName) !== -1) return;
        var sel = document.querySelector('.abm-student.is-selected');
        if (!sel) return;
        var idx = rows.indexOf(sel);
        switch (e.key) {
            case 'ArrowDown': e.preventDefault(); idx = Math.min(idx + 1, total - 1); break;
            case 'ArrowUp': e.preventDefault(); idx = Math.max(idx - 1, 0); break;
            case '1': setStatus(sel, 'H', false); return;
            case '2': setStatus(sel, 'I', false); return;
            case '3': setStatus(sel, 'S', false); return;
            case '4': setStatus(sel, 'A', false); return;
            default: return;
        }
        rows.forEach(function(r) { r.classList.remove('is-selected'); });
        rows[idx].classList.add('is-selected');
        rows[idx].scrollIntoView({ block: 'nearest', behavior: 'smooth' });
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
        if (!confirm('Reset semua status ke Hadir?')) return;
        rows.forEach(function(row) { setStatus(row, 'H', false); });
    });

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
        document.getElementById('confirmKelas').textContent = kelasName;
        document.getElementById('confirmTanggal').textContent = tanggalFormatted;
        document.getElementById('confirmTotal').textContent = (h + i + s + a) + ' Siswa';
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
