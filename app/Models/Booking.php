<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_id',
        'scheduled_at',
        'status',
        'notes',
        'nutritionist_notes',
        'price',
        'payment_status',
        'meeting_link',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'price' => 'decimal:2',
    ];

    /**
     * Relación con usuario/cliente
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con servicio
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Relación con plan nutricional (si se genera uno)
     */
    public function nutritionalPlan()
    {
        return $this->hasOne(NutritionalPlan::class);
    }

    /**
     * Verificar si la reserva está confirmada
     */
    public function isConfirmed()
    {
        return $this->status === 'confirmed';
    }

    /**
     * Verificar si la reserva está completada
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }
}

