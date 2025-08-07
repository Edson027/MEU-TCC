@extends('layouts.admin')

@section('title', 'Painel Administrativo')

@push('styles')
<style>
    .stat-card {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .stat-card-primary {
        border-left-color: #4e73df;
    }
    .stat-card-warning {
        border-left-color: #f6c23e;
    }
    .stat-card-danger {
        border-left-color: #e74a3b;
    }
    .stat-card-success {
        border-left-color: #1cc88a;
    }
    .stat-icon {
        font-size: 2rem;
        opacity: 0.3;
    }
    .activity-item {
        border-left: 3px solid transparent;
        transition: all 0.3s ease;
    }
    .activity-item:hover {
        background-color: #f8f9fa;
    }
    .medicine-activity {
        border-left-color: #4e73df;
    }
    .user-activity {
        border-left-color: #1cc88a;
    }
    .prescription-activity {
        border-left-color: #6f42c1;
    }
    .stat-progress {
        height: 5px;
        border-radius: 3px;
    }
    .stat-trend {
        font-size: 0.8rem;
    }
    .legend-item {
        display: inline-block;
        margin-right: 15px;
    }
    .legend-color {
        display: inline-block;
        width: 12px;
        height: 12px;
        margin-right: 5px;
        border-radius: 2px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Cabeçalho -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-tachometer-alt"></i> Painel Administrativo - Visão Geral
        </h1>
        <a href="{{ route('reports.index') }}"  class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-download fa-sm text-white-50"></i> Gerar Relatório
        </a>
    </div>

    <!-- Legenda dos Indicadores -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body py-2">
                    <div class="d-flex flex-wrap align-items-center">
                        <div class="legend-item"><span class="legend-color bg-primary"></span> Informação Primária</div>
                        <div class="legend-item"><span class="legend-color bg-success"></span> Indicador Positivo</div>
                        <div class="legend-item"><span class="legend-color bg-warning"></span> Atenção Necessária</div>
                        <div class="legend-item"><span class="legend-color bg-danger"></span> Ação Imediata</div>
                        <div class="legend-item"><i class="fas fa-arrow-up text-success"></i> Aumento</div>
                        <div class="legend-item"><i class="fas fa-arrow-down text-danger"></i> Redução</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cards de Estatísticas -->
    <div class="row">
        <!-- Total de Medicamentos -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card stat-card-primary h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Medicamentos Cadastrados
                                @if(isset($stats['medicine_trend']))
                                    <span class="stat-trend {{ $stats['medicine_trend'] > 0 ? 'text-success' : 'text-danger' }}">
                                        <i class="fas fa-arrow-{{ $stats['medicine_trend'] > 0 ? 'up' : 'down' }}"></i> 
                                        {{ abs($stats['medicine_trend']) }}% 
                                        {{ $stats['medicine_trend'] > 0 ? 'aumento' : 'redução' }}
                                    </span>
                                @endif
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_medicines'] }}</div>
                            <div class="mt-2 text-xs">
                                <span class="text-muted">Média mensal: {{ round($stats['total_medicines']/12) }}</span>
                            </div>
                            <div class="progress stat-progress mt-1">
<div class="progress-bar bg-primary" role="progressbar"
style="width:{{ min(100,($stats['total_medicines'])*100) }}%"aria-valuenow="{{ $stats['total_medicines'] }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="{{ $stats['total_medicines'] }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-pills stat-icon text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estoque Baixo -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card stat-card-warning h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Estoque Baixo
                                @if(isset($stats['low_stock_trend']))
                                    <span class="stat-trend {{ $stats['low_stock_trend'] > 0 ? 'text-danger' : 'text-success' }}">
                                        <i class="fas fa-arrow-{{ $stats['low_stock_trend'] > 0 ? 'up' : 'down' }}"></i> 
                                        {{ abs($stats['low_stock_trend']) }}% 
                                        {{ $stats['low_stock_trend'] > 0 ? 'aumento' : 'redução' }}
                                    </span>
                                @endif
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['low_stock'] }}</div>
                            <div class="mt-2 text-xs">
                                <span class="text-muted">{{ round(($stats['low_stock']/$stats['total_medicines'])*100, 1) }}% do total</span>
                            </div>
                            <div class="progress stat-progress mt-1">
                                <div class="progress-bar bg-warning" role="progressbar" 
                                     style="width: {{ min(100, ($stats['low_stock'])*100) }}%" 
                                     aria-valuenow="{{ $stats['low_stock'] }}" 
                                     aria-valuemin="0" 
                                     >
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle stat-icon text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Próximos a Vencer -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card stat-card-danger h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Próximos a Vencer (30 dias)
                                @if(isset($stats['expiring_trend']))
                                    <span class="stat-trend {{ $stats['expiring_trend'] > 0 ? 'text-danger' : 'text-success' }}">
                                        <i class="fas fa-arrow-{{ $stats['expiring_trend'] > 0 ? 'up' : 'down' }}"></i> 
                                        {{ abs($stats['expiring_trend']) }}% 
                                        {{ $stats['expiring_trend'] > 0 ? 'aumento' : 'redução' }}
                                    </span>
                                @endif
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['expiring_soon'] }}</div>
                            <div class="mt-2 text-xs">
                            </div>
                            <div class="progress stat-progress mt-1">
                                <div class="progress-bar bg-danger" role="progressbar" 
                                     style="width: {{ min(100, ($stats['expiring_soon'])*100) }}%" 
                                     aria-valuenow="{{ $stats['expiring_soon'] }}" 
                                     aria-valuemin="0" 
                                     >
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-times stat-icon text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Usuários Cadastrados -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card stat-card-success h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Usuários Cadastrados
                                @if(isset($stats['user_trend']))
                                    <span class="stat-trend {{ $stats['user_trend'] > 0 ? 'text-success' : 'text-danger' }}">
                                        <i class="fas fa-arrow-{{ $stats['user_trend'] > 0 ? 'up' : 'down' }}"></i> 
                                        {{ abs($stats['user_trend']) }}% 
                                        {{ $stats['user_trend'] > 0 ? 'crescimento' : 'redução' }}
                                    </span>
                                @endif
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_users'] }}</div>
                            <div class="mt-2 text-xs">
                              
                            </div>
                            <div class="progress stat-progress mt-1">
                                <div class="progress-bar bg-success" role="progressbar" 
                                     style="width: {{ min(100, ($stats['total_users'])*100) }}%" 
                                     aria-valuenow="{{ $stats['total_users'] }}" 
                                     aria-valuemin="0" 
                                     >
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users stat-icon text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos e Alertas -->
    <div class="row">
        <!-- Gráfico de Movimentações -->
    <div class="col-lg-8 mb-4">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Movimentações de Estoque (Últimos 30 dias)</h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" 
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" 
                     aria-labelledby="dropdownMenuLink">
                    <div class="dropdown-header">Opções de Visualização:</div>
                    <a class="dropdown-item" href="#" onclick="updateChart('30')">Últimos 30 dias</a>
                    <a class="dropdown-item" href="#" onclick="updateChart('90')">Últimos 90 dias</a>
                    <a class="dropdown-item" href="#" onclick="updateChart('365')">Últimos 12 meses</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="chart-pie pt-4 pb-2">
                <canvas id="movementPieChart"></canvas>
            </div>
            <div class="mt-4 text-center">
                <div class="d-flex justify-content-center">
                    <div class="mr-4">
                        <span class="d-inline-block rounded-circle bg-success mr-2" style="width:12px; height:12px;"></span>
                        <strong>Entradas:</strong> {{ $movementData->sum('entradas') }} unidades
                    </div>
                    <div class="mr-4">
                        <span class="d-inline-block rounded-circle bg-danger mr-2" style="width:12px; height:12px;"></span>
                        <strong>Saídas:</strong> {{ $movementData->sum('saidas') }} unidades
                    </div>
                    <div>
                        <span class="d-inline-block rounded-circle bg-primary mr-2" style="width:12px; height:12px;"></span>
                        <strong>Saldo:</strong> 
                        @php
                            $saldo = $movementData->sum('entradas') - $movementData->sum('saidas');
                        @endphp
                        <span class="{{ $saldo >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ abs($saldo) }} unidades {{ $saldo >= 0 ? '(Superávit)' : '(Déficit)' }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="mt-3 text-center small text-muted">
                <i class="fas fa-info-circle"></i> O gráfico mostra a proporção entre entradas e saídas no período selecionado.
            </div>
        </div>
    </div>
</div>

        <!-- Alertas Críticos -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-danger">Alertas Críticos</h6>
                    <span class="badge badge-danger">{{ $alerts['expired'] }} alertas expirados</span>
                    <span class="badge badge-danger">{{ $alerts['stock_out'] }} alertas com baixo stoque</span>
                </div>
                <div class="card-body">
                    @if($alerts['stock_out'] > 0)
                    <div class="alert alert-danger d-flex align-items-center mb-3">
                        <i class="fas fa-box-open fa-2x me-3"></i>
                        <div>
                            <h5 class="alert-heading">{{ $alerts['stock_out'] }} medicamentos esgotados</h5>
                            <p class="mb-1 small">Impacto estimado: {{ $alerts['stock_out_impact'] }} atendimentos</p>
                            <div class="progress stat-progress mb-2">
                                <div class="progress-bar bg-danger" role="progressbar" 
                                     style="width: {{ min(100, ($alerts['stock_out']/$stats['total_medicines'])*100) }}%" 
                                     aria-valuenow="{{ $alerts['stock_out'] }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="{{ $stats['total_medicines'] }}">
                                </div>
                            </div>
                            <a href="{{ route('medicines.index', ['stock_status' => 'out']) }}" class="btn btn-sm btn-danger">
                                Ver lista <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                    @endif

                    @if($alerts['expired'] > 0)
                    <div class="alert alert-warning d-flex align-items-center mb-3">
                        <i class="fas fa-calendar-times fa-2x me-3"></i>
                        <div>
                            <h5 class="alert-heading">{{ $alerts['expired'] }} medicamentos vencidos</h5>
                            <p class="mb-1 small">Perda estimada: unidades{{ number_format($alerts['expired'], 2, ',', '.') }}</p>
                            <div class="progress stat-progress mb-2">
                                <div class="progress-bar bg-warning" role="progressbar" 
                                     style="width: {{ min(100, ($alerts['expired']/$stats['total_medicines'])*100) }}%" 
                                     aria-valuenow="{{ $alerts['expired'] }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="{{ $stats['total_medicines'] }}">
                                </div>
                            </div>
                            <a href="{{ route('medicines.index', ['expiration' => 'expired']) }}" class="btn btn-sm btn-warning">
                                Ver lista <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                    @endif

                    @if($stats['low_stock'] > 0)
                    <div class="alert alert-info d-flex align-items-center">
                        <i class="fas fa-exclamation-circle fa-2x me-3"></i>
                        <div>
                            <h5 class="alert-heading">{{ $stats['low_stock'] }} medicamentos com estoque baixo</h5>
                            <p class="mb-1 small">Recomendado: reabastecer em {{ $stats['low_stock'] }} dias</p>
                            <div class="progress stat-progress mb-2">
                                <div class="progress-bar bg-info" role="progressbar" 
                                     style="width: {{ min(100, ($stats['low_stock']/$stats['total_medicines'])*100) }}%" 
                                     aria-valuenow="{{ $stats['low_stock'] }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="{{ $stats['total_medicines'] }}">
                                </div>
                            </div>
                            <a href="{{ route('medicines.index', ['stock_status' => 'low']) }}" class="btn btn-sm btn-info">
                                Ver lista <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Últimas Atividades -->
    <div class="row">
        <!-- Últimos Medicamentos -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Últimos Medicamentos</h6>
                    <div>
                        <span class="badge badge-primary mr-2">Total: {{ $activities['medicines']->count() }}</span>
                        <a href="{{ route('medicines.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($activities['medicines'] as $medicine)
                        <div class="activity-item medicine-activity p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $medicine->name }}</h6>
                                    <small class="text-muted">Lote: {{ $medicine->batch }}</small>
                                </div>
                                <span class="badge {{ $medicine->stock < $medicine->minimum_stock ? 'badge-warning' : 'badge-success' }}">
                                    {{ $medicine->stock }} un
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <small class="text-muted">
                                    @if($medicine->deleted_at)
                                    <i class="fas fa-trash text-danger"></i> Removido em {{ $medicine->deleted_at->format('d/m/Y') }}
                                    @else
                                    <i class="fas fa-calendar-alt"></i> {{ $medicine->created_at->format('d/m/Y H:i') }}
                                    @endif
                                </small>
                                <small class="{{ $medicine->stock < $medicine->minimum_stock ? 'text-danger' : 'text-success' }}">
                                    <i class="fas {{ $medicine->stock < $medicine->minimum_stock ? 'fa-exclamation-circle' : 'fa-check-circle' }}"></i>
                                </small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Últimos Usuários -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-success">Últimos Usuários</h6>
                    <div>
                        <span class="badge badge-success mr-2">Total: {{ $activities['users']->count() }}</span>
                        <a href="{{ route('users.create') }}" class="btn btn-sm btn-success">
                            <i class="fas fa-plus"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($activities['users'] as $user)
                        <div class="activity-item user-activity p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $user->name }}</h6>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </div>
                                <span class="badge 
                                    {{ $user->painel === 'administrador' ? 'badge-primary' : 
                                       ($user->painel === 'médico' ? 'badge-info' : 
                                        ($user->painel === 'enfermeira' ? 'badge-warning' : 'badge-secondary')) }}">
                                    {{ $user->painel }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <small class="text-muted">
                                    <i class="fas fa-calendar-alt"></i> {{ $user->created_at->format('d/m/Y H:i') }}
                                </small>
                                <small class="{{ $user->last_login_at ? 'text-success' : 'text-secondary' }}">
                                    <i class="fas {{ $user->last_login_at ? 'fa-user-check' : 'fa-user-clock' }}"></i>
                                </small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Estatísticas de Uso -->
      
<div class="col-lg-4 mb-4">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-info">Movimentação de Estoque</h6>
        </div>
        <div class="card-body">
            <div class="chart-bar pt-4 pb-2">
                <canvas id="stockChart"></canvas>
            </div>
            <div class="mt-4 text-center small">
                <span class="mr-2">
                    <i class="fas fa-circle text-success"></i> Entradas
                </span>
                <span class="mr-2">
                    <i class="fas fa-circle text-danger"></i> Saídas
                </span>
                <span class="mr-2">
                    <i class="fas fa-circle text-primary"></i> Saldo
                </span>
            </div>
            <!-- Análise de Déficit/Superávite -->
            <div class="mt-3 text-center">
                @if($saldo < 0)
                    <span class="badge badge-danger p-2">
                        <i class="fas fa-exclamation-triangle">Ouve um Déficit de estoque de </i> Déficit (Falta de estoque)
                    </span>
                @elseif($saldo > 0)
                    <span class="badge badge-success p-2">
                        <i class="fas fa-check-circle"></i> Superávite (Excesso de estoque)
                    </span>
                @else
                    <span class="badge badge-primary p-2">
                        <i class="fas fa-balance-scale"></i> Equilíbrio
                    </span>
                @endif
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
    // Gráfico de Movimentações
    const ctx = document.getElementById('movementChart').getContext('2d');
    const movementChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($movementData->pluck('date')->map(fn($date) => \Carbon\Carbon::parse($date))) !!},
            datasets: [
                {
                    label: 'Entradas',
                    data: {!! json_encode($movementData->pluck('entradas')) !!},
                    borderColor: '#1cc88a',
                    backgroundColor: 'rgba(28, 200, 138, 0.1)',
                    fill: true,
                    tension: 0.3
                },
                {
                    label: 'Saídas',
                    data: {!! json_encode($movementData->pluck('saidas')) !!},
                    borderColor: '#e74a3b',
                    backgroundColor: 'rgba(231, 74, 59, 0.1)',
                    fill: true,
                    tension: 0.3
                }
            ]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.raw + ' unidades';
                        },
                        footer: function(context) {
                            const entrada = context[0].raw;
                            const saida = context[1].raw;
                            const saldo = entrada - saida;
                            return 'Saldo: ' + saldo + ' unidades';
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Data'
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return Number.isInteger(value) ? value : '';
                        }
                    },
                    title: {
                        display: true,
                        text: 'Quantidade'
                    }
                }
            }
        }
    });

    // Gráfico de Uso por Perfil
    const usageCtx = document.getElementById('usageChart').getContext('2d');
    const usageChart = new Chart(usageCtx, {
        type: 'doughnut',
        data: {
            labels: ['Administradores', 'Médicos', 'Enfermeiras', 'Outros'],
            datasets: [{
                data: {!! json_encode($stats['roles']) !!},
                backgroundColor: ['#4e73df', '#36b9cc', '#f6c23e', '#858796'],
                hoverBackgroundColor: ['#2e59d9', '#2c9faf', '#dda20a', '#60616f'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            },
            cutout: '70%',
        },
    });


    function updateChart(days) {
        // Implementar chamada AJAX para atualizar o gráfico com base no período selecionado
        console.log('Atualizar gráfico para os últimos ' + days + ' dias');
        // Você precisará implementar a lógica para buscar novos dados do servidor
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Dados do Controller (Laravel)
        const ctx = document.getElementById('stockChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Entradas', 'Saídas', 'Saldo'],
                datasets: [{
                    label: 'Quantidade',
                    data: [{{ $totalEntradas }}, {{ $totalSaidas }}, {{ $saldo }}],
                    backgroundColor: [
                        'rgba(28, 200, 138, 0.8)',  // Verde (Entradas)
                        'rgba(231, 74, 59, 0.8)',   // Vermelho (Saídas)
                        'rgba(54, 162, 235, 0.8)'  // Azul (Saldo)
                    ],
                    borderColor: [
                        'rgba(28, 200, 138, 1)',
                        'rgba(231, 74, 59, 1)',
                        'rgba(54, 162, 235, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString(); // Formata números
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false // Oculta a legenda padrão
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ${context.raw.toLocaleString()}`;
                            }
                        }
                    }
                }
            }
        });
    });
</script>

<script>
    // Pie Chart Implementation
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('movementPieChart').getContext('2d');
        window.movementPieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Entradas', 'Saídas'],
                datasets: [{
                    data: [{{ $movementData->sum('entradas') }}, {{ $movementData->sum('saidas') }}],
                    backgroundColor: ['#1cc88a', '#e74a3b'],
                    hoverBackgroundColor: ['#17a673', '#be2617'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }]
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                    callbacks: {
                        label: function(tooltipItem, data) {
                            const dataset = data.datasets[tooltipItem.datasetIndex];
                            const total = dataset.data.reduce((previousValue, currentValue) => previousValue + currentValue);
                            const currentValue = dataset.data[tooltipItem.index];
                            const percentage = Math.floor(((currentValue/total) * 100)+0.5);
                            return data.labels[tooltipItem.index] + ': ' + currentValue + ' (' + percentage + '%)';
                        }
                    }
                },
                legend: {
                    display: false
                },
                cutoutPercentage: 0,
            }
        });
    });

    // Update chart function (keep your existing implementation)
    function updateChart(days) {
        // Your existing AJAX implementation here
    }
</script>
@endpush