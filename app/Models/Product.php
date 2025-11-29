<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo de Producto
 * 
 * Representa los productos de suplementos y alimentos saludables
 * disponibles para la venta en la tienda. Gestiona información de
 * precios, stock, imágenes, beneficios nutricionales e ingredientes.
 * 
 * @package App\Models
 * @property int $id
 * @property int $category_id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property float $price
 * @property float|null $compare_price
 * @property int $stock
 * @property string|null $sku
 * @property array|null $images
 * @property bool $is_active
 * @property bool $is_featured
 * @property string|null $benefits
 * @property string|null $ingredients
 * @property string|null $weight
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Product extends Model
{
    use HasFactory;

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array<int, string>
     */
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

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'images' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones Eloquent
    |--------------------------------------------------------------------------
    */

    /**
     * Obtiene la categoría a la que pertenece el producto.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Obtiene todas las reseñas del producto.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Obtiene todos los items de pedidos que incluyen este producto.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Métodos de Utilidad
    |--------------------------------------------------------------------------
    */

    /**
     * Calcula el promedio de calificaciones del producto.
     * 
     * Obtiene todas las reseñas aprobadas y calcula el promedio
     * de sus calificaciones (1-5 estrellas).
     * 
     * @return float|null  El promedio de calificaciones o null si no hay reseñas
     */
    public function averageRating()
    {
        return $this->reviews()->avg('rating');
    }

    /**
     * Verifica si hay stock disponible del producto.
     * 
     * Compara la cantidad solicitada con el stock disponible.
     * 
     * @param  int  $quantity  La cantidad a verificar (por defecto 1)
     * @return bool  True si hay suficiente stock, false en caso contrario
     */
    public function hasStock($quantity = 1)
    {
        return $this->stock >= $quantity;
    }
}

