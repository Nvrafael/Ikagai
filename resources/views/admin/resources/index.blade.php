@extends('admin.layout')

@section('title', 'Recursos')

@section('content')

<!-- Page Header -->
<div class="mb-12 flex items-center justify-between">
    <div>
        <h1 class="text-4xl font-light text-black tracking-tight mb-2">Recursos</h1>
        <p class="text-sm text-gray-500">Gestión de recetas, consejos, artículos y guías</p>
    </div>
    <a href="{{ route('admin.recursos.create') }}" class="bg-black text-white hover:bg-gray-900 px-6 py-3 text-sm font-medium transition-colors duration-200">
        + Nuevo Recurso
    </a>
</div>

<!-- Filters -->
<div class="mb-6 flex items-center gap-4">
    <div class="flex items-center gap-2">
        <label class="text-sm text-gray-600">Tipo:</label>
        <select onchange="window.location.href=this.value" class="px-4 py-2 border border-gray-200 text-sm focus:border-black focus:ring-0">
            <option value="{{ route('admin.recursos.index') }}" {{ !request('type') ? 'selected' : '' }}>Todos</option>
            <option value="{{ route('admin.recursos.index', ['type' => 'receta']) }}" {{ request('type') == 'receta' ? 'selected' : '' }}>Recetas</option>
            <option value="{{ route('admin.recursos.index', ['type' => 'consejo']) }}" {{ request('type') == 'consejo' ? 'selected' : '' }}>Consejos</option>
            <option value="{{ route('admin.recursos.index', ['type' => 'articulo']) }}" {{ request('type') == 'articulo' ? 'selected' : '' }}>Artículos</option>
            <option value="{{ route('admin.recursos.index', ['type' => 'guia']) }}" {{ request('type') == 'guia' ? 'selected' : '' }}>Guías</option>
        </select>
    </div>
    
    <div class="flex items-center gap-2">
        <label class="text-sm text-gray-600">Estado:</label>
        <select onchange="window.location.href=this.value" class="px-4 py-2 border border-gray-200 text-sm focus:border-black focus:ring-0">
            <option value="{{ route('admin.recursos.index', array_merge(request()->except('status'), [])) }}" {{ !request('status') ? 'selected' : '' }}>Todos</option>
            <option value="{{ route('admin.recursos.index', array_merge(request()->except('status'), ['status' => 'published'])) }}" {{ request('status') == 'published' ? 'selected' : '' }}>Publicados</option>
            <option value="{{ route('admin.recursos.index', array_merge(request()->except('status'), ['status' => 'draft'])) }}" {{ request('status') == 'draft' ? 'selected' : '' }}>Borradores</option>
        </select>
    </div>
</div>

<!-- Table -->
<div class="border border-gray-200">
    <table class="w-full">
        <thead>
            <tr class="border-b border-gray-200">
                <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">ID</th>
                <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">Título</th>
                <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">Tipo</th>
                <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">Autor</th>
                <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">Vistas</th>
                <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">Estado</th>
                <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">Publicado</th>
                <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($resources as $resource)
            <tr class="border-b border-gray-100 last:border-0 hover:bg-gray-50 transition-colors duration-200">
                <td class="px-6 py-4 text-sm text-gray-500 font-mono">{{ $resource->id }}</td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        @if($resource->featured_image)
                            <img src="{{ asset('storage/' . $resource->featured_image) }}" alt="{{ $resource->title }}" class="w-12 h-12 object-cover bg-gray-50">
                        @else
                            <div class="w-12 h-12 bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-400 text-xs">
                                Sin imagen
                            </div>
                        @endif
                        <div>
                            <div class="text-sm text-black font-medium">{{ Str::limit($resource->title, 50) }}</div>
                            @if($resource->excerpt)
                                <div class="text-xs text-gray-500 mt-1">{{ Str::limit($resource->excerpt, 60) }}</div>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <span class="inline-block px-3 py-1 text-xs font-medium uppercase tracking-wider
                        @if($resource->type === 'receta') bg-green-100 text-green-900 border border-green-200
                        @elseif($resource->type === 'consejo') bg-blue-100 text-blue-900 border border-blue-200
                        @elseif($resource->type === 'articulo') bg-purple-100 text-purple-900 border border-purple-200
                        @else bg-orange-100 text-orange-900 border border-orange-200
                        @endif">
                        {{ $resource->type }}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ $resource->author->name }}</td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ $resource->views }}</td>
                <td class="px-6 py-4">
                    @if($resource->is_published)
                        <span class="inline-block px-3 py-1 text-xs font-medium uppercase tracking-wider bg-green-50 text-green-900 border border-green-200">
                            Publicado
                        </span>
                    @else
                        <span class="inline-block px-3 py-1 text-xs font-medium uppercase tracking-wider bg-gray-50 text-gray-900 border border-gray-200">
                            Borrador
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">
                    {{ $resource->published_at ? $resource->published_at->format('d/m/Y') : '-' }}
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('resources.show', $resource->slug) }}" target="_blank" class="text-sm text-gray-500 hover:text-black transition-colors" title="Ver">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </a>
                        <a href="{{ route('admin.recursos.edit', $resource->id) }}" class="text-sm text-gray-500 hover:text-black transition-colors" title="Editar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </a>
                        <form action="{{ route('admin.recursos.destroy', $resource->id) }}" method="POST" class="inline" onsubmit="return confirmDelete('¿Estás seguro de que deseas eliminar este recurso?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm text-red-500 hover:text-red-700 transition-colors" title="Eliminar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-6 py-12 text-center text-sm text-gray-500">
                    No hay recursos disponibles. 
                    <a href="{{ route('admin.recursos.create') }}" class="text-black hover:underline">Crear uno ahora</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if($resources->hasPages())
    <div class="mt-8 flex items-center justify-center">
        {{ $resources->links() }}
    </div>
@endif

@endsection

