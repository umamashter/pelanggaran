@extends('layouts.main')
@section('title','Verifikasi Import Absensi')
@section('content')
@include('component.admin.absensi-module')
<style>
    .page-title-content { display: none !important; }
    .abm-verify-hero { padding: 20px 26px; margin-bottom: 20px; border-radius: 20px; }
    .abm-verify-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 12px; margin-bottom: 20px; }
    .abm-verify-stat {
        background: var(--ab-card); border: 1px solid var(--ab-border); border-radius: 16px;
        padding: 14px 16px; text-align: center; box-shadow: var(--ab-shadow);
        transition: transform .25s cubic-bezier(.4,0,.2,1);
    }
    .abm-verify-stat:hover { transform: translateY(-3px); }
    .abm-verify-stat .n { font-size: 24px; font-weight: 800; line-height: 1.1; font-variant-numeric: tabular-nums; }
    .abm-verify-stat .l { font-size: 10.5px; font-weight: 700; letter-spacing: .3px; text-transform: uppercase; margin-top: 4px; }
    .abm-verify-stat.h { --t: var(--ab-green); }
    .abm-verify-stat.i { --t: var(--ab-sky); }
    .abm-verify-stat.s { --t: var(--ab-amber); }
    .abm-verify-stat.a { --t: var(--ab-red); }
    .abm-verify-stat.h .n, .abm-verify-stat.h .l { color: var(--ab-green); }
    .abm-verify-stat.i .n, .abm-verify-stat.i .l { color: var(--ab-sky); }
    .abm-verify-stat.s .n, .abm-verify-stat.s .l { color: var(--ab-amber); }
    .abm-verify-stat.a .n, .abm-verify-stat.a .l { color: var(--ab-red); }
    .abm-verify-stat.neutral .n { color: var(--ab-text); }
    .abm-verify-stat.neutral .l { color: var(--ab-text-2); }

    .source-badge { display: inline-block; font-size: 9px; font-weight: 800; padding: 1px 5px; border-radius: 4px; margin-left: 3px; vertical-align: middle; line-height: 1.2; letter-spacing: .3px; }
    .source-ai { background: var(--ab-violet-soft); color: var(--ab-violet); }
    .source-default { background: var(--ab-border-soft); color: var(--ab-text-3); }
    .source-system { background: var(--ab-sky-soft); color: var(--ab-sky); }
    .source-review { background: var(--ab-amber-soft); color: var(--ab-amber); }
    .source-manual { background: var(--ab-red-soft); color: var(--ab-red); }
    .warning-badge {
        display: inline-flex; align-items: center; justify-content: center; width: 16px; height: 16px;
        font-size: 9px; font-weight: 800; border-radius: 50%; margin-left: 2px;
        background: var(--ab-amber-soft); color: var(--ab-amber); border: 1px solid var(--ab-amber-border);
    }
    .abm-verify-card { background: var(--ab-card); border: 1px solid var(--ab-border); border-radius: 18px; box-shadow: var(--ab-shadow); overflow: hidden; }
    .table-wrapper { overflow-x: auto; }
    .table-verify { border-collapse: separate; border-spacing: 0; width: 100%; min-width: 800px; }
    .table-verify thead th {
        background: var(--ab-border-soft); color: var(--ab-text-2); font-weight: 700; font-size: 11px;
        text-transform: uppercase; letter-spacing: .4px; padding: 10px 8px;
        border-bottom: 2px solid var(--ab-border); text-align: center; white-space: nowrap;
        position: sticky; top: 0; z-index: 2;
    }
    .table-verify thead th.name-col { text-align: left; min-width: 170px; }
    .table-verify thead th .day-hdr { font-size: 10px; color: var(--ab-text-3); display: block; }
    .table-verify tbody td { padding: 6px 4px; font-size: 12px; color: var(--ab-text-2); border-bottom: 1px solid var(--ab-border-soft); text-align: center; vertical-align: middle; }
    .table-verify tbody td.name-col { text-align: left; font-weight: 600; white-space: nowrap; padding-left: 12px; color: var(--ab-text); }
    .table-verify tbody tr:hover td { background: var(--ab-primary-soft); }
    .table-verify tbody tr:last-child td { border-bottom: none; }
    .cell-select {
        width: 48px; height: 30px; border-radius: 8px; border: 1.5px solid var(--ab-border);
        font-size: 12px; font-weight: 800; text-align: center; padding: 0;
        background: var(--ab-card); color: var(--ab-text-2); cursor: pointer;
        transition: all .2s; -webkit-appearance: none; appearance: none;
    }
    .cell-select:focus { border-color: var(--ab-primary); box-shadow: 0 0 0 2px var(--ab-primary-soft); outline: none; }
    .cell-select.status-h { background: var(--ab-green-soft); color: var(--ab-green); border-color: var(--ab-green-border); }
    .cell-select.status-i { background: var(--ab-sky-soft); color: var(--ab-sky); border-color: var(--ab-sky-border); }
    .cell-select.status-s { background: var(--ab-amber-soft); color: var(--ab-amber); border-color: var(--ab-amber-border); }
    .cell-select.status-a { background: var(--ab-red-soft); color: var(--ab-red); border-color: var(--ab-red-border); }
    .cell-select:disabled { background: var(--ab-border-soft); color: var(--ab-text-3); cursor: not-allowed; opacity: .7; }
    .cell-libur { font-size: 10px; color: var(--ab-text-3); font-weight: 700; }
    .cell-existing { position: relative; }
    .cell-existing::after { content: ''; position: absolute; inset: 0; background: rgba(217,119,6,.08); border-radius: 8px; pointer-events: none; }
    .abm-radio-card {
        display: flex; align-items: center; gap: 10px; padding: 12px 16px;
        border: 1.5px solid var(--ab-border); border-radius: 12px; cursor: pointer;
        transition: all .2s;
    }
    .abm-radio-card:hover { border-color: var(--ab-primary-border); }
    .abm-radio-card input:checked ~ * { }
    .abm-radio-card:has(input:checked) { border-color: var(--ab-primary); background: var(--ab-primary-soft); box-shadow: 0 0 0 3px var(--ab-primary-soft); }
    .abm-radio-card input { accent-color: var(--ab-primary); }
    .abm-verify-footer {
        position: sticky; bottom: 14px; z-index: 50;
        display: flex; align-items: center; gap: 16px; flex-wrap: wrap;
        background: var(--ab-card); border: 1px solid var(--ab-border); border-radius: 18px;
        padding: 12px 18px; box-shadow: 0 18px 44px -14px rgba(15,23,42,.28);
    }
    html.dark-mode .abm-verify-footer { background: rgba(13,47,56,.92); }
</style>

<div class="abs-mod import-verify-page" style="margin-top:0;">
    {{-- HERO --}}
    <div class="abm-hero abm-verify-hero">
        <div class="abm-hero-grid"></div>
        <div class="abm-hero-row">
            <div class="abm-hero-left">
                <div class="d-flex align-items-center gap-3">
                    <div class="abm-hero-icon"><i class="fas fa-check-double"></i></div>
                    <div>
                        <h3>Verifikasi Import Absensi</h3>
                        <p class="abm-hero-sub">Periksa hasil pembacaan sebelum disimpan.</p>
                    </div>
                </div>
                <div class="abm-hero-badges">
                    <span class="abm-hero-badge"><i class="fas fa-graduation-cap"></i> {{ $tahunAktif->tahun_ajaran }}</span>
                    <span class="abm-hero-badge"><i class="fas fa-chalkboard"></i> {{ $kelas->nama_kelas }}</span>
                    <span class="abm-hero-badge"><i class="fas fa-calendar"></i> {{ $monthStart->translatedFormat('F Y') }}</span>
                </div>
            </div>
            <div class="abm-hero-actions">
                <a href="{{ route('absensi.import') }}" class="abm-btn abm-btn--ghost"><i class="fas fa-arrow-left"></i> Batal</a>
            </div>
        </div>
    </div>

    @if(session('error'))
    <div class="abm-alert abm-alert--danger">
        <i class="fas fa-exclamation-circle"></i>
        <div style="flex:1;">{{ session('error') }}</div>
    </div>
    @endif

    @if($parseSource === 'fallback')
    <div class="abm-alert abm-alert--info">
        <i class="fas fa-info-circle"></i>
        <div><strong>AI Parser tidak tersedia.</strong> Data diproses menggunakan parser sederhana dan <strong>wajib diverifikasi</strong> oleh operator.</div>
    </div>
    @endif

    @if($aiWarning)
    <div class="abm-alert abm-alert--warn">
        <i class="fas fa-exclamation-triangle"></i>
        <div>Foto memiliki kualitas kurang optimal. AI tetap akan mencoba membaca gambar. Periksa hasil import sebelum menyimpan.<br>{{ $aiWarning }}</div>
    </div>
    @endif

    @if(!empty($parserMeta['document_classification']['status']) || !empty($parserMeta['decision']['status']))
    <div class="abm-alert abm-alert--info">
        <i class="fas fa-shield-alt"></i>
        <div>
            <strong>Gatekeeper:</strong>
            Dokumen {{ $parserMeta['document_classification']['status'] ?? 'SKIPPED' }}
            @if(!empty($parserMeta['decision']['status'])) · Decision {{ $parserMeta['decision']['status'] }} @endif
        </div>
    </div>
    @endif

    @if($ocrRawText || $aiJson || !empty($reviewItems) || !empty($unmatchedList))
    <div class="abm-card" style="padding:16px 20px;margin-bottom:16px;">
        <div class="abm-section-title mb-3" style="font-size:13.5px;"><i class="fas fa-search-plus"></i> Detail Hasil OCR</div>
        <div class="d-flex flex-wrap gap-2">
            @if($ocrRawText)
            <button class="abm-btn abm-btn--soft abm-btn--xs" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOcrRaw">
                <i class="fas fa-file-alt"></i> Lihat OCR Mentah
            </button>
            <div class="collapse" style="width:100%;" id="collapseOcrRaw">
                <pre style="background:#0f172a;color:#e2e8f0;padding:14px;border-radius:10px;font-size:11px;max-height:250px;overflow-y:auto;white-space:pre-wrap;word-break:break-all;margin-top:10px;">{{ $ocrRawText }}</pre>
            </div>
            @endif
            @if($aiJson)
            <button class="abm-btn abm-btn--soft abm-btn--xs" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAiJson">
                <i class="fas fa-code"></i> Lihat JSON AI
            </button>
            <div class="collapse" style="width:100%;" id="collapseAiJson">
                <pre style="background:#1e1b4b;color:#c4b5fd;padding:14px;border-radius:10px;font-size:11px;max-height:300px;overflow-y:auto;white-space:pre-wrap;word-break:break-all;margin-top:10px;">{{ json_encode($aiJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
            @endif
            @if(!empty($reviewItems))
            <button class="abm-btn abm-btn--xs abm-btn--outline" type="button" data-bs-toggle="collapse" data-bs-target="#collapseReview" style="border-color:var(--ab-amber-border);color:var(--ab-amber);">
                <i class="fas fa-exclamation-triangle"></i> Data REVIEW ({{ count($reviewItems) }})
            </button>
            <div class="collapse" style="width:100%;" id="collapseReview">
                <div style="background:var(--ab-amber-soft);padding:12px;border-radius:10px;font-size:12px;margin-top:10px;color:#92400e;">
                    @foreach($reviewItems as $ri)
                    <div class="mb-1">
                        <strong>{{ $ri['nama'] }}</strong> ({{ $ri['nisn'] }})
                        @foreach($ri['warnings'] as $w)
                            <br><span style="font-size:11px;"><i class="fas fa-info-circle me-1"></i>{{ $w }}</span>
                        @endforeach
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            @if(!empty($unmatchedList))
            <button class="abm-btn abm-btn--xs abm-btn--outline" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUnmatched" style="border-color:var(--ab-red-border);color:var(--ab-red);">
                <i class="fas fa-user-slash"></i> Tidak Cocok ({{ count($unmatchedList) }})
            </button>
            <div class="collapse" style="width:100%;" id="collapseUnmatched">
                <div style="background:var(--ab-red-soft);padding:12px;border-radius:10px;font-size:12px;margin-top:10px;color:#991b1b;">
                    @foreach($unmatchedList as $um)
                    <div class="mb-1">
                        <strong>{{ $um['nama'] }}</strong> (NISN: {{ $um['nisn'] }})
                        <span style="font-size:11px;">— {{ $um['warnings'][0] ?? 'Tidak ditemukan' }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

    @php
        $stats = $validation['stats'];
        $mappedCount = 0;
        $unmappedCount = 0;
        $reviewCount = 0;
        foreach ($matchedData as $row) {
            if ($row['student_id'] !== null) $mappedCount++;
            else $unmappedCount++;
            if (!empty($row['warnings'])) $reviewCount++;
        }
    @endphp

    @if($unmappedCount > 0)
    <div class="abm-alert abm-alert--danger">
        <i class="fas fa-exclamation-triangle"></i>
        <div><strong>{{ $unmappedCount }} siswa</strong> tidak ditemukan di database. Baris ini tetap ditampilkan namun tidak akan disimpan ke database.</div>
    </div>
    @endif

    @if($reviewCount > 0 && $mappedCount > 0)
    <div class="abm-alert abm-alert--warn">
        <i class="fas fa-exclamation-circle"></i>
        <div><strong>{{ $reviewCount }} siswa</strong> memiliki catatan perlu diperiksa (pencocokan parsial/fuzzy).</div>
    </div>
    @endif

    {{-- STATS --}}
    <div class="abm-verify-stats">
        <div class="abm-verify-stat neutral"><div class="n">{{ $mappedCount }}</div><div class="l">Siswa</div></div>
        <div class="abm-verify-stat h"><div class="n">{{ $stats['H'] }}</div><div class="l">Hadir (H)</div></div>
        <div class="abm-verify-stat i"><div class="n">{{ $stats['I'] }}</div><div class="l">Izin (I)</div></div>
        <div class="abm-verify-stat s"><div class="n">{{ $stats['S'] }}</div><div class="l">Sakit (S)</div></div>
        <div class="abm-verify-stat a"><div class="n">{{ $stats['A'] }}</div><div class="l">Alpha (A)</div></div>
        <div class="abm-verify-stat neutral"><div class="n">{{ $stats['source_ai'] ?? 0 }}</div><div class="l">Dari AI</div></div>
        <div class="abm-verify-stat neutral"><div class="n">{{ $stats['source_review'] ?? 0 }}</div><div class="l">Perlu Review</div></div>
        <div class="abm-verify-stat neutral"><div class="n">{{ $stats['UNKNOWN'] ?? 0 }}</div><div class="l">Unknown</div></div>
        <div class="abm-verify-stat neutral"><div class="n">{{ $stats['libur_jumat'] }}</div><div class="l">Libur Jumat</div></div>
    </div>

    @if(!empty($parserMeta))
    <div class="abm-alert abm-alert--info" style="margin-bottom:16px;">
        <i class="fas fa-microchip"></i>
        <div>
            <strong>Metadata Parser</strong><br>
            Metode grid: <b>{{ $parserMeta['grid_method'] ?? '-' }}</b> ·
            Grid: <b>{{ $parserMeta['grid_rows'] ?? 0 }}</b> baris / <b>{{ $parserMeta['grid_cols'] ?? 0 }}</b> kolom ·
            Kolom tanggal: <b>{{ $parserMeta['date_cols'] ?? 0 }}</b>
            @if(!empty($parserMeta['preprocess_steps']))
                <br>Preprocess: {{ implode(', ', $parserMeta['preprocess_steps']) }}
            @endif
        </div>
    </div>
    @endif

    @if(count($existingDates) > 0)
    <div class="abm-alert abm-alert--warn">
        <i class="fas fa-exclamation-triangle"></i>
        <div><strong>{{ count($existingDates) }}</strong> tanggal sudah memiliki data absensi. Pilih mode penanganan di bawah.</div>
    </div>
    @endif

    <form action="{{ route('absensi.import.confirm') }}" method="POST" id="confirmForm">
        @csrf

        <div class="abm-hintbox mb-3">
            <i class="fas fa-info-circle"></i>
            <span>Periksa hasil di bawah. Ubah status yang salah sebelum menyimpan. <strong>Sumber:</strong>
                <span class="source-badge source-ai">AI</span> dari AI,
                <span class="source-badge source-review">REVIEW</span> belum yakin dan wajib dicek,
                <span class="source-badge source-system">SYSTEM</span> Jumat/Belum Terjadi,
                <span class="source-badge source-manual">MANUAL</span> koreksi operator. Klik status untuk mengubah.</span>
        </div>

        <div class="abm-verify-card mb-3">
            <div style="padding:14px 16px 0;display:flex;justify-content:flex-end;">
                <label style="display:inline-flex;align-items:center;gap:8px;font-size:12px;color:var(--ab-text-2);font-weight:600;">
                    <input type="checkbox" id="toggleOnlyIssues">
                    Hanya tampilkan baris bermasalah
                </label>
            </div>
            <div class="table-wrapper">
                <table class="table-verify" id="tableVerify">
                    <thead>
                        <tr>
                            <th class="name-col">No / Nama Siswa</th>
                            <th style="min-width:90px;">NISN</th>
                            <th style="min-width:110px;">Review</th>
                            @foreach($actualDates as $day)
                                @php $info = $daysInfo[$day] ?? null; @endphp
                                <th>{{ $day }}<span class="day-hdr">{{ ($info['is_friday'] ?? false) ? 'JUM' : substr($info['day_name'] ?? '', 0, 3) }}</span></th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($matchedData as $idx => $row)
                        @php
                            $rowReviewCount = 0;
                            foreach($actualDates as $reviewDay) {
                                $reviewStatus = $row['statuses'][$reviewDay] ?? 'UNKNOWN';
                                $reviewSource = $row['sources'][$reviewDay] ?? 'REVIEW';
                                if ($reviewStatus === 'UNKNOWN' || $reviewSource === 'REVIEW') {
                                    $rowReviewCount++;
                                }
                            }
                        @endphp
                        <tr data-review-count="{{ $rowReviewCount }}">
                            <td class="name-col">
                                <span style="color:var(--ab-text-3);font-weight:400;font-size:11px;">{{ $idx + 1 }}.</span>
                                {{ $row['nama'] }}
                                @if(!$row['student_id'])
                                    <span class="warning-badge" style="background:var(--ab-red-soft);color:var(--ab-red);border-color:var(--ab-red-border);" title="Tidak ditemukan di database">?</span>
                                @endif
                                @if(!empty($row['warnings']))
                                    @foreach($row['warnings'] as $w)
                                        <span class="warning-badge" title="{{ $w }}">!</span>
                                    @endforeach
                                @endif
                                <span class="source-badge source-{{ strtolower($row['match_type'] === 'UNMATCHED_DB' ? 'DEFAULT' : ($row['match_type'] === 'none' ? 'MANUAL' : 'AI')) }}">{{ $row['match_type'] }}</span>
                            </td>
                            <td style="font-size:11px;color:var(--ab-text-3);white-space:nowrap;">{{ $row['nisn'] ?? '-' }}</td>
                            <td>
                                @if($rowReviewCount > 0)
                                    <span class="warning-badge" title="{{ $rowReviewCount }} sel perlu ditinjau">{{ $rowReviewCount }}</span>
                                @else
                                    <span class="source-badge source-system">OK</span>
                                @endif
                            </td>
                            @foreach($actualDates as $day)
                                @php
                                    $info = $daysInfo[$day] ?? null;
                                    $status = $row['statuses'][$day] ?? 'UNKNOWN';
                                    $source = $row['sources'][$day] ?? 'REVIEW';
                                    $isDisabled = ($info['is_friday'] ?? false) || ($info['is_future'] ?? false);
                                @endphp
                                <td class="{{ ($info['is_existing'] ?? false) ? 'cell-existing' : '' }}">
                                    @if($info['is_friday'] ?? false)
                                        <span class="cell-libur">LIBUR</span>
                                        <span class="source-badge source-system">SYSTEM</span>
                                    @elseif($info['is_future'] ?? false)
                                        <span class="cell-libur">-</span>
                                    @else
                                        <div class="d-inline-flex align-items-center">
                                            <select
                                                name="statuses[{{ $idx }}][{{ $day }}]"
                                                class="cell-select {{ $status === 'H' ? 'status-h' : ($status === 'I' ? 'status-i' : ($status === 'S' ? 'status-s' : ($status === 'A' ? 'status-a' : ''))) }}"
                                                data-day="{{ $day }}"
                                                data-student="{{ $idx }}"
                                                data-source="{{ $source }}"
                                                data-initial-review="{{ $source === 'REVIEW' || $status === 'UNKNOWN' ? '1' : '0' }}"
                                                {{ $isDisabled ? 'disabled' : '' }}
                                            >
                                                <option value="UNKNOWN" {{ $status === 'UNKNOWN' ? 'selected' : '' }}>?</option>
                                                <option value="H" {{ $status === 'H' ? 'selected' : '' }}>H</option>
                                                <option value="I" {{ $status === 'I' ? 'selected' : '' }}>I</option>
                                                <option value="S" {{ $status === 'S' ? 'selected' : '' }}>S</option>
                                                <option value="A" {{ $status === 'A' ? 'selected' : '' }}>A</option>
                                            </select>
                                            <span class="source-badge source-{{ strtolower($source) }}">{{ $source }}</span>
                                            @if(($row['review_reasons'][$day] ?? null) && $source === 'REVIEW')
                                                <span class="warning-badge" title="{{ $row['review_reasons'][$day] }}">!</span>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if(count($existingDates) > 0)
        <div class="mb-3">
            <label class="abm-field-label mb-2" style="display:block;"><i class="fas fa-copy"></i>Mode Penanganan Data Duplikat</label>
            <div class="d-flex gap-3 flex-wrap">
                <label class="abm-radio-card" for="modeSkip">
                    <input type="radio" name="duplicate_mode" id="modeSkip" value="skip" checked>
                    <span style="font-size:13px;font-weight:600;color:var(--ab-text-2);"><strong>Lewati</strong> tanggal yang sudah ada</span>
                </label>
                <label class="abm-radio-card" for="modeUpdate">
                    <input type="radio" name="duplicate_mode" id="modeUpdate" value="update">
                    <span style="font-size:13px;font-weight:600;color:var(--ab-text-2);"><strong>Perbarui</strong> data tanggal yang sudah ada</span>
                </label>
            </div>
        </div>
        @else
            <input type="hidden" name="duplicate_mode" value="skip">
        @endif

        <div class="abm-verify-footer">
            <div class="abm-actionbar-count">Total <b>{{ $mappedCount }}</b> siswa terdeteksi · <b id="pendingReviewCount">0</b> sel perlu review</div>
            <div style="margin-left:auto;display:flex;gap:8px;flex-wrap:wrap;">
                <a href="{{ route('absensi.import') }}" class="abm-btn abm-btn--soft"><i class="fas fa-arrow-left"></i> Batal</a>
                <button type="submit" class="abm-btn abm-btn--solid" id="confirmBtn" disabled>
                    <i class="fas fa-save"></i> Selesaikan Review Dulu
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var selects = document.querySelectorAll('.cell-select');
    var confirmBtn = document.getElementById('confirmBtn');
    var pendingReviewCount = document.getElementById('pendingReviewCount');
    var toggleOnlyIssues = document.getElementById('toggleOnlyIssues');

    function updateStats() {
        var pending = 0;

        selects.forEach(function(sel) {
            if (sel.disabled) return;
            sel.className = 'cell-select';
            var val = sel.value;
            var source = sel.getAttribute('data-source');

            if (val === 'H') sel.classList.add('status-h');
            else if (val === 'I') sel.classList.add('status-i');
            else if (val === 'S') sel.classList.add('status-s');
            else if (val === 'A') sel.classList.add('status-a');

            if (val === 'UNKNOWN' || source === 'REVIEW') {
                pending++;
            }
        });

        document.querySelectorAll('#tableVerify tbody tr').forEach(function(row) {
            var rowPending = parseInt(row.getAttribute('data-review-count') || '0', 10);
            row.style.display = toggleOnlyIssues && toggleOnlyIssues.checked && rowPending === 0 ? 'none' : '';
        });

        pendingReviewCount.textContent = pending;
        confirmBtn.disabled = pending > 0;
        confirmBtn.innerHTML = pending > 0
            ? '<i class="fas fa-exclamation-circle"></i> Selesaikan Review Dulu'
            : '<i class="fas fa-save"></i> Konfirmasi & Simpan';
    }

    selects.forEach(function(sel) {
        sel.addEventListener('change', function() {
            if (this.value === 'LIBUR') return;
            this.setAttribute('data-source', this.value === 'UNKNOWN' ? 'REVIEW' : 'MANUAL');
            var srcBadge = this.parentElement.querySelector('.source-badge');
            if (srcBadge) {
                srcBadge.textContent = this.value === 'UNKNOWN' ? 'REVIEW' : 'MANUAL';
                srcBadge.className = 'source-badge ' + (this.value === 'UNKNOWN' ? 'source-review' : 'source-manual');
            }

            var row = this.closest('tr');
            if (row) {
                var rowPending = 0;
                row.querySelectorAll('.cell-select').forEach(function(rowSel) {
                    if (rowSel.disabled) return;
                    if (rowSel.value === 'UNKNOWN' || rowSel.getAttribute('data-source') === 'REVIEW') {
                        rowPending++;
                    }
                });
                row.setAttribute('data-review-count', rowPending);
                var badge = row.querySelector('td:nth-child(3) .warning-badge, td:nth-child(3) .source-badge');
                if (badge) {
                    if (rowPending > 0) {
                        badge.textContent = rowPending;
                        badge.className = 'warning-badge';
                        badge.setAttribute('title', rowPending + ' sel perlu ditinjau');
                    } else {
                        badge.textContent = 'OK';
                        badge.className = 'source-badge source-system';
                        badge.removeAttribute('title');
                    }
                }
            }
            updateStats();
        });
    });

    if (toggleOnlyIssues) {
        toggleOnlyIssues.addEventListener('change', updateStats);
    }

    document.getElementById('confirmForm').addEventListener('submit', function(e) {
        if (confirmBtn.disabled) {
            e.preventDefault();
            return;
        }
        confirmBtn.disabled = true;
        confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
    });

    updateStats();
});
</script>
@endpush
