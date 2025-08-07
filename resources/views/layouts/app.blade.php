<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title') - PharmaStock</title>
    
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
    
    <!-- Chart.js via CDN com fallback local -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"
            integrity="sha256-7n8ZtJvJJtg1d6Zl3+6YS3F6QSlA6FkF7pFp0YiGkqM="
            crossorigin="anonymous"
            onerror="this.onerror=null;this.src='{{ asset('js/chart.js') }}'"></script>
    
    <!-- Estilos personalizados -->
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #224abe;
            --danger-color: #e74a3b;
            --warning-color: #f6c23e;
            --success-color: #1cc88a;
            --color-stock: #FFA500;
            --color-expiration: #FF4500;
            --color-unread: #F8F9FA;
            --pharma-primary: #4f46e5;
            --pharma-secondary: #6366f1;
        }
        
        body {
            background-color: #f8f9fc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            background-color: white;
        }
        
        .main-content {
            min-height: calc(100vh - 120px);
            padding-top: 2rem;
        }
        
        .notification-badge {
            font-size: 0.75rem;
            top: -10px;
            right: -10px;
        }
        
        .sidebar {
            background: linear-gradient(180deg, var(--pharma-primary) 10%, var(--pharma-secondary) 100%);
            min-height: 100vh;
            color: white;
        }
        
        .pharma-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        .pharma-brand span {
            color: var(--pharma-secondary);
        }
        
        .notification-card {
            transition: all 0.3s ease;
            border-radius: 8px;
            margin-bottom: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .notification-unread {
            background-color: var(--color-unread);
        }
        
        .notification-stock {
            border-left: 4px solid var(--color-stock);
        }
        
        .notification-expiration {
            border-left: 4px solid var(--color-expiration);
        }
        
        .badge-stock {
            background-color: var(--color-stock);
            color: #000;
        }
        
        .badge-expiration {
            background-color: var(--color-expiration);
            color: #FFF;
        }
        
        .nav-link {
            transition: all 0.2s ease;
            border-radius: 4px;
            margin: 2px 0;
        }
        
        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            font-weight: 600;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
            }
            
            .notification-card {
                margin-bottom: 0.75rem;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Barra de navegação -->
    <nav class="navbar navbar-expand-lg navbar-light shadow">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-pills me-2 text-indigo-600"></i>
                <span class="pharma-brand">Pharma<span>Stock</span></span>
            </a>
            
            <!-- Botão para mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Conteúdo da navbar -->
            <div class="collapse navbar-collapse" id="navbarContent">
                @guest
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    </ul>
                @else
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i>
                                <span>{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Perfil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i> Sair
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                @endguest
            </div>
        </div>
    </nav>

    <!-- Container principal -->
    @auth
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block sidebar p-0">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column px-3">
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->is('dashboard') ? 'active' : '' }}" 
                               href="{{ route('dashboard') }}">
                                <i class="fas fa-home me-2"></i>Dashboard
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
                            <a class="nav-link text-white {{ request()->is('notifications*') ? 'active' : '' }}" 
                               href="{{ route('notifications') }}">
                                <i class="fas fa-bell me-2"></i>
                                Notificações
                                @if(auth()->check() && ($unreadCount = auth()->user()->unreadNotifications->count()))
                                <span class="badge bg-danger rounded-pill notification-badge position-relative">
                                    {{ $unreadCount }}
                                </span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->is('reports*') ? 'active' : '' }}" 
                               href="{{ route('reports.index') }}">
                                <i class="fas fa-chart-line me-2"></i>
                                Relatórios
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->is('suppliers*') ? 'active' : '' }}" 
                               href="{{ route('suppliers.index') }}">
                                <i class="fas fa-truck me-2"></i>
                                Fornecedores
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
    @else
    <!-- Conteúdo para visitantes -->
    <main class="py-4">
        @yield('content')
    </main>
    @endauth

    <!-- Bootstrap Bundle JS via CDN com fallback local -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
            crossorigin="anonymous"
            onerror="this.onerror=null;this.src='{{ asset('js/bootstrap.bundle.min.js') }}'"></script>
    
    <!-- Scripts personalizados -->
    <script>
        // Verificação de conexão
        document.addEventListener('DOMContentLoaded', function() {
            const onlineStatus = document.getElementById('onlineStatus');
            
            function updateOnlineStatus() {
                if (navigator.onLine) {
                    if (onlineStatus) onlineStatus.classList.add('d-none');
                } else {
                    if (onlineStatus) onlineStatus.classList.remove('d-none');
                }
            }
            
            window.addEventListener('online', updateOnlineStatus);
            window.addEventListener('offline', updateOnlineStatus);
            updateOnlineStatus();
            
            // Verifica se o Bootstrap foi carregado
            if (typeof bootstrap === 'undefined') {
                console.error('Bootstrap não carregado, tentando fallback local');
                const script = document.createElement('script');
                script.src = '{{ asset('js/bootstrap.bundle.min.js') }}';
                document.body.appendChild(script);
            }
            
            // Verifica se o Chart.js foi carregado
            if (typeof Chart === 'undefined') {
                console.error('Chart.js não carregado, tentando fallback local');
                const script = document.createElement('script');
                script.src = '{{ asset('js/chart.js') }}';
                document.body.appendChild(script);
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>