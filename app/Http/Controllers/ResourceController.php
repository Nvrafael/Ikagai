<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    /**
     * Mostrar listado de recursos
     */
    public function index(Request $request)
    {
        $query = Resource::published()->with('author')->latest('published_at');

        // Filtrar por tipo si se especifica
        if ($request->has('type') && in_array($request->type, ['receta', 'consejo', 'articulo', 'guia'])) {
            $query->ofType($request->type);
        }

        // Buscar por título o contenido
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $resources = $query->paginate(12);

        return view('resources.index', compact('resources'));
    }

    /**
     * Mostrar un recurso específico
     */
    public function show($slug)
    {
        $resource = Resource::where('slug', $slug)
                           ->published()
                           ->with('author')
                           ->firstOrFail();

        // Incrementar contador de vistas
        $resource->incrementViews();

        // Recursos relacionados (mismo tipo)
        $relatedResources = Resource::published()
                                   ->where('type', $resource->type)
                                   ->where('id', '!=', $resource->id)
                                   ->inRandomOrder()
                                   ->limit(3)
                                   ->get();

        return view('resources.show', compact('resource', 'relatedResources'));
    }

    /**
     * Descargar archivo (para guías)
     */
    public function download($slug)
    {
        $resource = Resource::where('slug', $slug)
                           ->published()
                           ->firstOrFail();

        if (!$resource->download_file) {
            abort(404, 'Este recurso no tiene archivo descargable');
        }

        $filePath = storage_path('app/public/' . $resource->download_file);

        if (!file_exists($filePath)) {
            abort(404, 'Archivo no encontrado');
        }

        return response()->download($filePath);
    }
}
