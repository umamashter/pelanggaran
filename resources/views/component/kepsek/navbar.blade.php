<header class="l-header">
    <div class="l-header__inner clearfix">
        <div class="c-header-icon js-hamburger" style="border-left: 0; border-right: 1px solid #ccc;">
            <div class="hamburger-toggle">
                <span class="bar-top"></span>
                <span class="bar-mid"></span>
                <span class="bar-bot"></span>
            </div>
        </div>

        <div class="c-header-icon lol logo" style="border-left: 0; border-right: 1px solid rgba(255,255,255,.1);">
            <img src="../img/logo2.png" width="32" height="32" style="object-fit:contain;">
        </div>

        <div class="c-title" style="padding:0;">
            @if(isset($tahunAktifGlobal))
            <div style="display:flex;gap:8px;align-items:center;">
                <span style="font-size:11px;font-weight:600;padding:3px 10px;border-radius:20px;background:linear-gradient(135deg,#16a34a,#22c55e);color:#fff;display:inline-flex;align-items:center;gap:4px;"><i class="fas fa-calendar-alt" style="font-size:10px;"></i> {{ $tahunAktifGlobal->tahun_ajaran }}</span>
                @if(isset($tahunAktifGlobal->semesterAktif))
                <span style="font-size:11px;font-weight:600;padding:3px 10px;border-radius:20px;background:linear-gradient(135deg,#2563eb,#3b82f6);color:#fff;display:inline-flex;align-items:center;gap:4px;"><i class="fas fa-book-open" style="font-size:10px;"></i> {{ $tahunAktifGlobal->semesterAktif->nama ?? '' }}</span>
                @endif
            </div>
            @endif
        </div>

        <div class="ms-auto navbar-nav d-flex align-items-center">
            <span style="font-size:11px;font-weight:600;padding:3px 10px;border-radius:20px;background:linear-gradient(135deg,#7c3aed,#a78bfa);color:#fff;display:inline-flex;align-items:center;gap:4px;"><i class="fas fa-user-shield" style="font-size:10px;"></i> Kepala {{ Auth::user()->kepalaMadrasah?->jenjang?->kode ?? '' }}</span>

            <!-- Theme Toggle -->
            <a class="theme-toggle" href="#" title="Ganti tema">
                <i class="fas fa-moon"></i>
            </a>

            <div class="nav-item dropdown px-3">
                <a id="navbarDropdown" class="name-tag nav-link dropdown-toggle c-header-icon userDropdown me-2"
                    href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                    v-pre>
                    <div class="text-small d-inline-flex ms-1">{{ strtok(auth()->user()->name, ' ') }}</div>
                </a>

                <div class="dropdown-menu dropdown-menu-end me-2" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item py-2" href="{{ route('profil-saya.index') }}">
                        {{ __('Profil Saya') }}
                    </a>
                    <a class="dropdown-item py-2" href="/">
                        {{ __('Kembali') }}
                    </a>
                    <a class="dropdown-item py-2" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
