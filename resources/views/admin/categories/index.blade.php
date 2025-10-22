@extends('admin.layout')

@section('title', 'Gestión de Categorías')

@section('content')
<div class="page-header">
    <h1 class="page-title">Categorías</h1>
    <button onclick="toggleModal('createCategoryModal')" class="btn btn-primary">+ Nueva Categoría</button>
</div>

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Slug</th>
                    <th>Productos</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->slug }}</td>
                    <td>
                        <span class="badge badge-info">{{ $category->products_count }} productos</span>
                    </td>
                    <td>
                        <span class="badge {{ $category->is_active ? 'badge-success' : 'badge-danger' }}">
                            {{ $category->is_active ? 'Activa' : 'Inactiva' }}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button onclick="editCategory({{ $category->id }}, '{{ $category->name }}', '{{ $category->description }}', {{ $category->is_active ? 'true' : 'false' }})" class="btn btn-secondary btn-small">Editar</button>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display: inline;" onsubmit="return confirmDelete('¿Eliminar esta categoría? Los productos asociados se eliminarán.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-small">Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 40px; color: #718096;">
                        No hay categorías registradas. 
                        <a href="#" onclick="toggleModal('createCategoryModal')" style="color: #e53e3e;">Crear la primera</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($categories->hasPages())
    <div style="padding: 20px;">
        {{ $categories->links() }}
    </div>
    @endif
</div>

<!-- Modal Crear Categoría -->
<div id="createCategoryModal" class="modal">
    <div class="modal-content">
        <h2 class="modal-header">Nueva Categoría</h2>
        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="form-label">Nombre *</label>
                <input type="text" name="name" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label">Descripción</label>
                <textarea name="description" class="form-textarea"></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Imagen</label>
                <input type="file" name="image" class="form-input" accept="image/*">
            </div>
            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" checked>
                    <span>Categoría activa</span>
                </label>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="toggleModal('createCategoryModal')" class="btn btn-secondary">Cancelar</button>
                <button type="submit" class="btn btn-primary">Crear</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Editar Categoría -->
<div id="editCategoryModal" class="modal">
    <div class="modal-content">
        <h2 class="modal-header">Editar Categoría</h2>
        <form id="editCategoryForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Nombre *</label>
                <input type="text" name="name" id="edit_name" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label">Descripción</label>
                <textarea name="description" id="edit_description" class="form-textarea"></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Imagen</label>
                <input type="file" name="image" class="form-input" accept="image/*">
            </div>
            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" id="edit_is_active">
                    <span>Categoría activa</span>
                </label>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="toggleModal('editCategoryModal')" class="btn btn-secondary">Cancelar</button>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function editCategory(id, name, description, isActive) {
    document.getElementById('editCategoryForm').action = `/admin/categorias/${id}`;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_description').value = description || '';
    document.getElementById('edit_is_active').checked = isActive;
    toggleModal('editCategoryModal');
}
</script>
@endsection

