/**
 * Cliente API para IKIGAI
 * Funciones helper para interactuar con la API REST
 */

const API_BASE = '/api';

// Helper para hacer peticiones fetch
async function apiRequest(endpoint, options = {}) {
    const defaultOptions = {
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
    };

    // Obtener el token CSRF si existe
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (token) {
        defaultOptions.headers['X-CSRF-TOKEN'] = token;
    }

    const config = {
        ...defaultOptions,
        ...options,
        headers: {
            ...defaultOptions.headers,
            ...options.headers,
        },
    };

    try {
        const response = await fetch(`${API_BASE}${endpoint}`, config);
        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Error en la petición');
        }

        return data;
    } catch (error) {
        console.error('API Error:', error);
        throw error;
    }
}

// ==========================================
// API DE PRODUCTOS
// ==========================================

export const productsApi = {
    /**
     * Obtener lista de productos con filtros
     * @param {Object} filters - Filtros: search, category_id, min_price, max_price, in_stock, featured, sort_by, per_page
     */
    async getAll(filters = {}) {
        const params = new URLSearchParams(filters);
        return apiRequest(`/products?${params}`);
    },

    /**
     * Obtener un producto por ID
     */
    async getById(id) {
        return apiRequest(`/products/${id}`);
    },

    /**
     * Búsqueda rápida (autocompletado)
     * @param {string} query - Término de búsqueda
     */
    async quickSearch(query) {
        return apiRequest(`/products/search?q=${encodeURIComponent(query)}`);
    },

    /**
     * Verificar disponibilidad de stock
     * @param {number} id - ID del producto
     * @param {number} quantity - Cantidad solicitada
     */
    async checkStock(id, quantity) {
        return apiRequest(`/products/${id}/check-stock`, {
            method: 'POST',
            body: JSON.stringify({ quantity }),
        });
    },
};

// ==========================================
// API DE SERVICIOS
// ==========================================

export const servicesApi = {
    /**
     * Obtener lista de servicios
     * @param {Object} filters - Filtros: search, type, min_price, max_price
     */
    async getAll(filters = {}) {
        const params = new URLSearchParams(filters);
        return apiRequest(`/services?${params}`);
    },

    /**
     * Obtener un servicio por ID
     */
    async getById(id) {
        return apiRequest(`/services/${id}`);
    },
};

// ==========================================
// API DEL CARRITO
// ==========================================

export const cartApi = {
    /**
     * Obtener el carrito actual
     */
    async get() {
        return apiRequest('/cart');
    },

    /**
     * Agregar producto al carrito
     * @param {number} productId - ID del producto
     * @param {number} quantity - Cantidad
     */
    async add(productId, quantity = 1) {
        return apiRequest('/cart/add', {
            method: 'POST',
            body: JSON.stringify({ product_id: productId, quantity }),
        });
    },

    /**
     * Actualizar cantidad de un producto
     * @param {number} productId - ID del producto
     * @param {number} quantity - Nueva cantidad
     */
    async update(productId, quantity) {
        return apiRequest(`/cart/${productId}`, {
            method: 'PUT',
            body: JSON.stringify({ quantity }),
        });
    },

    /**
     * Eliminar producto del carrito
     * @param {number} productId - ID del producto
     */
    async remove(productId) {
        return apiRequest(`/cart/${productId}`, {
            method: 'DELETE',
        });
    },

    /**
     * Vaciar el carrito completo
     */
    async clear() {
        return apiRequest('/cart', {
            method: 'DELETE',
        });
    },
};

// ==========================================
// API DE CATEGORÍAS
// ==========================================

export const categoriesApi = {
    /**
     * Obtener lista de categorías
     */
    async getAll() {
        return apiRequest('/categories');
    },

    /**
     * Obtener una categoría con sus productos
     */
    async getById(id) {
        return apiRequest(`/categories/${id}`);
    },
};

// ==========================================
// UTILIDADES
// ==========================================

/**
 * Mostrar notificación toast
 */
export function showToast(message, type = 'success') {
    // Puedes personalizar esto según tu framework de notificaciones
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 p-4 rounded-lg text-white z-50 ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    }`;
    toast.textContent = message;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.transition = 'opacity 0.5s';
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 500);
    }, 3000);
}

/**
 * Formatear precio
 */
export function formatPrice(price) {
    return new Intl.NumberFormat('es-MX', {
        style: 'currency',
        currency: 'MXN',
    }).format(price);
}

// Exportar también como default
export default {
    products: productsApi,
    services: servicesApi,
    cart: cartApi,
    categories: categoriesApi,
    showToast,
    formatPrice,
};

