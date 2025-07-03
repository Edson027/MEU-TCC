@extends('Template.app')

@section('title', 'Solicitações')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Solicitações de Medicamentos</h1>
    <a href="{{ route('requests.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">
        <i class="fas fa-plus mr-1"></i> Nova Solicitação
    </a>
</div>

<!-- Filtros -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" action="{{ route('requests.index') }}">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Todos</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendentes</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Aprovados</option>
                    <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Parciais</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejeitados</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Urgência</label>
                <select name="urgency" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="all" {{ request('urgency') == 'all' ? 'selected' : '' }}>Todas</option>
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

<!-- Lista de Solicitações -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Medicamento</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Solicitante</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantidade</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Urgência</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
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
                    <div class="text-sm text-gray-500">Lote: {{ $request->medicine->batch }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $request->user->name }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $request->quantity }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                        bg-{{ $request->urgency_color }}-100 text-{{ $request->urgency_color }}-800">
                        {{ ucfirst(str_replace('_', ' ', $request->urgency_level)) }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                        bg-{{ $request->status_color }}-100 text-{{ $request->status_color }}-800">
                        @if($request->status == 'pending')
                            Pendente
                        @elseif($request->status == 'approved')
                            Aprovado
                        @elseif($request->status == 'partial')
                            Parcial
                        @else
                            Rejeitado
                        @endif
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <a href="{{ route('requests.show', $request) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">
                        <i class="fas fa-eye"></i>
                    </a>
                    @can('approve', $request)
                        @if($request->status == 'pending')
                            <button onclick="approveRequest({{ $request->id }})" class="text-green-600 hover:text-green-900 mr-2">
                                <i class="fas fa-check"></i>
                            </button>
                            <button onclick="showRejectModal({{ $request->id }})" class="text-red-600 hover:text-red-900">
                                <i class="fas fa-times"></i>
                            </button>
                        @endif
                    @endcan


                </td>

            </tr>

            @endforeach

        </tbody>
    </table>

    <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6">
        {{ $requests->links() }}
    </div>
</div>

<!-- Modal de Rejeição -->
<div id="rejectModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="rejectForm" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                        Rejeitar Solicitação
                    </h3>
                    <div class="mb-4">
                        <label for="response" class="block text-sm font-medium text-gray-700">Motivo da Rejeição *</label>
                        <textarea name="response" id="response" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required></textarea>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Confirmar Rejeição
                    </button>
                    <button type="button" onclick="hideRejectModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
    function approveRequest(requestId) {
        if (confirm('Deseja aprovar esta solicitação?')) {
            fetch(`/requests/${requestId}/approve`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({})
            }).then(response => {
                if (response.ok) {
                    window.location.reload();
                }
            });
        }
    }

    function showRejectModal(requestId) {
        document.getElementById('rejectForm').action = `/requests/${requestId}/reject`;
        document.getElementById('rejectModal').classList.remove('hidden');
    }

    function hideRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }
</script>
@endsection
