<div class="card shadow h-100">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-bell mr-1"></i> Alertas de Estoque
        </h6>
        @if($unreadCount > 0)
        <span class="badge badge-danger">{{ $unreadCount }}</span>
        @endif
    </div>
    <div class="card-body">
        @if($unreadCount == 0)
            <div class="text-center py-2">
                <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                <p class="text-muted mb-0">Nenhum alerta pendente</p>
            </div>
        @else
            <ul class="list-group list-group-flush">
                @foreach($urgentNotifications as $notification)
                <li class="list-group-item px-0 py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">{{ $notification->medicine->name }}</h6>
                            <small class="text-muted">{{ $notification->message }}</small>
                        </div>
                        <button wire:click="markAsRead({{ $notification->id }})" 
                                class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-check"></i>
                        </button>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted">
                            <i class="fas fa-boxes mr-1"></i>
                            {{ $notification->medicine->stock }} un. (mín: {{ $notification->medicine->minimum_stock }})
                        </small>
                    </div>
                </li>
                @endforeach
            </ul>
            <div class="mt-3 text-center">
                <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-primary">
                    Ver todas <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        @endif
    </div>
</div>