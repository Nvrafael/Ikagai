<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Features;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

/**
 * Componente Livewire de Inicio de Sesión
 * 
 * Maneja el proceso de autenticación de usuarios en la aplicación.
 * Incluye validación de credenciales, protección contra fuerza bruta,
 * soporte para autenticación de dos factores y redirección basada en roles.
 * 
 * @package App\Livewire\Auth
 */
#[Layout('components.layouts.auth')]
class Login extends Component
{
    /**
     * Dirección de correo electrónico del usuario.
     * Validado como requerido, string y email válido.
     */
    #[Validate('required|string|email')]
    public string $email = '';

    /**
     * Contraseña del usuario.
     * Validada como requerida y string.
     */
    #[Validate('required|string')]
    public string $password = '';

    /**
     * Indica si se debe recordar la sesión del usuario.
     */
    public bool $remember = false;

    /**
     * Maneja una solicitud de autenticación entrante.
     * 
     * Valida las credenciales, verifica rate limiting, gestiona autenticación
     * de dos factores si está habilitada, y redirige según el rol del usuario.
     * 
     * @throws \Illuminate\Validation\ValidationException
     * @return void
     */
    public function login(): void
    {
        // Validar inputs del formulario
        $this->validate();

        // Verificar que no se haya excedido el límite de intentos
        $this->ensureIsNotRateLimited();

        // Validar credenciales y obtener usuario
        $user = $this->validateCredentials();

        // Verificar si la autenticación de dos factores está habilitada
        if (Features::canManageTwoFactorAuthentication() && $user->hasEnabledTwoFactorAuthentication()) {
            // Almacenar datos en sesión para el flujo de 2FA
            Session::put([
                'login.id' => $user->getKey(),
                'login.remember' => $this->remember,
            ]);

            // Redirigir a la página de autenticación de dos factores
            $this->redirect(route('two-factor.login'), navigate: true);

            return;
        }

        // Iniciar sesión del usuario
        Auth::login($user, $this->remember);

        // Limpiar contador de intentos fallidos
        RateLimiter::clear($this->throttleKey());
        
        // Regenerar ID de sesión por seguridad
        Session::regenerate();

        // Redirigir según el rol del usuario
        if ($user->role === 'admin') {
            Session::flash('success', '¡Bienvenido de nuevo, Admin!');
            $this->redirect(route('admin.dashboard', absolute: false), navigate: true);
        } elseif ($user->role === 'nutritionist') {
            Session::flash('success', '¡Bienvenido de nuevo!');
            $this->redirect(route('nutritionist.dashboard', absolute: false), navigate: true);
        } else {
            // Los clientes van a la página principal
            Session::flash('success', '¡Bienvenido de nuevo a IKIGAI!');
            $this->redirect('/', navigate: true);
        }
    }

    /**
     * Valida las credenciales del usuario.
     * 
     * Recupera el usuario por email y verifica su contraseña.
     * Si las credenciales son inválidas, incrementa el contador de rate limiting.
     * 
     * @throws \Illuminate\Validation\ValidationException  Si las credenciales son inválidas
     * @return User  El usuario autenticado
     */
    protected function validateCredentials(): User
    {
        // Intentar recuperar usuario por email
        $user = Auth::getProvider()->retrieveByCredentials(['email' => $this->email, 'password' => $this->password]);

        // Verificar que el usuario existe y la contraseña es correcta
        if (! $user || ! Auth::getProvider()->validateCredentials($user, ['password' => $this->password])) {
            // Incrementar contador de intentos fallidos
            RateLimiter::hit($this->throttleKey());

            // Lanzar excepción con mensaje de error
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        return $user;
    }

    /**
     * Verifica que la solicitud de autenticación no exceda el límite de intentos.
     * 
     * Permite un máximo de 5 intentos antes de bloquear temporalmente.
     * 
     * @throws \Illuminate\Validation\ValidationException  Si se excedió el límite
     * @return void
     */
    protected function ensureIsNotRateLimited(): void
    {
        // Verificar si no se han excedido los 5 intentos permitidos
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        // Disparar evento de bloqueo
        event(new Lockout(request()));

        // Calcular segundos restantes para el desbloqueo
        $seconds = RateLimiter::availableIn($this->throttleKey());

        // Lanzar excepción con mensaje de throttling
        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Genera la clave de throttle para limitar intentos de autenticación.
     * 
     * Combina el email del usuario y su IP para rastrear intentos.
     * 
     * @return string  La clave de throttle
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}
