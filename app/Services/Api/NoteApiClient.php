<?php

namespace App\Services\Api;

use App\Models\Note;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class NoteApiClient
{
    public function __construct(
        protected ApiClient $client
    ) {}

    public function list(): Collection
    {
        $response = $this->client->auth()->get('/notes');

        if ($response->failed()) {
            throw new \RuntimeException('Impossible de récupérer les notes via l’API.');
        }

        return collect($response->json('data', []))
            ->map(fn (array $note) => $this->mapNote($note));
    }

    public function getById(int $noteId): Note
    {
        $response = $this->client->auth()->get("/notes/{$noteId}");

        if ($response->failed()) {
            throw new \RuntimeException('Impossible de récupérer la note via l’API.');
        }

        return $this->mapNote($response->json('data', []));
    }

    public function userOwnsNote(int $noteId): bool
    {
        $response = $this->client->auth()->get("/notes/{$noteId}");

        if ($response->failed()) {
            throw new \RuntimeException('Impossible de récupérer la note via l’API.');
        }

        return $response->json('data.user_id') === $this->client->auth()->user()->id;
    }

    public function create(string $text, int $tagId): object
    {
        $response = $this->client->auth()->post('/notes', [
            'text' => $text,
            'tag_id' => $tagId,
        ]);

        if ($response->created()) {
            return $this->mapNote($response->json('data', []));
        }

        if ($response->status() === 422) {
            throw ValidationException::withMessages($response->json('errors') ?? [
                'message' => $response->json('message') ?? 'Validation error',
            ]);
        }

        throw new \RuntimeException('Impossible de créer la note via l’API.');
    }

    public function delete(int $noteId): bool
    {
        $response = $this->client->auth()->delete("/notes/{$noteId}");

        return $response->ok();
    }

    private function mapNote(array $payload): object
    {
        $payload['tag'] = isset($payload['tag']) ? (object) $payload['tag'] : null;

        return (object) $payload;
    }
}

