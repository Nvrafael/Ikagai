<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recursos - IKIGAI</title>
    <meta name="description" content="Recetas saludables, consejos de bienestar y guías nutricionales para mejorar tu salud.">

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50 font-sans">
    
    <!-- Header -->
    <header class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="/" class="text-xl font-light tracking-tight text-black">
                        IKIGAI
                    </a>
                </div>

                <!-- Navigation -->
                <nav class="hidden md:flex space-x-12">
                    <a href="{{ route('products.index') }}" class="text-sm text-gray-600 hover:text-black transition-colors duration-200">
                        Productos
                    </a>
                    <a href="/#nutricionistas" class="text-sm text-gray-600 hover:text-black transition-colors duration-200">
                        Nutricionistas
                    </a>
                    <a href="{{ route('resources.index') }}" class="text-sm text-black font-medium transition-colors duration-200">
                        Recursos
                    </a>
                    <a href="{{ route('services.index') }}" class="text-sm text-gray-600 hover:text-black transition-colors duration-200">
                        Servicios
                    </a>
                </nav>

                <!-- Auth Buttons -->
                <div class="flex items-center space-x-6">
                    <!-- Carrito -->
                    <a href="{{ route('cart.index') }}" class="relative text-gray-600 hover:text-black transition-colors duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span id="cart-badge" class="hidden absolute -top-2 -right-2 bg-black text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-medium">
                            0
                        </span>
                    </a>
                    
                    @auth
                        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'nutritionist')
                            <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-black transition-colors duration-200">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('profile.edit') }}" class="text-sm text-gray-600 hover:text-black transition-colors duration-200">
                                Mi Perfil
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-gray-600 hover:text-black transition-colors duration-200">
                                Cerrar sesión
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-black transition-colors duration-200">
                            Iniciar sesión
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="bg-black text-white hover:bg-gray-900 px-6 py-2 text-sm font-medium transition-colors duration-200">
                                Registrarse
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="bg-beige py-20 lg:py-24 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <h1 class="text-5xl sm:text-6xl lg:text-7xl font-light text-black mb-6 tracking-tight">
                    Recursos
                </h1>
                <p class="text-xl text-gray-600 font-light">
                    Recetas saludables, consejos de bienestar y guías nutricionales para transformar tu vida
                </p>
            </div>
        </div>
    </section>

    <!-- Filtros -->
    <section class="bg-white py-8 border-b border-gray-200 sticky top-20 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                <!-- Filtros por tipo -->
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('resources.index') }}" 
                       class="px-6 py-2 text-sm font-medium transition-colors duration-200 {{ !request('type') ? 'bg-sage text-white' : 'bg-white text-gray-600 hover:text-black border border-gray-200' }}">
                        Todos
                    </a>
                    <a href="{{ route('resources.index', ['type' => 'receta']) }}" 
                       class="px-6 py-2 text-sm font-medium transition-colors duration-200 {{ request('type') == 'receta' ? 'bg-sage text-white' : 'bg-white text-gray-600 hover:text-black border border-gray-200' }}">
                        Recetas
                    </a>
                    <a href="{{ route('resources.index', ['type' => 'consejo']) }}" 
                       class="px-6 py-2 text-sm font-medium transition-colors duration-200 {{ request('type') == 'consejo' ? 'bg-sage text-white' : 'bg-white text-gray-600 hover:text-black border border-gray-200' }}">
                        Consejos
                    </a>
                    <a href="{{ route('resources.index', ['type' => 'articulo']) }}" 
                       class="px-6 py-2 text-sm font-medium transition-colors duration-200 {{ request('type') == 'articulo' ? 'bg-sage text-white' : 'bg-white text-gray-600 hover:text-black border border-gray-200' }}">
                        Artículos
                    </a>
                    <a href="{{ route('resources.index', ['type' => 'guia']) }}" 
                       class="px-6 py-2 text-sm font-medium transition-colors duration-200 {{ request('type') == 'guia' ? 'bg-sage text-white' : 'bg-white text-gray-600 hover:text-black border border-gray-200' }}">
                        Guías
                    </a>
                </div>

                <!-- Búsqueda -->
                <form method="GET" action="{{ route('resources.index') }}" class="flex-1 max-w-md">
                    @if(request('type'))
                        <input type="hidden" name="type" value="{{ request('type') }}">
                    @endif
                    <div class="relative">
                        <input 
                            type="text" 
                            name="search" 
                            placeholder="Buscar recursos..." 
                            value="{{ request('search') }}"
                            class="w-full px-4 py-2 pr-10 border border-gray-200 focus:border-sage focus:ring-2 focus:ring-sage focus:ring-opacity-20 transition-colors duration-200"
                        >
                        <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-sage">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Grid de Recursos -->
    <section class="py-16 lg:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if($resources->count() > 0)
                <!-- Contador de resultados -->
                <div class="mb-8">
                    <p class="text-sm text-gray-500">
                        Mostrando {{ $resources->firstItem() }}-{{ $resources->lastItem() }} de {{ $resources->total() }} recursos
                    </p>
                </div>

                <!-- Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
                    @foreach($resources as $resource)
                        <article class="group bg-white border border-gray-200 hover:border-black transition-all duration-200 overflow-hidden">
                            <a href="{{ route('resources.show', $resource->slug) }}" class="block">
                                <!-- Imagen -->
                                <div class="relative h-64 bg-gray-100 overflow-hidden">
                                    @if($resource->featured_image)
                                        <img src="{{ asset('storage/' . $resource->featured_image) }}" 
                                             alt="{{ $resource->title }}" 
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-beige">
                                            <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                @if($resource->type === 'receta')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                                @elseif($resource->type === 'consejo')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                                @elseif($resource->type === 'articulo')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                @else
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                @endif
                                            </svg>
                                        </div>
                                    @endif
                                    
                                    <!-- Badge de tipo -->
                                    <div class="absolute top-4 left-4">
                                        <span class="inline-block px-3 py-1 text-xs font-medium uppercase tracking-wider
                                            @if($resource->type === 'receta') bg-green-500 text-white
                                            @elseif($resource->type === 'consejo') bg-blue-500 text-white
                                            @elseif($resource->type === 'articulo') bg-purple-500 text-white
                                            @else bg-orange-500 text-white
                                            @endif">
                                            {{ $resource->type }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Contenido -->
                                <div class="p-6">
                                    <!-- Meta info -->
                                    <div class="flex items-center gap-4 mb-3 text-xs text-gray-500">
                                        <div class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span>{{ $resource->reading_time }} min</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            <span>{{ $resource->views }}</span>
                                        </div>
                                        @if($resource->type === 'receta' && $resource->metadata && isset($resource->metadata['tiempo_preparacion']))
                                            <div class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                                <span>{{ $resource->metadata['tiempo_preparacion'] }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Título -->
                                    <h3 class="text-xl font-normal text-black mb-3 group-hover:text-sage transition-colors duration-200">
                                        {{ $resource->title }}
                                    </h3>

                                    <!-- Extracto -->
                                    @if($resource->excerpt)
                                        <p class="text-sm text-gray-600 leading-relaxed mb-4">
                                            {{ Str::limit($resource->excerpt, 120) }}
                                        </p>
                                    @endif

                                    <!-- Autor y fecha -->
                                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 bg-sage text-white flex items-center justify-center rounded-full text-xs">
                                                {{ strtoupper(substr($resource->author->name, 0, 1)) }}
                                            </div>
                                            <span class="text-xs text-gray-600">{{ $resource->author->name }}</span>
                                        </div>
                                        <span class="text-xs text-gray-400">
                                            {{ $resource->published_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>

                <!-- Paginación -->
                @if($resources->hasPages())
                    <div class="flex justify-center">
                        <div class="flex items-center gap-2">
                            @if($resources->onFirstPage())
                                <span class="px-4 py-2 border border-gray-200 text-gray-400 text-sm cursor-not-allowed">
                                    Anterior
                                </span>
                            @else
                                <a href="{{ $resources->previousPageUrl() }}" 
                                   class="px-4 py-2 border border-sage text-sage-dark hover:border-sage-dark text-sm transition-colors duration-200">
                                    Anterior
                                </a>
                            @endif

                            @foreach(range(1, $resources->lastPage()) as $page)
                                @if($page == $resources->currentPage())
                                    <span class="px-4 py-2 bg-sage text-white text-sm">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $resources->url($page) }}" 
                                       class="px-4 py-2 border border-sage text-sage-dark hover:border-sage-dark text-sm transition-colors duration-200">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach

                            @if($resources->hasMorePages())
                                <a href="{{ $resources->nextPageUrl() }}" 
                                   class="px-4 py-2 border border-sage text-sage-dark hover:border-sage-dark text-sm transition-colors duration-200">
                                    Siguiente
                                </a>
                            @else
                                <span class="px-4 py-2 border border-gray-200 text-gray-400 text-sm cursor-not-allowed">
                                    Siguiente
                                </span>
                            @endif
                        </div>
                    </div>
                @endif
            @else
                <!-- Estado vacío -->
                <div class="text-center py-32 border border-gray-200 bg-white">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <h3 class="text-xl font-normal text-black mb-2">
                        No se encontraron recursos
                    </h3>
                    <p class="text-sm text-gray-500 mb-6">
                        @if(request()->has('search'))
                            Intenta ajustar tu búsqueda
                        @else
                            Próximamente tendremos contenido disponible
                        @endif
                    </p>
                    @if(request()->hasAny(['search', 'type']))
                        <a href="{{ route('resources.index') }}" 
                           class="inline-flex items-center text-sage-dark hover:text-sage text-sm font-medium border-b border-sage-dark hover:border-sage transition-colors duration-200">
                            Ver todos los recursos
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-black text-gray-500 py-16 border-t border-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="col-span-1">
                    <h3 class="text-xl font-light text-white mb-4 tracking-tight">IKIGAI</h3>
                    <p class="text-xs font-light">Equilibrio y bienestar integral</p>
                </div>
                <div>
                    <h4 class="text-white text-xs uppercase tracking-wider mb-4 font-medium">Productos</h4>
                    <ul class="space-y-2 text-xs font-light">
                        <li><a href="{{ route('products.index') }}" class="hover:text-white transition-colors duration-200">Todos</a></li>
                        <li><a href="{{ route('categories.index') }}" class="hover:text-white transition-colors duration-200">Categorías</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white text-xs uppercase tracking-wider mb-4 font-medium">Servicios</h4>
                    <ul class="space-y-2 text-xs font-light">
                        <li><a href="{{ route('services.index') }}" class="hover:text-white transition-colors duration-200">Consultas</a></li>
                        <li><a href="/#nutricionistas" class="hover:text-white transition-colors duration-200">Nutricionistas</a></li>
                        <li><a href="{{ route('resources.index') }}" class="hover:text-white transition-colors duration-200">Recursos</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white text-xs uppercase tracking-wider mb-4 font-medium">Legal</h4>
                    <ul class="space-y-2 text-xs font-light">
                        <li><a href="#" class="hover:text-white transition-colors duration-200">Términos</a></li>
                        <li><a href="#" class="hover:text-white transition-colors duration-200">Privacidad</a></li>
                        <li><a href="#" class="hover:text-white transition-colors duration-200">Contacto</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-900 pt-8">
                <p class="text-xs text-gray-600 font-light">&copy; {{ date('Y') }} IKIGAI. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadCartBadge();
        });

        async function loadCartBadge() {
            try {
                const response = await fetch('/carrito/count');
                const data = await response.json();
                
                if (data.success && data.total_items > 0) {
                    const badge = document.querySelector('#cart-badge');
                    if (badge) {
                        badge.textContent = data.total_items;
                        badge.classList.remove('hidden');
                    }
                }
            } catch (error) {
                console.error('Error al cargar el carrito:', error);
            }
        }
    </script>

</body>
</html>

