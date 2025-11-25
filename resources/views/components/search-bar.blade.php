{{-- Barra de búsqueda con API en tiempo real --}}
<div class="relative" x-data="{ open: false }">
    <!-- Input de búsqueda -->
    <div class="relative">
        <input 
            type="text" 
            id="search-input"
            placeholder="Buscar productos..." 
            class="w-full px-4 py-3 pl-10 pr-4 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent"
            @focus="open = true"
        >
        <svg class="absolute left-3 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
    </div>

    <!-- Resultados de búsqueda -->
    <div 
        id="search-results" 
        class="hidden absolute top-full left-0 right-0 mt-2 bg-white border border-gray-200 rounded-lg shadow-lg max-h-96 overflow-y-auto z-50"
    >
        <!-- Los resultados se cargarán aquí dinámicamente -->
    </div>
</div>

@push('scripts')
<script>
    // Widget de búsqueda simple sin dependencias
    (function() {
        const input = document.getElementById('search-input');
        const results = document.getElementById('search-results');
        let debounceTimer;

        if (!input) return;

        input.addEventListener('input', function(e) {
            clearTimeout(debounceTimer);
            const query = e.target.value.trim();

            if (query.length < 2) {
                results.classList.add('hidden');
                return;
            }

            debounceTimer = setTimeout(() => searchProducts(query), 300);
        });

        async function searchProducts(query) {
            try {
                results.innerHTML = '<div class="p-4 text-center"><div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-black"></div></div>';
                results.classList.remove('hidden');

                const response = await fetch(`/api/products/search?q=${encodeURIComponent(query)}`);
                const data = await response.json();

                if (data.success && data.data.length > 0) {
                    displayResults(data.data);
                } else {
                    results.innerHTML = '<div class="p-4 text-center text-gray-500 text-sm">No se encontraron productos</div>';
                }
            } catch (error) {
                console.error('Error:', error);
                results.innerHTML = '<div class="p-4 text-center text-red-500 text-sm">Error al buscar</div>';
            }
        }

        function displayResults(products) {
            const html = products.map(product => `
                <a href="${product.url}" class="flex items-center p-3 hover:bg-gray-50 transition-colors border-b border-gray-100 last:border-0">
                    <div class="w-12 h-12 flex-shrink-0 bg-gray-100 flex items-center justify-center">
                        ${product.image 
                            ? `<img src="${product.image}" alt="${product.name}" class="w-full h-full object-contain">`
                            : `<svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>`
                        }
                    </div>
                    <div class="ml-3 flex-1">
                        <div class="text-sm font-medium text-gray-900">${product.name}</div>
                        <div class="text-xs text-gray-500">$${product.price.toFixed(2)}</div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            `).join('');
            
            results.innerHTML = html;
        }

        // Cerrar al hacer click fuera
        document.addEventListener('click', function(e) {
            if (!input.contains(e.target) && !results.contains(e.target)) {
                results.classList.add('hidden');
            }
        });
    })();
</script>
@endpush

