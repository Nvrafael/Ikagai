<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

/**
 * Kernel HTTP de la Aplicación
 * 
 * Define los middleware HTTP que se ejecutan en cada solicitud.
 * Organiza middleware en tres grupos: globales, grupos de rutas y middleware de ruta.
 * 
 * @package App\Http
 */
class Kernel extends HttpKernel
{
    /**
     * Middleware HTTP globales de la aplicación.
     * 
     * Estos middleware se ejecutan en cada solicitud HTTP a la aplicación,
     * independientemente de la ruta o tipo de solicitud.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * Grupos de middleware de rutas de la aplicación.
     * 
     * Define conjuntos de middleware que pueden ser asignados a grupos de rutas.
     * 'web' se usa para rutas web con sesiones, cookies y CSRF.
     * 'api' se usa para rutas API con autenticación stateless y rate limiting.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * Middleware de ruta de la aplicación.
     *
     * Estos middleware pueden ser asignados a rutas individuales o grupos de rutas.
     * Proporcionan funcionalidades específicas como autenticación, autorización,
     * rate limiting, verificación de email y control de roles personalizado.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

        // Middleware personalizado para control de acceso basado en roles
        'role' => \App\Http\Middleware\RoleMiddleware::class,
    ];
}
