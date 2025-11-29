<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

/**
 * Controlador de Carrito de Compras
 * 
 * Gestiona todas las operaciones del carrito de compras almacenado en sesión.
 * Incluye agregar, actualizar, eliminar productos y calcular totales con IVA.
 * El carrito se mantiene en la sesión del usuario sin requerir autenticación.
 * 
 * @package App\Http\Controllers
 */
class CartController extends Controller
{
    /**
     * Muestra la vista del carrito de compras.
     * 
     * Obtiene los productos del carrito desde la sesión, calcula subtotales,
     * aplica el IVA (16%) y muestra el total a pagar.
     * 
     * @return \Illuminate\View\View  Vista con el contenido del carrito
     */
    public function index()
    {
        // Obtener carrito de la sesión (array vacío si no existe)
        $cart = session()->get('cart', []);
        
        // Preparar array para los items del carrito con información completa
        $cartItems = [];
        $subtotal = 0;
        
        // Procesar cada item del carrito
        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);
            
            if ($product) {
                // Calcular subtotal del item
                $itemTotal = $product->price * $item['quantity'];
                $subtotal += $itemTotal;
                
                // Agregar item con información completa
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'subtotal' => $itemTotal,
                ];
            }
        }
        
        // Calcular impuestos y total
        $tax = $subtotal * 0.16; // 16% IVA
        $total = $subtotal + $tax;
        
        return view('cart.index', compact('cartItems', 'subtotal', 'tax', 'total'));
    }

    /**
     * Agrega un producto al carrito o incrementa su cantidad.
     * 
     * Verifica la disponibilidad de stock antes de agregar el producto.
     * Si el producto ya existe en el carrito, incrementa la cantidad.
     * Responde en formato JSON para peticiones AJAX.
     * 
     * @param  \Illuminate\Http\Request  $request  La solicitud con ID y cantidad del producto
     * @return \Illuminate\Http\JsonResponse  Respuesta JSON con el resultado
     */
    public function add(Request $request)
    {
        // Validar los datos recibidos
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1',
        ]);

        // Obtener el producto
        $product = Product::findOrFail($request->product_id);
        $quantity = $request->quantity ?? 1;

        // Verificar que hay suficiente stock disponible
        if (!$product->hasStock($quantity)) {
            return response()->json([
                'success' => false,
                'message' => "Solo hay {$product->stock} unidades disponibles",
            ], 400);
        }

        // Obtener carrito actual de la sesión
        $cart = session()->get('cart', []);

        // Si el producto ya está en el carrito, actualizar cantidad
        if (isset($cart[$product->id])) {
            $newQuantity = $cart[$product->id]['quantity'] + $quantity;
            
            // Verificar stock para la nueva cantidad total
            if (!$product->hasStock($newQuantity)) {
                return response()->json([
                    'success' => false,
                    'message' => "No hay suficiente stock disponible",
                ], 400);
            }
            
            $cart[$product->id]['quantity'] = $newQuantity;
        } else {
            // Agregar nuevo producto al carrito con toda su información
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => (float) $product->price,
                'quantity' => $quantity,
                'image' => $product->images[0] ?? null, // Primera imagen o null
            ];
        }

        // Guardar carrito actualizado en la sesión
        session()->put('cart', $cart);

        // Calcular total de items en el carrito
        $totalItems = array_sum(array_column($cart, 'quantity'));

        return response()->json([
            'success' => true,
            'message' => 'Producto agregado al carrito',
            'total_items' => $totalItems,
        ]);
    }

    /**
     * Actualiza la cantidad de un producto en el carrito.
     * 
     * Si la cantidad es 0, elimina el producto del carrito.
     * Verifica el stock disponible antes de actualizar.
     * Responde en formato JSON para peticiones AJAX.
     * 
     * @param  \Illuminate\Http\Request  $request  La solicitud con la nueva cantidad
     * @param  int  $productId  El ID del producto a actualizar
     * @return \Illuminate\Http\JsonResponse  Respuesta JSON con el resultado
     */
    public function update(Request $request, $productId)
    {
        // Validar la nueva cantidad
        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        // Obtener carrito de la sesión
        $cart = session()->get('cart', []);

        // Verificar que el producto esté en el carrito
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

        // Verificar stock disponible para la nueva cantidad
        $product = Product::findOrFail($productId);
        if (!$product->hasStock($request->quantity)) {
            return response()->json([
                'success' => false,
                'message' => "Solo hay {$product->stock} unidades disponibles",
            ], 400);
        }

        // Actualizar cantidad en el carrito
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
     * Elimina un producto específico del carrito.
     * 
     * Responde en formato JSON para peticiones AJAX.
     * 
     * @param  int  $productId  El ID del producto a eliminar
     * @return \Illuminate\Http\JsonResponse  Respuesta JSON con el resultado
     */
    public function remove($productId)
    {
        // Obtener carrito de la sesión
        $cart = session()->get('cart', []);

        // Verificar que el producto esté en el carrito
        if (!isset($cart[$productId])) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado en el carrito',
            ], 404);
        }

        // Eliminar el producto del carrito
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
     * Obtiene el conteo total de items en el carrito.
     * 
     * Útil para actualizar badges o indicadores del carrito en la interfaz.
     * Responde en formato JSON para peticiones AJAX.
     * 
     * @return \Illuminate\Http\JsonResponse  Respuesta JSON con el total de items
     */
    public function count()
    {
        // Obtener carrito de la sesión
        $cart = session()->get('cart', []);
        
        // Calcular total de items (suma de todas las cantidades)
        $totalItems = array_sum(array_column($cart, 'quantity'));

        return response()->json([
            'success' => true,
            'total_items' => $totalItems,
        ]);
    }

    /**
     * Vacía completamente el carrito de compras.
     * 
     * Elimina todos los productos del carrito y limpia la sesión.
     * 
     * @return \Illuminate\Http\RedirectResponse  Redirección con mensaje de éxito
     */
    public function clear()
    {
        // Eliminar carrito de la sesión
        session()->forget('cart');
        
        return redirect()->route('cart.index')->with('success', 'Carrito vaciado');
    }
}
