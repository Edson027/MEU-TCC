<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PharmaStock - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Alpine.js -->
    <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body class="bg-gray-100">
  @auth  <!-- Navbar -->
  <!--  <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <i class="fas fa-pills text-indigo-600 text-2xl mr-2"></i>
                        <span class="text-xl font-bold text-indigo-800">Pharma<span class="text-indigo-500">Stock</span></span>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="{{ route('medicines.index') }}" class="border-indigo-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Medicamentos
                        </a>
                    </div>

                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="{{ route('requests.index') }}" class="border-indigo-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            PEdidos
                        </a>
                    </div>


                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="{{ route('reports.index') }}" class="border-indigo-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Relatórios
                        </a>
                    </div>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    <span class="text-gray-700 mr-4">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-800 rounded-md hover:bg-gray-200">
                            Sair
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>-->


    <nav class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <i class="fas fa-pills text-indigo-600 text-2xl mr-2"></i>
                    <span class="text-xl font-bold text-indigo-800">Pharma<span class="text-indigo-500">Stock</span></span>
                </div>
                
                <!-- Menu Items -->
                <div class="hidden sm:ml-6 sm:flex">
                    <!-- Medicamentos -->
                    <div class="relative group">
                        <a href="{{ route('medicines.index') }}" 
                           class="{{ request()->routeIs('medicines.*') ? 'border-green-500 text-green-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} inline-flex items-center px-4 pt-1 border-b-2 text-sm font-medium transition-colors duration-200">
                            Medicamentos
                        </a>
                        @if(request()->routeIs('medicines.*'))
                        <div class="absolute bottom-0 left-0 w-full h-1 bg-green-500 rounded-t"></div>
                        @endif
                    </div>
                    
                    <!-- Pedidos -->
                    <div class="relative group ml-4">
                        <a href="{{ route('requests.index') }}" 
                           class="{{ request()->routeIs('requests.*') ? 'border-blue-500 text-blue-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} inline-flex items-center px-4 pt-1 border-b-2 text-sm font-medium transition-colors duration-200">
                            Pedidos
                        </a>
                        @if(request()->routeIs('requests.*'))
                        <div class="absolute bottom-0 left-0 w-full h-1 bg-blue-500 rounded-t"></div>
                        @endif
                    </div>
                    
                    <!-- Relatórios -->
                    <div class="relative group ml-4">
                        <a href="{{ route('reports.index') }}" 
                           class="{{ request()->routeIs('reports.*') ? 'border-purple-500 text-purple-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} inline-flex items-center px-4 pt-1 border-b-2 text-sm font-medium transition-colors duration-200">
                            Relatórios
                        </a>
                        @if(request()->routeIs('reports.*'))
                        <div class="absolute bottom-0 left-0 w-full h-1 bg-purple-500 rounded-t"></div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- User Info and Logout -->
            <div class="hidden sm:ml-6 sm:flex sm:items-center">
                <span class="text-gray-700 mr-4">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-800 rounded-md hover:bg-gray-200 transition-colors duration-200">
                        Sair
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
   @endauth
    <!-- Conteúdo -->
    <main class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @yield('content')
                </div>
            </div>
        </div>
    </main>

    <!-- Notificações -->
    @if(session('success'))
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 3000)"
             class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg">
            {{ session('success') }}
        </div>
    @endif
</body>
</html>
