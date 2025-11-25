<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    /**
     * Búsqueda y filtrado de productos
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'reviews'])
            ->where('is_active', true);

        // Búsqueda por término
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtrar por categoría
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filtrar por rango de precio
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filtrar por disponibilidad
        if ($request->has('in_stock') && $request->in_stock) {
            $query->where('stock', '>', 0);
        }

        // Filtrar productos destacados
        if ($request->has('featured') && $request->featured) {
            $query->where('is_featured', true);
        }

        // Ordenamiento
        switch ($request->get('sort_by', 'name')) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('name', 'asc');
        }

        $perPage = min($request->get('per_page', 12), 50);
        $products = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $products->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'description' => $product->description,
                    'price' => (float) $product->price,
                    'compare_price' => $product->compare_price ? (float) $product->compare_price : null,
                    'stock' => $product->stock,
                    'images' => $product->images,
                    'is_featured' => $product->is_featured,
                    'category' => $product->category ? [
                        'id' => $product->category->id,
                        'name' => $product->category->name,
                        'slug' => $product->category->slug,
                    ] : null,
                    'average_rating' => round($product->averageRating() ?? 0, 1),
                    'reviews_count' => $product->reviews->count(),
                    'url' => route('products.show', $product->slug),
                ];
            }),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
        ]);
    }

    /**
     * Obtener un producto específico
     */
    public function show($id)
    {
        $product = Product::with(['category', 'reviews.user'])
            ->where('is_active', true)
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'description' => $product->description,
                'price' => (float) $product->price,
                'compare_price' => $product->compare_price ? (float) $product->compare_price : null,
                'stock' => $product->stock,
                'sku' => $product->sku,
                'images' => $product->images,
                'is_featured' => $product->is_featured,
                'benefits' => $product->benefits,
                'ingredients' => $product->ingredients,
                'weight' => $product->weight,
                'category' => $product->category ? [
                    'id' => $product->category->id,
                    'name' => $product->category->name,
                    'slug' => $product->category->slug,
                ] : null,
                'average_rating' => round($product->averageRating() ?? 0, 1),
                'reviews_count' => $product->reviews->count(),
                'reviews' => $product->reviews->take(5)->map(function($review) {
                    return [
                        'id' => $review->id,
                        'rating' => $review->rating,
                        'comment' => $review->comment,
                        'user_name' => $review->user->name,
                        'created_at' => $review->created_at->format('d/m/Y'),
                    ];
                }),
                'url' => route('products.show', $product->slug),
            ],
        ]);
    }

    /**
     * Verificar disponibilidad de stock
     */
    public function checkStock(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($id);

        $available = $product->hasStock($request->quantity);

        return response()->json([
            'success' => true,
            'data' => [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'requested_quantity' => (int) $request->quantity,
                'available_stock' => $product->stock,
                'is_available' => $available,
                'message' => $available 
                    ? 'Producto disponible' 
                    : "Solo hay {$product->stock} unidades disponibles",
            ],
        ]);
    }

    /**
     * Búsqueda rápida (autocompletado)
     */
    public function quickSearch(Request $request)
    {
        $search = $request->get('q', '');

        if (strlen($search) < 2) {
            return response()->json([
                'success' => true,
                'data' => [],
            ]);
        }

        $products = Product::where('is_active', true)
            ->where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            })
            ->take(10)
            ->get(['id', 'name', 'slug', 'price', 'images']);

        return response()->json([
            'success' => true,
            'data' => $products->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => (float) $product->price,
                    'image' => $product->images[0] ?? null,
                    'url' => route('products.show', $product->slug),
                ];
            }),
        ]);
    }
}

