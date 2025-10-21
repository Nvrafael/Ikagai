<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    /**
     * Mostrar todos los servicios
     */
    public function index()
    {
        $services = Service::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('services.index', compact('services'));
    }

    /**
     * Mostrar un servicio específico
     */
    public function show(Service $service)
    {
        return view('services.show', compact('service'));
    }

    /**
     * Guardar nuevo servicio (admin/nutricionista)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:15', // mínimo 15 minutos
            'type' => 'required|string|in:consultation,nutritional_plan,follow_up,workshop',
            'image' => 'nullable|image|max:2048',
            'includes' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('services', 'public');
        }

        $service = Service::create($validated);

        return redirect()->route('services.show', $service)
            ->with('success', 'Servicio creado exitosamente.');
    }

    /**
     * Actualizar servicio
     */
    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:15',
            'type' => 'required|string|in:consultation,nutritional_plan,follow_up,workshop',
            'image' => 'nullable|image|max:2048',
            'includes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($request->has('name')) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('services', 'public');
        }

        $service->update($validated);

        return redirect()->route('services.show', $service)
            ->with('success', 'Servicio actualizado exitosamente.');
    }

    /**
     * Eliminar servicio
     */
    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('services.index')
            ->with('success', 'Servicio eliminado exitosamente.');
    }
}
