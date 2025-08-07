@extends('layouts.admin')

@section('title', 'Novo Pedido de Reabastecimento')

@section('content')
<div class="container-fluid">
    <!-- Cabeçalho -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-cart-plus"></i> Novo Pedido de Reabastecimento
        </h1>
        <a href="{{ route('restock-orders.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Voltar
        </a>
    </div>

    <!-- Formulário -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informações do Pedido</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('restock-orders.store') }}"  method="POST" >
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="product_id">Produto *</label>
                                    <select class="form-control select2 @error('product_id') is-invalid @enderror" 
                                            id="product_id" name="product_id" required>
                                        <option value="">Selecione um produto</option>
                                        @foreach($medicine as $product)
                                            <option value="{{ $product->id }}" 
                                                {{ old('product_id') == $product->id ? 'selected' : '' }}
                                                data-stock="{{ $product->stock }}"
                                                data-minimum="{{ $product->minimum_stock }}">
                                                {{ $product->name }} 
                                                (Estoque: {{ $product->stock }} | Mínimo: {{ $product->minimum_stock }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('product_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="quantity_requested">Quantidade Solicitada *</label>
                                    <input type="number" 
                                           class="form-control @error('quantity_requested') is-invalid @enderror" 
                                           id="quantity_requested" 
                                           name="quantity_requested" 
                                           value="{{ old('quantity_requested') }}" 
                                           min="1" 
                                           required>
                                    @error('quantity_requested')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Quantidade mínima recomendada: <span id="recommended-quantity">0</span>
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="notes">Observações</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> 
                                    <strong>Informação:</strong> Este pedido será enviado para aprovação e 
                                    aparecerá na lista de pedidos pendentes.
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 text-right">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Salvar Pedido
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container .select2-selection--single {
        height: 38px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Inicializar Select2
        $('.select2').select2({
            placeholder: "Selecione um produto",
            allowClear: true
        });

        // Calcular quantidade recomendada quando o produto muda
        $('#product_id').change(function() {
            var selectedOption = $(this).find('option:selected');
            var currentStock = selectedOption.data('stock');
            var minimumStock = selectedOption.data('minimum');
            
            var recommended = minimumStock - currentStock;
            recommended = recommended > 0 ? recommended : 1;
            
            $('#recommended-quantity').text(recommended);
            $('#quantity_requested').val(recommended).attr('min', 1);
        });

        // Validação do formulário
        $('form').submit(function(e) {
            if ($('#product_id').val() === '') {
                e.preventDefault();
                alert('Por favor, selecione um produto.');
                $('#product_id').focus();
            }
        });
    });
</script>
@endpush