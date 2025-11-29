<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo de Plan Nutricional
 * 
 * Representa los planes alimenticios personalizados creados por
 * nutricionistas para sus clientes. Gestiona objetivos nutricionales,
 * restricciones dietéticas, planes de comidas y seguimiento de peso.
 * 
 * @package App\Models
 * @property int $id
 * @property int $user_id  Cliente al que pertenece el plan
 * @property int|null $booking_id  Reserva que originó el plan
 * @property string $title
 * @property string|null $description
 * @property array|null $objectives  Objetivos nutricionales (perder peso, ganar masa, etc.)
 * @property array|null $dietary_restrictions  Restricciones alimentarias (alergias, intolerancias)
 * @property array|null $meal_plan  Plan de comidas detallado por día/horario
 * @property string|null $recommendations  Recomendaciones adicionales del nutricionista
 * @property float|null $target_calories  Calorías objetivo diarias
 * @property float|null $current_weight  Peso actual del cliente
 * @property float|null $target_weight  Peso objetivo
 * @property \Illuminate\Support\Carbon|null $start_date  Fecha de inicio del plan
 * @property \Illuminate\Support\Carbon|null $end_date  Fecha de fin del plan
 * @property bool $is_active
 * @property string|null $status  Estado: active, completed, cancelled
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class NutritionalPlan extends Model
{
    use HasFactory;

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'booking_id',
        'title',
        'description',
        'objectives',
        'dietary_restrictions',
        'meal_plan',
        'recommendations',
        'target_calories',
        'current_weight',
        'target_weight',
        'start_date',
        'end_date',
        'is_active',
        'status',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'objectives' => 'array',
        'dietary_restrictions' => 'array',
        'meal_plan' => 'array',
        'target_calories' => 'decimal:2',
        'current_weight' => 'decimal:2',
        'target_weight' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones Eloquent
    |--------------------------------------------------------------------------
    */

    /**
     * Obtiene el usuario/cliente al que pertenece el plan nutricional.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtiene la reserva que originó este plan nutricional.
     * 
     * Un plan nutricional generalmente se crea después de una
     * consulta nutricional o sesión de seguimiento.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Métodos de Verificación
    |--------------------------------------------------------------------------
    */

    /**
     * Verifica si el plan nutricional está activo.
     * 
     * Un plan está activo si is_active es true Y el status es 'active'.
     * 
     * @return bool
     */
    public function isActive()
    {
        return $this->is_active && $this->status === 'active';
    }
}
