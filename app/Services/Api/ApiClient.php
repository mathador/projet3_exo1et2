<?php

namespace App\Services\Api;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;

class ApiClient
{
    public function __construct(
        protected ApiTokenService $tokenService
    ) {}

    /**
     * Client authentifié (Bearer token stocké en session).
     */
    public function auth(): PendingRequest
    {
        return $this->withToken($this->tokenService->ensureToken());
    }

    /**
     * Client avec un token explicite (peut être null pour appels publics).
     */
    public function withToken(?string $token): PendingRequest
    {
        $client = Http::baseUrl($this->baseUrl())
            ->acceptJson();

        return $token ? $client->withToken($token) : $client;
    }

    private function baseUrl(): string
    {
        return rtrim(config('services.api.base_url'), '/');
    }
}

