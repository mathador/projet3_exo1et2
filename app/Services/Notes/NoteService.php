<?php

namespace App\Services\Notes;

use App\Models\Note;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class NoteService
{
    /**
     * Récupère toutes les notes de l'utilisateur authentifié avec leurs tags.
     *
     * @return Collection
     */
    public function getUserNotes(): Collection
    {
        return Note::with('tag')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
    }

    /**
     * Crée une nouvelle note pour l'utilisateur authentifié.
     *
     * @param string $text
     * @param int $tagId
     * @return Note
     */
    public function createNote(string $text, int $tagId): Note
    {
        return Note::create([
            'user_id' => Auth::id(),
            'tag_id' => $tagId,
            'text' => $text,
        ]);
    }

    /**
     * Supprime une note si elle appartient à l'utilisateur authentifié.
     *
     * @param int $noteId
     * @return bool
     */
    public function deleteNote(int $noteId): bool
    {
        return Note::where('id', $noteId)
            ->where('user_id', Auth::id())
            ->delete() > 0;
    }

    /**
     * Vérifie si une note appartient à l'utilisateur authentifié.
     *
     * @param int $noteId
     * @return bool
     */
    public function userOwnsNote(int $noteId): bool
    {
        return Note::where('id', $noteId)
            ->where('user_id', Auth::id())
            ->exists();
    }
}

