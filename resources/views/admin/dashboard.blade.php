@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <h1 class="page-title">Dashboard</h1>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value">{{ $stats['total_users'] }}</div>
        <div class="stat-label">Total Usuarios</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $stats['total_products'] }}</div>
        <div class="stat-label">Total Productos</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $stats['total_orders'] }}</div>
        <div class="stat-label">Total Pedidos</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $stats['pending_reviews'] }}</div>
        <div class="stat-label">Reseñas Pendientes</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $stats['total_categories'] }}</div>
        <div class="stat-label">Total Categorías</div>
    </div>
</div>

<!-- Recent Orders -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">Pedidos Recientes</h2>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>N° Pedido</th>
                    <th>Cliente</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recent_orders as $order)
                <tr>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->user->name }}</td>
                    <td>${{ number_format($order->total, 2) }}</td>
                    <td>
                        @php
                            $statusClass = match($order->status) {
                                'pending' => 'badge-warning',
                                'delivered' => 'badge-success',
                                'cancelled' => 'badge-danger',
                                default => 'badge-info'
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
                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #718096;">No hay pedidos recientes</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Recent Users -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">Usuarios Recientes</h2>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Fecha de Registro</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recent_users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @php
                            $roleClass = match($user->role) {
                                'admin' => 'badge-danger',
                                'nutritionist' => 'badge-info',
                                'client' => 'badge-success',
                                default => 'badge-warning'
                            };
                            $roleText = match($user->role) {
                                'admin' => 'Administrador',
                                'nutritionist' => 'Nutricionista',
                                'client' => 'Cliente',
                                default => $user->role
                            };
                        @endphp
                        <span class="badge {{ $roleClass }}">{{ $roleText }}</span>
                    </td>
                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: #718096;">No hay usuarios recientes</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

