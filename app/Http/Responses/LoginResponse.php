<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

/**
 * Respuesta de Inicio de Sesión
 * 
 * Define el comportamiento personalizado después de un inicio de sesión exitoso.
 * Redirige a los usuarios a diferentes dashboards según su rol
 * (admin, nutricionista o cliente).
 * 
 * @package App\Http\Responses
 */
class LoginResponse implements LoginResponseContract
{
    /**
     * Crea una respuesta HTTP después de un inicio de sesión exitoso.
     * 
     * Determina la ruta de redirección apropiada basándose en el rol
     * del usuario autenticado. Usa la ruta "intended" si existe, permitiendo
     * redirigir al usuario a la página que intentaba acceder originalmente.
     *
     * @param  \Illuminate\Http\Request  $request  La solicitud HTTP
     * @return \Symfony\Component\HttpFoundation\Response  La respuesta de redirección
     */
    public function toResponse($request)
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        // Redirigir según el rol del usuario
        return match ($user->role) {
            // Administradores al dashboard de administración
            'admin' => redirect()->intended(route('admin.dashboard')),
            
            // Nutricionistas al dashboard de nutricionista
            'nutritionist' => redirect()->intended(route('nutritionist.dashboard')),
            
            // Clientes al dashboard de cliente
            'client' => redirect()->intended(route('client.dashboard')),
            
            // Por defecto, ir al dashboard general
            default => redirect()->intended(route('dashboard'))
        };
    }
}
