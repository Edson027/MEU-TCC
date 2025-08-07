@extends('layouts.admin')

@section('title', 'Editar Pedido #' . $order->id)

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
    .d-none {
        display: none !important;
    }
    .alert {
        position: relative;
        padding: 0.75rem 1.25rem;
        margin-bottom: 1rem;
        border: 1px solid transparent;
        border-radius: 0.35rem;
    }
    .alert-warning {
        color: #856404;
        background-color: #fff3cd;
        border-color: #ffeeba;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Cabeçalho -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-clipboard-list"></i> Editar Pedido #{{ $order->id }}
        </h1>
        <a href="{{ route('orders.show', $order) }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Voltar
        </a>
    </div>

    @if($order->status !== App\Models\Order::STATUS_PENDING)
        <div class="alert alert-warning">
            <strong>Atenção!</strong> Só é possível editar pedidos com status "Pendente".
        </div>
    @endif

    <!-- Formulário -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-gray-100">
            <h6 class="m-0 font-weight-bold text-primary">Informações do Pedido</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('orders.update', $order) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="medicine_id" class="form-label">Medicamento *</label>
                            <select class="form-control" id="medicine_id" name="medicine_id" required
                                {{ $order->status !== App\Models\Order::STATUS_PENDING ? 'disabled' : '' }}>
                                @foreach($medicines as $medicine)
                                    <option value="{{ $medicine->id }}" 
                                        data-stock="{{ $medicine->stock }}"
                                        {{ $order->medicine_id == $medicine->id ? 'selected' : '' }}>
                                        {{ $medicine->name }} (Estoque: {{ $medicine->stock }})
                                    </option>
                                @endforeach
                            </select>
                            @error('medicine_id') 
                                <span class="text-danger text-sm">{{ $message }}</span> 
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="quantity" class="form-label">Quantidade *</label>
                            <input type="number" class="form-control" id="quantity" 
                                name="quantity" min="1" value="{{ old('quantity', $order->quantity) }}" required
                                {{ $order->status !== App\Models\Order::STATUS_PENDING ? 'disabled' : '' }}>
                            <div id="stockWarning" class="text-danger d-none">
                                Quantidade maior que o estoque disponível!
                            </div>
                            @error('quantity') 
                                <span class="text-danger text-sm">{{ $message }}</span> 
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="urgency_level" class="form-label">Nível de Urgência *</label>
                            <select class="form-control" id="urgency_level" name="urgency_level" required
                                {{ $order->status !== App\Models\Order::STATUS_PENDING ? 'disabled' : '' }}>
                                @foreach($urgencyLevels as $value => $label)
                                    <option value="{{ $value }}" 
                                        {{ $order->urgency_level == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('urgency_level') 
                                <span class="text-danger text-sm">{{ $message }}</span> 
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <div class="form-control-plaintext">
                                @switch($order->status)
                                    @case('pending') <span class="badge bg-secondary">Pendente</span> @break
                                    @case('approved') <span class="badge bg-success">Aprovado</span> @break
                                    @case('rejected') <span class="badge bg-danger">Rejeitado</span> @break
                                    @case('completed') <span class="badge bg-primary">Completo</span> @break
                                @endswitch
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="notes" class="form-label">Observações</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3"
                        {{ $order->status !== App\Models\Order::STATUS_PENDING ? 'disabled' : '' }}>{{ old('notes', $order->notes) }}</textarea>
                    @error('notes') 
                        <span class="text-danger text-sm">{{ $message }}</span> 
                    @enderror
                </div>
                
                <div class="form-footer">
                    <button type="button" class="btn btn-secondary mr-2" 
                            onclick="window.location='{{ route('orders.show', $order) }}'">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    
                    @if($order->status === App\Models\Order::STATUS_PENDING)
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Atualizar Pedido
                        </button>
                    @else
                        <button type="button" class="btn btn-primary" disabled>
                            <i class="fas fa-save"></i> Atualizar Pedido
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

@if($order->status === App\Models\Order::STATUS_PENDING)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const medicineSelect = document.getElementById('medicine_id');
        const quantityInput = document.getElementById('quantity');
        const stockWarning = document.getElementById('stockWarning');
        
        medicineSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const stock = selectedOption.getAttribute('data-stock');
            
            if (stock) {
                quantityInput.max = stock;
            }
        });
        
        quantityInput.addEventListener('input', function() {
            const selectedOption = medicineSelect.options[medicineSelect.selectedIndex];
            const stock = selectedOption ? parseInt(selectedOption.getAttribute('data-stock')) : 0;
            const quantity = parseInt(this.value) || 0;
            
            if (quantity > stock) {
                stockWarning.classList.remove('d-none');
            } else {
                stockWarning.classList.add('d-none');
            }
        });
        
        // Trigger change event to set initial max value
        medicineSelect.dispatchEvent(new Event('change'));
    });
</script>
@endif
@endsection