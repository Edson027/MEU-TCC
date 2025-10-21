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
    .btn-success {
        color: #fff;
        background-color: #1cc88a;
        border-color: #1cc88a;
    }
    .btn-success:hover {
        color: #fff;
        background-color: #17a673;
        border-color: #169b6b;
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
    .alert-warning {
        background-color: #fef8e6;
        border-left-color: #f6c23e;
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
    .status-indicator {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        border-radius: 0.35rem;
        font-size: 0.875rem;
        font-weight: 600;
        margin-left: 0.5rem;
    }
    .generate-batch-btn {
        margin-top: 0.5rem;
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
            <form action="{{ route('medicines.store') }}" method="POST" id="medicineForm">
                @csrf
                
                <div class="info-box">
                    <i class="fas fa-info-circle text-primary"></i>
                    <span class="ml-2">Campos marcados com * são obrigatórios. Preencha todas as informações com atenção.</span>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name" class="form-label">Nome do Medicamento *</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                   class="form-control" required maxlength="255"
                                   placeholder="Digite o nome comercial do medicamento"
                                   oninput="generateBatchSuggestion()">
                            @error('name') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="category" class="form-label">Categoria *</label>
                            <select name="category" id="category" class="form-control" required>
                                <option value="">Selecione a categoria</option>
                                <option value="Antibióticos" {{ old('category') == 'Antibióticos' ? 'selected' : '' }}>Antibióticos</option>
                                <option value="Antifúngicos" {{ old('category') == 'Antifúngicos' ? 'selected' : '' }}>Antifúngicos</option>
                                <option value="Antiparasitários" {{ old('category') == 'Antiparasitários' ? 'selected' : '' }}>Antiparasitários</option>
                                <option value="Anti-hipertensivos" {{ old('category') == 'Anti-hipertensivos' ? 'selected' : '' }}>Anti-hipertensivos</option>
                                <option value="Diuréticos" {{ old('category') == 'Diuréticos' ? 'selected' : '' }}>Diuréticos</option>
                                <option value="Anticoagulantes" {{ old('category') == 'Anticoagulantes' ? 'selected' : '' }}>Anticoagulantes</option>
                                <option value="Antiarrítmicos" {{ old('category') == 'Antiarrítmicos' ? 'selected' : '' }}>Antiarrítmicos</option>
                                <option value="Analgésicos" {{ old('category') == 'Analgésicos' ? 'selected' : '' }}>Analgésicos</option>
                                <option value="Antidepressivos" {{ old('category') == 'Antidepressivos' ? 'selected' : '' }}>Antidepressivos</option>
                                <option value="Antipsicóticos" {{ old('category') == 'Antipsicóticos' ? 'selected' : '' }}>Antipsicóticos</option>
                                <option value="Antivirais" {{ old('category') == 'Antivirais' ? 'selected' : '' }}>Antivirais</option>
                                <option value="Hormônios" {{ old('category') == 'Hormônios' ? 'selected' : '' }}>Hormônios</option>
                                <option value="Vitaminas" {{ old('category') == 'Vitaminas' ? 'selected' : '' }}>Vitaminas</option>
                                <option value="Outros" {{ old('category') == 'Outros' ? 'selected' : '' }}>Outros</option>
                            </select>
                            @error('category') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                       <div class="col-md-6">
        <div class="form-group">
            <label for="fornecedor_id" class="form-label">Fornecedor *</label>
            <select name="fornecedor_id" id="fornecedor_id" class="form-control" required>
                <option value="">Selecione o fornecedor</option>
                @foreach($fornecedor as $fornecedores)
                    <option value="{{ $fornecedores->id }}" {{ old('fornecedor_id') == $fornecedores->id ? 'selected' : '' }}>
                        {{ $fornecedores->nome }}
                    </option>
                @endforeach
            </select>
            @error('fornecedor_id') 
                <span class="text-danger text-sm">{{ $message }}</span> 
            @enderror
        </div>
    </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="batch" class="form-label">Lote *</label>
                            <input type="text" name="batch" id="batch" value="{{ old('batch') }}" 
                                   class="form-control" required maxlength="50"
                                   placeholder="Ex: LOT202401A">
                            <div class="batch-info">
                                <i class="fas fa-info-circle"></i> Identificação única do lote do medicamento
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary generate-batch-btn" onclick="generateBatch()">
                                <i class="fas fa-magic"></i> Gerar Lote Automaticamente
                            </button>
                            @error('batch') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="expiration_date" class="form-label">Data de Validade *</label>
                            <input type="date" name="expiration_date" id="expiration_date" 
                                   value="{{ old('expiration_date') }}" 
                                   class="form-control" required min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                   onchange="updateExpirationStatus()">
                            <div id="expirationStatus" class="mt-1">
                                <span class="text-muted">
                                    <i class="fas fa-calendar"></i> Informe a data de validade
                                </span>
                            </div>
                            @error('expiration_date') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="stock" class="form-label">Estoque Inicial *</label>
                            <input type="number" name="stock" id="stock" value="{{ old('stock', 0) }}" 
                                   min="0" max="10000" class="form-control" required
                                   onchange="updateStockStatus()">
                            <div class="batch-info">
                                <i class="fas fa-info-circle"></i> Quantidade inicial em estoque
                            </div>
                            @error('stock') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="minimum_stock" class="form-label">Estoque Mínimo *</label>
                            <input type="number" name="minimum_stock" id="minimum_stock" 
                                   value="{{ old('minimum_stock', 10) }}" min="1" max="1000" class="form-control" required
                                   onchange="updateStockStatus()">
                            <div class="batch-info">
                                <i class="fas fa-info-circle"></i> Quantidade mínima para alertas de estoque
                            </div>
                            <div id="stockStatus" class="stock-status">
                                <small>Status:</small>
                                <div class="stock-progress">
                                    <div class="stock-progress-bar bg-success" style="width: 0%"></div>
                                </div>
                                <small class="text-success">0 / 0</small>
                            </div>
                            @error('minimum_stock') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Descrição e Observações</label>
                    <textarea name="description" id="description" rows="4" 
                              class="form-control" placeholder="Informações adicionais sobre o medicamento, como dosagem, contraindicações, etc."
                              maxlength="500">{{ old('description') }}</textarea>
                    <div class="batch-info">
                        <i class="fas fa-info-circle"></i> Máximo de 500 caracteres
                        <span id="charCount" class="float-right">0/500</span>
                    </div>
                    @error('description') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Alertas Preventivos -->
                <div id="alertsContainer">
                    <div class="alert-box alert-warning" id="lowStockAlert" style="display: none;">
                        <i class="fas fa-exclamation-triangle text-warning"></i>
                        <span class="ml-2">O estoque inicial está abaixo do estoque mínimo recomendado.</span>
                    </div>
                    
                    <div class="alert-box alert-warning" id="expirationAlert" style="display: none;">
                        <i class="fas fa-calendar-exclamation text-warning"></i>
                        <span class="ml-2" id="expirationAlertText"></span>
                    </div>
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

    <!-- Card de Informações Adicionais -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-gray-100">
            <h6 class="m-0 font-weight-bold text-primary">Dicas para Cadastro</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6><i class="fas fa-lightbulb text-warning"></i> Sobre Lotes:</h6>
                    <ul class="small text-muted">
                        <li>Utilize um padrão consistente para os lotes (ex: LOT202401A)</li>
                        <li>O lote deve ser único para cada medicamento</li>
                        <li>Use o botão "Gerar Lote Automaticamente" para sugestões</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6><i class="fas fa-lightbulb text-warning"></i> Sobre Estoque:</h6>
                    <ul class="small text-muted">
                        <li>Defina um estoque mínimo realista baseado no consumo</li>
                        <li>Considere o tempo de reposição ao definir estoques</li>
                        <li>Estoque inicial pode ser zero para medicamentos novos</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar contador de caracteres
        updateCharCount();
        
        // Inicializar status
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
            
            const stock = parseInt(document.getElementById('stock').value);
            const minimumStock = parseInt(document.getElementById('minimum_stock').value);
            
            if (stock < 0) {
                alert('O estoque não pode ser negativo.');
                e.preventDefault();
                return false;
            }
            
            if (minimumStock < 1) {
                alert('O estoque mínimo deve ser pelo menos 1 unidade.');
                e.preventDefault();
                return false;
            }
            
            // Verificar se o lote já existe (simulação)
            const batch = document.getElementById('batch').value;
            if (!batch) {
                alert('Por favor, informe um número de lote.');
                e.preventDefault();
                return false;
            }
        });
    });

    function updateCharCount() {
        const textarea = document.getElementById('description');
        const charCount = document.getElementById('charCount');
        const length = textarea.value.length;
        charCount.textContent = length + '/500';
        
        if (length > 450) {
            charCount.className = 'float-right text-warning';
        } else {
            charCount.className = 'float-right text-muted';
        }
    }

    function generateBatch() {
        const name = document.getElementById('name').value;
        const category = document.getElementById('category').value;
        const now = new Date();
        
        // Gerar sugestão de lote baseada no nome, categoria e data
        let batchSuggestion = 'LOT';
        batchSuggestion += now.getFullYear();
        batchSuggestion += String(now.getMonth() + 1).padStart(2, '0');
        
        if (category) {
            batchSuggestion += category.substring(0, 2).toUpperCase();
        }
        
        if (name) {
            // Pegar iniciais do nome
            const initials = name.split(' ')
                .filter(word => word.length > 2)
                .map(word => word[0])
                .join('')
                .toUpperCase();
            
            if (initials) {
                batchSuggestion += initials.substring(0, 3);
            }
        }
        
        batchSuggestion += Math.floor(Math.random() * 100).toString().padStart(2, '0');
        
        document.getElementById('batch').value = batchSuggestion;
    }

    function generateBatchSuggestion() {
        // Gerar sugestão apenas se o campo lote estiver vazio
        const batchField = document.getElementById('batch');
        if (!batchField.value) {
            generateBatch();
        }
    }

    function updateExpirationStatus() {
        const expirationDate = new Date(document.getElementById('expiration_date').value);
        const today = new Date();
        const diffTime = expirationDate - today;
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        
        const statusElement = document.getElementById('expirationStatus');
        const alertElement = document.getElementById('expirationAlert');
        const alertTextElement = document.getElementById('expirationAlertText');
        
        if (isNaN(diffDays)) {
            statusElement.innerHTML = '<span class="text-muted"><i class="fas fa-calendar"></i> Informe uma data válida</span>';
            alertElement.style.display = 'none';
            return;
        }
        
        let statusClass, icon, message;
        let showAlert = false;
        
        if (diffDays < 0) {
            statusClass = 'danger';
            icon = 'fa-calendar-times';
            message = Math.abs(diffDays) + ' dias desde a validade (VENCIDO)';
            showAlert = true;
            alertTextElement.textContent = 'Este medicamento está vencido. Não recomendado para cadastro.';
        } else if (diffDays < 30) {
            statusClass = 'warning';
            icon = 'fa-calendar-minus';
            message = diffDays + ' dias para a validade (PRÓXIMO)';
            showAlert = true;
            alertTextElement.textContent = 'Este medicamento vencerá em breve. Considere estoques menores.';
        } else if (diffDays < 90) {
            statusClass = 'warning';
            icon = 'fa-calendar-minus';
            message = diffDays + ' dias para a validade';
            showAlert = true;
            alertTextElement.textContent = 'Medicamento com validade curta. Planeje o consumo adequadamente.';
        } else {
            statusClass = 'success';
            icon = 'fa-calendar-check';
            message = diffDays + ' dias para a validade';
            showAlert = false;
        }
        
        statusElement.innerHTML = `<span class="text-${statusClass}"><i class="fas ${icon}"></i> ${message}</span>`;
        alertElement.style.display = showAlert ? 'block' : 'none';
    }

    function updateStockStatus() {
        const stock = parseInt(document.getElementById('stock').value) || 0;
        const minimumStock = parseInt(document.getElementById('minimum_stock').value) || 1;
        
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
        const statusText = document.querySelector('#stockStatus small:last-child');
        if (statusText) {
            statusText.textContent = `${stock} / ${minimumStock * 3}`;
            statusText.className = `text-${progressColor}`;
        }
        
        // Mostrar alerta se estoque estiver baixo
        const lowStockAlert = document.getElementById('lowStockAlert');
        if (stock < minimumStock && stock > 0) {
            lowStockAlert.style.display = 'block';
        } else {
            lowStockAlert.style.display = 'none';
        }
    }

    // Inicializar o evento de input para o textarea
    document.getElementById('description').addEventListener('input', updateCharCount);

    // Máscara para o lote (opcional)
    document.getElementById('batch').addEventListener('input', function(e) {
        this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
    });
</script>
@endpush