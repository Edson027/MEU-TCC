@extends('Template.app')

@section('title', 'Editar Pedido #' . $request->id)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 bg-gray-100 border-b">
            <h2 class="text-xl font-bold text-gray-800">Editar Pedido #{{ $request->id }}</h2>
            @if($request->status !== 'pending')
                <div class="mt-2 text-sm text-red-600">
                    Não é possível editar um pedido já respondido.
                </div>
            @endif
        </div>

        @if($request->status === 'pending')
        <form action="{{ route('requests.update', $request) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="medicine_id" class="block text-sm font-medium text-gray-700">Medicamento *</label>
                        <select name="medicine_id" id="medicine_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="">Selecione um medicamento</option>
                            @foreach($medicines as $medicine)
                                <option value="{{ $medicine->id }}" {{ old('medicine_id', $request->medicine_id) == $medicine->id ? 'selected' : '' }}>
                                    {{ $medicine->name }} (Estoque: {{ $medicine->stock }} unid.)
                                </option>
                            @endforeach
                        </select>
                        @error('medicine_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700">Quantidade *</label>
                        <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $request->quantity) }}" min="1"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        @error('quantity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nível de Urgência *</label>
                        <div class="mt-2 space-y-2">
                            <div class="flex items-center">
                                <input id="normal" name="urgency_level" type="radio" value="normal"
                                       class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300"
                                       {{ old('urgency_level', $request->urgency_level) == 'normal' ? 'checked' : '' }} required>
                                <label for="normal" class="ml-2 block text-sm text-gray-700">Normal</label>
                            </div>
                            <div class="flex items-center">
                                <input id="urgente" name="urgency_level" type="radio" value="urgente"
                                       class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300"
                                       {{ old('urgency_level', $request->urgency_level) == 'urgente' ? 'checked' : '' }}>
                                <label for="urgente" class="ml-2 block text-sm text-gray-700">Urgente</label>
                            </div>
                            <div class="flex items-center">
                                <input id="muito_urgente" name="urgency_level" type="radio" value="muito_urgente"
                                       class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300"
                                       {{ old('urgency_level', $request->urgency_level) == 'muito_urgente' ? 'checked' : '' }}>
                                <label for="muito_urgente" class="ml-2 block text-sm text-gray-700">Muito Urgente</label>
                            </div>
                        </div>
                        @error('urgency_level') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label for="reason" class="block text-sm font-medium text-gray-700">Motivo do Pedido *</label>
                    <textarea name="reason" id="reason" rows="4"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('reason', $request->reason) }}</textarea>
                    @error('reason') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-100 border-t flex justify-end">
                <button type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md mr-2"
                        onclick="window.location='{{ route('requests.show', $request) }}'">
                    Cancelar
                </button>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">
                    Atualizar Pedido
                </button>
            </div>
        </form>
        @else
        <div class="p-6">
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            Este pedido já foi respondido e não pode mais ser editado.
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md"
                        onclick="window.location='{{ route('requests.show', $request) }}'">
                    Voltar para o pedido
                </button>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
