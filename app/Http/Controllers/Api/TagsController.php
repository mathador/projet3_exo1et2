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

    public function index(): JsonResponse
    {
        $tags = $this->tagService->getAllTags();

        return response()->json([
            'data' => $tags,
        ]);
    }

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

    public function update(Request $request, string $id): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:tags,name,' . $id],
        ]);

        $tag = $this->tagService->getTagById((int) $id);

        if (!$tag) {
            return response()->json([
                'message' => 'Tag non trouvé',
            ], 404);
        }

        $tag->update($validated);

        return response()->json([
            'data' => $tag,
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $tag = $this->tagService->getTagById((int) $id);

        if (!$tag) {
            return response()->json([
                'message' => 'Tag non trouvé',
            ], 404);
        }

        $tag->delete();

        return response()->json([
            'message' => 'Tag supprimé avec succès',
        ]);
    }
}