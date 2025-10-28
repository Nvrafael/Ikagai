<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'IKIGAI') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-white font-sans">
    <div class="min-h-screen flex">
        <!-- Left Side - Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                <!-- Logo -->
                <div class="mb-12">
                    <a href="/" class="text-2xl font-light tracking-tight text-black">
                        IKIGAI
                    </a>
                </div>

                <!-- Content -->
                {{ $slot }}
            </div>
        </div>

        <!-- Right Side - Image/Pattern -->
        <div class="hidden lg:flex lg:w-1/2 bg-gray-50 items-center justify-center p-12 border-l border-gray-100">
            <div class="max-w-md text-center">
                <h2 class="text-4xl font-light text-black mb-6 tracking-tight">
                    Encuentra tu equilibrio
                </h2>
                <p class="text-base text-gray-500 font-light leading-relaxed">
                    Descubre el equilibrio perfecto entre tu salud y bienestar con nuestros suplementos naturales y orientación experta
                </p>
                
                <!-- Decorative element -->
                <div class="mt-12 flex justify-center gap-2">
                    <div class="w-16 h-1 bg-black"></div>
                    <div class="w-16 h-1 bg-gray-300"></div>
                    <div class="w-16 h-1 bg-gray-200"></div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
