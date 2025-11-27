@extends('admin.layout')

@section('title', 'Crear Producto')

@section('content')

<!-- Breadcrumb -->
<div class="mb-8 text-sm text-gray-500">
    <a href="{{ route('admin.dashboard') }}" class="hover:text-black transition-colors duration-200">Dashboard</a>
    <span class="mx-2">/</span>
    <a href="{{ route('admin.products.index') }}" class="hover:text-black transition-colors duration-200">Productos</a>
    <span class="mx-2">/</span>
    <span class="text-black">Nuevo</span>
</div>

<!-- Page Header -->
<div class="mb-12">
    <h1 class="text-4xl font-light text-black tracking-tight mb-2">Crear Producto</h1>
    <p class="text-sm text-gray-500">Agrega un nuevo producto a la tienda</p>
</div>

<!-- Form -->
<div class="border border-gray-200 p-8 bg-white">
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="space-y-8">
            <!-- Nombre -->
            <div>
                <label class="block text-sm font-medium text-black mb-2">Nombre del Producto *</label>
                <input type="text" name="name" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200" required value="{{ old('name') }}">
                @error('name')<span class="block mt-1 text-xs text-red-600">{{ $message }}</span>@enderror
            </div>

            <!-- Categoría -->
            <div>
                <label class="block text-sm font-medium text-black mb-2">Categoría *</label>
                <select name="category_id" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200" required>
                    <option value="">Selecciona una categoría</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')<span class="block mt-1 text-xs text-red-600">{{ $message }}</span>@enderror
            </div>

            <!-- Descripción -->
            <div>
                <label class="block text-sm font-medium text-black mb-2">Descripción *</label>
                <textarea name="description" rows="4" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200" required>{{ old('description') }}</textarea>
                @error('description')<span class="block mt-1 text-xs text-red-600">{{ $message }}</span>@enderror
            </div>

            <!-- Precios -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-black mb-2">Precio *</label>
                    <input type="number" name="price" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200" step="0.01" min="0" required value="{{ old('price') }}">
                    @error('price')<span class="block mt-1 text-xs text-red-600">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-black mb-2">Precio de Comparación</label>
                    <input type="number" name="compare_price" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200" step="0.01" min="0" value="{{ old('compare_price') }}">
                    @error('compare_price')<span class="block mt-1 text-xs text-red-600">{{ $message }}</span>@enderror
                </div>
            </div>

            <!-- Stock y SKU -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-black mb-2">Stock *</label>
                    <input type="number" name="stock" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200" min="0" required value="{{ old('stock', 0) }}">
                    @error('stock')<span class="block mt-1 text-xs text-red-600">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-black mb-2">SKU (Código)</label>
                    <input type="text" name="sku" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200" value="{{ old('sku') }}">
                    @error('sku')<span class="block mt-1 text-xs text-red-600">{{ $message }}</span>@enderror
                </div>
            </div>

            <!-- Peso -->
            <div>
                <label class="block text-sm font-medium text-black mb-2">Peso/Tamaño</label>
                <input type="text" name="weight" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200" placeholder="Ej: 500g, 1kg, 250ml" value="{{ old('weight') }}">
                @error('weight')<span class="block mt-1 text-xs text-red-600">{{ $message }}</span>@enderror
            </div>

            <!-- Beneficios -->
            <div>
                <label class="block text-sm font-medium text-black mb-2">Beneficios</label>
                <textarea name="benefits" rows="3" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200">{{ old('benefits') }}</textarea>
                @error('benefits')<span class="block mt-1 text-xs text-red-600">{{ $message }}</span>@enderror
            </div>

            <!-- Ingredientes -->
            <div>
                <label class="block text-sm font-medium text-black mb-2">Ingredientes</label>
                <textarea name="ingredients" rows="3" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200">{{ old('ingredients') }}</textarea>
                @error('ingredients')<span class="block mt-1 text-xs text-red-600">{{ $message }}</span>@enderror
            </div>

            <!-- Imágenes -->
            <div>
                <label class="block text-sm font-medium text-black mb-2">Imágenes del Producto</label>
                <input type="file" name="images[]" id="imageInput" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200" multiple accept="image/*">
                <p class="mt-1 text-xs text-gray-500">Puedes seleccionar múltiples imágenes</p>
                @error('images')<span class="block mt-1 text-xs text-red-600">{{ $message }}</span>@enderror
                
                <!-- Contenedor de previsualización -->
                <div id="imagePreviewContainer" class="mt-4 hidden">
                    <p class="text-sm font-medium text-black mb-2">Imágenes seleccionadas:</p>
                    <div id="imagePreviewGrid" class="grid grid-cols-4 gap-4"></div>
                </div>
            </div>

            <!-- Opciones -->
            <div class="space-y-3">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="w-4 h-4 border-gray-300">
                    <span class="text-sm text-black">Producto destacado</span>
                </label>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked {{ old('is_active', true) ? 'checked' : '' }} class="w-4 h-4 border-gray-300">
                    <span class="text-sm text-black">Producto activo</span>
                </label>
            </div>
        </div>

        <!-- Botones -->
        <div class="flex items-center gap-3 mt-12 pt-8 border-t border-gray-200">
            <button type="submit" class="bg-black text-white hover:bg-gray-900 px-8 py-3 text-sm font-medium transition-colors duration-200">
                Crear Producto
            </button>
            <a href="{{ route('admin.products.index') }}" class="bg-white text-black hover:bg-gray-50 px-8 py-3 text-sm font-medium border border-black transition-colors duration-200">
                Cancelar
            </a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('imageInput');
    const previewContainer = document.getElementById('imagePreviewContainer');
    const previewGrid = document.getElementById('imagePreviewGrid');
    let selectedFiles = [];

    imageInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        
        if (files.length > 0) {
            selectedFiles = files;
            updatePreview();
            previewContainer.classList.remove('hidden');
        } else {
            previewContainer.classList.add('hidden');
        }
    });

    function updatePreview() {
        previewGrid.innerHTML = '';
        
        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();
            
            reader.onload = function(event) {
                const div = document.createElement('div');
                div.className = 'relative group';
                div.innerHTML = `
                    <img src="${event.target.result}" alt="Preview ${index + 1}" class="w-full h-32 object-cover border border-gray-200">
                    <button type="button" 
                            onclick="removePreviewImage(${index})"
                            class="absolute top-2 right-2 bg-red-600 text-white p-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200 hover:bg-red-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                    <div class="absolute bottom-2 left-2 bg-black bg-opacity-70 text-white px-2 py-1 text-xs">
                        ${file.name}
                    </div>
                `;
                previewGrid.appendChild(div);
            };
            
            reader.readAsDataURL(file);
        });
    }

    window.removePreviewImage = function(index) {
        selectedFiles.splice(index, 1);
        
        // Actualizar el input file
        const dataTransfer = new DataTransfer();
        selectedFiles.forEach(file => {
            dataTransfer.items.add(file);
        });
        imageInput.files = dataTransfer.files;
        
        if (selectedFiles.length === 0) {
            previewContainer.classList.add('hidden');
        } else {
            updatePreview();
        }
    };
});
</script>

@endsection
