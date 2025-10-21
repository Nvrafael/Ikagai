<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

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

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * Relación con usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con items del pedido
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Generar número de pedido único
     */
    public static function generateOrderNumber()
    {
        $number = 'ORD-' . date('Ymd') . '-' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        
        // Verificar que sea único
        while (self::where('order_number', $number)->exists()) {
            $number = 'ORD-' . date('Ymd') . '-' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        }
        
        return $number;
    }
}
