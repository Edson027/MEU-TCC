@extends('layouts.admin')

@section('title', 'Pedidos de Medicamentos')

@push('styles')
<style>
    .table-responsive {
        overflow-x: auto;
    }
    .table {
        width: 100%;
        margin-bottom: 1rem;
        color: #858796;
    }
    .table th {
        padding: 0.75rem;
        vertical-align: top;
        border-top: 1px solid #e3e6f0;
    }
    .table thead th {
        vertical-align: bottom;
        border-bottom: 2px solid #e3e6f0;
    }
    .table td {
        padding: 0.75rem;
        vertical-align: top;
        border-top: 1px solid #e3e6f0;
    }
    .badge {
        display: inline-block;
        padding: 0.25em 0.4em;
        font-size: 75%;
        font-weight: 700;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0.35rem;
    }
    .badge-secondary {
        color: #fff;
        background-color: #6c757d;
    }
    .badge-primary {
        color: #fff;
        background-color: #4e73df;
    }
    .badge-success {
        color: #fff;
        background-color: #1cc88a;
    }
    .badge-danger {
        color: #fff;
        background-color: #e74a3b;
    }
    .badge-warning {
        color: #1f2d3d;
        background-color: #f6c23e;
    }
    .action-link {
        margin-right: 0.5rem;
    }
    .action-link:last-child {
        margin-right: 0;
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
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e3e6f0;
    }
    .btn-primary {
        background-color: #4e73df;
        border-color: #4e73df;
    }
    .btn-primary:hover {
        background-color: #2e59d9;
        border-color: #2653d4;
    }
    .btn-secondary {
        background-color: #858796;
        border-color: #858796;
    }
    .btn-secondary:hover {
        background-color: #717384;
        border-color: #6b6d7d;
    }
    .text-gray-800 {
        color: #5a5c69;
    }
    .bg-gray-100 {
        background-color: #f8f9fa !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Cabeçalho -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-clipboard-list"></i> Pedidos de Medicamentos
        </h1>
        <a href="{{ route('orders.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Novo Pedido
        </a>
    </div>

    <!-- Filtros -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-gray-100">
            <h6 class="m-0 font-weight-bold text-primary">Filtros</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('orders.index') }}">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Pesquisar</label>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   class="form-control" placeholder="Pesquise por medicamento ou solicitante">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="">Todos</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendentes</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Aprovados</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejeitados</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completos</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Urgência</label>
                            <select name="urgency" class="form-control">
                                <option value="">Todas</option>
                                <option value="high" {{ request('urgency') == 'high' ? 'selected' : '' }}>Alta</option>
                                <option value="medium" {{ request('urgency') == 'medium' ? 'selected' : '' }}>Média</option>
                                <option value="low" {{ request('urgency') == 'low' ? 'selected' : '' }}>Baixa</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter"></i> Filtrar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabela de Pedidos -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-gray-100">
            <h6 class="m-0 font-weight-bold text-primary">Lista de Pedidos</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Medicamento</th>
                            <th>Quantidade</th>
                            <th>Solicitante</th>
                            <th>Urgência</th>
                            <th>Status</th>
                            <th>Data</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>
                                <div class="font-weight-bold">{{ $order->medicine->name }}</div>
                                <div class="text-muted small">{{ $order->medicine->batch }}</div>
                            </td>
                            <td>{{ $order->quantity }}</td>
                            <td>{{ $order->requester->name }}</td>
                            <td>
                                @switch($order->urgency_level)
                                    @case('high') <span class="urgency-high"><i class="fas fa-exclamation-triangle"></i> Alta</span> @break
                                    @case('medium') <span class="urgency-medium"><i class="fas fa-exclamation-circle"></i> Média</span> @break
                                    @default <span class="urgency-low"><i class="fas fa-check-circle"></i> Baixa</span>
                                @endswitch
                            </td>
                            <td>
                                @switch($order->status)
                                    @case('pending') <span class="badge badge-secondary">Pendente</span> @break
                                    @case('approved') <span class="badge badge-success">Aprovado</span> @break
                                    @case('rejected') <span class="badge badge-danger">Rejeitado</span> @break
                                    @case('completed') <span class="badge badge-primary">Completo</span> @break
                                @endswitch
                            </td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('orders.show', $order) }}" class="action-link text-primary" title="Ver detalhes">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                @if($order->status === 'pending')
                                    <a href="{{ route('orders.edit', $order) }}" class="action-link text-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    @can('approve', $order)
                                        <form action="{{ route('orders.approve', $order) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="action-link text-success bg-transparent border-0" title="Aprovar">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endcan
                                    
                                    <form action="{{ route('orders.destroy', $order) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-link text-danger bg-transparent border-0" title="Rejeitar" onclick="return confirm('Tem certeza que deseja rejeitar este pedido?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Paginação -->
            <div class="d-flex justify-content-center mt-3">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection