@extends('layouts.admin')

@section('title', 'Gerenciar Usuários')

@push('styles')
<style>
    .user-status-active {
        color: #1cc88a;
    }
    .user-status-inactive {
        color: #e74a3b;
    }
    .search-box {
        transition: all 0.3s;
    }
    .search-box:focus-within {
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .sortable:hover {
        background-color: #f8f9fa;
        cursor: pointer;
    }
    .sort-arrow {
        display: inline-block;
        width: 0;
        height: 0;
        margin-left: 5px;
    }
    .sort-asc {
        border-left: 5px solid transparent;
        border-right: 5px solid transparent;
        border-bottom: 5px solid #4e73df;
    }
    .sort-desc {
        border-left: 5px solid transparent;
        border-right: 5px solid transparent;
        border-top: 5px solid #4e73df;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-users"></i> Gerenciar Usuários
        </h1>
        <div>
            <a href="{{ route('users.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Novo Usuário
            </a>
            <a href="{{ route('users.export') }}" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm ml-2">
                <i class="fas fa-file-excel fa-sm text-white-50"></i> Exportar
            </a>
        </div>
    </div>

    <!-- Filtros e Busca -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filtrar Usuários</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('users.index') }}">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="input-group search-box">
                            <input type="text" class="form-control" name="search" placeholder="Pesquisar..." value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <select class="form-control" name="status">
                            <option value="">Todos Status</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Ativo</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inativo</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <select class="form-control" name="role">
                            <option value="">Todos Perfis</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <button type="submit" class="btn btn-primary btn-block">Filtrar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabela de Usuários -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Lista de Usuários</h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                    <div class="dropdown-header">Ações em Lote:</div>
                    <a class="dropdown-item batch-action" href="#" data-action="activate">
                        <i class="fas fa-check-circle text-success"></i> Ativar Selecionados
                    </a>
                    <a class="dropdown-item batch-action" href="#" data-action="deactivate">
                        <i class="fas fa-times-circle text-danger"></i> Desativar Selecionados
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('users.export') }}">
                        <i class="fas fa-file-export"></i> Exportar para Excel
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="40px">
                                <input type="checkbox" id="select-all">
                            </th>
                            <th class="sortable" data-sort="name" data-direction="{{ request('sort') == 'name' && request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                Nome
                                @if(request('sort') == 'name')
                                    <span class="sort-arrow {{ request('direction') == 'asc' ? 'sort-asc' : 'sort-desc' }}"></span>
                                @endif
                            </th>
                            <th class="sortable" data-sort="email" data-direction="{{ request('sort') == 'email' && request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                Email
                                @if(request('sort') == 'email')
                                    <span class="sort-arrow {{ request('direction') == 'asc' ? 'sort-asc' : 'sort-desc' }}"></span>
                                @endif
                            </th>
                            <th>Perfis</th>
                            <th>Painel</th>
                            <th>Status</th>
                            <th class="sortable" data-sort="last_login_at" data-direction="{{ request('sort') == 'last_login_at' && request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                Último Login
                                @if(request('sort') == 'last_login_at')
                                    <span class="sort-arrow {{ request('direction') == 'asc' ? 'sort-asc' : 'sort-desc' }}"></span>
                                @endif
                            </th>
                            <th width="120px">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>
                                <input type="checkbox" class="user-checkbox" value="{{ $user->id }}">
                            </td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @foreach($user->roles as $role)
                                <span class="badge badge-primary">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                @if($user->hasRole('admin'))
                                    <span class="badge badge-danger">Administrativo</span>
                                @elseif($user->hasRole('doctor'))
                                    <span class="badge badge-info">Médico</span>
                                @else
                                    <span class="badge badge-secondary">Padrão</span>
                                @endif
                            </td>
                            <td>
                                @if($user->is_active)
                                    <span class="user-status-active">
                                        <i class="fas fa-check-circle"></i> Ativo
                                    </span>
                                @else
                                    <span class="user-status-inactive">
                                        <i class="fas fa-times-circle"></i> Inativo
                                    </span>
                                @endif
                            </td>
                            <td>
                                {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Nunca' }}
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal{{ $user->id }}" title="Excluir">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#passwordModal{{ $user->id }}" title="Alterar Senha">
                                        <i class="fas fa-key"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Modais (mantidos os mesmos do CRUD anterior) -->
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Paginação -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <p>Mostrando {{ $users->firstItem() }} a {{ $users->lastItem() }} de {{ $users->total() }} registros</p>
                </div>
                <div class="col-md-6">
                    <nav class="float-right">
                        {{ $users->withQueryString()->links() }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Ordenação por coluna
        $('.sortable').click(function() {
            const sort = $(this).data('sort');
            const direction = $(this).data('direction');
            const url = new URL(window.location.href);
            
            url.searchParams.set('sort', sort);
            url.searchParams.set('direction', direction);
            
            window.location.href = url.toString();
        });

        // Seleção múltipla
        $('#select-all').click(function() {
            $('.user-checkbox').prop('checked', this.checked);
        });

        // Ações em lote
        $('.batch-action').click(function(e) {
            e.preventDefault();
            const action = $(this).data('action');
            const selectedUsers = $('.user-checkbox:checked').map(function() {
                return $(this).val();
            }).get();

            if (selectedUsers.length === 0) {
                alert('Selecione pelo menos um usuário');
                return;
            }

            if (confirm(`Deseja realmente ${action === 'activate' ? 'ativar' : 'desativar'} ${selectedUsers.length} usuário(s)?`)) {
                $.ajax({
                    url: '/admin/users/batch-update',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        ids: selectedUsers,
                        action: action
                    },
                    success: function(response) {
                        location.reload();
                    },
                    error: function() {
                        alert('Ocorreu um erro. Tente novamente.');
                    }
                });
            }
        });
    });
</script>
@endpush