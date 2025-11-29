<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware de Control de Roles
 * 
 * Este middleware protege rutas verificando que el usuario autenticado
 * tenga uno de los roles permitidos para acceder al recurso solicitado.
 * 
 * @package App\Http\Middleware
 */
class RoleMiddleware
{
    /**
     * Maneja una solicitud entrante y verifica los roles del usuario.
     * 
     * Este método valida que:
     * - El usuario esté autenticado
     * - El usuario tenga uno de los roles permitidos
     * 
     * @param  \Illuminate\Http\Request  $request  La solicitud HTTP entrante
     * @param  \Closure  $next  El siguiente middleware en la cadena
     * @param  string  ...$roles  Lista variable de roles permitidos
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function handle($request, Closure $next, ...$roles)
    {
        // Verificar si el usuario está autenticado
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Verificar si el rol del usuario está en la lista de roles permitidos
        if (!in_array($request->user()->role, $roles)) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }
    
        // Continuar con la solicitud si las verificaciones son exitosas
        return $next($request);
    }
}
