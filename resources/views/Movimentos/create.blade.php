@extends('layouts.admin')

@section('title', $type === 'entrada' ? 'Registrar Entrada' : 'Registrar Saída')

@push('styles')
<style>
    .movement-icon {
        width: 80px;
        height: 80px;
        font-size: 2rem;
    }
    .form-container {
        max-width: 500px;
        margin: 0 auto;
    }
    .form-header {
        text-align: center;
        margin-bottom: 1.5rem;
    }
    .medicine-info {
        background-color: #f8f9fa;
        border-radius: 0.35rem;
        padding: 1rem;
        margin-bottom: 1.5rem;
        text-align: center;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Cabeçalho -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas {{ $type === 'entrada' ? 'fa-arrow-down' : 'fa-arrow-up' }}"></i>
            {{ $type === 'entrada' ? 'Registrar Entrada de Estoque' : 'Registrar Saída de Estoque' }}
        </h1>
        <a href="{{ route('medicines.show', $medicine) }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Voltar
        </a>
    </div>

    <!-- Formulário -->
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="form-header">
                        <div class="movement-icon rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3
                            {{ $type === 'entrada' ? 'bg-success text-white' : 'bg-danger text-white' }}">
                            <i class="fas {{ $type === 'entrada' ? 'fa-arrow-down' : 'fa-arrow-up' }}"></i>
                        </div>
                        <h3 class="h4 text-gray-900 mb-0">{{ $type === 'entrada' ? 'Entrada' : 'Saída' }} de Estoque</h3>
                    </div>

                    <div class="medicine-info">
                        <h4 class="h5 font-weight-bold text-gray-800">{{ $medicine->name }}</h4>
                        <p class="mb-0">Estoque atual: <span class="font-weight-bold">{{ $medicine->stock }} unidades</span></p>
                    </div>
 
                    <form action="{{ route('movements.store', $medicine) }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="{{ $type }}">

                        <div class="form-group">
                            <label for="quantity">Quantidade *</label>
                            <input type="number" id="quantity" name="quantity" min="1" 
                                   class="form-control {{ $errors->has('quantity') ? 'is-invalid' : '' }}" required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="movement_date">Data da Movimentação *</label>
                            <input type="datetime-local" id="movement_date" name="movement_date" readonly 
                                   value="{{ old('movement_date', now()->format('Y-m-d\TH:i')) }}"
                                   class="form-control {{ $errors->has('movement_date') ? 'is-invalid' : '' }}" required>
                            @error('movement_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="reason">Motivo *</label>
                            <textarea id="reason" name="reason" rows="3"
                                      class="form-control {{ $errors->has('reason') ? 'is-invalid' : '' }}" required></textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('medicines.show', $medicine) }}" class="btn btn-secondary">
                                <i class="fas fa-times mr-2"></i> Cancelar
                            </a>
                            <button type="submit" class="btn {{ $type === 'entrada' ? 'btn-success' : 'btn-danger' }}">
                                <i class="fas {{ $type === 'entrada' ? 'fa-arrow-down' : 'fa-arrow-up' }} mr-2"></i>
                                {{ $type === 'entrada' ? 'Registrar Entrada' : 'Registrar Saída' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection