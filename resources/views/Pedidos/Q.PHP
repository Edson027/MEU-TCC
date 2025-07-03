@extends('Template.app')

@section('title', 'Detalhes da Solicitação')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 bg-gray-100 border-b flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-800">Detalhes da Solicitação</h2>
            <span class="px-3 py-1 rounded-full text-sm font-semibold
                @switch($request->status)
                    @case('pending') bg-yellow-100 text-yellow-800 @break
                    @case('approved') bg-green-100 text-green-800 @break
                    @case('rejected') bg-red-100 text-red-800 @break
                    @case('partial') bg-blue-100 text-blue-800 @break
                @endswitch">
                {{ ucfirst($request->status) }}
            </span>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Medicamento</h3>
                    <p class="mt-1 text-sm text-gray-900">{{ $request->medicine->name }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500">Quantidade Solicitada</h3>
                    <p class="mt-1 text-sm text-gray-900">{{ $request->quantity }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500">Solicitante</h3>
                    <p class="mt-1 text-sm text-gray-900">{{ $request->user->name }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500">Nível de Urgência</h3>
                    <p class="mt-1 text-sm text-gray-900">
                        <span class="px-2 py-1 rounded-full text-xs font-medium
                            @switch($request->urgency_level)
                                @case('normal') bg-blue-100 text-blue-800 @break
                                @case('urgente') bg-yellow-100 text-yellow-800 @break
                                @case('muito_urgente') bg-red-100 text-red-800 @break
                            @endswitch">
                            {{ ucfirst(str_replace('_', ' ', $request->urgency_level)) }}
                        </span>
                    </p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500">Data da Solicitação</h3>
                    <p class="mt-1 text-sm text-gray-900">{{ $request->created_at->format('d/m/Y H:i') }}</p>
                </div>

                @if($request->status != 'pending')
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Data da Resposta</h3>
                    <p class="mt-1 text-sm text-gray-900">{{ $request->updated_at->format('d/m/Y H:i') }}</p>
                </div>
                @endif
            </div>

            <div class="mb-6">
                <h3 class="text-sm font-medium text-gray-500">Motivo da Solicitação</h3>
                <p class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $request->reason }}</p>
            </div>

            @if($request->status != 'pending')
            <div class="mb-6">
                <h3 class="text-sm font-medium text-gray-500">Resposta</h3>
                <p class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $request->response }}</p>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-500">Responsável pela Resposta</h3>
                <p class="mt-1 text-sm text-gray-900">{{ $request->responder->name ?? 'N/A' }}</p>
            </div>
            @endif
        </div>

      <!-- <div class="px-6 py-4 bg-gray-100 border-t flex justify-between">
            <a href="{{ route('requests.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md">
                Voltar
            </a>

            @can('approve', $request)
                @if($request->status == 'pending')
                <div class="space-x-2">
                    <form action="{{ route('requests.approve', $request->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                            Aprovar
                        </button>
                    </form>

                    <button type="button" onclick="openRejectModal()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                        Rejeitar
                    </button>
                </div>
                @endif
            @endcan
        </div>-->

        <div class="px-6 py-4 bg-gray-100 border-t flex justify-between">
    <a href="{{ route('requests.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md">
        Voltar
    </a>

    <div class="space-x-2">
        @if($request->status == 'pending')
           <a href="{{ route('requests.edit', $request) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md">
    Editar
</a>
        @endif

        @can('approve', $request)
            @if($request->status == 'pending')
                <!-- Botões de aprovar/rejeitar -->
            @endif
        @endcan
    </div>
</div>
    </div>
</div>

<!-- Modal para rejeição -->
@can('approve', $request)
@if($request->status == 'pending')
<div id="rejectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Rejeitar Solicitação</h3>
            <form action="{{ route('requests.reject', $request->id) }}" method="POST" class="mt-4">
                @csrf
                <div class="mb-4">
                    <label for="response" class="block text-sm font-medium text-gray-700 text-left">Motivo da rejeição *</label>
                    <textarea name="response" id="response" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required></textarea>
                </div>
                <div class="flex justify-end space-x-3 mt-4">
                    <button type="button" onclick="closeRejectModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                        Confirmar Rejeição
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openRejectModal() {
        document.getElementById('rejectModal').classList.remove('hidden');
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }
</script>
@endif
@endcan
@endsection
