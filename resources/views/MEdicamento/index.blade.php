@extends('Template.app')

@section('title', 'Medicamentos')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Gestão de Medicamentos</h1>
    <a href="{{ route('medicines.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">
        <i class="fas fa-plus mr-1"></i> Novo Medicamento
    </a>
</div>

<!-- Filtros -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" action="{{ route('medicines.index') }}">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Pesquisar</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Categoria</label>
                <select name="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Todas</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                            {{ $category }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Estoque</label>
                <select name="stock_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Todos</option>
                    <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Crítico</option>
                    <option value="normal" {{ request('stock_status') == 'normal' ? 'selected' : '' }}>Normal</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Validade</label>
                <select name="expiration" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Todas</option>
                    <option value="soon" {{ request('expiration') == 'soon' ? 'selected' : '' }}>Próximos a vencer</option>
                    <option value="expired" {{ request('expiration') == 'expired' ? 'selected' : '' }}>Vencidos</option>
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

<!-- Lista de Medicamentos -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lote</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Validade</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estoque</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($medicines as $medicine)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $medicine->name }}</div>
                    <div class="text-sm text-gray-500">{{ $medicine->category }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $medicine->batch }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $medicine->expiration_date }}
                    <span class="block text-xs {{
                        $medicine->expiration_status == 'Vencido' ? 'text-red-600' :
                        ($medicine->expiration_status == 'Próximo a vencer' ? 'text-yellow-600' : 'text-green-600')
                    }}">
                        {{ $medicine->expiration_status }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $medicine->stock }} unid.
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                        {{ $medicine->stock_status == 'Esgotado' ? 'bg-red-100 text-red-800' :
                           ($medicine->stock_status == 'Crítico' ? 'bg-red-100 text-red-800' :
                           ($medicine->stock_status == 'Atenção' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800')) }}">
                        {{ $medicine->stock_status }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <a href="{{ route('medicines.show', $medicine) }}" class="text-indigo-600 hover:text-indigo-900 mr-3" title="Ver detalhes">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('medicines.edit', $medicine) }}" class="text-yellow-600 hover:text-yellow-900 mr-3" title="Editar">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="{{ route('medicines.history', $medicine) }}" class="text-blue-600 hover:text-blue-900 mr-3" title="Histórico">
                        <i class="fas fa-history"></i>
                    </a>
                    <form action="{{ route('medicines.destroy', $medicine) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir este medicamento?')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Paginação -->
    <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6">
        {{ $medicines->links() }}
    </div>
</div>
@endsection
