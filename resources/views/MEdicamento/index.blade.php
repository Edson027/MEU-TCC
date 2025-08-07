@extends('layouts.admin')

@section('title', 'Medicamentos')

@push('styles')
<style>
    .table-responsive {
        overflow-x: auto;
    }
    .table {
        width: 100%;
        margin-bottom: 1rem;
        color: #858796;
    }
    .table th {
        padding: 0.75rem;
        vertical-align: top;
        border-top: 1px solid #e3e6f0;
    }
    .table thead th {
        vertical-align: bottom;
        border-bottom: 2px solid #e3e6f0;
    }
    .table td {
        padding: 0.75rem;
        vertical-align: top;
        border-top: 1px solid #e3e6f0;
    }
    .badge {
        display: inline-block;
        padding: 0.25em 0.4em;
        font-size: 75%;
        font-weight: 700;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0.35rem;
    }
    .badge-danger {
        color: #fff;
        background-color: #e74a3b;
    }
    .badge-warning {
        color: #1f2d3d;
        background-color: #f6c23e;
    }
    .badge-success {
        color: #fff;
        background-color: #1cc88a;
    }
    .action-link {
        margin-right: 0.5rem;
    }
    .action-link:last-child {
        margin-right: 0;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Cabeçalho -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-pills"></i> Gestão de Medicamentos
        </h1>
        <a href="{{ route('medicines.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Novo Medicamento
        </a>
    </div>

    <!-- Filtros -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-gray-100">
            <h6 class="m-0 font-weight-bold text-primary">Filtros</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('medicines.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Pesquisar</label>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   class="form-control" placeholder="Pesquise por lotes, categorias e nomes">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Categoria</label>
                            <select name="category" class="form-control">
                                <option value="">Todas</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                        {{ $category }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Estoque</label>
                            <select name="stock_status" class="form-control">
                                <option value="">Todos</option>
                                <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Crítico</option>
                                <option value="normal" {{ request('stock_status') == 'normal' ? 'selected' : '' }}>Normal</option>

                               <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Esgotado</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Validade</label>
                            <select name="expiration" class="form-control">
                                <option value="">Todas</option>
                                <option value="soon" {{ request('expiration') == 'soon' ? 'selected' : '' }}>Próximos a vencer</option>
                                <option value="expired" {{ request('expiration') == 'expired' ? 'selected' : '' }}>Vencidos</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter"></i> Filtrar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabela de Medicamentos -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-gray-100">
            <h6 class="m-0 font-weight-bold text-primary">Lista de Medicamentos</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Lote</th>
                            <th>Validade</th>
                            <th>Estoque</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($medicines as $medicine)
                        <tr>
                            <td>
                                <div class="font-weight-bold">{{ $medicine->name }}</div>
                                <div class="text-muted small">{{ $medicine->category }}</div>
                            </td>
                            <td>{{ $medicine->batch }}</td>
                            <td>
                                {{ $medicine->expiration_date }}
                                <div class="small {{
                                    $medicine->expiration_status == 'Vencido' ? 'text-danger' :
                                    ($medicine->expiration_status == 'Próximo a vencer' ? 'text-warning' : 'text-success')
                                }}">
                                    {{ $medicine->expiration_status }}
                                </div>
                            </td>
                            <td>{{ $medicine->stock }} unid.</td>
                            <td>
                                <span class="badge 
                                    {{ $medicine->stock_status == 'Esgotado' ? 'badge-danger' :
                                       ($medicine->stock_status == 'Crítico' ? 'badge-danger' :
                                       ($medicine->stock_status == 'Atenção' ? 'badge-warning' : 'badge-success')) }}">
                                    {{ $medicine->stock_status }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('medicines.show', $medicine) }}" class="action-link text-primary" title="Ver detalhes">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('medicines.edit', $medicine) }}" class="action-link text-warning" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('medicines.history', $medicine) }}" class="action-link text-info" title="Histórico">
                                    <i class="fas fa-history"></i>
                                </a>
                                <form action="{{ route('medicines.destroy', $medicine) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-link text-danger bg-transparent border-0" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir este medicamento?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Paginação -->
            <div class="d-flex justify-content-center mt-3">
                {{ $medicines->links() }}
            </div>
        </div>
    </div>
</div>
@endsection