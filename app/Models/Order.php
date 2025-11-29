<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo de Pedido
 * 
 * Representa un pedido de productos realizado por un usuario.
 * Gestiona toda la información del pedido incluyendo costos, estado,
 * información de envío y método de pago. Los items individuales
 * se almacenan en la tabla order_items relacionada.
 * 
 * @package App\Models
 * @property int $id
 * @property int $user_id
 * @property string $order_number  Número único del pedido
 * @property string $status  Estados: pending, processing, shipped, delivered, cancelled
 * @property float $subtotal
 * @property float $tax  Impuestos (IVA)
 * @property float $shipping  Costo de envío
 * @property float $total
 * @property string|null $payment_method
 * @property string|null $payment_status
 * @property string $shipping_name
 * @property string $shipping_email
 * @property string $shipping_phone
 * @property string $shipping_address
 * @property string $shipping_city
 * @property string $shipping_state
 * @property string $shipping_zipcode
 * @property string $shipping_country
 * @property string|null $notes
 * @property string|null $tracking_number  Número de guía de envío
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Order extends Model
{
    use HasFactory;

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'subtotal',
        'tax',
        'shipping',
        'total',
        'payment_method',
        'payment_status',
        'shipping_name',
        'shipping_email',
        'shipping_phone',
        'shipping_address',
        'shipping_city',
        'shipping_state',
        'shipping_zipcode',
        'shipping_country',
        'notes',
        'tracking_number',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones Eloquent
    |--------------------------------------------------------------------------
    */

    /**
     * Obtiene el usuario que realizó el pedido.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtiene todos los items (productos) del pedido.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Métodos de Utilidad
    |--------------------------------------------------------------------------
    */

    /**
     * Genera un número de pedido único.
     * 
     * El formato es: ORD-YYYYMMDD-XXXX
     * Donde XXXX es un número aleatorio de 4 dígitos.
     * Verifica que el número generado sea único en la base de datos.
     * 
     * @return string  El número de pedido generado
     */
    public static function generateOrderNumber()
    {
        // Generar número con formato: ORD-YYYYMMDD-XXXX
        $number = 'ORD-' . date('Ymd') . '-' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        
        // Verificar que sea único, regenerar si ya existe
        while (self::where('order_number', $number)->exists()) {
            $number = 'ORD-' . date('Ymd') . '-' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        }
        
        return $number;
    }
}
