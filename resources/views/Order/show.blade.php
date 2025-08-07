@extends('layouts.admin')

@section('title', 'Detalhes do Pedido #' . $order->id)

@push('styles')
<style>
    .info-card {
        transition: all 0.3s ease;
        border-left: 4px solid #4e73df;
    }
    .items-card {
        transition: all 0.3s ease;
        border-left: 4px solid #1cc88a;
    }
    .actions-card {
        transition: all 0.3s ease;
        border-left: 4px solid #f6c23e;
    }
    .badge-secondary {
        background-color: #6c757d;
        color: white;
    }
    .badge-primary {
        background-color: #4e73df;
        color: white;
    }
    .badge-success {
        background-color: #1cc88a;
        color: white;
    }
    .badge-danger {
        background-color: #e74a3b;
        color: white;
    }
    .badge-warning {
        background-color: #f6c23e;
        color: #1f2d3d;
    }
    .urgency-high {
        color: #e74a3b;
        font-weight: bold;
    }
    .urgency-medium {
        color: #f6c23e;
        font-weight: bold;
    }
    .urgency-low {
        color: #1cc88a;
    }
    .status-badge {
        font-size: 1rem;
        padding: 0.5em 0.8em;
    }
    .timeline {
        position: relative;
        padding-left: 1.5rem;
    }
    .timeline::before {
        content: '';
        position: absolute;
        left: 7px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e3e6f0;
    }
    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -1.5rem;
        top: 0.25rem;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #4e73df;
        border: 2px solid #fff;
    }
    .timeline-date {
        font-size: 0.85rem;
        color: #6c757d;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Cabeçalho -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-clipboard-list"></i> Detalhes do Pedido #{{ $order->id }}
        </h1>
        <div>
            <a href="{{ route('orders.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Voltar
            </a>
            @if($order->status === 'pending')
                <a href="{{ route('orders.edit', $order) }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm ml-2">
                    <i class="fas fa-edit fa-sm text-white-50"></i> Editar
                </a>
            @endif
        </div>
    </div>

    <!-- Cards de Informação -->
    <div class="row mb-4">
        <!-- Informações Básicas -->
        <div class="col-lg-4 mb-4">
            <div class="card info-card h-100">
                <div class="card-header py-3 bg-gray-100">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle"></i> Informações do Pedido
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h5 class="font-weight-bold text-gray-800">Status</h5>
                        <span class="status-badge badge 
                            @switch($order->status)
                                @case('pending') badge-secondary @break
                                @case('approved') badge-success @break
                                @case('rejected') badge-danger @break
                                @case('completed') badge-primary @break
                            @endswitch">
                            @switch($order->status)
                                @case('pending') Pendente @break
                                @case('approved') Aprovado @break
                                @case('rejected') Rejeitado @break
                                @case('completed') Completo @break
                            @endswitch
                        </span>
                    </div>
                    <div class="mb-3">
                        <h5 class="font-weight-bold text-gray-800">Data do Pedido</h5>
                        <p>{{ $order->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="mb-3">
                        <h5 class="font-weight-bold text-gray-800">Solicitante</h5>
                        <p>{{ $order->requester->name }}</p>
                    </div>
                    <div class="mb-3">
                        <h5 class="font-weight-bold text-gray-800">Urgência</h5>
                        <p>
                            @switch($order->urgency_level)
                                @case('high') <span class="urgency-high"><i class="fas fa-exclamation-triangle"></i> Alta</span> @break
                                @case('medium') <span class="urgency-medium"><i class="fas fa-exclamation-circle"></i> Média</span> @break
                                @default <span class="urgency-low"><i class="fas fa-check-circle"></i> Baixa</span>
                            @endswitch
                        </p>
                    </div>
                    @if($order->approved_by)
                        <div class="mb-3">
                            <h5 class="font-weight-bold text-gray-800">Aprovado por</h5>
                            <p>{{ $order->approver->name }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Itens do Pedido -->
        <div class="col-lg-4 mb-4">
            <div class="card items-card h-100">
                <div class="card-header py-3 bg-gray-100">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-pills"></i> Medicamento Solicitado
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h5 class="font-weight-bold text-gray-800">Nome</h5>
                        <p>{{ $order->medicine->name }}</p>
                    </div>
                    <div class="mb-3">
                        <h5 class="font-weight-bold text-gray-800">Lote</h5>
                        <p>{{ $order->medicine->batch }}</p>
                    </div>
                    <div class="mb-3">
                        <h5 class="font-weight-bold text-gray-800">Quantidade</h5>
                        <p>{{ $order->quantity }} unidades</p>
                    </div>
                    <div class="mb-3">
                        <h5 class="font-weight-bold text-gray-800">Estoque Disponível</h5>
                        <p>{{ $order->medicine->stock }} unidades</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ações e Observações -->
        <div class="col-lg-4 mb-4">
            <div class="card actions-card h-100">
                <div class="card-header py-3 bg-gray-100">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-comment-alt"></i> Observações
                    </h6>
                </div>
                <div class="card-body">
                    @if($order->notes)
                        <p class="text-gray-800">{{ $order->notes }}</p>
                    @else
                        <p class="text-muted font-italic">Nenhuma observação registrada</p>
                    @endif
                </div>
                @if($order->status === 'pending')
                    <div class="card-footer bg-gray-100">
                        <div class="d-flex justify-content-between">
                                <form action="{{ route('orders.approve', $order) }}" method="POST" class="w-100 mr-2">
                                    @csrf
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="fas fa-check"></i> Aprovar
                                    </button>
                                </form>
                            <form action="{{ route('orders.destroy', $order) }}" method="POST" class="w-100">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Tem certeza que deseja rejeitar este pedido?')">
                                    <i class="fas fa-times"></i> Rejeitar
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Histórico do Pedido -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-gray-100">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-history"></i> Histórico do Pedido
            </h6>
        </div>
        <div class="card-body">
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-date">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                    <h5 class="font-weight-bold">Pedido criado</h5>
                    <p>Solicitado por: {{ $order->requester->name }}</p>
                </div>
                
                @if($order->status === 'approved' || $order->status === 'rejected' || $order->status === 'completed')
                    <div class="timeline-item">
                        <div class="timeline-date">{{ $order->updated_at->format('d/m/Y H:i') }}</div>
                        <h5 class="font-weight-bold">Pedido {{ $order->status === 'approved' ? 'aprovado' : ($order->status === 'rejected' ? 'rejeitado' : 'completado') }}</h5>
                        <p>Por: {{ $order->approver->name }}</p>
                        @if($order->status === 'approved')
                            <p class="text-success"><i class="fas fa-check-circle"></i> Estoque atualizado: -{{ $order->quantity }} unidades</p>
                        @endif
                    </div>
                @endif
                
                @if($order->status === 'completed')
                    <div class="timeline-item">
                        <div class="timeline-date">{{ $order->updated_at->format('d/m/Y H:i') }}</div>
                        <h5 class="font-weight-bold">Pedido entregue</h5>
                        <p>Medicamento entregue ao solicitante</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection