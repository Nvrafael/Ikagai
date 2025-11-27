@extends('admin.layout')

@section('title', 'Crear Recurso')

@section('content')

<!-- Breadcrumb -->
<div class="mb-8 text-sm text-gray-500">
    <a href="{{ route('admin.dashboard') }}" class="hover:text-black transition-colors duration-200">Dashboard</a>
    <span class="mx-2">/</span>
    <a href="{{ route('admin.recursos.index') }}" class="hover:text-black transition-colors duration-200">Recursos</a>
    <span class="mx-2">/</span>
    <span class="text-black">Nuevo</span>
</div>

<!-- Page Header -->
<div class="mb-12">
    <h1 class="text-4xl font-light text-black tracking-tight mb-2">Crear Recurso</h1>
    <p class="text-sm text-gray-500">Agrega un nuevo recurso educativo</p>
</div>

<!-- Form -->
<div class="border border-gray-200 p-8 bg-white">
    <form action="{{ route('admin.recursos.store') }}" method="POST" enctype="multipart/form-data" id="resourceForm">
        @csrf
        
        <div class="space-y-8">
            <!-- Título -->
            <div>
                <label class="block text-sm font-medium text-black mb-2">Título *</label>
                <input type="text" name="title" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200" required value="{{ old('title') }}" placeholder="Título del recurso">
                @error('title')<span class="block mt-1 text-xs text-red-600">{{ $message }}</span>@enderror
            </div>

            <!-- Tipo -->
            <div>
                <label class="block text-sm font-medium text-black mb-2">Tipo de Recurso *</label>
                <select name="type" id="resourceType" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200" required onchange="toggleMetadata()">
                    <option value="">Selecciona un tipo</option>
                    <option value="receta" {{ old('type') == 'receta' ? 'selected' : '' }}>Receta</option>
                    <option value="consejo" {{ old('type') == 'consejo' ? 'selected' : '' }}>Consejo</option>
                    <option value="articulo" {{ old('type') == 'articulo' ? 'selected' : '' }}>Artículo</option>
                    <option value="guia" {{ old('type') == 'guia' ? 'selected' : '' }}>Guía</option>
                </select>
                @error('type')<span class="block mt-1 text-xs text-red-600">{{ $message }}</span>@enderror
            </div>

            <!-- Extracto -->
            <div>
                <label class="block text-sm font-medium text-black mb-2">Extracto</label>
                <textarea name="excerpt" rows="3" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200" placeholder="Breve descripción del recurso (máximo 500 caracteres)">{{ old('excerpt') }}</textarea>
                <p class="mt-1 text-xs text-gray-500">Resumen corto que aparecerá en las tarjetas de vista previa</p>
                @error('excerpt')<span class="block mt-1 text-xs text-red-600">{{ $message }}</span>@enderror
            </div>

            <!-- Contenido -->
            <div>
                <label class="block text-sm font-medium text-black mb-2">Contenido *</label>
                <textarea name="content" rows="15" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200 font-mono text-sm" required placeholder="Contenido completo del recurso (acepta HTML)">{{ old('content') }}</textarea>
                <p class="mt-1 text-xs text-gray-500">Puedes usar HTML para formato: &lt;h2&gt;, &lt;h3&gt;, &lt;p&gt;, &lt;ul&gt;, &lt;ol&gt;, &lt;strong&gt;, etc.</p>
                @error('content')<span class="block mt-1 text-xs text-red-600">{{ $message }}</span>@enderror
            </div>

            <!-- Metadatos para Recetas (condicional) -->
            <div id="recetaMetadata" style="display: none;" class="border border-gray-200 p-6 bg-gray-50">
                <h3 class="text-lg font-medium text-black mb-4">Información de la Receta</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-black mb-2">Tiempo de Preparación</label>
                        <input type="text" name="metadata[tiempo_preparacion]" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200" value="{{ old('metadata.tiempo_preparacion') }}" placeholder="Ej: 30 minutos">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-black mb-2">Porciones</label>
                        <input type="text" name="metadata[porciones]" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200" value="{{ old('metadata.porciones') }}" placeholder="Ej: 4 personas">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-black mb-2">Dificultad</label>
                        <select name="metadata[dificultad]" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200">
                            <option value="">Seleccionar</option>
                            <option value="facil" {{ old('metadata.dificultad') == 'facil' ? 'selected' : '' }}>Fácil</option>
                            <option value="media" {{ old('metadata.dificultad') == 'media' ? 'selected' : '' }}>Media</option>
                            <option value="dificil" {{ old('metadata.dificultad') == 'dificil' ? 'selected' : '' }}>Difícil</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-black mb-2">Calorías</label>
                        <input type="text" name="metadata[calorias]" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200" value="{{ old('metadata.calorias') }}" placeholder="Ej: 350 kcal por porción">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-black mb-2">Ingredientes</label>
                    <textarea name="metadata[ingredientes]" rows="8" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200" placeholder="Lista de ingredientes (uno por línea)">{{ old('metadata.ingredientes') }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">Escribe cada ingrediente en una línea nueva</p>
                </div>
            </div>

            <!-- Imagen Destacada -->
            <div>
                <label class="block text-sm font-medium text-black mb-2">Imagen Destacada</label>
                <input type="file" name="featured_image" accept="image/*" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200">
                <p class="mt-1 text-xs text-gray-500">Formatos: JPG, PNG, WebP. Tamaño máximo: 2MB</p>
                @error('featured_image')<span class="block mt-1 text-xs text-red-600">{{ $message }}</span>@enderror
            </div>

            <!-- Archivo Descargable (solo para guías) -->
            <div id="downloadableFile" style="display: none;">
                <label class="block text-sm font-medium text-black mb-2">Archivo Descargable (PDF)</label>
                <input type="file" name="download_file" accept=".pdf,.doc,.docx" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200">
                <p class="mt-1 text-xs text-gray-500">Para guías: PDF, DOC, DOCX. Tamaño máximo: 10MB</p>
                @error('download_file')<span class="block mt-1 text-xs text-red-600">{{ $message }}</span>@enderror
            </div>

            <!-- Estado de Publicación -->
            <div class="border-t border-gray-200 pt-8">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_published" value="1" class="w-5 h-5 border-gray-300 text-black focus:ring-black" {{ old('is_published') ? 'checked' : '' }}>
                    <div>
                        <span class="text-sm font-medium text-black">Publicar Inmediatamente</span>
                        <p class="text-xs text-gray-500">Si no se marca, se guardará como borrador</p>
                    </div>
                </label>
            </div>
        </div>

        <!-- Acciones -->
        <div class="flex items-center justify-between gap-4 mt-12 pt-8 border-t border-gray-200">
            <a href="{{ route('admin.recursos.index') }}" class="text-sm text-gray-500 hover:text-black transition-colors duration-200">
                Cancelar
            </a>
            <button type="submit" class="bg-black text-white hover:bg-gray-900 px-8 py-3 text-sm font-medium transition-colors duration-200">
                Crear Recurso
            </button>
        </div>
    </form>
</div>

@endsection

@section('scripts')
<script>
function toggleMetadata() {
    const type = document.getElementById('resourceType').value;
    const recetaMetadata = document.getElementById('recetaMetadata');
    const downloadableFile = document.getElementById('downloadableFile');
    
    // Mostrar metadatos solo para recetas
    if (type === 'receta') {
        recetaMetadata.style.display = 'block';
    } else {
        recetaMetadata.style.display = 'none';
    }
    
    // Mostrar archivo descargable solo para guías
    if (type === 'guia') {
        downloadableFile.style.display = 'block';
    } else {
        downloadableFile.style.display = 'none';
    }
}

// Ejecutar al cargar si hay un tipo seleccionado
document.addEventListener('DOMContentLoaded', function() {
    toggleMetadata();
});
</script>
@endsection

