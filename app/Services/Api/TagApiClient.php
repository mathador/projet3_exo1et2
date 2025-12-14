<?php

namespace App\Services\Api;

use App\Models\Tag;
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
            ->map(function (array $tag) {
                $instance = new Tag();
                $instance->forceFill($tag);

                return $instance;
            });
    }

    public function getById(int $tagId): Tag
    {
        $response = $this->client->auth()->get("/tags/{$tagId}");

        if ($response->failed()) {
            throw new \RuntimeException('Impossible de récupérer le tag via l’API.');
        }

        $tag = new Tag();
        $tag->forceFill($response->json('data', []));

        return $tag;
    }

    public function create(string $name): Tag
    {
        $response = $this->client->auth()->post('/tags', [
            'name' => $name,
        ]);

        if ($response->created()) {
            $tag = new Tag();
            $tag->forceFill($response->json('data', []));

            return $tag;
        }

        if ($response->status() === 422) {
            throw ValidationException::withMessages($response->json('errors') ?? [
                'name' => $response->json('message') ?? 'Validation error',
            ]);
        }

        throw new \RuntimeException('Impossible de créer le tag via l’API.');
    }

    public function delete(int $tagId): void
    {
        $response = $this->client->auth()->delete("/tags/{$tagId}");

        if ($response->failed()) {
            if ($response->status() === 409) {
                throw new \RuntimeException($response->json('message'));
            }
            throw new \RuntimeException('Impossible de supprimer le tag via l’API.');
        }
    }
}

