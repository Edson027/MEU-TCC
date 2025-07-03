@extends('Template.app')

@section('title', 'Relatórios Analíticos')

@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Relatórios Analíticos - Visão Estatística</h1>

    <!-- Seção 1: Tipos de Relatórios -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Tipos de Relatórios Disponíveis</h2>

        <!-- Tabela 1 -->
        <div class="overflow-x-auto mb-8">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="py-2 px-4 border-b">Tipo de Relatório</th>
                        <th class="py-2 px-4 border-b">Descrição</th>
                        <th class="py-2 px-4 border-b">Opções Disponíveis</th>
                        <th class="py-2 px-4 border-b">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="py-2 px-4 border-b text-center">Estoque</td>
                        <td class="py-2 px-4 border-b">Situação atual do estoque</td>
                        <td class="py-2 px-4 border-b">
                            <ul class="list-disc pl-5">
                                <li>Medicamentos com estoque crítico</li>
                                <li>Próximos a vencer (30 dias)</li>
                                <li>Baixa rotatividade</li>
                            </ul>
                        </td>
                        <td class="py-2 px-4 border-b text-center">
                            <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded mr-1">Visualizar</span>
                            <span class="inline-block bg-red-100 text-red-800 px-2 py-1 rounded">PDF</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="py-2 px-4 border-b text-center">Consumo</td>
                        <td class="py-2 px-4 border-b">Padrões de consumo de medicamentos</td>
                        <td class="py-2 px-4 border-b">
                            <strong>Período:</strong>
                            <ul class="list-disc pl-5">
                                <li>Última semana</li>
                                <li>Último mês</li>
                                <li>Último ano</li>
                            </ul>
                        </td>
                        <td class="py-2 px-4 border-b text-center">
                            <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded mr-1">Visualizar</span>
                            <span class="inline-block bg-red-100 text-red-800 px-2 py-1 rounded">PDF</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="py-2 px-4 border-b text-center">Solicitações</td>
                        <td class="py-2 px-4 border-b">Histórico de solicitações</td>
                        <td class="py-2 px-4 border-b">
                            <strong>Status:</strong>
                            <ul class="list-disc pl-5">
                                <li>Todos</li>
                                <li>Pendentes</li>
                                <li>Aprovados</li>
                                <li>Parciais</li>
                                <li>Rejeitados</li>
                            </ul>
                            <strong>Urgência:</strong>
                            <ul class="list-disc pl-5">
                                <li>Todas</li>
                                <li>Normal</li>
                                <li>Urgente</li>
                                <li>Muito Urgente</li>
                            </ul>
                        </td>
                        <td class="py-2 px-4 border-b text-center">
                            <span class="inline-block bg-purple-100 text-purple-800 px-2 py-1 rounded mr-1">Visualizar</span>
                            <span class="inline-block bg-red-100 text-red-800 px-2 py-1 rounded">PDF</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Gráfico 1: Distribuição dos Tipos de Relatórios -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold mb-3">Distribuição dos Tipos de Relatórios</h3>
            <div class="flex items-center justify-center">
                <div class="w-64 h-64 relative">
                    <!-- Pie Chart usando CSS puro -->
                    <div class="pie-chart" style="--percentage: 33; --color: #3B82F6;">
                        <div class="slice one"></div>
                        <div class="slice two" style="--percentage: 33; --color: #16A34A;"></div>
                        <div class="slice three" style="--percentage: 34; --color: #9333EA;"></div>
                        <div class="chart-center">
                            <span class="text-sm font-semibold">100%</span>
                        </div>
                    </div>
                </div>
                <div class="ml-8">
                    <div class="flex items-center mb-2">
                        <div class="w-4 h-4 bg-blue-500 mr-2"></div>
                        <span>Estoque (33%)</span>
                    </div>
                    <div class="flex items-center mb-2">
                        <div class="w-4 h-4 bg-green-500 mr-2"></div>
                        <span>Consumo (33%)</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-purple-500 mr-2"></div>
                        <span>Solicitações (34%)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Seção 2: Ações e Filtros -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Tabela 2: Ações Disponíveis -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Ações Disponíveis por Relatório</h2>
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="py-2 px-4 border-b">Ação</th>
                        <th class="py-2 px-4 border-b">Estoque</th>
                        <th class="py-2 px-4 border-b">Consumo</th>
                        <th class="py-2 px-4 border-b">Solicitações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="py-2 px-4 border-b">Visualizar</td>
                        <td class="py-2 px-4 border-b text-center">✓</td>
                        <td class="py-2 px-4 border-b text-center">✓</td>
                        <td class="py-2 px-4 border-b text-center">✓</td>
                    </tr>
                    <tr>
                        <td class="py-2 px-4 border-b">Exportar PDF</td>
                        <td class="py-2 px-4 border-b text-center">✓</td>
                        <td class="py-2 px-4 border-b text-center">✓</td>
                        <td class="py-2 px-4 border-b text-center">✓</td>
                    </tr>
                    <tr>
                        <td class="py-2 px-4 border-b">Filtros</td>
                        <td class="py-2 px-4 border-b text-center">-</td>
                        <td class="py-2 px-4 border-b text-center">1</td>
                        <td class="py-2 px-4 border-b text-center">2</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Gráfico 2: Complexidade dos Relatórios -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Complexidade dos Relatórios</h2>
            <p class="text-gray-600 mb-4">Número de Filtros por Relatório</p>
            <div class="h-64">
                <!-- Bar Chart usando CSS Grid -->
                <div class="h-full flex items-end space-x-6">
                    <div class="flex flex-col items-center">
                        <div class="w-12 bg-blue-500 mb-2" style="height: 20%;"></div>
                        <span class="text-sm">Estoque</span>
                        <span class="text-xs text-gray-500">0 filtros</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="w-12 bg-green-500 mb-2" style="height: 40%;"></div>
                        <span class="text-sm">Consumo</span>
                        <span class="text-xs text-gray-500">1 filtro</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="w-12 bg-purple-500 mb-2" style="height: 80%;"></div>
                        <span class="text-sm">Solicitações</span>
                        <span class="text-xs text-gray-500">2 filtros</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Seção 3: Cores e Ícones -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Esquema Visual dos Relatórios</h2>

        <!-- Tabela 3 -->
        <div class="overflow-x-auto mb-8">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="py-2 px-4 border-b">Elemento</th>
                        <th class="py-2 px-4 border-b">Cor</th>
                        <th class="py-2 px-4 border-b">Ícone</th>
                        <th class="py-2 px-4 border-b">Símbolo</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="py-2 px-4 border-b">Estoque</td>
                        <td class="py-2 px-4 border-b">
                            <span class="inline-block w-4 h-4 bg-blue-500 rounded-full mr-2"></span>
                            Azul (#3B82F6)
                        </td>
                        <td class="py-2 px-4 border-b">
                            <div class="flex flex-col">
                                <span>Exclamação (crítico)</span>
                                <span>Calendário (vencimento)</span>
                                <span>Gráfico (rotatividade)</span>
                            </div>
                        </td>
                        <td class="py-2 px-4 border-b text-center text-xl">
                            ⚠️ 📅 📈
                        </td>
                    </tr>
                    <tr>
                        <td class="py-2 px-4 border-b">Consumo</td>
                        <td class="py-2 px-4 border-b">
                            <span class="inline-block w-4 h-4 bg-green-500 rounded-full mr-2"></span>
                            Verde (#16A34A)
                        </td>
                        <td class="py-2 px-4 border-b">-</td>
                        <td class="py-2 px-4 border-b text-center">-</td>
                    </tr>
                    <tr>
                        <td class="py-2 px-4 border-b">Solicitações</td>
                        <td class="py-2 px-4 border-b">
                            <span class="inline-block w-4 h-4 bg-purple-500 rounded-full mr-2"></span>
                            Roxo (#9333EA)
                        </td>
                        <td class="py-2 px-4 border-b">-</td>
                        <td class="py-2 px-4 border-b text-center">-</td>
                    </tr>
                    <tr>
                        <td class="py-2 px-4 border-b">PDF</td>
                        <td class="py-2 px-4 border-b">
                            <span class="inline-block w-4 h-4 bg-red-500 rounded-full mr-2"></span>
                            Vermelho (#DC2626)
                        </td>
                        <td class="py-2 px-4 border-b">Arquivo PDF</td>
                        <td class="py-2 px-4 border-b text-center text-xl">📄</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Gráfico 3: Esquema de Cores -->
        <div>
            <h3 class="text-lg font-semibold mb-3">Distribuição de Cores dos Relatórios</h3>
            <div class="flex items-center justify-center">
                <div class="w-64 h-64 relative">
                    <!-- Pie Chart alternativo -->
                    <div class="donut-chart">
                        <div class="donut-segment" style="--offset: 0; --value: 33; --color: #3B82F6;"></div>
                        <div class="donut-segment" style="--offset: 33; --value: 33; --color: #16A34A;"></div>
                        <div class="donut-segment" style="--offset: 66; --value: 34; --color: #9333EA;"></div>
                    </div>
                </div>
                <div class="ml-8">
                    <div class="flex items-center mb-2">
                        <div class="w-4 h-4 bg-blue-500 mr-2"></div>
                        <span>Azul (Estoque)</span>
                    </div>
                    <div class="flex items-center mb-2">
                        <div class="w-4 h-4 bg-green-500 mr-2"></div>
                        <span>Verde (Consumo)</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-purple-500 mr-2"></div>
                        <span>Roxo (Solicitações)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Estilos para os gráficos */
    .pie-chart {
        width: 100%;
        height: 100%;
        position: relative;
        border-radius: 50%;
        overflow: hidden;
    }

    .slice {
        position: absolute;
        width: 100%;
        height: 100%;
        clip-path: polygon(50% 50%, 50% 0%, 100% 0%);
        transform: rotate(calc(var(--percentage) * 3.6deg));
        background-color: var(--color);
    }

    .slice.one {
        --percentage: 33;
        --color: #3B82F6;
    }

    .slice.two {
        clip-path: polygon(50% 50%, 100% 0%, 100% 100%);
    }

    .slice.three {
        clip-path: polygon(50% 50%, 100% 100%, 0% 100%, 0% 0%);
    }

    .chart-center {
        position: absolute;
        width: 70%;
        height: 70%;
        background: white;
        border-radius: 50%;
        top: 15%;
        left: 15%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .donut-chart {
        width: 100%;
        height: 100%;
        position: relative;
        border-radius: 50%;
        background: conic-gradient(
            #3B82F6 0% 33%,
            #16A34A 33% 66%,
            #9333EA 66% 100%
        );
    }

    .donut-chart::before {
        content: '';
        position: absolute;
        width: 70%;
        height: 70%;
        background: white;
        border-radius: 50%;
        top: 15%;
        left: 15%;
    }
</style>
@endsection
