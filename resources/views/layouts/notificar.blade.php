<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="navbarNotifications" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Notificações
        @if ($unreadCount = auth()->user()->unreadNotifications()->count())
            <span class="badge badge-danger">{{ $unreadCount }}</span>
        @endif
    </a>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarNotifications">
        @forelse (auth()->user()->notifications()->latest()->take(5)->get() as $notification)
            <a class="dropdown-item {{ $notification->read ? '' : 'font-weight-bold' }}" href="{{ route('notifications.index') }}">
                {{ Str::limit($notification->message, 50) }}
            </a>
        @empty
            <span class="dropdown-item">Nenhuma notificação</span>
        @endforelse
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="{{ route('notifications.index') }}">Ver todas</a>
    </div>
</li>