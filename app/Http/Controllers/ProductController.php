<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

/**
 * Controlador de Productos
 * 
 * Gestiona todas las operaciones CRUD relacionadas con los productos
 * de la tienda, incluyendo la gestión de imágenes, categorías, stock
 * e información nutricional.
 * 
 * @package App\Http\Controllers
 */
class ProductController extends Controller
{
    /**
     * Muestra el listado de productos con opciones de filtrado y búsqueda.
     * 
     * Puede mostrar dos vistas diferentes:
     * - Vista de administración (todos los productos)
     * - Vista pública (solo productos activos)
     * 
     * Permite filtrar por categoría, búsqueda por texto y ordenamiento.
     * 
     * @param  \Illuminate\Http\Request  $request  La solicitud con parámetros de filtrado
     * @return \Illuminate\View\View  Vista con el listado paginado de productos
     */
    public function index(Request $request)
    {
        // Iniciar consulta con categorías relacionadas
        $query = Product::with('category');
        
        // Si es ruta de administración, mostrar todos los productos
        if ($request->routeIs('admin.products.index')) {
            // No filtrar por is_active para que el admin vea todos
        } else {
            // En vista pública, solo mostrar productos activos
            $query->where('is_active', true);
        }

        // Filtrar por categoría si se especifica
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        // Búsqueda por nombre o descripción
        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Ordenamiento (por defecto: más recientes primero)
        $sortBy = $request->get('sort', 'created_at');
        $order = $request->get('order', 'desc');
        $query->orderBy($sortBy, $order);

        // Paginar resultados (12 productos por página)
        $products = $query->paginate(12);
        
        // Obtener categorías activas para filtros
        $categories = Category::where('is_active', true)->get();

        // Determinar la vista según la ruta
        if ($request->routeIs('admin.products.index')) {
            return view('admin.products.index', compact('products', 'categories'));
        }

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Muestra el formulario de creación de producto.
     * 
     * Solo accesible para administradores y nutricionistas.
     * 
     * @return \Illuminate\View\View  Vista con el formulario de creación
     */
    public function create()
    {
        // Obtener categorías activas ordenadas alfabéticamente
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Muestra los detalles de un producto específico.
     * 
     * Incluye la información del producto, categoría, reseñas de usuarios
     * y productos relacionados de la misma categoría.
     * 
     * @param  \App\Models\Product  $product  El producto a mostrar
     * @return \Illuminate\View\View  Vista con los detalles del producto
     */
    public function show(Product $product)
    {
        // Cargar relaciones: categoría y reseñas con usuarios
        $product->load(['category', 'reviews.user']);
        
        // Obtener hasta 4 productos relacionados de la misma categoría
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    /**
     * Crea y almacena un nuevo producto en la base de datos.
     * 
     * Solo accesible para administradores y nutricionistas.
     * Valida los datos, genera el slug automáticamente y procesa múltiples imágenes.
     * 
     * @param  \Illuminate\Http\Request  $request  La solicitud con los datos del producto
     * @return \Illuminate\Http\RedirectResponse  Redirección con mensaje de éxito
     */
    public function store(Request $request)
    {
        // Validar todos los campos del producto
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0', // Precio de comparación (antes)
            'stock' => 'required|integer|min:0',
            'sku' => 'nullable|string|unique:products,sku', // Código único del producto
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048', // Cada imagen máximo 2MB
            'benefits' => 'nullable|string', // Beneficios del producto
            'ingredients' => 'nullable|string', // Ingredientes
            'weight' => 'nullable|string', // Peso o presentación
            'is_featured' => 'boolean', // Producto destacado
            'is_active' => 'boolean', // Producto activo/visible
        ]);

        // Generar slug automáticamente desde el nombre
        $validated['slug'] = Str::slug($validated['name']);
        
        // Asegurar que is_active tenga valor por defecto
        if (!isset($validated['is_active'])) {
            $validated['is_active'] = true;
        }

        // Procesar y almacenar múltiples imágenes
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('products', 'public');
            }
            $validated['images'] = $images;
        }

        // Crear el producto en la base de datos
        $product = Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Producto creado exitosamente.');
    }

    /**
     * Actualiza un producto existente en la base de datos.
     * 
     * Permite modificar todos los datos del producto, incluyendo agregar
     * nuevas imágenes sin eliminar las existentes. Regenera el slug si el nombre cambia.
     * 
     * @param  \Illuminate\Http\Request  $request  La solicitud con los datos actualizados
     * @param  \App\Models\Product  $product  El producto a actualizar
     * @return \Illuminate\Http\RedirectResponse  Redirección con mensaje de éxito
     */
    public function update(Request $request, Product $product)
    {
        // Validar los datos actualizados
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'nullable|string|unique:products,sku,' . $product->id, // Excluir el producto actual
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048',
            'benefits' => 'nullable|string',
            'ingredients' => 'nullable|string',
            'weight' => 'nullable|string',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Regenerar slug si el nombre cambió
        if ($request->has('name')) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Procesar imágenes nuevas (mantener las existentes)
        if ($request->hasFile('images')) {
            $images = $product->images ?? [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('products', 'public');
            }
            $validated['images'] = $images;
        }

        // Actualizar el producto
        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Producto actualizado exitosamente.');
    }

    /**
     * Elimina un producto de la base de datos.
     * 
     * Elimina permanentemente el producto. Las reseñas y pedidos asociados
     * seguirán existiendo con referencia al producto eliminado.
     * 
     * @param  \App\Models\Product  $product  El producto a eliminar
     * @return \Illuminate\Http\RedirectResponse  Redirección con mensaje de éxito
     */
    public function destroy(Product $product)
    {
        // Eliminar el producto
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Producto eliminado exitosamente.');
    }

    /**
     * Elimina una imagen individual de un producto.
     * 
     * Elimina el archivo físico del almacenamiento y actualiza el array
     * de imágenes del producto. Responde en formato JSON para peticiones AJAX.
     * 
     * @param  \Illuminate\Http\Request  $request  La solicitud con la ruta de la imagen
     * @param  \App\Models\Product  $product  El producto dueño de la imagen
     * @return \Illuminate\Http\JsonResponse  Respuesta JSON con el resultado
     */
    public function removeImage(Request $request, Product $product)
    {
        // Validar que se proporcionó la ruta de la imagen
        $validated = $request->validate([
            'image' => 'required|string'
        ]);

        $imagePath = $validated['image'];
        $images = $product->images ?? [];

        // Buscar y eliminar la imagen del array
        $imageIndex = array_search($imagePath, $images);
        
        if ($imageIndex !== false) {
            // Eliminar el archivo físico del almacenamiento
            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            // Eliminar del array de imágenes
            unset($images[$imageIndex]);
            
            // Reindexar el array (mantener índices consecutivos)
            $images = array_values($images);

            // Actualizar el producto con el nuevo array de imágenes
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
