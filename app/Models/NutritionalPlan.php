<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NutritionalPlan extends Model
{
    use HasFactory;

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

    /**
     * Relación con usuario/cliente
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con reserva
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Verificar si el plan está activo
     */
    public function isActive()
    {
        return $this->is_active && $this->status === 'active';
    }
}

