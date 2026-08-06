<style>
/* ============================================================
   ABSENSI MODULE — shared design system (blue primary)
   Wrapper: .abs-mod
   ============================================================ */
.abs-mod {
    --ab-primary: #2563eb;
    --ab-primary-2: #3b82f6;
    --ab-primary-3: #60a5fa;
    --ab-primary-dark: #1d4ed8;
    --ab-primary-soft: #eff6ff;
    --ab-primary-border: rgba(37,99,235,.22);
    --ab-grad: linear-gradient(135deg, #2563eb, #3b82f6);
    --ab-grad-soft: linear-gradient(135deg, #2563eb, #60a5fa);
    --ab-grad-rad: radial-gradient(1200px 480px at 12% -20%, #eff6ff 0%, transparent 60%);
    --ab-bg: #f8fafc;
    --ab-card: #ffffff;
    --ab-border: #e2e8f0;
    --ab-border-soft: #eef2f7;
    --ab-text: #0f172a;
    --ab-text-2: #475569;
    --ab-text-3: #94a3b8;
    --ab-shadow: 0 6px 18px -6px rgba(15,23,42,.08);
    --ab-shadow-lg: 0 22px 48px -18px rgba(15,23,42,.18);
    --ab-radius: 14px;
    --ab-green: #16a34a;  --ab-green-soft: #f0fdf4;  --ab-green-border: #bbf7d0;
    --ab-amber: #d97706;  --ab-amber-soft: #fffbeb;  --ab-amber-border: #fde68a;
    --ab-red: #dc2626;    --ab-red-soft: #fef2f2;    --ab-red-border: #fecaca;
    --ab-sky: #0284c7;    --ab-sky-soft: #f0f9ff;    --ab-sky-border: #bae6fd;
    --ab-violet: #7c3aed; --ab-violet-soft: #f5f3ff; --ab-violet-border: #ddd6fe;
    font-family: 'Inter', 'Poppins', system-ui, sans-serif;
    color: var(--ab-text);
}
html.dark-mode .abs-mod {
    --ab-primary: #3DA9FC;
    --ab-primary-2: #2EA8FF;
    --ab-primary-3: #6ec9ff;
    --ab-primary-dark: #2EA8FF;
    --ab-primary-soft: rgba(61,169,252,.14);
    --ab-primary-border: rgba(61,169,252,.35);
    --ab-grad: linear-gradient(135deg, #2EA8FF, #00E5FF);
    --ab-grad-soft: linear-gradient(135deg, #2EA8FF, #00E5FF);
    --ab-grad-rad: radial-gradient(1200px 480px at 12% -20%, rgba(61,169,252,.12) 0%, transparent 60%);
    --ab-bg: #0f172a;
    --ab-card: #0D2F38;
    --ab-border: rgba(255,255,255,.1);
    --ab-border-soft: rgba(255,255,255,.06);
    --ab-text: #f8fafc;
    --ab-text-2: #cbd5e1;
    --ab-text-3: #7d96a6;
    --ab-shadow: 0 6px 18px -6px rgba(0,0,0,.35);
    --ab-shadow-lg: 0 22px 48px -18px rgba(0,0,0,.5);
    --ab-green: #34d399;  --ab-green-soft: rgba(52,211,153,.12);  --ab-green-border: rgba(52,211,153,.35);
    --ab-amber: #fbbf24;  --ab-amber-soft: rgba(251,191,36,.12);  --ab-amber-border: rgba(251,191,36,.35);
    --ab-red: #f87171;    --ab-red-soft: rgba(248,113,113,.12);    --ab-red-border: rgba(248,113,113,.35);
    --ab-sky: #38bdf8;    --ab-sky-soft: rgba(56,189,248,.12);    --ab-sky-border: rgba(56,189,248,.35);
    --ab-violet: #a78bfa; --ab-violet-soft: rgba(167,139,250,.12); --ab-violet-border: rgba(167,139,250,.35);
}
.abs-mod a { text-decoration: none !important; }
.abs-mod * { box-sizing: border-box; }
.abs-mod :focus-visible { outline: 2px solid var(--ab-primary-3); outline-offset: 2px; border-radius: 6px; }

/* ---------- Typography helpers ---------- */
.abm-section-title {
    display: flex; align-items: center; gap: 10px;
    font-size: 15px; font-weight: 800; color: var(--ab-text);
}
.abm-section-title i { color: var(--ab-primary); font-size: 15px; }
.abm-muted { color: var(--ab-text-3); }
.abm-num { font-variant-numeric: tabular-nums; }

/* ---------- Cards ---------- */
.abm-card {
    background: var(--ab-card);
    border: 1px solid var(--ab-border);
    border-radius: 18px;
    box-shadow: var(--ab-shadow);
    transition: transform .3s cubic-bezier(.4,0,.2,1), box-shadow .3s cubic-bezier(.4,0,.2,1), border-color .3s;
}
.abm-card--lift:hover { transform: translateY(-3px); box-shadow: var(--ab-shadow-lg); border-color: var(--ab-primary-border); }

/* ---------- Buttons ---------- */
.abm-btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    padding: 9px 16px; border-radius: 12px; border: 1px solid transparent;
    font-size: 12.5px; font-weight: 700; cursor: pointer; white-space: nowrap; line-height: 1.4;
    transition: all .22s cubic-bezier(.4,0,.2,1);
}
.abm-btn i { font-size: 13px; }
.abm-btn:active { transform: scale(.97); }
.abm-btn--solid { background: var(--ab-grad); color: #fff; box-shadow: 0 8px 20px -6px rgba(37,99,235,.45); }
.abm-btn--solid:hover { color: #fff; transform: translateY(-2px); box-shadow: 0 12px 26px -8px rgba(37,99,235,.55); }
html.dark-mode .abm-btn--solid { color: #001019; }
html.dark-mode .abm-btn--solid:hover { color: #001019; }
.abm-btn--outline { background: var(--ab-card); color: var(--ab-primary); border-color: var(--ab-primary-border); }
.abm-btn--outline:hover { background: var(--ab-primary-soft); color: var(--ab-primary); transform: translateY(-2px); }
.abm-btn--ghost { background: rgba(255,255,255,.14); color: #fff; border: 1px solid rgba(255,255,255,.24); backdrop-filter: blur(6px); }
.abm-btn--ghost:hover { background: rgba(255,255,255,.26); color: #fff; transform: translateY(-2px); }
.abm-btn--soft { background: var(--ab-primary-soft); color: var(--ab-primary); }
.abm-btn--soft:hover { background: var(--ab-primary); color: #fff; transform: translateY(-2px); }
.abm-btn--light { background: #fff; color: var(--ab-primary-dark); box-shadow: 0 6px 18px -4px rgba(0,0,0,.25); }
.abm-btn--light:hover { color: var(--ab-primary-dark); transform: translateY(-2px); box-shadow: 0 10px 24px -6px rgba(0,0,0,.3); }
html.dark-mode .abm-btn--light { color: #002e3b; }
html.dark-mode .abm-btn--light:hover { color: #002e3b; }
.abm-btn--danger { background: var(--ab-red-soft); color: var(--ab-red); border: 1px solid var(--ab-red-border); }
.abm-btn--danger:hover { background: var(--ab-red); color: #fff; transform: translateY(-2px); }
.abm-btn--sm { padding: 7px 12px; font-size: 12px; border-radius: 10px; }
.abm-btn--xs { padding: 6px 10px; font-size: 11.5px; border-radius: 9px; }
.abm-btn--block { width: 100%; }

/* ---------- Chips / pills ---------- */
.abm-chip {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 4px 13px; border-radius: 20px; font-size: 11.5px; font-weight: 700; white-space: nowrap;
    border: 1px solid transparent; line-height: 1.5;
}
.abm-chip i { font-size: 11px; }
.abm-chip--ok    { background: var(--ab-green-soft);  color: var(--ab-green);  border-color: var(--ab-green-border); }
.abm-chip--warn  { background: var(--ab-amber-soft);  color: var(--ab-amber);  border-color: var(--ab-amber-border); }
.abm-chip--danger{ background: var(--ab-red-soft);    color: var(--ab-red);    border-color: var(--ab-red-border); }
.abm-chip--info  { background: var(--ab-sky-soft);    color: var(--ab-sky);    border-color: var(--ab-sky-border); }
.abm-chip--blue  { background: var(--ab-primary-soft);color: var(--ab-primary);border-color: var(--ab-primary-border); }
.abm-chip--violet{ background: var(--ab-violet-soft); color: var(--ab-violet); border-color: var(--ab-violet-border); }
.abm-chip--muted { background: rgba(148,163,184,.14); color: var(--ab-text-2); border-color: var(--ab-border); }
html.dark-mode .abm-chip--muted { background: rgba(255,255,255,.05); }
.abm-chip--dot::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }

/* ---------- Hero ---------- */
.abm-hero {
    position: relative; overflow: hidden;
    border-radius: 22px;
    background: var(--ab-grad);
    color: #fff; padding: 26px 30px 24px;
    box-shadow: 0 18px 40px -12px rgba(37,99,235,.4), inset 0 1px 0 rgba(255,255,255,.16);
    margin-bottom: 20px;
}
html.dark-mode .abm-hero { box-shadow: 0 18px 40px -12px rgba(0,229,255,.35), inset 0 1px 0 rgba(255,255,255,.16); }
.abm-hero::before, .abm-hero::after {
    content: ''; position: absolute; border-radius: 50%; pointer-events: none;
}
.abm-hero::before { width: 340px; height: 340px; top: -150px; right: -70px; background: radial-gradient(circle, rgba(255,255,255,.18), transparent 70%); }
.abm-hero::after { width: 300px; height: 300px; bottom: -150px; left: 36%; background: radial-gradient(circle, rgba(255,255,255,.14), transparent 70%); }
.abm-hero-grid {
    position: absolute; inset: 0; opacity: .32; pointer-events: none;
    background-image: linear-gradient(rgba(255,255,255,.07) 1px, transparent 1px),
                      linear-gradient(90deg, rgba(255,255,255,.07) 1px, transparent 1px);
    background-size: 34px 34px;
}
.abm-hero-row { position: relative; z-index: 1; display: flex; flex-wrap: wrap; gap: 18px 26px; align-items: center; justify-content: space-between; }
.abm-hero-left { min-width: 0; }
.abm-hero-icon {
    width: 58px; height: 58px; border-radius: 18px; flex-shrink: 0;
    background: rgba(255,255,255,.16); border: 1px solid rgba(255,255,255,.22);
    backdrop-filter: blur(8px); display: flex; align-items: center; justify-content: center;
    font-size: 25px; box-shadow: inset 0 1px 0 rgba(255,255,255,.3);
}
.abm-hero h3 { color: #fff; font-weight: 800; font-size: 23px; letter-spacing: -.3px; margin-bottom: 3px; }
.abm-hero-sub { color: rgba(255,255,255,.82); font-size: 12.5px; margin-bottom: 0; }
.abm-hero-badges { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 12px; }
.abm-hero-badge {
    display: inline-flex; align-items: center; gap: 7px; padding: 5px 13px; border-radius: 10px;
    font-size: 11.5px; font-weight: 600; white-space: nowrap;
    background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.2);
    backdrop-filter: blur(6px); color: #fff;
}
.abm-hero-badge i { font-size: 11px; }
.abm-hero-right { display: flex; flex-direction: column; gap: 12px; align-items: stretch; }
.abm-hero-clock {
    display: flex; align-items: center; gap: 13px; padding: 11px 16px; border-radius: 15px;
    background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.2); backdrop-filter: blur(10px);
}
.abm-clock-time { font-size: 21px; font-weight: 700; line-height: 1; font-variant-numeric: tabular-nums; letter-spacing: .3px; }
.abm-clock-date { font-size: 11px; color: rgba(255,255,255,.78); margin-top: 4px; }
.abm-hero-actions { display: flex; flex-wrap: wrap; gap: 8px; justify-content: flex-end; }

/* ---------- KPI ---------- */
.abm-kpi-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 22px; }
.abm-kpi {
    position: relative; overflow: hidden;
    background: var(--ab-card); border: 1px solid var(--ab-border);
    border-radius: 18px; padding: 18px 20px;
    display: flex; align-items: center; gap: 15px;
    box-shadow: var(--ab-shadow);
    transition: transform .3s cubic-bezier(.4,0,.2,1), box-shadow .3s cubic-bezier(.4,0,.2,1);
}
.abm-kpi:hover { transform: translateY(-4px); box-shadow: var(--ab-shadow-lg); }
.abm-kpi::after {
    content: ''; position: absolute; top: -20px; right: -20px; width: 84px; height: 84px; border-radius: 50%;
    background: var(--ab-kpi-glow, rgba(37,99,235,.08));
}
.abm-kpi-icon {
    width: 52px; height: 52px; border-radius: 15px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: 21px; color: #fff;
    box-shadow: 0 6px 14px -4px var(--ab-kpi-shadow, rgba(37,99,235,.4));
    transition: transform .3s cubic-bezier(.4,0,.2,1);
}
.abm-kpi:hover .abm-kpi-icon { transform: scale(1.08) rotate(-3deg); }
.abm-kpi-icon.blue   { background: linear-gradient(135deg, #2563eb, #60a5fa); --ab-kpi-shadow: rgba(37,99,235,.4); }
.abm-kpi-icon.green  { background: linear-gradient(135deg, #16a34a, #22c55e); --ab-kpi-shadow: rgba(22,163,74,.4); }
.abm-kpi-icon.amber  { background: linear-gradient(135deg, #d97706, #f59e0b); --ab-kpi-shadow: rgba(217,119,6,.4); }
.abm-kpi-icon.violet { background: linear-gradient(135deg, #7c3aed, #a855f7); --ab-kpi-shadow: rgba(124,58,237,.4); }
.abm-kpi-icon.sky    { background: linear-gradient(135deg, #0284c7, #0ea5e9); --ab-kpi-shadow: rgba(2,132,199,.4); }
.abm-kpi-icon.rose   { background: linear-gradient(135deg, #dc2626, #f87171); --ab-kpi-shadow: rgba(220,38,38,.4); }
.abm-kpi-info { flex: 1; min-width: 0; position: relative; z-index: 1; }
.abm-kpi-num { font-size: 27px; font-weight: 800; color: var(--ab-text); line-height: 1; }
.abm-kpi-label { font-size: 12px; color: var(--ab-text-2); font-weight: 500; margin-top: 6px; letter-spacing: .2px; }
.abm-kpi-watermark { position: absolute; right: 14px; bottom: -8px; z-index: 0; font-size: 72px; opacity: .06; color: var(--ab-kpi-wm, #2563eb); pointer-events: none; }

/* ---------- Progress ---------- */
.abm-progress { height: 6px; border-radius: 6px; overflow: hidden; background: rgba(148,163,184,.18); }
.abm-progress > span {
    display: block; height: 100%; width: 0; border-radius: 6px;
    background: linear-gradient(90deg, var(--ab-primary), var(--ab-primary-3));
    transition: width 1.2s cubic-bezier(.22,1,.36,1);
}
.abm-progress--green > span { background: linear-gradient(90deg, #16a34a, #4ade80); }

/* ---------- Kelas card ---------- */
.abm-kelas-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
.abm-kelas {
    background: var(--ab-card); border: 1px solid var(--ab-border);
    border-radius: 18px; padding: 18px 18px 16px;
    display: flex; flex-direction: column; gap: 12px;
    box-shadow: var(--ab-shadow);
    transition: transform .3s cubic-bezier(.4,0,.2,1), box-shadow .3s cubic-bezier(.4,0,.2,1), border-color .3s;
    position: relative; overflow: hidden;
}
.abm-kelas::before {
    content: ''; position: absolute; inset-inline: 0; top: 0; height: 3px;
    background: var(--ab-grad); opacity: 0; transition: opacity .3s;
}
.abm-kelas:hover { transform: translateY(-4px); box-shadow: var(--ab-shadow-lg); border-color: var(--ab-primary-border); }
.abm-kelas:hover::before { opacity: 1; }
.abm-kelas.is-done::before { background: linear-gradient(90deg, #16a34a, #4ade80); opacity: 1; }
.abm-kelas-top { display: flex; align-items: flex-start; gap: 12px; }
.abm-kelas-avatar {
    width: 46px; height: 46px; border-radius: 14px; flex-shrink: 0; color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; font-weight: 800; letter-spacing: .5px;
    box-shadow: 0 4px 10px -2px rgba(15,23,42,.25);
}
.abm-kelas-avatar.c0 { background: linear-gradient(135deg, #2563eb, #60a5fa); }
.abm-kelas-avatar.c1 { background: linear-gradient(135deg, #7c3aed, #a855f7); }
.abm-kelas-avatar.c2 { background: linear-gradient(135deg, #0ea5e9, #22d3ee); }
.abm-kelas-avatar.c3 { background: linear-gradient(135deg, #16a34a, #4ade80); }
.abm-kelas-avatar.c4 { background: linear-gradient(135deg, #ea580c, #fb923c); }
.abm-kelas-avatar.c5 { background: linear-gradient(135deg, #db2777, #f472b6); }
.abm-kelas-name { font-weight: 800; color: var(--ab-text); font-size: 15px; line-height: 1.3; }
.abm-kelas-meta { font-size: 11.5px; color: var(--ab-text-3); margin-top: 3px; }
.abm-kelas-body { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; }
.abm-kelas-stat {
    background: var(--ab-border-soft); border-radius: 12px; padding: 10px 12px;
}
.abm-kelas-stat .v { font-size: 18px; font-weight: 800; color: var(--ab-text); line-height: 1; }
.abm-kelas-stat .l { font-size: 10.5px; color: var(--ab-text-3); margin-top: 4px; font-weight: 600; letter-spacing: .2px; text-transform: uppercase; }
.abm-kelas-wali { font-size: 12px; color: var(--ab-text-2); display: flex; align-items: center; gap: 7px; }
.abm-kelas-wali i { color: var(--ab-text-3); font-size: 12px; }
.abm-kelas-actions { display: flex; gap: 8px; padding-top: 4px; border-top: 1px dashed var(--ab-border); }
.abm-quick {
    flex: 1; display: inline-flex; align-items: center; justify-content: center; gap: 6px;
    padding: 8px 6px; border-radius: 10px; font-size: 11.5px; font-weight: 700; color: var(--ab-text-2);
    background: var(--ab-border-soft); transition: all .22s cubic-bezier(.4,0,.2,1);
}
.abm-quick i { font-size: 12px; }
.abm-quick--edit:hover { background: linear-gradient(135deg, #d97706, #f59e0b); color: #fff; box-shadow: 0 6px 14px -4px rgba(217,119,6,.5); }
.abm-quick--hist:hover { background: linear-gradient(135deg, #2563eb, #60a5fa); color: #fff; box-shadow: 0 6px 14px -4px rgba(37,99,235,.5); }
.abm-quick--print:hover { background: linear-gradient(135deg, #7c3aed, #a855f7); color: #fff; box-shadow: 0 6px 14px -4px rgba(124,58,237,.5); }
.abm-quick--input:hover { background: linear-gradient(135deg, #16a34a, #4ade80); color: #fff; box-shadow: 0 6px 14px -4px rgba(22,163,74,.5); }
.abm-quick.is-disabled { cursor: not-allowed; opacity: .45; }
.abm-quick.is-disabled:hover { background: var(--ab-border-soft); color: var(--ab-text-2); box-shadow: none; }

/* ---------- Status stepper buttons (H/I/S/A) ---------- */
.abm-stepper {
    display: grid; grid-template-columns: repeat(4, 1fr); gap: 7px;
    min-width: 250px;
}
.abm-step {
    border: 1.5px solid var(--ab-border); background: var(--ab-card);
    border-radius: 11px; padding: 9px 6px 8px; cursor: pointer;
    display: flex; flex-direction: column; align-items: center; gap: 3px;
    font-weight: 700; font-size: 10.5px; color: var(--ab-text-2);
    transition: all .2s cubic-bezier(.4,0,.2,1); user-select: none; line-height: 1.3;
}
.abm-step .k { font-size: 16px; font-weight: 800; line-height: 1; }
.abm-step:hover { transform: translateY(-2px); border-color: currentColor; }
.abm-step.is-on-h { color: var(--ab-green);  background: var(--ab-green-soft);  border-color: var(--ab-green-border); }
.abm-step.is-on-i { color: var(--ab-amber);  background: var(--ab-amber-soft);  border-color: var(--ab-amber-border); }
.abm-step.is-on-s { color: var(--ab-sky);    background: var(--ab-sky-soft);    border-color: var(--ab-sky-border); }
.abm-step.is-on-a { color: var(--ab-red);    background: var(--ab-red-soft);    border-color: var(--ab-red-border); }
.abm-step:active { transform: scale(.94); }

/* ---------- Student row ---------- */
.abm-student {
    display: flex; align-items: center; gap: 14px; flex-wrap: wrap;
    background: var(--ab-card); border: 1.5px solid var(--ab-border);
    border-radius: 14px; padding: 11px 14px;
    transition: border-color .25s, box-shadow .25s, background .25s;
}
.abm-student:hover { border-color: var(--ab-primary-border); box-shadow: var(--ab-shadow); }
.abm-student.is-selected { border-color: var(--ab-primary); box-shadow: 0 0 0 3px var(--ab-primary-soft); }
.abm-student.is-changed { border-color: var(--ab-amber); background: var(--ab-amber-soft); }
html.dark-mode .abm-student.is-changed { background: rgba(251,191,36,.06); }
.abm-student-no {
    width: 30px; flex-shrink: 0; text-align: center;
    font-size: 12px; font-weight: 700; color: var(--ab-text-3);
}
.abm-student-avatar {
    width: 40px; height: 40px; border-radius: 12px; flex-shrink: 0; color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; font-weight: 800;
    background: linear-gradient(135deg, #2563eb, #60a5fa);
    box-shadow: 0 4px 10px -2px rgba(37,99,235,.35);
}
.abm-student-main { flex: 1 1 170px; min-width: 0; }
.abm-student-name { font-weight: 700; color: var(--ab-text); font-size: 13.5px; line-height: 1.35; }
.abm-student-nisn { font-size: 11px; color: var(--ab-text-3); margin-top: 2px; }
.abm-keterangan-wrap { flex: 1 1 180px; min-width: 140px; }
.abm-keterangan {
    width: 100%; border: 1.5px solid var(--ab-border); background: var(--ab-card);
    border-radius: 10px; padding: 7px 11px; font-size: 12.5px; color: var(--ab-text);
    transition: border-color .2s, box-shadow .2s; height: 37px;
}
.abm-keterangan::placeholder { color: var(--ab-text-3); }
.abm-keterangan:focus { outline: none; border-color: var(--ab-primary); box-shadow: 0 0 0 3px var(--ab-primary-soft); }
.abm-student.is-on-a .abm-student-name { color: var(--ab-red); }
.abm-student-list { display: flex; flex-direction: column; gap: 8px; margin-bottom: 18px; }
.abm-keterangan-wrap { transition: opacity .2s, transform .2s; }
.abm-keterangan-wrap.is-hidden { opacity: 0; pointer-events: none; transform: translateY(-3px); }

/* ---------- Search ---------- */
.abm-search { position: relative; }
.abm-search i { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); color: var(--ab-text-3); font-size: 14px; }
.abm-search input {
    width: 100%; border: 1.5px solid var(--ab-border); background: var(--ab-card);
    border-radius: 12px; padding: 10px 14px 10px 38px; font-size: 13px; color: var(--ab-text);
    transition: border-color .2s, box-shadow .2s;
}
.abm-search input:focus { outline: none; border-color: var(--ab-primary); box-shadow: 0 0 0 3px var(--ab-primary-soft); }
.abm-search input::placeholder { color: var(--ab-text-3); }

/* ---------- Sticky action bar ---------- */
.abm-actionbar {
    position: sticky; bottom: 14px; z-index: 50;
    display: flex; align-items: center; gap: 16px; flex-wrap: wrap;
    background: var(--ab-card); border: 1px solid var(--ab-border);
    border-radius: 18px; padding: 12px 18px;
    box-shadow: 0 18px 44px -14px rgba(15,23,42,.28);
    backdrop-filter: blur(12px);
}
html.dark-mode .abm-actionbar { background: rgba(13,47,56,.92); }
.abm-actionbar-count { font-size: 13px; color: var(--ab-text-2); font-weight: 600; }
.abm-actionbar-count b { color: var(--ab-primary); font-variant-numeric: tabular-nums; }

/* ---------- Empty state ---------- */
.abm-empty { text-align: center; padding: 44px 20px; }
.abm-empty > i { font-size: 44px; opacity: .4; color: var(--ab-primary); margin-bottom: 12px; }
.abm-empty-title { font-size: 15px; font-weight: 700; color: var(--ab-text-2); margin-bottom: 4px; }
.abm-empty-sub { font-size: 12.5px; color: var(--ab-text-3); }

/* ---------- Alerts ---------- */
.abm-alert {
    display: flex; align-items: flex-start; gap: 12px;
    border-radius: 14px; padding: 14px 16px; font-size: 13px; line-height: 1.55;
    margin-bottom: 18px;
}
.abm-alert i { font-size: 16px; margin-top: 1px; }
.abm-alert--warn { background: var(--ab-amber-soft); color: #92400e; border: 1px solid var(--ab-amber-border); }
.abm-alert--danger { background: var(--ab-red-soft); color: #991b1b; border: 1px solid var(--ab-red-border); }
.abm-alert--info { background: var(--ab-primary-soft); color: var(--ab-primary-dark); border: 1px solid var(--ab-primary-border); }
html.dark-mode .abm-alert--warn { color: #fcd34d; }
html.dark-mode .abm-alert--danger { color: #fca5a5; }
html.dark-mode .abm-alert--info { color: #7dd3fc; }

/* ---------- Counter chips ---------- */
.abm-counter { display: flex; flex-wrap: wrap; gap: 8px; }
.abm-counter-item {
    display: inline-flex; align-items: baseline; gap: 6px;
    padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 700;
    border: 1px solid var(--ab-border); background: var(--ab-card);
}
.abm-counter-item b { font-size: 15px; font-variant-numeric: tabular-nums; }
.abm-counter-item.h { color: var(--ab-green); }
.abm-counter-item.i { color: var(--ab-amber); }
.abm-counter-item.s { color: var(--ab-sky); }
.abm-counter-item.a { color: var(--ab-red); }

/* ---------- Timeline steps (import) ---------- */
.abm-steps { display: flex; flex-direction: column; gap: 0; }
.abm-step-line { position: relative; padding: 0 0 18px 30px; }
.abm-step-line::before {
    content: ''; position: absolute; left: 9px; top: 26px; bottom: 0; width: 2px;
    background: var(--ab-border);
}
.abm-step-line:last-child { padding-bottom: 0; }
.abm-step-line:last-child::before { display: none; }
.abm-step-line .dot {
    position: absolute; left: 0; top: 0; width: 20px; height: 20px; border-radius: 50%;
    background: var(--ab-card); border: 2px solid var(--ab-primary); color: var(--ab-primary);
    display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 800;
}
.abm-step-line .t { font-size: 13px; font-weight: 700; color: var(--ab-text); }
.abm-step-line .d { font-size: 12px; color: var(--ab-text-3); margin-top: 2px; }

/* ---------- Heatmap (riwayat) ---------- */
.abm-heatmap { display: grid; grid-template-columns: repeat(auto-fit, minmax(58px, 1fr)); gap: 8px; }
.abm-heat-cell {
    border-radius: 10px; padding: 8px 6px; text-align: center;
    background: var(--ab-border-soft); border: 1px solid transparent; transition: transform .2s;
}
.abm-heat-cell:hover { transform: translateY(-2px); }
.abm-heat-cell .day { font-size: 12px; font-weight: 800; color: var(--ab-text); }
.abm-heat-cell .bar { height: 4px; border-radius: 4px; margin: 6px auto 0; background: var(--ab-border); }
.abm-heat-cell .bar span { display: block; height: 100%; border-radius: 4px; background: var(--ab-grad); }
.abm-heat-cell .n { font-size: 10px; color: var(--ab-text-3); font-weight: 600; margin-top: 4px; }

/* ---------- Modal helper ---------- */
.abm-modal-body { padding: 22px 24px; }
.abm-modal-icon {
    width: 58px; height: 58px; border-radius: 16px; display: inline-flex; align-items: center; justify-content: center;
    font-size: 25px; color: #fff; background: var(--ab-grad); box-shadow: 0 8px 20px -6px rgba(37,99,235,.45);
}

/* ---------- Form fields ---------- */
.abm-field-label { font-weight: 600; font-size: 13px; color: var(--ab-text-2); margin-bottom: 6px; }
.abm-field-label i { color: var(--ab-primary); margin-right: 6px; }
.abm-control {
    width: 100%; border: 1.5px solid var(--ab-border); background: var(--ab-card);
    border-radius: 12px; height: 46px; padding: 0 14px; font-size: 13.5px; color: var(--ab-text);
    transition: border-color .2s, box-shadow .2s;
}
.abm-control:focus { outline: none; border-color: var(--ab-primary); box-shadow: 0 0 0 3px var(--ab-primary-soft); }
.abm-hintbox {
    display: flex; align-items: center; gap: 12px;
    background: var(--ab-primary-soft); border: 1px solid var(--ab-primary-border);
    border-radius: 12px; padding: 11px 14px; font-size: 12.5px; color: var(--ab-text-2); line-height: 1.5;
}
.abm-hintbox i { color: var(--ab-primary); font-size: 15px; flex-shrink: 0; }
.abm-toolbar { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 16px; }
.abm-toolbar .abm-search { flex: 1 1 220px; max-width: 340px; }
.abm-undo {
    width: 34px; height: 34px; border-radius: 10px; border: none; flex-shrink: 0;
    display: inline-flex; align-items: center; justify-content: center; cursor: pointer;
    background: var(--ab-amber-soft); color: var(--ab-amber);
    transition: all .2s; font-size: 13px;
}
.abm-undo:hover { background: var(--ab-amber); color: #fff; }

/* ---------- Misc ---------- */
.abm-divider { border: none; border-top: 1px dashed var(--ab-border); margin: 14px 0; }
.abm-pulse { animation: abm-pulse 1.8s ease-in-out infinite; }
@keyframes abm-pulse { 0%, 100% { opacity: 1; } 50% { opacity: .35; } }
.abm-shimmer {
    background: linear-gradient(90deg, var(--ab-border-soft) 25%, rgba(148,163,184,.18) 50%, var(--ab-border-soft) 75%);
    background-size: 800px 100%; border-radius: 12px;
    animation: abm-shimmer 1.4s linear infinite;
}
@keyframes abm-shimmer { 0% { background-position: -800px 0; } 100% { background-position: 800px 0; } }

/* ---------- Responsive ---------- */
@media (max-width: 575.98px) {
    .abm-hero { padding: 20px 18px 18px; border-radius: 18px; }
    .abm-hero-row { flex-direction: column; align-items: stretch; }
    .abm-hero-right { flex-direction: row; align-items: center; justify-content: space-between; flex-wrap: wrap; }
    .abm-hero-actions { justify-content: flex-start; }
    .abm-hero-actions .abm-btn { flex: 1; justify-content: center; padding: 8px 10px; font-size: 11px; }
    .abm-kpi-grid { grid-template-columns: 1fr; gap: 12px; }
    .abm-kpi-num { font-size: 23px; }
    .abm-kelas-grid { grid-template-columns: 1fr; }
    .abm-stepper { min-width: 0; }
}
@media (min-width: 576px) and (max-width: 992px) {
    .abm-kpi-grid { grid-template-columns: repeat(2, 1fr); gap: 14px; }
    .abm-kelas-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (min-width: 993px) and (max-width: 1300px) {
    .abm-kelas-grid { grid-template-columns: repeat(2, 1fr); }
}

/* ---------- Reduced motion ---------- */
@media (prefers-reduced-motion: reduce) {
    .abs-mod * { transition: none !important; animation: none !important; }
    .abs-mod .abm-kpi:hover, .abs-mod .abm-kelas:hover, .abs-mod .abm-btn:hover { transform: none !important; }
}
</style>
