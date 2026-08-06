<style>
/* ==========================================================================
   KELAS MODULE — Design System
   Blue primary · neutral slate · light gray canvas · white cards · 14px radius
   Aesthetic target: Linear / Stripe / Vercel / shadcn dashboard (2026)
   Namespaced under .kls-* so it can coexist with the rest of the app.
   ========================================================================== */
:root {
    --kls-bg: #f6f7f9;
    --kls-surface: #ffffff;
    --kls-surface-2: #fafbfc;
    --kls-border: #e6e8ec;
    --kls-border-strong: #d0d5dd;
    --kls-text: #101828;
    --kls-text-2: #475467;
    --kls-text-3: #98a2b3;
    --kls-primary: #2563eb;
    --kls-primary-dark: #1d4ed8;
    --kls-primary-soft: #eff4ff;
    --kls-primary-border: #c7d7fe;
    --kls-success: #12b76a;
    --kls-success-soft: #ecfdf3;
    --kls-success-border: #a6f4c5;
    --kls-warning: #f79009;
    --kls-warning-soft: #fffaeb;
    --kls-warning-border: #fedf89;
    --kls-danger: #d92d20;
    --kls-danger-soft: #fef3f2;
    --kls-danger-border: #fecdca;
    --kls-info: #2e90fa;
    --kls-info-soft: #eff8ff;
    --kls-info-border: #b2ddff;
    --kls-violet: #7a5af8;
    --kls-violet-soft: #f5f3ff;
    --kls-violet-border: #d9d2fd;
    --kls-radius: 14px;
    --kls-radius-lg: 18px;
    --kls-radius-sm: 10px;
    --kls-shadow: 0 1px 2px rgba(16, 24, 40, .04), 0 1px 3px rgba(16, 24, 40, .05);
    --kls-shadow-lg: 0 8px 24px -8px rgba(16, 24, 40, .14), 0 2px 6px -2px rgba(16, 24, 40, .06);
    --kls-shadow-hover: 0 18px 40px -18px rgba(16, 24, 40, .22);
    --kls-font: 'Inter', 'Poppins', system-ui, -apple-system, sans-serif;
    --kls-ring: 0 0 0 4px rgba(37, 99, 235, .12);
}

html.dark-mode {
    --kls-bg: #0d1117;
    --kls-surface: #161b22;
    --kls-surface-2: #1c232d;
    --kls-border: #2d333b;
    --kls-border-strong: #3d444d;
    --kls-text: #f0f3f8;
    --kls-text-2: #b8c0cc;
    --kls-text-3: #7d8794;
    --kls-primary-soft: #14233f;
    --kls-primary-border: #1f3a66;
    --kls-success-soft: #0f2b21;
    --kls-success-border: #1f4a38;
    --kls-warning-soft: #2c2110;
    --kls-warning-border: #57401a;
    --kls-danger-soft: #2c1616;
    --kls-danger-border: #57201e;
    --kls-info-soft: #0f2438;
    --kls-info-border: #1d4060;
    --kls-violet-soft: #1c1833;
    --kls-violet-border: #37305c;
    --kls-shadow: 0 1px 2px rgba(0, 0, 0, .35);
    --kls-shadow-lg: 0 10px 28px -10px rgba(0, 0, 0, .55);
    --kls-shadow-hover: 0 18px 44px -18px rgba(0, 0, 0, .6);
}

.kls-page {
    font-family: var(--kls-font);
    color: var(--kls-text);
    background: transparent;
    padding-top: 24px;
}
.kls-page *,
.kls-page *::before,
.kls-page *::after { box-sizing: border-box; }

.kls-page .page-title-content { display: none !important; }

/* ---------- Generic card ---------- */
.kls-card {
    background: var(--kls-surface);
    border: 1px solid var(--kls-border);
    border-radius: var(--kls-radius);
    box-shadow: var(--kls-shadow);
}

/* ---------- Breadcrumb ---------- */
.kls-crumb {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 6px;
    font-size: 12px;
    color: var(--kls-text-3);
    margin-bottom: 14px;
}
.kls-crumb i { font-size: 10px; opacity: .7; }
.kls-crumb a { color: var(--kls-primary); text-decoration: none; font-weight: 600; }
.kls-crumb a:hover { text-decoration: underline; }

/* ---------- Hero ---------- */
.kls-hero {
    position: relative;
    overflow: hidden;
    background: var(--kls-surface);
    border: 1px solid var(--kls-border);
    border-radius: 20px;
    box-shadow: var(--kls-shadow);
    padding: 28px 30px;
    display: grid;
    grid-template-columns: minmax(0, 1.35fr) minmax(300px, .65fr);
    gap: 28px;
    align-items: center;
    isolation: isolate;
}
.kls-hero::before {
    content: "";
    position: absolute;
    inset: 0;
    z-index: -1;
    background:
        radial-gradient(600px 220px at 88% -10%, rgba(37, 99, 235, .09), transparent 60%),
        radial-gradient(400px 200px at -10% 110%, rgba(46, 144, 250, .07), transparent 55%);
    pointer-events: none;
}
.kls-hero::after {
    content: "";
    position: absolute;
    z-index: -1;
    top: -70px;
    right: -70px;
    width: 240px;
    height: 240px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(37, 99, 235, .08), transparent 70%);
    pointer-events: none;
}
.kls-hero-main { min-width: 0; }
.kls-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .6px;
    text-transform: uppercase;
    color: var(--kls-primary);
    background: var(--kls-primary-soft);
    border: 1px solid var(--kls-primary-border);
    padding: 5px 11px;
    border-radius: 999px;
    margin-bottom: 14px;
}
.kls-hero-title {
    margin: 0;
    font-size: clamp(22px, 2.4vw, 30px);
    font-weight: 800;
    letter-spacing: -.5px;
    line-height: 1.15;
    color: var(--kls-text);
}
.kls-hero-desc {
    margin: 10px 0 0;
    max-width: 640px;
    font-size: 13.5px;
    line-height: 1.7;
    color: var(--kls-text-2);
}
.kls-hero-chips { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 18px; }
.kls-hero-stats {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 12px;
    margin-top: 22px;
    padding-top: 20px;
    border-top: 1px dashed var(--kls-border);
}
.kls-hero-stat .k { font-size: 11px; font-weight: 600; color: var(--kls-text-3); }
.kls-hero-stat .v { margin-top: 5px; font-size: 24px; font-weight: 800; letter-spacing: -.4px; color: var(--kls-text); font-variant-numeric: tabular-nums; }
.kls-hero-stat .s { margin-top: 3px; font-size: 11px; color: var(--kls-text-3); }

.kls-hero-aside {
    display: grid;
    gap: 14px;
}
.kls-hero-panel {
    background: var(--kls-surface-2);
    border: 1px solid var(--kls-border);
    border-radius: var(--kls-radius-lg);
    padding: 18px;
}
.kls-hero-panel h4 { margin: 0 0 4px; font-size: 13px; font-weight: 800; color: var(--kls-text); }
.kls-hero-panel p { margin: 0; font-size: 12px; line-height: 1.6; color: var(--kls-text-2); }
.kls-mini-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 14px; }
.kls-mini-stat { background: var(--kls-surface); border: 1px solid var(--kls-border); border-radius: 12px; padding: 12px 14px; }
.kls-mini-stat .k { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; color: var(--kls-text-3); }
.kls-mini-stat .v { margin-top: 5px; font-size: 19px; font-weight: 800; color: var(--kls-text); font-variant-numeric: tabular-nums; }

/* ---------- Chips / badges ---------- */
.kls-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    min-height: 30px;
    padding: 0 12px;
    border-radius: 999px;
    font-size: 11.5px;
    font-weight: 700;
    white-space: nowrap;
    background: var(--kls-surface-2);
    color: var(--kls-text-2);
    border: 1px solid var(--kls-border);
}
.kls-chip--blue { background: var(--kls-primary-soft); color: var(--kls-primary-dark); border-color: var(--kls-primary-border); }
.kls-chip--green { background: var(--kls-success-soft); color: var(--kls-success); border-color: var(--kls-success-border); }
.kls-chip--amber { background: var(--kls-warning-soft); color: #b54708; border-color: var(--kls-warning-border); }
.kls-chip--violet { background: var(--kls-violet-soft); color: var(--kls-violet); border-color: var(--kls-violet-border); }
.kls-chip--neutral { background: var(--kls-surface-2); color: var(--kls-text-2); border-color: var(--kls-border); }

.kls-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    min-height: 28px;
    padding: 0 11px;
    border-radius: 8px;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .3px;
    background: var(--kls-primary-soft);
    color: var(--kls-primary-dark);
    border: 1px solid var(--kls-primary-border);
}
.kls-badge .dot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; }

/* ---------- Buttons ---------- */
.kls-btn {
    position: relative;
    overflow: hidden;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 42px;
    padding: 0 16px;
    border: 1px solid transparent;
    border-radius: var(--kls-radius-sm);
    font-family: var(--kls-font);
    font-size: 13px;
    font-weight: 700;
    color: var(--kls-text);
    text-decoration: none;
    cursor: pointer;
    transition: background .18s ease, border-color .18s ease, color .18s ease, box-shadow .18s ease, transform .18s ease;
    white-space: nowrap;
    user-select: none;
}
.kls-btn:focus-visible { outline: none; box-shadow: var(--kls-ring); }
.kls-btn:hover { transform: translateY(-1px); }
.kls-btn:active { transform: translateY(0); }
.kls-btn:disabled { opacity: .6; pointer-events: none; }

