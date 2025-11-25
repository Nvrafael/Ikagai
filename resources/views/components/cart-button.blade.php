{{-- Botón de agregar al carrito con API --}}
@props(['productId', 'quantity' => 1, 'text' => 'Agregar al carrito', 'class' => ''])

<button 
    type="button"
    data-add-to-cart
    data-product-id="{{ $productId }}"
    data-quantity="{{ $quantity }}"
    {{ $attributes->merge(['class' => 'inline-flex items-center justify-center bg-black text-white hover:bg-gray-900 px-6 py-3 text-sm font-medium transition-colors duration-200 ' . $class]) }}
>
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
    </svg>
    {{ $text }}
</button>

@once
@push('scripts')
<script>
    // Mini gestor de carrito
    (function() {
        let isProcessing = false;

        document.addEventListener('click', async function(e) {
            const btn = e.target.closest('[data-add-to-cart]');
            if (!btn || isProcessing) return;

            e.preventDefault();
            isProcessing = true;

            const productId = btn.dataset.productId;
            const quantity = parseInt(btn.dataset.quantity) || 1;
            const originalText = btn.innerHTML;

            try {
                // Cambiar el texto del botón
                btn.disabled = true;
                btn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

                const response = await fetch('/api/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({
                        product_id: parseInt(productId),
                        quantity: quantity
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Actualizar badge del carrito
                    const badge = document.querySelector('#cart-badge');
                    if (badge && data.data.total_items) {
                        badge.textContent = data.data.total_items;
                        badge.classList.remove('hidden');
                    }

                    // Mostrar notificación
                    showToast('¡Producto agregado al carrito!', 'success');
                    
                    // Cambiar temporalmente el texto
                    btn.innerHTML = '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>';
                    
                    setTimeout(() => {
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    }, 2000);
                } else {
                    throw new Error(data.message || 'Error al agregar producto');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast(error.message || 'Error al agregar producto', 'error');
                btn.innerHTML = originalText;
                btn.disabled = false;
            } finally {
                isProcessing = false;
            }
        });

        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 shadow-lg animate-fade-in ${
                type === 'success' ? 'bg-green-600' : 'bg-red-600'
            }`;
            toast.textContent = message;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.style.transition = 'opacity 0.3s';
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
    })();
</script>
@endpush
@endonce

