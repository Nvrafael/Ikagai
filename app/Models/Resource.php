<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Resource extends Model
{
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

    protected $casts = [
        'metadata' => 'array',
        'images' => 'array',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'views' => 'integer',
    ];

    // Generar slug automáticamente
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($resource) {
            if (empty($resource->slug)) {
                $resource->slug = Str::slug($resource->title);
            }
        });
    }

    // Relación con el autor (User)
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // Scope para recursos publicados
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    // Scope por tipo
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Incrementar vistas
    public function incrementViews()
    {
        $this->increment('views');
    }

    // Obtener tiempo de lectura estimado (en minutos)
    public function getReadingTimeAttribute()
    {
        $words = str_word_count(strip_tags($this->content));
        return ceil($words / 200); // Promedio de 200 palabras por minuto
    }
}
