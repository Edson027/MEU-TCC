@extends('layouts.admin')
@section('title', 'Detalhes do Usuário')

@push('styles')
<style>
    .card {
        position: relative;
        display: flex;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 1px solid #e3e6f0;
        border-radius: 0.35rem;
    }
    .card-header {
        padding: 0.75rem 1.25rem;
        margin-bottom: 0;
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
    }
    .card-body {
        flex: 1 1 auto;
        min-height: 1px;
        padding: 1.25rem;
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
    .badge-primary {
        color: #fff;
        background-color: #4e73df;
    }
    .badge-purple {
        color: #fff;
        background-color: #6f42c1;
    }
    .badge-secondary {
        color: #fff;
        background-color: #858796;
    }
    .user-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
    }
    .avatar-placeholder {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background-color: #eaecf4;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .info-label {
        font-weight: 600;
        color: #4e73df;
        margin-bottom: 0.25rem;
    }
    .info-value {
        color: #6e707e;
        margin-bottom: 1rem;
    }
    .btn {
        display: inline-block;
        font-weight: 400;
        color: #858796;
        text-align: center;
        vertical-align: middle;
        cursor: pointer;
        user-select: none;
        background-color: transparent;
        border: 1px solid transparent;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        border-radius: 0.35rem;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, 
                    border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
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
    .btn-warning {
        color: #1f2d3d;
        background-color: #f6c23e;
        border-color: #f6c23e;
    }
    .btn-warning:hover {
        color: #1f2d3d;
        background-color: #f4b619;
        border-color: #f4b30d;
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
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Cabeçalho -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user-circle"></i> Detalhes do Usuário
        </h1>
        <a href="{{ route('users.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Voltar para Lista
        </a>
    </div>

    <!-- Card de Detalhes -->
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-gray-100">
                    <h6 class="m-0 font-weight-bold text-primary">Informações do Usuário</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        @if($user->profile_photo_path)
                            <img class="user-avatar mr-4" src="{{ asset($user->profile_photo_path) }}" alt="{{ $user->name }}">
                        @else
                            <div class="avatar-placeholder mr-4">
                                <i class="fas fa-user text-gray-400 fa-2x"></i>
                            </div>
                        @endif
                        <div>
                            <h4 class="font-weight-bold text-gray-800 mb-1">{{ $user->name }}</h4>
                            <p class="text-muted mb-2">{{ $user->email }}</p>
                            <span class="badge 
                                {{ $user->painel == 'administrador' ? 'badge-danger' :
                                   ($user->painel == 'gerente' ? 'badge-warning' :
                                   ($user->painel == 'enfermeira' ? 'badge-success' : 'badge-primary')) }}">
                                {{ $user->painel }}
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="info-label">Tipo de Painel</div>
                                <div class="info-value">
                                    <span class="badge 
                                        {{ $user->painel == 'administrador' ? 'badge-danger' :
                                           ($user->painel == 'gerente' ? 'badge-warning' :
                                           ($user->painel == 'enfermeira' ? 'badge-success' : 'badge-primary')) }}">
                                        {{ $user->painel }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="info-label">Recebe Notificações</div>
                                <div class="info-value">
                                    @if($user->receives_notifications)
                                        <span class="badge badge-success">
                                            <i class="fas fa-check mr-1"></i> Sim
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">
                                            <i class="fas fa-times mr-1"></i> Não
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="info-label">Email Verificado</div>
                                <div class="info-value">
                                    @if($user->email_verified_at)
                                        <span class="badge badge-success">
                                            <i class="fas fa-check mr-1"></i> {{ $user->email_verified_at->format('d/m/Y H:i') }}
                                        </span>
                                    @else
                                        <span class="badge badge-danger">
                                            <i class="fas fa-times mr-1"></i> Não verificado
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="info-label">Data de Criação</div>
                                <div class="info-value">{{ $user->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="info-label">Última Atualização</div>
                                <div class="info-value">{{ $user->updated_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-gray-100 d-flex justify-content-end">
                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning mr-2">
                        <i class="fas fa-edit mr-1"></i> Editar
                    </a>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Voltar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection