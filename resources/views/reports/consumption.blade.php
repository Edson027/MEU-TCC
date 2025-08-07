@extends('layouts.admin')

@section('title', 'Relatório de Consumo')

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
    .stat-card-success {
        border-left-color: #1cc88a;
    }
    .stat-card-info {
        border-left-color: #36b9cc;
    }
    .stat-card-purple {
        border-left-color: #6f42c1;
    }
    .period-badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
    }
    .chart-container {
        position: relative;
        height: 300px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Cabeçalho -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-pills"></i> Relatório de Consumo de Medicamentos
            </h1>
            <p class="mb-0 text-gray-600">
                Período: {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}
                <span class="badge period-badge bg-gray-200 text-gray-800 ml-2">
                    {{ ucfirst($period) }}
                </span>
            </p>
        </div>
        <div>
            <a href="{{ route('reports.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm mr-2">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Voltar
            </a>
            <a href="{{ route('reports.consumption', array_merge(request()->all(), ['pdf' => 1])) }}"
               class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm">
                <i class="fas fa-file-pdf fa-sm text-white-50"></i> Exportar PDF
            </a>
        </div>
    </div>

    <!-- Seção Superior -->
    <div class="row mb-4">
        <!-- Top 10 Medicamentos Mais Consumidos -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-medal"></i> Top 10 Medicamentos Mais Consumidos
                    </h6>
                    <span class="badge badge-light">{{ $topConsumed->count() }} itens</span>
                </div>
                <div class="card-body">
                    @if($topConsumed->isEmpty())
                        <div class="alert alert-info">
                            Nenhum dado de consumo no período selecionado.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Medicamento</th>
                                        <th>Categoria</th>
                                        <th>Quantidade</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topConsumed as $item)
                                    <tr>
                                        <td>
                                            <div class="font-weight-bold">{{ $item->medicine->name }}</div>
                                        </td>
                                        <td>{{ $item->medicine->category }}</td>
                                        <td>{{ $item->total }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
 
        <!-- Consumo por Categoria -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-success text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-tags"></i> Consumo por Categoria
                    </h6>
                    <span class="badge badge-light">{{ $categories->count() }} categorias</span>
                </div>
                <div class="card-body">
                    @if($categories->isEmpty())
                        <div class="alert alert-info">
                            Nenhum dado de consumo por categoria no período.
                        </div>
                    @else
                        <div class="chart-container">
                            <canvas id="categoryChart"></canvas>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Consumo Mensal -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-purple text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-calendar-alt"></i> Consumo Mensal (Últimos 12 meses)
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="monthlyChart"></canvas>
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
        // Gráfico de consumo por categoria
        @if(!$categories->isEmpty())
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        const categoryChart = new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: @json($categories->pluck('category')),
                datasets: [{
                    data: @json($categories->pluck('total')),
                    backgroundColor: [
                        '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b',
                        '#6f42c1', '#fd7e14', '#20c997', '#6610f2', '#6c757d'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    },
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
                }
            }
        });
        @endif

        // Gráfico de consumo mensal
        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        const monthlyChart = new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: @json($monthlyConsumption->pluck('month')),
                datasets: [{
                    label: 'Consumo Mensal',
                    data: @json($monthlyConsumption->pluck('total')),
                    backgroundColor: '#6f42c1',
                    borderColor: '#5a32a8',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Quantidade Consumida'
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Mês'
                        },
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    });
</script>
@endpush