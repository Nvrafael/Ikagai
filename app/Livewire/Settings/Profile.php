<?php

namespace App\Livewire\Settings;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Component;

/**
 * Componente Livewire de Perfil de Usuario
 * 
 * Gestiona la actualización de la información de perfil del usuario autenticado,
 * incluyendo nombre y correo electrónico. Si el email cambia, se invalida
 * la verificación de email para requerir una nueva verificación.
 * 
 * @package App\Livewire\Settings
 */
class Profile extends Component
{
    /**
     * Nombre del usuario.
     */
    public string $name = '';

    /**
     * Correo electrónico del usuario.
     */
    public string $email = '';

    /**
     * Monta el componente con los datos del usuario actual.
     * 
     * Inicializa las propiedades públicas con los valores
     * actuales del usuario autenticado.
     * 
     * @return void
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Actualiza la información de perfil del usuario autenticado.
     * 
     * Valida los nuevos datos, actualiza el modelo de usuario y
     * si el email cambió, invalida la verificación de email.
     * Dispara un evento para notificar a otros componentes.
     * 
     * @return void
     */
    public function updateProfileInformation(): void
    {
        // Obtener usuario autenticado
        $user = Auth::user();

        // Validar los datos actualizados
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                // El email debe ser único, excepto para el usuario actual
                Rule::unique(User::class)->ignore($user->id),
            ],
        ]);

        // Llenar el modelo con los datos validados
        $user->fill($validated);

        // Si el email cambió, invalidar la verificación de email
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Guardar cambios en la base de datos
        $user->save();

        // Disparar evento para notificar a otros componentes
        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Reenvía la notificación de verificación de email al usuario actual.
     * 
     * Si el email ya está verificado, redirige al dashboard.
     * Si no, envía un nuevo email de verificación.
     * 
     * @return void
     */
    public function resendVerificationNotification(): void
    {
        // Obtener usuario autenticado
        $user = Auth::user();

        // Si el email ya está verificado, redirigir al dashboard
        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        // Enviar nueva notificación de verificación de email
        $user->sendEmailVerificationNotification();

        // Establecer mensaje flash de confirmación
        Session::flash('status', 'verification-link-sent');
    }
}
