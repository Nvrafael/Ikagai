<?php

namespace App\Livewire\Actions;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

/**
 * Clase Logout
 * 
 * Maneja la acción de cierre de sesión de usuarios en la aplicación.
 * Esta clase es invocable y se utiliza como acción en componentes Livewire.
 * 
 * @package App\Livewire\Actions
 */
class Logout
{
    /**
     * Cierra la sesión del usuario actual de la aplicación.
     * 
     * Este método realiza las siguientes acciones:
     * - Cierra la sesión del usuario del guard 'web'
     * - Invalida la sesión actual
     * - Regenera el token CSRF para prevenir ataques
     * - Redirige al usuario a la página principal
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke()
    {
        // Cerrar sesión del usuario autenticado
        Auth::guard('web')->logout();

        // Invalidar la sesión actual por seguridad
        Session::invalidate();
        
        // Regenerar el token CSRF
        Session::regenerateToken();

        // Redirigir a la página principal
        return redirect('/');
    }
}
