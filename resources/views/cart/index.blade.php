<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Carrito de Compras - IKIGAI</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-beige font-sans">
    
    <!-- Header -->
    <header class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex-shrink-0">
                    <a href="/" class="text-xl font-light tracking-tight text-black">
                        IKIGAI
                    </a>
                </div>

                <nav class="hidden md:flex space-x-12">
                    <a href="{{ route('products.index') }}" class="text-sm text-gray-600 hover:text-black transition-colors duration-200">
                        Productos
                    </a>
                    <a href="{{ route('services.index') }}" class="text-sm text-gray-600 hover:text-black transition-colors duration-200">
                        Servicios
                    </a>
                </nav>

                <div class="flex items-center space-x-6">
                    <a href="{{ route('cart.index') }}" class="relative text-black">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span id="cart-badge" class="absolute -top-2 -right-2 bg-sage text-white text-xs w-5 h-5 rounded-full flex items-center justify-center">
                            {{ count($cartItems) }}
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Mensaje de éxito -->
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
                </div>
            </div>
        </div>
    @endif

    <!-- Contenido Principal -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-4xl font-light text-black mb-2">Carrito de Compras</h1>
        <p class="text-gray-500 mb-12">Revisa tus productos seleccionados</p>

        @if(count($cartItems) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Lista de Productos -->
                <div class="lg:col-span-2 space-y-4">
                    @foreach($cartItems as $item)
                        <div class="bg-white border border-gray-200 p-6 flex items-center gap-6" data-product-id="{{ $item['product']->id }}">
                            <!-- Imagen -->
                            <div class="w-24 h-24 flex-shrink-0 bg-gray-50 flex items-center justify-center">
                                @if($item['product']->images && count($item['product']->images) > 0)
                                    <img src="{{ $item['product']->images[0] }}" alt="{{ $item['product']->name }}" class="w-full h-full object-contain">
                                @else
                                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                @endif
                            </div>

                            <!-- Info del Producto -->
                            <div class="flex-1">
                                <h3 class="text-lg font-medium text-black mb-1">
                                    <a href="{{ route('products.show', $item['product']->slug) }}" class="hover:text-gray-600">
                                        {{ $item['product']->name }}
                                    </a>
                                </h3>
                                @if($item['product']->category)
                                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">
                                        {{ $item['product']->category->name }}
                                    </p>
                                @endif
                                <p class="text-sm text-gray-600">
                                    ${{ number_format($item['product']->price, 2) }} c/u
                                </p>
                            </div>

                            <!-- Cantidad -->
                            <div class="flex items-center gap-3">
                                <button 
                                    onclick="updateQuantity({{ $item['product']->id }}, {{ $item['quantity'] - 1 }})"
                                    class="w-8 h-8 border border-sage hover:border-sage-dark flex items-center justify-center transition-colors"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                    </svg>
                                </button>
                                
                                <input 
                                    type="number" 
                                    value="{{ $item['quantity'] }}"
                                    min="1"
                                    max="{{ $item['product']->stock }}"
                                    class="w-16 text-center border border-sage py-1 focus:border-sage-dark focus:ring-2 focus:ring-sage focus:ring-opacity-20"
                                    onchange="updateQuantity({{ $item['product']->id }}, this.value)"
                                >
                                
                                <button 
                                    onclick="updateQuantity({{ $item['product']->id }}, {{ $item['quantity'] + 1 }})"
                                    class="w-8 h-8 border border-sage hover:border-sage-dark flex items-center justify-center transition-colors"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </button>
                            </div>

                            <!-- Subtotal y Eliminar -->
                            <div class="text-right">
                                <p class="text-lg font-medium text-black mb-2">
                                    $<span class="item-subtotal">{{ number_format($item['subtotal'], 2) }}</span>
                                </p>
                                <button 
                                    onclick="removeFromCart({{ $item['product']->id }})"
                                    class="text-sm text-red-600 hover:text-red-800"
                                >
                                    Eliminar
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Resumen del Pedido -->
                <div class="lg:col-span-1">
                    <div class="bg-white border border-gray-200 p-6 sticky top-24">
                        <h2 class="text-xl font-medium text-black mb-6">Resumen del Pedido</h2>
                        
                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal</span>
                                <span>$<span id="cart-subtotal">{{ number_format($subtotal, 2) }}</span></span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>IVA (16%)</span>
                                <span>$<span id="cart-tax">{{ number_format($tax, 2) }}</span></span>
                            </div>
                            <div class="border-t border-gray-200 pt-4">
                                <div class="flex justify-between text-xl font-medium text-black">
                                    <span>Total</span>
                                    <span>$<span id="cart-total">{{ number_format($total, 2) }}</span></span>
                                </div>
                            </div>
                        </div>

                        <button class="w-full bg-sage text-white py-4 hover:bg-sage-dark transition-colors mb-3 shadow-md hover:shadow-lg">
                            Proceder al Pago
                        </button>
                        
                        <a href="{{ route('products.index') }}" class="block text-center text-sm text-sage-dark hover:text-sage">
                            Continuar comprando
                        </a>

                        <form action="{{ route('cart.clear') }}" method="POST" class="mt-6">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full text-sm text-red-600 hover:text-red-800 py-2">
                                Vaciar carrito
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <!-- Carrito Vacío -->
            <div class="text-center py-24 bg-white border border-gray-200">
                <svg class="w-24 h-24 text-gray-300 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h3 class="text-2xl font-light text-black mb-2">Tu carrito está vacío</h3>
                <p class="text-gray-600 mb-8">Agrega productos para comenzar tu compra</p>
                <a href="{{ route('products.index') }}" class="inline-flex items-center bg-sage text-white px-8 py-3 hover:bg-sage-dark transition-colors shadow-md hover:shadow-lg">
                    Ver Productos
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
        @endif
    </div>

    <script>
        // Actualizar cantidad
        async function updateQuantity(productId, quantity) {
            if (quantity < 1) {
                if (confirm('¿Eliminar este producto del carrito?')) {
                    removeFromCart(productId);
                }
                return;
            }

            try {
                const response = await fetch(`/carrito/${productId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ quantity: parseInt(quantity) })
                });

                const data = await response.json();

                if (data.success) {
                    showToast('Carrito actualizado', 'success');
                    // Recargar la página para mostrar los cambios
                    setTimeout(() => window.location.reload(), 500);
                } else {
                    showToast(data.message || 'Error al actualizar', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Error al actualizar el carrito', 'error');
            }
        }

        // Eliminar del carrito
        async function removeFromCart(productId) {
            try {
                const response = await fetch(`/carrito/${productId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();

                if (data.success) {
                    showToast('Producto eliminado', 'success');
                    // Recargar la página para mostrar los cambios
                    setTimeout(() => window.location.reload(), 500);
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Error al eliminar producto', 'error');
            }
        }

        // Actualizar UI del carrito
        function updateCartUI(cartData) {
            // Actualizar badge
            const badge = document.getElementById('cart-badge');
            if (badge) {
                badge.textContent = cartData.count;
            }

            // Actualizar totales
            document.getElementById('cart-subtotal').textContent = cartData.subtotal.toFixed(2);
            document.getElementById('cart-tax').textContent = cartData.tax.toFixed(2);
            document.getElementById('cart-total').textContent = cartData.total.toFixed(2);

            // Actualizar subtotales individuales
            cartData.items.forEach(item => {
                const element = document.querySelector(`[data-product-id="${item.id}"] .item-subtotal`);
                if (element) {
                    element.textContent = item.subtotal.toFixed(2);
                }
                
                const input = document.querySelector(`[data-product-id="${item.id}"] input[type="number"]`);
                if (input) {
                    input.value = item.quantity;
                }
            });
        }

        // Mostrar notificación
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 shadow-lg ${
                type === 'success' ? 'bg-green-600' : 'bg-red-600'
            }`;
            toast.textContent = message;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.style.transition = 'opacity 0.3s';
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
    </script>
</body>
</html>

