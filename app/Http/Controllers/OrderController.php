<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Controlador de Pedidos
 * 
 * Gestiona todas las operaciones relacionadas con los pedidos de productos,
 * incluyendo la creación desde el carrito, visualización de pedidos,
 * actualización de estados y gestión administrativa.
 * Maneja transacciones de base de datos y actualización de inventario.
 * 
 * @package App\Http\Controllers
 */
class OrderController extends Controller
{
    /**
     * Muestra el listado de todos los pedidos del usuario autenticado.
     * 
     * Obtiene todos los pedidos del usuario ordenados por fecha de creación
     * (más recientes primero) con paginación.
     * 
     * @return \Illuminate\View\View  Vista con el listado paginado de pedidos
     */
    public function index()
    {
        // Obtener pedidos del usuario ordenados por más recientes
        $orders = Auth::user()->orders()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Muestra los detalles de un pedido específico.
     * 
     * Verifica que el usuario tenga permisos para ver el pedido
     * (propietario o administrador). Puede responder en HTML completo
     * o HTML parcial para peticiones AJAX.
     * 
     * @param  \App\Models\Order  $order  El pedido a mostrar
     * @return \Illuminate\View\View|\Illuminate\Contracts\Support\Renderable  Vista o HTML renderizado
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function show(Order $order)
    {
        // Verificar permisos: propietario o administrador
        if ($order->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'No tienes permiso para ver este pedido.');
        }

        // Cargar items del pedido con información del producto
        $order->load('items.product');

        // Si es una petición AJAX, devolver solo HTML parcial
        if (request()->wantsJson() || request()->ajax()) {
            return view('admin.orders.details', compact('order'))->render();
        }

        return view('orders.show', compact('order'));
    }

    /**
     * Crea un nuevo pedido desde el carrito de compras.
     * 
     * Valida los datos, verifica el stock de cada producto, calcula totales
     * con IVA y costo de envío, crea el pedido con sus items y actualiza el inventario.
     * Toda la operación se realiza dentro de una transacción de base de datos.
     * 
     * @param  \Illuminate\Http\Request  $request  La solicitud con los datos del pedido
     * @return \Illuminate\Http\RedirectResponse  Redirección con mensaje de éxito o error
     */
    public function store(Request $request)
    {
        // Validar todos los datos del pedido y envío
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'shipping_name' => 'required|string|max:255',
            'shipping_email' => 'required|email',
            'shipping_phone' => 'required|string',
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string',
            'shipping_state' => 'required|string',
            'shipping_zipcode' => 'required|string',
            'shipping_country' => 'nullable|string',
            'notes' => 'nullable|string', // Notas adicionales del cliente
            'payment_method' => 'nullable|string', // Método de pago seleccionado
        ]);

        // Iniciar transacción de base de datos
        DB::beginTransaction();

        try {
            $subtotal = 0;
            $orderItems = [];

            // Procesar cada item: calcular subtotal y verificar stock
            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                // Verificar que hay suficiente stock
                if (!$product->hasStock($item['quantity'])) {
                    DB::rollBack();
                    return back()->with('error', "No hay suficiente stock para {$product->name}");
                }

                // Calcular subtotal del item
                $itemSubtotal = $product->price * $item['quantity'];
                $subtotal += $itemSubtotal;

                // Preparar item para el pedido
                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price, // Guardar precio al momento de la compra
                    'subtotal' => $itemSubtotal,
                ];
            }

            // Calcular impuestos y costo de envío
            $tax = $subtotal * 0.16; // 16% IVA
            $shipping = $subtotal >= 500 ? 0 : 100; // Envío gratis sobre $500
            $total = $subtotal + $tax + $shipping;

            // Crear el pedido principal
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => Order::generateOrderNumber(), // Generar número único
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping' => $shipping,
                'total' => $total,
                // Información de envío
                'shipping_name' => $validated['shipping_name'],
                'shipping_email' => $validated['shipping_email'],
                'shipping_phone' => $validated['shipping_phone'],
                'shipping_address' => $validated['shipping_address'],
                'shipping_city' => $validated['shipping_city'],
                'shipping_state' => $validated['shipping_state'],
                'shipping_zipcode' => $validated['shipping_zipcode'],
                'shipping_country' => $validated['shipping_country'] ?? 'México',
                'notes' => $validated['notes'],
                'payment_method' => $validated['payment_method'],
            ]);

            // Crear items del pedido y actualizar stock de productos
            foreach ($orderItems as $item) {
                // Crear item del pedido
                $order->items()->create($item);
                
                // Reducir stock del producto
                $product = Product::find($item['product_id']);
                $product->decrement('stock', $item['quantity']);
            }

            // Confirmar transacción
            DB::commit();

            return redirect()->route('orders.show', $order)
                ->with('success', 'Pedido creado exitosamente. Número de pedido: ' . $order->order_number);

        } catch (\Exception $e) {
            // Revertir transacción en caso de error
            DB::rollBack();
            return back()->with('error', 'Error al procesar el pedido: ' . $e->getMessage());
        }
    }

    /**
     * Actualiza el estado de un pedido.
     * 
     * Solo accesible para administradores. Permite cambiar el estado del pedido
     * y opcionalmente agregar número de seguimiento para envíos.
     * 
     * @param  \Illuminate\Http\Request  $request  La solicitud con el nuevo estado
     * @param  \App\Models\Order  $order  El pedido a actualizar
     * @return \Illuminate\Http\RedirectResponse  Redirección con mensaje de éxito
     */
    public function updateStatus(Request $request, Order $order)
    {
        // Validar el nuevo estado y número de seguimiento
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'tracking_number' => 'nullable|string', // Número de guía de envío
        ]);

        // Actualizar el pedido
        $order->update($validated);

        return back()->with('success', 'Estado del pedido actualizado exitosamente.');
    }

    /**
     * Muestra el listado de todos los pedidos (panel de administración).
     * 
     * Solo accesible para administradores. Permite filtrar por estado
     * y buscar por número de pedido.
     * 
     * @param  \Illuminate\Http\Request  $request  La solicitud con parámetros de filtrado
     * @return \Illuminate\View\View  Vista con el listado paginado de todos los pedidos
     */
    public function adminIndex(Request $request)
    {
        // Iniciar consulta con usuario relacionado
        $query = Order::with('user');

        // Filtrar por estado si se especifica
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Buscar por número de pedido
        if ($request->has('search') && $request->search != '') {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }

        // Ordenar por más recientes primero
        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }
}
