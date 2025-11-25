<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartApiController extends Controller
{
    /**
     * Obtener el carrito actual (desde sesión)
     */
    public function index(Request $request)
    {
        $cart = session()->get('cart', []);
        
        $cartDetails = $this->getCartDetails($cart);

        return response()->json([
            'success' => true,
            'data' => $cartDetails,
        ]);
    }

    /**
     * Agregar producto al carrito
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Verificar stock
        if (!$product->hasStock($request->quantity)) {
            return response()->json([
                'success' => false,
                'message' => "Solo hay {$product->stock} unidades disponibles",
            ], 400);
        }

        $cart = session()->get('cart', []);

        // Si el producto ya está en el carrito, actualizar cantidad
        if (isset($cart[$product->id])) {
            $newQuantity = $cart[$product->id]['quantity'] + $request->quantity;
            
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
                'quantity' => $request->quantity,
                'image' => $product->images[0] ?? null,
            ];
        }

        session()->put('cart', $cart);

        $cartDetails = $this->getCartDetails($cart);

        return response()->json([
            'success' => true,
            'message' => 'Producto agregado al carrito',
            'data' => $cartDetails,
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

            return response()->json([
                'success' => true,
                'message' => 'Producto eliminado del carrito',
                'data' => $this->getCartDetails($cart),
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

        $cartDetails = $this->getCartDetails($cart);

        return response()->json([
            'success' => true,
            'message' => 'Carrito actualizado',
            'data' => $cartDetails,
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

        $cartDetails = $this->getCartDetails($cart);

        return response()->json([
            'success' => true,
            'message' => 'Producto eliminado del carrito',
            'data' => $cartDetails,
        ]);
    }

    /**
     * Limpiar el carrito completo
     */
    public function clear()
    {
        session()->forget('cart');

        return response()->json([
            'success' => true,
            'message' => 'Carrito vaciado',
            'data' => [
                'items' => [],
                'count' => 0,
                'subtotal' => 0,
                'total' => 0,
            ],
        ]);
    }

    /**
     * Obtener detalles del carrito con cálculos
     */
    private function getCartDetails($cart)
    {
        $items = [];
        $subtotal = 0;

        foreach ($cart as $item) {
            $itemTotal = $item['price'] * $item['quantity'];
            $subtotal += $itemTotal;

            $items[] = [
                'id' => $item['id'],
                'name' => $item['name'],
                'slug' => $item['slug'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'image' => $item['image'],
                'subtotal' => $itemTotal,
                'url' => route('products.show', $item['slug']),
            ];
        }

        return [
            'items' => $items,
            'count' => count($items),
            'total_items' => array_sum(array_column($items, 'quantity')),
            'subtotal' => round($subtotal, 2),
            'tax' => round($subtotal * 0.16, 2), // 16% IVA
            'total' => round($subtotal * 1.16, 2),
        ];
    }
}

