<x-guest-layout>
    <!-- Title -->
    <div class="mb-8">
        <h1 class="text-3xl font-light text-black mb-2 tracking-tight">Nueva contraseña</h1>
        <p class="text-sm text-gray-500">Crea una nueva contraseña para tu cuenta</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-black mb-2">
                Email
            </label>
            <input 
                id="email" 
                type="email" 
                name="email" 
                value="{{ old('email', $request->email) }}"
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
                Nueva contraseña
            </label>
            <input 
                id="password" 
                type="password" 
                name="password"
                required 
                autocomplete="new-password"
                class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200 text-sm @error('password') border-red-500 @enderror"
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
                class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200 text-sm"
                placeholder="Repite tu contraseña"
            />
        </div>

        <!-- Submit Button -->
        <button 
            type="submit"
            class="w-full bg-black text-white hover:bg-gray-900 px-6 py-4 text-sm font-medium transition-colors duration-200"
        >
            Restablecer contraseña
        </button>
    </form>
</x-guest-layout>
