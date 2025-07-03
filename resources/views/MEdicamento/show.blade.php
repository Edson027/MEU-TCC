<!-- resources/views/medicines/show.blade.php -->
@extends('Template.app')

@section('title', $medicine->name)

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">Detalhes do Medicamento</h2>
        <div>
            <a href="{{ route('movements.create', ['medicine' => $medicine->id, 'type' => 'entrada']) }}"
               class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 mr-2">
                <i class="fas fa-arrow-down mr-2"></i>Entrada
            </a>
            <a href="{{ route('movements.create', ['medicine' => $medicine->id, 'type' => 'saida']) }}"
               class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">
                <i class="fas fa-arrow-up mr-2"></i>Saída
            </a>
        </div>
    </div>

    <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="text-lg font-medium text-gray-900">Informações Básicas</h3>
            <div class="mt-4 space-y-2">
                <p><span class="font-semibold">Nome:</span> {{ $medicine->name }}</p>
                <p><span class="font-semibold">Lote:</span> {{ $medicine->batch }}</p>
                <p><span class="font-semibold">Validade:</span> {{ \Carbon\Carbon::parse($medicine->expiration_date)->format('d/m/Y') }}</p>
                <p><span class="font-semibold">Preço:</span> R$ {{ number_format($medicine->price, 2, ',', '.') }}</p>
            </div>
        </div>

        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="text-lg font-medium text-gray-900">Estoque</h3>
            <div class="mt-4 flex items-center justify-center">
                <div class="relative w-32 h-32">
                    <svg class="w-full h-full" viewBox="0 0 36 36">
                        <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                              fill="none"
                              stroke="#eee"
                              stroke-width="3" />
                        <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                              fill="none"
                              stroke="{{ $medicine->stock > 50 ? '#10B981' : ($medicine->stock > 10 ? '#FBBF24' : '#EF4444') }}"
                              stroke-width="3"
                              stroke-dasharray="{{ $medicine->stock }}, 100" />
                        <text x="18" y="20.5" text-anchor="middle" fill="#4B5563" font-size="8">{{ $medicine->stock }}%</text>
                    </svg>
                </div>
            </div>
            <p class="text-center mt-2 font-semibold">{{ $medicine->stock }} unidades disponíveis</p>
        </div>

        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="text-lg font-medium text-gray-900">Descrição</h3>
            <p class="mt-4 text-gray-700">{{ $medicine->description ?? 'Nenhuma descrição disponível' }}</p>
        </div>
    </div>
</div>

<h3 class="text-xl font-bold text-gray-800 mb-4">Histórico de Movimentações</h3>
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
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($movements as $movement)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ \Carbon\Carbon::parse($movement->movement_date)->format('d/m/Y H:i') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                        {{ $movement->type === 'entrada' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $movement->type === 'entrada' ? 'Entrada' : 'Saída' }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ $movement->quantity }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $movement->user->name }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">
                    {{ $movement->reason }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="px-4 py-3 bg-gray-50 sm:px-6">
        {{ $movements->links() }}
    </div>
</div>
@endsection
