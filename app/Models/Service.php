<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo de Servicio
 * 
 * Representa los servicios de nutrición ofrecidos en la plataforma,
 * tales como consultas nutricionales, planes alimenticios personalizados,
 * seguimientos y talleres. Gestiona precios, duración y disponibilidad.
 * 
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property float $price
 * @property int $duration  Duración en minutos
 * @property string $type  Tipo de servicio (consultation, nutritional_plan, follow_up, workshop)
 * @property bool $is_active
 * @property string|null $image
 * @property string|null $includes  Qué incluye el servicio
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Service extends Model
{
    use HasFactory;

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'duration',
        'type',
        'is_active',
        'image',
        'includes',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones Eloquent
    |--------------------------------------------------------------------------
    */

    /**
     * Obtiene todas las reservas asociadas a este servicio.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}

