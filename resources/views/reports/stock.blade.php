@extends('Template.app')

@section('title', 'Relatório de Estoque')

@section('content')
<div class="container mx-auto py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Relatório de Situação do Estoque</h1>
        <div>
            <a href="{{ route('reports.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md mr-2">
                <i class="fas fa-arrow-left mr-1"></i> Voltar
            </a>
            <a href="{{ route('reports.stock', ['pdf' => 1]) }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                <i class="fas fa-file-pdf mr-1"></i> Exportar PDF
            </a>
        </div>
    </div>

    <!-- Legenda de Cores -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <h3 class="font-semibold text-gray-700 mb-3">Legenda de Cores:</h3>
        <div class="flex flex-wrap gap-4">
            <div class="flex items-center">
                <div class="w-4 h-4 bg-red-500 rounded mr-2"></div>
                <span>Estoque Crítico (abaixo do mínimo)</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-yellow-500 rounded mr-2"></div>
                <span>Próximo a Vencer (≤30 dias)</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-purple-500 rounded mr-2"></div>
                <span>Baixa Rotatividade (<5 saídas/ano)</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-red-400 rounded mr-2"></div>
                <span>Medicamento Vencido</span>
            </div>
        </div>
    </div>

    <!-- Resumo Gráfico -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Card Estoque Crítico -->
        <div class="bg-white rounded-lg shadow-lg p-4 border-l-4 border-red-500">
            <h3 class="font-semibold text-red-700 mb-2">
                <i class="fas fa-exclamation-triangle mr-2"></i> Estoque Crítico
            </h3>
            <p class="text-2xl font-bold mb-4">{{ $criticalStock->count() }} itens</p>
            <canvas id="criticalChart" height="150"></canvas>
        </div>

        <!-- Card Próximos a Vencer -->
        <div class="bg-white rounded-lg shadow-lg p-4 border-l-4 border-yellow-500">
            <h3 class="font-semibold text-yellow-700 mb-2">
                <i class="fas fa-calendar-exclamation mr-2"></i> Próximos a Vencer
            </h3>
            <p class="text-2xl font-bold mb-4">{{ $expiringSoon->count() }} itens</p>
            <canvas id="expiringChart" height="150"></canvas>
        </div>

        <!-- Card Baixa Rotatividade -->
        <div class="bg-white rounded-lg shadow-lg p-4 border-l-4 border-purple-500">
            <h3 class="font-semibold text-purple-700 mb-2">
                <i class="fas fa-chart-line mr-2"></i> Baixa Rotatividade
            </h3>
            <p class="text-2xl font-bold mb-4">{{ $lowRotation->count() }} itens</p>
            <canvas id="rotationChart" height="150"></canvas>
        </div>
    </div>

    <!-- Estoque Crítico -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
        <div class="px-6 py-4 bg-red-100 border-b border-red-200">
            <h2 class="text-xl font-semibold text-red-800">
                <i class="fas fa-exclamation-triangle mr-2"></i> Estoque Crítico
                <span class="text-sm font-normal">(abaixo do mínimo)</span>
            </h2>
        </div>
        <div class="p-6">
            @if($criticalStock->isEmpty())
                <p class="text-gray-500">Nenhum medicamento com estoque crítico.</p>
            @else
                <div class="mb-4">
                    <canvas id="criticalStockChart" height="200"></canvas>
                </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Medicamento</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estoque Atual</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estoque Mínimo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diferença</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($criticalStock as $medicine)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $medicine->name }}</div>
                                <div class="text-sm text-gray-500">{{ $medicine->category }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $medicine->stock }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $medicine->minimum_stock }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-red-600">
                                {{ $medicine->stock - $medicine->minimum_stock }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <!-- Próximos a Vencer -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
        <div class="px-6 py-4 bg-yellow-100 border-b border-yellow-200">
            <h2 class="text-xl font-semibold text-yellow-800">
                <i class="fas fa-calendar-exclamation mr-2"></i> Próximos a Vencer
                <span class="text-sm font-normal">(30 dias ou menos)</span>
            </h2>
        </div>
        <div class="p-6">
            @if($expiringSoon->isEmpty())
                <p class="text-gray-500">Nenhum medicamento próximo ao vencimento.</p>
            @else
                <div class="mb-4">
                    <canvas id="expiringSoonChart" height="200"></canvas>
                </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Medicamento</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lote</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Validade</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dias Restantes</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estoque</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($expiringSoon as $medicine)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $medicine->name }}</div>
                                <div class="text-sm text-gray-500">{{ $medicine->category }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $medicine->batch }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $medicine->expiration_date }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="px-2 py-1 rounded-full bg-yellow-100 text-yellow-800">
                                    {{ now()->diffInDays($medicine->expiration_date) }} dias
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $medicine->stock }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <!-- Baixa Rotatividade -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-purple-100 border-b border-purple-200">
            <h2 class="text-xl font-semibold text-purple-800">
                <i class="fas fa-chart-line mr-2"></i> Baixa Rotatividade
                <span class="text-sm font-normal">(menos de 5 saídas no último ano)</span>
            </h2>
        </div>
        <div class="p-6">
            @if($lowRotation->isEmpty())
                <p class="text-gray-500">Nenhum medicamento com baixa rotatividade.</p>
            @else
                <div class="mb-4">
                    <canvas id="lowRotationChart" height="200"></canvas>
                </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Medicamento</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Saídas (12 meses)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estoque Atual</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Validade</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($lowRotation as $medicine)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $medicine->name }}</div>
                                <div class="text-sm text-gray-500">{{ $medicine->category }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="px-2 py-1 rounded-full bg-purple-100 text-purple-800">
                                    {{ $medicine->movements_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $medicine->stock }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $medicine->expiration_date }}
                                <span class="block text-xs {{ $medicine->expiration_status == 'Vencido' ? 'text-red-600 font-semibold' : 'text-yellow-600' }}">
                                    {{ $medicine->expiration_status }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Gráficos de Resumo
    new Chart(document.getElementById('criticalChart'), {
        type: 'doughnut',
        data: {
            labels: ['Abaixo do Mínimo', 'Acima do Mínimo'],
            datasets: [{
                data: [{{ $criticalStock->count() }}, {{ $totalMedicines - $criticalStock->count() }}],
                backgroundColor: ['#ef4444', '#d1d5db'],
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    new Chart(document.getElementById('expiringChart'), {
        type: 'doughnut',
        data: {
            labels: ['Próximos a Vencer', 'Dentro do Prazo'],
            datasets: [{
                data: [{{ $expiringSoon->count() }}, {{ $totalMedicines - $expiringSoon->count() }}],
                backgroundColor: ['#f59e0b', '#d1d5db'],
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    new Chart(document.getElementById('rotationChart'), {
        type: 'doughnut',
        data: {
            labels: ['Baixa Rotação', 'Boa Rotação'],
            datasets: [{
                data: [{{ $lowRotation->count() }}, {{ $totalMedicines - $lowRotation->count() }}],
                backgroundColor: ['#8b5cf6', '#d1d5db'],
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    // Gráfico de Estoque Crítico
    new Chart(document.getElementById('criticalStockChart'), {
        type: 'bar',
        data: {
            labels: @json($criticalStock->pluck('name')),
            datasets: [{
                label: 'Estoque Atual',
                data: @json($criticalStock->pluck('stock')),
                backgroundColor: '#ef4444',
            }, {
                label: 'Estoque Mínimo',
                data: @json($criticalStock->pluck('minimum_stock')),
                backgroundColor: '#fca5a5',
            }]
        },
        options: {
            responsive: true,
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
                borderColor: '#f59e0b',
                backgroundColor: 'rgba(245, 158, 11, 0.1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
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
                backgroundColor: 'rgba(139, 92, 246, 0.2)',
                borderColor: '#8b5cf6',
                pointBackgroundColor: '#8b5cf6',
            }]
        },
        options: {
            responsive: true,
            scales: {
                r: { beginAtZero: true }
            }
        }
    });
</script>
@endsection
@endsection
