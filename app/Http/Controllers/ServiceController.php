<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Controlador de Servicios
 * 
 * Gestiona todas las operaciones CRUD relacionadas con los servicios
 * de nutrición ofrecidos en la plataforma (consultas, planes nutricionales,
 * seguimientos, talleres, etc.).
 * 
 * @package App\Http\Controllers
 */
class ServiceController extends Controller
{
    /**
     * Muestra el listado de todos los servicios activos.
     * 
     * Obtiene y muestra todos los servicios marcados como activos,
     * ordenados por fecha de creación (más recientes primero).
     * 
     * @return \Illuminate\View\View  Vista con el listado de servicios
     */
    public function index()
    {
        // Obtener servicios activos ordenados por fecha de creación
        $services = Service::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('services.index', compact('services'));
    }

    /**
     * Muestra los detalles de un servicio específico.
     * 
     * @param  \App\Models\Service  $service  El servicio a mostrar (inyección de modelo)
     * @return \Illuminate\View\View  Vista con los detalles del servicio
     */
    public function show(Service $service)
    {
        return view('services.show', compact('service'));
    }

    /**
     * Crea y almacena un nuevo servicio en la base de datos.
     * 
     * Solo accesible para usuarios con rol de administrador o nutricionista.
     * Valida los datos, genera el slug automáticamente y procesa la imagen si existe.
     * 
     * @param  \Illuminate\Http\Request  $request  La solicitud con los datos del servicio
     * @return \Illuminate\Http\RedirectResponse  Redirección con mensaje de éxito
     */
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:15', // Duración mínima de 15 minutos
            'type' => 'required|string|in:consultation,nutritional_plan,follow_up,workshop',
            'image' => 'nullable|image|max:2048', // Imagen opcional, máximo 2MB
            'includes' => 'nullable|string',
        ]);

        // Generar slug automáticamente desde el nombre
        $validated['slug'] = Str::slug($validated['name']);

        // Procesar y almacenar la imagen si fue proporcionada
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('services', 'public');
        }

        // Crear el servicio en la base de datos
        $service = Service::create($validated);

        // Redirigir a la vista del servicio con mensaje de éxito
        return redirect()->route('services.show', $service)
            ->with('success', 'Servicio creado exitosamente.');
    }

    /**
     * Actualiza un servicio existente en la base de datos.
     * 
     * Permite modificar los datos de un servicio existente, incluyendo
     * su estado de activación. Regenera el slug si el nombre cambia.
     * 
     * @param  \Illuminate\Http\Request  $request  La solicitud con los datos actualizados
     * @param  \App\Models\Service  $service  El servicio a actualizar
     * @return \Illuminate\Http\RedirectResponse  Redirección con mensaje de éxito
     */
    public function update(Request $request, Service $service)
    {
        // Validar los datos del formulario
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

        // Regenerar slug si el nombre cambió
        if ($request->has('name')) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Actualizar imagen si se proporcionó una nueva
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('services', 'public');
        }

        // Actualizar el servicio en la base de datos
        $service->update($validated);

        // Redirigir con mensaje de éxito
        return redirect()->route('services.show', $service)
            ->with('success', 'Servicio actualizado exitosamente.');
    }

    /**
     * Elimina un servicio de la base de datos.
     * 
     * Elimina permanentemente un servicio. Las reservas asociadas
     * a este servicio seguirán existiendo en la base de datos.
     * 
     * @param  \App\Models\Service  $service  El servicio a eliminar
     * @return \Illuminate\Http\RedirectResponse  Redirección con mensaje de éxito
     */
    public function destroy(Service $service)
    {
        // Eliminar el servicio
        $service->delete();

        // Redirigir al listado con mensaje de éxito
        return redirect()->route('services.index')
            ->with('success', 'Servicio eliminado exitosamente.');
    }
}

