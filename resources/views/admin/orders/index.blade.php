@extends('admin.layout')

@section('title', 'Pedidos')

@section('content')

<!-- Page Header -->
<div class="mb-12 flex items-center justify-between">
    <div>
        <h1 class="text-4xl font-light text-black tracking-tight mb-2">Pedidos</h1>
        <p class="text-sm text-gray-500">Gestión de pedidos y envíos</p>
    </div>
</div>

<!-- Filtros -->
<div class="mb-8 border border-gray-200 p-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-xs text-gray-500 uppercase tracking-wider font-medium mb-2">Estado</label>
            <select name="status" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200 text-sm">
                <option value="">Todos</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Procesando</option>
                <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Enviado</option>
                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Entregado</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-500 uppercase tracking-wider font-medium mb-2">Buscar</label>
            <input type="text" name="search" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200 text-sm" placeholder="N° pedido o cliente..." value="{{ request('search') }}">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="bg-black text-white hover:bg-gray-900 px-6 py-3 text-sm font-medium transition-colors duration-200">
                Filtrar
            </button>
            @if(request('status') || request('search'))
            <a href="{{ route('admin.orders.index') }}" class="bg-white text-black hover:bg-gray-50 px-6 py-3 text-sm font-medium border border-black transition-colors duration-200">
                Limpiar
            </a>
            @endif
        </div>
    </form>
</div>

<!-- Table -->
<div class="border border-gray-200">
    <table class="w-full">
        <thead>
            <tr class="border-b border-gray-200">
                <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">N° Pedido</th>
                <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">Cliente</th>
                <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">Total</th>
                <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">Estado</th>
                <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">Pago</th>
                <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">Fecha</th>
                <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr class="border-b border-gray-100 last:border-0 hover:bg-gray-50 transition-colors duration-200">
                <td class="px-6 py-4 text-sm text-black font-mono font-medium">{{ $order->order_number }}</td>
                <td class="px-6 py-4 text-sm text-black">{{ $order->user->name }}</td>
                <td class="px-6 py-4 text-sm text-black font-medium">${{ number_format($order->total, 2) }}</td>
                <td class="px-6 py-4">
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
                    <span class="inline-block px-3 py-1 text-xs font-medium uppercase tracking-wider border {{ $statusClasses }}">
                        {{ $statusText }}
                    </span>
                </td>
                <td class="px-6 py-4">
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
                    <span class="inline-block px-3 py-1 text-xs font-medium uppercase tracking-wider border {{ $paymentClasses }}">
                        {{ $paymentText }}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-2">
                        <button onclick="viewOrder({{ $order->id }})" class="text-sm text-gray-600 hover:text-black border-b border-gray-600 hover:border-black transition-colors duration-200">
                            Ver
                        </button>
                        <button onclick="updateOrderStatus({{ $order->id }}, '{{ $order->status }}')" class="text-sm text-gray-600 hover:text-black border-b border-gray-600 hover:border-black transition-colors duration-200">
                            Estado
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-16 text-center text-sm text-gray-400">
                    No hay pedidos registrados
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if($orders->hasPages())
<div class="mt-8">
    {{ $orders->links() }}
</div>
@endif

<!-- Modal Actualizar Estado -->
<div id="updateStatusModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg">
        <div class="p-8">
            <h2 class="text-2xl font-light text-black mb-6 tracking-tight">Actualizar Estado del Pedido</h2>
            <form id="updateStatusForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-black mb-2">Estado del Pedido *</label>
                        <select name="status" id="order_status" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200" required>
                            <option value="pending">Pendiente</option>
                            <option value="processing">Procesando</option>
                            <option value="shipped">Enviado</option>
                            <option value="delivered">Entregado</option>
                            <option value="cancelled">Cancelado</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-black mb-2">Número de Seguimiento</label>
                        <input type="text" name="tracking_number" id="tracking_number" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200" placeholder="Ej: 123456789">
                    </div>
                </div>
                <div class="flex items-center gap-3 mt-8">
                    <button type="button" onclick="toggleModal('updateStatusModal')" class="flex-1 bg-white text-black hover:bg-gray-50 px-6 py-3 text-sm font-medium border border-black transition-colors duration-200">
                        Cancelar
                    </button>
                    <button type="submit" class="flex-1 bg-black text-white hover:bg-gray-900 px-6 py-3 text-sm font-medium transition-colors duration-200">
                        Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Ver Pedido -->
<div id="viewOrderModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-3xl max-h-[90vh] overflow-y-auto">
        <div class="p-8">
            <h2 class="text-2xl font-light text-black mb-6 tracking-tight">Detalles del Pedido</h2>
            <div id="orderDetails" class="text-sm text-gray-600">Cargando...</div>
            <div class="flex justify-end mt-8">
                <button type="button" onclick="toggleModal('viewOrderModal')" class="bg-white text-black hover:bg-gray-50 px-6 py-3 text-sm font-medium border border-black transition-colors duration-200">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function toggleModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.toggle('hidden');
    }
}

function updateOrderStatus(id, currentStatus) {
    document.getElementById('updateStatusForm').action = `/admin/pedidos/${id}/estado`;
    document.getElementById('order_status').value = currentStatus;
    toggleModal('updateStatusModal');
}

async function viewOrder(id) {
    toggleModal('viewOrderModal');
    const details = document.getElementById('orderDetails');
    details.innerHTML = '<p class="text-gray-400">Cargando...</p>';
    
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
            details.innerHTML = '<p class="text-red-600">Error al cargar los detalles del pedido.</p>';
        }
    } catch (error) {
        details.innerHTML = '<p class="text-red-600">Error de conexión.</p>';
    }
}

// Cerrar modal al hacer clic fuera
document.addEventListener('DOMContentLoaded', function() {
    const modals = document.querySelectorAll('[id$="Modal"]');
    modals.forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
    });
});
</script>
@endsection
