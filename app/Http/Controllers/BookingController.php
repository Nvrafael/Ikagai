<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador de Reservas
 * 
 * Gestiona todas las operaciones relacionadas con las reservas de servicios
 * de nutrición, incluyendo consultas, seguimientos y talleres.
 * Maneja la creación, visualización, actualización de estado y cancelación de reservas.
 * 
 * @package App\Http\Controllers
 */
class BookingController extends Controller
{
    /**
     * Muestra el listado de todas las reservas del usuario autenticado.
     * 
     * Obtiene todas las reservas del usuario ordenadas por fecha de agendamiento
     * (más recientes primero) con la información del servicio relacionado.
     * 
     * @return \Illuminate\View\View  Vista con el listado paginado de reservas
     */
    public function index()
    {
        // Obtener reservas del usuario con servicio relacionado
        $bookings = Auth::user()->bookings()
            ->with('service')
            ->orderBy('scheduled_at', 'desc')
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Muestra el formulario para crear una nueva reserva.
     * 
     * Pre-carga el servicio seleccionado para mostrar su información
     * en el formulario de reserva.
     * 
     * @param  \App\Models\Service  $service  El servicio a reservar
     * @return \Illuminate\View\View  Vista con el formulario de reserva
     */
    public function create(Service $service)
    {
        return view('bookings.create', compact('service'));
    }

    /**
     * Crea y almacena una nueva reserva en la base de datos.
     * 
     * Valida los datos, verifica la disponibilidad del horario y crea la reserva
     * asociada al usuario autenticado. El precio se toma del servicio seleccionado.
     * 
     * @param  \Illuminate\Http\Request  $request  La solicitud con los datos de la reserva
     * @return \Illuminate\Http\RedirectResponse  Redirección con mensaje de éxito o error
     */
    public function store(Request $request)
    {
        // Validar los datos de la reserva
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'scheduled_at' => 'required|date|after:now', // Fecha futura obligatoria
            'phone' => 'nullable|string|max:20',
            'notes' => 'required|string|min:20', // Mínimo 20 caracteres en las notas
        ]);

        // Obtener información del servicio
        $service = Service::findOrFail($validated['service_id']);

        // Verificar disponibilidad del horario
        $existingBooking = Booking::where('scheduled_at', $validated['scheduled_at'])
            ->where('status', '!=', 'cancelled')
            ->first();

        if ($existingBooking) {
            return back()->with('error', 'Este horario ya está reservado. Por favor, elige otro.');
        }

        // Añadir información del usuario autenticado y precio del servicio
        $validated['user_id'] = Auth::id();
        $validated['price'] = $service->price;
        
        // Si se proporcionó teléfono, agregarlo al inicio de las notas
        if (!empty($validated['phone'])) {
            $validated['notes'] = "Teléfono: " . $validated['phone'] . "\n\n" . $validated['notes'];
            unset($validated['phone']);
        }

        // Crear la reserva
        $booking = Booking::create($validated);

        return redirect()->back()
            ->with('success', 'Reserva creada exitosamente. Te contactaremos pronto para confirmar tu cita.');
    }

    /**
     * Muestra los detalles de una reserva específica.
     * 
     * Verifica que el usuario tenga permisos para ver la reserva
     * (propietario, nutricionista o administrador).
     * 
     * @param  \App\Models\Booking  $booking  La reserva a mostrar
     * @return \Illuminate\View\View  Vista con los detalles de la reserva
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function show(Booking $booking)
    {
        // Verificar permisos: propietario, nutricionista o admin
        if ($booking->user_id !== Auth::id() && !Auth::user()->isNutritionist() && !Auth::user()->isAdmin()) {
            abort(403, 'No tienes permiso para ver esta reserva.');
        }

        // Cargar relaciones: servicio y plan nutricional si existe
        $booking->load(['service', 'nutritionalPlan']);

        return view('bookings.show', compact('booking'));
    }

    /**
     * Actualiza el estado de una reserva.
     * 
     * Solo accesible para nutricionistas y administradores.
     * Permite cambiar el estado, agregar notas del nutricionista y enlace de reunión.
     * 
     * @param  \Illuminate\Http\Request  $request  La solicitud con los datos actualizados
     * @param  \App\Models\Booking  $booking  La reserva a actualizar
     * @return \Illuminate\Http\RedirectResponse  Redirección con mensaje de éxito
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        // Validar los datos actualizados
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'nutritionist_notes' => 'nullable|string', // Notas privadas del nutricionista
            'meeting_link' => 'nullable|url', // Enlace para reunión virtual
        ]);

        // Actualizar la reserva
        $booking->update($validated);

        return back()->with('success', 'Estado de la reserva actualizado exitosamente.');
    }

    /**
     * Cancela una reserva.
     * 
     * Solo el propietario de la reserva puede cancelarla.
     * Cambia el estado a 'cancelled' sin eliminar el registro.
     * 
     * @param  \App\Models\Booking  $booking  La reserva a cancelar
     * @return \Illuminate\Http\RedirectResponse  Redirección con mensaje de éxito
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function cancel(Booking $booking)
    {
        // Verificar que el usuario sea el propietario de la reserva
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para cancelar esta reserva.');
        }

        // Cambiar estado a cancelado
        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Reserva cancelada exitosamente.');
    }

    /**
     * Muestra el listado de todas las reservas (panel de administración).
     * 
     * Solo accesible para nutricionistas y administradores.
     * Permite filtrar por estado y fecha, ordenadas por fecha de agendamiento.
     * 
     * @param  \Illuminate\Http\Request  $request  La solicitud con parámetros de filtrado
     * @return \Illuminate\View\View  Vista con el listado paginado de todas las reservas
     */
    public function adminIndex(Request $request)
    {
        // Iniciar consulta con usuario y servicio relacionados
        $query = Booking::with(['user', 'service']);

        // Filtrar por estado si se especifica
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filtrar por fecha específica si se proporciona
        if ($request->has('date')) {
            $query->whereDate('scheduled_at', $request->date);
        }

        // Ordenar por fecha de agendamiento (próximas primero)
        $bookings = $query->orderBy('scheduled_at', 'asc')->paginate(20);

        return view('bookings.admin-index', compact('bookings'));
    }
}
