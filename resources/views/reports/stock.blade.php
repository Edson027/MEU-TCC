@extends('layouts.admin')

@section('title', 'Relatório de Estoque')

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
    .alert-card {
        border-left: 4px solid;
    }
    .alert-card-critical {
        border-left-color: #e74a3b;
    }
    .alert-card-warning {
        border-left-color: #f6c23e;
    }
    .alert-card-info {
        border-left-color: #36b9cc;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Cabeçalho -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-file-alt"></i> Relatório de Situação do Estoque
        </h1>
        <div>
            <a href="{{ route('reports.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm mr-2">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Voltar
            </a>
            <a href="{{ route('reports.stock', ['pdf' => 1]) }}" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm">
                <i class="fas fa-file-pdf fa-sm text-white-50"></i> Exportar PDF
            </a>
        </div>
    </div>

    <!-- Legenda de Cores -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle"></i> Legenda de Cores
                    </h6>
                </div>
                <div class="card-body py-2">
                    <div class="d-flex flex-wrap align-items-center">
                        <div class="legend-item"><span class="legend-color bg-danger"></span> Estoque Crítico (abaixo do mínimo)</div>
                        <div class="legend-item"><span class="legend-color bg-warning"></span> Próximo a Vencer (≤30 dias)</div>
                        <div class="legend-item"><span class="legend-color bg-purple"></span> Baixa Rotatividade (<5 saídas/ano)</div>
                        <div class="legend-item"><span class="legend-color bg-danger" style="opacity: 0.7;"></span> Medicamento Vencido</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Resumo Gráfico -->
    <div class="row mb-4">
        <!-- Card Estoque Crítico -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card stat-card stat-card-danger h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                <i class="fas fa-exclamation-triangle"></i> Estoque Crítico
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $criticalStock->count() }} itens</div>
                        </div>
                        <div class="col-auto">
                            <div style="width: 100px; height: 100px;">
                                <canvas id="criticalChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Próximos a Vencer -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card stat-card stat-card-warning h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                <i class="fas fa-calendar-exclamation"></i> Próximos a Vencer
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $expiringSoon->count() }} itens</div>
                        </div>
                        <div class="col-auto">
                            <div style="width: 100px; height: 100px;">
                                <canvas id="expiringChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Baixa Rotatividade -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card h-100" style="border-left: 4px solid #6f42c1;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #6f42c1;">
                                <i class="fas fa-chart-line"></i> Baixa Rotatividade
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $lowRotation->count() }} itens</div>
                        </div>
                        <div class="col-auto">
                            <div style="width: 100px; height: 100px;">
                                <canvas id="rotationChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estoque Crítico -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-danger text-white">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-exclamation-triangle mr-2"></i> Estoque Crítico (abaixo do mínimo)
            </h6>
            <span class="badge badge-light">{{ $criticalStock->count() }} itens</span>
        </div>
        <div class="card-body">
            @if($criticalStock->isEmpty())
                <div class="alert alert-info">
                    Nenhum medicamento com estoque crítico.
                </div>
            @else
                <div class="chart-area mb-4" style="height: 300px;">
                    <canvas id="criticalStockChart"></canvas>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th>Medicamento</th>
                                <th>Estoque Atual</th>
                                <th>Estoque Mínimo</th>
                                <th>Diferença</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($criticalStock as $medicine)
                            <tr>
                                <td>
                                    <div class="font-weight-bold">{{ $medicine->name }}</div>
                                    <div class="text-muted small">{{ $medicine->category }}</div>
                                </td>
                                <td>{{ $medicine->stock }}</td>
                                <td>{{ $medicine->minimum_stock }}</td>
                                <td class="font-weight-bold text-danger">
                                    {{ $medicine->stock - $medicine->minimum_stock }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Próximos a Vencer -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-warning text-white">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-calendar-exclamation mr-2"></i> Próximos a Vencer (30 dias ou menos)
            </h6>
            <span class="badge badge-light">{{ $expiringSoon->count() }} itens</span>
        </div>
        <div class="card-body">
            @if($expiringSoon->isEmpty())
                <div class="alert alert-info">
                    Nenhum medicamento próximo ao vencimento.
                </div>
            @else
                <div class="chart-area mb-4" style="height: 300px;">
                    <canvas id="expiringSoonChart"></canvas>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th>Medicamento</th>
                                <th>Lote</th>
                                <th>Validade</th>
                                <th>Dias Restantes</th>
                                <th>Estoque</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($expiringSoon as $medicine)
                            <tr>
                                <td>
                                    <div class="font-weight-bold">{{ $medicine->name }}</div>
                                    <div class="text-muted small">{{ $medicine->category }}</div>
                                </td>
                                <td>{{ $medicine->batch }}</td>
                                <td>{{ $medicine->expiration_date }}</td>
                                <td>
                                    <span class="badge badge-warning">
                                        {{ now()->diffInDays($medicine->expiration_date) }} dias
                                    </span>
                                </td>
                                <td>{{ $medicine->stock }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Baixa Rotatividade -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background-color: #6f42c1; color: white;">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-chart-line mr-2"></i> Baixa Rotatividade (menos de 5 saídas no último ano)
            </h6>
            <span class="badge badge-light">{{ $lowRotation->count() }} itens</span>
        </div>
        <div class="card-body">
            @if($lowRotation->isEmpty())
                <div class="alert alert-info">
                    Nenhum medicamento com baixa rotatividade.
                </div>
            @else
                <div class="chart-area mb-4" style="height: 300px;">
                    <canvas id="lowRotationChart"></canvas>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th>Medicamento</th>
                                <th>Saídas (12 meses)</th>
                                <th>Estoque Atual</th>
                                <th>Validade</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lowRotation as $medicine)
                            <tr>
                                <td>
                                    <div class="font-weight-bold">{{ $medicine->name }}</div>
                                    <div class="text-muted small">{{ $medicine->category }}</div>
                                </td>
                                <td>
                                    <span class="badge" style="background-color: #6f42c1; color: white;">
                                        {{ $medicine->movements_count }}
                                    </span>
                                </td>
                                <td>{{ $medicine->stock }}</td>
                                <td>
                                    {{ $medicine->expiration_date }}
                                    <div class="small {{ $medicine->expiration_status == 'Vencido' ? 'text-danger font-weight-bold' : 'text-warning' }}">
                                        {{ $medicine->expiration_status }}
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Gráficos de Resumo
    new Chart(document.getElementById('criticalChart'), {
        type: 'doughnut',
        data: {
            labels: ['Abaixo do Mínimo', 'Acima do Mínimo'],
            datasets: [{
                data: [{{ $criticalStock->count() }}, {{ $criticalStock->count() }}],
                backgroundColor: ['#e74a3b', '#d1d5db'],
            }]
        },
        options: { 
            responsive: true, 
            maintainAspectRatio: false,
            cutout: '70%'
        }
    });

    new Chart(document.getElementById('expiringChart'), {
        type: 'doughnut',
        data: {
            labels: ['Próximos a Vencer', 'Dentro do Prazo'],
            datasets: [{
                data: [{{ $expiringSoon->count() }}, {{ $expiringSoon->count() }}],
                backgroundColor: ['#f6c23e', '#d1d5db'],
            }]
        },
        options: { 
            responsive: true, 
            maintainAspectRatio: false,
            cutout: '70%'
        }
    });

    new Chart(document.getElementById('rotationChart'), {
        type: 'doughnut',
        data: {
            labels: ['Baixa Rotação', 'Boa Rotação'],
            datasets: [{
                data: [{{ $lowRotation->count() }}, {{ $lowRotation->count() }}],
                backgroundColor: ['#6f42c1', '#d1d5db'],
            }]
        },
        options: { 
            responsive: true, 
            maintainAspectRatio: false,
            cutout: '70%'
        }
    });

    // Gráfico de Estoque Crítico
    new Chart(document.getElementById('criticalStockChart'), {
        type: 'bar',
        data: {
            labels: @json($criticalStock->pluck('name')),
            datasets: [{
                label: 'Estoque Atual',
                data: @json($criticalStock->pluck('stock')),
                backgroundColor: '#e74a3b',
            }, {
                label: 'Estoque Mínimo',
                data: @json($criticalStock->pluck('minimum_stock')),
                backgroundColor: '#f6c23e',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Gráfico de Próximos a Vencer
    new Chart(document.getElementById('expiringSoonChart'), {
        type: 'line',
        data: {
            labels: @json($expiringSoon->pluck('name')),
            datasets: [{
                label: 'Dias Restantes',
                data: @json($expiringSoon->map(fn($m) => now()->diffInDays($m->expiration_date))),
                borderColor: '#f6c23e',
                backgroundColor: 'rgba(246, 194, 62, 0.1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Gráfico de Baixa Rotatividade
    new Chart(document.getElementById('lowRotationChart'), {
        type: 'radar',
        data: {
            labels: @json($lowRotation->pluck('name')),
            datasets: [{
                label: 'Saídas (12 meses)',
                data: @json($lowRotation->pluck('movements_count')),
                backgroundColor: 'rgba(111, 66, 193, 0.2)',
                borderColor: '#6f42c1',
                pointBackgroundColor: '#6f42c1',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: { beginAtZero: true }
            }
        }
    });
</script>
@endpush