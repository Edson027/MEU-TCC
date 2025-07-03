@extends('Template.app')

@section('title', 'Relatório de Consumo')

@section('content')
<div class="container mx-auto py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Relatório de Consumo de Medicamentos</h1>
            <p class="text-gray-600">
                Período: {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}
                <span class="ml-2 px-2 py-1 bg-gray-200 rounded-full text-xs">
                    {{ ucfirst($period) }}
                </span>
            </p>
        </div>
        <div>
            <a href="{{ route('reports.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md mr-2">
                <i class="fas fa-arrow-left mr-1"></i> Voltar
            </a>
            <a href="{{ route('reports.consumption', array_merge(request()->all(), ['pdf' => 1])) }}"
               class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                <i class="fas fa-file-pdf mr-1"></i> Exportar PDF
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Top 10 Medicamentos Mais Consumidos -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-blue-600 text-white">
                <h2 class="text-xl font-bold">Top 10 Medicamentos Mais Consumidos</h2>
            </div>
            <div class="p-6">
                @if($topConsumed->isEmpty())
                    <p class="text-gray-500">Nenhum dado de consumo no período selecionado.</p>
                @else
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Medicamento</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoria</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantidade</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($topConsumed as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->medicine->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item->medicine->category }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item->total }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

        <!-- Consumo por Categoria -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-green-600 text-white">
                <h2 class="text-xl font-bold">Consumo por Categoria</h2>
            </div>
            <div class="p-6">
                @if($categories->isEmpty())
                    <p class="text-gray-500">Nenhum dado de consumo por categoria no período.</p>
                @else
                    <canvas id="categoryChart" height="250"></canvas>
                @endif
            </div>
        </div>
    </div>

    <!-- Consumo Mensal -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-purple-600 text-white">
            <h2 class="text-xl font-bold">Consumo Mensal (Últimos 12 meses)</h2>
        </div>
        <div class="p-6">
            <canvas id="monthlyChart" height="100"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gráfico de consumo por categoria
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        const categoryChart = new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: @json($categories->pluck('category')),
                datasets: [{
                    data: @json($categories->pluck('total')),
                    backgroundColor: [
                        '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6',
                        '#EC4899', '#6366F1', '#06B6D4', '#F97316', '#64748B'
                    ],
                }]
            },
            options: {
                responsive: true,
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

        // Gráfico de consumo mensal
        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        const monthlyChart = new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: @json($monthlyConsumption->pluck('month')),
                datasets: [{
                    label: 'Consumo Mensal',
                    data: @json($monthlyConsumption->pluck('total')),
                    backgroundColor: '#7C3AED',
                    borderColor: '#6D28D9',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Quantidade Consumida'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Mês'
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
