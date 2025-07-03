@extends('Template.app')

@section('title', 'Relatório de Solicitações')

@section('content')
<div class="container mx-auto py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Relatório de Solicitações</h1>
        <div>
            <a href="{{ route('reports.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md mr-2">
                <i class="fas fa-arrow-left mr-1"></i> Voltar
            </a>
            <a href="{{ route('reports.requests', array_merge(request()->all(), ['pdf' => 1])) }}"
               class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                <i class="fas fa-file-pdf mr-1"></i> Exportar PDF
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-lg p-4 mb-6">
        <form method="GET" action="{{ route('reports.requests') }}">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="all" {{ request('status', 'all') == 'all' ? 'selected' : '' }}>Todos</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendentes</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Aprovados</option>
                        <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Parciais</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejeitados</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Urgência</label>
                    <select name="urgency" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="all" {{ request('urgency', 'all') == 'all' ? 'selected' : '' }}>Todas</option>
                        <option value="normal" {{ request('urgency') == 'normal' ? 'selected' : '' }}>Normal</option>
                        <option value="urgente" {{ request('urgency') == 'urgente' ? 'selected' : '' }}>Urgente</option>
                        <option value="muito_urgente" {{ request('urgency') == 'muito_urgente' ? 'selected' : '' }}>Muito Urgente</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">
                        <i class="fas fa-filter mr-1"></i> Filtrar
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Resultados -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gray-100 border-b">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">Resultados</h2>
                <span class="text-sm text-gray-600">
                    {{ $requests->total() }} solicitações encontradas
                </span>
            </div>
        </div>
        <div class="p-6">
            @if($requests->isEmpty())
                <p class="text-gray-500">Nenhuma solicitação encontrada com os filtros aplicados.</p>
            @else
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Medicamento</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Solicitante</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantidade</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($requests as $request)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $request->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $request->medicine->name }}</div>
                                <div class="text-sm text-gray-500">{{ $request->medicine->category }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $request->user->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $request->quantity }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($request->status === 'pending')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Pendente
                                    </span>
                                @elseif($request->status === 'approved')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Aprovado
                                    </span>
                                @elseif($request->status === 'partial')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Parcial
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Rejeitado
                                    </span>
                                @endif
                                @if($request->urgency_level !== 'normal')
                                    <span class="ml-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $request->urgency_level === 'muito_urgente' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $request->urgency_level === 'muito_urgente' ? 'Muito Urgente' : 'Urgente' }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $requests->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
