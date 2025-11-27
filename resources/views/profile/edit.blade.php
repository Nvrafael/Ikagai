<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mi Perfil - IKIGAI</title>

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

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
                    
                    <a href="{{ route('profile.edit') }}" class="text-sm text-black font-medium transition-colors duration-200">
                        Mi Perfil
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-gray-600 hover:text-black transition-colors duration-200">
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="bg-beige py-16 lg:py-20 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <h1 class="text-5xl sm:text-6xl font-light text-black mb-4 tracking-tight">
                    Mi Perfil
                </h1>
                <p class="text-lg text-gray-600 font-light">
                    Gestiona tu información personal y configuración de cuenta
                </p>
            </div>
        </div>
    </section>

    <!-- Contenido -->
    <div class="py-16 lg:py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Mensajes de éxito -->
            @if (session('status') === 'profile-updated')
                <div class="bg-green-50 border-l-4 border-green-500 p-6">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm font-medium text-green-900">¡Perfil actualizado exitosamente!</p>
                    </div>
                </div>
            @endif

            @if (session('status') === 'password-updated')
                <div class="bg-green-50 border-l-4 border-green-500 p-6">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm font-medium text-green-900">¡Contraseña actualizada exitosamente!</p>
                    </div>
                </div>
            @endif

            <!-- Información del Perfil -->
            <div class="bg-white border border-gray-200 p-8">
                <div class="mb-8">
                    <h2 class="text-2xl font-normal text-black mb-2">Información del Perfil</h2>
                    <p class="text-sm text-gray-600">Actualiza tu información personal y dirección de correo electrónico.</p>
                </div>

                <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                    @csrf
                    @method('patch')

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre *
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name', $user->name) }}"
                            required
                            autofocus
                            class="w-full px-4 py-3 border border-gray-300 focus:border-sage focus:ring-2 focus:ring-sage focus:ring-opacity-20 transition-colors duration-200"
                        >
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email *
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email', $user->email) }}"
                            required
                            class="w-full px-4 py-3 border border-gray-300 focus:border-sage focus:ring-2 focus:ring-sage focus:ring-opacity-20 transition-colors duration-200"
                        >
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200">
                                <p class="text-sm text-gray-700 mb-2">
                                    Tu dirección de correo electrónico no está verificada.
                                </p>
                                <form method="post" action="{{ route('verification.send') }}">
                                    @csrf
                                    <button type="submit" class="text-sm text-sage-dark hover:text-sage underline">
                                        Haz clic aquí para reenviar el correo de verificación.
                                    </button>
                                </form>

                                @if (session('status') === 'verification-link-sent')
                                    <p class="mt-2 text-sm font-medium text-green-600">
                                        Se ha enviado un nuevo enlace de verificación a tu correo.
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center gap-4 pt-4">
                        <button 
                            type="submit"
                            class="bg-sage text-white px-8 py-3 text-sm font-medium hover:bg-sage-dark transition-colors duration-200 shadow-md hover:shadow-lg"
                        >
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>

            <!-- Actualizar Contraseña -->
            <div class="bg-white border border-gray-200 p-8">
                <div class="mb-8">
                    <h2 class="text-2xl font-normal text-black mb-2">Actualizar Contraseña</h2>
                    <p class="text-sm text-gray-600">Asegúrate de usar una contraseña larga y aleatoria para mantener tu cuenta segura.</p>
                </div>

                <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                    @csrf
                    @method('put')

                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Contraseña Actual *
                        </label>
                        <input 
                            type="password" 
                            id="current_password" 
                            name="current_password"
                            autocomplete="current-password"
                            class="w-full px-4 py-3 border border-gray-300 focus:border-sage focus:ring-2 focus:ring-sage focus:ring-opacity-20 transition-colors duration-200"
                        >
                        @error('current_password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Nueva Contraseña *
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password"
                            autocomplete="new-password"
                            class="w-full px-4 py-3 border border-gray-300 focus:border-sage focus:ring-2 focus:ring-sage focus:ring-opacity-20 transition-colors duration-200"
                        >
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirmar Contraseña *
                        </label>
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation"
                            autocomplete="new-password"
                            class="w-full px-4 py-3 border border-gray-300 focus:border-sage focus:ring-2 focus:ring-sage focus:ring-opacity-20 transition-colors duration-200"
                        >
                        @error('password_confirmation')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-4 pt-4">
                        <button 
                            type="submit"
                            class="bg-sage text-white px-8 py-3 text-sm font-medium hover:bg-sage-dark transition-colors duration-200 shadow-md hover:shadow-lg"
                        >
                            Actualizar Contraseña
                        </button>
                    </div>
                </form>
            </div>

            <!-- Eliminar Cuenta -->
            <div class="bg-white border border-red-200 p-8">
                <div class="mb-8">
                    <h2 class="text-2xl font-normal text-red-600 mb-2">Eliminar Cuenta</h2>
                    <p class="text-sm text-gray-600">Una vez eliminada tu cuenta, todos tus datos serán permanentemente borrados. Antes de eliminar tu cuenta, por favor descarga cualquier dato que desees conservar.</p>
                </div>

                <button 
                    type="button"
                    onclick="if(confirm('¿Estás seguro de que deseas eliminar tu cuenta? Esta acción no se puede deshacer.')) { document.getElementById('delete-account-form').submit(); }"
                    class="bg-red-600 text-white px-8 py-3 text-sm font-medium hover:bg-red-700 transition-colors duration-200"
                >
                    Eliminar Cuenta
                </button>

                <form id="delete-account-form" method="post" action="{{ route('profile.destroy') }}" class="hidden">
                    @csrf
                    @method('delete')
                </form>
            </div>

        </div>
    </div>

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

    <!-- Cookie Banner -->
    @include('components.cookie-banner')

</body>
</html>
