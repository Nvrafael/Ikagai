<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index');
        }

        $cartItems = [];
        $subtotal = 0;
        
        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);
            if ($product) {
                $itemTotal = $product->price * $item['quantity'];
                $subtotal += $itemTotal;
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'subtotal' => $itemTotal,
                ];
            }
        }
        
        $tax = $subtotal * 0.16;
        $total = $subtotal + $tax;

        return view('checkout.index', compact('cartItems', 'subtotal', 'tax', 'total'));
    }

    public function store(Request $request)
    {
        Log::info('Checkout store initiated', $request->all());

        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_email' => 'required|email',
            'shipping_phone' => 'required|string',
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string',
            'shipping_state' => 'required|string',
            'shipping_zipcode' => 'required|string',
            'shipping_country' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            Log::warning('Checkout attempted with empty cart');
            return redirect()->route('cart.index')->with('error', 'El carrito está vacío.');
        }

        try {
            DB::beginTransaction();
            Log::info('DB transaction started');

            // 1. Calcular totales
            // ... (rest of the logic) ...
            $subtotal = 0;
            $lineItems = [];
            
            foreach ($cart as $productId => $item) {
                $product = Product::lockForUpdate()->find($productId);
                
                if (!$product || !$product->hasStock($item['quantity'])) {
                    throw new \Exception("El producto {$product->name} no tiene suficiente stock.");
                }

                $itemTotal = $product->price * $item['quantity'];
                $subtotal += $itemTotal;

                // Preparar item para Stripe
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'mxn',
                        'product_data' => [
                            'name' => $product->name,
                            'images' => $product->images ? [asset('storage/' . $product->images[0])] : [],
                        ],
                        'unit_amount' => (int) ($product->price * 100), // En centavos
                    ],
                    'quantity' => $item['quantity'],
                ];
            }

            $tax = $subtotal * 0.16;
            $total = $subtotal + $tax;

            // 2. Crear Orden Local
            $order = Order::create([
                'user_id' => Auth::id(), // Puede ser null si permitimos guest checkout, pero por ahora asumimos auth
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'status' => 'pending',
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping' => 0, // Por ahora envío gratis o fijo
                'total' => $total,
                'payment_method' => 'stripe',
                'payment_status' => 'pending',
                'shipping_name' => $request->shipping_name,
                'shipping_email' => $request->shipping_email,
                'shipping_phone' => $request->shipping_phone,
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'shipping_state' => $request->shipping_state,
                'shipping_zipcode' => $request->shipping_zipcode,
                'shipping_country' => $request->shipping_country ?? 'México',
                'notes' => $request->notes,
            ]);

            // 3. Crear Items de Orden y descontar stock
            foreach ($cart as $productId => $item) {
                $product = Product::find($productId);
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $product->price * $item['quantity'],
                ]);

                $product->decrementStock($item['quantity']);
            }

            DB::commit();

            // 4. Crear Sesión de Stripe
            Stripe::setApiKey(config('services.stripe.secret'));
            Log::info('Stripe API Key set');

            // Agregar impuestos a Stripe (simplificado, agregamos un item de impuesto)
            if ($tax > 0) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'mxn',
                        'product_data' => [
                            'name' => 'IVA (16%)',
                        ],
                        'unit_amount' => (int) ($tax * 100),
                    ],
                    'quantity' => 1,
                ];
            }

            Log::info('Creating Stripe Session');
            $checkoutSession = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('checkout.cancel'),
                'client_reference_id' => $order->id,
                'customer_email' => $request->shipping_email,
                'metadata' => [
                    'order_id' => $order->id,
                ],
            ]);
            Log::info('Stripe Session created: ' . $checkoutSession->url);

            return redirect($checkoutSession->url);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout Error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return back()->with('error', 'Error al procesar el pedido: ' . $e->getMessage());
        }
    }

    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            return redirect()->route('cart.index');
        }

        Stripe::setApiKey(config('services.stripe.secret'));
        
        try {
            $session = Session::retrieve($sessionId);
            $orderId = $session->client_reference_id;
            
            $order = Order::findOrFail($orderId);

            if ($session->payment_status === 'paid') {
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'processing'
                ]);
                
                // Vaciar carrito
                session()->forget('cart');
                
                return view('checkout.success', compact('order'));
            }

            return redirect()->route('cart.index')->with('error', 'El pago no se completó correctamente.');

        } catch (\Exception $e) {
            return redirect()->route('cart.index')->with('error', 'Error al verificar el pago.');
        }
    }

    public function cancel()
    {
        return view('checkout.cancel');
    }
}
