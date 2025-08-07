<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Sistema de Medicamentos</title>
    
    <!-- Bootstrap CSS via CDN com fallback local -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" 
          rel="stylesheet"
          integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM"
          crossorigin="anonymous"
          onerror="this.onerror=null;this.href='{{ asset('css/bootstrap.min.css') }}'">
    
    <!-- Font Awesome via CDN com fallback local -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
          rel="stylesheet"
          integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
          crossorigin="anonymous"
          onerror="this.onerror=null;this.href='{{ asset('vendor/fontawesome/css/all.min.css') }}'">
    
    <!-- Estilos personalizados -->
    <style>
        :root {
            --primary-color: #4e73df;
            --danger-color: #e74a3b;
            --warning-color: #f6c23e;
            --success-color: #1cc88a;
        }
        
        body {
            background-color: #f8f9fc;
        }
        
        .navbar {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .main-content {
            min-height: calc(100vh - 120px);
        }
        
        .notification-badge {
            font-size: 0.75rem;
            top: -10px;
            right: -10px;
        }
        
        .sidebar {
            background: linear-gradient(180deg, var(--primary-color) 10%, #224abe 100%);
            min-height: 100vh;
        }

        .notification-dropdown {
            min-width: 350px;
        }

        .notification-item.unread {
            background-color: #f8f9fa;
        }

        .notification-item:hover {
            background-color: #e9ecef;
        }

        .notification-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
        }

        .critical { background-color: var(--danger-color); }
        .high { background-color: var(--warning-color); }
        .medium { background-color: #36b9cc; }
        .low { background-color: var(--success-color); }

        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
            }
            
            .notification-dropdown {
                min-width: 280px;
                left: -200px !important;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Barra de navegação -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-pills me-2"></i>
                <strong>Sistema de Medicamentos</strong>
            </a>
            
            <!-- Botão para mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            
        </div>
    </nav>

    <!-- Container principal -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block sidebar bg-primary text-white p-0">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{route('PainelAdministrativo.dashboard')}}">
                                <i class="fas fa-home me-2"></i> Principal
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->is('medicines*') ? 'active' : '' }}" 
                               href="{{ route('medicines.index') }}">
                                <i class="fas fa-pills me-2"></i>
                                Medicamentos
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->is('reports*') ? 'active' : '' }}" 
                               href="{{ route('reports.index') }}">
                                <i class="fas fa-chart-line me-2"></i>
                                Relatórios
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Conteúdo principal -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 main-content">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap Bundle JS via CDN com fallback local -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
            crossorigin="anonymous"
            onerror="this.onerror=null;this.src='{{ asset('js/bootstrap.bundle.min.js') }}'"></script>
    
    <!-- Script para marcar notificações como lidas -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Marcar notificação como lida ao clicar
        document.querySelectorAll('.notification-item.unread').forEach(item => {
            item.addEventListener('click', function(e) {
                const notificationId = this.getAttribute('data-notification-id');
                if (notificationId) {
                    fetch(`/notifications/${notificationId}/mark-as-read`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    });
                }
            });
        });
        
        // Atualizar contadores a cada 60 segundos
        setInterval(fetchNotificationCount, 60000);
        
        function fetchNotificationCount() {
            fetch('/notifications/count')
                .then(response => response.json())
                .then(data => {
                    const badge = document.querySelector('.notification-badge');
                    if (data.unread_count > 0) {
                        badge.textContent = data.unread_count;
                        badge.style.display = 'block';
                    } else {
                        badge.style.display = 'none';
                    }
                });
        }
    });
    </script>

    @stack('scripts')
</body>
</html>