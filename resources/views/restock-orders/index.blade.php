@extends('layouts.admin')

@section('title', 'Pedidos de Reabastecimento')

@push('styles')
<style>
    .order-status-pending {
        border-left: 4px solid #f6c23e;
    }
    .order-status-approved {
        border-left: 4px solid #1cc88a;
    }
    .order-status-rejected {
        border-left: 4px solid #e74a3b;
    }
    .order-item {
        transition: all 0.3s ease;
    }
    .order-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
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
    .action-buttons .btn {
        margin-right: 5px;
        margin-bottom: 5px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Cabeçalho -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-boxes"></i> Pedidos de Reabastecimento
        </h1>
        <a href="{{ route('restock-orders.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Novo Pedido
        </a>
    </div>

    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body py-3">
                    <form method="GET" action="{{ route('restock-orders.index') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="">Todos</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendentes</option>
                                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Aprovados</option>
                                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejeitados</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="product">Produto</label>
                                    <select class="form-control" id="product" name="product_id">
                                        <option value="">Todos</option>
                                        @foreach($medicine as $product)
                                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="date_from">De</label>
                                    <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="date_to">Até</label>
                                    <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 text-right">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter"></i> Filtrar
                                </button>
                                <a href="{{ route('restock-orders.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-undo"></i> Limpar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Cards de Estatísticas -->
    <div class="row mb-4">
        <!-- Pedidos Pendentes -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pendentes
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pedidos Aprovados -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Aprovados
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['approved'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pedidos Rejeitados -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Rejeitados
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['rejected'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total de Pedidos -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total de Pedidos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-list-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de Pedidos -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Lista de Pedidos</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" 
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" 
                             aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Exportar:</div>
                            <a class="dropdown-item" href="#"><i class="fas fa-file-excel text-success"></i> Excel</a>
                            <a class="dropdown-item" href="#"><i class="fas fa-file-pdf text-danger"></i> PDF</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Produto</th>
                                    <th>Quantidade</th>
                                    <th>Solicitante</th>
                                    <th>Status</th>
                                    <th>Data</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr class="order-item order-status-{{ $order->status }}">
                                    <td>#{{ $order->id }}</td>
                                    <td>
                                        <a href="{{ route('products.show', $order->product_id) }}">
                                            {{ $order->product->name }}
                                        </a>
                                        <br>
                                        <small class="text-muted">Estoque: {{ $order->product->stock }}</small>
                                    </td>
                                    <td>{{ $order->quantity_requested }}</td>
                                    <td>{{ $order->requester->name }}</td>
                                    <td>
                                        <span class="badge badge-{{ $order->status }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                        @if($order->status == 'rejected' && $order->rejection_reason)
                                            <br>
                                            <small class="text-muted">Motivo: {{ $order->rejection_reason }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $order->created_at->format('d/m/Y H:i') }}
                                        @if($order->processed_at)
                                            <br>
                                            <small class="text-muted">Processado em: {{ $order->processed_at->format('d/m/Y H:i') }}</small>
                                        @endif
                                    </td>
                                    <td class="action-buttons">
                                        <a href="{{ route('restock-orders.show', $order->id) }}" class="btn btn-sm btn-info" title="Visualizar">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($order->status == 'pending')
                                            <form action="{{ route('restock-orders.approve', $order->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success" title="Aprovar">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <button class="btn btn-sm btn-danger reject-btn" 
                                                    data-toggle="modal" 
                                                    data-target="#rejectModal" 
                                                    data-order-id="{{ $order->id }}"
                                                    title="Rejeitar">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Rejeição -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Rejeitar Pedido de Reabastecimento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rejection_reason">Motivo da Rejeição</label>
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

@push('scripts')
<script>
    $(document).ready(function() {
        // Configurar modal de rejeição
        $('.reject-btn').click(function() {
            var orderId = $(this).data('order-id');
            var formAction = "{{ route('restock-orders.reject', ':id') }}".replace(':id', orderId);
            $('#rejectForm').attr('action', formAction);
        });

        // DataTable
        $('#dataTable').DataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json'
            },
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'btn btn-success btn-sm'
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    className: 'btn btn-danger btn-sm'
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Imprimir',
                    className: 'btn btn-secondary btn-sm'
                }
            ]
        });
    });
</script>
@endpush