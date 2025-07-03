@extends('Template.app')

@section('title', 'Pedido #' . $request->id)

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">Detalhes do Pedido #{{ $request->id }}</h2>
        <div class="flex space-x-2">
            @if($request->status == 'pending')
                @can('approve', $request)
                    <form action="{{ route('requests.approve', $request) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition-colors">
                            <i class="fas fa-check mr-2"></i>Aprovar
                        </button>
                    </form>

                    <button type="button" onclick="document.getElementById('rejectModal').classList.remove('hidden')"
                            class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors">
                        <i class="fas fa-times mr-2"></i>Rejeitar
                    </button>
                @endcan

                @if(auth()->id() == $request->user_id)
                    <a href="{{ route('requests.edit', $request) }}"
                       class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors">
                        <i class="fas fa-edit mr-2"></i>Editar
                    </a>
                @endif
            @endif

            <a href="{{ route('requests.index') }}"
               class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Voltar
            </a>
        </div>
    </div>

    <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Card 1: Informações do Pedido -->
        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="text-lg font-medium text-gray-900">Informações do Pedido</h3>
            <div class="mt-4 space-y-2">
                <p><span class="font-semibold">Medicamento:</span> {{ $request->medicine->name }}</p>
                <p><span class="font-semibold">Quantidade:</span> {{ $request->quantity }} unidades</p>
                <p><span class="font-semibold">Solicitante:</span> {{ $request->user->name }}</p>
                <p><span class="font-semibold">Data:</span> {{ $request->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <!-- Card 2: Status -->
        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="text-lg font-medium text-gray-900">Status</h3>
            <div class="mt-4 flex flex-col items-center">
                <span class="px-4 py-2 inline-flex text-lg leading-5 font-semibold rounded-full
                    {{ $request->status == 'approved' ? 'bg-green-100 text-green-800' :
                       ($request->status == 'rejected' ? 'bg-red-100 text-red-800' :
                       ($request->status == 'partial' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800')) }}">
                    {{ ucfirst($request->status) }}
                </span>

                <div class="mt-4 w-full">
                    <p class="font-semibold">Urgência:</p>
                    <span class="px-2 inline-flex text-sm leading-5 font-semibold rounded-full
                        {{ $request->urgency_level == 'normal' ? 'bg-blue-100 text-blue-800' :
                           ($request->urgency_level == 'urgente' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                        {{ ucfirst(str_replace('_', ' ', $request->urgency_level)) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Card 3: Motivo e Resposta -->
        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="text-lg font-medium text-gray-900">Motivo e Resposta</h3>
            <div class="mt-4 space-y-4">
                <div>
                    <p class="font-semibold">Motivo:</p>
                    <p class="text-gray-700">{{ $request->reason }}</p>
                </div>
                @if($request->status !== 'pending')
                <div>
                    <p class="font-semibold">Resposta:</p>
                    <p class="text-gray-700">{{ $request->response }}</p>
                    <p class="text-sm text-gray-500 mt-1">Respondido por: {{ $request->responder->name ?? 'N/A' }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($request->status !== 'pending' && $request->movement)
<h3 class="text-xl font-bold text-gray-800 mb-4">Movimentação Relacionada</h3>
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantidade</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Responsável</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Motivo</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $request->movement->movement_date->format('d/m/Y H:i') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                        {{ $request->movement->type === 'entrada' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $request->movement->type === 'entrada' ? 'Entrada' : 'Saída' }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ $request->movement->quantity }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $request->movement->user->name }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">
                    {{ $request->movement->reason }}
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endif

<!-- Modal de Rejeição -->
<div id="rejectModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Rejeitar Pedido</h3>
                <div class="mt-4">
                    <form id="rejectForm" action="{{ route('requests.reject', $request) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="response" class="block text-sm font-medium text-gray-700">Motivo da Rejeição *</label>
                            <textarea name="response" id="response" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required></textarea>
                        </div>
                    </form>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="submit" form="rejectForm" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Confirmar Rejeição
                </button>
                <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Fechar modal ao pressionar ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            document.getElementById('rejectModal').classList.add('hidden');
        }
    });

    // Fechar modal ao clicar fora
    document.getElementById('rejectModal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });
</script>
@endsection
