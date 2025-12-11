<?php

namespace App\Services\Api;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Laravel\Sanctum\NewAccessToken;

class ApiTokenService
{
    private const SESSION_TOKEN = 'api_bridge.token';
    private const SESSION_USER_ID = 'api_bridge.user_id';

    /**
     * Retourne un token d'accès API pour l'utilisateur courant, en le créant si besoin.
     */
    public function ensureToken(): string
    {
        $user = Auth::user();
        if (!$user) {
            throw new \RuntimeException('Utilisateur non authentifié pour générer un token API.');
        }

        $existing = Session::get(self::SESSION_TOKEN);
        $existingUserId = Session::get(self::SESSION_USER_ID);
        if ($existing && $existingUserId === $user->id) {
            return $existing;
        }

        $token = $this->createToken($user);

        Session::put(self::SESSION_TOKEN, $token->plainTextToken);
        Session::put(self::SESSION_USER_ID, $user->id);

        return $token->plainTextToken;
    }

    /**
     * Retourne le token courant s'il existe en session (sans en créer).
     */
    public function getCurrentToken(): ?string
    {
        return Session::get(self::SESSION_TOKEN);
    }

    /**
     * Oublie le token en session (le token est déjà révoqué côté API via /auth/logout).
     */
    public function forgetToken(): void
    {
        Session::forget(self::SESSION_TOKEN);
        Session::forget(self::SESSION_USER_ID);
    }

    private function createToken($user): NewAccessToken
    {
        return $user->createToken('web-bridge');
    }
}

