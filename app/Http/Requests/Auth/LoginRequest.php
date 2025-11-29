<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * Solicitud de Inicio de Sesión
 * 
 * Maneja la validación y autenticación de usuarios al iniciar sesión.
 * Implementa protección contra ataques de fuerza bruta mediante
 * limitación de intentos (rate limiting).
 * 
 * @package App\Http\Requests\Auth
 */
class LoginRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta solicitud.
     * 
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Obtiene las reglas de validación para la solicitud.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Intenta autenticar al usuario con las credenciales proporcionadas.
     * 
     * Verifica que no se haya excedido el límite de intentos,
     * intenta autenticar al usuario y gestiona el rate limiter.
     *
     * @throws \Illuminate\Validation\ValidationException  Si la autenticación falla
     * @return void
     */
    public function authenticate(): void
    {
        // Verificar que no se haya excedido el límite de intentos
        $this->ensureIsNotRateLimited();

        // Intentar autenticar con email y contraseña
        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            // Incrementar contador de intentos fallidos
            RateLimiter::hit($this->throttleKey());

            // Lanzar excepción de validación con mensaje de error
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // Limpiar contador de intentos después de autenticación exitosa
        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Verifica que no se haya excedido el límite de intentos de inicio de sesión.
     * 
     * Permite un máximo de 5 intentos antes de bloquear temporalmente.
     * El bloqueo dura hasta que expire el tiempo del rate limiter.
     *
     * @throws \Illuminate\Validation\ValidationException  Si se excedió el límite
     * @return void
     */
    public function ensureIsNotRateLimited(): void
    {
        // Verificar si no se han excedido los 5 intentos permitidos
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        // Disparar evento de bloqueo
        event(new Lockout($this));

        // Calcular segundos restantes hasta que se permita intentar de nuevo
        $seconds = RateLimiter::availableIn($this->throttleKey());

        // Lanzar excepción con mensaje de throttling
        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Genera la clave única de throttle para esta solicitud.
     * 
     * La clave combina el email del usuario y su dirección IP
     * para rastrear intentos de inicio de sesión por usuario y ubicación.
     * 
     * @return string  La clave de throttle
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
