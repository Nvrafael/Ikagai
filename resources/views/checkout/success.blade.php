<x-checkout-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
        <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        
        <h1 class="text-4xl font-light text-black mb-4">¡Gracias por tu compra!</h1>
        <p class="text-xl text-gray-600 mb-8">Tu pedido #{{ $order->order_number }} ha sido confirmado.</p>
        
        <div class="max-w-md mx-auto bg-white border border-gray-200 p-8 mb-8 text-left">
            <p class="mb-2"><strong>Estado del Pago:</strong> <span class="text-green-600 font-medium">Pagado</span></p>
            <p class="mb-2"><strong>Total:</strong> ${{ number_format($order->total, 2) }}</p>
            <p class="mb-4"><strong>Enviado a:</strong> {{ $order->shipping_address }}, {{ $order->shipping_city }}</p>
            <p class="text-sm text-gray-500">Hemos enviado un correo de confirmación a {{ $order->shipping_email }}</p>
        </div>

        <a href="{{ route('products.index') }}" class="inline-block bg-black text-white px-8 py-3 hover:bg-gray-800 transition-colors">
            Seguir Comprando
        </a>
    </div>
</x-checkout-layout>
