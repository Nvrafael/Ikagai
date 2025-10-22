@extends('admin.layout')

@section('title', 'Gestión de Reseñas')

@section('content')
<div class="page-header">
    <h1 class="page-title">Reseñas</h1>
</div>

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Producto</th>
                    <th>Usuario</th>
                    <th>Calificación</th>
                    <th>Comentario</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                <tr>
                    <td>{{ $review->id }}</td>
                    <td>{{ $review->product->name }}</td>
                    <td>
                        {{ $review->user->name }}
                        @if($review->is_verified_purchase)
                        <span class="badge badge-success" style="font-size: 10px;">✓ Compra verificada</span>
                        @endif
                    </td>
                    <td>
                        <div style="color: #f59e0b;">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $review->rating)
                                    ★
                                @else
                                    ☆
                                @endif
                            @endfor
                            <span style="color: #4a5568;">({{ $review->rating }})</span>
                        </div>
                    </td>
                    <td>
                        @if($review->title)
                        <strong>{{ $review->title }}</strong><br>
                        @endif
                        {{ Str::limit($review->comment, 50) }}
                    </td>
                    <td>
                        <span class="badge {{ $review->is_approved ? 'badge-success' : 'badge-warning' }}">
                            {{ $review->is_approved ? 'Aprobada' : 'Pendiente' }}
                        </span>
                    </td>
                    <td>{{ $review->created_at->format('d/m/Y') }}</td>
                    <td>
                        <div class="action-buttons">
                            <button onclick="viewReview({{ $review->id }}, '{{ $review->product->name }}', '{{ $review->user->name }}', {{ $review->rating }}, '{{ addslashes($review->title ?? '') }}', '{{ addslashes($review->comment) }}')" class="btn btn-secondary btn-small">Ver</button>
                            @if(!$review->is_approved)
                            <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-small">Aprobar</button>
                            </form>
                            @endif
                            <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" style="display: inline;" onsubmit="return confirmDelete('¿Eliminar esta reseña?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-small">Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 40px; color: #718096;">
                        No hay reseñas registradas.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($reviews->hasPages())
    <div style="padding: 20px;">
        {{ $reviews->links() }}
    </div>
    @endif
</div>

<!-- Modal Ver Reseña -->
<div id="viewReviewModal" class="modal">
    <div class="modal-content">
        <h2 class="modal-header">Detalles de la Reseña</h2>
        <div>
            <div class="form-group">
                <label class="form-label">Producto</label>
                <input type="text" id="review_product" class="form-input" readonly style="background: #f7fafc;">
            </div>
            <div class="form-group">
                <label class="form-label">Usuario</label>
                <input type="text" id="review_user" class="form-input" readonly style="background: #f7fafc;">
            </div>
            <div class="form-group">
                <label class="form-label">Calificación</label>
                <div id="review_rating" style="color: #f59e0b; font-size: 24px;"></div>
            </div>
            <div class="form-group">
                <label class="form-label">Título</label>
                <input type="text" id="review_title" class="form-input" readonly style="background: #f7fafc;">
            </div>
            <div class="form-group">
                <label class="form-label">Comentario</label>
                <textarea id="review_comment" class="form-textarea" readonly style="background: #f7fafc;"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" onclick="toggleModal('viewReviewModal')" class="btn btn-secondary">Cerrar</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function viewReview(id, product, user, rating, title, comment) {
    document.getElementById('review_product').value = product;
    document.getElementById('review_user').value = user;
    document.getElementById('review_title').value = title || 'Sin título';
    document.getElementById('review_comment').value = comment;
    
    let starsHtml = '';
    for (let i = 1; i <= 5; i++) {
        starsHtml += i <= rating ? '★' : '☆';
    }
    starsHtml += ` (${rating}/5)`;
    document.getElementById('review_rating').innerHTML = starsHtml;
    
    toggleModal('viewReviewModal');
}
</script>
@endsection

