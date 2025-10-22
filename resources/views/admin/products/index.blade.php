@extends('admin.layout')

@section('title', 'Gestión de Productos')

@section('content')
<div class="page-header">
    <h1 class="page-title">Productos</h1>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">+ Nuevo Producto</a>
</div>

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>
                        @if($product->images && count($product->images) > 0)
                            <img src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                        @else
                            <div style="width: 50px; height: 50px; background: #e2e8f0; border-radius: 5px; display: flex; align-items: center; justify-content: center;">📦</div>
                        @endif
                    </td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name }}</td>
                    <td>${{ number_format($product->price, 2) }}</td>
                    <td>
                        <span class="badge {{ $product->stock > 10 ? 'badge-success' : ($product->stock > 0 ? 'badge-warning' : 'badge-danger') }}">
                            {{ $product->stock }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $product->is_active ? 'badge-success' : 'badge-danger' }}">
                            {{ $product->is_active ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-secondary btn-small">Editar</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display: inline;" onsubmit="return confirmDelete('¿Eliminar este producto?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-small">Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 40px; color: #718096;">
                        No hay productos registrados. 
                        <a href="{{ route('admin.products.create') }}" style="color: #e53e3e;">Crear el primero</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($products->hasPages())
    <div style="padding: 20px;">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection

