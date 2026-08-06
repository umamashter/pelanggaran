{{-- ============================================================
     HAFLAH MODULE — Design System premium Modul Haflatul Imtihan
     Identitas: ungu #7C3AED + aksen orange #F97316.
     Wajib di-include di setiap halaman haflah (role admin).
     Skema: premium dashboard, rounded 16px, glass, glow accent,
     dark-mode ready via `html.dark-mode`.
     ============================================================ --}}

@php
if (!function_exists('hf_status_chip')) {
    function hf_status_chip($status)
    {
        return match ($status) {
            'Aktif' => 'hf-chip--green',
            'Persiapan' => 'hf-chip--amber',
            'Selesai' => 'hf-chip--violet',
            default => 'hf-chip--slate'
        };
    }
}
if (!function_exists('hf_status_icon')) {
    function hf_status_icon($status)
    {
        return match ($status) {
            'Aktif' => 'fa-play-circle',
            'Persiapan' => 'fa-clock',
            'Selesai' => 'fa-box-archive',
            default => 'fa-circle'
        };
    }
}
if (!function_exists('hf_seg_color')) {
    function hf_seg_color($i)
    {
        $palette = ['#7c3aed', '#f97316', '#10b981', '#f59e0b', '#ec4899', '#06b6d4', '#8b5cf6', '#22c55e'];
        return $palette[$i % count($palette)];
    }
}
if (!function_exists('hf_mini_bar')) {
    function hf_mini_bar($values)
    {
        $values = array_values(array_map(function ($v) {
            return max(0, (int) $v);
        }, $values));
        $total = array_sum($values);
        if ($total <= 0) {
            return '<span class="hf-bar hf-bar--empty"><i></i></span>';
        }
        $max = max($values) ?: 1;
        $bars = '';
        foreach ($values as $v) {
            $h = max(12, (int) round(($v / $max) * 100));
            $bars .= '<i style="height:' . $h . '%;"></i>';
        }
        return '<span class="hf-bar">' . $bars . '</span>';
    }
}
if (!function_exists('hf_dist_segs')) {
    function hf_dist_segs($values)
    {
        $values = array_values(array_map(function ($v) {
            return max(0, (int) $v);
        }, $values));
        $total = array_sum($values);
        if ($total <= 0) {
            return '<span class="hf-dist hf-dist--empty"><i></i></span>';
        }
        $seg = '';
        foreach ($values as $k => $v) {
            if ($v <= 0) {
                continue;
            }
            $w = ($v / $total) * 100;
            $seg .= '<i style="width:' . $w . '%;background:' . hf_seg_color($k) . ';" title="' . $v . '"></i>';
        }
        return '<span class="hf-dist">' . $seg . '</span>';
    }
}
@endphp

