@php
    $nbUser       = auth()->user();
    $nbName       = trim($nbUser->name ?? '');
    $nbFirstParts = explode(' ', $nbName);
    $nbInitials   = mb_strtoupper(mb_substr($nbFirstParts[0] ?? 'U', 0, 1));
    if (isset($nbFirstParts[1])) $nbInitials .= mb_strtoupper(mb_substr($nbFirstParts[1], 0, 1));
    $nbRoleMap     = [1 => 'Admin', 2 => 'Guru', 3 => 'Siswa', 4 => 'BK', 5 => 'Kepala Sekolah'];
    $nbRoleLabel   = $nbRoleMap[$nbUser->role] ?? 'Pengguna';
    $nbTA          = $tahunAktifGlobal->tahun_ajaran ?? null;
    $nbSemester    = $tahunAktifGlobal->semesterAktif->nama ?? null;
    $nbRoleSub     = $nbRoleLabel === 'Kepala Sekolah' ? 'Kepala Madrasah' : $nbRoleLabel;
@endphp

<style>
    /* ============================================================
       ADMIN NAVBAR — nb-* design system (blue primary)
       Mirrors the shared ABSENSI module tokens (.abs-mod / --ab-*)
       ============================================================ */
    .nb-navbar {
        --nb-primary: #2563eb;
        --nb-primary-2: #3b82f6;
        --nb-primary-3: #60a5fa;
        --nb-primary-dark: #1d4ed8;
        --nb-primary-soft: #eff6ff;
        --nb-primary-border: rgba(37,99,235,.22);
        --nb-grad: linear-gradient(135deg, #2563eb, #3b82f6);
        --nb-glass: rgba(255,255,255,.84);
        --nb-blur: 16px;
        --nb-border: #e8edf3;
        --nb-text: #0f172a;
        --nb-text-2: #475569;
        --nb-text-3: #94a3b8;
        --nb-shadow: 0 6px 22px -10px rgba(15,23,42,.16);
        --nb-green: #16a34a;  --nb-green-soft: #f0fdf4;  --nb-green-border: #bbf7d0;
        --nb-amber: #d97706;  --nb-amber-soft: #fffbeb;  --nb-amber-border: #fde68a;
        --nb-red: #dc2626;    --nb-red-soft: #fef2f2;    --nb-red-border: #fecaca;
        --nb-sky: #0284c7;    --nb-sky-soft: #f0f9ff;    --nb-sky-border: #bae6fd;
        --nb-violet: #7c3aed; --nb-violet-soft: #f5f3ff; --nb-violet-border: #ddd6fe;
        font-family: 'Inter', 'Poppins', system-ui, sans-serif;
    }
    html.dark-mode .nb-navbar {
        --nb-primary: #3DA9FC;
        --nb-primary-2: #2EA8FF;
        --nb-primary-3: #6ec9ff;
        --nb-primary-dark: #2EA8FF;
        --nb-primary-soft: rgba(61,169,252,.14);
        --nb-primary-border: rgba(61,169,252,.35);
        --nb-grad: linear-gradient(135deg, #2EA8FF, #00E5FF);
        --nb-glass: rgba(9,27,36,.66);
        --nb-blur: 18px;
        --nb-border: rgba(255,255,255,.10);
        --nb-text: #f8fafc;
        --nb-text-2: #cbd5e1;
        --nb-text-3: #7d96a6;
        --nb-shadow: 0 8px 26px -8px rgba(0,0,0,.55);
        --nb-green: #34d399;  --nb-green-soft: rgba(52,211,153,.12);  --nb-green-border: rgba(52,211,153,.35);
        --nb-amber: #fbbf24;  --nb-amber-soft: rgba(251,191,36,.12);  --nb-amber-border: rgba(251,191,36,.35);
        --nb-red: #f87171;    --nb-red-soft: rgba(248,113,113,.12);    --nb-red-border: rgba(248,113,113,.35);
        --nb-sky: #38bdf8;    --nb-sky-soft: rgba(56,189,248,.12);    --nb-sky-border: rgba(56,189,248,.35);
        --nb-violet: #a78bfa; --nb-violet-soft: rgba(167,139,250,.12); --nb-violet-border: rgba(167,139,250,.35);
    }

    /* ============================================================
       SHELL — sticky, glass, thin border
       ============================================================ */
    header.nb-navbar.l-header {
        position: sticky;
        top: 0;
        height: 70px;
        background: var(--nb-glass);
        -webkit-backdrop-filter: blur(var(--nb-blur)) saturate(160%);
        backdrop-filter: blur(var(--nb-blur)) saturate(160%);
        border-bottom: 1px solid var(--nb-border);
        box-shadow: var(--nb-shadow);
        z-index: 1020;
        display: flex;
        align-items: center;
        flex-shrink: 0;
        transition: box-shadow .3s ease, background-color .3s ease;
        animation: nbSlideIn .35s ease-out both;
        will-change: transform;
    }
    @keyframes nbSlideIn {
        from { opacity: 0; transform: translateY(-100%); }
        to { opacity: 1; transform: none; }
    }
    html.dark-mode header.nb-navbar.l-header {
        position: sticky;
        top: 0;
    }
    header.nb-navbar.l-header.is-scrolled {
        box-shadow: 0 10px 30px -12px rgba(15,23,42,.22);
        background: rgba(255,255,255,.92);
    }
    html.dark-mode header.nb-navbar.l-header.is-scrolled {
        background: rgba(7,20,26,.85);
    }
    .nb-inner {
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
        height: 100%;
        padding: 0 16px;
    }

    /* Hamburger: hidden on desktop, shown on mobile only
       (desktop collapse handled by sidebar's #sidebarToggler) */
    .nb-hamburger { display: none; }

    /* ============================================================
       ICON BUTTON — 44x44 target, ripple, focus ring
       ============================================================ */
    .nb-iconbtn {
        position: relative;
        flex-shrink: 0;
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        border: none;
        color: var(--nb-text-2);
        cursor: pointer;
        overflow: hidden;
        text-decoration: none;
        transition: background .2s ease, color .2s ease, transform .15s ease;
    }
    .nb-iconbtn:hover {
        background: var(--nb-primary-soft);
        color: var(--nb-primary);
        text-decoration: none;
    }
    .nb-iconbtn:active { transform: scale(.94); }
    .nb-iconbtn:focus-visible { outline: 2px solid var(--nb-primary); outline-offset: 2px; }
    .nb-iconbtn i { font-size: 16px; pointer-events: none; }
    .nb-ripple::after {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: inherit;
        background: radial-gradient(circle, rgba(37,99,235,.18) 55%, transparent 60%);
        transform: scale(0);
        opacity: 0;
        transition: transform .55s ease, opacity .45s ease;
        pointer-events: none;
    }
    .nb-ripple:active::after {
        transform: scale(1.8);
        opacity: 0;
        transition: 0s;
    }

    /* ============================================================
       GLOBAL SEARCH TRIGGER
       ============================================================ */
    .nb-search-trigger {
        position: relative;
        display: flex;
        align-items: center;
        gap: 10px;
        height: 40px;
        min-width: 240px;
        max-width: 330px;
        padding: 0 12px;
        border-radius: 12px;
        border: 1px solid var(--nb-border);
        background: var(--nb-primary-soft);
        color: var(--nb-text-3);
        font-size: 12.5px;
        font-weight: 500;
        cursor: pointer;
        text-align: left;
        transition: border-color .2s, background .2s, box-shadow .2s, color .2s;
    }
    .nb-search-trigger:hover {
        border-color: var(--nb-primary-border);
        background: #fff;
        box-shadow: 0 0 0 4px var(--nb-primary-soft);
        color: var(--nb-text-2);
    }
    html.dark-mode .nb-search-trigger:hover { background: rgba(255,255,255,.06); }
    .nb-search-trigger:focus-visible { outline: 2px solid var(--nb-primary); outline-offset: 2px; }
    .nb-search-trigger i { color: var(--nb-primary); font-size: 14px; }
    .nb-search-trigger .ph { flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .nb-search-trigger kbd {
        font: inherit;
        font-size: 10.5px;
        font-weight: 600;
        color: var(--nb-text-3);
        background: var(--nb-glass);
        padding: 2px 6px;
        border-radius: 6px;
        border: 1px solid var(--nb-border);
        box-shadow: 0 1px 0 var(--nb-border);
        white-space: nowrap;
    }

    /* ============================================================
       ACADEMIC STATUS CARD
       ============================================================ */
    .nb-academic {
        display: flex;
        align-items: center;
        height: 44px;
        padding: 0 12px;
        border-radius: 12px;
        background: var(--nb-primary-soft);
        border: 1px solid var(--nb-primary-border);
        cursor: default;
        flex-shrink: 0;
    }
    .nb-academic > i { font-size: 14px; color: var(--nb-primary); margin-right: 10px; }
    .nb-academic-cell { display: flex; flex-direction: column; line-height: 1.15; }
    .nb-academic-cell .k { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; color: var(--nb-text-3); }
    .nb-academic-cell .v { font-size: 12px; font-weight: 800; }
    .nb-ta  { color: var(--nb-green); }
    .nb-smt { color: var(--nb-primary); }
    .nb-academic-divider { width: 1px; height: 20px; background: var(--nb-primary-border); margin: 0 10px; }

    /* ============================================================
       ACTIONS CLUSTER
       ============================================================ */
    .nb-actions { display: flex; align-items: center; gap: 6px; margin-left: auto; min-width: 0; }

    /* ============================================================
       THEME SWITCH (keeps .theme-toggle class → existing JS logic)
       ============================================================ */
    .nb-theme-toggle i {
        display: inline-block;
        transition: transform .45s cubic-bezier(.34,1.56,.64,1);
    }
    html.dark-mode .nb-theme-toggle i { color: var(--nb-primary); }
    .nb-theme-toggle.nb-theme-flip i { animation: nbThemeSpin .5s cubic-bezier(.34,1.56,.64,1); }
    @keyframes nbThemeSpin { 0% { transform: rotate(0) scale(1); } 50% { transform: rotate(180deg) scale(1.25); } 100% { transform: rotate(360deg) scale(1); } }

    /* ============================================================
       PROFILE
       ============================================================ */
    .nb-profile {
        display: flex;
        align-items: center;
        gap: 10px;
        height: 46px;
        padding: 0 10px 0 6px;
        border-radius: 14px;
        border: 1px solid var(--nb-border);
        background: transparent;
        color: inherit;
        cursor: pointer;
        transition: background .2s, border-color .2s, box-shadow .2s;
        flex-shrink: 0;
    }
    .nb-profile:hover { background: var(--nb-primary-soft); border-color: var(--nb-primary-border); }
    .nb-profile:focus-visible { outline: 2px solid var(--nb-primary); outline-offset: 2px; }
    .nb-avatar {
        flex-shrink: 0;
        width: 34px;
        height: 34px;
        border-radius: 11px;
        color: #fff;
        font-size: 12px;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--nb-grad);
        box-shadow: 0 4px 10px -3px rgba(37,99,235,.45);
        letter-spacing: .5px;
    }
    .nb-profile-meta { display: flex; flex-direction: column; text-align: left; line-height: 1.15; min-width: 0; }
    .nb-profile-meta .n { font-size: 12.5px; font-weight: 700; color: var(--nb-text); max-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .nb-profile-meta .r { font-size: 10.5px; font-weight: 600; color: var(--nb-primary); }
    .nb-caret { font-size: 10px; color: var(--nb-text-3); margin-left: 2px; transition: transform .2s; }
    .dropdown.show .nb-caret { transform: rotate(180deg); }

    /* ============================================================
       DROPDOWN PANELS (glass, animated)
       ============================================================ */
    .nb-menu {
        border: 1px solid var(--nb-border);
        border-radius: 16px;
        padding: 6px;
        background: var(--nb-glass);
        -webkit-backdrop-filter: blur(18px) saturate(160%);
        backdrop-filter: blur(18px) saturate(160%);
        box-shadow: 0 18px 46px -12px rgba(15,23,42,.24);
        min-width: 230px;
        margin-top: 10px !important;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transform: translateY(6px) scale(.98);
        transform-origin: top right;
        transition: opacity .18s ease, transform .18s ease, visibility .18s;
    }
    .nb-menu.show { opacity: 1; visibility: visible; pointer-events: auto; transform: none; }
    .nb-menu-item {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 9px 12px;
        border-radius: 10px;
        color: var(--nb-text-2);
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: background .15s, color .15s, transform .15s;
        width: 100%;
        border: none;
        background: transparent;
        text-align: left;
        cursor: pointer;
    }
    .nb-menu-item:hover { background: var(--nb-primary-soft); color: var(--nb-primary); transform: translateX(2px); text-decoration: none; }
    .nb-menu-item:focus-visible { outline: 2px solid var(--nb-primary); outline-offset: 1px; }
    .nb-menu-item > i { width: 18px; text-align: center; font-size: 13px; color: var(--nb-text-3); transition: color .15s; }
    .nb-menu-item:hover > i { color: var(--nb-primary); }
    .nb-menu-sep { height: 1px; background: var(--nb-border); margin: 6px 10px; }
    .nb-menu-danger:hover, .nb-menu-danger:hover > i { background: var(--nb-red-soft); color: var(--nb-red); }
    .nb-menu-head {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        border-radius: 12px;
        background: var(--nb-primary-soft);
        border: 1px solid var(--nb-primary-border);
        margin-bottom: 6px;
    }
    .nb-menu-head .nb-avatar { width: 44px; height: 44px; border-radius: 13px; font-size: 15px; }
    .nb-menu-head .nm { display: flex; flex-direction: column; min-width: 0; line-height: 1.25; }
    .nb-menu-head .nm .a { font-size: 13.5px; font-weight: 800; color: var(--nb-text); }
    .nb-menu-head .nm .b { font-size: 10.5px; font-weight: 700; color: var(--nb-primary); }
    .nb-menu-head .nm .b.nb-online { display: flex; align-items: center; gap: 6px; color: var(--nb-green); }
    .nb-menu-head .nm .b .nb-dot {
        position: relative;
        flex-shrink: 0;
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: var(--nb-green);
        box-shadow: 0 0 0 3px var(--nb-green-soft);
    }
    .nb-menu-head .nm .b .nb-dot::after {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 50%;
        background: rgba(34, 197, 94, .4);
        animation: nbOnlinePing 2s cubic-bezier(.16, 1, .3, 1) infinite;
    }
    @keyframes nbOnlinePing {
        0% { transform: scale(1); opacity: .6; }
        100% { transform: scale(2.6); opacity: 0; }
    }
    .nb-menu-head .nm .c { font-size: 11px; color: var(--nb-text-3); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

    /* ============================================================
       NOTIFICATION CENTER
       ============================================================ */
    .nb-notif-trigger { position: relative; }
    .nb-badge {
        position: absolute;
        top: 5px;
        right: 5px;
        min-width: 17px;
        height: 17px;
        padding: 0 4px;
        border-radius: 20px;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: #fff;
        font-size: 9.5px;
        font-weight: 800;
        line-height: 17px;
        text-align: center;
        box-shadow: 0 3px 8px -2px rgba(220,38,38,.5), inset 0 0 0 2px var(--nb-glass);
        animation: nbBadgePop .5s cubic-bezier(.34,1.56,.64,1);
    }
    @keyframes nbBadgePop { 0% { transform: scale(0); } 60% { transform: scale(1.25); } 100% { transform: scale(1); } }
    .nb-notif-trigger.has-news i { animation: nbBellShake 2.4s ease-in-out infinite; }
    @keyframes nbBellShake {
        0%, 55%, 100% { transform: rotate(0); }
        60% { transform: rotate(14deg); }
        65% { transform: rotate(-12deg); }
        70% { transform: rotate(9deg); }
        75% { transform: rotate(-7deg); }
        80% { transform: rotate(4deg); }
        85% { transform: rotate(-2deg); }
    }
    .nb-notif-panel { width: 372px; max-width: calc(100vw - 24px); padding: 0; overflow: hidden; }
    .nb-notif-head {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 14px 16px;
        border-bottom: 1px solid var(--nb-border);
    }
    .nb-notif-head .tt { display: flex; align-items: center; gap: 9px; font-size: 14px; font-weight: 800; color: var(--nb-text); }
    .nb-notif-head .tt i { color: var(--nb-primary); }
    .nb-notif-head .cnt {
        min-width: 20px; height: 20px; padding: 0 6px; border-radius: 20px;
        background: var(--nb-primary); color: #fff; font-size: 10.5px; font-weight: 800;
        display: inline-flex; align-items: center; justify-content: center;
    }
    .nb-notif-head .all { margin-left: auto; }
    .nb-notif-head .all button {
        border: none; background: transparent; color: var(--nb-primary);
        font-size: 11.5px; font-weight: 700; padding: 4px 8px; border-radius: 8px; transition: background .15s;
    }
    .nb-notif-head .all button:hover { background: var(--nb-primary-soft); }
    .nb-notif-list { max-height: 372px; overflow-y: auto; overscroll-behavior: contain; }
    .nb-notif-list::-webkit-scrollbar { width: 5px; }
    .nb-notif-list::-webkit-scrollbar-thumb { background: var(--nb-border); border-radius: 5px; }
    .nb-notif-item {
        display: flex;
        align-items: flex-start;
        gap: 11px;
        padding: 12px 16px;
        border-bottom: 1px solid var(--nb-border-soft, var(--nb-border));
        transition: background .15s;
    }
    .nb-notif-item:hover { background: var(--nb-primary-soft); }
    .nb-notif-item.is-unread { background: var(--nb-primary-soft); }
    .nb-notif-item.is-unread::before { content: ''; width: 7px; height: 7px; border-radius: 50%; background: var(--nb-primary); margin-top: 7px; flex-shrink: 0; }
    .nb-notif-item.is-unread:hover { background: rgba(37,99,235,.10); }
    .nb-notif-ic {
        flex-shrink: 0;
        width: 36px;
        height: 36px;
        border-radius: 11px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        color: var(--nb-primary);
        background: var(--nb-primary-soft);
        border: 1px solid var(--nb-primary-border);
    }
    .nb-notif-ic.is-green  { color: var(--nb-green);  background: var(--nb-green-soft);  border-color: var(--nb-green-border); }
    .nb-notif-ic.is-red    { color: var(--nb-red);    background: var(--nb-red-soft);    border-color: var(--nb-red-border); }
    .nb-notif-ic.is-amber  { color: var(--nb-amber);  background: var(--nb-amber-soft);  border-color: var(--nb-amber-border); }
    .nb-notif-ic.is-sky    { color: var(--nb-sky);    background: var(--nb-sky-soft);    border-color: var(--nb-sky-border); }
    .nb-notif-ic.is-violet { color: var(--nb-violet); background: var(--nb-violet-soft); border-color: var(--nb-violet-border); }
    .nb-notif-body { flex: 1; min-width: 0; }
    .nb-notif-title { font-size: 12.5px; font-weight: 700; color: var(--nb-text); line-height: 1.35; }
    .nb-notif-meta { font-size: 11px; color: var(--nb-text-3); margin-top: 2px; }
    .nb-notif-time { font-size: 10.5px; color: var(--nb-text-3); margin-top: 3px; display: flex; align-items: center; gap: 5px; }
    .nb-notif-time i { font-size: 9px; opacity: .7; }
    .nb-notif-read {
        flex-shrink: 0;
        width: 28px; height: 28px; border-radius: 8px; border: none;
        background: transparent; color: var(--nb-text-3); cursor: pointer;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 12px; transition: all .15s; margin-top: 4px;
    }
    .nb-notif-read:hover { background: var(--nb-green-soft); color: var(--nb-green); }
    .nb-notif-empty { text-align: center; padding: 34px 20px; color: var(--nb-text-3); }
    .nb-notif-empty i { font-size: 34px; opacity: .35; display: block; margin-bottom: 10px; }
    .nb-notif-empty b { display: block; font-size: 13px; color: var(--nb-text-2); margin-bottom: 3px; }
    .nb-notif-empty span { font-size: 11.5px; }
    .nb-notif-foot {
        padding: 10px 12px;
        border-top: 1px solid var(--nb-border);
        display: flex;
        justify-content: center;
    }

    /* ============================================================
       GLOBAL SEARCH OVERLAY
       ============================================================ */
    .nb-search-overlay {
        position: fixed;
        inset: 0;
        z-index: 1060;
        display: flex;
        align-items: flex-start;
        justify-content: center;
        padding: 9vh 16px 16px;
        visibility: hidden;
        opacity: 0;
        transition: opacity .2s ease, visibility .2s;
    }
    .nb-search-overlay.is-open { visibility: visible; opacity: 1; }
    .nb-search-backdrop {
        position: absolute;
        inset: 0;
        background: rgba(15,23,42,.5);
        -webkit-backdrop-filter: blur(6px);
        backdrop-filter: blur(6px);
        transition: background .2s;
    }
    .nb-search-panel {
        position: relative;
        width: 100%;
        max-width: 600px;
        max-height: min(640px, 82vh);
        display: flex;
        flex-direction: column;
        background: var(--nb-glass);
        -webkit-backdrop-filter: blur(22px) saturate(160%);
        backdrop-filter: blur(22px) saturate(160%);
        border: 1px solid var(--nb-border);
        border-radius: 20px;
        box-shadow: 0 26px 70px -20px rgba(15,23,42,.4);
        overflow: hidden;
        transform: translateY(-12px) scale(.98);
        transition: transform .22s cubic-bezier(.32,.72,.24,1);
    }
    .nb-search-overlay.is-open .nb-search-panel { transform: translateY(0) scale(1); }
    .nb-search-input {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px 18px;
        border-bottom: 1px solid var(--nb-border);
    }
    .nb-search-input i { color: var(--nb-primary); font-size: 16px; flex-shrink: 0; }
    .nb-search-input input {
        flex: 1;
        min-width: 0;
        border: none;
        outline: none;
        background: transparent;
        font-size: 14.5px;
        font-weight: 600;
        color: var(--nb-text);
    }
    .nb-search-input input::placeholder { color: var(--nb-text-3); font-weight: 500; }
    .nb-search-input kbd {
        font: inherit; font-size: 10.5px; font-weight: 700; color: var(--nb-text-3);
        background: var(--nb-primary-soft); padding: 3px 7px; border-radius: 6px;
        border: 1px solid var(--nb-border); flex-shrink: 0;
    }
    .nb-search-body { flex: 1; overflow-y: auto; overscroll-behavior: contain; padding: 8px; }
    .nb-search-group-label {
        font-size: 10.5px; font-weight: 800; text-transform: uppercase; letter-spacing: .6px;
        color: var(--nb-text-3); padding: 10px 12px 6px;
    }
    .nb-search-result {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 12px;
        border-radius: 11px;
        text-decoration: none;
        color: var(--nb-text-2);
        transition: background .13s, color .13s;
    }
    .nb-search-result:hover, .nb-search-result.is-selected { background: var(--nb-primary-soft); color: var(--nb-primary); text-decoration: none; }
    .nb-search-result.is-selected { box-shadow: inset 0 0 0 1px var(--nb-primary-border); }
    .nb-search-result .ic {
        flex-shrink: 0;
        width: 34px; height: 34px; border-radius: 10px;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 14px; color: var(--nb-primary);
        background: var(--nb-primary-soft); border: 1px solid var(--nb-primary-border);
    }
    .nb-search-result .tt { display: flex; flex-direction: column; line-height: 1.2; min-width: 0; }
    .nb-search-result .tt .a { font-size: 13px; font-weight: 700; color: var(--nb-text); }
    .nb-search-result:hover .tt .a, .nb-search-result.is-selected .tt .a { color: var(--nb-primary); }
    .nb-search-result .tt .b { font-size: 11px; color: var(--nb-text-3); }
    .nb-search-empty { text-align: center; padding: 36px 20px; color: var(--nb-text-3); }
    .nb-search-empty i { font-size: 36px; opacity: .35; display: block; margin-bottom: 10px; }
    .nb-search-empty b { display: block; font-size: 13.5px; color: var(--nb-text-2); margin-bottom: 4px; }
    .nb-search-empty .go {
        display: inline-flex; align-items: center; gap: 8px; margin-top: 12px; padding: 8px 16px;
        border-radius: 10px; font-size: 12.5px; font-weight: 700; color: #fff;
        background: var(--nb-grad); border: none; text-decoration: none; box-shadow: 0 6px 16px -4px rgba(37,99,235,.5);
    }
    .nb-search-empty .go:hover { text-decoration: none; transform: translateY(-1px); }
    .nb-search-hint { padding: 8px 12px 16px; }
    .nb-search-cats { display: flex; flex-wrap: wrap; gap: 8px; }
    .nb-search-chip {
        display: inline-flex; align-items: center; gap: 7px; padding: 7px 12px;
        border-radius: 20px; font-size: 12px; font-weight: 600; color: var(--nb-text-2);
        background: var(--nb-primary-soft); border: 1px solid var(--nb-primary-border); cursor: pointer;
        transition: all .15s;
    }
    .nb-search-chip:hover { background: var(--nb-primary); color: #fff; }
    .nb-search-chip i { font-size: 11px; }
    .nb-search-note {
        display: flex; align-items: flex-start; gap: 9px; margin-top: 14px;
        font-size: 11.5px; color: var(--nb-text-3); line-height: 1.55;
    }
    .nb-search-note i { color: var(--nb-primary); margin-top: 2px; flex-shrink: 0; }
    .nb-search-foot {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 14px;
        padding: 9px 16px;
        border-top: 1px solid var(--nb-border);
        font-size: 10.5px;
        color: var(--nb-text-3);
        background: var(--nb-primary-soft);
    }
    .nb-search-foot span { display: inline-flex; align-items: center; gap: 5px; }
    .nb-search-foot kbd {
        font: inherit; font-weight: 700; color: var(--nb-text-2);
        background: var(--nb-glass); padding: 1px 5px; border-radius: 4px;
        border: 1px solid var(--nb-border);
    }

    /* ============================================================
       TOAST (lightweight, used by search footer placeholders)
       ============================================================ */
    .nb-toast {
        position: fixed;
        bottom: 24px;
        left: 50%;
        transform: translate(-50%, 16px);
        z-index: 1090;
        max-width: 90vw;
        padding: 11px 18px;
        border-radius: 12px;
        background: #0f172a;
        color: #f8fafc;
        font-size: 13px;
        font-weight: 600;
        box-shadow: 0 14px 34px -8px rgba(0,0,0,.4);
        opacity: 0;
        pointer-events: none;
        transition: opacity .25s ease, transform .25s ease;
    }
    .nb-toast.show { opacity: 1; transform: translate(-50%, 0); }
    html.dark-mode .nb-toast { background: #dbeafe; color: #1e3a8a; }

    /* ============================================================
       RESPONSIVE — tablet
       ============================================================ */
    @media (max-width: 1199.98px) {
        .nb-search-trigger { min-width: 180px; }
    }
    @media (max-width: 991.98px) {
        .nb-search-trigger {
            min-width: 0;
            width: 44px;
            padding: 0;
            justify-content: center;
            background: transparent;
            border-color: transparent;
        }
        .nb-search-trigger .ph, .nb-search-trigger kbd { display: none; }
        .nb-search-trigger:hover { background: var(--nb-primary-soft); box-shadow: none; }
        .nb-academic { padding: 0 10px; }
        .nb-academic-cell .k { display: none; }
        .nb-academic-divider { margin: 0 8px; }
    }
    @media (max-width: 575.98px) {
        .nb-notif-panel { position: fixed !important; left: 10px !important; right: 10px !important; width: auto !important; top: 64px; transform-origin: top center; }
    }

    /* ============================================================
       RESPONSIVE — mobile (matches nav-side-bar.js <= 768 breakpoint)
       ============================================================ */
    @media (max-width: 768px) {
        header.nb-navbar.l-header { height: 62px; }
        .nb-inner { padding: 0 10px; gap: 6px; }
        .nb-hamburger { display: inline-flex; }
        .nb-academic { display: none !important; }
        .nb-profile { padding: 0; border: none; background: transparent; }
        .nb-profile:hover { background: transparent; border-color: transparent; }
        .nb-profile-meta, .nb-caret { display: none; }
        .nb-actions { gap: 2px; }
        .nb-search-trigger { width: 40px; height: 40px; }
        .nb-iconbtn { width: 40px; height: 40px; }
        .nb-avatar { width: 34px; height: 34px; }
        .nb-search-panel { max-height: 86vh; }
    }

    /* ============================================================
       LEGACY NEUTRALIZATION — protect new navbar from older
       dark-mode.css / nav-side-bar.css rules (scoped + !important)
       ============================================================ */
    html.dark-mode header.nb-navbar.l-header {
        position: sticky;
        top: 0;
        background: var(--nb-glass);
        -webkit-backdrop-filter: blur(var(--nb-blur)) saturate(160%);
        backdrop-filter: blur(var(--nb-blur)) saturate(160%);
        border-bottom: 1px solid var(--nb-border);
        box-shadow: var(--nb-shadow);
    }
    html.dark-mode header.nb-navbar.l-header::after { content: none; }
    html.dark-mode header.nb-navbar.l-header.is-scrolled {
        background: rgba(7,20,26,.85);
        box-shadow: 0 10px 30px -12px rgba(0,0,0,.5);
    }
    html.dark-mode .nb-navbar .nb-notif-trigger .fa-bell {
        color: var(--nb-text-2) !important;
        text-shadow: none !important;
        filter: none !important;
    }
    html.dark-mode .nb-navbar .nb-theme-toggle.theme-toggle {
        color: var(--nb-text-2) !important;
        background: transparent !important;
        border: none !important;
        border-radius: 12px !important;
        box-shadow: none !important;
    }
    html.dark-mode .nb-navbar .nb-theme-toggle.theme-toggle:hover {
        background: var(--nb-primary-soft) !important;
        color: var(--nb-primary) !important;
    }
    html.dark-mode .nb-navbar .nb-theme-toggle i {
        filter: none !important;
        text-shadow: none !important;
        color: var(--nb-primary) !important;
    }
    html.dark-mode .nb-navbar .nb-notif-trigger i { color: var(--nb-text-2) !important; }
    /* Bell reuses Bootstrap .dropdown-toggle/.nav-link → strip caret + padding */
    .nb-navbar .nb-notif-trigger.nav-link {
        padding: 0;
        color: inherit;
    }
    .nb-navbar .nb-notif-trigger.dropdown-toggle::after { display: none; }

    @media (max-width: 768px) {
        .nb-navbar .nb-theme-toggle.theme-toggle {
            width: 40px !important; height: 40px !important;
            font-size: 16px !important; border-radius: 12px !important;
        }
        .nb-navbar .nb-theme-toggle.theme-toggle i { font-size: 16px !important; }
        .nb-navbar .nb-notif-trigger i { font-size: 16px !important; }
    }

    @media (prefers-reduced-motion: reduce) {
        header.nb-navbar.l-header,
        .nb-navbar *, .nb-navbar *::before, .nb-navbar *::after,
        .nb-search-overlay * { animation: none !important; transition: none !important; }
    }

    /* Profile menu: pin ke pojok kanan atas (anti melebar ke kanan, lepas dari Popper) */
    .nb-profile-dd .nb-menu {
        position: fixed !important;
        top: 62px !important;
        right: 12px !important;
        left: auto !important;
        margin-top: 0 !important;
    }

    @media (max-width: 768px) {
        .nb-profile-dd .nb-menu {
            top: 55px !important;
            right: 10px !important;
        }
    }
</style>

<header class="l-header nb-navbar" id="nbNavbar">
    <div class="nb-inner">

        {{-- MOBILE MENU TOGGLE (desktop-hidden; opens sidebar via nav-side-bar.js js-hamburger) --}}
        <button type="button" class="nb-iconbtn nb-hamburger js-hamburger"
                aria-label="Buka menu navigasi" title="Menu">
            <i class="fas fa-bars" aria-hidden="true"></i>
        </button>

        {{-- ACADEMIC STATUS CARD --}}
        @if($nbTA)
        <div class="nb-academic" title="Tahun Ajaran Aktif" aria-label="Tahun Ajaran Aktif: {{ $nbTA }}">
            <i class="fas fa-calendar-check"></i>
            <div class="nb-academic-cell">
                <span class="k">Tahun Ajaran</span>
                <span class="v nb-ta">{{ $nbTA }}</span>
            </div>
            @if($nbSemester)
            <span class="nb-academic-divider"></span>
            <div class="nb-academic-cell">
                <span class="k">Semester</span>
                <span class="v nb-smt">{{ $nbSemester }}</span>
            </div>
            @endif
        </div>
        @endif

        {{-- GLOBAL SEARCH --}}
        <button type="button" class="nb-search-trigger" id="nbSearchBtn"
                aria-label="Cari menu, guru, siswa, user, kelas (Ctrl+K)" title="Cari (Ctrl+K)">
            <i class="fas fa-search"></i>
            <span class="ph">Cari menu, guru, siswa...</span>
            <kbd>Ctrl&nbsp;K</kbd>
        </button>

        <div class="nb-actions">

            {{-- NOTIFICATION CENTER --}}
            @include('component.admin.notification-bell')

            {{-- THEME SWITCH (class .theme-toggle → existing logic preserved) --}}
            <button type="button" class="nb-iconbtn nb-ripple nb-theme-toggle theme-toggle"
                    aria-label="Ganti tema terang / gelap" title="Mode Terang / Mode Gelap">
                <i class="fas fa-moon" aria-hidden="true"></i>
            </button>

            {{-- PROFILE --}}
            <div class="dropdown nb-profile-dd">
                <button type="button" class="nb-profile" id="nbProfileBtn"
                        data-bs-toggle="dropdown" aria-expanded="false"
                        aria-label="Menu profil {{ $nbName }}">
                    <span class="nb-avatar" aria-hidden="true">{{ $nbInitials }}</span>
                    <span class="nb-profile-meta">
                        <span class="n">{{ strtok($nbName, ' ') }}</span>
                        <span class="r">{{ $nbRoleSub }}</span>
                    </span>
                    <i class="fas fa-chevron-down nb-caret" aria-hidden="true"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end nb-menu" aria-labelledby="nbProfileBtn" style="min-width:270px;">
                    <div class="nb-menu-head">
                        <span class="nb-avatar" aria-hidden="true">{{ $nbInitials }}</span>
                        <span class="nm">
                            <span class="a">{{ $nbName }}</span>
                            <span class="b nb-online"><i class="nb-dot"></i> Online</span>
                            @if($nbUser->email)
                            <span class="c">{{ $nbUser->email }}</span>
                            @endif
                        </span>
                    </div>
                    <a class="nb-menu-item" href="{{ route('profil-saya.index') }}">
                        <i class="fas fa-user-circle"></i> Profil Saya
                    </a>
                    <a class="nb-menu-item" href="/">
                        <i class="fas fa-globe"></i> Kembali ke Website
                    </a>
                    <div class="nb-menu-sep"></div>
                    <a class="nb-menu-item nb-menu-danger" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                </div>
            </div>
        </div>
    </div>
</header>

{{-- ============================================================
     GLOBAL SEARCH OVERLAY
     ============================================================ --}}
<div class="nb-search-overlay" id="nbSearchOverlay" role="dialog" aria-modal="true" aria-label="Pencarian global">
    <div class="nb-search-backdrop" data-nb-close></div>
    <div class="nb-search-panel">
        <div class="nb-search-input">
            <i class="fas fa-search" aria-hidden="true"></i>
            <input type="text" id="nbSearchInput" placeholder="Cari menu, guru, siswa, user, kelas..." autocomplete="off">
            <kbd>ESC</kbd>
        </div>
        <div class="nb-search-body">
            <div id="nbSearchResults"></div>
            <div class="nb-search-hint" id="nbSearchHint">
                <div class="nb-search-group-label">Pintasan Pencarian</div>
                <div class="nb-search-cats">
                    <button type="button" class="nb-search-chip" data-q="menu"><i class="fas fa-compass"></i> Menu</button>
                    <button type="button" class="nb-search-chip" data-q="guru"><i class="fas fa-user-tie"></i> Guru</button>
                    <button type="button" class="nb-search-chip" data-q="siswa"><i class="fas fa-user-graduate"></i> Siswa</button>
                    <button type="button" class="nb-search-chip" data-q="user"><i class="fas fa-users"></i> User</button>
                    <button type="button" class="nb-search-chip" data-q="kelas"><i class="fas fa-chalkboard"></i> Kelas</button>
                </div>
                <div class="nb-search-note">
                    <i class="fas fa-info-circle"></i>
                    <div>Pencarian menampilkan menu aplikasi secara instan. Pencarian data guru, siswa, user, dan kelas tersedia lewat halaman Master yang relevan.</div>
                </div>
            </div>
        </div>
        <div class="nb-search-foot">
            <span><kbd>↑</kbd><kbd>↓</kbd> navigasi</span>
            <span><kbd>Enter</kbd> buka</span>
            <span><kbd>Esc</kbd> tutup</span>
        </div>
    </div>
</div>

{{-- ============================================================
     ADDITIVE JS — search overlay, theme flip, toast
     (no changes to existing nav-side-bar.js / theme logic)
     ============================================================ --}}
@push('scripts')
<script>
(function() {
    var $ = window.jQuery;
    var doc = document, root = doc.documentElement;

    /* ---------- lightweight toast ---------- */
    window.nbToast = function(msg) {
        var t = doc.getElementById('nbToast');
        if (!t) { t = doc.createElement('div'); t.id = 'nbToast'; t.className = 'nb-toast'; doc.body.appendChild(t); }
        t.textContent = msg;
        t.classList.add('show');
        clearTimeout(window.__nbToastT);
        window.__nbToastT = setTimeout(function() { t.classList.remove('show'); }, 2600);
    };

    /* ---------- scrolled shadow ---------- */
    var nav = doc.getElementById('nbNavbar');
    if (nav) {
        var onScroll = function() { nav.classList.toggle('is-scrolled', (window.scrollY || window.pageYOffset) > 8); };
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    }

    /* ---------- theme switch flip animation (additive, no logic change) ---------- */
    var themeBtn = doc.querySelector('.nb-theme-toggle');
    if (themeBtn && window.MutationObserver) {
        var lastDark = root.classList.contains('dark-mode');
        new MutationObserver(function() {
            var isDark = root.classList.contains('dark-mode');
            if (isDark !== lastDark) {
                lastDark = isDark;
                themeBtn.classList.remove('nb-theme-flip');
                void themeBtn.offsetWidth;
                themeBtn.classList.add('nb-theme-flip');
            }
        }).observe(root, { attributes: true, attributeFilter: ['class'] });
    }

    /* ---------- global search ---------- */
    var NB_MENU = [
        { l: 'Dashboard',          i: 'fas fa-home',               u: '/home',                  k: 'home dashboard beranda utama' },
        { l: 'Master User',        i: 'fas fa-users',              u: '/master-user',           k: 'user akun login operator admin' },
        { l: 'Master Guru',        i: 'fas fa-user-tie',           u: '/master-guru',           k: 'guru pengajar wali mapel tenaga' },
        { l: 'Master Siswa',       i: 'fas fa-user-graduate',      u: '/master-siswa',          k: 'siswa peserta didik murid santri' },
        { l: 'Master Kelas',       i: 'fas fa-chalkboard',         u: '/kelas',                 k: 'kelas rombel rombongan ruang' },
        { l: 'Wali Kelas',         i: 'fas fa-user-tag',           u: '/wali-kelas',            k: 'wali kelas wali' },
        { l: 'Mata Pelajaran',     i: 'fas fa-book',               u: '/mata-pelajaran',        k: 'mapel mata pelajaran kurikulum' },
        { l: 'Jadwal Pelajaran',   i: 'fas fa-calendar-week',      u: '/jadwal-pelajaran',      k: 'jadwal pelajaran mengajar' },
        { l: 'Absensi Siswa',      i: 'fas fa-clipboard-check',    u: '/absensi',               k: 'absensi kehadiran hadir siswa' },
        { l: 'Absensi Guru',       i: 'fas fa-user-check',         u: '/admin/absensi-guru',    k: 'absensi guru kehadiran hadir' },
        { l: 'Penilaian',          i: 'fas fa-star',               u: '/penilaian',             k: 'nilai penilaian rapor prestasi' },
        { l: 'Haflatul Imtihan',   i: 'fas fa-award',              u: '/haflatul-imtihan',      k: 'haflah imtihan wisuda kelulusan' },
        { l: 'Daftar Sesi',        i: 'fas fa-clock',              u: '/sesi',                  k: 'sesi jadwal gelombang' },
        { l: 'Sesi Lomba',         i: 'fas fa-stopwatch',          u: '/sesi-lomba',            k: 'sesi lomba perlombaan' },
        { l: 'Lomba',              i: 'fas fa-trophy',             u: '/lomba',                 k: 'lomba perlombaan kompetisi' },
        { l: 'Peserta Lomba',      i: 'fas fa-user-tie',           u: '/peserta-lomba',         k: 'peserta lomba anggota' },
        { l: 'Kelompok Lomba',     i: 'fas fa-users',              u: '/kelompok-lomba',        k: 'kelompok lomba grup' },
        { l: 'Juri',               i: 'fas fa-user-check',         u: '/juri-lomba',            k: 'juri lomba dewan penilai' },
        { l: 'Aspek Penilaian',    i: 'fas fa-list-check',         u: '/aspek-penilaian',       k: 'aspek penilaian kriteria lomba' },
        { l: 'Penilaian Lomba',    i: 'fas fa-clipboard-check',    u: '/penilaian-lomba',       k: 'penilaian lomba nilai juri' },
        { l: 'Hasil Lomba',        i: 'fas fa-medal',              u: '/hasil-lomba',           k: 'hasil lomba juara pemenang' },
        { l: 'Pengumuman',         i: 'fas fa-bullhorn',           u: '/pengumuman',            k: 'pengumuman info berita' },
        { l: 'Galeri',             i: 'fas fa-images',             u: '/galery',                k: 'galeri foto dokumentasi' },
        { l: 'Profil Madrasah',    i: 'fas fa-building',           u: '/profil-madrasah',       k: 'profil madrasah sekolah identitas' },
        { l: 'Keamanan',           i: 'fas fa-shield-alt',         u: '/admin/keamanan',        k: 'keamanan security dashboard' },
        { l: 'Riwayat Login',      i: 'fas fa-history',            u: '/admin/riwayat-login',   k: 'riwayat login log histori' },
        { l: 'Perangkat Aktif',    i: 'fas fa-mobile-alt',         u: '/perangkat',             k: 'perangkat sesi aktif login' },
        { l: 'Kebijakan 2FA',      i: 'fas fa-key',                u: '/admin/kebijakan-2fa',   k: 'kebijakan 2fa otp keamanan' },
        { l: 'Lokasi Madrasah',    i: 'fas fa-map-marker-alt',     u: '/lokasi-madrasah',       k: 'lokasi madrasah alamat map peta' }
    ];

    var ENTITY_PAGE = {
        guru:  '/master-guru',
        siswa: '/master-siswa',
        user:  '/master-user',
        kelas: '/kelas',
        menu:  null
    };

    var searchOverlay = doc.getElementById('nbSearchOverlay');
    var searchInput   = doc.getElementById('nbSearchInput');
    var searchResults = doc.getElementById('nbSearchResults');
    var searchHint    = doc.getElementById('nbSearchHint');
    var searchBtn     = doc.getElementById('nbSearchBtn');
    if (!searchOverlay || !searchInput) return;

    var selIndex = -1;
    var lastResults = [];

    function esc(s) { return String(s).replace(/[&<>"']/g, function(c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]; }); }

    function openSearch() {
        searchOverlay.classList.add('is-open');
        searchOverlay.setAttribute('aria-hidden', 'false');
        doc.body.style.overflow = 'hidden';
        setTimeout(function() { searchInput.focus(); }, 60);
    }
    function closeSearch() {
        searchOverlay.classList.remove('is-open');
        searchOverlay.setAttribute('aria-hidden', 'true');
        doc.body.style.overflow = '';
        searchInput.value = '';
        renderHint();
    }
    function isOpen() { return searchOverlay.classList.contains('is-open'); }

    function renderHint() {
        searchResults.innerHTML = '';
        searchHint.style.display = '';
    }

    function renderResults(q) {
        q = q.trim().toLowerCase();
        if (!q) { renderHint(); return; }
        var hits = NB_MENU.filter(function(m) { return m.l.toLowerCase().indexOf(q) !== -1 || m.k.indexOf(q) !== -1; });
        var entity = null;
        var keys = Object.keys(ENTITY_PAGE);
        for (var i = 0; i < keys.length; i++) {
            if (q.indexOf(keys[i]) !== -1 && ENTITY_PAGE[keys[i]]) { entity = { key: keys[i], url: ENTITY_PAGE[keys[i]] }; break; }
        }
        searchHint.style.display = 'none';
        if (!hits.length) {
            var empty = '<div class="nb-search-empty"><i class="fas fa-search"></i>'
                + '<b>Tidak ada hasil untuk &ldquo;' + esc(q) + '&rdquo;</b>'
                + '<span>Coba kata kunci lain atau jelajahi menu Master.</span>';
            if (entity) {
                empty += '<br><a class="go" href="' + entity.url + '"><i class="fas fa-arrow-right"></i> Buka ' + esc(entity.key.charAt(0).toUpperCase() + entity.key.slice(1)) + '</a>';
            }
            empty += '</div>';
            searchResults.innerHTML = empty;
            lastResults = [];
            selIndex = -1;
            return;
        }
        selIndex = -1;
        lastResults = hits;
        searchResults.innerHTML = '<div class="nb-search-group-label">Menu &amp; Halaman</div>'
            + hits.map(function(m, idx) {
                return '<a class="nb-search-result" data-i="' + idx + '" href="' + m.u + '">'
                    + '<span class="ic"><i class="' + m.i + '"></i></span>'
                    + '<span class="tt"><span class="a">' + esc(m.l) + '</span><span class="b">' + m.u + '</span></span>'
                    + '</a>';
            }).join('');
        select(0);
    }

    function select(idx) {
        if (!lastResults.length) return;
        var items = searchResults.querySelectorAll('.nb-search-result');
        if (idx < 0) idx = 0;
        if (idx > items.length - 1) idx = items.length - 1;
        selIndex = idx;
        items.forEach(function(el, i) { el.classList.toggle('is-selected', i === idx); });
        if (items[idx]) items[idx].scrollIntoView({ block: 'nearest' });
    }

    searchBtn.addEventListener('click', openSearch);
    searchOverlay.querySelector('[data-nb-close]').addEventListener('click', closeSearch);
    searchInput.addEventListener('input', function() { renderResults(searchInput.value); });
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') { e.preventDefault(); closeSearch(); }
        else if (e.key === 'ArrowDown') { e.preventDefault(); select(selIndex + 1); }
        else if (e.key === 'ArrowUp') { e.preventDefault(); select(selIndex - 1); }
        else if (e.key === 'Enter') {
            e.preventDefault();
            var hit = lastResults[selIndex] || lastResults[0];
            if (hit) window.location.href = hit.u;
        }
    });
    searchResults.addEventListener('mousemove', function(e) {
        var el = e.target.closest('.nb-search-result');
        if (el) select(parseInt(el.getAttribute('data-i'), 10));
    });
    doc.querySelectorAll('.nb-search-chip').forEach(function(chip) {
        chip.addEventListener('click', function() {
            searchInput.value = chip.getAttribute('data-q');
            renderResults(searchInput.value);
            searchInput.focus();
        });
    });

    doc.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
            e.preventDefault();
            isOpen() ? closeSearch() : openSearch();
        }
    });
})();
</script>
@endpush
