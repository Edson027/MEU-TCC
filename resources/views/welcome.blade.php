<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PharmaStock - Gestão de Medicamentos</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --primary: #4e73df;
            --secondary: #858796;
            --success: #1cc88a;
            --info: #36b9cc;
            --warning: #f6c23e;
            --danger: #e74a3b;
            --light: #f8f9fc;
            --dark: #5a5c69;
        }
        
        body {
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f8f9fc;
        }
        
        .stat-card {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .stat-card-primary {
            border-left-color: var(--primary);
        }
        .stat-card-warning {
            border-left-color: var(--warning);
        }
        .stat-card-danger {
            border-left-color: var(--danger);
        }
        .stat-card-success {
            border-left-color: var(--success);
        }
        .stat-icon {
            font-size: 2rem;
            opacity: 0.3;
        }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        .floating { animation: float 6s ease-in-out infinite; }

        .fade-in { animation: fadeIn 1.5s ease-in; }
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        .wave {
            animation: wave 8s cubic-bezier(0.36, 0.45, 0.63, 0.53) infinite;
            transform: translate3d(0, 0, 0);
        }
        @keyframes wave {
            0% { margin-left: 0; }
            100% { margin-left: -1600px; }
        }
        
        .feature-card {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
        }
        
        .hero-section {
            background: linear-gradient(135deg, rgba(78, 115, 223, 0.1) 0%, rgba(28, 200, 138, 0.1) 100%);
        }
        
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .text-gradient {
            background: linear-gradient(135deg, var(--primary), var(--info));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .medicine-img {
            max-height: 300px;
            object-fit: contain;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-pills text-primary me-2"></i>
                <span class="text-dark">Pharma</span><span class="text-primary">Stock</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Funcionalidades</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#screenshots">Sistema</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contato</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt me-1"></i> Login
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section pt-7 pb-5 mt-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4 fade-in">
                        Controle Inteligente de
                        <span class="text-gradient d-block">Estoque de Medicamentos</span>
                    </h1>
                    <p class="lead text-muted mb-4">
                        Gerencie seu estoque farmacêutico com precisão, receba alertas de validade e otimize sua gestão com nossa plataforma especializada.
                    </p>
                    <div class="d-flex flex-wrap gap-3 mb-5">
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4 py-3">
                            <i class="fas fa-rocket me-2"></i> Comece Agora
                        </a>
                        <a href="#features" class="btn btn-outline-primary btn-lg px-4 py-3">
                            <i class="fas fa-vial me-2"></i> Funcionalidades
                        </a>
                    </div>
                    
                    <!-- Mini Stats -->
                    <div class="row g-3">
                        <div class="col-6 col-md-3">
                            <div class="card stat-card stat-card-primary h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-pills stat-icon text-primary mb-2"></i>
                                    <h5 class="mb-0">+500</h5>
                                    <small class="text-muted">Medicamentos</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="card stat-card stat-card-success h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-check-circle stat-icon text-success mb-2"></i>
                                    <h5 class="mb-0">99%</h5>
                                    <small class="text-muted">Disponibilidade</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="card stat-card stat-card-warning h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-bell stat-icon text-warning mb-2"></i>
                                    <h5 class="mb-0">24/7</h5>
                                    <small class="text-muted">Alertas</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="card stat-card stat-card-danger h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-shield-alt stat-icon text-danger mb-2"></i>
                                    <h5 class="mb-0">100%</h5>
                                    <small class="text-muted">Seguro</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="position-relative mt-5 mt-lg-0">
                        <div class="floating">
                            <img src="https://cdn.pixabay.com/photo/2017/01/31/22/06/medical-2027618_1280.png" 
                                 alt="Sistema de Gestão de Medicamentos" 
                                 class="img-fluid medicine-img">
                        </div>
                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-primary bg-opacity-10 rounded-circle" style="z-index: -1;"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Wave Divider -->
    <div class="position-relative" style="height: 100px; overflow: hidden;">
        <div class="wave position-absolute top-0 start-0 w-full h-full bg-white"></div>
    </div>

    <!-- Features Section -->
    <section id="features" class="py-7 bg-white">
        <div class="container">
            <div class="text-center mb-7">
                <h2 class="text-uppercase text-primary mb-3">Funcionalidades</h2>
                <h3 class="fw-bold">Controle completo do seu estoque</h3>
                <p class="text-muted w-lg-50 mx-auto">Tudo o que você precisa para uma gestão farmacêutica eficiente</p>
            </div>

            <div class="row g-4">
                <!-- Feature 1 -->
                <div class="col-md-4">
                    <div class="card feature-card h-100 border-start border-primary border-4">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                                    <i class="fas fa-pills text-primary fs-2"></i>
                                </div>
                                <h4 class="mb-0">Gestão de Medicamentos</h4>
                            </div>
                            <p class="text-muted">Cadastro completo com princípio ativo, dosagem, lote, validade e informações regulatórias.</p>
                            <div class="mt-4">
                                <img src="https://cdn.pixabay.com/photo/2016/11/22/23/44/pills-1853400_640.jpg" 
                                     alt="Gestão de Medicamentos" 
                                     class="img-fluid rounded mb-3">
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-primary bg-opacity-10 text-primary">
                                        <i class="fas fa-barcode me-1"></i> Controle de lotes
                                    </span>
                                    <span class="badge bg-primary bg-opacity-10 text-primary">
                                        <i class="fas fa-boxes me-1"></i> Organização
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="col-md-4">
                    <div class="card feature-card h-100 border-start border-warning border-4">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-warning bg-opacity-10 p-3 rounded me-3">
                                    <i class="fas fa-bell text-warning fs-2"></i>
                                </div>
                                <h4 class="mb-0">Alertas Inteligentes</h4>
                            </div>
                            <p class="text-muted">Notificações automáticas para medicamentos próximos ao vencimento ou com estoque crítico.</p>
                            <div class="mt-4">
                                <img src="https://cdn.pixabay.com/photo/2017/05/15/23/47/stethoscope-2316463_640.jpg" 
                                     alt="Alertas Inteligentes" 
                                     class="img-fluid rounded mb-3">
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-warning bg-opacity-10 text-warning">
                                        <i class="fas fa-calendar-exclamation me-1"></i> Validade
                                    </span>
                                    <span class="badge bg-warning bg-opacity-10 text-warning">
                                        <i class="fas fa-exclamation-triangle me-1"></i> Estoque baixo
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="col-md-4">
                    <div class="card feature-card h-100 border-start border-info border-4">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-info bg-opacity-10 p-3 rounded me-3">
                                    <i class="fas fa-chart-line text-info fs-2"></i>
                                </div>
                                <h4 class="mb-0">Relatórios Analíticos</h4>
                            </div>
                            <p class="text-muted">Dashboard completo com gráficos e métricas para tomada de decisão estratégica.</p>
                            <div class="mt-4">
                                <img src="https://cdn.pixabay.com/photo/2018/05/18/15/30/web-design-3411373_640.jpg" 
                                     alt="Relatórios Analíticos" 
                                     class="img-fluid rounded mb-3">
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-info bg-opacity-10 text-info">
                                        <i class="fas fa-file-medical me-1"></i> Análise de dados
                                    </span>
                                    <span class="badge bg-info bg-opacity-10 text-info">
                                        <i class="fas fa-download me-1"></i> Exportação
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Screenshots Section -->
    <section id="screenshots" class="py-7 bg-light">
        <div class="container">
            <div class="text-center mb-7">
                <h2 class="text-uppercase text-primary mb-3">Sistema PharmaStock</h2>
                <h3 class="fw-bold">Interface profissional e intuitiva</h3>
                <p class="text-muted w-lg-50 mx-auto">Conheça nosso painel administrativo completo</p>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body p-0 overflow-hidden">
                            <img src="https://cdn.pixabay.com/photo/2017/05/15/18/09/medicine-2316259_1280.jpg" 
                                 alt="Painel de Controle" 
                                 class="img-fluid w-100">
                        </div>
                        <div class="card-footer bg-white">
                            <h5 class="mb-0">Painel de Controle</h5>
                            <small class="text-muted">Visão geral do estoque e alertas</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body p-0 overflow-hidden">
                            <img src="https://cdn.pixabay.com/photo/2017/01/31/22/06/medical-2027619_1280.jpg" 
                                 alt="Gestão de Medicamentos" 
                                 class="img-fluid w-100">
                        </div>
                        <div class="card-footer bg-white">
                            <h5 class="mb-0">Gestão de Medicamentos</h5>
                            <small class="text-muted">Controle completo de cada item</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-7 bg-primary text-white">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-md-3 col-6">
                    <h2 class="display-4 fw-bold">500+</h2>
                    <p class="mb-0 text-white-50">Medicamentos</p>
                </div>
                <div class="col-md-3 col-6">
                    <h2 class="display-4 fw-bold">99%</h2>
                    <p class="mb-0 text-white-50">Disponibilidade</p>
                </div>
                <div class="col-md-3 col-6">
                    <h2 class="display-4 fw-bold">24/7</h2>
                    <p class="mb-0 text-white-50">Monitoramento</p>
                </div>
                <div class="col-md-3 col-6">
                    <h2 class="display-4 fw-bold">100%</h2>
                    <p class="mb-0 text-white-50">Confiável</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-7 bg-white">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="fw-bold mb-4">Pronto para otimizar sua gestão farmacêutica?</h2>
                    <p class="lead text-muted mb-5">Experimente o PharmaStock gratuitamente e descubra como podemos transformar sua gestão de medicamentos.</p>
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-5 py-3">
                        <i class="fas fa-rocket me-2"></i> Comece Agora
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-7 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h2 class="fw-bold mb-4">Entre em Contato</h2>
                    <p class="text-muted mb-5">Tem dúvidas ou precisa de mais informações? Nossa equipe está pronta para ajudar.</p>
                    
                    <div class="d-flex align-items-start mb-4">
                        <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                            <i class="fas fa-map-marker-alt text-primary"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">Endereço</h5>
                            <p class="text-muted mb-0">Rua Antonío Agostinho Neto, Hospital Pioneiro Zeca, Lubango, Huíla</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-start mb-4">
                        <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                            <i class="fas fa-phone-alt text-primary"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">Telefone</h5>
                            <p class="text-muted mb-0">(+244) 921 325 327 / 933 319 510</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-start mb-4">
                        <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                            <i class="fas fa-envelope text-primary"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">Email</h5>
                            <p class="text-muted mb-0">edsocarlos027@outlook.com</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <h4 class="mb-4">Envie uma mensagem</h4>
                            <form>
                                <div class="mb-3">
                                    <input type="text" class="form-control" placeholder="Seu nome">
                                </div>
                                <div class="mb-3">
                                    <input type="email" class="form-control" placeholder="Seu email">
                                </div>
                                <div class="mb-3">
                                    <input type="text" class="form-control" placeholder="Assunto">
                                </div>
                                <div class="mb-3">
                                    <textarea class="form-control" rows="4" placeholder="Sua mensagem"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Enviar Mensagem</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-5 bg-dark text-white">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-pills text-primary me-2"></i> PharmaStock
                    </h5>
                    <p class="text-muted">Solução completa para gestão de estoque de medicamentos e insumos farmacêuticos.</p>
                    <div class="d-flex gap-3 mt-4">
                        <a href="#" class="text-white"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                    <h5 class="fw-bold mb-3">Links</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-muted">Início</a></li>
                        <li class="mb-2"><a href="#features" class="text-muted">Funcionalidades</a></li>
                        <li class="mb-2"><a href="#screenshots" class="text-muted">Sistema</a></li>
                        <li class="mb-2"><a href="#contact" class="text-muted">Contato</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                    <h5 class="fw-bold mb-3">Legal</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-muted">Termos</a></li>
                        <li class="mb-2"><a href="#" class="text-muted">Privacidade</a></li>
                        <li class="mb-2"><a href="#" class="text-muted">LGPD</a></li>
                        <li class="mb-2"><a href="#" class="text-muted">Cookies</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h5 class="fw-bold mb-3">Newsletter</h5>
                    <p class="text-muted">Assine nossa newsletter para receber atualizações.</p>
                    <form class="mt-4">
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" placeholder="Seu email">
                            <button class="btn btn-primary" type="button">Assinar</button>
                        </div>
                    </form>
                </div>
            </div>
            <hr class="my-4 bg-gray-700">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0 text-muted">&copy; {{ now()->year }} PharmaStock. Todos os direitos reservados.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="mb-0 text-muted">Desenvolvido por <span class="text-primary">Eng Carlos</span></p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Ativar tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        // Gráfico de exemplo
        const ctx = document.getElementById('demoChart').getContext('2d');
        const demoChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
                datasets: [{
                    label: 'Entradas',
                    data: [12, 19, 3, 5, 2, 3],
                    backgroundColor: 'rgba(28, 200, 138, 0.8)',
                }, {
                    label: 'Saídas',
                    data: [8, 15, 5, 8, 3, 6],
                    backgroundColor: 'rgba(231, 74, 59, 0.8)',
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
    </script>
</body>
</html>