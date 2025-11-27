<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>IKIGAI - Encuentra tu equilibrio perfecto</title>
    <meta name="description" content="Descubre el equilibrio perfecto entre tu salud y bienestar con nuestros suplementos naturales y orientación experta.">

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
                    <a href="#nutricionistas" class="text-sm text-gray-600 hover:text-black transition-colors duration-200">
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

    <!-- Mensaje de éxito (si existe) -->
    @if(session('success'))
        <div class="bg-green-50 border-b-2 border-green-500">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm font-medium text-green-900">{{ session('success') }}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.parentElement.remove()" class="text-green-600 hover:text-green-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Hero Section -->
    <section class="relative py-32 lg:py-40 border-b border-gray-200 overflow-hidden">
        <!-- Imagen de fondo -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/zen-background.jpg') }}" 
                 alt="Zen background" 
                 class="w-full h-full object-cover opacity-85"
            />
        </div>
        
        <!-- Contenido -->
        <div class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-6xl sm:text-7xl lg:text-8xl font-light text-black mb-8 tracking-tight drop-shadow-lg">
                    IKIGAI
                </h1>
                <p class="text-xl sm:text-2xl text-gray-800 mb-6 max-w-2xl mx-auto font-light drop-shadow-md">
                    Equilibrio perfecto entre salud y bienestar
                </p>
                <p class="text-sm text-gray-700 mb-12 font-mono drop-shadow-md">
                    +800-808-815 / info@ikigai.com / Lun-Vie 9am-6pm
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center bg-sage text-white hover:bg-sage-dark px-10 py-4 text-base font-medium transition-colors duration-200 shadow-lg hover:shadow-xl">
                        Explorar productos
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </a>
                    <a href="#nutricionistas" class="inline-flex items-center justify-center bg-white text-sage-dark hover:bg-sage-light px-10 py-4 text-base font-medium border border-sage transition-colors duration-200 shadow-lg">
                        Nutricionistas
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Productos Populares -->
    <section class="py-24 lg:py-32 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-20">
                <h2 class="text-4xl sm:text-5xl font-light text-black mb-3 tracking-tight">
                    Productos
                </h2>
                <p class="text-base text-gray-600 font-light">
                    Nuestra selección de suplementos naturales
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-1">
                @forelse($featuredProducts as $product)
                    <div class="group bg-white border border-gray-200 hover:border-black transition-colors duration-200">
                        <a href="{{ route('products.show', $product->slug) }}" class="block p-8">
                            <!-- Imagen del producto -->
                            <div class="h-80 flex items-center justify-center mb-6 bg-gray-50">
                                @if($product->images && count($product->images) > 0)
                                    <img src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}" class="h-full w-auto object-contain opacity-90 group-hover:opacity-100 transition-opacity duration-200">
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
                                <div class="mb-4">
                                    <h3 class="text-lg font-normal text-black mb-1">
                                        {{ $product->name }}
                                    </h3>
                                    
                                    @if($product->category)
                                        <p class="text-xs text-gray-500 uppercase tracking-wider">
                                            {{ $product->category->name }}
                                        </p>
                                    @endif
                                </div>
                                
                                <!-- Precio -->
                                <div class="flex items-baseline gap-2 mb-4">
                                    <span class="text-base font-medium text-black">
                                        ${{ number_format($product->price, 2) }}
                                    </span>
                                    @if($product->compare_price)
                                        <span class="text-sm text-gray-400 line-through">
                                            ${{ number_format($product->compare_price, 2) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </a>
                        
                        <!-- Botón de carrito -->
                        <div class="px-8 pb-8" onclick="event.stopPropagation()">
                            <button 
                                data-add-to-cart
                                data-product-id="{{ $product->id }}"
                                class="w-full inline-flex items-center justify-center bg-sage text-white hover:bg-sage-dark px-6 py-3 text-sm font-medium transition-colors duration-200"
                            >
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Agregar al carrito
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-24 border border-gray-200 bg-white">
                        <p class="text-sm text-gray-400 font-light">No hay productos disponibles</p>
                    </div>
                @endforelse
            </div>

            @if($featuredProducts->count() > 0)
                <div class="mt-16">
                    <a href="{{ route('products.index') }}" class="inline-flex items-center text-sage-dark hover:text-sage text-sm font-medium border-b border-sage-dark hover:border-sage transition-colors duration-200">
                        Ver todos los productos
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </a>
                </div>
            @endif
        </div>
    </section>

    <!-- Nutricionistas -->
    <section id="nutricionistas" class="py-24 lg:py-32 bg-beige border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-20">
                <h2 class="text-4xl sm:text-5xl font-light text-black mb-3 tracking-tight">
                    Nutricionistas
                </h2>
                <p class="text-base text-gray-600 font-light">
                    Profesionales certificados
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-1">
                @forelse($nutritionists as $nutritionist)
                    <div class="group bg-white border border-sage hover:border-sage-dark transition-colors duration-200 p-12 flex flex-col">
                        <!-- Avatar -->
                        <div class="mb-12">
                            <div class="w-48 h-48 overflow-hidden mx-auto">
                                <img src="{{ asset('images/nutritionist-photo.jpg') }}" 
                                     alt="{{ $nutritionist->name }}" 
                                     class="w-full h-full object-cover">
                            </div>
                        </div>
                        
                        <!-- Info -->
                        <div class="mt-auto text-center">
                            <h3 class="text-xl font-normal text-black mb-2">
                                {{ $nutritionist->name }}
                            </h3>
                            
                            <p class="text-xs text-sage-dark uppercase tracking-wider mb-8">
                                Nutricionista certificado
                            </p>
                            
                            <!-- Botón -->
                            <a href="{{ route('nutritionist.profile') }}" class="inline-flex items-center text-sage-dark hover:text-sage text-sm font-medium border-b border-sage-dark hover:border-sage transition-colors duration-200">
                                Ver perfil
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-24 border border-gray-200 bg-white">
                        <p class="text-sm text-gray-400 font-light">Próximamente</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Recursos -->
    <section id="recursos" class="py-24 lg:py-32 bg-white border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-20">
                <h2 class="text-4xl sm:text-5xl font-light text-black mb-3 tracking-tight">
                    Recursos
                </h2>
                <p class="text-base text-gray-600 font-light">
                    Guías y herramientas para tu bienestar
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-1">
                <!-- Guía de suplementos -->
                <a href="{{ route('products.index') }}" class="group bg-white border border-gray-200 hover:border-black transition-colors duration-200 p-12">
                    <div class="mb-6">
                        <span class="text-6xl font-light text-gray-300">01</span>
                    </div>
                    <h3 class="text-xl font-normal text-black mb-4">
                        Guía de suplementos
                    </h3>
                    <p class="text-sm text-gray-500 font-light mb-6">
                        Encuentra el mejor suplemento para tus necesidades
                    </p>
                    <span class="inline-flex items-center text-sage-dark text-sm font-medium border-b border-sage-dark">
                        Ver más
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </span>
                </a>

                <!-- Recetas saludables -->
                <a href="#" class="group bg-white border border-gray-200 hover:border-black transition-colors duration-200 p-12">
                    <div class="mb-6">
                        <span class="text-6xl font-light text-gray-300">02</span>
                    </div>
                    <h3 class="text-xl font-normal text-black mb-4">
                        Recetas saludables
                    </h3>
                    <p class="text-sm text-gray-500 font-light mb-6">
                        Ideas deliciosas para una vida sana
                    </p>
                    <span class="inline-flex items-center text-sage-dark text-sm font-medium border-b border-sage-dark">
                        Ver más
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </span>
                </a>

                <!-- Consejos de bienestar -->
                <a href="#" class="group bg-white border border-gray-200 hover:border-black transition-colors duration-200 p-12">
                    <div class="mb-6">
                        <span class="text-6xl font-light text-gray-300">03</span>
                    </div>
                    <h3 class="text-xl font-normal text-black mb-4">
                        Consejos de bienestar
                    </h3>
                    <p class="text-sm text-gray-500 font-light mb-6">
                        Tips para mejorar tu salud día a día
                    </p>
                    <span class="inline-flex items-center text-black text-sm font-medium border-b border-black">
                        Ver más
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                            </span>
                </a>
            </div>
        </div>
    </section>

    <!-- CTA Final -->
    <section class="py-32 lg:py-40 bg-sage border-t border-sage-dark">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl sm:text-5xl lg:text-6xl font-light text-white mb-12 tracking-tight">
                Comienza tu viaje hacia el IKIGAI
            </h2>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-20">
                <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center bg-white text-sage-dark hover:bg-beige px-10 py-4 text-base font-medium transition-colors duration-200 shadow-lg">
                    Explorar productos
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
                
                <a href="{{ route('services.index') }}" class="inline-flex items-center justify-center bg-sage text-white hover:bg-sage-dark px-10 py-4 text-base font-medium border border-white transition-colors duration-200">
                    Ver servicios
                </a>
            </div>
            
            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-12 max-w-4xl mx-auto border-t border-gray-800 pt-12">
                <div>
                    <div class="text-3xl font-light text-white mb-1">5000+</div>
                    <div class="text-xs text-gray-500 uppercase tracking-wider">Clientes</div>
                </div>
                <div>
                    <div class="text-3xl font-light text-white mb-1">50+</div>
                    <div class="text-xs text-gray-500 uppercase tracking-wider">Productos</div>
                </div>
                <div>
                    <div class="text-3xl font-light text-white mb-1">98%</div>
                    <div class="text-xs text-gray-500 uppercase tracking-wider">Satisfacción</div>
                </div>
                <div>
                    <div class="text-3xl font-light text-white mb-1">24/7</div>
                    <div class="text-xs text-gray-500 uppercase tracking-wider">Soporte</div>
                </div>
            </div>
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
                        <li><a href="#" class="hover:text-white transition-colors duration-200">Destacados</a></li>
                    </ul>
                </div>

                <!-- Servicios -->
                <div>
                    <h4 class="text-white text-xs uppercase tracking-wider mb-4 font-medium">Servicios</h4>
                    <ul class="space-y-2 text-xs font-light">
                        <li><a href="{{ route('services.index') }}" class="hover:text-white transition-colors duration-200">Consultas</a></li>
                        <li><a href="#nutricionistas" class="hover:text-white transition-colors duration-200">Nutricionistas</a></li>
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
        // Auto-cerrar mensajes de éxito después de 5 segundos
        document.addEventListener('DOMContentLoaded', function() {
            const successMessage = document.querySelector('.bg-green-50');
            if (successMessage) {
                setTimeout(() => {
                    successMessage.style.transition = 'opacity 0.5s ease-out';
                    successMessage.style.opacity = '0';
                    setTimeout(() => successMessage.remove(), 500);
                }, 5000);
            }

            // Cargar el carrito al inicio
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

        // Gestión del carrito
        let isProcessing = false;

        document.addEventListener('click', async function(e) {
            const btn = e.target.closest('[data-add-to-cart]');
            if (!btn || isProcessing) return;

            e.preventDefault();
            e.stopPropagation();
            isProcessing = true;

            const productId = btn.dataset.productId;
            const originalContent = btn.innerHTML;

            try {
                // Cambiar el texto del botón
                btn.disabled = true;
                btn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

                const response = await fetch('/carrito/agregar', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({
                        product_id: parseInt(productId),
                        quantity: 1
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Actualizar badge del carrito
                    const badge = document.querySelector('#cart-badge');
                    if (badge && data.total_items) {
                        badge.textContent = data.total_items;
                        badge.classList.remove('hidden');
                    }

                    // Mostrar notificación
                    showToast('¡Producto agregado al carrito!', 'success');
                    
                    // Cambiar temporalmente el contenido
                    btn.innerHTML = '<svg class="w-5 h-5 mx-auto" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>';
                    
                    setTimeout(() => {
                        btn.innerHTML = originalContent;
                        btn.disabled = false;
                    }, 2000);
                } else {
                    throw new Error(data.message || 'Error al agregar producto');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast(error.message || 'Error al agregar producto', 'error');
                btn.innerHTML = originalContent;
                btn.disabled = false;
            } finally {
                isProcessing = false;
            }
        });

        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 shadow-lg transition-all duration-300 ${
                type === 'success' ? 'bg-green-600' : 'bg-red-600'
            }`;
            toast.textContent = message;
            toast.style.transform = 'translateX(400px)';
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.style.transform = 'translateX(0)';
            }, 10);

            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(400px)';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
    </script>

    <!-- Cookie Banner -->
    @include('components.cookie-banner')

    </body>
</html>
