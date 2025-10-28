<x-guest-layout>
    <!-- Title -->
    <div class="mb-8">
        <h1 class="text-3xl font-light text-black mb-2 tracking-tight">Iniciar sesión</h1>
        <p class="text-sm text-gray-500">Accede a tu cuenta de IKIGAI</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-6 px-4 py-3 bg-green-50 border-l-2 border-green-600 text-sm text-green-900">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

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
                autofocus 
                autocomplete="username"
                class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200 text-sm @error('email') border-red-500 @enderror"
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
                autocomplete="current-password"
                class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200 text-sm @error('password') border-red-500 @enderror"
                placeholder="••••••••"
            />
            @error('password')
                <span class="block mt-1 text-xs text-red-600">{{ $message }}</span>
            @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer">
                <input 
                    id="remember_me" 
                    type="checkbox" 
                    name="remember"
                    class="w-4 h-4 border-gray-300"
                />
                <span class="text-sm text-gray-600">Recordarme</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-gray-600 hover:text-black border-b border-gray-600 hover:border-black transition-colors duration-200">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <button 
            type="submit"
            class="w-full bg-black text-white hover:bg-gray-900 px-6 py-4 text-sm font-medium transition-colors duration-200"
        >
            Iniciar sesión
        </button>

        <!-- Register Link -->
        <div class="text-center pt-6 border-t border-gray-100">
            <p class="text-sm text-gray-500">
                ¿No tienes cuenta?
                <a href="{{ route('register') }}" class="text-black hover:text-gray-600 border-b border-black hover:border-gray-600 transition-colors duration-200 ml-1">
                    Regístrate aquí
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
