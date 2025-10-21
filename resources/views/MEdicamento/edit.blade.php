@extends('layouts.admin')

@section('title', 'Editar Medicamento: ' . $medicine->name)

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
    .text-warning {
        color: #f6c23e !important;
    }
    .text-success {
        color: #1cc88a !important;
    }
    .status-indicator {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        border-radius: 0.35rem;
        font-size: 0.875rem;
        font-weight: 600;
        margin-left: 0.5rem;
    }
    .info-box {
        background-color: #f8f9fa;
        border-left: 4px solid #4e73df;
        padding: 1rem;
        border-radius: 0.35rem;
        margin-bottom: 1.5rem;
    }
    .alert-box {
        padding: 1rem;
        border-radius: 0.35rem;
        margin-bottom: 1.5rem;
        border-left: 4px solid;
    }
    .alert-danger {
        background-color: #fdf3f2;
        border-left-color: #e74a3b;
    }
    .alert-warning {
        background-color: #fef8e6;
        border-left-color: #f6c23e;
    }
    .readonly-field {
        background-color: #f8f9fa;
        cursor: not-allowed;
    }
    .batch-info {
        font-size: 0.875rem;
        color: #858796;
        margin-top: 0.25rem;
    }
    .stock-status {
        display: flex;
        align-items: center;
        margin-top: 0.5rem;
    }
    .stock-progress {
        flex-grow: 1;
        height: 8px;
        background-color: #eaecf4;
        border-radius: 4px;
        overflow: hidden;
        margin: 0 0.5rem;
    }
    .stock-progress-bar {
        height: 100%;
        border-radius: 4px;
        transition: width 0.3s ease;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Cabeçalho -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-pills"></i> Editar Medicamento: {{ $medicine->name }}
        </h1>
        <div>
            <a href="{{ route('medicines.show', $medicine) }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm mr-2">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Voltar
            </a>
            <a href="{{ route('medicines.history', $medicine) }}" class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm">
                <i class="fas fa-history fa-sm text-white-50"></i> Histórico
            </a>
        </div>
    </div>

    <!-- Alertas -->
    @if($medicine->stock_status == 'Crítico' || $medicine->stock_status == 'Esgotado')
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <i class="fas fa-exclamation-triangle"></i>
        <strong>Alerta de Estoque!</strong> O medicamento está com estoque {{ strtolower($medicine->stock_status) }}.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if($medicine->expiration_status == 'Vencido' || $medicine->expiration_status == 'Próximo a vencer')
    <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
        <i class="fas fa-calendar-exclamation"></i>
        <strong>Alerta de Validade!</strong> Este medicamento está 
        @if($medicine->expiration_status == 'Vencido')
            <strong class="text-danger">vencido</strong> desde {{ \Carbon\Carbon::parse($medicine->expiration_date)->format('d/m/Y') }}.
        @else
            <strong>próximo a vencer</strong> em {{ \Carbon\Carbon::parse($medicine->expiration_date)->format('d/m/Y') }}.
        @endif
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <!-- Formulário -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-gray-100 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Informações do Medicamento</h6>
            <span class="badge badge-{{ $medicine->stock_status == 'Normal' ? 'success' : ($medicine->stock_status == 'Atenção' ? 'warning' : 'danger') }}">
                {{ $medicine->stock_status }}
            </span>
        </div>
        <div class="card-body">
            <form action="{{ route('medicines.update', $medicine) }}" method="POST" id="medicineForm">
                @csrf
                @method('PUT')
                
                <div class="info-box">
                    <i class="fas fa-info-circle text-primary"></i>
                    <span class="ml-2">Campos marcados com * são obrigatórios. Alterações serão registradas no histórico.</span>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name" class="form-label">Nome do Medicamento *</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $medicine->name) }}" 
                                   class="form-control" required maxlength="255" 
                                   placeholder="Digite o nome comercial do medicamento">
                            @error('name') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="category" class="form-label">Categoria *</label>
                            <select name="category" id="category" class="form-control" required>
                                <option value="">Selecione a categoria</option>
                                <option value="Antibióticos" {{ old('category', $medicine->category) == 'Antibióticos' ? 'selected' : '' }}>Antibióticos</option>
                                <option value="Antifúngicos" {{ old('category', $medicine->category) == 'Antifúngicos' ? 'selected' : '' }}>Antifúngicos</option>
                                <option value="Antiparasitários" {{ old('category', $medicine->category) == 'Antiparasitários' ? 'selected' : '' }}>Antiparasitários</option>
                                <option value="Anti-hipertensivos" {{ old('category', $medicine->category) == 'Anti-hipertensivos' ? 'selected' : '' }}>Anti-hipertensivos</option>
                                <option value="Diuréticos" {{ old('category', $medicine->category) == 'Diuréticos' ? 'selected' : '' }}>Diuréticos</option>
                                <option value="Anticoagulantes" {{ old('category', $medicine->category) == 'Anticoagulantes' ? 'selected' : '' }}>Anticoagulantes</option>
                                <option value="Antiarrítmicos" {{ old('category', $medicine->category) == 'Antiarrítmicos' ? 'selected' : '' }}>Antiarrítmicos</option>
                                <option value="Analgésicos" {{ old('category', $medicine->category) == 'Analgésicos' ? 'selected' : '' }}>Analgésicos</option>
                                <option value="Antidepressivos" {{ old('category', $medicine->category) == 'Antidepressivos' ? 'selected' : '' }}>Antidepressivos</option>
                                <option value="Antipsicóticos" {{ old('category', $medicine->category) == 'Antipsicóticos' ? 'selected' : '' }}>Antipsicóticos</option>
                                <option value="Antivirais" {{ old('category', $medicine->category) == 'Antivirais' ? 'selected' : '' }}>Antivirais</option>
                                <option value="Hormônios" {{ old('category', $medicine->category) == 'Hormônios' ? 'selected' : '' }}>Hormônios</option>
                                <option value="Vitaminas" {{ old('category', $medicine->category) == 'Vitaminas' ? 'selected' : '' }}>Vitaminas</option>
                                <option value="Outros" {{ old('category', $medicine->category) == 'Outros' ? 'selected' : '' }}>Outros</option>
                            </select>
                            @error('category') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="batch" class="form-label">Lote *</label>
                            <input type="text" name="batch" id="batch" value="{{ old('batch', $medicine->batch) }}" 
                                   class="form-control" required maxlength="50" 
                                   placeholder="Ex: LOT202401A">
                            <div class="batch-info">
                                <i class="fas fa-info-circle"></i> Identificação única do lote do medicamento
                            </div>
                            @error('batch') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="expiration_date" class="form-label">Data de Validade *</label>
                            <input type="date" name="expiration_date" id="expiration_date" 
                                   value="{{ old('expiration_date', $medicine->expiration_date) }}" 
                                   class="form-control" required min="{{ date('Y-m-d') }}"
                                   onchange="updateExpirationStatus()">
                            <div id="expirationStatus" class="mt-1">
                                @php
                                    $expirationDate = \Carbon\Carbon::parse($medicine->expiration_date);
                                    $daysUntilExpiration = $expirationDate->diffInDays(now(), false) * -1;
                                @endphp
                                <span class="text-{{ $daysUntilExpiration < 0 ? 'success' : ($daysUntilExpiration < 30 ? 'warning' : 'danger') }}">
                                    <i class="fas fa-calendar-{{ $daysUntilExpiration < 0 ? 'check' : ($daysUntilExpiration < 30 ? 'minus' : 'times') }}"></i>
                                    {{ abs($daysUntilExpiration) }} dias {{ $daysUntilExpiration >= 0 ? 'desde' : 'para' }} a validade
                                </span>
                            </div>
                            @error('expiration_date') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="stock" class="form-label">Estoque Atual</label>
                            <input type="number" name="stock" id="stock" value="{{ old('stock', $medicine->stock) }}" 
                                   class="form-control readonly-field" readonly>
                            <div class="stock-status">
                                <small>Status:</small>
                                <div class="stock-progress">
                                    @php
                                        $progress = min(100, ($medicine->stock / max($medicine->minimum_stock * 3, 1)) * 100);
                                        $progressColor = $medicine->stock > $medicine->minimum_stock * 2 ? 'success' : 
                                                        ($medicine->stock > $medicine->minimum_stock ? 'warning' : 'danger');
                                    @endphp
                                    <div class="stock-progress-bar bg-{{ $progressColor }}" 
                                         style="width: {{ $progress }}%"></div>
                                </div>
                                <small class="text-{{ $progressColor }}">
                                    {{ $medicine->stock }} / {{ $medicine->minimum_stock * 3 }}
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="minimum_stock" class="form-label">Estoque Mínimo *</label>
                            <input type="number" name="minimum_stock" id="minimum_stock" 
                                   value="{{ old('minimum_stock', $medicine->minimum_stock) }}" 
                                   min="1" max="1000" class="form-control" required
                                   onchange="updateStockStatus()">
                            <div class="batch-info">
                                <i class="fas fa-info-circle"></i> Quantidade mínima para alertas de estoque
                            </div>
                            @error('minimum_stock') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Descrição e Observações</label>
                    <textarea name="description" id="description" rows="4" 
                              class="form-control" placeholder="Informações adicionais sobre o medicamento, como dosagem, contraindicações, etc.">{{ old('description', $medicine->description) }}</textarea>
                    <div class="batch-info">
                        <i class="fas fa-info-circle"></i> Máximo de 500 caracteres
                    </div>
                    @error('description') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Alteração de Estoque</label>
                    <div class="alert-box alert-warning">
                        <i class="fas fa-exclamation-triangle text-warning"></i>
                        <span class="ml-2">Para alterar o estoque, utilize o sistema de movimentações através dos botões "Entrada" ou "Saída".</span>
                    </div>
                </div>
                
                <div class="form-footer">
                    <button type="button" class="btn btn-secondary mr-2" 
                            onclick="window.location='{{ route('medicines.show', $medicine) }}'">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Atualizar Medicamento
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Card de Ações Rápidas -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-gray-100">
            <h6 class="m-0 font-weight-bold text-primary">Ações Rápidas</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 text-center mb-3">
                    <a href="{{ route('movements.create', ['medicine' => $medicine->id, 'type' => 'entrada']) }}" 
                       class="btn btn-success btn-block">
                        <i class="fas fa-arrow-down"></i> Registrar Entrada
                    </a>
                </div>
                <div class="col-md-4 text-center mb-3">
                    <a href="{{ route('movements.create', ['medicine' => $medicine->id, 'type' => 'saida']) }}" 
                       class="btn btn-danger btn-block">
                        <i class="fas fa-arrow-up"></i> Registrar Saída
                    </a>
                </div>
                <div class="col-md-4 text-center mb-3">
                    <a href="{{ route('medicines.history', $medicine) }}" 
                       class="btn btn-info btn-block">
                        <i class="fas fa-history"></i> Ver Histórico
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar status
        updateExpirationStatus();
        updateStockStatus();
        
        // Validação do formulário
        document.getElementById('medicineForm').addEventListener('submit', function(e) {
            const expirationDate = new Date(document.getElementById('expiration_date').value);
            const today = new Date();
            
            if (expirationDate <= today) {
                if (!confirm('A data de validade informada já passou. Deseja continuar?')) {
                    e.preventDefault();
                    return false;
                }
            }
            
            const minimumStock = parseInt(document.getElementById('minimum_stock').value);
            if (minimumStock < 1) {
                alert('O estoque mínimo deve ser pelo menos 1 unidade.');
                e.preventDefault();
                return false;
            }
        });
    });

    function updateExpirationStatus() {
        const expirationDate = new Date(document.getElementById('expiration_date').value);
        const today = new Date();
        const diffTime = expirationDate - today;
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        
        const statusElement = document.getElementById('expirationStatus');
        
        if (isNaN(diffDays)) {
            statusElement.innerHTML = '<span class="text-muted"><i class="fas fa-calendar"></i> Informe uma data válida</span>';
            return;
        }
        
        let statusClass, icon, message;
        
        if (diffDays < 0) {
            statusClass = 'danger';
            icon = 'fa-calendar-times';
            message = Math.abs(diffDays) + ' dias desde a validade (VENCIDO)';
        } else if (diffDays < 30) {
            statusClass = 'warning';
            icon = 'fa-calendar-minus';
            message = diffDays + ' dias para a validade (PRÓXIMO)';
        } else {
            statusClass = 'success';
            icon = 'fa-calendar-check';
            message = diffDays + ' dias para a validade';
        }
        
        statusElement.innerHTML = `<span class="text-${statusClass}"><i class="fas ${icon}"></i> ${message}</span>`;
    }

    function updateStockStatus() {
        const stock = parseInt(document.getElementById('stock').value);
        const minimumStock = parseInt(document.getElementById('minimum_stock').value);
        
        if (isNaN(stock) || isNaN(minimumStock) || minimumStock <= 0) {
            return;
        }
        
        const progress = Math.min(100, (stock / Math.max(minimumStock * 3, 1)) * 100);
        let progressColor;
        
        if (stock > minimumStock * 2) {
            progressColor = 'success';
        } else if (stock > minimumStock) {
            progressColor = 'warning';
        } else {
            progressColor = 'danger';
        }
        
        // Atualizar a barra de progresso visual
        const progressBar = document.querySelector('.stock-progress-bar');
        if (progressBar) {
            progressBar.style.width = progress + '%';
            progressBar.className = `stock-progress-bar bg-${progressColor}`;
        }
        
        // Atualizar o texto
        const statusText = document.querySelector('.stock-status small:last-child');
        if (statusText) {
            statusText.textContent = `${stock} / ${minimumStock * 3}`;
            statusText.className = `text-${progressColor}`;
        }
    }

    // Máscara para o lote (opcional)
    document.getElementById('batch').addEventListener('input', function(e) {
        this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
    });
</script>
@endpush