<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo de Mensaje
 * 
 * Representa los mensajes privados intercambiados entre usuarios
 * del sistema (clientes, nutricionistas y administradores).
 * Gestiona conversaciones con estado de lectura y archivos adjuntos.
 * 
 * @package App\Models
 * @property int $id
 * @property int $sender_id  Usuario que envía el mensaje
 * @property int $receiver_id  Usuario que recibe el mensaje
 * @property string $message  Contenido del mensaje
 * @property bool $is_read  Si el mensaje ha sido leído
 * @property \Illuminate\Support\Carbon|null $read_at  Fecha de lectura
 * @property array|null $attachments  Archivos adjuntos
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Message extends Model
{
    use HasFactory;

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'is_read',
        'read_at',
        'attachments',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'attachments' => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones Eloquent
    |--------------------------------------------------------------------------
    */

    /**
     * Obtiene el usuario que envió el mensaje (remitente).
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Obtiene el usuario que recibe el mensaje (destinatario).
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Métodos de Utilidad
    |--------------------------------------------------------------------------
    */

    /**
     * Marca el mensaje como leído.
     * 
     * Actualiza el estado is_read a true y registra la fecha/hora
     * en que fue leído el mensaje.
     * 
     * @return bool
     */
    public function markAsRead()
    {
        return $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Obtiene la conversación completa entre dos usuarios.
     * 
     * Recupera todos los mensajes intercambiados entre dos usuarios,
     * independientemente de quién envió o recibió, ordenados
     * cronológicamente del más antiguo al más reciente.
     * 
     * @param  int  $userId1  ID del primer usuario
     * @param  int  $userId2  ID del segundo usuario
     * @return \Illuminate\Database\Eloquent\Collection  Colección de mensajes
     */
    public static function conversation($userId1, $userId2)
    {
        return self::where(function ($query) use ($userId1, $userId2) {
            // Mensajes donde userId1 es el remitente y userId2 es el destinatario
            $query->where('sender_id', $userId1)
                  ->where('receiver_id', $userId2);
        })->orWhere(function ($query) use ($userId1, $userId2) {
            // O viceversa: userId2 es el remitente y userId1 es el destinatario
            $query->where('sender_id', $userId2)
                  ->where('receiver_id', $userId1);
        })->orderBy('created_at', 'asc')->get();
    }
}
