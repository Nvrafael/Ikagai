<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Modelo de Recurso Educativo
 * 
 * Representa recursos educativos de nutrición como recetas, consejos,
 * artículos y guías descargables. Gestiona publicación, vistas y
 * metadatos adicionales. Incluye funcionalidad de slug automático.
 * 
 * @package App\Models
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $type  Tipo: receta, consejo, articulo, guia
 * @property string|null $excerpt  Extracto o resumen
 * @property string $content  Contenido completo en HTML
 * @property array|null $metadata  Metadatos adicionales (tiempo de preparación, dificultad, etc.)
 * @property string|null $featured_image  Imagen principal
 * @property array|null $images  Galería de imágenes adicionales
 * @property string|null $download_file  Archivo descargable (para guías)
 * @property int $author_id  Usuario autor del recurso
 * @property bool $is_published
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property int $views  Contador de visualizaciones
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Resource extends Model
{
    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'type',
        'excerpt',
        'content',
        'metadata',
        'featured_image',
        'images',
        'download_file',
        'author_id',
        'is_published',
        'published_at',
        'views',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'metadata' => 'array',
        'images' => 'array',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'views' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Eventos del Modelo
    |--------------------------------------------------------------------------
    */

    /**
     * Configuración de eventos del modelo.
     * 
     * Genera automáticamente el slug al crear un nuevo recurso
     * si no se proporcionó uno manualmente.
     */
    protected static function boot()
    {
        parent::boot();

        // Al crear un nuevo recurso, generar slug automáticamente
        static::creating(function ($resource) {
            if (empty($resource->slug)) {
                $resource->slug = Str::slug($resource->title);
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relaciones Eloquent
    |--------------------------------------------------------------------------
    */

    /**
     * Obtiene el usuario autor del recurso.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Scope para filtrar solo recursos publicados.
     * 
     * Un recurso está publicado si is_published es true, tiene fecha
     * de publicación y esa fecha no es futura.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    /**
     * Scope para filtrar recursos por tipo.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $type  El tipo de recurso (receta, consejo, articulo, guia)
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /*
    |--------------------------------------------------------------------------
    | Métodos de Utilidad
    |--------------------------------------------------------------------------
    */

    /**
     * Incrementa el contador de visualizaciones del recurso.
     * 
     * @return bool
     */
    public function incrementViews()
    {
        return $this->increment('views');
    }

    /**
     * Calcula el tiempo de lectura estimado del contenido.
     * 
     * Asume una velocidad promedio de lectura de 200 palabras por minuto.
     * 
     * @return int  Tiempo de lectura en minutos
     */
    public function getReadingTimeAttribute()
    {
        // Contar palabras en el contenido (sin etiquetas HTML)
        $words = str_word_count(strip_tags($this->content));
        
        // Calcular minutos (promedio de 200 palabras por minuto)
        return ceil($words / 200);
    }
}
