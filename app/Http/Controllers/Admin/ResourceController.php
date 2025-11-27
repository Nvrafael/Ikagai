<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Resource::with('author')->latest();

        // Filtrar por tipo
        if ($request->has('type') && in_array($request->type, ['receta', 'consejo', 'articulo', 'guia'])) {
            $query->where('type', $request->type);
        }

        // Filtrar por estado de publicación
        if ($request->has('status')) {
            if ($request->status === 'published') {
                $query->where('is_published', true);
            } elseif ($request->status === 'draft') {
                $query->where('is_published', false);
            }
        }

        $resources = $query->paginate(20)->withQueryString();
        
        return view('admin.resources.index', compact('resources'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.resources.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:receta,consejo,articulo,guia',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'download_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'is_published' => 'boolean',
            
            // Metadatos para recetas
            'metadata.ingredientes' => 'nullable|string',
            'metadata.tiempo_preparacion' => 'nullable|string',
            'metadata.porciones' => 'nullable|string',
            'metadata.dificultad' => 'nullable|in:facil,media,dificil',
            'metadata.calorias' => 'nullable|string',
        ]);

        // Generar slug único
        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $counter = 1;
        
        while (Resource::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $validated['slug'] = $slug;
        $validated['author_id'] = auth()->id();

        // Subir imagen destacada
        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('resources/images', 'public');
            $validated['featured_image'] = $path;
        }

        // Subir archivo descargable
        if ($request->hasFile('download_file')) {
            $path = $request->file('download_file')->store('resources/downloads', 'public');
            $validated['download_file'] = $path;
        }

        // Procesar metadatos
        if (isset($validated['metadata'])) {
            $metadata = array_filter($validated['metadata']);
            $validated['metadata'] = !empty($metadata) ? $metadata : null;
        }

        // Establecer fecha de publicación si está publicado
        if ($validated['is_published'] ?? false) {
            $validated['published_at'] = now();
        }

        Resource::create($validated);

        return redirect()->route('admin.resources.index')
                        ->with('success', 'Recurso creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Resource $recurso)
    {
        return view('admin.resources.show', ['resource' => $recurso]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Resource $recurso)
    {
        return view('admin.resources.edit', ['resource' => $recurso]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Resource $recurso)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:receta,consejo,articulo,guia',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'download_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'is_published' => 'boolean',
            
            // Metadatos para recetas
            'metadata.ingredientes' => 'nullable|string',
            'metadata.tiempo_preparacion' => 'nullable|string',
            'metadata.porciones' => 'nullable|string',
            'metadata.dificultad' => 'nullable|in:facil,media,dificil',
            'metadata.calorias' => 'nullable|string',
        ]);

        // Actualizar slug si cambió el título
        if ($validated['title'] !== $recurso->title) {
            $slug = Str::slug($validated['title']);
            $originalSlug = $slug;
            $counter = 1;
            
            while (Resource::where('slug', $slug)->where('id', '!=', $recurso->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            
            $validated['slug'] = $slug;
        }

        // Subir nueva imagen destacada
        if ($request->hasFile('featured_image')) {
            // Eliminar imagen anterior
            if ($recurso->featured_image) {
                Storage::disk('public')->delete($recurso->featured_image);
            }
            
            $path = $request->file('featured_image')->store('resources/images', 'public');
            $validated['featured_image'] = $path;
        }

        // Subir nuevo archivo descargable
        if ($request->hasFile('download_file')) {
            // Eliminar archivo anterior
            if ($recurso->download_file) {
                Storage::disk('public')->delete($recurso->download_file);
            }
            
            $path = $request->file('download_file')->store('resources/downloads', 'public');
            $validated['download_file'] = $path;
        }

        // Procesar metadatos
        if (isset($validated['metadata'])) {
            $metadata = array_filter($validated['metadata']);
            $validated['metadata'] = !empty($metadata) ? $metadata : null;
        }

        // Establecer fecha de publicación si se publica ahora
        if (($validated['is_published'] ?? false) && !$recurso->published_at) {
            $validated['published_at'] = now();
        } elseif (!($validated['is_published'] ?? false)) {
            $validated['published_at'] = null;
        }

        $recurso->update($validated);

        return redirect()->route('admin.recursos.index')
                        ->with('success', 'Recurso actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Resource $recurso)
    {
        // Eliminar archivos asociados
        if ($recurso->featured_image) {
            Storage::disk('public')->delete($recurso->featured_image);
        }
        
        if ($recurso->download_file) {
            Storage::disk('public')->delete($recurso->download_file);
        }

        $recurso->delete();

        return redirect()->route('admin.recursos.index')
                        ->with('success', 'Recurso eliminado exitosamente');
    }
}
