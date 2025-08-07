<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificações - Sistema de Medicamentos</title>
    
    <!-- Conexões pré-estabelecidas para CDNs -->
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    
    <!-- Pré-carregamento de recursos críticos -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" as="style">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" as="style">
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/chart.js" as="script">
    
    <!-- CSS Online com fallback local -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
          integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" 
          crossorigin="anonymous"
          onerror="this.onerror=null;this.href='/css/bootstrap.min.css';">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
          integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
          crossorigin="anonymous"
          onerror="this.onerror=null;this.href='/vendor/fontawesome/css/all.min.css';">
    
    <!-- Estilos dinâmicos embutidos -->
    <style>
        :root {
            --color-stock: #FFA500;
            --color-expiration: #FF4500;
            --color-unread: rgba(248, 249, 250, 0.8);
            --shadow-sm: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            --shadow-md: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        
        body {
            background-color: #f5f5f5;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        
        .notification-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        
        .notification-card {
            transition: all 0.3s ease;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            background-color: white;
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }
        
        .notification-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }
        
        .notification-unread {
            background-color: var(--color-unread);
            border-left: 4px solid #6c757d;
        }
        
        .notification-stock {
            border-left: 4px solid var(--color-stock);
        }
        
        .notification-expiration {
            border-left: 4px solid var(--color-expiration);
        }
        
        .badge-custom {
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            padding: 0.35em 0.65em;
        }
        
        .badge-stock {
            background-color: var(--color-stock);
            color: #1a1a1a;
        }
        
        .badge-expiration {
            background-color: var(--color-expiration);
            color: white;
        }
        
        .notification-actions {
            min-width: 80px;
        }
        
        @media (max-width: 576px) {
            .notification-card {
                margin-bottom: 0.75rem;
            }
            
            .notification-actions {
                flex-direction: row !important;
                min-width: auto;
                gap: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Barra de navegação -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-pills me-2"></i>
                Sistema de Medicamentos
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('medicines.index') }}">
                            <i class="fas fa-list me-1"></i> Medicamentos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('notifications') }}">
                            <i class="fas fa-bell me-1"></i> Notificações
                            @if($unreadCount > 0)
                            <span class="badge bg-danger rounded-pill ms-1">{{ $unreadCount }}</span>
                            @endif
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Status de conexão -->
    <div id="online-status" class="position-fixed top-0 end-0 m-3 alert alert-warning d-none" style="z-index: 1100;">
        <i class="fas fa-wifi-slash me-2"></i> Você está offline - Algumas funcionalidades podem estar limitadas
    </div>

    <!-- Conteúdo principal -->
    <main class="notification-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-bell me-2"></i> Notificações
            </h1>
            <div class="d-flex gap-2">
                <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="fas fa-check-circle me-1"></i> Marcar todas como lidas
                    </button>
                </form>
                <form action="{{ route('notifications.clear') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash-alt me-1"></i> Limpar tudo
                    </button>
                </form>
            </div>
        </div>

        <!-- Filtros -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('notifications') }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="type" class="form-label">Tipo</label>
                            <select name="type" id="type" class="form-select">
                                <option value="">Todos</option>
                                <option value="stock" {{ request('type') == 'stock' ? 'selected' : '' }}>Estoque Baixo</option>
                                <option value="expiration" {{ request('type') == 'expiration' ? 'selected' : '' }}>Validade</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">Todos</option>
                                <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Lidas</option>
                                <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Não lidas</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="date" class="form-label">Período</label>
                            <select name="date" id="date" class="form-select">
                                <option value="">Todos</option>
                                <option value="today" {{ request('date') == 'today' ? 'selected' : '' }}>Hoje</option>
                                <option value="week" {{ request('date') == 'week' ? 'selected' : '' }}>Últimos 7 dias</option>
                                <option value="month" {{ request('date') == 'month' ? 'selected' : '' }}>Últimos 30 dias</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter me-1"></i> Filtrar
                                </button>
                                <a href="{{ route('notifications') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-undo me-1"></i> Limpar
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lista de notificações -->
        <div class="card">
            <div class="card-body p-0">
                @forelse($notifications as $notification)
                <div class="notification-card notification-{{ $notification->type }} {{ $notification->read ? '' : 'notification-unread' }} p-3">
                    <div class="d-flex justify-content-between">
                        <div class="flex-grow-1">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                @if($notification->type == 'stock')
                                <span class="badge badge-stock badge-custom">
                                    <i class="fas fa-boxes me-1"></i> ESTOQUE BAIXO
                                </span>
                                @else
                                <span class="badge badge-expiration badge-custom">
                                    <i class="fas fa-calendar-times me-1"></i> VALIDADE
                                </span>
                                @endif
                                <small class="text-muted">{{ $notification->created_at->format('d/m/Y H:i') }}</small>
                                @if(!$notification->read)
                                <span class="badge bg-warning text-dark badge-custom">NOVA</span>
                                @endif
                            </div>
                            
                            <h5 class="mb-2">
                                @if($notification->medicine)
                                <a href="{{ route('medicines.show', $notification->medicine) }}" class="text-decoration-none text-dark fw-bold">
                                    {{ $notification->medicine->name }}
                                    <small class="text-muted">({{ $notification->medicine->batch }})</small>
                                </a>
                                @else
                                <span class="text-danger fw-bold">Medicamento não disponível</span>
                                @endif
                            </h5>
                            
                            <p class="mb-2 text-muted">{{ $notification->message }}</p>
                            
                            @if($notification->medicine)
                            <div class="d-flex flex-wrap gap-3">
                                <span class="d-inline-flex align-items-center gap-1">
                                    <i class="fas fa-box text-muted"></i>
                                    <span class="fw-bold">Estoque:</span>
                                    <span class="{{ $notification->medicine->stock < $notification->medicine->minimum_stock ? 'text-danger fw-bold' : '' }}">
                                        {{ $notification->medicine->stock }}
                                    </span>
                                </span>
                                
                                <span class="d-inline-flex align-items-center gap-1">
                                    <i class="fas fa-arrow-down text-muted"></i>
                                    <span class="fw-bold">Mínimo:</span>
                                    <span>{{ $notification->medicine->minimum_stock }}</span>
                                </span>
                                
                                @if($notification->type == 'expiration')
                                <span class="d-inline-flex align-items-center gap-1">
                                    <i class="fas fa-calendar-day text-muted"></i>
                                    <span class="fw-bold">Validade:</span>
                                    <span class="{{ $notification->medicine->expiration_date < now() ? 'text-danger fw-bold' : '' }}">
                                        {{ $notification->medicine->expiration_date->format('d/m/Y') }}
                                    </span>
                                </span>
                                @endif
                            </div>
                            @endif
                        </div>
                        
                        <div class="notification-actions d-flex flex-column ms-2">
                            @if(!$notification->read)
                            <form action="{{ route('notifications.markAsRead', $notification) }}" method="POST" class="mb-2">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-success w-100" title="Marcar como lida">
                                    <i class="fas fa-check me-1"></i> Lida
                                </button>
                            </form>
                            @endif
                            
                            <form action="{{ route('notifications.delete', $notification) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger w-100" title="Remover notificação">
                                    <i class="fas fa-trash me-1"></i> Remover
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">Nenhuma notificação encontrada</h4>
                    <p class="text-muted">Você será notificado quando houver alertas de estoque ou validade</p>
                </div>
                @endforelse
            </div>
            
            @if($notifications->hasPages())
            <div class="card-footer">
                <div class="d-flex justify-content-center">
                    {{ $notifications->links() }}
                </div>
            </div>
            @endif
        </div>
    </main>

    <!-- Scripts Online com fallback local -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
            crossorigin="anonymous"
            onerror="this.onerror=null;this.src='/js/bootstrap.bundle.min.js';"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"
            onerror="this.onerror=null;this.src='/js/chart.js';"></script>
    
    <!-- Scripts customizados -->
    <script>
        // Verificação de recursos carregados
        function checkResources() {
            // Bootstrap
            if (typeof bootstrap === 'undefined') {
                console.log('Bootstrap não carregado via CDN, carregando localmente');
                loadLocalResource('/js/bootstrap.bundle.min.js', 'script');
            }
            
            // Font Awesome (verificação indireta)
            if (!document.fonts.check('1em "Font Awesome 6 Free"')) {
                console.log('Font Awesome não carregado via CDN, carregando localmente');
                loadLocalResource('/vendor/fontawesome/css/all.min.css', 'style');
            }
            
            // Chart.js
            if (typeof Chart === 'undefined') {
                console.log('Chart.js não carregado via CDN, carregando localmente');
                loadLocalResource('/js/chart.js', 'script');
            }
        }
        
        function loadLocalResource(path, type) {
            return new Promise((resolve, reject) => {
                const element = document.createElement(type === 'script' ? 'script' : 'link');
                
                if (type === 'script') {
                    element.src = path;
                    element.onload = resolve;
                    element.onerror = reject;
                    document.body.appendChild(element);
                } else {
                    element.rel = 'stylesheet';
                    element.href = path;
                    element.onload = resolve;
                    element.onerror = reject;
                    document.head.appendChild(element);
                }
            });
        }
        
        // Verificação de conexão
        function updateOnlineStatus() {
            const statusElement = document.getElementById('online-status');
            if (navigator.onLine) {
                statusElement.classList.add('d-none');
            } else {
                statusElement.classList.remove('d-none');
            }
        }
        
        // Inicialização
        document.addEventListener('DOMContentLoaded', function() {
            checkResources();
            updateOnlineStatus();
            
            window.addEventListener('online', updateOnlineStatus);
            window.addEventListener('offline', updateOnlineStatus);
        });
    </script>
</body>
</html>