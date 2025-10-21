<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PharmaStock Manager</title>
    
    <!-- Bootstrap CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" 
          rel="stylesheet"
          integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM"
          crossorigin="anonymous">
    
    <!-- Font Awesome via CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
          rel="stylesheet"
          integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
          crossorigin="anonymous">
    
    <style>
        :root {
            --primary-color: #2c5aa0;
            --primary-light: #3a6bc7;
            --secondary-color: #4e73df;
            --secondary-light: #6c8ae8;
            --danger-color: #e74a3b;
            --warning-color: #f6c23e;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --light-bg: #f8f9fc;
            --dark-bg: #1a2235;
            --light-text: #f8f9fc;
            --dark-text: #2e3a59;
            --gray-text: #6c757d;
            --border-radius: 12px;
            --transition-speed: 0.3s;
        }
        
        .dark-mode {
            --light-bg: #1a2235;
            --dark-text: #f8f9fc;
            --gray-text: #b0b7c3;
        }
        
        body {
            background: linear-gradient(135deg, var(--dark-bg) 0%, #232c41 100%);
            color: var(--dark-text);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            transition: background-color var(--transition-speed);
        }
        
        .dark-mode body {
            background: linear-gradient(135deg, var(--dark-bg) 0%, #232c41 100%);
        }
        
        .login-container {
            max-width: 1100px;
            width: 100%;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.2);
            transition: transform var(--transition-speed) ease, box-shadow var(--transition-speed) ease;
        }
        
        .login-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 1rem 3rem 0 rgba(58, 59, 69, 0.25);
        }
        
        .login-left {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        .login-left::before {
            content: '';
            position: absolute;
            top: -70px;
            right: -70px;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
        }
        
        .login-left::after {
            content: '';
            position: absolute;
            bottom: -80px;
            left: -80px;
            width: 250px;
            height: 250px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
        }
        
        .login-right {
            background: white;
            padding: 3rem;
            transition: background-color var(--transition-speed);
        }
        
        .dark-mode .login-right {
            background: #222c44;
        }
        
        .brand-text {
            font-weight: 800;
            font-size: 1.8rem;
            position: relative;
            z-index: 2;
        }
        
        .medicine-img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            transition: transform var(--transition-speed) ease;
        }
        
        .medicine-img:hover {
            transform: scale(1.03);
        }
        
        .btn-primary {
            background: linear-gradient(to right, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            padding: 0.8rem 2rem;
            font-weight: 600;
            transition: all var(--transition-speed) ease;
            box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
            position: relative;
            overflow: hidden;
        }
        
        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.7s ease;
        }
        
        .btn-primary:hover::before {
            left: 100%;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08);
            background: linear-gradient(to right, var(--primary-light) 0%, var(--secondary-light) 100%);
        }
        
        .form-control {
            padding: 0.9rem 1rem;
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
            transition: all var(--transition-speed) ease;
            background-color: #fff;
        }
        
        .dark-mode .form-control {
            background-color: #2a3651;
            border-color: #3a4562;
            color: var(--light-text);
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(44, 92, 197, 0.25);
            transform: translateY(-2px);
        }
        
        .input-group-text {
            background-color: #f8f9fc;
            border: 1px solid #e2e8f0;
            transition: all var(--transition-speed) ease;
        }
        
        .dark-mode .input-group-text {
            background-color: #2a3651;
            border-color: #3a4562;
            color: var(--light-text);
        }
        
        .form-control:focus + .input-group-text,
        .form-control:focus ~ .input-group-text {
            border-color: var(--primary-color);
            background-color: rgba(44, 92, 197, 0.05);
        }
        
        .pharmacy-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        
        .feature-list {
            list-style-type: none;
            padding: 0;
        }
        
        .feature-list li {
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            position: relative;
            z-index: 2;
            transition: transform var(--transition-speed) ease;
        }
        
        .feature-list li:hover {
            transform: translateX(5px);
        }
        
        .feature-list i {
            background-color: rgba(255, 255, 255, 0.2);
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            transition: all var(--transition-speed) ease;
        }
        
        .feature-list li:hover i {
            background-color: rgba(255, 255, 255, 0.3);
            transform: scale(1.1) rotate(5deg);
        }
        
        .card {
            border-radius: var(--border-radius);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            border: none;
            transition: all var(--transition-speed) ease;
        }
        
        .dark-mode .card {
            background-color: #222c44;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .text-blue {
            color: var(--primary-color) !important;
        }
        
        .dark-mode .text-blue {
            color: var(--secondary-light) !important;
        }
        
        .theme-toggle {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.2);
            width: 50px;
            height: 24px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            padding: 2px;
            cursor: pointer;
            z-index: 100;
            transition: background var(--transition-speed) ease;
        }
        
        .theme-toggle-handle {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: white;
            transition: transform var(--transition-speed) ease;
        }
        
        .dark-mode .theme-toggle {
            background: rgba(0, 0, 0, 0.3);
        }
        
        .dark-mode .theme-toggle-handle {
            transform: translateX(26px);
        }
        
        .password-toggle {
            cursor: pointer;
            transition: color var(--transition-speed) ease;
        }
        
        .password-toggle:hover {
            color: var(--primary-color) !important;
        }
        
        .loading-bar {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 4px;
            width: 0%;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            border-radius: 0 0 var(--border-radius) var(--border-radius);
            transition: width 0.5s ease;
        }
        
        .btn-primary:hover .loading-bar {
            width: 100%;
        }
        
        .floating-element {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        
        .validity-label {
            color: #000000 !important; /* Cor preta para a legenda de validade */
            font-weight: 500;
            margin-top: 10px;
            background-color: rgba(255, 255, 255, 0.7);
            padding: 5px 10px;
            border-radius: 5px;
            display: inline-block;
        }
        
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }
            
            .login-left, .login-right {
                width: 100%;
                padding: 2rem;
            }
            
            body {
                padding: 10px;
            }
            
            .theme-toggle {
                top: 10px;
                right: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="theme-toggle" id="themeToggle">
        <div class="theme-toggle-handle"></div>
    </div>

    <div class="container login-container">
        <div class="row no-gutters">
            <!-- Left side with information -->
            <div class="col-md-6 login-left pharmacy-pattern">
                <div class="text-center mb-4 floating-element">
                    <h1 class="brand-text">
                        <i class="fas fa-pills me-2"></i>
                        <span class="text-white">PharmaStock</span> <span class="text-warning">Manager</span>
                    </h1>
                    <p class="lead" style="color: #a8c6ff!important; font-weight: 500;">Sistema de Gestão de Estoque de Medicamentos</p>
                </div>
                
                <img src="https://images.unsplash.com/photo-1585435557343-3b092031d5b5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" 
                     alt="Sistema de Gestão de Medicamentos" 
                     class="medicine-img mb-4 floating-element">
                
                <h4 class="mb-3" style="color: #a8c6ff!important;">Benefícios do Sistema:</h4>
                <ul class="feature-list">
                    <li><i class="fas fa-check"></i> Controle completo de estoque</li>
                    <li><i class="fas fa-check"></i> Alertas de validade automáticos</li>
                    <li><i class="fas fa-check"></i> Relatórios detalhados</li>
                    <li><i class="fas fa-check"></i> Interface intuitiva</li>
                    <li><i class="fas fa-check"></i> Segurança de dados</li>
                </ul>
                
                <!-- Legenda de validade em preto -->
                <div class="text-center mt-4">
                    <span class="validity-label">
                        <i class="fas fa-shield-alt me-2"></i>Sistema de validação integrado
                    </span>
                </div>
            </div>
            
            <!-- Right side with login form -->
            <div class="col-md-6 login-right">
                <div class="card border-0">
                    <div class="card-header bg-white border-0 pb-0">
                        <h3 class="text-center text-blue">{{ __('Login') }}</h3>
                        <p class="text-center" style="color: var(--dark-text); opacity: 0.8;">Acesse sua conta para gerenciar seu estoque</p>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}" id="loginForm">
                            @csrf

                            <div class="row mb-3">
                                <label for="email" class="form-label" style="color: var(--dark-text);">{{ __('Email Address') }}</label>
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope" style="color: var(--primary-color);"></i></span>
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                               name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                                               placeholder="Seu endereço de email">
                                        <span class="input-group-text d-none" id="emailValid"><i class="fas fa-check text-success"></i></span>
                                        <span class="input-group-text d-none" id="emailInvalid"><i class="fas fa-times text-danger"></i></span>
                                    </div>
                                    <div class="form-text" id="emailHelp" style="color: var(--gray-text);">Digite um email válido</div>

                                    @error('email')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="form-label" style="color: var(--dark-text);">{{ __('Password') }}</label>
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock" style="color: var(--primary-color);"></i></span>
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                               name="password" required autocomplete="current-password"
                                               placeholder="Sua senha">
                                        <span class="input-group-text password-toggle" id="togglePassword">
                                            <i class="fas fa-eye" style="color: var(--primary-color);"></i>
                                        </span>
                                    </div>
                                    <div class="form-text" id="passwordHelp" style="color: var(--gray-text);">Mínimo de 8 caracteres</div>

                                    @error('password')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="remember" style="color: var(--dark-text);">
                                            {{ __('Lembrar-me') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-12 position-relative">
                                    <button type="submit" class="btn btn-primary w-100 py-2">
                                        <i class="fas fa-sign-in-alt me-2"></i> {{ __('Login') }}
                                        <div class="loading-bar"></div>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="row mt-3">
                                <div class="col-md-12 text-center">
                                    <a href="#" style="color: var(--primary-color); text-decoration: none; transition: color var(--transition-speed) ease;">Esqueceu sua senha?</a>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle JS via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
            crossorigin="anonymous"></script>
            
    <script>
        // Toggle de tema claro/escuro
        const themeToggle = document.getElementById('themeToggle');
        themeToggle.addEventListener('click', () => {
            document.body.classList.toggle('dark-mode');
            
            // Salvar preferência no localStorage
            const isDarkMode = document.body.classList.contains('dark-mode');
            localStorage.setItem('darkMode', isDarkMode);
        });
        
        // Verificar preferência salva
        if (localStorage.getItem('darkMode') === 'true') {
            document.body.classList.add('dark-mode');
        }
        
        // Toggle de visibilidade de senha
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        
        togglePassword.addEventListener('click', function () {
            // Alternar o tipo de input
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            // Alternar o ícone
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
        
        // Validação de email em tempo real
        const email = document.getElementById('email');
        const emailValid = document.getElementById('emailValid');
        const emailInvalid = document.getElementById('emailInvalid');
        const emailHelp = document.getElementById('emailHelp');
        
        email.addEventListener('input', function() {
            const emailValue = this.value;
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (emailValue === '') {
                emailValid.classList.add('d-none');
                emailInvalid.classList.add('d-none');
                emailHelp.textContent = 'Digite um email válido';
                emailHelp.style.color = 'var(--gray-text)';
            } else if (emailPattern.test(emailValue)) {
                emailValid.classList.remove('d-none');
                emailInvalid.classList.add('d-none');
                emailHelp.textContent = 'Email válido';
                emailHelp.style.color = 'var(--success-color)';
            } else {
                emailValid.classList.add('d-none');
                emailInvalid.classList.remove('d-none');
                emailHelp.textContent = 'Formato de email inválido';
                emailHelp.style.color = 'var(--danger-color)';
            }
        });
        
        // Validação de senha em tempo real
        const passwordHelp = document.getElementById('passwordHelp');
        
        password.addEventListener('input', function() {
            const passwordValue = this.value;
            
            if (passwordValue === '') {
                passwordHelp.textContent = 'Mínimo de 8 caracteres';
                passwordHelp.style.color = 'var(--gray-text)';
            } else if (passwordValue.length >= 8) {
                passwordHelp.textContent = 'Senha forte';
                passwordHelp.style.color = 'var(--success-color)';
            } else {
                passwordHelp.textContent = 'Senha muito curta';
                passwordHelp.style.color = 'var(--danger-color)';
            }
        });
        
        // Efeito de loading no submit
        const loginForm = document.getElementById('loginForm');
        
        loginForm.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Entrando...';
        });
    </script>
</body>
</html>