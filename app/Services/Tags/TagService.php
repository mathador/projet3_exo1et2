<?php

namespace App\Services\Tags;

use App\Models\Tag;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class TagService
{
    /**
     * Récupère tous les tags.
     *
     * @return Collection
     */
    public function getAllTags(): Collection
    {
        return Tag::all();
    }

    /**
     * Crée un nouveau tag.
     *
     * @param string $name
     * @return Tag
     * @throws ValidationException
     */
    public function createTag(string $name): Tag
    {
        // Vérification de l'unicité
        if (Tag::where('name', $name)->exists()) {
            throw ValidationException::withMessages([
                'name' => 'Ce tag existe déjà.',
            ]);
        }

        return Tag::create(['name' => $name]);
    }

    /**
     * Vérifie si un tag existe par son ID.
     *
     * @param int $tagId
     * @return bool
     */
    public function tagExists(int $tagId): bool
    {
        return Tag::where('id', $tagId)->exists();
    }

    /**
     * Récupère un tag par son ID.
     *
     * @param int $tagId
     * @return Tag|null
     */
    public function getTagById(int $tagId): ?Tag
    {
        return Tag::find($tagId);
    }
}

