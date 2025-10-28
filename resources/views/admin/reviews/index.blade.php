@extends('admin.layout')

@section('title', 'Reseñas')

@section('content')

<!-- Page Header -->
<div class="mb-12">
    <h1 class="text-4xl font-light text-black tracking-tight mb-2">Reseñas</h1>
    <p class="text-sm text-gray-500">Moderación de reseñas de productos</p>
</div>

<!-- Table -->
<div class="border border-gray-200">
    <table class="w-full">
        <thead>
            <tr class="border-b border-gray-200">
                <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">ID</th>
                <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">Producto</th>
                <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">Usuario</th>
                <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">Calificación</th>
                <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">Comentario</th>
                <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">Estado</th>
                <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">Fecha</th>
                <th class="text-left px-6 py-4 text-xs text-gray-500 uppercase tracking-wider font-medium">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reviews as $review)
            <tr class="border-b border-gray-100 last:border-0 hover:bg-gray-50 transition-colors duration-200">
                <td class="px-6 py-4 text-sm text-gray-500 font-mono">{{ $review->id }}</td>
                <td class="px-6 py-4 text-sm text-black">{{ $review->product->name }}</td>
                <td class="px-6 py-4">
                    <div class="text-sm text-black">{{ $review->user->name }}</div>
                    @if($review->is_verified_purchase)
                    <span class="inline-block mt-1 px-2 py-0.5 text-xs font-medium uppercase tracking-wider border bg-green-50 text-green-900 border-green-200">
                        ✓ Compra verificada
                    </span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-1 text-yellow-500">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $review->rating)
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                </svg>
                            @else
                                <svg class="w-4 h-4 fill-current text-gray-300" viewBox="0 0 20 20">
                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                </svg>
                            @endif
                        @endfor
                        <span class="text-sm text-gray-600 ml-2">({{ $review->rating }})</span>
                    </div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600 max-w-xs">
                    @if($review->title)
                    <div class="font-medium text-black mb-1">{{ $review->title }}</div>
                    @endif
                    <div class="truncate">{{ Str::limit($review->comment, 60) }}</div>
                </td>
                <td class="px-6 py-4">
                    <span class="inline-block px-3 py-1 text-xs font-medium uppercase tracking-wider border {{ $review->is_approved ? 'bg-green-50 text-green-900 border-green-200' : 'bg-yellow-50 text-yellow-900 border-yellow-200' }}">
                        {{ $review->is_approved ? 'Aprobada' : 'Pendiente' }}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $review->created_at->format('d/m/Y') }}</td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-2">
                        <button onclick="viewReview({{ $review->id }}, '{{ $review->product->name }}', '{{ $review->user->name }}', {{ $review->rating }}, '{{ addslashes($review->title ?? '') }}', '{{ addslashes($review->comment) }}')" class="text-sm text-gray-600 hover:text-black border-b border-gray-600 hover:border-black transition-colors duration-200">
                            Ver
                        </button>
                        @if(!$review->is_approved)
                        <form action="{{ route('admin.reviews.approve', $review) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-sm text-green-600 hover:text-green-900 border-b border-green-600 hover:border-green-900 transition-colors duration-200">
                                Aprobar
                            </button>
                        </form>
                        @endif
                        <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" onsubmit="return confirmDelete('¿Eliminar esta reseña?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm text-red-600 hover:text-red-900 border-b border-red-600 hover:border-red-900 transition-colors duration-200">
                                Eliminar
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-6 py-16 text-center text-sm text-gray-400">
                    No hay reseñas registradas
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if($reviews->hasPages())
<div class="mt-8">
    {{ $reviews->links() }}
</div>
@endif

<!-- Modal Ver Reseña -->
<div id="viewReviewModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-2xl">
        <div class="p-8">
            <h2 class="text-2xl font-light text-black mb-6 tracking-tight">Detalles de la Reseña</h2>
            <div class="space-y-6">
                <div>
                    <label class="block text-xs text-gray-500 uppercase tracking-wider font-medium mb-2">Producto</label>
                    <input type="text" id="review_product" class="w-full px-4 py-3 border border-gray-200 bg-gray-50 text-sm" readonly>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 uppercase tracking-wider font-medium mb-2">Usuario</label>
                    <input type="text" id="review_user" class="w-full px-4 py-3 border border-gray-200 bg-gray-50 text-sm" readonly>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 uppercase tracking-wider font-medium mb-2">Calificación</label>
                    <div id="review_rating" class="text-2xl"></div>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 uppercase tracking-wider font-medium mb-2">Título</label>
                    <input type="text" id="review_title" class="w-full px-4 py-3 border border-gray-200 bg-gray-50 text-sm" readonly>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 uppercase tracking-wider font-medium mb-2">Comentario</label>
                    <textarea id="review_comment" rows="4" class="w-full px-4 py-3 border border-gray-200 bg-gray-50 text-sm" readonly></textarea>
                </div>
            </div>
            <div class="flex justify-end mt-8">
                <button type="button" onclick="toggleModal('viewReviewModal')" class="bg-white text-black hover:bg-gray-50 px-6 py-3 text-sm font-medium border border-black transition-colors duration-200">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function toggleModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.toggle('hidden');
    }
}

function viewReview(id, product, user, rating, title, comment) {
    document.getElementById('review_product').value = product;
    document.getElementById('review_user').value = user;
    document.getElementById('review_title').value = title || 'Sin título';
    document.getElementById('review_comment').value = comment;
    
    let starsHtml = '<div class="flex items-center gap-1 text-yellow-500">';
    for (let i = 1; i <= 5; i++) {
        if (i <= rating) {
            starsHtml += '<svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>';
        } else {
            starsHtml += '<svg class="w-5 h-5 fill-current text-gray-300" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>';
        }
    }
    starsHtml += `<span class="text-sm text-gray-600 ml-2">(${rating}/5)</span></div>`;
    document.getElementById('review_rating').innerHTML = starsHtml;
    
    toggleModal('viewReviewModal');
}

// Cerrar modal al hacer clic fuera
document.addEventListener('DOMContentLoaded', function() {
    const modals = document.querySelectorAll('[id$="Modal"]');
    modals.forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
    });
});
</script>
@endsection
