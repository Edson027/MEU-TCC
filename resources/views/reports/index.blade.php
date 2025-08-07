@extends('layouts.admin')

@section('title', 'Relatórios')

@push('styles')
<style>
    .report-card {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
        height: 100;
    }
    .report-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .report-card-primary {
        border-left-color: #4e73df;
    }
    .report-card-success {
        border-left-color: #1cc88a;
    }
    .report-card-purple {
        border-left-color: #6f42c1;
    }
    .report-icon {
        font-size: 1.5rem;
        margin-right: 10px;
    }
    .report-header {
        border-bottom: 1px solid rgba(255,255,255,0.2);
    }
    .report-button {
        transition: all 0.2s ease;
    }
    .report-button:hover {
        transform: translateY(-1px);
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Cabeçalho -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-file-alt"></i> Relatórios Analíticos
        </h1>
    </div>

    <!-- Cards de Relatórios -->
    <div class="row">
        <!-- Relatório de Estoque -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card report-card report-card-primary h-100">
                <div class="card-header py-3 report-header bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-boxes report-icon"></i> Relatório de Estoque
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-4">
                        <li class="mb-2">
                            <i class="fas fa-exclamation-triangle text-danger mr-2"></i>
                            <span>Medicamentos com estoque crítico</span>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-calendar-exclamation text-warning mr-2"></i>
                            <span>Próximos a vencer (30 dias)</span>
                        </li>
                        <li>
                            <i class="fas fa-chart-line text-purple mr-2"></i>
                            <span>Baixa rotatividade</span>
                        </li>
                    </ul>
                    <div class="d-grid gap-2">
                        <a href="{{ route('reports.stock') }}" class="btn btn-primary report-button">
                            Gerar Relatório
                        </a>
                        <a href="{{ route('reports.stock', ['pdf' => 1]) }}" class="btn btn-danger report-button">
                            <i class="fas fa-file-pdf mr-2"></i> Exportar PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Relatório de Consumo -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card report-card report-card-success h-100">
                <div class="card-header py-3 report-header bg-success text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-chart-bar report-icon"></i> Relatório de Consumo
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('reports.consumption') }}" method="GET">
                        <div class="mb-3">
                            <label class="form-label">Período:</label>
                            <select name="period" class="form-select">
                                <option value="week">Última semana</option>
                                <option value="month" selected>Último mês</option>
                                <option value="year">Último ano</option>
                            </select>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success report-button">
                                Visualizar
                            </button>
                            <button type="submit" name="pdf" value="1" class="btn btn-danger report-button">
                                <i class="fas fa-file-pdf mr-2"></i> Exportar PDF
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Relatório de Solicitações -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card report-card report-card-purple h-100">
                <div class="card-header py-3 report-header bg-purple text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-clipboard-list report-icon"></i> Relatório de Solicitações
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('reports.requests') }}" method="GET">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Status:</label>
                                <select name="status" class="form-select">
                                    <option value="all">Todos</option>
                                    <option value="pending">Pendentes</option>
                                    <option value="approved">Aprovados</option>
                                    <option value="partial">Parciais</option>
                                    <option value="rejected">Rejeitados</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Urgência:</label>
                                <select name="urgency" class="form-select">
                                    <option value="all">Todas</option>
                                    <option value="normal">Normal</option>
                                    <option value="urgente">Urgente</option>
                                    <option value="muito_urgente">Muito Urgente</option>
                                </select>
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-purple report-button text-white">
                                Visualizar
                            </button>
                            <button type="submit" name="pdf" value="1" class="btn btn-danger report-button">
                                <i class="fas fa-file-pdf mr-2"></i> Exportar PDF
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection