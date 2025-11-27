<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Productos - IKIGAI</title>
    <meta name="description" content="Explora nuestro catálogo completo de suplementos naturales para tu salud y bienestar.">

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
                    <a href="{{ route('products.index') }}" class="text-sm text-black font-medium transition-colors duration-200">
                        Productos
                    </a>
                    <a href="/#nutricionistas" class="text-sm text-gray-600 hover:text-black transition-colors duration-200">
                        Nutricionistas
                    </a>
                    <a href="{{ route('resources.index') }}" class="text-sm text-gray-600 hover:text-black transition-colors duration-200">
                        Recursos
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
    <section class="relative bg-beige py-20 lg:py-24 border-b border-gray-200 overflow-hidden">
        <!-- Imagen de fondo con opacidad -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/products-hero-bg.jpg') }}" 
                 alt="Productos naturales" 
                 class="w-full h-full object-cover opacity-20">
        </div>
        
        <!-- Contenido -->
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <h1 class="text-5xl sm:text-6xl lg:text-7xl font-light text-black mb-6 tracking-tight">
                    Productos
                </h1>
                <p class="text-xl text-gray-600 font-light">
                    Descubre nuestra selección de suplementos naturales diseñados para tu bienestar
                </p>
            </div>
        </div>
    </section>

    <!-- Filtros y Búsqueda -->
    <section class="bg-white py-8 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="GET" action="{{ route('products.index') }}" class="flex flex-col lg:flex-row gap-4">
                <!-- Búsqueda -->
                <div class="flex-1">
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Buscar productos..." 
                        value="{{ request('search') }}"
                        class="w-full px-6 py-3 border border-gray-200 bg-white text-sm focus:outline-none focus:border-sage focus:ring-2 focus:ring-sage focus:ring-opacity-20 transition-colors duration-200"
                    >
                </div>

                <!-- Categoría -->
                <div class="w-full lg:w-64">
                    <select 
                        name="category" 
                        class="w-full px-6 py-3 border border-gray-200 bg-white text-sm focus:outline-none focus:border-sage focus:ring-2 focus:ring-sage focus:ring-opacity-20 transition-colors duration-200"
                        onchange="this.form.submit()"
                    >
                        <option value="">Todas las categorías</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Ordenar -->
                <div class="w-full lg:w-64">
                    <select 
                        name="sort" 
                        class="w-full px-6 py-3 border border-gray-200 bg-white text-sm focus:outline-none focus:border-sage focus:ring-2 focus:ring-sage focus:ring-opacity-20 transition-colors duration-200"
                        onchange="this.form.submit()"
                    >
                        <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Más recientes</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nombre A-Z</option>
                        <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Precio: menor a mayor</option>
                    </select>
                </div>

                <button 
                    type="submit"
                    class="bg-sage text-white hover:bg-sage-dark px-8 py-3 text-sm font-medium transition-colors duration-200 shadow-md hover:shadow-lg"
                >
                    Buscar
                </button>
            </form>

            <!-- Filtros activos -->
            @if(request('search') || request('category'))
                <div class="mt-4 flex items-center gap-3">
                    <span class="text-xs text-gray-500 uppercase tracking-wider">Filtros activos:</span>
                    <div class="flex gap-2">
                        @if(request('search'))
                            <a href="{{ route('products.index', array_merge(request()->except('search'), [])) }}" 
                               class="inline-flex items-center bg-sage-light border border-sage px-3 py-1 text-xs text-sage-dark hover:border-sage-dark transition-colors duration-200">
                                Búsqueda: "{{ request('search') }}"
                                <svg class="w-3 h-3 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </a>
                        @endif
                        @if(request('category'))
                            <a href="{{ route('products.index', array_merge(request()->except('category'), [])) }}" 
                               class="inline-flex items-center bg-sage-light border border-sage px-3 py-1 text-xs text-sage-dark hover:border-sage-dark transition-colors duration-200">
                                Categoría: {{ $categories->find(request('category'))->name ?? '' }}
                                <svg class="w-3 h-3 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </a>
                        @endif
                        <a href="{{ route('products.index') }}" class="text-xs text-sage-dark hover:text-sage underline">
                            Limpiar todo
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Grid de Productos -->
    <section class="py-16 lg:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if($products->count() > 0)
                <!-- Contador de resultados -->
                <div class="mb-8">
                    <p class="text-sm text-gray-500">
                        Mostrando {{ $products->firstItem() }}-{{ $products->lastItem() }} de {{ $products->total() }} productos
                    </p>
                </div>

                <!-- Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-1 mb-16">
                    @foreach($products as $product)
                        <div class="group bg-white border border-gray-200 hover:border-black transition-colors duration-200">
                            <a href="{{ route('products.show', $product->slug) }}" class="block p-8">
                                <!-- Imagen del producto -->
                                <div class="h-64 flex items-center justify-center mb-6 bg-gray-50">
                                    @if($product->images && count($product->images) > 0)
                                        <img src="{{ asset('storage/' . $product->images[0]) }}" 
                                             alt="{{ $product->name }}" 
                                             class="h-full w-auto object-contain opacity-90 group-hover:opacity-100 transition-opacity duration-200">
                                    @else
                                        <div class="w-24 h-24 border-2 border-gray-200 flex items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Info del producto -->
                                <div>
                                    @if($product->category)
                                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">
                                            {{ $product->category->name }}
                                        </p>
                                    @endif
                                    
                                    <h3 class="text-lg font-normal text-black mb-4">
                                        {{ $product->name }}
                                    </h3>
                                    
                                    <!-- Precio -->
                                    <div class="flex items-baseline gap-2">
                                        <span class="text-base font-medium text-black">
                                            ${{ number_format($product->price, 2) }}
                                        </span>
                                        @if($product->compare_price && $product->compare_price > $product->price)
                                            <span class="text-sm text-gray-400 line-through">
                                                ${{ number_format($product->compare_price, 2) }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <!-- Stock bajo -->
                                    @if($product->stock > 0 && $product->stock <= 5)
                                        <p class="text-xs text-red-600 mt-2">
                                            Solo quedan {{ $product->stock }} unidades
                                        </p>
                                    @elseif($product->stock <= 0)
                                        <p class="text-xs text-gray-400 mt-2">
                                            Agotado
                                        </p>
                                    @endif
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                <!-- Paginación -->
                @if($products->hasPages())
                    <div class="flex justify-center">
                        <div class="flex items-center gap-2">
                            {{-- Botón anterior --}}
                            @if($products->onFirstPage())
                                <span class="px-4 py-2 border border-gray-200 text-gray-400 text-sm cursor-not-allowed">
                                    Anterior
                                </span>
                            @else
                                <a href="{{ $products->previousPageUrl() }}" 
                                   class="px-4 py-2 border border-sage text-sage-dark hover:border-sage-dark text-sm transition-colors duration-200">
                                    Anterior
                                </a>
                            @endif

                            {{-- Números de página --}}
                            @foreach(range(1, $products->lastPage()) as $page)
                                @if($page == $products->currentPage())
                                    <span class="px-4 py-2 bg-sage text-white text-sm">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $products->url($page) }}" 
                                       class="px-4 py-2 border border-sage text-sage-dark hover:border-sage-dark text-sm transition-colors duration-200">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach

                            {{-- Botón siguiente --}}
                            @if($products->hasMorePages())
                                <a href="{{ $products->nextPageUrl() }}" 
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <h3 class="text-xl font-normal text-black mb-2">
                        No se encontraron productos
                    </h3>
                    <p class="text-sm text-gray-500 mb-6">
                        Intenta ajustar tus filtros de búsqueda
                    </p>
                    @if(request()->hasAny(['search', 'category', 'sort']))
                        <a href="{{ route('products.index') }}" 
                           class="inline-flex items-center text-sage-dark hover:text-sage text-sm font-medium border-b border-sage-dark hover:border-sage transition-colors duration-200">
                            Ver todos los productos
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 lg:py-32 bg-sage border-t border-sage-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl sm:text-5xl font-light text-white mb-6 tracking-tight">
                ¿Necesitas ayuda personalizada?
            </h2>
            <p class="text-lg text-white font-light mb-10 max-w-2xl mx-auto opacity-90">
                Nuestros nutricionistas certificados pueden ayudarte a elegir los mejores suplementos para tus objetivos
            </p>
            <a href="/#nutricionistas" class="inline-flex items-center justify-center bg-white text-sage-dark hover:bg-beige px-10 py-4 text-base font-medium transition-colors duration-200 shadow-md hover:shadow-lg">
                Consultar con un nutricionista
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-black text-gray-500 py-16 border-t border-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <!-- Logo y descripción -->
                <div class="col-span-1">
                    <h3 class="text-xl font-light text-white mb-4 tracking-tight">IKIGAI</h3>
                    <p class="text-xs font-light">
                        Equilibrio y bienestar integral
                    </p>
                </div>

                <!-- Enlaces rápidos -->
                <div>
                    <h4 class="text-white text-xs uppercase tracking-wider mb-4 font-medium">Productos</h4>
                    <ul class="space-y-2 text-xs font-light">
                        <li><a href="{{ route('products.index') }}" class="hover:text-white transition-colors duration-200">Todos</a></li>
                        <li><a href="{{ route('categories.index') }}" class="hover:text-white transition-colors duration-200">Categorías</a></li>
                    </ul>
                </div>

                <!-- Servicios -->
                <div>
                    <h4 class="text-white text-xs uppercase tracking-wider mb-4 font-medium">Servicios</h4>
                    <ul class="space-y-2 text-xs font-light">
                        <li><a href="{{ route('services.index') }}" class="hover:text-white transition-colors duration-200">Consultas</a></li>
                        <li><a href="/#nutricionistas" class="hover:text-white transition-colors duration-200">Nutricionistas</a></li>
                        <li><a href="{{ route('resources.index') }}" class="hover:text-white transition-colors duration-200">Recursos</a></li>
                    </ul>
                </div>

                <!-- Legal -->
                <div>
                    <h4 class="text-white text-xs uppercase tracking-wider mb-4 font-medium">Legal</h4>
                    <ul class="space-y-2 text-xs font-light">
                        <li><a href="#" class="hover:text-white transition-colors duration-200">Términos</a></li>
                        <li><a href="#" class="hover:text-white transition-colors duration-200">Privacidad</a></li>
                        <li><a href="{{ route('cookies.policy') }}" class="hover:text-white transition-colors duration-200">Cookies</a></li>
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
        // Cargar badge del carrito al inicio
        document.addEventListener('DOMContentLoaded', function() {
            loadCartBadge();
        });

        // Cargar badge del carrito
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

    <!-- Cookie Banner -->
    @include('components.cookie-banner')

</body>
</html>

