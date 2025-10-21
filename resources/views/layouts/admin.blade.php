<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - PharmaStock Manager</title>
    
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
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    
    <!-- Estilos personalizados -->
    <style>
        :root {
            --primary-color: #2c5aa0;
            --secondary-color: #4e73df;
            --danger-color: #e74a3b;
            --warning-color: #f6c23e;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --light-bg: #f8f9fc;
            --dark-text: #2e3a59;
        }
        
        body {
            background-color: var(--light-bg);
            color: var(--dark-text);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            background: linear-gradient(90deg, var(--primary-color) 0%, var(--secondary-color) 100%) !important;
        }
        
        .main-content {
            min-height: calc(100vh - 120px);
            padding-top: 20px;
        }
        
        .notification-badge {
            font-size: 0.75rem;
            top: -10px;
            right: -10px;
        }
        
        .sidebar {
            background: linear-gradient(180deg, var(--primary-color) 10%, #1a3f8a 100%);
            min-height: 100vh;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.85);
            border-left: 3px solid transparent;
            padding: 0.75rem 1rem;
            transition: all 0.2s;
        }
        
        .sidebar .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
            border-left: 3px solid rgba(255, 255, 255, 0.5);
        }
        
        .sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.15);
            border-left: 3px solid #fff;
            font-weight: 600;
        }
        
        .sidebar .nav-link i {
            width: 24px;
            text-align: center;
        }

        .notification-dropdown {
            min-width: 400px;
            max-height: 500px;
            overflow-y: auto;
        }

        .notification-item.unread {
            background-color: #f8f9fa;
            border-left: 3px solid var(--primary-color);
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
        .medium { background-color: var(--info-color); }
        .low { background-color: var(--success-color); }
        
        .dashboard-card {
            border-radius: 10px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
            border: none;
            height: 100%;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 2rem 0 rgba(58, 59,69, 0.2);
        }
        
        .card-primary { border-left: 4px solid var(--primary-color); }
        .card-success { border-left: 4px solid var(--success-color); }
        .card-warning { border-left: 4px solid var(--warning-color); }
        .card-danger { border-left: 4px solid var(--danger-color); }
        .card-info { border-left: 4px solid var(--info-color); }
        
        .status-badge {
            padding: 0.35em 0.65em;
            font-size: 0.75em;
            font-weight: 600;
            border-radius: 0.35rem;
        }
        
        .stock-low { background-color: #fce8e6; color: #d93025; }
        .stock-normal { background-color: #e6f4ea; color: #137333; }
        .stock-critical { background-color: #fef7e0; color: #f9ab00; }
        .expired { background-color: #fae8e8; color: #c5221f; }
        
        .search-box {
            position: relative;
        }
        
        .search-box .form-control {
            padding-left: 40px;
            border-radius: 20px;
        }
        
        .search-box i {
            position: absolute;
            left: 15px;
            top: 12px;
            color: #6c757d;
        }
        
        .quick-actions {
            background-color: white;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            margin-bottom: 20px;
        }
        
        .action-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
            transition: all 0.2s;
            color: var(--dark-text);
            text-decoration: none;
        }
        
        .action-btn:hover {
            background-color: #f0f4ff;
            transform: translateY(-3px);
        }
        
        .action-btn i {
            font-size: 1.5rem;
            margin-bottom: 8px;
            color: var(--primary-color);
        }
        
        .table-medicines th {
            background-color: var(--primary-color);
            color: white;
        }
        
        .expiry-warning {
            background-color: #fff3f3 !important;
        }
        
        .low-stock-warning {
            background-color: #fff8e6 !important;
        }
        
        .notification-time {
            font-size: 0.75rem;
            color: #6c757d;
            display: block;
            margin-top: 2px;
        }
        
        .empty-notification {
            color: #6c757d;
            text-align: center;
            padding: 1rem;
            font-style: italic;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
                position: fixed;
                bottom: 0;
                width: 100%;
                z-index: 1000;
                padding: 0;
            }
            
            .sidebar .nav {
                flex-direction: row;
                overflow-x: auto;
            }
            
            .sidebar .nav-link {
                border-left: none;
                border-top: 3px solid transparent;
                text-align: center;
                font-size: 0.8rem;
                padding: 0.5rem;
            }
            
            .sidebar .nav-link:hover,
            .sidebar .nav-link.active {
                border-left: none;
                border-top: 3px solid rgba(255, 255, 255, 0.5);
            }
            
            .sidebar .nav-link i {
                display: block;
                margin: 0 auto 5px;
            }
            
            .main-content {
                padding-bottom: 70px;
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
    <nav class="navbar navbar-expand-lg navbar-dark shadow">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-pills me-2"></i>
                <strong>PharmaStock Manager</strong>
            </a>
            
            <!-- Barra de pesquisa 
            <div class="d-none d-md-flex search-box mx-4">
                <i class="fas fa-search"></i>
                <input type="text" class="form-control" placeholder="Pesquisar medicamentos...">
            </div>-->
            
            <!-- Itens da direita -->
            <div class="d-flex align-items-center">
                <!-- Notificações -->
                <div class="dropdown me-3">
                    <button class="btn btn-light btn-sm position-relative" type="button" id="notificationDropdown" 
                            data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge" id="notificationCount">
                            0
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="notificationDropdown" id="notificationList">
                        <li class="dropdown-header">Notificações</li>
                        <li><hr class="dropdown-divider"></li>
                        <li id="emptyNotification" class="empty-notification">Carregando notificações...</li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-center text-primary" href="{{ route('medicines.index') }}">Ver todos os medicamentos</a></li>
                    </ul>
                </div>
                
                <!-- Perfil do usuário -->
                <div class="dropdown">
                    <button class="btn btn-light btn-sm dropdown-toggle d-flex align-items-center" type="button" 
                            id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        {{Auth::user()->name}}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Perfil</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Configurações</a></li>
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
                </div>
            </div>
        </div>
    </nav>

    <!-- Container principal -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block sidebar p-0">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" 
                               href="{{ route('PainelAdministrativo.dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('medicines*') ? 'active' : '' }}" 
                               href="{{ route('medicines.index') }}">
                                <i class="fas fa-pills me-2"></i>
                                Medicamentos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('categories*') ? 'active' : '' }}" 
                               href="{{route('index')}}">
                                <i class="fas fa-tags me-2"></i>
                                Fornecedores
                            </a>
                        </li>

              <!-- Usuários 
<div class="relative group ml-4">
    <a href="{{ route('users.index') }}" 
       class="{{ request()->routeIs('users.*') ? 'border-orange-500 text-orange-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} inline-flex items-center px-4 pt-1 border-b-2 text-sm font-medium transition-colors duration-200">
        Usuários
    </a>
    @if(request()->routeIs('users.*'))
    <div class="absolute bottom-0 left-0 w-full h-1 bg-orange-500 rounded-t"></div>
    @endif
</div>

-->

                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('suppliers*') ? 'active' : '' }}" 
                               href="{{route('users.index')}}">
                                <i class="fas fa-user me-2"></i>
                            Usuários
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('inventory*') ? 'active' : '' }}" 
                               href="{{route('movements.index')}}">
                                <i class="fas fa-boxes me-2"></i>
                                Movimentações
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('reports*') ? 'active' : '' }}" 
                               href="{{ route('reports.index') }}">
                                <i class="fas fa-chart-line me-2"></i>
                                Relatórios
                            </a>
                        </li>
                       
                        <li class="nav-item">
                            <a class="nav-link" 
                               href="{{ route('supply-orders.index') }}">
 <i class="fas fa-clipboard-list me-2"></i>
                               Pedidos de Abastecimento
                            </a>
                        </li>
                       
                       
                        <li class="nav-item mt-3">
                            <a class="nav-link" href="#">
                                <i class="fas fa-question-circle me-2"></i>
                                Ajuda & Suporte
                            </a>
                        </li>
                    </ul>
                </div>
            </div>


            <!-- Conteúdo principal -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <!-- Cabeçalho da página -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h4 mb-0">@yield('page-title', 'Dashboard')</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('PainelAdministrativo.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">@yield('page-title', 'Dashboard')</li>
                        </ol>
                    </nav>
                </div>
                
                <!-- Alertas e notificações -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap Bundle JS via CDN com fallback local -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
            crossorigin="anonymous"
            onerror="this.onerror=null;this.src='{{ asset('js/bootstrap.bundle.min.js') }}'"></script>
    
    <!-- jQuery e DataTables -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- Scripts personalizados -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar DataTables em tabelas com a classe 'datatable'
        $('.datatable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
            },
            responsive: true
        });
        
        // Função para buscar dados do servidor
        async function fetchMedicinesData() {
            try {
                const response = await fetch('/api/medicines/notifications');
                if (!response.ok) {
                    throw new Error('Erro ao buscar dados');
                }
                return await response.json();
            } catch (error) {
                console.error('Erro:', error);
                return { critical_stock: [], expiring_soon: [], zero_stock: [] };
            }
        }

        // Função para calcular dias até a expiração
        function daysUntilExpiration(expirationDate) {
            const today = new Date();
            const expDate = new Date(expirationDate);
            const diffTime = expDate - today;
            return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        }

        // Função para formatar a data
        function formatDate(dateString) {
            const options = { day: '2-digit', month: '2-digit', year: 'numeric' };
            return new Date(dateString).toLocaleDateString('pt-BR', options);
        }

        // Função para atualizar as notificações
        async function updateNotifications() {
            const data = await fetchMedicinesData();
            
            const criticalStock = data.critical_stock || [];
            const expiringMedicines = data.expiring_soon || [];
            const zeroStock = data.zero_stock || [];
            
            // Calcular total de notificações
            const totalNotifications = criticalStock.length + expiringMedicines.length + zeroStock.length;
            document.getElementById('notificationCount').textContent = totalNotifications;
            
            // Atualizar lista de notificações
            const notificationList = document.getElementById('notificationList');
            const emptyNotification = document.getElementById('emptyNotification');
            
            // Limpar notificações existentes (exceto os itens fixos)
            const itemsToRemove = notificationList.querySelectorAll('.notification-item-container');
            itemsToRemove.forEach(item => item.remove());
            
            // Mostrar mensagem de "nenhuma notificação" se aplicável
            if (totalNotifications === 0) {
                emptyNotification.textContent = 'Nenhuma notificação no momento';
                emptyNotification.style.display = 'block';
            } else {
                emptyNotification.style.display = 'none';
                
                // Adicionar notificações de estoque crítico
                criticalStock.forEach(med => {
                    const notificationItem = document.createElement('li');
                    notificationItem.className = 'notification-item-container';
                    notificationItem.innerHTML = `
                        <a class="dropdown-item notification-item unread" href="/medicines/${med.id}">
                            <span class="notification-dot critical"></span>
                            <strong>Estoque crítico:</strong> ${med.name} está com apenas ${med.stock} unidades (mínimo: ${med.minimum_stock})
                            <span class="notification-time">Lote: ${med.batch || 'N/A'}</span>
                        </a>
                    `;
                    notificationList.insertBefore(notificationItem, emptyNotification.nextElementSibling);
                });
                
                // Adicionar notificações de validade próxima
                expiringMedicines.forEach(med => {
                    const days = daysUntilExpiration(med.expiration_date);
                    const notificationItem = document.createElement('li');
                    notificationItem.className = 'notification-item-container';
                    notificationItem.innerHTML = `
                        <a class="dropdown-item notification-item unread" href="/medicines/${med.id}">
                            <span class="notification-dot high"></span>
                            <strong>Validade próxima:</strong> ${med.name} vence em ${days} dias (${formatDate(med.expiration_date)})
                            <span class="notification-time">Lote: ${med.batch || 'N/A'}</span>
                        </a>
                    `;
                    notificationList.insertBefore(notificationItem, emptyNotification.nextElementSibling);
                });
                
                // Adicionar notificações de estoque zerado
                zeroStock.forEach(med => {
                    const notificationItem = document.createElement('li');
                    notificationItem.className = 'notification-item-container';
                    notificationItem.innerHTML = `
                        <a class="dropdown-item notification-item unread" href="/medicines/${med.id}">
                            <span class="notification-dot warning"></span>
                            <strong>Estoque zerado:</strong> ${med.name} está indisponível
                            <span class="notification-time">Última atualização: ${formatDate(med.updated_at)}</span>
                        </a>
                    `;
                    notificationList.insertBefore(notificationItem, emptyNotification.nextElementSibling);
                });
            }
        }

        // Atualizar notificações a cada 60 segundos
        updateNotifications();
        setInterval(updateNotifications, 60000);

        // Atualizar notificações quando o dropdown for aberto
        document.getElementById('notificationDropdown').addEventListener('click', function() {
            updateNotifications();
        });
        
        // Destacar linhas com medicamentos próximos do vencimento ou com estoque baixo
        document.querySelectorAll('.table-medicines tr').forEach(row => {
            const expiryDate = row.getAttribute('data-expiration-date');
            const stockLevel = row.getAttribute('data-stock');
            const criticalLevel = row.getAttribute('data-minimum-stock');
            
            if (expiryDate) {
                const expiry = new Date(expiryDate);
                const today = new Date();
                const diffTime = Math.abs(expiry - today);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                
                if (diffDays <= 30) {
                    row.classList.add('expiry-warning');
                }
            }
            
            if (stockLevel && criticalLevel && parseInt(stockLevel) <= parseInt(criticalLevel)) {
                row.classList.add('low-stock-warning');
            }
        });
    });
    </script>

    @stack('scripts')
</body>
</html>