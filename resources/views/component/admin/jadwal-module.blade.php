{{-- ============================================================
     JADWAL MODULE — Design System bersama Modul Jadwal Pelajaran
     Wajib di-include di setiap halaman jadwal (role admin).
     Skema: biru (primary), rounded 14px, soft shadow, glass ringan,
     gradient halus, dark-mode ready via `html.dark-mode`.
     ============================================================ --}}

@php
if (!function_exists('jd_mapel_color_idx')) {
    function jd_mapel_color_idx($nama)
    {
        $nama = strtolower(trim((string) $nama));
        $norm = preg_replace('/[^a-z0-9]/', '', $nama) ?? '';
        $map = [
            'sejarah' => 9,
            'indonesia' => 2,
            'matematika' => 1,
            'kewarganegaraan' => 5,
            'qur`an' => 7,
            'quran' => 7,
            'hadis' => 7,
            'hadits' => 7,
            'aqidah' => 7,
            'akidah' => 7,
            'inggris' => 10,
            'penjaskes' => 11,
            'olahraga' => 11,
            'fiqih' => 8,
            'fikih' => 8,
            'fiqh' => 8,
            'bahasaarab' => 6,
            'alam' => 3,
            'sosial' => 4,
            'agama' => 0,
            'pkn' => 5,
            'ppkn' => 5,
            'ski' => 9,
            'mtk' => 1,
            'bindo' => 2,
            'pjok' => 11,
            'pai' => 0,
            'arab' => 6,
        ];
        foreach ($map as $key => $idx) {
            if (strpos($norm, $key) !== false) {
                return $idx;
            }
        }
        $h = 0;
        $len = strlen($norm);
        for ($i = 0; $i < $len; $i++) {
            $h = ($h * 31 + ord($norm[$i])) & 0x7fffffff;
        }
        return $h % 12;
    }
}
@endphp

<style>
.jd-mod {
    --jd-primary: #2563eb;
    --jd-primary-2: #3b82f6;
    --jd-primary-3: #60a5fa;
    --jd-primary-dark: #1d4ed8;
    --jd-primary-soft: rgba(37, 99, 235, .10);
    --jd-primary-border: rgba(37, 99, 235, .28);
    --jd-grad: linear-gradient(135deg, #2563eb 0%, #3b82f6 55%, #60a5fa 100%);
    --jd-grad-soft: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    --jd-grad-rad: radial-gradient(120% 120% at 8% 0%, rgba(255,255,255,.18) 0%, rgba(255,255,255,0) 42%), linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    --jd-bg: #f1f5f9;
    --jd-card: #ffffff;
    --jd-border: #e2e8f0;
    --jd-border-soft: #f1f5f9;
    --jd-text: #0f172a;
    --jd-text-2: #475569;
    --jd-text-3: #94a3b8;
    --jd-shadow: 0 1px 2px rgba(15,23,42,.05), 0 4px 16px -4px rgba(15,23,42,.10);
    --jd-shadow-lg: 0 24px 60px -12px rgba(15,23,42,.22);
    --jd-radius: 14px;
    --jd-radius-lg: 20px;
    --jd-green: #16a34a; --jd-green-soft: rgba(22,163,74,.10); --jd-green-border: rgba(22,163,74,.30);
    --jd-amber: #d97706; --jd-amber-soft: rgba(217,119,6,.10); --jd-amber-border: rgba(217,119,6,.30);
    --jd-red: #dc2626; --jd-red-soft: rgba(220,38,38,.10); --jd-red-border: rgba(220,38,38,.30);
    --jd-sky: #0284c7; --jd-sky-soft: rgba(2,132,199,.10); --jd-sky-border: rgba(2,132,199,.30);
    --jd-violet: #7c3aed; --jd-violet-soft: rgba(124,58,237,.10); --jd-violet-border: rgba(124,58,237,.30);
    --jd-rose: #db2777; --jd-rose-soft: rgba(219,39,119,.10); --jd-rose-border: rgba(219,39,119,.30);

    font-family: 'Poppins', 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
    color: var(--jd-text);
}
html.dark-mode .jd-mod {
    --jd-bg: rgba(148,163,184,.05);
    --jd-card: rgba(255,255,255,.05);
    --jd-border: rgba(255,255,255,.12);
    --jd-border-soft: rgba(255,255,255,.06);
    --jd-text: #f1f5f9;
    --jd-text-2: #cbd5e1;
    --jd-text-3: #94a3b8;
    --jd-primary-soft: rgba(96,165,250,.16);
    --jd-primary-border: rgba(96,165,250,.45);
    --jd-shadow: 0 4px 18px -4px rgba(0,0,0,.45);
    --jd-shadow-lg: 0 30px 70px -12px rgba(0,0,0,.6);
    --jd-grad-soft: linear-gradient(135deg, rgba(37,99,235,.16) 0%, rgba(37,99,235,.06) 100%);
}
.jd-mod a { text-decoration: none !important; }
.jd-mod [data-bs-toggle="modal"], .jd-mod .jd-clickable, .jd-mod .jd-btn, .jd-mod .jd-tab, .jd-mod .jd-tab-kelas { cursor: pointer; }
.jd-mod :focus-visible { outline: 2px solid var(--jd-primary); outline-offset: 2px; border-radius: 8px; }

/* ---------- Buttons ---------- */
.jd-btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 7px;
    border: none; border-radius: 12px; padding: 10px 18px;
    font-size: 13px; font-weight: 600; font-family: inherit;
    background: var(--jd-card); color: var(--jd-text); border: 1px solid var(--jd-border);
    transition: all .2s ease; position: relative; overflow: hidden; white-space: nowrap;
}
.jd-btn i { font-size: 14px; }
.jd-btn:hover { transform: translateY(-1px); box-shadow: var(--jd-shadow); }
.jd-btn:active { transform: translateY(0) scale(.98); }
.jd-btn:disabled { opacity: .55; cursor: not-allowed; transform: none; box-shadow: none; }
.jd-btn--solid { background: var(--jd-grad); color: #fff; border-color: transparent; box-shadow: 0 6px 18px -6px rgba(37,99,235,.55); }
.jd-btn--solid:hover { box-shadow: 0 10px 24px -6px rgba(37,99,235,.6); color: #fff; }
.jd-btn--soft { background: var(--jd-primary-soft); color: var(--jd-primary); border-color: var(--jd-primary-border); }
.jd-btn--soft:hover { background: rgba(37,99,235,.16); color: var(--jd-primary); }
.jd-btn--ghost { background: transparent; border-color: transparent; color: var(--jd-text-2); }
.jd-btn--ghost:hover { background: var(--jd-bg); color: var(--jd-text); }
.jd-btn--light { background: rgba(255,255,255,.16); color: #fff; border-color: rgba(255,255,255,.28); backdrop-filter: blur(6px); }
.jd-btn--light:hover { background: rgba(255,255,255,.26); color: #fff; }
.jd-btn--outline { background: transparent; color: var(--jd-primary); border-color: var(--jd-primary-border); }
.jd-btn--outline:hover { background: var(--jd-primary-soft); color: var(--jd-primary); }
.jd-btn--danger { background: var(--jd-red); color: #fff; border-color: transparent; box-shadow: 0 6px 18px -6px rgba(220,38,38,.5); }
.jd-btn--danger:hover { background: #b91c1c; color: #fff; }
.jd-btn--success { background: var(--jd-green); color: #fff; border-color: transparent; box-shadow: 0 6px 18px -6px rgba(22,163,74,.5); }
.jd-btn--success:hover { background: #15803d; color: #fff; }
.jd-btn--sm { padding: 7px 13px; font-size: 12px; border-radius: 10px; }
.jd-btn--sm i { font-size: 12px; }
.jd-btn--xs { padding: 5px 10px; font-size: 11.5px; border-radius: 9px; gap: 5px; }
.jd-btn--xs i { font-size: 11px; }
.jd-btn--block { width: 100%; }

/* Ripple */
.jd-ripple { position: absolute; border-radius: 50%; background: rgba(255,255,255,.5); transform: scale(0); animation: jdRipple .55s ease-out forwards; pointer-events: none; }
.jd-btn--soft .jd-ripple, .jd-btn--outline .jd-ripple { background: rgba(37,99,235,.25); }
@keyframes jdRipple { to { transform: scale(3); opacity: 0; } }

/* ---------- Chip / Badge ---------- */
.jd-chip {
    display: inline-flex; align-items: center; gap: 6px;
    border-radius: 999px; padding: 5px 12px; font-size: 11.5px; font-weight: 600;
    background: var(--jd-bg); color: var(--jd-text-2); border: 1px solid var(--jd-border);
}
.jd-chip i { font-size: 12px; }
.jd-chip--blue { background: var(--jd-primary-soft); color: var(--jd-primary); border-color: var(--jd-primary-border); }
.jd-chip--green { background: var(--jd-green-soft); color: var(--jd-green); border-color: var(--jd-green-border); }
.jd-chip--amber { background: var(--jd-amber-soft); color: var(--jd-amber); border-color: var(--jd-amber-border); }
.jd-chip--red { background: var(--jd-red-soft); color: var(--jd-red); border-color: var(--jd-red-border); }
.jd-chip--violet { background: var(--jd-violet-soft); color: var(--jd-violet); border-color: var(--jd-violet-border); }
.jd-chip--muted { background: transparent; color: var(--jd-text-3); border-color: transparent; }
.jd-chip--dot { padding-left: 9px; }
.jd-chip--dot::before { content: ""; width: 7px; height: 7px; border-radius: 50%; background: currentColor; }
html.dark-mode .jd-chip { background: rgba(255,255,255,.06); color: var(--jd-text-2); border-color: var(--jd-border); }

/* ---------- Hero ---------- */
.jd-hero {
    position: relative; overflow: hidden;
    background: var(--jd-grad-rad); color: #fff;
    border-radius: var(--jd-radius-lg); padding: 26px 28px; margin-bottom: 20px;
    box-shadow: 0 20px 40px -16px rgba(29,78,216,.5);
}
.jd-hero::before { content: ""; position: absolute; inset: 0; pointer-events: none;
    background-image: radial-gradient(rgba(255,255,255,.14) 1px, transparent 1px); background-size: 22px 22px; opacity: .4; }
.jd-hero::after { content: ""; position: absolute; right: -90px; top: -110px; width: 300px; height: 300px; border-radius: 50%;
    background: rgba(255,255,255,.08); pointer-events: none; }
.jd-hero-grid { position: relative; display: flex; flex-wrap: wrap; gap: 20px; align-items: center; justify-content: space-between; }
.jd-hero-left { display: flex; gap: 16px; align-items: flex-start; min-width: 0; }
.jd-hero-icon { flex-shrink: 0; width: 56px; height: 56px; border-radius: 16px; display: inline-flex; align-items: center; justify-content: center;
    background: rgba(255,255,255,.18); border: 1px solid rgba(255,255,255,.25); backdrop-filter: blur(10px); font-size: 24px; color: #fff; }
.jd-hero-title { font-size: 22px; font-weight: 700; letter-spacing: -.3px; margin: 0 0 4px; color: #fff; }
.jd-hero-sub { font-size: 12.5px; opacity: .85; margin: 0; line-height: 1.5; max-width: 560px; }
.jd-hero-badges { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 12px; }
.jd-hero-badge { display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; border-radius: 999px; font-size: 11.5px; font-weight: 600;
    background: rgba(255,255,255,.16); border: 1px solid rgba(255,255,255,.24); backdrop-filter: blur(8px); color: #fff; }
.jd-hero-badge i { font-size: 12px; opacity: .9; }
.jd-hero-badge--ok { background: rgba(22,163,74,.35); border-color: rgba(22,163,74,.5); }
.jd-hero-badge--warn { background: rgba(217,119,6,.4); border-color: rgba(217,119,6,.55); }
.jd-hero-right { display: flex; flex-wrap: wrap; gap: 8px; align-items: center; }

/* ---------- KPI ---------- */
.jd-kpi-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 20px; }
.jd-kpi {
    position: relative; overflow: hidden;
    background: var(--jd-card); border: 1px solid var(--jd-border); border-radius: var(--jd-radius);
    padding: 18px 20px; box-shadow: var(--jd-shadow);
    display: flex; align-items: center; gap: 14px;
    transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease;
}
.jd-kpi:hover { transform: translateY(-3px); box-shadow: var(--jd-shadow-lg); border-color: var(--jd-primary-border); }
.jd-kpi-icon { flex-shrink: 0; width: 46px; height: 46px; border-radius: 13px; display: inline-flex; align-items: center; justify-content: center; font-size: 19px; }
.jd-kpi-icon.blue { background: var(--jd-primary-soft); color: var(--jd-primary); }
.jd-kpi-icon.green { background: var(--jd-green-soft); color: var(--jd-green); }
.jd-kpi-icon.amber { background: var(--jd-amber-soft); color: var(--jd-amber); }
.jd-kpi-icon.violet { background: var(--jd-violet-soft); color: var(--jd-violet); }
.jd-kpi-icon.rose { background: var(--jd-rose-soft); color: var(--jd-rose); }
.jd-kpi-icon.sky { background: var(--jd-sky-soft); color: var(--jd-sky); }
.jd-kpi-num { font-size: 24px; font-weight: 700; letter-spacing: -.5px; line-height: 1.1; color: var(--jd-text); font-variant-numeric: tabular-nums; }
.jd-kpi-label { font-size: 11.5px; font-weight: 600; color: var(--jd-text-3); text-transform: uppercase; letter-spacing: .4px; margin-top: 2px; }
.jd-kpi-sub { font-size: 11px; color: var(--jd-text-3); margin-top: 2px; }
.jd-kpi-watermark { position: absolute; right: -18px; bottom: -22px; font-size: 90px; opacity: .035; pointer-events: none; line-height: 1; }

/* ---------- Toolbar (sticky filter) ---------- */
.jd-toolbar {
    position: sticky; top: 78px; z-index: 940;
    display: flex; flex-wrap: wrap; align-items: flex-end; gap: 10px;
    background: var(--jd-card); border: 1px solid var(--jd-border); border-radius: var(--jd-radius);
    padding: 14px 16px; margin-bottom: 18px; box-shadow: var(--jd-shadow);
    backdrop-filter: blur(10px);
}
.jd-toolbar::before { content: ""; position: absolute; top: 0; left: 16px; right: 16px; height: 1px;
    background: linear-gradient(90deg, transparent, rgba(37,99,235,.25), transparent); opacity: 0; transition: opacity .2s; }
.jd-toolbar.is-stuck::before { opacity: 1; }
.jd-filter { display: flex; flex-direction: column; gap: 5px; min-width: 140px; }
.jd-filter label { font-size: 10.5px; font-weight: 700; color: var(--jd-text-3); text-transform: uppercase; letter-spacing: .5px; }
.jd-select, .jd-control {
    height: 40px; border-radius: 11px; border: 1.5px solid var(--jd-border); background: var(--jd-card);
    color: var(--jd-text); font-size: 13px; font-family: inherit; padding: 0 12px; width: 100%;
    transition: border-color .2s, box-shadow .2s;
}
.jd-select:focus, .jd-control:focus { outline: none; border-color: var(--jd-primary); box-shadow: 0 0 0 3px var(--jd-primary-soft); }
.jd-search { position: relative; min-width: 180px; flex: 1 1 180px; }
.jd-search i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--jd-text-3); font-size: 14px; pointer-events: none; }
.jd-search .jd-control { padding-left: 36px; }
.jd-toolbar-actions { display: flex; gap: 8px; align-items: center; margin-left: auto; }

/* ---------- Tabs ---------- */
.jd-tabs { display: inline-flex; flex-wrap: wrap; gap: 8px; padding: 6px; border-radius: 14px;
    background: var(--jd-bg); border: 1px solid var(--jd-border); margin-bottom: 16px; position: relative; }
.jd-tab { position: relative; display: inline-flex; align-items: center; gap: 7px; padding: 9px 18px; border-radius: 10px;
    font-size: 13px; font-weight: 600; color: var(--jd-text-2); border: none; background: transparent; font-family: inherit;
    transition: color .2s ease; }
.jd-tab i { font-size: 14px; }
.jd-tab .jd-count { min-width: 20px; height: 20px; padding: 0 6px; border-radius: 999px; font-size: 10.5px; font-weight: 700;
    display: inline-flex; align-items: center; justify-content: center; background: var(--jd-border-soft); color: var(--jd-text-3); transition: all .2s; }
.jd-tab.active { color: #fff; }
.jd-tab.active .jd-count { background: rgba(255,255,255,.25); color: #fff; }
.jd-tab-pill { position: absolute; top: 6px; bottom: 6px; border-radius: 10px; background: var(--jd-grad);
    box-shadow: 0 6px 16px -6px rgba(37,99,235,.6); transition: left .3s cubic-bezier(.22,1,.36,1), width .3s cubic-bezier(.22,1,.36,1); z-index: 0; }
.jd-tabs-kelas { display: flex; gap: 8px; overflow-x: auto; padding-bottom: 8px; margin-bottom: 12px; scrollbar-width: thin; }
.jd-tabs-kelas::-webkit-scrollbar { height: 6px; }
.jd-tabs-kelas::-webkit-scrollbar-thumb { background: var(--jd-border); border-radius: 6px; }
.jd-tab-kelas { position: relative; display: inline-flex; align-items: center; gap: 8px; padding: 8px 15px; border-radius: 11px;
    font-size: 12.5px; font-weight: 600; color: var(--jd-text-2); border: 1px solid var(--jd-border); background: var(--jd-card); white-space: nowrap;
    transition: all .2s ease; font-family: inherit; }
.jd-tab-kelas i { font-size: 13px; color: var(--jd-primary); }
.jd-tab-kelas:hover { transform: translateY(-1px); border-color: var(--jd-primary-border); color: var(--jd-text); box-shadow: var(--jd-shadow); }
.jd-tab-kelas.active { background: var(--jd-primary-soft); color: var(--jd-primary); border-color: var(--jd-primary-border); box-shadow: 0 4px 14px -6px rgba(37,99,235,.4); }
.jd-tab-kelas .jd-count { font-size: 10px; min-width: 19px; height: 19px; padding: 0 6px; border-radius: 999px;
    display: inline-flex; align-items: center; justify-content: center; background: var(--jd-border-soft); color: var(--jd-text-3); font-weight: 700; }
.jd-tab-kelas.active .jd-count { background: var(--jd-primary); color: #fff; }
.jd-tab-kelas.is-empty { opacity: .55; }

/* ---------- Scheduler grid ---------- */
.jd-scheduler-wrap { overflow-x: auto; border-radius: var(--jd-radius); border: 1px solid var(--jd-border); background: var(--jd-card); box-shadow: var(--jd-shadow); }
.jd-scheduler { min-width: 980px; }
.jd-sched-row { display: grid; grid-template-columns: 108px repeat(6, minmax(150px, 1fr)); }
.jd-sched-head { background: linear-gradient(135deg, rgba(37,99,235,.07), rgba(37,99,235,.02)); border-bottom: 1px solid var(--jd-border); }
.jd-sched-head .jd-sched-hcell { padding: 13px 10px; text-align: center; font-weight: 700; font-size: 12.5px; color: var(--jd-text); border-right: 1px solid var(--jd-border); }
.jd-sched-head .jd-sched-hcell:first-child { text-align: left; color: var(--jd-text-3); font-size: 11px; text-transform: uppercase; letter-spacing: .5px; }
.jd-sched-head .jd-sched-hcell:last-child { border-right: none; }
.jd-sched-head .jd-day-sub { display: block; font-size: 10px; color: var(--jd-text-3); font-weight: 500; }
.jd-sched-body-row { border-bottom: 1px solid var(--jd-border); }
.jd-sched-body-row:last-child { border-bottom: none; }
.jd-sched-time { display: flex; flex-direction: column; justify-content: center; gap: 2px; padding: 10px 14px;
    background: var(--jd-bg); border-right: 1px solid var(--jd-border); }
.jd-sched-time b { font-size: 12.5px; color: var(--jd-text); }
.jd-sched-time span { font-size: 10px; color: var(--jd-text-3); }
.jd-sched-cell { min-height: 92px; padding: 8px; border-right: 1px solid var(--jd-border); transition: background .2s ease; position: relative; }
.jd-sched-cell:last-child { border-right: none; }
.jd-sched-cell:hover { background: var(--jd-bg); }
.jd-sched-break { grid-column: 1 / -1; display: flex; align-items: center; justify-content: center; gap: 10px;
    padding: 9px 14px; background: var(--jd-amber-soft); border-top: 1px dashed var(--jd-amber-border); border-bottom: 1px dashed var(--jd-amber-border);
    font-size: 11.5px; font-weight: 700; color: var(--jd-amber); letter-spacing: .3px; }
.jd-sched-break i { font-size: 14px; }

/* Slot / Card jadwal dalam grid */
.jd-slot { --mc: var(--jd-primary); --mc-soft: var(--jd-primary-soft); --mc-border: var(--jd-primary-border);
    display: block; width: 100%; height: 100%; min-height: 76px; text-align: left; border: none; font-family: inherit;
    background: var(--mc-soft); border-left: 4px solid var(--mc); border-radius: 12px; padding: 9px 11px;
    color: var(--jd-text); transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease, background .18s ease;
    position: relative; overflow: hidden; }
.jd-slot:hover { transform: translateY(-2px); box-shadow: 0 10px 22px -8px rgba(15,23,42,.25); background: var(--mc-soft); }
.jd-slot-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 6px; margin-bottom: 5px; }
.jd-slot-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--mc); flex-shrink: 0; margin-top: 5px; box-shadow: 0 0 0 3px var(--mc-soft); }
.jd-slot-badge { font-size: 9px; font-weight: 700; letter-spacing: .4px; color: var(--mc); background: var(--jd-card);
    border-radius: 6px; padding: 2px 6px; border: 1px solid var(--mc-border); }
.jd-slot-name { font-size: 12px; font-weight: 700; color: var(--jd-text); line-height: 1.3; margin-bottom: 3px; word-break: break-word; }
.jd-slot-guru { font-size: 10.5px; color: var(--jd-text-2); display: flex; align-items: center; gap: 4px; }
.jd-slot-guru i { font-size: 11px; color: var(--jd-text-3); }
.jd-slot-time { font-size: 9.5px; color: var(--jd-text-3); margin-top: 5px; font-weight: 600; }
.jd-slot.is-conflict { border-color: var(--jd-red); box-shadow: 0 0 0 2px var(--jd-red-soft); background: var(--jd-red-soft); }
.jd-slot.is-conflict .jd-slot-badge { color: var(--jd-red); border-color: var(--jd-red-border); background: #fff; }
html.dark-mode .jd-slot.is-conflict .jd-slot-badge { background: var(--jd-card); }
.jd-conflict-tag { position: absolute; top: -1px; right: -1px; font-size: 9px; font-weight: 700; color: #fff;
    background: var(--jd-red); border-radius: 0 11px 0 10px; padding: 3px 8px; letter-spacing: .3px; }
html.dark-mode .jd-slot:hover { box-shadow: 0 12px 26px -8px rgba(0,0,0,.6); }

/* Add-cell button dalam grid kosong */
.jd-add-cell { width: 34px; height: 34px; border-radius: 11px; border: 2px dashed var(--jd-border); background: transparent;
    color: var(--jd-text-3); display: inline-flex; align-items: center; justify-content: center; font-size: 15px; cursor: pointer;
    transition: all .2s ease; }
.jd-add-cell:hover { border-color: var(--jd-primary); color: var(--jd-primary); background: var(--jd-primary-soft); transform: scale(1.08); }

/* Mapel palette (0..11) */
.jd-mc-0 { --mc:#16a34a; --mc-soft:rgba(22,163,74,.10); --mc-border:rgba(22,163,74,.32); }
.jd-mc-1 { --mc:#2563eb; --mc-soft:rgba(37,99,235,.10); --mc-border:rgba(37,99,235,.32); }
.jd-mc-2 { --mc:#ea580c; --mc-soft:rgba(234,88,12,.10); --mc-border:rgba(234,88,12,.32); }
.jd-mc-3 { --mc:#7c3aed; --mc-soft:rgba(124,58,237,.10); --mc-border:rgba(124,58,237,.32); }
.jd-mc-4 { --mc:#dc2626; --mc-soft:rgba(220,38,38,.10); --mc-border:rgba(220,38,38,.32); }
.jd-mc-5 { --mc:#0891b2; --mc-soft:rgba(8,145,178,.10); --mc-border:rgba(8,145,178,.32); }
.jd-mc-6 { --mc:#0d9488; --mc-soft:rgba(13,148,136,.10); --mc-border:rgba(13,148,136,.32); }
.jd-mc-7 { --mc:#0f766e; --mc-soft:rgba(15,118,110,.10); --mc-border:rgba(15,118,110,.32); }
.jd-mc-8 { --mc:#65a30d; --mc-soft:rgba(101,163,13,.10); --mc-border:rgba(101,163,13,.32); }
.jd-mc-9 { --mc:#4f46e5; --mc-soft:rgba(79,70,229,.10); --mc-border:rgba(79,70,229,.32); }
.jd-mc-10 { --mc:#db2777; --mc-soft:rgba(219,39,119,.10); --mc-border:rgba(219,39,119,.32); }
.jd-mc-11 { --mc:#d97706; --mc-soft:rgba(217,119,6,.10); --mc-border:rgba(217,119,6,.32); }
html.dark-mode .jd-mc-0 { --mc-soft:rgba(74,222,128,.16); --mc-border:rgba(74,222,128,.45); }
html.dark-mode .jd-mc-1 { --mc-soft:rgba(96,165,250,.16); --mc-border:rgba(96,165,250,.45); }
html.dark-mode .jd-mc-2 { --mc-soft:rgba(251,146,60,.16); --mc-border:rgba(251,146,60,.45); }
html.dark-mode .jd-mc-3 { --mc-soft:rgba(167,139,250,.16); --mc-border:rgba(167,139,250,.45); }
html.dark-mode .jd-mc-4 { --mc-soft:rgba(248,113,113,.16); --mc-border:rgba(248,113,113,.45); }
html.dark-mode .jd-mc-5 { --mc-soft:rgba(34,211,238,.16); --mc-border:rgba(34,211,238,.45); }
html.dark-mode .jd-mc-6 { --mc-soft:rgba(45,212,191,.16); --mc-border:rgba(45,212,191,.45); }
html.dark-mode .jd-mc-7 { --mc-soft:rgba(45,212,191,.16); --mc-border:rgba(45,212,191,.45); }
html.dark-mode .jd-mc-8 { --mc-soft:rgba(163,230,53,.16); --mc-border:rgba(163,230,53,.45); }
html.dark-mode .jd-mc-9 { --mc-soft:rgba(129,140,248,.16); --mc-border:rgba(129,140,248,.45); }
html.dark-mode .jd-mc-10 { --mc-soft:rgba(244,114,182,.16); --mc-border:rgba(244,114,182,.45); }
html.dark-mode .jd-mc-11 { --mc-soft:rgba(251,191,36,.16); --mc-border:rgba(251,191,36,.45); }

/* Legend */
.jd-legend { display: flex; flex-wrap: wrap; gap: 8px 14px; align-items: center; }
.jd-legend-item { display: inline-flex; align-items: center; gap: 7px; font-size: 11.5px; color: var(--jd-text-2); font-weight: 500; }
.jd-mapel-dot { width: 10px; height: 10px; border-radius: 4px; }

/* ---------- Cards ---------- */
.jd-card { background: var(--jd-card); border: 1px solid var(--jd-border); border-radius: var(--jd-radius); box-shadow: var(--jd-shadow); }
.jd-card--lift { transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease; }
.jd-card--lift:hover { transform: translateY(-4px); box-shadow: var(--jd-shadow-lg); border-color: var(--jd-primary-border); }
.jd-card-pad { padding: 20px; }
.jd-section-title { display: flex; align-items: center; gap: 9px; font-size: 15px; font-weight: 700; color: var(--jd-text); margin: 0 0 4px; }
.jd-section-title i { color: var(--jd-primary); font-size: 17px; }
.jd-section-sub { font-size: 12px; color: var(--jd-text-3); margin-bottom: 16px; }
.jd-card-header { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; padding: 16px 20px; border-bottom: 1px solid var(--jd-border); }

/* ---------- Modal helpers ---------- */
.jd-modal-icon { width: 62px; height: 62px; border-radius: 17px; display: inline-flex; align-items: center; justify-content: center; font-size: 26px;
    background: var(--jd-primary-soft); color: var(--jd-primary); }
.jd-modal-sub { font-size: 12px; color: var(--jd-text-3); }
.jd-info-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 10px; }
.jd-info-cell { background: var(--jd-bg); border: 1px solid var(--jd-border); border-radius: 12px; padding: 11px 13px; }
.jd-info-cell .lbl { font-size: 10px; font-weight: 700; color: var(--jd-text-3); text-transform: uppercase; letter-spacing: .5px; margin-bottom: 3px; display: flex; align-items: center; gap: 5px; }
.jd-info-cell .lbl i { font-size: 11px; }
.jd-info-cell .val { font-size: 13px; font-weight: 600; color: var(--jd-text); }

/* ---------- Wizard ---------- */
.jd-stepper { display: flex; align-items: center; gap: 0; margin-bottom: 22px; }
.jd-step { display: flex; align-items: center; gap: 10px; flex: 1; }
.jd-step:last-child { flex: 0 0 auto; }
.jd-step-dot { width: 34px; height: 34px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;
    background: var(--jd-bg); border: 1.5px solid var(--jd-border); color: var(--jd-text-3); font-size: 13px; font-weight: 700; flex-shrink: 0; transition: all .25s ease; }
.jd-step.active .jd-step-dot { background: var(--jd-grad); border-color: transparent; color: #fff; box-shadow: 0 6px 16px -6px rgba(37,99,235,.6); }
.jd-step.done .jd-step-dot { background: var(--jd-green); border-color: transparent; color: #fff; }
.jd-step-txt { display: flex; flex-direction: column; }
.jd-step-txt b { font-size: 12px; color: var(--jd-text); }
.jd-step-txt span { font-size: 10.5px; color: var(--jd-text-3); }
.jd-step-line { flex: 1; height: 2px; background: var(--jd-border); margin: 0 14px; border-radius: 2px; position: relative; overflow: hidden; min-width: 24px; }
.jd-step-line::after { content: ""; position: absolute; inset: 0; background: var(--jd-grad); transform: scaleX(0); transform-origin: left; transition: transform .4s ease; }
.jd-step-line.done::after { transform: scaleX(1); }
.jd-wizard-pane { display: none; animation: jdFadeUp .3s ease both; }
.jd-wizard-pane.is-show { display: block; }
@keyframes jdFadeUp { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: none; } }

/* ---------- Conflict checker ---------- */
.jd-conflict { display: none; margin-top: 14px; border-radius: 12px; padding: 12px 14px; border: 1px solid var(--jd-red-border); background: var(--jd-red-soft); }
.jd-conflict.is-show { display: block; animation: jdFadeUp .25s ease both; }
.jd-conflict-title { font-size: 12px; font-weight: 700; color: var(--jd-red); display: flex; align-items: center; gap: 7px; margin-bottom: 8px; }
.jd-conflict-title i { font-size: 14px; }
.jd-conflict-item { display: flex; align-items: center; gap: 8px; font-size: 12px; color: var(--jd-text-2); padding: 4px 0; }
.jd-conflict-item i { color: var(--jd-red); font-size: 12px; }
.jd-conflict-ok { display: none; margin-top: 14px; border-radius: 12px; padding: 12px 14px; border: 1px solid var(--jd-green-border); background: var(--jd-green-soft); }
.jd-conflict-ok.is-show { display: flex; align-items: center; gap: 8px; font-size: 12.5px; font-weight: 600; color: var(--jd-green); animation: jdFadeUp .25s ease both; }

/* ---------- Preview card ---------- */
.jd-preview { --mc: var(--jd-primary); --mc-soft: var(--jd-primary-soft); --mc-border: var(--jd-primary-border);
    display: flex; align-items: center; gap: 14px; border-radius: 13px; background: var(--mc-soft); border: 1px solid var(--mc-border);
    border-left: 4px solid var(--mc); padding: 13px 15px; }
.jd-preview-icon { width: 42px; height: 42px; border-radius: 12px; background: var(--jd-card); color: var(--mc);
    display: inline-flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; border: 1px solid var(--mc-border); }
.jd-preview-name { font-size: 14px; font-weight: 700; color: var(--jd-text); }
.jd-preview-meta { font-size: 11.5px; color: var(--jd-text-2); display: flex; flex-wrap: wrap; gap: 4px 12px; margin-top: 2px; }
.jd-preview-meta span { display: inline-flex; align-items: center; gap: 4px; }
.jd-preview-meta i { font-size: 11px; color: var(--mc); }

/* ---------- Timeline ---------- */
.jd-timeline { position: relative; padding-left: 0; }
.jd-tl-item { position: relative; display: flex; gap: 14px; padding-bottom: 18px; }
.jd-tl-item::before { content: ""; position: absolute; left: 15px; top: 34px; bottom: 0; width: 2px; background: var(--jd-border); }
.jd-tl-item:last-child::before { display: none; }
.jd-tl-dot { position: relative; z-index: 1; flex-shrink: 0; width: 32px; height: 32px; border-radius: 50%; background: var(--jd-card);
    border: 2px solid var(--jd-border); display: inline-flex; align-items: center; justify-content: center; color: var(--jd-text-3); font-size: 12px; }
.jd-tl-item.is-mc .jd-tl-dot { --mc: var(--jd-primary); --mc-soft: var(--jd-primary-soft); --mc-border: var(--jd-primary-border);
    background: var(--mc-soft); border-color: var(--mc-border); color: var(--mc); }
.jd-tl-body { flex: 1; min-width: 0; }
.jd-tl-card { background: var(--jd-card); border: 1px solid var(--jd-border); border-radius: 12px; padding: 13px 15px; box-shadow: var(--jd-shadow);
    display: flex; flex-wrap: wrap; align-items: center; gap: 12px; transition: all .2s ease; }
.jd-tl-card:hover { border-color: var(--jd-primary-border); transform: translateX(3px); box-shadow: var(--jd-shadow-lg); }
.jd-tl-time { flex-shrink: 0; min-width: 88px; text-align: center; background: var(--jd-bg); border: 1px solid var(--jd-border); border-radius: 10px; padding: 7px 9px; }
.jd-tl-time b { display: block; font-size: 12px; color: var(--jd-text); }
.jd-tl-time span { font-size: 9.5px; color: var(--jd-text-3); font-weight: 600; }
.jd-tl-main { flex: 1; min-width: 0; }
.jd-tl-name { font-size: 13.5px; font-weight: 700; color: var(--jd-text); }
.jd-tl-guru { font-size: 11.5px; color: var(--jd-text-2); }
.jd-tl-tag { flex-shrink: 0; }

/* ---------- Stat grid (per-kelas) ---------- */
.jd-stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px; margin-bottom: 22px; }
.jd-stat { display: flex; align-items: center; gap: 12px; padding: 16px 18px; }
.jd-stat-icon { width: 42px; height: 42px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; font-size: 17px; }
.jd-stat-icon.blue { background: var(--jd-primary-soft); color: var(--jd-primary); }
.jd-stat-icon.green { background: var(--jd-green-soft); color: var(--jd-green); }
.jd-stat-icon.violet { background: var(--jd-violet-soft); color: var(--jd-violet); }
.jd-stat-icon.amber { background: var(--jd-amber-soft); color: var(--jd-amber); }
.jd-stat-num { font-size: 20px; font-weight: 700; line-height: 1.1; color: var(--jd-text); }
.jd-stat-label { font-size: 11px; font-weight: 600; color: var(--jd-text-3); text-transform: uppercase; letter-spacing: .4px; }

/* ---------- Migration / Salin ---------- */
.jd-mig { padding: 4px 6px; }
.jd-mig-step { display: flex; align-items: center; gap: 10px; padding: 10px 0; opacity: .45; transition: opacity .3s ease; }
.jd-mig-step.is-active { opacity: 1; }
.jd-mig-step.is-done { opacity: 1; }
.jd-mig-step-icon { width: 30px; height: 30px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;
    background: var(--jd-bg); border: 1.5px solid var(--jd-border); color: var(--jd-text-3); font-size: 12px; flex-shrink: 0; transition: all .25s; }
.jd-mig-step.is-active .jd-mig-step-icon { background: var(--jd-primary-soft); border-color: var(--jd-primary-border); color: var(--jd-primary); animation: jdPulse 1.6s ease-in-out infinite; }
.jd-mig-step.is-done .jd-mig-step-icon { background: var(--jd-green); border-color: transparent; color: #fff; }
.jd-mig-step-txt b { font-size: 12.5px; color: var(--jd-text); display: block; }
.jd-mig-step-txt span { font-size: 11px; color: var(--jd-text-3); }
@keyframes jdPulse { 0%,100% { box-shadow: 0 0 0 0 rgba(37,99,235,.35); } 50% { box-shadow: 0 0 0 8px rgba(37,99,235,0); } }
.jd-mig-bar { height: 8px; border-radius: 999px; background: var(--jd-bg); border: 1px solid var(--jd-border); overflow: hidden; }
.jd-mig-bar-fill { height: 100%; width: 0%; border-radius: 999px; background: var(--jd-grad); transition: width .5s cubic-bezier(.22,1,.36,1); }
.jd-mig-stats { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; margin-top: 16px; }
.jd-mig-stat { border: 1px solid var(--jd-border); border-radius: 12px; padding: 12px; background: var(--jd-card); text-align: center; }
.jd-mig-stat b { display: block; font-size: 20px; font-weight: 700; color: var(--jd-text); font-variant-numeric: tabular-nums; }
.jd-mig-stat span { font-size: 10.5px; font-weight: 600; color: var(--jd-text-3); text-transform: uppercase; letter-spacing: .4px; }
.jd-mig-stat.berhasil b { color: var(--jd-green); }
.jd-mig-stat.dilewati b { color: var(--jd-amber); }
.jd-mig-stat.gagal b { color: var(--jd-red); }

/* ---------- Progress ---------- */
.jd-progress { height: 7px; border-radius: 999px; background: var(--jd-bg); border: 1px solid var(--jd-border); overflow: hidden; }
.jd-progress-fill { height: 100%; border-radius: 999px; background: var(--jd-grad); transition: width .6s cubic-bezier(.22,1,.36,1); position: relative; }
.jd-progress-fill::after { content: ""; position: absolute; inset: 0; background: linear-gradient(90deg, transparent, rgba(255,255,255,.4), transparent);
    background-size: 200% 100%; animation: jdShine 1.6s linear infinite; }
@keyframes jdShine { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }

/* ---------- Empty state ---------- */
.jd-empty { text-align: center; padding: 52px 24px; }
.jd-empty-illus { position: relative; width: 120px; height: 120px; margin: 0 auto 18px; }
.jd-empty-illus .ring { position: absolute; inset: 0; border-radius: 50%; border: 2px dashed var(--jd-primary-border); animation: jdSpin 14s linear infinite; }
.jd-empty-illus .core { position: absolute; inset: 18px; border-radius: 50%; background: var(--jd-grad-soft); display: inline-flex; align-items: center; justify-content: center; font-size: 40px; color: var(--jd-primary); box-shadow: inset 0 0 0 1px var(--jd-primary-border); }
@keyframes jdSpin { to { transform: rotate(360deg); } }
.jd-empty-title { font-size: 16px; font-weight: 700; color: var(--jd-text); margin-bottom: 6px; }
.jd-empty-sub { font-size: 12.5px; color: var(--jd-text-3); max-width: 380px; margin: 0 auto 18px; line-height: 1.6; }

/* ---------- Skeleton ---------- */
.jd-skeleton { position: relative; overflow: hidden; background: var(--jd-bg); border-radius: 10px; }
.jd-skeleton::after { content: ""; position: absolute; inset: 0; background: linear-gradient(90deg, transparent, rgba(255,255,255,.5), transparent);
    background-size: 200% 100%; animation: jdShimmer 1.4s ease infinite; }
html.dark-mode .jd-skeleton::after { background: linear-gradient(90deg, transparent, rgba(255,255,255,.08), transparent); }
@keyframes jdShimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }

/* ---------- Toast ---------- */
.jd-toast-wrap { position: fixed; top: 84px; right: 20px; z-index: 1090; display: flex; flex-direction: column; gap: 10px; pointer-events: none; }
.jd-toast { pointer-events: auto; display: flex; align-items: flex-start; gap: 11px; min-width: 300px; max-width: 380px;
    background: var(--jd-card); border: 1px solid var(--jd-border); border-radius: 14px; padding: 13px 15px; box-shadow: var(--jd-shadow-lg);
    animation: jdToastIn .35s cubic-bezier(.22,1,.36,1) both; border-left: 4px solid var(--jd-primary); }
.jd-toast.ok { border-left-color: var(--jd-green); }
.jd-toast.err { border-left-color: var(--jd-red); }
.jd-toast i { font-size: 16px; margin-top: 1px; color: var(--jd-primary); }
.jd-toast.ok i { color: var(--jd-green); }
.jd-toast.err i { color: var(--jd-red); }
.jd-toast b { display: block; font-size: 13px; color: var(--jd-text); }
.jd-toast span { font-size: 11.5px; color: var(--jd-text-2); line-height: 1.5; }
.jd-toast .jd-toast-close { margin-left: auto; background: none; border: none; color: var(--jd-text-3); font-size: 14px; cursor: pointer; padding: 0 2px; }
@keyframes jdToastIn { from { opacity: 0; transform: translateX(24px); } to { opacity: 1; transform: none; } }
.jd-toast.is-out { animation: jdToastOut .3s ease both; }
@keyframes jdToastOut { to { opacity: 0; transform: translateX(24px); } }

/* ---------- FAB ---------- */
.jd-fab { position: fixed; right: 22px; bottom: 22px; z-index: 1040; width: 56px; height: 56px; border-radius: 18px; border: none;
    background: var(--jd-grad); color: #fff; font-size: 22px; display: inline-flex; align-items: center; justify-content: center;
    box-shadow: 0 14px 30px -8px rgba(37,99,235,.6); transition: transform .2s ease, box-shadow .2s ease; }
.jd-fab:hover { transform: translateY(-3px) scale(1.04); box-shadow: 0 20px 40px -8px rgba(37,99,235,.7); }
.jd-fab i { pointer-events: none; }

/* ---------- Day card (mobile) ---------- */
.jd-day-card { border: 1px solid var(--jd-border); border-radius: var(--jd-radius); background: var(--jd-card); box-shadow: var(--jd-shadow); overflow: hidden; }
.jd-day-head { display: flex; align-items: center; gap: 10px; padding: 13px 16px; border-bottom: 1px solid var(--jd-border); background: var(--jd-bg); }
.jd-day-head i { font-size: 16px; color: var(--jd-primary); }
.jd-day-head b { font-size: 13px; color: var(--jd-text); }
.jd-day-head .jd-count { margin-left: auto; }
.jd-day-body { padding: 12px 14px; display: flex; flex-direction: column; gap: 9px; }
.jd-day-item { display: flex; align-items: center; gap: 11px; border-radius: 11px; padding: 10px 12px;
    background: var(--mc-soft, var(--jd-bg)); border-left: 4px solid var(--mc, var(--jd-primary)); }
.jd-day-item-time { flex-shrink: 0; text-align: center; min-width: 54px; }
.jd-day-item-time b { display: block; font-size: 12.5px; color: var(--jd-text); }
.jd-day-item-time span { font-size: 9px; color: var(--jd-text-3); font-weight: 600; }
.jd-day-item-name { font-size: 12.5px; font-weight: 700; color: var(--jd-text); line-height: 1.3; }
.jd-day-item-guru { font-size: 11px; color: var(--jd-text-2); }

/* ---------- Detail hero (per-kelas) ---------- */
.jd-detail-hero { position: relative; overflow: hidden; background: var(--jd-grad-rad); color: #fff; border-radius: var(--jd-radius-lg);
    padding: 26px 28px; margin-bottom: 22px; box-shadow: 0 20px 40px -16px rgba(29,78,216,.5); }
.jd-detail-hero::before { content: ""; position: absolute; inset: 0; background-image: radial-gradient(rgba(255,255,255,.14) 1px, transparent 1px); background-size: 22px 22px; opacity: .4; pointer-events: none; }
.jd-detail-hero-grid { position: relative; display: flex; flex-wrap: wrap; gap: 18px; align-items: center; justify-content: space-between; }
.jd-detail-avatar { width: 62px; height: 62px; border-radius: 18px; background: rgba(255,255,255,.18); border: 1px solid rgba(255,255,255,.3);
    display: inline-flex; align-items: center; justify-content: center; font-size: 26px; color: #fff; flex-shrink: 0; backdrop-filter: blur(8px); }
.jd-detail-title { font-size: 22px; font-weight: 700; letter-spacing: -.3px; margin: 0; color: #fff; }
.jd-detail-sub { font-size: 12.5px; opacity: .85; margin-top: 3px; }
.jd-detail-meta { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 12px; }

/* ---------- Back link ---------- */
.jd-back { display: inline-flex; align-items: center; gap: 7px; font-size: 12.5px; font-weight: 600; color: var(--jd-text-2); padding: 7px 13px;
    border-radius: 10px; border: 1px solid var(--jd-border); background: var(--jd-card); transition: all .2s ease; }
.jd-back:hover { color: var(--jd-primary); border-color: var(--jd-primary-border); transform: translateX(-2px); }

/* ---------- Export center ---------- */
.jd-export-preview { border: 1px solid var(--jd-border); border-radius: 14px; background: var(--jd-card); padding: 18px; position: relative; overflow: hidden; }
.jd-export-preview .sheet { border: 1px solid var(--jd-border); border-radius: 8px; background: #fff; padding: 12px; position: relative; }
html.dark-mode .jd-export-preview .sheet { background: #0d1526; }
.jd-export-row { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 10px 0; border-bottom: 1px dashed var(--jd-border); }
.jd-export-row:last-child { border-bottom: none; }
.jd-export-row .lbl { font-size: 12px; color: var(--jd-text-3); display: inline-flex; align-items: center; gap: 6px; }
.jd-export-row .lbl i { font-size: 12px; }
.jd-export-row .val { font-size: 12.5px; font-weight: 600; color: var(--jd-text); }

/* ---------- Day strip (jadwal-jenjang detail) ---------- */
.jd-day-strip { display: flex; align-items: center; gap: 14px; border-radius: var(--jd-radius); border: 1px solid var(--jd-border); background: var(--jd-card);
    box-shadow: var(--jd-shadow); overflow: hidden; margin-bottom: 14px; }
.jd-day-strip-label { flex-shrink: 0; width: 120px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 2px;
    background: var(--jd-primary-soft); border-right: 1px solid var(--jd-border); padding: 14px 8px; }
.jd-day-strip-label b { font-size: 14px; color: var(--jd-primary); }
.jd-day-strip-label span { font-size: 9.5px; color: var(--jd-text-3); font-weight: 600; text-transform: uppercase; letter-spacing: .5px; }
.jd-day-strip-body { flex: 1; display: grid; grid-auto-flow: column; grid-auto-columns: minmax(150px, 1fr); gap: 10px; overflow-x: auto; padding: 12px 14px; }

/* ---------- Responsive ---------- */
@media (max-width: 1199.98px) {
    .jd-kpi-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 767.98px) {
    .jd-hero { padding: 20px 18px; }
    .jd-hero-title { font-size: 18px; }
    .jd-kpi-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
    .jd-kpi { padding: 14px 14px; gap: 10px; }
    .jd-kpi-icon { width: 40px; height: 40px; font-size: 16px; }
    .jd-kpi-num { font-size: 19px; }
    .jd-toolbar { top: 66px; }
    .jd-toast-wrap { right: 12px; left: 12px; min-width: 0; }
    .jd-toast { min-width: 0; width: 100%; }
}
@media (max-width: 575.98px) {
    .jd-kpi-grid { grid-template-columns: 1fr 1fr; }
    .jd-hero-right { width: 100%; }
    .jd-hero-right .jd-btn { flex: 1; }
    .jd-detail-hero { padding: 20px 18px; }
}
@media (prefers-reduced-motion: reduce) {
    .jd-mod *, .jd-mod *::before, .jd-mod *::after {
        animation-duration: .01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: .01ms !important;
    }
}
</style>

<script>
window.JD = window.JD || {};
window.JD.mapelColorIdx = function (name) {
    var norm = String(name || '').toLowerCase().replace(/[^a-z0-9]/g, '');
    var map = {
        'sejarah': 9, 'indonesia': 2, 'matematika': 1, 'kewarganegaraan': 5,
        'quran': 7, 'hadis': 7, 'hadits': 7, 'aqidah': 7, 'akidah': 7,
        'inggris': 10, 'penjaskes': 11, 'olahraga': 11, 'fiqih': 8, 'fikih': 8, 'fiqh': 8,
        'bahasaarab': 6, 'alam': 3, 'sosial': 4, 'agama': 0, 'pkn': 5, 'ppkn': 5,
        'ski': 9, 'mtk': 1, 'bindo': 2, 'pjok': 11, 'pai': 0, 'arab': 6
    };
    for (var key in map) {
        if (norm.indexOf(key) !== -1) return map[key];
    }
    var h = 0;
    for (var i = 0; i < norm.length; i++) h = (h * 31 + norm.charCodeAt(i)) & 0x7fffffff;
    return h % 12;
};
window.JD.ripple = function (evt) {
    var el = evt.currentTarget;
    var rect = el.getBoundingClientRect();
    var r = Math.max(rect.width, rect.height) / 2;
    var d = document.createElement('span');
    d.className = 'jd-ripple';
    d.style.width = d.style.height = (r * 2) + 'px';
    d.style.left = (evt.clientX - rect.left - r) + 'px';
    d.style.top = (evt.clientY - rect.top - r) + 'px';
    el.appendChild(d);
    setTimeout(function () { d.remove(); }, 600);
};
window.JD.toast = function (type, title, msg) {
    var wrap = document.querySelector('.jd-toast-wrap');
    if (!wrap) { wrap = document.createElement('div'); wrap.className = 'jd-toast-wrap'; document.body.appendChild(wrap); }
    var icon = type === 'ok' ? 'bi-check-circle-fill' : (type === 'err' ? 'bi-exclamation-circle-fill' : 'bi-info-circle-fill');
    var t = document.createElement('div');
    t.className = 'jd-toast ' + (type || '');
    t.setAttribute('role', 'status');
    t.innerHTML = '<i class="bi ' + icon + '"></i><div><b>' + title + '</b><span>' + (msg || '') + '</span></div><button type="button" class="jd-toast-close" aria-label="Tutup">&times;</button>';
    wrap.appendChild(t);
    var close = function () { t.classList.add('is-out'); setTimeout(function () { t.remove(); }, 320); };
    t.querySelector('.jd-toast-close').addEventListener('click', close);
    setTimeout(close, 4600);
};
window.JD.skeleton = function (wrap, cols) {
    var h = '';
    for (var r = 0; r < 5; r++) {
        h += '<div class="jd-sched-row" style="grid-template-columns:108px repeat(' + cols + ', minmax(150px,1fr));">';
        h += '<div class="jd-sched-time"><span class="jd-skeleton" style="height:14px;width:46px;"></span></div>';
        for (var c = 0; c < cols; c++) h += '<div class="jd-sched-cell"><div class="jd-skeleton" style="height:72px;"></div></div>';
        h += '</div>';
    }
    wrap.innerHTML = h;
};
</script>
