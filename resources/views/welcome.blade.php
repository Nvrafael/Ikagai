<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
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
                    <a href="#recursos" class="text-sm text-gray-600 hover:text-black transition-colors duration-200">
                        Recursos
                    </a>
                    <a href="{{ route('services.index') }}" class="text-sm text-gray-600 hover:text-black transition-colors duration-200">
                        Servicios
                    </a>
                </nav>

                <!-- Auth Buttons -->
                <div class="flex items-center space-x-6">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm text-gray-600 hover:text-black transition-colors duration-200">
                            Dashboard
                        </a>
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
    <section class="relative bg-white py-32 lg:py-40 border-b border-gray-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-6xl sm:text-7xl lg:text-8xl font-light text-black mb-8 tracking-tight">
                    IKIGAI
                </h1>
                <p class="text-xl sm:text-2xl text-gray-500 mb-6 max-w-2xl mx-auto font-light">
                    Equilibrio perfecto entre salud y bienestar
                </p>
                <p class="text-sm text-gray-400 mb-12 font-mono">
                    +800-808-815 / info@ikigai.com / Lun-Vie 9am-6pm
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center bg-black text-white hover:bg-gray-900 px-10 py-4 text-base font-medium transition-colors duration-200">
                        Explorar productos
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </a>
                    <a href="#nutricionistas" class="inline-flex items-center justify-center bg-white text-black hover:bg-gray-50 px-10 py-4 text-base font-medium border border-black transition-colors duration-200">
                        Nutricionistas
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Productos Populares -->
    <section class="py-24 lg:py-32 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-20">
                <h2 class="text-4xl sm:text-5xl font-light text-black mb-3 tracking-tight">
                    Productos
                </h2>
                <p class="text-base text-gray-500 font-light">
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
                                    <img src="{{ $product->images[0] }}" alt="{{ $product->name }}" class="h-full w-auto object-contain opacity-90 group-hover:opacity-100 transition-opacity duration-200">
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
                                <div class="flex items-baseline gap-2">
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
                    </div>
                @empty
                    <div class="col-span-full text-center py-24 border border-gray-200 bg-white">
                        <p class="text-sm text-gray-400 font-light">No hay productos disponibles</p>
                    </div>
                @endforelse
            </div>

            @if($featuredProducts->count() > 0)
                <div class="mt-16">
                    <a href="{{ route('products.index') }}" class="inline-flex items-center text-black hover:text-gray-600 text-sm font-medium border-b border-black hover:border-gray-600 transition-colors duration-200">
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
    <section id="nutricionistas" class="py-24 lg:py-32 bg-white border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-20">
                <h2 class="text-4xl sm:text-5xl font-light text-black mb-3 tracking-tight">
                    Nutricionistas
                </h2>
                <p class="text-base text-gray-500 font-light">
                    Profesionales certificados
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-1">
                @forelse($nutritionists as $nutritionist)
                    <div class="group bg-white border border-gray-200 hover:border-black transition-colors duration-200 p-12">
                        <!-- Avatar -->
                        <div class="mb-8">
                            <div class="w-20 h-20 bg-black text-white flex items-center justify-center">
                                <span class="text-2xl font-light">{{ $nutritionist->initials() }}</span>
                            </div>
                        </div>
                        
                        <!-- Info -->
                        <h3 class="text-xl font-normal text-black mb-2">
                            {{ $nutritionist->name }}
                        </h3>
                        
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-8">
                            Nutricionista certificado
                        </p>
                        
                        <!-- Botón -->
                        @auth
                            <a href="{{ route('messages.new-conversation') }}" class="inline-flex items-center text-black hover:text-gray-600 text-sm font-medium border-b border-black hover:border-gray-600 transition-colors duration-200">
                                Contactar
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center text-black hover:text-gray-600 text-sm font-medium border-b border-black hover:border-gray-600 transition-colors duration-200">
                                Iniciar sesión
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        @endauth
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
    <section id="recursos" class="py-24 lg:py-32 bg-gray-50 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-20">
                <h2 class="text-4xl sm:text-5xl font-light text-black mb-3 tracking-tight">
                    Recursos
                </h2>
                <p class="text-base text-gray-500 font-light">
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
                    <span class="inline-flex items-center text-black text-sm font-medium border-b border-black">
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
                    <span class="inline-flex items-center text-black text-sm font-medium border-b border-black">
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
    <section class="py-32 lg:py-40 bg-black border-t border-gray-900">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl sm:text-5xl lg:text-6xl font-light text-white mb-12 tracking-tight">
                Comienza tu viaje hacia el IKIGAI
            </h2>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-20">
                <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center bg-white text-black hover:bg-gray-100 px-10 py-4 text-base font-medium transition-colors duration-200">
                    Explorar productos
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
                
                <a href="{{ route('services.index') }}" class="inline-flex items-center justify-center bg-black text-white hover:bg-gray-900 px-10 py-4 text-base font-medium border border-white transition-colors duration-200">
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
                        <li><a href="#recursos" class="hover:text-white transition-colors duration-200">Recursos</a></li>
                    </ul>
                </div>

                <!-- Legal -->
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

    </body>
</html>
