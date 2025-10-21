@extends('layouts.admin')

@section('title', 'Novo Pedido de Abastecimento')
@section('page-title', 'Novo Pedido de Abastecimento')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card dashboard-card">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-plus-circle me-2"></i>
                        Criar Novo Pedido de Abastecimento
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('supply-orders.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="medicine_id" class="form-label">
                                        <i class="fas fa-pills me-1 text-primary"></i>
                                        Medicamento *
                                    </label>
                                    <select name="medicine_id" id="medicine_id" class="form-select" required>
                                        <option value="">Selecione um medicamento</option>
                                        @foreach($medicines as $medicine)
                                        <option value="{{ $medicine->id }}" 
                                                {{ old('medicine_id') == $medicine->id ? 'selected' : '' }}
                                                data-stock="{{ $medicine->stock }}"
                                                data-minimum="{{ $medicine->minimum_stock }}">
                                            {{ $medicine->name }} 
                                            (Estoque: {{ $medicine->stock }} | Mín: {{ $medicine->minimum_stock }})
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('medicine_id')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="quantity_requested" class="form-label">
                                        <i class="fas fa-boxes me-1 text-primary"></i>
                                        Quantidade Solicitada *
                                    </label>
                                    <input type="number" 
                                           name="quantity_requested" 
                                           id="quantity_requested"
                                           class="form-control"
                                           min="1"
                                           value="{{ old('quantity_requested') }}"
                                           required>
                                    @error('quantity_requested')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text" id="quantityHelp">
                                        Digite a quantidade desejada
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="reason" class="form-label">
                                <i class="fas fa-comment me-1 text-primary"></i>
                                Motivo da Solicitação *
                            </label>
                            <textarea name="reason" id="reason" class="form-control" rows="3" required>{{ old('reason') }}</textarea>
                            @error('reason')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="expected_delivery_date" class="form-label">
                                <i class="fas fa-calendar-alt me-1 text-primary"></i>
                                Previsão de Entrega
                            </label>
                            <input type="date" 
                                   name="expected_delivery_date" 
                                   id="expected_delivery_date"
                                   class="form-control"
                                   value="{{ old('expected_delivery_date') }}"
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                            @error('expected_delivery_date')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('supply-orders.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Criar Pedido
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Card de Informações -->
            <div class="card dashboard-card mt-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Informações Importantes
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-lightbulb me-2"></i>Dicas para Pedidos:</h6>
                        <ul class="mb-0">
                            <li>Verifique o estoque atual antes de solicitar</li>
                            <li>Considere o consumo médio mensal do medicamento</li>
                            <li>Informe claramente o motivo da solicitação</li>
                            <li>Estime uma data realista para entrega</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const medicineSelect = document.getElementById('medicine_id');
    const quantityInput = document.getElementById('quantity_requested');
    const quantityHelp = document.getElementById('quantityHelp');
    
    function updateQuantityHelp() {
        const selectedOption = medicineSelect.options[medicineSelect.selectedIndex];
        if (selectedOption.value) {
            const stock = parseInt(selectedOption.getAttribute('data-stock'));
            const minimum = parseInt(selectedOption.getAttribute('data-minimum'));
            const recommended = Math.max(minimum * 2 - stock, minimum);
            
            quantityHelp.innerHTML = `
                <strong>Estoque atual:</strong> ${stock} unidades<br>
                <strong>Estoque mínimo:</strong> ${minimum} unidades<br>
                <strong>Quantidade recomendada:</strong> ${recommended} unidades
            `;
            
            if (quantityInput.value === '') {
                quantityInput.value = recommended;
            }
        } else {
            quantityHelp.textContent = 'Digite a quantidade desejada';
        }
    }
    
    medicineSelect.addEventListener('change', updateQuantityHelp);
    updateQuantityHelp(); // Executar ao carregar a página
});
</script>
@endpush