<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Tags\TagService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TagsController extends Controller
{
    public function __construct(
        protected TagService $tagService
    ) {}

    /**
     * Liste tous les tags.
     *
     * @OA\Get(
     *     path="/api/tags",
     *     summary="Liste des tags",
     *     tags={"Tags"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des tags",
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
        $tags = $this->tagService->getAllTags();

        return response()->json([
            'data' => $tags,
        ]);
    }

    /**
     * Crée un nouveau tag.
     *
     * @OA\Post(
     *     path="/api/tags",
     *     summary="Créer un tag",
     *     tags={"Tags"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Important")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tag créé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié"
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
            'name' => ['required', 'string', 'max:50'],
        ]);

        try {
            $tag = $this->tagService->createTag($validated['name']);

            return response()->json([
                'data' => $tag,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Ce tag existe déjà',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Affiche un tag spécifique.
     *
     * @OA\Get(
     *     path="/api/tags/{id}",
     *     summary="Afficher un tag",
     *     tags={"Tags"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tag trouvé",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tag non trouvé"
     *     )
     * )
     */
    public function show(string $id): JsonResponse
    {
        $tag = $this->tagService->getTagById((int) $id);

        if (!$tag) {
            return response()->json([
                'message' => 'Tag non trouvé',
            ], 404);
        }

        return response()->json([
            'data' => $tag,
        ]);
    }

    /**
     * Met à jour un tag.
     *
     * @OA\Put(
     *     path="/api/tags/{id}",
     *     summary="Mettre à jour un tag",
     *     tags={"Tags"},
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
     *             @OA\Property(property="name", type="string", example="Tag mis à jour")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tag mis à jour",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tag non trouvé"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erreur de validation"
     *     )
     * )
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:tags,name,' . $id],
        ]);

        $tag = $this->tagService->updateTag((int) $id, $validated);

        if (!$tag) {
            return response()->json([
                'message' => 'Tag non trouvé',
            ], 404);
        }

        return response()->json([
            'data' => $tag,
        ]);
    }

    /**
     * Supprime un tag.
     *
     * @OA\Delete(
     *     path="/api/tags/{id}",
     *     summary="Supprimer un tag",
     *     tags={"Tags"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tag supprimé",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Tag supprimé avec succès")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tag non trouvé"
     *     )
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        if ($this->tagService->deleteTag((int) $id)) {
            return response()->json([
                'message' => 'Tag supprimé avec succès',
            ]);
        }

        return response()->json([
            'message' => 'Tag non trouvé',
        ], 404);
    }
}
