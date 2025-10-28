<div class="space-y-8">
    <!-- Información General -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Info del Pedido -->
        <div class="border border-gray-200 p-6">
            <h3 class="text-lg font-normal text-black mb-4 pb-3 border-b border-gray-100">Información del Pedido</h3>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500">Número:</dt>
                    <dd class="text-black font-mono font-medium">{{ $order->order_number }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Fecha:</dt>
                    <dd class="text-black">{{ $order->created_at->format('d/m/Y H:i') }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Estado:</dt>
                    <dd>
                        @php
                            $statusClasses = match($order->status) {
                                'pending' => 'bg-yellow-50 text-yellow-900 border-yellow-200',
                                'processing' => 'bg-blue-50 text-blue-900 border-blue-200',
                                'shipped' => 'bg-purple-50 text-purple-900 border-purple-200',
                                'delivered' => 'bg-green-50 text-green-900 border-green-200',
                                'cancelled' => 'bg-red-50 text-red-900 border-red-200',
                                default => 'bg-gray-50 text-gray-900 border-gray-200'
                            };
                            $statusText = match($order->status) {
                                'pending' => 'Pendiente',
                                'processing' => 'Procesando',
                                'shipped' => 'Enviado',
                                'delivered' => 'Entregado',
                                'cancelled' => 'Cancelado',
                                default => $order->status
                            };
                        @endphp
                        <span class="inline-block px-2 py-1 text-xs font-medium uppercase tracking-wider border {{ $statusClasses }}">
                            {{ $statusText }}
                        </span>
                    </dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Pago:</dt>
                    <dd>
                        @php
                            $paymentClasses = match($order->payment_status) {
                                'paid' => 'bg-green-50 text-green-900 border-green-200',
                                'pending' => 'bg-yellow-50 text-yellow-900 border-yellow-200',
                                'failed' => 'bg-red-50 text-red-900 border-red-200',
                                'refunded' => 'bg-blue-50 text-blue-900 border-blue-200',
                                default => 'bg-gray-50 text-gray-900 border-gray-200'
                            };
                            $paymentText = match($order->payment_status) {
                                'paid' => 'Pagado',
                                'pending' => 'Pendiente',
                                'failed' => 'Fallido',
                                'refunded' => 'Reembolsado',
                                default => $order->payment_status
                            };
                        @endphp
                        <span class="inline-block px-2 py-1 text-xs font-medium uppercase tracking-wider border {{ $paymentClasses }}">
                            {{ $paymentText }}
                        </span>
                    </dd>
                </div>
            </dl>
        </div>
        
        <!-- Info de Envío -->
        <div class="border border-gray-200 p-6">
            <h3 class="text-lg font-normal text-black mb-4 pb-3 border-b border-gray-100">Información de Envío</h3>
            <dl class="space-y-3 text-sm">
                <div>
                    <dt class="text-gray-500 mb-1">Nombre:</dt>
                    <dd class="text-black">{{ $order->shipping_name }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500 mb-1">Email:</dt>
                    <dd class="text-black">{{ $order->shipping_email }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500 mb-1">Teléfono:</dt>
                    <dd class="text-black">{{ $order->shipping_phone }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500 mb-1">Dirección:</dt>
                    <dd class="text-black leading-relaxed">
                        {{ $order->shipping_address }}<br>
                        {{ $order->shipping_city }}, {{ $order->shipping_state }}<br>
                        {{ $order->shipping_zipcode }}, {{ $order->shipping_country }}
                    </dd>
                </div>
            </dl>
        </div>
    </div>
    
    <!-- Productos -->
    <div>
        <h3 class="text-lg font-normal text-black mb-4">Productos del Pedido</h3>
        <div class="border border-gray-200">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left px-4 py-3 text-xs text-gray-500 uppercase tracking-wider font-medium">Producto</th>
                        <th class="text-center px-4 py-3 text-xs text-gray-500 uppercase tracking-wider font-medium">Cantidad</th>
                        <th class="text-right px-4 py-3 text-xs text-gray-500 uppercase tracking-wider font-medium">Precio</th>
                        <th class="text-right px-4 py-3 text-xs text-gray-500 uppercase tracking-wider font-medium">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr class="border-b border-gray-100 last:border-0">
                        <td class="px-4 py-3 text-black">{{ $item->product->name }}</td>
                        <td class="px-4 py-3 text-center text-black font-medium">{{ $item->quantity }}</td>
                        <td class="px-4 py-3 text-right text-black">${{ number_format($item->price, 2) }}</td>
                        <td class="px-4 py-3 text-right text-black font-medium">${{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="border-t-2 border-gray-300">
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-right text-gray-500">Subtotal:</td>
                        <td class="px-4 py-3 text-right text-black">${{ number_format($order->subtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-right text-gray-500">Impuestos:</td>
                        <td class="px-4 py-3 text-right text-black">${{ number_format($order->tax, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-right text-gray-500">Envío:</td>
                        <td class="px-4 py-3 text-right text-black">${{ number_format($order->shipping, 2) }}</td>
                    </tr>
                    <tr class="text-lg">
                        <td colspan="3" class="px-4 py-4 text-right text-black font-medium">TOTAL:</td>
                        <td class="px-4 py-4 text-right text-black font-bold">${{ number_format($order->total, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    
    <!-- Notas -->
    @if($order->notes)
    <div class="border-l-2 border-gray-300 pl-6 py-2 bg-gray-50">
        <p class="text-xs text-gray-500 uppercase tracking-wider font-medium mb-2">Notas del pedido:</p>
        <p class="text-sm text-black">{{ $order->notes }}</p>
    </div>
    @endif
</div>
