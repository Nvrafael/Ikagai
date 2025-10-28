@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')

<!-- Page Header -->
<div class="mb-12">
    <h1 class="text-4xl font-light text-black tracking-tight mb-2">Dashboard</h1>
    <p class="text-sm text-gray-500">Resumen general del sistema</p>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-1 mb-16">
    <!-- Total Usuarios -->
    <div class="bg-white border border-gray-200 hover:border-black transition-colors duration-200 p-8">
        <div class="mb-4">
            <div class="text-4xl font-light text-black">{{ $stats['total_users'] }}</div>
        </div>
        <div class="text-xs text-gray-500 uppercase tracking-wider">Usuarios</div>
    </div>
    
    <!-- Total Productos -->
    <div class="bg-white border border-gray-200 hover:border-black transition-colors duration-200 p-8">
        <div class="mb-4">
            <div class="text-4xl font-light text-black">{{ $stats['total_products'] }}</div>
        </div>
        <div class="text-xs text-gray-500 uppercase tracking-wider">Productos</div>
    </div>
    
    <!-- Total Pedidos -->
    <div class="bg-white border border-gray-200 hover:border-black transition-colors duration-200 p-8">
        <div class="mb-4">
            <div class="text-4xl font-light text-black">{{ $stats['total_orders'] }}</div>
        </div>
        <div class="text-xs text-gray-500 uppercase tracking-wider">Pedidos</div>
    </div>
    
    <!-- Reseñas Pendientes -->
    <div class="bg-white border border-gray-200 hover:border-black transition-colors duration-200 p-8">
        <div class="mb-4">
            <div class="text-4xl font-light text-black">{{ $stats['pending_reviews'] }}</div>
        </div>
        <div class="text-xs text-gray-500 uppercase tracking-wider">Reseñas</div>
    </div>
    
    <!-- Total Categorías -->
    <div class="bg-white border border-gray-200 hover:border-black transition-colors duration-200 p-8">
        <div class="mb-4">
            <div class="text-4xl font-light text-black">{{ $stats['total_categories'] }}</div>
        </div>
        <div class="text-xs text-gray-500 uppercase tracking-wider">Categorías</div>
    </div>
</div>

<!-- Recent Orders -->
<div class="mb-16">
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-light text-black tracking-tight">Pedidos Recientes</h2>
        <a href="{{ route('admin.orders.index') }}" class="text-sm text-gray-500 hover:text-black border-b border-gray-500 hover:border-black transition-colors duration-200">
            Ver todos
        </a>
    </div>
    
    <div class="border border-gray-200">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200">
                    <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">N° Pedido</th>
                    <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">Cliente</th>
                    <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">Total</th>
                    <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">Estado</th>
                    <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">Fecha</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recent_orders as $order)
                <tr class="border-b border-gray-100 last:border-0 hover:bg-gray-50 transition-colors duration-200">
                    <td class="px-6 py-4 text-sm text-black font-mono">{{ $order->order_number }}</td>
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
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $order->created_at->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-400">
                        No hay pedidos recientes
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Recent Users -->
<div class="mb-16">
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-light text-black tracking-tight">Usuarios Recientes</h2>
        <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500 hover:text-black border-b border-gray-500 hover:border-black transition-colors duration-200">
            Ver todos
        </a>
    </div>
    
    <div class="border border-gray-200">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200">
                    <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">Nombre</th>
                    <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">Email</th>
                    <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">Rol</th>
                    <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">Registro</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recent_users as $user)
                <tr class="border-b border-gray-100 last:border-0 hover:bg-gray-50 transition-colors duration-200">
                    <td class="px-6 py-4 text-sm text-black">{{ $user->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                    <td class="px-6 py-4">
                        @php
                            $roleClasses = match($user->role) {
                                'admin' => 'bg-black text-white border-black',
                                'nutritionist' => 'bg-blue-50 text-blue-900 border-blue-200',
                                'client' => 'bg-gray-50 text-gray-900 border-gray-200',
                                default => 'bg-gray-50 text-gray-900 border-gray-200'
                            };
                            $roleText = match($user->role) {
                                'admin' => 'Admin',
                                'nutritionist' => 'Nutricionista',
                                'client' => 'Cliente',
                                default => $user->role
                            };
                        @endphp
                        <span class="inline-block px-3 py-1 text-xs font-medium uppercase tracking-wider border {{ $roleClasses }}">
                            {{ $roleText }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $user->created_at->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-sm text-gray-400">
                        No hay usuarios recientes
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
