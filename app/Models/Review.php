<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo de Reseña
 * 
 * Representa las reseñas y calificaciones dejadas por los usuarios
 * en los productos. Incluye sistema de verificación de compra y
 * aprobación por parte de administradores.
 * 
 * @package App\Models
 * @property int $id
 * @property int $product_id
 * @property int $user_id
 * @property int $rating  Calificación de 1 a 5 estrellas
 * @property string|null $title  Título opcional de la reseña
 * @property string $comment
 * @property bool $is_verified_purchase  Si el usuario compró el producto
 * @property bool $is_approved  Si fue aprobada por un administrador
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Review extends Model
{
    use HasFactory;

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'user_id',
        'rating',
        'title',
        'comment',
        'is_verified_purchase',
        'is_approved',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_verified_purchase' => 'boolean',
        'is_approved' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones Eloquent
    |--------------------------------------------------------------------------
    */

    /**
     * Obtiene el producto al que pertenece esta reseña.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Obtiene el usuario que escribió la reseña.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
