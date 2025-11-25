<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Mostrar la vista del carrito
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        
        // Obtener detalles completos de los productos en el carrito
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
        
        $tax = $subtotal * 0.16; // 16% IVA
        $total = $subtotal + $tax;
        
        return view('cart.index', compact('cartItems', 'subtotal', 'tax', 'total'));
    }

    /**
     * Agregar producto al carrito
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $quantity = $request->quantity ?? 1;

        // Verificar stock
        if (!$product->hasStock($quantity)) {
            return response()->json([
                'success' => false,
                'message' => "Solo hay {$product->stock} unidades disponibles",
            ], 400);
        }

        $cart = session()->get('cart', []);

        // Si el producto ya está en el carrito, actualizar cantidad
        if (isset($cart[$product->id])) {
            $newQuantity = $cart[$product->id]['quantity'] + $quantity;
            
            if (!$product->hasStock($newQuantity)) {
                return response()->json([
                    'success' => false,
                    'message' => "No hay suficiente stock disponible",
                ], 400);
            }
            
            $cart[$product->id]['quantity'] = $newQuantity;
        } else {
            // Agregar nuevo producto al carrito
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => (float) $product->price,
                'quantity' => $quantity,
                'image' => $product->images[0] ?? null,
            ];
        }

        session()->put('cart', $cart);

        // Calcular total de items
        $totalItems = array_sum(array_column($cart, 'quantity'));

        return response()->json([
            'success' => true,
            'message' => 'Producto agregado al carrito',
            'total_items' => $totalItems,
        ]);
    }

    /**
     * Actualizar cantidad de un producto
     */
    public function update(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $cart = session()->get('cart', []);

        if (!isset($cart[$productId])) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado en el carrito',
            ], 404);
        }

        // Si la cantidad es 0, eliminar del carrito
        if ($request->quantity == 0) {
            unset($cart[$productId]);
            session()->put('cart', $cart);

            $totalItems = array_sum(array_column($cart, 'quantity'));

            return response()->json([
                'success' => true,
                'message' => 'Producto eliminado del carrito',
                'total_items' => $totalItems,
            ]);
        }

        // Verificar stock
        $product = Product::findOrFail($productId);
        if (!$product->hasStock($request->quantity)) {
            return response()->json([
                'success' => false,
                'message' => "Solo hay {$product->stock} unidades disponibles",
            ], 400);
        }

        $cart[$productId]['quantity'] = $request->quantity;
        session()->put('cart', $cart);

        $totalItems = array_sum(array_column($cart, 'quantity'));

        return response()->json([
            'success' => true,
            'message' => 'Carrito actualizado',
            'total_items' => $totalItems,
        ]);
    }

    /**
     * Eliminar producto del carrito
     */
    public function remove($productId)
    {
        $cart = session()->get('cart', []);

        if (!isset($cart[$productId])) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado en el carrito',
            ], 404);
        }

        unset($cart[$productId]);
        session()->put('cart', $cart);

        $totalItems = array_sum(array_column($cart, 'quantity'));

        return response()->json([
            'success' => true,
            'message' => 'Producto eliminado del carrito',
            'total_items' => $totalItems,
        ]);
    }

    /**
     * Obtener conteo del carrito
     */
    public function count()
    {
        $cart = session()->get('cart', []);
        $totalItems = array_sum(array_column($cart, 'quantity'));

        return response()->json([
            'success' => true,
            'total_items' => $totalItems,
        ]);
    }

    /**
     * Limpiar el carrito
     */
    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Carrito vaciado');
    }
}
