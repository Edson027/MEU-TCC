@extends('layouts.admin')

@section('title', 'Cadastrar Fornecedor')

@push('styles')
<style>
    .card-form {
        border-left: 4px solid #4e73df;
    }
    .required-field::after {
        content: "*";
        color: #e74a3b;
        margin-left: 4px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Cabeçalho -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-plus-circle"></i> Cadastrar Fornecedor
        </h1>
        <a href="{{ route('fornecedors.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Voltar
        </a>
    </div>

    <!-- Formulário -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow card-form">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informações do Fornecedor</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('fornecedors.store') }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nome" class="required-field">Nome do Fornecedor</label>
                                    <input type="text" class="form-control @error('nome') is-invalid @enderror" 
                                           id="nome" name="nome" value="{{ old('nome') }}" 
                                           placeholder="Digite o nome do fornecedor" required>
                                    @error('nome')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nif" class="required-field">NIF</label>
                                    <input type="text" class="form-control @error('nif') is-invalid @enderror" 
                                           id="nif" name="nif" value="{{ old('nif') }}" 
                                           placeholder="Digite o NIF" required>
                                    @error('nif')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="telefone" class="required-field">Telefone</label>
                                    <input type="number" class="form-control @error('telefone') is-invalid @enderror" 
                                           id="telefone" name="telefone" value="{{ old('telefone') }}" 
                                           placeholder="Digite o telefone" required>
                                    @error('telefone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="localizacao" class="required-field">Localização</label>
                                    <input type="text" class="form-control @error('localizacao') is-invalid @enderror" 
                                           id="localizacao" name="localizacao" value="{{ old('localizacao') }}" 
                                           placeholder="Digite a localização" required>
                                    @error('localizacao')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="descricao">Descrição</label>
                            <textarea class="form-control @error('descricao') is-invalid @enderror" 
                                      id="descricao" name="descricao" rows="3" 
                                      placeholder="Digite uma descrição para o fornecedor">{{ old('descricao') }}</textarea>
                            @error('descricao')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="reset" class="btn btn-secondary mr-2">
                                <i class="fas fa-undo"></i> Limpar
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Cadastrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection