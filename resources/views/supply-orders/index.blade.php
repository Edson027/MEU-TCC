@extends('layouts.admin')

@section('title', 'Pedidos de Abastecimento')
@section('page-title', 'Pedidos de Abastecimento')

@section('content')
<div class="container-fluid">
    <!-- Ações Rápidas -->
    <div class="quick-actions mb-4">
        <div class="row g-3">
            <div class="col-md-4">
                <a href="{{ route('supply-orders.create') }}" class="action-btn">
                    <i class="fas fa-plus-circle"></i>
                    <span>Novo Pedido</span>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('supply-orders.generate-auto') }}" class="action-btn">
                    <i class="fas fa-robot"></i>
                    <span>Gerar Automático</span>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('reports.index') }}" class="action-btn">
                    <i class="fas fa-chart-bar"></i>
                    <span>Relatórios</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Alertas de Estoque -->
    @if($stockAlerts->count() > 0)
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Alertas de Estoque Baixo</strong>
        <ul class="mb-0 mt-2">
            @foreach($stockAlerts as $medicine)
            <li>
                <strong>{{ $medicine->name }}</strong> - 
                Estoque: <span class="badge bg-danger">{{ $medicine->stock }}</span> 
                (Mínimo: {{ $medicine->minimum_stock }})
                @if($medicine->supplyOrders->count() > 0)
                <small class="text-success ms-2">
                    <i class="fas fa-check-circle"></i> Pedido em andamento
                </small>
                @endif
            </li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Filtros -->
    <div class="card dashboard-card mb-4">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0"><i class="fas fa-filter me-2"></i>Filtros</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('supply-orders.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Todos os Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendente</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Aprovado</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejeitado</option>
                            <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Parcial</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Concluído</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Medicamento</label>
                        <select name="medicine_id" class="form-select">
                            <option value="">Todos os Medicamentos</option>
                            @foreach($medicines as $medicine)
                            <option value="{{ $medicine->id }}" {{ request('medicine_id') == $medicine->id ? 'selected' : '' }}>
                                {{ $medicine->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Filtrar
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Estatísticas Rápidas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card dashboard-card card-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Pendentes</h6>
                            <h3 class="mb-0">{{ $supplyOrders->where('status', 'pending')->count() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card dashboard-card card-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Aprovados</h6>
                            <h3 class="mb-0">{{ $supplyOrders->where('status', 'approved')->count() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card dashboard-card card-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Parciais</h6>
                            <h3 class="mb-0">{{ $supplyOrders->where('status', 'partial')->count() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-tasks fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card dashboard-card card-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Concluídos</h6>
                            <h3 class="mb-0">{{ $supplyOrders->where('status', 'completed')->count() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-flag-checkered fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de Pedidos -->
    <div class="card dashboard-card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0"><i class="fas fa-list me-2"></i>Pedidos de Abastecimento</h5>
            <span class="badge bg-primary">{{ $supplyOrders->total() }} registros</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover datatable">
                    <thead class="table-primary">
                        <tr>
                            <th>Nº Pedido</th>
                            <th>Medicamento</th>
                            <th>Quantidade</th>
                            <th>Status</th>
                            <th>Data Solicitação</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($supplyOrders as $order)
                        <tr>
                            <td>
                                <strong>{{ $order->order_number }}</strong>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-pills text-primary me-2"></i>
                                    {{ $order->medicine->name }}
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $order->quantity_requested }}</span>
                                @if($order->quantity_received > 0)
                                <br>
                                <small class="text-success">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Recebido: {{ $order->quantity_received }}
                                </small>
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusConfig = [
                                        'pending' => ['class' => 'bg-warning', 'icon' => 'clock'],
                                        'approved' => ['class' => 'bg-info', 'icon' => 'check-circle'],
                                        'rejected' => ['class' => 'bg-danger', 'icon' => 'times-circle'],
                                        'partial' => ['class' => 'bg-primary', 'icon' => 'tasks'],
                                        'completed' => ['class' => 'bg-success', 'icon' => 'flag-checkered']
                                    ];
                                    $config = $statusConfig[$order->status] ?? $statusConfig['pending'];
                                @endphp
                                <span class="badge {{ $config['class'] }} status-badge">
                                    <i class="fas fa-{{ $config['icon'] }} me-1"></i>
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>
                                <i class="far fa-calendar me-1 text-muted"></i>
                                {{ $order->request_date->format('d/m/Y') }}
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('supply-orders.show', $order) }}" 
                                       class="btn btn-info" title="Visualizar">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($order->isPending())
                                    <a href="{{ route('supply-orders.edit', $order) }}"
                                       class="btn btn-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endif
                                    @if(in_array($order->status, ['approved', 'partial']))
                                    <button class="btn btn-success" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#receiveModal{{ $order->id }}"
                                            title="Registrar Recebimento">
                                        <i class="fas fa-truck-loading"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Paginação -->
            @if($supplyOrders->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Mostrando {{ $supplyOrders->firstItem() }} a {{ $supplyOrders->lastItem() }} 
                    de {{ $supplyOrders->total() }} registros
                </div>
                <nav>
                    {{ $supplyOrders->links() }}
                </nav>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modais de Recebimento -->
@foreach($supplyOrders as $order)
@if(in_array($order->status, ['approved', 'partial']))
<div class="modal fade" id="receiveModal{{ $order->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registrar Recebimento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('supply-orders.receive', $order) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Quantidade Recebida</label>
                        <input type="number" 
                               name="quantity_received" 
                               class="form-control"
                               min="1" 
                               max="{{ $order->getRemainingQuantity() }}"
                               value="{{ $order->getRemainingQuantity() }}"
                               required>
                        <div class="form-text">
                            Quantidade pendente: {{ $order->getRemainingQuantity() }}
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Data de Recebimento</label>
                        <input type="date" name="actual_delivery_date" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Registrar Recebimento</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endforeach

@endsection

@push('styles')
<style>
    .table td {
        vertical-align: middle;
    }
    .status-badge {
        font-size: 0.75em;
    }
</style>
@endpush