@extends('layouts.admin')
@section('title', 'Usuários')

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
    .badge-primary {
        color: #fff;
        background-color: #4e73df;
    }
    .badge-purple {
        color: #fff;
        background-color: #6f42c1;
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
    .summary-card.admins {
        border-left-color: #e74a3b;
    }
    .summary-card.managers {
        border-left-color: #f6c23e;
    }
    .summary-card.nurses {
        border-left-color: #1cc88a;
    }
    .sort-icon {
        margin-left: 5px;
        font-size: 0.8em;
    }
    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }
    .avatar-placeholder {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #eaecf4;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Cabeçalho -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-users"></i> Gestão de Usuários
        </h1>
        <div>
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm mr-2">
                <i class="fas fa-download fa-sm text-white-50"></i> Exportar
            </a>
            <a href="{{ route('users.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Novo Usuário
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
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Usuários</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $users->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 py-2 summary-card admins">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Administradores</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $users->where('painel', 'administrador')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-shield fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 py-2 summary-card managers">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Técnicos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $users->where('painel', 'tecnico')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 py-2 summary-card nurses">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Enfermeiros</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $users->where('painel', 'enfermeiro')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-nurse fa-2x text-gray-300"></i>
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
            <a href="{{ route('users.index') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-sync"></i> Limpar Filtros
            </a>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('users.index') }}">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Pesquisar</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       class="form-control" placeholder="Pesquise por nome ou email">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Painel</label>
                            <select name="painel_filter" class="form-control">
                                <option value="">Todos os painéis</option>
                                @foreach($painelOptions as $option)
                                    <option value="{{ $option }}" {{ request('painel_filter') == $option ? 'selected' : '' }}>
                                        {{ ucfirst($option) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Notificações</label>
                            <select name="notifications_filter" class="form-control">
                                <option value="">Todos</option>
                                <option value="1" {{ request('notifications_filter') == '1' ? 'selected' : '' }}>Recebe notificações</option>
                                <option value="0" {{ request('notifications_filter') == '0' ? 'selected' : '' }}>Não recebe notificações</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100 mr-2">
                            <i class="fas fa-filter"></i> Filtrar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabela de Usuários -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-gray-100">
            <h6 class="m-0 font-weight-bold text-primary">Lista de Usuários</h6>
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
                            <th class="sortable" data-sort="email">Email
                                <span class="sort-icon">
                                    @if(request('sort') == 'email' && request('order') == 'asc')
                                        <i class="fas fa-sort-up"></i>
                                    @elseif(request('sort') == 'email' && request('order') == 'desc')
                                        <i class="fas fa-sort-down"></i>
                                    @else
                                        <i class="fas fa-sort"></i>
                                    @endif
                                </span>
                            </th>
                            <th class="sortable" data-sort="painel">Painel
                                <span class="sort-icon">
                                    @if(request('sort') == 'painel' && request('order') == 'asc')
                                        <i class="fas fa-sort-up"></i>
                                    @elseif(request('sort') == 'painel' && request('order') == 'desc')
                                        <i class="fas fa-sort-down"></i>
                                    @else
                                        <i class="fas fa-sort"></i>
                                    @endif
                                </span>
                            </th>
                            <th>Notificações</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($user->profile_photo_path)
                                        <img class="user-avatar mr-3" src="{{ asset($user->profile_photo_path) }}" alt="{{ $user->name }}">
                                    @else
                                        <div class="avatar-placeholder mr-3">
                                            <i class="fas fa-user text-gray-400"></i>
                                        </div>
                                    @endif
                                    <div class="font-weight-bold">{{ $user->name }}</div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge 
                                    {{ $user->painel == 'administrador' ? 'badge-danger' :
                                       ($user->painel == 'gerente' ? 'badge-warning' :
                                       ($user->painel == 'enfermeira' ? 'badge-success' : 'badge-primary')) }}">
                                    {{ $user->painel }}
                                </span>
                            </td>
                            <td>
                                @if($user->receives_notifications)
                                    <span class="badge badge-success">
                                        <i class="fas fa-bell mr-1"></i> Ativo
                                    </span>
                                @else
                                    <span class="badge badge-secondary">
                                        <i class="fas fa-bell-slash mr-1"></i> Inativo
                                    </span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('users.show', $user->id) }}" class="action-link text-primary" title="Ver detalhes">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('users.edit', $user->id) }}" class="action-link text-warning" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-link text-danger bg-transparent border-0" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <i class="fas fa-search fa-2x mb-2 text-gray-300"></i>
                                <p class="text-muted">Nenhum usuário encontrado com os filtros aplicados.</p>
                                <a href="{{ route('users.index') }}" class="btn btn-primary btn-sm">
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
                    Mostrando {{ $users->firstItem() ?? 0 }} a {{ $users->lastItem() ?? 0 }} de {{ $users->total() }} registros
                </div>
                <div>
                    {{ $users->appends(request()->except('page'))->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
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