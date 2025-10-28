@extends('admin.layout')

@section('title', 'Categorías')

@section('content')

<!-- Page Header -->
<div class="mb-12 flex items-center justify-between">
    <div>
        <h1 class="text-4xl font-light text-black tracking-tight mb-2">Categorías</h1>
        <p class="text-sm text-gray-500">Gestión de categorías de productos</p>
    </div>
    <button onclick="toggleModal('createCategoryModal')" class="bg-black text-white hover:bg-gray-900 px-6 py-3 text-sm font-medium transition-colors duration-200">
        + Nueva Categoría
    </button>
</div>

<!-- Table -->
<div class="border border-gray-200">
    <table class="w-full">
        <thead>
            <tr class="border-b border-gray-200">
                <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">ID</th>
                <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">Nombre</th>
                <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">Slug</th>
                <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">Productos</th>
                <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">Estado</th>
                <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
            <tr class="border-b border-gray-100 last:border-0 hover:bg-gray-50 transition-colors duration-200">
                <td class="px-6 py-4 text-sm text-gray-500 font-mono">{{ $category->id }}</td>
                <td class="px-6 py-4 text-sm text-black font-medium">{{ $category->name }}</td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ $category->slug }}</td>
                <td class="px-6 py-4">
                    <span class="inline-block px-3 py-1 text-xs font-medium uppercase tracking-wider border bg-blue-50 text-blue-900 border-blue-200">
                        {{ $category->products_count }} productos
                    </span>
                </td>
                <td class="px-6 py-4">
                    <span class="inline-block px-3 py-1 text-xs font-medium uppercase tracking-wider border {{ $category->is_active ? 'bg-green-50 text-green-900 border-green-200' : 'bg-red-50 text-red-900 border-red-200' }}">
                        {{ $category->is_active ? 'Activa' : 'Inactiva' }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-2">
                        <button onclick="editCategory({{ $category->id }}, '{{ $category->name }}', '{{ addslashes($category->description) }}', {{ $category->is_active ? 'true' : 'false' }})" class="text-sm text-gray-600 hover:text-black border-b border-gray-600 hover:border-black transition-colors duration-200">
                            Editar
                        </button>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirmDelete('¿Eliminar esta categoría? Los productos asociados quedarán sin categoría.')">
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
                <td colspan="6" class="px-6 py-16 text-center">
                    <p class="text-sm text-gray-400 mb-2">No hay categorías registradas</p>
                    <button onclick="toggleModal('createCategoryModal')" class="text-sm text-black hover:text-gray-600 border-b border-black hover:border-gray-600 transition-colors duration-200">
                        Crear la primera categoría
                    </button>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if($categories->hasPages())
<div class="mt-8">
    {{ $categories->links() }}
</div>
@endif

<!-- Modal Crear Categoría -->
<div id="createCategoryModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg">
        <div class="p-8">
            <h2 class="text-2xl font-light text-black mb-6 tracking-tight">Nueva Categoría</h2>
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-black mb-2">Nombre *</label>
                        <input type="text" name="name" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-black mb-2">Descripción</label>
                        <textarea name="description" rows="3" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-black mb-2">Imagen</label>
                        <input type="file" name="image" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200" accept="image/*">
                    </div>
                    <div>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 border-gray-300">
                            <span class="text-sm text-black">Categoría activa</span>
                        </label>
                    </div>
                </div>
                <div class="flex items-center gap-3 mt-8">
                    <button type="button" onclick="toggleModal('createCategoryModal')" class="flex-1 bg-white text-black hover:bg-gray-50 px-6 py-3 text-sm font-medium border border-black transition-colors duration-200">
                        Cancelar
                    </button>
                    <button type="submit" class="flex-1 bg-black text-white hover:bg-gray-900 px-6 py-3 text-sm font-medium transition-colors duration-200">
                        Crear
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Categoría -->
<div id="editCategoryModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg">
        <div class="p-8">
            <h2 class="text-2xl font-light text-black mb-6 tracking-tight">Editar Categoría</h2>
            <form id="editCategoryForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-black mb-2">Nombre *</label>
                        <input type="text" name="name" id="edit_name" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-black mb-2">Descripción</label>
                        <textarea name="description" id="edit_description" rows="3" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-black mb-2">Imagen</label>
                        <input type="file" name="image" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200" accept="image/*">
                    </div>
                    <div>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" id="edit_is_active" class="w-4 h-4 border-gray-300">
                            <span class="text-sm text-black">Categoría activa</span>
                        </label>
                    </div>
                </div>
                <div class="flex items-center gap-3 mt-8">
                    <button type="button" onclick="toggleModal('editCategoryModal')" class="flex-1 bg-white text-black hover:bg-gray-50 px-6 py-3 text-sm font-medium border border-black transition-colors duration-200">
                        Cancelar
                    </button>
                    <button type="submit" class="flex-1 bg-black text-white hover:bg-gray-900 px-6 py-3 text-sm font-medium transition-colors duration-200">
                        Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function toggleModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.toggle('hidden');
    }
}

function editCategory(id, name, description, isActive) {
    document.getElementById('editCategoryForm').action = `/admin/categorias/${id}`;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_description').value = description || '';
    document.getElementById('edit_is_active').checked = isActive;
    toggleModal('editCategoryModal');
}

// Cerrar modal al hacer clic fuera
document.addEventListener('DOMContentLoaded', function() {
    const modals = document.querySelectorAll('[id$="Modal"]');
    modals.forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
    });
});
</script>
@endsection
