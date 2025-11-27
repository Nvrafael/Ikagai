<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Título del recurso
            $table->string('slug')->unique(); // URL amigable
            $table->enum('type', ['receta', 'consejo', 'articulo', 'guia']); // Tipo de recurso
            $table->text('excerpt')->nullable(); // Resumen corto
            $table->longText('content'); // Contenido completo
            $table->json('metadata')->nullable(); // Metadatos extra (ingredientes, tiempo, etc.)
            $table->string('featured_image')->nullable(); // Imagen destacada
            $table->json('images')->nullable(); // Galería de imágenes
            $table->string('download_file')->nullable(); // Archivo descargable (para guías)
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade'); // Autor
            $table->boolean('is_published')->default(false); // Estado de publicación
            $table->timestamp('published_at')->nullable(); // Fecha de publicación
            $table->integer('views')->default(0); // Contador de vistas
            $table->timestamps();
            
            // Índices para mejorar el rendimiento
            $table->index('type');
            $table->index('is_published');
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
