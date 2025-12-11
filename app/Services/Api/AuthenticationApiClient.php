<?php

namespace App\Services\Api;

use App\Models\User;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Validation\ValidationException;

class AuthenticationApiClient
{
    public function __construct(
        protected PendingRequest $client
    ) {}

    public function login(User $user, string $password): bool
    {
        $response = $this->client->post('/auth/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        if ($response->ok()) {
            $token = $response->json('data.token');

            // Stocker le token pour les requêtes futures
            $this->client->withToken($token);

            return true;
        }

        if ($response->status() === 422) {
            throw ValidationException::withMessages($response->json('errors') ?? [
                'message' => $response->json('message') ?? 'Validation error',
            ]);
        }

        return false;
    }

    public function logout(): bool
    {
        $response = $this->client->post('/auth/logout');

        if ($response->ok()) {
            // Supprimer le token stocké
            $this->client->withToken($response['token'] ?? null);

            return true;
        }

        return false;
    }

    public function register(string $name, string $email, string $password, string $passwordConfirmation): object
    {
        $response = $this->client->post('/auth/register', [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $passwordConfirmation,
        ]);

        if ($response->status() === 201) {
            $data = $response->json('data', []);
            $this->client->withToken($data['token'] ?? null);

            return (object) $data;
        }

        if ($response->status() === 422) {
            throw ValidationException::withMessages($response->json('errors') ?? [
                'message' => $response->json('message') ?? 'Validation error',
            ]);
        }

        throw new \RuntimeException('Impossible de créer l’utilisateur via l’API.');
    }
}

