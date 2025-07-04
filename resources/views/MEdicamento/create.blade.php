@extends('Template.app')

@section('title', 'Cadastrar Medicamento')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 bg-gray-100 border-b">
            <h2 class="text-xl font-bold text-gray-800">Cadastrar Novo Medicamento</h2>
        </div>

        <form action="{{ route('medicines.store') }}" method="POST">
            @csrf
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nome do Medicamento *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700">Categoria *</label>
                        <input type="text" name="category" id="category" value="{{ old('category') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        @error('category') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="batch" class="block text-sm font-medium text-gray-700">Lote *</label>
                        <input type="text" name="batch" id="batch" value="{{ old('batch') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        @error('batch') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="expiration_date" class="block text-sm font-medium text-gray-700">Data de Validade *</label>
                        <input type="date" name="expiration_date" id="expiration_date" value="{{ old('expiration_date') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        @error('expiration_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="stock" class="block text-sm font-medium text-gray-700">Estoque Inicial *</label>
                        <input type="number" name="stock" id="stock" value="{{ old('stock', 0) }}" min="0"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        @error('stock') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="minimum_stock" class="block text-sm font-medium text-gray-700">Estoque Mínimo *</label>
                        <input type="number" name="minimum_stock" id="minimum_stock" value="{{ old('minimum_stock', 1) }}" min="1"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        @error('minimum_stock') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                 
                </div>

                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700">Descrição</label>
                    <textarea name="description" id="description" rows="3"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                    @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-100 border-t flex justify-end">
                <button type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md mr-2"
                        onclick="window.location='{{ route('medicines.index')}}'">
                    Cancelar
                </button>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">
                    Cadastrar Medicamento
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
