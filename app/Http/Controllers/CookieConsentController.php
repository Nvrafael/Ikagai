<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CookieConsentController extends Controller
{
    /**
     * Actualizar el consentimiento de cookies
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'consent' => 'required|in:accepted,rejected',
        ]);

        if (auth()->check()) {
            // Usuario autenticado: guardar en la base de datos
            auth()->user()->update([
                'cookie_consent' => $validated['consent'],
                'cookie_consent_date' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Preferencia de cookies guardada',
        ]);
    }

    /**
     * Obtener el estado actual del consentimiento
     */
    public function status()
    {
        if (auth()->check()) {
            // Usuario autenticado: leer de la base de datos
            return response()->json([
                'hasConsent' => auth()->user()->cookie_consent !== null,
                'consent' => auth()->user()->cookie_consent,
                'date' => auth()->user()->cookie_consent_date,
            ]);
        }

        // Usuario no autenticado: no tiene consentimiento en BD
        return response()->json([
            'hasConsent' => false,
            'consent' => null,
            'date' => null,
        ]);
    }
}
