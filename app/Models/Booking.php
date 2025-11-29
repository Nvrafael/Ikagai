<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo de Reserva
 * 
 * Representa las reservas de servicios de nutrición realizadas por los usuarios.
 * Gestiona el agendamiento de citas, estados de las reservas, pagos y
 * enlaces de reuniones virtuales.
 * 
 * @package App\Models
 * @property int $id
 * @property int $user_id
 * @property int $service_id
 * @property \Illuminate\Support\Carbon $scheduled_at
 * @property string $status  Estados: pending, confirmed, completed, cancelled
 * @property string|null $notes  Notas del cliente
 * @property string|null $nutritionist_notes  Notas privadas del nutricionista
 * @property float $price
 * @property string|null $payment_status
 * @property string|null $meeting_link  Enlace para reunión virtual
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Booking extends Model
{
    use HasFactory;

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array<int, string>
     */
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

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'scheduled_at' => 'datetime',
        'price' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones Eloquent
    |--------------------------------------------------------------------------
    */

    /**
     * Obtiene el usuario/cliente que realizó la reserva.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtiene el servicio que fue reservado.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Obtiene el plan nutricional generado a partir de esta reserva.
     * 
     * Una reserva puede generar un plan nutricional como resultado
     * de la consulta o seguimiento.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function nutritionalPlan()
    {
        return $this->hasOne(NutritionalPlan::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Métodos de Verificación de Estado
    |--------------------------------------------------------------------------
    */

    /**
     * Verifica si la reserva está confirmada.
     * 
     * @return bool
     */
    public function isConfirmed()
    {
        return $this->status === 'confirmed';
    }

    /**
     * Verifica si la reserva está completada.
     * 
     * @return bool
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }
}

