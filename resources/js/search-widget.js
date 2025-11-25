/**
 * Widget de búsqueda en tiempo real
 * Implementa búsqueda instantánea de productos usando la API
 */

class SearchWidget {
    constructor(inputSelector, resultsSelector) {
        this.input = document.querySelector(inputSelector);
        this.resultsContainer = document.querySelector(resultsSelector);
        this.debounceTimer = null;
        this.minChars = 2;
        
        if (this.input) {
            this.init();
        }
    }

    init() {
        this.input.addEventListener('input', (e) => {
            this.handleInput(e.target.value);
        });

        // Cerrar resultados al hacer click fuera
        document.addEventListener('click', (e) => {
            if (!this.input.contains(e.target) && !this.resultsContainer.contains(e.target)) {
                this.hideResults();
            }
        });
    }

    handleInput(query) {
        // Limpiar el timer anterior
        clearTimeout(this.debounceTimer);

        if (query.length < this.minChars) {
            this.hideResults();
            return;
        }

        // Debounce: esperar 300ms después del último input
        this.debounceTimer = setTimeout(() => {
            this.search(query);
        }, 300);
    }

    async search(query) {
        try {
            this.showLoading();

            const response = await fetch(`/api/products/search?q=${encodeURIComponent(query)}`);
            const data = await response.json();

            if (data.success) {
                this.displayResults(data.data);
            }
        } catch (error) {
            console.error('Error al buscar:', error);
            this.showError();
        }
    }

    displayResults(products) {
        if (products.length === 0) {
            this.resultsContainer.innerHTML = `
                <div class="p-4 text-center text-gray-500 text-sm">
                    No se encontraron productos
                </div>
            `;
            this.showResults();
            return;
        }

        const html = products.map(product => `
            <a href="${product.url}" class="flex items-center p-3 hover:bg-gray-50 transition-colors border-b border-gray-100 last:border-0">
                <div class="w-12 h-12 flex-shrink-0 bg-gray-100 flex items-center justify-center">
                    ${product.image 
                        ? `<img src="${product.image}" alt="${product.name}" class="w-full h-full object-contain">`
                        : `<svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                           </svg>`
                    }
                </div>
                <div class="ml-3 flex-1">
                    <div class="text-sm font-medium text-gray-900">${product.name}</div>
                    <div class="text-xs text-gray-500">$${product.price.toFixed(2)}</div>
                </div>
            </a>
        `).join('');

        this.resultsContainer.innerHTML = html;
        this.showResults();
    }

    showLoading() {
        this.resultsContainer.innerHTML = `
            <div class="p-4 text-center">
                <div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-black"></div>
            </div>
        `;
        this.showResults();
    }

    showError() {
        this.resultsContainer.innerHTML = `
            <div class="p-4 text-center text-red-500 text-sm">
                Error al buscar productos
            </div>
        `;
        this.showResults();
    }

    showResults() {
        this.resultsContainer.classList.remove('hidden');
    }

    hideResults() {
        this.resultsContainer.classList.add('hidden');
    }
}

// Inicializar el widget cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    new SearchWidget('#search-input', '#search-results');
});

export default SearchWidget;

