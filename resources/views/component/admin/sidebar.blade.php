{{-- ============================================================
     SIDEBAR ADMIN — Premium Enterprise Navigation (V2)
     Desain mengikuti design system Absensi Siswa (--ab-*) dan
     Navbar Admin (--nb-*): primary blue, soft surface, white card,
     border tipis, radius besar, shadow lembut, glass ringan.

     Behavior: full flyout navigation.
     - Klik menu induk SELALU membuka panel flyout di samping
       (mode expanded maupun collapsed), sama seperti desain lama.
     - Mobile: panel menjadi bottom sheet.
     Menu, rute, dan logika aktif (request()->is) TIDAK diubah.
     ============================================================ --}}

@php
    $sbUser       = auth()->user();
    $sbName       = trim($sbUser->name ?? '');
    $sbFirstParts = explode(' ', $sbName);
    $sbInitials   = mb_strtoupper(mb_substr($sbFirstParts[0] ?? 'U', 0, 1));
    if (isset($sbFirstParts[1])) $sbInitials .= mb_strtoupper(mb_substr($sbFirstParts[1], 0, 1));
    $sbRoleMap    = [1 => 'Admin', 2 => 'Guru', 3 => 'Siswa', 4 => 'BK', 5 => 'Kepala Sekolah'];
    $sbRoleLabel  = $sbRoleMap[$sbUser->role] ?? 'Pengguna';
@endphp

<style>
/* ============================================================
   SIDEBAR V2 — design system (.sb-sidebar)
   Mirror token --ab-* / --nb-* agar satu keluarga dengan dashboard.
   ============================================================ */
.sb-sidebar {
    --sb-primary: #2563eb;
    --sb-primary-2: #3b82f6;
    --sb-primary-3: #60a5fa;
    --sb-primary-dark: #1d4ed8;
    --sb-primary-soft: #eff6ff;
    --sb-primary-border: rgba(37, 99, 235, .22);
    --sb-grad: linear-gradient(135deg, #2563eb, #3b82f6);
    --sb-bg: rgba(255, 255, 255, .88);
    --sb-glass: rgba(255, 255, 255, .92);
    --sb-blur: 18px;
    --sb-border: #e8edf3;
    --sb-border-soft: #f1f5f9;
    --sb-text: #0f172a;
    --sb-text-2: #475569;
    --sb-text-3: #94a3b8;
    --sb-hover-bg: #f1f5f9;
    --sb-active-soft: #eff6ff;
    --sb-active-border: rgba(37, 99, 235, .30);
    --sb-shadow: 0 8px 28px -12px rgba(15, 23, 42, .16);
    --sb-shadow-lg: 0 22px 48px -18px rgba(15, 23, 42, .22);
    --sb-flyout-bg: rgba(255, 255, 255, .94);
    --sb-flyout-shadow: 0 18px 40px -8px rgba(15, 23, 42, .18), 0 6px 14px -6px rgba(15, 23, 42, .10);
    --sb-green: #16a34a;   --sb-green-soft: #f0fdf4;   --sb-green-border: #bbf7d0;
    --sb-amber: #d97706;   --sb-amber-soft: #fffbeb;   --sb-amber-border: #fde68a;
    --sb-red: #dc2626;     --sb-red-soft: #fef2f2;     --sb-red-border: #fecaca;
    --sb-ripple: rgba(37, 99, 235, .12);
    --sb-radius: 14px;
    font-family: 'Inter', 'Poppins', system-ui, sans-serif;
}
html.dark-mode .sb-sidebar {
    --sb-primary: #3DA9FC;
    --sb-primary-2: #2EA8FF;
    --sb-primary-3: #6ec9ff;
    --sb-primary-dark: #2EA8FF;
    --sb-primary-soft: rgba(61, 169, 252, .14);
    --sb-primary-border: rgba(61, 169, 252, .35);
    --sb-grad: linear-gradient(135deg, #2EA8FF, #00E5FF);
    --sb-bg: rgba(9, 22, 32, .90);
    --sb-glass: rgba(13, 25, 38, .94);
    --sb-border: rgba(255, 255, 255, .09);
    --sb-border-soft: rgba(255, 255, 255, .06);
    --sb-text: #f8fafc;
    --sb-text-2: #cbd5e1;
    --sb-text-3: #7d96a6;
    --sb-hover-bg: rgba(61, 169, 252, .10);
    --sb-active-soft: rgba(61, 169, 252, .14);
    --sb-active-border: rgba(61, 169, 252, .38);
    --sb-shadow: 0 8px 26px -8px rgba(0, 0, 0, .55);
    --sb-shadow-lg: 0 22px 48px -18px rgba(0, 0, 0, .55);
    --sb-flyout-bg: rgba(13, 25, 38, .95);
    --sb-flyout-shadow: 0 18px 40px -8px rgba(0, 0, 0, .55), 0 6px 14px -6px rgba(0, 0, 0, .30);
    --sb-green: #34d399;   --sb-green-soft: rgba(52, 211, 153, .12);   --sb-green-border: rgba(52, 211, 153, .35);
    --sb-amber: #fbbf24;   --sb-amber-soft: rgba(251, 191, 36, .12);   --sb-amber-border: rgba(251, 191, 36, .35);
    --sb-red: #f87171;     --sb-red-soft: rgba(248, 113, 113, .12);    --sb-red-border: rgba(248, 113, 113, .35);
    --sb-ripple: rgba(61, 169, 252, .16);
}

/* ---------- Shell ---------- */
.sidebar.sb-sidebar {
    background: var(--sb-bg);
    -webkit-backdrop-filter: blur(var(--sb-blur)) saturate(160%);
    backdrop-filter: blur(var(--sb-blur)) saturate(160%);
    border-right: 1px solid var(--sb-border);
    box-shadow: var(--sb-shadow);
    transition: width var(--t-slow), background-color .3s ease, border-color .3s ease, box-shadow .3s ease;
    animation: sbSlideIn .3s ease-out both;
    will-change: transform;
}
@keyframes sbSlideIn {
    from { opacity: 0; transform: translateX(-100%); }
    to { opacity: 1; transform: none; }
}
.sb-sidebar::after { content: none !important; }
.sb-sidebar :focus-visible {
    outline: 2px solid var(--sb-primary-3) !important;
    outline-offset: 2px !important;
    border-radius: 10px;
}

/* ---------- Header / brand ---------- */
.sb-sidebar .sidebar-header {
    height: 72px;
    padding: 0 14px;
    gap: 10px;
    background: transparent;
    border-bottom: 1px solid var(--sb-border);
}
.sb-sidebar .sidebar-toggler {
    width: 38px;
    height: 38px;
    border-radius: 11px;
    border: 1px solid transparent;
    transition: background .2s ease, border-color .2s ease, transform .2s ease;
}
.sb-sidebar .sidebar-toggler:hover {
    background: var(--sb-primary-soft);
    border-color: var(--sb-primary-border);
}
.sb-sidebar .sidebar-toggler span { background: var(--sb-text-2); }
.sb-sidebar .sidebar-toggler:hover span { background: var(--sb-primary); }
.sb-sidebar .sidebar-toggler:active { transform: scale(.92); }

.sb-sidebar .sidebar-brand { gap: 10px; min-width: 0; }
.sb-sidebar .sidebar-brand-icon {
    flex-shrink: 0;
    width: 42px;
    height: 42px;
    border-radius: 13px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: var(--sb-grad);
    border: 1px solid rgba(255, 255, 255, .35);
    box-shadow: 0 6px 16px -6px rgba(37, 99, 235, .55), inset 0 1px 0 rgba(255, 255, 255, .35);
    overflow: hidden;
    transition: transform .25s cubic-bezier(.34, 1.56, .64, 1), box-shadow .25s ease;
}
html.dark-mode .sb-sidebar .sidebar-brand-icon {
    box-shadow: 0 6px 18px -6px rgba(46, 168, 255, .45), inset 0 1px 0 rgba(255, 255, 255, .18);
}
.sb-sidebar .sidebar-brand:hover .sidebar-brand-icon { transform: scale(1.05) rotate(-3deg); }
.sb-sidebar .sidebar-brand-icon img { width: 26px; height: auto; object-fit: contain; }
html.dark-mode .sb-sidebar .sidebar-brand-icon img { filter: none !important; }

.sb-sidebar .sidebar-brand-text { min-width: 0; }
.sb-sidebar .sidebar-brand-name { font-size: 16px; font-weight: 800; color: var(--sb-text); letter-spacing: -.3px; }
.sb-sidebar .sidebar-brand-sub { font-size: 11px; color: var(--sb-text-3); margin-top: 1px; }

/* ---------- Profile mini ---------- */
.sb-profile {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 4px 14px 14px;
    flex-shrink: 0;
    padding: 10px 10px;
    border-radius: var(--sb-radius);
    border: 1px solid var(--sb-border);
    background: var(--sb-border-soft);
    text-decoration: none !important;
    transition: background .2s ease, border-color .2s ease, box-shadow .2s ease, transform .2s ease;
    overflow: hidden;
}
.sb-profile:hover {
    background: var(--sb-primary-soft);
    border-color: var(--sb-primary-border);
    box-shadow: 0 6px 16px -10px rgba(37, 99, 235, .5);
    transform: translateY(-1px);
}
.sb-avatar {
    flex-shrink: 0;
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: var(--sb-grad);
    color: #fff;
    font-size: 13px;
    font-weight: 800;
    letter-spacing: .5px;
    box-shadow: 0 5px 14px -4px rgba(37, 99, 235, .5), inset 0 1px 0 rgba(255, 255, 255, .35);
}
.sb-profile-meta { display: flex; flex-direction: column; min-width: 0; line-height: 1.25; }
.sb-profile-meta .n { font-size: 13px; font-weight: 700; color: var(--sb-text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 120px; }
.sb-profile-meta .s { display: inline-flex; align-items: center; gap: 5px; font-size: 10.5px; font-weight: 600; color: var(--sb-green); }
.sb-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: var(--sb-green);
    box-shadow: 0 0 0 3px var(--sb-green-soft);
    flex-shrink: 0;
}
.sb-dot.sb-dot--pulse { animation: sbPulse 2s ease-in-out infinite; }
@keyframes sbPulse {
    0%, 100% { box-shadow: 0 0 0 3px var(--sb-green-soft); }
    50% { box-shadow: 0 0 0 5px transparent; }
}
.sb-role-badge {
    margin-left: auto;
    flex-shrink: 0;
    padding: 3px 9px;
    border-radius: 20px;
    background: var(--sb-primary);
    color: #fff;
    font-size: 9.5px;
    font-weight: 700;
    letter-spacing: .3px;
    text-transform: uppercase;
    white-space: nowrap;
    box-shadow: 0 4px 10px -3px rgba(37, 99, 235, .5);
}

/* ---------- Body scrollbar ---------- */
.sb-sidebar .sidebar-body { padding: 6px 0 18px; overflow-x: hidden; }
.sb-sidebar .sidebar-body::-webkit-scrollbar { width: 4px; }
.sb-sidebar .sidebar-body::-webkit-scrollbar-track { background: transparent; }
.sb-sidebar .sidebar-body::-webkit-scrollbar-thumb { background: var(--sb-border); border-radius: 4px; }

/* ---------- Menu container ---------- */
.sb-sidebar .sidebar-menu { padding: 0 10px; gap: 2px; }

/* ---------- Group labels ---------- */
.sb-group {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 16px 10px 6px;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--sb-text-3);
    white-space: nowrap;
    user-select: none;
}
.sb-group::after {
    content: '';
    flex: 1;
    height: 1px;
    background: linear-gradient(90deg, var(--sb-border), transparent);
}
.sb-group:first-child { margin-top: 6px; }

/* ---------- Menu link ---------- */
.sb-sidebar .menu-link {
    position: relative;
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 1px 0;
    padding: 8px 12px;
    min-height: 50px;
    border-radius: var(--sb-radius);
    border: 1px solid transparent;
    color: var(--sb-text-2);
    text-decoration: none !important;
    white-space: nowrap;
    overflow: hidden;
    cursor: pointer;
    transition: background .2s ease, color .2s ease, transform .2s ease, box-shadow .2s ease, border-color .2s ease;
}
.sb-sidebar .menu-link:hover {
    background: var(--sb-hover-bg);
    border-color: var(--sb-border-soft);
    color: var(--sb-text);
    transform: translateY(-1px) scale(1.03);
    box-shadow: 0 8px 18px -12px rgba(15, 23, 42, .35);
}
.sb-sidebar .menu-link:active { transform: translateY(0) scale(.985); }

.sb-sidebar .menu-icon {
    flex-shrink: 0;
    width: 38px;
    height: 38px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 11px;
    background: var(--sb-border-soft);
    border: 1px solid var(--sb-border);
    color: var(--sb-text-2);
    font-size: 17px;
    transition: all .2s ease;
    box-shadow: none;
}
.sb-sidebar .menu-icon i { transition: color .2s ease; }
.sb-sidebar .menu-link:hover .menu-icon {
    background: var(--sb-primary-soft);
    border-color: var(--sb-primary-border);
    color: var(--sb-primary);
    transform: scale(1.06);
    box-shadow: 0 4px 12px -6px rgba(37, 99, 235, .4);
}
.sb-sidebar .menu-text {
    font-size: 13.5px;
    font-weight: 600;
    color: var(--sb-text-2);
    transition: color .2s ease, transform .2s cubic-bezier(.34, 1.56, .64, 1);
    overflow: hidden;
}
.sb-sidebar .menu-link:hover .menu-text { color: var(--sb-text); transform: translateX(4px); }
.sb-sidebar .menu-arrow {
    margin-left: auto;
    flex-shrink: 0;
    font-size: 11px;
    color: var(--sb-text-3);
    transition: transform .25s cubic-bezier(.34, 1.56, .64, 1), color .2s ease;
    opacity: 1;
}
.sb-sidebar .menu-item.open > .menu-link .menu-arrow { transform: rotate(180deg); }
.sb-sidebar .menu-link:hover .menu-arrow { color: var(--sb-primary); }

/* ---------- Active menu ---------- */
.sb-sidebar .menu-item.is-active > .menu-link {
    background: linear-gradient(135deg, var(--sb-primary-soft), transparent 70%);
    border-color: var(--sb-active-border);
    color: var(--sb-primary-dark);
    font-weight: 700;
    box-shadow: 0 10px 22px -12px rgba(37, 99, 235, .5), inset 0 0 20px rgba(37, 99, 235, .05);
}
.sb-sidebar .menu-item.is-active > .menu-link::before {
    content: '';
    position: absolute;
    left: -1px;
    top: 22%;
    bottom: 22%;
    width: 3.5px;
    border-radius: 0 6px 6px 0;
    background: var(--sb-grad);
    box-shadow: 0 0 14px var(--sb-primary-3);
}
.sb-sidebar .menu-item.is-active > .menu-link .menu-icon {
    background: var(--sb-grad);
    border-color: transparent;
    color: #fff;
    box-shadow: 0 6px 16px -6px rgba(37, 99, 235, .6), inset 0 1px 0 rgba(255, 255, 255, .35);
}
.sb-sidebar .menu-item.is-active > .menu-link .menu-text { color: var(--sb-primary-dark); font-weight: 700; }
html.dark-mode .sb-sidebar .menu-item.is-active > .menu-link .menu-text,
html.dark-mode .sb-sidebar .menu-item.is-active > .menu-link { color: #fff; }

/* Badge "Aktif" (slide-in) */
.sb-sidebar:not(.is-collapsed) .menu-item.is-active > .menu-link::after {
    content: 'Aktif';
    margin-left: auto;
    flex-shrink: 0;
    padding: 3px 8px;
    border-radius: 20px;
    background: var(--sb-primary);
    color: #fff;
    font-size: 9px;
    font-weight: 800;
    letter-spacing: .4px;
    text-transform: uppercase;
    box-shadow: 0 4px 10px -3px rgba(37, 99, 235, .55);
    animation: sbBadgeIn .3s cubic-bezier(.34, 1.56, .64, 1);
}
@keyframes sbBadgeIn {
    from { opacity: 0; transform: translateX(-8px) scale(.8); }
    to { opacity: 1; transform: none; }
}

/* Parent highlight (subpage aktif) */
.sb-sidebar .menu-item.has-submenu.has-active > .menu-link {
    background: linear-gradient(135deg, var(--sb-primary-soft), transparent 75%);
    color: var(--sb-text);
}
.sb-sidebar .menu-item.has-submenu.has-active > .menu-link .menu-icon {
    background: var(--sb-primary-soft);
    border-color: var(--sb-primary-border);
    color: var(--sb-primary);
}

/* ---------- Accordion submenu (expanded) ---------- */
.sb-sidebar .menu-submenu {
    list-style: none;
    margin: 2px 0 2px 14px;
    padding: 0;
    padding-left: 0;
    position: relative;
    overflow: hidden;
    max-height: 0;
    background: transparent;
    transition: max-height .32s cubic-bezier(.4, 0, .2, 1);
}
.sb-sidebar .menu-item.open > .menu-submenu {
    max-height: calc(100vh - 240px);
    overflow-y: auto;
    overscroll-behavior: none;
}
.sb-sidebar .menu-submenu::-webkit-scrollbar { width: 6px; }
.sb-sidebar .menu-submenu::-webkit-scrollbar-track { background: transparent; }
.sb-sidebar .menu-submenu::-webkit-scrollbar-thumb { background: var(--sb-border); border-radius: 6px; }
.sb-sidebar .menu-submenu::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 6px;
    bottom: 6px;
    width: 2px;
    background: var(--sb-border);
    border-radius: 2px;
}
.sb-sidebar .menu-submenu-title {
    padding: 6px 0 8px 38px;
    margin: 0;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--sb-text-3);
    white-space: nowrap;
    user-select: none;
}
.sb-sidebar .menu-submenu-item {
    position: relative;
    padding: 1px 0 1px 36px;
}
.sb-sidebar .menu-submenu-item::before {
    content: '';
    position: absolute;
    left: 12px;
    top: 50%;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: var(--sb-text-3);
    transform: translateY(-50%);
    transition: background .2s ease, transform .2s ease, box-shadow .2s ease;
}
.sb-sidebar .menu-submenu-link {
    position: relative;
    display: flex;
    align-items: center;
    gap: 9px;
    margin: 1px 0;
    padding: 8px 12px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 500;
    color: var(--sb-text-2);
    text-decoration: none !important;
    white-space: nowrap;
    transition: background .18s ease, color .18s ease, transform .18s ease;
}
.sb-sidebar .menu-submenu-link:hover {
    background: var(--sb-hover-bg);
    color: var(--sb-primary-dark);
    transform: translateX(3px);
}
.sb-sidebar .menu-submenu-item:hover::before,
.sb-sidebar .menu-submenu-item.is-active::before {
    background: var(--sb-primary);
    box-shadow: 0 0 0 4px var(--sb-primary-soft);
    transform: translateY(-50%) scale(1.15);
}
.sb-sidebar .menu-submenu-item.is-active > .menu-submenu-link {
    background: var(--sb-primary-soft);
    color: var(--sb-primary-dark);
    font-weight: 700;
    box-shadow: inset 0 0 0 1px var(--sb-primary-border);
}
.sb-sidebar .menu-submenu-item.is-active > .menu-submenu-link::after {
    content: '';
    position: absolute;
    right: 12px;
    top: 50%;
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background: var(--sb-primary);
    transform: translateY(-50%);
}

/* ---------- Ripple ---------- */
.sb-ripple {
    position: absolute;
    border-radius: 50%;
    background: var(--sb-ripple);
    transform: scale(0);
    animation: sbRipple .6s ease-out forwards;
    pointer-events: none;
}
@keyframes sbRipple { to { transform: scale(1); opacity: 0; } }

/* ============================================================
   COLLAPSED MODE — icon rail + flyout panel
   ============================================================ */
.sb-sidebar.is-collapsed .sidebar-header {
    padding: 0 6px;
    justify-content: center;
    gap: 4px;
}
.sb-sidebar.is-collapsed .sidebar-toggler { width: 32px; height: 32px; }
.sb-sidebar.is-collapsed .sidebar-brand-icon { width: 38px; height: 38px; border-radius: 12px; }
.sb-sidebar.is-collapsed .sidebar-brand-icon img { width: 24px; }
.sb-sidebar.is-collapsed .sidebar-brand-text { opacity: 0; width: 0; pointer-events: none; }

.sb-sidebar.is-collapsed .sb-profile {
    margin: 2px 8px 10px;
    padding: 8px;
    justify-content: center;
    background: transparent;
    border-color: transparent;
}
.sb-sidebar.is-collapsed .sb-profile:hover { background: var(--sb-primary-soft); }
.sb-sidebar.is-collapsed .sb-avatar { width: 44px; height: 44px; font-size: 14px; border-radius: 13px; }
.sb-sidebar.is-collapsed .sb-profile-meta,
.sb-sidebar.is-collapsed .sb-role-badge { display: none; }

.sb-sidebar.is-collapsed .sb-group { display: none; }

.sb-sidebar.is-collapsed .sidebar-menu { padding: 0 8px; }
.sb-sidebar.is-collapsed .menu-link { padding: 8px; justify-content: center; }
.sb-sidebar.is-collapsed .menu-text,
.sb-sidebar.is-collapsed .menu-arrow { opacity: 0; width: 0; }
.sb-sidebar.is-collapsed .menu-icon { margin: 0; width: 42px; height: 42px; }

/* Tooltip modern (icon rail) */
.sb-sidebar.is-collapsed .menu-link[title]::after {
    content: attr(title);
    position: fixed;
    left: calc(var(--sidebar-collapsed) + 12px);
    top: auto;
    background: #0f172a;
    color: #f8fafc;
    font-size: 12px;
    font-weight: 600;
    padding: 6px 12px;
    border-radius: 8px;
    white-space: nowrap;
    pointer-events: none;
    opacity: 0;
    transform: translateY(-50%) translateX(-4px);
    transition: opacity .15s ease, transform .15s ease;
    z-index: 9999;
    border: 1px solid rgba(255, 255, 255, .08);
    box-shadow: 0 10px 28px -8px rgba(0, 0, 0, .35);
}
.sb-sidebar.is-collapsed .menu-link[title]:hover::after { opacity: 1; transform: translateY(-50%) translateX(0); }
.sb-sidebar.is-collapsed .menu-item.has-submenu > .menu-link[title]::after { display: none; }
html.dark-mode .sb-sidebar.is-collapsed .menu-link[title]::after {
    background: #1b2a3a;
    color: #f8fafc;
    border-color: rgba(61, 169, 252, .25);
}

/* Flyout panel (selalu aktif — mode flyout penuh) */
.sb-sidebar.sidebar--flyout .menu-item.has-submenu > .menu-submenu {
    display: block;
    position: fixed;
    left: -99999px;
    top: 0;
    z-index: 1100;
    margin: 0;
    min-width: 244px;
    max-height: calc(100vh - 24px);
    overflow-y: auto;
    overscroll-behavior: none;
    padding: 8px;
    background: var(--sb-flyout-bg);
    -webkit-backdrop-filter: blur(var(--sb-blur)) saturate(160%);
    backdrop-filter: blur(var(--sb-blur)) saturate(160%);
    border: 1px solid var(--sb-border);
    border-radius: 16px;
    box-shadow: var(--sb-flyout-shadow);
    opacity: 0;
    transform: translateX(-12px) scale(.97);
    transform-origin: 0 0;
    pointer-events: none;
    transition: opacity .18s ease, transform .18s ease;
}
.sb-sidebar.sidebar--flyout .menu-item.has-submenu.is-flyout-open > .menu-submenu {
    opacity: 1;
    transform: none;
    pointer-events: auto;
}
.sb-sidebar.sidebar--flyout .menu-submenu::-webkit-scrollbar { width: 6px; }
.sb-sidebar.sidebar--flyout .menu-submenu::-webkit-scrollbar-track { background: transparent; }
.sb-sidebar.sidebar--flyout .menu-submenu::-webkit-scrollbar-thumb { background: var(--sb-border); border-radius: 6px; }

