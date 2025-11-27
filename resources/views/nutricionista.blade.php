<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tu nutricionista de confianza - IKIGAI</title>
    <meta name="description" content="Conoce a nuestro especialista en nutrición deportiva y alimentación consciente. Reserva tu cita personalizada.">

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Colores personalizados para la página del nutricionista */
        .bg-sage { background-color: #9CAF88; }
        .bg-sage-light { background-color: #E8F5E9; }
        .bg-beige { background-color: #F5F1E8; }
        .text-sage { color: #7A9A65; }
        .border-sage { border-color: #9CAF88; }
        .hover\:bg-sage:hover { background-color: #9CAF88; }
        .hover\:border-sage:hover { border-color: #9CAF88; }
        
        /* Animación suave al cargar */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }
        
        .delay-1 { animation-delay: 0.2s; opacity: 0; }
        .delay-2 { animation-delay: 0.4s; opacity: 0; }
        .delay-3 { animation-delay: 0.6s; opacity: 0; }
    </style>
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
                    <a href="/#nutricionistas" class="text-sm text-black font-medium transition-colors duration-200">
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
    <section class="relative bg-beige py-20 lg:py-28 border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center fade-in-up">
                <div class="inline-block px-4 py-1 bg-sage-light border border-sage text-sage text-xs uppercase tracking-wider mb-6 rounded-full">
                    Nutrición Profesional
                </div>
                <h1 class="text-5xl sm:text-6xl lg:text-7xl font-light text-black mb-6 tracking-tight">
                    Tu nutricionista de confianza
                </h1>
                <p class="text-xl sm:text-2xl text-gray-600 max-w-2xl mx-auto font-light">
                    Conoce a nuestro especialista y reserva tu cita personalizada
                </p>
            </div>
        </div>
    </section>

    <!-- Información del Nutricionista -->
    <section id="nutricionista" class="py-20 lg:py-28 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
                
                <!-- Imagen y datos principales -->
                <div class="fade-in-up delay-1">
                    <!-- Foto profesional -->
                    <div class="mb-8 bg-sage-light p-8 border border-sage flex items-center justify-center">
                        <div class="relative w-full">
                            <!-- Imagen del nutricionista -->
                            <img src="{{ asset('images/nutritionist-photo.jpg') }}" 
                                 alt="Paco Villar Cantalejo - Nutricionista Certificado" 
                                 class="w-full h-auto object-cover rounded-sm">
                        </div>
                    </div>

                    <!-- Nombre y título -->
                    <div class="mb-8">
                        <h2 class="text-3xl font-normal text-black mb-2">
                            Paco Villar Cantalejo
                        </h2>
                        <p class="text-sm text-sage uppercase tracking-wider font-medium">
                            Nutricionista Certificado
                        </p>
                    </div>

                    <!-- Datos profesionales -->
                    <div class="space-y-4 bg-beige p-8 border border-gray-200">
                        <div class="flex border-b border-gray-300 pb-3">
                            <div class="w-32 text-sm font-medium text-gray-600">Titulación:</div>
                            <div class="flex-1 text-sm text-gray-900">Licenciada en Nutrición y Dietética</div>
                        </div>
                        <div class="flex border-b border-gray-300 pb-3">
                            <div class="w-32 text-sm font-medium text-gray-600">Experiencia:</div>
                            <div class="flex-1 text-sm text-gray-900">+10 años en nutrición deportiva</div>
                        </div>
                        <div class="flex border-b border-gray-300 pb-3">
                            <div class="w-32 text-sm font-medium text-gray-600">Especialidades:</div>
                            <div class="flex-1 text-sm text-gray-900">Nutrición deportiva, Pérdida de peso, Alimentación consciente</div>
                        </div>
                        <div class="flex">
                            <div class="w-32 text-sm font-medium text-gray-600">Idiomas:</div>
                            <div class="flex-1 text-sm text-gray-900">Español, Inglés, Catalán</div>
                        </div>
                    </div>
                </div>

                <!-- Descripción y filosofía -->
                <div class="fade-in-up delay-2">
                    <div class="mb-12">
                        <h3 class="text-2xl font-normal text-black mb-6">
                            Sobre mí
                        </h3>
                        
                        <div class="space-y-4 text-gray-700 leading-relaxed">
                            <p>
                                Mi enfoque se centra en crear planes nutricionales personalizados que se adapten a tu estilo de vida, 
                                objetivos y necesidades específicas. Creo firmemente en la alimentación consciente como herramienta 
                                fundamental para lograr un bienestar integral.
                            </p>
                            
                            <p>
                                Con más de 10 años de experiencia ayudando a deportistas y personas que buscan mejorar su salud, 
                                he desarrollado un método que combina ciencia, práctica y un profundo respeto por la individualidad 
                                de cada persona. No existen dietas universales, solo caminos personalizados hacia tu mejor versión.
                            </p>
                            
                            <p>
                                Mi filosofía es simple: la nutrición debe ser sostenible, disfrutar de la comida es fundamental, 
                                y los cambios duraderos se logran con educación, paciencia y acompañamiento profesional continuo.
                            </p>
                        </div>
                    </div>

                    <!-- Áreas de especialidad destacadas -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-sage-light p-6 border border-sage">
                            <div class="text-sage mb-2">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <h4 class="text-sm font-medium text-black mb-1">Nutrición Deportiva</h4>
                            <p class="text-xs text-gray-600">Optimiza tu rendimiento</p>
                        </div>
                        
                        <div class="bg-sage-light p-6 border border-sage">
                            <div class="text-sage mb-2">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </div>
                            <h4 class="text-sm font-medium text-black mb-1">Bienestar Integral</h4>
                            <p class="text-xs text-gray-600">Equilibrio cuerpo-mente</p>
                        </div>
                        
                        <div class="bg-sage-light p-6 border border-sage">
                            <div class="text-sage mb-2">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <h4 class="text-sm font-medium text-black mb-1">Planes Personalizados</h4>
                            <p class="text-xs text-gray-600">Adaptados a ti</p>
                        </div>
                        
                        <div class="bg-sage-light p-6 border border-sage">
                            <div class="text-sage mb-2">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <h4 class="text-sm font-medium text-black mb-1">Educación Nutricional</h4>
                            <p class="text-xs text-gray-600">Aprende para siempre</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Formulario de Reserva de Cita -->
    <section class="py-20 lg:py-28 bg-beige border-t border-gray-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 fade-in-up delay-1">
                <h2 class="text-4xl sm:text-5xl font-light text-black mb-4 tracking-tight">
                    Reserva tu cita
                </h2>
                <p class="text-lg text-gray-600 font-light">
                    Comienza tu camino hacia una vida más saludable
                </p>
            </div>

            <div class="bg-white border border-gray-200 shadow-lg p-8 sm:p-12 fade-in-up delay-2">
                
                @if(session('success'))
                    <div class="mb-8 p-6 bg-green-50 border-l-4 border-sage text-gray-900">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-sage mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h4 class="font-medium mb-1">¡Reserva confirmada!</h4>
                                <p class="text-sm">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-8 p-6 bg-red-50 border-l-4 border-red-500 text-red-900">
                        <h4 class="font-medium mb-2">Por favor, corrige los siguientes errores:</h4>
                        <ul class="list-disc list-inside text-sm space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @auth
                    <form action="{{ route('bookings.store') }}" method="POST" id="bookingForm" class="space-y-6">
                        @csrf
                        
                        <!-- Campo oculto para service_id -->
                        <input type="hidden" name="service_id" value="{{ $service->id ?? 1 }}">

                        <!-- Nombre completo -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre completo *
                            </label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                value="{{ auth()->user()->name }}"
                                readonly
                                class="w-full px-4 py-3 border border-gray-300 focus:border-sage focus:ring-2 focus:ring-sage focus:ring-opacity-20 transition-colors duration-200 bg-gray-50"
                                required
                            >
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email *
                            </label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="{{ auth()->user()->email }}"
                                readonly
                                class="w-full px-4 py-3 border border-gray-300 focus:border-sage focus:ring-2 focus:ring-sage focus:ring-opacity-20 transition-colors duration-200 bg-gray-50"
                                required
                            >
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Teléfono *
                            </label>
                            <input 
                                type="tel" 
                                id="phone" 
                                name="phone" 
                                placeholder="+34 600 000 000"
                                class="w-full px-4 py-3 border border-gray-300 focus:border-sage focus:ring-2 focus:ring-sage focus:ring-opacity-20 transition-colors duration-200"
                                required
                            >
                            <p class="mt-1 text-xs text-gray-500">Formato: +34 600 000 000</p>
                        </div>

                        <!-- Fecha y Hora -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Fecha preferida *
                                </label>
                                <input 
                                    type="date" 
                                    id="date" 
                                    name="date"
                                    min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                    class="w-full px-4 py-3 border border-gray-300 focus:border-sage focus:ring-2 focus:ring-sage focus:ring-opacity-20 transition-colors duration-200"
                                    required
                                >
                            </div>

                            <div>
                                <label for="time" class="block text-sm font-medium text-gray-700 mb-2">
                                    Hora preferida *
                                </label>
                                <select 
                                    id="time" 
                                    name="time"
                                    class="w-full px-4 py-3 border border-gray-300 focus:border-sage focus:ring-2 focus:ring-sage focus:ring-opacity-20 transition-colors duration-200"
                                    required
                                >
                                    <option value="">Selecciona una hora</option>
                                    <option value="09:00">09:00</option>
                                    <option value="10:00">10:00</option>
                                    <option value="11:00">11:00</option>
                                    <option value="12:00">12:00</option>
                                    <option value="13:00">13:00</option>
                                    <option value="16:00">16:00</option>
                                    <option value="17:00">17:00</option>
                                    <option value="18:00">18:00</option>
                                    <option value="19:00">19:00</option>
                                </select>
                            </div>
                        </div>

                        <!-- Motivo de la consulta -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Motivo de la consulta *
                            </label>
                            <textarea 
                                id="notes" 
                                name="notes" 
                                rows="5"
                                placeholder="Cuéntanos qué te gustaría trabajar en la consulta, tus objetivos, preocupaciones o cualquier información relevante..."
                                class="w-full px-4 py-3 border border-gray-300 focus:border-sage focus:ring-2 focus:ring-sage focus:ring-opacity-20 transition-colors duration-200 resize-none"
                                required
                            ></textarea>
                            <p class="mt-1 text-xs text-gray-500">Mínimo 20 caracteres</p>
                        </div>

                        <!-- Botón de envío -->
                        <div class="pt-4">
                            <button 
                                type="submit"
                                class="w-full bg-sage text-white py-4 px-8 text-base font-medium hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg"
                            >
                                <span class="flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Confirmar cita
                                </span>
                            </button>
                        </div>

                        <p class="text-xs text-gray-500 text-center mt-4">
                            Al confirmar tu cita, aceptas nuestros términos y condiciones. 
                            Te contactaremos en breve para confirmar la disponibilidad.
                        </p>
                    </form>
                @else
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        <h3 class="text-xl font-normal text-gray-900 mb-2">Inicia sesión para reservar</h3>
                        <p class="text-gray-600 mb-6">Necesitas una cuenta para reservar una cita con nuestro nutricionista</p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center bg-black text-white px-8 py-3 text-sm font-medium hover:bg-gray-900 transition-colors duration-200">
                                Iniciar sesión
                            </a>
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center bg-sage text-white px-8 py-3 text-sm font-medium hover:bg-opacity-90 transition-colors duration-200">
                                Crear cuenta
                            </a>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </section>

    <!-- Contacto Directo -->
    <section id="contacto" class="py-20 lg:py-28 bg-white border-t border-gray-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 fade-in-up">
                <h2 class="text-4xl sm:text-5xl font-light text-black mb-4 tracking-tight">
                    Contacto directo
                </h2>
                <p class="text-lg text-gray-600 font-light">
                    ¿Tienes alguna pregunta? Estoy aquí para ayudarte
                </p>
            </div>

            @auth
                <!-- Usuario autenticado: mostrar botones de contacto -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 fade-in-up delay-1">
                    
                    <!-- WhatsApp -->
                    <a 
                        href="https://wa.me/34629642414?text=Hola,%20me%20gustaría%20reservar%20una%20cita%20con%20el%20nutricionista%20Paco%20Villar" 
                        target="_blank"
                        class="group bg-gradient-to-br from-green-500 to-green-600 text-white p-8 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1"
                    >
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mr-6">
                                <div class="w-16 h-16 bg-white bg-opacity-20 flex items-center justify-center group-hover:bg-opacity-30 transition-all duration-300">
                                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-xl font-medium mb-2">WhatsApp</h3>
                                <p class="text-white text-opacity-90 text-sm mb-4">
                                    Respuesta rápida y directa. Escríbeme por WhatsApp para resolver tus dudas.
                                </p>
                                <div class="flex items-center text-sm font-medium">
                                    <span>Enviar mensaje</span>
                                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-2 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </a>

                    <!-- Email -->
                    <a 
                        href="mailto:nutricionista@ikigai.com?subject=Consulta%20nutricional&body=Hola,%0D%0A%0D%0AMe%20gustaría%20más%20información%20sobre..." 
                        class="group bg-gradient-to-br from-blue-500 to-blue-600 text-white p-8 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1"
                    >
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mr-6">
                                <div class="w-16 h-16 bg-white bg-opacity-20 flex items-center justify-center group-hover:bg-opacity-30 transition-all duration-300">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-xl font-medium mb-2">Email</h3>
                                <p class="text-white text-opacity-90 text-sm mb-4">
                                    Envíame un correo con tu consulta y te responderé en menos de 24 horas.
                                </p>
                                <div class="flex items-center text-sm font-medium">
                                    <span>nutricionista@ikigai.com</span>
                                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-2 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </a>

                </div>
            @else
                <!-- Usuario NO autenticado: mostrar mensaje para iniciar sesión -->
                <div class="bg-white border-2 border-gray-200 p-12 text-center fade-in-up delay-1">
                    <div class="mb-6">
                        <svg class="w-16 h-16 text-gray-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-normal text-gray-900 mb-3">Inicia sesión para contactar</h3>
                    <p class="text-gray-600 mb-8 max-w-md mx-auto">
                        Para poder contactar directamente con nuestro nutricionista por WhatsApp o Email, necesitas tener una cuenta e iniciar sesión.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center bg-black text-white px-8 py-3 text-sm font-medium hover:bg-gray-900 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Iniciar sesión
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center bg-sage text-white px-8 py-3 text-sm font-medium hover:bg-opacity-90 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            Crear cuenta gratis
                        </a>
                    </div>
                </div>
            @endauth

            <!-- Info adicional -->
            <div class="mt-12 text-center bg-beige p-8 border border-gray-200 fade-in-up delay-2">
                <h3 class="text-lg font-medium text-black mb-4">Horario de atención</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-sm text-gray-700">
                    <div>
                        <div class="font-medium text-black mb-1">Lunes a Viernes</div>
                        <div>9:00 - 13:00</div>
                        <div>16:00 - 19:00</div>
                    </div>
                    <div>
                        <div class="font-medium text-black mb-1">Sábados</div>
                        <div>9:00 - 13:00</div>
                    </div>
                    <div>
                        <div class="font-medium text-black mb-1">Domingos</div>
                        <div class="text-gray-500">Cerrado</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Final -->
    <section class="py-20 lg:py-28 bg-sage text-white border-t border-sage">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl font-light mb-6 tracking-tight">
                ¿Listo para transformar tu vida?
            </h2>
            <p class="text-lg text-white text-opacity-90 mb-8 font-light">
                El primer paso hacia una vida más saludable comienza con una conversación
            </p>
            <a 
                href="#reserva" 
                onclick="document.getElementById('bookingForm')?.scrollIntoView({behavior: 'smooth', block: 'center'}); return false;"
                class="inline-flex items-center bg-white text-sage px-10 py-4 text-base font-medium hover:bg-opacity-90 transition-colors duration-200 shadow-lg"
            >
                Reservar mi cita
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        <li><a href="#nutricionista" class="hover:text-white transition-colors duration-200">Nutricionista</a></li>
                    </ul>
                </div>

                <!-- Legal -->
                <div>
                    <h4 class="text-white text-xs uppercase tracking-wider mb-4 font-medium">Legal</h4>
                    <ul class="space-y-2 text-xs font-light">
                        <li><a href="#" class="hover:text-white transition-colors duration-200">Términos</a></li>
                        <li><a href="#" class="hover:text-white transition-colors duration-200">Privacidad</a></li>
                        <li><a href="{{ route('cookies.policy') }}" class="hover:text-white transition-colors duration-200">Cookies</a></li>
                        <li><a href="#contacto" class="hover:text-white transition-colors duration-200">Contacto</a></li>
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

        // Validación del formulario
        document.getElementById('bookingForm')?.addEventListener('submit', function(e) {
            const phone = document.getElementById('phone').value;
            const notes = document.getElementById('notes').value;
            const date = document.getElementById('date').value;
            const time = document.getElementById('time').value;
            
            // Validar teléfono
            const phoneRegex = /^(\+34|0034|34)?[6789]\d{8}$/;
            if (!phoneRegex.test(phone.replace(/\s/g, ''))) {
                e.preventDefault();
                alert('Por favor, introduce un número de teléfono válido español.');
                return false;
            }
            
            // Validar motivo de consulta
            if (notes.length < 20) {
                e.preventDefault();
                alert('Por favor, describe tu motivo de consulta con más detalle (mínimo 20 caracteres).');
                return false;
            }
            
            // Validar fecha y hora
            if (!date || !time) {
                e.preventDefault();
                alert('Por favor, selecciona una fecha y hora para tu cita.');
                return false;
            }
            
            // Combinar fecha y hora para el campo scheduled_at
            const scheduledAt = date + ' ' + time + ':00';
            
            // Crear input oculto con la fecha combinada
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'scheduled_at';
            input.value = scheduledAt;
            this.appendChild(input);
        });

        // Animación de scroll suave para enlaces internos
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href === '#' || href === '#reserva') return;
                
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Mensaje de confirmación visual al enviar
        const form = document.getElementById('bookingForm');
        if (form) {
            form.addEventListener('submit', function() {
                const button = this.querySelector('button[type="submit"]');
                button.disabled = true;
                button.innerHTML = '<span class="flex items-center justify-center"><svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Procesando...</span>';
            });
        }
    </script>

    <!-- Cookie Banner -->
    @include('components.cookie-banner')

</body>
</html>

