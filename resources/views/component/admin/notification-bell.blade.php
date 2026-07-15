@php
    $unreadCount = auth()->user()?->unreadNotifications->count() ?? 0;
    $recentNotifications = auth()->user()?->notifications()->limit(8)->get() ?? collect();
@endphp

<div class="nav-item dropdown px-2">
    <a class="nav-link dropdown-toggle c-header-icon position-relative" href="#"
       role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
       style="border-right:0; width:auto; padding:0 8px;" title="Notifikasi">
        <i class="fas fa-bell" style="font-size:18px;color:#555;"></i>
        @if ($unreadCount > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:9px;min-width:18px;height:18px;line-height:18px;">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                <span class="visually-hidden">notifikasi belum dibaca</span>
            </span>
        @endif
    </a>

    <div class="dropdown-menu dropdown-menu-end shadow" style="border-radius:14px;border:1px solid #e8ecf0;width:360px;max-width:90vw;padding:0;">

        <div class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom" style="border-radius:14px 14px 0 0;background:#f8fafc;">
            <span class="fw-bold" style="font-size:14px;color:var(--ms-text);">
                <i class="fas fa-bell me-1" style="color:var(--ms-primary);"></i> Notifikasi
            </span>
            @if ($unreadCount > 0)
                <form method="POST" action="{{ route('notifications.read-all') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-link text-decoration-none" style="font-size:11px;padding:0;">
                        Tandai semua dibaca
                    </button>
                </form>
            @endif
        </div>

        <div style="max-height:360px;overflow-y:auto;">
            @forelse ($recentNotifications as $n)
                @php $data = is_string($n->data) ? json_decode($n->data, true) : $n->data; @endphp
                <div class="px-3 py-2 border-bottom" style="{{ is_null($n->read_at) ? 'background:rgba(59,130,246,.06);' : '' }}">
                    <div class="d-flex align-items-start gap-2">
                        <i class="fas {{ $data['icon'] ?? 'fa-bell' }} mt-1" style="color:var(--ms-primary);font-size:13px;"></i>
                        <div class="flex-grow-1" style="min-width:0;">
                            <div class="fw-semibold" style="font-size:13px;color:var(--ms-text);">
                                {{ $data['title'] ?? 'Notifikasi' }}
                            </div>
                            @if (!empty($data['data']))
                                <div class="text-muted" style="font-size:11px;">
                                    @if (!empty($data['data']['browser']))
                                        {{ $data['data']['browser'] }}
                                        @if (!empty($data['data']['ip'])) · IP {{ $data['data']['ip'] }}@endif
                                    @endif
                                </div>
                            @endif
                            <div class="text-muted" style="font-size:10px;">{{ $n->created_at?->diffForHumans() }}</div>
                        </div>
                        @if (is_null($n->read_at))
                            <form method="POST" action="{{ route('notifications.read', $n->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-link text-decoration-none p-0" title="Tandai dibaca" style="font-size:11px;">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-4" style="font-size:13px;">
                    <i class="fas fa-bell-slash fa-lg mb-2 d-block" style="opacity:.3;"></i>
                    Tidak ada notifikasi.
                </div>
            @endforelse
        </div>
    </div>
</div>