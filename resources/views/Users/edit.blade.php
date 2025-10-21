@extends('layouts.admin')
@section('title', 'Editar Usuário')

@push('styles')
<style>
    .form-control {
        display: block;
        width: 100%;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        font-weight: 400;
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
    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #4e73df;
    }
    .form-group {
        margin-bottom: 1.5rem;
    }
    .form-check {
        position: relative;
        display: block;
        padding-left: 1.25rem;
    }
    .form-check-input {
        position: absolute;
        margin-top: 0.3rem;
        margin-left: -1.25rem;
    }
    .form-check-label {
        margin-bottom: 0;
        color: #858796;
    }
    .text-danger {
        color: #e74a3b !important;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
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
    .user-info-header {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    .user-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 15px;
    }
    .avatar-placeholder {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background-color: #eaecf4;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Cabeçalho -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user-edit"></i> Editar Usuário
        </h1>
        <a href="{{ route('users.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Voltar para Lista
        </a>
    </div>

    <!-- Formulário -->
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-gray-100">
                    <h6 class="m-0 font-weight-bold text-primary">Informações do Usuário</h6>
                </div>
                <div class="card-body">
                    <div class="user-info-header">
                        @if($user->profile_photo_path)
                            <img class="user-avatar" src="{{ asset($user->profile_photo_path) }}" alt="{{ $user->name }}">
                        @else
                            <div class="avatar-placeholder">
                                <i class="fas fa-user text-gray-400 fa-2x"></i>
                            </div>
                        @endif
                        <div>
                            <h5 class="font-weight-bold text-gray-800">{{ $user->name }}</h5>
                            <p class="text-muted mb-0">{{ $user->email }}</p>
                            <span class="badge 
                                {{ $user->painel == 'administrador' ? 'badge-danger' :
                                   ($user->painel == 'gerente' ? 'badge-warning' :
                                   ($user->painel == 'enfermeiro' ? 'badge-success' : 'badge-primary')) }}">
                                {{ $user->painel }}
                            </span>
                        </div>
                    </div>

                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="name" class="form-label">Nome</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                   class="form-control @error('name') is-invalid @enderror">
                            @error('name')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                   class="form-control @error('email') is-invalid @enderror">
                            @error('email')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password" class="form-label">Nova Senha (deixe em branco para manter a atual)</label>
                                    <input type="password" name="password" id="password"
                                           class="form-control @error('password') is-invalid @enderror">
                                    @error('password')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation" class="form-label">Confirmar Nova Senha</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                           class="form-control">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="painel" class="form-label">Tipo de Painel</label>
                            <select name="painel" id="painel" required
                                    class="form-control @error('painel') is-invalid @enderror">
                                <option value="administrador" {{ old('painel', $user->painel) == 'administrador' ? 'selected' : '' }}>Administrador</option>
                                <option value="gerente" {{ old('painel', $user->painel) == 'gerente' ? 'selected' : '' }}>Gerente</option>
                                <option value="enfermeiro" {{ old('painel', $user->painel) == 'enfermeiro' ? 'selected' : '' }}>enfermeiro</option>
                                <option value="médico" {{ old('painel', $user->painel) == 'médico' ? 'selected' : '' }}>Médico</option>
                                <option value="tecnico" {{ old('painel', $user->painel) == 'tecnico' ? 'selected' : '' }}>Técnico</option>
                            </select>
                            @error('painel')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" name="receives_notifications" id="receives_notifications" value="1" 
                                       {{ old('receives_notifications', $user->receives_notifications) ? 'checked' : '' }}
                                       class="form-check-input @error('receives_notifications') is-invalid @enderror">
                                <label for="receives_notifications" class="form-check-label">Receber notificações</label>
                                @error('receives_notifications')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary mr-3">Cancelar</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Atualizar Usuário
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection