@extends('admin.layout')

@section('title', 'Editar Recurso')

@section('content')

<!-- Breadcrumb -->
<div class="mb-8 text-sm text-gray-500">
    <a href="{{ route('admin.dashboard') }}" class="hover:text-black transition-colors duration-200">Dashboard</a>
    <span class="mx-2">/</span>
    <a href="{{ route('admin.recursos.index') }}" class="hover:text-black transition-colors duration-200">Recursos</a>
    <span class="mx-2">/</span>
    <span class="text-black">Editar</span>
</div>

<!-- Page Header -->
<div class="mb-12">
    <h1 class="text-4xl font-light text-black tracking-tight mb-2">Editar Recurso</h1>
    <p class="text-sm text-gray-500">{{ $resource->title }}</p>
</div>

<!-- Form -->
<div class="border border-gray-200 p-8 bg-white">
    <form action="{{ route('admin.recursos.update', $resource->id) }}" method="POST" enctype="multipart/form-data" id="resourceForm">
        @csrf
        @method('PUT')
        
        <div class="space-y-8">
            <!-- Título -->
            <div>
                <label class="block text-sm font-medium text-black mb-2">Título *</label>
                <input type="text" name="title" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200" required value="{{ old('title', $resource->title) }}" placeholder="Título del recurso">
                @error('title')<span class="block mt-1 text-xs text-red-600">{{ $message }}</span>@enderror
            </div>

            <!-- Tipo -->
            <div>
                <label class="block text-sm font-medium text-black mb-2">Tipo de Recurso *</label>
                <select name="type" id="resourceType" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200" required onchange="toggleMetadata()">
                    <option value="">Selecciona un tipo</option>
                    <option value="receta" {{ old('type', $resource->type) == 'receta' ? 'selected' : '' }}>Receta</option>
                    <option value="consejo" {{ old('type', $resource->type) == 'consejo' ? 'selected' : '' }}>Consejo</option>
                    <option value="articulo" {{ old('type', $resource->type) == 'articulo' ? 'selected' : '' }}>Artículo</option>
                    <option value="guia" {{ old('type', $resource->type) == 'guia' ? 'selected' : '' }}>Guía</option>
                </select>
                @error('type')<span class="block mt-1 text-xs text-red-600">{{ $message }}</span>@enderror
            </div>

            <!-- Extracto -->
            <div>
                <label class="block text-sm font-medium text-black mb-2">Extracto</label>
                <textarea name="excerpt" rows="3" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200" placeholder="Breve descripción del recurso (máximo 500 caracteres)">{{ old('excerpt', $resource->excerpt) }}</textarea>
                <p class="mt-1 text-xs text-gray-500">Resumen corto que aparecerá en las tarjetas de vista previa</p>
                @error('excerpt')<span class="block mt-1 text-xs text-red-600">{{ $message }}</span>@enderror
            </div>

            <!-- Contenido -->
            <div>
                <label class="block text-sm font-medium text-black mb-2">Contenido *</label>
                <textarea name="content" rows="15" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200 font-mono text-sm" required placeholder="Contenido completo del recurso (acepta HTML)">{{ old('content', $resource->content) }}</textarea>
                <p class="mt-1 text-xs text-gray-500">Puedes usar HTML para formato: &lt;h2&gt;, &lt;h3&gt;, &lt;p&gt;, &lt;ul&gt;, &lt;ol&gt;, &lt;strong&gt;, etc.</p>
                @error('content')<span class="block mt-1 text-xs text-red-600">{{ $message }}</span>@enderror
            </div>

            <!-- Metadatos para Recetas (condicional) -->
            <div id="recetaMetadata" style="display: none;" class="border border-gray-200 p-6 bg-gray-50">
                <h3 class="text-lg font-medium text-black mb-4">Información de la Receta</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-black mb-2">Tiempo de Preparación</label>
                        <input type="text" name="metadata[tiempo_preparacion]" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200" value="{{ old('metadata.tiempo_preparacion', $resource->metadata['tiempo_preparacion'] ?? '') }}" placeholder="Ej: 30 minutos">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-black mb-2">Porciones</label>
                        <input type="text" name="metadata[porciones]" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200" value="{{ old('metadata.porciones', $resource->metadata['porciones'] ?? '') }}" placeholder="Ej: 4 personas">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-black mb-2">Dificultad</label>
                        <select name="metadata[dificultad]" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200">
                            <option value="">Seleccionar</option>
                            <option value="facil" {{ old('metadata.dificultad', $resource->metadata['dificultad'] ?? '') == 'facil' ? 'selected' : '' }}>Fácil</option>
                            <option value="media" {{ old('metadata.dificultad', $resource->metadata['dificultad'] ?? '') == 'media' ? 'selected' : '' }}>Media</option>
                            <option value="dificil" {{ old('metadata.dificultad', $resource->metadata['dificultad'] ?? '') == 'dificil' ? 'selected' : '' }}>Difícil</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-black mb-2">Calorías</label>
                        <input type="text" name="metadata[calorias]" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200" value="{{ old('metadata.calorias', $resource->metadata['calorias'] ?? '') }}" placeholder="Ej: 350 kcal por porción">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-black mb-2">Ingredientes</label>
                    <textarea name="metadata[ingredientes]" rows="8" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200" placeholder="Lista de ingredientes (uno por línea)">{{ old('metadata.ingredientes', $resource->metadata['ingredientes'] ?? '') }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">Escribe cada ingrediente en una línea nueva</p>
                </div>
            </div>

            <!-- Imagen Destacada Actual -->
            @if($resource->featured_image)
                <div>
                    <label class="block text-sm font-medium text-black mb-2">Imagen Actual</label>
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $resource->featured_image) }}" alt="{{ $resource->title }}" class="w-48 h-48 object-cover border border-gray-200">
                    </div>
                </div>
            @endif

            <!-- Nueva Imagen Destacada -->
            <div>
                <label class="block text-sm font-medium text-black mb-2">{{ $resource->featured_image ? 'Cambiar Imagen Destacada' : 'Imagen Destacada' }}</label>
                <input type="file" name="featured_image" accept="image/*" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200">
                <p class="mt-1 text-xs text-gray-500">Formatos: JPG, PNG, WebP. Tamaño máximo: 2MB. Deja vacío para mantener la imagen actual.</p>
                @error('featured_image')<span class="block mt-1 text-xs text-red-600">{{ $message }}</span>@enderror
            </div>

            <!-- Archivo Descargable (solo para guías) -->
            <div id="downloadableFile" style="display: none;">
                @if($resource->download_file)
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-black mb-2">Archivo Actual</label>
                        <a href="{{ route('resources.download', $resource->slug) }}" class="inline-flex items-center text-sm text-black hover:underline">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            {{ basename($resource->download_file) }}
                        </a>
                    </div>
                @endif
                <label class="block text-sm font-medium text-black mb-2">{{ $resource->download_file ? 'Cambiar Archivo Descargable' : 'Archivo Descargable (PDF)' }}</label>
                <input type="file" name="download_file" accept=".pdf,.doc,.docx" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200">
                <p class="mt-1 text-xs text-gray-500">Para guías: PDF, DOC, DOCX. Tamaño máximo: 10MB. Deja vacío para mantener el archivo actual.</p>
                @error('download_file')<span class="block mt-1 text-xs text-red-600">{{ $message }}</span>@enderror
            </div>

            <!-- Estado de Publicación -->
            <div class="border-t border-gray-200 pt-8">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_published" value="1" class="w-5 h-5 border-gray-300 text-black focus:ring-black" {{ old('is_published', $resource->is_published) ? 'checked' : '' }}>
                    <div>
                        <span class="text-sm font-medium text-black">Publicado</span>
                        <p class="text-xs text-gray-500">Si se desmarca, el recurso se guardará como borrador</p>
                    </div>
                </label>
            </div>

            <!-- Estadísticas -->
            <div class="border-t border-gray-200 pt-8">
                <h3 class="text-sm font-medium text-black mb-4">Estadísticas</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-gray-50 p-4 border border-gray-200">
                        <div class="text-xs text-gray-500 mb-1">Vistas</div>
                        <div class="text-2xl font-light text-black">{{ $resource->views }}</div>
                    </div>
                    <div class="bg-gray-50 p-4 border border-gray-200">
                        <div class="text-xs text-gray-500 mb-1">Creado</div>
                        <div class="text-sm text-black">{{ $resource->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    <div class="bg-gray-50 p-4 border border-gray-200">
                        <div class="text-xs text-gray-500 mb-1">Última Actualización</div>
                        <div class="text-sm text-black">{{ $resource->updated_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones -->
        <div class="flex items-center justify-between gap-4 mt-12 pt-8 border-t border-gray-200">
            <a href="{{ route('admin.recursos.index') }}" class="text-sm text-gray-500 hover:text-black transition-colors duration-200">
                Cancelar
            </a>
            <div class="flex items-center gap-4">
                <a href="{{ route('resources.show', $resource->slug) }}" target="_blank" class="text-sm text-gray-500 hover:text-black transition-colors duration-200">
                    Ver Recurso
                </a>
                <button type="submit" class="bg-black text-white hover:bg-gray-900 px-8 py-3 text-sm font-medium transition-colors duration-200">
                    Guardar Cambios
                </button>
            </div>
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

// Ejecutar al cargar
document.addEventListener('DOMContentLoaded', function() {
    toggleMetadata();
});
</script>
@endsection

