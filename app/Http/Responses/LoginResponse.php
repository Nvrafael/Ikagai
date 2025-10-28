<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $user = Auth::user();

        // Redirigir según el rol del usuario
        return match ($user->role) {
            'admin' => redirect()->intended(route('admin.dashboard')),
            'nutritionist' => redirect()->intended(route('nutritionist.dashboard')),
            'client' => redirect()->intended(route('client.dashboard')),
            default => redirect()->intended(route('dashboard'))
        };
    }
}

