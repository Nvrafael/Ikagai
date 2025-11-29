<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * Componente Livewire de Registro
 * 
 * Maneja el proceso de registro de nuevos usuarios en la aplicación.
 * Los nuevos usuarios se registran automáticamente con el rol de "cliente"
 * y son autenticados inmediatamente después del registro exitoso.
 * 
 * @package App\Livewire\Auth
 */
#[Layout('components.layouts.auth')]
class Register extends Component
{
    /**
     * Nombre completo del usuario.
     */
    public string $name = '';

    /**
     * Dirección de correo electrónico del usuario.
     */
    public string $email = '';

    /**
     * Contraseña del usuario.
     */
    public string $password = '';

    /**
     * Confirmación de la contraseña.
     */
    public string $password_confirmation = '';

    /**
     * Maneja una solicitud de registro entrante.
     * 
     * Valida los datos del formulario, crea el nuevo usuario con rol de cliente,
     * dispara el evento de registro, autentica automáticamente al usuario
     * y lo redirige a la página principal.
     * 
     * @return void
     */
    public function register(): void
    {
        // Validar datos del formulario de registro
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        // Hashear la contraseña para almacenarla de forma segura
        $validated['password'] = Hash::make($validated['password']);
        
        // Asignar rol de cliente por defecto a todos los nuevos usuarios
        $validated['role'] = 'client';

        // Disparar evento de usuario registrado (para enviar email de verificación, etc.)
        event(new Registered(($user = User::create($validated))));

        // Autenticar automáticamente al usuario recién registrado
        Auth::login($user);

        // Regenerar ID de sesión por seguridad
        Session::regenerate();
        
        // Mensaje de bienvenida
        Session::flash('success', '¡Bienvenido a IKIGAI! Tu cuenta ha sido creada exitosamente.');

        // Redirigir a los clientes a la página principal
        $this->redirect('/', navigate: true);
    }
}
