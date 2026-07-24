@extends('layouts.main')
@section('title','Verifikasi Import Absensi')
@section('content')
<style>
.page-title-content { display: none !important; }
:root { --ms-primary: #16a34a; --ms-primary-dark: #15803d; --ms-primary-light: #dcfce7; --ms-bg: #f5f7fb; --ms-border: #e2e8f0; --ms-text: #1e293b; --ms-text-soft: #64748b; }
.import-verify-page { font-family: 'Inter', 'Poppins', system-ui, sans-serif; margin-top: 22px; }
.header-icon { width: 52px; height: 52px; border-radius: 14px; background: linear-gradient(135deg, #f59e0b, #fbbf24); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 24px; box-shadow: 0 4px 14px rgba(245,158,11,.3); flex-shrink: 0; }
.badge-modern { display: inline-flex; align-items: center; padding: 4px 14px; border-radius: 20px; font-size: 12px; font-weight: 500; white-space: nowrap; }
.badge-ta { background: #f0fdf4; color: #16a34a; }
.badge-warning-custom { background: #fffbeb; color: #92400e; }
.info-card-modern { background: #f0fdf4; border-left: 4px solid #16a34a; border-radius: 12px; padding: 16px 20px; font-size: 13px; color: #166534; box-shadow: 0 1px 3px rgba(0,0,0,.06); margin-bottom: 20px; }
.stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 10px; margin-bottom: 20px; }
.stat-badge { padding: 10px 14px; border-radius: 12px; font-size: 12px; font-weight: 600; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,.05); }
.stat-badge .stat-num { font-size: 20px; font-weight: 800; display: block; line-height: 1.2; }
.stat-h { background: #f0fdf4; color: #166534; }
.stat-i { background: #eff6ff; color: #1e40af; }
.stat-s { background: #fef3c7; color: #92400e; }
.stat-a { background: #fef2f2; color: #991b1b; }
.stat-q { background: #f5f3ff; color: #5b21b6; }
.stat-libur { background: #f1f5f9; color: #475569; }
.stat-future { background: #f8fafc; color: #94a3b8; }
.table-wrapper { overflow-x: auto; border-radius: 14px; box-shadow: 0 4px 16px rgba(0,0,0,.06); border: 1px solid var(--ms-border); }
.table-verify { border-collapse: collapse; width: 100%; min-width: 800px; }
.table-verify thead th { background: linear-gradient(180deg, #f8fafc, #f1f5f9); color: #475569; font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; padding: 10px 8px; border-bottom: 2px solid var(--ms-border); text-align: center; white-space: nowrap; position: sticky; top: 0; z-index: 2; }
.table-verify thead th.name-col { text-align: left; min-width: 160px; }
.table-verify tbody td { padding: 6px 4px; font-size: 12px; color: #334155; border-bottom: 1px solid #f1f5f9; text-align: center; vertical-align: middle; }
.table-verify tbody td.name-col { text-align: left; font-weight: 600; white-space: nowrap; padding-left: 12px; }
.table-verify tbody tr:hover td { background: #f8fafc; }
.table-verify tbody tr:last-child td { border-bottom: none; }
.table-verify .day-hdr { font-size: 10px; color: #94a3b8; display: block; }
.cell-select { width: 48px; height: 30px; border-radius: 6px; border: 1.5px solid var(--ms-border); font-size: 12px; font-weight: 600; text-align: center; padding: 0; background: #fff; cursor: pointer; transition: all .2s; -webkit-appearance: none; appearance: none; }
.cell-select:focus { border-color: var(--ms-primary); box-shadow: 0 0 0 2px rgba(22,163,74,.15); outline: none; }
.cell-select.status-h { background: #f0fdf4; color: #166534; border-color: #bbf7d0; }
.cell-select.status-i { background: #eff6ff; color: #1e40af; border-color: #bfdbfe; }
.cell-select.status-s { background: #fef3c7; color: #92400e; border-color: #fde68a; }
.cell-select.status-a { background: #fef2f2; color: #991b1b; border-color: #fecaca; }
.cell-select.status-q { background: #f5f3ff; color: #5b21b6; border-color: #c4b5fd; }
.cell-select:disabled { background: #f1f5f9; color: #94a3b8; cursor: not-allowed; opacity: .7; }
.cell-libur { font-size: 10px; color: #94a3b8; font-weight: 600; background: #f8fafc; }
.cell-existing { position: relative; }
.cell-existing::after { content: ''; position: absolute; inset: 0; background: rgba(245,158,11,.08); border-radius: 6px; pointer-events: none; }
.btn-simpan-ms { padding: 10px 28px; border-radius: 10px; font-size: 14px; font-weight: 600; border: none; background: linear-gradient(135deg, #16a34a, #22c55e); color: #fff; transition: all .25s; box-shadow: 0 4px 14px rgba(22,163,74,.3); display: inline-flex; align-items: center; gap: 8px; }
.btn-simpan-ms:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(22,163,74,.4); color: #fff; }
.btn-simpan-ms:disabled { opacity: .6; cursor: not-allowed; transform: none; box-shadow: none; }
.btn-kembali-ms { padding: 10px 20px; border-radius: 10px; font-size: 14px; font-weight: 500; border: 1.5px solid var(--ms-border); background: #fff; color: #475569; transition: all .25s; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; }
.btn-kembali-ms:hover { border-color: var(--ms-primary); color: var(--ms-primary); background: var(--ms-primary-light); }
.duplicate-alert { background: #fffbeb; border-left: 4px solid #f59e0b; border-radius: 12px; padding: 14px 20px; font-size: 13px; color: #92400e; margin-bottom: 20px; }
@media (max-width: 768px) { .stats-row { grid-template-columns: repeat(3, 1fr); } }
</style>

<div class="import-verify-page">
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon"><i class="fas fa-check-double"></i></div>
                <div>
                    <h4 class="mb-1 fw-bold" style="color: var(--ms-text); font-size: 20px;">Verifikasi Import Absensi</h4>
                    <span class="badge-modern badge-ta">
                        <i class="fas fa-graduation-cap me-1"></i>{{ $tahunAktif->tahun_ajaran }}
                    </span>
                    <span class="badge-modern badge-warning-custom ms-2">
                        <i class="fas fa-camera me-1"></i>{{ $kelas->nama_kelas }} &middot; {{ $monthStart->translatedFormat('F Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 12px; border: none; box-shadow: 0 2px 8px rgba(220,53,69,.1);">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="info-card-modern">
        <div><i class="fas fa-info-circle"></i> Periksa hasil OCR di bawah. Ubah status yang salah sebelum menyimpan. <strong>Sel kosong = "?"</strong> dan harus ditentukan. <strong>"." = Hadir (H)</strong>.</div>
    </div>

    @php
        $stats = $validation['stats'];
    @endphp

    <div class="stats-row">
        <div class="stat-badge stat-h"><span class="stat-num">{{ $stats['H'] }}</span>Hadir (H)</div>
        <div class="stat-badge stat-i"><span class="stat-num">{{ $stats['I'] }}</span>Izin (I)</div>
        <div class="stat-badge stat-s"><span class="stat-num">{{ $stats['S'] }}</span>Sakit (S)</div>
        <div class="stat-badge stat-a"><span class="stat-num">{{ $stats['A'] }}</span>Alpha (A)</div>
        <div class="stat-badge stat-q"><span class="stat-num" id="unknownCount">{{ $stats['?'] }}</span>Perlu Diperiksa</div>
        <div class="stat-badge stat-libur"><span class="stat-num">{{ $stats['libur_jumat'] }}</span>Libur Jumat</div>
        <div class="stat-badge stat-future"><span class="stat-num">{{ $stats['belum_terjadi'] }}</span>Belum Terjadi</div>
    </div>

    @if(count($existingDates) > 0)
    <div class="duplicate-alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>{{ count($existingDates) }}</strong> tanggal sudah memiliki data absensi. Pilih mode penanganan di bawah.
    </div>
    @endif

    <form action="{{ route('absensi.import.confirm') }}" method="POST" id="confirmForm">
        @csrf

        <div class="table-wrapper mb-3">
            <table class="table-verify" id="tableVerify">
                <thead>
                    <tr>
                        <th class="name-col">No / Nama Siswa</th>
                        @for($day = 1; $day <= $totalDays; $day++)
                            @php
                                $info = $daysInfo[$day];
                                $isDisabled = $info['is_friday'] || $info['is_future'];
                            @endphp
                            <th>
                                {{ $day }}
                                <span class="day-hdr">{{ $info['is_friday'] ? 'JUM' : substr($info['day_name'], 0, 3) }}</span>
                            </th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @foreach($matchedData as $idx => $row)
                    <tr>
                        <td class="name-col">
                            <span style="color:#94a3b8;font-weight:400;font-size:11px;">{{ $idx + 1 }}.</span>
                            {{ $row['nama'] }}
                            @if(!$row['student_id'])
                                <span class="badge bg-danger ms-1" style="font-size:9px;">?</span>
                            @endif
                        </td>
                        @for($day = 1; $day <= $totalDays; $day++)
                            @php
                                $info = $daysInfo[$day];
                                $status = $row['statuses'][$day] ?? '?';
                                $isDisabled = $info['is_friday'] || $info['is_future'];
                            @endphp
                            <td class="{{ $info['is_existing'] ? 'cell-existing' : '' }}">
                                @if($info['is_friday'])
                                    <span class="cell-libur">JUM</span>
                                @elseif($info['is_future'])
                                    <span class="cell-libur">-</span>
                                @else
                                    <select
                                        name="statuses[{{ $idx }}][{{ $day }}]"
                                        class="cell-select {{ $status === 'H' ? 'status-h' : ($status === 'I' ? 'status-i' : ($status === 'S' ? 'status-s' : ($status === 'A' ? 'status-a' : 'status-q'))) }}"
                                        data-day="{{ $day }}"
                                        data-student="{{ $idx }}"
                                        {{ $isDisabled ? 'disabled' : '' }}
                                    >
                                        <option value="?" {{ $status === '?' ? 'selected' : '' }}>?</option>
                                        <option value="H" {{ $status === 'H' ? 'selected' : '' }}>H</option>
                                        <option value="I" {{ $status === 'I' ? 'selected' : '' }}>I</option>
                                        <option value="S" {{ $status === 'S' ? 'selected' : '' }}>S</option>
                                        <option value="A" {{ $status === 'A' ? 'selected' : '' }}>A</option>
                                    </select>
                                @endif
                            </td>
                        @endfor
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if(count($existingDates) > 0)
        <div class="mb-3">
            <label class="form-label fw-bold" style="font-size:13px;">Mode Penanganan Data Duplikat:</label>
            <div class="d-flex gap-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="duplicate_mode" id="modeSkip" value="skip" checked>
                    <label class="form-check-label" for="modeSkip" style="font-size:13px;">
                        <strong>Lewati</strong> tanggal yang sudah ada
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="duplicate_mode" id="modeUpdate" value="update">
                    <label class="form-check-label" for="modeUpdate" style="font-size:13px;">
                        <strong>Perbarui</strong> data tanggal yang sudah ada
                    </label>
                </div>
            </div>
        </div>
        @else
            <input type="hidden" name="duplicate_mode" value="skip">
        @endif

        <div class="d-flex gap-2">
            <a href="{{ route('absensi.import') }}" class="btn-kembali-ms">
                <i class="fas fa-arrow-left"></i> Batal
            </a>
            <button type="submit" class="btn-simpan-ms" id="confirmBtn">
                <i class="fas fa-save"></i> Konfirmasi & Simpan
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selects = document.querySelectorAll('.cell-select');
    const unknownCountEl = document.getElementById('unknownCount');
    const confirmBtn = document.getElementById('confirmBtn');

    function updateStats() {
        let counts = { H: 0, I: 0, S: 0, A: 0, '?': 0 };
        selects.forEach(function(sel) {
            if (sel.disabled) return;
            const val = sel.value;
            if (counts[val] !== undefined) counts[val]++;
        });
        unknownCountEl.textContent = counts['?'];

        // Update select class based on value
        selects.forEach(function(sel) {
            if (sel.disabled) return;
            sel.className = 'cell-select';
            const val = sel.value;
            if (val === 'H') sel.classList.add('status-h');
            else if (val === 'I') sel.classList.add('status-i');
            else if (val === 'S') sel.classList.add('status-s');
            else if (val === 'A') sel.classList.add('status-a');
            else sel.classList.add('status-q');
        });
    }

    selects.forEach(function(sel) {
        sel.addEventListener('change', updateStats);
    });

    // Confirm before submit
    document.getElementById('confirmForm').addEventListener('submit', function(e) {
        // Count remaining '?'
        let unknownCount = 0;
        selects.forEach(function(sel) {
            if (!sel.disabled && sel.value === '?') unknownCount++;
        });

        if (unknownCount > 0) {
            e.preventDefault();
            alert('Masih ada ' + unknownCount + ' data yang berstatus "?". Semua data harus ditentukan (H/I/S/A) sebelum disimpan.');
            return;
        }

        confirmBtn.disabled = true;
        confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
    });
});
</script>
@endpush
@endsection
