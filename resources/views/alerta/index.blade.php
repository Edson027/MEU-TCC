@extends('layouts.admin')

@section('title', 'Monitor de Estoque')

@push('styles')
<style>
    /* Estilos base do template */
    .stat-card {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .stat-card-primary {
        border-left-color: #4e73df;
    }
    .stat-card-warning {
        border-left-color: #f6c23e;
    }
    .stat-card-danger {
        border-left-color: #e74a3b;
    }
    .stat-card-success {
        border-left-color: #1cc88a;
    }
    .stat-icon {
        font-size: 2rem;
        opacity: 0.3;
    }
    
    /* Estilos específicos para estoque */
    .stock-level-critical {
        background-color: rgba(231, 76, 60, 0.1);
        border-left: 4px solid #e74a3b;
    }
    .stock-level-warning {
        background-color: rgba(246, 194, 62, 0.1);
        border-left: 4px solid #f6c23e;
    }
    .stock-level-normal {
        background-color: rgba(28, 200, 138, 0.1);
        border-left: 4px solid #1cc88a;
    }
    .stock-level-out {
        background-color: rgba(108, 117, 125, 0.1);
        border-left: 4px solid #6c757d;
    }
    
    .stock-badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
    }
    .stock-progress {
        height: 5px;
        border-radius: 3px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Cabeçalho -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-pills"></i> Monitor de Estoque de Medicamentos
        </h1>
        <div>
            <a href="{{ route('medicines.create') }}" class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Novo Medicamento
            </a>
            <a href="{{ route('stock.export') }}" class="btn btn-sm btn-success shadow-sm ml-2">
                <i class="fas fa-file-export fa-sm text-white-50"></i> Exportar
            </a>
        </div>
    </div>

    <!-- Legenda dos Níveis de Estoque -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body py-2">
                    <div class="d-flex flex-wrap align-items-center">
                        <div class="legend-item mr-3"><span class="legend-color bg-danger"></span> Crítico (abaixo de 20%)</div>
                        <div class="legend-item mr-3"><span class="legend-color bg-warning"></span> Alerta (abaixo de 50%)</div>
                        <div class="legend-item mr-3"><span class="legend-color bg-success"></span> Normal (acima de 50%)</div>
                        <div class="legend-item mr-3"><span class="legend-color bg-secondary"></span> Esgotado</div>
                        <div class="legend-item"><i class="fas fa-bell text-danger"></i> Notificações ativas</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cards de Resumo -->
    <div class="row">
        <!-- Total de Medicamentos -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card stat-card-primary h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Medicamentos Cadastrados
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_medicines'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-pills stat-icon text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estoque Crítico -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card stat-card-danger h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Estoque Crítico
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['critical_stock'] }}</div>
                            <div class="mt-2 mb-0 text-xs">
                                <span class="text-danger font-weight-bold">
                                    <i class="fas fa-arrow-down"></i> {{ $stats['critical_percentage'] }}% do total
                                </span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle stat-icon text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estoque Baixo -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card stat-card-warning h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Estoque Baixo
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['low_stock'] }}</div>
                            <div class="mt-2 mb-0 text-xs">
                                <span class="text-warning font-weight-bold">
                                    <i class="fas fa-arrow-down"></i> {{ $stats['low_percentage'] }}% do total
                                </span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-circle stat-icon text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estoque Normal -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card stat-card-success h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Estoque Normal
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['normal_stock'] }}</div>
                            <div class="mt-2 mb-0 text-xs">
                                <span class="text-success font-weight-bold">
                                    <i class="fas fa-check-circle"></i> {{ $stats['normal_percentage'] }}% do total
                                </span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check stat-icon text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de Medicamentos com Filtros -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list"></i> Lista de Medicamentos
            </h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" 
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-filter fa-sm fa-fw text-gray-400"></i> Filtrar
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" 
                     aria-labelledby="dropdownMenuLink">
                    <h6 class="dropdown-header">Nível de Estoque:</h6>
                    <a class="dropdown-item" href="{{ route('medicines.index') }}">Todos</a>
                    <a class="dropdown-item" href="{{ route('medicines.index', ['stock_level' => 'critical']) }}">
                        <span class="badge badge-danger">Crítico</span>
                    </a>
                    <a class="dropdown-item" href="{{ route('medicines.index', ['stock_level' => 'low']) }}">
                        <span class="badge badge-warning">Baixo</span>
                    </a>
                    <a class="dropdown-item" href="{{ route('medicines.index', ['stock_level' => 'normal']) }}">
                        <span class="badge badge-success">Normal</span>
                    </a>
                    <a class="dropdown-item" href="{{ route('medicines.index', ['stock_level' => 'out']) }}">
                        <span class="badge badge-secondary">Esgotado</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <h6 class="dropdown-header">Ordenar por:</h6>
                    <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'stock', 'order' => 'asc']) }}">Menor Estoque</a>
                    <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'stock', 'order' => 'desc']) }}">Maior Estoque</a>
                    <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'order' => 'asc']) }}">Nome (A-Z)</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>Nome</th>
                            <th>Lote</th>
                            <th>Validade</th>
                            <th>Estoque</th>
                            <th>Mínimo</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($medicines as $medicine)
                        <tr class="@if($medicine->stock <= 0) stock-level-out
                                    @elseif($medicine->stock < $medicine->minimum_stock * 0.2) stock-level-critical
                                    @elseif($medicine->stock < $medicine->minimum_stock) stock-level-warning
                                    @else stock-level-normal @endif">
                            <td>{{ $medicine->name }}</td>
                            <td>{{ $medicine->batch ?? 'N/A' }}</td>
                            <td @if($medicine->getExpirationStatusAttribute()) class="text-danger font-weight-bold" @endif>
                                {{ $medicine->expiration_date }}
                                @if($medicine->getExpirationStatusAttribute())
                                    <i class="fas fa-exclamation-circle ml-1"></i>
                                @endif
                            </td>
                            <td>{{ $medicine->stock }}</td>
                            <td>{{ $medicine->minimum_stock }}</td>
                            <td>
                                @if($medicine->stock <= 0)
                                    <span class="badge badge-secondary stock-badge">ESGOTADO</span>
                                @elseif($medicine->stock < $medicine->minimum_stock * 0.2)
                                    <span class="badge badge-danger stock-badge">CRÍTICO</span>
                                @elseif($medicine->stock < $medicine->minimum_stock)
                                    <span class="badge badge-warning stock-badge">ALERTA</span>
                                @else
                                    <span class="badge badge-success stock-badge">NORMAL</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('medicines.edit', $medicine->id) }}" 
                                       class="btn btn-sm btn-primary mr-1" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($medicine->stock < $medicine->minimum_stock)
                                    <button class="btn btn-sm btn-warning mr-1 stock-alert-btn" 
                                            data-medicine-id="{{ $medicine->id }}"
                                            data-medicine-name="{{ $medicine->name }}"
                                            title="Solicitar Reposição">
                                        <i class="fas fa-bell"></i>
                                    </button>
                                    @endif
                                    <form action="{{ route('medicines.destroy', $medicine->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Remover">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Paginação -->
            <div class="d-flex justify-content-center mt-3">
                {{ $medicines->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

    <!-- Alertas e Notificações -->
    <div class="row">
        <!-- Alertas de Estoque Crítico -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4 border-left-danger">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-danger text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-exclamation-triangle"></i> Alertas Críticos
                    </h6>
                    <span class="badge badge-light">{{ $criticalAlerts->count() }} alertas</span>
                </div>
                <div class="card-body">
                    @if($criticalAlerts->count() > 0)
                        <div class="list-group">
                            @foreach($criticalAlerts as $medicine)
                            <div class="list-group-item list-group-item-action flex-column align-items-start mb-2 border-left-danger">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">{{ $medicine->name }}</h5>
                                    <small class="text-danger font-weight-bold">CRÍTICO</small>
                                </div>
                                <p class="mb-1">Estoque: {{ $medicine->stock }} (Mínimo: {{ $medicine->minimum_stock }})</p>
                                <div class="progress mb-1 stock-progress">
                                    <div class="progress-bar bg-danger" 
                                         role="progressbar" 
                                         style="width: {{ ($medicine->stock / $medicine->minimum_stock) * 100 }}%" 
                                         aria-valuenow="{{ $medicine->stock }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="{{ $medicine->minimum_stock }}">
                                    </div>
                                </div>
                                <small>
                                    <i class="fas fa-clock"></i> 
                                    Último alerta: {{ $medicine->last_alerted_at ? $medicine->last_alerted_at->diffForHumans() : 'Nunca' }}
                                </small>
                                <div class="mt-2">
                                    <button class="btn btn-sm btn-outline-danger request-restock" 
                                            data-id="{{ $medicine->id }}">
                                        <i class="fas fa-truck"></i> Solicitar Reposição
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <h5 class="text-success">Nenhum alerta crítico no momento</h5>
                            <p class="text-muted">Todos os medicamentos estão com estoque seguro</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

     
    </div>
</div>

<!-- Modal para Solicitação de Reposição -->
<div class="modal fade" id="restockModal" tabindex="-1" role="dialog" aria-labelledby="restockModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="restockModalLabel">Solicitar Reposição</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="restockForm" method="POST">
                    @csrf
                    <input type="hidden" name="medicine_id" id="modalMedicineId">
                    
                    <div class="form-group">
                        <label for="medicineName">Medicamento</label>
                        <input type="text" class="form-control" id="medicineName" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label for="currentStock">Estoque Atual</label>
                        <input type="text" class="form-control" id="currentStock" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label for="minimumStock">Estoque Mínimo</label>
                        <input type="text" class="form-control" id="minimumStock" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label for="quantity">Quantidade para Repor</label>
                        <input type="number" class="form-control" name="quantity" id="quantity" min="1" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="priority">Prioridade</label>
                        <select class="form-control" name="priority" id="priority" required>
                            <option value="low">Baixa</option>
                            <option value="medium" selected>Média</option>
                            <option value="high">Alta</option>
                            <option value="urgent">Urgente</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="notes">Observações</label>
                        <textarea class="form-control" name="notes" id="notes" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-warning" id="submitRestock">Enviar Solicitação</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Inicialização do modal de reposição
    $('.stock-alert-btn, .request-restock').click(function() {
        const medicineId = $(this).data('medicine-id');
        const medicineName = $(this).data('medicine-name');
        
        // Aqui você pode fazer uma requisição AJAX para obter os dados atualizados
        // ou usar dados já disponíveis na página
        $('#modalMedicineId').val(medicineId);
        $('#medicineName').val(medicineName);
        
        // Exemplo com dados estáticos - substitua por uma chamada AJAX se necessário
        const row = $(this).closest('tr');
        $('#currentStock').val(row.find('td:eq(3)').text());
        $('#minimumStock').val(row.find('td:eq(4)').text());
        
        // Calcular quantidade sugerida para repor
        const minStock = parseInt(row.find('td:eq(4)').text());
        const currentStock = parseInt(row.find('td:eq(3)').text());
        const suggestedQty = minStock * 2 - currentStock;
        $('#quantity').val(suggestedQty > 0 ? suggestedQty : minStock);
        
        $('#restockModal').modal('show');
    });
    
    // Envio do formulário de reposição
    $('#submitRestock').click(function() {
        const form = $('#restockForm');
        const submitBtn = $(this);
        
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Enviando...');
        
        $.ajax({
            url: '{{ route("stock.request-restock") }}',
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                $('#restockModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Solicitação Enviada!',
                    text: 'A reposição foi solicitada com sucesso.',
                    timer: 3000
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: xhr.responseJSON.message || 'Ocorreu um erro ao enviar a solicitação.'
                });
            },
            complete: function() {
                submitBtn.prop('disabled', false).text('Enviar Solicitação');
            }
        });
    });
    
    // Configuração do gráfico de níveis de estoque
    const ctx = document.getElementById('stockChart').getContext('2d');
    const stockChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Crítico', 'Baixo', 'Normal', 'Esgotado'],
            datasets: [{
                data: [
                    {{$stats['critical_stock']}},
                    {{$stats['low_stock']}},
                    {{$stats['normal_stock']}},
                    {{$stats['out_of_stock']}}
                ],
                backgroundColor: [
                    '#e74a3b',
                    '#f6c23e',
                    '#1cc88a',
                    '#6c757d'
                ],
                hoverBackgroundColor: [
                    '#be2617',
                    '#dda20a',
                    '#17a673',
                    '#495057'
                ],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
                callbacks: {
                    label: function(tooltipItem, data) {
                        const dataset = data.datasets[tooltipItem.datasetIndex];
                        const total = dataset.data.reduce((previousValue, currentValue) => previousValue + currentValue);
                        const currentValue = dataset.data[tooltipItem.index];
                        const percentage = Math.floor(((currentValue/total) * 100)+0.5);
                        return data.labels[tooltipItem.index] + ': ' + currentValue + ' (' + percentage + '%)';
                    }
                }
            },
            legend: {
                display: true,
                position: 'right'
            },
            cutout: 70,
        },
    });
    
    // Configuração para notificações em tempo real
    window.Echo.channel('stock-alerts')
        .listen('LowStockEvent', (data) => {
            const level = data.medicine.stock <= 0 ? 'out_of_stock' : 
                         data.medicine.stock < data.medicine.minimum_stock * 0.2 ? 'critical' :
                         data.medicine.stock < data.medicine.minimum_stock ? 'warning' : 'normal';
            
            const colors = {
                'out_of_stock': '#6c757d',
                'critical': '#e74a3b',
                'warning': '#f6c23e',
                'normal': '#1cc88a'
            };
            
            const icons = {
                'out_of_stock': 'fa-box-open',
                'critical': 'fa-exclamation-triangle',
                'warning': 'fa-exclamation-circle',
                'normal': 'fa-check-circle'
            };
            
            // Notificação Toast
            Toast.fire({
                icon: 'warning',
                title: `ALERTA DE ESTOQUE - ${data.medicine.name}`,
                html: `<div class="text-left">
                          <strong>Status:</strong> <span class="text-${level === 'critical' || level === 'out_of_stock' ? 'danger' : 'warning'}">${level.toUpperCase().replace('_', ' ')}</span><br>
                          <strong>Estoque:</strong> ${data.medicine.stock}<br>
                          <strong>Mínimo:</strong> ${data.medicine.minimum_stock}
                       </div>`,
                timer: 10000,
                background: '#f8f9fa',
                iconColor: colors[level]
            });
            
            // Atualizar a tabela em tempo real
            const row = $(`tr[data-id="${data.medicine.id}"]`);
            if (row.length) {
                row.find('.stock-badge').removeClass('badge-danger badge-warning badge-success badge-secondary')
                    .addClass(level === 'critical' ? 'badge-danger' : 
                              level === 'warning' ? 'badge-warning' : 
                              level === 'normal' ? 'badge-success' : 'badge-secondary')
                    .text(level === 'critical' ? 'CRÍTICO' : 
                          level === 'warning' ? 'ALERTA' : 
                          level === 'normal' ? 'NORMAL' : 'ESGOTADO');
                
                row.removeClass('stock-level-critical stock-level-warning stock-level-normal stock-level-out')
                    .addClass(level === 'critical' ? 'stock-level-critical' : 
                              level === 'warning' ? 'stock-level-warning' : 
                              level === 'normal' ? 'stock-level-normal' : 'stock-level-out');
            }
        });
});
</script>
@endpush