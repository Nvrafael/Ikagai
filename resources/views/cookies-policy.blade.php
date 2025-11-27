<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Política de Cookies - IKIGAI</title>

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
                    <a href="{{ route('resources.index') }}" class="text-sm text-gray-600 hover:text-black transition-colors duration-200">Recursos</a>
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

    <!-- Contenido Principal -->
    <div class="py-16 lg:py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-5xl font-light text-black mb-8 tracking-tight">Política de Cookies</h1>
            
            <div class="prose prose-lg max-w-none">
                <p class="text-gray-600 mb-6">
                    Última actualización: {{ date('d/m/Y') }}
                </p>

                <h2 class="text-2xl font-normal text-black mb-4 mt-8">¿Qué son las cookies?</h2>
                <p class="text-gray-700 leading-relaxed mb-6">
                    Las cookies son pequeños archivos de texto que se almacenan en tu dispositivo cuando visitas nuestro sitio web. 
                    Nos ayudan a mejorar tu experiencia de navegación y entender cómo utilizas nuestro sitio.
                </p>

                <h2 class="text-2xl font-normal text-black mb-4 mt-8">¿Qué cookies utilizamos?</h2>
                
                <h3 class="text-xl font-normal text-black mb-3 mt-6">Cookies esenciales</h3>
                <p class="text-gray-700 leading-relaxed mb-4">
                    Estas cookies son necesarias para el funcionamiento básico del sitio web, como la autenticación de usuarios 
                    y el carrito de compras. No pueden ser desactivadas.
                </p>

                <h3 class="text-xl font-normal text-black mb-3 mt-6">Cookies de preferencias</h3>
                <p class="text-gray-700 leading-relaxed mb-4">
                    Almacenan tus preferencias y configuraciones, como tu consentimiento de cookies.
                </p>

                <h3 class="text-xl font-normal text-black mb-3 mt-6">Cookies de análisis</h3>
                <p class="text-gray-700 leading-relaxed mb-4">
                    Nos ayudan a entender cómo los visitantes interactúan con nuestro sitio web, 
                    recopilando información de forma anónima sobre las páginas visitadas y los clics realizados.
                </p>

                <h2 class="text-2xl font-normal text-black mb-4 mt-8">¿Cómo gestionar las cookies?</h2>
                <p class="text-gray-700 leading-relaxed mb-6">
                    Puedes aceptar o rechazar las cookies opcionales cuando visitas nuestro sitio. 
                    También puedes cambiar la configuración de cookies en tu navegador en cualquier momento.
                </p>

                <div class="bg-beige border border-gray-200 p-6 mt-8">
                    <h3 class="text-lg font-medium text-black mb-3">Gestionar preferencias</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Tu preferencia actual: 
                        <span id="currentPreference" class="font-medium text-black"></span>
                    </p>
                    <div class="flex gap-3">
                        <button onclick="updateCookiePreference('accepted')" class="px-6 py-2 text-sm bg-sage text-white hover:bg-sage-dark transition-colors">
                            Aceptar Cookies
                        </button>
                        <button onclick="updateCookiePreference('rejected')" class="px-6 py-2 text-sm border border-gray-300 text-gray-700 hover:border-black hover:text-black transition-colors">
                            Rechazar Cookies
                        </button>
                    </div>
                </div>

                <h2 class="text-2xl font-normal text-black mb-4 mt-8">Contacto</h2>
                <p class="text-gray-700 leading-relaxed mb-6">
                    Si tienes alguna pregunta sobre nuestra política de cookies, puedes contactarnos en 
                    <a href="mailto:info@ikigai.com" class="text-sage-dark hover:text-sage underline">info@ikigai.com</a>
                </p>
            </div>

            <div class="mt-12 pt-8 border-t border-gray-200">
                <a href="/" class="inline-flex items-center text-sage-dark hover:text-sage text-sm font-medium border-b border-sage-dark hover:border-sage transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Volver al inicio
                </a>
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
        document.addEventListener('DOMContentLoaded', function() {
            loadCartBadge();
            displayCurrentPreference();
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

        async function displayCurrentPreference() {
            const element = document.getElementById('currentPreference');
            
            @auth
                // Usuario autenticado: obtener del servidor
                try {
                    const response = await fetch('/cookies/status');
                    const data = await response.json();
                    
                    if (data.consent === 'accepted') {
                        element.textContent = 'Aceptadas ✓';
                        element.className = 'font-medium text-green-600';
                    } else if (data.consent === 'rejected') {
                        element.textContent = 'Rechazadas';
                        element.className = 'font-medium text-red-600';
                    } else {
                        element.textContent = 'No configuradas';
                        element.className = 'font-medium text-gray-500';
                    }
                } catch (error) {
                    element.textContent = 'Error al cargar';
                    element.className = 'font-medium text-gray-500';
                }
            @else
                // Usuario no autenticado: obtener de localStorage
                const preference = localStorage.getItem('cookieConsent');
                
                if (preference === 'accepted') {
                    element.textContent = 'Aceptadas ✓';
                    element.className = 'font-medium text-green-600';
                } else if (preference === 'rejected') {
                    element.textContent = 'Rechazadas';
                    element.className = 'font-medium text-red-600';
                } else {
                    element.textContent = 'No configuradas';
                    element.className = 'font-medium text-gray-500';
                }
            @endauth
        }

        async function updateCookiePreference(status) {
            @auth
                // Usuario autenticado: guardar en el servidor
                try {
                    const response = await fetch('/cookies/consent', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        body: JSON.stringify({ consent: status })
                    });

                    const data = await response.json();
                    
                    if (data.success) {
                        await displayCurrentPreference();
                        const message = status === 'accepted' ? 'Cookies aceptadas' : 'Cookies rechazadas';
                        showToast(message, 'success');
                    }
                } catch (error) {
                    showToast('Error al guardar preferencia', 'error');
                }
            @else
                // Usuario no autenticado: guardar en localStorage
                localStorage.setItem('cookieConsent', status);
                localStorage.setItem('cookieConsentDate', new Date().toISOString());
                await displayCurrentPreference();
                
                const message = status === 'accepted' ? 'Cookies aceptadas' : 'Cookies rechazadas';
                showToast(message, 'success');
            @endauth
        }

        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 shadow-lg transition-all duration-300 ${
                type === 'success' ? 'bg-green-600' : 'bg-red-600'
            }`;
            toast.textContent = message;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
    </script>

</body>
</html>

