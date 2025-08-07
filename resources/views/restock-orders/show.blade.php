@extends('layouts.admin')

@section('title', 'Detalhes do Pedido')

@section('content')
<div class="container-fluid">
    <!-- Cabeçalho -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-file-invoice"></i> Detalhes do Pedido #{{ $order->id }}
        </h1>
        <div>
            <a href="{{ route('restock-orders.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Voltar
            </a>
            @if($order->status == 'pending')
                <form action="{{ route('restock-orders.approve', $order->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm">
                        <i class="fas fa-check fa-sm text-white-50"></i> Aprovar
                    </button>
                </form>
                <button class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm" 
                        data-toggle="modal" 
                        data-target="#rejectModal">
                    <i class="fas fa-times fa-sm text-white-50"></i> Rejeitar
                </button>
            @endif
        </div>
    </div>

    <!-- Status do Pedido -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body py-3">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Status</div>
                            <div>
                                <span class="badge badge-{{ $order->status }} py-2 px-3">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Data do Pedido</div>
                            <div>{{ $order->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Solicitante</div>
                            <div>{{ $order->requester->name }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Processado em</div>
                            <div>{{ $order->processed_at ? $order->processed_at->format('d/m/Y H:i') : '--' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detalhes do Pedido -->
    <div class="row">
        <!-- Informações do Produto -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-pills"></i> Informações do Produto
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th width="30%">Produto</th>
                                    <td>
                                        <a href="{{ route('products.show', $order->product_id) }}">
                                            {{ $order->product->name }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Lote</th>
                                    <td>{{ $order->product->batch ?? '--' }}</td>
                                </tr>
                                <tr>
                                    <th>Validade</th>
                                    <td>{{ $order->product->expiration_date ? $order->product->expiration_date->format('d/m/Y') : '--' }}</td>
                                </tr>
                                <tr>
                                    <th>Categoria</th>
                                    <td>{{ $order->product->category ?? '--' }}</td>
                                </tr>
                                <tr>
                                    <th>Estoque Atual</th>
                                    <td>
                                        {{ $order->product->stock }}
                                        <div class="progress mt-2">
                                            @php
                                                $percentage = ($order->product->stock / $order->product->minimum_stock) * 100;
                                                $color = $percentage < 50 ? 'danger' : ($percentage < 100 ? 'warning' : 'success');
                                            @endphp
                                            <div class="progress-bar bg-{{ $color }}" 
                                                 role="progressbar" 
                                                 style="width: {{ min(100, $percentage) }}%" 
                                                 aria-valuenow="{{ $order->product->stock }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="{{ $order->product->minimum_stock * 2 }}">
                                            </div>
                                        </div>
                                        <small class="text-muted">Mínimo: {{ $order->product->minimum_stock }}</small>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detalhes do Pedido -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-clipboard-list"></i> Detalhes do Pedido
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th width="30%">Quantidade Solicitada</th>
                                    <td>{{ $order->quantity_requested }}</td>
                                </tr>
                                <tr>
                                    <th>Estoque Projetado</th>
                                    <td>
                                        {{ $order->product->stock + ($order->status == 'approved' ? $order->quantity_requested : 0) }}
                                        @if($order->status == 'approved')
                                            <small class="text-success d-block">
                                                (+{{ $order->quantity_requested }} unidades adicionadas)
                                            </small>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge badge-{{ $order->status }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @if($order->status == 'rejected')
                                <tr>
                                    <th>Motivo da Rejeição</th>
                                    <td>{{ $order->rejection_reason }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th>Processado por</th>
                                    <td>{{ $order->processor ? $order->processor->name : '--' }}</td>
                                </tr>
                                <tr>
                                    <th>Data de Processamento</th>
                                    <td>{{ $order->processed_at ? $order->processed_at->format('d/m/Y H:i') : '--' }}</td>
                                </tr>
                                <tr>
                                    <th>Observações</th>
                                    <td>{{ $order->notes ?? '--' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Histórico -->
    @if($order->logs->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history"></i> Histórico de Alterações
                    </h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @foreach($order->logs as $log)
                        <div class="timeline-item">
                            <div class="timeline-icon">
                                @if($log->action == 'created')
                                    <i class="fas fa-plus-circle text-primary"></i>
                                @elseif($log->action == 'approved')
                                    <i class="fas fa-check-circle text-success"></i>
                                @elseif($log->action == 'rejected')
                                    <i class="fas fa-times-circle text-danger"></i>
                                @else
                                    <i class="fas fa-edit text-info"></i>
                                @endif
                            </div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-1">{{ $log->description }}</h6>
                                    <small class="text-muted">{{ $log->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                                <small class="text-muted">Por: {{ $log->user->name }}</small>
                                @if($log->details)
                                    <div class="mt-2 p-2 bg-light rounded">
                                        <pre class="mb-0">{{ json_encode($log->details, JSON_PRETTY_PRINT) }}</pre>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Modal de Rejeição -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Rejeitar Pedido #{{ $order->id }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('restock-orders.reject', $order->id) }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rejection_reason">Motivo da Rejeição *</label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Confirmar Rejeição</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 50px;
    }
    .timeline-item {
        position: relative;
        padding-bottom: 20px;
    }
    .timeline-icon {
        position: absolute;
        left: -40px;
        top: 0;
        width: 30px;
        height: 30px;
        text-align: center;
        line-height: 30px;
        background: #fff;
        border-radius: 50%;
        border: 2px solid #ddd;
    }
    .timeline-content {
        padding: 15px;
        background: #f8f9fa;
        border-radius: 5px;
        border-left: 3px solid #4e73df;
    }
    .timeline-item:last-child {
        padding-bottom: 0;
    }
    .badge-pending {
        background-color: #f6c23e;
        color: #000;
    }
    .badge-approved {
        background-color: #1cc88a;
        color: #fff;
    }
    .badge-rejected {
        background-color: #e74a3b;
        color: #fff;
    }
</style>
@endpush