.kls-btn--primary { background: var(--kls-primary); color: #fff; box-shadow: 0 1px 2px rgba(16,24,40,.2); }
.kls-btn--primary:hover { background: var(--kls-primary-dark); color: #fff; box-shadow: 0 6px 16px -6px rgba(37,99,235,.5); }
.kls-btn--secondary { background: var(--kls-surface); color: var(--kls-text-2); border-color: var(--kls-border-strong); }
.kls-btn--secondary:hover { background: var(--kls-surface-2); color: var(--kls-text); border-color: var(--kls-primary-border); }
.kls-btn--ghost { background: transparent; color: var(--kls-text-2); }
.kls-btn--ghost:hover { background: var(--kls-surface-2); color: var(--kls-text); }
.kls-btn--soft { background: var(--kls-primary-soft); color: var(--kls-primary-dark); border-color: var(--kls-primary-border); }
.kls-btn--soft:hover { background: var(--kls-primary); color: #fff; }
.kls-btn--danger { background: var(--kls-danger); color: #fff; }
.kls-btn--danger:hover { background: #b42318; color: #fff; box-shadow: 0 6px 16px -6px rgba(217,32,39,.5); }
.kls-btn--danger-soft { background: var(--kls-danger-soft); color: var(--kls-danger); border-color: var(--kls-danger-border); }
.kls-btn--danger-soft:hover { background: var(--kls-danger); color: #fff; }
.kls-btn--sm { min-height: 34px; padding: 0 12px; font-size: 12px; border-radius: 9px; }
.kls-btn--block { width: 100%; }

.kls-icon-btn {
    width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: var(--kls-radius-sm);
    border: 1px solid var(--kls-border);
    background: var(--kls-surface);
    color: var(--kls-text-2);
    font-size: 15px;
    cursor: pointer;
    text-decoration: none;
    transition: all .16s ease;
}
.kls-icon-btn:hover { transform: translateY(-2px); box-shadow: var(--kls-shadow-lg); }
.kls-icon-btn:focus-visible { outline: none; box-shadow: var(--kls-ring); }
.kls-icon-btn--blue { color: var(--kls-primary); }
.kls-icon-btn--blue:hover { background: var(--kls-primary-soft); border-color: var(--kls-primary-border); color: var(--kls-primary-dark); }
.kls-icon-btn--amber { color: #dc6803; }
.kls-icon-btn--amber:hover { background: var(--kls-warning-soft); border-color: var(--kls-warning-border); color: #b54708; }
.kls-icon-btn--green { color: var(--kls-success); }
.kls-icon-btn--green:hover { background: var(--kls-success-soft); border-color: var(--kls-success-border); color: #027a48; }
.kls-icon-btn--red { color: var(--kls-danger); }
.kls-icon-btn--red:hover { background: var(--kls-danger-soft); border-color: var(--kls-danger-border); color: #b42318; }
.kls-icon-btn--violet { color: var(--kls-violet); }
.kls-icon-btn--violet:hover { background: var(--kls-violet-soft); border-color: var(--kls-violet-border); color: #6941c6; }

/* ---------- KPI ---------- */
.kls-kpi-grid { display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 16px; margin-top: 18px; }
.kls-kpi {
    position: relative;
    overflow: hidden;
    background: var(--kls-surface);
    border: 1px solid var(--kls-border);
    border-radius: var(--kls-radius);
    box-shadow: var(--kls-shadow);
    padding: 18px;
    transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
}
.kls-kpi::after {
    content: "";
    position: absolute;
    right: -22px;
    bottom: -26px;
    width: 84px;
    height: 84px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(37,99,235,.08), transparent 70%);
    pointer-events: none;
}
.kls-kpi:hover { transform: translateY(-3px); box-shadow: var(--kls-shadow-hover); border-color: var(--kls-primary-border); }
.kls-kpi-top { display: flex; align-items: center; justify-content: space-between; gap: 10px; }
.kls-kpi-ico {
    width: 42px; height: 42px;
    border-radius: 12px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}
.kls-kpi-ico.blue { background: var(--kls-primary-soft); color: var(--kls-primary); }
.kls-kpi-ico.green { background: var(--kls-success-soft); color: var(--kls-success); }
.kls-kpi-ico.amber { background: var(--kls-warning-soft); color: #dc6803; }
.kls-kpi-ico.violet { background: var(--kls-violet-soft); color: var(--kls-violet); }
.kls-kpi-ico.info { background: var(--kls-info-soft); color: var(--kls-info); }
.kls-kpi-tag {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 10px; font-weight: 800; letter-spacing: .4px;
    text-transform: uppercase;
    color: var(--kls-text-3);
}
.kls-kpi-num { margin-top: 14px; font-size: 26px; font-weight: 800; letter-spacing: -.5px; line-height: 1; color: var(--kls-text); font-variant-numeric: tabular-nums; }
.kls-kpi-label { margin-top: 7px; font-size: 12px; font-weight: 600; color: var(--kls-text-2); }
.kls-kpi-sub { margin-top: 3px; font-size: 11px; color: var(--kls-text-3); }
.kls-kpi-bar { margin-top: 12px; height: 5px; border-radius: 99px; background: var(--kls-surface-2); border: 1px solid var(--kls-border); overflow: hidden; }
.kls-kpi-bar > span { display: block; height: 100%; width: 0; border-radius: 99px; background: var(--kls-primary); transition: width .9s cubic-bezier(.4,0,.2,1); }

/* ---------- Toolbar ---------- */
.kls-toolbar {
    position: sticky;
    top: 78px;
    z-index: 940;
    background: var(--kls-surface);
    border: 1px solid var(--kls-border);
    border-radius: var(--kls-radius-lg);
    box-shadow: var(--kls-shadow-lg);
    padding: 16px 18px;
    margin-top: 18px;
    display: grid;
    gap: 14px;
}
.kls-toolbar-top { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
.kls-toolbar-title { display: flex; align-items: center; gap: 10px; }
.kls-toolbar-title h2 { margin: 0; font-size: 16px; font-weight: 800; color: var(--kls-text); }
.kls-toolbar-title p { margin: 3px 0 0; font-size: 12px; color: var(--kls-text-3); }
.kls-toolbar-row { display: grid; grid-template-columns: 1.2fr .5fr .45fr auto auto; gap: 12px; align-items: end; }
.kls-field { display: flex; flex-direction: column; gap: 6px; }
.kls-field > label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; color: var(--kls-text-3); }
.kls-input-wrap { position: relative; display: flex; align-items: center; }
.kls-input-wrap > i { position: absolute; left: 13px; color: var(--kls-text-3); font-size: 15px; pointer-events: none; }
.kls-input,
.kls-select {
    width: 100%;
    min-height: 44px;
    border: 1px solid var(--kls-border-strong);
    border-radius: var(--kls-radius-sm);
    background: var(--kls-surface);
    color: var(--kls-text);
    font-size: 13px;
    font-family: var(--kls-font);
    padding: 0 14px;
    transition: border-color .16s ease, box-shadow .16s ease;
    appearance: none;
    -webkit-appearance: none;
}
.kls-input-wrap .kls-input { padding-left: 40px; }
.kls-input::placeholder { color: var(--kls-text-3); }
.kls-input:focus, .kls-select:focus { outline: none; border-color: var(--kls-primary); box-shadow: var(--kls-ring); }
.kls-select {
    cursor: pointer;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' fill='none' viewBox='0 0 16 16'%3E%3Cpath fill='%2398a2b3' d='M8 11L2 5h12l-6 6z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    padding-right: 36px;
}
.kls-seg { display: inline-flex; gap: 3px; padding: 3px; background: var(--kls-surface-2); border: 1px solid var(--kls-border); border-radius: var(--kls-radius-sm); }
.kls-seg button {
    display: inline-flex; align-items: center; justify-content: center; gap: 6px;
    min-height: 36px; padding: 0 12px;
    border: none; background: transparent;
    border-radius: 8px;
    font-size: 12.5px; font-weight: 700; color: var(--kls-text-3);
    cursor: pointer; transition: all .16s ease;
    font-family: var(--kls-font);
}
.kls-seg button:hover { color: var(--kls-text-2); }
.kls-seg button.is-active { background: var(--kls-surface); color: var(--kls-primary); box-shadow: var(--kls-shadow); }

/* ---------- Card grid (primary class list) ---------- */
.kls-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 16px; margin-top: 18px; }
.kls-classcard {
    position: relative;
    display: flex;
    flex-direction: column;
    background: var(--kls-surface);
    border: 1px solid var(--kls-border);
    border-radius: var(--kls-radius-lg);
    box-shadow: var(--kls-shadow);
    padding: 20px;
    transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
}
.kls-classcard:hover { transform: translateY(-3px); box-shadow: var(--kls-shadow-hover); border-color: var(--kls-primary-border); }
.kls-classcard-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; }
.kls-classcard-title { display: flex; align-items: center; gap: 12px; min-width: 0; }
.kls-class-ico {
    width: 46px; height: 46px; flex-shrink: 0;
    border-radius: 14px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 20px;
    color: #fff;
    box-shadow: 0 8px 16px -8px rgba(16,24,40,.4);
}
.kls-class-ico.mi { background: linear-gradient(135deg, #16a34a, #4ade80); }
.kls-class-ico.mts { background: linear-gradient(135deg, #2563eb, #60a5fa); }
.kls-class-ico.ma { background: linear-gradient(135deg, #dc6803, #fbbf24); }
.kls-class-ico.default { background: linear-gradient(135deg, #475467, #98a2b3); }
.kls-classcard-name { font-size: 16px; font-weight: 800; color: var(--kls-text); letter-spacing: -.2px; line-height: 1.2; }
.kls-classcard-meta { margin-top: 3px; font-size: 12px; color: var(--kls-text-3); }
.kls-classcard-body { margin-top: 16px; display: grid; gap: 10px; }
.kls-classcard-row { display: flex; align-items: center; gap: 9px; font-size: 12.5px; color: var(--kls-text-2); }
.kls-classcard-row i { width: 16px; text-align: center; color: var(--kls-text-3); font-size: 13px; }
.kls-classcard-row b { color: var(--kls-text); font-weight: 700; }
.kls-capacity { margin-top: 4px; }
.kls-capacity-top { display: flex; align-items: center; justify-content: space-between; font-size: 11px; color: var(--kls-text-3); margin-bottom: 6px; }
.kls-capacity-top b { color: var(--kls-text-2); font-weight: 700; }
.kls-progress { height: 7px; border-radius: 99px; background: var(--kls-surface-2); border: 1px solid var(--kls-border); overflow: hidden; }
.kls-progress > span { display: block; height: 100%; border-radius: 99px; background: var(--kls-primary); }
.kls-progress.green > span { background: var(--kls-success); }
.kls-progress.amber > span { background: var(--kls-warning); }
.kls-progress.red > span { background: var(--kls-danger); }
.kls-classcard-foot {
    margin-top: 16px;
    padding-top: 14px;
    border-top: 1px solid var(--kls-border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
}
.kls-updated { font-size: 10.5px; color: var(--kls-text-3); }
.kls-actions { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }

/* ---------- List / table view ---------- */
.kls-listcard { display: none; margin-top: 18px; }
.kls-table { width: 100%; border-collapse: collapse; }
.kls-table thead th {
    font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: .4px;
    color: var(--kls-text-3); text-align: left; padding: 12px 16px; border-bottom: 1px solid var(--kls-border);
    white-space: nowrap; background: var(--kls-surface-2);
}
.kls-table thead th:first-child { border-radius: 12px 0 0 12px; }
.kls-table thead th:last-child { border-radius: 0 12px 12px 0; }
.kls-table tbody td { padding: 14px 16px; font-size: 13px; color: var(--kls-text-2); border-bottom: 1px solid var(--kls-border); vertical-align: middle; }
.kls-table tbody tr:last-child td { border-bottom: none; }
.kls-table tbody tr { transition: background .14s ease; }
.kls-table tbody tr:hover { background: var(--kls-surface-2); }
.kls-table .num { color: var(--kls-text-3); font-weight: 600; }

/* ---------- Avatar ---------- */
.kls-avatar {
    width: 38px; height: 38px; flex-shrink: 0;
    border-radius: 12px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 13px; font-weight: 800; letter-spacing: .3px;
    color: #fff;
}
.kls-avatar.blue { background: linear-gradient(135deg, #2563eb, #60a5fa); }
.kls-avatar.green { background: linear-gradient(135deg, #12b76a, #53d595); }
.kls-avatar.amber { background: linear-gradient(135deg, #dc6803, #f79009); }
.kls-avatar.violet { background: linear-gradient(135deg, #7a5af8, #a78bfa); }
.kls-avatar.red { background: linear-gradient(135deg, #d92d20, #f97066); }
.kls-avatar.info { background: linear-gradient(135deg, #2e90fa, #7cc4fa); }

/* ---------- Section card w/ header ---------- */
.kls-panel { margin-top: 18px; }
.kls-panel-head {
    display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;
    padding: 18px 20px 16px;
    border-bottom: 1px solid var(--kls-border);
}
.kls-panel-title h3 { margin: 0; font-size: 15px; font-weight: 800; color: var(--kls-text); display: flex; align-items: center; gap: 9px; }
.kls-panel-title p { margin: 4px 0 0; font-size: 12px; color: var(--kls-text-3); }
.kls-panel-body { padding: 16px 20px 20px; }

/* ---------- Empty state ---------- */
.kls-empty { text-align: center; padding: 52px 24px; }
.kls-empty-illus {
    width: 88px; height: 88px; margin: 0 auto 18px;
    border-radius: 28px;
    display: flex; align-items: center; justify-content: center;
    font-size: 36px; color: var(--kls-primary);
    background: var(--kls-primary-soft);
    border: 1px solid var(--kls-primary-border);
    animation: klsFloat 3.4s ease-in-out infinite;
}
.kls-empty h4 { margin: 0; font-size: 17px; font-weight: 800; color: var(--kls-text); }
.kls-empty p { margin: 8px auto 0; max-width: 420px; font-size: 13px; line-height: 1.7; color: var(--kls-text-3); }

/* ---------- Modal ---------- */
.kls-modal .modal-content {
    border: 1px solid var(--kls-border);
    border-radius: 20px;
    box-shadow: 0 30px 70px -30px rgba(16,24,40,.35);
    overflow: hidden;
    background: var(--kls-surface);
}
.kls-modal .modal-dialog { max-width: 560px; }
.kls-modal .modal-dialog--lg { max-width: 720px; }
.kls-modal-head {
    display: flex; align-items: flex-start; justify-content: space-between; gap: 12px;
    padding: 22px 24px 18px;
    border-bottom: 1px solid var(--kls-border);
}
.kls-modal-head-inner { display: flex; align-items: flex-start; gap: 12px; }
.kls-modal-ico {
    width: 46px; height: 46px; flex-shrink: 0;
    border-radius: 14px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 20px;
}
.kls-modal-ico.blue { background: var(--kls-primary-soft); color: var(--kls-primary); }
.kls-modal-ico.green { background: var(--kls-success-soft); color: var(--kls-success); }
.kls-modal-ico.amber { background: var(--kls-warning-soft); color: #dc6803; }
.kls-modal-ico.red { background: var(--kls-danger-soft); color: var(--kls-danger); }
.kls-modal-ico.violet { background: var(--kls-violet-soft); color: var(--kls-violet); }
.kls-modal-title { margin: 0; font-size: 18px; font-weight: 800; color: var(--kls-text); letter-spacing: -.2px; }
.kls-modal-sub { margin: 4px 0 0; font-size: 12.5px; line-height: 1.6; color: var(--kls-text-3); }
.kls-modal-body { padding: 22px 24px; }
.kls-modal-foot {
    display: flex; align-items: center; justify-content: flex-end; gap: 10px; flex-wrap: wrap;
    padding: 16px 24px 20px;
    border-top: 1px solid var(--kls-border);
    background: var(--kls-surface-2);
}
.kls-form-grid { display: grid; gap: 18px; }

/* Floating labels (Bootstrap form-floating restyled) */
.kls-modal .form-floating > label { font-size: 12.5px; color: var(--kls-text-3); padding-left: 15px; }
.kls-modal .form-floating > .form-control,
.kls-modal .form-floating > .form-select {
    min-height: 54px; height: auto;
    border: 1px solid var(--kls-border-strong);
    border-radius: 12px;
    background: var(--kls-surface);
    color: var(--kls-text);
    font-size: 13.5px;
    padding: 1.5rem .9rem .5rem;
    transition: border-color .16s ease, box-shadow .16s ease;
}
.kls-modal .form-floating > .form-select { padding-top: 1.35rem; padding-bottom: .5rem; }
.kls-modal .form-floating > .form-control:focus,
.kls-modal .form-floating > .form-select:focus { border-color: var(--kls-primary); box-shadow: var(--kls-ring); }
.kls-modal .form-floating > .form-control.is-invalid,
.kls-modal .form-floating > .form-select.is-invalid { border-color: var(--kls-danger); box-shadow: 0 0 0 4px rgba(217,32,39,.1); }
.kls-field-msg { display: flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600; margin-top: 6px; }
.kls-field-msg.ok { color: var(--kls-success); }
.kls-field-msg.err { color: var(--kls-danger); }

/* Loading button spinner */
.kls-spinner {
    width: 14px; height: 14px; flex-shrink: 0;
    border-radius: 50%;
    border: 2px solid currentColor;
    border-top-color: transparent;
    animation: klsSpin .7s linear infinite;
}

/* Success check animation */
.kls-success-anim { text-align: center; padding: 26px 8px 10px; }
.kls-success-ring {
    width: 74px; height: 74px; margin: 0 auto 16px;
    border-radius: 50%;
    background: var(--kls-success-soft);
    border: 1px solid var(--kls-success-border);
    display: flex; align-items: center; justify-content: center;
    color: var(--kls-success); font-size: 32px;
    animation: klsPop .45s cubic-bezier(.34,1.56,.64,1) both;
}
.kls-success-anim h4 { margin: 0; font-size: 17px; font-weight: 800; color: var(--kls-text); }
.kls-success-anim p { margin: 6px 0 0; font-size: 13px; color: var(--kls-text-3); }

/* ---------- Confirm dialog ---------- */
.kls-confirm-ico {
    width: 64px; height: 64px; margin: 0 auto 14px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 26px;
}
.kls-confirm-ico.red { background: var(--kls-danger-soft); color: var(--kls-danger); }
.kls-confirm-ico.amber { background: var(--kls-warning-soft); color: #dc6803; }
.kls-confirm-ico.blue { background: var(--kls-primary-soft); color: var(--kls-primary); }
.kls-confirm-title { font-size: 17px; font-weight: 800; color: var(--kls-text); }
.kls-confirm-text { margin-top: 6px; font-size: 13px; line-height: 1.65; color: var(--kls-text-3); }

/* ---------- Info rows (detail page) ---------- */
.kls-info-list { display: grid; gap: 0; }
.kls-info-row {
    display: flex; align-items: flex-start; justify-content: space-between; gap: 12px;
    padding: 12px 0; border-bottom: 1px dashed var(--kls-border);
}
.kls-info-row:last-child { border-bottom: none; }
.kls-info-row .k { font-size: 12px; color: var(--kls-text-3); }
.kls-info-row .v { font-size: 13px; font-weight: 700; color: var(--kls-text); text-align: right; }

/* ---------- Quick action panel ---------- */
.kls-quick { display: grid; gap: 10px; }
.kls-quick a, .kls-quick button {
    display: flex; align-items: center; gap: 12px;
    width: 100%;
    padding: 12px 14px;
    border-radius: 12px;
    border: 1px solid var(--kls-border);
    background: var(--kls-surface);
    color: var(--kls-text-2);
    font-size: 12.5px; font-weight: 700;
    text-decoration: none;
    cursor: pointer;
    transition: all .16s ease;
    font-family: var(--kls-font);
    text-align: left;
}
.kls-quick a i, .kls-quick button i {
    width: 34px; height: 34px; flex-shrink: 0;
    border-radius: 10px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 15px;
    background: var(--kls-surface-2); color: var(--kls-primary);
    transition: all .16s ease;
}
.kls-quick a:hover, .kls-quick button:hover { border-color: var(--kls-primary-border); background: var(--kls-primary-soft); color: var(--kls-primary-dark); transform: translateY(-1px); }
.kls-quick a:hover i, .kls-quick button:hover i { background: var(--kls-primary); color: #fff; }
.kls-quick a small, .kls-quick button small { display: block; font-size: 10.5px; font-weight: 600; color: var(--kls-text-3); }

/* ---------- Wizard ---------- */
.kls-wizard { display: flex; align-items: flex-start; gap: 4px; margin: 6px 0 22px; }
.kls-wstep { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 6px; position: relative; }
.kls-wstep::before {
    content: ""; position: absolute; top: 17px; left: -50%; width: 100%; height: 2px;
    background: var(--kls-border); z-index: 0;
}
.kls-wstep:first-child::before { display: none; }
.kls-wstep.is-done::before { background: var(--kls-primary); }
.kls-wdot {
    position: relative; z-index: 1;
    width: 34px; height: 34px; border-radius: 50%;
    display: inline-flex; align-items: center; justify-content: center;
    background: var(--kls-surface); border: 2px solid var(--kls-border);
    color: var(--kls-text-3); font-size: 13px; font-weight: 800;
    transition: all .2s ease;
}
.kls-wstep.is-active .kls-wdot { border-color: var(--kls-primary); color: var(--kls-primary); box-shadow: var(--kls-ring); }
.kls-wstep.is-done .kls-wdot { background: var(--kls-primary); border-color: var(--kls-primary); color: #fff; }
.kls-wlabel { font-size: 10.5px; font-weight: 700; color: var(--kls-text-3); text-align: center; }
.kls-wstep.is-active .kls-wlabel { color: var(--kls-primary); }
.kls-wstep.is-done .kls-wlabel { color: var(--kls-text-2); }

/* ---------- Schedule / weekly calendar ---------- */
.kls-cal { margin-top: 18px; }
.kls-cal-head { display: grid; grid-template-columns: 96px repeat(6, minmax(0,1fr)); gap: 8px; margin-bottom: 8px; }
.kls-cal-head > div { text-align: center; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: .4px; color: var(--kls-text-3); padding: 8px; }
.kls-cal-row { display: grid; grid-template-columns: 96px repeat(6, minmax(0,1fr)); gap: 8px; margin-bottom: 8px; }
.kls-cal-time {
    display: flex; flex-direction: column; justify-content: center; gap: 2px;
    background: var(--kls-surface-2); border: 1px solid var(--kls-border); border-radius: 12px;
    padding: 10px 12px;
}
.kls-cal-time b { font-size: 12.5px; color: var(--kls-text); font-weight: 800; }
.kls-cal-time span { font-size: 10.5px; color: var(--kls-text-3); }
.kls-cal-cell {
    min-height: 88px;
    background: var(--kls-surface); border: 1px solid var(--kls-border); border-radius: 12px;
    display: flex; align-items: stretch;
}
.kls-cal-cell.is-break { background: repeating-linear-gradient(45deg, var(--kls-surface-2), var(--kls-surface-2) 8px, var(--kls-surface) 8px, var(--kls-surface) 16px); display: flex; align-items: center; justify-content: center; color: var(--kls-text-3); font-size: 11.5px; font-weight: 700; }
.kls-slot {
    flex: 1; display: flex; flex-direction: column; justify-content: space-between; gap: 4px;
    padding: 10px 12px; border-radius: 12px; cursor: default;
    border: 1px solid transparent;
    transition: transform .16s ease, box-shadow .16s ease;
}
.kls-slot:hover { transform: translateY(-1px); box-shadow: var(--kls-shadow-lg); }
.kls-slot.mc-1 { background: var(--kls-primary-soft); border-color: var(--kls-primary-border); }
.kls-slot.mc-2 { background: var(--kls-success-soft); border-color: var(--kls-success-border); }
.kls-slot.mc-3 { background: var(--kls-warning-soft); border-color: var(--kls-warning-border); }
.kls-slot.mc-4 { background: var(--kls-violet-soft); border-color: var(--kls-violet-border); }
.kls-slot.mc-5 { background: var(--kls-info-soft); border-color: var(--kls-info-border); }
.kls-slot.mc-1 .kls-slot-ico { color: var(--kls-primary); background: var(--kls-surface); }
.kls-slot.mc-2 .kls-slot-ico { color: var(--kls-success); background: var(--kls-surface); }
.kls-slot.mc-3 .kls-slot-ico { color: #dc6803; background: var(--kls-surface); }
.kls-slot.mc-4 .kls-slot-ico { color: var(--kls-violet); background: var(--kls-surface); }
.kls-slot.mc-5 .kls-slot-ico { color: var(--kls-info); background: var(--kls-surface); }
.kls-slot-top { display: flex; align-items: flex-start; gap: 7px; }
.kls-slot-ico { width: 26px; height: 26px; flex-shrink: 0; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; font-size: 12px; }
.kls-slot-name { font-size: 12px; font-weight: 800; color: var(--kls-text); line-height: 1.3; }
.kls-slot-guru { display: flex; align-items: center; gap: 6px; font-size: 10.5px; color: var(--kls-text-2); font-weight: 600; }
.kls-slot-time { display: flex; align-items: center; gap: 5px; font-size: 10px; color: var(--kls-text-3); font-weight: 600; }

/* ---------- Toasts ---------- */
.kls-toasts { position: fixed; top: 88px; right: 18px; z-index: 1200; display: grid; gap: 10px; width: min(360px, calc(100vw - 28px)); pointer-events: none; }
.kls-toast {
    display: flex; align-items: flex-start; gap: 12px;
    padding: 14px 16px;
    background: var(--kls-surface); border: 1px solid var(--kls-border);
    border-radius: 14px; box-shadow: var(--kls-shadow-hover);
    pointer-events: auto;
    opacity: 0; transform: translateY(-8px);
    transition: opacity .22s ease, transform .22s ease;
}
.kls-toast.is-show { opacity: 1; transform: translateY(0); }
.kls-toast-ico { width: 38px; height: 38px; flex-shrink: 0; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; font-size: 17px; }
.kls-toast.ok .kls-toast-ico { background: var(--kls-success-soft); color: var(--kls-success); }
.kls-toast.err .kls-toast-ico { background: var(--kls-danger-soft); color: var(--kls-danger); }
.kls-toast-t { font-size: 13px; font-weight: 800; color: var(--kls-text); }
.kls-toast-s { margin-top: 2px; font-size: 12px; line-height: 1.55; color: var(--kls-text-2); }

/* ---------- Skeleton ---------- */
.kls-skeleton { display: grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap: 16px; margin-top: 18px; }
.kls-skel-card { height: 230px; border-radius: var(--kls-radius-lg); background: linear-gradient(90deg, #eef0f3 25%, #e6e8ec 37%, #eef0f3 63%); background-size: 400% 100%; animation: klsShimmer 1.3s ease infinite; }
html.dark-mode .kls-skel-card { background: linear-gradient(90deg, #1c232d 25%, #232b36 37%, #1c232d 63%); background-size: 400% 100%; animation: klsShimmer 1.3s ease infinite; }

/* ---------- Alert (inline flash) ---------- */
.kls-alert {
    display: flex; align-items: flex-start; gap: 12px;
    border-radius: 12px; padding: 13px 16px;
    font-size: 13px; line-height: 1.6;
    border: 1px solid transparent;
}
.kls-alert i { margin-top: 2px; font-size: 17px; }
.kls-alert b { display: block; font-size: 13px; font-weight: 800; }
.kls-alert.ok { background: var(--kls-success-soft); border-color: var(--kls-success-border); color: #027a48; }
.kls-alert.err { background: var(--kls-danger-soft); border-color: var(--kls-danger-border); color: #b42318; }
html.dark-mode .kls-alert.ok { color: #6ce9a6; }
html.dark-mode .kls-alert.err { color: #fda29b; }

/* ---------- Student workspace (guru) ---------- */
.kls-ws-hero { display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; }
.kls-ws-num { display: grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap: 14px; margin-top: 18px; }
.kls-ws-table { margin-top: 18px; }

/* ---------- Animations ---------- */
@keyframes klsSpin { to { transform: rotate(360deg); } }
@keyframes klsShimmer { 0% { background-position: 100% 0; } 100% { background-position: 0 0; } }
@keyframes klsFloat { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }
@keyframes klsPop { from { transform: scale(.5); opacity: 0; } to { transform: scale(1); opacity: 1; } }

/* ---------- Focus / touch ---------- */
.kls-page a:focus-visible,
.kls-page button:focus-visible { outline: none; box-shadow: var(--kls-ring); }
.kls-page .kls-icon-btn, .kls-page .kls-btn, .kls-page .kls-quick a, .kls-page .kls-quick button { touch-action: manipulation; }

/* ---------- Reduced motion ---------- */
@media (prefers-reduced-motion: reduce) {
    .kls-page *, .kls-page *::before, .kls-page *::after { animation: none !important; transition: none !important; }
}

/* ---------- Responsive ---------- */
@media (max-width: 1199.98px) {
    .kls-kpi-grid { grid-template-columns: repeat(3, minmax(0,1fr)); }
    .kls-grid { grid-template-columns: repeat(2, minmax(0,1fr)); }
    .kls-skeleton { grid-template-columns: repeat(2, minmax(0,1fr)); }
    .kls-hero { grid-template-columns: 1fr; }
    .kls-toolbar-row { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 991.98px) {
    .kls-toolbar { top: 70px; }
    .kls-ws-num { grid-template-columns: repeat(2, minmax(0,1fr)); }
    .kls-cal-head, .kls-cal-row { grid-template-columns: 76px repeat(6, minmax(0,1fr)); gap: 6px; }
}
@media (max-width: 767.98px) {
    .kls-page { padding-top: 18px; }
    .kls-hero { padding: 22px 20px; border-radius: 16px; }
    .kls-hero-stats { grid-template-columns: repeat(2, minmax(0,1fr)); }
    .kls-grid, .kls-skeleton { grid-template-columns: 1fr; }
    .kls-kpi-grid { grid-template-columns: repeat(2, minmax(0,1fr)); }
    .kls-toolbar-row { grid-template-columns: 1fr; }
    .kls-table-wrap { overflow-x: auto; }
    .kls-cal-scroll { overflow-x: auto; }
    .kls-cal-head, .kls-cal-row { min-width: 680px; }
}
@media (max-width: 575.98px) {
    .kls-kpi-grid { grid-template-columns: 1fr; }
    .kls-hero-stats { grid-template-columns: 1fr 1fr; }
    .kls-mini-grid { grid-template-columns: 1fr; }
    .kls-ws-num { grid-template-columns: 1fr 1fr; }
    .kls-modal .modal-dialog { margin: 12px; }
}
</style>
