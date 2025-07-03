@extends('Template.app')

@section('title', 'Pedidos')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Gestão de Pedidos</h1>
    <a href="{{ route('requests.create') }}"class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">
        <i class="fas fa-plus mr-1"></i> Novo Pedido
    </a>
 <a href="{{ route('requests.pedido') }}"class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md"">
    <i class="fas fa-plus mr-1"></i> Estado dos pedido</a>
</div>


<!-- Filtros -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" action="{{ route('requests.index') }}">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Todos</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendente</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Aprovado</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejeitado</option>
                    <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Parcial</option>
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
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantidade</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Solicitante</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Urgência</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($requests as $request)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $request->id }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $request->medicine->name }}</div>
                    <div class="text-sm text-gray-500">{{ $request->medicine->category }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $request->quantity }} unid.
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $request->user->name }}</div>
                    <div class="text-sm text-gray-500">{{ $request->created_at->format('d/m/Y H:i') }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                        {{ $request->status == 'approved' ? 'bg-green-100 text-green-800' :
                           ($request->status == 'rejected' ? 'bg-red-100 text-red-800' :
                           ($request->status == 'partial' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800')) }}">
                        {{ ucfirst($request->status) }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                        {{ $request->urgency_level == 'normal' ? 'bg-blue-100 text-blue-800' :
                           ($request->urgency_level == 'urgente' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                        {{ ucfirst(str_replace('_', ' ', $request->urgency_level)) }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $request->created_at->format('d/m/Y') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <a href="{{ route('requests.show', $request) }}" class="text-indigo-600 hover:text-indigo-900 mr-3" title="Ver detalhes">
                        <i class="fas fa-eye"></i>
                    </a>
                    @if($request->status == 'pending' && auth()->id() == $request->user_id)
                        <a href="{{ route('requests.edit', $request) }}" class="text-yellow-600 hover:text-yellow-900 mr-3" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Paginação -->
    <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6">
        {{ $requests->links() }}
    </div>
</div>
@endsection
