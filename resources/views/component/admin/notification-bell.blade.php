@php
    $unreadCount = auth()->user()?->unreadNotifications->count() ?? 0;
    $recentNotifications = auth()->user()?->notifications()->limit(8)->get() ?? collect();
@endphp

<div class="nav-item dropdown px-2 nb-notif-wrap">
    <a class="nav-link dropdown-toggle nb-iconbtn nb-ripple nb-notif-trigger position-relative{{ $unreadCount > 0 ? ' has-news' : '' }}"
       href="#"
       role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
       title="Notifikasi">
        <i class="fas fa-bell" aria-hidden="true"></i>
        @if ($unreadCount > 0)
            <span class="nb-badge" aria-hidden="true">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
            <span class="visually-hidden">{{ $unreadCount }} notifikasi belum dibaca</span>
        @endif
    </a>

    <div class="dropdown-menu dropdown-menu-end nb-menu nb-notif-panel" aria-label="Pusat notifikasi">

        <div class="nb-notif-head">
            <span class="tt">
                <i class="fas fa-bell" aria-hidden="true"></i> Notifikasi
                @if ($unreadCount > 0)
                    <span class="cnt">{{ $unreadCount }}</span>
                @endif
            </span>
            @if ($unreadCount > 0)
                <span class="all">
                    <form method="POST" action="{{ route('notifications.read-all') }}" class="d-inline">
                        @csrf
                        <button type="submit">Tandai semua dibaca</button>
                    </form>
                </span>
            @endif
        </div>

        <div class="nb-notif-list">
            @forelse ($recentNotifications as $n)
                @php
                    $data = is_string($n->data) ? json_decode($n->data, true) : $n->data;
                    $icon = $data['icon'] ?? 'fa-bell';
                    $variant = match ($icon) {
                        'fa-mobile-screen' => 'is-sky',
                        'fa-location-dot'  => 'is-violet',
                        'fa-shield-halved' => 'is-green',
                        'fa-key'           => 'is-amber',
                        'fa-lock'          => 'is-red',
                        default            => '',
                    };
                @endphp
                <div class="nb-notif-item{{ is_null($n->read_at) ? ' is-unread' : '' }}">
                    <span class="nb-notif-ic {{ $variant }}"><i class="fas {{ $icon }}" aria-hidden="true"></i></span>
                    <div class="nb-notif-body">
                        <div class="nb-notif-title">{{ $data['title'] ?? 'Notifikasi' }}</div>
                        @if (!empty($data['data']))
                            @if (!empty($data['data']['browser']) || !empty($data['data']['ip']))
                                <div class="nb-notif-meta">
                                    @if (!empty($data['data']['browser']))
                                        {{ $data['data']['browser'] }}
                                        @if (!empty($data['data']['ip'])) · IP {{ $data['data']['ip'] }}@endif
                                    @endif
                                </div>
                            @endif
                        @endif
                        <div class="nb-notif-time">
                            <i class="far fa-clock" aria-hidden="true"></i>{{ $n->created_at?->diffForHumans() }}
                        </div>
                    </div>
                    @if (is_null($n->read_at))
                        <form method="POST" action="{{ route('notifications.read', $n->id) }}">
                            @csrf
                            <button type="submit" class="nb-notif-read" title="Tandai dibaca" aria-label="Tandai dibaca">
                                <i class="fas fa-check" aria-hidden="true"></i>
                            </button>
                        </form>
                    @endif
                </div>
            @empty
                <div class="nb-notif-empty">
                    <i class="fas fa-bell-slash" aria-hidden="true"></i>
                    <b>Tidak ada notifikasi</b>
                    <span>Semua aman — aktivitas baru akan muncul di sini.</span>
                </div>
            @endforelse
        </div>
    </div>
</div>
