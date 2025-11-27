<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Mostrar todos los productos
     */
    public function index(Request $request)
    {
        $query = Product::with('category');
        
        // Si es para el admin, mostrar todos los productos
        if ($request->routeIs('admin.products.index')) {
            // No filtrar por is_active
        } else {
            $query->where('is_active', true);
        }

        // Filtrar por categoría
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        // Filtrar por búsqueda
        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Ordenar
        $sortBy = $request->get('sort', 'created_at');
        $order = $request->get('order', 'desc');
        $query->orderBy($sortBy, $order);

        $products = $query->paginate(12);
        $categories = Category::where('is_active', true)->get();

        // Determinar la vista según la ruta
        if ($request->routeIs('admin.products.index')) {
            return view('admin.products.index', compact('products', 'categories'));
        }

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Mostrar un producto específico
     */
    public function show(Product $product)
    {
        $product->load(['category', 'reviews.user']);
        
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    /**
     * Guardar nuevo producto (admin/nutricionista)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'nullable|string|unique:products,sku',
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048',
            'benefits' => 'nullable|string',
            'ingredients' => 'nullable|string',
            'weight' => 'nullable|string',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        
        // Asegurar que is_active tiene valor
        if (!isset($validated['is_active'])) {
            $validated['is_active'] = true;
        }

        // Procesar imágenes
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('products', 'public');
            }
            $validated['images'] = $images;
        }

        $product = Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Producto creado exitosamente.');
    }

    /**
     * Actualizar producto
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'nullable|string|unique:products,sku,' . $product->id,
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048',
            'benefits' => 'nullable|string',
            'ingredients' => 'nullable|string',
            'weight' => 'nullable|string',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        if ($request->has('name')) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Procesar imágenes nuevas
        if ($request->hasFile('images')) {
            $images = $product->images ?? [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('products', 'public');
            }
            $validated['images'] = $images;
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Producto actualizado exitosamente.');
    }

    /**
     * Eliminar producto
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Producto eliminado exitosamente.');
    }

    /**
     * Eliminar una imagen individual del producto
     */
    public function removeImage(Request $request, Product $product)
    {
        $validated = $request->validate([
            'image' => 'required|string'
        ]);

        $imagePath = $validated['image'];
        $images = $product->images ?? [];

        // Buscar y eliminar la imagen del array
        $imageIndex = array_search($imagePath, $images);
        
        if ($imageIndex !== false) {
            // Eliminar el archivo físico
            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            // Eliminar del array
            unset($images[$imageIndex]);
            
            // Reindexar el array
            $images = array_values($images);

            // Actualizar el producto
            $product->update(['images' => $images]);

            return response()->json([
                'success' => true,
                'message' => 'Imagen eliminada correctamente'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Imagen no encontrada'
        ], 404);
    }
}

