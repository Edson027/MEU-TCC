@extends('layouts.admin')

@section('title', 'Movimentações de Estoque')

@push('styles')
<style>
    .badge-entrada {
        background-color: #28a745;
    }
    .badge-saida {
        background-color: #dc3545;
    }
    .table-actions {
        white-space: nowrap;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Cabeçalho -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-exchange-alt"></i> Movimentações de Estoque
        </h1>
        <div>
            <a href="{{ route('medicines.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm mr-2">
                <i class="fas fa-pills fa-sm text-white-50"></i> Ver Medicamentos
            </a>
            <a href="{{ route('movements.export', request()->query()) }}" 
               class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm">
                <i class="fas fa-file-export fa-sm text-white-50"></i> Exportar
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter"></i> Filtros
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('movements.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="search" class="form-label">Pesquisa</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ $search }}" placeholder="Medicamento, motivo ou usuário...">
                    </div>
                    <div class="col-md-2">
                        <label for="type" class="form-label">Tipo</label>
                        <select class="form-select" id="type" name="type">
                            <option value="">Todos</option>
                            @foreach($types as $key => $value)
                                <option value="{{ $key }}" {{ $type == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="medicine_id" class="form-label">Medicamento</label>
                        <select class="form-select" id="medicine_id" name="medicine_id">
                            <option value="">Todos</option>
                            @foreach($medicines as $medicine)
                                <option value="{{ $medicine->id }}" {{ $medicineId == $medicine->id ? 'selected' : '' }}>
                                    {{ $medicine->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="start_date" class="form-label">Data Início</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" 
                               value="{{ $startDate }}">
                    </div>
                    <div class="col-md-2">
                        <label for="end_date" class="form-label">Data Fim</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" 
                               value="{{ $endDate }}">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabela de Movimentações -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list"></i> Lista de Movimentações
            </h6>
            <span class="badge badge-primary">Total: {{ $movements->total() }}</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>Data</th>
                            <th>Medicamento</th>
                            <th>Tipo</th>
                            <th>Quantidade</th>
                            <th>Motivo</th>
                            <th>Usuário</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movements as $movement)
                            <tr>
                                <td>{{ $movement->movement_date->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('medicines.show', $movement->medicine) }}" class="text-primary">
                                        {{ $movement->medicine->name }}
                                    </a>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $movement->type === 'entrada' ? 'success' : 'danger' }}">
                                        {{ $types[$movement->type] }}
                                    </span>
                                </td>
                                <td>
                                    <span class="font-weight-bold {{ $movement->type === 'entrada' ? 'text-success' : 'text-danger' }}">
                                        {{ $movement->type === 'entrada' ? '+' : '-' }}{{ $movement->quantity }}
                                    </span>
                                </td>
                                <td>{{ Str::limit($movement->reason, 50) }}</td>
                                <td>{{ $movement->user->name }}</td>
                                <td class="table-actions">
                                    <a href="{{ route('movements.show', $movement) }}" 
                                       class="btn btn-info btn-sm" title="Detalhes">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-2x mb-3"></i>
                                    <p>Nenhuma movimentação encontrada</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            <div class="d-flex justify-content-center mt-4">
                {{ $movements->links() }}
            </div>
        </div>
    </div>
</div>
@endsection