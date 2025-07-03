<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PharmaStock - Gestão de Medicamentos</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Alpine.js para interatividade -->
    <script src="//unpkg.com/alpinejs" defer></script>

    <style>
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
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <!-- Navbar -->
    <nav class="bg-white bg-opacity-90 backdrop-blur-sm shadow-md fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <i class="fas fa-pills text-indigo-600 text-2xl mr-2"></i>
                        <span class="text-xl font-bold text-indigo-800">Pharma<span class="text-indigo-500">Stock</span></span>
                    </div>
                </div>
                <div class="flex items-center">
                    <a href="{{ route('login') }}"
                       class="ml-4 px-6 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-full shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="pt-24 pb-12 sm:pt-32 sm:pb-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="fade-in">
                    <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900 tracking-tight">
                        Controle Inteligente de
                        <span class="block mt-2 text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">
                            Estoque de Medicamentos
                        </span>
                    </h1>
                    <p class="mt-5 text-lg text-gray-600 max-w-lg">
                        Gerencie seu estoque farmacêutico com precisão, receba alertas de validade e otimize sua gestão com nossa plataforma especializada.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-4">
                        <a href="{{ route('register') }}"
                           class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-medium rounded-full shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                            Comece Agora
                        </a>
                        <a href="#features"
                           class="px-8 py-3 bg-white text-indigo-600 font-medium rounded-full border border-indigo-200 shadow-md hover:shadow-lg transition-shadow duration-300">
                            <i class="fas fa-vial mr-2"></i>Conheça as Funcionalidades
                        </a>
                    </div>
                </div>
                <div class="relative">
                    <div class="floating">
                        <img src="https://cdn.pixabay.com/photo/2017/01/31/22/06/medical-2027618_1280.png"
                             alt="Medicamentos"
                             class="w-full max-w-md mx-auto">
                    </div>
                    <div class="absolute -bottom-8 -left-8 w-32 h-32 bg-indigo-200 rounded-full opacity-40"></div>
                    <div class="absolute -top-8 -right-8 w-40 h-40 bg-purple-200 rounded-full opacity-30"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Wave Divider -->
    <div class="relative h-24 overflow-hidden">
        <div class="wave absolute top-0 w-[6400px] h-24 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMjAwIDEyMCI+PHBhdGggZmlsbD0iI2ZmZiIgZmlsbC1vcGFjaXR5PSIxIiBkPSJNMCAwVjEyMEMxMjAgMCAyNDAgMCAzNjAgMEM0ODAgMCA2MDAgMCA3MjAgMEM4NDAgMCA5NjAgMCAxMDgwIDBDMTIwMCAwIDEyMDAgMCAxMjAwIDBIMFYwWiI+PC9wYXRoPjwvc3ZnPg==')]"></div>
    </div>

    <!-- Features Section -->
    <div id="features" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-base font-semibold text-indigo-600 tracking-wide uppercase">Funcionalidades</h2>
                <p class="mt-2 text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    Controle completo do seu estoque
                </p>
            </div>

            <div class="mt-16">
                <div class="grid md:grid-cols-3 gap-12">
                    <!-- Feature 1 -->
                    <div x-data="{ hover: false }"
                         @mouseenter="hover = true"
                         @mouseleave="hover = false"
                         class="bg-gradient-to-br from-blue-50 to-indigo-50 p-8 rounded-2xl shadow-lg border border-indigo-100 transform transition-all duration-500"
                         :class="hover ? 'scale-105 shadow-xl' : ''">
                        <div class="w-16 h-16 bg-indigo-500 rounded-lg flex items-center justify-center text-white text-2xl mb-6">
                            <i class="fas fa-pills"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Gestão de Medicamentos</h3>
                        <p class="text-gray-600 mb-4">
                            Cadastro completo com princípio ativo, dosagem, lote, validade e informações regulatórias.
                        </p>
                        <div class="mt-6">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                <i class="fas fa-barcode mr-2"></i>Controle de lotes
                            </span>
                        </div>
                    </div>

                    <!-- Feature 2 -->
                    <div x-data="{ hover: false }"
                         @mouseenter="hover = true"
                         @mouseleave="hover = false"
                         class="bg-gradient-to-br from-blue-50 to-indigo-50 p-8 rounded-2xl shadow-lg border border-indigo-100 transform transition-all duration-500"
                         :class="hover ? 'scale-105 shadow-xl' : ''">
                        <div class="w-16 h-16 bg-purple-500 rounded-lg flex items-center justify-center text-white text-2xl mb-6">
                            <i class="fas fa-bell"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Alertas Inteligentes</h3>
                        <p class="text-gray-600 mb-4">
                            Notificações automáticas para medicamentos próximos ao vencimento ou com estoque crítico.
                        </p>
                        <div class="mt-6">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                <i class="fas fa-calendar-exclamation mr-2"></i>Validade
                            </span>
                        </div>
                    </div>

                    <!-- Feature 3 -->
                    <div x-data="{ hover: false }"
                         @mouseenter="hover = true"
                         @mouseleave="hover = false"
                         class="bg-gradient-to-br from-blue-50 to-indigo-50 p-8 rounded-2xl shadow-lg border border-indigo-100 transform transition-all duration-500"
                         :class="hover ? 'scale-105 shadow-xl' : ''">
                        <div class="w-16 h-16 bg-blue-500 rounded-lg flex items-center justify-center text-white text-2xl mb-6">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Relatórios Analíticos</h3>
                        <p class="text-gray-600 mb-4">
                            Dashboard completo com gráficos e métricas para tomada de decisão estratégica.
                        </p>
                        <div class="mt-6">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-file-medical mr-2"></i>Análise de dados
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section
    <div class="py-16 bg-gradient-to-r from-indigo-500 to-purple-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                <span class="block">Pronto para otimizar sua gestão?</span>
                <span class="block mt-2 text-indigo-100">Experimente gratuitamente por 14 dias.</span>
            </h2>
            <div class="mt-10 flex justify-center">
                <a href="{{ route('register') }}"
                   class="px-8 py-4 bg-white text-indigo-600 font-bold rounded-full shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center">
                    <i class="fas fa-rocket mr-3"></i> Iniciar Teste Gratuito
                </a>
            </div>
        </div>
    </div>

     Footer -->
    <footer class="bg-gray-900 text-white pt-12 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-lg font-bold mb-4 flex items-center">
                        <i class="fas fa-pills text-indigo-400 mr-2"></i>
                        PharmaStock
                    </h3>
                    <p class="text-gray-400 text-sm">
                        Solução completa para gestão de estoque de medicamentos e insumos farmacêuticos.
                    </p>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-300 uppercase tracking-wider mb-4">Links Rápidos</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Sobre Nós</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Funcionalidades</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Planos</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Contato</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-300 uppercase tracking-wider mb-4">Legal</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Termos de Uso</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Política de Privacidade</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">LGPD</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-300 uppercase tracking-wider mb-4">Contato</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-3 text-indigo-400"></i>
                            <span>Rua Antonío Agostinho Neto, Hospital  Pioneiro Zeca, -Lubango, Huíla</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone-alt mr-3 text-indigo-400"></i>
                            <span>(+244) 921325327/933319510</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-3 text-indigo-400"></i>
                            <span>edsocarlos027@outlook.com.com</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="mt-12 pt-8 border-t border-gray-800 text-center text-gray-400 text-sm">
                &copy; {{ now()->year }} PharmaStock. Todos os direitos reservados,Por Eng Carlos
            </div>
        </div>
    </footer>
</body>
</html>
