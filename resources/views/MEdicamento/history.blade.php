@extends('layouts.admin')

@section('title', 'Histórico: ' . $medicine->name)

@push('styles')
<style>
    .history-card {
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
    }
    .history-card:hover {
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }
    .badge-entry {
        background-color: #1cc88a;
        color: white;
    }
    .badge-exit {
        background-color: #e74a3b;
        color: white;
    }
    .badge-pending {
        background-color: #f6c23e;
        color: #1f2d3d;
    }
    .badge-approved {
        background-color: #1cc88a;
        color: white;
    }
    .badge-partial {
        background-color: #4e73df;
        color: white;
    }
    .badge-rejected {
        background-color: #e74a3b;
        color: white;
    }
    .table-responsive {
        overflow-x: auto;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Cabeçalho -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-history"></i> Histórico Completo: {{ $medicine->name }}
        </h1>
        <a href="{{ route('medicines.show', $medicine) }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Voltar
        </a>
    </div>

    <!-- Card de Movimentações -->
    <div class="card shadow mb-4 history-card">
        <div class="card-header py-3 bg-gray-100">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-exchange-alt"></i> Movimentações de Estoque
            </h6>
        </div>
        <div class="card-body">
            @if($movements->isEmpty())
                <div class="text-center py-4">
                    <i class="fas fa-box-open fa-3x text-gray-300 mb-3"></i>
                    <p class="text-gray-500">Nenhuma movimentação registrada para este medicamento.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Tipo</th>
                                <th>Quantidade</th>
                                <th>Responsável</th>
                                <th>Motivo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($movements as $movement)
                            <tr>
                                <td>{{ $movement->movement_date->format('d/m/Y H:i') }}</td>
                                <td>
                                    <span class="badge {{ $movement->type === 'entrada' ? 'badge-entry' : 'badge-exit' }}">
                                        {{ $movement->type === 'entrada' ? 'Entrada' : 'Saída' }}
                                    </span>
                                </td>
                                <td>{{ $movement->quantity }}</td>
                                <td>{{ $movement->user->name }}</td>
                                <td>{{ $movement->reason }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center mt-3">
                    {{ $movements->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Card de Solicitações -->
    <div class="card shadow mb-4 history-card">
        <div class="card-header py-3 bg-gray-100">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-clipboard-list"></i> Solicitações
            </h6>
        </div>
        <div class="card-body">
            @if($requests->isEmpty())
                <div class="text-center py-4">
                    <i class="fas fa-clipboard fa-3x text-gray-300 mb-3"></i>
                    <p class="text-gray-500">Nenhuma solicitação registrada para este medicamento.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Solicitante</th>
                                <th>Quantidade</th>
                                <th>Status</th>
                                <th>Resposta</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $request)
                            <tr>
                                <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $request->user->name }}</td>
                                <td>{{ $request->quantity }}</td>
                                <td>
                                    @if($request->status === 'pending')
                                        <span class="badge badge-pending">Pendente</span>
                                    @elseif($request->status === 'approved')
                                        <span class="badge badge-approved">Aprovado</span>
                                    @elseif($request->status === 'partial')
                                        <span class="badge badge-partial">Parcial</span>
                                    @else
                                        <span class="badge badge-rejected">Rejeitado</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $request->response ?? '-' }}
                                    @if($request->responded_by)
                                        <div class="text-xs text-muted">por {{ $request->responder->name }}</div>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center mt-3">
                    {{ $requests->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection