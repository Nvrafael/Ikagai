<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

/**
 * Modelo de Usuario
 * 
 * Representa a los usuarios de la aplicación con sus diferentes roles
 * (cliente, nutricionista, administrador). Gestiona autenticación,
 * autorización, relaciones con pedidos, reservas, reseñas y mensajes.
 * Incluye autenticación de dos factores mediante Laravel Fortify.
 * 
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $role
 * @property bool|null $cookie_consent
 * @property \Illuminate\Support\Carbon|null $cookie_consent_date
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'cookie_consent',
        'cookie_consent_date',
    ];

    /**
     * Los atributos que deben ocultarse en la serialización.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Obtiene los atributos que deben ser convertidos a tipos nativos.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'cookie_consent_date' => 'datetime',
        ];
    }

    /**
     * Obtiene las iniciales del nombre del usuario.
     * 
     * Extrae las primeras letras de las dos primeras palabras del nombre.
     * Por ejemplo: "Juan Pérez" devuelve "JP"
     * 
     * @return string  Las iniciales del usuario
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')  // Dividir nombre por espacios
            ->take(2)  // Tomar solo las dos primeras palabras
            ->map(fn ($word) => Str::substr($word, 0, 1))  // Obtener primera letra de cada palabra
            ->implode('');  // Unir las letras
    }

    /*
    |--------------------------------------------------------------------------
    | Relaciones Eloquent
    |--------------------------------------------------------------------------
    */

    /**
     * Obtiene todas las reseñas escritas por el usuario.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Obtiene todas las reservas de servicios del usuario.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Obtiene todos los pedidos de productos del usuario.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Obtiene todos los planes nutricionales asignados al usuario.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function nutritionalPlans()
    {
        return $this->hasMany(NutritionalPlan::class);
    }

    /**
     * Obtiene todos los mensajes enviados por el usuario.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Obtiene todos los mensajes recibidos por el usuario.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Métodos de Verificación de Roles
    |--------------------------------------------------------------------------
    */

    /**
     * Verifica si el usuario tiene el rol de nutricionista.
     * 
     * @return bool
     */
    public function isNutritionist()
    {
        return $this->role === 'nutritionist';
    }

    /**
     * Verifica si el usuario tiene el rol de cliente.
     * 
     * @return bool
     */
    public function isClient()
    {
        return $this->role === 'client';
    }

    /**
     * Verifica si el usuario tiene el rol de administrador.
     * 
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
