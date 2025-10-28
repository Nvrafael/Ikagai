<x-guest-layout>
    <!-- Title -->
    <div class="mb-8">
        <h1 class="text-3xl font-light text-black mb-2 tracking-tight">Recuperar contraseña</h1>
        <p class="text-sm text-gray-500">Te enviaremos un enlace para restablecer tu contraseña</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-6 px-4 py-3 bg-green-50 border-l-2 border-green-600 text-sm text-green-900">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
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
                class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200 text-sm @error('email') border-red-500 @enderror"
                placeholder="tu@email.com"
            />
            @error('email')
                <span class="block mt-1 text-xs text-red-600">{{ $message }}</span>
            @enderror
        </div>

        <!-- Submit Button -->
        <button 
            type="submit"
            class="w-full bg-black text-white hover:bg-gray-900 px-6 py-4 text-sm font-medium transition-colors duration-200"
        >
            Enviar enlace de recuperación
        </button>

        <!-- Back to Login -->
        <div class="text-center pt-6 border-t border-gray-100">
            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-black border-b border-gray-600 hover:border-black transition-colors duration-200">
                ← Volver al inicio de sesión
            </a>
        </div>
    </form>
</x-guest-layout>
