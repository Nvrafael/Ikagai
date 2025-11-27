<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $resource->title }} - IKIGAI</title>
    <meta name="description" content="{{ $resource->excerpt ?? Str::limit(strip_tags($resource->content), 160) }}">

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-white font-sans">
    
    <!-- Header -->
    <header class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex-shrink-0">
                    <a href="/" class="text-xl font-light tracking-tight text-black">IKIGAI</a>
                </div>
                <nav class="hidden md:flex space-x-12">
                    <a href="{{ route('products.index') }}" class="text-sm text-gray-600 hover:text-black transition-colors duration-200">Productos</a>
                    <a href="/#nutricionistas" class="text-sm text-gray-600 hover:text-black transition-colors duration-200">Nutricionistas</a>
                    <a href="{{ route('resources.index') }}" class="text-sm text-black font-medium transition-colors duration-200">Recursos</a>
                    <a href="{{ route('services.index') }}" class="text-sm text-gray-600 hover:text-black transition-colors duration-200">Servicios</a>
                </nav>
                <div class="flex items-center space-x-6">
                    <a href="{{ route('cart.index') }}" class="relative text-gray-600 hover:text-black transition-colors duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span id="cart-badge" class="hidden absolute -top-2 -right-2 bg-black text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-medium">0</span>
                    </a>
                    @auth
                        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'nutritionist')
                            <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-black transition-colors duration-200">Dashboard</a>
                        @else
                            <a href="{{ route('profile.edit') }}" class="text-sm text-gray-600 hover:text-black transition-colors duration-200">Mi Perfil</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-gray-600 hover:text-black transition-colors duration-200">Cerrar sesión</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-black transition-colors duration-200">Iniciar sesión</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="bg-black text-white hover:bg-gray-900 px-6 py-2 text-sm font-medium transition-colors duration-200">Registrarse</a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Breadcrumb -->
    <div class="bg-beige border-b border-gray-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex items-center gap-2 text-sm text-gray-600">
                <a href="{{ route('resources.index') }}" class="hover:text-black transition-colors">Recursos</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <a href="{{ route('resources.index', ['type' => $resource->type]) }}" class="hover:text-black transition-colors capitalize">{{ $resource->type }}s</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-black">{{ Str::limit($resource->title, 50) }}</span>
            </nav>
        </div>
    </div>

    <!-- Contenido Principal -->
    <article class="py-12 lg:py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header del artículo -->
            <header class="mb-8">
                <div class="mb-4">
                    <span class="inline-block px-4 py-1 text-xs font-medium uppercase tracking-wider
                        @if($resource->type === 'receta') bg-green-500 text-white
                        @elseif($resource->type === 'consejo') bg-blue-500 text-white
                        @elseif($resource->type === 'articulo') bg-purple-500 text-white
                        @else bg-orange-500 text-white
                        @endif">
                        {{ $resource->type }}
                    </span>
                </div>

                <h1 class="text-4xl sm:text-5xl font-light text-black mb-6 tracking-tight">
                    {{ $resource->title }}
                </h1>

                @if($resource->excerpt)
                    <p class="text-xl text-gray-600 font-light mb-8 leading-relaxed">
                        {{ $resource->excerpt }}
                    </p>
                @endif

                <div class="flex flex-wrap items-center gap-6 text-sm text-gray-600 pb-8 border-b border-gray-200">
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 bg-sage text-white flex items-center justify-center rounded-full">
                            {{ strtoupper(substr($resource->author->name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="font-medium text-black">{{ $resource->author->name }}</div>
                            <div class="text-xs text-gray-500">{{ $resource->published_at->format('d M, Y') }}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ $resource->reading_time }} min de lectura</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <span>{{ $resource->views }} vistas</span>
                    </div>
                </div>
            </header>

            <!-- Imagen destacada -->
            @if($resource->featured_image)
                <div class="mb-12">
                    <img src="{{ asset('storage/' . $resource->featured_image) }}" 
                         alt="{{ $resource->title }}" 
                         class="w-full h-auto rounded-lg shadow-lg">
                </div>
            @endif

            <!-- Metadatos para RECETAS -->
            @if($resource->type === 'receta' && $resource->metadata)
                <div class="mb-12 bg-beige border border-gray-200 p-8">
                    <h2 class="text-2xl font-normal text-black mb-6">Información de la receta</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        @if(isset($resource->metadata['tiempo_preparacion']))
                            <div>
                                <div class="text-sage mb-2">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="text-sm text-gray-600 mb-1">Tiempo</div>
                                <div class="font-medium text-black">{{ $resource->metadata['tiempo_preparacion'] }}</div>
                            </div>
                        @endif
                        @if(isset($resource->metadata['porciones']))
                            <div>
                                <div class="text-sage mb-2">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <div class="text-sm text-gray-600 mb-1">Porciones</div>
                                <div class="font-medium text-black">{{ $resource->metadata['porciones'] }}</div>
                            </div>
                        @endif
                        @if(isset($resource->metadata['dificultad']))
                            <div>
                                <div class="text-sage mb-2">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <div class="text-sm text-gray-600 mb-1">Dificultad</div>
                                <div class="font-medium text-black capitalize">{{ $resource->metadata['dificultad'] }}</div>
                            </div>
                        @endif
                        @if(isset($resource->metadata['calorias']))
                            <div>
                                <div class="text-sage mb-2">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path>
                                    </svg>
                                </div>
                                <div class="text-sm text-gray-600 mb-1">Calorías</div>
                                <div class="font-medium text-black">{{ $resource->metadata['calorias'] }}</div>
                            </div>
                        @endif
                    </div>

                    @if(isset($resource->metadata['ingredientes']))
                        <div class="mt-8 pt-8 border-t border-gray-300">
                            <h3 class="text-xl font-normal text-black mb-4">Ingredientes</h3>
                            <div class="prose prose-sm max-w-none text-gray-700">
                                {!! nl2br(e($resource->metadata['ingredientes'])) !!}
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Contenido principal -->
            <div class="prose prose-lg max-w-none mb-12">
                {!! $resource->content !!}
            </div>

            <!-- Botón de descarga para guías -->
            @if($resource->type === 'guia' && $resource->download_file)
                <div class="mb-12 bg-sage-light border-2 border-sage p-8 text-center">
                    <h3 class="text-xl font-normal text-black mb-4">Descarga esta guía</h3>
                    <p class="text-gray-600 mb-6">Obtén el archivo completo en formato PDF</p>
                    <a href="{{ route('resources.download', $resource->slug) }}" 
                       class="inline-flex items-center bg-sage text-white px-8 py-3 hover:bg-sage-dark transition-colors shadow-md">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Descargar guía
                    </a>
                </div>
            @endif

            <!-- Recursos relacionados -->
            @if($relatedResources->count() > 0)
                <div class="mt-16 pt-16 border-t border-gray-200">
                    <h2 class="text-3xl font-light text-black mb-8">Más {{ $resource->type }}s que te pueden interesar</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($relatedResources as $related)
                            <article class="group border border-gray-200 hover:border-black transition-colors">
                                <a href="{{ route('resources.show', $related->slug) }}" class="block">
                                    <div class="relative h-48 bg-gray-100 overflow-hidden">
                                        @if($related->featured_image)
                                            <img src="{{ asset('storage/' . $related->featured_image) }}" 
                                                 alt="{{ $related->title }}" 
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-beige">
                                                <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="p-4">
                                        <h3 class="font-normal text-black mb-2 group-hover:text-sage transition-colors">
                                            {{ Str::limit($related->title, 60) }}
                                        </h3>
                                        <p class="text-sm text-gray-600">{{ $related->reading_time }} min de lectura</p>
                                    </div>
                                </a>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="mt-12 pt-12 border-t border-gray-200">
                <a href="{{ route('resources.index') }}" 
                   class="inline-flex items-center text-sage-dark hover:text-sage text-sm font-medium border-b border-sage-dark hover:border-sage transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Volver a recursos
                </a>
            </div>
        </div>
    </article>

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

