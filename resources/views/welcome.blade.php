<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PharmaStock - Sistema de Gestão de Estoques Farmacêuticos</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --primary: #2c6abc;
            --secondary: #6c757d;
            --success: #198754;
            --info: #0dcaf0;
            --warning: #ffc107;
            --danger: #dc3545;
            --light: #f8f9fa;
            --dark: #212529;
            --pharma-blue: #2c6abc;
            --pharma-green: #198754;
            --pharma-teal: #20c997;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fc;
            color: #333;
        }
        
        .stat-card {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
            border-radius: 8px;
            overflow: hidden;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }
        .stat-card-primary {
            border-left-color: var(--pharma-blue);
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
            font-size: 2.2rem;
            opacity: 0.8;
        }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        .floating { animation: float 5s ease-in-out infinite; }

        .fade-in { animation: fadeIn 1s ease-in; }
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }
        
        .feature-card {
            transition: all 0.3s ease;
            border-radius: 10px;
            overflow: hidden;
            height: 100%;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.15);
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.6rem;
        }
        
        .hero-section {
            background: linear-gradient(135deg, rgba(44, 106, 188, 0.08) 0%, rgba(25, 135, 84, 0.08) 100%);
            padding-top: 100px;
            padding-bottom: 50px;
        }
        
        .btn-primary {
            background-color: var(--pharma-blue);
            border-color: var(--pharma-blue);
            border-radius: 6px;
            padding: 10px 20px;
            font-weight: 600;
        }
        .btn-primary:hover {
            background-color: #2359a0;
            border-color: #2359a0;
        }
        
        .btn-outline-primary {
            color: var(--pharma-blue);
            border-color: var(--pharma-blue);
            border-radius: 6px;
            padding: 10px 20px;
            font-weight: 600;
        }
        .btn-outline-primary:hover {
            background-color: var(--pharma-blue);
            color: white;
        }
        
        .text-gradient {
            background: linear-gradient(135deg, var(--pharma-blue), var(--pharma-teal));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 700;
        }
        
        .medicine-img {
            max-height: 320px;
            object-fit: contain;
        }
        
        .section-title {
            position: relative;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }
        .section-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: var(--pharma-blue);
        }
        .section-title.text-center:after {
            left: 50%;
            transform: translateX(-50%);
        }
        
        .feature-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            font-size: 28px;
        }
        
        .testimonial-card {
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s;
        }
        .testimonial-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .pharma-badge {
            background: rgba(44, 106, 188, 0.1);
            color: var(--pharma-blue);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .nav-link {
            font-weight: 500;
            color: #333;
            transition: all 0.3s;
        }
        .nav-link:hover {
            color: var(--pharma-blue);
        }
        
        .navbar {
            padding: 15px 0;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
        }
        
        footer {
            background: #2c3e50;
        }
        
        .stats-section {
            background: linear-gradient(135deg, var(--pharma-blue) 0%, #2359a0 100%);
            color: white;
            padding: 70px 0;
        }
        
        .contact-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(44, 106, 188, 0.1);
            color: var(--pharma-blue);
            font-size: 20px;
            margin-right: 15px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-pills me-2" style="color: var(--pharma-blue);"></i>
                <span style="color: #333;">Pharma</span><span style="color: var(--pharma-blue);">Stock</span>
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
                        <a class="nav-link" href="#benefits">Vantagens</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#screenshots">Sistema</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#testimonials">Clientes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contato</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt me-1"></i> Acessar Sistema
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4 fade-in">
                        Controle Completo de 
                        <span class="text-gradient d-block">Estoque Farmacêutico</span>
                    </h1>
                    <p class="lead mb-4" style="color: #555;">
                        Sistema especializado para gestão de medicamentos, controle de validade e otimização de processos em farmácias e drogarias.
                    </p>
                    <div class="d-flex flex-wrap gap-3 mb-5">
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4 py-3">
                            <i class="fas fa-rocket me-2"></i> Acessar Plataforma
                        </a>
                        <a href="#features" class="btn btn-outline-primary btn-lg px-4 py-3">
                            <i class="fas fa-list me-2"></i> Ver Funcionalidades
                        </a>
                    </div>
                    
                    <!-- Mini Stats -->
                    <div class="row g-3">
                        <div class="col-6 col-md-3">
                            <div class="card stat-card stat-card-primary h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-pills stat-icon mb-2" style="color: var(--pharma-blue);"></i>
                                    <h5 class="mb-0">+2.000</h5>
                                    <small class="text-muted">Medicamentos</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="card stat-card stat-card-success h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-check-circle stat-icon mb-2" style="color: var(--success);"></i>
                                    <h5 class="mb-0">99.8%</h5>
                                    <small class="text-muted">Disponibilidade</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="card stat-card stat-card-warning h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-bell stat-icon mb-2" style="color: var(--warning);"></i>
                                    <h5 class="mb-0">24/7</h5>
                                    <small class="text-muted">Alertas</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="card stat-card stat-card-danger h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-shield-alt stat-icon mb-2" style="color: var(--danger);"></i>
                                    <h5 class="mb-0">100%</h5>
                                    <small class="text-muted">Conformidade</small>
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

    <!-- Features Section -->
    <section id="features" class="py-5 bg-white">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="section-title text-center d-inline-block">Funcionalidades Principais</h2>
                <p class="text-muted w-lg-50 mx-auto">Desenvolvemos ferramentas especializadas para atender às necessidades específicas do controle farmacêutico</p>
            </div>

            <div class="row g-4">
                <!-- Feature 1 -->
                <div class="col-md-4">
                    <div class="card feature-card h-100 border-0">
                        <div class="card-body p-4 text-center">
                            <div class="feature-icon bg-primary bg-opacity-10 text-primary mx-auto">
                                <i class="fas fa-pills"></i>
                            </div>
                            <h4 class="mb-3">Gestão de Medicamentos</h4>
                            <p class="text-muted">Controle completo de entradas, saídas, saldos e movimentações de medicamentos com registro de lote e validade.</p>
                            <ul class="list-unstyled mt-4 text-start">
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Controle de lotes e validades</li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Registro de princípio ativo e dosagem</li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Categorização por tipo e função</li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Controle de tarjas e restrições</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="col-md-4">
                    <div class="card feature-card h-100 border-0">
                        <div class="card-body p-4 text-center">
                            <div class="feature-icon bg-warning bg-opacity-10 text-warning mx-auto">
                                <i class="fas fa-bell"></i>
                            </div>
                            <h4 class="mb-3">Alertas Inteligentes</h4>
                            <p class="text-muted">Sistema de notificações para validades, estoque crítico e medicamentos com controle especial.</p>
                            <ul class="list-unstyled mt-4 text-start">
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Alertas de vencimento (30, 60, 90 dias)</li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Notificações de estoque mínimo</li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Avisos de medicamentos controlados</li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Alertas de temperatura e armazenamento</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="col-md-4">
                    <div class="card feature-card h-100 border-0">
                        <div class="card-body p-4 text-center">
                            <div class="feature-icon bg-info bg-opacity-10 text-info mx-auto">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h4 class="mb-3">Relatórios e Analytics</h4>
                            <p class="text-muted">Relatórios detalhados e dashboards interativos para análise e tomada de decisão estratégica.</p>
                            <ul class="list-unstyled mt-4 text-start">
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Curva ABC de medicamentos</li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Análise de giro de estoque</li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Relatórios de validade</li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Indicadores de performance</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section id="benefits" class="py-5 bg-light">
        <div class="container py-5">
            <div class="row">
                <div class="col-lg-6">
                    <h2 class="section-title">Vantagens do Sistema</h2>
                    <p class="lead">O PharmaStock foi desenvolvido para simplificar e otimizar a gestão de estoques farmacêuticos</p>
                    
                    <div class="d-flex align-items-start mb-4">
                        <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                            <i class="fas fa-check-circle text-primary"></i>
                        </div>
                        <div>
                            <h5>Redução de Perdas</h5>
                            <p class="text-muted mb-0">Controle rigoroso de prazos de validade reduz perdas em até 85%</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-start mb-4">
                        <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                            <i class="fas fa-check-circle text-primary"></i>
                        </div>
                        <div>
                            <h5>Conformidade Regulatória</h5>
                            <p class="text-muted mb-0">Atendimento às exigências da Anvisa e demais órgãos reguladores</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-start mb-4">
                        <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                            <i class="fas fa-check-circle text-primary"></i>
                        </div>
                        <div>
                            <h5>Otimização de Processos</h5>
                            <p class="text-muted mb-0">Automatize processos manuais e ganhe eficiência operacional</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h4 class="mb-4">Solicite uma Demonstração</h4>
                            <p class="text-muted mb-4">Preencha o formulário e nossa equipe entrará em contato para agendar uma demonstração personalizada</p>
                            <form>
                                <div class="mb-3">
                                    <label class="form-label">Nome Completo</label>
                                    <input type="text" class="form-control" placeholder="Seu nome">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email Profissional</label>
                                    <input type="email" class="form-control" placeholder="seu@email.com">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Telefone</label>
                                    <input type="text" class="form-control" placeholder="(00) 00000-0000">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Empresa</label>
                                    <input type="text" class="form-control" placeholder="Nome da empresa">
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Solicitar Demonstração</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Screenshots Section -->
    <section id="screenshots" class="py-5 bg-white">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="section-title text-center d-inline-block">Interface do Sistema</h2>
                <p class="text-muted w-lg-50 mx-auto">Conheça nosso painel administrativo completo e intuitivo desenvolvido para profissionais farmacêuticos</p>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card shadow-sm h-100 border-0">
                        <div class="card-body p-0 overflow-hidden rounded-top">
                            <img src="https://cdn.pixabay.com/photo/2017/05/15/18/09/medicine-2316259_1280.jpg" 
                                 alt="Painel de Controle" 
                                 class="img-fluid w-100">
                        </div>
                        <div class="card-footer bg-white border-0">
                            <h5 class="mb-1">Dashboard Principal</h5>
                            <p class="text-muted mb-0">Visão geral com indicadores chave e alertas prioritários</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow-sm h-100 border-0">
                        <div class="card-body p-0 overflow-hidden rounded-top">
                            <img src="https://cdn.pixabay.com/photo/2017/01/31/22/06/medical-2027619_1280.jpg" 
                                 alt="Gestão de Medicamentos" 
                                 class="img-fluid w-100">
                        </div>
                        <div class="card-footer bg-white border-0">
                            <h5 class="mb-1">Controle de Medicamentos</h5>
                            <p class="text-muted mb-0">Interface completa para gestão de itens do estoque</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-md-3 col-6">
                    <h2 class="display-4 fw-bold">2.000+</h2>
                    <p class="mb-0">Medicamentos Controlados</p>
                </div>
                <div class="col-md-3 col-6">
                    <h2 class="display-4 fw-bold">99.8%</h2>
                    <p class="mb-0">Disponibilidade</p>
                </div>
                <div class="col-md-3 col-6">
                    <h2 class="display-4 fw-bold">500+</h2>
                    <p class="mb-0">Clientes Satisfeitos</p>
                </div>
                <div class="col-md-3 col-6">
                    <h2 class="display-4 fw-bold">24/7</h2>
                    <p class="mb-0">Suporte Especializado</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-5 bg-light">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="section-title text-center d-inline-block">O que Nossos Clientes Dizem</h2>
                <p class="text-muted w-lg-50 mx-auto">Depoimentos de farmacêuticos e gestores que utilizam nosso sistema</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card testiomonial-card h-100 border-0">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-4">
                                <img src="https://randomuser.me/api/portraits/women/65.jpg" alt="User" class="rounded-circle me-3" width="60" height="60">
                                <div>
                                    <h5 class="mb-0">Dra. Mariana Santos</h5>
                                    <small class="text-muted">Farmacêutica - Drogaria Central</small>
                                </div>
                            </div>
                            <p class="text-muted">"O sistema revolucionou nosso controle de validades. Reduzimos perdas em 80% no primeiro ano de uso."</p>
                            <div class="pharma-badge d-inline-block">Há 2 anos utilizando</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card testiomonial-card h-100 border-0">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-4">
                                <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="User" class="rounded-circle me-3" width="60" height="60">
                                <div>
                                    <h5 class="mb-0">Carlos Oliveira</h5>
                                    <small class="text-muted">Gerente - Farmácia São Paulo</small>
                                </div>
                            </div>
                            <p class="text-muted">"A interface intuitiva e os relatórios detalhados nos permitem tomar decisões mais estratégicas sobre nosso estoque."</p>
                            <div class="pharma-badge d-inline-block">Há 1 ano utilizando</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card testiomonial-card h-100 border-0">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-4">
                                <img src="https://randomuser.me/api/portraits/women/45.jpg" alt="User" class="rounded-circle me-3" width="60" height="60">
                                <div>
                                    <h5 class="mb-0">Dra. Ana Costa</h5>
                                    <small class="text-muted">Responsável Técnica - Hospital Lifecare</small>
                                </div>
                            </div>
                            <p class="text-muted">"Os alertas automáticos nos poupam horas de verificação manual e garantem conformidade com as regulamentações."</p>
                            <div class="pharma-badge d-inline-block">Há 3 anos utilizando</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5 bg-white">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="fw-bold mb-4">Pronto para transformar sua gestão farmacêutica?</h2>
                    <p class="lead text-muted mb-5">Experimente o PharmaStock gratuitamente por 15 dias e descubra como podemos otimizar seu controle de medicamentos.</p>
                    <div class="d-flex flex-wrap gap-3 justify-content-center">
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-5 py-3">
                            <i class="fas fa-rocket me-2"></i> Testar Gratuitamente
                        </a>
                        <a href="#contact" class="btn btn-outline-primary btn-lg px-5 py-3">
                            <i class="fas fa-phone me-2"></i> Falar com Vendas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-5 bg-light">
        <div class="container py-5">
            <div class="row">
                <div class="col-lg-5">
                    <h2 class="fw-bold mb-4">Entre em Contato</h2>
                    <p class="text-muted mb-5">Tem dúvidas ou precisa de mais informações? Nossa equipe especializada está pronta para ajudar.</p>
                    
                    <div class="d-flex align-items-start mb-4">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">Endereço</h5>
                            <p class="text-muted mb-0">Rua Antonío Agostinho Neto, Hospital Pioneiro Zeca, Lubango, Huíla</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-start mb-4">
                        <div class="contact-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">Telefone</h5>
                            <p class="text-muted mb-0">(+244) 921 325 327 / 933 319 510</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-start mb-4">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">Email</h5>
                            <p class="text-muted mb-0">edsocarlos027@outlook.com</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <h4 class="mb-4">Envie uma mensagem</h4>
                            <form>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nome Completo</label>
                                        <input type="text" class="form-control" placeholder="Seu nome">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" placeholder="seu@email.com">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Assunto</label>
                                    <input type="text" class="form-control" placeholder="Assunto da mensagem">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Mensagem</label>
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
    <footer class="py-5 text-white">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-pills me-2" style="color: var(--pharma-blue);"></i> PharmaStock
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
                    <p class="text-muted">Assine nossa newsletter para receber atualizações sobre o sistema.</p>
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
        
        // Smooth scroll para links internos
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>