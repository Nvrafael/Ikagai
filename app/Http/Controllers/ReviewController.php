<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador de Reseñas
 * 
 * Gestiona todas las operaciones relacionadas con las reseñas de productos,
 * incluyendo la creación, actualización, eliminación y aprobación de reseñas.
 * Verifica las compras del usuario y gestiona el estado de verificación.
 * 
 * @package App\Http\Controllers
 */
class ReviewController extends Controller
{
    /**
     * Crea y almacena una nueva reseña para un producto.
     * 
     * Verifica que el usuario no haya dejado una reseña previamente para el mismo producto
     * y determina si la reseña es de una compra verificada.
     * 
     * @param  \Illuminate\Http\Request  $request  La solicitud con los datos de la reseña
     * @param  \App\Models\Product  $product  El producto a reseñar
     * @return \Illuminate\Http\RedirectResponse  Redirección con mensaje de éxito o error
     */
    public function store(Request $request, Product $product)
    {
        // Validar los datos de la reseña
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5', // Calificación de 1 a 5 estrellas
            'title' => 'nullable|string|max:255', // Título opcional de la reseña
            'comment' => 'required|string', // Comentario requerido
        ]);

        // Verificar si el usuario ya dejó una reseña para este producto
        $existingReview = Review::where('product_id', $product->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Ya has dejado una reseña para este producto.');
        }

        // Asignar el producto y usuario a la reseña
        $validated['product_id'] = $product->id;
        $validated['user_id'] = Auth::id();

        // Verificar si el usuario ha comprado el producto
        $hasPurchased = Auth::user()->orders()
            ->whereHas('items', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })
            ->where('status', 'delivered') // Solo pedidos entregados
            ->exists();

        // Marcar como compra verificada si corresponde
        $validated['is_verified_purchase'] = $hasPurchased;

        // Crear la reseña
        Review::create($validated);

        return back()->with('success', 'Reseña publicada exitosamente.');
    }

    /**
     * Actualiza una reseña existente.
     * 
     * Solo el autor de la reseña puede actualizarla.
     * 
     * @param  \Illuminate\Http\Request  $request  La solicitud con los datos actualizados
     * @param  \App\Models\Review  $review  La reseña a actualizar
     * @return \Illuminate\Http\RedirectResponse  Redirección con mensaje de éxito
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function update(Request $request, Review $review)
    {
        // Verificar que el usuario sea el autor de la reseña
        if ($review->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para editar esta reseña.');
        }

        // Validar los datos actualizados
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'comment' => 'required|string',
        ]);

        // Actualizar la reseña
        $review->update($validated);

        return back()->with('success', 'Reseña actualizada exitosamente.');
    }

    /**
     * Elimina una reseña de la base de datos.
     * 
     * Solo el autor de la reseña o un administrador pueden eliminarla.
     * 
     * @param  \App\Models\Review  $review  La reseña a eliminar
     * @return \Illuminate\Http\RedirectResponse  Redirección con mensaje de éxito
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function destroy(Review $review)
    {
        // Verificar que el usuario sea el autor o administrador
        if ($review->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'No tienes permiso para eliminar esta reseña.');
        }

        // Eliminar la reseña
        $review->delete();

        return back()->with('success', 'Reseña eliminada exitosamente.');
    }

    /**
     * Aprueba una reseña (solo para administradores).
     * 
     * Marca una reseña como aprobada, haciéndola visible públicamente.
     * 
     * @param  \App\Models\Review  $review  La reseña a aprobar
     * @return \Illuminate\Http\RedirectResponse  Redirección con mensaje de éxito
     */
    public function approve(Review $review)
    {
        // Marcar la reseña como aprobada
        $review->update(['is_approved' => true]);

        return back()->with('success', 'Reseña aprobada exitosamente.');
    }
}

