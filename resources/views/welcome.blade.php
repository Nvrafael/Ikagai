<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>IKIGAI - Encuentra tu equilibrio</title>

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="antialiased bg-gray-50">
    
    <!-- Header -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="/" class="flex items-center">
                        <span class="text-2xl font-bold text-purple-600">IKIGAI</span>
                    </a>
                </div>

                <!-- Navigation -->
                <nav class="hidden md:flex space-x-8">
                    <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-purple-600 px-3 py-2 text-sm font-medium">
                        Tienda
                    </a>
                    <a href="#nutricionistas" class="text-gray-700 hover:text-purple-600 px-3 py-2 text-sm font-medium">
                        Nutricionistas
                    </a>
                    <a href="#recursos" class="text-gray-700 hover:text-purple-600 px-3 py-2 text-sm font-medium">
                        Recursos
                    </a>
                    <a href="{{ route('services.index') }}" class="text-gray-700 hover:text-purple-600 px-3 py-2 text-sm font-medium">
                        Comunidad
                    </a>
                </nav>

                <!-- Auth Buttons -->
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-gray-700 hover:text-purple-600 px-4 py-2 text-sm font-medium">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-purple-600 px-4 py-2 text-sm font-medium">
                            Iniciar sesión
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="bg-purple-600 text-white hover:bg-purple-700 px-6 py-2 rounded-full text-sm font-medium transition">
                                Crear cuenta
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-purple-50 via-pink-50 to-blue-50 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mb-6">
                    Encuentra tu <span class="text-purple-600">IKIGAI</span>
                </h1>
                <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                    Descubre el equilibrio perfecto entre tu salud y bienestar con nuestros suplementos naturales y orientación experta
                    <br>+800-808-815 | info@ikigai.com | Lun-Vie 9am-6pm
                </p>
                <a href="{{ route('products.index') }}" class="inline-block bg-purple-600 text-white hover:bg-purple-700 px-8 py-3 rounded-full text-lg font-medium transition">
                    Explorar productos
                </a>
            </div>
        </div>
    </section>

    <!-- Productos Populares -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">
                Nuestros productos más populares
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($featuredProducts as $product)
                    <div class="bg-orange-100 rounded-2xl overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <div class="p-8">
                            <div class="h-64 flex items-center justify-center mb-6">
                                @if($product->images && count($product->images) > 0)
                                    <img src="{{ $product->images[0] }}" alt="{{ $product->name }}" class="h-full object-contain">
                                @else
                                    <div class="w-32 h-32 bg-orange-200 rounded-full"></div>
                                @endif
                            </div>
                            <div class="text-center">
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $product->name }}</h3>
                                <p class="text-gray-600 mb-4">{{ Str::limit($product->description, 100) }}</p>
                                <a href="{{ route('products.show', $product->slug) }}" class="inline-block text-purple-600 hover:text-purple-700 font-medium">
                                    Ver detalles →
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12">
                        <p class="text-gray-500">No hay productos destacados disponibles en este momento.</p>
                    </div>
                @endforelse
            </div>

            @if($featuredProducts->count() > 0)
                <div class="text-center mt-12">
                    <a href="{{ route('products.index') }}" class="inline-block bg-purple-600 text-white hover:bg-purple-700 px-8 py-3 rounded-full text-lg font-medium transition">
                        Ver todos los productos
                    </a>
                </div>
            @endif
        </div>
    </section>

    <!-- Nutricionistas -->
    <section id="nutricionistas" class="py-16 bg-purple-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">
                Conoce a nuestros nutricionistas
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($nutritionists as $nutritionist)
                    <div class="bg-white rounded-2xl p-8 text-center hover:shadow-xl transition-shadow duration-300">
                        <div class="w-32 h-32 bg-gradient-to-br from-purple-400 to-pink-400 rounded-full mx-auto mb-6 flex items-center justify-center">
                            <span class="text-white text-3xl font-bold">{{ $nutritionist->initials() }}</span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $nutritionist->name }}</h3>
                        <p class="text-purple-600 font-medium mb-4">Nutricionista certificado</p>
                        <p class="text-gray-600 mb-6">Experto en nutrición holística y bienestar integral</p>
                        @auth
                            <a href="{{ route('messages.new-conversation') }}" class="inline-block text-purple-600 hover:text-purple-700 font-medium">
                                Contactar →
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="inline-block text-purple-600 hover:text-purple-700 font-medium">
                                Inicia sesión para contactar →
                            </a>
                        @endauth
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12">
                        <p class="text-gray-500">Próximamente tendremos nutricionistas disponibles.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Recursos -->
    <section id="recursos" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">
                Recursos para tu bienestar
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Guía de suplementos -->
                <div class="group rounded-2xl overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="h-64 bg-gradient-to-br from-orange-100 to-orange-200 flex items-center justify-center">
                        <svg class="w-24 h-24 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <div class="p-6 bg-white">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Guía de suplementos</h3>
                        <p class="text-gray-600 mb-4">Encuentra el mejor suplemento para tus necesidades</p>
                        <a href="{{ route('products.index') }}" class="text-purple-600 hover:text-purple-700 font-medium">
                            Explorar guía →
                        </a>
                    </div>
                </div>

                <!-- Recetas saludables -->
                <div class="group rounded-2xl overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="h-64 bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center">
                        <svg class="w-24 h-24 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
                        </svg>
                    </div>
                    <div class="p-6 bg-white">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Recetas saludables</h3>
                        <p class="text-gray-600 mb-4">Ideas deliciosas para una vida sana</p>
                        <a href="#" class="text-purple-600 hover:text-purple-700 font-medium">
                            Ver recetas →
                        </a>
                    </div>
                </div>

                <!-- Consejos de bienestar -->
                <div class="group rounded-2xl overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="h-64 bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center">
                        <svg class="w-24 h-24 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <div class="p-6 bg-white">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Consejos de bienestar</h3>
                        <p class="text-gray-600 mb-4">Tips para mejorar tu salud día a día</p>
                        <a href="#" class="text-purple-600 hover:text-purple-700 font-medium">
                            Leer consejos →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Final -->
    <section class="py-20 bg-gradient-to-br from-purple-600 to-pink-600">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold text-white mb-6">
                Comienza tu viaje hacia el IKIGAI
            </h2>
            <p class="text-xl text-purple-100 mb-8">
                Únete a nuestra comunidad y descubre el equilibrio perfecto para tu vida
            </p>
            <a href="{{ route('products.index') }}" class="inline-block bg-white text-purple-600 hover:bg-gray-100 px-8 py-3 rounded-full text-lg font-medium transition">
                Explorar productos
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Logo y descripción -->
                <div class="col-span-1">
                    <h3 class="text-2xl font-bold text-white mb-4">IKIGAI</h3>
                    <p class="text-sm">
                        Tu camino hacia el equilibrio y el bienestar integral.
                    </p>
                </div>

                <!-- Enlaces rápidos -->
                <div>
                    <h4 class="text-white font-semibold mb-4">Tienda</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('products.index') }}" class="hover:text-white">Todos los productos</a></li>
                        <li><a href="{{ route('categories.index') }}" class="hover:text-white">Categorías</a></li>
                        <li><a href="#" class="hover:text-white">Ofertas</a></li>
                    </ul>
                </div>

                <!-- Servicios -->
                <div>
                    <h4 class="text-white font-semibold mb-4">Servicios</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('services.index') }}" class="hover:text-white">Consultas</a></li>
                        <li><a href="#nutricionistas" class="hover:text-white">Nutricionistas</a></li>
                        <li><a href="#recursos" class="hover:text-white">Recursos</a></li>
                    </ul>
                </div>

                <!-- Legal -->
                <div>
                    <h4 class="text-white font-semibold mb-4">Legal</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white">Términos de uso</a></li>
                        <li><a href="#" class="hover:text-white">Política de privacidad</a></li>
                        <li><a href="#" class="hover:text-white">Contacto</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8 text-sm text-center">
                <p>&copy; {{ date('Y') }} IKIGAI. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

</body>
</html>
