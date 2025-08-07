@extends('layouts.admin')

@section('title', 'Cadastrar Medicamento')

@push('styles')
<style>
    .form-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    .form-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e3e6f0;
        padding: 1.5rem;
    }
    .form-header h2 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #4e73df;
    }
    .form-body {
        padding: 2rem;
    }
    .form-footer {
        background-color: #f8f9fa;
        border-top: 1px solid #e3e6f0;
        padding: 1.5rem;
        display: flex;
        justify-content: flex-end;
    }
    .form-group {
        margin-bottom: 1.5rem;
    }
    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #5a5c69;
    }
    .form-control {
        display: block;
        width: 100%;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        color: #6e707e;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #d1d3e2;
        border-radius: 0.35rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    .form-control:focus {
        color: #6e707e;
        background-color: #fff;
        border-color: #bac8f3;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }
    .btn {
        display: inline-block;
        font-weight: 400;
        color: #858796;
        text-align: center;
        vertical-align: middle;
        user-select: none;
        background-color: transparent;
        border: 1px solid transparent;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        border-radius: 0.35rem;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    .btn-primary {
        color: #fff;
        background-color: #4e73df;
        border-color: #4e73df;
    }
    .btn-primary:hover {
        color: #fff;
        background-color: #2e59d9;
        border-color: #2653d4;
    }
    .btn-secondary {
        color: #fff;
        background-color: #858796;
        border-color: #858796;
    }
    .btn-secondary:hover {
        color: #fff;
        background-color: #717384;
        border-color: #6b6d7d;
    }
    .text-danger {
        color: #e74a3b !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Cabeçalho -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-pills"></i> Cadastrar Novo Medicamento
        </h1>
        <a href="{{ route('medicines.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Voltar
        </a>
    </div>

    <!-- Formulário -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-gray-100">
            <h6 class="m-0 font-weight-bold text-primary">Informações do Medicamento</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('medicines.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name" class="form-label">Nome do Medicamento *</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                   class="form-control" required>
                            @error('name') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="category" class="form-label">Categoria *</label>
                            <select name="category" id="category" class="form-control" required>
                                <option value="">Selecione a categoria do produto</option>
                                <option value="Antibióticos">Antibióticos</option>
                                <option value="Antifúngicos">Antifúngicos</option>
                                <option value="Antiparasitários">Antiparasitários</option>
                                <option value="Anti-hipertensivos">Anti-hipertensivos</option>
                                <option value="Diuréticos">Diuréticos</option>
                                <option value="Anticoagulantes">Anticoagulantes</option>
                                <option value="Antiarrítmicos">Antiarrítmicos</option>
                                <option value="Analgésicos">Analgésicos</option>
                                <option value="Antidepressivos">Antidepressivos</option>
                                <option value="Antipsicóticos">Antipsicóticos</option>
                            </select>
                            @error('category') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="batch" class="form-label">Lote *</label>
                            <select name="batch" id="batch" class="form-control" required>
                                <option value="">Selecione o lote do medicamento</option>
                                <option value="LOTEA01">Lote A01</option>
                                <option value="LOTEA02">Lote A02</option>
                                <option value="LOTEB01">Lote B01</option>
                                <option value="LOTEB02">Lote B02</option>
                                <option value="LOTEC01">Lote C01</option>
                                <option value="LOTEC02">Lote C02</option>
                            </select>
                            @error('batch') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="expiration_date" class="form-label">Data de Validade *</label>
                            <input type="date" name="expiration_date" id="expiration_date" 
                                   value="{{ old('expiration_date') }}" class="form-control" required>
                            @error('expiration_date') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="stock" class="form-label">Estoque Inicial *</label>
                            <input type="number" name="stock" id="stock" value="{{ old('stock', 0) }}" 
                                   min="0" class="form-control" required>
                            @error('stock') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="minimum_stock" class="form-label">Estoque Mínimo *</label>
                            <input type="number" name="minimum_stock" id="minimum_stock" 
                                   value="{{ old('minimum_stock', 1) }}" min="1" class="form-control" required>
                            @error('minimum_stock') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Descrição</label>
                    <textarea name="description" id="description" rows="3" 
                              class="form-control">{{ old('description') }}</textarea>
                    @error('description') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                
                <div class="form-footer">
                    <button type="button" class="btn btn-secondary mr-2" 
                            onclick="window.location='{{ route('medicines.index') }}'">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Cadastrar Medicamento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection