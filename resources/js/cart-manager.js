/**
 * Gestor de Carrito de Compras
 * Maneja todas las operaciones del carrito usando la API
 */

class CartManager {
    constructor() {
        this.cartBadge = document.querySelector('#cart-badge');
        this.cartModal = document.querySelector('#cart-modal');
        this.init();
    }

    init() {
        // Cargar el carrito al inicio
        this.loadCart();

        // Escuchar eventos de agregar al carrito
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-add-to-cart]')) {
                e.preventDefault();
                const productId = e.target.dataset.productId;
                const quantity = e.target.dataset.quantity || 1;
                this.addToCart(productId, parseInt(quantity));
            }

            if (e.target.matches('[data-remove-from-cart]')) {
                e.preventDefault();
                const productId = e.target.dataset.productId;
                this.removeFromCart(productId);
            }

            if (e.target.matches('[data-clear-cart]')) {
                e.preventDefault();
                this.clearCart();
            }
        });
    }

    async loadCart() {
        try {
            const response = await fetch('/api/cart');
            const data = await response.json();

            if (data.success) {
                this.updateUI(data.data);
            }
        } catch (error) {
            console.error('Error al cargar el carrito:', error);
        }
    }

    async addToCart(productId, quantity = 1) {
        try {
            const response = await fetch('/api/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken(),
                },
                body: JSON.stringify({
                    product_id: parseInt(productId),
                    quantity: quantity,
                }),
            });

            const data = await response.json();

            if (data.success) {
                this.updateUI(data.data);
                this.showNotification('¡Producto agregado al carrito!', 'success');
            } else {
                this.showNotification(data.message || 'Error al agregar producto', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showNotification('Error al agregar producto', 'error');
        }
    }

    async removeFromCart(productId) {
        try {
            const response = await fetch(`/api/cart/${productId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': this.getCsrfToken(),
                },
            });

            const data = await response.json();

            if (data.success) {
                this.updateUI(data.data);
                this.showNotification('Producto eliminado', 'success');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showNotification('Error al eliminar producto', 'error');
        }
    }

    async updateQuantity(productId, quantity) {
        try {
            const response = await fetch(`/api/cart/${productId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken(),
                },
                body: JSON.stringify({ quantity: parseInt(quantity) }),
            });

            const data = await response.json();

            if (data.success) {
                this.updateUI(data.data);
            } else {
                this.showNotification(data.message || 'Error al actualizar', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    async clearCart() {
        if (!confirm('¿Estás seguro de vaciar el carrito?')) {
            return;
        }

        try {
            const response = await fetch('/api/cart', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': this.getCsrfToken(),
                },
            });

            const data = await response.json();

            if (data.success) {
                this.updateUI(data.data);
                this.showNotification('Carrito vaciado', 'success');
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    updateUI(cartData) {
        // Actualizar el badge del carrito
        if (this.cartBadge) {
            this.cartBadge.textContent = cartData.total_items || 0;
            
            if (cartData.total_items > 0) {
                this.cartBadge.classList.remove('hidden');
            } else {
                this.cartBadge.classList.add('hidden');
            }
        }

        // Disparar evento personalizado para que otros componentes puedan reaccionar
        document.dispatchEvent(new CustomEvent('cartUpdated', { 
            detail: cartData 
        }));
    }

    showNotification(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 shadow-lg transform transition-all duration-300 ${
            type === 'success' ? 'bg-green-600' : 'bg-red-600'
        }`;
        toast.textContent = message;
        
        document.body.appendChild(toast);

        // Animación de entrada
        setTimeout(() => {
            toast.style.transform = 'translateX(0)';
        }, 10);

        // Remover después de 3 segundos
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    }

    formatPrice(price) {
        return new Intl.NumberFormat('es-MX', {
            style: 'currency',
            currency: 'MXN',
        }).format(price);
    }
}

// Inicializar el gestor cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    window.cartManager = new CartManager();
});

export default CartManager;

