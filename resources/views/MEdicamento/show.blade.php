@extends('layouts.admin')

@section('title', $medicine->name)

@push('styles')
<style>
    .info-card {
        transition: all 0.3s ease;
        border-left: 4px solid #4e73df;
    }
    .stock-card {
        transition: all 0.3s ease;
        border-left: 4px solid #1cc88a;
    }
    .description-card {
        transition: all 0.3s ease;
        border-left: 4px solid #f6c23e;
    }
    .progress-circle {
        width: 120px;
        height: 120px;
    }
    .movement-entry {
        border-left: 3px solid #1cc88a;
    }
    .movement-exit {
        border-left: 3px solid #e74a3b;
    }
    .badge-success {
        background-color: #1cc88a;
        color: white;
    }
    .badge-danger {
        background-color: #e74a3b;
        color: white;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Cabeçalho -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-pills"></i> Detalhes do Medicamento: {{ $medicine->name }}
        </h1>
        <div>
            <a href="{{ route('movements.create', ['medicine' => $medicine->id, 'type' => 'entrada']) }}"
               class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm mr-2">
                <i class="fas fa-arrow-down fa-sm text-white-50"></i> Registrar Entrada
            </a>
            <a href="{{ route('movements.create', ['medicine' => $medicine->id, 'type' => 'saida']) }}"
               class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm">
                <i class="fas fa-arrow-up fa-sm text-white-50"></i> Registrar Saída
            </a>
        </div>
    </div>

    <!-- Cards de Informação -->
    <div class="row mb-4">
        <!-- Informações Básicas -->
        <div class="col-lg-4 mb-4">
            <div class="card info-card h-100">
                <div class="card-header py-3 bg-gray-100">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle"></i> Informações Básicas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h5 class="font-weight-bold text-gray-800">Nome</h5>
                        <p>{{ $medicine->name }}</p>
                    </div>
                    <div class="mb-3">
                        <h5 class="font-weight-bold text-gray-800">Lote</h5>
                        <p>{{ $medicine->batch }}</p>
                    </div>
                    <div class="mb-3">
                        <h5 class="font-weight-bold text-gray-800">Validade</h5>
                        <p>{{ \Carbon\Carbon::parse($medicine->expiration_date)->format('d/m/Y') }}</p>
                    </div>
                    <div class="mb-3">
                        <h5 class="font-weight-bold text-gray-800">Categoria</h5>
                        <p>{{ $medicine->category }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estoque -->
        <div class="col-lg-4 mb-4">
            <div class="card stock-card h-100">
                <div class="card-header py-3 bg-gray-100">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-boxes"></i> Situação do Estoque
                    </h6>
                </div>
                <div class="card-body text-center">
                    <div class="progress-circle mx-auto mb-3">
                        <svg class="w-full h-full" viewBox="0 0 36 36">
                            <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                  fill="none"
                                  stroke="#eee"
                                  stroke-width="3" />
                            <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                  fill="none"
                                  stroke="{{ $medicine->stock > 50 ? '#1cc88a' : ($medicine->stock > 10 ? '#f6c23e' : '#e74a3b') }}"
                                  stroke-width="3"
                                  stroke-dasharray="{{ $medicine->stock }}, 100" />
                            <text x="18" y="20.5" text-anchor="middle" fill="#4B5563" font-size="8">{{ $medicine->stock }}%</text>
                        </svg>
                    </div>
                    <h4 class="font-weight-bold text-gray-800">{{ $medicine->stock }} unidades disponíveis</h4>
                    <p class="text-muted">Estoque mínimo: {{ $medicine->minimum_stock }} unidades</p>
                    
                    <div class="mt-3">
                        <span class="badge 
                            {{ $medicine->stock_status == 'Esgotado' ? 'badge-danger' :
                               ($medicine->stock_status == 'Crítico' ? 'badge-danger' :
                               ($medicine->stock_status == 'Atenção' ? 'badge-warning' : 'badge-success')) }}">
                            {{ $medicine->stock_status }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Descrição -->
        <div class="col-lg-4 mb-4">
            <div class="card description-card h-100">
                <div class="card-header py-3 bg-gray-100">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-align-left"></i> Descrição
                    </h6>
                </div>
                <div class="card-body">
                    @if($medicine->description)
                        <p class="text-gray-800">{{ $medicine->description }}</p>
                    @else
                        <p class="text-muted font-italic">Nenhuma descrição disponível</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Histórico de Movimentações -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-gray-100">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-history"></i> Histórico de Movimentações
            </h6>
        </div>
        <div class="card-body">
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
                        <tr class="{{ $movement->type === 'entrada' ? 'movement-entry' : 'movement-exit' }}">
                            <td>{{ \Carbon\Carbon::parse($movement->movement_date)->format('d/m/Y H:i') }}</td>
                            <td>
                                <span class="badge {{ $movement->type === 'entrada' ? 'badge-success' : 'badge-danger' }}">
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
            
            <!-- Paginação -->
            <div class="d-flex justify-content-center mt-3">
                {{ $movements->links() }}
            </div>
        </div>
    </div>
</div>
@endsection