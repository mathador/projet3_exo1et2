<?php

namespace App\Services\Api;

use App\Models\Note;
use App\Services\Notes\NoteService;
use Illuminate\Support\Collection;

class NoteApiClient
{
    public function __construct(
        protected NoteService $noteService
    ) {}

    public function list(): Collection
    {
        return $this->noteService->getUserNotes();
    }

    public function getById(int $noteId): ?Note
    {
        return $this->noteService->getNoteById($noteId);
    }

    public function userOwnsNote(int $noteId): bool
    {
        return $this->noteService->userOwnsNote($noteId);
    }

    public function create(string $text, int $tagId): Note
    {
        return $this->noteService->createNote($text, $tagId);
    }

    public function delete(int $noteId): bool
    {
        return $this->noteService->deleteNote($noteId);
    }
}