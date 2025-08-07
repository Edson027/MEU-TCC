// resources/views/notifications/index.blade.php
@extends('layouts.admin')

@section('title', 'Notificações')

@push('styles')
<style>
    .notification-item {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }
    .notification-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    }
    .notification-unread {
        border-left-color: #4e73df;
        background-color: rgba(78, 115, 223, 0.05);
    }
    .notification-critical {
        border-left-color: #e74a3b;
    }
    .notification-warning {
        border-left-color: #f6c23e;
    }
    .notification-info {
        border-left-color: #36b9cc;
    }
    .notification-icon {
        font-size: 1.5rem;
        opacity: 0.8;
    }
    .notification-progress {
        height: 4px;
        border-radius: 2px;
    }
    .notification-badge {
        font-size: 0.7rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Cabeçalho -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-bell"></i> Notificações
        </h1>
        <div>
            <span class="badge badge-primary mr-2">
                Total: {{ $notifications->total() }}
            </span>
            <form action="{{ route('notifications.markAllRead') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-check-circle fa-sm text-white-50"></i> Marcar todas como lidas
                </button>
            </form>
        </div>
    </div>

    <!-- Legenda -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body py-2">
                    <div class="d-flex flex-wrap align-items-center">
                        <div class="legend-item"><span class="legend-color bg-primary"></span> Não lida</div>
                        <div class="legend-item"><span class="legend-color bg-danger"></span> Crítica (Estoque zerado)</div>
                        <div class="legend-item"><span class="legend-color bg-warning"></span> Alerta (Estoque baixo)</div>
                        <div class="legend-item"><span class="legend-color bg-info"></span> Informativa</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Notificações -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($notifications as $notification)
                            @php
                                $isCritical = isset($notification->data['current_stock']) && $notification->data['current_stock'] <= 0;
                                $isWarning = isset($notification->data['current_stock']) && 
                                            isset($notification->data['minimum_stock']) && 
                                            $notification->data['current_stock'] < $notification->data['minimum_stock'];
                            @endphp
                            
                            <a href="{{ $notification->data['url'] ?? '#' }}" 
                               class="list-group-item list-group-item-action notification-item 
                                      {{ $notification->read() ? '' : 'notification-unread' }}
                                      {{ $isCritical ? 'notification-critical' : ($isWarning ? 'notification-warning' : 'notification-info') }}">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="mb-0">
                                                <i class="fas 
                                                    {{ $isCritical ? 'fa-exclamation-triangle text-danger' : 
                                                       ($isWarning ? 'fa-exclamation-circle text-warning' : 'fa-info-circle text-info') }} 
                                                    notification-icon mr-2"></i>
                                                {{ $notification->data['message'] }}
                                            </h6>
                                            <span class="badge notification-badge 
                                                  {{ $notification->read() ? 'badge-secondary' : 'badge-primary' }}">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        
                                        @if(isset($notification->data['medicine_name']))
                                        <div class="pl-4">
                                            <p class="mb-1">
                                                <strong>Medicamento:</strong> {{ $notification->data['medicine_name'] }}
                                            </p>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <small class="text-muted">
                                                        <strong>Stock atual:</strong> 
                                                        <span class="{{ $isCritical ? 'text-danger' : ($isWarning ? 'text-warning' : 'text-dark') }}">
                                                            {{ $notification->data['current_stock'] }}
                                                        </span>
                                                    </small>
                                                </div>
                                                <div class="col-md-3">
                                                    <small class="text-muted">
                                                        <strong>Mínimo:</strong> {{ $notification->data['minimum_stock'] }}
                                                    </small>
                                                </div>
                                                <div class="col-md-3">
                                                    <small class="text-muted">
                                                        <strong>Necessário:</strong> {{ $notification->data['required_amount'] }}
                                                    </small>
                                                </div>
                                            </div>
                                            
                                            @if(isset($notification->data['current_stock']))
                                            <div class="progress notification-progress mt-2">
                                                <div class="progress-bar 
                                                    {{ $isCritical ? 'bg-danger' : ($isWarning ? 'bg-warning' : 'bg-info') }}" 
                                                    role="progressbar" 
                                                    style="width: {{ min(100, ($notification->data['current_stock'] / $notification->data['minimum_stock']) * 100) }}%" 
                                                    aria-valuenow="{{ $notification->data['current_stock'] }}" 
                                                    aria-valuemin="0" 
                                                    aria-valuemax="{{ $notification->data['minimum_stock'] }}">
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                        @endif
                                    </div>
                                    
                                    @if(!$notification->read())
                                    <div class="ml-3">
                                        <form method="POST" action="{{ route('notifications.markRead', $notification->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    </div>
                                    @endif
                                </div>
                            </a>
                        @empty
                            <div class="list-group-item text-center py-4">
                                <i class="fas fa-bell-slash fa-2x text-muted mb-3"></i>
                                <h5 class="text-muted">Nenhuma notificação encontrada</h5>
                                <p class="small">Você não tem notificações no momento</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                
                @if($notifications->hasPages())
                <div class="card-footer">
                    {{ $notifications->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Atualizar contador de notificações não lidas periodicamente
    function updateUnreadCount() {
        fetch('{{ route("notifications.unread-count") }}')
            .then(response => response.json())
            .then(data => {
                document.querySelectorAll('.notification-count').forEach(el => {
                    el.textContent = data.count;
                    el.style.display = data.count > 0 ? 'inline-block' : 'none';
                });
            });
    }
    
    // Atualizar a cada 30 segundos
    setInterval(updateUnreadCount, 30000);
</script>
@endpush