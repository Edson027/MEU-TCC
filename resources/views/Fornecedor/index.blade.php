@extends('layouts.admin')

@section('title', 'Fornecedores')

@push('styles')
<style>
    .table-responsive {
        min-height: 400px;
    }
    .status-badge {
        font-size: 0.75rem;
    }
    .action-buttons .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Cabeçalho -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-truck-loading"></i> Fornecedores
        </h1>
        <a href="{{ route('fornecedors.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Novo Fornecedor
        </a>
    </div>

    <!-- Filtros e Busca -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body py-3">
                    <form method="GET" action="{{ route('fornecedors.index') }}">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group mb-0">
                                    <input type="text" class="form-control" name="search" 
                                           placeholder="Buscar por nome, NIF ou localização..." 
                                           value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-search"></i> Filtrar
                                </button>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('fornecedors.index') }}" class="btn btn-secondary btn-block">
                                    <i class="fas fa-sync"></i> Limpar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de Fornecedores -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Lista de Fornecedores</h6>
                    <span class="badge badge-primary">{{ $fornecedors->total() }} registros</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Nome</th>
                                    <th>NIF</th>
                                    <th>Localização</th>
                                    <th>Telefone</th>
                                    <th>Data de Registro</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($fornecedors as $fornecedor)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="mr-3">
                                                <i class="fas fa-truck text-primary"></i>
                                            </div>
                                            <div>
                                                <strong>{{ $fornecedor->nome }}</strong>
                                                <div class="text-muted small">{{ Str::limit($fornecedor->descricao, 50) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $fornecedor->nif }}</td>
                                    <td>{{ $fornecedor->localizacao }}</td>
                                    <td>{{ $fornecedor->telefone }}</td>
                                    <td>{{ $fornecedor->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="action-buttons">
                                        <div class="d-flex">
                                            <a href="{{ route('fornecedors.show', $fornecedor->id) }}" 
                                               class="btn btn-sm btn-info mr-1" title="Visualizar">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('fornecedors.edit', $fornecedor->id) }}" 
                                               class="btn btn-sm btn-primary mr-1" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('fornecedors.destroy', $fornecedor->id) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" 
                                                        title="Excluir" 
                                                        onclick="return confirm('Tem certeza que deseja excluir este fornecedor?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="fas fa-info-circle fa-2x text-gray-300 mb-2"></i>
                                        <p class="text-muted">Nenhum fornecedor encontrado.</p>
                                        <a href="{{ route('fornecedors.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Cadastrar Primeiro Fornecedor
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Paginação -->
                    @if($fornecedors->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Mostrando {{ $fornecedors->firstItem() }} a {{ $fornecedors->lastItem() }} de {{ $fornecedors->total() }} registros
                        </div>
                        <nav>
                            {{ $fornecedors->links() }}
                        </nav>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection