@extends('Template.app')

@section('content')
<div class="container">
    <h1>Minhas Notificações</h1>
    
    @if ($notifications->isEmpty())
        <div class="alert alert-info">Nenhuma notificação encontrada.</div>
    @else
        <div class="list-group">
            @foreach ($notifications as $notification)
                <div class="list-group-item {{ $notification->read ? '' : 'list-group-item-warning' }}">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5>{{ $notification->message }}</h5>
                            <small class="text-muted">
                                Medicamento: {{ $notification->medicine->name }} | 
                                {{ $notification->created_at->format('d/m/Y H:i') }}
                            </small>
                        </div>
                        <div>
                            @if (!$notification->read)
                                <form action="{{ route('notifications.read', $notification) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-outline-success">Marcar como lida</button>
                                </form>
                            @endif
                            <form action="{{ route('notifications.destroy', $notification) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Remover</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="mt-3">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection