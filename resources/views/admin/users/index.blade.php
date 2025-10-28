@extends('admin.layout')

@section('title', 'Usuarios')

@section('content')

<!-- Page Header -->
<div class="mb-12">
    <h1 class="text-4xl font-light text-black tracking-tight mb-2">Usuarios</h1>
    <p class="text-sm text-gray-500">Gestión de usuarios del sistema</p>
</div>

<!-- Table -->
<div class="border border-gray-200">
    <table class="w-full">
        <thead>
            <tr class="border-b border-gray-200">
                <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">ID</th>
                <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">Nombre</th>
                <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">Email</th>
                <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">Rol</th>
                <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">Registro</th>
                <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr class="border-b border-gray-100 last:border-0 hover:bg-gray-50 transition-colors duration-200">
                <td class="px-6 py-4 text-sm text-gray-500 font-mono">{{ $user->id }}</td>
                <td class="px-6 py-4 text-sm text-black font-medium">{{ $user->name }}</td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                <td class="px-6 py-4">
                    @php
                        $roleClasses = match($user->role) {
                            'admin' => 'bg-black text-white border-black',
                            'nutritionist' => 'bg-blue-50 text-blue-900 border-blue-200',
                            'client' => 'bg-gray-50 text-gray-900 border-gray-200',
                            default => 'bg-gray-50 text-gray-900 border-gray-200'
                        };
                        $roleText = match($user->role) {
                            'admin' => 'Admin',
                            'nutritionist' => 'Nutricionista',
                            'client' => 'Cliente',
                            default => $user->role
                        };
                    @endphp
                    <span class="inline-block px-3 py-1 text-xs font-medium uppercase tracking-wider border {{ $roleClasses }}">
                        {{ $roleText }}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-2">
                        <button onclick="editUserRole({{ $user->id }}, '{{ $user->name }}', '{{ $user->role }}')" class="text-sm text-gray-600 hover:text-black border-b border-gray-600 hover:border-black transition-colors duration-200">
                            Cambiar Rol
                        </button>
                        @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirmDelete('¿Eliminar este usuario?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm text-red-600 hover:text-red-900 border-b border-red-600 hover:border-red-900 transition-colors duration-200">
                                Eliminar
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if($users->hasPages())
<div class="mt-8">
    {{ $users->links() }}
</div>
@endif

<!-- Modal Editar Rol -->
<div id="editRoleModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg">
        <div class="p-8">
            <h2 class="text-2xl font-light text-black mb-6 tracking-tight">Cambiar Rol de Usuario</h2>
            <form id="editRoleForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-black mb-2">Usuario</label>
                        <input type="text" id="edit_user_name" class="w-full px-4 py-3 border border-gray-200 bg-gray-50 cursor-not-allowed" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-black mb-2">Nuevo Rol *</label>
                        <select name="role" id="edit_role" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition-colors duration-200" required>
                            <option value="client">Cliente</option>
                            <option value="nutritionist">Nutricionista</option>
                            <option value="admin">Administrador</option>
                        </select>
                    </div>
                </div>
                <div class="flex items-center gap-3 mt-8">
                    <button type="button" onclick="toggleModal('editRoleModal')" class="flex-1 bg-white text-black hover:bg-gray-50 px-6 py-3 text-sm font-medium border border-black transition-colors duration-200">
                        Cancelar
                    </button>
                    <button type="submit" class="flex-1 bg-black text-white hover:bg-gray-900 px-6 py-3 text-sm font-medium transition-colors duration-200">
                        Actualizar Rol
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

function editUserRole(id, name, currentRole) {
    document.getElementById('editRoleForm').action = `/admin/usuarios/${id}/rol`;
    document.getElementById('edit_user_name').value = name;
    document.getElementById('edit_role').value = currentRole;
    toggleModal('editRoleModal');
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
