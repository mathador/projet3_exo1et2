<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Notes\NoteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Note;

class NotesController extends Controller
{
    public function __construct(
        protected NoteService $noteService
    ) {}

    public function index(): JsonResponse
    {
        $notes = $this->noteService->getUserNotes();

        return response()->json([
            'data' => $notes,
        ]);
    }

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

    public function show(string $id): JsonResponse
    {
        $note = $this->noteService->getNoteById((int) $id);

        if (!$note) {
            return response()->json([
                'message' => 'Note non trouvée',
            ], 404);
        }

        return response()->json([
            'data' => $note->load('tag'),
        ]);
    }

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

        $this->noteService->updateNote((int) $id, $validated);
        $note = $this->noteService->getNoteById((int) $id);


        return response()->json([
            'data' => $note->load('tag'),
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        if ($this->noteService->deleteNote((int) $id)) {
            return response()->json([
                'message' => 'Note supprimée avec succès',
            ]);
        }
        
        return response()->json([
            'message' => 'Note non trouvée',
        ], 404);
    }
}