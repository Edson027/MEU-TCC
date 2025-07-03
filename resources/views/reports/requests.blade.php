@extends('Template.app')

@section('title', 'Relatório de Solicitações')

@section('content')
<div class="container mx-auto py-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Relatório de Solicitações</h2>

        <div class="flex space-x-2">
            <!-- Filtro de Status -->
            <select id="statusFilter" class="rounded border-gray-300">
                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Todos Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendentes</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Aprovadas</option>
                <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Parciais</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejeitadas</option>
            </select>

            <!-- Filtro de Urgência -->
            <select id="urgencyFilter" class="rounded border-gray-300">
                <option value="all" {{ request('urgency') == 'all' ? 'selected' : '' }}>Todas Urgências</option>
                <option value="normal" {{ request('urgency') == 'normal' ? 'selected' : '' }}>Normal</option>
                <option value="urgente" {{ request('urgency') == 'urgente' ? 'selected' : '' }}>Urgente</option>
                <option value="muito_urgente" {{ request('urgency') == 'muito_urgente' ? 'selected' : '' }}>Muito Urgente</option>
            </select>

            <a href="{{ route('reports.requests', array_merge(request()->all(), ['pdf' => 1])) }}"
               class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                <i class="fas fa-file-pdf mr-1"></i> PDF
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Medicamento</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Solicitante</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantidade</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Urgência</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
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
                            <div class="text-sm text-gray-500">{{ $request->medicine->batch }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $request->user->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $request->quantity }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($request->urgency_level == 'urgente')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Urgente
                                </span>
                            @elseif($request->urgency_level == 'muito_urgente')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Muito Urgente
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Normal
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($request->status === 'pending')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Pendente
                                </span>
                            @elseif($request->status === 'approved')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Aprovado
                                </span>
                            @elseif($request->status === 'partial')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Parcial
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Rejeitado
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3 bg-gray-50 border-t sm:px-6">
            {{ $requests->links() }}
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusFilter = document.getElementById('statusFilter');
    const urgencyFilter = document.getElementById('urgencyFilter');

    function applyFilters() {
        const status = statusFilter.value;
        const urgency = urgencyFilter.value;

        let url = new URL(window.location.href);
        url.searchParams.set('status', status);
        url.searchParams.set('urgency', urgency);

        window.location.href = url.toString();
    }

    statusFilter.addEventListener('change', applyFilters);
    urgencyFilter.addEventListener('change', applyFilters);
});
</script>
@endsection
