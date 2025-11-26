<x-guest-layout>
    <!-- Title -->
    <div class="mb-8">
        <h1 class="text-3xl font-light text-black mb-2 tracking-tight">Crear cuenta</h1>
        <p class="text-sm text-gray-500">Únete a la comunidad IKIGAI</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <!-- Hidden Role Field - Always client -->
        <input type="hidden" name="role" value="client">

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-black mb-2">
                Nombre completo
            </label>
            <input 
                id="name" 
                type="text" 
                name="name" 
                value="{{ old('name') }}"
                required 
                autofocus 
                autocomplete="name"
                class="w-full px-4 py-3 border border-gray-200 focus:border-sage focus:ring-2 focus:ring-sage focus:ring-opacity-20 focus:outline-none transition-colors duration-200 text-sm @error('name') border-red-500 @enderror"
                placeholder="Tu nombre completo"
            />
            @error('name')
                <span class="block mt-1 text-xs text-red-600">{{ $message }}</span>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-black mb-2">
                Email
            </label>
            <input 
                id="email" 
                type="email" 
                name="email" 
                value="{{ old('email') }}"
                required 
                autocomplete="username"
                class="w-full px-4 py-3 border border-gray-200 focus:border-sage focus:ring-2 focus:ring-sage focus:ring-opacity-20 focus:outline-none transition-colors duration-200 text-sm @error('email') border-red-500 @enderror"
                placeholder="tu@email.com"
            />
            @error('email')
                <span class="block mt-1 text-xs text-red-600">{{ $message }}</span>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-black mb-2">
                Contraseña
            </label>
            <input 
                id="password" 
                type="password" 
                name="password"
                required 
                autocomplete="new-password"
                class="w-full px-4 py-3 border border-gray-200 focus:border-sage focus:ring-2 focus:ring-sage focus:ring-opacity-20 focus:outline-none transition-colors duration-200 text-sm @error('password') border-red-500 @enderror"
                placeholder="Mínimo 8 caracteres"
            />
            @error('password')
                <span class="block mt-1 text-xs text-red-600">{{ $message }}</span>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-black mb-2">
                Confirmar contraseña
            </label>
            <input 
                id="password_confirmation" 
                type="password" 
                name="password_confirmation"
                required 
                autocomplete="new-password"
                class="w-full px-4 py-3 border border-gray-200 focus:border-sage focus:ring-2 focus:ring-sage focus:ring-opacity-20 focus:outline-none transition-colors duration-200 text-sm"
                placeholder="Repite tu contraseña"
            />
        </div>

        <!-- Submit Button -->
        <button 
            type="submit"
            class="w-full bg-sage text-white hover:bg-sage-dark px-6 py-4 text-sm font-medium transition-colors duration-200 shadow-md hover:shadow-lg"
        >
            Crear cuenta
        </button>

        <!-- Login Link -->
        <div class="text-center pt-6 border-t border-gray-100">
            <p class="text-sm text-gray-600">
                ¿Ya tienes cuenta?
                <a href="{{ route('login') }}" class="text-sage-dark hover:text-sage border-b border-sage-dark hover:border-sage transition-colors duration-200 ml-1">
                    Inicia sesión
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
