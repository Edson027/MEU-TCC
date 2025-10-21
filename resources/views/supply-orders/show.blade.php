@extends('layouts.admin')

@section('title', 'Detalhes do Pedido')
@section('page-title', 'Detalhes do Pedido')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <!-- Card de Informações do Pedido -->
            <div class="card dashboard-card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-file-invoice me-2"></i>
                        Pedido #{{ $supplyOrder->order_number }}
                    </h5>
                    <span class="badge bg-{{ $supplyOrder->status == 'pending' ? 'warning' : ($supplyOrder->status == 'approved' ? 'info' : 'success') }}">
                        {{ ucfirst($supplyOrder->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-pills me-2 text-primary"></i>Informações do Medicamento</h6>
                            <ul class="list-unstyled">
                                <li><strong>Nome:</strong> {{ $supplyOrder->medicine->name }}</li>
                                <li><strong>Descrição:</strong> {{ $supplyOrder->medicine->description ?? 'N/A' }}</li>
                                <li><strong>Lote:</strong> {{ $supplyOrder->medicine->batch ?? 'N/A' }}</li>
                                <li><strong>Validade:</strong> {{ $supplyOrder->medicine->expiration_date }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-clipboard-list me-2 text-primary"></i>Detalhes do Pedido</h6>
                            <ul class="list-unstyled">
                                <li><strong>Quantidade Solicitada:</strong> 
                                    <span class="badge bg-secondary">{{ $supplyOrder->quantity_requested }}</span>
                                </li>
                                <li><strong>Quantidade Recebida:</strong> 
                                    <span class="badge bg-{{ $supplyOrder->quantity_received > 0 ? 'success' : 'secondary' }}">
                                        {{ $supplyOrder->quantity_received }}
                                    </span>
                                </li>
                                <li><strong>Data Solicitação:</strong> {{ $supplyOrder->request_date->format('d/m/Y') }}</li>
                                <li><strong>Previsão Entrega:</strong> 
                                    {{ $supplyOrder->expected_delivery_date ? $supplyOrder->expected_delivery_date->format('d/m/Y') : 'N/A' }}
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    @if($supplyOrder->reason)
                    <div class="mt-3">
                        <h6><i class="fas fa-comment me-2 text-primary"></i>Motivo da Solicitação</h6>
                        <p class="text-muted">{{ $supplyOrder->reason }}</p>
                    </div>
                    @endif
                    
                    @if($supplyOrder->rejection_reason)
                    <div class="mt-3">
                        <h6><i class="fas fa-times-circle me-2 text-danger"></i>Motivo da Rejeição</h6>
                        <p class="text-danger">{{ $supplyOrder->rejection_reason }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- Card de Ações -->
            <div class="card dashboard-card">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0"><i class="fas fa-cogs me-2"></i>Ações</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($supplyOrder->isPending())
                        <button class="btn btn-success mb-2" 
                                data-bs-toggle="modal" 
                                data-bs-target="#approveModal">
                            <i class="fas fa-check me-2"></i>Aprovar Pedido
                        </button>
                        <button class="btn btn-danger mb-2" 
                                data-bs-toggle="modal" 
                                data-bs-target="#rejectModal">
                            <i class="fas fa-times me-2"></i>Rejeitar Pedido
                        </button>
                        @endif
                        
                        @if(in_array($supplyOrder->status, ['approved', 'partial']))
                        <button class="btn btn-primary mb-2" 
                                data-bs-toggle="modal" 
                                data-bs-target="#receiveModal">
                            <i class="fas fa-truck-loading me-2"></i>Registrar Recebimento
                        </button>
                        @endif
                        
                        <a href="{{ route('supply-orders.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Voltar para Lista
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Card de Informações do Sistema -->
            <div class="card dashboard-card mt-3">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0"><i class="fas fa-info-circle me-2"></i>Informações do Sistema</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li><strong>Solicitante:</strong> {{ $supplyOrder->requester->name }}</li>
                        <li><strong>Data Criação:</strong> {{ $supplyOrder->created_at->format('d/m/Y H:i') }}</li>
                        @if($supplyOrder->approved_by)
                        <li><strong>Aprovador:</strong> {{ $supplyOrder->approver->name }}</li>
                        <li><strong>Data Aprovação:</strong> {{ $supplyOrder->updated_at->format('d/m/Y H:i') }}</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modais -->
@if($supplyOrder->isPending())
<!-- Modal Aprovar -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Aprovar Pedido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('supply-orders.approve', $supplyOrder) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Data Prevista para Entrega</label>
                        <input type="date" name="expected_delivery_date" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Aprovar Pedido</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Rejeitar -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rejeitar Pedido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('supply-orders.reject', $supplyOrder) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Motivo da Rejeição</label>
                        <textarea name="rejection_reason" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Rejeitar Pedido</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@if(in_array($supplyOrder->status, ['approved', 'partial']))
<!-- Modal Recebimento -->
<div class="modal fade" id="receiveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registrar Recebimento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('supply-orders.receive', $supplyOrder) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Quantidade Recebida</label>
                        <input type="number" 
                               name="quantity_received" 
                               class="form-control"
                               min="1" 
                               max="{{ $supplyOrder->getRemainingQuantity() }}"
                               value="{{ $supplyOrder->getRemainingQuantity() }}"
                               required>
                        <div class="form-text">
                            Quantidade pendente: {{ $supplyOrder->getRemainingQuantity() }}
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

@endsection