{{-- ============================================================
     LOMBA WORKSPACE — Design System terpadu Modul Lomba (Haflatul Imtihan)
     Identitas: navy-indigo #2B3C78 + gold #E7A615 ("Semarak").
     Dipakai DI SEMUA halaman modul lomba (admin): haflah, sesi-lomba,
     lomba, peserta-lomba, kelompok-lomba, anggota-kelompok, juri-lomba,
     aspek-penilaian, penilaian-lomba, hasil-lomba.
     Skema: workspace premium, rounded 14px, soft shadow, glass ringan,
     dark-mode ready via `html.dark-mode`.
     ============================================================ --}}

@php
if (!function_exists('lw_status_chip')) {
    function lw_status_chip($status)
    {
        return match ($status) {
            'Aktif' => 'lw-chip--green',
            'Persiapan' => 'lw-chip--amber',
            'Selesai' => 'lw-chip--navy',
            'Belum Mulai' => 'lw-chip--amber',
            'Berlangsung' => 'lw-chip--green',
            'Terdaftar' => 'lw-chip--navy',
            'Hadir' => 'lw-chip--green',
            'Tidak Hadir' => 'lw-chip--red',
            'Diskualifikasi' => 'lw-chip--red',
            'Individu' => 'lw-chip--navy',
            'Tim' => 'lw-chip--violet',
            'Juara 1' => 'lw-chip--gold',
            'Juara 2' => 'lw-chip--slate',
            'Juara 3' => 'lw-chip--amber',
            default => 'lw-chip--slate'
        };
    }
}
if (!function_exists('lw_status_icon')) {
    function lw_status_icon($status)
    {
        return match ($status) {
            'Aktif' => 'bi-play-circle-fill',
            'Persiapan' => 'bi-hourglass-split',
            'Selesai' => 'bi-archive-fill',
            'Belum Mulai' => 'bi-hourglass-split',
            'Berlangsung' => 'bi-play-circle-fill',
            'Terdaftar' => 'bi-person-check-fill',
            'Hadir' => 'bi-check-circle-fill',
            'Tidak Hadir' => 'bi-x-circle-fill',
            'Diskualifikasi' => 'bi-slash-circle-fill',
            'Individu' => 'bi-person-fill',
            'Tim' => 'bi-people-fill',
            'Juara 1' => 'bi-trophy-fill',
            'Juara 2' => 'bi-trophy-fill',
            'Juara 3' => 'bi-trophy-fill',
            default => 'bi-circle-fill'
        };
    }
}
if (!function_exists('lw_seg_color')) {
    function lw_seg_color($i)
    {
        $palette = ['#2b3c78', '#e7a615', '#0e9f6e', '#d97706', '#db2777', '#3b82f6', '#7c3aed', '#0891b2'];
        return $palette[$i % count($palette)];
    }
}
if (!function_exists('lw_mini_bar')) {
    function lw_mini_bar($values)
    {
        $values = array_values(array_map(function ($v) {
            return max(0, (int) $v);
        }, $values));
        $total = array_sum($values);
        if ($total <= 0) {
            return '<span class="lw-bar lw-bar--empty"><i></i></span>';
        }
        $max = max($values) ?: 1;
        $bars = '';
        foreach ($values as $v) {
            $h = max(12, (int) round(($v / $max) * 100));
            $bars .= '<i style="height:' . $h . '%;"></i>';
        }
        return '<span class="lw-bar">' . $bars . '</span>';
    }
}
if (!function_exists('lw_dist_segs')) {
    function lw_dist_segs($values)
    {
        $values = array_values(array_map(function ($v) {
            return max(0, (int) $v);
        }, $values));
        $total = array_sum($values);
        if ($total <= 0) {
            return '<span class="lw-dist lw-dist--empty"><i></i></span>';
        }
        $seg = '';
        foreach ($values as $k => $v) {
            if ($v <= 0) {
                continue;
            }
            $w = ($v / $total) * 100;
            $seg .= '<i style="width:' . $w . '%;background:' . lw_seg_color($k) . ';" title="' . $v . '"></i>';
        }
        return '<span class="lw-dist">' . $seg . '</span>';
    }
}
if (!function_exists('lw_initial')) {
    function lw_initial($nama)
    {
        $nama = trim((string) $nama);
        if ($nama === '') {
            return '?';
        }
        $parts = preg_split('/\s+/', $nama);
        $init = mb_substr($parts[0], 0, 1);
        if (count($parts) > 1) {
            $init .= mb_substr(end($parts), 0, 1);
        }
        return mb_strtoupper($init);
    }
}
if (!function_exists('lw_ava_color')) {
    function lw_ava_color($nama)
    {
        $palette = ['#2b3c78', '#e7a615', '#0e9f6e', '#d97706', '#db2777', '#3b82f6', '#7c3aed', '#0891b2'];
        $h = 0;
        $s = (string) $nama;
        for ($i = 0; $i < strlen($s); $i++) {
            $h = ($h * 31 + ord($s[$i])) & 0x7fffffff;
        }
        return $palette[$h % count($palette)];
    }
}
if (!function_exists('lw_juara')) {
    function lw_juara($peringkat)
    {
        $p = (int) $peringkat;
        if ($p === 1) {
            return '🥇';
        }
        if ($p === 2) {
            return '🥈';
        }
        if ($p === 3) {
            return '🥉';
        }
        return null;
    }
}
if (!function_exists('lw_medal')) {
    function lw_medal($peringkat)
    {
        $p = (int) $peringkat;
        if ($p === 1) {
            return '<span class="lw-medal lw-medal--gold" aria-hidden="true"><i class="bi bi-trophy-fill"></i></span>';
        }
        if ($p === 2) {
            return '<span class="lw-medal lw-medal--silver" aria-hidden="true"><i class="bi bi-trophy-fill"></i></span>';
        }
        if ($p === 3) {
            return '<span class="lw-medal lw-medal--bronze" aria-hidden="true"><i class="bi bi-trophy-fill"></i></span>';
        }
        return null;
    }
}
@endphp

