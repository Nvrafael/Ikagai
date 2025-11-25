<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceApiController extends Controller
{
    /**
     * Listar y filtrar servicios
     */
    public function index(Request $request)
    {
        $query = Service::where('is_active', true);

        // Búsqueda por término
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtrar por tipo
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Filtrar por rango de precio
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $services = $query->get();

        return response()->json([
            'success' => true,
            'data' => $services->map(function($service) {
                return [
                    'id' => $service->id,
                    'name' => $service->name,
                    'slug' => $service->slug,
                    'description' => $service->description,
                    'price' => (float) $service->price,
                    'duration' => $service->duration,
                    'type' => $service->type,
                    'image' => $service->image,
                    'includes' => $service->includes,
                    'url' => route('services.show', $service->slug),
                ];
            }),
        ]);
    }

    /**
     * Obtener un servicio específico
     */
    public function show($id)
    {
        $service = Service::where('is_active', true)->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $service->id,
                'name' => $service->name,
                'slug' => $service->slug,
                'description' => $service->description,
                'price' => (float) $service->price,
                'duration' => $service->duration,
                'type' => $service->type,
                'image' => $service->image,
                'includes' => $service->includes,
                'url' => route('services.show', $service->slug),
            ],
        ]);
    }
}

