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
    .expiration-card {
        transition: all 0.3s ease;
        border-left: 4px solid #36b9cc;
    }
    .progress-circle {
        width: 120px;
        height: 120px;
    }
    .movement-entry {
        border-left: 3px solid #1cc88a;
        background-color: rgba(28, 200, 138, 0.05);
    }
    .movement-exit {
        border-left: 3px solid #e74a3b;
        background-color: rgba(231, 74, 59, 0.05);
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
    .badge-info {
        background-color: #36b9cc;
        color: white;
    }
    .alert-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        font-size: 0.7rem;
    }
    .chart-container {
        position: relative;
        height: 300px;
    }
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    .nav-tabs .nav-link.active {
        font-weight: bold;
        border-bottom: 3px solid #4e73df;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Cabeçalho com Breadcrumb -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-pills"></i> Detalhes do Medicamento: {{ $medicine->name }}
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('medicines.index') }}">Medicamentos</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $medicine->name }}</li>
                </ol>
            </nav>
        </div>
        <div class="action-buttons">
            <a href="{{ route('medicines.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Voltar
            </a>
            <a href="{{ route('movements.create', ['medicine' => $medicine->id, 'type' => 'entrada']) }}"
               class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm">
                <i class="fas fa-arrow-down fa-sm text-white-50"></i> Entrada
            </a>
            <a href="{{ route('movements.create', ['medicine' => $medicine->id, 'type' => 'saida']) }}"
               class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm">
                <i class="fas fa-arrow-up fa-sm text-white-50"></i> Saída
            </a>
            <a href="{{ route('medicines.edit', $medicine) }}"
               class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Editar
            </a>
            <a href="{{ route('medicines.history', $medicine) }}"
               class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm">
                <i class="fas fa-history fa-sm text-white-50"></i> Histórico
            </a>
        </div>
    </div>

    <!-- Alertas de Status -->
    @if($medicine->stock_status == 'Crítico' || $medicine->stock_status == 'Esgotado')
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle"></i>
        <strong>Alerta de Estoque!</strong> O medicamento está com estoque {{ strtolower($medicine->stock_status) }}.
        @if($medicine->stock_status == 'Esgotado')
            <span class="ml-2">Estoque: {{ $medicine->stock }} unidades</span>
        @else
            <span class="ml-2">Estoque atual: {{ $medicine->stock }} unidades (Mínimo: {{ $medicine->minimum_stock }} unidades)</span>
        @endif
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if($medicine->expiration_status == 'Vencido' || $medicine->expiration_status == 'Próximo a vencer')
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="fas fa-calendar-exclamation"></i>
        <strong>Alerta de Validade!</strong> Este medicamento está 
        @if($medicine->expiration_status == 'Vencido')
            <strong class="text-danger">vencido</strong> desde {{ \Carbon\Carbon::parse($medicine->expiration_date)->format('d/m/Y') }}.
        @else
            <strong>próximo a vencer</strong> em {{ \Carbon\Carbon::parse($medicine->expiration_date)->format('d/m/Y') }}.
        @endif
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <!-- Cards de Informação -->
    <div class="row mb-4">
        <!-- Informações Básicas -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card info-card h-100">
                <div class="card-header py-3 bg-gray-100 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle"></i> Informações Básicas
                    </h6>
                    <span class="position-relative">
                        <a href="{{ route('medicines.edit', $medicine) }}" class="text-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                    </span>
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
                        <span class="badge 
                            {{ $medicine->expiration_status == 'Vencido' ? 'badge-danger' :
                               ($medicine->expiration_status == 'Próximo a vencer' ? 'badge-warning' : 'badge-success') }}">
                            {{ $medicine->expiration_status }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <h5 class="font-weight-bold text-gray-800">Categoria</h5>
                        <p>{{ $medicine->category }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estoque -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stock-card h-100">
                <div class="card-header py-3 bg-gray-100">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-boxes"></i> Situação do Estoque
                    </h6>
                </div>
                <div class="card-body text-center">
                    <div class="progress-circle mx-auto mb-3 position-relative">
                        <svg class="w-full h-full" viewBox="0 0 36 36">
                            <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                  fill="none"
                                  stroke="#eee"
                                  stroke-width="3" />
                            <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                  fill="none"
                                  stroke="{{ $medicine->stock > 50 ? '#1cc88a' : ($medicine->stock > 10 ? '#f6c23e' : '#e74a3b') }}"
                                  stroke-width="3"
                                  stroke-dasharray="{{ min($medicine->stock, 100) }}, 100" />
                            <text x="18" y="20.5" text-anchor="middle" fill="#4B5563" font-size="8">{{ $medicine->stock }} uni</text>
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

                    @if($medicine->stock < $medicine->minimum_stock && $medicine->stock > 0)
                    <div class="mt-2">
                        <small class="text-danger">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $medicine->minimum_stock - $medicine->stock }} unidades abaixo do mínimo
                        </small>
                    </div>
                    @endif
                </div>
            </div>
        </div>


        <!-- Validade -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card expiration-card h-100">
                <div class="card-header py-3 bg-gray-100">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-calendar-alt"></i> Informações de Validade
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $expirationDate = \Carbon\Carbon::parse($medicine->expiration_date);
                        $daysUntilExpiration = $expirationDate->diffInDays(now(), false) * -1;
                    @endphp
                    
                    <div class="text-center mb-3">
                        <h2 class="{{ $daysUntilExpiration < 0 ? 'text-success' : ($daysUntilExpiration < 30 ? 'text-warning' : 'text-danger') }}">
                            {{ abs($daysUntilExpiration) }}
                        </h2>
                        <p class="text-muted">
                            dias {{ $daysUntilExpiration >= 0 ? 'desde' : 'para' }} a validade
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <h5 class="font-weight-bold text-gray-800">Data de Validade</h5>
                        <p>{{ $expirationDate->format('d/m/Y') }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h5 class="font-weight-bold text-gray-800">Status</h5>
                        <span class="badge 
                            {{ $medicine->expiration_status == 'Vencido' ? 'badge-danger' :
                               ($medicine->expiration_status == 'Próximo a vencer' ? 'badge-warning' : 'badge-success') }}">
                            {{ $medicine->expiration_status }}
                        </span>
                    </div>
                    
                    @if($medicine->expiration_status == 'Próximo a vencer' || $medicine->expiration_status == 'Vencido')
                    <div class="alert alert-{{ $medicine->expiration_status == 'Vencido' ? 'danger' : 'warning' }} mt-3">
                        <small>
                            <i class="fas fa-exclamation-triangle"></i>
                            {{ $medicine->expiration_status == 'Vencido' 
                                ? 'Este medicamento não deve ser utilizado' 
                                : 'Atenção! Verifique o uso deste medicamento' }}
                        </small>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Descrição -->
        <div class="col-xl-3 col-md-6 mb-4">
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
                    
                    <hr>
                    
                    <div class="mt-3">
                        <h5 class="font-weight-bold text-gray-800">Data de Cadastro</h5>
                        <p>{{ \Carbon\Carbon::parse($medicine->created_at)->format('d/m/Y H:i') }}</p>
                    </div>
                    
                    <div class="mt-3">
                        <h5 class="font-weight-bold text-gray-800">Última Atualização</h5>
                        <p>{{ \Carbon\Carbon::parse($medicine->updated_at)->format('d/m/Y H:i') }}</p>
                    </div>


                    <div class="mt-3">
                        <h5 class="font-weight-bold text-gray-800">Nome do Fornecedor </h5>
                        @foreach($movements as $movement)
                        
                        <p>  @if($movement->medicine && $movement->medicine->fornecedor)
                {{ $movement->medicine->fornecedor->nome }}
            @else
                N/A
            @endif
            </p>    
                        @endforeach
                        
                        
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Abas para Conteúdo -->
    <div class="row mb-4">
        <div class="col-12">
            <ul class="nav nav-tabs" id="medicineTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="movements-tab" data-toggle="tab" href="#movements" role="tab" aria-controls="movements" aria-selected="true">
                        <i class="fas fa-history"></i> Movimentações
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="stats-tab" data-toggle="tab" href="#stats" role="tab" aria-controls="stats" aria-selected="false">
                        <i class="fas fa-chart-line"></i> Estatísticas
                    </a>
                </li>
            </ul>
            
            <div class="tab-content" id="medicineTabsContent">
                <!-- Tab de Movimentações -->
                <div class="tab-pane fade show active" id="movements" role="tabpanel" aria-labelledby="movements-tab">
                    <div class="card shadow mt-3">
                        <div class="card-header py-3 bg-gray-100 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-history"></i> Histórico de Movimentações
                            </h6>
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-primary active" data-filter="all">Todas</button>
                                <button type="button" class="btn btn-outline-success" data-filter="entrada">Entradas</button>
                                <button type="button" class="btn btn-outline-danger" data-filter="saida">Saídas</button>
                            </div>
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
                                        <tr class="movement-row {{ $movement->type === 'entrada' ? 'movement-entry' : 'movement-exit' }}" data-type="{{ $movement->type }}">
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
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted small">
                                    Mostrando {{ $movements->firstItem() ?? 0 }} a {{ $movements->lastItem() ?? 0 }} de {{ $movements->total() }} registros
                                </div>
                                <div>
                                    {{ $movements->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Tab de Estatísticas -->
                <div class="tab-pane fade" id="stats" role="tabpanel" aria-labelledby="stats-tab">
                    <div class="card shadow mt-3">
                        <div class="card-header py-3 bg-gray-100">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-chart-line"></i> Estatísticas de Movimentação
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="chart-container">
                                        <canvas id="movementChart"></canvas>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="m-0 font-weight-bold">Resumo de Movimentações</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row text-center">
                                                    @foreach($movements as $movement)
                        
                                                <div class="col-6">
                                                    <div class="text-success">
                                                        <h4> {{ $movement->type === 'entrada'}} </h4>
                                                        <small>Total Entradas</small>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="text-danger">
                                                        <h4>{{ $movement->type === 'saida'}}</h4>
                                                        <small>Total Saídas</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row text-center">
                                                <div class="col-12">
                                                    <div class="text-primary">
                                                        <h4>0</h4>
                                                        <small>Saldo Líquido</small>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Filtro de movimentações
        const filterButtons = document.querySelectorAll('[data-filter]');
        const movementRows = document.querySelectorAll('.movement-row');
        
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                const filter = this.dataset.filter;
                
                // Atualizar botões ativos
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                // Filtrar linhas
                movementRows.forEach(row => {
                    if (filter === 'all' || row.dataset.type === filter) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
        
        // Gráfico de movimentações
        @if(isset($movementStats) && count($movementStats) > 0)
        const ctx = document.getElementById('movementChart').getContext('2d');
        const movementChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_keys($movementStats)) !!},
                datasets: [
                    {
                        label: 'Entradas',
                        data: {!! json_encode(array_column($movementStats, 'entradas')) !!},
                        borderColor: '#1cc88a',
                        backgroundColor: 'rgba(28, 200, 138, 0.1)',
                        fill: true,
                        tension: 0.3
                    },
                    {
                        label: 'Saídas',
                        data: {!! json_encode(array_column($movementStats, 'saidas')) !!},
                        borderColor: '#e74a3b',
                        backgroundColor: 'rgba(231, 74, 59, 0.1)',
                        fill: true,
                        tension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Movimentações nos Últimos 30 Dias'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Quantidade'
                        }
                    }
                }
            }
        });
        @endif
        
        // Inicializar abas
        $('#medicineTabs a').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
        });
    });
</script>
@endpush