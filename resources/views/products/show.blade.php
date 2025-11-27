<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $product->name }} - IKIGAI</title>
    <meta name="description" content="{{ Str::limit($product->description, 160) }}">

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-beige font-sans">
    
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
                    <a href="/#recursos" class="text-sm text-gray-600 hover:text-black transition-colors duration-200">
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
                        <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-black transition-colors duration-200">
                            Dashboard
                        </a>
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

    <!-- Breadcrumb -->
    <section class="bg-white py-6 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-2 text-sm">
                <a href="/" class="text-sage-dark hover:text-sage transition-colors duration-200">Inicio</a>
                <span class="text-gray-300">/</span>
                <a href="{{ route('products.index') }}" class="text-sage-dark hover:text-sage transition-colors duration-200">Productos</a>
                @if($product->category)
                    <span class="text-gray-300">/</span>
                    <a href="{{ route('products.index', ['category' => $product->category->id]) }}" class="text-sage-dark hover:text-sage transition-colors duration-200">
                        {{ $product->category->name }}
                    </a>
                @endif
                <span class="text-gray-300">/</span>
                <span class="text-black">{{ $product->name }}</span>
            </div>
        </div>
    </section>

    <!-- Producto Principal -->
    <section class="py-16 lg:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                
                <!-- Galería de Imágenes -->
                <div>
                    <div class="sticky top-24">
                        @if($product->images && count($product->images) > 0)
                            <!-- Imagen principal -->
                            <div class="bg-gray-50 border border-gray-200 p-12 mb-4" id="main-image">
                                <img src="{{ asset('storage/' . $product->images[0]) }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-auto object-contain max-h-[500px]">
                            </div>
                            
                            <!-- Miniaturas -->
                            @if(count($product->images) > 1)
                                <div class="grid grid-cols-4 gap-2">
                                    @foreach($product->images as $index => $image)
                                        <button 
                                            onclick="changeMainImage('{{ asset('storage/' . $image) }}')"
                                            class="bg-gray-50 border border-gray-200 p-4 hover:border-sage transition-colors duration-200 {{ $index === 0 ? 'border-sage' : '' }}"
                                        >
                                            <img src="{{ asset('storage/' . $image) }}" 
                                                 alt="{{ $product->name }} - {{ $index + 1 }}" 
                                                 class="w-full h-auto object-contain">
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        @else
                            <div class="bg-gray-50 border border-gray-200 p-24 flex items-center justify-center">
                                <div class="w-32 h-32 border-2 border-gray-200 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Información del Producto -->
                <div>
                    <!-- Categoría -->
                    @if($product->category)
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-4">
                            {{ $product->category->name }}
                        </p>
                    @endif

                    <!-- Nombre -->
                    <h1 class="text-4xl sm:text-5xl font-light text-black mb-6 tracking-tight">
                        {{ $product->name }}
                    </h1>

                    <!-- Precio -->
                    <div class="mb-8">
                        <div class="flex items-baseline gap-3 mb-2">
                            <span class="text-3xl font-normal text-black">
                                ${{ number_format($product->price, 2) }}
                            </span>
                            @if($product->compare_price && $product->compare_price > $product->price)
                                <span class="text-xl text-gray-400 line-through">
                                    ${{ number_format($product->compare_price, 2) }}
                                </span>
                                <span class="text-sm text-red-600 font-medium">
                                    Ahorra ${{ number_format($product->compare_price - $product->price, 2) }}
                                </span>
                            @endif
                        </div>
                        
                        <!-- Stock -->
                        @if($product->stock > 0)
                            @if($product->stock <= 5)
                                <p class="text-sm text-red-600">
                                    Solo quedan {{ $product->stock }} unidades
                                </p>
                            @else
                                <p class="text-sm text-green-600">
                                    Disponible en stock
                                </p>
                            @endif
                        @else
                            <p class="text-sm text-gray-400">
                                Agotado temporalmente
                            </p>
                        @endif
                    </div>

                    <!-- Descripción -->
                    <div class="prose prose-sm max-w-none mb-8 pb-8 border-b border-gray-100">
                        <p class="text-base text-gray-600 font-light leading-relaxed">
                            {{ $product->description }}
                        </p>
                    </div>

                    <!-- Información adicional -->
                    @if($product->weight || $product->sku)
                        <div class="mb-8 pb-8 border-b border-gray-100">
                            <dl class="space-y-2">
                                @if($product->weight)
                                    <div class="flex text-sm">
                                        <dt class="text-gray-500 w-24">Peso:</dt>
                                        <dd class="text-black">{{ $product->weight }}</dd>
                                    </div>
                                @endif
                                @if($product->sku)
                                    <div class="flex text-sm">
                                        <dt class="text-gray-500 w-24">SKU:</dt>
                                        <dd class="text-black font-mono">{{ $product->sku }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    @endif

                    <!-- Selector de cantidad -->
                    @if($product->stock > 0)
                        <div class="mb-6">
                            <label class="block text-sm text-gray-600 mb-2">Cantidad</label>
                            <div class="flex items-center gap-3 w-32">
                                <button 
                                    onclick="decrementQuantity()"
                                    class="w-10 h-10 border border-sage hover:border-sage-dark flex items-center justify-center transition-colors"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                    </svg>
                                </button>
                                
                                <input 
                                    type="number" 
                                    id="product-quantity"
                                    value="1"
                                    min="1"
                                    max="{{ $product->stock }}"
                                    class="w-full text-center border border-sage py-2 focus:border-sage-dark focus:ring-2 focus:ring-sage focus:ring-opacity-20"
                                >
                                
                                <button 
                                    onclick="incrementQuantity()"
                                    class="w-10 h-10 border border-sage hover:border-sage-dark flex items-center justify-center transition-colors"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Botón de compra -->
                    <div class="space-y-4 mb-8">
                        @if($product->stock > 0)
                            <button 
                                data-add-to-cart
                                data-product-id="{{ $product->id }}"
                                class="w-full bg-sage text-white hover:bg-sage-dark px-10 py-4 text-base font-medium transition-colors duration-200 flex items-center justify-center shadow-md hover:shadow-lg"
                            >
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Agregar al carrito
                            </button>
                        @else
                            <button 
                                disabled
                                class="w-full bg-gray-200 text-gray-500 px-10 py-4 text-base font-medium cursor-not-allowed"
                            >
                                Agotado
                            </button>
                        @endif

                        <a 
                            href="{{ route('products.index') }}" 
                            class="block w-full text-center bg-white text-sage-dark hover:bg-sage-light px-10 py-4 text-base font-medium border border-sage transition-colors duration-200"
                        >
                            Continuar comprando
                        </a>
                    </div>

                    <!-- Características -->
                    <div class="bg-sage-light border border-sage p-8">
                        <h3 class="text-sm uppercase tracking-wider text-sage-dark mb-4 font-medium">
                            Características
                        </h3>
                        <ul class="space-y-3 text-sm text-gray-700">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-sage-dark mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Envío gratis en pedidos superiores a $50</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-sage-dark mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Garantía de devolución de 30 días</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-sage-dark mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Productos 100% naturales</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-sage-dark mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Soporte de nutricionistas certificados</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Detalles adicionales (acordeón) -->
    <section class="py-16 bg-white border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl">
                <!-- Beneficios -->
                @if($product->benefits)
                    <details class="group border-b border-gray-200 py-6">
                        <summary class="flex justify-between items-center cursor-pointer list-none">
                            <h3 class="text-lg font-normal text-black">Beneficios</h3>
                            <svg class="w-5 h-5 text-gray-500 group-open:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <div class="mt-4 prose prose-sm max-w-none text-gray-600">
                            {!! nl2br(e($product->benefits)) !!}
                        </div>
                    </details>
                @endif

                <!-- Ingredientes -->
                @if($product->ingredients)
                    <details class="group border-b border-gray-200 py-6">
                        <summary class="flex justify-between items-center cursor-pointer list-none">
                            <h3 class="text-lg font-normal text-black">Ingredientes</h3>
                            <svg class="w-5 h-5 text-gray-500 group-open:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <div class="mt-4 prose prose-sm max-w-none text-gray-600">
                            {!! nl2br(e($product->ingredients)) !!}
                        </div>
                    </details>
                @endif

                <!-- Modo de uso -->
                <details class="group border-b border-gray-200 py-6">
                    <summary class="flex justify-between items-center cursor-pointer list-none">
                        <h3 class="text-lg font-normal text-black">Modo de uso</h3>
                        <svg class="w-5 h-5 text-gray-500 group-open:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </summary>
                    <div class="mt-4 prose prose-sm max-w-none text-gray-600">
                        <p>Consulta con tu nutricionista para obtener recomendaciones personalizadas sobre el uso de este producto según tus necesidades específicas.</p>
                    </div>
                </details>
            </div>
        </div>
    </section>

    <!-- Productos Relacionados -->
    @if($relatedProducts->count() > 0)
        <section class="py-24 lg:py-32 bg-beige border-t border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-12">
                    <h2 class="text-3xl sm:text-4xl font-light text-black mb-3 tracking-tight">
                        Productos relacionados
                    </h2>
                    <p class="text-base text-gray-600 font-light">
                        También te podrían interesar
                    </p>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-1">
                    @foreach($relatedProducts as $relatedProduct)
                        <div class="group bg-white border border-gray-200 hover:border-black transition-colors duration-200">
                            <a href="{{ route('products.show', $relatedProduct->slug) }}" class="block p-8">
                                <!-- Imagen del producto -->
                                <div class="h-64 flex items-center justify-center mb-6 bg-gray-50">
                                    @if($relatedProduct->images && count($relatedProduct->images) > 0)
                                        <img src="{{ asset('storage/' . $relatedProduct->images[0]) }}" 
                                             alt="{{ $relatedProduct->name }}" 
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
                                    <h3 class="text-lg font-normal text-black mb-2">
                                        {{ $relatedProduct->name }}
                                    </h3>
                                    
                                    <!-- Precio -->
                                    <div class="flex items-baseline gap-2">
                                        <span class="text-base font-medium text-black">
                                            ${{ number_format($relatedProduct->price, 2) }}
                                        </span>
                                        @if($relatedProduct->compare_price && $relatedProduct->compare_price > $relatedProduct->price)
                                            <span class="text-sm text-gray-400 line-through">
                                                ${{ number_format($relatedProduct->compare_price, 2) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

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
                        <li><a href="/#recursos" class="hover:text-white transition-colors duration-200">Recursos</a></li>
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

    <script>
        const MAX_STOCK = {{ $product->stock }};

        // Cambiar imagen principal
        function changeMainImage(imageUrl) {
            const mainImage = document.querySelector('#main-image img');
            mainImage.src = imageUrl;
            
            // Actualizar borde de miniatura activa
            const thumbnails = document.querySelectorAll('#main-image').parentElement.querySelectorAll('button');
            thumbnails.forEach((thumb, index) => {
                thumb.classList.remove('border-black');
                thumb.classList.add('border-gray-200');
            });
            event.currentTarget.classList.remove('border-gray-200');
            event.currentTarget.classList.add('border-black');
        }

        // Gestión de cantidad
        function incrementQuantity() {
            const input = document.getElementById('product-quantity');
            if (parseInt(input.value) < MAX_STOCK) {
                input.value = parseInt(input.value) + 1;
            }
        }

        function decrementQuantity() {
            const input = document.getElementById('product-quantity');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
            }
        }

        // Cargar badge del carrito
        document.addEventListener('DOMContentLoaded', loadCartBadge);

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
            isProcessing = true;

            const productId = btn.dataset.productId;
            const quantity = parseInt(document.getElementById('product-quantity')?.value || 1);
            const originalContent = btn.innerHTML;

            try {
                btn.disabled = true;
                btn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

                const response = await fetch('/carrito/agregar', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        product_id: parseInt(productId),
                        quantity: quantity
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Actualizar badge
                    const badge = document.querySelector('#cart-badge');
                    if (badge && data.total_items) {
                        badge.textContent = data.total_items;
                        badge.classList.remove('hidden');
                    }

                    // Mostrar notificación
                    showToast(`¡${quantity} producto(s) agregado(s) al carrito!`, 'success');
                    
                    // Cambiar contenido temporalmente
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

</body>
</html>

