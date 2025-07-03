@extends('Template.app')

@section('title', 'Status dos Pedidos')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Gestão de Pedidos</h1>
    <a href="{{ route('requests.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">
        <i class="fas fa-plus mr-1"></i> Novo Pedido
    </a>
</div>

<!-- Filtros -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" action="{{ route('requests.index') }}">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Todos</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendentes</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Aprovados</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejeitados</option>
                    <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Parciais</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Urgência</label>
                <select name="urgency" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Todas</option>
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

<!-- Lista de Pedidos -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Medicamento</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Solicitante</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantidade</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Urgência</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($requests as $request)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $request->id }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $request->medicine->name }}</div>
                    <div class="text-sm text-gray-500">Estoque: {{ $request->medicine->stock }} unid.</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $request->user->name }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $request->quantity }} unid.
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @switch($request->urgency_level)
                        @case('normal')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                Normal
                            </span>
                            @break
                        @case('urgente')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Urgente
                            </span>
                            @break
                        @case('muito_urgente')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Muito Urgente
                            </span>
                            @break
                    @endswitch
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @switch($request->status)
                        @case('pending')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                Pendente
                            </span>
                            @break
                        @case('approved')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Aprovado
                            </span>
                            @break
                        @case('rejected')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Rejeitado
                            </span>
                            @break
                        @case('partial')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Parcial
                            </span>
                            @break
                    @endswitch
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $request->created_at->format('d/m/Y H:i') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <a href="{{ route('requests.show', $request) }}" class="text-indigo-600 hover:text-indigo-900 mr-3" title="Ver detalhes">
                        <i class="fas fa-eye"></i>
                    </a>

                    @if($request->status == 'pending' && auth()->user()->can('approve', $request))
                        <!-- Botão de Aprovar com modal -->
                        <button type="button" class="text-green-600 hover:text-green-900 mr-3" title="Aprovar" data-bs-toggle="modal" data-bs-target="#approveModal{{ $request->id }}">
                            <i class="fas fa-check"></i>
                        </button>

                        <!-- Botão de Rejeitar com modal -->
                        <button type="button" class="text-red-600 hover:text-red-900 mr-3" title="Rejeitar" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $request->id }}">
                            <i class="fas fa-times"></i>
                        </button>

                        <!-- Modal de Aprovação -->
                        <div class="modal fade" id="approveModal{{ $request->id }}" tabindex="-1" aria-labelledby="approveModalLabel{{ $request->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('requests.approve', $request) }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="approveModalLabel{{ $request->id }}">Aprovar Pedido #{{ $request->id }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="mb-4">Você está prestes a aprovar o pedido de <strong>{{ $request->quantity }}</strong> unidades de <strong>{{ $request->medicine->name }}</strong>.</p>
                                            <p class="mb-4">Estoque atual: <strong>{{ $request->medicine->stock }}</strong> unidades.</p>

                                            <div class="mb-3">
                                                <label for="response{{ $request->id }}" class="block text-sm font-medium text-gray-700">Observação (opcional):</label>
                                                <textarea class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" id="response{{ $request->id }}" name="response" rows="3" placeholder="Adicione uma observação se necessário"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">Confirmar Aprovação</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Modal de Rejeição -->
                        <div class="modal fade" id="rejectModal{{ $request->id }}" tabindex="-1" aria-labelledby="rejectModalLabel{{ $request->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('requests.reject', $request) }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="rejectModalLabel{{ $request->id }}">Rejeitar Pedido #{{ $request->id }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="mb-4">Você está prestes a rejeitar o pedido de <strong>{{ $request->quantity }}</strong> unidades de <strong>{{ $request->medicine->name }}</strong>.</p>

                                            <div class="mb-3">
                                                <label for="response_reject{{ $request->id }}" class="block text-sm font-medium text-gray-700">Motivo da rejeição (obrigatório):</label>
                                                <textarea class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" id="response_reject{{ $request->id }}" name="response" rows="3" required placeholder="Informe o motivo da rejeição"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">Confirmar Rejeição</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                    Nenhum pedido encontrado.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Paginação -->
    <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6">
        {{ $requests->links() }}
    </div>
</div>
@endsection
