<div style="font-size: 14px;">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
        <div>
            <h3 style="font-size: 16px; margin-bottom: 10px; color: #2d3748;">Información del Pedido</h3>
            <p><strong>Número:</strong> {{ $order->order_number }}</p>
            <p><strong>Fecha:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Estado:</strong> 
                @php
                    $statusText = match($order->status) {
                        'pending' => 'Pendiente',
                        'processing' => 'Procesando',
                        'shipped' => 'Enviado',
                        'delivered' => 'Entregado',
                        'cancelled' => 'Cancelado',
                        default => $order->status
                    };
                @endphp
                <span class="badge badge-info">{{ $statusText }}</span>
            </p>
            <p><strong>Pago:</strong> 
                @php
                    $paymentText = match($order->payment_status) {
                        'paid' => 'Pagado',
                        'pending' => 'Pendiente',
                        'failed' => 'Fallido',
                        'refunded' => 'Reembolsado',
                        default => $order->payment_status
                    };
                @endphp
                <span class="badge badge-success">{{ $paymentText }}</span>
            </p>
        </div>
        
        <div>
            <h3 style="font-size: 16px; margin-bottom: 10px; color: #2d3748;">Información de Envío</h3>
            <p><strong>Nombre:</strong> {{ $order->shipping_name }}</p>
            <p><strong>Email:</strong> {{ $order->shipping_email }}</p>
            <p><strong>Teléfono:</strong> {{ $order->shipping_phone }}</p>
            <p><strong>Dirección:</strong><br>
                {{ $order->shipping_address }}<br>
                {{ $order->shipping_city }}, {{ $order->shipping_state }}<br>
                {{ $order->shipping_zipcode }}, {{ $order->shipping_country }}
            </p>
        </div>
    </div>
    
    <h3 style="font-size: 16px; margin-bottom: 10px; color: #2d3748;">Productos</h3>
    <table style="width: 100%; margin-bottom: 20px;">
        <thead>
            <tr style="background: #f7fafc;">
                <th style="padding: 10px; text-align: left;">Producto</th>
                <th style="padding: 10px; text-align: center;">Cantidad</th>
                <th style="padding: 10px; text-align: right;">Precio</th>
                <th style="padding: 10px; text-align: right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr style="border-bottom: 1px solid #e2e8f0;">
                <td style="padding: 10px;">{{ $item->product->name }}</td>
                <td style="padding: 10px; text-align: center;">{{ $item->quantity }}</td>
                <td style="padding: 10px; text-align: right;">${{ number_format($item->price, 2) }}</td>
                <td style="padding: 10px; text-align: right;">${{ number_format($item->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="padding: 10px; text-align: right;"><strong>Subtotal:</strong></td>
                <td style="padding: 10px; text-align: right;">${{ number_format($order->subtotal, 2) }}</td>
            </tr>
            <tr>
                <td colspan="3" style="padding: 10px; text-align: right;"><strong>Impuestos:</strong></td>
                <td style="padding: 10px; text-align: right;">${{ number_format($order->tax, 2) }}</td>
            </tr>
            <tr>
                <td colspan="3" style="padding: 10px; text-align: right;"><strong>Envío:</strong></td>
                <td style="padding: 10px; text-align: right;">${{ number_format($order->shipping, 2) }}</td>
            </tr>
            <tr style="font-size: 16px; font-weight: bold;">
                <td colspan="3" style="padding: 10px; text-align: right;">TOTAL:</td>
                <td style="padding: 10px; text-align: right; color: #e53e3e;">${{ number_format($order->total, 2) }}</td>
            </tr>
        </tfoot>
    </table>
    
    @if($order->notes)
    <div style="background: #f7fafc; padding: 15px; border-radius: 5px;">
        <strong>Notas del pedido:</strong><br>
        {{ $order->notes }}
    </div>
    @endif
</div>

