<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
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
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
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
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Relación con reseñas del usuario
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Relación con reservas del usuario
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Relación con pedidos del usuario
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Relación con planes nutricionales del usuario
     */
    public function nutritionalPlans()
    {
        return $this->hasMany(NutritionalPlan::class);
    }

    /**
     * Mensajes enviados por el usuario
     */
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Mensajes recibidos por el usuario
     */
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    /**
     * Verificar si el usuario es nutricionista
     */
    public function isNutritionist()
    {
        return $this->role === 'nutritionist';
    }

    /**
     * Verificar si el usuario es cliente
     */
    public function isClient()
    {
        return $this->role === 'client';
    }

    /**
     * Verificar si el usuario es administrador
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
