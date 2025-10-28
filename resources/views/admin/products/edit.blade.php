@extends('admin.layout')

@section('title', 'Editar Producto')

@section('content')

<!-- Breadcrumb -->
<div class="mb-8 text-sm text-gray-500">
    <a href="{{ route('admin.dashboard') }}" class="hover:text-black transition-colors duration-200">Dashboard</a>
    <span class="mx-2">/</span>
    <a href="{{ route('admin.products.index') }}" class="hover:text-black transition-colors duration-200">Productos</a>
    <span class="mx-2">/</span>
    <span class="text-black">{{ $product->name }}</span>
</div>

<!-- Page Header -->
<div class="mb-12">
    <h1 class="text-4xl font-light text-black tracking-tight mb-2">Editar Producto</h1>
    <p class="text-sm text-gray-500">Modificar información del producto</p>
</div>

<!-- Form -->
<div class="border border-gray-200 p-8 bg-white">
    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="space-y-8">
            <!-- Nombre -->
            <div>
                <label class="block text-sm font-medium text-black mb-2">Nombre del Producto *</label>
                <input type="text" name="name" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200" required value="{{ old('name', $product->name) }}">
                @error('name')<span class="block mt-1 text-xs text-red-600">{{ $message }}</span>@enderror
            </div>

            <!-- Categoría -->
            <div>
                <label class="block text-sm font-medium text-black mb-2">Categoría *</label>
                <select name="category_id" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200" required>
                    <option value="">Selecciona una categoría</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')<span class="block mt-1 text-xs text-red-600">{{ $message }}</span>@enderror
            </div>

            <!-- Descripción -->
            <div>
                <label class="block text-sm font-medium text-black mb-2">Descripción *</label>
                <textarea name="description" rows="4" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200" required>{{ old('description', $product->description) }}</textarea>
                @error('description')<span class="block mt-1 text-xs text-red-600">{{ $message }}</span>@enderror
            </div>

            <!-- Precios -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-black mb-2">Precio *</label>
                    <input type="number" name="price" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200" step="0.01" min="0" required value="{{ old('price', $product->price) }}">
                    @error('price')<span class="block mt-1 text-xs text-red-600">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-black mb-2">Precio de Comparación</label>
                    <input type="number" name="compare_price" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200" step="0.01" min="0" value="{{ old('compare_price', $product->compare_price) }}">
                    @error('compare_price')<span class="block mt-1 text-xs text-red-600">{{ $message }}</span>@enderror
                </div>
            </div>

            <!-- Stock y SKU -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-black mb-2">Stock *</label>
                    <input type="number" name="stock" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200" min="0" required value="{{ old('stock', $product->stock) }}">
                    @error('stock')<span class="block mt-1 text-xs text-red-600">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-black mb-2">SKU (Código)</label>
                    <input type="text" name="sku" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200" value="{{ old('sku', $product->sku) }}">
                    @error('sku')<span class="block mt-1 text-xs text-red-600">{{ $message }}</span>@enderror
                </div>
            </div>

            <!-- Peso -->
            <div>
                <label class="block text-sm font-medium text-black mb-2">Peso/Tamaño</label>
                <input type="text" name="weight" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200" placeholder="Ej: 500g, 1kg, 250ml" value="{{ old('weight', $product->weight) }}">
                @error('weight')<span class="block mt-1 text-xs text-red-600">{{ $message }}</span>@enderror
            </div>

            <!-- Beneficios -->
            <div>
                <label class="block text-sm font-medium text-black mb-2">Beneficios</label>
                <textarea name="benefits" rows="3" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200">{{ old('benefits', $product->benefits) }}</textarea>
                @error('benefits')<span class="block mt-1 text-xs text-red-600">{{ $message }}</span>@enderror
            </div>

            <!-- Ingredientes -->
            <div>
                <label class="block text-sm font-medium text-black mb-2">Ingredientes</label>
                <textarea name="ingredients" rows="3" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200">{{ old('ingredients', $product->ingredients) }}</textarea>
                @error('ingredients')<span class="block mt-1 text-xs text-red-600">{{ $message }}</span>@enderror
            </div>

            <!-- Imágenes actuales -->
            @if($product->images && count($product->images) > 0)
            <div>
                <label class="block text-sm font-medium text-black mb-4">Imágenes Actuales</label>
                <div class="grid grid-cols-4 gap-4">
                    @foreach($product->images as $image)
                    <div class="relative group">
                        <img src="{{ asset('storage/' . $image) }}" alt="{{ $product->name }}" class="w-full h-32 object-cover border border-gray-200">
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Nuevas imágenes -->
            <div>
                <label class="block text-sm font-medium text-black mb-2">Nuevas Imágenes</label>
                <input type="file" name="images[]" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200" multiple accept="image/*">
                <p class="mt-1 text-xs text-gray-500">Deja vacío para mantener las imágenes actuales</p>
                @error('images')<span class="block mt-1 text-xs text-red-600">{{ $message }}</span>@enderror
            </div>

            <!-- Opciones -->
            <div class="space-y-3">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} class="w-4 h-4 border-gray-300">
                    <span class="text-sm text-black">Producto destacado</span>
                </label>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="w-4 h-4 border-gray-300">
                    <span class="text-sm text-black">Producto activo</span>
                </label>
            </div>
        </div>

        <!-- Botones -->
        <div class="flex items-center gap-3 mt-12 pt-8 border-t border-gray-200">
            <button type="submit" class="bg-black text-white hover:bg-gray-900 px-8 py-3 text-sm font-medium transition-colors duration-200">
                Actualizar Producto
            </button>
            <a href="{{ route('admin.products.index') }}" class="bg-white text-black hover:bg-gray-50 px-8 py-3 text-sm font-medium border border-black transition-colors duration-200">
                Cancelar
            </a>
            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirmDelete('¿Eliminar este producto permanentemente?')" class="ml-auto">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white hover:bg-red-700 px-8 py-3 text-sm font-medium transition-colors duration-200">
                    Eliminar Producto
                </button>
            </form>
        </div>
    </form>
</div>
@endsection
