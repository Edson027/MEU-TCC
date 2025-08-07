@extends('layouts.admin')

@section('title', 'Relatório de Reabastecimento')

@push('styles')
<style>
    .low-stock {
        background-color: #fff3cd !important;
    }
    .critical-stock {
        background-color: #f8d7da !important;
    }
    .good-stock {
        background-color: #d1e7dd !important;
    }
    .stock-indicator {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-right: 5px;
    }
    .indicator-low {
        background-color: #ffc107;
    }
    .indicator-critical {
        background-color: #dc3545;
    }
    .indicator-good {
        background-color: #198754;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Cabeçalho -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-chart-bar"></i> Relatório de Reabastecimento
        </h1>
        <div>
            <a href="{{ route('restock-orders.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Voltar
            </a>
            <button class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" onclick="window.print()">
                <i class="fas fa-print fa-sm text-white-50"></i> Imprimir
            </button>
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm" id="exportExcel">
                <i class="fas fa-file-excel fa-sm text-white-50"></i> Exportar
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body py-3">
                    <form method="GET" action="{{ route('restock-orders.report') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="category">Categoria</label>
                                    <select class="form-control" id="category" name="category">
                                        <option value="">Todas</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                                {{ $category }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="stock_status">Status de Estoque</label>
                                    <select class="form-control" id="stock_status" name="stock_status">
                                        <option value="">Todos</option>
                                        <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Estoque Baixo</option>
                                        <option value="critical" {{ request('stock_status') == 'critical' ? 'selected' : '' }}>Estoque Crítico</option>
                                        <option value="good" {{ request('stock_status') == 'good' ? 'selected' : '' }}>Estoque Bom</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="expiration_status">Status de Validade</label>
                                    <select class="form-control" id="expiration_status" name="expiration_status">
                                        <option value="">Todos</option>
                                        <option value="expired" {{ request('expiration_status') == 'expired' ? 'selected' : '' }}>Vencidos</option>
                                        <option value="expiring_soon" {{ request('expiration_status') == 'expiring_soon' ? 'selected' : '' }}>Próximos a Vencer</option>
                                        <option value="valid" {{ request('expiration_status') == 'valid' ? 'selected' : '' }}>Válidos</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="sort">Ordenar por</label>
                                    <select class="form-control" id="sort" name="sort">
                                        <option value="stock_asc" {{ request('sort') == 'stock_asc' ? 'selected' : '' }}>Estoque (Baixo → Alto)</option>
                                        <option value="stock_desc" {{ request('sort') == 'stock_desc' ? 'selected' : '' }}>Estoque (Alto → Baixo)</option>
                                        <option value="expiration_asc" {{ request('sort') == 'expiration_asc' ? 'selected' : '' }}>Validade (Próximos)</option>
                                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nome (A → Z)</option>
                                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nome (Z → A)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 text-right">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter"></i> Filtrar
                                </button>
                                <a href="{{ route('restock-orders.report') }}" class="btn btn-secondary">
                                    <i class="fas fa-undo"></i> Limpar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Produtos Analisados
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $products->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-boxes fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Estoque Baixo
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['low_stock'] }}</div>
                            <div class="mt-2 text-xs">
                                <span class="text-muted">{{ round(($stats['low_stock']/$products->total())*100, 1) }}% do total</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Estoque Crítico
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['critical_stock'] }}</div>
                            <div class="mt-2 text-xs">
                                <span class="text-muted">{{ round(($stats['critical_stock']/$products->total())*100, 1) }}% do total</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-skull-crossbones fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Próximos a Vencer (30 dias)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['expiring_soon'] }}</div>
                            <div class="mt-2 text-xs">
                                <span class="text-muted">{{ round(($stats['expiring_soon']/$products->total())*100, 1) }}% do total</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-times fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de Produtos -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Lista de Produtos para Reabastecimento</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" 
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" 
                             aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Exportar:</div>
                            <a class="dropdown-item" href="#" id="exportPdf"><i class="fas fa-file-pdf text-danger"></i> PDF</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" id="generateOrder"><i class="fas fa-cart-plus text-primary"></i> Gerar Pedido</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr>
                                    <th width="40px">
                                        <input type="checkbox" id="selectAll">
                                    </th>
                                    <th>Produto</th>
                                    <th>Categoria</th>
                                    <th>Estoque</th>
                                    <th>Mínimo</th>
                                    <th>Status</th>
                                    <th>Validade</th>
                                    <th>Recomendação</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                @php
                                    $stockPercentage = ($product->stock / $product->minimum_stock) * 100;
                                    $stockClass = $product->stock == 0 ? 'critical-stock' : 
                                                ($stockPercentage < 50 ? 'low-stock' : 
                                                ($stockPercentage < 100 ? '' : 'good-stock'));
                                    $stockIndicator = $product->stock == 0 ? 'indicator-critical' : 
                                                   ($stockPercentage < 50 ? 'indicator-low' : 'indicator-good');
                                    $expiringClass = $product->expiration_date && $product->expiration_date->diffInDays(now()) <= 30 ? 
                                                   'text-danger font-weight-bold' : '';
                                @endphp
                                <tr class="{{ $stockClass }}">
                                    <td>
                                        <input type="checkbox" class="product-check" data-id="{{ $product->id }}" 
                                               data-name="{{ $product->name }}" 
                                               data-recommendation="{{ max($product->minimum_stock - $product->stock, 1) }}">
                                    </td>
                                    <td>
                                        <a href="{{ route('products.show', $product->id) }}">
                                            {{ $product->name }}
                                        </a>
                                        @if($product->batch)
                                            <br><small class="text-muted">Lote: {{ $product->batch }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $product->category ?? '--' }}</td>
                                    <td>
                                        {{ $product->stock }}
                                        <div class="progress mt-2" style="height: 5px;">
                                            <div class="progress-bar bg-{{ $product->stock == 0 ? 'danger' : ($stockPercentage < 50 ? 'warning' : 'success') }}" 
                                                 role="progressbar" 
                                                 style="width: {{ min(100, $stockPercentage) }}%" 
                                                 aria-valuenow="{{ $product->stock }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="{{ $product->minimum_stock * 2 }}">
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $product->minimum_stock }}</td>
                                    <td>
                                        <span class="stock-indicator {{ $stockIndicator }}"></span>
                                        @if($product->stock == 0)
                                            Crítico
                                        @elseif($stockPercentage < 50)
                                            Baixo
                                        @elseif($stockPercentage < 100)
                                            Atenção
                                        @else
                                            Bom
                                        @endif
                                    </td>
                                    <td class="{{ $expiringClass }}">
                                        @if($product->expiration_date)
                                            {{ $product->expiration_date->format('d/m/Y') }}
                                            @if($product->expiration_date->isPast())
                                                <br><small class="text-danger">VENCIDO</small>
                                            @elseif($product->expiration_date->diffInDays(now()) <= 30)
                                                <br><small class="text-danger">Vence em {{ $product->expiration_date->diffInDays(now()) }} dias</small>
                                            @endif
                                        @else
                                            --
                                        @endif
                                    </td>
                                    <td>
                                        @if($product->stock < $product->minimum_stock)
                                            {{ max($product->minimum_stock - $product->stock, 1) }} unidades
                                        @else
                                            --
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('restock-orders.create', ['product_id' => $product->id]) }}" 
                                           class="btn btn-sm btn-primary" title="Solicitar Reabastecimento">
                                            <i class="fas fa-cart-plus"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Gerar Pedido -->
<div class="modal fade" id="generateOrderModal" tabindex="-1" role="dialog" aria-labelledby="generateOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="generateOrderModalLabel">Gerar Pedido de Reabastecimento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('restock-orders.mass-create') }}">
                @csrf
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th>Estoque Atual</th>
                                    <th>Mínimo</th>
                                    <th>Quantidade Recomendada</th>
                                    <th>Quantidade Solicitada</th>
                                </tr>
                            </thead>
                            <tbody id="selectedProducts">
                                <!-- Produtos selecionados serão adicionados aqui via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group mt-3">
                        <label for="notes">Observações</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Enviar Pedido</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
<script>
    $(document).ready(function() {
        // Selecionar todos os produtos
        $('#selectAll').change(function() {
            $('.product-check').prop('checked', $(this).prop('checked'));
        });

        // Exportar para Excel
        $('#exportExcel').click(function(e) {
            e.preventDefault();
            
            // Criar uma cópia da tabela sem as colunas de ações e seleção
            var table = $('#dataTable').clone();
            table.find('th:first-child, td:first-child, td:last-child').remove();
            
            // Criar planilha
            var wb = XLSX.utils.table_to_book(table[0], {sheet:"Relatório"});
            
            // Gerar arquivo
            XLSX.writeFile(wb, 'relatorio_reabastecimento.xlsx');
        });

        // Exportar para PDF
        $('#exportPdf').click(function(e) {
            e.preventDefault();
            
            // Criar uma cópia da tabela sem as colunas de ações e seleção
            var table = $('#dataTable').clone();
            table.find('th:first-child, td:first-child, td:last-child').remove();
            
            // Configurar PDF
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            
            doc.autoTable({
                html: table[0],
                margin: { top: 20 },
                styles: {
                    fontSize: 8,
                    cellPadding: 2,
                    valign: 'middle'
                },
                headStyles: {
                    fillColor: [78, 115, 223],
                    textColor: 255,
                    fontSize: 9
                },
                alternateRowStyles: {
                    fillColor: [240, 240, 240]
                },
                didDrawPage: function(data) {
                    // Cabeçalho
                    doc.setFontSize(16);
                    doc.setTextColor(40);
                    doc.text('Relatório de Reabastecimento', data.settings.margin.left, 10);
                    
                    // Rodapé
                    doc.setFontSize(10);
                    doc.text('Gerado em: ' + new Date().toLocaleDateString(), data.settings.margin.left, doc.internal.pageSize.height - 10);
                }
            });
            
            doc.save('relatorio_reabastecimento.pdf');
        });

        // Gerar pedido para produtos selecionados
        $('#generateOrder').click(function(e) {
            e.preventDefault();
            
            var selectedProducts = [];
            $('.product-check:checked').each(function() {
                selectedProducts.push({
                    id: $(this).data('id'),
                    name: $(this).data('name'),
                    recommendation: $(this).data('recommendation')
                });
            });
            
            if (selectedProducts.length === 0) {
                alert('Por favor, selecione pelo menos um produto.');
                return;
            }
            
            // Limpar tabela de produtos selecionados
            $('#selectedProducts').empty();
            
            // Adicionar produtos selecionados à tabela
            selectedProducts.forEach(function(product) {
                var row = `
                    <tr>
                        <td>
                            ${product.name}
                            <input type="hidden" name="products[]" value="${product.id}">
                        </td>
                        <td class="text-center">--</td>
                        <td class="text-center">--</td>
                        <td class="text-center">${product.recommendation}</td>
                        <td>
                            <input type="number" 
                                   class="form-control form-control-sm" 
                                   name="quantities[]" 
                                   value="${product.recommendation}" 
                                   min="1" 
                                   required>
                        </td>
                    </tr>
                `;
                $('#selectedProducts').append(row);
            });
            
            // Mostrar modal
            $('#generateOrderModal').modal('show');
        });
    });
</script>
@endpush