/* Reset accordion decorations di dalam panel flyout */
.sb-sidebar.sidebar--flyout .menu-submenu::before,
.sb-sidebar.sidebar--flyout .menu-submenu-item::before { content: none; }
.sb-sidebar.sidebar--flyout .menu-submenu-item { padding: 0; }
.sb-sidebar.sidebar--flyout .menu-submenu-title {
    padding: 6px 12px 8px;
    margin: 0 0 4px;
    border-bottom: 1px solid var(--sb-border-soft);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--sb-text-3);
}
.sb-sidebar.sidebar--flyout .menu-submenu-item {
    position: relative;
    padding-left: 20px;
}
.sb-sidebar.sidebar--flyout .menu-submenu-item::before {
    content: '';
    position: absolute;
    left: 14px;
    top: 50%;
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: var(--sb-text-3);
    transform: translateY(-50%);
    transition: background .18s ease, transform .18s ease;
}
.sb-sidebar.sidebar--flyout .menu-submenu-link { padding: 9px 12px; font-size: 13px; font-weight: 500; }
.sb-sidebar.sidebar--flyout .menu-submenu-link:hover { transform: translateX(2px); }
.sb-sidebar.sidebar--flyout .menu-submenu-item:hover::before,
.sb-sidebar.sidebar--flyout .menu-submenu-item.is-active::before {
    background: var(--sb-primary);
    transform: translateY(-50%) scale(1.15);
}
.sb-sidebar.sidebar--flyout .menu-submenu-item.is-active > .menu-submenu-link {
    background: var(--sb-primary-soft);
    color: var(--sb-primary-dark);
    font-weight: 700;
    box-shadow: inset 0 0 0 1px var(--sb-primary-border);
}
html.dark-mode .sb-sidebar.sidebar--flyout .menu-submenu-item.is-active > .menu-submenu-link { color: #fff; }
.sb-sidebar.sidebar--flyout .menu-submenu-item.is-active > .menu-submenu-link::before {
    content: '';
    position: absolute;
    left: -1px;
    top: 18%;
    bottom: 18%;
    width: 3px;
    border-radius: 0 4px 4px 0;
    background: var(--sb-primary);
}
.sb-sidebar.sidebar--flyout .menu-submenu-item.is-active > .menu-submenu-link::after { display: none; }

/* Parent highlight saat flyout terbuka (override tema hijau lama) */
.sb-sidebar.sidebar--flyout .menu-item.has-submenu.is-flyout-open > .menu-link {
    background: linear-gradient(135deg, var(--sb-primary-soft), transparent 75%);
    color: var(--sb-text);
}
.sb-sidebar.sidebar--flyout .menu-item.has-submenu.is-flyout-open > .menu-link .menu-icon {
    background: var(--sb-primary-soft);
    border-color: var(--sb-primary-border);
    color: var(--sb-primary);
}

/* Tooltip di collapsed: hanya untuk item tanpa submenu */
.sb-sidebar.sidebar--flyout.is-collapsed .menu-item:not(.has-submenu) > .menu-link[title]::after { display: block !important; }

/* ============================================================
   OVERLAY (mobile) — blur backdrop
   ============================================================ */
.sb-sidebar ~ .sidebar-overlay,
.sb-sidebar + .sidebar-overlay {
    background: rgba(15, 23, 42, .32);
    -webkit-backdrop-filter: blur(4px);
    backdrop-filter: blur(4px);
}

/* ============================================================
   RESPONSIVE
   ============================================================ */
@media (max-width: 768px) {
    .sb-sidebar { backdrop-filter: blur(var(--sb-blur)) saturate(160%); }
    .sb-sidebar .sidebar-toggler { display: none; }
    .sb-sidebar .sidebar-header .sidebar-toggler { display: flex; }
    .sb-sidebar.is-mobile-open .menu-link { justify-content: flex-start; padding: 8px 12px; }
    .sb-sidebar.is-mobile-open .menu-text,
    .sb-sidebar.is-mobile-open .menu-arrow { opacity: 1; width: auto; }
    .sb-sidebar.is-mobile-open .sidebar-brand-text,
    .sb-sidebar.is-mobile-open .sb-profile-meta,
    .sb-sidebar.is-mobile-open .sb-role-badge { opacity: 1; }
    .sb-sidebar .sb-group { display: flex; }

    /* Mobile: panel flyout jadi bottom sheet (override tema hijau lama) */
    .sb-sidebar.sidebar--flyout .menu-item.has-submenu > .menu-submenu {
        left: 0 !important;
        right: 0 !important;
        top: auto !important;
        bottom: 0 !important;
        width: 100% !important;
        min-width: 0;
        max-height: 82vh;
        border-radius: 20px 20px 0 0;
        border: 1px solid var(--sb-border);
        border-bottom: none;
        border-top-width: 2px;
        transform: translateY(110%);
        transform-origin: center bottom;
        box-shadow: 0 -10px 40px -8px rgba(15, 23, 42, .18);
    }
    .sb-sidebar.sidebar--flyout .menu-item.has-submenu.is-flyout-open > .menu-submenu {
        transform: translateY(0);
    }
}
@media (max-width: 480px) {
    .sb-sidebar .sb-brand-name { font-size: 15px; }
}

/* ============================================================
   REDUCED MOTION
   ============================================================ */
@media (prefers-reduced-motion: reduce) {
    .sb-sidebar, .sb-sidebar *, .sb-sidebar *::before, .sb-sidebar *::after,
    .sb-sidebar .menu-submenu, .sb-sidebar .sb-ripple {
        animation: none !important;
        transition: none !important;
    }
}

/* ============================================================
   LEGACY NEUTRALIZATION — dark-mode.css neon cyan
   (sidebar V2 memakai token blue, bukan cyan)
   ============================================================ */
html.dark-mode .sidebar.sb-sidebar {
    background: var(--sb-bg) !important;
    -webkit-backdrop-filter: blur(var(--sb-blur)) saturate(160%) !important;
    backdrop-filter: blur(var(--sb-blur)) saturate(160%) !important;
    border-right: 1px solid var(--sb-border) !important;
    box-shadow: var(--sb-shadow) !important;
}
html.dark-mode .sb-sidebar .sidebar-header {
    background: transparent !important;
    border-bottom-color: var(--sb-border) !important;
}
html.dark-mode .sb-sidebar .sidebar-brand-name { color: var(--sb-text) !important; }
html.dark-mode .sb-sidebar .sidebar-brand-sub { color: var(--sb-text-3) !important; }
html.dark-mode .sb-sidebar .sidebar-toggler span { background: var(--sb-text-2) !important; box-shadow: none !important; }
html.dark-mode .sb-sidebar .sidebar-toggler:hover span { background: var(--sb-primary) !important; box-shadow: none !important; }
html.dark-mode .sb-sidebar .sidebar-toggler:hover { background: var(--sb-primary-soft) !important; }
html.dark-mode .sb-sidebar .menu-link { color: var(--sb-text-2) !important; }
html.dark-mode .sb-sidebar .menu-link:hover {
    background: var(--sb-hover-bg) !important;
    border-color: var(--sb-border-soft) !important;
    color: var(--sb-text) !important;
    box-shadow: 0 8px 18px -12px rgba(0, 0, 0, .5) !important;
}
html.dark-mode .sb-sidebar .menu-icon {
    background: var(--sb-border-soft) !important;
    color: var(--sb-text-2) !important;
    border-color: var(--sb-border) !important;
    box-shadow: none !important;
}
html.dark-mode .sb-sidebar .menu-icon i { filter: none !important; }
html.dark-mode .sb-sidebar .menu-link:hover .menu-icon {
    background: var(--sb-primary-soft) !important;
    color: var(--sb-primary) !important;
    border-color: var(--sb-primary-border) !important;
    box-shadow: none !important;
}
html.dark-mode .sb-sidebar .menu-text { color: var(--sb-text-2) !important; }
html.dark-mode .sb-sidebar .menu-link:hover .menu-text { color: var(--sb-text) !important; }
html.dark-mode .sb-sidebar .menu-arrow { color: var(--sb-text-3) !important; }
html.dark-mode .sb-sidebar .menu-item.is-active > .menu-link {
    background: linear-gradient(135deg, var(--sb-primary-soft), transparent 70%) !important;
    border-color: var(--sb-active-border) !important;
    box-shadow: 0 10px 22px -12px rgba(0, 0, 0, .5) !important;
    transform: none !important;
}
html.dark-mode .sb-sidebar .menu-item.is-active > .menu-link::before { background: var(--sb-grad) !important; box-shadow: 0 0 14px var(--sb-primary-3) !important; }
html.dark-mode .sb-sidebar .menu-item.is-active > .menu-link::after { content: none !important; }
html.dark-mode .sb-sidebar .menu-item.is-active > .menu-link .menu-text {
    color: #fff !important;
    text-shadow: none !important;
}
html.dark-mode .sb-sidebar .menu-item.is-active > .menu-link .menu-icon {
    background: var(--sb-grad) !important;
    color: #fff !important;
    box-shadow: none !important;
    transform: none !important;
}
html.dark-mode .sb-sidebar .menu-item.has-submenu.has-active > .menu-link {
    background: linear-gradient(135deg, var(--sb-primary-soft), transparent 75%) !important;
    box-shadow: none !important;
}
html.dark-mode .sb-sidebar .menu-submenu { background: transparent !important; border-left: none !important; }
html.dark-mode .sb-sidebar .menu-submenu::before,
html.dark-mode .sb-sidebar .menu-submenu-item::before { background: var(--sb-border) !important; }
html.dark-mode .sb-sidebar .menu-submenu-link { color: var(--sb-text-2) !important; }
html.dark-mode .sb-sidebar .menu-submenu-link:hover { background: var(--sb-hover-bg) !important; color: var(--sb-primary) !important; }
html.dark-mode .sb-sidebar .menu-submenu-item.is-active > .menu-submenu-link {
    background: var(--sb-primary-soft) !important;
    color: #fff !important;
    border-left: none !important;
}
html.dark-mode .sb-sidebar .menu-submenu-item.is-active > .menu-submenu-link::before { background: var(--sb-primary) !important; box-shadow: none !important; }
html.dark-mode .sb-sidebar .menu-submenu-title { color: var(--sb-text-3) !important; }
html.dark-mode .sb-sidebar .menu-submenu-item.is-active > .menu-submenu-link { box-shadow: inset 0 0 0 1px var(--sb-primary-border) !important; }
html.dark-mode .sb-sidebar .sb-group::after { background: linear-gradient(90deg, var(--sb-border), transparent) !important; }
html.dark-mode .sb-sidebar .sb-profile { background: var(--sb-border-soft) !important; border-color: var(--sb-border) !important; }
html.dark-mode .sb-sidebar .sb-profile:hover { background: var(--sb-primary-soft) !important; border-color: var(--sb-primary-border) !important; }
html.dark-mode .sb-sidebar.is-collapsed .sb-profile { background: transparent !important; border-color: transparent !important; }
</style>

<aside class="sidebar sb-sidebar sidebar--flyout" id="sidebar" data-nav="flyout" aria-label="Navigasi admin">
    <div class="sidebar-header sb-header">
        <button class="sidebar-toggler" id="sidebarToggler" type="button"
                aria-label="Buka / tutup menu samping" title="Buka / tutup menu">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <a href="/" class="sidebar-brand sb-brand" title="Siakad MIS Nurul Ulum">
            <span class="sidebar-brand-icon">
                <img src="../img/logo2.png" alt="MIS Nurul Ulum">
            </span>
            <span class="sidebar-brand-text">
                <span class="sidebar-brand-name">Siakad</span>
                <span class="sidebar-brand-sub">Nurul Ulum Patapan</span>
            </span>
        </a>
    </div>

    <div class="sidebar-body">
        <nav class="sidebar-nav" aria-label="Navigasi utama">
            <ul class="sidebar-menu">

                {{-- Dashboard --}}
                <li class="sb-group">Navigasi</li>
                <li class="menu-item{{ request()->is('home*') ? ' is-active' : '' }}">
                    <a href="/home" class="menu-link" title="Dashboard"{{ request()->is('home*') ? ' aria-current="page"' : '' }}>
                        <span class="menu-icon"><i class="bi bi-grid-1x2-fill"></i></span>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </li>

                {{-- Data Master --}}
                <li class="sb-group">Manajemen</li>
                <li class="menu-item has-submenu{{ request()->is('master-user*', 'master-guru*', 'master-siswa*', 'mata-pelajaran*', 'kelas*', 'tahun-ajaran*', 'arsip-tahun-ajaran*', 'wali-kelas*', 'semester*', 'alumni*') ? ' has-active' : '' }}">
                    <a href="#" class="menu-link menu-toggle" title="Data Master" data-flyout-toggle aria-haspopup="true" aria-expanded="false">
                        <span class="menu-icon"><i class="bi bi-collection"></i></span>
                        <span class="menu-text">Data Master</span>
                        <span class="menu-arrow"><i class="bi bi-chevron-down"></i></span>
                    </a>
                    <ul class="menu-submenu">
                        <li class="menu-submenu-title">Data Master</li>
                        <li class="menu-submenu-item{{ request()->is('master-user*') ? ' is-active' : '' }}">
                            <a href="/master-user" class="menu-submenu-link">Master User</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('master-guru*') ? ' is-active' : '' }}">
                            <a href="/master-guru" class="menu-submenu-link">Master Guru</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('master-siswa*') ? ' is-active' : '' }}">
                            <a href="/master-siswa" class="menu-submenu-link">Master Siswa</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('mata-pelajaran*') ? ' is-active' : '' }}">
                            <a href="/mata-pelajaran" class="menu-submenu-link">Mata Pelajaran</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('kelas*') ? ' is-active' : '' }}">
                            <a href="{{ route('kelas.index') }}" class="menu-submenu-link">Master Kelas</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('wali-kelas*') ? ' is-active' : '' }}">
                            <a href="{{ route('wali-kelas.index') }}" class="menu-submenu-link">Wali Kelas</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('master-kepala-madrasah*') ? ' is-active' : '' }}">
                            <a href="{{ route('master-kepala-madrasah.index') }}" class="menu-submenu-link">Kepala Madrasah</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('tahun-ajaran*', 'arsip-tahun-ajaran*') ? ' is-active' : '' }}">
                            <a href="{{ route('tahun-ajaran.index') }}" class="menu-submenu-link">Tahun Ajaran</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('semester*') ? ' is-active' : '' }}">
                            <a href="{{ route('semester.index') }}" class="menu-submenu-link">Semester</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('alumni*') ? ' is-active' : '' }}">
                            <a href="{{ route('alumni.index') }}" class="menu-submenu-link">Alumni</a>
                        </li>
                    </ul>
                </li>

                {{-- Akademik --}}
                <li class="menu-item has-submenu{{ request()->is('pengampu-mapel*', 'jadwal-pelajaran*', 'jadwal-jenjang*', 'jadwal-per-kelas*', 'jadwal-grid*', 'cetak-siswa*', 'absensi*', 'penilaian', 'penilaian/*', 'penilaian-riwayat*', 'penilaian-hasil*', 'admin/absensi-guru*') ? ' has-active' : '' }}">
                    <a href="#" class="menu-link menu-toggle" title="Akademik" data-flyout-toggle aria-haspopup="true" aria-expanded="false">
                        <span class="menu-icon"><i class="bi bi-book"></i></span>
                        <span class="menu-text">Akademik</span>
                        <span class="menu-arrow"><i class="bi bi-chevron-down"></i></span>
                    </a>
                    <ul class="menu-submenu">
                        <li class="menu-submenu-title">Akademik</li>
                        <li class="menu-submenu-item{{ request()->is('pengampu-mapel*') ? ' is-active' : '' }}">
                            <a href="/pengampu-mapel" class="menu-submenu-link">Guru Mapel</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('jadwal-pelajaran*', 'jadwal-jenjang*', 'jadwal-per-kelas*', 'jadwal-grid*') ? ' is-active' : '' }}">
                            <a href="{{ route('jadwal-pelajaran.index') }}" class="menu-submenu-link">Jadwal</a>
                        </li>
                        {{-- <li class="menu-submenu-item{{ request()->is('jadwal-siswa*') ? ' is-active' : '' }}">
                            <a href="{{ route('jadwal-siswa') }}" class="menu-submenu-link">Jadwal Siswa</a>
                        </li> --}}
                        <li class="menu-submenu-item{{ request()->is('absensi*') ? ' is-active' : '' }}">
                            <a href="{{ route('absensi.index') }}" class="menu-submenu-link">Absensi Siswa</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('admin/absensi-guru*') ? ' is-active' : '' }}">
                            <a href="{{ route('admin.absensi-guru.index') }}" class="menu-submenu-link">Absensi Guru</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('penilaian', 'penilaian/*', 'penilaian-riwayat*', 'penilaian-hasil*') ? ' is-active' : '' }}">
                            <a href="{{ route('penilaian.index') }}" class="menu-submenu-link">Penilaian</a>
                        </li>
                    </ul>
                </li>

                {{-- Pelanggaran (hidden, di-comment untuk pengembangan nanti) --}}
                {{-- <li class="menu-item has-submenu{{ request()->is('peraturan*', 'tindak-lanjut*', 'penanganan*', 'master-histori*') ? ' has-active' : '' }}">
                    <a href="#" class="menu-link menu-toggle" title="Pelanggaran" data-flyout-toggle aria-haspopup="true" aria-expanded="false">
                        <span class="menu-icon"><i class="fas fa-gavel"></i></span>
                        <span class="menu-text">Pelanggaran</span>
                        <span class="menu-arrow"><i class="fas fa-chevron-down"></i></span>
                    </a>
                    <ul class="menu-submenu">
                        <li class="menu-submenu-title">Pelanggaran</li>
                        <li class="menu-submenu-item{{ request()->is('peraturan*') ? ' is-active' : '' }}">
                            <a href="/peraturan" class="menu-submenu-link">Peraturan</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('tindak-lanjut*') ? ' is-active' : '' }}">
                            <a href="{{ route('tindak-lanjut.index') }}" class="menu-submenu-link">Tindakan</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('penanganan*') ? ' is-active' : '' }}">
                            <a href="/penanganan" class="menu-submenu-link">Penanganan</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('master-histori*') ? ' is-active' : '' }}">
                            <a href="/master-histori" class="menu-submenu-link">Histori</a>
                        </li>
                    </ul>
                </li> --}}

                {{-- Haflah / Lomba --}}
                <li class="sb-group">Kompetisi</li>
                <li class="menu-item has-submenu{{ request()->is('haflatul-imtihan*', 'sesi*', 'sesi-lomba*', 'lomba*', 'peserta-lomba*', 'kelompok-lomba*', 'juri-lomba*', 'aspek-penilaian*', 'penilaian-lomba*', 'hasil-lomba*') ? ' has-active' : '' }}">
                    <a href="#" class="menu-link menu-toggle" title="Haflah" data-flyout-toggle aria-haspopup="true" aria-expanded="false">
                        <span class="menu-icon"><i class="bi bi-trophy"></i></span>
                        <span class="menu-text">Haflah</span>
                        <span class="menu-arrow"><i class="bi bi-chevron-down"></i></span>
                    </a>
                    <ul class="menu-submenu">
                        <li class="menu-submenu-title">Haflah</li>
                        <li class="menu-submenu-item{{ request()->is('haflatul-imtihan*') ? ' is-active' : '' }}">
                            <a href="{{ route('haflatul-imtihan.index') }}" class="menu-submenu-link">Haflatul Imtihan</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('sesi', 'sesi/*') ? ' is-active' : '' }}">
                            <a href="{{ route('sesi.index') }}" class="menu-submenu-link">Daftar Sesi</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('sesi-lomba*') ? ' is-active' : '' }}">
                            <a href="{{ route('sesi-lomba.index') }}" class="menu-submenu-link">Sesi Lomba</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('lomba') || request()->is('lomba/*') ? ' is-active' : '' }}">
                            <a href="{{ route('lomba.index') }}" class="menu-submenu-link">Lomba</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('kelompok-lomba*') ? ' is-active' : '' }}">
                            <a href="{{ route('kelompok-lomba.index') }}" class="menu-submenu-link">Kelompok</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('peserta-lomba*', 'anggota-kelompok*') ? ' is-active' : '' }}">
                            <a href="{{ route('peserta-lomba.index') }}" class="menu-submenu-link">Peserta Lomba</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('juri-lomba*') ? ' is-active' : '' }}">
                            <a href="{{ route('juri-lomba.index') }}" class="menu-submenu-link">Juri</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('aspek-penilaian*') ? ' is-active' : '' }}">
                            <a href="{{ route('aspek-penilaian.index') }}" class="menu-submenu-link">Aspek Penilaian</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('penilaian-lomba*') ? ' is-active' : '' }}">
                            <a href="{{ route('penilaian-lomba.index') }}" class="menu-submenu-link">Penilaian</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('hasil-lomba*') ? ' is-active' : '' }}">
                            <a href="{{ route('hasil-lomba.index') }}" class="menu-submenu-link">Hasil</a>
                        </li>
                    </ul>
                </li>

                {{-- Informasi --}}
                <li class="sb-group">Publikasi</li>
                <li class="menu-item has-submenu{{ request()->is('pengumuman*', 'galery*') ? ' has-active' : '' }}">
                    <a href="#" class="menu-link menu-toggle" title="Informasi" data-flyout-toggle aria-haspopup="true" aria-expanded="false">
                        <span class="menu-icon"><i class="bi bi-megaphone"></i></span>
                        <span class="menu-text">Informasi</span>
                        <span class="menu-arrow"><i class="bi bi-chevron-down"></i></span>
                    </a>
                    <ul class="menu-submenu">
                        <li class="menu-submenu-title">Informasi</li>
                        <li class="menu-submenu-item{{ request()->is('pengumuman*') ? ' is-active' : '' }}">
                            <a href="{{ route('pengumuman.index') }}" class="menu-submenu-link">Pengumuman</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('galery*') ? ' is-active' : '' }}">
                            <a href="{{ route('galery.index') }}" class="menu-submenu-link">Galeri</a>
                        </li>
                    </ul>
                </li>

                {{-- Laporan (hidden, di-comment untuk pengembangan nanti) --}}
                {{-- <li class="menu-item has-submenu{{ request()->is('laporan*') ? ' has-active' : '' }}">
                    <a href="#" class="menu-link menu-toggle" title="Laporan" data-flyout-toggle aria-haspopup="true" aria-expanded="false">
                        <span class="menu-icon"><i class="fas fa-file-alt"></i></span>
                        <span class="menu-text">Laporan</span>
                        <span class="menu-arrow"><i class="fas fa-chevron-down"></i></span>
                    </a>
                    <ul class="menu-submenu">
                        <li class="menu-submenu-title">Laporan</li>
                        <li class="menu-submenu-item{{ request()->is('laporan*') ? ' is-active' : '' }}">
                            <a href="{{ route('laporan.rekap-periode') }}" class="menu-submenu-link">Laporan Pelanggaran</a>
                        </li>
                    </ul>
                </li> --}}

                {{-- Pengaturan --}}
                <li class="sb-group">Sistem</li>
                <li class="menu-item has-submenu{{ request()->is('2fa*', 'admin/keamanan*', 'admin/riwayat-login*', 'riwayat-login*', 'perangkat*', 'admin/kebijakan-2fa*', 'lokasi-madrasah*', 'profil-madrasah*') ? ' has-active' : '' }}">
                    <a href="#" class="menu-link menu-toggle" title="Pengaturan" data-flyout-toggle aria-haspopup="true" aria-expanded="false">
                        <span class="menu-icon"><i class="bi bi-gear"></i></span>
                        <span class="menu-text">Pengaturan</span>
                        <span class="menu-arrow"><i class="bi bi-chevron-down"></i></span>
                    </a>
                    <ul class="menu-submenu">
                        <li class="menu-submenu-title">Pengaturan</li>
                        <li class="menu-submenu-item{{ request()->is('profil-madrasah*') ? ' is-active' : '' }}">
                            <a href="{{ route('profil-madrasah.index') }}" class="menu-submenu-link">Profil</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('admin/keamanan*') ? ' is-active' : '' }}">
                            <a href="{{ route('admin.security-dashboard.index') }}" class="menu-submenu-link">Keamanan</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('admin/riwayat-login*', 'riwayat-login*') ? ' is-active' : '' }}">
                            <a href="{{ route('admin.login-history.index') }}" class="menu-submenu-link">Riwayat Login</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('perangkat*') ? ' is-active' : '' }}">
                            <a href="{{ route('active-sessions.index') }}" class="menu-submenu-link">Perangkat Aktif</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('2fa*') ? ' is-active' : '' }}">
                            <a href="{{ route('2fa.setup') }}" class="menu-submenu-link">Keamanan 2FA</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('admin/kebijakan-2fa*') ? ' is-active' : '' }}">
                            <a href="{{ route('admin.2fa-policy.index') }}" class="menu-submenu-link">Kebijakan 2FA</a>
                        </li>
                        <li class="menu-submenu-item{{ request()->is('admin/lokasi-madrasah*') ? ' is-active' : '' }}">
                            <a href="{{ route('lokasi-madrasah.index') }}" class="menu-submenu-link">Lokasi Madrasah</a>
                        </li>
                    </ul>
                </li>

            </ul>
        </nav>
    </div>

    {{-- PROFILE MINI (pinned di bawah) --}}
    <a href="{{ route('profil-saya.index') }}" class="sb-profile" title="{{ $sbName }} · {{ $sbRoleLabel }}">
        <span class="sb-avatar" aria-hidden="true">{{ $sbInitials }}</span>
        <span class="sb-profile-meta">
            <span class="n">{{ $sbName }}</span>
            <span class="s"><span class="sb-dot sb-dot--pulse" aria-hidden="true"></span> Online</span>
        </span>
        <span class="sb-role-badge" aria-hidden="true">{{ $sbRoleLabel }}</span>
    </a>
</aside>

@push('scripts')
<script>
(function () {
    'use strict';
    var sb = document.getElementById('sidebar');
    if (!sb || !sb.classList.contains('sb-sidebar')) return;

    if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    /* Ripple halus pada toggle accordion (bukan navigasi) */
    sb.addEventListener('click', function (e) {
        var toggle = e.target.closest('.menu-toggle');
        if (!toggle) return;
        var link = toggle;
        var ripple = link.querySelector('.sb-ripple');
        if (ripple) ripple.remove();
        ripple = document.createElement('span');
        ripple.className = 'sb-ripple';
        link.appendChild(ripple);
        var rect = link.getBoundingClientRect();
        var size = Math.max(rect.width, rect.height) * 2;
        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = (e.clientX - rect.left - size / 2) + 'px';
        ripple.style.top = (e.clientY - rect.top - size / 2) + 'px';
    });
})();
</script>
@endpush
