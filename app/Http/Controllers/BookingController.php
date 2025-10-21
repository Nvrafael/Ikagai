<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Mostrar todas las reservas del usuario
     */
    public function index()
    {
        $bookings = Auth::user()->bookings()
            ->with('service')
            ->orderBy('scheduled_at', 'desc')
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Mostrar formulario para nueva reserva
     */
    public function create(Service $service)
    {
        return view('bookings.create', compact('service'));
    }

    /**
     * Guardar nueva reserva
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'scheduled_at' => 'required|date|after:now',
            'notes' => 'nullable|string',
        ]);

        $service = Service::findOrFail($validated['service_id']);

        // Verificar disponibilidad (puedes agregar lógica más compleja aquí)
        $existingBooking = Booking::where('scheduled_at', $validated['scheduled_at'])
            ->where('status', '!=', 'cancelled')
            ->first();

        if ($existingBooking) {
            return back()->with('error', 'Este horario ya está reservado. Por favor, elige otro.');
        }

        $validated['user_id'] = Auth::id();
        $validated['price'] = $service->price;

        $booking = Booking::create($validated);

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Reserva creada exitosamente. Te contactaremos pronto para confirmar.');
    }

    /**
     * Mostrar una reserva específica
     */
    public function show(Booking $booking)
    {
        // Verificar que el usuario sea el dueño o nutricionista/admin
        if ($booking->user_id !== Auth::id() && !Auth::user()->isNutritionist() && !Auth::user()->isAdmin()) {
            abort(403, 'No tienes permiso para ver esta reserva.');
        }

        $booking->load(['service', 'nutritionalPlan']);

        return view('bookings.show', compact('booking'));
    }

    /**
     * Actualizar estado de reserva
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'nutritionist_notes' => 'nullable|string',
            'meeting_link' => 'nullable|url',
        ]);

        $booking->update($validated);

        return back()->with('success', 'Estado de la reserva actualizado exitosamente.');
    }

    /**
     * Cancelar reserva
     */
    public function cancel(Booking $booking)
    {
        // Verificar que el usuario sea el dueño
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para cancelar esta reserva.');
        }

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Reserva cancelada exitosamente.');
    }

    /**
     * Listar todas las reservas (nutricionista/admin)
     */
    public function adminIndex(Request $request)
    {
        $query = Booking::with(['user', 'service']);

        // Filtros
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('date')) {
            $query->whereDate('scheduled_at', $request->date);
        }

        $bookings = $query->orderBy('scheduled_at', 'asc')->paginate(20);

        return view('bookings.admin-index', compact('bookings'));
    }
}
