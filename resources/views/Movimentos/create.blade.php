@extends('Template.app')

@section('title', $type === 'entrada' ? 'Registrar Entrada' : 'Registrar Saída')

@section('content')
<div class="max-w-md mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-center mb-6">
            <div class="w-16 h-16 rounded-full flex items-center justify-center
                {{ $type === 'entrada' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                <i class="fas {{ $type === 'entrada' ? 'fa-arrow-down' : 'fa-arrow-up' }} text-2xl"></i>
            </div>
        </div>

        <h2 class="text-xl font-bold text-center mb-6">
            {{ $type === 'entrada' ? 'Registrar Entrada de Estoque' : 'Registrar Saída de Estoque' }}
        </h2>

        <p class="text-center mb-6">
            Medicamento: <span class="font-semibold">{{ $medicine->name }}</span><br>
            Estoque atual: <span class="font-semibold">{{ $medicine->stock }} unidades</span>
        </p>

        <form action="{{ route('movements.store', $medicine) }}" method="POST">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">

            <div class="mb-4">
                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">
                    Quantidade *
                </label>
                <input type="number" id="quantity" name="quantity" min="1"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                       required>
                @error('quantity')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="movement_date" class="block text-sm font-medium text-gray-700 mb-1">
                    Data da Movimentação *
                </label>
                <input type="datetime-local" id="movement_date" name="movement_date"
                       value="{{ old('movement_date', now()->format('Y-m-d\TH:i')) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                       required>
                @error('movement_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">
                    Motivo *
                </label>
                <textarea id="reason" name="reason" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                          required></textarea>
                @error('reason')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <a href="{{ route('medicines.show', $medicine) }}"
                   class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left mr-2"></i> Cancelar
                </a>
                <button type="submit"
                        class="{{ $type === 'entrada' ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700' }} text-white px-4 py-2 rounded-md">
                    {{ $type === 'entrada' ? 'Registrar Entrada' : 'Registrar Saída' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
