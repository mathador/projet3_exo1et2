<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Notes\NoteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotesController extends Controller
{
    public function __construct(
        protected NoteService $noteService
    ) {}

    /**
     * Liste toutes les notes de l'utilisateur authentifié.
     *
     * @OA\Get(
     *     path="/api/notes",
     *     summary="Liste des notes",
     *     tags={"Notes"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des notes",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié"
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $notes = $this->noteService->getUserNotes();

        return response()->json([
            'data' => $notes,
        ]);
    }

    /**
     * Crée une nouvelle note.
     *
     * @OA\Post(
     *     path="/api/notes",
     *     summary="Créer une note",
     *     tags={"Notes"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"text","tag_id"},
     *             @OA\Property(property="text", type="string", example="Ma nouvelle note"),
     *             @OA\Property(property="tag_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Note créée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erreur de validation"
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'text' => ['required', 'string'],
            'tag_id' => ['required', 'exists:tags,id'],
        ]);

        $note = $this->noteService->createNote(
            $validated['text'],
            $validated['tag_id']
        );

        return response()->json([
            'data' => $note->load('tag'),
        ], 201);
    }

    /**
     * Affiche une note spécifique.
     *
     * @OA\Get(
     *     path="/api/notes/{id}",
     *     summary="Afficher une note",
     *     tags={"Notes"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Note trouvée",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Note non trouvée"
     *     )
     * )
     */
    public function show(string $id): JsonResponse
    {
        $notes = $this->noteService->getUserNotes();
        $note = $notes->firstWhere('id', $id);

        if (!$note) {
            return response()->json([
                'message' => 'Note non trouvée',
            ], 404);
        }

        return response()->json([
            'data' => $note->load('tag'),
        ]);
    }

    /**
     * Met à jour une note.
     *
     * @OA\Put(
     *     path="/api/notes/{id}",
     *     summary="Mettre à jour une note",
     *     tags={"Notes"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="text", type="string", example="Note mise à jour"),
     *             @OA\Property(property="tag_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Note mise à jour",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Note non trouvée"
     *     )
     * )
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $validated = $request->validate([
            'text' => ['sometimes', 'required', 'string'],
            'tag_id' => ['sometimes', 'required', 'exists:tags,id'],
        ]);

        if (!$this->noteService->userOwnsNote((int) $id)) {
            return response()->json([
                'message' => 'Note non trouvée',
            ], 404);
        }

        $note = \App\Models\Note::findOrFail($id);
        $note->update($validated);

        return response()->json([
            'data' => $note->load('tag'),
        ]);
    }

    /**
     * Supprime une note.
     *
     * @OA\Delete(
     *     path="/api/notes/{id}",
     *     summary="Supprimer une note",
     *     tags={"Notes"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Note supprimée",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Note supprimée avec succès")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Note non trouvée"
     *     )
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        if (!$this->noteService->deleteNote((int) $id)) {
            return response()->json([
                'message' => 'Note non trouvée',
            ], 404);
        }

        return response()->json([
            'message' => 'Note supprimée avec succès',
        ]);
    }
}

