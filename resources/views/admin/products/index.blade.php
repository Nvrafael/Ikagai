@extends('admin.layout')

@section('title', 'Productos')

@section('content')

<!-- Page Header -->
<div class="mb-12 flex items-center justify-between">
    <div>
        <h1 class="text-4xl font-light text-black tracking-tight mb-2">Productos</h1>
        <p class="text-sm text-gray-500">Gestión de productos de la tienda</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="bg-black text-white hover:bg-gray-900 px-6 py-3 text-sm font-medium transition-colors duration-200">
        + Nuevo Producto
    </a>
</div>

<!-- Table -->
<div class="border border-gray-200">
    <table class="w-full">
        <thead>
            <tr class="border-b border-gray-200">
                <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">ID</th>
                <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">Imagen</th>
                <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">Nombre</th>
                <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">Categoría</th>
                <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">Precio</th>
                <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">Stock</th>
                <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">Estado</th>
                <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr class="border-b border-gray-100 last:border-0 hover:bg-gray-50 transition-colors duration-200">
                <td class="px-6 py-4 text-sm text-gray-500 font-mono">{{ $product->id }}</td>
                <td class="px-6 py-4">
                    @if($product->images && count($product->images) > 0)
                        <img src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}" class="w-12 h-12 object-cover bg-gray-50">
                    @else
                        <div class="w-12 h-12 bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-400 text-xs">
                            Sin imagen
                        </div>
                    @endif
                </td>
                <td class="px-6 py-4 text-sm text-black font-medium">{{ $product->name }}</td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ $product->category->name ?? 'Sin categoría' }}</td>
                <td class="px-6 py-4 text-sm text-black font-medium">${{ number_format($product->price, 2) }}</td>
                <td class="px-6 py-4">
                    <span class="inline-block px-3 py-1 text-xs font-medium uppercase tracking-wider border {{ $product->stock > 10 ? 'bg-green-50 text-green-900 border-green-200' : ($product->stock > 0 ? 'bg-yellow-50 text-yellow-900 border-yellow-200' : 'bg-red-50 text-red-900 border-red-200') }}">
                        {{ $product->stock }} unidades
                    </span>
                </td>
                <td class="px-6 py-4">
                    <span class="inline-block px-3 py-1 text-xs font-medium uppercase tracking-wider border {{ $product->is_active ? 'bg-green-50 text-green-900 border-green-200' : 'bg-red-50 text-red-900 border-red-200' }}">
                        {{ $product->is_active ? 'Activo' : 'Inactivo' }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.products.edit', $product) }}" class="text-sm text-gray-600 hover:text-black border-b border-gray-600 hover:border-black transition-colors duration-200">
                            Editar
                        </a>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirmDelete('¿Eliminar este producto?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm text-red-600 hover:text-red-900 border-b border-red-600 hover:border-red-900 transition-colors duration-200">
                                Eliminar
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-6 py-16 text-center">
                    <p class="text-sm text-gray-400 mb-2">No hay productos registrados</p>
                    <a href="{{ route('admin.products.create') }}" class="text-sm text-black hover:text-gray-600 border-b border-black hover:border-gray-600 transition-colors duration-200">
                        Crear el primer producto
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if($products->hasPages())
<div class="mt-8">
    {{ $products->links() }}
</div>
@endif

@endsection
