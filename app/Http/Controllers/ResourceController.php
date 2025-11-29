<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;

/**
 * Controlador de Recursos Educativos
 * 
 * Gestiona el acceso y visualización de recursos educativos de nutrición
 * tales como recetas, consejos, artículos y guías descargables.
 * Incluye funcionalidades de búsqueda, filtrado y descarga de archivos.
 * 
 * @package App\Http\Controllers
 */
class ResourceController extends Controller
{
    /**
     * Muestra el listado de recursos educativos con opciones de filtrado.
     * 
     * Permite filtrar por tipo de recurso y realizar búsquedas por texto.
     * Los resultados están paginados y ordenados por fecha de publicación.
     * 
     * @param  \Illuminate\Http\Request  $request  La solicitud con parámetros de filtrado
     * @return \Illuminate\View\View  Vista con el listado paginado de recursos
     */
    public function index(Request $request)
    {
        // Iniciar consulta con recursos publicados, incluyendo autor
        $query = Resource::published()->with('author')->latest('published_at');

        // Filtrar por tipo si se especifica en la solicitud
        if ($request->has('type') && in_array($request->type, ['receta', 'consejo', 'articulo', 'guia'])) {
            $query->ofType($request->type);
        }

        // Buscar por título, extracto o contenido si se proporciona término de búsqueda
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Paginar resultados (12 recursos por página)
        $resources = $query->paginate(12);

        return view('resources.index', compact('resources'));
    }

    /**
     * Muestra un recurso educativo específico.
     * 
     * Busca el recurso por su slug, incrementa el contador de vistas
     * y obtiene recursos relacionados del mismo tipo.
     * 
     * @param  string  $slug  El slug único del recurso
     * @return \Illuminate\View\View  Vista con el detalle del recurso
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function show($slug)
    {
        // Buscar recurso por slug
        $resource = Resource::where('slug', $slug)
                           ->published()
                           ->with('author')
                           ->firstOrFail();

        // Incrementar contador de visualizaciones
        $resource->incrementViews();

        // Obtener 3 recursos relacionados del mismo tipo (aleatorio)
        $relatedResources = Resource::published()
                                   ->where('type', $resource->type)
                                   ->where('id', '!=', $resource->id)
                                   ->inRandomOrder()
                                   ->limit(3)
                                   ->get();

        return view('resources.show', compact('resource', 'relatedResources'));
    }

    /**
     * Descarga el archivo asociado a un recurso (para guías descargables).
     * 
     * Verifica que el recurso tenga un archivo disponible y que el
     * archivo exista en el almacenamiento antes de iniciar la descarga.
     * 
     * @param  string  $slug  El slug del recurso con el archivo a descargar
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse  El archivo para descarga
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function download($slug)
    {
        // Buscar recurso por slug
        $resource = Resource::where('slug', $slug)
                           ->published()
                           ->firstOrFail();

        // Verificar que el recurso tenga archivo descargable
        if (!$resource->download_file) {
            abort(404, 'Este recurso no tiene archivo descargable');
        }

        // Construir ruta completa del archivo
        $filePath = storage_path('app/public/' . $resource->download_file);

        // Verificar que el archivo exista físicamente
        if (!file_exists($filePath)) {
            abort(404, 'Archivo no encontrado');
        }

        // Iniciar descarga del archivo
        return response()->download($filePath);
    }
}
