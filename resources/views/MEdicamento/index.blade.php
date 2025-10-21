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
        cursor: pointer;
    }
    .table th:hover {
        background-color: #f8f9fc;
    }
    .table th.active {
        background-color: #eaecf4;
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
    .badge-info {
        color: #fff;
        background-color: #36b9cc;
    }
    .action-link {
        margin-right: 0.5rem;
    }
    .action-link:last-child {
        margin-right: 0;
    }
    .summary-card {
        border-left: 4px solid;
    }
    .summary-card.total {
        border-left-color: #4e73df;
    }
    .summary-card.critical {
        border-left-color: #e74a3b;
    }
    .summary-card.expiring {
        border-left-color: #f6c23e;
    }
    .summary-card.expired {
        border-left-color: #5a5c69;
    }
    .date-range-fields {
        display: none;
        margin-top: 10px;
    }
    .sort-icon {
        margin-left: 5px;
        font-size: 0.8em;
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
        <div>
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm mr-2">
                <i class="fas fa-download fa-sm text-white-50"></i> Exportar
            </a>
            <a href="{{ route('medicines.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Novo Medicamento
            </a>
        </div>
    </div>

    <!-- Cards de Resumo -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 py-2 summary-card total">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Medicamentos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $summary['total_medicines'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-pills fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 py-2 summary-card critical">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Estoque Crítico</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $summary['low_stock'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 py-2 summary-card expiring">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Próximos a Vencer</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $summary['expiring_soon'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 py-2 summary-card expired">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Vencidos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $summary['expired_medicines'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-times fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-gray-100 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Filtros</h6>
            <a href="{{ route('medicines.index') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-sync"></i> Limpar Filtros
            </a>
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
                            <select name="expiration" id="expirationFilter" class="form-control">
                                <option value="">Todas</option>
                                <option value="soon" {{ request('expiration') == 'soon' ? 'selected' : '' }}>Próximos a vencer</option>
                                <option value="expired" {{ request('expiration') == 'expired' ? 'selected' : '' }}>Vencidos</option>
                                <option value="range" {{ request('expiration') == 'range' ? 'selected' : '' }}>Intervalo personalizado</option>
                            </select>
                            
                            <div id="dateRangeFields" class="date-range-fields">
                                <label class="small">De:</label>
                                <input type="date" name="expiration_start" value="{{ request('expiration_start') }}" class="form-control form-control-sm mb-1">
                                
                                <label class="small">Até:</label>
                                <input type="date" name="expiration_end" value="{{ request('expiration_end') }}" class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100 mr-2">
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
                            <th class="sortable" data-sort="name">Nome 
                                <span class="sort-icon">
                                    @if(request('sort') == 'name' && request('order') == 'asc')
                                        <i class="fas fa-sort-up"></i>
                                    @elseif(request('sort') == 'name' && request('order') == 'desc')
                                        <i class="fas fa-sort-down"></i>
                                    @else
                                        <i class="fas fa-sort"></i>
                                    @endif
                                </span>
                            </th>
                            <th class="sortable" data-sort="batch">Lote
                                <span class="sort-icon">
                                    @if(request('sort') == 'batch' && request('order') == 'asc')
                                        <i class="fas fa-sort-up"></i>
                                    @elseif(request('sort') == 'batch' && request('order') == 'desc')
                                        <i class="fas fa-sort-down"></i>
                                    @else
                                        <i class="fas fa-sort"></i>
                                    @endif
                                </span>
                            </th>
                            <th class="sortable" data-sort="expiration_date">Validade
                                <span class="sort-icon">
                                    @if(request('sort') == 'expiration_date' && request('order') == 'asc')
                                        <i class="fas fa-sort-up"></i>
                                    @elseif(request('sort') == 'expiration_date' && request('order') == 'desc')
                                        <i class="fas fa-sort-down"></i>
                                    @else
                                        <i class="fas fa-sort"></i>
                                    @endif
                                </span>
                            </th>
                            <th class="sortable" data-sort="stock">Estoque
                                <span class="sort-icon">
                                    @if(request('sort') == 'stock' && request('order') == 'asc')
                                        <i class="fas fa-sort-up"></i>
                                    @elseif(request('sort') == 'stock' && request('order') == 'desc')
                                        <i class="fas fa-sort-down"></i>
                                    @else
                                        <i class="fas fa-sort"></i>
                                    @endif
                                </span>
                            </th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($medicines as $medicine)
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
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="fas fa-search fa-2x mb-2 text-gray-300"></i>
                                <p class="text-muted">Nenhum medicamento encontrado com os filtros aplicados.</p>
                                <a href="{{ route('medicines.index') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-sync"></i> Limpar Filtros
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Paginação -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted small">
                    Mostrando {{ $medicines->firstItem() ?? 0 }} a {{ $medicines->lastItem() ?? 0 }} de {{ $medicines->total() }} registros
                </div>
                <div>
                    {{ $medicines->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Controle do filtro de data range
        const expirationFilter = document.getElementById('expirationFilter');
        const dateRangeFields = document.getElementById('dateRangeFields');
        
        function toggleDateRangeFields() {
            if (expirationFilter.value === 'range') {
                dateRangeFields.style.display = 'block';
            } else {
                dateRangeFields.style.display = 'none';
            }
        }
        
        // Inicializar estado
        toggleDateRangeFields();
        
        // Adicionar listener para mudanças
        expirationFilter.addEventListener('change', toggleDateRangeFields);
        
        // Ordenação por colunas
        const sortableHeaders = document.querySelectorAll('.sortable');
        
        sortableHeaders.forEach(header => {
            header.addEventListener('click', function() {
                const sortField = this.dataset.sort;
                let sortOrder = 'asc';
                
                // Se já está ordenado por este campo, inverter a ordem
                if ('{{ request('sort') }}' === sortField) {
                    sortOrder = '{{ request('order') }}' === 'asc' ? 'desc' : 'asc';
                }
                
                // Construir URL com parâmetros de ordenação
                const url = new URL(window.location.href);
                url.searchParams.set('sort', sortField);
                url.searchParams.set('order', sortOrder);
                
                window.location.href = url.toString();
            });
        });
    });
</script>
@endpush