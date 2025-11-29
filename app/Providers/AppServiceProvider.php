<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use App\Http\Responses\LoginResponse;

/**
 * Proveedor de Servicios de la Aplicación
 * 
 * Registra servicios y enlaces de contenedor para la aplicación.
 * En este caso, registra una respuesta de login personalizada que
 * redirige a diferentes dashboards según el rol del usuario.
 * 
 * @package App\Providers
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Registra cualquier servicio de la aplicación.
     * 
     * Este método se ejecuta antes de boot() y se usa para vincular
     * clases al contenedor de servicios de Laravel.
     * 
     * @return void
     */
    public function register(): void
    {
        // Registrar respuesta personalizada de login
        // Esto permite que Fortify use nuestra clase LoginResponse personalizada
        // que redirige a diferentes dashboards según el rol del usuario
        $this->app->singleton(LoginResponseContract::class, LoginResponse::class);
    }

    /**
     * Inicializa cualquier servicio de la aplicación.
     * 
     * Este método se ejecuta después de que todos los servicios
     * han sido registrados. Úsalo para configuración que dependa
     * de otros servicios.
     * 
     * @return void
     */
    public function boot(): void
    {
        // Aquí puedes agregar lógica de inicialización
        // Por ejemplo: registrar políticas, observers, listeners, etc.
    }
}
