<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Mostrar todos los pedidos del usuario
     */
    public function index()
    {
        $orders = Auth::user()->orders()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Mostrar un pedido específico
     */
    public function show(Order $order)
    {
        // Verificar que el usuario sea el dueño o admin
        if ($order->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'No tienes permiso para ver este pedido.');
        }

        $order->load('items.product');

        return view('orders.show', compact('order'));
    }

    /**
     * Crear nuevo pedido desde el carrito
     */
    public function store(Request $request)
    {
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
            'notes' => 'nullable|string',
            'payment_method' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $subtotal = 0;
            $orderItems = [];

            // Calcular subtotal y verificar stock
            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                if (!$product->hasStock($item['quantity'])) {
                    DB::rollBack();
                    return back()->with('error', "No hay suficiente stock para {$product->name}");
                }

                $itemSubtotal = $product->price * $item['quantity'];
                $subtotal += $itemSubtotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $itemSubtotal,
                ];
            }

            // Calcular impuestos y envío (puedes personalizar esto)
            $tax = $subtotal * 0.16; // 16% IVA
            $shipping = $subtotal >= 500 ? 0 : 100; // Envío gratis sobre $500
            $total = $subtotal + $tax + $shipping;

            // Crear pedido
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => Order::generateOrderNumber(),
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping' => $shipping,
                'total' => $total,
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

            // Crear items del pedido y actualizar stock
            foreach ($orderItems as $item) {
                $order->items()->create($item);
                
                $product = Product::find($item['product_id']);
                $product->decrement('stock', $item['quantity']);
            }

            DB::commit();

            return redirect()->route('orders.show', $order)
                ->with('success', 'Pedido creado exitosamente. Número de pedido: ' . $order->order_number);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al procesar el pedido: ' . $e->getMessage());
        }
    }

    /**
     * Actualizar estado del pedido (admin)
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'tracking_number' => 'nullable|string',
        ]);

        $order->update($validated);

        return back()->with('success', 'Estado del pedido actualizado exitosamente.');
    }

    /**
     * Listar todos los pedidos (admin)
     */
    public function adminIndex(Request $request)
    {
        $query = Order::with('user');

        // Filtros
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('orders.admin-index', compact('orders'));
    }
}
