@extends('admin.layout')

@section('title', 'Gestión de Pedidos')

@section('content')
<div class="page-header">
    <h1 class="page-title">Pedidos</h1>
</div>

<!-- Filtros -->
<div class="card" style="margin-bottom: 20px;">
    <form method="GET" style="display: flex; gap: 15px; align-items: end;">
        <div class="form-group" style="margin-bottom: 0; flex: 1;">
            <label class="form-label">Estado</label>
            <select name="status" class="form-select">
                <option value="">Todos</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Procesando</option>
                <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Enviado</option>
                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Entregado</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
            </select>
        </div>
        <div class="form-group" style="margin-bottom: 0; flex: 1;">
            <label class="form-label">Buscar</label>
            <input type="text" name="search" class="form-input" placeholder="N° pedido..." value="{{ request('search') }}">
        </div>
        <button type="submit" class="btn btn-primary">Filtrar</button>
        @if(request('status') || request('search'))
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Limpiar</a>
        @endif
    </form>
</div>

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>N° Pedido</th>
                    <th>Cliente</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Pago</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td><strong>{{ $order->order_number }}</strong></td>
                    <td>{{ $order->user->name }}</td>
                    <td>${{ number_format($order->total, 2) }}</td>
                    <td>
                        @php
                            $statusClass = match($order->status) {
                                'pending' => 'badge-warning',
                                'processing' => 'badge-info',
                                'shipped' => 'badge-info',
                                'delivered' => 'badge-success',
                                'cancelled' => 'badge-danger',
                                default => 'badge-warning'
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
                        <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                    </td>
                    <td>
                        @php
                            $paymentClass = match($order->payment_status) {
                                'paid' => 'badge-success',
                                'pending' => 'badge-warning',
                                'failed' => 'badge-danger',
                                'refunded' => 'badge-info',
                                default => 'badge-warning'
                            };
                            $paymentText = match($order->payment_status) {
                                'paid' => 'Pagado',
                                'pending' => 'Pendiente',
                                'failed' => 'Fallido',
                                'refunded' => 'Reembolsado',
                                default => $order->payment_status
                            };
                        @endphp
                        <span class="badge {{ $paymentClass }}">{{ $paymentText }}</span>
                    </td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <div class="action-buttons">
                            <button onclick="viewOrder({{ $order->id }})" class="btn btn-secondary btn-small">Ver</button>
                            <button onclick="updateOrderStatus({{ $order->id }}, '{{ $order->status }}')" class="btn btn-primary btn-small">Estado</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px; color: #718096;">
                        No hay pedidos registrados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($orders->hasPages())
    <div style="padding: 20px;">
        {{ $orders->links() }}
    </div>
    @endif
</div>

<!-- Modal Actualizar Estado -->
<div id="updateStatusModal" class="modal">
    <div class="modal-content">
        <h2 class="modal-header">Actualizar Estado del Pedido</h2>
        <form id="updateStatusForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Estado del Pedido *</label>
                <select name="status" id="order_status" class="form-select" required>
                    <option value="pending">Pendiente</option>
                    <option value="processing">Procesando</option>
                    <option value="shipped">Enviado</option>
                    <option value="delivered">Entregado</option>
                    <option value="cancelled">Cancelado</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Número de Seguimiento</label>
                <input type="text" name="tracking_number" id="tracking_number" class="form-input" placeholder="Ej: 123456789">
            </div>
            <div class="modal-footer">
                <button type="button" onclick="toggleModal('updateStatusModal')" class="btn btn-secondary">Cancelar</button>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Ver Pedido -->
<div id="viewOrderModal" class="modal">
    <div class="modal-content" style="max-width: 700px;">
        <h2 class="modal-header">Detalles del Pedido</h2>
        <div id="orderDetails">Cargando...</div>
        <div class="modal-footer">
            <button type="button" onclick="toggleModal('viewOrderModal')" class="btn btn-secondary">Cerrar</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function updateOrderStatus(id, currentStatus) {
    document.getElementById('updateStatusForm').action = `/admin/pedidos/${id}/estado`;
    document.getElementById('order_status').value = currentStatus;
    toggleModal('updateStatusModal');
}

async function viewOrder(id) {
    toggleModal('viewOrderModal');
    const details = document.getElementById('orderDetails');
    details.innerHTML = 'Cargando...';
    
    try {
        const response = await fetch(`/admin/pedidos/${id}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (response.ok) {
            const html = await response.text();
            details.innerHTML = html;
        } else {
            details.innerHTML = '<p style="color: #e53e3e;">Error al cargar los detalles del pedido.</p>';
        }
    } catch (error) {
        details.innerHTML = '<p style="color: #e53e3e;">Error de conexión.</p>';
    }
}
</script>
@endsection

