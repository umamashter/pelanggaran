<style>
    .cover-page {
        width: 100%; height: 100%; position: relative;
        border: 6px solid #065f46; border-radius: 12px;
        padding: 30px 50px;
        overflow: hidden;
    }

    .corner-deco {
        position: absolute; width: 80px; height: 80px;
        border: 3px solid #065f46; opacity: 0.3;
    }
    .corner-deco.tl { top: 12px; left: 12px; border-right: none; border-bottom: none; border-radius: 8px 0 0 0; }
    .corner-deco.tr { top: 12px; right: 12px; border-left: none; border-bottom: none; border-radius: 0 8px 0 0; }
    .corner-deco.bl { bottom: 12px; left: 12px; border-right: none; border-top: none; border-radius: 0 0 0 8px; }
    .corner-deco.br { bottom: 12px; right: 12px; border-left: none; border-top: none; border-radius: 0 0 8px 0; }

    .dot-pattern-left, .dot-pattern-right {
        position: absolute; top: 0; bottom: 0; width: 60px;
        background-image: radial-gradient(circle, #065f46 1px, transparent 1px);
        background-size: 10px 10px; opacity: 0.06;
    }
    .dot-pattern-left { left: 20px; }
    .dot-pattern-right { right: 20px; }

    .accent-line-top {
        position: absolute; top: 20px; left: 50%; transform: translateX(-50%);
        width: 60%; height: 2px;
        background: linear-gradient(90deg, transparent, #065f46, transparent);
    }
    .accent-line-bottom {
        position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%);
        width: 60%; height: 2px;
        background: linear-gradient(90deg, transparent, #065f46, transparent);
    }

    .leaf-left, .leaf-right {
        position: absolute; top: 50%; transform: translateY(-50%);
        width: 40px; height: 120px; border-radius: 50%; opacity: 0.05;
        background: #065f46;
    }
    .leaf-left { left: 25px; }
    .leaf-right { right: 25px; }

    .gold-accent {
        position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
        width: 280px; height: 280px; border-radius: 50%;
        border: 1px solid #d4a843; opacity: 0.15;
    }

    .cover-content { position: relative; z-index: 10; width: 100%; height: 100%; }
    .cover-content table { width: 100%; height: 100%; border-collapse: collapse; border: none; }
    .cover-content td { border: none; text-align: center; vertical-align: middle; padding: 0 40px; }

    .foundation-name {
        font-size: 16px; font-weight: 800; color: #065f46;
        letter-spacing: 2px; text-transform: uppercase; margin-bottom: 3px;
    }
    .school-name-top {
        font-size: 13px; font-weight: 700; color: #065f46;
        letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 2px;
    }
    .school-address-top {
        font-size: 9px; color: #64748b; margin-bottom: 4px;
    }
    .school-id {
        font-size: 9px; color: #475569; font-weight: 600;
        letter-spacing: 0.5px;
    }
    .school-id span { margin: 0 8px; color: #065f46; }
    .sekretariat {
        font-size: 8px; color: #64748b; margin-top: 4px; line-height: 1.5;
    }

    .divider {
        width: 200px; height: 2px; margin: 12px auto;
        background: linear-gradient(90deg, transparent, #d4a843, transparent);
    }

    .logo-section { margin: 12px 0; }
    .logo-section img { width: 90px; height: 90px; object-fit: contain; }

    .main-title {
        font-size: 32px; font-weight: 900; color: #065f46;
        letter-spacing: 3px; text-transform: uppercase;
        line-height: 1.2; margin-bottom: 4px;
    }
    .sub-title {
        font-size: 15px; font-weight: 700; color: #065f46;
        letter-spacing: 1.5px; text-transform: uppercase;
        margin-bottom: 2px;
    }
    .sub-title-address {
        font-size: 10px; color: #64748b;
    }

    .year-badge {
        display: inline-block;
        background: linear-gradient(135deg, #065f46, #047857);
        color: #fff; font-size: 13px; font-weight: 700;
        padding: 8px 32px; border-radius: 30px;
        letter-spacing: 2px; text-transform: uppercase;
        margin-top: 8px;
    }

    .class-info {
        margin-top: 6px; font-size: 11px; font-weight: 600;
        color: #065f46;
    }

    .bottom-info {
        font-size: 8px; color: #94a3b8; margin-top: 10px;
    }
</style>

<div class="cover-page">
    <div class="corner-deco tl"></div>
    <div class="corner-deco tr"></div>
    <div class="corner-deco bl"></div>
    <div class="corner-deco br"></div>
    <div class="dot-pattern-left"></div>
    <div class="dot-pattern-right"></div>
    <div class="accent-line-top"></div>
    <div class="accent-line-bottom"></div>
    <div class="leaf-left"></div>
    <div class="leaf-right"></div>
    <div class="gold-accent"></div>

    <div class="cover-content">
        <table>
            <tr>
                <td>
                    <div class="foundation-name">Yayasan Pendidikan Nurul Ulum</div>
                    <div class="school-name-top">Raudhatul Athfal (RA) Nurul Ulum</div>
                    <div class="school-address-top">Patapan Guluk-Guluk, Sumenep, Madura</div>
                    <div class="school-id">
                        NSM : 101235290218 <span>|</span> NPSN : 69749413
                    </div>
                    <div class="sekretariat">
                        Sekretariat : Jl. Datuk Idris, Patapan, Guluk-Guluk, Sumenep, Madura 69463<br>
                        | 08175249622
                    </div>

                    <div class="divider"></div>

                    <div class="logo-section">
                        <img src="{{ public_path('img/logo2.png') }}" alt="Logo MI Nurul Ulum">
                    </div>

                    <div class="main-title">Buku Absensi Siswa</div>
                    <div class="sub-title">Madrasah Ibtidaiyah (MI) Nurul Ulum</div>
                    <div class="sub-title-address">Patapan Guluk-Guluk, Sumenep</div>

                    <div class="divider"></div>

                    <div class="year-badge">Tahun Pelajaran {{ $tahunAjaran->tahun_ajaran }}</div>

                    @if(isset($kelas) && $kelas)
                    <div class="class-info">Kelas : {{ strtoupper($kelas->nama_kelas) }}</div>
                    @endif

                    @if(isset($bulanLabel) && $bulanLabel)
                    <div class="class-info">Bulan : {{ strtoupper($bulanLabel) }}</div>
                    @endif

                    <div class="bottom-info">Dokumen ini digunakan sebagai buku absensi harian siswa</div>
                </td>
            </tr>
        </table>
    </div>
</div>
