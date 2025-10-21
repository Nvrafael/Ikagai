<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Guardar nueva reseña
     */
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'comment' => 'required|string',
        ]);

        // Verificar si el usuario ya dejó una reseña para este producto
        $existingReview = Review::where('product_id', $product->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Ya has dejado una reseña para este producto.');
        }

        $validated['product_id'] = $product->id;
        $validated['user_id'] = Auth::id();

        // Verificar si el usuario compró el producto
        $hasPurchased = Auth::user()->orders()
            ->whereHas('items', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })
            ->where('status', 'delivered')
            ->exists();

        $validated['is_verified_purchase'] = $hasPurchased;

        Review::create($validated);

        return back()->with('success', 'Reseña publicada exitosamente.');
    }

    /**
     * Actualizar reseña
     */
    public function update(Request $request, Review $review)
    {
        // Verificar que el usuario sea el autor
        if ($review->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para editar esta reseña.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'comment' => 'required|string',
        ]);

        $review->update($validated);

        return back()->with('success', 'Reseña actualizada exitosamente.');
    }

    /**
     * Eliminar reseña
     */
    public function destroy(Review $review)
    {
        // Verificar que el usuario sea el autor o admin
        if ($review->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'No tienes permiso para eliminar esta reseña.');
        }

        $review->delete();

        return back()->with('success', 'Reseña eliminada exitosamente.');
    }

    /**
     * Aprobar/rechazar reseña (admin)
     */
    public function approve(Review $review)
    {
        $review->update(['is_approved' => true]);

        return back()->with('success', 'Reseña aprobada exitosamente.');
    }
}

