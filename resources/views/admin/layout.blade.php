<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel de Administración') - IKIGAI</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @yield('styles')
</head>
<body class="antialiased bg-white font-sans">
    
    <!-- Header -->
    <header class="fixed top-0 left-0 right-0 h-16 bg-white border-b border-gray-100 z-50">
        <div class="h-full px-6 flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center gap-12">
                <a href="{{ route('admin.dashboard') }}" class="text-lg font-light tracking-tight text-black">
                    IKIGAI
                </a>
                
                <!-- Main Nav -->
                <nav class="hidden md:flex items-center gap-8">
                    <a href="{{ route('admin.dashboard') }}" class="text-sm {{ request()->routeIs('admin.dashboard') ? 'text-black border-b border-black' : 'text-gray-500 hover:text-black' }} pb-1 transition-colors duration-200">
                        Dashboard
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="text-sm {{ request()->routeIs('admin.products.*') ? 'text-black border-b border-black' : 'text-gray-500 hover:text-black' }} pb-1 transition-colors duration-200">
                        Productos
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="text-sm {{ request()->routeIs('admin.categories.*') ? 'text-black border-b border-black' : 'text-gray-500 hover:text-black' }} pb-1 transition-colors duration-200">
                        Categorías
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="text-sm {{ request()->routeIs('admin.users.*') ? 'text-black border-b border-black' : 'text-gray-500 hover:text-black' }} pb-1 transition-colors duration-200">
                        Usuarios
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="text-sm {{ request()->routeIs('admin.orders.*') ? 'text-black border-b border-black' : 'text-gray-500 hover:text-black' }} pb-1 transition-colors duration-200">
                        Pedidos
                    </a>
                    <a href="{{ route('admin.reviews.index') }}" class="text-sm {{ request()->routeIs('admin.reviews.*') ? 'text-black border-b border-black' : 'text-gray-500 hover:text-black' }} pb-1 transition-colors duration-200">
                        Reseñas
                    </a>
                </nav>
            </div>
            
            <!-- User Menu -->
            <div class="flex items-center gap-6">
                <a href="/" class="text-sm text-gray-500 hover:text-black transition-colors duration-200">
                    Ver sitio
                </a>
                
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-black text-white flex items-center justify-center text-xs font-light">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="hidden sm:block">
                        <div class="text-sm font-normal text-black">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-gray-500">Administrador</div>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-gray-500 hover:text-black transition-colors duration-200">
                        Salir
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="pt-16 min-h-screen">
        <div class="max-w-7xl mx-auto px-6 py-12">
            
            <!-- Alerts -->
            @if(session('success'))
                <div class="mb-8 px-6 py-4 bg-green-50 border-l-2 border-green-600 text-sm text-green-900">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-8 px-6 py-4 bg-red-50 border-l-2 border-red-600 text-sm text-red-900">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-8 px-6 py-4 bg-red-50 border-l-2 border-red-600 text-sm text-red-900">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            @yield('content')
        </div>
    </main>

    <script>
        // Cerrar alertas automáticamente después de 5 segundos
        setTimeout(() => {
            document.querySelectorAll('[class*="bg-green-50"], [class*="bg-red-50"]').forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);

        // Confirmación para eliminar
        function confirmDelete(message = '¿Estás seguro de que deseas eliminar este elemento?') {
            return confirm(message);
        }

        // CSRF Token para peticiones AJAX
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    </script>

    @yield('scripts')
</body>
</html>
