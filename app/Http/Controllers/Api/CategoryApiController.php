<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryApiController extends Controller
{
    /**
     * Listar todas las categorías con conteo de productos
     */
    public function index()
    {
        $categories = Category::withCount('products')->get();

        return response()->json([
            'success' => true,
            'data' => $categories->map(function($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'description' => $category->description,
                    'products_count' => $category->products_count,
                    'url' => route('categories.show', $category->slug),
                ];
            }),
        ]);
    }

    /**
     * Obtener una categoría con sus productos
     */
    public function show($id)
    {
        $category = Category::with('products')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => $category->description,
                'products_count' => $category->products->count(),
                'products' => $category->products->map(function($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'slug' => $product->slug,
                        'price' => (float) $product->price,
                        'image' => $product->images[0] ?? null,
                        'url' => route('products.show', $product->slug),
                    ];
                }),
                'url' => route('categories.show', $category->slug),
            ],
        ]);
    }
}

