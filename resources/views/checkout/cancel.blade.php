<x-checkout-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
        <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </div>
        
        <h1 class="text-4xl font-light text-black mb-4">Pago Cancelado</h1>
        <p class="text-xl text-gray-600 mb-8">El proceso de pago fue cancelado. No se ha realizado ningún cargo.</p>
        
        <div class="flex justify-center gap-4">
            <a href="{{ route('checkout.index') }}" class="inline-block bg-black text-white px-8 py-3 hover:bg-gray-800 transition-colors">
                Intentar de nuevo
            </a>
            <a href="{{ route('cart.index') }}" class="inline-block border border-black text-black px-8 py-3 hover:bg-gray-50 transition-colors">
                Volver al Carrito
            </a>
        </div>
    </div>
</x-checkout-layout>
