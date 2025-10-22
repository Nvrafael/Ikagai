@extends('admin.layout')

@section('title', 'Editar Producto')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Dashboard</a> / 
    <a href="{{ route('admin.products.index') }}">Productos</a> / 
    Editar: {{ $product->name }}
</div>

<div class="page-header">
    <h1 class="page-title">Editar Producto</h1>
</div>

<div class="card">
    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label class="form-label">Nombre del Producto *</label>
            <input type="text" name="name" class="form-input" required value="{{ old('name', $product->name) }}">
            @error('name')<span style="color: #e53e3e; font-size: 13px;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Categoría *</label>
            <select name="category_id" class="form-select" required>
                <option value="">Selecciona una categoría</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')<span style="color: #e53e3e; font-size: 13px;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Descripción *</label>
            <textarea name="description" class="form-textarea" required>{{ old('description', $product->description) }}</textarea>
            @error('description')<span style="color: #e53e3e; font-size: 13px;">{{ $message }}</span>@enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label class="form-label">Precio *</label>
                <input type="number" name="price" class="form-input" step="0.01" min="0" required value="{{ old('price', $product->price) }}">
                @error('price')<span style="color: #e53e3e; font-size: 13px;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Precio de Comparación</label>
                <input type="number" name="compare_price" class="form-input" step="0.01" min="0" value="{{ old('compare_price', $product->compare_price) }}">
                @error('compare_price')<span style="color: #e53e3e; font-size: 13px;">{{ $message }}</span>@enderror
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label class="form-label">Stock *</label>
                <input type="number" name="stock" class="form-input" min="0" required value="{{ old('stock', $product->stock) }}">
                @error('stock')<span style="color: #e53e3e; font-size: 13px;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">SKU (Código)</label>
                <input type="text" name="sku" class="form-input" value="{{ old('sku', $product->sku) }}">
                @error('sku')<span style="color: #e53e3e; font-size: 13px;">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Peso/Tamaño</label>
            <input type="text" name="weight" class="form-input" placeholder="Ej: 500g, 1kg, 250ml" value="{{ old('weight', $product->weight) }}">
            @error('weight')<span style="color: #e53e3e; font-size: 13px;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Beneficios</label>
            <textarea name="benefits" class="form-textarea">{{ old('benefits', $product->benefits) }}</textarea>
            @error('benefits')<span style="color: #e53e3e; font-size: 13px;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Ingredientes</label>
            <textarea name="ingredients" class="form-textarea">{{ old('ingredients', $product->ingredients) }}</textarea>
            @error('ingredients')<span style="color: #e53e3e; font-size: 13px;">{{ $message }}</span>@enderror
        </div>

        @if($product->images && count($product->images) > 0)
        <div class="form-group">
            <label class="form-label">Imágenes Actuales</label>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                @foreach($product->images as $image)
                <img src="{{ asset('storage/' . $image) }}" alt="Imagen" style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px; border: 2px solid #e2e8f0;">
                @endforeach
            </div>
        </div>
        @endif

        <div class="form-group">
            <label class="form-label">Agregar Nuevas Imágenes</label>
            <input type="file" name="images[]" class="form-input" multiple accept="image/*">
            <small style="color: #718096;">Las nuevas imágenes se agregarán a las existentes</small>
            @error('images')<span style="color: #e53e3e; font-size: 13px;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                <span>Producto destacado</span>
            </label>
        </div>

        <div class="form-group">
            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                <span>Producto activo</span>
            </label>
        </div>

        <div style="display: flex; gap: 10px; margin-top: 30px;">
            <button type="submit" class="btn btn-primary">Actualizar Producto</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection

