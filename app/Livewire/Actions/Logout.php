<?php

namespace App\Livewire\Actions;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Services\Api\ApiClient;
use App\Services\Api\ApiTokenService;

class Logout
{
    /**
     * Log the current user out of the application.
     */
    public function __invoke(ApiClient $apiClient, ApiTokenService $tokenService)
    {
        // Déconnexion côté API (supprime le token Sanctum utilisé par le bridge)
        $token = $tokenService->getCurrentToken();
        if ($token) {
            $apiClient->withToken($token)->post('/auth/logout');
        }

        Auth::guard('web')->logout();

        Session::invalidate();
        Session::regenerateToken();

        $tokenService->forgetToken();

        return redirect('/');
    }
}
