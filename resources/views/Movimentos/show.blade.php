@extends('layouts.admin')

@section('title', 'Detalhes da Movimentação')

@section('content')
<div class="container-fluid">
    <!-- Cabeçalho -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-info-circle"></i> Detalhes da Movimentação
        </h1>
        <a href="{{ route('movements.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Voltar para Lista
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Informações da Movimentação</h6>
                    <span class="badge badge-{{ $movement->type === 'entrada' ? 'success' : 'danger' }}">
                        {{ $movement->type === 'entrada' ? 'Entrada' : 'Saída' }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6 text-center">
                            <div class="movement-icon rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3
                                {{ $movement->type === 'entrada' ? 'bg-success text-white' : 'bg-danger text-white' }}">
                                <i class="fas {{ $movement->type === 'entrada' ? 'fa-arrow-down' : 'fa-arrow-up' }}"></i>
                            </div>
                            <h4 class="h5 font-weight-bold text-gray-800">
                                {{ $movement->type === 'entrada' ? 'Entrada' : 'Saída' }} de Estoque
                            </h4>
                            <h2 class="h3 font-weight-bold {{ $movement->type === 'entrada' ? 'text-success' : 'text-danger' }}">
                                {{ $movement->type === 'entrada' ? '+' : '-' }}{{ $movement->quantity }}
                            </h2>
                        </div>
                        <div class="col-md-6">
                            <div class="medicine-info">
                                <h4 class="h5 font-weight-bold text-gray-800">{{ $movement->medicine->name }}</h4>
                                <p class="mb-1">Código: <strong>{{ $movement->medicine->id }}</strong></p>
                                <p class="mb-0">Estoque atual: <strong>{{ $movement->medicine->stock }} unidades</strong></p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <strong><i class="fas fa-calendar-alt text-primary mr-2"></i>Data da Movimentação:</strong>
                                <p class="text-muted mb-0">{{ $movement->movement_date->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <strong><i class="fas fa-user text-primary mr-2"></i>Usuário Responsável:</strong>
                                <p class="text-muted mb-0">{{ $movement->user->name }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <strong><i class="fas fa-clock text-primary mr-2"></i>Registrado em:</strong>
                                <p class="text-muted mb-0">{{ $movement->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <strong><i class="fas fa-sync-alt text-primary mr-2"></i>Atualizado em:</strong>
                                <p class="text-muted mb-0">{{ $movement->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="info-item">
                        <strong><i class="fas fa-sticky-note text-primary mr-2"></i>Motivo:</strong>
                        <p class="text-muted mb-0 mt-2 p-3 bg-light rounded">
                            {{ $movement->reason ?? 'Não informado' }}
                        </p>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('movements.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i> Voltar para Lista
                    </a>
                    <a href="{{ route('medicines.show', $movement->medicine) }}" class="btn btn-primary">
                        <i class="fas fa-pills mr-2"></i> Ver Medicamento
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection