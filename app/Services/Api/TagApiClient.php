<?php

namespace App\Services\Api;

use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class TagApiClient
{
    public function __construct(
        protected ApiClient $client
    ) {}

    public function list(): Collection
    {
        $response = $this->client->auth()->get('/tags');

        if ($response->failed()) {
            throw new \RuntimeException('Impossible de récupérer les tags via l’API.');
        }

        return collect($response->json('data', []))
            ->map(fn (array $tag) => (object) $tag);
    }

    public function create(string $name): object
    {
        $response = $this->client->auth()->post('/tags', [
            'name' => $name,
        ]);

        if ($response->created()) {
            return (object) $response->json('data', []);
        }

        if ($response->status() === 422) {
            throw ValidationException::withMessages($response->json('errors') ?? [
                'name' => $response->json('message') ?? 'Validation error',
            ]);
        }

        throw new \RuntimeException('Impossible de créer le tag via l’API.');
    }
}