<style>
.lw-mod {
    --lw-primary: #2b3c78;
    --lw-primary-2: #3b4e96;
    --lw-primary-3: #6b7fc4;
    --lw-primary-4: #8fa1da;
    --lw-primary-dark: #22305f;
    --lw-primary-soft: rgba(43, 60, 120, .09);
    --lw-primary-border: rgba(43, 60, 120, .27);
    --lw-accent: #e7a615;
    --lw-accent-2: #f2bc2e;
    --lw-accent-3: #ffd97a;
    --lw-accent-soft: rgba(231, 166, 21, .12);
    --lw-accent-border: rgba(231, 166, 21, .34);
    --lw-grad: linear-gradient(135deg, #22305f 0%, #2b3c78 55%, #3b4e96 100%);
    --lw-grad-rad: radial-gradient(120% 130% at 8% 0%, rgba(255, 255, 255, .16) 0%, rgba(255, 255, 255, 0) 46%), linear-gradient(135deg, #1d2b53 0%, #31458f 100%);
    --lw-grad-soft: linear-gradient(135deg, rgba(43, 60, 120, .08) 0%, rgba(231, 166, 21, .06) 100%);
    --lw-bg: #f5f7fb;
    --lw-card: #ffffff;
    --lw-border: #e5eaf3;
    --lw-border-soft: #f1f4fa;
    --lw-text: #1b2437;
    --lw-text-2: #5a6a85;
    --lw-text-3: #9aa7bd;
    --lw-shadow: 0 1px 2px rgba(27, 36, 55, .05), 0 6px 20px -6px rgba(27, 36, 55, .09);
    --lw-shadow-lg: 0 28px 64px -14px rgba(27, 36, 55, .22);
    --lw-radius: 14px;
    --lw-radius-lg: 20px;
    --lw-green: #0e9f6e; --lw-green-soft: rgba(14, 159, 110, .11); --lw-green-border: rgba(14, 159, 110, .32);
    --lw-amber: #d97706; --lw-amber-soft: rgba(217, 119, 6, .11); --lw-amber-border: rgba(217, 119, 6, .32);
    --lw-red: #dc4c4c; --lw-red-soft: rgba(220, 76, 76, .10); --lw-red-border: rgba(220, 76, 76, .30);
    --lw-sky: #3b82f6; --lw-sky-soft: rgba(59, 130, 246, .11); --lw-sky-border: rgba(59, 130, 246, .32);
    --lw-violet: #7c3aed; --lw-violet-soft: rgba(124, 58, 237, .10); --lw-violet-border: rgba(124, 58, 237, .30);
    --lw-rose: #db2777; --lw-rose-soft: rgba(219, 39, 119, .10); --lw-rose-border: rgba(219, 39, 119, .30);
    --lw-navy: #2b3c78; --lw-navy-soft: rgba(43, 60, 120, .09); --lw-navy-border: rgba(43, 60, 120, .27);
    --lw-gold: #b45309; --lw-gold-soft: rgba(231, 166, 21, .14); --lw-gold-border: rgba(231, 166, 21, .36);

    font-family: 'Poppins', system-ui, -apple-system, sans-serif;
    color: var(--lw-text);
}
html.dark-mode .lw-mod {
    --lw-bg: rgba(148, 163, 184, .06);
    --lw-card: rgba(255, 255, 255, .055);
    --lw-border: rgba(255, 255, 255, .13);
    --lw-border-soft: rgba(255, 255, 255, .06);
    --lw-text: #f1f4fb;
    --lw-text-2: #c6cede;
    --lw-text-3: #8692ad;
    --lw-primary-soft: rgba(111, 133, 203, .18);
    --lw-primary-border: rgba(143, 161, 218, .45);
    --lw-accent-soft: rgba(247, 193, 65, .15);
    --lw-accent-border: rgba(247, 193, 65, .45);
    --lw-grad-soft: linear-gradient(135deg, rgba(43, 60, 120, .28) 0%, rgba(231, 166, 21, .12) 100%);
    --lw-shadow: 0 4px 20px -6px rgba(0, 0, 0, .5);
    --lw-shadow-lg: 0 34px 80px -14px rgba(0, 0, 0, .6);
}
.lw-mod a { text-decoration: none !important; }
.lw-mod [data-bs-toggle="modal"], .lw-mod .lw-clickable, .lw-mod .lw-btn, .lw-mod .lw-card--lift, .lw-mod .lw-qn-card, .lw-mod .lw-team-card, .lw-mod .lw-pick-card { cursor: pointer; }
.lw-mod :focus-visible { outline: 2px solid var(--lw-primary); outline-offset: 2px; border-radius: 8px; }

/* ---------- Buttons ---------- */
.lw-btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 7px;
    border: 1px solid var(--lw-border); border-radius: 12px; padding: 10px 18px;
    font-size: 13px; font-weight: 600; font-family: inherit;
    background: var(--lw-card); color: var(--lw-text);
    transition: all .2s ease; position: relative; overflow: hidden; white-space: nowrap;
}
.lw-btn i { font-size: 14px; }
.lw-btn:hover { transform: translateY(-1px); box-shadow: var(--lw-shadow); color: var(--lw-text); }
.lw-btn:active { transform: translateY(0) scale(.98); }
.lw-btn:disabled { opacity: .55; cursor: not-allowed; transform: none; box-shadow: none; }
.lw-btn--solid { background: var(--lw-grad); color: #fff; border-color: transparent; box-shadow: 0 8px 22px -8px rgba(43, 60, 120, .6); }
.lw-btn--solid:hover { box-shadow: 0 12px 28px -8px rgba(43, 60, 120, .68); color: #fff; }
.lw-btn--soft { background: var(--lw-primary-soft); color: var(--lw-primary); border-color: var(--lw-primary-border); }
.lw-btn--soft:hover { background: rgba(43, 60, 120, .16); color: var(--lw-primary); }
.lw-btn--accent { background: var(--lw-accent); color: #1d2b08; border-color: transparent; box-shadow: 0 8px 22px -8px rgba(231, 166, 21, .55); }
.lw-btn--accent:hover { background: #d6940f; color: #1d2b08; }
.lw-btn--ghost { background: transparent; border-color: transparent; color: var(--lw-text-2); }
.lw-btn--ghost:hover { background: var(--lw-bg); color: var(--lw-text); }
.lw-btn--light { background: rgba(255, 255, 255, .16); color: #fff; border-color: rgba(255, 255, 255, .30); backdrop-filter: blur(6px); }
.lw-btn--light:hover { background: rgba(255, 255, 255, .28); color: #fff; }
.lw-btn--outline { background: transparent; color: var(--lw-primary); border-color: var(--lw-primary-border); }
.lw-btn--outline:hover { background: var(--lw-primary-soft); color: var(--lw-primary); }
.lw-btn--danger { background: var(--lw-red); color: #fff; border-color: transparent; box-shadow: 0 8px 22px -8px rgba(220, 76, 76, .5); }
.lw-btn--danger:hover { background: #c93c3c; color: #fff; }
.lw-btn--success { background: var(--lw-green); color: #fff; border-color: transparent; box-shadow: 0 8px 22px -8px rgba(14, 159, 110, .5); }
.lw-btn--success:hover { background: #0c8a61; color: #fff; }
.lw-btn--amber { background: var(--lw-amber); color: #fff; border-color: transparent; }
.lw-btn--amber:hover { background: #b45309; color: #fff; }
.lw-btn--sm { padding: 7px 14px; font-size: 12px; border-radius: 10px; }
.lw-btn--sm i { font-size: 12px; }
.lw-btn--xs { padding: 5px 10px; font-size: 11.5px; border-radius: 9px; gap: 5px; }
.lw-btn--xs i { font-size: 11px; }
.lw-btn--block { width: 100%; }
.lw-btn--amber-soft { background: var(--lw-amber-soft); color: var(--lw-amber); border-color: var(--lw-amber-border); }
.lw-btn--amber-soft:hover { background: rgba(217, 119, 6, .18); color: var(--lw-amber); }
.lw-btn--danger-soft { background: var(--lw-red-soft); color: var(--lw-red); border-color: var(--lw-red-border); }
.lw-btn--danger-soft:hover { background: rgba(220, 76, 76, .16); color: var(--lw-red); }
.lw-btn--success-soft { background: var(--lw-green-soft); color: var(--lw-green); border-color: var(--lw-green-border); }
.lw-btn--success-soft:hover { background: rgba(14, 159, 110, .18); color: var(--lw-green); }
.lw-btn--accent-soft { background: var(--lw-accent-soft); color: var(--lw-accent); border-color: var(--lw-accent-border); }
.lw-btn--accent-soft:hover { background: rgba(231, 166, 21, .18); color: var(--lw-accent); }
.lw-btn-lock { pointer-events: none; opacity: .45; }

.lw-ripple { position: absolute; border-radius: 50%; background: rgba(255, 255, 255, .55); transform: scale(0); animation: lwRipple .55s ease-out forwards; pointer-events: none; }
.lw-btn--soft .lw-ripple, .lw-btn--outline .lw-ripple { background: rgba(43, 60, 120, .25); }
.lw-btn--accent .lw-ripple, .lw-btn--accent-soft .lw-ripple { background: rgba(255, 255, 255, .5); }
@keyframes lwRipple { to { transform: scale(3); opacity: 0; } }

/* ---------- Chip / Badge ---------- */
.lw-chip {
    display: inline-flex; align-items: center; gap: 6px;
    border-radius: 999px; padding: 5px 12px; font-size: 11.5px; font-weight: 600;
    background: var(--lw-bg); color: var(--lw-text-2); border: 1px solid var(--lw-border);
}
.lw-chip i { font-size: 12px; }
.lw-chip--navy { background: var(--lw-navy-soft); color: var(--lw-primary); border-color: var(--lw-navy-border); }
.lw-chip--green { background: var(--lw-green-soft); color: var(--lw-green); border-color: var(--lw-green-border); }
.lw-chip--amber { background: var(--lw-amber-soft); color: var(--lw-amber); border-color: var(--lw-amber-border); }
.lw-chip--red { background: var(--lw-red-soft); color: var(--lw-red); border-color: var(--lw-red-border); }
.lw-chip--accent { background: var(--lw-accent-soft); color: var(--lw-accent); border-color: var(--lw-accent-border); }
.lw-chip--gold { background: var(--lw-gold-soft); color: var(--lw-gold); border-color: var(--lw-gold-border); }
.lw-chip--violet { background: var(--lw-violet-soft); color: var(--lw-violet); border-color: var(--lw-violet-border); }
.lw-chip--slate { background: transparent; color: var(--lw-text-3); border-color: transparent; }
.lw-chip--glow { background: var(--lw-green); color: #fff; border-color: transparent; box-shadow: 0 0 0 4px var(--lw-green-soft), 0 6px 18px -6px rgba(14, 159, 110, .65); animation: lwGlow 2.2s ease-in-out infinite; }
@keyframes lwGlow { 0%,100% { box-shadow: 0 0 0 4px var(--lw-green-soft), 0 6px 18px -6px rgba(14, 159, 110, .65); } 50% { box-shadow: 0 0 0 8px var(--lw-green-soft), 0 6px 18px -6px rgba(14, 159, 110, .65); } }
.lw-chip--dot { padding-left: 9px; }
.lw-chip--dot::before { content: ""; width: 7px; height: 7px; border-radius: 50%; background: currentColor; }
html.dark-mode .lw-chip { background: rgba(255, 255, 255, .06); }
.lw-chip-mini { font-size: 10px; padding: 3px 9px; gap: 5px; }
.lw-badge-count { display: inline-flex; align-items: center; justify-content: center; min-width: 20px; height: 20px; padding: 0 6px;
    border-radius: 999px; font-size: 10.5px; font-weight: 700; background: var(--lw-primary-soft); color: var(--lw-primary); }
.lw-badge-count--gold { background: var(--lw-accent); color: #fff; }
.lw-badge-count--green { background: var(--lw-green); color: #fff; }
.lw-badge-count--red { background: var(--lw-red); color: #fff; }

/* ---------- Alerts ---------- */
.lw-alert { display: flex; align-items: center; gap: 12px; border-radius: 14px; padding: 13px 16px; font-size: 13px; font-weight: 600;
    margin-bottom: 18px; border: 1px solid var(--lw-border); background: var(--lw-card); box-shadow: var(--lw-shadow); }
.lw-alert i { font-size: 16px; flex-shrink: 0; }
.lw-alert b { font-weight: 700; }
.lw-alert span { font-weight: 500; opacity: .88; }
.lw-alert ul { font-weight: 500; opacity: .92; margin: 0 0 0 4px; padding-left: 18px; }
.lw-alert--warn { border-color: var(--lw-amber-border); background: var(--lw-amber-soft); color: var(--lw-amber); }
.lw-alert--err { border-color: var(--lw-red-border); background: var(--lw-red-soft); color: var(--lw-red); }
.lw-alert--ok { border-color: var(--lw-green-border); background: var(--lw-green-soft); color: var(--lw-green); }
.lw-alert--accent { border-color: var(--lw-accent-border); background: var(--lw-accent-soft); color: var(--lw-accent); }
.lw-alert-close { margin-left: auto; background: none; border: none; font-size: 15px; cursor: pointer; line-height: 1; padding: 0 2px; }
.lw-error-banner { display: flex; align-items: flex-start; gap: 10px; border-radius: 14px; padding: 14px 16px; margin-bottom: 18px;
    border: 1px solid var(--lw-red-border); background: var(--lw-red-soft); color: var(--lw-red); font-size: 12.5px; line-height: 1.6; }
.lw-error-banner i { font-size: 16px; margin-top: 1px; flex-shrink: 0; }
.lw-error-banner b { font-weight: 700; }

/* ---------- Hero ---------- */
.lw-hero {
    position: relative; overflow: hidden;
    background: var(--lw-grad-rad); color: #fff;
    border-radius: var(--lw-radius-lg); padding: 26px 28px; margin-bottom: 20px;
    box-shadow: 0 24px 48px -18px rgba(29, 43, 83, .55);
}
.lw-hero::before { content: ""; position: absolute; inset: 0; pointer-events: none;
    background-image: radial-gradient(rgba(255, 255, 255, .14) 1px, transparent 1px); background-size: 22px 22px; opacity: .4; }
.lw-hero::after { content: ""; position: absolute; right: -90px; top: -120px; width: 320px; height: 320px; border-radius: 50%;
    background: rgba(231, 166, 21, .14); filter: blur(6px); pointer-events: none; }
.lw-hero-grid { position: relative; display: flex; flex-wrap: wrap; gap: 20px; align-items: center; justify-content: space-between; }
.lw-hero-left { display: flex; gap: 16px; align-items: flex-start; min-width: 0; flex: 1 1 380px; }
.lw-hero-icon { flex-shrink: 0; width: 56px; height: 56px; border-radius: 17px; display: inline-flex; align-items: center; justify-content: center;
    background: rgba(255, 255, 255, .18); border: 1px solid rgba(255, 255, 255, .28); backdrop-filter: blur(10px); font-size: 24px; color: #fff; }
.lw-hero-title { font-size: 22px; font-weight: 700; letter-spacing: -.3px; margin: 0 0 4px; color: #fff; }
.lw-hero-sub { font-size: 12.5px; opacity: .88; margin: 0; line-height: 1.55; max-width: 560px; }
.lw-hero-badges { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 12px; }
.lw-hero-badge { display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; border-radius: 999px; font-size: 11.5px; font-weight: 600;
    background: rgba(255, 255, 255, .16); border: 1px solid rgba(255, 255, 255, .26); backdrop-filter: blur(8px); color: #fff; }
.lw-hero-badge i { font-size: 12px; opacity: .92; }
.lw-hero-badge--ok { background: rgba(14, 159, 110, .4); border-color: rgba(14, 159, 110, .6); }
.lw-hero-badge--warn { background: rgba(217, 119, 6, .42); border-color: rgba(217, 119, 6, .6); }
.lw-hero-badge--accent { background: rgba(231, 166, 21, .4); border-color: rgba(231, 166, 21, .6); }
.lw-hero-right { display: flex; flex-wrap: wrap; gap: 8px; align-items: center; }

/* ---------- Countdown ---------- */
.lw-countdown { position: relative; display: flex; align-items: center; gap: 10px; margin-top: 16px; }
.lw-cd-box { min-width: 58px; text-align: center; border-radius: 13px; padding: 9px 10px 7px;
    background: rgba(255, 255, 255, .14); border: 1px solid rgba(255, 255, 255, .26); backdrop-filter: blur(8px); }
.lw-cd-num { display: block; font-size: 22px; font-weight: 700; line-height: 1; color: #fff; font-variant-numeric: tabular-nums; }
.lw-cd-lbl { display: block; font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; color: rgba(255, 255, 255, .78); margin-top: 3px; }
.lw-cd-sep { font-size: 20px; font-weight: 700; color: rgba(255, 255, 255, .55); }
.lw-cd-label { display: inline-flex; align-items: center; gap: 6px; font-size: 11px; font-weight: 700; letter-spacing: .4px;
    text-transform: uppercase; background: rgba(255, 255, 255, .16); border: 1px solid rgba(255, 255, 255, .26); border-radius: 999px; padding: 5px 12px; }

/* ---------- KPI ---------- */
.lw-kpi-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 20px; }
.lw-kpi { position: relative; overflow: hidden; background: var(--lw-card); border: 1px solid var(--lw-border); border-radius: var(--lw-radius);
    padding: 18px 20px; box-shadow: var(--lw-shadow); display: flex; align-items: center; gap: 14px; transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease; }
.lw-kpi:hover { transform: translateY(-3px); box-shadow: var(--lw-shadow-lg); border-color: var(--lw-primary-border); }
.lw-kpi-icon { flex-shrink: 0; width: 46px; height: 46px; border-radius: 14px; display: inline-flex; align-items: center; justify-content: center; font-size: 19px; }
.lw-kpi-icon.navy { background: var(--lw-navy-soft); color: var(--lw-primary); }
.lw-kpi-icon.green { background: var(--lw-green-soft); color: var(--lw-green); }
.lw-kpi-icon.amber { background: var(--lw-amber-soft); color: var(--lw-amber); }
.lw-kpi-icon.accent { background: var(--lw-accent-soft); color: var(--lw-accent); }
.lw-kpi-icon.rose { background: var(--lw-rose-soft); color: var(--lw-rose); }
.lw-kpi-icon.sky { background: var(--lw-sky-soft); color: var(--lw-sky); }
.lw-kpi-icon.violet { background: var(--lw-violet-soft); color: var(--lw-violet); }
.lw-kpi-main { flex: 1; min-width: 0; }
.lw-kpi-num { font-size: 24px; font-weight: 700; letter-spacing: -.5px; line-height: 1.1; color: var(--lw-text); font-variant-numeric: tabular-nums; }
.lw-kpi-label { font-size: 11px; font-weight: 600; color: var(--lw-text-3); text-transform: uppercase; letter-spacing: .4px; margin-top: 2px; }
.lw-kpi-sub { font-size: 11px; color: var(--lw-text-3); margin-top: 2px; }
.lw-kpi-foot { margin-top: 8px; }
.lw-kpi-pct { display: inline-flex; align-items: center; gap: 4px; font-size: 10px; font-weight: 700; color: var(--lw-primary); }
.lw-kpi-pct i { font-size: 10px; }
.lw-kpi-watermark { position: absolute; right: -18px; bottom: -22px; font-size: 92px; opacity: .035; pointer-events: none; line-height: 1; }

/* distribution bar (KPI mini chart) */
.lw-dist { display: flex; height: 6px; width: 100%; border-radius: 999px; overflow: hidden; gap: 2px; background: var(--lw-bg); }
.lw-dist i { display: block; height: 100%; border-radius: 999px; transition: width .5s cubic-bezier(.22,1,.36,1); }
.lw-dist--empty { background: var(--lw-bg); }
.lw-dist--empty i { display: none; }

/* mini bar sparkline */
.lw-bar { display: flex; align-items: flex-end; gap: 3px; height: 30px; width: 100%; }
.lw-bar i { flex: 1; display: block; border-radius: 4px 4px 2px 2px; background: linear-gradient(180deg, #6b7fc4, #2b3c78); min-height: 2px; transition: height .5s cubic-bezier(.22,1,.36,1); }
.lw-bar i:nth-child(3n) { background: linear-gradient(180deg, #f2bc2e, #e7a615); }
.lw-bar i:nth-child(3n+2) { background: linear-gradient(180deg, #4dd6a5, #0e9f6e); }
.lw-bar--empty { background: var(--lw-bg); border-radius: 6px; }
.lw-bar--empty i { display: none; }

/* ---------- Toolbar ---------- */
.lw-toolbar { position: sticky; top: 78px; z-index: 940; display: flex; flex-wrap: wrap; align-items: flex-end; gap: 10px;
    background: var(--lw-card); border: 1px solid var(--lw-border); border-radius: var(--lw-radius);
    padding: 14px 16px; margin-bottom: 18px; box-shadow: var(--lw-shadow); backdrop-filter: blur(10px); }
.lw-toolbar::before { content: ""; position: absolute; top: 0; left: 16px; right: 16px; height: 1px;
    background: linear-gradient(90deg, transparent, rgba(43, 60, 120, .30), transparent); opacity: 0; transition: opacity .2s; }
.lw-toolbar.is-stuck::before { opacity: 1; }
.lw-filter { display: flex; flex-direction: column; gap: 5px; min-width: 140px; }
.lw-filter label { font-size: 10px; font-weight: 700; color: var(--lw-text-3); text-transform: uppercase; letter-spacing: .5px; }
.lw-select, .lw-control { height: 40px; border-radius: 11px; border: 1.5px solid var(--lw-border); background: var(--lw-card);
    color: var(--lw-text); font-size: 13px; font-family: inherit; padding: 0 12px; width: 100%; transition: border-color .2s, box-shadow .2s; }
.lw-select:focus, .lw-control:focus { outline: none; border-color: var(--lw-primary); box-shadow: 0 0 0 3px var(--lw-primary-soft); }
.lw-control.is-invalid, .lw-select.is-invalid { border-color: var(--lw-red); box-shadow: 0 0 0 3px var(--lw-red-soft); }
.lw-control[readonly] { background: var(--lw-bg); color: var(--lw-text-3); cursor: not-allowed; }
.lw-search { position: relative; min-width: 190px; flex: 1 1 190px; }
.lw-search i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--lw-text-3); font-size: 14px; pointer-events: none; }
.lw-search .lw-control { padding-left: 36px; }
.lw-toolbar-actions { display: flex; gap: 8px; align-items: center; margin-left: auto; }

/* ---------- Tabs (segmented) ---------- */
.lw-tabs { display: inline-flex; align-items: center; gap: 4px; background: var(--lw-bg); border: 1px solid var(--lw-border);
    border-radius: 12px; padding: 4px; flex-wrap: wrap; }
.lw-tab { display: inline-flex; align-items: center; gap: 6px; border: none; background: transparent; border-radius: 9px;
    padding: 7px 14px; font-size: 12.5px; font-weight: 600; color: var(--lw-text-2); font-family: inherit; transition: all .2s ease; cursor: pointer; }
.lw-tab i { font-size: 13px; }
.lw-tab:hover { color: var(--lw-text); }
.lw-tab.active { background: var(--lw-card); color: var(--lw-primary); box-shadow: 0 2px 10px -2px rgba(27, 36, 55, .18); }
.lw-tab.active .lw-badge-count { background: var(--lw-primary); color: #fff; }
.lw-tab.is-disabled { opacity: .5; pointer-events: none; }
.lw-tab-pill { border-radius: 999px; }

/* ---------- Card & Table ---------- */
.lw-card { background: var(--lw-card); border: 1px solid var(--lw-border); border-radius: var(--lw-radius); box-shadow: var(--lw-shadow); }
.lw-card--lift { transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease; }
.lw-card--lift:hover { transform: translateY(-4px); box-shadow: var(--lw-shadow-lg); border-color: var(--lw-primary-border); }
.lw-card-pad { padding: 22px; }
.lw-card-header { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; padding: 16px 20px; border-bottom: 1px solid var(--lw-border); }
.lw-section-title { display: flex; align-items: center; gap: 9px; font-size: 15px; font-weight: 700; color: var(--lw-text); margin: 0 0 4px; }
.lw-section-title i { color: var(--lw-primary); font-size: 17px; }
.lw-section-sub { font-size: 12px; color: var(--lw-text-3); margin-bottom: 16px; }
.lw-section-sub.mb-0 { margin-bottom: 0; }
.lw-table-card { overflow: hidden; }
.lw-mod .table-lw { margin: 0; --bs-table-bg: transparent; }
.lw-mod .table-lw > thead th { font-size: 10.5px; text-transform: uppercase; letter-spacing: .5px; color: var(--lw-text-3);
    background: var(--lw-bg); border-bottom: 1px solid var(--lw-border); padding: 12px 14px; white-space: nowrap; }
.lw-mod .table-lw > tbody td { padding: 12px 14px; font-size: 13px; color: var(--lw-text-2); border-color: var(--lw-border-soft); vertical-align: middle; }
.lw-mod .table-lw > tbody tr { transition: background .15s ease; }
.lw-mod .table-lw > tbody tr:hover td { background: var(--lw-bg); }
.lw-num { color: var(--lw-text-3); font-variant-numeric: tabular-nums; }
.lw-cell-icon { display: inline-flex; align-items: center; gap: 7px; color: var(--lw-text-2); font-size: 12.5px; white-space: nowrap; }
.lw-cell-icon i { color: var(--lw-primary); font-size: 11.5px; }
.lw-haf-name b { display: block; font-size: 13px; color: var(--lw-text); line-height: 1.35; }
.lw-haf-name .lw-chip { margin-top: 4px; }
.lw-actions { display: inline-flex; gap: 6px; }
.lw-row-aktif td { background: var(--lw-primary-soft) !important; }
.lw-row-aktif:hover td { background: var(--lw-primary-soft) !important; }
html.dark-mode .lw-row-aktif td { background: rgba(143, 161, 218, .16) !important; }
html.dark-mode .lw-row-aktif:hover td { background: rgba(143, 161, 218, .16) !important; }
.lw-pagi { display: flex; justify-content: space-between; align-items: center; gap: 10px; flex-wrap: wrap; padding: 14px 20px; border-top: 1px solid var(--lw-border); }
.lw-pagi-info { font-size: 11.5px; color: var(--lw-text-3); }
.lw-mod .pagination { margin: 0; }
.lw-mod .pagination .page-link { color: var(--lw-text-2); border-color: var(--lw-border); background: var(--lw-card); font-size: 12.5px; min-width: 32px; text-align: center; }
.lw-mod .pagination .page-link:hover { color: var(--lw-primary); border-color: var(--lw-primary-border); background: var(--lw-primary-soft); }
.lw-mod .pagination .active .page-link { background: var(--lw-grad); border-color: transparent; color: #fff; box-shadow: 0 4px 14px -4px rgba(43, 60, 120, .65); }
.lw-mod .pagination .disabled .page-link { opacity: .5; }

/* responsive table -> mobile cards */
.lw-mobile-card { display: none; }
@media (max-width: 767.98px) {
    .lw-table-desktop { display: none; }
    .lw-mobile-card { display: block; }
    .lw-mobile-card-list { display: flex; flex-direction: column; gap: 12px; padding: 14px; }
    .lw-mobile-card { background: var(--lw-card); border: 1px solid var(--lw-border); border-radius: var(--lw-radius); box-shadow: var(--lw-shadow); }
    .lw-mobile-card-head { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 12px 14px; border-bottom: 1px solid var(--lw-border-soft); }
    .lw-mobile-card-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px 12px; padding: 12px 14px; }
    .lw-mobile-card-field .k { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; color: var(--lw-text-3); }
    .lw-mobile-card-field .v { font-size: 12.5px; color: var(--lw-text); font-weight: 600; margin-top: 1px; }
    .lw-mobile-card-actions { display: flex; gap: 6px; padding: 10px 14px; border-top: 1px solid var(--lw-border-soft); flex-wrap: wrap; }
}

/* ---------- Timeline ---------- */
.lw-timeline { position: relative; }
.lw-tl-item { position: relative; display: flex; gap: 14px; padding-bottom: 18px; }
.lw-tl-item::before { content: ""; position: absolute; left: 15px; top: 34px; bottom: 0; width: 2px; background: var(--lw-border); }
.lw-tl-item:last-child::before { display: none; }
.lw-tl-dot { position: relative; z-index: 1; flex-shrink: 0; width: 32px; height: 32px; border-radius: 50%; background: var(--lw-card);
    border: 2px solid var(--lw-border); display: inline-flex; align-items: center; justify-content: center; color: var(--lw-text-3); font-size: 12px; }
.lw-tl-item.is-current .lw-tl-dot { background: var(--lw-grad); border-color: transparent; color: #fff; box-shadow: 0 0 0 5px var(--lw-primary-soft); }
.lw-tl-item.is-done .lw-tl-dot { background: var(--lw-green); border-color: transparent; color: #fff; }
.lw-tl-item.is-current::before, .lw-tl-item.is-done::before { background: var(--lw-primary-border); }
.lw-tl-card { flex: 1; min-width: 0; background: var(--lw-card); border: 1px solid var(--lw-border); border-radius: 13px; padding: 13px 16px;
    box-shadow: var(--lw-shadow); display: flex; flex-wrap: wrap; align-items: center; gap: 10px 14px; transition: all .2s ease; }
.lw-tl-item.is-current .lw-tl-card { border-color: var(--lw-primary-border); background: linear-gradient(135deg, var(--lw-primary-soft), rgba(231, 166, 21, .05)); }
.lw-tl-time { flex-shrink: 0; min-width: 88px; text-align: center; background: var(--lw-bg); border: 1px solid var(--lw-border); border-radius: 11px; padding: 7px 9px; }
.lw-tl-time b { display: block; font-size: 12px; color: var(--lw-text); }
.lw-tl-time span { font-size: 9.5px; color: var(--lw-text-3); font-weight: 600; }
.lw-tl-main { flex: 1; min-width: 0; }
.lw-tl-name { font-size: 13.5px; font-weight: 700; color: var(--lw-text); }
.lw-tl-desc { font-size: 11.5px; color: var(--lw-text-3); margin-top: 1px; }
.lw-tl-tag { flex-shrink: 0; }

/* ---------- Wizard ---------- */
.lw-stepper { display: flex; align-items: center; gap: 0; margin-bottom: 24px; }
.lw-step { display: flex; align-items: center; gap: 10px; flex: 1; }
.lw-step:last-child { flex: 0 0 auto; }
.lw-step-dot { width: 34px; height: 34px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;
    background: var(--lw-bg); border: 1.5px solid var(--lw-border); color: var(--lw-text-3); font-size: 13px; font-weight: 700; flex-shrink: 0; transition: all .25s ease; }
.lw-step.active .lw-step-dot { background: var(--lw-grad); border-color: transparent; color: #fff; box-shadow: 0 6px 18px -6px rgba(43, 60, 120, .65); }
.lw-step.done .lw-step-dot { background: var(--lw-green); border-color: transparent; color: #fff; }
.lw-step-txt { display: flex; flex-direction: column; }
.lw-step-txt b { font-size: 12px; color: var(--lw-text); }
.lw-step-txt span { font-size: 10.5px; color: var(--lw-text-3); }
.lw-step-line { flex: 1; height: 2px; background: var(--lw-border); margin: 0 14px; border-radius: 2px; position: relative; overflow: hidden; min-width: 24px; }
.lw-step-line::after { content: ""; position: absolute; inset: 0; background: var(--lw-grad); transform: scaleX(0); transform-origin: left; transition: transform .4s ease; }
.lw-step-line.done::after { transform: scaleX(1); }
.lw-wizard-pane { display: none; animation: lwFadeUp .32s ease both; }
.lw-wizard-pane.is-show { display: block; }
@keyframes lwFadeUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: none; } }
.lw-wizard-nav { display: flex; align-items: center; gap: 10px; margin-top: 26px; padding-top: 20px; border-top: 1px solid var(--lw-border); flex-wrap: wrap; }
.lw-wizard-nav .spacer { flex: 1; }
.lw-step-btn { cursor: pointer; }

/* ---------- Form ---------- */
.lw-form-card { max-width: 880px; }
.lw-mod .form-label { font-size: 12px; font-weight: 700; color: var(--lw-text-2); margin-bottom: 6px; }
.lw-form-section { display: flex; align-items: center; gap: 9px; font-size: 13px; font-weight: 700; color: var(--lw-text); margin-bottom: 16px; }
.lw-form-section i { color: var(--lw-primary); }
.lw-form-err { font-size: 12px; color: var(--lw-red); margin-top: 5px; display: flex; align-items: center; gap: 5px; }
.lw-status-note { display: flex; align-items: flex-start; gap: 10px; border-radius: 12px; padding: 12px 14px;
    border: 1px solid var(--lw-amber-border); background: var(--lw-amber-soft); color: var(--lw-amber); font-size: 12.5px; line-height: 1.6; }
.lw-status-note b { font-weight: 700; }
.lw-lock-note { font-size: 11.5px; color: var(--lw-text-3); margin-top: 5px; display: flex; align-items: center; gap: 6px; }
.lw-form-divider { display: flex; gap: 12px; margin-top: 28px; padding-top: 20px; border-top: 1px solid var(--lw-border); flex-wrap: wrap; }
.lw-field { display: flex; flex-direction: column; gap: 5px; }
.lw-field-label { font-size: 10px; font-weight: 700; color: var(--lw-text-3); text-transform: uppercase; letter-spacing: .5px; display: inline-flex; align-items: center; gap: 5px; }
.lw-field-label i { font-size: 11px; }
.lw-help-text { font-size: 11px; color: var(--lw-text-3); margin-top: 4px; }
.lw-inline-error { font-size: 11.5px; color: var(--lw-red); margin-top: 4px; display: inline-flex; align-items: center; gap: 4px; }
.lw-inline-error i { font-size: 11px; }

/* ---------- Preview card ---------- */
.lw-preview { display: flex; align-items: center; gap: 14px; border-radius: 14px; background: var(--lw-grad-soft);
    border: 1px solid var(--lw-primary-border); border-left: 4px solid var(--lw-primary); padding: 15px 17px; transition: border-color .2s, background .2s; }
.lw-preview.has-date { border-color: var(--lw-green-border); border-left-color: var(--lw-green); background: linear-gradient(135deg, var(--lw-green-soft), rgba(231, 166, 21, .05)); }
.lw-preview-icon { width: 44px; height: 44px; border-radius: 13px; background: var(--lw-card); color: var(--lw-primary);
    display: inline-flex; align-items: center; justify-content: center; font-size: 19px; flex-shrink: 0; border: 1px solid var(--lw-primary-border); }
.lw-preview.has-date .lw-preview-icon { color: var(--lw-green); border-color: var(--lw-green-border); }
.lw-preview-name { font-size: 14px; font-weight: 700; color: var(--lw-text); }
.lw-preview-meta { font-size: 11.5px; color: var(--lw-text-2); display: flex; flex-wrap: wrap; gap: 4px 12px; margin-top: 2px; }
.lw-preview-meta span { display: inline-flex; align-items: center; gap: 4px; }
.lw-preview-meta i { font-size: 11px; color: var(--lw-primary); }
.lw-preview.has-date .lw-preview-meta i { color: var(--lw-green); }

/* ---------- Warning card ---------- */
.lw-warn-card { display: flex; align-items: flex-start; gap: 12px; border-radius: 14px; padding: 14px 16px; margin-bottom: 18px;
    border: 1px solid var(--lw-accent-border); background: linear-gradient(135deg, var(--lw-accent-soft), rgba(219, 39, 119, .06)); color: var(--lw-accent); font-size: 12.5px; line-height: 1.6; }
.lw-warn-card i { font-size: 17px; margin-top: 1px; flex-shrink: 0; }
.lw-warn-card b { font-weight: 700; }

/* ---------- Detail ---------- */
.lw-detail-hero { position: relative; overflow: hidden; background: var(--lw-grad-rad); color: #fff; border-radius: var(--lw-radius-lg);
    padding: 26px 28px; margin-bottom: 22px; box-shadow: 0 24px 48px -18px rgba(29, 43, 83, .55); }
.lw-detail-hero::before { content: ""; position: absolute; inset: 0; background-image: radial-gradient(rgba(255, 255, 255, .14) 1px, transparent 1px); background-size: 22px 22px; opacity: .4; pointer-events: none; }
.lw-detail-hero-grid { position: relative; display: flex; flex-wrap: wrap; gap: 18px; align-items: center; justify-content: space-between; }
.lw-detail-avatar { width: 62px; height: 62px; border-radius: 18px; background: rgba(255, 255, 255, .18); border: 1px solid rgba(255, 255, 255, .32);
    display: inline-flex; align-items: center; justify-content: center; font-size: 26px; color: #fff; flex-shrink: 0; backdrop-filter: blur(8px); }
.lw-detail-title { font-size: 22px; font-weight: 700; letter-spacing: -.3px; margin: 0; color: #fff; }
.lw-detail-sub { font-size: 12.5px; opacity: .88; margin-top: 3px; }
.lw-detail-meta { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 12px; }

.lw-info-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 10px; }
.lw-info-cell { background: var(--lw-bg); border: 1px solid var(--lw-border); border-radius: 13px; padding: 12px 14px; }
.lw-info-cell .lbl { font-size: 10px; font-weight: 700; color: var(--lw-text-3); text-transform: uppercase; letter-spacing: .5px; margin-bottom: 4px; display: flex; align-items: center; gap: 5px; }
.lw-info-cell .lbl i { font-size: 11px; }
.lw-info-cell .val { font-size: 13px; font-weight: 600; color: var(--lw-text); }

.lw-stat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 20px; }
.lw-stat { display: flex; align-items: center; gap: 12px; padding: 16px 18px; }
.lw-stat-icon { width: 42px; height: 42px; border-radius: 13px; display: inline-flex; align-items: center; justify-content: center; font-size: 17px; }
.lw-stat-icon.navy { background: var(--lw-navy-soft); color: var(--lw-primary); }
.lw-stat-icon.green { background: var(--lw-green-soft); color: var(--lw-green); }
.lw-stat-icon.accent { background: var(--lw-accent-soft); color: var(--lw-accent); }
.lw-stat-icon.amber { background: var(--lw-amber-soft); color: var(--lw-amber); }
.lw-stat-icon.violet { background: var(--lw-violet-soft); color: var(--lw-violet); }
.lw-stat-icon.sky { background: var(--lw-sky-soft); color: var(--lw-sky); }
.lw-stat-num { font-size: 20px; font-weight: 700; line-height: 1.1; color: var(--lw-text); }
.lw-stat-label { font-size: 11px; font-weight: 600; color: var(--lw-text-3); text-transform: uppercase; letter-spacing: .4px; }

/* ---------- Checklist kesiapan ---------- */
.lw-checklist { display: flex; flex-direction: column; gap: 8px; }
.lw-check-item { display: flex; align-items: center; gap: 12px; border-radius: 13px; padding: 12px 14px;
    background: var(--lw-bg); border: 1px solid var(--lw-border); transition: border-color .2s, transform .2s; }
.lw-check-item:hover { border-color: var(--lw-primary-border); transform: translateX(3px); }
.lw-check-item.is-ok { background: var(--lw-green-soft); border-color: var(--lw-green-border); }
.lw-check-ic { width: 36px; height: 36px; border-radius: 11px; background: var(--lw-card); color: var(--lw-text-3);
    display: inline-flex; align-items: center; justify-content: center; font-size: 15px; flex-shrink: 0; border: 1px solid var(--lw-border); }
.lw-check-item.is-ok .lw-check-ic { color: var(--lw-green); border-color: var(--lw-green-border); }
.lw-check-txt { flex: 1; min-width: 0; }
.lw-check-txt b { display: block; font-size: 12.5px; color: var(--lw-text); }
.lw-check-txt span { font-size: 11px; color: var(--lw-text-3); }
.lw-check-val { flex-shrink: 0; }

/* ---------- Quick nav ---------- */
.lw-quicknav-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; }
.lw-qn-card { --qn: var(--lw-primary); --qn-soft: var(--lw-primary-soft); --qn-border: var(--lw-primary-border);
    display: flex; align-items: center; gap: 12px; padding: 15px 16px; border-radius: 14px; background: var(--lw-card);
    border: 1px solid var(--lw-border); box-shadow: var(--lw-shadow); transition: all .2s ease; position: relative; overflow: hidden; }
.lw-qn-card:hover { transform: translateY(-3px); box-shadow: var(--lw-shadow-lg); border-color: var(--qn-border); }
.lw-qn-card::after { content: ""; position: absolute; right: -30px; top: -30px; width: 90px; height: 90px; border-radius: 50%; background: var(--qn-soft); opacity: 0; transition: opacity .2s; }
.lw-qn-card:hover::after { opacity: 1; }
.lw-qn-ic { flex-shrink: 0; width: 42px; height: 42px; border-radius: 13px; background: var(--qn-soft); color: var(--qn);
    display: inline-flex; align-items: center; justify-content: center; font-size: 17px; border: 1px solid var(--qn-border); }
.lw-qn-body { min-width: 0; position: relative; z-index: 1; }
.lw-qn-name { display: block; font-size: 12.5px; font-weight: 700; color: var(--lw-text); }
.lw-qn-sub { display: block; font-size: 10.5px; color: var(--lw-text-3); margin-top: 1px; }
.lw-qn-arrow { margin-left: auto; color: var(--lw-text-3); font-size: 13px; transition: transform .2s ease, color .2s ease; position: relative; z-index: 1; }
.lw-qn-card:hover .lw-qn-arrow { transform: translateX(3px); color: var(--qn); }
.lw-qn-card.lw-qn--navy { --qn: var(--lw-primary); --qn-soft: var(--lw-primary-soft); --qn-border: var(--lw-primary-border); }
.lw-qn-card.lw-qn--accent { --qn: var(--lw-accent); --qn-soft: var(--lw-accent-soft); --qn-border: var(--lw-accent-border); }
.lw-qn-card.lw-qn--green { --qn: var(--lw-green); --qn-soft: var(--lw-green-soft); --qn-border: var(--lw-green-border); }
.lw-qn-card.lw-qn--amber { --qn: var(--lw-amber); --qn-soft: var(--lw-amber-soft); --qn-border: var(--lw-amber-border); }
.lw-qn-card.lw-qn--rose { --qn: var(--lw-rose); --qn-soft: var(--lw-rose-soft); --qn-border: var(--lw-rose-border); }
.lw-qn-card.lw-qn--sky { --qn: var(--lw-sky); --qn-soft: var(--lw-sky-soft); --qn-border: var(--lw-sky-border); }
.lw-qn-card.lw-qn--violet { --qn: var(--lw-violet); --qn-soft: var(--lw-violet-soft); --qn-border: var(--lw-violet-border); }

/* ---------- Active banner ---------- */
.lw-active { position: relative; overflow: hidden; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px;
    padding: 18px 22px; border-radius: var(--lw-radius); margin-bottom: 20px;
    background: linear-gradient(120deg, var(--lw-green-soft), rgba(231, 166, 21, .05));
    border: 1px solid var(--lw-green-border); box-shadow: var(--lw-shadow); }
.lw-active::before { content: ""; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; background: var(--lw-green); }
.lw-active-left { display: flex; align-items: center; gap: 14px; z-index: 1; min-width: 0; }
.lw-active-icon { width: 46px; height: 46px; border-radius: 14px; background: var(--lw-green-soft); color: var(--lw-green);
    display: inline-flex; align-items: center; justify-content: center; font-size: 19px; flex-shrink: 0; }
.lw-active .lbl { font-size: 10px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: var(--lw-green); display: flex; align-items: center; gap: 6px; }
.lw-active .name { font-size: 15px; font-weight: 700; color: var(--lw-text); line-height: 1.3; }
.lw-active .dates { font-size: 11.5px; color: var(--lw-text-2); margin-top: 2px; }
.lw-active .dates i { color: var(--lw-green); margin-right: 4px; }
.lw-active-right { display: flex; align-items: center; gap: 8px; z-index: 1; }

/* ---------- Locked banner ---------- */
.lw-lock-banner { display: flex; align-items: flex-start; gap: 12px; border-radius: 14px; padding: 14px 16px; margin-bottom: 18px;
    border: 1px dashed var(--lw-red-border); background: var(--lw-red-soft); color: var(--lw-red); font-size: 12.5px; line-height: 1.6; }
.lw-lock-banner i { font-size: 17px; margin-top: 1px; flex-shrink: 0; }
.lw-lock-banner b { font-weight: 700; }

/* ---------- Progress ---------- */
.lw-progress { height: 8px; border-radius: 999px; background: var(--lw-bg); border: 1px solid var(--lw-border); overflow: hidden; }
.lw-progress-fill { height: 100%; border-radius: 999px; background: var(--lw-grad); transition: width .6s cubic-bezier(.22,1,.36,1); position: relative; }
.lw-progress-fill::after { content: ""; position: absolute; inset: 0; background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .4), transparent);
    background-size: 200% 100%; animation: lwShine 1.6s linear infinite; }
@keyframes lwShine { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }

/* ---------- Empty state ---------- */
.lw-empty { text-align: center; padding: 52px 24px; }
.lw-empty-illus { position: relative; width: 124px; height: 124px; margin: 0 auto 18px; }
.lw-empty-illus .ring { position: absolute; inset: 0; border-radius: 50%; border: 2px dashed var(--lw-primary-border); animation: lwSpin 14s linear infinite; }
.lw-empty-illus .ring-2 { position: absolute; inset: 10px; border-radius: 50%; border: 2px dashed var(--lw-accent-border); animation: lwSpin 22s linear infinite reverse; }
.lw-empty-illus .core { position: absolute; inset: 22px; border-radius: 50%; background: var(--lw-grad-soft); display: inline-flex; align-items: center; justify-content: center; font-size: 42px; color: var(--lw-primary); box-shadow: inset 0 0 0 1px var(--lw-primary-border); }
@keyframes lwSpin { to { transform: rotate(360deg); } }
.lw-empty-title { font-size: 16px; font-weight: 700; color: var(--lw-text); margin-bottom: 6px; }
.lw-empty-sub { font-size: 12.5px; color: var(--lw-text-3); max-width: 400px; margin: 0 auto 18px; line-height: 1.6; }

/* ---------- Skeleton ---------- */
.lw-skeleton { position: relative; overflow: hidden; background: var(--lw-bg); border-radius: 10px; }
.lw-skeleton::after { content: ""; position: absolute; inset: 0; background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .55), transparent);
    background-size: 200% 100%; animation: lwShimmer 1.4s ease infinite; }
html.dark-mode .lw-skeleton::after { background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .08), transparent); }
@keyframes lwShimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }

/* ---------- Toast ---------- */
.lw-toast-wrap { position: fixed; top: 84px; right: 20px; z-index: 1090; display: flex; flex-direction: column; gap: 10px; pointer-events: none; }
.lw-toast { pointer-events: auto; display: flex; align-items: flex-start; gap: 11px; min-width: 300px; max-width: 380px;
    background: var(--lw-card); border: 1px solid var(--lw-border); border-radius: 14px; padding: 13px 15px; box-shadow: var(--lw-shadow-lg);
    animation: lwToastIn .35s cubic-bezier(.22,1,.36,1) both; border-left: 4px solid var(--lw-primary); }
.lw-toast.ok { border-left-color: var(--lw-green); }
.lw-toast.err { border-left-color: var(--lw-red); }
.lw-toast i { font-size: 16px; margin-top: 1px; color: var(--lw-primary); }
.lw-toast.ok i { color: var(--lw-green); }
.lw-toast.err i { color: var(--lw-red); }
.lw-toast b { display: block; font-size: 13px; color: var(--lw-text); }
.lw-toast span { font-size: 11.5px; color: var(--lw-text-2); line-height: 1.5; }
.lw-toast .lw-toast-close { margin-left: auto; background: none; border: none; color: var(--lw-text-3); font-size: 14px; cursor: pointer; padding: 0 2px; }
@keyframes lwToastIn { from { opacity: 0; transform: translateX(24px); } to { opacity: 1; transform: none; } }
.lw-toast.is-out { animation: lwToastOut .3s ease both; }
@keyframes lwToastOut { to { opacity: 0; transform: translateX(24px); } }

/* ---------- FAB ---------- */
.lw-fab { position: fixed; right: 22px; bottom: 22px; z-index: 1040; width: 56px; height: 56px; border-radius: 17px; border: none;
    background: var(--lw-grad); color: #fff; font-size: 22px; display: inline-flex; align-items: center; justify-content: center;
    box-shadow: 0 14px 32px -8px rgba(43, 60, 120, .62); transition: transform .2s ease, box-shadow .2s ease; }
.lw-fab:hover { transform: translateY(-3px) scale(1.04); box-shadow: 0 20px 44px -8px rgba(43, 60, 120, .72); }
.lw-fab i { pointer-events: none; }

/* ---------- Confirm modal ---------- */
.lw-confirm-overlay { position: fixed; inset: 0; z-index: 1080; background: rgba(15, 15, 20, .5); backdrop-filter: blur(4px);
    display: flex; align-items: center; justify-content: center; padding: 18px; animation: lwFadeIn .18s ease both; }
.lw-confirm-box { width: 100%; max-width: 400px; background: var(--lw-card); border: 1px solid var(--lw-border); border-radius: 18px;
    box-shadow: var(--lw-shadow-lg); padding: 24px; animation: lwZoomIn .22s cubic-bezier(.22,1,.36,1) both; }
.lw-confirm-icon { width: 58px; height: 58px; border-radius: 16px; background: var(--lw-red-soft); color: var(--lw-red);
    display: inline-flex; align-items: center; justify-content: center; font-size: 24px; margin-bottom: 14px; }
.lw-confirm-title { font-size: 16px; font-weight: 700; color: var(--lw-text); margin: 0 0 6px; }
.lw-confirm-msg { font-size: 12.5px; color: var(--lw-text-2); line-height: 1.6; margin-bottom: 20px; }
.lw-confirm-actions { display: flex; gap: 10px; justify-content: flex-end; flex-wrap: wrap; }
@keyframes lwFadeIn { from { opacity: 0; } to { opacity: 1; } }
@keyframes lwZoomIn { from { opacity: 0; transform: scale(.94) translateY(8px); } to { opacity: 1; transform: none; } }

/* ---------- Back link ---------- */
.lw-back { display: inline-flex; align-items: center; gap: 7px; font-size: 12.5px; font-weight: 600; color: var(--lw-text-2); padding: 7px 13px;
    border-radius: 10px; border: 1px solid var(--lw-border); background: var(--lw-card); transition: all .2s ease; }
.lw-back:hover { color: var(--lw-primary); border-color: var(--lw-primary-border); transform: translateX(-2px); }

/* ---------- Pulse / dot ---------- */
.lw-pulse { animation: lwPulse 2s infinite; }
@keyframes lwPulse { 0%,100% { box-shadow: 0 0 0 0 rgba(14, 159, 110, .5); } 50% { box-shadow: 0 0 0 8px rgba(14, 159, 110, 0); } }
.lw-dot { width: 7px; height: 7px; border-radius: 50%; background: currentColor; flex-shrink: 0; animation: lwDot 1.6s ease-in-out infinite; }
@keyframes lwDot { 0%,100% { opacity: 1; transform: scale(1); } 50% { opacity: .4; transform: scale(.7); } }

/* ---------- Avatar / person ---------- */
.lw-avatar { flex-shrink: 0; width: 42px; height: 42px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;
    font-size: 13px; font-weight: 700; color: #fff; background: var(--lw-grad); border: 2px solid var(--lw-card); box-shadow: 0 0 0 1px var(--lw-border); }
.lw-avatar--sm { width: 32px; height: 32px; font-size: 11px; border-width: 1.5px; }
.lw-avatar--lg { width: 56px; height: 56px; font-size: 17px; }
.lw-avatar-stack { display: inline-flex; align-items: center; }
.lw-avatar-stack .lw-avatar { margin-left: -9px; }
.lw-avatar-stack .lw-avatar:first-child { margin-left: 0; }
.lw-ava-more { width: 32px; height: 32px; border-radius: 50%; background: var(--lw-primary-soft); color: var(--lw-primary); border: 2px solid var(--lw-card);
    display: inline-flex; align-items: center; justify-content: center; font-size: 10.5px; font-weight: 700; margin-left: -9px; }

/* ---------- Team / member card ---------- */
.lw-team-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 16px; }
.lw-team-card { display: flex; flex-direction: column; gap: 12px; padding: 18px; border-radius: var(--lw-radius); background: var(--lw-card);
    border: 1px solid var(--lw-border); box-shadow: var(--lw-shadow); transition: all .2s ease; position: relative; overflow: hidden; }
.lw-team-card:hover { transform: translateY(-3px); box-shadow: var(--lw-shadow-lg); border-color: var(--lw-primary-border); }
.lw-team-top { display: flex; align-items: center; gap: 12px; }
.lw-team-name { font-size: 14px; font-weight: 700; color: var(--lw-text); line-height: 1.3; }
.lw-team-code { font-size: 11px; color: var(--lw-primary); font-weight: 600; }
.lw-team-meta { display: flex; flex-wrap: wrap; gap: 6px 12px; font-size: 11.5px; color: var(--lw-text-2); }
.lw-team-meta span { display: inline-flex; align-items: center; gap: 4px; }
.lw-team-meta i { color: var(--lw-primary); font-size: 11px; }
.lw-team-stats { display: flex; gap: 10px; flex-wrap: wrap; }
.lw-team-stat { flex: 1; min-width: 84px; text-align: center; background: var(--lw-bg); border: 1px solid var(--lw-border); border-radius: 11px; padding: 8px 6px; }
.lw-team-stat b { display: block; font-size: 15px; color: var(--lw-text); font-variant-numeric: tabular-nums; }
.lw-team-stat span { font-size: 9.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; color: var(--lw-text-3); }
.lw-team-members { display: flex; flex-wrap: wrap; gap: 6px; }
.lw-member { display: inline-flex; align-items: center; gap: 7px; background: var(--lw-bg); border: 1px solid var(--lw-border); border-radius: 999px; padding: 4px 10px 4px 4px; font-size: 11.5px; color: var(--lw-text-2); }
.lw-member-ava { width: 24px; height: 24px; border-radius: 50%; font-size: 9.5px; border: none; }
.lw-team-actions { display: flex; gap: 6px; margin-top: auto; padding-top: 4px; }
.lw-member-list { display: flex; flex-direction: column; gap: 8px; }
.lw-member-row { display: flex; align-items: center; gap: 12px; background: var(--lw-bg); border: 1px solid var(--lw-border); border-radius: 12px; padding: 10px 12px; transition: border-color .2s, transform .2s; }
.lw-member-row:hover { border-color: var(--lw-primary-border); transform: translateX(3px); }
.lw-member-name { font-size: 13px; font-weight: 600; color: var(--lw-text); }
.lw-member-sub { font-size: 11px; color: var(--lw-text-3); }

/* ---------- Judge card ---------- */
.lw-judge-card { display: flex; align-items: flex-start; gap: 12px; padding: 16px; border-radius: var(--lw-radius); background: var(--lw-card);
    border: 1px solid var(--lw-border); box-shadow: var(--lw-shadow); transition: all .2s ease; }
.lw-judge-card:hover { border-color: var(--lw-primary-border); transform: translateY(-2px); box-shadow: var(--lw-shadow-lg); }
.lw-judge-main { flex: 1; min-width: 0; }
.lw-judge-name { font-size: 13.5px; font-weight: 700; color: var(--lw-text); }
.lw-judge-sub { font-size: 11.5px; color: var(--lw-text-3); margin-top: 1px; }
.lw-judge-side { display: flex; flex-direction: column; align-items: flex-end; gap: 4px; }
.lw-judge-total { font-size: 15px; font-weight: 700; color: var(--lw-primary); font-variant-numeric: tabular-nums; }

/* ---------- Podium ---------- */
.lw-podium { display: flex; align-items: flex-end; justify-content: center; gap: 10px; padding: 22px 10px 6px; }
.lw-podium-place { flex: 1; max-width: 210px; display: flex; flex-direction: column; align-items: center; text-align: center; }
.lw-podium-ava { width: 62px; height: 62px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;
    font-size: 18px; font-weight: 700; color: #fff; margin-bottom: 8px; position: relative; }
.lw-podium-place--1 .lw-podium-ava { width: 78px; height: 78px; font-size: 22px; box-shadow: 0 0 0 4px var(--lw-accent-soft); }
.lw-podium-place--2 .lw-podium-ava { box-shadow: 0 0 0 4px rgba(148, 163, 184, .35); }
.lw-podium-place--3 .lw-podium-ava { box-shadow: 0 0 0 4px rgba(217, 119, 6, .25); }
.lw-podium-medal { position: absolute; top: -8px; right: -8px; width: 26px; height: 26px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 13px; color: #fff; }
.lw-medal--gold { background: linear-gradient(135deg, #f2bc2e, #d6940f); }
.lw-medal--silver { background: linear-gradient(135deg, #cbd5e1, #94a3b8); }
.lw-medal--bronze { background: linear-gradient(135deg, #e6a05a, #b06e2f); }
.lw-podium-name { font-size: 12.5px; font-weight: 700; color: var(--lw-text); line-height: 1.3; max-width: 150px; }
.lw-podium-sub { font-size: 10.5px; color: var(--lw-text-3); margin-top: 1px; }
.lw-podium-block { width: 100%; border-radius: 12px 12px 4px 4px; color: #fff; display: inline-flex; align-items: center; justify-content: center;
    font-size: 14px; font-weight: 800; margin-top: 10px; font-variant-numeric: tabular-nums; }
.lw-podium-place--1 .lw-podium-block { height: 96px; background: linear-gradient(180deg, #f2bc2e, #d6940f); box-shadow: 0 10px 24px -8px rgba(231, 166, 21, .6); }
.lw-podium-place--2 .lw-podium-block { height: 74px; background: linear-gradient(180deg, #e2e8f0, #94a3b8); box-shadow: 0 8px 20px -8px rgba(100, 116, 139, .5); }
.lw-podium-place--3 .lw-podium-block { height: 58px; background: linear-gradient(180deg, #f2c29a, #b06e2f); box-shadow: 0 8px 20px -8px rgba(176, 110, 47, .5); }

/* ---------- Readiness / health ---------- */
.lw-readiness { display: flex; align-items: center; gap: 10px; }
.lw-readiness .lw-progress { flex: 1; }
.lw-health-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 10px; }
.lw-health { background: var(--lw-bg); border: 1px solid var(--lw-border); border-radius: 13px; padding: 12px 14px; display: flex; flex-direction: column; gap: 6px; }
.lw-health-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: var(--lw-text-3); display: inline-flex; align-items: center; gap: 5px; }
.lw-health-label i { font-size: 11px; }
.lw-health-num { font-size: 17px; font-weight: 700; color: var(--lw-text); }
.lw-health-progress { height: 5px; border-radius: 999px; background: var(--lw-border); overflow: hidden; }
.lw-health-progress i { display: block; height: 100%; border-radius: 999px; background: var(--lw-grad); }

/* ---------- Split / two-col ---------- */
.lw-split { display: grid; grid-template-columns: 340px 1fr; gap: 18px; align-items: start; }
.lw-split-item { min-width: 0; }
.lw-two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; align-items: start; }

/* ---------- Pick cards (selectable options) ---------- */
.lw-pick-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(210px, 1fr)); gap: 12px; }
.lw-pick-card { display: flex; align-items: center; gap: 12px; padding: 14px; border-radius: 13px; background: var(--lw-card);
    border: 1.5px solid var(--lw-border); transition: all .2s ease; position: relative; }
.lw-pick-card:hover { border-color: var(--lw-primary-border); transform: translateY(-2px); box-shadow: var(--lw-shadow); }
.lw-pick-card.is-picked { border-color: var(--lw-primary); background: var(--lw-primary-soft); box-shadow: 0 0 0 3px var(--lw-primary-soft); }
.lw-pick-icon { width: 40px; height: 40px; border-radius: 12px; background: var(--lw-primary-soft); color: var(--lw-primary);
    display: inline-flex; align-items: center; justify-content: center; font-size: 17px; flex-shrink: 0; }
.lw-pick-title { font-size: 13px; font-weight: 700; color: var(--lw-text); }
.lw-pick-sub { font-size: 11px; color: var(--lw-text-3); margin-top: 1px; }
.lw-pick-check { margin-left: auto; width: 22px; height: 22px; border-radius: 50%; border: 1.5px solid var(--lw-border); display: inline-flex;
    align-items: center; justify-content: center; color: #fff; font-size: 12px; flex-shrink: 0; transition: all .2s ease; }
.lw-pick-card.is-picked .lw-pick-check { background: var(--lw-primary); border-color: var(--lw-primary); }

/* ---------- Selection bar ---------- */
.lw-selbar { position: sticky; bottom: 12px; z-index: 930; display: flex; flex-wrap: wrap; align-items: center; gap: 10px;
    background: var(--lw-card); border: 1px solid var(--lw-primary-border); border-radius: 14px; padding: 12px 16px;
    box-shadow: var(--lw-shadow-lg); margin-top: 18px; }
.lw-sel-progress { flex: 1; min-width: 160px; }
.lw-sel-info { font-size: 12.5px; font-weight: 600; color: var(--lw-text); }

/* ---------- Searchable select ---------- */
.lw-searchable { position: relative; }
.lw-searchable .lw-search-input { width: 100%; height: 40px; border-radius: 11px; border: 1.5px solid var(--lw-border); background: var(--lw-card);
    color: var(--lw-text); font-size: 13px; font-family: inherit; padding: 0 34px 0 12px; cursor: pointer; transition: border-color .2s, box-shadow .2s; }
.lw-searchable .lw-search-input:focus { outline: none; border-color: var(--lw-primary); box-shadow: 0 0 0 3px var(--lw-primary-soft); }
.lw-searchable .lw-search-input[readonly] { background: var(--lw-bg); color: var(--lw-text-3); }
.lw-searchable .lw-caret { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: var(--lw-text-3); font-size: 12px; pointer-events: none; }
.lw-search-drop { position: absolute; top: calc(100% + 6px); left: 0; right: 0; z-index: 960; background: var(--lw-card);
    border: 1px solid var(--lw-border); border-radius: 12px; box-shadow: var(--lw-shadow-lg); padding: 6px; max-height: 280px; overflow: auto; display: none; }
.lw-search-drop.is-open { display: block; animation: lwFadeUp .18s ease both; }
.lw-search-drop .lw-sd-search { position: relative; margin-bottom: 6px; }
.lw-search-drop .lw-sd-search i { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: var(--lw-text-3); font-size: 12px; }
.lw-search-drop .lw-sd-search input { width: 100%; height: 36px; border-radius: 9px; border: 1px solid var(--lw-border); background: var(--lw-bg);
    padding: 0 10px 0 30px; font-size: 12.5px; font-family: inherit; color: var(--lw-text); }
.lw-search-drop .lw-sd-search input:focus { outline: none; border-color: var(--lw-primary); }
.lw-sd-opt { display: block; width: 100%; text-align: left; border: none; background: transparent; border-radius: 9px; padding: 9px 11px;
    font-size: 12.5px; color: var(--lw-text-2); font-family: inherit; cursor: pointer; transition: background .15s; }
.lw-sd-opt:hover, .lw-sd-opt.is-active { background: var(--lw-primary-soft); color: var(--lw-primary); }
.lw-sd-empty { padding: 14px; text-align: center; font-size: 12px; color: var(--lw-text-3); }

/* ---------- Slider ---------- */
.lw-slider-wrap { display: flex; align-items: center; gap: 12px; }
.lw-slider { flex: 1; -webkit-appearance: none; appearance: none; height: 6px; border-radius: 999px; background: var(--lw-border); outline: none; }
.lw-slider::-webkit-slider-thumb { -webkit-appearance: none; width: 20px; height: 20px; border-radius: 50%; background: var(--lw-primary);
    border: 3px solid #fff; box-shadow: 0 2px 8px rgba(27, 36, 55, .3); cursor: pointer; }
.lw-slider::-moz-range-thumb { width: 20px; height: 20px; border-radius: 50%; background: var(--lw-primary); border: 3px solid #fff; box-shadow: 0 2px 8px rgba(27, 36, 55, .3); cursor: pointer; }
.lw-slider-value { flex-shrink: 0; min-width: 58px; text-align: center; font-size: 13px; font-weight: 700; color: var(--lw-primary);
    background: var(--lw-primary-soft); border-radius: 10px; padding: 6px 8px; font-variant-numeric: tabular-nums; }

/* ---------- Chart / breakdown ---------- */
.lw-chart { display: flex; flex-direction: column; gap: 10px; }
.lw-chart-col { display: flex; align-items: center; gap: 10px; }
.lw-chart-name { flex: 0 0 150px; font-size: 12px; color: var(--lw-text-2); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.lw-chart-bar-wrap { flex: 1; height: 22px; background: var(--lw-bg); border: 1px solid var(--lw-border); border-radius: 7px; overflow: hidden; }
.lw-chart-bar { height: 100%; background: var(--lw-grad); border-radius: 7px; transition: width .6s cubic-bezier(.22,1,.36,1); min-width: 2px; }
.lw-chart-score { flex: 0 0 52px; text-align: right; font-size: 12px; font-weight: 700; color: var(--lw-text); font-variant-numeric: tabular-nums; }
.lw-breakdown { display: flex; flex-direction: column; gap: 8px; }
.lw-breakdown-item { display: flex; align-items: center; gap: 12px; background: var(--lw-bg); border: 1px solid var(--lw-border); border-radius: 12px; padding: 10px 12px; }
.lw-breakdown-name { flex: 1; min-width: 0; font-size: 12.5px; font-weight: 600; color: var(--lw-text); }
.lw-breakdown-val { font-size: 12px; font-weight: 700; color: var(--lw-primary); font-variant-numeric: tabular-nums; }

/* ---------- Score input ---------- */
.lw-score-card { background: var(--lw-card); border: 1px solid var(--lw-border); border-radius: var(--lw-radius); box-shadow: var(--lw-shadow); }
.lw-score-head { display: flex; align-items: center; gap: 10px; padding: 14px 16px; border-bottom: 1px solid var(--lw-border-soft); }
.lw-score-input { width: 100px; text-align: center; font-size: 15px; font-weight: 700; color: var(--lw-primary);
    border: 1.5px solid var(--lw-border); border-radius: 11px; padding: 8px 6px; background: var(--lw-card); font-family: inherit; }
.lw-score-input:focus { outline: none; border-color: var(--lw-primary); box-shadow: 0 0 0 3px var(--lw-primary-soft); }

/* ---------- Sticky bottom actions ---------- */
.lw-sticky-actions { position: sticky; bottom: 14px; z-index: 950; display: flex; flex-wrap: wrap; align-items: center; gap: 10px;
    background: var(--lw-card); border: 1px solid var(--lw-border); border-radius: 14px; padding: 12px 16px;
    box-shadow: 0 18px 40px -12px rgba(27, 36, 55, .25); backdrop-filter: blur(8px); }
.lw-sticky-actions .spacer { flex: 1; }

/* ---------- Summary ---------- */
.lw-summary { display: flex; flex-wrap: wrap; gap: 10px; }
.lw-summary-cell { flex: 1; min-width: 130px; background: var(--lw-bg); border: 1px solid var(--lw-border); border-radius: 12px; padding: 12px 14px; }
.lw-summary-cell .k { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; color: var(--lw-text-3); }
.lw-summary-cell .v { font-size: 16px; font-weight: 700; color: var(--lw-text); margin-top: 2px; }

/* ---------- Breadcrumb ---------- */
.lw-breadcrumb { display: flex; flex-wrap: wrap; align-items: center; gap: 6px; font-size: 11.5px; color: var(--lw-text-3); margin-bottom: 12px; }
.lw-breadcrumb a { color: var(--lw-text-2); }
.lw-breadcrumb a:hover { color: var(--lw-primary); }
.lw-breadcrumb i { font-size: 10px; }

/* ---------- Accordion ---------- */
.lw-acc { display: flex; flex-direction: column; gap: 10px; }
.lw-acc-item { border: 1px solid var(--lw-border); border-radius: 12px; overflow: hidden; background: var(--lw-card); }
.lw-acc-head { width: 100%; display: flex; align-items: center; gap: 10px; padding: 13px 15px; border: none; background: transparent;
    font-size: 13px; font-weight: 600; color: var(--lw-text); font-family: inherit; cursor: pointer; transition: background .15s; }
.lw-acc-head:hover { background: var(--lw-bg); }
.lw-acc-head .lw-caret { margin-left: auto; transition: transform .2s; }
.lw-acc-item.is-open .lw-acc-head .lw-caret { transform: rotate(180deg); }
.lw-acc-body { display: none; padding: 0 15px 15px; }
.lw-acc-item.is-open .lw-acc-body { display: block; animation: lwFadeUp .25s ease both; }

/* ---------- Notes ---------- */
.lw-note { display: flex; align-items: flex-start; gap: 10px; border-radius: 12px; padding: 12px 14px; font-size: 12px; line-height: 1.6;
    border: 1px solid var(--lw-border); background: var(--lw-bg); color: var(--lw-text-2); }
.lw-note i { font-size: 15px; margin-top: 1px; color: var(--lw-primary); flex-shrink: 0; }
.lw-hint { font-size: 11px; color: var(--lw-text-3); }

/* ---------- Responsive ---------- */
@media (max-width: 1199.98px) {
    .lw-kpi-grid { grid-template-columns: repeat(2, 1fr); }
    .lw-stat-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 991.98px) {
    .lw-split { grid-template-columns: 1fr; }
    .lw-two-col { grid-template-columns: 1fr; }
}
@media (max-width: 767.98px) {
    .lw-hero { padding: 20px 18px; }
    .lw-hero-title { font-size: 18px; }
    .lw-kpi-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
    .lw-kpi { padding: 14px 14px; gap: 10px; }
    .lw-kpi-icon { width: 40px; height: 40px; font-size: 16px; }
    .lw-kpi-num { font-size: 19px; }
    .lw-toolbar { top: 66px; }
    .lw-toast-wrap { right: 12px; left: 12px; min-width: 0; }
    .lw-toast { min-width: 0; width: 100%; }
    .lw-quicknav-grid { grid-template-columns: 1fr 1fr; }
    .lw-cd-box { min-width: 50px; }
    .lw-cd-num { font-size: 18px; }
    .lw-team-grid { grid-template-columns: 1fr; }
    .lw-selbar { flex-direction: column; align-items: stretch; }
}
@media (max-width: 575.98px) {
    .lw-kpi-grid { grid-template-columns: 1fr 1fr; }
    .lw-hero-right { width: 100%; }
    .lw-hero-right .lw-btn { flex: 1; }
    .lw-detail-hero { padding: 20px 18px; }
    .lw-quicknav-grid { grid-template-columns: 1fr; }
    .lw-stepper .lw-step-txt span { display: none; }
    .lw-cd-sep { display: none; }
    .lw-cd-box { min-width: 44px; }
    .lw-countdown { gap: 6px; }
}
@media (prefers-reduced-motion: reduce) {
    .lw-mod *, .lw-mod *::before, .lw-mod *::after {
        animation-duration: .01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: .01ms !important;
    }
}
</style>

<script>
window.LW = window.LW || {};

window.LW.ripple = function (evt) {
    var el = evt.currentTarget;
    var rect = el.getBoundingClientRect();
    var r = Math.max(rect.width, rect.height) / 2;
    var d = document.createElement('span');
    d.className = 'lw-ripple';
    d.style.width = d.style.height = (r * 2) + 'px';
    d.style.left = (evt.clientX - rect.left - r) + 'px';
    d.style.top = (evt.clientY - rect.top - r) + 'px';
    el.appendChild(d);
    setTimeout(function () { d.remove(); }, 600);
};

window.LW.toast = function (type, title, msg) {
    var wrap = document.querySelector('.lw-toast-wrap');
    if (!wrap) { wrap = document.createElement('div'); wrap.className = 'lw-toast-wrap'; document.body.appendChild(wrap); }
    var icon = type === 'ok' ? 'bi-check-circle-fill' : (type === 'err' ? 'bi-exclamation-octagon-fill' : 'bi-info-circle-fill');
    var t = document.createElement('div');
    t.className = 'lw-toast ' + (type || '');
    t.setAttribute('role', 'status');
    t.innerHTML = '<i class="bi ' + icon + '"></i><div><b>' + title + '</b><span>' + (msg || '') + '</span></div><button type="button" class="lw-toast-close" aria-label="Tutup">&times;</button>';
    wrap.appendChild(t);
    var close = function () { t.classList.add('is-out'); setTimeout(function () { t.remove(); }, 320); };
    t.querySelector('.lw-toast-close').addEventListener('click', close);
    setTimeout(close, 4600);
};

window.LW.counter = function (el, dur) {
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

window.LW.countdown = function (el) {
    var target = new Date(el.getAttribute('data-target')).getTime();
    var label = el.getAttribute('data-label') || 'Berlangsung';
    var doneTxt = el.getAttribute('data-done') || 'Berlangsung';
    if (isNaN(target)) { return; }
    var dBox = el.querySelector('.lw-cd-d');
    var hBox = el.querySelector('.lw-cd-h');
    var mBox = el.querySelector('.lw-cd-m');
    var sBox = el.querySelector('.lw-cd-s');
    var pad = function (n) { return n < 10 ? '0' + n : String(n); };
    var tick = function () {
        var diff = target - Date.now();
        if (diff <= 0) {
            if (dBox) { dBox.textContent = '00'; }
            if (hBox) { hBox.textContent = '00'; }
            if (mBox) { mBox.textContent = '00'; }
            if (sBox) { sBox.textContent = '00'; }
            el.classList.add('is-done');
            var lbl = el.querySelector('.lw-cd-label-txt');
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
            var lbl = el.querySelector('.lw-cd-label-txt');
            if (lbl) { lbl.textContent = label; }
            el.setAttribute('data-set-label', '1');
        }
        setTimeout(tick, 1000);
    };
    tick();
};

window.LW.confirm = function (title, msg, iconClass) {
    return new Promise(function (resolve) {
        var overlay = document.createElement('div');
        overlay.className = 'lw-confirm-overlay';
        overlay.setAttribute('role', 'dialog');
        overlay.setAttribute('aria-modal', 'true');
        overlay.innerHTML =
            '<div class="lw-confirm-box">' +
                '<div class="lw-confirm-icon"><i class="bi ' + (iconClass || 'bi-trash') + '"></i></div>' +
                '<h4 class="lw-confirm-title"></h4>' +
                '<p class="lw-confirm-msg"></p>' +
                '<div class="lw-confirm-actions">' +
                    '<button type="button" class="lw-btn" data-lw-no><i class="bi bi-x"></i> Batal</button>' +
                    '<button type="button" class="lw-btn lw-btn--danger" data-lw-yes><i class="bi bi-check"></i> Ya, Lanjutkan</button>' +
                '</div>' +
            '</div>';
        overlay.querySelector('.lw-confirm-title').textContent = title || 'Konfirmasi';
        overlay.querySelector('.lw-confirm-msg').textContent = msg || 'Yakin ingin melanjutkan?';
        document.body.appendChild(overlay);
        var done = function (val) {
            overlay.classList.add('is-out');
            setTimeout(function () { overlay.remove(); }, 160);
            resolve(val);
        };
        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) { done(false); }
        });
        overlay.querySelector('[data-lw-no]').addEventListener('click', function () { done(false); });
        overlay.querySelector('[data-lw-yes]').addEventListener('click', function () { done(true); });
    });
};

window.LW.confirmForm = function (form, title, msg, iconClass) {
    return new Promise(function (resolve) {
        window.LW.confirm(title, msg, iconClass).then(function (ok) {
            if (ok) {
                var btn = form.querySelector('[type=submit]');
                if (btn) { btn.disabled = true; }
                form.submit();
            }
            resolve(ok);
        });
    });
};

window.LW.searchable = function (root) {
    var input = root.querySelector('.lw-search-input');
    var drop = root.querySelector('.lw-search-drop');
    var real = root.querySelector('select');
    if (!input || !drop || !real) { return; }
    var search = drop.querySelector('.lw-sd-search input');
    var opts = [].slice.call(drop.querySelectorAll('.lw-sd-opt'));
    var label = function () {
        var sel = real.options[real.selectedIndex];
        return sel && sel.text ? sel.text : (input.getAttribute('data-placeholder') || '');
    };
    input.value = label();
    input.addEventListener('click', function (e) {
        e.stopPropagation();
        drop.classList.toggle('is-open');
        if (drop.classList.contains('is-open')) {
            if (search) { search.value = ''; search.focus(); }
            filter('');
        }
    });
    if (search) {
        search.addEventListener('input', function () { filter(search.value); });
    }
    opts.forEach(function (opt) {
        opt.addEventListener('click', function () {
            real.value = opt.getAttribute('data-value');
            input.value = opt.getAttribute('data-label') || opt.textContent;
            drop.classList.remove('is-open');
            root.dispatchEvent(new CustomEvent('lwchange', { detail: { value: real.value } }));
        });
    });
    real.addEventListener('change', function () { input.value = label(); });
    document.addEventListener('click', function (e) {
        if (!root.contains(e.target)) { drop.classList.remove('is-open'); }
    });
    var filter = function (q) {
        q = q.toLowerCase();
        opts.forEach(function (o) {
            o.style.display = (o.textContent.toLowerCase().indexOf(q) !== -1) ? '' : 'none';
        });
    };
};

window.LW.tabs = function () {
    [].slice.call(document.querySelectorAll('[data-lw-tabs]')).forEach(function (wrap) {
        var btns = [].slice.call(wrap.querySelectorAll('[data-lw-tab-target]'));
        btns.forEach(function (btn) {
            if (btn.getAttribute('data-lw-bound') === '1') { return; }
            btn.setAttribute('data-lw-bound', '1');
            btn.addEventListener('click', function () {
                var group = btn.getAttribute('data-lw-tabs');
                var target = btn.getAttribute('data-lw-tab-target');
                wrap.querySelectorAll('[data-lw-tab-target]').forEach(function (b) {
                    b.classList.toggle('active', b === btn);
                });
                [].slice.call(document.querySelectorAll('[data-lw-tab-pane="' + group + '"]')).forEach(function (pane) {
                    pane.classList.toggle('is-show', pane.id === target);
                });
            });
        });
    });
};

window.LW.accordion = function () {
    [].slice.call(document.querySelectorAll('.lw-acc-item .lw-acc-head')).forEach(function (head) {
        if (head.getAttribute('data-lw-bound') === '1') { return; }
        head.setAttribute('data-lw-bound', '1');
        head.addEventListener('click', function () {
            head.parentElement.classList.toggle('is-open');
        });
    });
};

window.LW.tooltips = function () {
    if (!window.bootstrap || !bootstrap.Tooltip) { return; }
    var nodes = [].slice.call(document.querySelectorAll('.lw-mod [data-bs-toggle="tooltip"]'));
    nodes.forEach(function (el) {
        if (!el.getAttribute('data-bs-original-title')) {
            new bootstrap.Tooltip(el, { trigger: 'hover focus', delay: { show: 120, hide: 60 } });
        }
    });
};

window.LW.init = function () {
    [].slice.call(document.querySelectorAll('.lw-mod [data-count]')).forEach(function (el) {
        window.LW.counter(el);
    });
    [].slice.call(document.querySelectorAll('.lw-countdown[data-target]')).forEach(function (el) {
        window.LW.countdown(el);
    });
    [].slice.call(document.querySelectorAll('.lw-searchable')).forEach(function (el) {
        window.LW.searchable(el);
    });
    window.LW.tabs();
    window.LW.accordion();
    window.LW.tooltips();
};

(function () {
    function ready(fn) {
        if (document.readyState !== 'loading') { fn(); }
        else { document.addEventListener('DOMContentLoaded', fn); }
    }
    ready(function () {
        if (window.LW) { window.LW.init(); }
    });
})();
</script>
