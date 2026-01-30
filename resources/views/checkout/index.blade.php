<x-checkout-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-4xl font-light text-black mb-2">Finalizar Compra</h1>
        <p class="text-gray-500 mb-12">Ingresa tus datos de envío</p>

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-8">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">
                            Por favor corrige los siguientes errores:
                        </p>
                        <ul class="mt-1 list-disc list-inside text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-8">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Formulario de Envío -->
            <div>
                <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form" class="space-y-6">
                    @csrf
                    
                    <div class="bg-white p-6 border border-gray-200">
                        <h2 class="text-xl font-medium text-black mb-6">Información de Contacto</h2>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="shipping_email" class="block text-sm font-medium text-black mb-2">Email</label>
                                <input type="email" name="shipping_email" id="shipping_email" value="{{ auth()->user()->email ?? old('shipping_email') }}" required 
                                    class="w-full px-4 py-3 border border-gray-200 focus:border-sage focus:ring-2 focus:ring-sage focus:ring-opacity-20 focus:outline-none transition-colors duration-200 text-sm @error('shipping_email') border-red-500 @enderror">
                                @error('shipping_email')
                                    <span class="block mt-1 text-xs text-red-600">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="shipping_phone" class="block text-sm font-medium text-black mb-2">Teléfono</label>
                                <input type="tel" name="shipping_phone" id="shipping_phone" value="{{ old('shipping_phone') }}" required 
                                    class="w-full px-4 py-3 border border-gray-200 focus:border-sage focus:ring-2 focus:ring-sage focus:ring-opacity-20 focus:outline-none transition-colors duration-200 text-sm @error('shipping_phone') border-red-500 @enderror">
                                @error('shipping_phone')
                                    <span class="block mt-1 text-xs text-red-600">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 border border-gray-200">
                        <h2 class="text-xl font-medium text-black mb-6">Dirección de Envío</h2>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="shipping_name" class="block text-sm font-medium text-black mb-2">Nombre Completo</label>
                                <input type="text" name="shipping_name" id="shipping_name" value="{{ auth()->user()->name ?? old('shipping_name') }}" required 
                                    class="w-full px-4 py-3 border border-gray-200 focus:border-sage focus:ring-2 focus:ring-sage focus:ring-opacity-20 focus:outline-none transition-colors duration-200 text-sm @error('shipping_name') border-red-500 @enderror">
                                @error('shipping_name')
                                    <span class="block mt-1 text-xs text-red-600">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="shipping_address" class="block text-sm font-medium text-black mb-2">Dirección</label>
                                <input type="text" name="shipping_address" id="shipping_address" value="{{ old('shipping_address') }}" required 
                                    class="w-full px-4 py-3 border border-gray-200 focus:border-sage focus:ring-2 focus:ring-sage focus:ring-opacity-20 focus:outline-none transition-colors duration-200 text-sm @error('shipping_address') border-red-500 @enderror">
                                @error('shipping_address')
                                    <span class="block mt-1 text-xs text-red-600">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="shipping_city" class="block text-sm font-medium text-black mb-2">Ciudad</label>
                                    <input type="text" name="shipping_city" id="shipping_city" value="{{ old('shipping_city') }}" required 
                                        class="w-full px-4 py-3 border border-gray-200 focus:border-sage focus:ring-2 focus:ring-sage focus:ring-opacity-20 focus:outline-none transition-colors duration-200 text-sm @error('shipping_city') border-red-500 @enderror">
                                    @error('shipping_city')
                                        <span class="block mt-1 text-xs text-red-600">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label for="shipping_state" class="block text-sm font-medium text-black mb-2">Estado/Provincia</label>
                                    <input type="text" name="shipping_state" id="shipping_state" value="{{ old('shipping_state') }}" required 
                                        class="w-full px-4 py-3 border border-gray-200 focus:border-sage focus:ring-2 focus:ring-sage focus:ring-opacity-20 focus:outline-none transition-colors duration-200 text-sm @error('shipping_state') border-red-500 @enderror">
                                    @error('shipping_state')
                                        <span class="block mt-1 text-xs text-red-600">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="shipping_zipcode" class="block text-sm font-medium text-black mb-2">Código Postal</label>
                                    <input type="text" name="shipping_zipcode" id="shipping_zipcode" value="{{ old('shipping_zipcode') }}" required 
                                        class="w-full px-4 py-3 border border-gray-200 focus:border-sage focus:ring-2 focus:ring-sage focus:ring-opacity-20 focus:outline-none transition-colors duration-200 text-sm @error('shipping_zipcode') border-red-500 @enderror">
                                    @error('shipping_zipcode')
                                        <span class="block mt-1 text-xs text-red-600">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label for="shipping_country" class="block text-sm font-medium text-black mb-2">País</label>
                                    <select name="shipping_country" id="shipping_country" class="w-full px-4 py-3 border border-gray-200 focus:border-sage focus:ring-2 focus:ring-sage focus:ring-opacity-20 focus:outline-none transition-colors duration-200 text-sm">
                                        <option value="México">México</option>
                                        <option value="España">España</option>
                                        <option value="Estados Unidos">Estados Unidos</option>
                                        <option value="Otro">Otro</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div>
                                <label for="notes" class="block text-sm font-medium text-black mb-2">Notas del pedido (opcional)</label>
                                <textarea name="notes" id="notes" rows="3" class="w-full px-4 py-3 border border-gray-200 focus:border-sage focus:ring-2 focus:ring-sage focus:ring-opacity-20 focus:outline-none transition-colors duration-200 text-sm">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-sage text-white hover:bg-sage-dark px-6 py-4 text-sm font-medium transition-colors duration-200 shadow-md hover:shadow-lg">
                        Pagar con Tarjeta (Stripe)
                    </button>
                </form>
            </div>

            <!-- Resumen del Pedido -->
            <div>
                <div class="bg-gray-50 p-6 sticky top-24">
                    <h2 class="text-xl font-medium text-black mb-6">Tu Pedido</h2>
                    
                    <div class="space-y-4 mb-6">
                        @foreach($cartItems as $item)
                            <div class="flex justify-between items-start">
                                <div class="flex gap-3">
                                    <div class="w-16 h-16 bg-white flex items-center justify-center border border-gray-200">
                                        @if($item['product']->images && count($item['product']->images) > 0)
                                            <img src="{{ asset('storage/' . $item['product']->images[0]) }}" class="w-full h-full object-contain">
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-medium text-sm text-black">{{ $item['product']->name }}</p>
                                        <p class="text-xs text-gray-500">Cant: {{ $item['quantity'] }}</p>
                                    </div>
                                </div>
                                <p class="text-sm font-medium">${{ number_format($item['subtotal'], 2) }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-gray-200 pt-4 space-y-2">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal</span>
                            <span>${{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>IVA (16%)</span>
                            <span>${{ number_format($tax, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-xl font-medium text-black pt-2 border-t border-gray-200 mt-2">
                            <span>Total</span>
                            <span>${{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-checkout-layout>