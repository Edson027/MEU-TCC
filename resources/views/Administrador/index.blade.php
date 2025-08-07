@extends('layouts.app')

@section('title', 'Painel Administrativo')

@section('content')
<div class="container-fluid px-4">
    <!-- Cabeçalho -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Painel Administrativo</h1>
        <div>
            <form action="{{ route('medicines.check') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-sync-alt"></i> Verificar Estoque
                </button>
            </form>
            <a href="{{ route('notifications') }}" class="btn btn-sm btn-outline-secondary ml-2">
                <i class="fas fa-bell"></i> Notificações
                @if($unreadCount > 0)
                <span class="badge bg-danger">{{ $unreadCount }}</span>
                @endif
            </a>
        </div>
    </div>

    <!-- Cards de Status -->
    <div class="row mb-4">
        <!-- Total de Medicamentos -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total de Medicamentos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalMedicines }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-pills fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estoque Baixo -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Estoque Baixo</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $lowStockCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <a href="{{ route('medicines.index', ['stock_status' => 'low']) }}" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <!-- Próximos a Vencer -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Próximos a Vencer (30 dias)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $expiringSoonCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-times fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <a href="{{ route('medicines.index', ['expiration' => 'soon']) }}" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <!-- Vencidos -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-dark shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                Medicamentos Vencidos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $expiredCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-ban fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <a href="{{ route('medicines.index', ['expiration' => 'expired']) }}" class="stretched-link"></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos e Tabelas -->
    <div class="row">
        <!-- Gráfico de Estoque por Categoria -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Medicamentos por Categoria</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="categoryChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        @foreach($categories as $category)
                        <span class="mr-2">
                            <i class="fas fa-circle" style="color: {{ $categoryColors[$loop->index] }}"></i> {{ $category->category }}
                        </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfico de Movimentações -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Movimentações Recentes (7 dias)</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="movementChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabelas de Alerta -->
    <div class="row">
        <!-- Medicamentos com Estoque Crítico -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-exclamation-triangle"></i> Estoque Crítico
                    </h6>
                    <a href="{{ route('medicines.create') }}" class="btn btn-sm btn-primary">+ Adicionar</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Medicamento</th>
                                    <th>Lote</th>
                                    <th>Estoque</th>
                                    <th>Mínimo</th>
                                    <th>Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lowStockMedicines as $medicine)
                                <tr class="{{ $medicine->stock == 0 ? 'table-danger' : '' }}">
                                    <td>
                                        <a href="{{ route('medicines.show', $medicine) }}">{{ $medicine->name }}</a>
                                    </td>
                                    <td>{{ $medicine->batch }}</td>
                                    <td class="{{ $medicine->stock < $medicine->minimum_stock ? 'text-danger font-weight-bold' : '' }}">
                                        {{ $medicine->stock }}
                                    </td>
                                    <td>{{ $medicine->minimum_stock }}</td>
                                    <td>
                                        <a href="{{ route('medicines.edit', $medicine) }}" class="btn btn-sm btn-outline-primary" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('medicines.history', $medicine) }}" class="btn btn-sm btn-outline-info" title="Histórico">
                                            <i class="fas fa-history"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Nenhum medicamento com estoque crítico</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Medicamentos Próximos a Vencer -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-danger">
                        <i class="fas fa-calendar-times"></i> Próximos a Vencer (30 dias)
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
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
                                @forelse($expiringSoonMedicines as $medicine)
                                <tr class="{{ $medicine->expiration_date < now() ? 'table-dark' : '' }}">
                                    <td>
                                        <a href="{{ route('medicines.show', $medicine) }}">{{ $medicine->name }}</a>
                                    </td>
                                    <td>{{ $medicine->batch }}</td>
                                    <td>{{ \Carbon\Carbon::parse($medicine->expiration_date)->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge {{ $medicine->expiration_date < now() ? 'badge-danger' : ($medicine->expiration_date < now()->addDays(15) ? 'badge-warning' : 'badge-info') }}">
                                            {{ $medicine->expiration_date < now() ? 'Vencido' : \Carbon\Carbon::now()->diffInDays($medicine->expiration_date) . ' dias' }}
                                        </span>
                                    </td>
                                    <td>{{ $medicine->stock }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Nenhum medicamento próximo da validade</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notificações Recentes -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bell"></i> Notificações Recentes
                    </h6>
                    <a href="{{ route('notifications') }}" class="btn btn-sm btn-outline-primary">Ver Todas</a>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($recentNotifications as $notification)
                        <div class="list-group-item {{ $notification->read ? '' : 'bg-light' }}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="badge badge-{{ $notification->type == 'stock' ? 'warning' : 'danger' }}">
                                            {{ $notification->type == 'stock' ? 'Estoque Baixo' : 'Validade' }}
                                        </span>
                                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">
                                        @if($notification->medicine)
                                        <strong>{{ $notification->medicine->name }}</strong> ({{ $notification->medicine->batch }})
                                        @else
                                        <strong>Medicamento não encontrado</strong>
                                        @endif
                                    </p>
                                    <p class="mb-0 small text-muted">{{ $notification->message }}</p>
                                </div>
                                <div class="ml-3">
                                    @if(!$notification->read)
                                    <form action="{{ route('notifications.markAsRead', $notification) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-link text-success" title="Marcar como lida">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    @endif
                                    <form action="{{ route('notifications.delete', $notification) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-link text-danger" title="Remover">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="list-group-item text-center text-muted py-4">
                            Nenhuma notificação recente
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .chart-area, .chart-pie {
        position: relative;
        height: 300px;
    }
    .stretched-link::after {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: 1;
        pointer-events: auto;
        content: "";
        background-color: rgba(0,0,0,0);
    }

    .card {
    border-radius: 0.5rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    margin-bottom: 1.5rem;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
    font-weight: 600;
}

.display-4 {
    font-size: 2.5rem;
    font-weight: 300;
    line-height: 1.2;
}

.list-group-item {
    border-left: 0;
    border-right: 0;
}

.list-group-item:first-child {
    border-top: 0;
}

.table th, .table td {
    vertical-align: middle;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Gráfico de Categorias (Pizza)
    const categoryCtx = document.getElementById('categoryChart');
    const categoryChart = new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($categories->pluck('category')) !!},
            datasets: [{
                data: {!! json_encode($categories->pluck('count')) !!},
                backgroundColor: {!! json_encode($categoryColors) !!},
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            cutout: '70%',
        }
    });

    // Gráfico de Movimentações (Barras)
    const movementCtx = document.getElementById('movementChart');
    const movementChart = new Chart(movementCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($movementDays->pluck('date_formatted')) !!},
            datasets: [
                {
                    label: "Entradas",
                    backgroundColor: "#4e73df",
                    hoverBackgroundColor: "#2e59d9",
                    borderColor: "#4e73df",
                    data: {!! json_encode($movementDays->pluck('entradas')) !!},
                },
                {
                    label: "Saídas",
                    backgroundColor: "#e74a3b",
                    hoverBackgroundColor: "#e02d1b",
                    borderColor: "#e74a3b",
                    data: {!! json_encode($movementDays->pluck('saidas')) !!},
                }
            ]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                x: {
                    stacked: true,
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        maxTicksLimit: 7
                    }
                },
                y: {
                    stacked: false,
                    ticks: {
                        beginAtZero: true,
                        callback: function(value) {
                            if (Number.isInteger(value)) {
                                return value;
                            }
                        }
                    },
                    grid: {
                        color: "rgb(234, 236, 244)",
                        zeroLineColor: "rgb(234, 236, 244)",
                        drawBorder: false,
                        borderDash: [2],
                        zeroLineBorderDash: [2]
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top'
                },
                tooltip: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyColor: "#858796",
                    titleMarginBottom: 10,
                    titleColor: '#6e707e',
                    titleFontSize: 14,
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: true,
                    intersect: false,
                    mode: 'index',
                    caretPadding: 10,
                }
            }
        }
    });
</script>
@endpush