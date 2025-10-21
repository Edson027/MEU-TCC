@extends('layouts.admin')

@section('title', 'Detalhes do Fornecedor')

@push('styles')
<style>
    .detail-card {
        border-left: 4px solid #1cc88a;
    }
    .info-label {
        font-weight: 600;
        color: #5a5c69;
    }
    .info-value {
        font-size: 1.1rem;
        color: #4e73df;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Cabeçalho -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-info-circle"></i> Detalhes do Fornecedor
        </h1>
        <div>
            <a href="{{ route('fornecedors.edit', $fornecedor->id) }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2">
                <i class="fas fa-edit fa-sm text-white-50"></i> Editar
            </a>
            <a href="{{ route('fornecedors.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Voltar
            </a>
        </div>
    </div>

    <!-- Detalhes -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow detail-card">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Informações do Fornecedor</h6>
                    <span class="badge badge-success">ID: {{ $fornecedor->id }}</span>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <h4 class="text-primary">{{ $fornecedor->nome }}</h4>
                            <p class="text-muted">{{ $fornecedor->descricao }}</p>
                        </div>
                        <div class="col-md-4 text-right">
                            <div class="mb-2">
                                <span class="badge badge-info p-2">
                                    <i class="fas fa-calendar"></i> 
                                    Criado em: {{ $fornecedor->created_at->format('d/m/Y H:i') }}
                                </span>
                            </div>
                            <div>
                                <span class="badge badge-light p-2">
                                    <i class="fas fa-sync"></i> 
                                    Atualizado em: {{ $fornecedor->updated_at->format('d/m/Y H:i') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-light py-2">
                                    <h6 class="m-0 font-weight-bold text-dark">Dados Principais</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="info-label">NIF</div>
                                        <div class="info-value">{{ $fornecedor->nif }}</div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="info-label">Telefone</div>
                                        <div class="info-value">{{ $fornecedor->telefone }}</div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="info-label">Localização</div>
                                        <div class="info-value">{{ $fornecedor->localizacao }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-light py-2">
                                    <h6 class="m-0 font-weight-bold text-dark">Estatísticas</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="info-label">Tempo no Sistema</div>
                                        <div class="info-value">{{ $fornecedor->created_at->diffForHumans() }}</div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="info-label">Última Atualização</div>
                                        <div class="info-value">{{ $fornecedor->updated_at->diffForHumans() }}</div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="info-label">Status</div>
                                        <div>
                                            <span class="badge badge-success p-2">
                                                <i class="fas fa-check-circle"></i> Ativo
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ações -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-light py-2">
                                    <h6 class="m-0 font-weight-bold text-dark">Ações</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex">
                                        <a href="{{ route('fornecedors.edit', $fornecedor->id) }}" 
                                           class="btn btn-primary mr-2">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <form action="{{ route('fornecedors.destroy', $fornecedor->id) }}" 
                                              method="POST" class="mr-2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" 
                                                    onclick="return confirm('Tem certeza que deseja excluir este fornecedor?')">
                                                <i class="fas fa-trash"></i> Excluir
                                            </button>
                                        </form>
                                        <a href="{{ route('fornecedors.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-list"></i> Ver Todos
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection