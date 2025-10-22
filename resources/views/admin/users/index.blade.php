@extends('admin.layout')

@section('title', 'Gestión de Usuarios')

@section('content')
<div class="page-header">
    <h1 class="page-title">Usuarios</h1>
</div>

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Fecha de Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @php
                            $roleClass = match($user->role) {
                                'admin' => 'badge-danger',
                                'nutritionist' => 'badge-info',
                                'client' => 'badge-success',
                                default => 'badge-warning'
                            };
                            $roleText = match($user->role) {
                                'admin' => 'Administrador',
                                'nutritionist' => 'Nutricionista',
                                'client' => 'Cliente',
                                default => $user->role
                            };
                        @endphp
                        <span class="badge {{ $roleClass }}">{{ $roleText }}</span>
                    </td>
                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <div class="action-buttons">
                            <button onclick="editUserRole({{ $user->id }}, '{{ $user->name }}', '{{ $user->role }}')" class="btn btn-secondary btn-small">Cambiar Rol</button>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display: inline;" onsubmit="return confirmDelete('¿Eliminar este usuario?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-small">Eliminar</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    @if($users->hasPages())
    <div style="padding: 20px;">
        {{ $users->links() }}
    </div>
    @endif
</div>

<!-- Modal Editar Rol -->
<div id="editRoleModal" class="modal">
    <div class="modal-content">
        <h2 class="modal-header">Cambiar Rol de Usuario</h2>
        <form id="editRoleForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Usuario</label>
                <input type="text" id="edit_user_name" class="form-input" readonly style="background: #f7fafc;">
            </div>
            <div class="form-group">
                <label class="form-label">Nuevo Rol *</label>
                <select name="role" id="edit_role" class="form-select" required>
                    <option value="client">Cliente</option>
                    <option value="nutritionist">Nutricionista</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="toggleModal('editRoleModal')" class="btn btn-secondary">Cancelar</button>
                <button type="submit" class="btn btn-primary">Actualizar Rol</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function editUserRole(id, name, currentRole) {
    document.getElementById('editRoleForm').action = `/admin/usuarios/${id}/rol`;
    document.getElementById('edit_user_name').value = name;
    document.getElementById('edit_role').value = currentRole;
    toggleModal('editRoleModal');
}
</script>
@endsection

