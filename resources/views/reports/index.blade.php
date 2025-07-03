@extends('Template.app')

@section('title', 'Relatórios')

@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Relatórios Analíticos</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Relatório de Estoque -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-blue-600 text-white">
                <h2 class="text-xl font-bold">Relatório de Estoque</h2>
                <p class="text-blue-100">Situação atual do estoque</p>
            </div>
            <div class="p-6">
                <ul class="space-y-2 mb-4">
                    <li class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                        <span>Medicamentos com estoque crítico</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-calendar-exclamation text-yellow-500 mr-2"></i>
                        <span>Próximos a vencer (30 dias)</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-chart-line text-purple-500 mr-2"></i>
                        <span>Baixa rotatividade</span>
                    </li>
                </ul>
                <a href="{{ route('reports.stock') }}" class="block text-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    Gerar Relatório
                </a>
                <a href="{{ route('reports.stock', ['pdf' => 1]) }}" class="mt-2 block text-center bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                    <i class="fas fa-file-pdf mr-2"></i> Exportar PDF
                </a>
            </div>
        </div>

        <!-- Relatório de Consumo -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-green-600 text-white">
                <h2 class="text-xl font-bold">Relatório de Consumo</h2>
                <p class="text-green-100">Padrões de consumo de medicamentos</p>
            </div>
            <div class="p-6">
                <form action="{{ route('reports.consumption') }}" method="GET" class="mb-4">
                    <label class="block text-gray-700 mb-2">Período:</label>
                    <select name="period" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        <option value="week">Última semana</option>
                        <option value="month" selected>Último mês</option>
                        <option value="year">Último ano</option>
                    </select>
                    <div class="mt-4 grid grid-cols-2 gap-2">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                            Visualizar
                        </button>
                        <button type="submit" name="pdf" value="1" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                            <i class="fas fa-file-pdf mr-1"></i> PDF
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Relatório de Solicitações -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-purple-600 text-white">
                <h2 class="text-xl font-bold">Relatório de Solicitações</h2>
                <p class="text-purple-100">Histórico de solicitações</p>
            </div>
            <div class="p-6">
                <form action="{{ route('reports.requests') }}" method="GET" class="mb-4">
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 mb-1">Status:</label>
                            <select name="status" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                <option value="all">Todos</option>
                                <option value="pending">Pendentes</option>
                                <option value="approved">Aprovados</option>
                                <option value="partial">Parciais</option>
                                <option value="rejected">Rejeitados</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-1">Urgência:</label>
                            <select name="urgency" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                <option value="all">Todas</option>
                                <option value="normal">Normal</option>
                                <option value="urgente">Urgente</option>
                                <option value="muito_urgente">Muito Urgente</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md">
                            Visualizar
                        </button>
                        <button type="submit" name="pdf" value="1" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                            <i class="fas fa-file-pdf mr-1"></i> PDF
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
