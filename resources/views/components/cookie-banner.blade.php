<!-- Cookie Banner -->
<div id="cookieBanner" class="fixed bottom-0 left-0 right-0 z-50 transform translate-y-full transition-transform duration-300 ease-out" style="display: none;">
    <div class="bg-white border-t border-gray-200 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex-1 text-center md:text-left">
                    <p class="text-sm text-gray-700">
                        Utilizamos cookies para mejorar tu experiencia en nuestro sitio web. Al continuar navegando, aceptas nuestra 
                        <a href="{{ route('cookies.policy') }}" class="text-sage-dark hover:text-sage underline">política de cookies</a>.
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <button onclick="rejectCookies()" class="px-6 py-2 text-sm text-gray-600 hover:text-black border border-gray-200 hover:border-black transition-colors duration-200">
                        Rechazar
                    </button>
                    <button onclick="acceptCookies()" class="px-6 py-2 text-sm bg-sage text-white hover:bg-sage-dark transition-colors duration-200">
                        Aceptar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Verificar si el usuario ya respondió sobre las cookies
document.addEventListener('DOMContentLoaded', async function() {
    @auth
        // Usuario autenticado: verificar en el servidor
        try {
            const response = await fetch('/cookies/status');
            const data = await response.json();
            
            if (!data.hasConsent) {
                showCookieBanner();
            }
        } catch (error) {
            console.error('Error al verificar cookies:', error);
        }
    @else
        // Usuario no autenticado: usar localStorage
        const cookieConsent = localStorage.getItem('cookieConsent');
        
        if (!cookieConsent) {
            showCookieBanner();
        }
    @endauth
});

function showCookieBanner() {
    setTimeout(function() {
        const banner = document.getElementById('cookieBanner');
        banner.style.display = 'block';
        banner.offsetHeight; // Trigger reflow
        banner.style.transform = 'translateY(0)';
    }, 1000);
}

async function acceptCookies() {
    await saveCookieConsent('accepted');
}

async function rejectCookies() {
    await saveCookieConsent('rejected');
}

async function saveCookieConsent(consent) {
    @auth
        // Usuario autenticado: guardar en el servidor
        try {
            const response = await fetch('/cookies/consent', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({ consent: consent })
            });

            const data = await response.json();
            
            if (data.success) {
                hideCookieBanner();
            }
        } catch (error) {
            console.error('Error al guardar cookies:', error);
            hideCookieBanner(); // Ocultar de todos modos
        }
    @else
        // Usuario no autenticado: usar localStorage
        localStorage.setItem('cookieConsent', consent);
        localStorage.setItem('cookieConsentDate', new Date().toISOString());
        hideCookieBanner();
    @endauth
}

function hideCookieBanner() {
    const banner = document.getElementById('cookieBanner');
    banner.style.transform = 'translateY(100%)';
    
    setTimeout(function() {
        banner.style.display = 'none';
    }, 300);
}
</script>

