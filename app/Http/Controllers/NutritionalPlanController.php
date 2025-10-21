<?php

namespace App\Http\Controllers;

use App\Models\NutritionalPlan;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NutritionalPlanController extends Controller
{
    /**
     * Mostrar todos los planes del usuario
     */
    public function index()
    {
        $plans = Auth::user()->nutritionalPlans()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('nutritional-plans.index', compact('plans'));
    }

    /**
     * Mostrar un plan específico
     */
    public function show(NutritionalPlan $nutritionalPlan)
    {
        // Verificar que el usuario sea el dueño o nutricionista/admin
        if ($nutritionalPlan->user_id !== Auth::id() && !Auth::user()->isNutritionist() && !Auth::user()->isAdmin()) {
            abort(403, 'No tienes permiso para ver este plan.');
        }

        $nutritionalPlan->load(['booking', 'user']);

        return view('nutritional-plans.show', compact('nutritionalPlan'));
    }

    /**
     * Crear nuevo plan nutricional (nutricionista)
     */
    public function create(Request $request)
    {
        $booking = null;
        if ($request->has('booking_id')) {
            $booking = Booking::findOrFail($request->booking_id);
        }

        return view('nutritional-plans.create', compact('booking'));
    }

    /**
     * Guardar nuevo plan nutricional
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'booking_id' => 'nullable|exists:bookings,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'objectives' => 'nullable|array',
            'dietary_restrictions' => 'nullable|array',
            'meal_plan' => 'nullable|array',
            'recommendations' => 'nullable|string',
            'target_calories' => 'nullable|numeric',
            'current_weight' => 'nullable|numeric',
            'target_weight' => 'nullable|numeric',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        $plan = NutritionalPlan::create($validated);

        return redirect()->route('nutritional-plans.show', $plan)
            ->with('success', 'Plan nutricional creado exitosamente.');
    }

    /**
     * Actualizar plan nutricional
     */
    public function update(Request $request, NutritionalPlan $nutritionalPlan)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'objectives' => 'nullable|array',
            'dietary_restrictions' => 'nullable|array',
            'meal_plan' => 'nullable|array',
            'recommendations' => 'nullable|string',
            'target_calories' => 'nullable|numeric',
            'current_weight' => 'nullable|numeric',
            'target_weight' => 'nullable|numeric',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'nullable|in:active,completed,cancelled',
            'is_active' => 'boolean',
        ]);

        $nutritionalPlan->update($validated);

        return redirect()->route('nutritional-plans.show', $nutritionalPlan)
            ->with('success', 'Plan nutricional actualizado exitosamente.');
    }

    /**
     * Eliminar plan nutricional
     */
    public function destroy(NutritionalPlan $nutritionalPlan)
    {
        $nutritionalPlan->delete();

        return redirect()->route('nutritional-plans.index')
            ->with('success', 'Plan nutricional eliminado exitosamente.');
    }

    /**
     * Listar todos los planes (nutricionista/admin)
     */
    public function adminIndex(Request $request)
    {
        $query = NutritionalPlan::with('user');

        // Filtros
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $plans = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('nutritional-plans.admin-index', compact('plans'));
    }
}