<style>
.hf-mod {
    --hf-primary: #7c3aed;
    --hf-primary-2: #8b5cf6;
    --hf-primary-3: #a78bfa;
    --hf-primary-dark: #6d28d9;
    --hf-primary-soft: rgba(124, 58, 237, .10);
    --hf-primary-border: rgba(124, 58, 237, .30);
    --hf-accent: #f97316;
    --hf-accent-2: #fb923c;
    --hf-accent-soft: rgba(249, 115, 22, .10);
    --hf-accent-border: rgba(249, 115, 22, .32);
    --hf-grad: linear-gradient(135deg, #7c3aed 0%, #8b5cf6 55%, #a78bfa 100%);
    --hf-grad-rad: radial-gradient(120% 130% at 8% 0%, rgba(255,255,255,.22) 0%, rgba(255,255,255,0) 45%), linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
    --hf-grad-soft: linear-gradient(135deg, rgba(124,58,237,.10) 0%, rgba(249,115,22,.06) 100%);
    --hf-bg: #f4f4f8;
    --hf-card: #ffffff;
    --hf-border: #e4e4ee;
    --hf-border-soft: #f1f1f6;
    --hf-text: #18181b;
    --hf-text-2: #52525b;
    --hf-text-3: #a1a1aa;
    --hf-shadow: 0 1px 2px rgba(24,24,27,.05), 0 6px 20px -6px rgba(24,24,27,.10);
    --hf-shadow-lg: 0 28px 64px -14px rgba(24,24,27,.24);
    --hf-radius: 16px;
    --hf-radius-lg: 22px;
    --hf-green: #10b981; --hf-green-soft: rgba(16,185,129,.10); --hf-green-border: rgba(16,185,129,.32);
    --hf-amber: #d97706; --hf-amber-soft: rgba(217,119,6,.10); --hf-amber-border: rgba(217,119,6,.32);
    --hf-red: #e11d48; --hf-red-soft: rgba(225,29,72,.10); --hf-red-border: rgba(225,29,72,.32);
    --hf-sky: #0891b2; --hf-sky-soft: rgba(8,145,178,.10); --hf-sky-border: rgba(8,145,178,.32);
    --hf-violet: #7c3aed; --hf-violet-soft: rgba(124,58,237,.10); --hf-violet-border: rgba(124,58,237,.30);
    --hf-rose: #db2777; --hf-rose-soft: rgba(219,39,119,.10); --hf-rose-border: rgba(219,39,119,.32);

    font-family: 'Plus Jakarta Sans', 'Poppins', system-ui, -apple-system, sans-serif;
    color: var(--hf-text);
}
html.dark-mode .hf-mod {
    --hf-bg: rgba(148,163,184,.06);
    --hf-card: rgba(255,255,255,.055);
    --hf-border: rgba(255,255,255,.13);
    --hf-border-soft: rgba(255,255,255,.06);
    --hf-text: #f4f4f5;
    --hf-text-2: #d4d4d8;
    --hf-text-3: #8e8e96;
    --hf-primary-soft: rgba(167,139,250,.18);
    --hf-primary-border: rgba(167,139,250,.48);
    --hf-accent-soft: rgba(251,146,60,.16);
    --hf-accent-border: rgba(251,146,60,.48);
    --hf-grad-soft: linear-gradient(135deg, rgba(124,58,237,.16) 0%, rgba(249,115,22,.08) 100%);
    --hf-shadow: 0 4px 20px -6px rgba(0,0,0,.5);
    --hf-shadow-lg: 0 34px 80px -14px rgba(0,0,0,.65);
}
.hf-mod a { text-decoration: none !important; }
.hf-mod [data-bs-toggle="modal"], .hf-mod .hf-clickable, .hf-mod .hf-btn, .hf-mod .hf-qn-card { cursor: pointer; }
.hf-mod :focus-visible { outline: 2px solid var(--hf-primary); outline-offset: 2px; border-radius: 8px; }

/* ---------- Buttons ---------- */
.hf-btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 7px;
    border: 1px solid var(--hf-border); border-radius: 13px; padding: 10px 18px;
    font-size: 13px; font-weight: 600; font-family: inherit;
    background: var(--hf-card); color: var(--hf-text);
    transition: all .2s ease; position: relative; overflow: hidden; white-space: nowrap;
}
.hf-btn i { font-size: 14px; }
.hf-btn:hover { transform: translateY(-1px); box-shadow: var(--hf-shadow); color: var(--hf-text); }
.hf-btn:active { transform: translateY(0) scale(.98); }
.hf-btn:disabled { opacity: .55; cursor: not-allowed; transform: none; box-shadow: none; }
.hf-btn--solid { background: var(--hf-grad); color: #fff; border-color: transparent; box-shadow: 0 8px 22px -8px rgba(124,58,237,.6); }
.hf-btn--solid:hover { box-shadow: 0 12px 28px -8px rgba(124,58,237,.68); color: #fff; }
.hf-btn--soft { background: var(--hf-primary-soft); color: var(--hf-primary); border-color: var(--hf-primary-border); }
.hf-btn--soft:hover { background: rgba(124,58,237,.17); color: var(--hf-primary); }
.hf-btn--accent { background: var(--hf-accent); color: #fff; border-color: transparent; box-shadow: 0 8px 22px -8px rgba(249,115,22,.55); }
.hf-btn--accent:hover { background: #ea580c; color: #fff; }
.hf-btn--ghost { background: transparent; border-color: transparent; color: var(--hf-text-2); }
.hf-btn--ghost:hover { background: var(--hf-bg); color: var(--hf-text); }
.hf-btn--light { background: rgba(255,255,255,.16); color: #fff; border-color: rgba(255,255,255,.30); backdrop-filter: blur(6px); }
.hf-btn--light:hover { background: rgba(255,255,255,.28); color: #fff; }
.hf-btn--outline { background: transparent; color: var(--hf-primary); border-color: var(--hf-primary-border); }
.hf-btn--outline:hover { background: var(--hf-primary-soft); color: var(--hf-primary); }
.hf-btn--danger { background: var(--hf-red); color: #fff; border-color: transparent; box-shadow: 0 8px 22px -8px rgba(225,29,72,.5); }
.hf-btn--danger:hover { background: #be123c; color: #fff; }
.hf-btn--success { background: var(--hf-green); color: #fff; border-color: transparent; box-shadow: 0 8px 22px -8px rgba(16,185,129,.5); }
.hf-btn--success:hover { background: #059669; color: #fff; }
.hf-btn--amber { background: var(--hf-amber); color: #fff; border-color: transparent; }
.hf-btn--amber:hover { background: #b45309; color: #fff; }
.hf-btn--sm { padding: 7px 14px; font-size: 12px; border-radius: 11px; }
.hf-btn--sm i { font-size: 12px; }
.hf-btn--xs { padding: 5px 10px; font-size: 11.5px; border-radius: 10px; gap: 5px; }
.hf-btn--xs i { font-size: 11px; }
.hf-btn--block { width: 100%; }
.hf-btn--amber-soft { background: var(--hf-amber-soft); color: var(--hf-amber); border-color: var(--hf-amber-border); }
.hf-btn--amber-soft:hover { background: rgba(217,119,6,.18); color: var(--hf-amber); }
.hf-btn--danger-soft { background: var(--hf-red-soft); color: var(--hf-red); border-color: var(--hf-red-border); }
.hf-btn--danger-soft:hover { background: rgba(225,29,72,.16); color: var(--hf-red); }
.hf-btn--success-soft { background: var(--hf-green-soft); color: var(--hf-green); border-color: var(--hf-green-border); }
.hf-btn--success-soft:hover { background: rgba(16,185,129,.18); color: var(--hf-green); }
.hf-btn--accent-soft { background: var(--hf-accent-soft); color: var(--hf-accent); border-color: var(--hf-accent-border); }
.hf-btn--accent-soft:hover { background: rgba(249,115,22,.18); color: var(--hf-accent); }
.hf-btn-lock { pointer-events: none; opacity: .45; }

.hf-ripple { position: absolute; border-radius: 50%; background: rgba(255,255,255,.55); transform: scale(0); animation: hfRipple .55s ease-out forwards; pointer-events: none; }
.hf-btn--soft .hf-ripple, .hf-btn--outline .hf-ripple { background: rgba(124,58,237,.25); }
.hf-btn--accent .hf-ripple, .hf-btn--accent-soft .hf-ripple { background: rgba(255,255,255,.4); }
@keyframes hfRipple { to { transform: scale(3); opacity: 0; } }

/* ---------- Chip / Badge ---------- */
.hf-chip {
    display: inline-flex; align-items: center; gap: 6px;
    border-radius: 999px; padding: 5px 12px; font-size: 11.5px; font-weight: 600;
    background: var(--hf-bg); color: var(--hf-text-2); border: 1px solid var(--hf-border);
}
.hf-chip i { font-size: 12px; }
.hf-chip--violet { background: var(--hf-primary-soft); color: var(--hf-primary); border-color: var(--hf-primary-border); }
.hf-chip--green { background: var(--hf-green-soft); color: var(--hf-green); border-color: var(--hf-green-border); }
.hf-chip--amber { background: var(--hf-amber-soft); color: var(--hf-amber); border-color: var(--hf-amber-border); }
.hf-chip--red { background: var(--hf-red-soft); color: var(--hf-red); border-color: var(--hf-red-border); }
.hf-chip--accent { background: var(--hf-accent-soft); color: var(--hf-accent); border-color: var(--hf-accent-border); }
.hf-chip--slate { background: transparent; color: var(--hf-text-3); border-color: transparent; }
.hf-chip--glow { background: var(--hf-green); color: #fff; border-color: transparent; box-shadow: 0 0 0 4px var(--hf-green-soft), 0 6px 18px -6px rgba(16,185,129,.65); animation: hfGlow 2.2s ease-in-out infinite; }
@keyframes hfGlow { 0%,100% { box-shadow: 0 0 0 4px var(--hf-green-soft), 0 6px 18px -6px rgba(16,185,129,.65); } 50% { box-shadow: 0 0 0 8px var(--hf-green-soft), 0 6px 18px -6px rgba(16,185,129,.65); } }
.hf-chip--dot { padding-left: 9px; }
.hf-chip--dot::before { content: ""; width: 7px; height: 7px; border-radius: 50%; background: currentColor; }
html.dark-mode .hf-chip { background: rgba(255,255,255,.06); }
.hf-chip-mini { font-size: 10px; padding: 3px 9px; gap: 5px; }

/* ---------- Alerts ---------- */
.hf-alert { display: flex; align-items: center; gap: 12px; border-radius: 15px; padding: 13px 16px; font-size: 13px; font-weight: 600;
    margin-bottom: 18px; border: 1px solid var(--hf-border); background: var(--hf-card); box-shadow: var(--hf-shadow); }
.hf-alert i { font-size: 16px; flex-shrink: 0; }
.hf-alert b { font-weight: 700; }
.hf-alert span { font-weight: 500; opacity: .88; }
.hf-alert ul { font-weight: 500; opacity: .92; }
.hf-alert--warn { border-color: var(--hf-amber-border); background: var(--hf-amber-soft); color: var(--hf-amber); }
.hf-alert--err { border-color: var(--hf-red-border); background: var(--hf-red-soft); color: var(--hf-red); }
.hf-alert--ok { border-color: var(--hf-green-border); background: var(--hf-green-soft); color: var(--hf-green); }
.hf-alert--accent { border-color: var(--hf-accent-border); background: var(--hf-accent-soft); color: var(--hf-accent); }
.hf-alert-close { margin-left: auto; background: none; border: none; font-size: 15px; cursor: pointer; line-height: 1; padding: 0 2px; }

/* ---------- Hero ---------- */
.hf-hero {
    position: relative; overflow: hidden;
    background: var(--hf-grad-rad); color: #fff;
    border-radius: var(--hf-radius-lg); padding: 26px 28px; margin-bottom: 20px;
    box-shadow: 0 24px 48px -18px rgba(109,40,217,.55);
}
.hf-hero::before { content: ""; position: absolute; inset: 0; pointer-events: none;
    background-image: radial-gradient(rgba(255,255,255,.15) 1px, transparent 1px); background-size: 22px 22px; opacity: .4; }
.hf-hero::after { content: ""; position: absolute; right: -90px; top: -120px; width: 320px; height: 320px; border-radius: 50%;
    background: rgba(255,255,255,.09); pointer-events: none; }
.hf-hero-grid { position: relative; display: flex; flex-wrap: wrap; gap: 20px; align-items: center; justify-content: space-between; }
.hf-hero-left { display: flex; gap: 16px; align-items: flex-start; min-width: 0; flex: 1 1 380px; }
.hf-hero-icon { flex-shrink: 0; width: 56px; height: 56px; border-radius: 17px; display: inline-flex; align-items: center; justify-content: center;
    background: rgba(255,255,255,.18); border: 1px solid rgba(255,255,255,.28); backdrop-filter: blur(10px); font-size: 24px; color: #fff; }
.hf-hero-title { font-size: 22px; font-weight: 700; letter-spacing: -.3px; margin: 0 0 4px; color: #fff; }
.hf-hero-sub { font-size: 12.5px; opacity: .88; margin: 0; line-height: 1.55; max-width: 560px; }
.hf-hero-badges { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 12px; }
.hf-hero-badge { display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; border-radius: 999px; font-size: 11.5px; font-weight: 600;
    background: rgba(255,255,255,.16); border: 1px solid rgba(255,255,255,.26); backdrop-filter: blur(8px); color: #fff; }
.hf-hero-badge i { font-size: 12px; opacity: .92; }
.hf-hero-badge--ok { background: rgba(16,185,129,.4); border-color: rgba(16,185,129,.6); }
.hf-hero-badge--warn { background: rgba(217,119,6,.42); border-color: rgba(217,119,6,.6); }
.hf-hero-badge--accent { background: rgba(249,115,22,.4); border-color: rgba(249,115,22,.6); }
.hf-hero-right { display: flex; flex-wrap: wrap; gap: 8px; align-items: center; }

/* ---------- Countdown ---------- */
.hf-countdown { position: relative; display: flex; align-items: center; gap: 10px; margin-top: 16px; }
.hf-cd-box { min-width: 58px; text-align: center; border-radius: 13px; padding: 9px 10px 7px;
    background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.26); backdrop-filter: blur(8px); }
.hf-cd-num { display: block; font-size: 22px; font-weight: 700; line-height: 1; color: #fff; font-variant-numeric: tabular-nums; }
.hf-cd-lbl { display: block; font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; color: rgba(255,255,255,.78); margin-top: 3px; }
.hf-cd-sep { font-size: 20px; font-weight: 700; color: rgba(255,255,255,.55); }
.hf-cd-label { display: inline-flex; align-items: center; gap: 6px; font-size: 11px; font-weight: 700; letter-spacing: .4px;
    text-transform: uppercase; background: rgba(255,255,255,.16); border: 1px solid rgba(255,255,255,.26); border-radius: 999px; padding: 5px 12px; }

/* ---------- KPI ---------- */
.hf-kpi-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 20px; }
.hf-kpi { position: relative; overflow: hidden; background: var(--hf-card); border: 1px solid var(--hf-border); border-radius: var(--hf-radius);
    padding: 18px 20px; box-shadow: var(--hf-shadow); display: flex; align-items: center; gap: 14px; transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease; }
.hf-kpi:hover { transform: translateY(-3px); box-shadow: var(--hf-shadow-lg); border-color: var(--hf-primary-border); }
.hf-kpi-icon { flex-shrink: 0; width: 46px; height: 46px; border-radius: 14px; display: inline-flex; align-items: center; justify-content: center; font-size: 19px; }
.hf-kpi-icon.violet { background: var(--hf-primary-soft); color: var(--hf-primary); }
.hf-kpi-icon.green { background: var(--hf-green-soft); color: var(--hf-green); }
.hf-kpi-icon.amber { background: var(--hf-amber-soft); color: var(--hf-amber); }
.hf-kpi-icon.accent { background: var(--hf-accent-soft); color: var(--hf-accent); }
.hf-kpi-icon.rose { background: var(--hf-rose-soft); color: var(--hf-rose); }
.hf-kpi-icon.sky { background: var(--hf-sky-soft); color: var(--hf-sky); }
.hf-kpi-main { flex: 1; min-width: 0; }
.hf-kpi-num { font-size: 24px; font-weight: 700; letter-spacing: -.5px; line-height: 1.1; color: var(--hf-text); font-variant-numeric: tabular-nums; }
.hf-kpi-label { font-size: 11px; font-weight: 600; color: var(--hf-text-3); text-transform: uppercase; letter-spacing: .4px; margin-top: 2px; }
.hf-kpi-sub { font-size: 11px; color: var(--hf-text-3); margin-top: 2px; }
.hf-kpi-foot { margin-top: 8px; }
.hf-kpi-pct { display: inline-flex; align-items: center; gap: 4px; font-size: 10px; font-weight: 700; color: var(--hf-primary); }
.hf-kpi-pct i { font-size: 10px; }
.hf-kpi-watermark { position: absolute; right: -18px; bottom: -22px; font-size: 92px; opacity: .035; pointer-events: none; line-height: 1; }

/* distribution bar (KPI mini chart) */
.hf-dist { display: flex; height: 6px; width: 100%; border-radius: 999px; overflow: hidden; gap: 2px; background: var(--hf-bg); }
.hf-dist i { display: block; height: 100%; border-radius: 999px; transition: width .5s cubic-bezier(.22,1,.36,1); }
.hf-dist--empty { background: var(--hf-bg); }
.hf-dist--empty i { display: none; }

/* mini bar sparkline */
.hf-bar { display: flex; align-items: flex-end; gap: 3px; height: 30px; width: 100%; }
.hf-bar i { flex: 1; display: block; border-radius: 4px 4px 2px 2px; background: linear-gradient(180deg, #a78bfa, #7c3aed); min-height: 2px; transition: height .5s cubic-bezier(.22,1,.36,1); }
.hf-bar i:nth-child(3n) { background: linear-gradient(180deg, #fb923c, #f97316); }
.hf-bar i:nth-child(3n+2) { background: linear-gradient(180deg, #6ee7b7, #10b981); }
.hf-bar--empty { background: var(--hf-bg); border-radius: 6px; }
.hf-bar--empty i { display: none; }

/* ---------- Toolbar ---------- */
.hf-toolbar { position: sticky; top: 78px; z-index: 940; display: flex; flex-wrap: wrap; align-items: flex-end; gap: 10px;
    background: var(--hf-card); border: 1px solid var(--hf-border); border-radius: var(--hf-radius);
    padding: 14px 16px; margin-bottom: 18px; box-shadow: var(--hf-shadow); backdrop-filter: blur(10px); }
.hf-toolbar::before { content: ""; position: absolute; top: 0; left: 16px; right: 16px; height: 1px;
    background: linear-gradient(90deg, transparent, rgba(124,58,237,.30), transparent); opacity: 0; transition: opacity .2s; }
.hf-toolbar.is-stuck::before { opacity: 1; }
.hf-filter { display: flex; flex-direction: column; gap: 5px; min-width: 140px; }
.hf-filter label { font-size: 10px; font-weight: 700; color: var(--hf-text-3); text-transform: uppercase; letter-spacing: .5px; }
.hf-select, .hf-control { height: 40px; border-radius: 12px; border: 1.5px solid var(--hf-border); background: var(--hf-card);
    color: var(--hf-text); font-size: 13px; font-family: inherit; padding: 0 12px; width: 100%; transition: border-color .2s, box-shadow .2s; }
.hf-select:focus, .hf-control:focus { outline: none; border-color: var(--hf-primary); box-shadow: 0 0 0 3px var(--hf-primary-soft); }
.hf-control.is-invalid, .hf-select.is-invalid { border-color: var(--hf-red); box-shadow: 0 0 0 3px var(--hf-red-soft); }
.hf-control[readonly] { background: var(--hf-bg); color: var(--hf-text-3); cursor: not-allowed; }
.hf-search { position: relative; min-width: 190px; flex: 1 1 190px; }
.hf-search i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--hf-text-3); font-size: 14px; pointer-events: none; }
.hf-search .hf-control { padding-left: 36px; }
.hf-toolbar-actions { display: flex; gap: 8px; align-items: center; margin-left: auto; }

/* ---------- Card & Table ---------- */
.hf-card { background: var(--hf-card); border: 1px solid var(--hf-border); border-radius: var(--hf-radius); box-shadow: var(--hf-shadow); }
.hf-card--lift { transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease; }
.hf-card--lift:hover { transform: translateY(-4px); box-shadow: var(--hf-shadow-lg); border-color: var(--hf-primary-border); }
.hf-card-pad { padding: 22px; }
.hf-card-header { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; padding: 16px 20px; border-bottom: 1px solid var(--hf-border); }
.hf-section-title { display: flex; align-items: center; gap: 9px; font-size: 15px; font-weight: 700; color: var(--hf-text); margin: 0 0 4px; }
.hf-section-title i { color: var(--hf-primary); font-size: 17px; }
.hf-section-sub { font-size: 12px; color: var(--hf-text-3); margin-bottom: 16px; }
.hf-section-sub.mb-0 { margin-bottom: 0; }
.hf-table-card { overflow: hidden; }
.hf-mod .table-hf { margin: 0; --bs-table-bg: transparent; }
.hf-mod .table-hf > thead th { font-size: 10.5px; text-transform: uppercase; letter-spacing: .5px; color: var(--hf-text-3);
    background: var(--hf-bg); border-bottom: 1px solid var(--hf-border); padding: 12px 14px; white-space: nowrap; }
.hf-mod .table-hf > tbody td { padding: 12px 14px; font-size: 13px; color: var(--hf-text-2); border-color: var(--hf-border-soft); vertical-align: middle; }
.hf-mod .table-hf > tbody tr { transition: background .15s ease; }
.hf-mod .table-hf > tbody tr:hover td { background: var(--hf-bg); }
.hf-num { color: var(--hf-text-3); font-variant-numeric: tabular-nums; }
.hf-cell-icon { display: inline-flex; align-items: center; gap: 7px; color: var(--hf-text-2); font-size: 12.5px; white-space: nowrap; }
.hf-cell-icon i { color: var(--hf-primary); font-size: 11.5px; }
.hf-haf-name b { display: block; font-size: 13px; color: var(--hf-text); line-height: 1.35; }
.hf-haf-name .hf-chip { margin-top: 4px; }
.hf-actions { display: inline-flex; gap: 6px; }
.hf-row-aktif td { background: var(--hf-primary-soft) !important; }
.hf-row-aktif:hover td { background: var(--hf-primary-soft) !important; }
html.dark-mode .hf-row-aktif td { background: rgba(167,139,250,.16) !important; }
html.dark-mode .hf-row-aktif:hover td { background: rgba(167,139,250,.16) !important; }
.hf-pagi { display: flex; justify-content: space-between; align-items: center; gap: 10px; flex-wrap: wrap; padding: 14px 20px; border-top: 1px solid var(--hf-border); }
.hf-pagi-info { font-size: 11.5px; color: var(--hf-text-3); }
.hf-mod .pagination { margin: 0; }
.hf-mod .pagination .page-link { color: var(--hf-text-2); border-color: var(--hf-border); background: var(--hf-card); font-size: 12.5px; min-width: 32px; text-align: center; }
.hf-mod .pagination .page-link:hover { color: var(--hf-primary); border-color: var(--hf-primary-border); background: var(--hf-primary-soft); }
.hf-mod .pagination .active .page-link { background: var(--hf-grad); border-color: transparent; color: #fff; box-shadow: 0 4px 14px -4px rgba(124,58,237,.65); }
.hf-mod .pagination .disabled .page-link { opacity: .5; }

/* ---------- Timeline ---------- */
.hf-timeline { position: relative; }
.hf-tl-item { position: relative; display: flex; gap: 14px; padding-bottom: 18px; }
.hf-tl-item::before { content: ""; position: absolute; left: 15px; top: 34px; bottom: 0; width: 2px; background: var(--hf-border); }
.hf-tl-item:last-child::before { display: none; }
.hf-tl-dot { position: relative; z-index: 1; flex-shrink: 0; width: 32px; height: 32px; border-radius: 50%; background: var(--hf-card);
    border: 2px solid var(--hf-border); display: inline-flex; align-items: center; justify-content: center; color: var(--hf-text-3); font-size: 12px; }
.hf-tl-item.is-current .hf-tl-dot { background: var(--hf-grad); border-color: transparent; color: #fff; box-shadow: 0 0 0 5px var(--hf-primary-soft); }
.hf-tl-item.is-done .hf-tl-dot { background: var(--hf-green); border-color: transparent; color: #fff; }
.hf-tl-item.is-current::before, .hf-tl-item.is-done::before { background: var(--hf-primary-border); }
.hf-tl-card { flex: 1; min-width: 0; background: var(--hf-card); border: 1px solid var(--hf-border); border-radius: 13px; padding: 13px 16px;
    box-shadow: var(--hf-shadow); display: flex; flex-wrap: wrap; align-items: center; gap: 10px 14px; transition: all .2s ease; }
.hf-tl-item.is-current .hf-tl-card { border-color: var(--hf-primary-border); background: linear-gradient(135deg, var(--hf-primary-soft), rgba(249,115,22,.05)); }
.hf-tl-time { flex-shrink: 0; min-width: 88px; text-align: center; background: var(--hf-bg); border: 1px solid var(--hf-border); border-radius: 11px; padding: 7px 9px; }
.hf-tl-time b { display: block; font-size: 12px; color: var(--hf-text); }
.hf-tl-time span { font-size: 9.5px; color: var(--hf-text-3); font-weight: 600; }
.hf-tl-main { flex: 1; min-width: 0; }
.hf-tl-name { font-size: 13.5px; font-weight: 700; color: var(--hf-text); }
.hf-tl-desc { font-size: 11.5px; color: var(--hf-text-3); margin-top: 1px; }
.hf-tl-tag { flex-shrink: 0; }

/* ---------- Wizard ---------- */
.hf-stepper { display: flex; align-items: center; gap: 0; margin-bottom: 24px; }
.hf-step { display: flex; align-items: center; gap: 10px; flex: 1; }
.hf-step:last-child { flex: 0 0 auto; }
.hf-step-dot { width: 34px; height: 34px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;
    background: var(--hf-bg); border: 1.5px solid var(--hf-border); color: var(--hf-text-3); font-size: 13px; font-weight: 700; flex-shrink: 0; transition: all .25s ease; }
.hf-step.active .hf-step-dot { background: var(--hf-grad); border-color: transparent; color: #fff; box-shadow: 0 6px 18px -6px rgba(124,58,237,.65); }
.hf-step.done .hf-step-dot { background: var(--hf-green); border-color: transparent; color: #fff; }
.hf-step-txt { display: flex; flex-direction: column; }
.hf-step-txt b { font-size: 12px; color: var(--hf-text); }
.hf-step-txt span { font-size: 10.5px; color: var(--hf-text-3); }
.hf-step-line { flex: 1; height: 2px; background: var(--hf-border); margin: 0 14px; border-radius: 2px; position: relative; overflow: hidden; min-width: 24px; }
.hf-step-line::after { content: ""; position: absolute; inset: 0; background: var(--hf-grad); transform: scaleX(0); transform-origin: left; transition: transform .4s ease; }
.hf-step-line.done::after { transform: scaleX(1); }
.hf-wizard-pane { display: none; animation: hfFadeUp .32s ease both; }
.hf-wizard-pane.is-show { display: block; }
@keyframes hfFadeUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: none; } }
.hf-wizard-nav { display: flex; align-items: center; gap: 10px; margin-top: 26px; padding-top: 20px; border-top: 1px solid var(--hf-border); flex-wrap: wrap; }
.hf-wizard-nav .spacer { flex: 1; }

/* ---------- Form ---------- */
.hf-form-card { max-width: 880px; }
.hf-mod .form-label { font-size: 12px; font-weight: 700; color: var(--hf-text-2); margin-bottom: 6px; }
.hf-form-section { display: flex; align-items: center; gap: 9px; font-size: 13px; font-weight: 700; color: var(--hf-text); margin-bottom: 16px; }
.hf-form-section i { color: var(--hf-primary); }
.hf-form-err { font-size: 12px; color: var(--hf-red); margin-top: 5px; display: flex; align-items: center; gap: 5px; }
.hf-status-note { display: flex; align-items: flex-start; gap: 10px; border-radius: 12px; padding: 12px 14px;
    border: 1px solid var(--hf-amber-border); background: var(--hf-amber-soft); color: var(--hf-amber); font-size: 12.5px; line-height: 1.6; }
.hf-status-note b { font-weight: 700; }
.hf-lock-note { font-size: 11.5px; color: var(--hf-text-3); margin-top: 5px; display: flex; align-items: center; gap: 6px; }
.hf-form-divider { display: flex; gap: 12px; margin-top: 28px; padding-top: 20px; border-top: 1px solid var(--hf-border); flex-wrap: wrap; }

/* ---------- Preview card ---------- */
.hf-preview { display: flex; align-items: center; gap: 14px; border-radius: 15px; background: var(--hf-grad-soft);
    border: 1px solid var(--hf-primary-border); border-left: 4px solid var(--hf-primary); padding: 15px 17px; transition: border-color .2s, background .2s; }
.hf-preview.has-date { border-color: var(--hf-green-border); border-left-color: var(--hf-green); background: linear-gradient(135deg, var(--hf-green-soft), rgba(249,115,22,.05)); }
.hf-preview-icon { width: 44px; height: 44px; border-radius: 13px; background: var(--hf-card); color: var(--hf-primary);
    display: inline-flex; align-items: center; justify-content: center; font-size: 19px; flex-shrink: 0; border: 1px solid var(--hf-primary-border); }
.hf-preview.has-date .hf-preview-icon { color: var(--hf-green); border-color: var(--hf-green-border); }
.hf-preview-name { font-size: 14px; font-weight: 700; color: var(--hf-text); }
.hf-preview-meta { font-size: 11.5px; color: var(--hf-text-2); display: flex; flex-wrap: wrap; gap: 4px 12px; margin-top: 2px; }
.hf-preview-meta span { display: inline-flex; align-items: center; gap: 4px; }
.hf-preview-meta i { font-size: 11px; color: var(--hf-primary); }
.hf-preview.has-date .hf-preview-meta i { color: var(--hf-green); }

/* ---------- Warning card (ganti TA) ---------- */
.hf-warn-card { display: flex; align-items: flex-start; gap: 12px; border-radius: 14px; padding: 14px 16px; margin-bottom: 18px;
    border: 1px solid var(--hf-accent-border); background: linear-gradient(135deg, var(--hf-accent-soft), rgba(236,72,153,.06)); color: var(--hf-accent); font-size: 12.5px; line-height: 1.6; }
.hf-warn-card i { font-size: 17px; margin-top: 1px; flex-shrink: 0; }
.hf-warn-card b { font-weight: 700; }

/* ---------- Detail ---------- */
.hf-detail-hero { position: relative; overflow: hidden; background: var(--hf-grad-rad); color: #fff; border-radius: var(--hf-radius-lg);
    padding: 26px 28px; margin-bottom: 22px; box-shadow: 0 24px 48px -18px rgba(109,40,217,.55); }
.hf-detail-hero::before { content: ""; position: absolute; inset: 0; background-image: radial-gradient(rgba(255,255,255,.15) 1px, transparent 1px); background-size: 22px 22px; opacity: .4; pointer-events: none; }
.hf-detail-hero-grid { position: relative; display: flex; flex-wrap: wrap; gap: 18px; align-items: center; justify-content: space-between; }
.hf-detail-avatar { width: 62px; height: 62px; border-radius: 18px; background: rgba(255,255,255,.18); border: 1px solid rgba(255,255,255,.32);
    display: inline-flex; align-items: center; justify-content: center; font-size: 26px; color: #fff; flex-shrink: 0; backdrop-filter: blur(8px); }
.hf-detail-title { font-size: 22px; font-weight: 700; letter-spacing: -.3px; margin: 0; color: #fff; }
.hf-detail-sub { font-size: 12.5px; opacity: .88; margin-top: 3px; }
.hf-detail-meta { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 12px; }

.hf-info-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 10px; }
.hf-info-cell { background: var(--hf-bg); border: 1px solid var(--hf-border); border-radius: 13px; padding: 12px 14px; }
.hf-info-cell .lbl { font-size: 10px; font-weight: 700; color: var(--hf-text-3); text-transform: uppercase; letter-spacing: .5px; margin-bottom: 4px; display: flex; align-items: center; gap: 5px; }
.hf-info-cell .lbl i { font-size: 11px; }
.hf-info-cell .val { font-size: 13px; font-weight: 600; color: var(--hf-text); }

.hf-stat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 20px; }
.hf-stat { display: flex; align-items: center; gap: 12px; padding: 16px 18px; }
.hf-stat-icon { width: 42px; height: 42px; border-radius: 13px; display: inline-flex; align-items: center; justify-content: center; font-size: 17px; }
.hf-stat-icon.violet { background: var(--hf-primary-soft); color: var(--hf-primary); }
.hf-stat-icon.green { background: var(--hf-green-soft); color: var(--hf-green); }
.hf-stat-icon.accent { background: var(--hf-accent-soft); color: var(--hf-accent); }
.hf-stat-icon.amber { background: var(--hf-amber-soft); color: var(--hf-amber); }
.hf-stat-num { font-size: 20px; font-weight: 700; line-height: 1.1; color: var(--hf-text); }
.hf-stat-label { font-size: 11px; font-weight: 600; color: var(--hf-text-3); text-transform: uppercase; letter-spacing: .4px; }

/* ---------- Checklist kesiapan ---------- */
.hf-checklist { display: flex; flex-direction: column; gap: 8px; }
.hf-check-item { display: flex; align-items: center; gap: 12px; border-radius: 13px; padding: 12px 14px;
    background: var(--hf-bg); border: 1px solid var(--hf-border); transition: border-color .2s, transform .2s; }
.hf-check-item:hover { border-color: var(--hf-primary-border); transform: translateX(3px); }
.hf-check-item.is-ok { background: var(--hf-green-soft); border-color: var(--hf-green-border); }
.hf-check-ic { width: 36px; height: 36px; border-radius: 11px; background: var(--hf-card); color: var(--hf-text-3);
    display: inline-flex; align-items: center; justify-content: center; font-size: 15px; flex-shrink: 0; border: 1px solid var(--hf-border); }
.hf-check-item.is-ok .hf-check-ic { color: var(--hf-green); border-color: var(--hf-green-border); }
.hf-check-txt { flex: 1; min-width: 0; }
.hf-check-txt b { display: block; font-size: 12.5px; color: var(--hf-text); }
.hf-check-txt span { font-size: 11px; color: var(--hf-text-3); }
.hf-check-val { flex-shrink: 0; }

/* ---------- Quick nav ---------- */
.hf-quicknav-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; }
.hf-qn-card { --qn: var(--hf-primary); --qn-soft: var(--hf-primary-soft); --qn-border: var(--hf-primary-border);
    display: flex; align-items: center; gap: 12px; padding: 15px 16px; border-radius: 14px; background: var(--hf-card);
    border: 1px solid var(--hf-border); box-shadow: var(--hf-shadow); transition: all .2s ease; position: relative; overflow: hidden; }
.hf-qn-card:hover { transform: translateY(-3px); box-shadow: var(--hf-shadow-lg); border-color: var(--qn-border); }
.hf-qn-card::after { content: ""; position: absolute; right: -30px; top: -30px; width: 90px; height: 90px; border-radius: 50%; background: var(--qn-soft); opacity: 0; transition: opacity .2s; }
.hf-qn-card:hover::after { opacity: 1; }
.hf-qn-ic { flex-shrink: 0; width: 42px; height: 42px; border-radius: 13px; background: var(--qn-soft); color: var(--qn);
    display: inline-flex; align-items: center; justify-content: center; font-size: 17px; border: 1px solid var(--qn-border); }
.hf-qn-body { min-width: 0; position: relative; z-index: 1; }
.hf-qn-name { display: block; font-size: 12.5px; font-weight: 700; color: var(--hf-text); }
.hf-qn-sub { display: block; font-size: 10.5px; color: var(--hf-text-3); margin-top: 1px; }
.hf-qn-arrow { margin-left: auto; color: var(--hf-text-3); font-size: 13px; transition: transform .2s ease, color .2s ease; position: relative; z-index: 1; }
.hf-qn-card:hover .hf-qn-arrow { transform: translateX(3px); color: var(--qn); }
.hf-qn-card.hf-qn--violet { --qn: var(--hf-primary); --qn-soft: var(--hf-primary-soft); --qn-border: var(--hf-primary-border); }
.hf-qn-card.hf-qn--accent { --qn: var(--hf-accent); --qn-soft: var(--hf-accent-soft); --qn-border: var(--hf-accent-border); }
.hf-qn-card.hf-qn--green { --qn: var(--hf-green); --qn-soft: var(--hf-green-soft); --qn-border: var(--hf-green-border); }
.hf-qn-card.hf-qn--amber { --qn: var(--hf-amber); --qn-soft: var(--hf-amber-soft); --qn-border: var(--hf-amber-border); }
.hf-qn-card.hf-qn--rose { --qn: var(--hf-rose); --qn-soft: var(--hf-rose-soft); --qn-border: var(--hf-rose-border); }
.hf-qn-card.hf-qn--sky { --qn: var(--hf-sky); --qn-soft: var(--hf-sky-soft); --qn-border: var(--hf-sky-border); }

/* ---------- Active banner ---------- */
.hf-active { position: relative; overflow: hidden; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px;
    padding: 18px 22px; border-radius: var(--hf-radius); margin-bottom: 20px;
    background: linear-gradient(120deg, var(--hf-green-soft), rgba(249,115,22,.04));
    border: 1px solid var(--hf-green-border); box-shadow: var(--hf-shadow); }
.hf-active::before { content: ""; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; background: var(--hf-green); }
.hf-active-left { display: flex; align-items: center; gap: 14px; z-index: 1; min-width: 0; }
.hf-active-icon { width: 46px; height: 46px; border-radius: 14px; background: var(--hf-green-soft); color: var(--hf-green);
    display: inline-flex; align-items: center; justify-content: center; font-size: 19px; flex-shrink: 0; }
.hf-active .lbl { font-size: 10px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: var(--hf-green); display: flex; align-items: center; gap: 6px; }
.hf-active .name { font-size: 15px; font-weight: 700; color: var(--hf-text); line-height: 1.3; }
.hf-active .dates { font-size: 11.5px; color: var(--hf-text-2); margin-top: 2px; }
.hf-active .dates i { color: var(--hf-green); margin-right: 4px; }
.hf-active-right { display: flex; align-items: center; gap: 8px; z-index: 1; }

/* ---------- Progress ---------- */
.hf-progress { height: 8px; border-radius: 999px; background: var(--hf-bg); border: 1px solid var(--hf-border); overflow: hidden; }
.hf-progress-fill { height: 100%; border-radius: 999px; background: var(--hf-grad); transition: width .6s cubic-bezier(.22,1,.36,1); position: relative; }
.hf-progress-fill::after { content: ""; position: absolute; inset: 0; background: linear-gradient(90deg, transparent, rgba(255,255,255,.4), transparent);
    background-size: 200% 100%; animation: hfShine 1.6s linear infinite; }
@keyframes hfShine { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }

/* ---------- Empty state ---------- */
.hf-empty { text-align: center; padding: 52px 24px; }
.hf-empty-illus { position: relative; width: 124px; height: 124px; margin: 0 auto 18px; }
.hf-empty-illus .ring { position: absolute; inset: 0; border-radius: 50%; border: 2px dashed var(--hf-primary-border); animation: hfSpin 14s linear infinite; }
.hf-empty-illus .ring-2 { position: absolute; inset: 10px; border-radius: 50%; border: 2px dashed var(--hf-accent-border); animation: hfSpin 22s linear infinite reverse; }
.hf-empty-illus .core { position: absolute; inset: 22px; border-radius: 50%; background: var(--hf-grad-soft); display: inline-flex; align-items: center; justify-content: center; font-size: 42px; color: var(--hf-primary); box-shadow: inset 0 0 0 1px var(--hf-primary-border); }
@keyframes hfSpin { to { transform: rotate(360deg); } }
.hf-empty-title { font-size: 16px; font-weight: 700; color: var(--hf-text); margin-bottom: 6px; }
.hf-empty-sub { font-size: 12.5px; color: var(--hf-text-3); max-width: 400px; margin: 0 auto 18px; line-height: 1.6; }

/* ---------- Skeleton ---------- */
.hf-skeleton { position: relative; overflow: hidden; background: var(--hf-bg); border-radius: 10px; }
.hf-skeleton::after { content: ""; position: absolute; inset: 0; background: linear-gradient(90deg, transparent, rgba(255,255,255,.55), transparent);
    background-size: 200% 100%; animation: hfShimmer 1.4s ease infinite; }
html.dark-mode .hf-skeleton::after { background: linear-gradient(90deg, transparent, rgba(255,255,255,.08), transparent); }
@keyframes hfShimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }

/* ---------- Toast ---------- */
.hf-toast-wrap { position: fixed; top: 84px; right: 20px; z-index: 1090; display: flex; flex-direction: column; gap: 10px; pointer-events: none; }
.hf-toast { pointer-events: auto; display: flex; align-items: flex-start; gap: 11px; min-width: 300px; max-width: 380px;
    background: var(--hf-card); border: 1px solid var(--hf-border); border-radius: 15px; padding: 13px 15px; box-shadow: var(--hf-shadow-lg);
    animation: hfToastIn .35s cubic-bezier(.22,1,.36,1) both; border-left: 4px solid var(--hf-primary); }
.hf-toast.ok { border-left-color: var(--hf-green); }
.hf-toast.err { border-left-color: var(--hf-red); }
.hf-toast i { font-size: 16px; margin-top: 1px; color: var(--hf-primary); }
.hf-toast.ok i { color: var(--hf-green); }
.hf-toast.err i { color: var(--hf-red); }
.hf-toast b { display: block; font-size: 13px; color: var(--hf-text); }
.hf-toast span { font-size: 11.5px; color: var(--hf-text-2); line-height: 1.5; }
.hf-toast .hf-toast-close { margin-left: auto; background: none; border: none; color: var(--hf-text-3); font-size: 14px; cursor: pointer; padding: 0 2px; }
@keyframes hfToastIn { from { opacity: 0; transform: translateX(24px); } to { opacity: 1; transform: none; } }
.hf-toast.is-out { animation: hfToastOut .3s ease both; }
@keyframes hfToastOut { to { opacity: 0; transform: translateX(24px); } }

/* ---------- FAB ---------- */
.hf-fab { position: fixed; right: 22px; bottom: 22px; z-index: 1040; width: 56px; height: 56px; border-radius: 18px; border: none;
    background: var(--hf-grad); color: #fff; font-size: 22px; display: inline-flex; align-items: center; justify-content: center;
    box-shadow: 0 14px 32px -8px rgba(124,58,237,.62); transition: transform .2s ease, box-shadow .2s ease; }
.hf-fab:hover { transform: translateY(-3px) scale(1.04); box-shadow: 0 20px 44px -8px rgba(124,58,237,.72); }
.hf-fab i { pointer-events: none; }

/* ---------- Confirm modal ---------- */
.hf-confirm-overlay { position: fixed; inset: 0; z-index: 1080; background: rgba(15,15,20,.5); backdrop-filter: blur(4px);
    display: flex; align-items: center; justify-content: center; padding: 18px; animation: hfFadeIn .18s ease both; }
.hf-confirm-box { width: 100%; max-width: 400px; background: var(--hf-card); border: 1px solid var(--hf-border); border-radius: 20px;
    box-shadow: var(--hf-shadow-lg); padding: 24px; animation: hfZoomIn .22s cubic-bezier(.22,1,.36,1) both; }
.hf-confirm-icon { width: 58px; height: 58px; border-radius: 16px; background: var(--hf-red-soft); color: var(--hf-red);
    display: inline-flex; align-items: center; justify-content: center; font-size: 24px; margin-bottom: 14px; }
.hf-confirm-title { font-size: 16px; font-weight: 700; color: var(--hf-text); margin: 0 0 6px; }
.hf-confirm-msg { font-size: 12.5px; color: var(--hf-text-2); line-height: 1.6; margin-bottom: 20px; }
.hf-confirm-actions { display: flex; gap: 10px; justify-content: flex-end; flex-wrap: wrap; }
@keyframes hfFadeIn { from { opacity: 0; } to { opacity: 1; } }
@keyframes hfZoomIn { from { opacity: 0; transform: scale(.94) translateY(8px); } to { opacity: 1; transform: none; } }

/* ---------- Back link ---------- */
.hf-back { display: inline-flex; align-items: center; gap: 7px; font-size: 12.5px; font-weight: 600; color: var(--hf-text-2); padding: 7px 13px;
    border-radius: 11px; border: 1px solid var(--hf-border); background: var(--hf-card); transition: all .2s ease; }
.hf-back:hover { color: var(--hf-primary); border-color: var(--hf-primary-border); transform: translateX(-2px); }

/* ---------- Pulse / dot ---------- */
.hf-pulse { animation: hfPulse 2s infinite; }
@keyframes hfPulse { 0%,100% { box-shadow: 0 0 0 0 rgba(16,185,129,.5); } 50% { box-shadow: 0 0 0 8px rgba(16,185,129,0); } }
.hf-dot { width: 7px; height: 7px; border-radius: 50%; background: currentColor; flex-shrink: 0; animation: hfDot 1.6s ease-in-out infinite; }
@keyframes hfDot { 0%,100% { opacity: 1; transform: scale(1); } 50% { opacity: .4; transform: scale(.7); } }

/* ---------- Responsive ---------- */
@media (max-width: 1199.98px) {
    .hf-kpi-grid { grid-template-columns: repeat(2, 1fr); }
    .hf-stat-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 767.98px) {
    .hf-hero { padding: 20px 18px; }
    .hf-hero-title { font-size: 18px; }
    .hf-kpi-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
    .hf-kpi { padding: 14px 14px; gap: 10px; }
    .hf-kpi-icon { width: 40px; height: 40px; font-size: 16px; }
    .hf-kpi-num { font-size: 19px; }
    .hf-toolbar { top: 66px; }
    .hf-toast-wrap { right: 12px; left: 12px; min-width: 0; }
    .hf-toast { min-width: 0; width: 100%; }
    .hf-quicknav-grid { grid-template-columns: 1fr 1fr; }
    .hf-cd-box { min-width: 50px; }
    .hf-cd-num { font-size: 18px; }
}
@media (max-width: 575.98px) {
    .hf-kpi-grid { grid-template-columns: 1fr 1fr; }
    .hf-hero-right { width: 100%; }
    .hf-hero-right .hf-btn { flex: 1; }
    .hf-detail-hero { padding: 20px 18px; }
    .hf-quicknav-grid { grid-template-columns: 1fr; }
    .hf-stepper .hf-step-txt span { display: none; }
    .hf-cd-sep { display: none; }
    .hf-cd-box { min-width: 44px; }
    .hf-countdown { gap: 6px; }
}
@media (prefers-reduced-motion: reduce) {
    .hf-mod *, .hf-mod *::before, .hf-mod *::after {
        animation-duration: .01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: .01ms !important;
    }
}
</style>

<script>
window.HF = window.HF || {};

window.HF.ripple = function (evt) {
    var el = evt.currentTarget;
    var rect = el.getBoundingClientRect();
    var r = Math.max(rect.width, rect.height) / 2;
    var d = document.createElement('span');
    d.className = 'hf-ripple';
    d.style.width = d.style.height = (r * 2) + 'px';
    d.style.left = (evt.clientX - rect.left - r) + 'px';
    d.style.top = (evt.clientY - rect.top - r) + 'px';
    el.appendChild(d);
    setTimeout(function () { d.remove(); }, 600);
};

window.HF.toast = function (type, title, msg) {
    var wrap = document.querySelector('.hf-toast-wrap');
    if (!wrap) { wrap = document.createElement('div'); wrap.className = 'hf-toast-wrap'; document.body.appendChild(wrap); }
    var icon = type === 'ok' ? 'fa-check-circle' : (type === 'err' ? 'fa-exclamation-circle' : 'fa-info-circle');
    var t = document.createElement('div');
    t.className = 'hf-toast ' + (type || '');
    t.setAttribute('role', 'status');
    t.innerHTML = '<i class="fas ' + icon + '"></i><div><b>' + title + '</b><span>' + (msg || '') + '</span></div><button type="button" class="hf-toast-close" aria-label="Tutup">&times;</button>';
    wrap.appendChild(t);
    var close = function () { t.classList.add('is-out'); setTimeout(function () { t.remove(); }, 320); };
    t.querySelector('.hf-toast-close').addEventListener('click', close);
    setTimeout(close, 4600);
};

window.HF.counter = function (el, dur) {
    var target = parseInt(el.getAttribute('data-count'), 10);
    if (isNaN(target)) { target = 0; }
    var durMs = dur || 900;
    var start = null;
    var step = function (ts) {
        if (!start) { start = ts; }
        var p = Math.min(1, (ts - start) / durMs);
        var eased = 1 - Math.pow(1 - p, 3);
        el.textContent = Math.round(target * eased).toLocaleString('id-ID');
        if (p < 1) { window.requestAnimationFrame(step); }
        else { el.textContent = target.toLocaleString('id-ID'); }
    };
    window.requestAnimationFrame(step);
};

window.HF.countdown = function (el) {
    var target = new Date(el.getAttribute('data-target')).getTime();
    var label = el.getAttribute('data-label') || 'Berlangsung';
    var doneTxt = el.getAttribute('data-done') || 'Berlangsung';
    if (isNaN(target)) { return; }
    var dBox = el.querySelector('.hf-cd-d');
    var hBox = el.querySelector('.hf-cd-h');
    var mBox = el.querySelector('.hf-cd-m');
    var sBox = el.querySelector('.hf-cd-s');
    var pad = function (n) { return n < 10 ? '0' + n : String(n); };
    var tick = function () {
        var diff = target - Date.now();
        if (diff <= 0) {
            if (dBox) { dBox.textContent = '00'; }
            if (hBox) { hBox.textContent = '00'; }
            if (mBox) { mBox.textContent = '00'; }
            if (sBox) { sBox.textContent = '00'; }
            el.classList.add('is-done');
            var lbl = el.querySelector('.hf-cd-label-txt');
            if (lbl) { lbl.textContent = doneTxt; }
            return;
        }
        var d = Math.floor(diff / 86400000);
        var h = Math.floor((diff % 86400000) / 3600000);
        var m = Math.floor((diff % 3600000) / 60000);
        var s = Math.floor((diff % 60000) / 1000);
        if (dBox) { dBox.textContent = pad(d); }
        if (hBox) { hBox.textContent = pad(h); }
        if (mBox) { mBox.textContent = pad(m); }
        if (sBox) { sBox.textContent = pad(s); }
        if (label && el.getAttribute('data-set-label') !== '1') {
            var lbl = el.querySelector('.hf-cd-label-txt');
            if (lbl) { lbl.textContent = label; }
            el.setAttribute('data-set-label', '1');
        }
        setTimeout(tick, 1000);
    };
    tick();
};

window.HF.confirm = function (title, msg, iconClass) {
    return new Promise(function (resolve) {
        var overlay = document.createElement('div');
        overlay.className = 'hf-confirm-overlay';
        overlay.setAttribute('role', 'dialog');
        overlay.setAttribute('aria-modal', 'true');
        overlay.innerHTML =
            '<div class="hf-confirm-box">' +
                '<div class="hf-confirm-icon"><i class="fas ' + (iconClass || 'fa-trash-alt') + '"></i></div>' +
                '<h4 class="hf-confirm-title"></h4>' +
                '<p class="hf-confirm-msg"></p>' +
                '<div class="hf-confirm-actions">' +
                    '<button type="button" class="hf-btn" data-hf-no><i class="fas fa-times"></i> Batal</button>' +
                    '<button type="button" class="hf-btn hf-btn--danger" data-hf-yes><i class="fas fa-check"></i> Ya, Lanjutkan</button>' +
                '</div>' +
            '</div>';
        overlay.querySelector('.hf-confirm-title').textContent = title || 'Konfirmasi';
        overlay.querySelector('.hf-confirm-msg').textContent = msg || 'Yakin ingin melanjutkan?';
        document.body.appendChild(overlay);
        var done = function (val) {
            overlay.classList.add('is-out');
            setTimeout(function () { overlay.remove(); }, 160);
            resolve(val);
        };
        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) { done(false); }
        });
        overlay.querySelector('[data-hf-no]').addEventListener('click', function () { done(false); });
        overlay.querySelector('[data-hf-yes]').addEventListener('click', function () { done(true); });
    });
};

window.HF.confirmForm = function (form, title, msg, iconClass) {
    return new Promise(function (resolve) {
        window.HF.confirm(title, msg, iconClass).then(function (ok) {
            if (ok) {
                var btn = form.querySelector('[type=submit]');
                if (btn) { btn.disabled = true; }
                form.submit();
            }
            resolve(ok);
        });
    });
};

window.HF.tooltips = function () {
    if (!window.bootstrap || !bootstrap.Tooltip) { return; }
    var nodes = [].slice.call(document.querySelectorAll('.hf-mod [data-bs-toggle="tooltip"]'));
    nodes.forEach(function (el) {
        if (!el.getAttribute('data-bs-original-title')) {
            new bootstrap.Tooltip(el, { trigger: 'hover focus', delay: { show: 120, hide: 60 } });
        }
    });
};

window.HF.init = function () {
    [].slice.call(document.querySelectorAll('.hf-mod [data-count]')).forEach(function (el) {
        window.HF.counter(el);
    });
    [].slice.call(document.querySelectorAll('.hf-countdown[data-target]')).forEach(function (el) {
        window.HF.countdown(el);
    });
    window.HF.tooltips();
};

(function () {
    function ready(fn) {
        if (document.readyState !== 'loading') { fn(); }
        else { document.addEventListener('DOMContentLoaded', fn); }
    }
    ready(function () {
        if (window.HF) { window.HF.init(); }
    });
})();
</script>
