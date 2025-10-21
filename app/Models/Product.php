<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'compare_price',
        'stock',
        'sku',
        'images',
        'is_active',
        'is_featured',
        'benefits',
        'ingredients',
        'weight',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'images' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    /**
     * Relación con categoría
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relación con reseñas
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Relación con items de pedidos
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Calcular el promedio de calificaciones
     */
    public function averageRating()
    {
        return $this->reviews()->avg('rating');
    }

    /**
     * Verificar si hay stock disponible
     */
    public function hasStock($quantity = 1)
    {
        return $this->stock >= $quantity;
    }
}
