@extends('admin.layout')

@section('title', 'Crear Producto')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Dashboard</a> / 
    <a href="{{ route('admin.products.index') }}">Productos</a> / 
    Nuevo Producto
</div>

<div class="page-header">
    <h1 class="page-title">Crear Nuevo Producto</h1>
</div>

<div class="card">
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="form-group">
            <label class="form-label">Nombre del Producto *</label>
            <input type="text" name="name" class="form-input" required value="{{ old('name') }}">
            @error('name')<span style="color: #e53e3e; font-size: 13px;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Categoría *</label>
            <select name="category_id" class="form-select" required>
                <option value="">Selecciona una categoría</option>
                @foreach(\App\Models\Category::all() as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')<span style="color: #e53e3e; font-size: 13px;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Descripción *</label>
            <textarea name="description" class="form-textarea" required>{{ old('description') }}</textarea>
            @error('description')<span style="color: #e53e3e; font-size: 13px;">{{ $message }}</span>@enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label class="form-label">Precio *</label>
                <input type="number" name="price" class="form-input" step="0.01" min="0" required value="{{ old('price') }}">
                @error('price')<span style="color: #e53e3e; font-size: 13px;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Precio de Comparación</label>
                <input type="number" name="compare_price" class="form-input" step="0.01" min="0" value="{{ old('compare_price') }}">
                @error('compare_price')<span style="color: #e53e3e; font-size: 13px;">{{ $message }}</span>@enderror
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label class="form-label">Stock *</label>
                <input type="number" name="stock" class="form-input" min="0" required value="{{ old('stock', 0) }}">
                @error('stock')<span style="color: #e53e3e; font-size: 13px;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">SKU (Código)</label>
                <input type="text" name="sku" class="form-input" value="{{ old('sku') }}">
                @error('sku')<span style="color: #e53e3e; font-size: 13px;">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Peso/Tamaño</label>
            <input type="text" name="weight" class="form-input" placeholder="Ej: 500g, 1kg, 250ml" value="{{ old('weight') }}">
            @error('weight')<span style="color: #e53e3e; font-size: 13px;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Beneficios</label>
            <textarea name="benefits" class="form-textarea">{{ old('benefits') }}</textarea>
            @error('benefits')<span style="color: #e53e3e; font-size: 13px;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Ingredientes</label>
            <textarea name="ingredients" class="form-textarea">{{ old('ingredients') }}</textarea>
            @error('ingredients')<span style="color: #e53e3e; font-size: 13px;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Imágenes del Producto</label>
            <input type="file" name="images[]" class="form-input" multiple accept="image/*">
            <small style="color: #718096;">Puedes seleccionar múltiples imágenes</small>
            @error('images')<span style="color: #e53e3e; font-size: 13px;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                <span>Producto destacado</span>
            </label>
        </div>

        <div class="form-group">
            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                <input type="checkbox" name="is_active" value="1" checked {{ old('is_active', true) ? 'checked' : '' }}>
                <span>Producto activo</span>
            </label>
        </div>

        <div style="display: flex; gap: 10px; margin-top: 30px;">
            <button type="submit" class="btn btn-primary">Crear Producto</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection

